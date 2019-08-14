<?php

/**
 * This is the model class for table "chapters".
 *
 * The followings are the available columns in table 'chapters':
 * @property integer $id
 * @property integer $id_lesson
 * @property string $title
 * @property string $description
 * @property string $created_at
 * @property string $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 *
 * The followings are the available model relations:
 * @property ChapterFiles[] $chapterFiles
 * @property Lesson $idLesson
 */
class Chapters extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */

	/*public function tableName()
	{
		return 'chapters';
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
        return 'chapters';
    }

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_lesson, title', 'required'),
			array('id_lesson, created_by, updated_by, chapter_type, semester, year,sync_status', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>255),
			array('updated_at, description, content', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_lesson, title, description, created_at, updated_at, created_by, updated_by, chapter_type, sync_status', 'safe', 'on'=>'search'),
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
			'chapterFiles' => array(self::HAS_MANY, 'ChapterFiles', 'id_chapter'),
			'mapel' => array(self::BELONGS_TO, 'Lesson', 'id_lesson'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'id_lesson' => 'Mata Pelajaran',
			'title' => 'Judul',
			'description' => 'Ringkasan',
			'content' => 'Uraian',
			'created_at' => 'Created At',
			'updated_at' => 'Updated At',
			'created_by' => 'Created By',
			'updated_by' => 'Updated By',
			'chapter_type' => 'Tipe Materi',
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
		$criteria->compare('id_lesson',$this->id_lesson);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('created_at',$this->created_at,true);
		$criteria->compare('updated_at',$this->updated_at,true);
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
	 * @return Chapters the static model class
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
