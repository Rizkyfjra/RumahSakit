<?php

class StudentQuizController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			//'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		$cekFitur = Option::model()->findAll(array('condition'=>'key_config LIKE "%fitur_ulangan%"'));
		$status = 1;

		if(!empty($cekFitur)){
			if($cekFitur[0]->value == 2){
				$status = 2;
			}
		}

		return array(
			/*array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),*/
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
				//'users'=>array('@'),
				'expression' => "(!Yii::app()->user->isGuest && $status == 1)",
			),
			/*array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),*/
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('formatNilai','detail','nilai','delete'),
				'expression' => "(Yii::app()->user->YiiTeacher && $status == 1)",
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete','formatNilai','detail','nilai','download'),
				'expression' => "(Yii::app()->user->YiiAdmin && $status == 1)",
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$model = $this->loadModel($id);
		$cekQuiz = Quiz::model()->findByPk($model->quiz_id);
		if(empty($cekQuiz)){
			Yii::app()->user->setFlash('error','Ulangan Tidak Ditemukan !');
			$this->redirect(array('site/index'));
		}
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	public function actionDownload($id){
		$kuis = $this->loadModel($id);

	  	Yii::import('ext.phpexcel.XPHPExcel');
	    $objPHPExcel= XPHPExcel::createPHPExcel();
	      $objPHPExcel->getProperties()->setCreator("Admin")
	                             ->setLastModifiedBy("Admin")
	                             ->setTitle("Jawaban Kuis ")
	                             ->setSubject("")
	                             ->setDescription("")
	                             ->setKeywords("");

	 	$styleArray = array(
					    'font'  => array(
					        'bold'  => true,
					        'color' => array('rgb' => 'FF9900'),
					        'size'  => 11,
					        'name'  => 'Verdana'
					    ));
	 	$style = array(
	        'alignment' => array(
	            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
	        ),
	        'font'=>array(
	        	'bold'=>true,
	        	),
	        'borders' => array(
			    'allborders' => array(
			      'style' => PHPExcel_Style_Border::BORDER_THIN
			    )
			  )
	    );

	    $style1 = array(
	        'font'=>array(
	        	'bold'=>true,
	        	),
	         'borders' => array(
			    'allborders' => array(
			      'style' => PHPExcel_Style_Border::BORDER_THIN
			    )
			  )
	    );
	    $style3 = array(
	        'font'=>array(
	        	'bold'=>true,
	        	),
	    );

	     $style2 = array(
	         'borders' => array(
			    'allborders' => array(
			      'style' => PHPExcel_Style_Border::BORDER_THIN
			    )
			  )
	    );

	    $objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('C2','Daftar Jawaban ');
		/*$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('C3','Bandung 40194');
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('C4','Telp');
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('C5','Website');*/
		$objPHPExcel->getActiveSheet()->getStyle('C2')->applyFromArray($styleArray);

		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A7','Kuis : '.$kuis->quiz->title)
					->setCellValue('A8','Nama : '.$kuis->user->display_name)
		            ->mergeCells('A6:B6')
		            ->mergeCells('A7:B7')
		            ->mergeCells('A8:B8');
		$objPHPExcel->getActiveSheet()->getStyle('A6:B6')->applyFromArray($style3);
		$objPHPExcel->getActiveSheet()->getStyle('A7:B7')->applyFromArray($style3);
		$objPHPExcel->getActiveSheet()->getStyle('A8:B8')->applyFromArray($style3);

		// Add some data
		$objPHPExcel->setActiveSheetIndex(0)
		            ->setCellValue('A10', 'No Soal')
		            ->setCellValue('B10', 'Tipe Soal')
		            ->setCellValue('C10', 'Judul Soal')
		            ->setCellValue('D10', 'Jawaban Siswa')
		            // ->mergeCells('A10:A11')
		            // ->mergeCells('B10:B11')
		            // ->mergeCells('C10:C11')
		            // ->mergeCells('D10:D11')
		            // ->mergeCells('E10:E11')
		            // ->mergeCells('F10:F11')
		            // ->mergeCells('G10:G11')
		            //->mergeCells('E10:E11')
		            //->mergeCells('F10:F11')
		            ->getStyle('B10:B11')->applyFromArray($style);
		$objPHPExcel->getActiveSheet()->getStyle('C10:C11')->applyFromArray($style);
		$objPHPExcel->getActiveSheet()->getStyle('A10:A11')->applyFromArray($style);
		$objPHPExcel->getActiveSheet()->getStyle('D10:D11')->applyFromArray($style);
		$objPHPExcel->getActiveSheet()->getStyle('E10:E11')->applyFromArray($style);
		$objPHPExcel->getActiveSheet()->getStyle('F10:F11')->applyFromArray($style);

		$objPHPExcel->getActiveSheet()
				    ->getColumnDimension('B')
				    ->setAutoSize(true);

		$objPHPExcel->getActiveSheet()
				    ->getColumnDimension('C')
				    ->setAutoSize(true);

		$objPHPExcel->getActiveSheet()
				    ->getColumnDimension('D')
				    ->setAutoSize(true);

		$objPHPExcel->getActiveSheet()
				    ->getColumnDimension('E')
				    ->setAutoSize(true);

		$objPHPExcel->getActiveSheet()
				    ->getColumnDimension('F')
				    ->setAutoSize(true);

		$huruf = range('D', 'Z');
		$no=12;
		$counter=1;
		$cell=0;
		$jawaban=json_decode($kuis->student_answer,true);
		foreach ($jawaban as $key => $value) {
		$soal = Questions::model()->findByPk($key);
			if($soal->type == 1){
                        $tipe_soal = "Isian";
                      }elseif ($soal->type ==2) {
                        $tipe_soal = "Esai";
                      }else{
                        $tipe_soal = "Pilihan Ganda";
                      }

			$objPHPExcel->setActiveSheetIndex(0)
			            ->setCellValue('A'.$no.'', ''.$counter.'')
			            ->setCellValue('B'.$no.'', ''.$tipe_soal.'')
			            ->setCellValue('C'.$no.'', ''.$soal->title.'');



			    $objPHPExcel->setActiveSheetIndex(0)
			            ->setCellValue('D'.$no.'', ''.$value.'');

			$no++;
			$counter++;
			$cell++;

			}



		//$objPHPExcel->getActiveSheet()->getStyle('A12:'.$next[10].$no++)->applyFromArray($style2);
		// Rename worksheet
		$objPHPExcel->getActiveSheet()->setTitle('Rekap Nilai');


		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);


		// Redirect output to a clientâ€™s web browser (Excel5)
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="Rekap Jawaban '.$kuis->user->display_name.'.xls"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');

		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		Yii::app()->end();
	}

	public function actionDetail($id,$soal)
	{
		$soal = Questions::model()->findByPk($soal);
		$this->render('detail',array(
			'model'=>$this->loadModel($id),
			'soal'=>$soal,
		));
	}

	public function actionNilai(){
		if(isset($_POST['nilai'])){
			$sc = $_POST['sc'];
			$model = $this->loadModel($sc);
			$nilai = $_POST['nilai'];
			$totNilai = 0;
			if(!empty($nilai)){
				foreach ($nilai as $value) {
					$totNilai = $totNilai+$value;
				}
			}
			if($totNilai > 100){
				Yii::app()->user->setFlash('error','Nilai Total Esai Lebih Dari 100 Silahkan Periksa Kembali!');
				$this->redirect(Yii::app()->request->urlReferrer);
			}

			$model->essay_score = $totNilai;

			if($model->essay_score > 100){
				Yii::app()->user->setFlash('error','Nilai Melebihi 100 Silahkan Periksa Kembali!');
				$this->redirect(Yii::app()->request->urlReferrer);
			}

			if($model->save()){
				Yii::app()->user->setFlash('success','Nilai Berhasil Ditambahkan!');
				$this->redirect(array('view','id'=>$model->id));
			}
		}
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new StudentQuiz;
		$activity = new Activities;
		$usr=User::model()->findByPk(Yii::app()->user->id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['StudentQuiz']))
		{
			$model->attributes=$_POST['StudentQuiz'];
			if($model->save())
				$activity->activity_type='Mengerjakan Kuis';
				$activity->content='Siswa'.$usr->display_name.' Mengerjakan Kuis';
				$activity->created_by=Yii::app()->user->id;
				$activity->save();

				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['StudentQuiz']))
		{
			$model->attributes=$_POST['StudentQuiz'];
			$model->sync_status=2;
						
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		//echo "fasdasdf";
		$this->loadModel($id)->delete();
		if (!empty($_GET['id_quiz'])) {
			$this->redirect(array('quiz/'.$_GET['id_quiz']));
		} else {
			$this->redirect(array('quiz/list'));
		}	
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		if(Yii::app()->user->YiiStudent){
			$term='student_id = '.Yii::app()->user->id;
		}elseif(Yii::app()->user->YiiTeacher){
			$term='q.created_by = '.Yii::app()->user->id;
		}else{
			$term='';
		}
		$prefix = Yii::app()->params['tablePrefix'];
		if(!Yii::app()->user->YiiTeacher){
			$dataProvider=new CActiveDataProvider('StudentQuiz',array(
				'criteria'=>array(
					'condition'=>$term),
				'pagination'=>array('pageSize'=>100)
				));
		}else{
			$dataProvider=new CActiveDataProvider('StudentQuiz',array(
				'criteria'=>array(
					'select'=>array('t.*'),
					'join'=>'JOIN '.$prefix.'quiz AS q ON q.id = t.quiz_id',
					'condition'=>$term),
				'pagination'=>array('pageSize'=>100)
				));
		}

		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	public function actionFormatNilai(){
		if(isset($_POST['nilai'])){
			$nilais = $_POST['nilai'];
			//echo "<pre>";
			$no=0;
			$total=0;
			foreach ($nilais as $value) {
				$cekNilai = StudentQuiz::model()->findByPk($value);
				if(!empty($cekNilai)){
					$new=array();
					if(!empty($cekNilai->student_answer)){
						if(@unserialize($cekNilai->student_answer)){
							$jwb=unserialize($cekNilai->student_answer);
							foreach ($jwb as $key => $value) {
								$new[$key]=$value;
							}
							$cekNilai->student_answer = json_encode($new);
						}

					}

					if($cekNilai->save()){
						$no++;
					}

					$total++;
				}
			}
			Yii::app()->user->setFlash('success','Update '.$no.' Dari '.$total.' Nilai Ulangan Siswa Berhasil');
			$this->redirect(array('index'));
			//echo "</pre>";
		}
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new StudentQuiz('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['StudentQuiz']))
			$model->attributes=$_GET['StudentQuiz'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return StudentQuiz the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=StudentQuiz::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param StudentQuiz $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='student-quiz-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
