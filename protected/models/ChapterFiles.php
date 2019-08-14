<?php

/**
 * This is the model class for table "chapter_files".
 *
 * The followings are the available columns in table 'chapter_files':
 * @property integer $id
 * @property integer $id_chapter
 * @property string $created_at
 * @property string $updated_at
 * @property string $file
 * @property string $content
 * @property integer $created_by
 * @property integer $updated_by
 *
 * The followings are the available model relations:
 * @property Chapters $idChapter
 */
class ChapterFiles extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	/*public function tableName()
	{
		return 'chapter_files';
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
        return 'chapter_files';
    }

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('id_chapter', 'required'),
			array('id_chapter, created_by, updated_by, sync_status', 'numerical', 'integerOnly'=>true),
			array('type', 'length', 'max'=>255),
			array('file', 'file','types'=>'doc, docx, xls, xlsx, ppt, pptx, pdf', 'maxSize'=>1024 * 1024 * 200,'allowEmpty'=>true, 'on'=>'dokumen'), 
			array('file', 'file','types'=>'mp3, mkv, mp4, MP4, avi, flv, swf', 'maxSize'=>1024 * 1024 * 200,'allowEmpty'=>true, 'on'=>'video'),
			array('file', 'file','types'=>'jpg, gif, png', 'maxSize'=>1024 * 1024 * 200,'allowEmpty'=>true, 'on'=>'gambar'),
			array('updated_at, content', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_chapter, created_at, updated_at, file, type, content, created_by, updated_by, sync_status', 'safe', 'on'=>'search'),
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
			'idChapter' => array(self::BELONGS_TO, 'Chapters', 'id_chapter'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'id_chapter' => 'Id Chapter',
			'created_at' => 'Created At',
			'updated_at' => 'Updated At',
			'file' => 'Lampiran',
			'type' => 'Type',
			'content' => 'Content',
			'created_by' => 'Created By',
			'updated_by' => 'Updated By',
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
		$criteria->compare('id_chapter',$this->id_chapter);
		$criteria->compare('created_at',$this->created_at,true);
		$criteria->compare('updated_at',$this->updated_at,true);
		$criteria->compare('file',$this->file,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('updated_by',$this->updated_by);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ChapterFiles the static model class
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
 		return parent::beforeSave();
	}
}
