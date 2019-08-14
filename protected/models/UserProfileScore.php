<?php

/**
 * This is the model class for table "user_profile_score".
 *
 * The followings are the available columns in table 'user_profile_score':
 * @property integer $id
 * @property integer $user_id
 * @property integer $smt_01_pai
 * @property integer $smt_01_pkn
 * @property integer $smt_01_bindo
 * @property integer $smt_01_bingg
 * @property integer $smt_01_mat
 * @property integer $smt_01_ipa
 * @property integer $smt_01_ips
 * @property integer $smt_01_seni
 * @property integer $smt_01_or
 * @property integer $smt_01_tik
 * @property integer $smt_02_pai
 * @property integer $smt_02_pkn
 * @property integer $smt_02_bindo
 * @property integer $smt_02_bingg
 * @property integer $smt_02_mat
 * @property integer $smt_02_ipa
 * @property integer $smt_02_ips
 * @property integer $smt_02_seni
 * @property integer $smt_02_or
 * @property integer $smt_02_tik
 * @property integer $smt_03_pai
 * @property integer $smt_03_pkn
 * @property integer $smt_03_bindo
 * @property integer $smt_03_bingg
 * @property integer $smt_03_mat
 * @property integer $smt_03_ipa
 * @property integer $smt_03_ips
 * @property integer $smt_03_seni
 * @property integer $smt_03_or
 * @property integer $smt_03_tik
 * @property integer $smt_04_pai
 * @property integer $smt_04_pkn
 * @property integer $smt_04_bindo
 * @property integer $smt_04_bingg
 * @property integer $smt_04_mat
 * @property integer $smt_04_ipa
 * @property integer $smt_04_ips
 * @property integer $smt_04_seni
 * @property integer $smt_04_or
 * @property integer $smt_04_tik
 * @property integer $smt_05_pai
 * @property integer $smt_05_pkn
 * @property integer $smt_05_bindo
 * @property integer $smt_05_bingg
 * @property integer $smt_05_mat
 * @property integer $smt_05_ipa
 * @property integer $smt_05_ips
 * @property integer $smt_05_seni
 * @property integer $smt_05_or
 * @property integer $smt_05_tik
 * @property integer $smt_06_pai
 * @property integer $smt_06_pkn
 * @property integer $smt_06_bindo
 * @property integer $smt_06_bingg
 * @property integer $smt_06_mat
 * @property integer $smt_06_ipa
 * @property integer $smt_06_ips
 * @property integer $smt_06_seni
 * @property integer $smt_06_or
 * @property integer $smt_06_tik
 */
