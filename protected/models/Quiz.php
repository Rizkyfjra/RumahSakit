<?php

/**
 * This is the model class for table "quiz".
 *
 * The followings are the available columns in table 'quiz':
 * @property integer $id
 * @property integer $lesson_id
 * @property integer $chapter_id
 * @property integer $quiz_type
 * @property string $created_at
 * @property string $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $end_time
 * @property integer $total_question
 * @property integer $status
 */
class Quiz extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	/*public function tableName()
	{
		return 'quiz';
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
        return 'quiz';
    }

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('lesson_id', 'required'),
			array('lesson_id, quiz_type, chapter_id, quiz_type, repeat_quiz, add_to_summary, created_by, updated_by, end_time, total_question, status, semester, year, random, random_opt, sync_status, show_nilai', 'numerical', 'integerOnly'=>true),
			array('title, passcode', 'length', 'max'=>255),
			array('updated_at, created_at, start_time', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, lesson_id, chapter_id, quiz_type, title, repeat_quiz, created_at, add_to_summary, updated_at, start_time, created_by, updated_by, end_time, total_question, status, random, random_opt, sync_status, show_nilai, passcode', 'safe', 'on'=>'search'),
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
			'teacher'=>array(SELF::BELONGS_TO, 'User', 'created_by'),
			'lesson'=>array(SELF::BELONGS_TO, 'Lesson', 'lesson_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'title' => 'Judul',
			'lesson_id' => 'Pelajaran',
			'chapter_id' => 'Chapter',
			'quiz_type' => 'Tipe Ulangan',
			'created_at' => 'Created At',
			'updated_at' => 'Updated At',
			'created_by' => 'Created By',
			'updated_by' => 'Updated By',
			'start_time' => 'Start Time',
			'end_time' => 'Waktu Pengerjaan',
			'total_question' => 'Total Pertanyaan',
			'status' => 'Status',
			'add_to_summary' => 'Tambah Ke Rekap Nilai',
			'repeat_quiz' => 'Berapa Kali Percobaan',
			'random' => 'Acak Soal',
			'random_opt' => 'Acak Pilhan Jawaban',
			'show_nilai' => 'Tampil Nilai Langsung',
			'passcode' => 'Kode Kunci Pembuka Ulangan',
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
		$criteria->compare('title',$this->title);
		$criteria->compare('lesson_id',$this->lesson_id);
		$criteria->compare('chapter_id',$this->chapter_id);
		$criteria->compare('quiz_type',$this->quiz_type);
		$criteria->compare('created_at',$this->created_at,true);
		$criteria->compare('updated_at',$this->updated_at,true);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('updated_by',$this->updated_by);
		$criteria->compare('start_time',$this->start_time,true);
		$criteria->compare('end_time',$this->end_time);
		$criteria->compare('total_question',$this->total_question);
		$criteria->compare('status',$this->status);
		$criteria->compare('add_to_summary',$this->add_to_summary);
		$criteria->compare('repeat_quiz',$this->repeat_quiz);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Quiz the static model class
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
        	//$this->created_by = Yii::app()->user->id;
    	} else {
    		$this->updated_at = new CDbExpression('NOW()');
    		$this->updated_by = Yii::app()->user->id;
    	}
 		
 		return parent::beforeSave();
	}
}
