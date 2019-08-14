<?php

class LessonKdController extends Controller
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
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
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
				if(Yii::app()->session['semester']){
					$optSemester = Yii::app()->session['semester'];
				} else {
					$optSemester=Option::model()->findByAttributes(array('key_config'=>'semester'))->value;
				}
					if(Yii::app()->session['tahun_ajaran']){
					$optTahunAjaran = Yii::app()->session['tahun_ajaran'];
				} else {
					$optTahunAjaran=Option::model()->findByAttributes(array('key_config'=>'tahun_ajaran'))->value;
				}

		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
				if(Yii::app()->session['semester']){
					$optSemester = Yii::app()->session['semester'];
				} else {
					$optSemester=Option::model()->findByAttributes(array('key_config'=>'semester'))->value;
				}
					if(Yii::app()->session['tahun_ajaran']){
					$optTahunAjaran = Yii::app()->session['tahun_ajaran'];
				} else {
					$optTahunAjaran=Option::model()->findByAttributes(array('key_config'=>'tahun_ajaran'))->value;
				}

		$model=new LessonKd;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['LessonKd']))
		{
			$model->attributes=$_POST['LessonKd'];
			$model->semester=$optSemester;
			$model->tahun_ajaran=$optTahunAjaran;

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
				if(Yii::app()->session['semester']){
					$optSemester = Yii::app()->session['semester'];
				} else {
					$optSemester=Option::model()->findByAttributes(array('key_config'=>'semester'))->value;
				}
					if(Yii::app()->session['tahun_ajaran']){
					$optTahunAjaran = Yii::app()->session['tahun_ajaran'];
				} else {
					$optTahunAjaran=Option::model()->findByAttributes(array('key_config'=>'tahun_ajaran'))->value;
				}

		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['LessonKd']))
		{
			$model->attributes=$_POST['LessonKd'];
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
				if(Yii::app()->session['semester']){
					$optSemester = Yii::app()->session['semester'];
				} else {
					$optSemester=Option::model()->findByAttributes(array('key_config'=>'semester'))->value;
				}
					if(Yii::app()->session['tahun_ajaran']){
					$optTahunAjaran = Yii::app()->session['tahun_ajaran'];
				} else {
					$optTahunAjaran=Option::model()->findByAttributes(array('key_config'=>'tahun_ajaran'))->value;
				}

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
				if(Yii::app()->session['semester']){
					$optSemester = Yii::app()->session['semester'];
				} else {
					$optSemester=Option::model()->findByAttributes(array('key_config'=>'semester'))->value;
				}
					if(Yii::app()->session['tahun_ajaran']){
					$optTahunAjaran = Yii::app()->session['tahun_ajaran'];
				} else {
					$optTahunAjaran=Option::model()->findByAttributes(array('key_config'=>'tahun_ajaran'))->value;
				}

		$dataProvider=new CActiveDataProvider('LessonKd');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
				if(Yii::app()->session['semester']){
					$optSemester = Yii::app()->session['semester'];
				} else {
					$optSemester=Option::model()->findByAttributes(array('key_config'=>'semester'))->value;
				}
					if(Yii::app()->session['tahun_ajaran']){
					$optTahunAjaran = Yii::app()->session['tahun_ajaran'];
				} else {
					$optTahunAjaran=Option::model()->findByAttributes(array('key_config'=>'tahun_ajaran'))->value;
				}

		$model=new LessonKd('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['LessonKd']))
			$model->attributes=$_GET['LessonKd'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return LessonKd the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
				if(Yii::app()->session['semester']){
					$optSemester = Yii::app()->session['semester'];
				} else {
					$optSemester=Option::model()->findByAttributes(array('key_config'=>'semester'))->value;
				}
					if(Yii::app()->session['tahun_ajaran']){
					$optTahunAjaran = Yii::app()->session['tahun_ajaran'];
				} else {
					$optTahunAjaran=Option::model()->findByAttributes(array('key_config'=>'tahun_ajaran'))->value;
				}

		$model=LessonKd::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param LessonKd $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
				if(Yii::app()->session['semester']){
					$optSemester = Yii::app()->session['semester'];
				} else {
					$optSemester=Option::model()->findByAttributes(array('key_config'=>'semester'))->value;
				}
					if(Yii::app()->session['tahun_ajaran']){
					$optTahunAjaran = Yii::app()->session['tahun_ajaran'];
				} else {
					$optTahunAjaran=Option::model()->findByAttributes(array('key_config'=>'tahun_ajaran'))->value;
				}

		if(isset($_POST['ajax']) && $_POST['ajax']==='lesson-kd-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
