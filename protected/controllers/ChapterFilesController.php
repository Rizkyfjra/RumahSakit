<?php

class ChapterFilesController extends Controller
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
		$cekFitur = Option::model()->findAll(array('condition'=>'key_config LIKE "%fitur_materi%"'));
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
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('download','index','view'),
				//'users'=>array('@'),
				'expression'=>"(!Yii::app()->user->isGuest && $status == 1)",
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update','download'),
				'expression'=>"(Yii::app()->user->YiiTeacher && $status == 1)",
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'expression'=>"(Yii::app()->user->YiiAdmin && $status == 1)",
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
	public function actionView($id,$tipe)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
			'tipe'=>$tipe,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new ChapterFiles;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['ChapterFiles']))
		{
			$model->attributes=$_POST['ChapterFiles'];
			if($model->save())
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

		if(isset($_POST['ChapterFiles']))
		{
			$model->attributes=$_POST['ChapterFiles'];
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
		$dataProvider=new CActiveDataProvider('ChapterFiles');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new ChapterFiles('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['ChapterFiles']))
			$model->attributes=$_GET['ChapterFiles'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	public function actionDownload($id){
		$cekFile = ChapterFiles::model()->findByPk($id);
		if(!empty($cekFile)){
			$name = $cekFile->file;
			$lesson_id = $cekFile->idChapter->id_lesson;
		}else{
			$name = NULL;
			$lesson_id = NULL;
		}

		//$dir_path = Yii::getPathOfAlias('webroot') . '/images/resums/';

		$fileName = Yii::app()->basePath.'/../images/chapters/'.$lesson_id.'/'.$name;

		if (file_exists($fileName)){
		    return Yii::app()->getRequest()->sendFile($name, @file_get_contents($fileName));
		}else{
		   $fileName = Yii::app()->basePath.'/../images/upload/'.$name;
		   return Yii::app()->getRequest()->sendFile($name, @file_get_contents($fileName));
		   // throw new CHttpException(404, 'The requested page does not exist.');
		}

	}
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return ChapterFiles the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=ChapterFiles::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param ChapterFiles $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='chapter-files-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
