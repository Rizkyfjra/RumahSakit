<?php

class AnnouncementsController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column1';

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
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('create','update','admin','delete','hapus'),
				'expression' => 'Yii::app()->user->YiiAdmin',
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('create','update','admin','delete'),
				'expression' => 'Yii::app()->user->YiiKepsek',
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
	public function actionCreate()
	{
		$model=new Announcements;
		$activity = new Activities;
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Announcements']))
		{
			$model->attributes = $_POST['Announcements'];
			$model->author_id = Yii::app()->user->id;
			if($model->save()){

				/*$notif->content= "Kepala Sekolah Menampilkan Pengumuman ".$model->title."";
				$notif->user_id=Yii::app()->user->id;
				//$notif->user_id_to=$model->teacher->id;
				$notif->relation_id=$model->id;
				$notif->tipe='announcement';
				$notif->save();*/

				$activity->activity_type='Buat Pengumuman Baru';
				$activity->content=$model->content;
				$activity->created_by=Yii::app()->user->id;
				$activity->save();

				Yii::app()->user->setFlash('success', 'Pengumuman Berhasil Dibuat!');
				$this->redirect(array('view','id'=>$model->id));
			}
			Yii::app()->user->setFlash('error', 'Pengumuman Gagal Dibuat!');
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
		$activity = new Activities;
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Announcements']))
		{
			$model->attributes=$_POST['Announcements'];
			$model->sync_status=2;
			if($model->save())
				$activity->activity_type='Edit Pengumuman '.$model->title;
				$activity->content=$model->content;
				$activity->created_by=$model->author_id;
				$activity->updated_by=Yii::app()->user->id;
				$activity->save();

				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}


	public function actionHapus($id){
		// $model=Lesson::model()->findByPk($id);

		// $model->trash = 1;
		// $model->user_id = "(ID:".$model->user_id.")";

		// if($model->save()){
		// 	 Yii::app()->user->setFlash('error','Pelajaran Berhasil Dihapus !');
		// 	 $this->redirect(array('index'));
		// }
		$this->loadModel($id)->delete();
		 Yii::app()->user->setFlash('error','Pengumuman Berhasil Dihapus !');
			 $this->redirect(array('/site/index'));

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
		$dataProvider=new CActiveDataProvider('Announcements');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Announcements('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Announcements']))
			$model->attributes=$_GET['Announcements'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Announcements the loaded model
	 * @throws CHttpException
	 */
	
	public function loadModel($id)
	{
		$model=Announcements::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Announcements $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='announcements-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
