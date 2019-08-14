<?php

class UserProfileScoreController extends Controller
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
				'actions'=>array(),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('view','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('update','view'),
				'expression' => 'Yii::app()->user->YiiAdmin',
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
		$model_user=User::model()->findByPk($id);

		if ($model_user->trash != NULL){
			Yii::app()->user->setFlash('error', "Maaf user sudah dihapus dari sistem!");
			$this->redirect(array('site/index'));
			exit();
		}

		if (Yii::app()->user->YiiStudent and Yii::app()->user->id != $model_user->id){
			Yii::app()->user->setFlash('error', "Maaf anda tidak punya izin akses!");
			$this->redirect(array('site/index'));
			exit();
		}

		$profil_score=UserProfileScore::model()->findByAttributes(array('user_id'=>$id));
		if(!empty($profil_score)){
			$model=$this->loadModel($profil_score->id);

			$this->render('view',array(
				'model'=>$this->loadModel($profil_score->id),
				'user_id'=>$id,
				'nama'=>$model_user->display_name,
			));
		}else{
			Yii::app()->user->setFlash('error', "Maaf profil masih kosong, silakan update terlebih dahulu!");
			$this->redirect(array('update','id'=>$id));
			exit();
		}
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	// public function actionCreate()
	// {
	// 	$model=new UserProfileScore;

	// 	// Uncomment the following line if AJAX validation is needed
	// 	// $this->performAjaxValidation($model);

	// 	if(isset($_POST['UserProfileScore']))
	// 	{
	// 		$model->attributes=$_POST['UserProfileScore'];
	// 		if($model->save())
	// 			$this->redirect(array('view','id'=>$model->id));
	// 	}

	// 	$this->render('create',array(
	// 		'model'=>$model,
	// 	));
	// }

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model_user=User::model()->findByPk($id);

		if ($model_user->trash != NULL){
			Yii::app()->user->setFlash('error', "Maaf user sudah dihapus dari sistem!");
			$this->redirect(array('site/index'));
			exit();
		}

		if (Yii::app()->user->YiiStudent and Yii::app()->user->id != $model_user->id){
			Yii::app()->user->setFlash('error', "Maaf anda tidak punya izin akses!");
			$this->redirect(array('site/index'));
			exit();
		}

		$profil_score=UserProfileScore::model()->findByAttributes(array('user_id'=>$id));
		if(!empty($profil_score)){
			$model=$this->loadModel($profil_score->id);
			$model->sync_status=2;
		}else{
			$model=new UserProfileScore;
		}

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['UserProfileScore']))
		{
			$model->attributes=$_POST['UserProfileScore'];
			$model->user_id=$id;

			if($model->save())
				$this->redirect(array('view','id'=>$model->user_id));
		}

		$this->render('update',array(
			'model'=>$model,
			'user_id'=>$id,
			'nama'=>$model_user->display_name,
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
	// public function actionIndex()
	// {
	// 	$dataProvider=new CActiveDataProvider('UserProfileScore');
	// 	$this->render('index',array(
	// 		'dataProvider'=>$dataProvider,
	// 	));
	// }

	/**
	 * Manages all models.
	 */
	// public function actionAdmin()
	// {
	// 	$model=new UserProfileScore('search');
	// 	$model->unsetAttributes();  // clear any default values
	// 	if(isset($_GET['UserProfileScore']))
	// 		$model->attributes=$_GET['UserProfileScore'];

	// 	$this->render('admin',array(
	// 		'model'=>$model,
	// 	));
	// }

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return UserProfileScore the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=UserProfileScore::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param UserProfileScore $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='user-profile-score-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
