<?php

/**
 * This is the model class for table "class".
 *
 * The followings are the available columns in table 'class':
 * @property integer $id
 * @property string $name
 */
class Clases extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	/*public function tableName()
	{
		return 'class';
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
        return 'class';
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
			array('teacher_id, sync_status, kelompok', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, sync_status', 'safe', 'on'=>'search'),
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
			'clases'=>array(self::HAS_MANY, 'Lesson', 'class_id'),
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
			'name' => 'Name',
			'teacher_id' => 'Penanggung Jawab',
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
		$criteria->compare('teacher_id',$this->teacher_id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function path_image($id) {
		$id = (string)$id;
		$id_length = strlen($id);
		$ratus = substr($id, -3);


		if ($id_length > 3){
		$ribu = substr($id, -6, -3);
		} else {
		$ribu = "000";
		}
		if ($id_length > 6){
		$juta = substr($id, -9, -6);
		} else {
		$juta = "000";
		}
		if ($id_length > 9){
		$milyar = substr($id, -12, -9);
		} else {
		$milyar = "00";
		}

		if (strlen($ratus) == 1) {
		$ratus = "00".$ratus;
		} elseif (strlen($ratus) == 2) {
		$ratus = "0".$ratus;
		}
		if (strlen($ribu) == 1) {
		$ribu = "00".$ribu;
		} elseif (strlen($ribu) == 2) {
		$ribu = "0".$ribu;
		}
		if (strlen($juta) == 1) {
		$juta = "00".$juta;
		} elseif (strlen($juta) == 2) {
		$juta = "0".$juta;
		}
		if (strlen($milyar) == 1) {
		$milyar = "0".$milyar;
		}

		$path = "/".$milyar."/".$juta."/".$ribu."/".$ratus."/";

		return $path;
	}

	public function rentang($kkm){
		

		$optSchoolType=Option::model()->findByAttributes(array('key_config'=>'school_name'))->value;		
		
		 if (Yii::app()->session['semester']) {
            $optSemester = Yii::app()->session['semester'];
        } else {
            $optSemester = Option::model()->findByAttributes(array('key_config' => 'semester'))->value;
        }
        if (Yii::app()->session['tahun_ajaran']) {
            $optTahunAjaran = Yii::app()->session['tahun_ajaran'];
        } else {
            $optTahunAjaran = Option::model()->findByAttributes(array('key_config' => 'tahun_ajaran'))->value;
        }

		
		$a_xii = range(95, 100);
		$b_xii = range(87, 94);
		$c_xii = range(80, 86);
		$d_xii = range(0, 79);
		
		
		$a_xi = range(93, 100);
		$b_xi = range(85, 92);
		$c_xi = range(78, 84);
		$d_xi = range(0, 77);

			$a_x = range(90, 100);
			$b_x = range(79, 89);
			$c_x = range(75, 78);
			$d_x = range(0, 74);

		$a_ix = range(90, 100);
		$b_ix = range(80, 89);
		$c_ix = range(70, 79);
		$d_ix = range(0, 69);

		//65
			$a_iv = range(78, 100);
			$b_iv = range(76, 78);
			$c_iv = range(65, 76);
			$d_iv = range(0, 65);



		if ($optSchoolType=="SMA NEGERI 22 BANDUNG") {

			if ($optTahunAjaran=="2017") {
				//80
				$a_xii = range(94, 100);
				$b_xii = range(87, 93);
				$c_xii = range(80, 86);
				$d_xii = range(0, 79);
				
				//78
				$a_xi = range(91, 100);
				$b_xi = range(81, 90);
				$c_xi = range(78, 80);
				$d_xi = range(0, 77);

				//75
				$a_x = range(92, 100);
				$b_x = range(83, 91);
				$c_x = range(75, 82);
				$d_x = range(0, 74);	

				//70
				$a_ix = range(90, 100);
				$b_ix = range(80, 89);
				$c_ix = range(70, 79);
				$d_ix = range(0, 69);

				//74
				$a_viii = range(91, 100);
				$b_viii = range(82, 90);
				$c_viii = range(74, 81);
				$d_viii = range(0, 73);

				//76
				$a_vii = range(92, 100);
				$b_vii = range(84, 91);
				$c_vii = range(76, 83);
				$d_vii = range(0, 75);


				//77
				$a_vi = range(93, 100);
				$b_vi = range(85, 92);
				$c_vi = range(77, 84);
				$d_vi = range(0, 76);


				//79
				$a_v = range(93, 100);
				$b_v = range(86, 92);
				$c_v = range(79, 85);
				$d_v = range(0, 78);

				//65
				$a_iv = range(78, 100);
				$b_iv = range(76, 78);
				$c_iv = range(65, 76);
				$d_iv = range(0, 65);

			} else {
				//80
				$a_xii = range(94, 100);
				$b_xii = range(87, 93);
				$c_xii = range(80, 86);
				$d_xii = range(0, 79);
				
				//78
				$a_xi = range(94, 100);
				$b_xi = range(86, 93);
				$c_xi = range(78, 85);
				$d_xi = range(0, 77);

				//75
				$a_x = range(92, 100);
				$b_x = range(83, 91);
				$c_x = range(75, 82);
				$d_x = range(0, 74);	

				//70
				$a_ix = range(90, 100);
				$b_ix = range(80, 89);
				$c_ix = range(70, 79);
				$d_ix = range(0, 69);

				//74
				$a_viii = range(91, 100);
				$b_viii = range(82, 90);
				$c_viii = range(74, 81);
				$d_viii = range(0, 73);

				//76
				$a_vii = range(92, 100);
				$b_vii = range(84, 91);
				$c_vii = range(76, 83);
				$d_vii = range(0, 75);


				//77
				$a_vi = range(93, 100);
				$b_vi = range(85, 92);
				$c_vi = range(77, 84);
				$d_vi = range(0, 76);


				//79
				$a_v = range(93, 100);
				$b_v = range(86, 92);
				$c_v = range(79, 85);
				$d_v = range(0, 78);

				//65
				$a_iv = range(78, 100);
				$b_iv = range(76, 78);
				$c_iv = range(65, 76);
				$d_iv = range(0, 65);
			}
			
		} else if ($optSchoolType=="SMA NEGERI 2 BANDUNG" || $optSchoolType=="SMA Negeri 2 Bandung"){
			$a_xii = range(91, 100);
			$b_xii = range(82, 90);
			$c_xii = range(80, 81);
			$d_xii = range(0, 79);
			
			$a_xi = range(91, 100);
			$b_xi = range(81, 90);
			$c_xi = range(78, 80);
			$d_xi = range(0, 77);

			$a_x = range(90, 100);
			$b_x = range(80, 89);
			$c_x = range(75, 79);
			$d_x = range(0, 74);	

			$a_ix = range(90, 100);
			$b_ix = range(80, 89);
			$c_ix = range(70, 79);
			$d_ix = range(0, 69);

			//65
			$a_iv = range(78, 100);
			$b_iv = range(76, 78);
			$c_iv = range(65, 76);
			$d_iv = range(0, 65);
		} else if ($optSchoolType=="SMA NEGERI 1 BANDUNG" || $optSchoolType=="SMA Negeri 1 Bandung"){
			$a_xii = range(91, 100);
			$b_xii = range(82, 90);
			$c_xii = range(80, 81);
			$d_xii = range(0, 79);
			
			$a_xi = range(91, 100);
			$b_xi = range(81, 90);
			$c_xi = range(78, 80);
			$d_xi = range(0, 77);

			$a_x = range(91, 100);
			$b_x = range(83, 90);
			$c_x = range(75, 82);
			$d_x = range(0, 74);	

			$a_ix = range(90, 100);
			$b_ix = range(80, 89);
			$c_ix = range(70, 79);
			$d_ix = range(0, 69);


			//65
			$a_iv = range(78, 100);
			$b_iv = range(76, 78);
			$c_iv = range(65, 76);
			$d_iv = range(0, 65);
		} else {
			$a_xii = range(91, 100);
			$b_xii = range(82, 90);
			$c_xii = range(80, 81);
			$d_xii = range(0, 79);
			
			$a_xi = range(91, 100);
			$b_xi = range(81, 90);
			$c_xi = range(78, 80);
			$d_xi = range(0, 77);

			$a_x = range(90, 100);
			$b_x = range(80, 989);
			$c_x = range(75, 79);
			$d_x = range(0, 74);	

			$a_ix = range(90, 100);
			$b_ix = range(80, 89);
			$c_ix = range(70, 79);
			$d_ix = range(0, 69);


			//65
			$a_iv = range(78, 100);
			$b_iv = range(76, 78);
			$c_iv = range(65, 76);
			$d_iv = range(0, 65);

			//74
				$a_viii = range(91, 100);
				$b_viii = range(82, 90);
				$c_viii = range(74, 81);
				$d_viii = range(0, 73);

				//76
				$a_vii = range(92, 100);
				$b_vii = range(84, 91);
				$c_vii = range(76, 83);
				$d_vii = range(0, 75);


				//77
				$a_vi = range(93, 100);
				$b_vi = range(85, 92);
				$c_vi = range(77, 84);
				$d_vi = range(0, 76);
		}

		if ($kkm=="80") {
			$a = $a_xii;
			$b = $b_xii;
			$c = $c_xii;
			$d = $d_xii;

		} else if ($kkm=="78") {
			$a = $a_xi;
			$b = $b_xi;
			$c = $c_xi;
			$d = $d_xi;
			
		} else if ($kkm=="75") {
			$a = $a_x;
			$b = $b_x;
			$c = $c_x;
			$d = $d_x;
		} else if ($kkm=="70") {
			$a = $a_ix;
			$b = $b_ix;
			$c = $c_ix;
			$d = $d_ix;
		} else if ($kkm=="74") {
			$a = $a_viii;
			$b = $b_viii;
			$c = $c_viii;
			$d = $d_viii;

			
		} else if ($kkm=="76") {
			$a = $a_vii;
			$b = $b_vii;
			$c = $c_vii;
			$d = $d_vii;

			
		} else if ($kkm=="77") {
			$a = $a_vi;
			$b = $b_vi;
			$c = $c_vi;
			$d = $d_vi;

			
		} else if ($kkm=="79") {
			$a = $a_v;
			$b = $b_v;
			$c = $c_v;
			$d = $d_v;

			
		} else if ($kkm=="65") {
			$a = $a_iv;
			$b = $b_iv;
			$c = $c_iv;
			$d = $d_iv;

			
		}
	

		$return[0] = $a;
		$return[1] = $b;
		$return[2] = $c;
		$return[3] = $d;

		return $return;
	}


		public function predikat($id,$kkm){
		// $a = range(97, 100);
		// $amin = range(88, 96);
		// $bplus = range(80, 87);
		// $b = range(72, 79);
		// $bmin = range(63, 71);
		// $cplus = range(55, 62);
		// $c = range(47, 54);
		// $cmin = range(38, 46);
		// $dplus = range(30, 37);
		// $d = range(25, 29);


		// if(in_array($id, $a)){
		// 	$nilai = "A";
		// }elseif(in_array($id, $amin)){
		// 	$nilai = "A-";
		// }elseif(in_array($id, $bplus)){
		// 	$nilai = "B+";
		// }elseif(in_array($id, $b)){
		// 	$nilai = "B";
		// }elseif(in_array($id, $bmin)){
		// 	$nilai = "B-";
		// }elseif(in_array($id, $cplus)){
		// 	$nilai = "C+";
		// }elseif(in_array($id, $c)){
		// 	$nilai = "C";
		// }elseif(in_array($id, $cmin)){
		// 	$nilai = "C-";
		// }elseif(in_array($id, $dplus)){
		// 	$nilai = "D+";
		// }elseif(in_array($id, $d)){
		// 	$nilai = "D";
		// }else{
		// 	$nilai = "E";
		// }

		$optSchoolType=Option::model()->findByAttributes(array('key_config'=>'school_name'))->value;		
		

		 if (Yii::app()->session['semester']) {
            $optSemester = Yii::app()->session['semester'];
        } else {
            $optSemester = Option::model()->findByAttributes(array('key_config' => 'semester'))->value;
        }
        if (Yii::app()->session['tahun_ajaran']) {
            $optTahunAjaran = Yii::app()->session['tahun_ajaran'];
        } else {
            $optTahunAjaran = Option::model()->findByAttributes(array('key_config' => 'tahun_ajaran'))->value;
        }
		
		$a_xii = range(95, 100);
		$b_xii = range(87, 94);
		$c_xii = range(80, 86);
		$d_xii = range(0, 79);
		
		
		$a_xi = range(93, 100);
		$b_xi = range(85, 92);
		$c_xi = range(78, 84);
		$d_xi = range(0, 77);

			$a_x = range(91, 100);
			$b_x = range(80, 90);
			$c_x = range(75, 79);
			$d_x = range(0, 74);

		    $a_ix = range(90, 100);
			$b_ix = range(80, 89);
			$c_ix = range(70, 79);
			$d_ix = range(0, 69);

			//65
			$a_iv = range(78, 100);
			$b_iv = range(76, 78);
			$c_iv = range(65, 76);
			$d_iv = range(0, 65);



		if ($optSchoolType=="SMA NEGERI 22 BANDUNG") {
			
			if ($optTahunAjaran=="2017") {
				//80
				$a_xii = range(94, 100);
				$b_xii = range(87, 93);
				$c_xii = range(80, 86);
				$d_xii = range(0, 79);
				
				//78
				$a_xi = range(91, 100);
				$b_xi = range(81, 90);
				$c_xi = range(78, 80);
				$d_xi = range(0, 77);

				//75
				$a_x = range(92, 100);
				$b_x = range(83, 91);
				$c_x = range(75, 82);
				$d_x = range(0, 74);	

				//70
				$a_ix = range(90, 100);
				$b_ix = range(80, 89);
				$c_ix = range(70, 79);
				$d_ix = range(0, 69);

				//74
				$a_viii = range(91, 100);
				$b_viii = range(82, 90);
				$c_viii = range(74, 81);
				$d_viii = range(0, 73);

				//76
				$a_vii = range(92, 100);
				$b_vii = range(84, 91);
				$c_vii = range(76, 83);
				$d_vii = range(0, 75);


				//77
				$a_vi = range(93, 100);
				$b_vi = range(85, 92);
				$c_vi = range(77, 84);
				$d_vi = range(0, 76);


				//79
				$a_v = range(93, 100);
				$b_v = range(86, 92);
				$c_v = range(79, 85);
				$d_v = range(0, 78);

				//65
				$a_iv = range(78, 100);
				$b_iv = range(76, 78);
				$c_iv = range(65, 76);
				$d_iv = range(0, 65);


			} else {
				//80
				$a_xii = range(94, 100);
				$b_xii = range(87, 93);
				$c_xii = range(80, 86);
				$d_xii = range(0, 79);
				
				//78
				$a_xi = range(94, 100);
				$b_xi = range(86, 93);
				$c_xi = range(78, 85);
				$d_xi = range(0, 77);

				//75
				$a_x = range(92, 100);
				$b_x = range(83, 91);
				$c_x = range(75, 82);
				$d_x = range(0, 74);	

				//70
				$a_ix = range(90, 100);
				$b_ix = range(80, 89);
				$c_ix = range(70, 79);
				$d_ix = range(0, 69);

				//74
				$a_viii = range(91, 100);
				$b_viii = range(82, 90);
				$c_viii = range(74, 81);
				$d_viii = range(0, 73);

				//76
				$a_vii = range(92, 100);
				$b_vii = range(84, 91);
				$c_vii = range(76, 83);
				$d_vii = range(0, 75);


				//77
				$a_vi = range(93, 100);
				$b_vi = range(85, 92);
				$c_vi = range(77, 84);
				$d_vi = range(0, 76);


				//79
				$a_v = range(93, 100);
				$b_v = range(86, 92);
				$c_v = range(79, 85);
				$d_v = range(0, 78);

				//65
				$a_iv = range(78, 100);
				$b_iv = range(76, 78);
				$c_iv = range(65, 76);
				$d_iv = range(0, 65);
			}


			// //80
			// $a_xii = range(94, 100);
			// $b_xii = range(87, 93);
			// $c_xii = range(80, 86);
			// $d_xii = range(0, 79);
			
			// //78
			// $a_xi = range(94, 100);
			// $b_xi = range(86, 93);
			// $c_xi = range(78, 85);
			// $d_xi = range(0, 77);

			// //75
			// $a_x = range(92, 100);
			// $b_x = range(83, 91);
			// $c_x = range(75, 82);
			// $d_x = range(0, 74);	

			// //70
			// $a_ix = range(90, 100);
			// $b_ix = range(80, 89);
			// $c_ix = range(70, 79);
			// $d_ix = range(0, 69);

			// //74
			// $a_viii = range(91, 100);
			// $b_viii = range(82, 90);
			// $c_viii = range(74, 81);
			// $d_viii = range(0, 73);

			// //76
			// $a_vii = range(92, 100);
			// $b_vii = range(84, 91);
			// $c_vii = range(76, 83);
			// $d_vii = range(0, 75);


			// //77
			// $a_vi = range(93, 100);
			// $b_vi = range(85, 92);
			// $c_vi = range(77, 84);
			// $d_vi = range(0, 76);


			// //79
			// $a_v = range(93, 100);
			// $b_v = range(86, 92);
			// $c_v = range(79, 85);
			// $d_v = range(0, 78);



			//65
			$a_iv = range(78, 100);
			$b_iv = range(76, 78);
			$c_iv = range(65, 76);
			$d_iv = range(0, 65);


		} else if ($optSchoolType=="SMA NEGERI 2 BANDUNG" || $optSchoolType=="SMA Negeri 2 Bandung"){
			$a_xii = range(91, 100);
			$b_xii = range(82, 90);
			$c_xii = range(80, 81);
			$d_xii = range(0, 79);
			
			$a_xi = range(91, 100);
			$b_xi = range(81, 90);
			$c_xi = range(78, 80);
			$d_xi = range(0, 77);

			$a_x = range(90, 100);
			$b_x = range(80, 89);
			$c_x = range(75, 79);
			$d_x = range(0, 74);	

			$a_ix = range(90, 100);
			$b_ix = range(80, 89);
			$c_ix = range(70, 79);
			$d_ix = range(0, 69);
		} else if ($optSchoolType=="SMA NEGERI 1 BANDUNG" || $optSchoolType=="SMA Negeri 1 Bandung"){
			$a_xii = range(91, 100);
			$b_xii = range(82, 90);
			$c_xii = range(80, 81);
			$d_xii = range(0, 79);
			
			$a_xi = range(91, 100);
			$b_xi = range(81, 90);
			$c_xi = range(78, 80);
			$d_xi = range(0, 77);

			$a_x = range(91, 100);
			$b_x = range(83, 90);
			$c_x = range(75, 82);
			$d_x = range(0, 74);	

			$a_ix = range(90, 100);
			$b_ix = range(80, 89);
			$c_ix = range(70, 79);
			$d_ix = range(0, 69);
		}  else {
			$a_xii = range(91, 100);
			$b_xii = range(82, 90);
			$c_xii = range(80, 81);
			$d_xii = range(0, 79);
			
			$a_xi = range(91, 100);
			$b_xi = range(81, 90);
			$c_xi = range(78, 80);
			$d_xi = range(0, 77);

			$a_x = range(90, 100);
			$b_x = range(80, 89);
			$c_x = range(75, 79);
			$d_x = range(0, 74);	

			$a_ix = range(90, 100);
			$b_ix = range(80, 89);
			$c_ix = range(70, 79);
			$d_ix = range(0, 69);


			//65
			$a_iv = range(78, 100);
			$b_iv = range(76, 78);
			$c_iv = range(65, 76);
			$d_iv = range(0, 65);

			//74
				$a_viii = range(91, 100);
				$b_viii = range(82, 90);
				$c_viii = range(74, 81);
				$d_viii = range(0, 73);

				//76
				$a_vii = range(92, 100);
				$b_vii = range(84, 91);
				$c_vii = range(76, 83);
				$d_vii = range(0, 75);


				//77
				$a_vi = range(93, 100);
				$b_vi = range(85, 92);
				$c_vi = range(77, 84);
				$d_vi = range(0, 76);
		}

		if ($kkm=="80") {
			$a = $a_xii;
			$b = $b_xii;
			$c = $c_xii;
			$d = $d_xii;

			if(in_array($id, $a)){
				$nilai = "A";
			}elseif(in_array($id, $b)){
				$nilai = "B";
			}elseif(in_array($id, $c)){
				$nilai = "C";
			}elseif(in_array($id, $d)){
				$nilai = "D";
			}else{
				$nilai = "D";
			}
		} else if ($kkm=="78") {
			$a = $a_xi;
			$b = $b_xi;
			$c = $c_xi;
			$d = $d_xi;

			if(in_array($id, $a)){
				$nilai = "A";
			}elseif(in_array($id, $b)){
				$nilai = "B";
			}elseif(in_array($id, $c)){
				$nilai = "C";
			}elseif(in_array($id, $d)){
				$nilai = "D";
			}else{
				$nilai = "D";
			}
		} else if ($kkm=="75") {
			$a = $a_x;
			$b = $b_x;
			$c = $c_x;
			$d = $d_x;

			if(in_array($id, $a)){
				$nilai = "A";
			}elseif(in_array($id, $b)){
				$nilai = "B";
			}elseif(in_array($id, $c)){
				$nilai = "C";
			}elseif(in_array($id, $d)){
				$nilai = "D";
			}else{
				$nilai = "D";
			}
		} else if ($kkm=="70") {
			$a = $a_ix;
			$b = $b_ix;
			$c = $c_ix;
			$d = $d_ix;

			if(in_array($id, $a)){
				$nilai = "A";
			}elseif(in_array($id, $b)){
				$nilai = "B";
			}elseif(in_array($id, $c)){
				$nilai = "C";
			}elseif(in_array($id, $d)){
				$nilai = "D";
			}else{
				$nilai = "D";
			}
		} else if ($kkm=="74") {
			$a = $a_viii;
			$b = $b_viii;
			$c = $c_viii;
			$d = $d_viii;

			if(in_array($id, $a)){
				$nilai = "A";
			}elseif(in_array($id, $b)){
				$nilai = "B";
			}elseif(in_array($id, $c)){
				$nilai = "C";
			}elseif(in_array($id, $d)){
				$nilai = "D";
			}else{
				$nilai = "D";
			}
		} else if ($kkm=="76") {
			$a = $a_vii;
			$b = $b_vii;
			$c = $c_vii;
			$d = $d_vii;

			if(in_array($id, $a)){
				$nilai = "A";
			}elseif(in_array($id, $b)){
				$nilai = "B";
			}elseif(in_array($id, $c)){
				$nilai = "C";
			}elseif(in_array($id, $d)){
				$nilai = "D";
			}else{
				$nilai = "D";
			}
		} else if ($kkm=="77") {
			$a = $a_vi;
			$b = $b_vi;
			$c = $c_vi;
			$d = $d_vi;

			if(in_array($id, $a)){
				$nilai = "A";
			}elseif(in_array($id, $b)){
				$nilai = "B";
			}elseif(in_array($id, $c)){
				$nilai = "C";
			}elseif(in_array($id, $d)){
				$nilai = "D";
			}else{
				$nilai = "D";
			}
		} else if ($kkm=="79") {
			$a = $a_v;
			$b = $b_v;
			$c = $c_v;
			$d = $d_v;

			if(in_array($id, $a)){
				$nilai = "A";
			}elseif(in_array($id, $b)){
				$nilai = "B";
			}elseif(in_array($id, $c)){
				$nilai = "C";
			}elseif(in_array($id, $d)){
				$nilai = "D";
			}else{
				$nilai = "D";
			}
		} else if ($kkm=="65") {
			$a = $a_iv;
			$b = $b_iv;
			$c = $c_iv;
			$d = $d_iv;

			if(in_array($id, $a)){
				$nilai = "A";
			}elseif(in_array($id, $b)){
				$nilai = "B";
			}elseif(in_array($id, $c)){
				$nilai = "C";
			}elseif(in_array($id, $d)){
				$nilai = "D";
			}else{
				$nilai = "D";
			}
		}



		

		
		

		return $nilai;
	}

	public function nilaiHuruf($id){
		
		switch ($id) {
			case 99:
				$huruf = "SB";
				break;

			case 95:
				$huruf = "A-";
				break;

			case 90:
				$huruf = "B+";
				break;
				
			case 85:
				$huruf = "B";
				break;
				
			case 80:
				$huruf = "B-";
				break;

			case 75:
				$huruf = "C+";
				break;
				
			case 70:
				$huruf = "C";
				break;
				
			case 65:
				$huruf = "C-";
				break;

			case 60:
				$huruf = "D+";
				break;	

			case 55:
				$huruf = "D";
				break;

			case 50:
				$huruf = "E";
				break;	

			case 25:
				$huruf = "B";
				break;										
			
			default:
				$huruf = "B";
				break;
		}

		return $huruf;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Clases the static model class
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
	}
}
