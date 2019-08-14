<?php

class ExamController extends Controller
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column1';

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
                'actions' => array('index', 'view', 'create', 'update', 'hapus', 'create_schedule', 'update_schedule',
                    'hapus_schedule', 'create_room', 'list_student', 'update_room', 'hapus_room', 'view_room',
                    'print_out', 'tatatertib', 'beritaacara', 'reset_tatatertib', 'reset_beritaacara', 'print_room'),
                 'expression' => 'Yii::app()->user->YiiAdmin',
            ),

             array('allow',  // allow all users to perform 'index' and 'view' actions
                'actions' => array('index','print_out'),
                 'expression' => 'Yii::app()->user->YiiStudent',
            ),
            array('deny',  // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actionView($id, $type = null)
    {
        $current_user = Yii::app()->user->id;
        Yii::app()->session['userView' . $current_user . 'returnURL'] = Yii::app()->request->Url;

        $model = $this->loadModel($id);
        $type = ($type == null) ? "jadwal" : $type;
        $classes_list = array();
        $datas = null;
        if ($model->trash == 1) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }

        if ($type == "jadwal") {
            $datas = ExamSchedule::model()->findAll(array('condition' => 'exam_id = ' . $id));
        } else if ($type == "ruang") {
            $datas = ExamRoom::model()->findAll(array('condition' => 'exam_id = ' . $id));
        } else if ($type == "kartu") {
            $clist = ClassDetail::model()->findAll(array('order' => 'name'));
            
            if (!empty($clist)) {
                foreach ($clist as $cl) {
                    $classes_list[$cl->id] = $cl->name;
                }
            }
        } else if($type== "tertib"){
            $datas = TataTertib::model()->findByPk('1');
        }else if($type== "berita"){
            $datas = BeritaAcara::model()->findByPk('1');
        }
        $this->render('v2/view', array(
            'model' => $model,
            'class' => $classes_list,
            'type' => $type,
            'datas' => $datas
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        if (Yii::app()->session['semester']) {
            $optSemester = Yii::app()->session['semester'];
        } else {
            $optSemester = Option::model()->findByAttributes(array('key_config' => 'semester'))->value;
        }
        if (Yii::app()->session['tahun_ajaran']) {
            $optTahunAjaran = Yii::app()->session['tahun_ajaran'];
        } else {
            $optTahunAjaran = Option::model()->findByAttributes(array('key_config' => 'tahun_ajaran'))->value;
        }

        $model = new Exam;

        if (isset($_POST['Exam'])) {
            $model->attributes = $_POST['Exam'];
            $model->semester = $optSemester;
            $model->year = $optTahunAjaran;

            if ($model->save()) {
                $date = $model->start_date;
                while (strtotime($date) <= strtotime($model->end_date)) {
                    $schedule = new ExamSchedule;
                    $schedule->exam_id = $model->id;
                    $schedule->date = $date;
                    $schedule->insert();

                    $date = date("Y-m-d", strtotime("+1 day", strtotime($schedule->date)));
                }

                Yii::app()->user->setFlash('success', 'Administrasi Berhasil Dibuat !');
                $this->redirect(array('view', 'id' => $model->id));
            }
        }

        $this->render('v2/form', array(
            'model' => $model,
            'semester' => $optSemester,
            'year' => $optTahunAjaran
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);

        if ($model->trash == 1) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }

        if (isset($_POST['Exam'])) {
            $model->attributes = $_POST['Exam'];

            if ($model->save()) {
                $date = $model->start_date;
                $tmp_date = array();
                while (strtotime($date) <= strtotime($model->end_date)) {
                    $schedule = new ExamSchedule;
                    $schedule->exam_id = $model->id;
                    $schedule->date = $date;
                    $tmp_date[] = $date;

                    $duplicate = ExamSchedule::model()->find(array('condition' => 'exam_id = ' . $model->id . ' AND date = "' . $date . '"'));

                    if (!$duplicate)
                        $schedule->insert();

                    $date = date("Y-m-d", strtotime("+1 day", strtotime($schedule->date)));
                }
                if (!empty($tmp_date)) {
                    $sql = 'DELETE FROM exam_schedule WHERE exam_id = ' . $model->id . ' AND date NOT IN("' . implode('", "', $tmp_date) . '")';

                    $deleteCommand = Yii::app()->db->createCommand($sql);
                    $deleteCommand->execute();
                }

                Yii::app()->user->setFlash('success', 'Administrasi Ujian Berhasil Diubah !');
                $this->redirect(array('view', 'id' => $model->id));
            }
        }

        $this->render('v2/form', array(
            'model' => $model
        ));
    }

    public function actionHapus($id)
    {
        $model = $this->loadModel($id);
        $model->trash = 1;

        if ($model->save()) {
            Yii::app()->user->setFlash('error', 'Ulangan Berhasil Dihapus !');
            if (!empty(Yii::app()->session['returnURL'])) {
                $this->redirect(Yii::app()->session['returnURL']);
                Yii::app()->session->remove('returnURL');
            } else {
                $this->redirect(Yii::app()->request->urlReferrer);
            }
        }
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
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $dataProvider = new CActiveDataProvider('Exam', array(
            'criteria' => array(
                'condition' => 't.trash is null',
            ),
            'pagination' => array('pageSize' => 15)
        ));

//        Yii::app()->session->remove('returnURL');
        $this->render('v2/list', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $model = new Exam('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Exam']))
            $model->attributes = $_GET['Exam'];

        $this->render('admin', array(
            'model' => $model,
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
        $model = Exam::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Lks $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'lks-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate_schedule($schedule_id)
    {
        $model = new ExamScheduleClass();

        if (isset($_POST['ExamScheduleClass'])) {
            $post = $_POST['ExamScheduleClass'];

            $model->schedule_id = $post['schedule_id'];
            $model->class_id = json_encode($post['class_id']);
            $model->lesson_id = json_encode($post['lesson_id']);
            $model->lesson_time = json_encode($post['lesson_time']);

            if ($model->save()) {

                Yii::app()->user->setFlash('success', 'Jadwal Ujian Berhasil Dibuat !');
                $current_user = Yii::app()->user->id;
                $this->redirect(Yii::app()->session['userView' . $current_user . 'returnURL']);
            }
        }

        $this->render('v2/form_schedule', array(
            'model' => $model,
            'schedule_id' => $schedule_id,
        ));
    }

    public function actionUpdate_schedule($id)
    {
        $model = ExamScheduleClass::model()->findByPk($id);
        $model->class_id = json_decode($model->class_id);
        $model->lesson_id = json_decode($model->lesson_id);

        if (isset($_POST['ExamScheduleClass'])) {
            $post = $_POST['ExamScheduleClass'];

            $model->schedule_id = $post['schedule_id'];
            $model->class_id = json_encode($post['class_id']);
            $model->lesson_id = json_encode($post['lesson_id']);
            $model->lesson_time = json_encode($post['lesson_time']);

            if ($model->save()) {

                Yii::app()->user->setFlash('success', 'Jadwal Ujian Berhasil Diubah !');
                $current_user = Yii::app()->user->id;
                $this->redirect(Yii::app()->session['userView' . $current_user . 'returnURL']);
            }
        }

        $this->render('v2/form_schedule', array(
            'model' => $model,
            'schedule_id' => $model->schedule_id,
        ));
    }

    public function actionHapus_schedule($id)
    {
        $model = ExamScheduleClass::model()->findByPk($id);

        if ($model->delete()) {

            Yii::app()->user->setFlash('Danger', 'Jadwal Ujian Berhasil Dihapus !');
            $current_user = Yii::app()->user->id;
            $this->redirect(Yii::app()->session['userView' . $current_user . 'returnURL']);
        }

    }

    public function actionCreate_room($exam_id)
    {
        //formalitas hanya untuk view
        $model = new ExamStudentlist;

        if (isset($_POST['exam_id'])) {
            $room = new ExamRoom;
            $room->exam_id = $_POST['exam_id'];
            $room->no_room = $_POST['no_room'];
            $studentlist = $_POST['selected-student'];
            if ($room->save()) {

                foreach ($studentlist as $student) {
                    $student_model = new ExamStudentlist;
                    $split = explode("|", $student);

                    $student_model->room_id = $room->id;
                    $student_model->student_id = $split[0];
                    $student_model->class_id = $split[1];

                    $student_model->insert();
                }

                Yii::app()->user->setFlash('success', 'Ruang Ujian Berhasil Diubah !');
                $current_user = Yii::app()->user->id;
                $this->redirect(Yii::app()->session['userView' . $current_user . 'returnURL']);
            }
        }

        $this->render('v2/form_room', array(
            'model' => $model,
            'exam_id' => $exam_id,
        ));
    }

    public function actionUpdate_room($room_id)
    {
        //formalitas hanya untuk view
        $model = ExamRoom::model()->findByPk($room_id);
        $list_student = $model->list;

        if (isset($_POST['room_id'])) {

            $room_id = $_POST['room_id'];
            $model->no_room = $_POST['no_room'];
            if($model->save()){
                $studentlist = $_POST['selected-student'];
                foreach ($list_student as $list) {
                    $list->delete();
                }
                foreach ($studentlist as $student) {
                    $student_model = new ExamStudentlist;
                    $split = explode("|", $student);

                    $student_model->room_id = $room_id;
                    $student_model->student_id = $split[0];
                    $student_model->class_id = $split[1];

                    $student_model->insert();
                }

                Yii::app()->user->setFlash('success', 'Ruang Ujian Berhasil Diubah !');
                $current_user = Yii::app()->user->id;
                $this->redirect(Yii::app()->session['userView' . $current_user . 'returnURL']);
            }
        }


        $this->render('v2/form_room', array(
            'model' => $model,
            'list_student' => $list_student,
            'room_id' => $model->id,
            'exam_id' => $model->exam_id,
        ));
    }

    public function actionHapus_room($room_id)
    {
        $model = ExamRoom::model()->findByPk($room_id);
        if ($model->delete()) {
            Yii::app()->user->setFlash('Danger', 'Jadwal Ujian Berhasil Dihapus !');
            $current_user = Yii::app()->user->id;
            $this->redirect(Yii::app()->session['userView' . $current_user . 'returnURL']);
        }
    }

    public function actionView_room($room_id)
    {
        $model = ExamStudentlist::model()->findAll(array(
            'condition' => 't.room_id='. $room_id,
        ));
        $this->render('v2/view_room', array(
            'model' => $model
        ));

    }

    public function actionList_student($class_id)
    {
        $siswa = User::model()->findAll(array('select' => 'id, display_name', 'condition' => 'class_id = ' . $class_id . ' and trash is null order by display_name ASC'));
        $list = array();

        if (!empty($siswa)) {
            foreach ($siswa as $cl) {
                $list[$cl->display_name] = $cl->id;
            }
        }
        echo json_encode($list);
    }

    public function actionPrint_out($exam_id, $student_id)
    {
        if (Yii::app()->user->YiiStudent) {
            if($student_id!=Yii::app()->user->id){
                $this->redirect(array('/site/index')); 
            }
        }

        $model = $this->loadModel($exam_id);    
        if (Yii::app()->session['semester']) {
            $optSemester = Yii::app()->session['semester'];
        } else {
            $optSemester = Option::model()->findByAttributes(array('key_config' => 'semester'))->value;
        }
        if (Yii::app()->session['tahun_ajaran']) {
            $optTahunAjaran = Yii::app()->session['tahun_ajaran'];
        } else {
            $optTahunAjaran = Option::model()->findByAttributes(array('key_config' => 'tahun_ajaran'))->value;
        }


        $siswa = User::model()->findByPk($student_id);
        $jadwal = Yii::app()->db->createCommand('SELECT t.*, u.* FROM `exam_schedule` `t` JOIN exam_schedule_class as u on t.id = u.schedule_id WHERE exam_id = ' . $exam_id . ' AND u.class_id LIKE \'%"'. $siswa->class_id .'"%\'')->query();
        //ruangan
        $ruang = ExamStudentlist::model()->find(array('condition' => 'student_id='. $student_id));
        $this->render('v2/print_out', array(
            'model' => $model,
            'siswa' => $siswa,
            'jadwal' => $jadwal,
            'ruang' => $ruang,
            'semester' => $optSemester,
            'year' => $optTahunAjaran,
            'student_id' => $student_id,
        ));
    }

    public function actionTatatertib(){
        $model = TataTertib::model()->findByPk(1);
        if (isset($_POST['id'])) {
            $model->edited = $_POST['edited'];

            if($model->save()){
                Yii::app()->user->setFlash('success', 'Tata Tertib Ujian Berhasil Diubah !');
                $current_user = Yii::app()->user->id;
                $this->redirect(Yii::app()->session['userView' . $current_user . 'returnURL']);
            }
        }
        $this->render('v2/form_tatatertib', array(
            'model' => $model
        ));
    }

    public function actionReset_tatatertib() {
        $model = TataTertib::model()->findByPk(1);
        $model->edited =  $model->reset;

        if($model->save())
            $this->redirect(array('/exam/tatatertib'));
    }

    public function actionBeritaacara(){
        $model = BeritaAcara::model()->findByPk(1);
        if (isset($_POST['id'])) {
            $model->edited = $_POST['edited'];

            if($model->save()){
                Yii::app()->user->setFlash('success', 'Berita Acara Berhasil Diubah !');
                $current_user = Yii::app()->user->id;
                $this->redirect(Yii::app()->session['userView' . $current_user . 'returnURL']);
            }
        }
        $this->render('v2/form_beritaacara', array(
            'model' => $model
        ));
    }

    public function actionReset_beritaacara() {
        $model = BeritaAcara::model()->findByPk(1);
        $model->edited =  $model->reset;

        if($model->save())
            $this->redirect(array('/exam/beritaacara'));
    }

    public function actionPrint_room($exam_id, $room_id) {
        $room = ExamRoom::model()->findByPk($room_id);
        $exam = Exam::model()->findByPk($exam_id);

        $this->render('v2/print_room', array(
            'room' => $room,
            'exam' => $exam
        ));
    }
}
