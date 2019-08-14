<?php

class SkillController extends Controller
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
			/*array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),*/
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('index','view','create','update','addMark','hapus'),
				'expression' => "(Yii::app()->user->YiiTeacher && $status == 1)",
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('index','view','create','update','addMark','admin','delete','hapus'),
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

		if($model->trash == 1){
			throw new CHttpException(404, 'The requested page does not exist.');
		}

		$mapel=Lesson::model()->findByAttributes(array('id'=>$model->lesson_id));
		$kelas=ClassDetail::model()->findByAttributes(array('id'=>$mapel->class_id));

		if($model->lesson->moving_class == 1){
			$cekUser = LessonMc::model()->findAll(array('condition'=>'lesson_id = '.$model->lesson->id));
			$user_ids = array();
			$list_user_id = NULL;
			if(!empty($cekUser)){
				foreach ($cekUser as $cekUsr) {
					array_push($user_ids, $cekUsr->user_id);
				}
				$list_user_id = implode(',', $user_ids);
			}

			$siswa = User::model()->findAll(array('condition'=>'id IN ('.$list_user_id.') and trash is null'));
		}else{
			$siswa = User::model()->findAll(array('condition'=>'class_id = '.$kelas->id.' and trash is null'));
		}

		$this->render('view',array(
			'model'=>$this->loadModel($id),
			'siswa'=>$siswa
		));
	}

	public function actionAddMark($id){
		$mark = new StudentSkill;
		$nilai = $_POST['score'];
		$siswa = $_POST['student_id'];
		$aid = $_POST['skill_id'];
		$lid = $_POST['lesson_id'];
		$result = array_combine($siswa, $nilai);
		$current_user = Yii::app()->user->id;
		$prefix = Yii::app()->params['tablePrefix'];
		$ida=41;
		$mt=1;
		$dt=0;
		//echo "<pre>";
		foreach ($result as $key => $value) {
			if(!empty($value)){
				$cek=StudentSkill::model()->findByAttributes(array('student_id'=>$key,'skill_id'=>$aid));
				if(!empty($cek)){
					$sql="UPDATE ".$prefix."student_skill SET sync_status = 2, score = :score, updated_at = NOW(), updated_by = :updated_by WHERE student_id = :sid AND skill_id = :aid";
					$command=Yii::app()->db->createCommand($sql);
					$command->bindParam(":aid",$aid,PDO::PARAM_STR);
					$command->bindParam(":sid",$key,PDO::PARAM_STR);
					//$command->bindParam(":updated_by",$current_user,PDO::PARAM_STR);
					$command->bindParam(":score",$value,PDO::PARAM_STR);
					if($command->execute()){
						$dt++;
					}
				}else{
					$sql="INSERT INTO ".$prefix."student_skill (skill_id, student_id, score) VALUES(:aid,:sid,:score)";
					$command=Yii::app()->db->createCommand($sql);
					//$command->bindParam(":lid",$lid,PDO::PARAM_STR);
					$command->bindParam(":aid",$aid,PDO::PARAM_STR);
					$command->bindParam(":sid",$key,PDO::PARAM_STR);
					$command->bindParam(":score",$value,PDO::PARAM_STR);
					if($command->execute()){
						$dt++;
					}
				}
			}

		}
		//print_r($result);
		//print_r($siswa);
		//echo count($result);
		//echo "</pre>";
		Yii::app()->user->setFlash('success', 'Input Nilai '.$dt.' Siswa Berhasil !');
		$this->redirect(array('view','id'=>$id));
	}

	public function actionHapus($id){
		$model = $this->loadModel($id);

		$model->trash = 1;
		$model->sync_status = 2;

		if($model->save()){
			Yii::app()->user->setFlash('error','Data Berhasil Dihapus!');
			$this->redirect(array('/lesson/view','id'=>$model->lesson_id));
		}
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate($lesson_id=null)
	{
		$model=new Skill;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Skill']))
		{
			$model->attributes=$_POST['Skill'];
			if(!empty($lesson_id)){
				$model->lesson_id = $lesson_id;
			}
			$model->teacher_id = Yii::app()->user->id;

			if($model->save()){
				$this->redirect(array('view','id'=>$model->id));
			}
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

		if($model->trash == 1){
			throw new CHttpException(404, 'The requested page does not exist.');
		}
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Skill']))
		{
			$model->attributes=$_POST['Skill'];
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
		$dataProvider=new CActiveDataProvider('Skill',array(
			'criteria'=>array(
				'condition'=>'t.trash is null and teacher_id = '.Yii::app()->user->id)
			));
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Skill('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Skill']))
			$model->attributes=$_GET['Skill'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Skill the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Skill::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Skill $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='skill-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
