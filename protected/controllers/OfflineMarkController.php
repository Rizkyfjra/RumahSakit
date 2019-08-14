<?php

class OfflineMarkController extends Controller
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
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		$cekFitur = Option::model()->findAll(array('condition'=>'key_config LIKE "%fitur_tugas%"'));
		$status = 1;

		if(!empty($cekFitur)){
			if($cekFitur[0]->value == 2){
				$status = 2;
			}
		}

		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('suggestSiswa'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('index','view'),
				'users'=>array('@'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'expression' => "(Yii::app()->user->YiiTeacher && $status == 1)",
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'expression' => "(Yii::app()->user->YiiKepsek  && $status == 1)",
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('create','update','admin','delete'),
				'expression' => "(Yii::app()->user->YiiAdmin  && $status == 1)",
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
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate($idl=null)
	{
		$model=new OfflineMark;
		//$assignment=new Assignment;
		if(!empty($idl)){
			$mp=Lesson::model()->findByPk($idl);
			$kls=Clases::model()->findByPk($mp->class_id);
		}
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['OfflineMark']))
		{
			$model->attributes=$_POST['OfflineMark'];
			//$assignment->attributes=$_POST['Assignment'];
			if (!empty($model->student_id)) {
				list($a, $b) = explode(":", $model->student_id);
				$student_id = str_replace(")","",$b);
				$model->student_id = $student_id;
			}

			$model->lesson_id=$idl;
			$uploadedFile = CUploadedFile::getInstance($model, 'file');
			$model->file = $uploadedFile;
			$model->sync_status=2;

			if($model->save()){
				if(!file_exists(Yii::app()->basePath.'/../images/offline_mark/'.$kls->id)){
					mkdir(Yii::app()->basePath.'/../images/offline_mark/'.$kls->id.'/'.$idl,0777,true);
				}

				if(!empty($uploadedFile)){
					$uploadedFile->saveAs(Yii::app()->basePath.'/../images/offline_mark/'.$$kls->id.'/'.$idl.'/'.$uploadedFile);
				}
				Yii::app()->user->setFlash('success','Input Nilai Offline Berhasil !');
				$this->redirect(array('lesson/rekapNilai','id'=>$idl));
			}
			Yii::app()->user->setFlash('error','Input Nilai Offline Gagal !');
		}

		$this->render('create',array(
			'model'=>$model,
			'idl'=>$idl,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id,$idl=null)
	{
		$model=$this->loadModel($id);
		$old_file=$model->file;
		if(!empty($idl)){
			$mp=Lesson::model()->findByPk($idl);
			$kls=Clases::model()->findByPk($mp->class_id);
		}
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['OfflineMark']))
		{
			$model->attributes=$_POST['OfflineMark'];
			$model->lesson_id=$idl;

			if (!empty($model->student_id)) {
				list($a, $b) = explode(":", $model->student_id);
				$student_id = str_replace(")","",$b);
				$model->student_id = $student_id;
			}

			$uploadedFile = CUploadedFile::getInstance($model, 'file');
			if(!empty($uploadedFile)){
				$model->file = $uploadedFile;
			}else{
				$model->file = $old_file;
			}

			if($model->save())
				if(!file_exists(Yii::app()->basePath.'/../images/offline_mark/'.$kls->id)){
					mkdir(Yii::app()->basePath.'/../images/offline_mark/'.$kls->id.'/'.$idl,0777,true);
				}

				if(!empty($uploadedFile)){
					$uploadedFile->saveAs(Yii::app()->basePath.'/../images/offline_mark/'.$$kls->id.'/'.$idl.'/'.$uploadedFile);
				}
				$this->redirect(array('lesson/rekapNilai','id'=>$idl));
				//$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
			'idl'=>$idl,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('OfflineMark');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new OfflineMark('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['OfflineMark']))
			$model->attributes=$_GET['OfflineMark'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	public function actionSuggestSiswa(){
		$request=trim($_GET['term']);
		$mp=$_GET['idl'];
		$mapel=Lesson::model()->findByPk($mp);
	    $kelas=Clases::model()->findByPk($mapel->class_id);
	    $nk=$kelas->id;
		//echo $request;
		//echo $nk;
	    if($request!=''){

	    	if (Yii::app()->user->YiiTeacher || Yii::app()->user->YiiKepsek){
		    	$current_user = Yii::app()->user->id;
		    	$term_conditon = " AND class_id = $nk";
		    }else {
		    	//$term_conditon = " AND class_id = $nk";
		    }
	        $model=User::model()->findAll(array("condition"=>"lower(display_name) like lower('$request%') $term_conditon"));
	        $data=array();
	        foreach($model as $get){

	        	$data[]=$get->display_name.' (ID:'.$get->id.')';

	        }
	        $this->layout='empty';
	        echo json_encode($data);
	    }
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return OfflineMark the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=OfflineMark::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param OfflineMark $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='offline-mark-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
