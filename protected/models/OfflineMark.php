<?php

/**
 * This is the model class for table "offline_mark".
 *
 * The followings are the available columns in table 'offline_mark':
 * @property integer $id
 * @property integer $lesson_id
 * @property integer $assignment_id
 * @property integer $student_id
 * @property string $created_at
 * @property string $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $score
 * @property string $file
 */
class OfflineMark extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	/*public function tableName()
	{
		return 'offline_mark';
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
        return 'offline_mark';
    }

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('student_id', 'required'),
			array('lesson_id, assignment_id, student_id, created_by, updated_by, score, mark_type, sync_status', 'numerical', 'integerOnly'=>true),
			array('file', 'length', 'max'=>255),
			array('updated_at, created_at,', 'safe'),
			array('file', 'file','types'=>'jpg, gif, png, cdr, psd, doc, docx, pdf, pptx, ppt, xls, xlsx, mp4, 3gp, avi, ogg','allowEmpty'=>true, 'maxSize'=>1024 * 1024 * 50,'on'=>'insert,update'), 
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, lesson_id, assignment_id, student_id, created_at, updated_at, created_by, updated_by, score, file, mark_type, sync_status', 'safe', 'on'=>'search'),
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
			'siswa'=>array(self::BELONGS_TO,'User','student_id'),
			'mapel'=>array(self::BELONGS_TO,'Lesson','lesson_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'lesson_id' => 'Pelajaran',
			'assignment_id' => 'Tugas',
			'student_id' => 'Siswa',
			'created_at' => 'Created At',
			'updated_at' => 'Updated At',
			'created_by' => 'Created By',
			'updated_by' => 'Updated By',
			'score' => 'Nilai',
			'file' => 'File',
			'mark_type' => 'Tipe Nilai',
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
		$criteria->compare('lesson_id',$this->lesson_id);
		$criteria->compare('assignment_id',$this->assignment_id);
		$criteria->compare('student_id',$this->student_id);
		$criteria->compare('created_at',$this->created_at,true);
		$criteria->compare('updated_at',$this->updated_at,true);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('updated_by',$this->updated_by);
		$criteria->compare('score',$this->score);
		$criteria->compare('file',$this->file,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return OfflineMark the static model class
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
        	$this->created_by = Yii::app()->user->id;
    	} else {
    		$this->updated_at = new CDbExpression('NOW()');
    		$this->updated_by = Yii::app()->user->id;
    	}
 		
 		return parent::beforeSave();
	}
}
