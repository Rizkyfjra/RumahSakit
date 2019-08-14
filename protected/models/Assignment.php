<?php

/**
 * This is the model class for table "assignment".
 *
 * The followings are the available columns in table 'assignment':
 * @property integer $id
 * @property string $title
 * @property string $content
 * @property string $created_at
 * @property string $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property string $due_date
 * @property integer $lesson_id
 */
class Assignment extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public $lesson_name;
	public $class_name;
	
	/*public function tableName()
	{
		return 'assignment';
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
        return 'assignment';
    }

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, lesson_id', 'required'),
			//array('created_by, updated_by, lesson_id', 'numerical', 'integerOnly'=>true),
			array('assignment_type, add_to_summary, semester, year, status, sync_status, trash', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>255),
			array('updated_at, due_date, content, recipient', 'safe'),
			array('file', 'file','types'=>'jpg, gif, png, doc, docx, xls, xlsx,pdf, mp4, MP4, avi, flv, mkv, swf, pptx', 'allowEmpty'=>true, 'maxSize'=>1024 * 1024 * 2, 'on'=>'insert,update'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, title, content, created_at, updated_at, add_to_summary, created_by, updated_by, due_date, lesson_id, recipient, file, assignment_type, status, sync_status, trash', 'safe', 'on'=>'search'),
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
			'lesson'=>array(self::BELONGS_TO, 'Lesson', 'lesson_id'),
			'teacher'=>array(self::BELONGS_TO, 'User', 'created_by'),
			'assign'=>array(self::HAS_MANY, 'StudentAssignment', 'id')
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
			'content' => 'Uraian',
			'created_at' => 'Created At',
			'updated_at' => 'Updated At',
			'created_by' => 'Created By',
			'updated_by' => 'Updated By',
			'due_date' => 'Batas Akhir Pengumpulan',
			'lesson_id' => 'Mata Pelajaran',
			'file' => 'Lampiran',
			'recipient' => 'Siswa Penerima',
			'add_to_summary' => 'Tambah Ke Rekap Nilai',
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
		$criteria->compare('title',$this->title,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('created_at',$this->created_at,true);
		$criteria->compare('updated_at',$this->updated_at,true);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('updated_by',$this->updated_by);
		$criteria->compare('due_date',$this->due_date,true);
		$criteria->compare('lesson_id',$this->lesson_id);
		$criteria->compare('file',$this->file);
		$criteria->compare('recipient',$this->recipient);
		$criteria->compare('add_to_summary',$this->add_to_summary);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Assignment the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function beforeSave()
	{

		if (!empty($this->lesson_id)) {
			$cek_lid = explode(":", $this->lesson_id);
			if (count($cek_lid) >= 2){
				list($a, $b) = explode(":", $this->lesson_id);
				$lesson_id = str_replace(")","",$b);
				$this->lesson_id = $lesson_id;
			}
		}

		if (!empty($this->recipient)) {
			$cek_rcp = explode(":", $this->recipient);
			if (count($cek_rcp) >= 2){
				list($c, $d) = explode(":", $this->recipient);
				$recipient = str_replace(")","",$d);
				$this->recipient = $recipient;
			}
		}
		
		if ($this->isNewRecord) {
			$created_by_cek = $this->created_by;
			if (empty($created_by_cek)){
				$this->created_by = Yii::app()->user->id;
			}
			$updated_by_cek = $this->updated_by;
			if (empty($updated_by_cek)){
				$this->updated_by = Yii::app()->user->id;
			}

        	$this->created_at = new CDbExpression('NOW()');
        	$this->updated_at = new CDbExpression('NOW()');
    	}
    	else {
    		$updated_by_cek = $this->updated_by;
			if (empty($updated_by_cek)){
				$this->updated_by = Yii::app()->user->id;
			}
        	$this->updated_at = new CDbExpression('NOW()');
 		}
		return true;
	}

}
