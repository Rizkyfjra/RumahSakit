<?php

/**
 * This is the model class for table "lesson".
 *
 * The followings are the available columns in table 'lesson':
 * @property integer $id
 * @property string $name
 * @property integer $user_id
 * @property integer $class_id
 */
class Lesson extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public $class_name;
	public $jml;
	public $big;
	
	/*public function tableName()
	{
		return 'lesson';
	}*/

	protected $tbl_prefix = null;
	
	public function tableName()
	{
		if ($this->tbl_prefix === null)
        {
            // Fetch prefix
            $this->tbl_prefix = Yii::app()->params['tablePrefix'];
        }
 
        // Prepend prefix, call our new method
        return ($this->tbl_prefix . $this->_tableName());
		//return 'absensi';
	}

	protected function _tableName()
    {
        // Call the original method for our table name stuff
        return 'lesson';
    }

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, user_id, class_id', 'required'),
			array('sync_status, moving_class, big, list_id', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, user_id, class_id, list_id', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'class'=>array(self::BELONGS_TO, 'ClassDetail', 'class_id'),
			'grade'=>array(self::BELONGS_TO, 'Clases', 'class_id'),
			'users'=>array(self::BELONGS_TO, 'User', 'user_id'),
			'detail'=>array(self::BELONGS_TO, 'ClassDetail', 'list_id'),
			'kd'=>array(self::HAS_MANY, 'LessonKd', 'lesson_id')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Nama Pelajaran',
			'user_id' => 'Guru',
			'class_id' => 'Kelas',
			'moving_class' => 'Lintas Minat'
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
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('class_id',$this->class_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Lesson the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function beforeSave()
	{
	
		if (!empty($this->user_id) && Yii::app()->user->YiiAdmin) {
			list($a, $b) = explode(":", $this->user_id);
			$user_id = str_replace(")","",$b);
			$this->user_id = $user_id;
		}

		/*if (!empty($this->class_id)) {
			list($a, $b) = explode(":", $this->class_id);
			$class_id = str_replace(")","",$b);
			$this->class_id = $class_id;
		}*/
		
		if ($this->isNewRecord) {
		
			$created_by_cek = $this->created_by;
			if (empty($created_by_cek)){
				$this->created_by = Yii::app()->user->id;
			}
        	$this->created_at = new CDbExpression('NOW()');
        	$this->updated_at = new CDbExpression('NOW()');
    	}
    	else {
    		$updated_by_cek = $this->updated_by;
			if (empty($updated_by_cek)){
				$this->updated_by = Yii::app()->user->id;
			}
        	$this->updated_at = new CDbExpression('NOW()');
 		}
		return true;
	}

	public function getData($id) {
	   	$sql = "SELECT MAX(jumlah) FROM (SELECT COUNT('student_id') AS jumlah FROM offline_mark WHERE lesson_id = $id AND mark_type = 1 GROUP BY 'student_id') AS penjumlahan";
		$data=Yii::app()->db->createCommand($sql);
		//$data->bindParam(":lid",$this->id,PDO::PARAM_STR);
		$data->queryColumn();
	    return $data->queryColumn();
	}
}