class UserProfileScore extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'user_profile_score';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, smt_01_pai, smt_01_pkn, smt_01_bindo, smt_01_bingg, smt_01_mat, smt_01_ipa, smt_01_ips, smt_01_seni, smt_01_or, smt_01_tik, smt_02_pai, smt_02_pkn, smt_02_bindo, smt_02_bingg, smt_02_mat, smt_02_ipa, smt_02_ips, smt_02_seni, smt_02_or, smt_02_tik, smt_03_pai, smt_03_pkn, smt_03_bindo, smt_03_bingg, smt_03_mat, smt_03_ipa, smt_03_ips, smt_03_seni, smt_03_or, smt_03_tik, smt_04_pai, smt_04_pkn, smt_04_bindo, smt_04_bingg, smt_04_mat, smt_04_ipa, smt_04_ips, smt_04_seni, smt_04_or, smt_04_tik, smt_05_pai, smt_05_pkn, smt_05_bindo, smt_05_bingg, smt_05_mat, smt_05_ipa, smt_05_ips, smt_05_seni, smt_05_or, smt_05_tik, smt_06_pai, smt_06_pkn, smt_06_bindo, smt_06_bingg, smt_06_mat, smt_06_ipa, smt_06_ips, smt_06_seni, smt_06_or, smt_06_tik', 'required'),
			array('user_id, smt_01_pai, smt_01_pkn, smt_01_bindo, smt_01_bingg, smt_01_mat, smt_01_ipa, smt_01_ips, smt_01_seni, smt_01_or, smt_01_tik, smt_02_pai, smt_02_pkn, smt_02_bindo, smt_02_bingg, smt_02_mat, smt_02_ipa, smt_02_ips, smt_02_seni, smt_02_or, smt_02_tik, smt_03_pai, smt_03_pkn, smt_03_bindo, smt_03_bingg, smt_03_mat, smt_03_ipa, smt_03_ips, smt_03_seni, smt_03_or, smt_03_tik, smt_04_pai, smt_04_pkn, smt_04_bindo, smt_04_bingg, smt_04_mat, smt_04_ipa, smt_04_ips, smt_04_seni, smt_04_or, smt_04_tik, smt_05_pai, smt_05_pkn, smt_05_bindo, smt_05_bingg, smt_05_mat, smt_05_ipa, smt_05_ips, smt_05_seni, smt_05_or, smt_05_tik, smt_06_pai, smt_06_pkn, smt_06_bindo, smt_06_bingg, smt_06_mat, smt_06_ipa, smt_06_ips, smt_06_seni, smt_06_or, smt_06_tik', 'numerical', 'integerOnly'=>true),
			
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user_id', 'safe', 'on'=>'search'),
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
			'user' => array(self::BELONGS_TO, 'Users', 'user_id'),			
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_id' => 'User ID',
			'smt_01_pai' => 'PAI',
			'smt_01_pkn' => 'PKN',
			'smt_01_bindo' => 'B.INDO',
			'smt_01_bingg' => 'B.INGG',
			'smt_01_mat' => 'MAT',
			'smt_01_ipa' => 'IPA',
			'smt_01_ips' => 'IPS',
			'smt_01_seni' => 'Seni',
			'smt_01_or' => 'OR',
			'smt_01_tik' => 'TIK',
			'smt_02_pai' => 'PAI',
			'smt_02_pkn' => 'PKN',
			'smt_02_bindo' => 'B.INDO',
			'smt_02_bingg' => 'B.INGG',
			'smt_02_mat' => 'MAT',
			'smt_02_ipa' => 'IPA',
			'smt_02_ips' => 'IPS',
			'smt_02_seni' => 'Seni',
			'smt_02_or' => 'OR',
			'smt_02_tik' => 'TIK',
			'smt_03_pai' => 'PAI',
			'smt_03_pkn' => 'PKN',
			'smt_03_bindo' => 'B.INDO',
			'smt_03_bingg' => 'B.INGG',
			'smt_03_mat' => 'MAT',
			'smt_03_ipa' => 'IPA',
			'smt_03_ips' => 'IPS',
			'smt_03_seni' => 'Seni',
			'smt_03_or' => 'OR',
			'smt_03_tik' => 'TIK',
			'smt_04_pai' => 'PAI',
			'smt_04_pkn' => 'PKN',
			'smt_04_bindo' => 'B.INDO',
			'smt_04_bingg' => 'B.INGG',
			'smt_04_mat' => 'MAT',
			'smt_04_ipa' => 'IPA',
			'smt_04_ips' => 'IPS',
			'smt_04_seni' => 'Seni',
			'smt_04_or' => 'OR',
			'smt_04_tik' => 'TIK',
			'smt_05_pai' => 'PAI',
			'smt_05_pkn' => 'PKN',
			'smt_05_bindo' => 'Binddo',
			'smt_05_bingg' => 'B.INGG',
			'smt_05_mat' => 'MAT',
			'smt_05_ipa' => 'IPA',
			'smt_05_ips' => 'IPS',
			'smt_05_seni' => 'Seni',
			'smt_05_or' => 'OR',
			'smt_05_tik' => 'TIK',
			'smt_06_pai' => 'PAI',
			'smt_06_pkn' => 'PKN',
			'smt_06_bindo' => 'B.INDO',
			'smt_06_bingg' => 'B.INGG',
			'smt_06_mat' => 'MAT',
			'smt_06_ipa' => 'IPA',
			'smt_06_ips' => 'IPS',
			'smt_06_seni' => 'Seni',
			'smt_06_or' => 'OR',
			'smt_06_tik' => 'TIK',
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
		$criteria->compare('smt_01_pai',$this->smt_01_pai);
		$criteria->compare('smt_01_pkn',$this->smt_01_pkn);
		$criteria->compare('smt_01_bindo',$this->smt_01_bindo);
		$criteria->compare('smt_01_bingg',$this->smt_01_bingg);
		$criteria->compare('smt_01_mat',$this->smt_01_mat);
		$criteria->compare('smt_01_ipa',$this->smt_01_ipa);
		$criteria->compare('smt_01_ips',$this->smt_01_ips);
		$criteria->compare('smt_01_seni',$this->smt_01_seni);
		$criteria->compare('smt_01_or',$this->smt_01_or);
		$criteria->compare('smt_01_tik',$this->smt_01_tik);
		$criteria->compare('smt_02_pai',$this->smt_02_pai);
		$criteria->compare('smt_02_pkn',$this->smt_02_pkn);
		$criteria->compare('smt_02_bindo',$this->smt_02_bindo);
		$criteria->compare('smt_02_bingg',$this->smt_02_bingg);
		$criteria->compare('smt_02_mat',$this->smt_02_mat);
		$criteria->compare('smt_02_ipa',$this->smt_02_ipa);
		$criteria->compare('smt_02_ips',$this->smt_02_ips);
		$criteria->compare('smt_02_seni',$this->smt_02_seni);
		$criteria->compare('smt_02_or',$this->smt_02_or);
		$criteria->compare('smt_02_tik',$this->smt_02_tik);
		$criteria->compare('smt_03_pai',$this->smt_03_pai);
		$criteria->compare('smt_03_pkn',$this->smt_03_pkn);
		$criteria->compare('smt_03_bindo',$this->smt_03_bindo);
		$criteria->compare('smt_03_bingg',$this->smt_03_bingg);
		$criteria->compare('smt_03_mat',$this->smt_03_mat);
		$criteria->compare('smt_03_ipa',$this->smt_03_ipa);
		$criteria->compare('smt_03_ips',$this->smt_03_ips);
		$criteria->compare('smt_03_seni',$this->smt_03_seni);
		$criteria->compare('smt_03_or',$this->smt_03_or);
		$criteria->compare('smt_03_tik',$this->smt_03_tik);
		$criteria->compare('smt_04_pai',$this->smt_04_pai);
		$criteria->compare('smt_04_pkn',$this->smt_04_pkn);
		$criteria->compare('smt_04_bindo',$this->smt_04_bindo);
		$criteria->compare('smt_04_bingg',$this->smt_04_bingg);
		$criteria->compare('smt_04_mat',$this->smt_04_mat);
		$criteria->compare('smt_04_ipa',$this->smt_04_ipa);
		$criteria->compare('smt_04_ips',$this->smt_04_ips);
		$criteria->compare('smt_04_seni',$this->smt_04_seni);
		$criteria->compare('smt_04_or',$this->smt_04_or);
		$criteria->compare('smt_04_tik',$this->smt_04_tik);
		$criteria->compare('smt_05_pai',$this->smt_05_pai);
		$criteria->compare('smt_05_pkn',$this->smt_05_pkn);
		$criteria->compare('smt_05_bindo',$this->smt_05_bindo);
		$criteria->compare('smt_05_bingg',$this->smt_05_bingg);
		$criteria->compare('smt_05_mat',$this->smt_05_mat);
		$criteria->compare('smt_05_ipa',$this->smt_05_ipa);
		$criteria->compare('smt_05_ips',$this->smt_05_ips);
		$criteria->compare('smt_05_seni',$this->smt_05_seni);
		$criteria->compare('smt_05_or',$this->smt_05_or);
		$criteria->compare('smt_05_tik',$this->smt_05_tik);
		$criteria->compare('smt_06_pai',$this->smt_06_pai);
		$criteria->compare('smt_06_pkn',$this->smt_06_pkn);
		$criteria->compare('smt_06_bindo',$this->smt_06_bindo);
		$criteria->compare('smt_06_bingg',$this->smt_06_bingg);
		$criteria->compare('smt_06_mat',$this->smt_06_mat);
		$criteria->compare('smt_06_ipa',$this->smt_06_ipa);
		$criteria->compare('smt_06_ips',$this->smt_06_ips);
		$criteria->compare('smt_06_seni',$this->smt_06_seni);
		$criteria->compare('smt_06_or',$this->smt_06_or);
		$criteria->compare('smt_06_tik',$this->smt_06_tik);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UserProfileScore the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
