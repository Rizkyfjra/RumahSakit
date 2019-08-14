<?php

/**
 * This is the model class for table "users".
 *
 * The followings are the available columns in table 'users':
 * @property integer $id
 * @property string $email
 * @property string $username
 * @property string $encrypted_password
 * @property integer $role_id
 */
class User extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    /* public function tableName()
      {
      return 'users';
      } */

    protected $tbl_prefix = null;

    public function tableName() {
        if ($this->tbl_prefix === null) {
            // Fetch prefix
            $this->tbl_prefix = Yii::app()->params['tablePrefix'];
        }

        // Prepend prefix, call our new method
        return ($this->tbl_prefix . $this->_tableName());
        //return 'absensi';
    }

    protected function _tableName() {
        // Call the original method for our table name stuff
        return 'users';
    }

    public $password2;
    public $new_password;
    public $new_password2;
    public $nilai;
    public $old_password;

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        $message = '{attribute} tidak boleh kosong.';

        return array(
            array('email, username, encrypted_password, role_id, display_name', 'required', 'message' => $message),
            //array('email, username, encrypted_password, role_id, class_id, display_name', 'required', 'message'=>$message, 'on'=>'sc_student'),
            array('email, username, encrypted_password, role_id, display_name', 'required', 'message' => $message, 'on' => 'sc_student'),
            array('id_absen_solution,role_id, sync_status, trash', 'numerical', 'integerOnly' => true),
            array('email, username, encrypted_password,new_password, old_password, image', 'length', 'max' => 255),
            array('password2', 'compare', 'compareAttribute' => 'encrypted_password', 'message' => 'Password tidak sama', 'except' => 'update'),
            array('new_password2', 'compare', 'compareAttribute' => 'new_password', 'message' => 'Password tidak sama2', 'on' => 'update'),
            array('image', 'file', 'types' => 'jpg, gif, png', 'allowEmpty' => true, 'maxSize' => 1024 * 1024 * 50, 'on' => 'insert,update'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, email, username, encrypted_password, role_id, sync_status, image, trash', 'safe', 'on' => 'search'),
            array('username', 'unique'),
            array('email', 'unique'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'user' => array(self::HAS_MANY, 'Notification', 'user_id'),
            'class' => array(self::BELONGS_TO, 'ClassDetail', 'class_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'email' => 'Email',
            'username' => 'NIK/NIP',
            'encrypted_password' => 'Password',
            'role_id' => 'Role',
            'display_name' => 'Full Name',
            'class_id' => 'Kelas',
            'id_absen_solution' => 'ID Absensi'
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('username', $this->username, true);
        $criteria->compare('encrypted_password', $this->encrypted_password, true);
        $criteria->compare('role_id', $this->role_id);
        $criteria->compare('class_id', $this->class_id);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return User the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function beforeSave() {

        if ($this->isNewRecord) {
            if (!empty($this->class_id)) {
                $count_cid = explode(":", $this->class_id);
                if (count($count_cid) >= 2) {
                    list($a, $b) = explode(":", $this->class_id);
                    $class_id = str_replace(")", "", $b);
                    $this->class_id = $class_id;
                }
            }
            $this->created_at = new CDbExpression('NOW()');
            $this->updated_at = new CDbExpression('NOW()');
        } else {
            $this->updated_at = new CDbExpression('NOW()');
            if (!empty($this->new_password2)) {
                $this->encrypted_password = $this->new_password;
                $this->password2 = $this->new_password2;
            }
        }

        if (!empty($this->encrypted_password) && $this->encrypted_password == $this->password2) {
            $ph = new PasswordHash(Yii::app()->params['phpass']['iteration_count_log2'], Yii::app()->params['phpass']['portable_hashes']);
            $this->encrypted_password = $ph->HashPassword($this->encrypted_password);
        }
        return parent::beforeSave();
    }

    public function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

}
