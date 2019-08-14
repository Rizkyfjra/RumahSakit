<?php

/**
 * This is the model class for table "student_assignment".
 *
 * The followings are the available columns in table 'student_assignment':
 * @property integer $id
 * @property integer $assignment_id
 * @property string $file
 * @property integer $student_id
 * @property integer $score
 * @property string $uploaded_at
 * @property integer $marked_by
 * @property string $marked_at
 */
class StudentAssignment extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public $title;
	public $due_date;
	public $lesson_name;
	public $class_name;
	public $display_name;

	/*public function tableName()
	{
		return 'student_assignment';
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
        return 'student_assignment';
    }
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('assignment_id', 'required'),
			array('assignment_id, student_id, status, trash, sync_status', 'numerical', 'integerOnly'=>true),
			array('file', 'length', 'max'=>255),
			array('content, created_at, updated_at','safe'),
			array('file', 'file','types'=>'jpg, gif, png, cdr, psd, doc, docx, pdf, pptx, ppt, xls, xlsx, mp4, 3gp, avi, ogg','allowEmpty'=>true, 'maxSize'=>1024 * 1024 * 50,'on'=>'insert,update'), 
			//array('uploaded_at', 'safe'), 
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, assignment_id, file, student_id, score, content, sync_status, trash', 'safe', 'on'=>'search'),
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
			'teacher_assign'=>array(self::BELONGS_TO, 'Assignment', 'assignment_id'),
			'student'=>array(self::BELONGS_TO, 'User', 'student_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'assignment_id' => 'Assignment',
			'file' => 'File',
			'student_id' => 'Student',
			'score' => 'Score',
			'created_at' => 'Created At',
			'updated_at' => 'Updated At',
			'status' => 'Status',
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
		$criteria->compare('assignment_id',$this->assignment_id);
		$criteria->compare('file',$this->file,true);
		$criteria->compare('student_id',$this->student_id);
		$criteria->compare('score',$this->score);
		$criteria->compare('status',$this->status);
		//$criteria->compare('uploaded_at',$this->uploaded_at,true);
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return StudentAssignment the static model class
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
    	} else {
    		$this->updated_at = new CDbExpression('NOW()');
    	}
 		
 		return parent::beforeSave();
	}
}
