<?php

class LksController extends Controller
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
				'users'=>array('@'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update','bulkMateri','bulkTugas','bulkUlangan'),
				'expression' => 'Yii::app()->user->YiiTeacher',
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('create','update','admin','delete','bulkMateri','bulkTugas','bulkUlangan'),
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
		$model=$this->loadModel($id);
		$materis='';
		$tugases='';
		$ulangans='';

		if(!empty($model->chapters)){
			$materis=$model->chapters;
			$materi=Chapters::model()->findAll(array('condition'=>'id_lesson = '.$model->lesson_id.' and id not in ('.$materis.')'));
		}else{
			$materi=Chapters::model()->findAll(array('condition'=>'id_lesson = '.$model->lesson_id));
		}

		if(!empty($model->assignments)){
			$tugases=$model->assignments;
			$tugas=Assignment::model()->findAll(array('condition'=>'lesson_id = '.$model->lesson_id.' and id not in ('.$tugases.')'));
		}else{
			$tugas=Assignment::model()->findAll(array('condition'=>'lesson_id = '.$model->lesson_id));
		}

		if(!empty($model->quizes)){
			$ulangans=$model->quizes;

			if(!Yii::app()->user->YiiStudent){
				$ulangan=Quiz::model()->findAll(array('condition'=>'lesson_id = '.$model->lesson_id.' and id not in ('.$ulangans.')'));
			}else{
				$ulangan=Quiz::model()->findAll(array('condition'=>'lesson_id = '.$model->lesson_id.' and id not in ('.$ulangans.') and status = 1'));
			}
		}else{
			if(!Yii::app()->user->YiiStudent){
				$ulangan=Quiz::model()->findAll(array('condition'=>'lesson_id = '.$model->lesson_id));
			}else{
				$ulangan=Quiz::model()->findAll(array('condition'=>'lesson_id = '.$model->lesson_id.' and status = 1'));
			}

		}

		$this->render('view',array(
			'model'=>$this->loadModel($id),
			'materi'=>$materi,
			'tugas'=>$tugas,
			'ulangan'=>$ulangan
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Lks;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Lks']))
		{
			$model->attributes=$_POST['Lks'];

			if(Yii::app()->user->YiiTeacher){
				$model->teacher_id = Yii::app()->user->id;
			}else{
				if (!empty($model->teacher_id)) {
					list($a, $b) = explode(":", $model->teacher_id);
					$user_id = str_replace(")","",$b);
					$model->teacher_id = $user_id;
				}
			}

			/*echo "<pre>";
			print_r($model);
			echo "</pre>";*/
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

		if(isset($_POST['Lks']))
		{
			$model->attributes=$_POST['Lks'];
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
	public function actionIndex($id_lesson=null)
	{
		$term='';

		if(!empty($id_lesson)){
			if(Yii::app()->user->YiiTeacher){
				$term=' lesson_id = '.$id_lesson.' and teacher_id = '.Yii::app()->user->id;
			}else{
				$term=' lesson_id = '.$id_lesson;
			}

		}

		$dataProvider=new CActiveDataProvider('Lks',array(
			'criteria'=>array(
				'condition'=>$term,
				)
			));

		// $this->render('index',array(
		// 	'dataProvider'=>$dataProvider,
		// ));
	}

	public function actionBulkMateri(){
		if(isset($_POST['materi'])){
			$lks = Lks::model()->findByPk($_POST['lks']);

			$materi = $_POST['materi'];
			$count_id = count($materi);

			//if(!empty($sola)){
				$data = implode(',', $materi);
			//}else{
			//	$data = null;
			//	$count_id = null;
			//}

			if(empty($_POST['materi'])){
				Yii::app()->user->setFlash('danger','Harap Checklist Materi !');
			}

			if(!empty($lks->chapters)){
				$old_materi = explode(',', $lks->chapters);
				$result = array_merge($old_materi,$materi);
				$lks->chapters=implode(',', $result);
				$total = count($result);
				//$lks->total_question=$total;
			}else{
				$lks->chapters=$data;
				//$lks->total_question=$count_id;
			}

			$lks->sync_status=NULL;
			if($lks->save()){
				Yii::app()->user->setFlash('success',''.$count_id.' Materi Berhasil Ditambahkan !');
				$this->redirect(array('/lks/view','id'=>$lks->id));
			}

			$this->redirect(array('/lks/view','id'=>$lks->id));
			/*echo "<pre>";
			print_r($data);
			echo $count_id;
			print_r($quiz);
			echo "</pre>";*/

		}
	}

	public function actionBulkTugas(){
		if(isset($_POST['tugas'])){
			$lks = Lks::model()->findByPk($_POST['lks']);

			$tugas = $_POST['tugas'];
			$count_id = count($tugas);

			//if(!empty($sola)){
				$data = implode(',', $tugas);
			//}else{
			//	$data = null;
			//	$count_id = null;
			//}

			if(empty($_POST['tugas'])){
				Yii::app()->user->setFlash('danger','Harap Checklist Tugas !');
			}

			if(!empty($lks->assignments)){
				$old_tugas = explode(',', $lks->assignments);
				$result = array_merge($old_tugas,$tugas);
				$lks->assignments=implode(',', $result);
				$total = count($result);
				//$lks->total_question=$total;
			}else{
				$lks->assignments=$data;
				//$lks->total_question=$count_id;
			}

			$lks->sync_status=NULL;
			if($lks->save()){
				Yii::app()->user->setFlash('success',''.$count_id.' Tugas Berhasil Ditambahkan !');
				$this->redirect(array('/lks/view','id'=>$lks->id));
			}

			$this->redirect(array('/lks/view','id'=>$lks->id));
			/*echo "<pre>";
			print_r($data);
			echo $count_id;
			print_r($quiz);
			echo "</pre>";*/

		}
	}

	public function actionBulkUlangan(){
		if(isset($_POST['ulangan'])){
			$lks = Lks::model()->findByPk($_POST['lks']);

			$ulangan = $_POST['ulangan'];
			$count_id = count($ulangan);

			//if(!empty($sola)){
				$data = implode(',', $ulangan);
			//}else{
			//	$data = null;
			//	$count_id = null;
			//}

			if(empty($_POST['Ulangan'])){
				Yii::app()->user->setFlash('danger','Harap Checklist Ulangan !');
			}

			if(!empty($lks->quizes)){
				$old_ulangan = explode(',', $lks->quizes);
				$result = array_merge($old_ulangan,$ulangan);
				$lks->quizes=implode(',', $result);
				$total = count($result);
				//$lks->total_question=$total;
			}else{
				$lks->quizes=$data;
				//$lks->total_question=$count_id;
			}

			$lks->sync_status=NULL;
			if($lks->save()){
				Yii::app()->user->setFlash('success',''.$count_id.' Ulangan Berhasil Ditambahkan !');
				$this->redirect(array('/lks/view','id'=>$lks->id));
			}

			$this->redirect(array('/lks/view','id'=>$lks->id));
			/*echo "<pre>";
			print_r($data);
			echo $count_id;
			print_r($quiz);
			echo "</pre>";*/

		}
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Lks('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Lks']))
			$model->attributes=$_GET['Lks'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Lks the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Lks::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Lks $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='lks-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
