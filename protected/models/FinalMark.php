<?php

/**
 * This is the model class for table "final_mark".
 *
 * The followings are the available columns in table 'final_mark':
 * @property integer $id
 * @property integer $user_id
 * @property string $tipe
 * @property integer $semester
 * @property string $tahun_ajaran
 * @property integer $nilai
 */
class FinalMark extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'final_mark';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, tipe, semester, tahun_ajaran, nilai', 'required'),
			array('user_id, semester, nilai', 'numerical', 'integerOnly'=>true),
			array('tipe, tahun_ajaran', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user_id, tipe, semester, tahun_ajaran, nilai', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_id' => 'User',
			'tipe' => 'Tipe',
			'semester' => 'Semester',
			'tahun_ajaran' => 'Tahun Ajaran',
			'nilai' => 'Nilai',
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
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('tipe',$this->tipe,true);
		$criteria->compare('semester',$this->semester);
		$criteria->compare('tahun_ajaran',$this->tahun_ajaran,true);
		$criteria->compare('nilai',$this->nilai);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return FinalMark the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
