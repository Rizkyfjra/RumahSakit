<?php

/**
 * This is the model class for table "class_detail".
 *
 * The followings are the available columns in table 'class_detail':
 * @property integer $id
 * @property string $name
 * @property integer $class_id
 * @property integer $urutan
 */
class ClassDetail extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'class_detail';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name', 'required'),
			array('class_id, urutan', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, class_id, urutan', 'safe', 'on'=>'search'),
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
			'teacher'=>array(self::BELONGS_TO, 'User', 'teacher_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Nama',
			'class_id' => 'Tingkat',
			'teacher_id' => 'Guru',
			'urutan' => 'Urutan',
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
		$criteria->compare('class_id',$this->class_id);
		$criteria->compare('urutan',$this->urutan);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ClassDetail the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

		public function beforeSave()
	{
	
		if (!empty($this->teacher_id) && Yii::app()->user->YiiAdmin) {
			list($a, $b) = explode(":", $this->teacher_id);
			$teacher_id = str_replace(")","",$b);
			$this->teacher_id = $teacher_id;
		}
		return true;
	}
}
