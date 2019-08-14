<?php

class CutiController extends Controller
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
				'actions'=>array('create','form','datacuti','update','updatenolak','admin','delete','hapus'),
				'expression' => 'Yii::app()->user->YiiAdmin',
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('create','datacuti','update','updatenolak','admin','delete'),
				'expression' => 'Yii::app()->user->YiiKepsek',
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

    public function actionform()
    {
        $modelCuti = new Cuti;
        if(isset($_POST['Cuti'])){
            $modelCuti->id = $_POST['Cuti']['id'];
            $modelCuti->nama = $_POST['Cuti']['nama'];
            $modelCuti->mulai_cuti = $_POST['Cuti']['mulai_cuti'];
			$modelCuti->akhir_cuti = $_POST['Cuti']['akhir_cuti'];
			$modelCuti->type = $_POST['Cuti']['type'];
            $modelCuti->status = 'Belum Di Konfirmasi';
            if($modelCuti->save()){
				Yii::app()->user->setFlash('success','Anda Telah Mengajukan Cuti !');
				$this->redirect(array('/cuti/form'));
			}

        }
		$this->render('form', array('model' =>$modelCuti));
	}

    public function actiondatacuti()
    {
        $modelCuti = Cuti::model()->findAll();

		// $this->render('datacuti', array('model' =>$modelCuti));

		$criteria=new CDbCriteria();
		$count=Cuti::model()->count($criteria);
		$pages=new CPagination($count);
		// results per page
		$pages->pageSize=5;

		$this->render('datacuti', array(
		'model' => $modelCuti,
		'pages' => $pages
		));

	}

	public function actionUpdate($id)
	{
		$modelCuti=$this->loadModel($id);
		$activity = new Activities;
		// $this->performAjaxValidation($model);
		$modelCuti = Cuti::model()->findByPk($id);
			if(isset($_POST['Cuti'])){
				$modelCuti->status = 'Di Setujui';
				if($modelCuti->save()){
					Yii::app()->user->setFlash('success','Anda Telah Mengkonfirmasi Cuti !');
					$this->redirect(array('/cuti/datacuti'));
				}
			}

		$this->render('update',array('model'=>$modelCuti,));
	}

	public function actionUpdatenolak($id)
	{
		$modelCuti=$this->loadModel($id);
		$activity = new Activities;
		// $this->performAjaxValidation($model);
		$modelCuti = Cuti::model()->findByPk($id);
			if(isset($_POST['Cuti'])){
				$modelCuti->status = 'Tidak Di Setujui';
				if($modelCuti->save()){
					Yii::app()->user->setFlash('success','Anda Telah Menolak Pengajuan Cuti !');
					$this->redirect(array('/cuti/datacuti'));
				}
			}

			$this->render('update', array('model' =>$modelCuti));
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
		Yii::app()->user->setFlash('error','Data Cuti Dihapus !');
			$this->redirect(array('/cuti/datacuti'));

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
		$dataProvider=new CActiveDataProvider('Cuti');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	public function actionAdmin()
	{
		$model=new Cuti('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Cuti']))
			$model->attributes=$_GET['Cuti'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Cuti the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Cuti::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='datacuti-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
