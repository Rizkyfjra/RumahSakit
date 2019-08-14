<?php

class NotificationController extends Controller
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
			// array('allow',  // allow all users to perform 'index' and 'view' actions
			// 	'actions'=>array(,'view'),
			// 	'users'=>array('*'),
			// ),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('index','ajaxlist'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete','Gene','create','update','ajaxlist'),
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
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate($tipe=null)
	{
		$model=new Notification;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		// if(isset($_POST['Notification']))
		// {
		// 	$model->attributes=$_POST['Notification'];
		// 	if($model->save())
		// 		$this->redirect(array('view','id'=>$model->id));
		// }

		// $this->render('create',array(
		// 	'model'=>$model,
		// ));





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

		if(isset($_POST['Notification']))
		{
			$model->attributes=$_POST['Notification'];
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
		$current_user = Yii::app()->user->id;

		if (Yii::app()->user->YiiStudent) {
			$modelUser=User::model()->findByPk($current_user);
			$class_student_id = $modelUser->class_id;
			$term_conditon = "";
		} else {
			$term_conditon = "";
		}

		$dataProvider=new CActiveDataProvider('Notification', array(
				'criteria'=>array(
					'order'=>'t.created_at DESC',
					'condition'=>'user_id_to = '.Yii::app()->user->id.' '.$term_conditon,
				),
				'pagination'=>array(
		        	'pageSize'=>'15'
		    	),
			));

		Yii::app()->user->setState('__yiiCNotif',NULL);

		$this->render('v2/list',array(
			'dataProvider'=>$dataProvider,
		));
	}

	public function actionAjaxList()
	{
		$current_user = Yii::app()->user->id;

		if (Yii::app()->user->YiiStudent) {
			$modelUser=User::model()->findByPk($current_user);
			$class_student_id = $modelUser->class_id;
			$term_conditon = "";
		} else {
			$term_conditon = "";
		}

		$dataProvider=new CActiveDataProvider('Notification', array(
				'criteria'=>array(
					'order'=>'t.created_at DESC',
					'condition'=>'user_id_to = '.Yii::app()->user->id.' '.$term_conditon,
				),
				'pagination'=>array(
		        	'pageSize'=>'4'
		    	),
			));

		Yii::app()->user->setState('__yiiCNotif',NULL);

		$this->renderPartial('v2/ajax',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Notification('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Notification']))
			$model->attributes=$_GET['Notification'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Notification the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Notification::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Notification $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='notification-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function actionGene($user_id,$notif_id){

		$random_password = User::model()->generateRandomString(5);

		$modelUser = User::model()->findByPk($user_id);

		$modelUser->encrypted_password=$random_password;
		$modelUser->password2=$random_password;
		$modelUser->reset_password=$random_password;

		$modelUser->sync_status=NULL;
		$prefix = Yii::app()->params['tablePrefix'];
		if($modelUser->save()) {
			Yii::app()->user->setFlash('success','Reset Password Berhasil');

			$sql='UPDATE '.$prefix.'notification set sync_status = 2, status = "respon" where id = :id ';
	        $command=Yii::app()->db->createCommand($sql);
	        $command->bindParam(":id", $notif_id, PDO::PARAM_STR);
	        $command->execute();
		} else {
			Yii::app()->user->setFlash('error','Reset Password Gagal');
		}

		$this->redirect(array('index'));

	}
}
