<?php

class ChaptersController extends Controller
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
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
				//'users'=>array('@'),
				'expression'=>"(!Yii::app()->user->isGuest && $status == 1)",
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update','hapus','copy'),
				'expression'=>"(Yii::app()->user->YiiTeacher && $status == 1)",
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete','copy','create','update','hapus'),
				'expression'=>"(Yii::app()->user->YiiAdmin && $status == 1)",
			),
			array('deny',  // deny all users
				'users'=>array('*'),
				// 'actions'=>array('*'),
				// 'expression'=>"{$status} == {2}",
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

		$model=$this->loadModel($id);
		if($model->trash == 1){
			throw new CHttpException(404, 'The requested page does not exist.');
		}

		$chapterFiles = ChapterFiles::model()->findAll(array("condition"=>"id_chapter = $id"));
		/*$chapterFiles=new CActiveDataProvider('ChapterFiles',array(
			'criteria'=>array(
				'select'=>'t.id, t.file, t.id_chapter',
				'join'=>'JOIN chapters AS c ON c.id = t.id_chapter JOIN lesson AS l ON l.id = c.id_lesson',
				'condition'=>'l.id = t.id'
				),
			));*/
		$this->render('v2/view',array(
			'model'=>$this->loadModel($id),
			'files'=>$chapterFiles,
		));
	}

	public function actionHapus($id)
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
		$model->trash = 1;
		$model->sync_status = 2;

		if($model->save()){
			Yii::app()->user->setFlash('error','Materi Berhasil Dihapus !');
			if(!empty(Yii::app()->session['returnURL'])){
				$this->redirect(Yii::app()->session['returnURL']);
				Yii::app()->session->remove('returnURL');
			}else{
				$this->redirect(Yii::app()->request->urlReferrer);
			}
		}
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate($lesson_id=null)
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

		$model=new Chapters;
		$model2=new ChapterFiles;

		$activity=new Activities;
		/*$lessons=new CActiveDataProvider('Lesson',array(
			'criteria'=>array(
				'select'=>'t.*, c.name as class_name',
				//'join'=>'JOIN class AS c ON c.id = t.class_id',
				'condition'=>'user_id = '.Yii::app()->user->id.''),
			));*/
		if(Yii::app()->user->YiiTeacher){
			$lessons = Lesson::model()->findAll(array('condition'=>'user_id = '.Yii::app()->user->id));
		} else {
			$lessons = Lesson::model()->findAll();
		}
		
		$curr_user=Yii::app()->user->id;
		$user_detail=User::model()->findByPk($curr_user);

		if(!empty($user_detail)){
			$name=$user_detail->display_name;
			$kelas=$user_detail->class_id;
		}else{
			$name=NULL;
			$kelas=NULL;
		}
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Chapters']) OR isset($_POST['ChapterFiles']) OR isset($_POST['lks_id']))
		{

			 $_POST['Chapters']['content'] = str_replace("[math]","$$",$_POST['Chapters']['content']);
			 $_POST['Chapters']['content'] = str_replace("[/math]","$$",$_POST['Chapters']['content']);


			$model->attributes=$_POST['Chapters'];
			$model2->attributes=$_POST['ChapterFiles'];
			$model->created_by=Yii::app()->user->id;
			if(!empty($lesson_id)){
				$model->id_lesson = $lesson_id;
			}


			if($model->chapter_type == 1){
				$model2->scenario = 'video';
			}elseif($model->chapter_type == 2){
				$model2->scenario = 'gambar';
			}elseif($model->chapter_type == 3){
				$model2->scenario = 'dokumen';
			}


			/*$lesson=$model->id_lesson;

			if (!empty($lesson)) {
				list($a, $b) = explode(":", $lesson);
				$lesson = str_replace(")","",$b);
				$model->id_lesson = $lesson;
			}*/
			$model->year = $optTahunAjaran;
			$model->semester = $optSemester;

			$lks_id = $_POST['lks_id'];
			if(!empty($lks_id)){
				$lks = Lks::model()->findByPk($lks_id);
			}

			if($model2->validate()){


				if($model->save()){

						$model2->id_chapter=$model->id;
						$uploadedFile = CUploadedFile::getInstance($model2, 'file');

						if(!empty($uploadedFile)){
							$model2->file = $uploadedFile;
							$ext = pathinfo($uploadedFile, PATHINFO_EXTENSION);
							$model2->type = $ext;


							$model2->created_by=Yii::app()->user->id;
							$model2->save();

							if(!empty($lks_id)){
								if(!empty($lks->chapters)){
									$lks->chapters=$lks->chapters.",".$model->id;
								}else{
									$lks->chapters=$model->id;
								}
								$totMateri = explode(',', $lks->chapters);
								$tm = count($totMateri);
								//$cekQuiz->total_question=$tq;
								$lks->save();
							}

							if(!file_exists(Yii::app()->basePath.'/../images/chapters/'.$model->id_lesson)){
								mkdir(Yii::app()->basePath.'/../images/chapters/'.$model->id_lesson,0775,true);
							}

							if(!file_exists(Yii::app()->basePath.'/../images/chapters/upload')){
								mkdir(Yii::app()->basePath.'/../images/chapters/upload');
							}

							if(!empty($uploadedFile)){
								if($model2->type == 'pdf'){
									$uploadedFile->saveAs(Yii::app()->basePath.'/../images/chapters/upload/'.$uploadedFile);
								}else{
									$uploadedFile->saveAs(Yii::app()->basePath.'/../images/chapters/'.$model->id_lesson.'/'.$uploadedFile);
									// if($model2->type == 'ppt' || $model2->type == 'pptx'){
									// 	$url_file = Yii::app()->basePath.'/../images/chapters/'.$model->id_lesson.'/'.$uploadedFile;
									// 	exec('unoconv -f pdf $url_file');
									// 	shell_exec('unoconv -f pdf '.$url_file.'');
									// }
								}

								/*if($model2->type == 'ppt' || $model2->type == 'pptx'){
									$file = Yii::app()->basePath.'/../images/chapters/'.$model->id_lesson.'/'.$uploadedFile;
									$command = 'unoconv -f pdf '.$file;
									exec($command);
									//$HOME=putenv('/var/www/');
									//putenv('PATH=/usr/');
									//exec('/usr/bin/unoconv -f pdf /var/www/smart-school/images/chaptes/1/'.$uploadedFile.'');
									//exec('echo $HOME & unoconv -vvvv --format %s --output %s %s 2>~/output.txt');
								}*/
							}
						}

						$siswa = LessonMc::model()->findAll(array('condition'=>'lesson_id = '.$model->id_lesson));
						$kelas=Lesson::model()->findByPk($model->id_lesson);

						if(!empty($siswa)){
							foreach ($siswa as $murid) {

								$notif=new Notification;
								$notif->content= "Guru ".$name." Menambah Materi Baru";
								$notif->user_id=Yii::app()->user->id;
								//$notif->class_id_to=$kelas->class_id;
								$notif->user_id_to=$murid->user_id;
								$notif->tipe='add-chapter';
								$notif->relation_id=$model->id;
								$notif->save();
							}
						}

						// $activity->activity_type="Upload Materi ".$model->title." ".$kelas->name;
						$activity->activity_type="new_chapter";
						$activity->created_by=Yii::app()->user->id;
						$activity->save();

					if(!empty(Yii::app()->session['returnURL'])){
						$this->redirect(Yii::app()->session['returnURL']);
						Yii::app()->session->remove('returnURL');
					}else{
						if(!empty($lks_id)){
							$this->redirect(array('/lks/view','id'=>$lks_id));
						}else{
							$this->redirect(array('view','id'=>$model->id));
						}
					}
				}
			}
		}

		$activity->activity_type="open_form_new_chapter";
						$activity->created_by=Yii::app()->user->id;
						$activity->save();

		$this->render('v2/form',array(
			'model'=>$model,
			'model2'=>$model2,
			'lessons'=>$lessons,
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

		$notif=new Notification;
		$activity=new Activities;
		$model=$this->loadModel($id);

		if($model->trash == 1){
			throw new CHttpException(404, 'The requested page does not exist.');
		}
		/*$lessons=new CActiveDataProvider('Lesson',array(
			'criteria'=>array(
				'select'=>'t.*, c.name as class_name',
				'join'=>'JOIN class AS c ON c.id = t.class_id',
				'condition'=>'user_id = '.Yii::app()->user->id.''),
			));*/
		$lessons = Lesson::model()->findAll(array('condition'=>'user_id = '.Yii::app()->user->id));

		$curr_user=Yii::app()->user->id;
		$user_detail=User::model()->findByPk($curr_user);

		if(!empty($user_detail)){
			$name=$user_detail->display_name;
			$kelas=$user_detail->class_id;
		}else{
			$name=NULL;
			$kelas=NULL;
		}

		$mapel=Lesson::model()->findByPk($model->id_lesson);
		$model2=ChapterFiles::model()->findAll(array("condition"=>"id_chapter = $model->id"));
		if(empty($model2)){
			$model2 = new ChapterFiles;
			$old_file = null;
			$model2->id_chapter=$model->id;
		}else{
			foreach ($model2 as $key) {
				$idFile = $key->id;
			}
			$model2 = ChapterFiles::model()->findByPk($idFile);
			$old_file=$model2->file;
			//$model2->id_chapter=$model->id;
		}
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Chapters']) OR isset($_POST['ChapterFiles']))
		{

			 $_POST['Chapters']['content'] = str_replace("[math]","$$",$_POST['Chapters']['content']);
			 $_POST['Chapters']['content'] = str_replace("[/math]","$$",$_POST['Chapters']['content']);

			$model->attributes=$_POST['Chapters'];
			$model2->attributes=$_POST['ChapterFiles'];
			//$lesson=$model->id_lesson;
			$uploadedFile = CUploadedFile::getInstance($model2, 'file');

			if($model->chapter_type == 1){
				$model2->scenario = 'video';
			}elseif($model->chapter_type == 2){
				$model2->scenario = 'gambar';
			}elseif($model->chapter_type == 3){
				$model2->scenario = 'dokumen';
			}
			if(!empty($uploadedFile)){
				$model2->file = $uploadedFile;
				$ext = pathinfo($uploadedFile, PATHINFO_EXTENSION);
				$model2->type = $ext;
			}else{
				$model2->file = $old_file;
			}
			$model->sync_status = 2;
			$model2->updated_by=Yii::app()->user->id;
			$model2->sync_status = 2;
			$model->updated_by = Yii::app()->user->id;

			if(!file_exists(Yii::app()->basePath.'/../images/chapters/'.$model->id)){
				mkdir(Yii::app()->basePath.'/../images/chapters/'.$model->id,0775,true);
			}
			/*echo "<pre>";
			print_r($_FILES);
			echo "</pre>";*/
			if($model->save() && $model2->validate()){

				if(!empty($uploadedFile)){
					$model2->save();
				}

				$kelas=Lesson::model()->findByPk($model->id_lesson);
				$notif->content= "Update Materi ".$mapel->name." ".$model->title;
				$notif->user_id=Yii::app()->user->id;
				$notif->class_id_to=$kelas->class_id;
				$notif->tipe='add-chapter';
				$notif->relation_id=$model->id;
				$notif->save();

				$activity->activity_type="Update Materi ".$model->title." ".$kelas->name;
				$activity->created_by=Yii::app()->user->id;
				$activity->save();

				if(!file_exists(Yii::app()->basePath.'/../images/chapters/'.$model->id_lesson)){
					mkdir(Yii::app()->basePath.'/../images/chapters/'.$model->id_lesson,0775,true);
				}

				if(!file_exists(Yii::app()->basePath.'/../images/chapters/upload')){
					mkdir(Yii::app()->basePath.'/../images/chapters/upload');
				}

				if(!empty($uploadedFile)){
					if($model2->type == 'pdf'){
						$uploadedFile->saveAs(Yii::app()->basePath.'/../images/chapters/upload/'.$uploadedFile);
					}else{
						$uploadedFile->saveAs(Yii::app()->basePath.'/../images/chapters/'.$model->id_lesson.'/'.$uploadedFile);
						// if($model2->type == 'ppt' || $model2->type == 'pptx'){
						// 	$url_file = Yii::app()->basePath.'/../images/chapters/'.$model->id_lesson.'/'.$uploadedFile;
						// 	exec('unoconv -f pdf $url_file');
						// 	shell_exec('unoconv -f pdf '.$url_file.'');
						// }
					}
				}

				if(!empty(Yii::app()->session['returnURL'])){
					$this->redirect(Yii::app()->session['returnURL']);
					Yii::app()->session->remove('returnURL');
				}else{
					$this->redirect(array('view','id'=>$model->id));
				}
			}
		}

		$this->render('v2/form',array(
			'model'=>$model,
			'model2'=>$model2,
			'lessons'=>$lessons,
		));
	}

	public function actionCopy($id)
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

		$materi = $this->loadModel($id);
		$fileMateri = ChapterFiles::model()->findByAttributes(array('id_chapter'=>$id));
		$model = new Chapters;
		$model2 = new ChapterFiles;
        if (Yii::app()->user->YiiTeacher) {
            $lessons = Lesson::model()->findAll(array('condition' => 'user_id = ' . Yii::app()->user->id . ' AND trash is NULL AND semester = ' . $optSemester . " AND year = " . $optTahunAjaran));
        } else {
            $lessons = Lesson::model()->findAll(array('condition' => 'trash is NULL AND semester = ' . $optSemester . " AND year = " . $optTahunAjaran));
        }

		if(isset($_POST['Chapters'])){
			$model->attributes=$_POST['Chapters'];

			$model->content = $materi->content;
			$model->chapter_type = $materi->chapter_type;
			$model->semester = $materi->semester;
			$model->year = $materi->year;
			$model->created_by = $materi->created_by;

			//copy('foo/test.php', 'bar/test.php');



			if($model->save()){
				if(!empty($fileMateri)){
					$model2->id_chapter=$model->id;
					$model2->created_by=Yii::app()->user->id;
					$model2->file = $fileMateri->file;
					$model2->type = $fileMateri->type;
					$model2->save();

					if(!file_exists(Yii::app()->basePath.'/../images/chapters/'.$model->id_lesson)){
								mkdir(Yii::app()->basePath.'/../images/chapters/'.$model->id_lesson,0775,true);
					}

					$fileName = Yii::app()->basePath.'/../images/chapters/'.$materi->id_lesson.'/'.$model2->file;

					if (file_exists($fileName)){
					    copy(Yii::app()->basePath.'/../images/chapters/'.$materi->id_lesson.'/'.$model2->file, Yii::app()->basePath.'/../images/chapters/'.$model->id_lesson.'/'.$model2->file);
					}else{
					   //$fileName = Yii::app()->basePath.'/../images/upload/'.$name;
					  // return Yii::app()->getRequest()->sendFile($name, @file_get_contents($fileName));
					   copy(Yii::app()->basePath.'/../images/chapters/upload/'.$model2->file, Yii::app()->basePath.'/../images/chapters/'.$model->id_lesson.'/'.$model2->file);
					}


				}
				Yii::app()->user->setFlash('success','Materi Berhasil Disalin!');
				$this->redirect(array('view','id'=>$model->id));
			}
		}

		$this->render('v2/copy',array(
			'materi'=>$materi,
			'model'=>$model,
			'lessons'=>$lessons,
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

		if(Yii::app()->user->YiiTeacher){
			$dataProvider=new CActiveDataProvider('Chapters',array(
				'criteria'=>array(
					'condition'=>'semester = '.$optSemester.' and year = '.$optTahunAjaran.' and created_by = '.Yii::app()->user->id.' and t.trash is null'
				),
				'pagination'=>array('pageSize'=>15)
				));
		}else{
			$dataProvider=new CActiveDataProvider('Chapters');
		}

		Yii::app()->session->remove('returnURL');
		$this->render('v2/list',array(
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

		$model=new Chapters('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Chapters']))
			$model->attributes=$_GET['Chapters'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Chapters the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Chapters::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Chapters $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='chapters-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
