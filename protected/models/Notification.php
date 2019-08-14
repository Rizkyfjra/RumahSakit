<?php

/**
 * This is the model class for table "notification".
 *
 * The followings are the available columns in table 'notification':
 * @property integer $id
 * @property string $content
 * @property integer $user_id
 * @property integer $user_id_to
 * @property string $tipe
 */
class Notification extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	/*public function tableName()
	{
		return 'notification';
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
        return 'notification';
    }

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('content, user_id', 'required'),
			array('user_id, user_id_to, class_id_to, sync_status', 'numerical', 'integerOnly'=>true),
			array('content, tipe', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, content, user_id, user_id_to, class_id_to, tipe, sync_status', 'safe', 'on'=>'search'),
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
			'user'=>array(self::BELONGS_TO, 'User', 'user_id'),
			'user_to'=>array(self::BELONGS_TO, 'User', 'user_id_to'),
			'class_to'=>array(self::BELONGS_TO, 'Clases', 'class_id_to'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'content' => 'Content',
			'user_id' => 'User',
			'user_id_to' => 'User Id To',
			'tipe' => 'Tipe',
			'class_id_to' => 'Class Id To',
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
		$criteria->compare('content',$this->content,true);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('user_id_to',$this->user_id_to,true);
		$criteria->compare('tipe',$this->tipe,true);
		$criteria->compare('class_id_to',$this->user_id_to,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Notification the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function beforeSave()
	{
		
		if ($this->isNewRecord) {
			
        	$this->created_at = new CDbExpression('NOW()');
        	$this->updated_at = new CDbExpression('NOW()');
    	}
    	else {
    		
        	$this->updated_at = new CDbExpression('NOW()');
 		}
		return true;
	}
}
