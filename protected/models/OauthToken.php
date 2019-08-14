<?php

/**
 * This is the model class for table "oauth_token".
 *
 * The followings are the available columns in table 'oauth_token':
 * @property integer $id
 * @property integer $id_user
 * @property string $token
 * @property string $last_token
 * @property string $expired_date
 *
 * The followings are the available model relations:
 * @property Users $idUser
 */
class OauthToken extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	/*public function tableName()
	{
		return 'oauth_token';
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
        return 'oauth_token';
    }

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, id_user', 'numerical', 'integerOnly'=>true),
			array('token, last_token', 'length', 'max'=>255),
			array('expired_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_user, token, last_token, expired_date', 'safe', 'on'=>'search'),
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
			'User' => array(self::BELONGS_TO, 'User', 'id_user'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'id_user' => 'Id User',
			'token' => 'Token',
			'last_token' => 'Last Token',
			'expired_date' => 'Expired Date',
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
		$criteria->compare('id_user',$this->id_user);
		$criteria->compare('token',$this->token,true);
		$criteria->compare('last_token',$this->last_token,true);
		$criteria->compare('expired_date',$this->expired_date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return OauthToken the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
