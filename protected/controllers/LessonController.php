<?php

class LessonController extends Controller
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
                'actions' => array('Suggest'),
                'users' => array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('index', 'view', 'filterLesson'),
                'users' => array('@'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('DownloadAbsenPelajaran','showAll', 'ImportQuiz', 'AddNilaiRapor', 'EditNilaiRapor', 'NilaiRapor', 'admin', 'hapus', 'delete', 'create', 'update', 'exportNilai', 'createExcel', 'rekapNilai', 'addMark', 'rekapDownload', 'raport', 'bulk', 'downloadFile', 'copyExcel', 'copykd', 'inputData', 'addFromTable', 'bulkList', 'hapusMurid', 'Presensi', 'NilaiKetSikap', 'NilaiKetSikapDua', 'NilaiKd', 'NilaiKdDua', 'addMarkPresensi', 'addMarkKetSik', 'addMarkKetSikDua', 'addMarkKd', 'addMarkKdDua', 'importnilainuts', 'importnilaiuas', 'managekd', 'ReplikaKd'),
                'expression' => 'Yii::app()->user->YiiAdmin',
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('ImportQuiz','DownloadAbsenPelajaran','AddNilaiRapor', 'EditNilaiRapor', 'NilaiRapor', 'create', 'update', 'exportNilai', 'createExcel', 'rekapNilai', 'offlineTask', 'NilaiKd', 'NilaiKdDua', 'addMark', 'rekapDownload', 'raport', 'presensi', 'nilaiKetSikap', 'NilaiKetSikapDua', 'addMarkPresensi', 'addMarkKd', 'addMarkKdDua', 'AddMarkKetSik', 'AddMarkKetSikDua', 'copyExcel', 'copykd', 'inputData', 'addFromTable', 'hapusMurid'),
                'expression' => 'Yii::app()->user->YiiTeacher',
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('DownloadAbsenPelajaran','createExcel', 'rekapNilai', 'rekapDownload', 'raport'),
                'expression' => 'Yii::app()->user->YiiWali',
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('create', 'update', 'exportNilai', 'createExcel', 'rekapNilai', 'rekapDownload', 'raport'),
                'expression' => 'Yii::app()->user->YiiKepsek',
            ),
            array('deny',  // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id, $type = NULL)
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

        $model = $this->loadModel($id);
        $modelAct = new Activities;

        if(Yii::app()->user->YiiTeacher && $model->user_id != Yii::app()->user->id) {
           Yii::app()->user->setFlash('success', 'Maaf Anda Tidak Punya Hak Akses');
           $this->redirect(array('/site/index'));
        }

        if ($model->trash != NULL) {
            Yii::app()->user->setFlash('error', 'Pelajaran ini telah dihapus admin !');
            $this->redirect(array('/site/index'));
        }

        if (Yii::app()->user->YiiStudent) {
            $term = "lesson_id = " . $id . " AND status is not null";
            $term1 = "lesson_id = " . $id;
            $term2 = "lesson_id = " . $id . " AND trash is null";
        } elseif (Yii::app()->user->YiiTeacher) {
            $term = "lesson_id = " . $id . " AND status is not null";
            $term1 = "lesson_id = " . $id;
            $term2 = "lesson_id = " . $id . " AND quiz_type IN (0,1,2) AND trash is null";
        } else {
            $term = "lesson_id = " . $id;
            $term1 = "lesson_id = " . $id;
            $term2 = "lesson_id = " . $id . " AND trash is null";
        }


        $datas = NULL;
        $dataProvider = NULL;

        if ($type == "materi") {
            $datas = Chapters::model()->findAll(array("condition" => "semester = " . $optSemester . " and year = " . $optTahunAjaran . " and id_lesson = $id and trash is null"));
        } elseif ($type == "tugas") {
            $datas = Assignment::model()->findAll(array("condition" => "semester = " . $optSemester . " and year = " . $optTahunAjaran . " and lesson_id =  $id AND assignment_type IS NULL AND trash is null"));
        } elseif ($type == "ulangan") {
            $datas = Quiz::model()->findAll(array("condition" => "semester = " . $optSemester . " and year = " . $optTahunAjaran . " and " . $term2));
        } elseif ($type == "banks") {
            if (Yii::app()->user->YiiTeacher) {
                // $term_banks='((share_status IS NULL or teacher_id = '.Yii::app()->user->id.') or (share_status = 1 and lesson_id = '.$model->list_id.') or (share_status = 2 and share_teacher LIKE "%,'.Yii::app()->user->id.',%") or share_status = 3) and trash is null';
                $term_banks = 'teacher_id = ' . Yii::app()->user->id . ' and trash is null';
            } else {
                $term_banks = 'trash is null';
            }

            $dataProvider = new CActiveDataProvider('Questions', array(
                'criteria' => array(
                    'order' => 't.created_at DESC',
                    'condition' => $term_banks),
                'pagination' => array('pageSize' => 15),
            ));
        }

        // $datasis = LessonMc::model()->findAll(array("condition"=>"semester = ".$optSemester." and year = ".$optTahunAjaran." and lesson_id = ".$id." and trash is null"));

        $term_datasis = "semester = " . $optSemester . " and year = " . $optTahunAjaran . " and lesson_id = " . $id . " and t.trash is null";
        $datasis = new CActiveDataProvider('LessonMc', array(
            'criteria' => array(
                'join' => 'JOIN users AS u ON u.id = t.user_id ',
                'condition' => '' . $term_datasis,
                'order' => 'u.display_name ASC',
            ),
            'pagination' => array('pageSize' => 500)
        ));


        $ti = Assignment::model()->findAll(array("condition" => "semester = " . $optSemester . " and year = " . $optTahunAjaran . " and lesson_id =  $id AND assignment_type IS NULL AND recipient = " . Yii::app()->user->id));

        $lmc = LessonMc::model()->findAll(array("condition" => "semester = " . $optSemester . " and year = " . $optTahunAjaran . " and lesson_id = " . $id));

        $used_students = NULL;

        if (!empty($lmc)) {
            $used_id = array();
            foreach ($lmc as $mc) {
                array_push($used_id, $mc->user_id);
            }
            $used_students = implode(',', $used_id);
        }

        $list_big = NULL;
        $final_list = array();

        if ($model->moving_class == 1) {
            $big_class = ClassDetail::model()->findAll(array('condition' => 'class_id = ' . $model->class_id));

            if (!empty($big_class)) {
                foreach ($big_class as $bc) {
                    array_push($final_list, $bc->id);
                }
                $list_big = implode(',', $final_list);
            }

        }

        if ($used_students != NULL) {
            if ($model->moving_class == 1) {
                $raw_students = User::model()->findAll(array('condition' => 'role_id = 2 and trash is null and class_id IN (' . $list_big . ') and id not in (' . $used_students . ')', "order" => "display_name"));
            } else {
                $raw_students = User::model()->findAll(array('condition' => 'role_id = 2 and trash is null and class_id = ' . $model->class_id . ' and id not in (' . $used_students . ')', "order" => "display_name"));
            }

        } else {
            if ($model->moving_class == 1) {
                $raw_students = User::model()->findAll(array('condition' => 'role_id = 2 and trash is null and class_id IN (' . $list_big . ')', "order" => "display_name"));
            } else {
                $raw_students = User::model()->findAll(array('condition' => 'role_id = 2 and trash is null and class_id = ' . $model->class_id, "order" => "display_name"));
            }

        }


        // foreach ($datasis->getData() as $value) {
        // 	echo "<pre>";
        // 	print_r($value->student);
        // 	echo "</pre>";
        // }

        // echo "<pre>";
        // 	print_r($datasis->getData());
        // 	echo "</pre>";


        Yii::app()->session['returnURL'] = Yii::app()->request->Url;
        $this->render('v2/view', array(
            'model' => $this->loadModel($id),
            'modelAct' => $modelAct,
            'dataProvider' => $dataProvider,
            'datas' => $datas,
            'datasis' => $datasis->getData(),
            'tgsIndv' => $ti,
            'type' => $type,
            'students' => $raw_students,
        ));
    }

    public function actionHapus($id)
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

        $model = Lesson::model()->findByPk($id);

        $cekUjian = Quiz::model()->findAll(array('condition' => 'lesson_id = ' . $id . ' and trash is null'));
        $cekMateri = Chapters::model()->findAll(array('condition' => 'id_lesson = ' . $id . ' and trash is null'));
        $cekTugas = Assignment::model()->findAll(array('condition' => 'lesson_id = ' . $id . ' and trash is null'));

        if ($cekUjian or $cekMateri or $cekTugas) {
            Yii::app()->user->setFlash('error', 'Pelajaran gagal dihapus, karena masih ada ujian atau materi atau tugas didalamnya. !');
            $this->redirect(array('index'));
        } else {

            $model->trash = 1;
            $model->sync_status = 2;
            $model->user_id = "(ID:" . $model->user_id . ")";

            if ($model->save()) {
                Yii::app()->user->setFlash('error', 'Pelajaran Berhasil Dihapus !');

                if (!empty(Yii::app()->session['returnURL'])) {
                    $this->redirect(Yii::app()->session['returnURL']);
                    Yii::app()->session['returnURL'] = NULL;
                } else {
                    $this->redirect(array('index'));
                }
            }

        }


    }

    public function actionHapusMurid($id)
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

        $model = LessonMc::model()->findByPk($id);

        // $model->trash = 1;

        // if($model->save()){

        $model->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax'])) {
            Yii::app()->user->setFlash('error', 'Siswa Berhasil Dihapus Di Pelajaran Ini!');
            $this->redirect(Yii::app()->request->urlReferrer);
        }
    }

    public function actionShowAll()
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

        $prefix = Yii::app()->params['tablePrefix'];
        $usr = User::model()->findByPk(Yii::app()->user->id);

        if (isset($_POST['users'])) {
            //echo "<pre>";
            $ids = array();
            $id_gabungan = NULL;
            $id_lesson = $_POST['id_lesson'];

            if (isset($_POST['hapus'])) {
                $tipe = "hapus";
                //echo $tipe;
            } elseif (isset($_POST['ganjil'])) {
                $tipe = "ganjil";
            } else {
                $tipe = "genap";
                //echo $tipe;
            }

            if (!empty($_POST['users'])) {
                foreach ($_POST['users'] as $key) {
                    array_push($ids, $key);
                }

                $id_gabungan = implode(',', $ids);
                //echo $id_gabungan;
                $cekAllQuiz = LessonMc::model()->findAll(array('condition' => 'user_id IN (' . $id_gabungan . ') AND semester = ' . $optSemester . " AND year = " . $optTahunAjaran));
                $total = count($cekAllQuiz);

                if ($tipe == "hapus") {
                    if (!empty($cekAllQuiz)) {
                        //$gabid = implode(',', $idq);
                        $show = "DELETE FROM " . $prefix . "lesson_mc where user_id IN (" . $id_gabungan . ") AND lesson_id = " . $id_lesson . " AND semester = " . $optSemester . " AND year = " . $optTahunAjaran;

                        $showCommand = Yii::app()->db->createCommand($show);

                        if ($showCommand->execute()) {
                            Yii::app()->user->setFlash('success', 'Berhasil Menghapus ' . $total . ' Siswa');
                            $this->redirect(Yii::app()->request->urlReferrer);
                        } else {
                            Yii::app()->user->setFlash('success', 'Gagal Menghapus ' . $total . ' Siswa');
                            $this->redirect(Yii::app()->request->urlReferrer);
                        }
                    } else {
                        Yii::app()->user->setFlash('error', 'Anda Belum Memilih Siswa');
                        $this->redirect(Yii::app()->request->urlReferrer);
                    }
                } elseif ($tipe == "ganjil") {
                    if (!empty($cekAllQuiz)) {
                        //$gabid = implode(',', $idq);
                        $show = "UPDATE " . $prefix . "quiz set sync_status = 2, status = 2 where id IN (" . $id_gabungan . ") AND semester = " . $optSemester . " AND year = " . $optTahunAjaran;

                        $showCommand = Yii::app()->db->createCommand($show);

                        if ($showCommand->execute()) {
                            Yii::app()->user->setFlash('success', 'Berhasil Menutup ' . $total . ' Siswa');
                            $this->redirect(Yii::app()->request->urlReferrer);
                        } else {
                            Yii::app()->user->setFlash('success', 'Berhasil Menutup ' . $total . ' Siswa');
                            $this->redirect(Yii::app()->request->urlReferrer);
                        }
                    } else {
                        Yii::app()->user->setFlash('error', 'Anda Belum Memilih Siswa');
                        $this->redirect(Yii::app()->request->urlReferrer);
                    }
                } else {
                    if (!empty($cekAllQuiz)) {
                        foreach ($cekAllQuiz as $value) {
                            $siswa = LessonMc::model()->findAll(array('condition' => 'lesson_id = ' . $value->lesson_id . " AND semester = " . $optSemester . " AND year = " . $optTahunAjaran));
                            if (!empty($siswa)) {
                                foreach ($siswa as $murid) {

                                    $notif = new Notification;
                                    $kelas = Lesson::model()->findByPk($value->lesson_id);
                                    $notif->content = $usr->display_name . " Menampilkan " . $value->title;
                                    $notif->user_id = Yii::app()->user->id;
                                    $notif->relation_id = $value->id;
                                    //$notif->class_id_to=$kelas->class_id;
                                    $notif->user_id_to = $murid->user_id;
                                    $notif->tipe = "quiz";
                                    $notif->save();
                                }
                            }
                        }
                        //$gabid = implode(',', $idq);
                        $show = "UPDATE " . $prefix . "quiz set sync_status = 2, status = 1 where id IN (" . $id_gabungan . ") AND semester = " . $optSemester . " AND year = " . $optTahunAjaran;

                        $showCommand = Yii::app()->db->createCommand($show);

                        if ($showCommand->execute()) {
                            Yii::app()->user->setFlash('success', 'Berhasil Menampilkan ' . $total . ' Siswa');
                            $this->redirect(Yii::app()->request->urlReferrer);
                        } else {
                            Yii::app()->user->setFlash('success', 'Berhasil Menampilkan ' . $total . ' Siswa');
                            $this->redirect(Yii::app()->request->urlReferrer);
                        }
                    } else {
                        Yii::app()->user->setFlash('error', 'Anda Belum Memilih Siswa');
                        $this->redirect(Yii::app()->request->urlReferrer);
                    }
                }

            } else {
                Yii::app()->user->setFlash('error', 'Anda Belum Memilih Siswa');
                $this->redirect(Yii::app()->request->urlReferrer);
            }

            //echo "</pre>";
        } else {
            Yii::app()->user->setFlash('error', 'Anda Belum Memilih Siswa');
            $this->redirect(Yii::app()->request->urlReferrer);
        }


    }

    public function actionFilterLesson()
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

        $current_user = Yii::app()->user->id;
        $term = '';
        $tipe = null;
        $nama = null;
        if (isset($_GET)) {
            $tipe = $_GET['tipe'];
            $nama = strtolower($_GET['nama']);

            if ($tipe == 1) {
                if (!Yii::app()->user->YiiAdmin) {
                    $term = " AND lower(name) LIKE '%" . $nama . "%' AND trash IS NULL  ";
                } else {
                    $term = "lower(name) LIKE '%" . $nama . "%' AND trash IS NULL";
                }
            } else {
                $listKelas = ClassDetail::model()->findAll(array("condition" => "name LIKE '%" . $nama . "%' ORDER BY id"));

                if (!empty($listKelas)) {
                    $idc = array();
                    foreach ($listKelas as $value) {
                        array_push($idc, $value->id);
                    }
                    $kls = implode(',', $idc);
                    if (!Yii::app()->user->YiiAdmin) {
                        $term = " AND class_id IN(" . $kls . ") AND trash IS NULL";
                    } else {
                        $term = "class_id IN(" . $kls . ") AND trash IS NULL";
                    }
                }
            }
        }

        if (Yii::app()->user->YiiTeacher) {
            $term_conditon = "user_id = $current_user";
        } elseif (Yii::app()->user->YiiStudent) {
            $modelUser = User::model()->findByPk($current_user);
            $class_student_id = $modelUser->class_id;
            $term_conditon = "class_id = $class_student_id";
        } elseif (Yii::app()->user->YiiWali) {
            $kelas = Clases::model()->findByAttributes(array('teacher_id' => Yii::app()->user->id));
            $kelas_id = $kelas->id;
            $term_conditon = "class_id = $kelas_id";
        } else {
            $term_conditon = '';
        }

        if ($term_conditon == "") {
            if ($term == "") {
                $term_conditon = $term_conditon . " semester = " . $optSemester . " and year = " . $optTahunAjaran;
            } else {
                $term_conditon = $term_conditon . " semester = " . $optSemester . " and year = " . $optTahunAjaran . " and ";
            }
        } else {
            $term_conditon = $term_conditon . " and semester = " . $optSemester . " and year = " . $optTahunAjaran . " and ";
        }

        $dataProvider = new CActiveDataProvider('Lesson', array(
            'criteria' => array(
                'condition' => '' . $term_conditon . $term,
                'order' => 'class_id'
            ),
            'pagination' => array('pageSize' => 10)
        ));

        $this->render('v2/index', array(
            'dataProvider' => $dataProvider,
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

        $nama_sekolah = Option::model()->findAll(array('condition' => 'key_config LIKE "%school_name%"'));
        $nama_sekolah = $nama_sekolah[0]->value;

        $model = new Lesson;
        $cekKelas = ClassDetail::model()->findAll(array('order' => 'name'));

        if (Yii::app()->user->YiiTeacher) {
            Yii::app()->user->setFlash('error', 'Silahkan Hubungi Admin untuk membuat pelajaran baru');
            $this->redirect(array('/site/index'));
        }

        if (empty($cekKelas)) {
            Yii::app()->user->setFlash('error', 'Belum Ada Kelas Yang Dibuat. Silahkan Buat Kelas Terlebih Dahulu!');
            $this->redirect(array('/clases/addExcel'));
        }
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Lesson'])) {
            $model->attributes = $_POST['Lesson'];
            if (Yii::app()->user->YiiTeacher) {
                $model->user_id = Yii::app()->user->id;
            }
            $cekNama = LessonList::model()->findByPk($model->list_id);
            $model->name = $cekNama->name;
            $model->kelompok = $cekNama->group;

            if ($model->moving_class == 1) {
                $model->class_id = $model->big;
            }

            $model->semester = $optSemester;
            $model->year = $optTahunAjaran;
            if ($model->save()) {
                if ($model->moving_class != 1) {
                    $siswas = User::model()->findAll(array('condition' => 'class_id = ' . $model->class_id . ' and trash is null'));
                    $sukses = 0;
                    if (!empty($siswas)) {

                        foreach ($siswas as $siswa) {
                            $join = "INSERT INTO lesson_mc (lesson_id,user_id,semester,year) values(" . $model->id . "," . $siswa->id . "," . $optSemester . "," . $optTahunAjaran . ")";

                            $joinCommand = Yii::app()->db->createCommand($join);

                            if ($joinCommand->execute()) {
                                $sukses++;
                            }
                        }

                    }
                }

                // if(!empty(Yii::app()->session['returnURL'])){
                // 	$this->redirect(Yii::app()->session['returnURL']);
                // 	Yii::app()->session['returnURL'] = NULL;
                // }else{
                $this->redirect(array('view', 'id' => $model->id));
                // }
            }
        }

        $this->render('v2/form', array(
            'model' => $model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
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

        $model = $this->loadModel($id);

        $nama_sekolah = Option::model()->findAll(array('condition' => 'key_config LIKE "%school_name%"'));
        $nama_sekolah = $nama_sekolah[0]->value;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Lesson'])) {
            $model->attributes = $_POST['Lesson'];
            $model->sync_status = 2;

            $cekNama = LessonList::model()->findByPk($model->list_id);
            $model->kelompok = $cekNama->group;
            if (!$nama_sekolah=="TAR-Q") {    
                 $model->name = $cekNama->name;
            } 

            

            if ($model->moving_class == 1) {
                $model->class_id = $model->big;
            }

            if ($model->save()) {
                if (!empty(Yii::app()->session['returnURL'])) {
                    $this->redirect(Yii::app()->session['returnURL']);
                    Yii::app()->session['returnURL'] = NULL;
                } else {
                    $this->redirect(array('view', 'id' => $model->id));
                }
            }
        }

        $this->render('v2/form', array(
            'model' => $model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
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

        $current_user = Yii::app()->user->id;

        if (Yii::app()->user->YiiTeacher) {
            $term_conditon = 'semester = "' . $optSemester . '" and year = "' . $optTahunAjaran . '" and user_id = "' . $current_user . '" AND trash IS NULL';
        } elseif (Yii::app()->user->YiiStudent) {
            $term_conditon = 't.semester = "' . $optSemester . '" and t.year = "' . $optTahunAjaran . '" and lm.user_id = "' . $current_user . '" AND t.trash IS NULL';
        } elseif (Yii::app()->user->YiiWali) {
            $kelas = Clases::model()->findByAttributes(array('teacher_id' => Yii::app()->user->id, 'trash' => NULL));
            $kelas_id = $kelas->id;
            $term_conditon = 'semester = "' . $optSemester . '" and year = "' . $optTahunAjaran . '" and class_id = "' . $kelas_id . '"';
        } else {
            $term_conditon = 'semester = "' . $optSemester . '" and year = "' . $optTahunAjaran . '" and trash IS NULL';
        }

        if (!Yii::app()->user->YiiStudent) {
            $dataProvider = new CActiveDataProvider('Lesson', array(
                'criteria' => array(
                    'condition' => '' . $term_conditon,
                    'order' => 'id DESC',
                ),
                'pagination' => array('pageSize' => 10)
            ));
        } else {
            $dataProvider = new CActiveDataProvider('Lesson', array(
                'criteria' => array(
                    'join' => 'JOIN lesson_mc AS lm ON lm.lesson_id = t.id ',
                    'condition' => '' . $term_conditon,
                    'order' => 't.id DESC',
                ),
                'pagination' => array('pageSize' => 10)
            ));
        }

        $this->render('v2/index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    public function actionRaport()
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

        $current_user = Yii::app()->user->id;

        if (Yii::app()->user->YiiTeacher) {
            $term_conditon = "user_id = $current_user";
        } elseif (Yii::app()->user->YiiStudent) {
            $modelUser = User::model()->findByPk($current_user);
            $class_student_id = $modelUser->class_id;

            $term_conditon = "class_id = $class_student_id";
        } else {
            $term_conditon = '';
        }

        $dataProvider = new CActiveDataProvider('Lesson', array(
            'criteria' => array(
                'condition' => '' . $term_conditon,
            ),
        ));

        $this->render('list-raport', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin()
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

        $model = new Lesson('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Lesson']))
            $model->attributes = $_GET['Lesson'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Lesson the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
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

        $model = Lesson::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Lesson $model the model to be validated
     */
    protected function performAjaxValidation($model)
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

        if (isset($_POST['ajax']) && $_POST['ajax'] === 'lesson-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionSuggest()
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

        $request = trim($_GET['term']);
        if ($request != '') {
            if (Yii::app()->user->YiiTeacher) {
                $current_user = Yii::app()->user->id;
                $term_conditon = "and user_id = $current_user";
            } else {
                $term_conditon = "";
            }
            $model = Lesson::model()->findAll(array("condition" => "semester = " . $optSemester . " and year = " . $optTahunAjaran . " and lower(name) like lower('$request%') $term_conditon"));
            $data = array();
            foreach ($model as $get) {
                /*if(!empty($get->kecamatan)){
	        		$kec=$get->district;
	        		$kab=$kec->regency;

	        		$data[]=$get->title.', '.$kec->name.', '.$kab->name.' (ID:'.$get->id.')';
	        	}
	        	else{*/
                $data[] = $get->name . ' | ' . $get->class->name . ' (ID:' . $get->id . ')';
                //}

            }
            $this->layout = 'empty';
            echo json_encode($data);
        }
    }

    public function actionExportNilai($id)
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

        header('Content-type: text/csv');
        header('Content-Disposition: attachment; filename="rekap.csv"');
        $model = $this->loadModel($id);
        $kelas = Clases::model()->findByPk($model->class_id);
        $prefix = Yii::app()->params['tablePrefix'];

        $tgl = date("j F Y H:i ", time());
        $rekapNilai = new CActiveDataProvider('User', array(
            'criteria' => array(
                'select' => 't.*,u.score as nilai',
                'join' => 'JOIN ' . $prefix . 'student_assignment AS u ON u.student_id = t.id JOIN ' . $prefix . 'assignment AS a ON a.id = u.assignment_id JOIN ' . $prefix . 'lesson AS l ON l.id = a.lesson_id',
                'condition' => 'l.id = ' . $id . ''),
            'pagination' => array('pageSize' => 50),
        ));
        $no = 1;
        $dt = $rekapNilai->getData();
        echo "Data per tanggal $tgl \r\nKelas : $kelas->name \r\nSemester : \r\nPelajaran : $model->name \r\nNO ,NAMA SISWA,TUGAS HARIAN,ULANGAN,UTS,UAS,KETERANGAN \r\n";
        foreach ($dt as $key) {
            echo "\"$no\",\"$key->display_name\",\"$key->nilai\" \r\n";
            $no++;
        }

        exit;
    }

    public function actionCreateExcel($id)
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

        $user = User::model()->findByPk(Yii::app()->user->id);
        $user->display_name;
        $model = $this->loadModel($id);
        if ($model->moving_class == 1) {
            $kelas = Clases::model()->findByPk($model->class_id);
        } else {
            $kelas = ClassDetail::model()->findByPk($model->class_id);
        }

        //$siswa = User::model()->findAll(array('condition'=>'class_id = '.$kelas->id.' and trash is null'));
        $siswa = LessonMc::model()->findAll(array('condition' => 'lesson_id = ' . $id));

        if (Yii::app()->session['semester']) {
            $semester = Yii::app()->session['semester'];
        } else {
            $semester = Option::model()->findAll(array('condition' => 'key_config LIKE "%semester%"'));
        }
        if (Yii::app()->session['tahun_ajaran']) {
            $tahun_ajaran = Yii::app()->session['tahun_ajaran'];
        } else {
            $tahun_ajaran = Option::model()->findAll(array('condition' => 'key_config LIKE "%tahun_ajaran%"'));
        }

        $tugas = Assignment::model()->findAll(array('condition' => 'lesson_id = ' . $id . ' AND add_to_summary is null and trash is null and semester = ' . $semester[0]->value . ' and year = ' . $tahun_ajaran[0]->value));
        $tugasOnline = Assignment::model()->findAll(array('condition' => 'lesson_id = ' . $id . ' and assignment_type is null AND add_to_summary is null and trash is null and semester = ' . $semester[0]->value . ' and year = ' . $tahun_ajaran[0]->value));
        $tOffline = OfflineMark::model()->findAll(array('condition' => 'lesson_id = ' . $id . ' AND mark_type = 1'));
        $kuis = Quiz::model()->findAll(array('condition' => 'lesson_id = ' . $id . ' AND add_to_summary is null and quiz_type < 1 and semester = ' . $semester[0]->value . ' and year = ' . $tahun_ajaran[0]->value . ' and trash is null'));
        $uas = Quiz::model()->findAll(array('condition' => 'lesson_id = ' . $id . ' AND add_to_summary is null and quiz_type = 2 and semester = ' . $semester[0]->value . ' and year = ' . $tahun_ajaran[0]->value . ' and trash is null'));
        $uts = Quiz::model()->findAll(array('condition' => 'lesson_id = ' . $id . ' AND add_to_summary is null and quiz_type = 1 and semester = ' . $semester[0]->value . ' and year = ' . $tahun_ajaran[0]->value . ' and trash is null'));
        $prefix = Yii::app()->params['tablePrefix'];

        $nama_sekolah = Option::model()->findAll(array('condition' => 'key_config LIKE "%school_name%"'));
        $kepala_sekolah = Option::model()->findAll(array('condition' => 'key_config LIKE "%kepsek_id%"'));
        $alamat_sekolah = Option::model()->findAll(array('condition' => 'key_config LIKE "%school_address%"'));
        $nilai_harian = Option::model()->findAll(array('condition' => 'key_config LIKE "%nilai_harian%"'));
        $nilai_uts = Option::model()->findAll(array('condition' => 'key_config LIKE "%nilai_uts%"'));
        $nilai_uas = Option::model()->findAll(array('condition' => 'key_config LIKE "%nilai_uas%"'));
        $kurikulum_sekolah = Option::model()->findAll(array('condition' => 'key_config LIKE "%kurikulum%"'));

        if (!empty($nama_sekolah)) {
            $school_name = strtoupper($nama_sekolah[0]->value);
        } else {
            $school_name = "";
        }

        if (!empty($alamat_sekolah)) {
            $school_address = $alamat_sekolah[0]->value;
        } else {
            $school_address = "";
        }

        if (!empty($kepala_sekolah)) {
            $user_kepsek = User::model()->findByPk($kepala_sekolah[0]->value);
            $kepsek = $user_kepsek->display_name;
            $nik = $user_kepsek->username;
        } else {
            $kepsek = "Medidu";
            $nik = "022";
        }

        Yii::import('ext.phpexcel.XPHPExcel');
        $objPHPExcel = XPHPExcel::createPHPExcel();
        $objPHPExcel->getProperties()->setCreator($user->display_name)
            ->setLastModifiedBy($user->display_name)
            ->setTitle("Daftar Nilai")
            ->setSubject("")
            ->setDescription("")
            ->setKeywords("")
            ->setCategory($model->name);
        /*$objPHPExcel->getDefaultStyle()
				    ->getBorders()
				    ->getTop()
				        ->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$objPHPExcel->getDefaultStyle()
				    ->getBorders()
				    ->getBottom()
				        ->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$objPHPExcel->getDefaultStyle()
				    ->getBorders()
				    ->getLeft()
				        ->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$objPHPExcel->getDefaultStyle()
				    ->getBorders()
				    ->getRight()
				        ->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);*/

        $styleArray = array(
            'font' => array(
                'bold' => true,
                'color' => array('rgb' => 'FF9900'),
                'size' => 11,
                'name' => 'Verdana'
            ));
        $style = array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ),
            'font' => array(
                'bold' => true,
            ),
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        );

        $style1 = array(
            'font' => array(
                'bold' => true,
            ),
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        );
        $style3 = array(
            'font' => array(
                'bold' => true,
            ),
        );

        $style2 = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        );

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('C2', '' . $school_name . '');
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('C3', '' . $school_address . '');
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('C4', 'Telp');
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('C5', 'Website');
        $objPHPExcel->getActiveSheet()->getStyle('C2')->applyFromArray($styleArray);

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A6', 'Kelas : ' . $kelas->name)
            ->setCellValue('A7', 'Mata Pelajaran : ' . $model->name)
            ->setCellValue('A8', 'Semester : ')
            ->mergeCells('A6:B6')
            ->mergeCells('A7:B7')
            ->mergeCells('A8:B8');
        $objPHPExcel->getActiveSheet()->getStyle('A6:B6')->applyFromArray($style3);
        $objPHPExcel->getActiveSheet()->getStyle('A7:B7')->applyFromArray($style3);
        $objPHPExcel->getActiveSheet()->getStyle('A8:B8')->applyFromArray($style3);

        // Add some data
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A10', 'No')
            ->setCellValue('B10', 'NIS')
            ->setCellValue('C10', 'Nama Siswa')
            ->setCellValue('D10', 'Nilai Harian')
            ->mergeCells('A10:A11')
            ->mergeCells('B10:B11')
            ->mergeCells('C10:C11')
            ->mergeCells('D10:D11')
            ->getStyle('B10:B11')->applyFromArray($style);
        $objPHPExcel->getActiveSheet()->getStyle('C10:C11')->applyFromArray($style);
        $objPHPExcel->getActiveSheet()->getStyle('A10:A11')->applyFromArray($style);

        $objPHPExcel->getActiveSheet()
            ->getColumnDimension('B')
            ->setAutoSize(true);

        $objPHPExcel->getActiveSheet()
            ->getColumnDimension('C')
            ->setAutoSize(true);

        $a = range('D', 'Z');
        $n = 0;
        $x = 11;
        $last = 0;
        $p = 1;
        foreach ($tugas as $tgs) {
            $na = $a[$n];
            if ($tgs->assignment_type == NULL) {
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('' . $na . $x . '', '');
            } else {
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('' . $na . $x . '', '');
            }
            $n++;
            $p++;
            $last = $na;
        }

        foreach ($kuis as $qz) {
            $na = $a[$n];
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('' . $na . $x . '', '');
            $n++;
            $p++;
            $last = $na;
        }

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('D10', 'Tugas Harian')
            ->mergeCells('D10:' . $last . '10')
            ->getStyle('D10:' . $last . '10')->applyFromArray($style);

        $next = range($last, 'Z');
        $c = 10;
        $d = 6;

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('' . $next[1] . $c . '', 'RNH')
            ->setCellValue('' . $next[2] . $c . '', 'UTS')
            ->setCellValue('' . $next[3] . $c . '', 'UAS')
            ->setCellValue('' . $next[4] . $c . '', 'Raport')
            ->mergeCells('' . $next[1] . $c . ':' . $next[1] . '11')
            ->mergeCells('' . $next[2] . $c . ':' . $next[2] . '11')
            ->mergeCells('' . $next[3] . $c . ':' . $next[3] . '11')
            ->mergeCells('' . $next[4] . $c . ':' . $next[4] . '11');
        $objPHPExcel->getActiveSheet()->getStyle('' . $next[1] . $c . ':' . $next[1] . '11')->applyFromArray($style);
        $objPHPExcel->getActiveSheet()->getStyle('' . $next[2] . $c . ':' . $next[2] . '11')->applyFromArray($style);
        $objPHPExcel->getActiveSheet()->getStyle('' . $next[3] . $c . ':' . $next[3] . '11')->applyFromArray($style);
        $objPHPExcel->getActiveSheet()->getStyle('' . $next[4] . $c . ':' . $next[4] . '11')->applyFromArray($style);

        /*$objPHPExcel->getActiveSheet()
				    ->getColumnDimension(''.$next[3].'')
				    ->setAutoSize(true);*/

        $rekapNilai = new CActiveDataProvider('User', array(
            'criteria' => array(
                'select' => 't.*,u.score as nilai',
                'join' => 'JOIN ' . $prefix . 'student_assignment AS u ON u.student_id = t.id JOIN ' . $prefix . 'assignment AS a ON a.id = u.assignment_id JOIN ' . $prefix . 'lesson AS l ON l.id = a.lesson_id',
                'condition' => 'l.id = ' . $id . ''),
            'pagination' => array('pageSize' => 50),
        ));

        $huruf = range('D', 'Z');
        $no = 12;
        $counter = 1;
        $cell = 0;
        foreach ($siswa as $key) {
            $ts = StudentAssignment::model()->with('teacher_assign')->together()->findAll(array('condition' => 'student_id = ' . $key->student->id . ' AND teacher_assign.lesson_id = ' . $model->id));
            $k = 0;
            $l = 0;
            $rnh = 0;
            $tnh = 0;
            $div = 0;
            $div2 = 0;
            $pnh = 0;
            $nuts = 0;
            $puts = 0;
            $tuas = 0;
            $ruas = 0;
            $puas = 0;
            $puts = 0;
            $ruts = 0;
            $nrpt = 0;
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $no . '', '' . $counter . '')
                ->setCellValue('B' . $no . '', '' . $key->student->username . '')
                ->setCellValue('C' . $no . '', '' . $key->student->display_name . '');
            foreach ($tugas as $value) {
                $ts = StudentAssignment::model()->findByAttributes(array('student_id' => $key->student->id, 'assignment_id' => $value->id, 'status' => 1));
                $om = OfflineMark::model()->findAll(array('condition' => 'student_id = ' . $key->student->id . ' AND lesson_id = ' . $model->id . ' AND mark_type = 1 AND assignment_id = ' . $value->id));
                if ($value->assignment_type == NULL) {
                    if (!empty($ts)) {
                        $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('' . $huruf[$k] . $no . '', $ts->score);
                        $tnh = $tnh + $ts->score;
                    }
                    $k++;
                    $l = $k;
                } else {
                    if (!empty($om)) {
                        $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('' . $huruf[$l] . $no . '', $om[0]->score);
                        $tnh = $tnh + $om[0]->score;
                    }
                    $l++;
                }
                $div++;
            }

            foreach ($kuis as $q) {
                $nq = StudentQuiz::model()->findByAttributes(array('student_id' => $key->student->id, 'quiz_id' => $q->id));
                if (!empty($nq)) {
                    $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('' . $huruf[$l] . $no . '', $nq->score);
                    $tnh = $tnh + $nq->score;
                }
                $l++;
                $div++;
            }

            $rnh = round($tnh / $div);
            //$pnh=round($rnh*$nilai_harian[0]->value/100);

            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('' . $huruf[$l] . $no . '', $rnh);

            //$no1=$objPHPExcel->getActiveSheet()->getCell($huruf[$k].$no)->getValue();
            //$no1=$objPHPExcel->getActiveSheet()->getCell($huruf[$k].$no)->getValue();
            //$avg="=AVERAGE(".$huruf[$k].$no.":".$huruf[$k+$div].$no.")";
            /*$objPHPExcel->setActiveSheetIndex(0)
				            ->setCellValue(''.$huruf[$l].$no.'', $rnh);*/
            /*$objPHPExcel->setActiveSheetIndex(0)
				            ->setCellValue(''.$huruf[$l].$no.'', $rnh);*/
            $l++;
            foreach ($uts as $uks) {
                $squ = StudentQuiz::model()->findByAttributes(array('quiz_id' => $uks->id, 'student_id' => $key->student->id));
                if (!empty($squ)) {
                    $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('' . $huruf[$l] . $no . '', $squ->score);

                    //$tnh=$tnh+$sq->score;
                    $puts = $squ->score;
                }
                $l++;
                $div++;
            }

            //$ruts=round($puts*$nilai_uts[0]->value/100);

            foreach ($uas as $ukk) {
                $sq = StudentQuiz::model()->findByAttributes(array('quiz_id' => $ukk->id, 'student_id' => $key->student->id));
                if (!empty($sq)) {
                    $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('' . $huruf[$l] . $no . '', $sq->score);

                    //$tnh=$tnh+$sq->score;
                    $puas = $sq->score;
                }
                $l++;
                $div++;
            }

            //$ruas=round($puas*$nilai_uas[0]->value/100);

            if ($div > 0 || $div2 > 0) {

                if ($kurikulum_sekolah[0]->value == 2013) {
                    $pnh = ($tnh + $puts + $puas) / ($div + $div2);
                } else {
                    $pnh = $rnh * $nilai_harian[0]->value / 100;
                }
            }

            if ($kurikulum_sekolah[0]->value == 2013) {
                $nrpt = round($pnh);
            } else {
                $ruts = $puts * $nilai_uts[0]->value / 100;
                $ruas = $puas * $nilai_uas[0]->value / 100;
                $nrpt = round($pnh + $ruts + $ruas);
            }

            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('' . $huruf[$l] . $no . '', $nrpt);

            $no++;
            $counter++;
            $cell++;
        }
        $objPHPExcel->getActiveSheet()->getStyle('A12:' . $next[10] . $no++)->applyFromArray($style2);
        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle('Rekap Nilai');


        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);


        // Redirect output to a clientâ€™s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Daftar-Nilai"' . $model->name . '-Kelas"' . $kelas->name . '".xls"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        Yii::app()->end();
    }

    public function actionRekapDownload($id)
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

        $cekFitur = Option::model()->findAll(array('condition' => 'key_config LIKE "%fitur_rekap%"'));
        //$status = 1;

        if (!empty($cekFitur)) {
            if ($cekFitur[0]->value == 2) {
                Yii::app()->user->setFlash('error', 'Fitur Ini Dimatikan !');
                $this->redirect(array('/site/index'));
            }
        }

        if (Yii::app()->session['semester']) {
            $semester = Yii::app()->session['semester'];
        } else {
            $semester = Option::model()->findAll(array('condition' => 'key_config LIKE "%semester%"'));
        }
        if (Yii::app()->session['tahun_ajaran']) {
            $tahun_ajaran = Yii::app()->session['tahun_ajaran'];
        } else {
            $tahun_ajaran = Option::model()->findAll(array('condition' => 'key_config LIKE "%tahun_ajaran%"'));
        }

        $model = $this->loadModel($id);
        if ($model->moving_class == 1) {
            $kelas = Clases::model()->findByPk($model->class_id);
        } else {
            $kelas = ClassDetail::model()->findByPk($model->class_id);
        }
        //$siswa = User::model()->findAll(array('condition'=>'class_id = '.$kelas->id.' and trash is null'));
        $siswa = LessonMc::model()->findAll(array('condition' => 'lesson_id = ' . $id));
        //$tugas = Assignment::model()->findAll(array('condition'=>'lesson_id = '.$id.' and assignment_type is null'));
        $kuis = Quiz::model()->findAll(array('condition' => 'lesson_id = ' . $id . ' AND add_to_summary is null and quiz_type < 1 and semester = ' . $semester[0]->value . ' and year = ' . $tahun_ajaran[0]->value . ' and trash is null'));
        $tugas = Assignment::model()->findAll(array('condition' => 'lesson_id = ' . $id . ' AND add_to_summary is null and trash is null and semester = ' . $semester[0]->value . ' and year = ' . $tahun_ajaran[0]->value));
        $tugasOnline = Assignment::model()->findAll(array('condition' => 'lesson_id = ' . $id . ' and assignment_type is null AND add_to_summary is null and trash is null and semester = ' . $semester[0]->value . ' and year = ' . $tahun_ajaran[0]->value));
        $prefix = Yii::app()->params['tablePrefix'];
        $sql = "select max(jumlah) as jml from (select count(`student_id`) as jumlah from " . $prefix . "offline_mark where lesson_id = 34 and mark_type = 1 group by `student_id`) as penjumlahan";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();

        $uas = Quiz::model()->findAll(array('condition' => 'lesson_id = ' . $id . ' AND add_to_summary is null and quiz_type = 2 and semester = ' . $semester[0]->value . ' and year = ' . $tahun_ajaran[0]->value . ' and trash is null'));

        $uts = Quiz::model()->findAll(array('condition' => 'lesson_id = ' . $id . ' AND add_to_summary is null and quiz_type = 1 and semester = ' . $semester[0]->value . ' and year = ' . $tahun_ajaran[0]->value . ' and trash is null'));
        //$uas=Quiz::model()->findAll(array('condition'=>'lesson_id = '.$id.' AND add_to_summary is null and quiz_type = 2'));
        $prefix = Yii::app()->params['tablePrefix'];
        $sql = "select max(jumlah) as jml from (select count(`student_id`) as jumlah from " . $prefix . "offline_mark where lesson_id = 34 and mark_type = 1 group by `student_id`) as penjumlahan";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        //echo "first_name: ";
        if (!empty($rows)) {
            foreach ($rows as $row) {
                //echo $row['jml'];
                //echo "<br>";
                $row['jml'];
            }
        }
        $result = $row['jml'];

        /*Default DOMPDF File Path*/

        $template_directory = Yii::app()->theme->baseUrl;
        $template_file = Yii::app()->theme->basePath . '/css/print.bootstrap.min.css';
        $inline_style = file_get_contents($template_file);
        $icon_sprite = Yii::app()->theme->basePath . '/images/glyphicons-halflings.png';
        $inline_style = str_replace('../images/glyphicons-halflings.png', $icon_sprite, $inline_style);

        $pdf = Yii::app()->dompdf;
        $pdf->dompdf->set_paper('a4', 'landscape');

        /*$this->renderPartial('/lesson/rekap',array(
			'inline_style'=>$inline_style,
			'model'=>$model,
			'siswa'=>$siswa,
			'tugas'=>$tugas,
			'to'=>$tugasOnline,
			'result'=>$result,
			'kelas'=>$kelas,
			));*/

        $pdf->generate($this->renderPartial('/lesson/rekap', array(
            'inline_style' => $inline_style,
            'model' => $model,
            'siswa' => $siswa,
            'tugas' => $tugas,
            'to' => $tugasOnline,
            'result' => $result,
            'kuis' => $kuis,
            'uas' => $uas,
            'uts' => $uts,
            'kelas' => $kelas,), true, true), 'rekap-nilai-kelas-' . $model->name . '-' . $kelas->name . '.pdf', false);
    }

    public function actionRekapNilai($id)
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

        $cekFitur = Option::model()->findAll(array('condition' => 'key_config LIKE "%fitur_rekap%"'));
        //$status = 1;

        if (!empty($cekFitur)) {
            if ($cekFitur[0]->value == 2) {
                Yii::app()->user->setFlash('error', 'Fitur Ini Dimatikan !');
                $this->redirect(array('/site/index'));
            }
        }

        if (Yii::app()->session['semester']) {
            $semester = Yii::app()->session['semester'];
        } else {
            $semester = Option::model()->findAll(array('condition' => 'key_config LIKE "%semester%"'));
        }
        if (Yii::app()->session['tahun_ajaran']) {
            $tahun_ajaran = Yii::app()->session['tahun_ajaran'];
        } else {
            $tahun_ajaran = Option::model()->findAll(array('condition' => 'key_config LIKE "%tahun_ajaran%"'));
        }

        $assign = new Assignment;
        $model = $this->loadModel($id);
        if ($model->moving_class == 1) {
            $kelas = Clases::model()->findByPk($model->class_id);
        } else {
            $kelas = ClassDetail::model()->findByPk($model->class_id);
        }
        //$siswa = User::model()->findAll(array('condition'=>'class_id = '.$kelas->id.' and trash is null'));
        $siswa = LessonMc::model()->findAll(array('condition' => 'lesson_id = ' . $id));
        //$tugas = Assignment::model()->findAll(array('condition'=>'lesson_id = '.$id.' and assignment_type is null'));
        $kuis = Quiz::model()->findAll(array('condition' => 'lesson_id = ' . $id . ' AND add_to_summary is null and quiz_type < 1 and semester = ' . $semester[0]->value . ' and year = ' . $tahun_ajaran[0]->value . ' and trash is null'));
        $tugas = Assignment::model()->findAll(array('condition' => 'lesson_id = ' . $id . ' AND add_to_summary is null and trash is null and semester = ' . $semester[0]->value . ' and year = ' . $tahun_ajaran[0]->value));
        $tugasOnline = Assignment::model()->findAll(array('condition' => 'lesson_id = ' . $id . ' and assignment_type is null AND add_to_summary is null and trash is null and semester = ' . $semester[0]->value . ' and year = ' . $tahun_ajaran[0]->value));
        $prefix = Yii::app()->params['tablePrefix'];
        $sql = "select max(jumlah) as jml from (select count(`student_id`) as jumlah from " . $prefix . "offline_mark where lesson_id = 34 and mark_type = 1 group by `student_id`) as penjumlahan";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();

        $uas = Quiz::model()->findAll(array('condition' => 'lesson_id = ' . $id . ' AND add_to_summary is null and quiz_type = 2 and semester = ' . $semester[0]->value . ' and year = ' . $tahun_ajaran[0]->value . ' and trash is null'));

        $uts = Quiz::model()->findAll(array('condition' => 'lesson_id = ' . $id . ' AND add_to_summary is null and quiz_type = 1 and semester = ' . $semester[0]->value . ' and year = ' . $tahun_ajaran[0]->value . ' and trash is null'));
        //echo "first_name: ";
        if (!empty($rows)) {
            foreach ($rows as $row) {
                //echo $row['jml'];
                //echo "<br>";
                $row['jml'];
            }
        }
        $result = $row['jml'];

        $this->render('rekap-nilai', array(
            'model' => $model,
            'siswa' => $siswa,
            'tugas' => $tugas,
            'to' => $tugasOnline,
            'result' => $result,
            'assign' => $assign,
            'kuis' => $kuis,
            'kls' => $kelas,
            'uas' => $uas,
            'uts' => $uts,
        ));
    }

    public function actionOfflineTask($id)
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

        $model = new Assignment;
        $notif = new Notification;
        $activity = new Activities;
        $users = User::model()->findByPk(Yii::app()->user->id);
        /*$lessons=new CActiveDataProvider('Lesson',array(
			'criteria'=>array(
				'select'=>'t.*, c.name as class_name',
				'join'=>'JOIN class AS c ON c.id = t.class_id',
				'condition'=>'user_id = '.Yii::app()->user->id.''),
			));*/
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Assignment'])) {
            $model->attributes = $_POST['Assignment'];
            $uploadedFile = CUploadedFile::getInstance($model, 'file');
            $model->file = $uploadedFile;

            date_default_timezone_set("Asia/Jakarta");
            $now_time = date("Y-m-d H:i:s");
            $now_time = new DateTime($now_time);
            $due_date_cek = new DateTime($model->due_date);

            /*if($model->due_date == '0000-00-00 00:00:00'){
				Yii::app()->user->setFlash('error', "Maaf batas akhir pengumpulan tidak boleh kosong!");
				$this->redirect(array('create'));
			}

			if($due_date_cek < $now_time){
				Yii::app()->user->setFlash('error', "Maaf batas akhir pengumpulan tidak boleh kurang dari waktu sekarang!");
				$this->redirect(array('create'));
			}*/

            $model->lesson_id = $id;

            $model->assignment_type = 1;

            if ($model->save()) {
                /*$kelas=Lesson::model()->findByPk($model->lesson_id);
				$notif->content= "Guru ".$users->display_name." Menambah Tugas Baru";
				$notif->user_id=Yii::app()->user->id;
				$notif->relation_id=$model->id;
				$notif->class_id_to=$kelas->class_id;
				$notif->tipe="assignment";
				$notif->save();

				$activity->activity_type="Buat Tugas ".$kelas->name;
				$activity->created_by=Yii::app()->user->id;
				$activity->save();*/
                Yii::app()->user->setFlash('success', 'Tugas Offline Berhasil Dibuat');
                $this->redirect(array('lesson/rekapNilai', 'id' => $id));
            }
        }
        $this->redirect(array('lesson/rekapNilai', 'id' => $id));
    }

    public function actionAddMarkPresensi($id)
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

        $model = $this->loadModel($id);
        $mapel = Lesson::model()->findByAttributes(array('id' => $id));
        $kelas = ClassDetail::model()->findByAttributes(array('id' => $mapel->class_id));

        $prefix = Yii::app()->params['tablePrefix'];

        if (Yii::app()->user->YiiStudent) {
            Yii::app()->user->setFlash('error', "Maaf anda tidak punya izin akses!");
            $this->redirect(array('site/index'));
        }

        if (isset($_POST['save'])) {
            $sql = "SELECT u.id, u.display_name, lm.presensi_hadir, lm.presensi_sakit, lm.presensi_izin, lm.presensi_alfa FROM lesson_mc AS lm JOIN users AS u ON u.id = lm.user_id WHERE lm.semester = " . $optSemester . " AND lm.year = " . $optTahunAjaran . " AND lm.lesson_id = " . $id . " AND u.trash IS NULL ORDER BY u.display_name";

            $command = Yii::app()->db->createCommand($sql);
            $rows = $command->queryAll();

            if (!empty($rows)) {
                $presensiUpdate = 0;
                foreach ($rows as $keys => $value) {
                    $presensiHadir = ($value['presensi_hadir'] == NULL) ? 0 : $value['presensi_hadir'];
                    $presensiSakit = ($value['presensi_sakit'] == NULL) ? 0 : $value['presensi_sakit'];
                    $presensiIzin = ($value['presensi_izin'] == NULL) ? 0 : $value['presensi_izin'];
                    $presensiAlfa = ($value['presensi_alfa'] == NULL) ? 0 : $value['presensi_alfa'];
                    $updateCount = 0;

                    if (isset($_POST['presensi-hadir-' . $value['id']])) {
                        $presensiHadir = $_POST['presensi-hadir-' . $value['id']];

                        if (!empty($presensiHadir)) {
                            $sqlUpdate = "UPDATE " . $prefix . "lesson_mc SET sync_status = 2, presensi_hadir = " . $presensiHadir . " WHERE user_id = " . $value['id'] . " AND lesson_id = " . $id . " AND semester = " . $optSemester . " AND year = " . $optTahunAjaran;
                            $commandUpdate = Yii::app()->db->createCommand($sqlUpdate);

                            if ($commandUpdate->execute()) {
                                $updateCount++;
                            }
                        }
                    }

                    if (isset($_POST['presensi-sakit-' . $value['id']])) {
                        $presensiSakit = $_POST['presensi-sakit-' . $value['id']];

                        if (!empty($presensiSakit)) {
                            $sqlUpdate = "UPDATE " . $prefix . "lesson_mc SET sync_status = 2, presensi_sakit = " . $presensiSakit . " WHERE user_id = " . $value['id'] . " AND lesson_id = " . $id . " AND semester = " . $optSemester . " AND year = " . $optTahunAjaran;
                            $commandUpdate = Yii::app()->db->createCommand($sqlUpdate);

                            if ($commandUpdate->execute()) {
                                $updateCount++;
                            }
                        }
                    }

                    if (isset($_POST['presensi-izin-' . $value['id']])) {
                        $presensiIzin = $_POST['presensi-izin-' . $value['id']];

                        if (!empty($presensiIzin)) {
                            $sqlUpdate = "UPDATE " . $prefix . "lesson_mc SET sync_status = 2, presensi_izin = " . $presensiIzin . " WHERE user_id = " . $value['id'] . " AND lesson_id = " . $id . " AND semester = " . $optSemester . " AND year = " . $optTahunAjaran;
                            $commandUpdate = Yii::app()->db->createCommand($sqlUpdate);

                            if ($commandUpdate->execute()) {
                                $updateCount++;
                            }
                        }
                    }

                    if (isset($_POST['presensi-alfa-' . $value['id']])) {
                        $presensiAlfa = $_POST['presensi-alfa-' . $value['id']];

                        if (!empty($presensiAlfa)) {
                            $sqlUpdate = "UPDATE " . $prefix . "lesson_mc SET sync_status = 2, presensi_alfa = " . $presensiAlfa . " WHERE user_id = " . $value['id'] . " AND lesson_id = " . $id . " AND semester = " . $optSemester . " AND year = " . $optTahunAjaran;
                            $commandUpdate = Yii::app()->db->createCommand($sqlUpdate);

                            if ($commandUpdate->execute()) {
                                $updateCount++;
                            }
                        }
                    }

                    if ($updateCount > 0) {
                        $presensiUpdate++;
                    }
                }

                Yii::app()->user->setFlash('success', 'Update ' . $presensiUpdate . ' Presensi Siswa di Pelajaran Berhasil !');
                $this->redirect(array('lesson/presensi', 'id' => $id));
            } else {
                $this->redirect(array('lesson/presensi', 'id' => $id));
            }
        }
    }

    public function actionAddMark($id)
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

        $mark = new OfflineMark;
        $nilai = $_POST['nilai'];
        $siswa = $_POST['id'];
        $aid = $_POST['assignment_id'];
        $result = array_combine($siswa, $nilai);
        $current_user = Yii::app()->user->id;
        $prefix = Yii::app()->params['tablePrefix'];
        $ida = 41;
        $mt = 1;
        $dt = 0;
        //echo "<pre>";
        foreach ($result as $key => $value) {
            if (!empty($value)) {
                $cek = OfflineMark::model()->findByAttributes(array('student_id' => $key, 'assignment_id' => $aid));
                if (!empty($cek)) {
                    $sql = "UPDATE " . $prefix . "offline_mark SET sync_status = 2, score = :score, updated_at = NOW(), updated_by = :updated_by WHERE student_id = :sid AND assignment_id = :aid";
                    $command = Yii::app()->db->createCommand($sql);
                    $command->bindParam(":aid", $aid, PDO::PARAM_STR);
                    $command->bindParam(":sid", $key, PDO::PARAM_STR);
                    $command->bindParam(":updated_by", $current_user, PDO::PARAM_STR);
                    $command->bindParam(":score", $value, PDO::PARAM_STR);
                    if ($command->execute()) {
                        $dt++;
                    }
                } else {
                    $sql = "INSERT INTO " . $prefix . "offline_mark (lesson_id, assignment_id, student_id, created_at, updated_at, created_by, score, mark_type) VALUES(:lid,:aid,:sid,NOW(),NOW(),:created_by,:score,:mark_type)";
                    $command = Yii::app()->db->createCommand($sql);
                    $command->bindParam(":lid", $id, PDO::PARAM_STR);
                    $command->bindParam(":aid", $aid, PDO::PARAM_STR);
                    $command->bindParam(":sid", $key, PDO::PARAM_STR);
                    $command->bindParam(":created_by", $current_user, PDO::PARAM_STR);
                    $command->bindParam(":score", $value, PDO::PARAM_STR);
                    $command->bindParam(":mark_type", $mt, PDO::PARAM_STR);
                    if ($command->execute()) {
                        $dt++;
                    }
                }
            }

        }
        //print_r($result);
        //print_r($siswa);
        //echo count($result);
        //echo "</pre>";
        Yii::app()->user->setFlash('success', 'Input Nilai ' . $dt . ' Siswa Berhasil !');
        $this->redirect(array('lesson/rekapNilai', 'id' => $id));
    }

    public function actionBulk()
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

        $model = new Activities;


        if (isset($_POST['Activities'])) {
            $model->attributes = $_POST['Activities'];
            //$filelist=CUploadedFile::getInstancesByName('csvfile');
            $xlsFile = CUploadedFile::getInstancesByName('csvfile');
            $prefix = Yii::app()->params['tablePrefix'];
            $sukses = 0;
            $fail = 0;
            // To validate

            if ($model->validate()) {
                $cek_id = "";
                foreach ($xlsFile as $file) {
                    try {
                        $transaction = Yii::app()->db->beginTransaction();
                        $data = Yii::app()->yexcel->readActiveSheet($file->tempName, "r");
                        $data_raw = array();
                        $raw = array();
                        $gambar = array();
                        $pilihan = NULL;
                        $row = 1;
                        //print_r($data);
                        $highestRow = $data->getHighestRow();
                        $highestColumn = $data->getHighestColumn();
                        $range = 'A2:AM' . $highestRow . '';
                        //$text = $data->toArray(null, true, true, true);
                        $text = $data->rangeToArray($range);
                        $head = $data->rangeToArray('A1:AM1');


                        foreach ($text as $key => $val) {
                            // //echo "<pre>";
                            // //print_r($val);
                            $column = array_combine($head[0], $val);
                            // $row2 = $row;
                            $column = array_filter($column);
                            // echo "<pre>";
                            // print_r($column);
                            // echo "</pre>";

                            $cekNama = LessonList::model()->findByPk($column['id Pel']);

                            $nama = $cekNama->name;
                            $kelompok = $cekNama->group;

                            $id_guru = $column['id Guru'];
                            $list_id = $column['id Pel'];
                            unset($column['No']);
                            unset($column['id Guru']);
                            unset($column['Nama Guru']);
                            unset($column['id Pel']);
                            unset($column['Nama Pelajaran']);

                            // echo "<pre>";
                            // print_r($column);
                            // echo "</pre>";

                            $insert = array();
                            foreach ($column as $c_key => $c_value) {
                                $admin_id = "1";

                                $insert = "INSERT INTO " . $prefix . "lesson (name,user_id,class_id,created_at,updated_at,created_by,kelompok,list_id,semester,year) values(:name,:user_id,:class_id,NOW(),NOW(),:uid,:kelompok,:list_id,:semester,:year)";

                                $insertCommand = Yii::app()->db->createCommand($insert);

                                $insertCommand->bindParam(":name", $nama, PDO::PARAM_STR);
                                $insertCommand->bindParam(":user_id", $id_guru, PDO::PARAM_STR);
                                $insertCommand->bindParam(":class_id", $c_key, PDO::PARAM_STR);
                                $insertCommand->bindParam(":uid", $admin_id, PDO::PARAM_STR);
                                $insertCommand->bindParam(":kelompok", $kelompok, PDO::PARAM_STR);
                                $insertCommand->bindParam(":list_id", $list_id, PDO::PARAM_STR);
                                $insertCommand->bindParam(":semester", $optSemester, PDO::PARAM_STR);
                                $insertCommand->bindParam(":year", $optTahunAjaran, PDO::PARAM_STR);


                                if ($insertCommand->execute()) {
                                    $sukses++;

                                } else {
                                    $fail++;
                                }
                            }

                            $row++;
                            //$urutan++;
                        }
                        Yii::app()->user->setFlash('success', "Import " . $sukses . " Pelajaran Berhasil !");
                        $transaction->commit();

                    } catch (Exception $error) {
                        //echo "<pre>";
                        //print_r($error);
                        //echo "</pre>";
                        $transaction->rollback();
                        throw new CHttpException($error);
                    }
                }
                //$this->redirect(array('import_form'));
            } else {
                Yii::app()->user->setFlash('error', "Import Gagal!");
            }
        }

        /*if (!empty($sukses)){
			$row2 = $row - 1;
			Yii::app()->user->setFlash('success', "Berhasil buat $row2 record kelas!");
		}*/


        $this->render('bulk', array(
            'model' => $model,

        ));

    }

    public function actionBulkList()
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

        $model = new Activities;


        if (isset($_POST['Activities'])) {
            $model->attributes = $_POST['Activities'];
            //$filelist=CUploadedFile::getInstancesByName('csvfile');
            $xlsFile = CUploadedFile::getInstancesByName('csvfile');
            $prefix = Yii::app()->params['tablePrefix'];
            $sukses = 0;
            $fail = 0;
            // To validate

            if ($model->validate()) {
                $cek_id = "";
                foreach ($xlsFile as $file) {
                    try {
                        $transaction = Yii::app()->db->beginTransaction();
                        $data = Yii::app()->yexcel->readActiveSheet($file->tempName, "r");
                        $data_raw = array();
                        $raw = array();
                        $gambar = array();
                        $pilihan = NULL;
                        $row = 1;
                        //print_r($data);
                        $highestRow = $data->getHighestRow();
                        $highestColumn = $data->getHighestColumn();
                        $range = 'A2:H' . $highestRow . '';
                        //$text = $data->toArray(null, true, true, true);
                        $text = $data->rangeToArray($range);
                        $head = $data->rangeToArray('A1:H1');

                        foreach ($text as $key => $val) {
                            //echo "<pre>";
                            //print_r($val);
                            $column = array_combine($head[0], $val);
                            $row2 = $row;
                            //print_r($column);
                            //print_r($coordinate[1]);
                            //print_r($gambar);
                            //echo "</pre>";

                            if (!empty($column['MAPEL'])) {
                                $nama = $column['MAPEL'];
                                $kelompok = $column['KELOMPOK'];
                                $group = 0;
                                if (empty($nama)) {
                                    Yii::app()->user->setFlash('error', "Baris $row2 . kolom mapel harus di isi");
                                    $this->redirect(array('bulk'));
                                }

                                if (!empty($kelompok)) {
                                    if (strtolower($kelompok) == 'a') {
                                        $group = 1;
                                    } elseif (strtolower($kelompok) == 'b') {
                                        $group = 2;
                                    } else {
                                        $group = 3;
                                    }
                                }

                                $list = new LessonList;

                                $list->name = $nama;
                                $list->group = $group;

                                if ($list->save()) {
                                    $sukses++;
                                }

                                /*$insert="INSERT INTO ".$prefix."lesson_list (name,group) values(:name,:group)";

				 				$insertCommand=Yii::app()->db->createCommand($insert);

								$insertCommand->bindParam(":name",$nama,PDO::PARAM_STR);
								$insertCommand->bindParam(":group",$group,PDO::PARAM_STR);

								if($insertCommand->execute()){
									$sukses++;

								}else{
									$fail++;
								}*/

                            }

                            $row++;
                            //$urutan++;
                        }
                        Yii::app()->user->setFlash('success', "Import " . $sukses . " Pelajaran Berhasil !");
                        $transaction->commit();

                    } catch (Exception $error) {
                        //echo "<pre>";
                        //print_r($error);
                        //echo "</pre>";
                        $transaction->rollback();
                        throw new CHttpException($error);
                    }


                }
                //$this->redirect(array('import_form'));
            } else {
                Yii::app()->user->setFlash('error', "Import Gagal!");
            }
        }

        /*if (!empty($sukses)){
			$row2 = $row - 1;
			Yii::app()->user->setFlash('success', "Berhasil buat $row2 record kelas!");
		}*/


        $this->render('bulk-2', array(
            'model' => $model,

        ));

    }

    public function actionDownloadFile()
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

        $dir_path = Yii::getPathOfAlias('webroot') . '/images/';

        $filePlace = Yii::app()->basePath . '/../images/format-import-mapel.xlsx';
        //echo $fileName;
        $fileName = "format-import-mapel.xlsx";

        if (file_exists($filePlace)) {
            return Yii::app()->getRequest()->sendFile($fileName, @file_get_contents($filePlace));
        } else {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
    }

    public function actionPresensi($id)
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

        $model = $this->loadModel($id);
        $mapel = Lesson::model()->findByAttributes(array('id' => $id));
        $kelas = ClassDetail::model()->findByAttributes(array('id' => $mapel->class_id));

        if (Yii::app()->user->YiiStudent) {
            Yii::app()->user->setFlash('error', "Maaf anda tidak punya izin akses!");
            $this->redirect(array('site/index'));
        }

        $presensi = NULL;

        $sql = "SELECT u.id, u.display_name, lm.presensi_hadir, lm.presensi_sakit, lm.presensi_izin, lm.presensi_alfa FROM lesson_mc AS lm JOIN users AS u ON u.id = lm.user_id WHERE lm.semester = " . $optSemester . " AND lm.year = " . $optTahunAjaran . " AND lm.lesson_id = " . $id . " AND u.trash IS NULL ORDER BY u.display_name";

        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();

        if (!empty($rows)) {
            $presensi = $rows;
        }

        $this->render('presensi', array(
            'model' => $model,
            'mapel' => $mapel,
            'kelas' => $kelas,
            'presensi' => $presensi,
        ));
    }

    public function actionNilaiKetSikap($id)
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

        $model = $this->loadModel($id);
        $mapel = Lesson::model()->findByAttributes(array('id' => $id));
        $kelas = ClassDetail::model()->findByAttributes(array('id' => $mapel->class_id));

        if (Yii::app()->user->YiiStudent) {
            Yii::app()->user->setFlash('error', "Maaf anda tidak punya izin akses!");
            $this->redirect(array('site/index'));
        }

        if ($model->moving_class == 1) {
            $lmc = LessonMc::model()->findAll(array("condition" => "semester = " . $optSemester . " and year = " . $optTahunAjaran . " and lesson_id = " . $id));
            $used_students = NULL;
            if (!empty($lmc)) {
                $used_id = array();
                foreach ($lmc as $mc) {
                    array_push($used_id, $mc->user_id);
                }
                $used_students = implode(',', $used_id);
            }
            $list_big = NULL;
            $final_list = array();

            if ($model->moving_class == 1) {
                $big_class = ClassDetail::model()->findAll(array('condition' => 'class_id = ' . $model->class_id));

                if (!empty($big_class)) {
                    foreach ($big_class as $bc) {
                        array_push($final_list, $bc->id);
                    }
                    $list_big = implode(',', $final_list);
                }

            }

            if ($used_students != NULL) {
                if ($model->moving_class == 1) {
                    // $siswa = User::model()->findAll(array('condition'=>'role_id = 2 and trash is null and class_id IN ('.$list_big.') and id in ('.$used_students.')',"order" => "display_name"));
                } else {
                    $raw_students = User::model()->findAll(array('condition' => 'role_id = 2 and trash is null and class_id = ' . $model->class_id . ' and id in (' . $used_students . ')', "order" => "display_name"));
                }

            } else {
                if ($model->moving_class == 1) {
                    // $siswa = User::model()->findAll(array('condition'=>'role_id = 2 and trash is null and class_id IN ('.$list_big.')',"order" => "display_name"));
                } else {
                    $raw_students = User::model()->findAll(array('condition' => 'role_id = 2 and trash is null and class_id = ' . $model->class_id, "order" => "display_name"));
                }

            }
        } else {
            // $siswa = User::model()->findAll(array('condition'=>'class_id = '.$kelas->id,"order" => "display_name"));
        }

        $sql = "SELECT l.`id`,l.`name`,fm.`nilai`,fm.`nilai_desc`,fm.`tipe`,l.`kelompok`,l.`list_id`,u.`id` user_id,u.`display_name`
				FROM `final_mark` as fm
				join `users` as u on u.`id` = fm.`user_id`
				join `lesson` as l on fm.`lesson_id` = l.`id`
				WHERE l.`id` = " . $model->id . "
				and l.semester = " . $optSemester . "
				and l.year = " . $optTahunAjaran . "
				and u.trash is null
				order by l.`list_id`";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();

        $nilai_arr = array();

        foreach ($rows as $key => $item) {
            // $item["nilai-".$item["tipe"]] = $item["nilai"];
            // $arr[$item['id']]["name"] = $item["name"];
            // $arr[$item['id']]["kelompok"] = $item["kelompok"];
            // $arr[$item['id']]["nilai-".$item["tipe"]] = $item["nilai"];

            $nilai_arr[$item["user_id"]]["kelompok"] = $item["kelompok"];
            $nilai_arr[$item["user_id"]]["nilai-" . $item["tipe"]] = $item["nilai"];
            $nilai_arr[$item["user_id"]]["desc-" . $item["tipe"]] = $item["nilai_desc"];
        }

        // echo "<pre>";
        // 	print_r($nilai_arr);
        // echo "</pre>";

        $this->render('nilai-ketsikap', array(
            'model' => $this->loadModel($id),
            'nilai_arr' => $nilai_arr,
            // 'siswa'=>$siswa,
        ));
    }

    public function actionNilaiKetSikapDua($id)
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

        $model = $this->loadModel($id);
        $mapel = Lesson::model()->findByAttributes(array('id' => $id));
        $kelas = ClassDetail::model()->findByAttributes(array('id' => $mapel->class_id));

        if (Yii::app()->user->YiiStudent) {
            Yii::app()->user->setFlash('error', "Maaf anda tidak punya izin akses!");
            $this->redirect(array('site/index'));
        }

        if ($model->moving_class == 1) {
            $lmc = LessonMc::model()->findAll(array("condition" => "semester = " . $optSemester . " and year = " . $optTahunAjaran . " and lesson_id = " . $id));
            $used_students = NULL;
            if (!empty($lmc)) {
                $used_id = array();
                foreach ($lmc as $mc) {
                    array_push($used_id, $mc->user_id);
                }
                $used_students = implode(',', $used_id);
            }
            $list_big = NULL;
            $final_list = array();

            if ($model->moving_class == 1) {
                $big_class = ClassDetail::model()->findAll(array('condition' => 'class_id = ' . $model->class_id));

                if (!empty($big_class)) {
                    foreach ($big_class as $bc) {
                        array_push($final_list, $bc->id);
                    }
                    $list_big = implode(',', $final_list);
                }

            }

            if ($used_students != NULL) {
                if ($model->moving_class == 1) {
                    $siswa = User::model()->findAll(array('condition' => 'role_id = 2 and trash is null and class_id IN (' . $list_big . ') and id in (' . $used_students . ')', "order" => "display_name"));
                } else {
                    $raw_students = User::model()->findAll(array('condition' => 'role_id = 2 and trash is null and class_id = ' . $model->class_id . ' and id in (' . $used_students . ')', "order" => "display_name"));
                }

            } else {
                if ($model->moving_class == 1) {
                    $siswa = User::model()->findAll(array('condition' => 'role_id = 2 and trash is null and class_id IN (' . $list_big . ')', "order" => "display_name"));
                } else {
                    $raw_students = User::model()->findAll(array('condition' => 'role_id = 2 and trash is null and class_id = ' . $model->class_id, "order" => "display_name"));
                }

            }
        } else {
            $siswa = User::model()->findAll(array('condition' => 'class_id = ' . $kelas->id, "order" => "display_name"));
        }

        $sql = "SELECT l.`id`,l.`name`,fm.`nilai`,fm.`nilai_desc`,fm.`tipe`,l.`kelompok`,l.`list_id`,u.`id` user_id,u.`display_name`
				FROM `final_mark` as fm
				join `users` as u on u.`id` = fm.`user_id`
				join `lesson` as l on fm.`lesson_id` = l.`id`
				WHERE l.`id` = " . $model->id . "
				and l.semester = " . $optSemester . "
				and l.year = " . $optTahunAjaran . "
				and u.trash is null
				order by l.`list_id`";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();

        $nilai_arr = array();

        foreach ($rows as $key => $item) {
            // $item["nilai-".$item["tipe"]] = $item["nilai"];
            // $arr[$item['id']]["name"] = $item["name"];
            // $arr[$item['id']]["kelompok"] = $item["kelompok"];
            // $arr[$item['id']]["nilai-".$item["tipe"]] = $item["nilai"];

            $nilai_arr[$item["user_id"]]["kelompok"] = $item["kelompok"];
            $nilai_arr[$item["user_id"]]["nilai-" . $item["tipe"]] = $item["nilai"];
            $nilai_arr[$item["user_id"]]["desc-" . $item["tipe"]] = $item["nilai_desc"];
        }

        // echo "<pre>";
        // 	print_r($nilai_arr);
        // echo "</pre>";

        $this->render('nilai-ketsikap-dua', array(
            'model' => $this->loadModel($id),
            'nilai_arr' => $nilai_arr,
            'siswa' => $siswa,
        ));
    }

    public function actionManageKd()
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

        $sql = "SELECT distinct(l.list_id) id, ls.name lesson_name, c.name level
				FROM lesson as l
				LEFT JOIN lesson_list as ls
				ON l.list_id =ls.id
				LEFT JOIN class_detail as cd
				ON l.class_id =cd.id
				LEFT JOIN class as c
				ON cd.class_id =c.id

				where l.trash is null
				and l.semester = " . $optSemester . "
				and l.year = " . $optTahunAjaran . "
				;";

        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();

        // echo "<pre>";
        // 	print_r($rows);
        // echo "</pre>";
        $this->render('managekd', array(
            'dataProvider' => $rows,
        ));

    }


    public function actionNilaiKd($id)
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

        $nama_sekolah = Option::model()->findAll(array('condition' => 'key_config LIKE "%school_name%"'));
        $nama_sekolah = $nama_sekolah[0]->value;

        $mapel = Lesson::model()->findByAttributes(array('id' => $id, 'semester' => $optSemester, 'year' => $optTahunAjaran));
        // $kelas=LessonList::model()->findByAttributes(array('id'=>$id));

        if (Yii::app()->user->YiiStudent) {
            Yii::app()->user->setFlash('error', "Maaf anda tidak punya izin akses!");
            $this->redirect(array('site/index'));
        }

        //$siswa = User::model()->findAll(array('condition'=>'class_id = '.$kelas->id));

        if ($nama_sekolah == "SMP Darul Hikam"){
            $siswa = array('KD1', 'KD2', 'KD3', 'KD4', 'KD5');
        } else {    
            $siswa = array('KD1', 'KD2', 'KD3', 'KD4', 'KD5', 'KD6', 'KD7');
        }   
        
        $this->render('nilai-kd', array(
            'model' => $this->loadModel($id),
            'siswa' => $siswa
        ));


    }

    public function actionNilaiKdDua($id)
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

        $mapel = Lesson::model()->findByAttributes(array('id' => $id, 'semester' => $optSemester, 'year' => $optTahunAjaran));
        // $kelas=LessonList::model()->findByAttributes(array('id'=>$id));

        if (Yii::app()->user->YiiStudent) {
            Yii::app()->user->setFlash('error', "Maaf anda tidak punya izin akses!");
            $this->redirect(array('site/index'));
        }

        //$siswa = User::model()->findAll(array('condition'=>'class_id = '.$kelas->id));
        $siswa = array('KD1_ket', 'KD2_ket', 'KD3_ket', 'KD4_ket', 'KD5_ket', 'KD6_ket', 'KD7_ket');
        $this->render('nilai-kd-dua', array(
            'model' => $this->loadModel($id),
            'siswa' => $siswa
        ));

    }


    public function actionImportnilainuts()
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

        $model = new Activities;

        $sukses = 0;
        $fail = 0;


        if (isset($_POST['Activities'])) {
            $model->attributes = $_POST['Activities'];
            //$filelist=CUploadedFile::getInstancesByName('csvfile');
            $xlsFile = CUploadedFile::getInstancesByName('csvfile');
            $prefix = Yii::app()->params['tablePrefix'];

            // To validate
            $urutan = 1;
            if ($model->validate()) {
                $cek_id = "";
                foreach ($xlsFile as $file) {
                    try {
                        $transaction = Yii::app()->db->beginTransaction();
                        $data = Yii::app()->yexcel->readActiveSheet($file->tempName, "r");
                        $data_raw = array();
                        $raw = array();
                        $gambar = array();
                        $pilihan = NULL;
                        $row = 1;
                        //print_r($data);
                        $highestRow = $data->getHighestRow();
                        $highestColumn = $data->getHighestColumn();
                        $range = 'A2:L' . $highestRow . '';
                        //$text = $data->toArray(null, true, true, true);
                        $text = $data->rangeToArray($range);
                        $head = $data->rangeToArray('A1:L1');

                        foreach ($text as $key => $val) {
                            //echo "<pre>";
                            //print_r($val);
                            $column = array_combine($head[0], $val);
                            $row2 = $row;
                            //print_r($column);
                            //print_r($coordinate[1]);
                            //print_r($gambar);
                            //echo "</pre>";
                            // $var =$column['Nilai Harian'];
                            // if (!empty($var) or $var !='0') {
                            // 	// $column['Nilai Harian'] = "0";
                            // 	echo $var . ' is empty';
                            // 	//echo $column['Nilai Harian'];
                            // 	echo "</br>";
                            // }
                            if ($column['Nilai Harian'] == '0') {
                                $column['Nilai Harian'] = 1;
                            }

                            if ($column['Nilai UTS'] == '0') {
                                $column['Nilai UTS'] = 1;
                            }

                            if (!empty($column['NO INDUK']) and !empty($column['Nilai Harian']) and !empty($column['Nilai UTS']) and !empty($column['ID MAPEL'])) {
                                //$nik = $column['nip'];
                                $nama = $column['NAMA PESERTA DIDIK'];
                                //$email = $column['email'];
                                //$password = $column['password'];

                                $length = 5;
                                $length2 = 9;
                                $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                                $characters2 = '0123456789';
                                $charactersLength = strlen($characters);
                                $charactersLength2 = strlen($characters2);
                                $randomString = '';
                                $randomNik = '';
                                // for ($i = 0; $i < $length; $i++) {
                                //     $randomString .= $characters[rand(0, $charactersLength - 1)];
                                // }

                                // for ($x = 0; $x < $length2; $x++) {
                                //     $randomNik .= $characters2[rand(0, $charactersLength2 - 1)];
                                // }

                                // $password = $randomString;
                                // $rawrole = $column['ROLE'];
                                // if(strtolower($rawrole) == "guru"){
                                // 	$role = 1;
                                // }elseif(strtolower($rawrole) == "siswa"){
                                // 	$role = 2;
                                // }elseif(strtolower($rawrole) == "walikelas"){
                                // 	$role = 4;
                                // }elseif(strtolower($rawrole) == "kepalasekolah"){
                                // 	$role = 5;
                                // }else{
                                // 	$role = 2;
                                // }

                                //$kelas = $column['ID_KELAS'];
                                // if(empty($column['EMAIL'])){
                                // 	$email = $randomString."@mail.id";
                                // }else{
                                // 	$email = $column['EMAIL'];
                                // }

                                $nik = $column['NO INDUK'];
                                $id_mapel = $column['ID MAPEL'];
                                $nik_final = str_replace('-', '', trim($nik));
                                $tipe1 = 'rnh';
                                $tipe2 = 'uts';
                                $rnh = $column['Nilai Harian'];
                                $uts = $column['Nilai UTS'];

                                $siswa = User::model()->findAll(array('condition' => 'username = ' . $nik_final));

                                $cekUts = FinalMark::model()->findAll(array("condition" => "user_id = " . $siswa[0]->id . " AND lesson_id = " . $id_mapel . " AND tipe = 'uts' AND semester = '" . $optSemester . "' AND tahun_ajaran = '" . $optTahunAjaran . "'"));
                                $cekRnh = FinalMark::model()->findAll(array("condition" => "user_id = " . $siswa[0]->id . " AND lesson_id = " . $id_mapel . " AND tipe = 'rnh' AND semester = '" . $optSemester . "' AND tahun_ajaran = '" . $optTahunAjaran . "'"));

                                // $ph=new PasswordHash(Yii::app()->params['phpass']['iteration_count_log2'], Yii::app()->params['phpass']['portable_hashes']);
                                // $passwd = $ph->HashPassword($password);

                                // if ($role == 2){
                                // 	if (empty($kelas)){
                                // 		Yii::app()->user->setFlash('error', "Baris $row2 . kolom kelas harus di isi");
                                // 		$this->redirect(array('bulk'));
                                // 	}
                                // }

                                if (empty($nama)) {
                                    Yii::app()->user->setFlash('error', "Baris $row2 . kolom nama harus di isi");
                                    $this->redirect(array('bulk'));
                                }


                                // if (empty($password)){
                                // 	Yii::app()->user->setFlash('error', "Baris $row2 . kolom password harus di isi");
                                // 	$this->redirect(array('bulk'));
                                // }

                                // if (empty($role)){
                                // 	Yii::app()->user->setFlash('error', "Baris $row2 . kolom role harus di isi");
                                // 	$this->redirect(array('bulk'));
                                // }

                                // if (!is_numeric($role)){
                                // 	Yii::app()->user->setFlash('error', "Baris $row2 . kolom role harus numeric (foreign key)");
                                // 	$this->redirect(array('bulk'));
                                // }

                                // if (!empty($kelas) and !is_numeric($kelas)){
                                // 	Yii::app()->user->setFlash('error', "Baris $row2 . kolom kelas harus numeric (foreign key)");
                                // 	$this->redirect(array('bulk'));
                                // }

                                // $cekUE=User::model()->findAll(array("condition"=>"username = '$nik' or email = '$email'"));

                                // if (!empty($cekUE)){
                                // 	Yii::app()->user->setFlash('error', "Baris $row2 . username atau email sudah terdaftar di database");
                                // 	$this->redirect(array('bulk'));
                                // }


                                if (empty($cekRnh)) {
                                    $insert1 = "INSERT INTO " . $prefix . "final_mark (user_id,lesson_id,tipe,semester,tahun_ajaran,nilai,created_at,updated_at,created_by,updated_by) values('" . $siswa[0]->id . "','" . $id_mapel . "','" . $tipe1 . "',$optSemester,$optTahunAjaran,'" . $rnh . "',NOW(),NOW(),'" . Yii::app()->user->id . "','" . Yii::app()->user->id . "')";

                                    $insertCommand1 = Yii::app()->db->createCommand($insert1);


                                    if ($insertCommand1->execute()) {
                                        $sukses++;
                                    } else {
                                        $fail++;
                                    }
                                } else {
                                    $sql1 = "UPDATE " . $prefix . "final_mark SET sync_status = 2, nilai = " . $rnh . ", updated_at = NOW(), updated_by = " . Yii::app()->user->id . " WHERE user_id = " . $siswa[0]->id . " AND lesson_id = " . $id_mapel . " AND tipe = 'rnh' AND semester = '" . $optSemester . "' AND tahun_ajaran = '" . $optTahunAjaran . "'";
                                    $command1 = Yii::app()->db->createCommand($sql1);
                                    if ($command1->execute()) {
                                        $sukses++;
                                    } else {
                                        $fail++;
                                    }
                                }


                                if (empty($cekUts)) {
                                    $insert2 = "INSERT INTO " . $prefix . "final_mark (user_id,lesson_id,tipe,semester,tahun_ajaran,nilai,created_at,updated_at,created_by,updated_by) values('" . $siswa[0]->id . "','" . $id_mapel . "','" . $tipe2 . "',$optSemester,$optTahunAjaran,'" . $uts . "',NOW(),NOW(),'" . Yii::app()->user->id . "','" . Yii::app()->user->id . "')";

                                    $insertCommand2 = Yii::app()->db->createCommand($insert2);

                                    if ($insertCommand2->execute()) {
                                        $sukses++;
                                    } else {
                                        $fail++;
                                    }
                                } else {
                                    $sql2 = "UPDATE " . $prefix . "final_mark SET sync_status = 2, nilai = " . $uts . ", updated_at = NOW(), updated_by = " . Yii::app()->user->id . " WHERE user_id = " . $siswa[0]->id . " AND lesson_id = " . $id_mapel . " AND tipe = 'uts' AND semester = '" . $optSemester . "' AND tahun_ajaran = '" . $optTahunAjaran . "'";
                                    $command2 = Yii::app()->db->createCommand($sql2);
                                    if ($command2->execute()) {
                                        $sukses++;
                                    } else {
                                        $fail++;
                                    }
                                }


                            }

                            $row++;
                            $urutan++;
                        }
                        Yii::app()->user->setFlash('success', "Berhasil Import " . $sukses . " Nilai Siswa, Gagal import  " . $fail . " nilai !");
                        $transaction->commit();

                    } catch (Exception $error) {
                        //echo "<pre>";
                        //print_r($error);
                        //echo "</pre>";
                        $transaction->rollback();
                        throw new CHttpException($error);
                    }
                }
                //$this->redirect(array('import_form'));
            } else {
                Yii::app()->user->setFlash('error', "Import Gagal!");
            }
        }

        /*if (!empty($sukses)){
			$row2 = $row - 1;
			Yii::app()->user->setFlash('success', "Berhasil buat $row2 record user!");
		}*/


        $this->render('importnilainuts', array(
            'model' => $model,

        ));

    }

    public function actionImportnilaiuas()
    {
        $optSemester = Option::model()->findByAttributes(array('key_config' => 'semester'))->value;
        $optTahunAjaran = Option::model()->findByAttributes(array('key_config' => 'tahun_ajaran'))->value;

        $model = new Activities;

        $sukses = 0;
        $fail = 0;


        if (isset($_POST['Activities'])) {
            $model->attributes = $_POST['Activities'];
            //$filelist=CUploadedFile::getInstancesByName('csvfile');
            $xlsFile = CUploadedFile::getInstancesByName('csvfile');
            $prefix = Yii::app()->params['tablePrefix'];

            // To validate
            $urutan = 1;
            if ($model->validate()) {
                $cek_id = "";
                foreach ($xlsFile as $file) {
                    try {
                        $transaction = Yii::app()->db->beginTransaction();
                        $data = Yii::app()->yexcel->readActiveSheet($file->tempName, "r");
                        $data_raw = array();
                        $raw = array();
                        $gambar = array();
                        $pilihan = NULL;
                        $row = 1;
                        //print_r($data);
                        $highestRow = $data->getHighestRow();
                        $highestColumn = $data->getHighestColumn();
                        $range = 'A2:O' . $highestRow . '';
                        //$text = $data->toArray(null, true, true, true);
                        $text = $data->rangeToArray($range);
                        $head = $data->rangeToArray('A1:O1');

                        foreach ($text as $key => $val) {
                            //echo "<pre>";
                            //print_r($val);
                            $column = array_combine($head[0], $val);
                            $row2 = $row;
                            //print_r($column);
                            //print_r($coordinate[1]);
                            //print_r($gambar);
                            //echo "</pre>";
                            // $var =$column['Nilai Harian'];
                            // if (!empty($var) or $var !='0') {
                            // 	// $column['Nilai Harian'] = "0";
                            // 	echo $var . ' is empty';
                            // 	//echo $column['Nilai Harian'];
                            // 	echo "</br>";
                            // }
                            if ($column['Angka Ptahu'] == '0') {
                                $column['Angka Ptahu'] = 1;
                            }

                            if ($column['Angka Ktrmp'] == '0') {
                                $column['Angka Ktrmp'] = 1;
                            }

                            // if (!empty($column['NO. INDUK'])  and !empty($column['Angka Ptahu']) and !empty($column['Angka Ktrmp']) and !empty($column['KODE MAPEL']) and !empty($column['DSK TRMPL']) and !empty($column['DSK PTAHU'])  ){
                            if (!empty($column['NO. INDUK']) and !empty($column['KODE MAPEL'])) {
                                //$nik = $column['nip'];
                                $nama = $column['N A M A'];
                                //$email = $column['email'];
                                //$password = $column['password'];

                                $length = 5;
                                $length2 = 9;
                                $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                                $characters2 = '0123456789';
                                $charactersLength = strlen($characters);
                                $charactersLength2 = strlen($characters2);
                                $randomString = '';
                                $randomNik = '';
                                // for ($i = 0; $i < $length; $i++) {
                                //     $randomString .= $characters[rand(0, $charactersLength - 1)];
                                // }

                                // for ($x = 0; $x < $length2; $x++) {
                                //     $randomNik .= $characters2[rand(0, $charactersLength2 - 1)];
                                // }

                                // $password = $randomString;
                                // $rawrole = $column['ROLE'];
                                // if(strtolower($rawrole) == "guru"){
                                // 	$role = 1;
                                // }elseif(strtolower($rawrole) == "siswa"){
                                // 	$role = 2;
                                // }elseif(strtolower($rawrole) == "walikelas"){
                                // 	$role = 4;
                                // }elseif(strtolower($rawrole) == "kepalasekolah"){
                                // 	$role = 5;
                                // }else{
                                // 	$role = 2;
                                // }

                                //$kelas = $column['ID_KELAS'];
                                // if(empty($column['EMAIL'])){
                                // 	$email = $randomString."@mail.id";
                                // }else{
                                // 	$email = $column['EMAIL'];
                                // }

                                $nik = $column['NO. INDUK'];
                                $id_mapel = $column['KODE MAPEL'];
                                $nik_final = str_replace('-', '', trim($nik));
                                $tipe1 = 'pengetahuan';
                                $tipe2 = 'keterampilan';
                                $tipe3 = 'desc_pengetahuan';
                                $tipe4 = 'desc_keterampilan';
                                if (!empty($column['Angka Ptahu'])) {
                                    $pengetahuan = $column['Angka Ptahu'];
                                } else {
                                    $pengetahuan = "10";
                                }

                                if (!empty($column['Angka Ktrmp'])) {
                                    $keterampilan = $column['Angka Ktrmp'];
                                } else {
                                    $keterampilan = "10";
                                }

                                if (!empty($column['DSK PTAHU'])) {
                                    $desc_pengetahuan = $column['DSK PTAHU'];
                                } else {
                                    $desc_pengetahuan = "-";
                                }

                                if (!empty($column['DSK TRMPL'])) {
                                    $desc_keterampilan = $column['DSK TRMPL'];
                                } else {
                                    $desc_keterampilan = "-";
                                }


                                $siswa = User::model()->findAll(array('condition' => 'username = ' . $nik_final));

                                $cekpengetahuan = FinalMark::model()->findAll(array("condition" => "user_id = " . $siswa[0]->id . " AND lesson_id = " . $id_mapel . " AND tipe = 'pengetahuan' AND semester = '" . $optSemester . "' AND tahun_ajaran = '" . $optTahunAjaran . "'"));
                                $cekketerampilan = FinalMark::model()->findAll(array("condition" => "user_id = " . $siswa[0]->id . " AND lesson_id = " . $id_mapel . " AND tipe = 'keterampilan' AND semester = '" . $optSemester . "' AND tahun_ajaran = '" . $optTahunAjaran . "'"));

                                $cek_desc_pengetahuan = FinalMark::model()->findAll(array("condition" => "user_id = " . $siswa[0]->id . " AND lesson_id = " . $id_mapel . " AND tipe = 'desc_pengetahuan' AND semester = '" . $optSemester . "' AND tahun_ajaran = '" . $optTahunAjaran . "'"));
                                $cek_desc_keterampilan = FinalMark::model()->findAll(array("condition" => "user_id = " . $siswa[0]->id . " AND lesson_id = " . $id_mapel . " AND tipe = 'desc_keterampilan' AND semester = '" . $optSemester . "' AND tahun_ajaran = '" . $optTahunAjaran . "'"));

                                // $ph=new PasswordHash(Yii::app()->params['phpass']['iteration_count_log2'], Yii::app()->params['phpass']['portable_hashes']);
                                // $passwd = $ph->HashPassword($password);

                                // if ($role == 2){
                                // 	if (empty($kelas)){
                                // 		Yii::app()->user->setFlash('error', "Baris $row2 . kolom kelas harus di isi");
                                // 		$this->redirect(array('bulk'));
                                // 	}
                                // }

                                if (empty($nama)) {
                                    Yii::app()->user->setFlash('error', "Baris $row2 . kolom nama harus di isi");
                                    $this->redirect(array('bulk'));
                                }


                                // if (empty($password)){
                                // 	Yii::app()->user->setFlash('error', "Baris $row2 . kolom password harus di isi");
                                // 	$this->redirect(array('bulk'));
                                // }

                                // if (empty($role)){
                                // 	Yii::app()->user->setFlash('error', "Baris $row2 . kolom role harus di isi");
                                // 	$this->redirect(array('bulk'));
                                // }

                                // if (!is_numeric($role)){
                                // 	Yii::app()->user->setFlash('error', "Baris $row2 . kolom role harus numeric (foreign key)");
                                // 	$this->redirect(array('bulk'));
                                // }

                                // if (!empty($kelas) and !is_numeric($kelas)){
                                // 	Yii::app()->user->setFlash('error', "Baris $row2 . kolom kelas harus numeric (foreign key)");
                                // 	$this->redirect(array('bulk'));
                                // }

                                // $cekUE=User::model()->findAll(array("condition"=>"username = '$nik' or email = '$email'"));

                                // if (!empty($cekUE)){
                                // 	Yii::app()->user->setFlash('error', "Baris $row2 . username atau email sudah terdaftar di database");
                                // 	$this->redirect(array('bulk'));
                                // }


                                if (empty($cekpengetahuan)) {
                                    $insert1 = "INSERT INTO " . $prefix . "final_mark (user_id,lesson_id,tipe,semester,tahun_ajaran,nilai,created_at,updated_at,created_by,updated_by) values('" . $siswa[0]->id . "','" . $id_mapel . "','" . $tipe1 . "',$optSemester,$optTahunAjaran,'" . $pengetahuan . "',NOW(),NOW(),'" . Yii::app()->user->id . "','" . Yii::app()->user->id . "')";

                                    $insertCommand1 = Yii::app()->db->createCommand($insert1);


                                    if ($insertCommand1->execute()) {
                                        $sukses++;
                                    } else {
                                        $fail++;
                                    }
                                } else {
                                    $sql1 = "UPDATE " . $prefix . "final_mark SET nilai = " . $pengetahuan . ", updated_at = NOW(), updated_by = " . Yii::app()->user->id . " WHERE user_id = " . $siswa[0]->id . " AND lesson_id = " . $id_mapel . " AND tipe = 'pengetahuan' AND semester = '" . $optSemester . "' AND tahun_ajaran = '" . $optTahunAjaran . "'";
                                    $command1 = Yii::app()->db->createCommand($sql1);
                                    if ($command1->execute()) {
                                        $sukses++;
                                    } else {
                                        $fail++;
                                    }
                                }

                                if (empty($cek_desc_pengetahuan)) {
                                    $insert1 = "INSERT INTO " . $prefix . "final_mark (user_id,lesson_id,tipe,semester,tahun_ajaran,nilai_desc,created_at,updated_at,created_by,updated_by) values('" . $siswa[0]->id . "','" . $id_mapel . "','" . $tipe3 . "',$optSemester,$optTahunAjaran,'" . $desc_pengetahuan . "',NOW(),NOW(),'" . Yii::app()->user->id . "','" . Yii::app()->user->id . "')";

                                    $insertCommand1 = Yii::app()->db->createCommand($insert1);


                                    if ($insertCommand1->execute()) {
                                        $sukses++;
                                    } else {
                                        $fail++;
                                    }
                                } else {
                                    $sql1 = "UPDATE " . $prefix . "final_mark SET nilai_desc = '" . $desc_pengetahuan . "', updated_at = NOW(), updated_by = " . Yii::app()->user->id . " WHERE user_id = " . $siswa[0]->id . " AND lesson_id = " . $id_mapel . " AND tipe = 'desc_pengetahuan' AND semester = '" . $optSemester . "' AND tahun_ajaran = '" . $optTahunAjaran . "'";
                                    $command1 = Yii::app()->db->createCommand($sql1);
                                    if ($command1->execute()) {
                                        $sukses++;
                                    } else {
                                        $fail++;
                                    }
                                }


                                if (empty($cekketerampilan)) {
                                    $insert2 = "INSERT INTO " . $prefix . "final_mark (user_id,lesson_id,tipe,semester,tahun_ajaran,nilai,created_at,updated_at,created_by,updated_by) values('" . $siswa[0]->id . "','" . $id_mapel . "','" . $tipe2 . "',$optSemester,$optTahunAjaran,'" . $keterampilan . "',NOW(),NOW(),'" . Yii::app()->user->id . "','" . Yii::app()->user->id . "')";

                                    $insertCommand2 = Yii::app()->db->createCommand($insert2);

                                    if ($insertCommand2->execute()) {
                                        $sukses++;
                                    } else {
                                        $fail++;
                                    }
                                } else {
                                    $sql2 = "UPDATE " . $prefix . "final_mark SET nilai = " . $keterampilan . ", updated_at = NOW(), updated_by = " . Yii::app()->user->id . " WHERE user_id = " . $siswa[0]->id . " AND lesson_id = " . $id_mapel . " AND tipe = 'keterampilan' AND semester = '" . $optSemester . "' AND tahun_ajaran = '" . $optTahunAjaran . "'";
                                    $command2 = Yii::app()->db->createCommand($sql2);
                                    if ($command2->execute()) {
                                        $sukses++;
                                    } else {
                                        $fail++;
                                    }
                                }

                                if (empty($cek_desc_keterampilan)) {
                                    $insert2 = "INSERT INTO " . $prefix . "final_mark (user_id,lesson_id,tipe,semester,tahun_ajaran,nilai_desc,created_at,updated_at,created_by,updated_by) values('" . $siswa[0]->id . "','" . $id_mapel . "','" . $tipe4 . "',$optSemester,$optTahunAjaran,'" . $desc_keterampilan . "',NOW(),NOW(),'" . Yii::app()->user->id . "','" . Yii::app()->user->id . "')";

                                    $insertCommand2 = Yii::app()->db->createCommand($insert2);

                                    if ($insertCommand2->execute()) {
                                        $sukses++;
                                    } else {
                                        $fail++;
                                    }
                                } else {
                                    $sql2 = "UPDATE " . $prefix . "final_mark SET nilai_desc = '" . $desc_keterampilan . "', updated_at = NOW(), updated_by = " . Yii::app()->user->id . " WHERE user_id = " . $siswa[0]->id . " AND lesson_id = " . $id_mapel . " AND tipe = 'desc_keterampilan' AND semester = '" . $optSemester . "' AND tahun_ajaran = '" . $optTahunAjaran . "'";
                                    $command2 = Yii::app()->db->createCommand($sql2);
                                    if ($command2->execute()) {
                                        $sukses++;
                                    } else {
                                        $fail++;
                                    }
                                }


                            }

                            $row++;
                            $urutan++;
                        }
                        Yii::app()->user->setFlash('success', "Berhasil Import " . $sukses . " Nilai Siswa, Gagal import  " . $fail . " nilai !");
                        $transaction->commit();

                    } catch (Exception $error) {
                        //echo "<pre>";
                        //print_r($error);
                        //echo "</pre>";
                        $transaction->rollback();
                        throw new CHttpException($error);
                    }
                }
                //$this->redirect(array('import_form'));
            } else {
                Yii::app()->user->setFlash('error', "Import Gagal!");
            }
        }

        /*if (!empty($sukses)){
			$row2 = $row - 1;
			Yii::app()->user->setFlash('success', "Berhasil buat $row2 record user!");
		}*/


        $this->render('importnilaiuas', array(
            'model' => $model,

        ));

    }

    public function actionAddMarkKetSik($id)
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

        $mark = new OfflineMark;
        $nilai_kd1 = $_POST['score_kd1'];
        $nilai_kd2 = $_POST['score_kd2'];
        $nilai_kd3 = $_POST['score_kd3'];
        $nilai_kd4 = $_POST['score_kd4'];
        $nilai_kd5 = $_POST['score_kd5'];
        $nilai_kd6 = $_POST['score_kd6'];
        $nilai_kd7 = $_POST['score_kd7'];
        $nilai_pen = $_POST['score_pen'];
        // $nilai_ket = $_POST['score_ket'];
        // $nilai_sik = $_POST['score_sik'];
        $siswa = $_POST['student_id'];
        $semester = $optSemester;
        $tahun_ajaran = $optTahunAjaran;
        $lid = $_POST['lesson_id'];
        $result_kd1 = array_combine($siswa, $nilai_kd1);
        $result_kd2 = array_combine($siswa, $nilai_kd2);
        $result_kd3 = array_combine($siswa, $nilai_kd3);
        $result_kd4 = array_combine($siswa, $nilai_kd4);
        $result_kd5 = array_combine($siswa, $nilai_kd5);
        $result_kd6 = array_combine($siswa, $nilai_kd6);
        $result_kd7 = array_combine($siswa, $nilai_kd7);
        $result_pen = array_combine($siswa, $nilai_pen);
        // $result_ket = array_combine($siswa, $nilai_ket);
        // $result_sik = array_combine($siswa, $nilai_sik);
        $current_user = Yii::app()->user->id;
        $prefix = Yii::app()->params['tablePrefix'];
        $ida = 41;
        $mt = 1;
        $dt = 0;


        foreach ($result_kd1 as $key => $value) {
            if (!empty($value)) {
                $cek = FinalMark::model()->findByAttributes(array('user_id' => $key, 'lesson_id' => $lid, 'tipe' => 'kd1', 'semester' => $semester, 'tahun_ajaran' => $tahun_ajaran));
                if (!empty($cek)) {
                    if ($value > 100) {
                        $the_score = 0;
                        $fm_id = $cek->id;
                        $sql = "UPDATE " . $prefix . "final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        $command->bindParam(":updated_by", $current_user, PDO::PARAM_STR);
                        $command->bindParam(":score", $the_score, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    } else {
                        $fm_id = $cek->id;
                        $sql = "UPDATE " . $prefix . "final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        $command->bindParam(":updated_by", $current_user, PDO::PARAM_STR);
                        $command->bindParam(":score", $value, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    }
                } else {
                    $sql = "INSERT INTO " . $prefix . "final_mark (lesson_id, user_id, created_at, updated_at, created_by, nilai, tipe,semester, tahun_ajaran) VALUES(:lid,:sid,NOW(),NOW(),:created_by,:score,'kd1', :semester,:tahun_ajaran)";
                    $command = Yii::app()->db->createCommand($sql);
                    $command->bindParam(":lid", $lid, PDO::PARAM_STR);
                    $command->bindParam(":sid", $key, PDO::PARAM_STR);
                    $command->bindParam(":created_by", $current_user, PDO::PARAM_STR);
                    $command->bindParam(":score", $value, PDO::PARAM_STR);
                    $command->bindParam(":semester", $semester, PDO::PARAM_STR);
                    $command->bindParam(":tahun_ajaran", $tahun_ajaran, PDO::PARAM_STR);
                    if ($command->execute()) {
                        $dt++;
                    }
                }
            }

        }
        foreach ($result_kd2 as $key => $value) {
            if (!empty($value)) {
                $cek = FinalMark::model()->findByAttributes(array('user_id' => $key, 'lesson_id' => $lid, 'tipe' => 'kd2', 'semester' => $semester, 'tahun_ajaran' => $tahun_ajaran));
                if (!empty($cek)) {
                    if ($value > 100) {
                        $the_score = 0;
                        $fm_id = $cek->id;
                        $sql = "UPDATE " . $prefix . "final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        $command->bindParam(":updated_by", $current_user, PDO::PARAM_STR);
                        $command->bindParam(":score", $the_score, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    } else {
                        $fm_id = $cek->id;
                        $sql = "UPDATE " . $prefix . "final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        $command->bindParam(":updated_by", $current_user, PDO::PARAM_STR);
                        $command->bindParam(":score", $value, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    }
                } else {
                    $sql = "INSERT INTO " . $prefix . "final_mark (lesson_id, user_id, created_at, updated_at, created_by, nilai, tipe,semester, tahun_ajaran) VALUES(:lid,:sid,NOW(),NOW(),:created_by,:score,'kd2', :semester,:tahun_ajaran)";
                    $command = Yii::app()->db->createCommand($sql);
                    $command->bindParam(":lid", $lid, PDO::PARAM_STR);
                    $command->bindParam(":sid", $key, PDO::PARAM_STR);
                    $command->bindParam(":created_by", $current_user, PDO::PARAM_STR);
                    $command->bindParam(":score", $value, PDO::PARAM_STR);
                    $command->bindParam(":semester", $semester, PDO::PARAM_STR);
                    $command->bindParam(":tahun_ajaran", $tahun_ajaran, PDO::PARAM_STR);
                    if ($command->execute()) {
                        $dt++;
                    }
                }
            }

        }
        foreach ($result_kd3 as $key => $value) {
            if (!empty($value)) {
                $cek = FinalMark::model()->findByAttributes(array('user_id' => $key, 'lesson_id' => $lid, 'tipe' => 'kd3', 'semester' => $semester, 'tahun_ajaran' => $tahun_ajaran));
                if (!empty($cek)) {
                    if ($value > 100) {
                        $the_score = 0;
                        $fm_id = $cek->id;
                        $sql = "UPDATE " . $prefix . "final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        $command->bindParam(":updated_by", $current_user, PDO::PARAM_STR);
                        $command->bindParam(":score", $the_score, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    } else {
                        $fm_id = $cek->id;
                        $sql = "UPDATE " . $prefix . "final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        $command->bindParam(":updated_by", $current_user, PDO::PARAM_STR);
                        $command->bindParam(":score", $value, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    }
                } else {
                    $sql = "INSERT INTO " . $prefix . "final_mark (lesson_id, user_id, created_at, updated_at, created_by, nilai, tipe,semester, tahun_ajaran) VALUES(:lid,:sid,NOW(),NOW(),:created_by,:score,'kd3', :semester,:tahun_ajaran)";
                    $command = Yii::app()->db->createCommand($sql);
                    $command->bindParam(":lid", $lid, PDO::PARAM_STR);
                    $command->bindParam(":sid", $key, PDO::PARAM_STR);
                    $command->bindParam(":created_by", $current_user, PDO::PARAM_STR);
                    $command->bindParam(":score", $value, PDO::PARAM_STR);
                    $command->bindParam(":semester", $semester, PDO::PARAM_STR);
                    $command->bindParam(":tahun_ajaran", $tahun_ajaran, PDO::PARAM_STR);
                    if ($command->execute()) {
                        $dt++;
                    }
                }
            }

        }
        foreach ($result_kd4 as $key => $value) {
            if (!empty($value)) {
                $cek = FinalMark::model()->findByAttributes(array('user_id' => $key, 'lesson_id' => $lid, 'tipe' => 'kd4', 'semester' => $semester, 'tahun_ajaran' => $tahun_ajaran));
                if (!empty($cek)) {
                    if ($value > 100) {
                        $the_score = 0;
                        $fm_id = $cek->id;
                        $sql = "UPDATE " . $prefix . "final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        $command->bindParam(":updated_by", $current_user, PDO::PARAM_STR);
                        $command->bindParam(":score", $the_score, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    } else {
                        $fm_id = $cek->id;
                        $sql = "UPDATE " . $prefix . "final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        $command->bindParam(":updated_by", $current_user, PDO::PARAM_STR);
                        $command->bindParam(":score", $value, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    }
                } else {
                    $sql = "INSERT INTO " . $prefix . "final_mark (lesson_id, user_id, created_at, updated_at, created_by, nilai, tipe,semester, tahun_ajaran) VALUES(:lid,:sid,NOW(),NOW(),:created_by,:score,'kd4', :semester,:tahun_ajaran)";
                    $command = Yii::app()->db->createCommand($sql);
                    $command->bindParam(":lid", $lid, PDO::PARAM_STR);
                    $command->bindParam(":sid", $key, PDO::PARAM_STR);
                    $command->bindParam(":created_by", $current_user, PDO::PARAM_STR);
                    $command->bindParam(":score", $value, PDO::PARAM_STR);
                    $command->bindParam(":semester", $semester, PDO::PARAM_STR);
                    $command->bindParam(":tahun_ajaran", $tahun_ajaran, PDO::PARAM_STR);
                    if ($command->execute()) {
                        $dt++;
                    }
                }
            }

        }
        foreach ($result_kd5 as $key => $value) {
            if (!empty($value)) {
                $cek = FinalMark::model()->findByAttributes(array('user_id' => $key, 'lesson_id' => $lid, 'tipe' => 'kd5', 'semester' => $semester, 'tahun_ajaran' => $tahun_ajaran));
                if (!empty($cek)) {
                    if ($value > 100) {
                        $the_score = 0;
                        $fm_id = $cek->id;
                        $sql = "UPDATE " . $prefix . "final_mark SET nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        $command->bindParam(":updated_by", $current_user, PDO::PARAM_STR);
                        $command->bindParam(":score", $the_score, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    } else {
                        $fm_id = $cek->id;
                        $sql = "UPDATE " . $prefix . "final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        $command->bindParam(":updated_by", $current_user, PDO::PARAM_STR);
                        $command->bindParam(":score", $value, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    }
                } else {
                    $sql = "INSERT INTO " . $prefix . "final_mark (lesson_id, user_id, created_at, updated_at, created_by, nilai, tipe,semester, tahun_ajaran) VALUES(:lid,:sid,NOW(),NOW(),:created_by,:score,'kd5', :semester,:tahun_ajaran)";
                    $command = Yii::app()->db->createCommand($sql);
                    $command->bindParam(":lid", $lid, PDO::PARAM_STR);
                    $command->bindParam(":sid", $key, PDO::PARAM_STR);
                    $command->bindParam(":created_by", $current_user, PDO::PARAM_STR);
                    $command->bindParam(":score", $value, PDO::PARAM_STR);
                    $command->bindParam(":semester", $semester, PDO::PARAM_STR);
                    $command->bindParam(":tahun_ajaran", $tahun_ajaran, PDO::PARAM_STR);
                    if ($command->execute()) {
                        $dt++;
                    }
                }
            }

        }
        foreach ($result_kd6 as $key => $value) {
            if (!empty($value)) {
                $cek = FinalMark::model()->findByAttributes(array('user_id' => $key, 'lesson_id' => $lid, 'tipe' => 'kd6', 'semester' => $semester, 'tahun_ajaran' => $tahun_ajaran));
                if (!empty($cek)) {
                    if ($value > 100) {
                        $the_score = 0;
                        $fm_id = $cek->id;
                        $sql = "UPDATE " . $prefix . "final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        $command->bindParam(":updated_by", $current_user, PDO::PARAM_STR);
                        $command->bindParam(":score", $the_score, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    } else {
                        $fm_id = $cek->id;
                        $sql = "UPDATE " . $prefix . "final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        $command->bindParam(":updated_by", $current_user, PDO::PARAM_STR);
                        $command->bindParam(":score", $value, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    }
                } else {
                    $sql = "INSERT INTO " . $prefix . "final_mark (lesson_id, user_id, created_at, updated_at, created_by, nilai, tipe,semester, tahun_ajaran) VALUES(:lid,:sid,NOW(),NOW(),:created_by,:score,'kd6', :semester,:tahun_ajaran)";
                    $command = Yii::app()->db->createCommand($sql);
                    $command->bindParam(":lid", $lid, PDO::PARAM_STR);
                    $command->bindParam(":sid", $key, PDO::PARAM_STR);
                    $command->bindParam(":created_by", $current_user, PDO::PARAM_STR);
                    $command->bindParam(":score", $value, PDO::PARAM_STR);
                    $command->bindParam(":semester", $semester, PDO::PARAM_STR);
                    $command->bindParam(":tahun_ajaran", $tahun_ajaran, PDO::PARAM_STR);
                    if ($command->execute()) {
                        $dt++;
                    }
                }
            }

        }
        foreach ($result_kd7 as $key => $value) {
            if (!empty($value)) {
                $cek = FinalMark::model()->findByAttributes(array('user_id' => $key, 'lesson_id' => $lid, 'tipe' => 'kd7', 'semester' => $semester, 'tahun_ajaran' => $tahun_ajaran));
                if (!empty($cek)) {
                    if ($value > 100) {
                        $the_score = 0;
                        $fm_id = $cek->id;
                        $sql = "UPDATE " . $prefix . "final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        $command->bindParam(":updated_by", $current_user, PDO::PARAM_STR);
                        $command->bindParam(":score", $the_score, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    } else {
                        $fm_id = $cek->id;
                        $sql = "UPDATE " . $prefix . "final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        $command->bindParam(":updated_by", $current_user, PDO::PARAM_STR);
                        $command->bindParam(":score", $value, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    }
                } else {
                    $sql = "INSERT INTO " . $prefix . "final_mark (lesson_id, user_id, created_at, updated_at, created_by, nilai, tipe,semester, tahun_ajaran) VALUES(:lid,:sid,NOW(),NOW(),:created_by,:score,'kd7', :semester,:tahun_ajaran)";
                    $command = Yii::app()->db->createCommand($sql);
                    $command->bindParam(":lid", $lid, PDO::PARAM_STR);
                    $command->bindParam(":sid", $key, PDO::PARAM_STR);
                    $command->bindParam(":created_by", $current_user, PDO::PARAM_STR);
                    $command->bindParam(":score", $value, PDO::PARAM_STR);
                    $command->bindParam(":semester", $semester, PDO::PARAM_STR);
                    $command->bindParam(":tahun_ajaran", $tahun_ajaran, PDO::PARAM_STR);
                    if ($command->execute()) {
                        $dt++;
                    }
                }
            }

        }

        foreach ($result_pen as $key => $value) {
            if (!empty($value)) {
                $cek = FinalMark::model()->findByAttributes(array('user_id' => $key, 'lesson_id' => $lid, 'tipe' => 'pengetahuan', 'semester' => $semester, 'tahun_ajaran' => $tahun_ajaran));
                if (!empty($cek)) {
                    if ($value > 100) {
                        $the_score = 0;
                        $fm_id = $cek->id;
                        $sql = "UPDATE " . $prefix . "final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        $command->bindParam(":updated_by", $current_user, PDO::PARAM_STR);
                        $command->bindParam(":score", $the_score, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    } else {
                        $fm_id = $cek->id;
                        $sql = "UPDATE " . $prefix . "final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        $command->bindParam(":updated_by", $current_user, PDO::PARAM_STR);
                        $command->bindParam(":score", $value, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    }
                } else {
                    $sql = "INSERT INTO " . $prefix . "final_mark (lesson_id, user_id, created_at, updated_at, created_by, nilai, tipe,semester, tahun_ajaran) VALUES(:lid,:sid,NOW(),NOW(),:created_by,:score,'pengetahuan', :semester,:tahun_ajaran)";
                    $command = Yii::app()->db->createCommand($sql);
                    $command->bindParam(":lid", $lid, PDO::PARAM_STR);
                    $command->bindParam(":sid", $key, PDO::PARAM_STR);
                    $command->bindParam(":created_by", $current_user, PDO::PARAM_STR);
                    $command->bindParam(":score", $value, PDO::PARAM_STR);
                    $command->bindParam(":semester", $semester, PDO::PARAM_STR);
                    $command->bindParam(":tahun_ajaran", $tahun_ajaran, PDO::PARAM_STR);
                    if ($command->execute()) {
                        $dt++;
                    }
                }
            }

        }


        //print_r($result);
        //print_r($siswa);
        //echo count($result);
        //echo "</pre>";
        Yii::app()->user->setFlash('success', 'Input Nilai Siswa Berhasil !');
        $this->redirect(array('lesson/NilaiKetSikap', 'id' => $id));
    }

    public function actionAddMarkKetSikDua($id)
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

        $mark = new OfflineMark;
        $nilai_kd1 = $_POST['score_kd1'];
        $nilai_kd2 = $_POST['score_kd2'];
        $nilai_kd3 = $_POST['score_kd3'];
        $nilai_kd4 = $_POST['score_kd4'];
        $nilai_kd5 = $_POST['score_kd5'];
        $nilai_kd6 = $_POST['score_kd6'];
        $nilai_kd7 = $_POST['score_kd7'];
        // $nilai_pen = $_POST['score_pen'];
        $nilai_ket = $_POST['score_ket'];
        // $nilai_sik = $_POST['score_sik'];
        $siswa = $_POST['student_id'];
        $semester = $optSemester;
        $tahun_ajaran = $optTahunAjaran;
        $lid = $_POST['lesson_id'];
        $result_kd1 = array_combine($siswa, $nilai_kd1);
        $result_kd2 = array_combine($siswa, $nilai_kd2);
        $result_kd3 = array_combine($siswa, $nilai_kd3);
        $result_kd4 = array_combine($siswa, $nilai_kd4);
        $result_kd5 = array_combine($siswa, $nilai_kd5);
        $result_kd6 = array_combine($siswa, $nilai_kd6);
        $result_kd7 = array_combine($siswa, $nilai_kd7);
        // $result_pen = array_combine($siswa, $nilai_pen);
        $result_ket = array_combine($siswa, $nilai_ket);
        // $result_sik = array_combine($siswa, $nilai_sik);
        $current_user = Yii::app()->user->id;
        $prefix = Yii::app()->params['tablePrefix'];
        $ida = 41;
        $mt = 1;
        $dt = 0;
        //echo "<pre>";
        foreach ($result_ket as $key => $value) {
            if (!empty($value)) {
                $cek = FinalMark::model()->findByAttributes(array('user_id' => $key, 'lesson_id' => $lid, 'tipe' => 'keterampilan', 'semester' => $semester, 'tahun_ajaran' => $tahun_ajaran));
                if (!empty($cek)) {
                    if ($value > 100) {
                        $the_score = 0;
                        $fm_id = $cek->id;
                        $sql = "UPDATE " . $prefix . "final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        $command->bindParam(":updated_by", $current_user, PDO::PARAM_STR);
                        $command->bindParam(":score", $the_score, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    } else {
                        $fm_id = $cek->id;
                        $sql = "UPDATE " . $prefix . "final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        $command->bindParam(":updated_by", $current_user, PDO::PARAM_STR);
                        $command->bindParam(":score", $value, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    }
                } else {
                    $sql = "INSERT INTO " . $prefix . "final_mark (lesson_id, user_id, created_at, updated_at, created_by, nilai, tipe,semester, tahun_ajaran) VALUES(:lid,:sid,NOW(),NOW(),:created_by,:score,'keterampilan', :semester,:tahun_ajaran)";
                    $command = Yii::app()->db->createCommand($sql);
                    $command->bindParam(":lid", $lid, PDO::PARAM_STR);
                    $command->bindParam(":sid", $key, PDO::PARAM_STR);
                    $command->bindParam(":created_by", $current_user, PDO::PARAM_STR);
                    $command->bindParam(":score", $value, PDO::PARAM_STR);
                    $command->bindParam(":semester", $semester, PDO::PARAM_STR);
                    $command->bindParam(":tahun_ajaran", $tahun_ajaran, PDO::PARAM_STR);
                    if ($command->execute()) {
                        $dt++;
                    }
                }
            }

        }

        foreach ($result_kd1 as $key => $value) {
            if (!empty($value)) {
                $cek = FinalMark::model()->findByAttributes(array('user_id' => $key, 'lesson_id' => $lid, 'tipe' => 'kd1_ket', 'semester' => $semester, 'tahun_ajaran' => $tahun_ajaran));
                if (!empty($cek)) {
                    if ($value > 100) {
                        $the_score = 0;
                        $fm_id = $cek->id;
                        $sql = "UPDATE " . $prefix . "final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        $command->bindParam(":updated_by", $current_user, PDO::PARAM_STR);
                        $command->bindParam(":score", $the_score, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    } else {
                        $fm_id = $cek->id;
                        $sql = "UPDATE " . $prefix . "final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        $command->bindParam(":updated_by", $current_user, PDO::PARAM_STR);
                        $command->bindParam(":score", $value, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    }
                } else {
                    $sql = "INSERT INTO " . $prefix . "final_mark (lesson_id, user_id, created_at, updated_at, created_by, nilai, tipe,semester, tahun_ajaran) VALUES(:lid,:sid,NOW(),NOW(),:created_by,:score,'kd1_ket', :semester,:tahun_ajaran)";
                    $command = Yii::app()->db->createCommand($sql);
                    $command->bindParam(":lid", $lid, PDO::PARAM_STR);
                    $command->bindParam(":sid", $key, PDO::PARAM_STR);
                    $command->bindParam(":created_by", $current_user, PDO::PARAM_STR);
                    $command->bindParam(":score", $value, PDO::PARAM_STR);
                    $command->bindParam(":semester", $semester, PDO::PARAM_STR);
                    $command->bindParam(":tahun_ajaran", $tahun_ajaran, PDO::PARAM_STR);
                    if ($command->execute()) {
                        $dt++;
                    }
                }
            }

        }
        foreach ($result_kd2 as $key => $value) {
            if (!empty($value)) {
                $cek = FinalMark::model()->findByAttributes(array('user_id' => $key, 'lesson_id' => $lid, 'tipe' => 'kd2_ket', 'semester' => $semester, 'tahun_ajaran' => $tahun_ajaran));
                if (!empty($cek)) {
                    if ($value > 100) {
                        $the_score = 0;
                        $fm_id = $cek->id;
                        $sql = "UPDATE " . $prefix . "final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        $command->bindParam(":updated_by", $current_user, PDO::PARAM_STR);
                        $command->bindParam(":score", $the_score, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    } else {
                        $fm_id = $cek->id;
                        $sql = "UPDATE " . $prefix . "final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        $command->bindParam(":updated_by", $current_user, PDO::PARAM_STR);
                        $command->bindParam(":score", $value, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    }
                } else {
                    $sql = "INSERT INTO " . $prefix . "final_mark (lesson_id, user_id, created_at, updated_at, created_by, nilai, tipe,semester, tahun_ajaran) VALUES(:lid,:sid,NOW(),NOW(),:created_by,:score,'kd2_ket', :semester,:tahun_ajaran)";
                    $command = Yii::app()->db->createCommand($sql);
                    $command->bindParam(":lid", $lid, PDO::PARAM_STR);
                    $command->bindParam(":sid", $key, PDO::PARAM_STR);
                    $command->bindParam(":created_by", $current_user, PDO::PARAM_STR);
                    $command->bindParam(":score", $value, PDO::PARAM_STR);
                    $command->bindParam(":semester", $semester, PDO::PARAM_STR);
                    $command->bindParam(":tahun_ajaran", $tahun_ajaran, PDO::PARAM_STR);
                    if ($command->execute()) {
                        $dt++;
                    }
                }
            }

        }
        foreach ($result_kd3 as $key => $value) {
            if (!empty($value)) {
                $cek = FinalMark::model()->findByAttributes(array('user_id' => $key, 'lesson_id' => $lid, 'tipe' => 'kd3_ket', 'semester' => $semester, 'tahun_ajaran' => $tahun_ajaran));
                if (!empty($cek)) {
                    if ($value > 100) {
                        $the_score = 0;
                        $fm_id = $cek->id;
                        $sql = "UPDATE " . $prefix . "final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        $command->bindParam(":updated_by", $current_user, PDO::PARAM_STR);
                        $command->bindParam(":score", $the_score, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    } else {
                        $fm_id = $cek->id;
                        $sql = "UPDATE " . $prefix . "final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        $command->bindParam(":updated_by", $current_user, PDO::PARAM_STR);
                        $command->bindParam(":score", $value, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    }
                } else {
                    $sql = "INSERT INTO " . $prefix . "final_mark (lesson_id, user_id, created_at, updated_at, created_by, nilai, tipe,semester, tahun_ajaran) VALUES(:lid,:sid,NOW(),NOW(),:created_by,:score,'kd3_ket', :semester,:tahun_ajaran)";
                    $command = Yii::app()->db->createCommand($sql);
                    $command->bindParam(":lid", $lid, PDO::PARAM_STR);
                    $command->bindParam(":sid", $key, PDO::PARAM_STR);
                    $command->bindParam(":created_by", $current_user, PDO::PARAM_STR);
                    $command->bindParam(":score", $value, PDO::PARAM_STR);
                    $command->bindParam(":semester", $semester, PDO::PARAM_STR);
                    $command->bindParam(":tahun_ajaran", $tahun_ajaran, PDO::PARAM_STR);
                    if ($command->execute()) {
                        $dt++;
                    }
                }
            }

        }
        foreach ($result_kd4 as $key => $value) {
            if (!empty($value)) {
                $cek = FinalMark::model()->findByAttributes(array('user_id' => $key, 'lesson_id' => $lid, 'tipe' => 'kd4_ket', 'semester' => $semester, 'tahun_ajaran' => $tahun_ajaran));
                if (!empty($cek)) {
                    if ($value > 100) {
                        $the_score = 0;
                        $fm_id = $cek->id;
                        $sql = "UPDATE " . $prefix . "final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        $command->bindParam(":updated_by", $current_user, PDO::PARAM_STR);
                        $command->bindParam(":score", $the_score, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    } else {
                        $fm_id = $cek->id;
                        $sql = "UPDATE " . $prefix . "final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        $command->bindParam(":updated_by", $current_user, PDO::PARAM_STR);
                        $command->bindParam(":score", $value, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    }
                } else {
                    $sql = "INSERT INTO " . $prefix . "final_mark (lesson_id, user_id, created_at, updated_at, created_by, nilai, tipe,semester, tahun_ajaran) VALUES(:lid,:sid,NOW(),NOW(),:created_by,:score,'kd4_ket', :semester,:tahun_ajaran)";
                    $command = Yii::app()->db->createCommand($sql);
                    $command->bindParam(":lid", $lid, PDO::PARAM_STR);
                    $command->bindParam(":sid", $key, PDO::PARAM_STR);
                    $command->bindParam(":created_by", $current_user, PDO::PARAM_STR);
                    $command->bindParam(":score", $value, PDO::PARAM_STR);
                    $command->bindParam(":semester", $semester, PDO::PARAM_STR);
                    $command->bindParam(":tahun_ajaran", $tahun_ajaran, PDO::PARAM_STR);
                    if ($command->execute()) {
                        $dt++;
                    }
                }
            }

        }
        foreach ($result_kd5 as $key => $value) {
            if (!empty($value)) {
                $cek = FinalMark::model()->findByAttributes(array('user_id' => $key, 'lesson_id' => $lid, 'tipe' => 'kd5_ket', 'semester' => $semester, 'tahun_ajaran' => $tahun_ajaran));
                if (!empty($cek)) {
                    if ($value > 100) {
                        $the_score = 0;
                        $fm_id = $cek->id;
                        $sql = "UPDATE " . $prefix . "final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        $command->bindParam(":updated_by", $current_user, PDO::PARAM_STR);
                        $command->bindParam(":score", $the_score, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    } else {
                        $fm_id = $cek->id;
                        $sql = "UPDATE " . $prefix . "final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        $command->bindParam(":updated_by", $current_user, PDO::PARAM_STR);
                        $command->bindParam(":score", $value, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    }
                } else {
                    $sql = "INSERT INTO " . $prefix . "final_mark (lesson_id, user_id, created_at, updated_at, created_by, nilai, tipe,semester, tahun_ajaran) VALUES(:lid,:sid,NOW(),NOW(),:created_by,:score,'kd5_ket', :semester,:tahun_ajaran)";
                    $command = Yii::app()->db->createCommand($sql);
                    $command->bindParam(":lid", $lid, PDO::PARAM_STR);
                    $command->bindParam(":sid", $key, PDO::PARAM_STR);
                    $command->bindParam(":created_by", $current_user, PDO::PARAM_STR);
                    $command->bindParam(":score", $value, PDO::PARAM_STR);
                    $command->bindParam(":semester", $semester, PDO::PARAM_STR);
                    $command->bindParam(":tahun_ajaran", $tahun_ajaran, PDO::PARAM_STR);
                    if ($command->execute()) {
                        $dt++;
                    }
                }
            }

        }
        foreach ($result_kd6 as $key => $value) {
            if (!empty($value)) {
                $cek = FinalMark::model()->findByAttributes(array('user_id' => $key, 'lesson_id' => $lid, 'tipe' => 'kd6_ket', 'semester' => $semester, 'tahun_ajaran' => $tahun_ajaran));
                if (!empty($cek)) {
                    if ($value > 100) {
                        $the_score = 0;
                        $fm_id = $cek->id;
                        $sql = "UPDATE " . $prefix . "final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        $command->bindParam(":updated_by", $current_user, PDO::PARAM_STR);
                        $command->bindParam(":score", $the_score, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    } else {
                        $fm_id = $cek->id;
                        $sql = "UPDATE " . $prefix . "final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        $command->bindParam(":updated_by", $current_user, PDO::PARAM_STR);
                        $command->bindParam(":score", $value, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    }
                } else {
                    $sql = "INSERT INTO " . $prefix . "final_mark (lesson_id, user_id, created_at, updated_at, created_by, nilai, tipe,semester, tahun_ajaran) VALUES(:lid,:sid,NOW(),NOW(),:created_by,:score,'kd6_ket', :semester,:tahun_ajaran)";
                    $command = Yii::app()->db->createCommand($sql);
                    $command->bindParam(":lid", $lid, PDO::PARAM_STR);
                    $command->bindParam(":sid", $key, PDO::PARAM_STR);
                    $command->bindParam(":created_by", $current_user, PDO::PARAM_STR);
                    $command->bindParam(":score", $value, PDO::PARAM_STR);
                    $command->bindParam(":semester", $semester, PDO::PARAM_STR);
                    $command->bindParam(":tahun_ajaran", $tahun_ajaran, PDO::PARAM_STR);
                    if ($command->execute()) {
                        $dt++;
                    }
                }
            }

        }
        foreach ($result_kd7 as $key => $value) {
            if (!empty($value)) {
                $cek = FinalMark::model()->findByAttributes(array('user_id' => $key, 'lesson_id' => $lid, 'tipe' => 'kd7_ket', 'semester' => $semester, 'tahun_ajaran' => $tahun_ajaran));
                if (!empty($cek)) {
                    if ($value > 100) {
                        $the_score = 0;
                        $fm_id = $cek->id;
                        $sql = "UPDATE " . $prefix . "final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        $command->bindParam(":updated_by", $current_user, PDO::PARAM_STR);
                        $command->bindParam(":score", $the_score, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    } else {
                        $fm_id = $cek->id;
                        $sql = "UPDATE " . $prefix . "final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        $command->bindParam(":updated_by", $current_user, PDO::PARAM_STR);
                        $command->bindParam(":score", $value, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    }
                } else {
                    $sql = "INSERT INTO " . $prefix . "final_mark (lesson_id, user_id, created_at, updated_at, created_by, nilai, tipe,semester, tahun_ajaran) VALUES(:lid,:sid,NOW(),NOW(),:created_by,:score,'kd7_ket', :semester,:tahun_ajaran)";
                    $command = Yii::app()->db->createCommand($sql);
                    $command->bindParam(":lid", $lid, PDO::PARAM_STR);
                    $command->bindParam(":sid", $key, PDO::PARAM_STR);
                    $command->bindParam(":created_by", $current_user, PDO::PARAM_STR);
                    $command->bindParam(":score", $value, PDO::PARAM_STR);
                    $command->bindParam(":semester", $semester, PDO::PARAM_STR);
                    $command->bindParam(":tahun_ajaran", $tahun_ajaran, PDO::PARAM_STR);
                    if ($command->execute()) {
                        $dt++;
                    }
                }
            }

        }

        //print_r($result);
        //print_r($siswa);
        //echo count($result);
        //echo "</pre>";
        Yii::app()->user->setFlash('success', 'Input Nilai Siswa Berhasil !');
        $this->redirect(array('lesson/NilaiKetSikapDua', 'id' => $id));
    }

    public function actionAddMarkKd($id)
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

        $mark = new LessonKd;
        $description = $_POST['description'];
        // $level = $_POST['level'];
        $siswa = $_POST['title'];
        $semester = $optSemester;
        $tahun_ajaran = $optTahunAjaran;
        $lid = $_POST['lesson_id'];
        $result_kd1 = array_combine($siswa, $description);
        $current_user = Yii::app()->user->id;
        $prefix = Yii::app()->params['tablePrefix'];
        $ida = 41;
        $mt = 1;
        $dt = 0;
        //echo "<pre>";


        foreach ($result_kd1 as $key => $value) {
            if (!empty($value)) {
                $cek = LessonKd::model()->findByAttributes(array('title' => $key, 'lesson_id' => $lid, 'semester' => $semester, 'tahun_ajaran' => $tahun_ajaran));
                if (!empty($cek)) {
                    if ($value == "kosong") {
                        $fm_id = $cek->id;
                        $the_value = "";

                        $sql = "DELETE FROM lesson_kd WHERE id = :id";

                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                        
                        // $sql = "UPDATE " . $prefix . "lesson_kd SET sync_status = 2, description = :description, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        // $command = Yii::app()->db->createCommand($sql);
                        // $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        // $command->bindParam(":updated_by", $current_user, PDO::PARAM_STR);
                        // $command->bindParam(":description", $the_value, PDO::PARAM_STR);
                        // if ($command->execute()) {
                        //     $dt++;
                        // }
                    } else {
                        $fm_id = $cek->id;
                        $sql = "UPDATE " . $prefix . "lesson_kd SET sync_status = 2, description = :description, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        $command->bindParam(":updated_by", $current_user, PDO::PARAM_STR);
                        $command->bindParam(":description", $value, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    }

                } else {
                    $sql = "INSERT INTO " . $prefix . "lesson_kd (lesson_id, created_at, updated_at, created_by, description, title,semester, tahun_ajaran) VALUES(:lid,NOW(),NOW(),:created_by,:description,:title, :semester,:tahun_ajaran)";
                    $command = Yii::app()->db->createCommand($sql);
                    $command->bindParam(":lid", $lid, PDO::PARAM_STR);
                    // $command->bindParam(":level",$level,PDO::PARAM_STR);
                    $command->bindParam(":created_by", $current_user, PDO::PARAM_STR);
                    $command->bindParam(":description", $value, PDO::PARAM_STR);
                    $command->bindParam(":title", $key, PDO::PARAM_STR);
                    $command->bindParam(":semester", $semester, PDO::PARAM_STR);
                    $command->bindParam(":tahun_ajaran", $tahun_ajaran, PDO::PARAM_STR);
                    if ($command->execute()) {
                        $dt++;
                    }
                }
            }

        }
        //print_r($result);
        //print_r($siswa);
        //echo count($result);
        //echo "</pre>";
        Yii::app()->user->setFlash('success', 'Input KD Berhasil !');
        $this->redirect(array('lesson/NilaiKd', 'id' => $id));
    }

    public function actionAddMarkKdDua($id)
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

        $mark = new LessonKd;
        $description = $_POST['description'];
        // $level = $_POST['level'];
        $siswa = $_POST['title'];
        $semester = $optSemester;
        $tahun_ajaran = $optTahunAjaran;
        $lid = $_POST['lesson_id'];
        $result_kd1 = array_combine($siswa, $description);
        $current_user = Yii::app()->user->id;
        $prefix = Yii::app()->params['tablePrefix'];
        $ida = 41;
        $mt = 1;
        $dt = 0;
        //echo "<pre>";


        foreach ($result_kd1 as $key => $value) {
            if (!empty($value)) {
                $cek = LessonKd::model()->findByAttributes(array('title' => $key, 'lesson_id' => $lid, 'semester' => $semester, 'tahun_ajaran' => $tahun_ajaran));
                if (!empty($cek)) {
                    if ($value == "kosong") {
                        $fm_id = $cek->id;
                        $the_value = "";
                        
                        $sql = "DELETE FROM lesson_kd WHERE id = :id";

                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    } else {
                        $fm_id = $cek->id;
                        $sql = "UPDATE " . $prefix . "lesson_kd SET sync_status = 2, description = :description, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        $command->bindParam(":updated_by", $current_user, PDO::PARAM_STR);
                        $command->bindParam(":description", $value, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    }

                } else {
                    $sql = "INSERT INTO " . $prefix . "lesson_kd (lesson_id, created_at, updated_at, created_by, description, title,semester, tahun_ajaran) VALUES(:lid,NOW(),NOW(),:created_by,:description,:title, :semester,:tahun_ajaran)";
                    $command = Yii::app()->db->createCommand($sql);
                    $command->bindParam(":lid", $lid, PDO::PARAM_STR);
                    // $command->bindParam(":level",$level,PDO::PARAM_STR);
                    $command->bindParam(":created_by", $current_user, PDO::PARAM_STR);
                    $command->bindParam(":description", $value, PDO::PARAM_STR);
                    $command->bindParam(":title", $key, PDO::PARAM_STR);
                    $command->bindParam(":semester", $semester, PDO::PARAM_STR);
                    $command->bindParam(":tahun_ajaran", $tahun_ajaran, PDO::PARAM_STR);
                    if ($command->execute()) {
                        $dt++;
                    }
                }
            }

        }
        //print_r($result);
        //print_r($siswa);
        //echo count($result);
        //echo "</pre>";
        Yii::app()->user->setFlash('success', 'Input KD Berhasil !');
        $this->redirect(array('lesson/NilaiKdDua', 'id' => $id));
    }

    public function actionCopyExcel()
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

        if (isset($_POST['datasiswa'])) {
            //echo "<pre>";
            $wrap_data = array();
            $all_student = array();
            $student = array();
            $wrap_data['messages'] = 'failed';
            if (!empty($_POST['datasiswa'])) {

                $raw = $_POST['datasiswa'];
                $data = preg_split('/\r\n|[\r\n]/', $raw);
                //print_r($data);
                //echo count($data);


                foreach ($data as $value) {
                    $each = preg_split('/\s+/', $value);
                    $nis = trim($each[0]);
                    //$cekUE=User::model()->findAll(array("condition"=>"username = '$nis' or email = '$email'"));
                    $total_kata = count($each);
                    if ($total_kata > 1) {
                        $nama = trim($each[1]);
                        for ($i = 0; $i < $total_kata; $i++) {
                            if ($i > 1) {
                                $nama .= " ";
                                $nama .= $each[$i];
                            }
                        }
                    }
                    $student['nomor_induk'] = $nis;
                    $student['nama_lengkap'] = $nama;
                    //if(!empty($cekUE)){
                    //	$student['status'] = 'Terpakai';
                    //}else{
                    //	$student['status'] = 'Baru';
                    //}

                    array_push($all_student, $student);
                    //echo $nis." ".trim($nama)." (".$total_kata.")<br>";
                }
                $wrap_data['messages'] = 'success';
                $wrap_data['data'] = $all_student;
            }
            //print_r($_POST['datasiswa']);
            echo json_encode($wrap_data, JSON_PRETTY_PRINT);
            //print_r($raw_json);
            //echo "</pre>";
        }
    }

    public function actionDownloadAbsenPelajaran($id)
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

        
    
        //------


                $user = User::model()->findByPk(Yii::app()->user->id);
                $prefix = Yii::app()->params['tablePrefix'];

                  $term_datasis = "semester = " . $optSemester . " and year = " . $optTahunAjaran . " and lesson_id = " . $id . " and t.trash is null";
                    $siswa = new CActiveDataProvider('LessonMc', array(
                        'criteria' => array(
                            'join' => 'JOIN users AS u ON u.id = t.user_id ',
                            'condition' => '' . $term_datasis,
                            'order' => 'u.display_name ASC',
                        ),
                        'pagination' => array('pageSize' => 300)
                    ));

                // $siswa = User::model()->findAll(array('condition'=>'class_id = '.$id.' and trash is null order by display_name ASC'));
                Yii::import('ext.phpexcel.XPHPExcel');
                  $objPHPExcel= XPHPExcel::createPHPExcel();
                  $objPHPExcel->getProperties()->setCreator($user->display_name)
                                         ->setLastModifiedBy($user->display_name)
                                         ->setTitle("Daftar User ")
                                         ->setSubject("")
                                         ->setDescription("")
                                         ->setKeywords("")
                                         ->setCategory("Daftar User");

                $styleArray = array(
                                'font'  => array(
                                    'bold'  => true,
                                    'color' => array('rgb' => 'FF9900'),
                                    'size'  => 11,
                                    'name'  => 'Verdana'
                                ));
                $style = array(
                    'alignment' => array(
                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                    ),
                    'font'=>array(
                        'bold'=>true,
                        ),
                    'borders' => array(
                        'allborders' => array(
                          'style' => PHPExcel_Style_Border::BORDER_THIN
                        )
                      )
                );

                $style1 = array(
                    'font'=>array(
                        'bold'=>true,
                        ),
                     'borders' => array(
                        'allborders' => array(
                          'style' => PHPExcel_Style_Border::BORDER_THIN
                        )
                      )
                );
                $style3 = array(
                    'font'=>array(
                        'bold'=>true,
                        ),
                );

                 $style2 = array(
                     'borders' => array(
                        'allborders' => array(
                          'style' => PHPExcel_Style_Border::BORDER_THIN
                        )
                      )
                );

                // if(!empty($keyword)){
                //  $objPHPExcel->setActiveSheetIndex(0)
                            // ->setCellValue('C2','Daftar User '.$keyword);
                // } else {
                //  $objPHPExcel->setActiveSheetIndex(0)
                            // ->setCellValue('C2','Daftar User ');
                // }


                // $objPHPExcel->getActiveSheet()->getStyle('C2')->applyFromArray($styleArray);

                // Add some data
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A1', 'Nama')
                            ->setCellValue('B1', 'UH 1')
                            ->setCellValue('C1', 'UH 2')
                            ->setCellValue('D1', 'UH 3')
                            ->setCellValue('E1', 'UH 4')
                            ->setCellValue('F1', 'UH 5')
                            ->setCellValue('G1', 'UH 6')
                            ->setCellValue('H1', 'UH 7')
                            ->setCellValue('I1', 'R_NTU')
                            ->setCellValue('J1', 'UTS_P')
                            ->setCellValue('K1', 'UTS_K')
                            ->setCellValue('L1', 'N_UAS')
                            ->setCellValue('M1', 'N.PRAK')
                            ->setCellValue('N1', 'N.PROJ')
                            ->setCellValue('O1', 'N.PORT')
                            ->setCellValue('P1', 'PENGE')
                            ->setCellValue('Q1', 'KET');
                            // ->getStyle('B10:B11')->applyFromArray($style);
                // $objPHPExcel->getActiveSheet()->getStyle('C10:C11')->applyFromArray($style);
                // $objPHPExcel->getActiveSheet()->getStyle('A10:A11')->applyFromArray($style);
                // $objPHPExcel->getActiveSheet()->getStyle('D10:D11')->applyFromArray($style);
                // $objPHPExcel->getActiveSheet()->getStyle('E10:E11')->applyFromArray($style);
                // $objPHPExcel->getActiveSheet()->getStyle('F10:F11')->applyFromArray($style);

                            $objPHPExcel->getActiveSheet()
                            ->getColumnDimension('A')
                            ->setAutoSize(true);
                
                $objPHPExcel->getActiveSheet()
                            ->getColumnDimension('B')
                            ->setAutoSize(true);

                $objPHPExcel->getActiveSheet()
                            ->getColumnDimension('C')
                            ->setAutoSize(true);

                $objPHPExcel->getActiveSheet()
                            ->getColumnDimension('D')
                            ->setAutoSize(true);

                $objPHPExcel->getActiveSheet()
                            ->getColumnDimension('E')
                            ->setAutoSize(true);

                $objPHPExcel->getActiveSheet()
                            ->getColumnDimension('F')
                            ->setAutoSize(true);


                $objPHPExcel->getActiveSheet()
                            ->getColumnDimension('G')
                            ->setAutoSize(true);


                $objPHPExcel->getActiveSheet()
                            ->getColumnDimension('H')
                            ->setAutoSize(true);


                $objPHPExcel->getActiveSheet()
                            ->getColumnDimension('I')
                            ->setAutoSize(true);


                $objPHPExcel->getActiveSheet()
                            ->getColumnDimension('J')
                            ->setAutoSize(true);


                $objPHPExcel->getActiveSheet()
                            ->getColumnDimension('K')
                            ->setAutoSize(true);


                $objPHPExcel->getActiveSheet()
                            ->getColumnDimension('L')
                            ->setAutoSize(true);


                $objPHPExcel->getActiveSheet()
                            ->getColumnDimension('M')
                            ->setAutoSize(true);


                $objPHPExcel->getActiveSheet()
                            ->getColumnDimension('N')
                            ->setAutoSize(true);


                $objPHPExcel->getActiveSheet()
                            ->getColumnDimension('O')
                            ->setAutoSize(true);


                $objPHPExcel->getActiveSheet()
                            ->getColumnDimension('P')
                            ->setAutoSize(true);


                $objPHPExcel->getActiveSheet()
                            ->getColumnDimension('Q')
                            ->setAutoSize(true);

                $huruf = range('D', 'Z');
                $no=2;
                $counter=1;
                $cell=0;
                foreach ($siswa->getData() as $key) {
                    $objPHPExcel->setActiveSheetIndex(0)
                                ->setCellValue('A'.$no.'', ''.$key->student->display_name.'');

                    $no++;
                    $counter++;
                    $cell++;
                }
                //$objPHPExcel->getActiveSheet()->getStyle('A12:'.$next[10].$no++)->applyFromArray($style2);
                // Rename worksheet
                $objPHPExcel->getActiveSheet()->setTitle('Daftar User');


                // Set active sheet index to the first sheet, so Excel opens this as the first sheet
                $objPHPExcel->setActiveSheetIndex(0);


                // Redirect output to a clientâ€™s web browser (Excel5)
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="Daftar-Form-Input-Nilai.xls"');
                header('Cache-Control: max-age=0');
                // If you're serving to IE 9, then the following may be needed
                header('Cache-Control: max-age=1');

                // If you're serving to IE over SSL, then the following may be needed
                header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
                header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
                header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
                header ('Pragma: public'); // HTTP/1.0

                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
                $objWriter->save('php://output');
                      Yii::app()->end();
    }

    public function actionCopykd()
    {
        if (isset($_POST['datasiswa'])) {
            //echo "<pre>";
            $wrap_data = array();
            $all_student = array();
            $student = array();
            $wrap_data['messages'] = 'failed';
            if (!empty($_POST['datasiswa'])) {

                $raw = $_POST['datasiswa'];
                $data = preg_split('/\r\n|[\r\n]/', $raw);
                //print_r($data);
                //echo count($data);


                foreach ($data as $value) {
                    if ($value != '') {
                        $each = preg_split('/\s+/', $value);


                        $kd1 = trim($each[0]);
                        $kd2 = trim($each[1]);
                        $kd3 = trim($each[2]);
                        $kd4 = trim($each[3]);
                        $kd5 = trim($each[4]);
                        $kd6 = trim($each[5]);
                        $kd7 = trim($each[6]);
                        $r_ntu = trim($each[7]);
                        $uts_p = trim($each[8]);
                        $uts_k = trim($each[9]);
                        $n_uas = trim($each[10]);
                        $n_prak = trim($each[11]);
                        $n_proj = trim($each[12]);
                        $n_port = trim($each[13]);
                        $peng = trim($each[14]);
                        $ket = trim($each[15]);
                        //$cekUE=User::model()->findAll(array("condition"=>"username = '$nis' or email = '$email'"));
                        // $total_kata = count($each);
                        // if($total_kata > 7){
                        // 	$nama = trim($each[1]);
                        // 	for ($i=0; $i < $total_kata; $i++) {
                        // 		if($i>7){
                        // 			$nama .= " ";
                        // 			$nama .= $each[$i];
                        // 		}
                        // 	}
                        // }
                        $student['kd1'] = $kd1;
                        $student['kd2'] = $kd2;
                        $student['kd3'] = $kd3;
                        $student['kd4'] = $kd4;
                        $student['kd5'] = $kd5;
                        $student['kd6'] = $kd6;
                        $student['kd7'] = $kd7;
                        $student['tugas'] = $r_ntu;
                        $student['uts_p'] = $uts_p;
                        $student['uts_k'] = $uts_k;
                        $student['n_uas'] = $n_uas;
                        $student['n_prak'] = $n_prak;
                        $student['n_proj'] = $n_proj;
                        $student['n_port'] = $n_port;
                        $student['peng'] = $peng;
                        $student['ket'] = $ket;

                        //if(!empty($cekUE)){
                        //	$student['status'] = 'Terpakai';
                        //}else{
                        //	$student['status'] = 'Baru';
                        //}

                        array_push($all_student, $student);
                        //echo $nis." ".trim($nama)." (".$total_kata.")<br>";

                    }
                }
                $wrap_data['messages'] = 'success';
                $wrap_data['data'] = $all_student;
            }
            //print_r($_POST['datasiswa']);
            echo json_encode($wrap_data, JSON_PRETTY_PRINT);
            //print_r($raw_json);
            //echo "</pre>";
        }
    }

    public function actionInputData($id)
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

        $model = $this->loadModel($id);
        $prefix = Yii::app()->params['tablePrefix'];

        if (isset($_POST['nis'])) {

            //echo "<pre>";
            //print_r($_POST);
            if (!empty($_POST['nis']) && !empty($_POST['nama'])) {
                $result = array_combine($_POST['nis'], $_POST['nama']);
                //print_r($result);
                $length = 5;
                $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $charactersLength = strlen($characters);

                $sukses = 0;

                foreach ($result as $key => $value) {
                    $randomString = '';

                    for ($i = 0; $i < $length; $i++) {
                        $randomString .= $characters[rand(0, $charactersLength - 1)];
                    }

                    $nis = $key;
                    $nama = $value;
                    $email = $randomString . "@mail.id";
                    $password = $randomString;
                    $role = 2;
                    $ph = new PasswordHash(Yii::app()->params['phpass']['iteration_count_log2'], Yii::app()->params['phpass']['portable_hashes']);
                    $passwd = $ph->HashPassword($password);
                    $teacher_id = $model->user_id;
                    if ($model->moving_class == 1) {
                        $class_id = NULL;
                    } else {
                        $class_id = $model->class_id;
                    }


                    $cekUE = User::model()->findAll(array("condition" => "username = '$nis' or email = '$email'"));

                    if (!empty($cekUE)) {
                        $user_id = $cekUE[0]->id;

                        $lmc = LessonMc::model()->findAll(array("condition" => "semester = " . $optSemester . " and year = " . $optTahunAjaran . " and lesson_id = " . $id . " and user_id = " . $user_id));

                        if (empty($lmc)) {
                            $join = "INSERT INTO " . $prefix . "lesson_mc (lesson_id,user_id,teacher_id,semester,year) values(:lesson_id,:user_id,:teacher_id,:semester,:year)";

                            $joinCommand = Yii::app()->db->createCommand($join);

                            $joinCommand->bindParam(":lesson_id", $id, PDO::PARAM_STR);
                            $joinCommand->bindParam(":user_id", $user_id, PDO::PARAM_STR);
                            $joinCommand->bindParam(":teacher_id", $teacher_id, PDO::PARAM_STR);
                            $joinCommand->bindParam(":semester", $optSemester, PDO::PARAM_STR);
                            $joinCommand->bindParam(":year", $optTahunAjaran, PDO::PARAM_STR);

                            if ($joinCommand->execute()) {
                                $sukses++;
                            }
                        }

                    } else {
                        $insert = "INSERT INTO " . $prefix . "users (email,username,encrypted_password,role_id,created_at,updated_at,reset_password,display_name,class_id) values(:email,:username,:encrypted_password,:role_id,NOW(),NOW(),:reset_password,:display_name,:class_id)";

                        $insertCommand = Yii::app()->db->createCommand($insert);

                        $insertCommand->bindParam(":email", $email, PDO::PARAM_STR);
                        $insertCommand->bindParam(":username", $nis, PDO::PARAM_STR);
                        $insertCommand->bindParam(":encrypted_password", $passwd, PDO::PARAM_STR);
                        $insertCommand->bindParam(":role_id", $role, PDO::PARAM_STR);
                        $insertCommand->bindParam(":reset_password", $password, PDO::PARAM_STR);
                        $insertCommand->bindParam(":display_name", $nama, PDO::PARAM_STR);
                        $insertCommand->bindParam(":class_id", $class_id, PDO::PARAM_STR);

                        if ($insertCommand->execute()) {
                            $user_id = Yii::app()->db->getLastInsertId();
                            $join = "INSERT INTO " . $prefix . "lesson_mc (lesson_id,user_id,teacher_id,semester,year) values(:lesson_id,:user_id,:teacher_id,:semester,:year)";

                            $joinCommand = Yii::app()->db->createCommand($join);

                            $joinCommand->bindParam(":lesson_id", $id, PDO::PARAM_STR);
                            $joinCommand->bindParam(":user_id", $user_id, PDO::PARAM_STR);
                            $joinCommand->bindParam(":teacher_id", $teacher_id, PDO::PARAM_STR);
                            $joinCommand->bindParam(":semester", $optSemester, PDO::PARAM_STR);
                            $joinCommand->bindParam(":year", $optTahunAjaran, PDO::PARAM_STR);

                            if ($joinCommand->execute()) {
                                $sukses++;
                            }
                        }
                    }
                }

                if ($sukses > 0) {
                    Yii::app()->user->setFlash('success', 'Siswa Berhasil Didaftarkan!');

                    $this->redirect(array('view', 'id' => $id));
                } else {
                    Yii::app()->user->setFlash('error', 'Siswa Gagal Didaftarkan!');

                    $this->redirect(array('view', 'id' => $id));
                }
            }

            //echo "</pre>";

        }
    }

    public function actionAddFromTable($id)
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

        $model = $this->loadModel($id);
        $prefix = Yii::app()->params['tablePrefix'];

        if (isset($_POST['siswa'])) {

            //echo "<pre>";
            //print_r($_POST);
            $sukses = 0;
            if (!empty($_POST['siswa'])) {

                $siswa = $_POST['siswa'];
                foreach ($siswa as $value) {

                    $cekUE = User::model()->findByPk($value);

                    if (!empty($cekUE)) {
                        $user_id = $cekUE->id;

                        $lmc = LessonMc::model()->findAll(array("condition" => "semester = " . $optSemester . " and year = " . $optTahunAjaran . " and lesson_id = " . $id . " and user_id = " . $user_id));

                        if (empty($lmc)) {
                            $join = "INSERT INTO " . $prefix . "lesson_mc (lesson_id,user_id,teacher_id,semester,year) values(:lesson_id,:user_id,:teacher_id,:semester,:year)";

                            $joinCommand = Yii::app()->db->createCommand($join);

                            $joinCommand->bindParam(":lesson_id", $id, PDO::PARAM_STR);
                            $joinCommand->bindParam(":user_id", $user_id, PDO::PARAM_STR);
                            $joinCommand->bindParam(":teacher_id", $teacher_id, PDO::PARAM_STR);
                            $joinCommand->bindParam(":semester", $optSemester, PDO::PARAM_STR);
                            $joinCommand->bindParam(":year", $optTahunAjaran, PDO::PARAM_STR);

                            if ($joinCommand->execute()) {
                                $sukses++;
                            }
                        }

                    }
                }

                if ($sukses > 0) {
                    Yii::app()->user->setFlash('success', 'Siswa Berhasil Didaftarkan!');

                    $this->redirect(array('view', 'id' => $id));
                } else {
                    Yii::app()->user->setFlash('error', 'Siswa Sudah Terdaftar Di Pelajaran Ini!');

                    $this->redirect(array('view', 'id' => $id));
                }
            }

            //echo "</pre>";

        }
    }

    public function actionReplikaKd()
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

        $sql = "select * from lesson as l where id not in (select distinct(lesson_id) from lesson_kd)";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();


        $arr = array();
        $sukses = 0;
        $fail = 0;


        foreach ($rows as $key => $item) {

            $arr_desc = array();
            $sql_desc = "select * from lesson_kd as l where created_by = " . $item['user_id'] . "";
            $command_desc = Yii::app()->db->createCommand($sql_desc);
            $rows_desc = $command_desc->queryAll();

            $arr[$item['id']]["name"] = $item["name"];
            $arr[$item['id']]["user_id"] = $item["user_id"];

            if (!empty($rows_desc)) {
                foreach ($rows_desc as $key_desc => $item_desc) {
                    $arr[$item['id']]["kd"][$item_desc['title']] = $item_desc["description"];
                    // $arr[$item['id']]["nilai-".$item["tipe"]] = $item["nilai"];
                    // $arr[$item['id']]["desc-".$item["tipe"]] = $item["nilai_desc"];
                    $insert1 = "INSERT INTO lesson_kd (title,lesson_id,description,semester,tahun_ajaran,created_at,updated_at,created_by) values('" . $item_desc['title'] . "','" . $item['id'] . "','" . $item_desc["description"] . "',$optSemester,$optTahunAjaran,NOW(),NOW(),'" . Yii::app()->user->id . "')";
                    // echo $insert1.";</br>";
                    $insertCommand1 = Yii::app()->db->createCommand($insert1);


                    if ($insertCommand1->execute()) {
                        $sukses++;
                        echo $sukses;
                    } else {
                        $fail++;
                        echo $fail;
                    }

                }
            }
        }


        // echo "<pre>";
        //    	print_r($arr);
        //    echo "</pre>";


        // $this->render('index',array(
        // 	'dataProvider'=>$dataProvider,
        // ));
    }

    public function actionImportQuiz($id)
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
        $modelAct = new Activities;
        $model = new Quiz;
        if (isset($_POST['Activities'])) {
            $modelAct->attributes = $_POST['Activities'];
            $file = CUploadedFile::getInstance($modelAct, 'sqlFile');
            if (strlen($file) > 0) {
                $file->saveAs(Yii::app()->basePath . '/../images/' . $file);
                $theFile = Yii::app()->basePath . '/../images/' . $file;
                // echo $theFile;

                $pdo = Yii::app()->db->pdoInstance;
                try {
                    if (file_exists($theFile)) {
                        $sqlStream = file_get_contents($theFile);
                        $sqlStream = rtrim($sqlStream);
                        $newStream = preg_replace_callback("/\((.*)\)/", create_function('$matches', 'return str_replace(";"," $$$ ",$matches[0]);'), $sqlStream);
                        $sqlArray = explode(";", $newStream);
                        foreach ($sqlArray as $value) {
                            if (!empty($value)) {
                                $sql = str_replace(" $$$ ", ";", $value) . ";";
                                $pdo->exec($sql);
                                $ids[] = $pdo->lastInsertId();
                            }
                        }
                        $theids = implode(",", $ids);
                        // echo $theids;

                        $nama_quiz = explode(".", $file);
                        $nama_quiz = str_replace("_", " ", $nama_quiz[0]);

                        $model->title = $nama_quiz;
                        $model->end_time = 90;
                        $model->total_question = count($ids);
                        $model->question = $theids;
                        $model->random = null;
                        $model->random_opt = null;
                        $model->show_nilai = null;

                        $model->status = null;
                        $model->repeat_quiz = 1;
                        $model->lesson_id = $id;
                        $mapel = Lesson::model()->findByPk($model->lesson_id);
                        $model->created_by = $mapel->user_id;
                        $model->quiz_type == 0;
                        $model->passcode = "edubox";
                        $model->semester = $optSemester;
                        $model->year = $optTahunAjaran;

                        if ($model->save()) {
                            // echo "succeed to import the sql data!";
                            $this->redirect(array('/quiz/view', 'id' => $model->id));
                        }


                        // return true;
                    }
                } catch (PDOException $e) {
                    echo $e->getMessage();
                    exit;
                }
                // $this->redirect(array('view','id'=>$model->id_user));
            }
        } else {
            echo "File Kosong";
        }
    }

    public function actionNilaiRapor($id)
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

        $model = $this->loadModel($id);
        $mapel = Lesson::model()->findByAttributes(array('id' => $id));
        $kelas = ClassDetail::model()->findByAttributes(array('id' => $mapel->class_id));

        if (Yii::app()->user->YiiStudent) {
            Yii::app()->user->setFlash('error', "Maaf anda tidak punya izin akses!");
            $this->redirect(array('site/index'));
        }

     
            // $siswa = User::model()->findAll(array('condition'=>'class_id = '.$kelas->id.' ORDER BY display_name'));
            $lmc = LessonMc::model()->findAll(array("condition" => "semester = " . $optSemester . " and year = " . $optTahunAjaran . " and lesson_id = " . $id));
            $used_students = NULL;
            if (!empty($lmc)) {
                $used_id = array();
                foreach ($lmc as $mc) {
                    array_push($used_id, $mc->user_id);
                }
                $used_students = implode(',', $used_id);
            }
            $siswa = User::model()->findAll(array('condition' => 'role_id = 2 and trash is null and id in (' . $used_students . ') ORDER BY display_name'));
        

        $sql = "SELECT l.`id`,l.`name`,fm.`nilai`,fm.`nilai_desc`,fm.`tipe`,l.`kelompok`,l.`list_id`,u.`id` user_id,u.`display_name`
				FROM `final_mark` as fm
				join `users` as u on u.`id` = fm.`user_id`
				join `lesson` as l on fm.`lesson_id` = l.`id`
				WHERE l.`id` = " . $model->id . "
				and l.semester = " . $optSemester . "
				and l.year = " . $optTahunAjaran . "
				and u.`trash` is null 
				order by u.`display_name`";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();

        $nilai_arr = array();

        foreach ($rows as $key => $item) {
            // $item["nilai-".$item["tipe"]] = $item["nilai"];
            // $arr[$item['id']]["name"] = $item["name"];
            // $arr[$item['id']]["kelompok"] = $item["kelompok"];
            // $arr[$item['id']]["nilai-".$item["tipe"]] = $item["nilai"];

            $nilai_arr[$item["user_id"]]["kelompok"] = $item["kelompok"];
            $nilai_arr[$item["user_id"]]["nilai-" . $item["tipe"]] = $item["nilai"];
            $nilai_arr[$item["user_id"]]["desc-" . $item["tipe"]] = $item["nilai_desc"];
        }

        $sql2 = "SELECT q.`id`,q.`title`,sq.`score` as nilai, q.`quiz_type` as tipe,l.`kelompok`,l.`list_id`,u.`id` user_id,u.`display_name`
				FROM `student_quiz` as sq
				join `users` as u on u.`id` = sq.`student_id`
				join `quiz` as q on sq.`quiz_id` = q.`id`
				join `lesson` as l on q.`lesson_id` = l.`id`

				WHERE l.`id` = " . $model->id . "
				and l.semester = " . $optSemester . "
				and l.year = " . $optTahunAjaran . "
				and u.`trash` is null 
				order by u.`display_name`";
        $command2 = Yii::app()->db->createCommand($sql2);
        $rows2 = $command2->queryAll();

        

     


        foreach ($rows2 as $key2 => $item2) {
            $nilai_arr[$item2["user_id"]]["nilai-" . $item2["tipe"]][$item2["title"]] = $item2["nilai"];
            // $nilai_arr[$item2["user_id"]]["desc-".$item2["tipe"]] = $item2["nilai_desc"];
        }

        $sql3 = "SELECT *,count(absen) as absensi
                  FROM `absensi`
                  WHERE `id_lesson` = " . $model->id . "
                  group by user_id;";
        $command3 = Yii::app()->db->createCommand($sql3);
        $rows3 = $command3->queryAll();

        if (!empty($rows3)) {
            foreach ($rows3 as $key3 => $item3) {
                 $nilai_arr[$item3["user_id"]]["absensi"][$item3["absen"]] = $item3["absensi"];
             }    
        }
        

        // echo "<pre>";
        // 	print_r($nilai_arr);
        // echo "</pre>";

        $this->render('nilai-rapor', array(
            'model' => $this->loadModel($id),
            'nilai_arr' => $nilai_arr,
            'siswa' => $siswa,
        ));
    }


    public function actionEditNilaiRapor($id)
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

        $model = $this->loadModel($id);
        $mapel = Lesson::model()->findByAttributes(array('id' => $id));
        $kelas = ClassDetail::model()->findByAttributes(array('id' => $mapel->class_id));

        if (Yii::app()->user->YiiStudent) {
            Yii::app()->user->setFlash('error', "Maaf anda tidak punya izin akses!");
            $this->redirect(array('site/index'));
        }

       

            $lmc = LessonMc::model()->findAll(array("condition" => "semester = " . $optSemester . " and year = " . $optTahunAjaran . " and lesson_id = " . $id));
            $used_students = NULL;
            if (!empty($lmc)) {
                $used_id = array();
                foreach ($lmc as $mc) {
                    array_push($used_id, $mc->user_id);
                }
                $used_students = implode(',', $used_id);
            }
            $siswa = User::model()->findAll(array('condition' => 'role_id = 2 and trash is null and id in (' . $used_students . ') ORDER BY display_name'));
            // $siswa = User::model()->findAll(array('condition'=>'class_id = '.$kelas->id.' ORDER BY display_name'));
        

        $sql = "SELECT l.`id`,l.`name`,fm.`nilai`,fm.`nilai_desc`,fm.`tipe`,l.`kelompok`,l.`list_id`,u.`id` user_id,u.`display_name`
				FROM `final_mark` as fm
				join `users` as u on u.`id` = fm.`user_id`
				join `lesson` as l on fm.`lesson_id` = l.`id`
				WHERE l.`id` = " . $model->id . "
				and l.semester = " . $optSemester . "
				and l.year = " . $optTahunAjaran . "
				and u.`trash` is null 
				order by u.`display_name` DESC";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();

        $nilai_arr = array();
        $nilai_arr3 = array();
        $nilai_arr4 = array();

        foreach ($rows as $key => $item) {
            // $item["nilai-".$item["tipe"]] = $item["nilai"];
            // $arr[$item['id']]["name"] = $item["name"];
            // $arr[$item['id']]["kelompok"] = $item["kelompok"];
            // $arr[$item['id']]["nilai-".$item["tipe"]] = $item["nilai"];

            $nilai_arr[$item["user_id"]]["kelompok"] = $item["kelompok"];
            $nilai_arr[$item["user_id"]]["nilai-" . $item["tipe"]] = $item["nilai"];
            $nilai_arr[$item["user_id"]]["desc-" . $item["tipe"]] = $item["nilai_desc"];
        }

        $sql2 = "SELECT q.`id`,q.`title`,sq.`score` as nilai, q.`quiz_type` as tipe,l.`kelompok`,l.`list_id`,u.`id` user_id,u.`display_name`
				FROM `student_quiz` as sq
				join `users` as u on u.`id` = sq.`student_id`
				join `quiz` as q on sq.`quiz_id` = q.`id`
				join `lesson` as l on q.`lesson_id` = l.`id`

				WHERE l.`id` = " . $model->id . "
				and l.semester = " . $optSemester . "
				and l.year = " . $optTahunAjaran . "
				and u.`trash` is null 
				order by u.`display_name` DESC";
        $command2 = Yii::app()->db->createCommand($sql2);
        $rows2 = $command2->queryAll();


        foreach ($rows2 as $key2 => $item2) {
            $nilai_arr[$item2["user_id"]]["nilai-" . $item2["tipe"]][$item2["title"]] = $item2["nilai"];
            // $nilai_arr[$item2["user_id"]]["desc-".$item2["tipe"]] = $item2["nilai_desc"];
        }


         $sql4 = "SELECT q.`id`,q.`title`,sq.`score` as nilai,l.`kelompok`,l.`list_id`,u.`id` user_id,u.`display_name`
                FROM `student_assignment` as sq
                join `users` as u on u.`id` = sq.`student_id`
                join `assignment` as q on sq.`assignment_id` = q.`id`
                join `lesson` as l on q.`lesson_id` = l.`id`

                WHERE l.`id` = " . $model->id . "
                and l.semester = " . $optSemester . "
                and l.year = " . $optTahunAjaran . "
                and u.`trash` is null 
                order by u.`display_name` DESC";

        $command4 = Yii::app()->db->createCommand($sql4);
        $rows4 = $command4->queryAll();


        foreach ($rows4 as $key4 => $item4) {
            $nilai_arr4[$item4["user_id"]][$item4["title"]] = $item4["nilai"];
            // $nilai_arr[$item2["user_id"]]["desc-".$item2["tipe"]] = $item2["nilai_desc"];
        }

        if ($optSemester=="2") {
                // $sql_lid = "";
                $sql_li="SELECT `id` FROM `lesson` WHERE `list_id` = '" . $model->list_id . "' AND `class_id` = '" . $model->class_id . "' AND `semester` = '".$optSemester."' AND `year` = '" . $optTahunAjaran . "' AND trash is null";
                $command_li = Yii::app()->db->createCommand($sql_li);
                $rows_li = $command_li->queryAll();


                $sql3 = "SELECT l.`id`,l.`name`,fm.`nilai`,fm.`nilai_desc`,fm.`tipe`,l.`kelompok`,l.`list_id`,u.`id` user_id,u.`display_name`
                FROM `final_mark` as fm
                join `users` as u on u.`id` = fm.`user_id`
                join `lesson` as l on fm.`lesson_id` = l.`id`
                WHERE l.`id` = " . $rows_li[0]['id'] . "
                and l.semester = \"1\"
                and l.year = " . $optTahunAjaran . "
                and u.`trash` is null 
                order by u.`display_name` DESC";
                $command3 = Yii::app()->db->createCommand($sql3);
                $rows3 = $command3->queryAll();

                foreach ($rows3 as $key3 => $item3) {
                    // $item["nilai-".$item["tipe"]] = $item["nilai"];
                    // $arr[$item['id']]["name"] = $item["name"];
                    // $arr[$item['id']]["kelompok"] = $item["kelompok"];
                    // $arr[$item['id']]["nilai-".$item["tipe"]] = $item["nilai"];

                    $nilai_arr3[$item3["user_id"]]["kelompok"] = $item3["kelompok"];
                    $nilai_arr3[$item3["user_id"]]["nilai-" . $item3["tipe"]] = $item3["nilai"];
                    $nilai_arr3[$item3["user_id"]]["desc-" . $item3["tipe"]] = $item3["nilai_desc"];
                }
                // $nilai_arr3 = $rows3;
        }

        // echo "<pre>";
        // 	print_r($nilai_arr4);
        // echo "</pre>";

        $this->render('nilai-rapor-edit', array(
            'model' => $this->loadModel($id),
            'nilai_arr' => $nilai_arr,
            'nilai_arr3' => $nilai_arr3,
            'nilai_arr4' => $nilai_arr4,
            'siswa' => $siswa,
        ));
    }


    public function actionAddNilaiRapor($id)
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

        $mark = new OfflineMark;
        $nilai_kd1 = $_POST['score_kd1'];
        $nilai_kd2 = $_POST['score_kd2'];
        $nilai_kd3 = $_POST['score_kd3'];
        $nilai_kd4 = $_POST['score_kd4'];
        $nilai_kd5 = $_POST['score_kd5'];
        $nilai_kd6 = $_POST['score_kd6'];
        $nilai_kd7 = $_POST['score_kd7'];


        $nilai_tu1 = $_POST['score_tu1'];
        $nilai_tu2 = $_POST['score_tu2'];
        $nilai_tu3 = $_POST['score_tu3'];
        $nilai_tu4 = $_POST['score_tu4'];
        $nilai_tu5 = $_POST['score_tu5'];
        $nilai_tu6 = $_POST['score_tu6'];
        $nilai_tu7 = $_POST['score_tu7'];

        $nilai_pen = $_POST['score_pen'];

        $nilai_ket = $_POST['score_ket'];
        $nilai_tugas = $_POST['score_tugas'];
        $nilai_uas = $_POST['score_uas'];
        $nilai_uts_k = $_POST['score_uts_k'];
        $nilai_uts_p = $_POST['score_uts_p'];
        $nilai_kd1_ket = $_POST['score_kd1_ket'];
        $nilai_kd2_ket = $_POST['score_kd2_ket'];
        $nilai_kd3_ket = $_POST['score_kd3_ket'];


        // $nilai_sik = $_POST['score_sik'];
        $siswa = $_POST['student_id'];
        $semester = $optSemester;
        $tahun_ajaran = $optTahunAjaran;
        $lid = $_POST['lesson_id'];
        $result_kd1 = array_combine($siswa, $nilai_kd1);
        $result_kd2 = array_combine($siswa, $nilai_kd2);
        $result_kd3 = array_combine($siswa, $nilai_kd3);
        $result_kd4 = array_combine($siswa, $nilai_kd4);
        $result_kd5 = array_combine($siswa, $nilai_kd5);
        $result_kd6 = array_combine($siswa, $nilai_kd6);
        $result_kd7 = array_combine($siswa, $nilai_kd7);


         $result_tu1 = array_combine($siswa, $nilai_tu1);
        $result_tu2 = array_combine($siswa, $nilai_tu2);
        $result_tu3 = array_combine($siswa, $nilai_tu3);
        $result_tu4 = array_combine($siswa, $nilai_tu4);
        $result_tu5 = array_combine($siswa, $nilai_tu5);
        $result_tu6 = array_combine($siswa, $nilai_tu6);
        $result_tu7 = array_combine($siswa, $nilai_tu7);

        $result_pen = array_combine($siswa, $nilai_pen);
        $result_ket = array_combine($siswa, $nilai_ket);

        $result_tugas = array_combine($siswa, $nilai_tugas);
        $result_uas = array_combine($siswa, $nilai_uas);
        $result_uts_k = array_combine($siswa, $nilai_uts_k);
        $result_uts_p = array_combine($siswa, $nilai_uts_p);

        $result_kd1_ket = array_combine($siswa, $nilai_kd1_ket);
        $result_kd2_ket = array_combine($siswa, $nilai_kd2_ket);
        $result_kd3_ket = array_combine($siswa, $nilai_kd3_ket);
        // $result_sik = array_combine($siswa, $nilai_sik);
        $current_user = Yii::app()->user->id;
        $prefix = Yii::app()->params['tablePrefix'];
        $ida = 41;
        $mt = 1;
        $dt = 0;


        foreach ($result_kd1 as $key => $value) {
            if (!empty($value)) {
                $cek = FinalMark::model()->findByAttributes(array('user_id' => $key, 'lesson_id' => $lid, 'tipe' => 'kd1', 'semester' => $semester, 'tahun_ajaran' => $tahun_ajaran));
                if (!empty($cek)) {
                    if ($value > 100) {
                        $the_score = 0;
                        $fm_id = $cek->id;
                        $sql = "UPDATE " . $prefix . "final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        $command->bindParam(":updated_by", $current_user, PDO::PARAM_STR);
                        $command->bindParam(":score", $the_score, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    } elseif ($value == 1) {
                        $fm_id = $cek->id;
                        //$sql="UPDATE ".$prefix."final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $sql = "DELETE FROM final_mark WHERE id = :id";

                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    } else {
                        $fm_id = $cek->id;
                        $sql = "UPDATE " . $prefix . "final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        $command->bindParam(":updated_by", $current_user, PDO::PARAM_STR);
                        $command->bindParam(":score", $value, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    }
                } else {
                    $sql = "INSERT INTO " . $prefix . "final_mark (lesson_id, user_id, created_at, updated_at, created_by, nilai, tipe,semester, tahun_ajaran) VALUES(:lid,:sid,NOW(),NOW(),:created_by,:score,'kd1', :semester,:tahun_ajaran)";
                    $command = Yii::app()->db->createCommand($sql);
                    $command->bindParam(":lid", $lid, PDO::PARAM_STR);
                    $command->bindParam(":sid", $key, PDO::PARAM_STR);
                    $command->bindParam(":created_by", $current_user, PDO::PARAM_STR);
                    $command->bindParam(":score", $value, PDO::PARAM_STR);
                    $command->bindParam(":semester", $semester, PDO::PARAM_STR);
                    $command->bindParam(":tahun_ajaran", $tahun_ajaran, PDO::PARAM_STR);
                    if ($command->execute()) {
                        $dt++;
                    }
                }
            }

        }
        foreach ($result_kd2 as $key => $value) {
            if (!empty($value)) {
                $cek = FinalMark::model()->findByAttributes(array('user_id' => $key, 'lesson_id' => $lid, 'tipe' => 'kd2', 'semester' => $semester, 'tahun_ajaran' => $tahun_ajaran));
                if (!empty($cek)) {
                    if ($value > 100) {
                        $the_score = 0;
                        $fm_id = $cek->id;
                        $sql = "UPDATE " . $prefix . "final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        $command->bindParam(":updated_by", $current_user, PDO::PARAM_STR);
                        $command->bindParam(":score", $the_score, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    } elseif ($value == 1) {
                        $fm_id = $cek->id;
                        //$sql="UPDATE ".$prefix."final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $sql = "DELETE FROM final_mark WHERE id = :id";

                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    } else {
                        $fm_id = $cek->id;
                        $sql = "UPDATE " . $prefix . "final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        $command->bindParam(":updated_by", $current_user, PDO::PARAM_STR);
                        $command->bindParam(":score", $value, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    }
                } else {
                    $sql = "INSERT INTO " . $prefix . "final_mark (lesson_id, user_id, created_at, updated_at, created_by, nilai, tipe,semester, tahun_ajaran) VALUES(:lid,:sid,NOW(),NOW(),:created_by,:score,'kd2', :semester,:tahun_ajaran)";
                    $command = Yii::app()->db->createCommand($sql);
                    $command->bindParam(":lid", $lid, PDO::PARAM_STR);
                    $command->bindParam(":sid", $key, PDO::PARAM_STR);
                    $command->bindParam(":created_by", $current_user, PDO::PARAM_STR);
                    $command->bindParam(":score", $value, PDO::PARAM_STR);
                    $command->bindParam(":semester", $semester, PDO::PARAM_STR);
                    $command->bindParam(":tahun_ajaran", $tahun_ajaran, PDO::PARAM_STR);
                    if ($command->execute()) {
                        $dt++;
                    }
                }
            }

        }
        foreach ($result_kd3 as $key => $value) {
            if (!empty($value)) {
                $cek = FinalMark::model()->findByAttributes(array('user_id' => $key, 'lesson_id' => $lid, 'tipe' => 'kd3', 'semester' => $semester, 'tahun_ajaran' => $tahun_ajaran));
                if (!empty($cek)) {
                    if ($value > 100) {
                        $the_score = 0;
                        $fm_id = $cek->id;
                        $sql = "UPDATE " . $prefix . "final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        $command->bindParam(":updated_by", $current_user, PDO::PARAM_STR);
                        $command->bindParam(":score", $the_score, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    } elseif ($value == 1) {
                        $fm_id = $cek->id;
                        //$sql="UPDATE ".$prefix."final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $sql = "DELETE FROM final_mark WHERE id = :id";

                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    } else {
                        $fm_id = $cek->id;
                        $sql = "UPDATE " . $prefix . "final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        $command->bindParam(":updated_by", $current_user, PDO::PARAM_STR);
                        $command->bindParam(":score", $value, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    }
                } else {
                    $sql = "INSERT INTO " . $prefix . "final_mark (lesson_id, user_id, created_at, updated_at, created_by, nilai, tipe,semester, tahun_ajaran) VALUES(:lid,:sid,NOW(),NOW(),:created_by,:score,'kd3', :semester,:tahun_ajaran)";
                    $command = Yii::app()->db->createCommand($sql);
                    $command->bindParam(":lid", $lid, PDO::PARAM_STR);
                    $command->bindParam(":sid", $key, PDO::PARAM_STR);
                    $command->bindParam(":created_by", $current_user, PDO::PARAM_STR);
                    $command->bindParam(":score", $value, PDO::PARAM_STR);
                    $command->bindParam(":semester", $semester, PDO::PARAM_STR);
                    $command->bindParam(":tahun_ajaran", $tahun_ajaran, PDO::PARAM_STR);
                    if ($command->execute()) {
                        $dt++;
                    }
                }
            }

        }
        foreach ($result_kd4 as $key => $value) {
            if (!empty($value)) {
                $cek = FinalMark::model()->findByAttributes(array('user_id' => $key, 'lesson_id' => $lid, 'tipe' => 'kd4', 'semester' => $semester, 'tahun_ajaran' => $tahun_ajaran));
                if (!empty($cek)) {
                    if ($value > 100) {
                        $the_score = 0;
                        $fm_id = $cek->id;
                        $sql = "UPDATE " . $prefix . "final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        $command->bindParam(":updated_by", $current_user, PDO::PARAM_STR);
                        $command->bindParam(":score", $the_score, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    } elseif ($value == 1) {
                        $fm_id = $cek->id;
                        //$sql="UPDATE ".$prefix."final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $sql = "DELETE FROM final_mark WHERE id = :id";

                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    } else {
                        $fm_id = $cek->id;
                        $sql = "UPDATE " . $prefix . "final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        $command->bindParam(":updated_by", $current_user, PDO::PARAM_STR);
                        $command->bindParam(":score", $value, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    }
                } else {
                    $sql = "INSERT INTO " . $prefix . "final_mark (lesson_id, user_id, created_at, updated_at, created_by, nilai, tipe,semester, tahun_ajaran) VALUES(:lid,:sid,NOW(),NOW(),:created_by,:score,'kd4', :semester,:tahun_ajaran)";
                    $command = Yii::app()->db->createCommand($sql);
                    $command->bindParam(":lid", $lid, PDO::PARAM_STR);
                    $command->bindParam(":sid", $key, PDO::PARAM_STR);
                    $command->bindParam(":created_by", $current_user, PDO::PARAM_STR);
                    $command->bindParam(":score", $value, PDO::PARAM_STR);
                    $command->bindParam(":semester", $semester, PDO::PARAM_STR);
                    $command->bindParam(":tahun_ajaran", $tahun_ajaran, PDO::PARAM_STR);
                    if ($command->execute()) {
                        $dt++;
                    }
                }
            }

        }
        foreach ($result_kd5 as $key => $value) {
            if (!empty($value)) {
                $cek = FinalMark::model()->findByAttributes(array('user_id' => $key, 'lesson_id' => $lid, 'tipe' => 'kd5', 'semester' => $semester, 'tahun_ajaran' => $tahun_ajaran));
                if (!empty($cek)) {
                    if ($value > 100) {
                        $the_score = 0;
                        $fm_id = $cek->id;
                        $sql = "UPDATE " . $prefix . "final_mark SET nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        $command->bindParam(":updated_by", $current_user, PDO::PARAM_STR);
                        $command->bindParam(":score", $the_score, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    } elseif ($value == 1) {
                        $fm_id = $cek->id;
                        //$sql="UPDATE ".$prefix."final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $sql = "DELETE FROM final_mark WHERE id = :id";

                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    } else {
                        $fm_id = $cek->id;
                        $sql = "UPDATE " . $prefix . "final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        $command->bindParam(":updated_by", $current_user, PDO::PARAM_STR);
                        $command->bindParam(":score", $value, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    }
                } else {
                    $sql = "INSERT INTO " . $prefix . "final_mark (lesson_id, user_id, created_at, updated_at, created_by, nilai, tipe,semester, tahun_ajaran) VALUES(:lid,:sid,NOW(),NOW(),:created_by,:score,'kd5', :semester,:tahun_ajaran)";
                    $command = Yii::app()->db->createCommand($sql);
                    $command->bindParam(":lid", $lid, PDO::PARAM_STR);
                    $command->bindParam(":sid", $key, PDO::PARAM_STR);
                    $command->bindParam(":created_by", $current_user, PDO::PARAM_STR);
                    $command->bindParam(":score", $value, PDO::PARAM_STR);
                    $command->bindParam(":semester", $semester, PDO::PARAM_STR);
                    $command->bindParam(":tahun_ajaran", $tahun_ajaran, PDO::PARAM_STR);
                    if ($command->execute()) {
                        $dt++;
                    }
                }
            }

        }
        foreach ($result_kd6 as $key => $value) {
            if (!empty($value)) {
                $cek = FinalMark::model()->findByAttributes(array('user_id' => $key, 'lesson_id' => $lid, 'tipe' => 'kd6', 'semester' => $semester, 'tahun_ajaran' => $tahun_ajaran));
                if (!empty($cek)) {
                    if ($value > 100) {
                        $the_score = 0;
                        $fm_id = $cek->id;
                        $sql = "UPDATE " . $prefix . "final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        $command->bindParam(":updated_by", $current_user, PDO::PARAM_STR);
                        $command->bindParam(":score", $the_score, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    } elseif ($value == 1) {
                        $fm_id = $cek->id;
                        //$sql="UPDATE ".$prefix."final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $sql = "DELETE FROM final_mark WHERE id = :id";

                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    } else {
                        $fm_id = $cek->id;
                        $sql = "UPDATE " . $prefix . "final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        $command->bindParam(":updated_by", $current_user, PDO::PARAM_STR);
                        $command->bindParam(":score", $value, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    }
                } else {
                    $sql = "INSERT INTO " . $prefix . "final_mark (lesson_id, user_id, created_at, updated_at, created_by, nilai, tipe,semester, tahun_ajaran) VALUES(:lid,:sid,NOW(),NOW(),:created_by,:score,'kd6', :semester,:tahun_ajaran)";
                    $command = Yii::app()->db->createCommand($sql);
                    $command->bindParam(":lid", $lid, PDO::PARAM_STR);
                    $command->bindParam(":sid", $key, PDO::PARAM_STR);
                    $command->bindParam(":created_by", $current_user, PDO::PARAM_STR);
                    $command->bindParam(":score", $value, PDO::PARAM_STR);
                    $command->bindParam(":semester", $semester, PDO::PARAM_STR);
                    $command->bindParam(":tahun_ajaran", $tahun_ajaran, PDO::PARAM_STR);
                    if ($command->execute()) {
                        $dt++;
                    }
                }
            }

        }
        foreach ($result_kd7 as $key => $value) {
            if (!empty($value)) {
                $cek = FinalMark::model()->findByAttributes(array('user_id' => $key, 'lesson_id' => $lid, 'tipe' => 'kd7', 'semester' => $semester, 'tahun_ajaran' => $tahun_ajaran));
                if (!empty($cek)) {
                    if ($value > 100) {
                        $the_score = 0;
                        $fm_id = $cek->id;
                        $sql = "UPDATE " . $prefix . "final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        $command->bindParam(":updated_by", $current_user, PDO::PARAM_STR);
                        $command->bindParam(":score", $the_score, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    } elseif ($value == 1) {
                        $fm_id = $cek->id;
                        //$sql="UPDATE ".$prefix."final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $sql = "DELETE FROM final_mark WHERE id = :id";

                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    } else {
                        $fm_id = $cek->id;
                        $sql = "UPDATE " . $prefix . "final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        $command->bindParam(":updated_by", $current_user, PDO::PARAM_STR);
                        $command->bindParam(":score", $value, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    }
                } else {
                    $sql = "INSERT INTO " . $prefix . "final_mark (lesson_id, user_id, created_at, updated_at, created_by, nilai, tipe,semester, tahun_ajaran) VALUES(:lid,:sid,NOW(),NOW(),:created_by,:score,'kd7', :semester,:tahun_ajaran)";
                    $command = Yii::app()->db->createCommand($sql);
                    $command->bindParam(":lid", $lid, PDO::PARAM_STR);
                    $command->bindParam(":sid", $key, PDO::PARAM_STR);
                    $command->bindParam(":created_by", $current_user, PDO::PARAM_STR);
                    $command->bindParam(":score", $value, PDO::PARAM_STR);
                    $command->bindParam(":semester", $semester, PDO::PARAM_STR);
                    $command->bindParam(":tahun_ajaran", $tahun_ajaran, PDO::PARAM_STR);
                    if ($command->execute()) {
                        $dt++;
                    }
                }
            }

        }

         foreach ($result_tu1 as $key => $value) {
            if (!empty($value)) {
                $cek = FinalMark::model()->findByAttributes(array('user_id' => $key, 'lesson_id' => $lid, 'tipe' => 'tu1', 'semester' => $semester, 'tahun_ajaran' => $tahun_ajaran));
                if (!empty($cek)) {
                    if ($value > 100) {
                        $the_score = 0;
                        $fm_id = $cek->id;
                        $sql = "UPDATE " . $prefix . "final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        $command->bindParam(":updated_by", $current_user, PDO::PARAM_STR);
                        $command->bindParam(":score", $the_score, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    } elseif ($value == 1) {
                        $fm_id = $cek->id;
                        //$sql="UPDATE ".$prefix."final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $sql = "DELETE FROM final_mark WHERE id = :id";

                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    } else {
                        $fm_id = $cek->id;
                        $sql = "UPDATE " . $prefix . "final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        $command->bindParam(":updated_by", $current_user, PDO::PARAM_STR);
                        $command->bindParam(":score", $value, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    }
                } else {
                    $sql = "INSERT INTO " . $prefix . "final_mark (lesson_id, user_id, created_at, updated_at, created_by, nilai, tipe,semester, tahun_ajaran) VALUES(:lid,:sid,NOW(),NOW(),:created_by,:score,'tu1', :semester,:tahun_ajaran)";
                    $command = Yii::app()->db->createCommand($sql);
                    $command->bindParam(":lid", $lid, PDO::PARAM_STR);
                    $command->bindParam(":sid", $key, PDO::PARAM_STR);
                    $command->bindParam(":created_by", $current_user, PDO::PARAM_STR);
                    $command->bindParam(":score", $value, PDO::PARAM_STR);
                    $command->bindParam(":semester", $semester, PDO::PARAM_STR);
                    $command->bindParam(":tahun_ajaran", $tahun_ajaran, PDO::PARAM_STR);
                    if ($command->execute()) {
                        $dt++;
                    }
                }
            }

        }
        foreach ($result_tu2 as $key => $value) {
            if (!empty($value)) {
                $cek = FinalMark::model()->findByAttributes(array('user_id' => $key, 'lesson_id' => $lid, 'tipe' => 'tu2', 'semester' => $semester, 'tahun_ajaran' => $tahun_ajaran));
                if (!empty($cek)) {
                    if ($value > 100) {
                        $the_score = 0;
                        $fm_id = $cek->id;
                        $sql = "UPDATE " . $prefix . "final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        $command->bindParam(":updated_by", $current_user, PDO::PARAM_STR);
                        $command->bindParam(":score", $the_score, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    } elseif ($value == 1) {
                        $fm_id = $cek->id;
                        //$sql="UPDATE ".$prefix."final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $sql = "DELETE FROM final_mark WHERE id = :id";

                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    } else {
                        $fm_id = $cek->id;
                        $sql = "UPDATE " . $prefix . "final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        $command->bindParam(":updated_by", $current_user, PDO::PARAM_STR);
                        $command->bindParam(":score", $value, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    }
                } else {
                    $sql = "INSERT INTO " . $prefix . "final_mark (lesson_id, user_id, created_at, updated_at, created_by, nilai, tipe,semester, tahun_ajaran) VALUES(:lid,:sid,NOW(),NOW(),:created_by,:score,'tu2', :semester,:tahun_ajaran)";
                    $command = Yii::app()->db->createCommand($sql);
                    $command->bindParam(":lid", $lid, PDO::PARAM_STR);
                    $command->bindParam(":sid", $key, PDO::PARAM_STR);
                    $command->bindParam(":created_by", $current_user, PDO::PARAM_STR);
                    $command->bindParam(":score", $value, PDO::PARAM_STR);
                    $command->bindParam(":semester", $semester, PDO::PARAM_STR);
                    $command->bindParam(":tahun_ajaran", $tahun_ajaran, PDO::PARAM_STR);
                    if ($command->execute()) {
                        $dt++;
                    }
                }
            }

        }
        foreach ($result_tu3 as $key => $value) {
            if (!empty($value)) {
                $cek = FinalMark::model()->findByAttributes(array('user_id' => $key, 'lesson_id' => $lid, 'tipe' => 'tu3', 'semester' => $semester, 'tahun_ajaran' => $tahun_ajaran));
                if (!empty($cek)) {
                    if ($value > 100) {
                        $the_score = 0;
                        $fm_id = $cek->id;
                        $sql = "UPDATE " . $prefix . "final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        $command->bindParam(":updated_by", $current_user, PDO::PARAM_STR);
                        $command->bindParam(":score", $the_score, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    } elseif ($value == 1) {
                        $fm_id = $cek->id;
                        //$sql="UPDATE ".$prefix."final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $sql = "DELETE FROM final_mark WHERE id = :id";

                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    } else {
                        $fm_id = $cek->id;
                        $sql = "UPDATE " . $prefix . "final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        $command->bindParam(":updated_by", $current_user, PDO::PARAM_STR);
                        $command->bindParam(":score", $value, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    }
                } else {
                    $sql = "INSERT INTO " . $prefix . "final_mark (lesson_id, user_id, created_at, updated_at, created_by, nilai, tipe,semester, tahun_ajaran) VALUES(:lid,:sid,NOW(),NOW(),:created_by,:score,'tu3', :semester,:tahun_ajaran)";
                    $command = Yii::app()->db->createCommand($sql);
                    $command->bindParam(":lid", $lid, PDO::PARAM_STR);
                    $command->bindParam(":sid", $key, PDO::PARAM_STR);
                    $command->bindParam(":created_by", $current_user, PDO::PARAM_STR);
                    $command->bindParam(":score", $value, PDO::PARAM_STR);
                    $command->bindParam(":semester", $semester, PDO::PARAM_STR);
                    $command->bindParam(":tahun_ajaran", $tahun_ajaran, PDO::PARAM_STR);
                    if ($command->execute()) {
                        $dt++;
                    }
                }
            }

        }
        foreach ($result_tu4 as $key => $value) {
            if (!empty($value)) {
                $cek = FinalMark::model()->findByAttributes(array('user_id' => $key, 'lesson_id' => $lid, 'tipe' => 'tu4', 'semester' => $semester, 'tahun_ajaran' => $tahun_ajaran));
                if (!empty($cek)) {
                    if ($value > 100) {
                        $the_score = 0;
                        $fm_id = $cek->id;
                        $sql = "UPDATE " . $prefix . "final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        $command->bindParam(":updated_by", $current_user, PDO::PARAM_STR);
                        $command->bindParam(":score", $the_score, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    } elseif ($value == 1) {
                        $fm_id = $cek->id;
                        //$sql="UPDATE ".$prefix."final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $sql = "DELETE FROM final_mark WHERE id = :id";

                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    } else {
                        $fm_id = $cek->id;
                        $sql = "UPDATE " . $prefix . "final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        $command->bindParam(":updated_by", $current_user, PDO::PARAM_STR);
                        $command->bindParam(":score", $value, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    }
                } else {
                    $sql = "INSERT INTO " . $prefix . "final_mark (lesson_id, user_id, created_at, updated_at, created_by, nilai, tipe,semester, tahun_ajaran) VALUES(:lid,:sid,NOW(),NOW(),:created_by,:score,'tu4', :semester,:tahun_ajaran)";
                    $command = Yii::app()->db->createCommand($sql);
                    $command->bindParam(":lid", $lid, PDO::PARAM_STR);
                    $command->bindParam(":sid", $key, PDO::PARAM_STR);
                    $command->bindParam(":created_by", $current_user, PDO::PARAM_STR);
                    $command->bindParam(":score", $value, PDO::PARAM_STR);
                    $command->bindParam(":semester", $semester, PDO::PARAM_STR);
                    $command->bindParam(":tahun_ajaran", $tahun_ajaran, PDO::PARAM_STR);
                    if ($command->execute()) {
                        $dt++;
                    }
                }
            }

        }
        foreach ($result_tu5 as $key => $value) {
            if (!empty($value)) {
                $cek = FinalMark::model()->findByAttributes(array('user_id' => $key, 'lesson_id' => $lid, 'tipe' => 'tu5', 'semester' => $semester, 'tahun_ajaran' => $tahun_ajaran));
                if (!empty($cek)) {
                    if ($value > 100) {
                        $the_score = 0;
                        $fm_id = $cek->id;
                        $sql = "UPDATE " . $prefix . "final_mark SET nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        $command->bindParam(":updated_by", $current_user, PDO::PARAM_STR);
                        $command->bindParam(":score", $the_score, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    } elseif ($value == 1) {
                        $fm_id = $cek->id;
                        //$sql="UPDATE ".$prefix."final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $sql = "DELETE FROM final_mark WHERE id = :id";

                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    } else {
                        $fm_id = $cek->id;
                        $sql = "UPDATE " . $prefix . "final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        $command->bindParam(":updated_by", $current_user, PDO::PARAM_STR);
                        $command->bindParam(":score", $value, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    }
                } else {
                    $sql = "INSERT INTO " . $prefix . "final_mark (lesson_id, user_id, created_at, updated_at, created_by, nilai, tipe,semester, tahun_ajaran) VALUES(:lid,:sid,NOW(),NOW(),:created_by,:score,'tu5', :semester,:tahun_ajaran)";
                    $command = Yii::app()->db->createCommand($sql);
                    $command->bindParam(":lid", $lid, PDO::PARAM_STR);
                    $command->bindParam(":sid", $key, PDO::PARAM_STR);
                    $command->bindParam(":created_by", $current_user, PDO::PARAM_STR);
                    $command->bindParam(":score", $value, PDO::PARAM_STR);
                    $command->bindParam(":semester", $semester, PDO::PARAM_STR);
                    $command->bindParam(":tahun_ajaran", $tahun_ajaran, PDO::PARAM_STR);
                    if ($command->execute()) {
                        $dt++;
                    }
                }
            }

        }
        foreach ($result_tu6 as $key => $value) {
            if (!empty($value)) {
                $cek = FinalMark::model()->findByAttributes(array('user_id' => $key, 'lesson_id' => $lid, 'tipe' => 'tu6', 'semester' => $semester, 'tahun_ajaran' => $tahun_ajaran));
                if (!empty($cek)) {
                    if ($value > 100) {
                        $the_score = 0;
                        $fm_id = $cek->id;
                        $sql = "UPDATE " . $prefix . "final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        $command->bindParam(":updated_by", $current_user, PDO::PARAM_STR);
                        $command->bindParam(":score", $the_score, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    } elseif ($value == 1) {
                        $fm_id = $cek->id;
                        //$sql="UPDATE ".$prefix."final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $sql = "DELETE FROM final_mark WHERE id = :id";

                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    } else {
                        $fm_id = $cek->id;
                        $sql = "UPDATE " . $prefix . "final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        $command->bindParam(":updated_by", $current_user, PDO::PARAM_STR);
                        $command->bindParam(":score", $value, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    }
                } else {
                    $sql = "INSERT INTO " . $prefix . "final_mark (lesson_id, user_id, created_at, updated_at, created_by, nilai, tipe,semester, tahun_ajaran) VALUES(:lid,:sid,NOW(),NOW(),:created_by,:score,'tu6', :semester,:tahun_ajaran)";
                    $command = Yii::app()->db->createCommand($sql);
                    $command->bindParam(":lid", $lid, PDO::PARAM_STR);
                    $command->bindParam(":sid", $key, PDO::PARAM_STR);
                    $command->bindParam(":created_by", $current_user, PDO::PARAM_STR);
                    $command->bindParam(":score", $value, PDO::PARAM_STR);
                    $command->bindParam(":semester", $semester, PDO::PARAM_STR);
                    $command->bindParam(":tahun_ajaran", $tahun_ajaran, PDO::PARAM_STR);
                    if ($command->execute()) {
                        $dt++;
                    }
                }
            }

        }
        foreach ($result_tu7 as $key => $value) {
            if (!empty($value)) {
                $cek = FinalMark::model()->findByAttributes(array('user_id' => $key, 'lesson_id' => $lid, 'tipe' => 'tu7', 'semester' => $semester, 'tahun_ajaran' => $tahun_ajaran));
                if (!empty($cek)) {
                    if ($value > 100) {
                        $the_score = 0;
                        $fm_id = $cek->id;
                        $sql = "UPDATE " . $prefix . "final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        $command->bindParam(":updated_by", $current_user, PDO::PARAM_STR);
                        $command->bindParam(":score", $the_score, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    } elseif ($value == 1) {
                        $fm_id = $cek->id;
                        //$sql="UPDATE ".$prefix."final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $sql = "DELETE FROM final_mark WHERE id = :id";

                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    } else {
                        $fm_id = $cek->id;
                        $sql = "UPDATE " . $prefix . "final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        $command->bindParam(":updated_by", $current_user, PDO::PARAM_STR);
                        $command->bindParam(":score", $value, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    }
                } else {
                    $sql = "INSERT INTO " . $prefix . "final_mark (lesson_id, user_id, created_at, updated_at, created_by, nilai, tipe,semester, tahun_ajaran) VALUES(:lid,:sid,NOW(),NOW(),:created_by,:score,'tu7', :semester,:tahun_ajaran)";
                    $command = Yii::app()->db->createCommand($sql);
                    $command->bindParam(":lid", $lid, PDO::PARAM_STR);
                    $command->bindParam(":sid", $key, PDO::PARAM_STR);
                    $command->bindParam(":created_by", $current_user, PDO::PARAM_STR);
                    $command->bindParam(":score", $value, PDO::PARAM_STR);
                    $command->bindParam(":semester", $semester, PDO::PARAM_STR);
                    $command->bindParam(":tahun_ajaran", $tahun_ajaran, PDO::PARAM_STR);
                    if ($command->execute()) {
                        $dt++;
                    }
                }
            }

        }

        foreach ($result_pen as $key => $value) {
            if (!empty($value)) {
                $cek = FinalMark::model()->findByAttributes(array('user_id' => $key, 'lesson_id' => $lid, 'tipe' => 'pengetahuan', 'semester' => $semester, 'tahun_ajaran' => $tahun_ajaran));
                if (!empty($cek)) {
                    if ($value > 100) {
                        $the_score = 0;
                        $fm_id = $cek->id;
                        $sql = "UPDATE " . $prefix . "final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE user_id = :user_id and lesson_id = :lesson_id and tipe = :tipe and semester = :semester and tahun_ajaran = :tahun_ajaran ";
                        $command = Yii::app()->db->createCommand($sql);
                       
                         $tipe = "pengetahuan";    
                        $command->bindParam(":user_id", $key, PDO::PARAM_STR);
                        $command->bindParam(":lesson_id", $lid, PDO::PARAM_STR);
                        $command->bindParam(":tipe", $tipe, PDO::PARAM_STR);
                        $command->bindParam(":semester", $semester, PDO::PARAM_STR);
                        $command->bindParam(":tahun_ajaran", $tahun_ajaran, PDO::PARAM_STR);    

                        // $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        $command->bindParam(":updated_by", $current_user, PDO::PARAM_STR);
                        $command->bindParam(":score", $the_score, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    } elseif ($value == 1) {
                        $fm_id = $cek->id;
                        //$sql="UPDATE ".$prefix."final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $sql = "DELETE FROM final_mark WHERE id = :id";

                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    } else {
                        $fm_id = $cek->id;
                        $sql = "UPDATE " . $prefix . "final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE user_id = :user_id and lesson_id = :lesson_id and tipe = :tipe and semester = :semester and tahun_ajaran = :tahun_ajaran ";
                        $command = Yii::app()->db->createCommand($sql);
                         
                        $tipe = "pengetahuan";    
                        $command->bindParam(":user_id", $key, PDO::PARAM_STR);
                        $command->bindParam(":lesson_id", $lid, PDO::PARAM_STR);
                        $command->bindParam(":tipe", $tipe, PDO::PARAM_STR);
                        $command->bindParam(":semester", $semester, PDO::PARAM_STR);
                        $command->bindParam(":tahun_ajaran", $tahun_ajaran, PDO::PARAM_STR);    

                        // $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        $command->bindParam(":updated_by", $current_user, PDO::PARAM_STR);
                        $command->bindParam(":score", $value, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    }
                } else {
                    $sql = "INSERT INTO " . $prefix . "final_mark (lesson_id, user_id, created_at, updated_at, created_by, nilai, tipe,semester, tahun_ajaran) VALUES(:lid,:sid,NOW(),NOW(),:created_by,:score,'pengetahuan', :semester,:tahun_ajaran)";
                    $command = Yii::app()->db->createCommand($sql);
                    $command->bindParam(":lid", $lid, PDO::PARAM_STR);
                    $command->bindParam(":sid", $key, PDO::PARAM_STR);
                    $command->bindParam(":created_by", $current_user, PDO::PARAM_STR);
                    $command->bindParam(":score", $value, PDO::PARAM_STR);
                    $command->bindParam(":semester", $semester, PDO::PARAM_STR);
                    $command->bindParam(":tahun_ajaran", $tahun_ajaran, PDO::PARAM_STR);
                    if ($command->execute()) {
                        $dt++;
                    }
                }
            }

        }

        foreach ($result_ket as $key => $value) {
            if (!empty($value)) {
                $cek = FinalMark::model()->findByAttributes(array('user_id' => $key, 'lesson_id' => $lid, 'tipe' => 'keterampilan', 'semester' => $semester, 'tahun_ajaran' => $tahun_ajaran));
                if (!empty($cek)) {
                    if ($value > 100) {
                        $the_score = 0;
                        $fm_id = $cek->id;
                         $sql = "UPDATE " . $prefix . "final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE user_id = :user_id and lesson_id = :lesson_id and tipe = :tipe and semester = :semester and tahun_ajaran = :tahun_ajaran ";
                        $command = Yii::app()->db->createCommand($sql);
                        
                        $tipe = "keterampilan";    
                        $command->bindParam(":user_id", $key, PDO::PARAM_STR);
                        $command->bindParam(":lesson_id", $lid, PDO::PARAM_STR);
                        $command->bindParam(":tipe", $tipe, PDO::PARAM_STR);
                        $command->bindParam(":semester", $semester, PDO::PARAM_STR);
                        $command->bindParam(":tahun_ajaran", $tahun_ajaran, PDO::PARAM_STR);    

                        // $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        $command->bindParam(":updated_by", $current_user, PDO::PARAM_STR);
                        $command->bindParam(":score", $the_score, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    } elseif ($value == 1) {
                        $fm_id = $cek->id;
                        //$sql="UPDATE ".$prefix."final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $sql = "DELETE FROM final_mark WHERE id = :id";

                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    } else {
                        $fm_id = $cek->id;
                        $sql = "UPDATE " . $prefix . "final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE user_id = :user_id and lesson_id = :lesson_id and tipe = :tipe and semester = :semester and tahun_ajaran = :tahun_ajaran ";
                        $command = Yii::app()->db->createCommand($sql);
                        
                        $tipe = "keterampilan";    
                        $command->bindParam(":user_id", $key, PDO::PARAM_STR);
                        $command->bindParam(":lesson_id", $lid, PDO::PARAM_STR);
                        $command->bindParam(":tipe", $tipe, PDO::PARAM_STR);
                        $command->bindParam(":semester", $semester, PDO::PARAM_STR);
                        $command->bindParam(":tahun_ajaran", $tahun_ajaran, PDO::PARAM_STR);    


                        // $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        $command->bindParam(":updated_by", $current_user, PDO::PARAM_STR);
                        $command->bindParam(":score", $value, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    }
                } else {
                    $sql = "INSERT INTO " . $prefix . "final_mark (lesson_id, user_id, created_at, updated_at, created_by, nilai, tipe,semester, tahun_ajaran) VALUES(:lid,:sid,NOW(),NOW(),:created_by,:score,'keterampilan', :semester,:tahun_ajaran)";
                    $command = Yii::app()->db->createCommand($sql);
                    $command->bindParam(":lid", $lid, PDO::PARAM_STR);
                    $command->bindParam(":sid", $key, PDO::PARAM_STR);
                    $command->bindParam(":created_by", $current_user, PDO::PARAM_STR);
                    $command->bindParam(":score", $value, PDO::PARAM_STR);
                    $command->bindParam(":semester", $semester, PDO::PARAM_STR);
                    $command->bindParam(":tahun_ajaran", $tahun_ajaran, PDO::PARAM_STR);
                    if ($command->execute()) {
                        $dt++;
                    }
                }
            }

        }


        foreach ($result_tugas as $key => $value) {
            if (!empty($value)) {
                $cek = FinalMark::model()->findByAttributes(array('user_id' => $key, 'lesson_id' => $lid, 'tipe' => 'tugas', 'semester' => $semester, 'tahun_ajaran' => $tahun_ajaran));
                if (!empty($cek)) {
                    if ($value > 100) {
                        $the_score = 0;
                        $fm_id = $cek->id;
                        $sql = "UPDATE " . $prefix . "final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        $command->bindParam(":updated_by", $current_user, PDO::PARAM_STR);
                        $command->bindParam(":score", $the_score, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    } elseif ($value == 1) {
                        $fm_id = $cek->id;
                        //$sql="UPDATE ".$prefix."final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $sql = "DELETE FROM final_mark WHERE id = :id";

                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    } else {
                        $fm_id = $cek->id;
                        $sql = "UPDATE " . $prefix . "final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        $command->bindParam(":updated_by", $current_user, PDO::PARAM_STR);
                        $command->bindParam(":score", $value, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    }
                } else {
                    $sql = "INSERT INTO " . $prefix . "final_mark (lesson_id, user_id, created_at, updated_at, created_by, nilai, tipe,semester, tahun_ajaran) VALUES(:lid,:sid,NOW(),NOW(),:created_by,:score,'tugas', :semester,:tahun_ajaran)";
                    $command = Yii::app()->db->createCommand($sql);
                    $command->bindParam(":lid", $lid, PDO::PARAM_STR);
                    $command->bindParam(":sid", $key, PDO::PARAM_STR);
                    $command->bindParam(":created_by", $current_user, PDO::PARAM_STR);
                    $command->bindParam(":score", $value, PDO::PARAM_STR);
                    $command->bindParam(":semester", $semester, PDO::PARAM_STR);
                    $command->bindParam(":tahun_ajaran", $tahun_ajaran, PDO::PARAM_STR);
                    if ($command->execute()) {
                        $dt++;
                    }
                }
            }

        }

        foreach ($result_uas as $key => $value) {
            if (!empty($value)) {
                $cek = FinalMark::model()->findByAttributes(array('user_id' => $key, 'lesson_id' => $lid, 'tipe' => 'uas', 'semester' => $semester, 'tahun_ajaran' => $tahun_ajaran));
                if (!empty($cek)) {
                    if ($value > 100) {
                        $the_score = 0;
                        $fm_id = $cek->id;
                        $sql = "UPDATE " . $prefix . "final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        $command->bindParam(":updated_by", $current_user, PDO::PARAM_STR);
                        $command->bindParam(":score", $the_score, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    } elseif ($value == 1) {
                        $fm_id = $cek->id;
                        //$sql="UPDATE ".$prefix."final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $sql = "DELETE FROM final_mark WHERE id = :id";

                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    } else {
                        $fm_id = $cek->id;
                        $sql = "UPDATE " . $prefix . "final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        $command->bindParam(":updated_by", $current_user, PDO::PARAM_STR);
                        $command->bindParam(":score", $value, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    }
                } else {
                    $sql = "INSERT INTO " . $prefix . "final_mark (lesson_id, user_id, created_at, updated_at, created_by, nilai, tipe,semester, tahun_ajaran) VALUES(:lid,:sid,NOW(),NOW(),:created_by,:score,'uas', :semester,:tahun_ajaran)";
                    $command = Yii::app()->db->createCommand($sql);
                    $command->bindParam(":lid", $lid, PDO::PARAM_STR);
                    $command->bindParam(":sid", $key, PDO::PARAM_STR);
                    $command->bindParam(":created_by", $current_user, PDO::PARAM_STR);
                    $command->bindParam(":score", $value, PDO::PARAM_STR);
                    $command->bindParam(":semester", $semester, PDO::PARAM_STR);
                    $command->bindParam(":tahun_ajaran", $tahun_ajaran, PDO::PARAM_STR);
                    if ($command->execute()) {
                        $dt++;
                    }
                }
            }

        }


        foreach ($result_uts_p as $key => $value) {
            if (!empty($value)) {
                $cek = FinalMark::model()->findByAttributes(array('user_id' => $key, 'lesson_id' => $lid, 'tipe' => 'uts_p', 'semester' => $semester, 'tahun_ajaran' => $tahun_ajaran));
                if (!empty($cek)) {
                    if ($value > 100) {
                        $the_score = 0;
                        $fm_id = $cek->id;
                        $sql = "UPDATE " . $prefix . "final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        $command->bindParam(":updated_by", $current_user, PDO::PARAM_STR);
                        $command->bindParam(":score", $the_score, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    } elseif ($value == 1) {
                        $fm_id = $cek->id;
                        //$sql="UPDATE ".$prefix."final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $sql = "DELETE FROM final_mark WHERE id = :id";

                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    } else {
                        $fm_id = $cek->id;
                        $sql = "UPDATE " . $prefix . "final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        $command->bindParam(":updated_by", $current_user, PDO::PARAM_STR);
                        $command->bindParam(":score", $value, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    }
                } else {
                    $sql = "INSERT INTO " . $prefix . "final_mark (lesson_id, user_id, created_at, updated_at, created_by, nilai, tipe,semester, tahun_ajaran) VALUES(:lid,:sid,NOW(),NOW(),:created_by,:score,'uts_p', :semester,:tahun_ajaran)";
                    $command = Yii::app()->db->createCommand($sql);
                    $command->bindParam(":lid", $lid, PDO::PARAM_STR);
                    $command->bindParam(":sid", $key, PDO::PARAM_STR);
                    $command->bindParam(":created_by", $current_user, PDO::PARAM_STR);
                    $command->bindParam(":score", $value, PDO::PARAM_STR);
                    $command->bindParam(":semester", $semester, PDO::PARAM_STR);
                    $command->bindParam(":tahun_ajaran", $tahun_ajaran, PDO::PARAM_STR);
                    if ($command->execute()) {
                        $dt++;
                    }
                }
            }

        }

        foreach ($result_uts_k as $key => $value) {
            if (!empty($value)) {
                $cek = FinalMark::model()->findByAttributes(array('user_id' => $key, 'lesson_id' => $lid, 'tipe' => 'uts_k', 'semester' => $semester, 'tahun_ajaran' => $tahun_ajaran));
                if (!empty($cek)) {
                    if ($value > 100) {
                        $the_score = 0;
                        $fm_id = $cek->id;
                        $sql = "UPDATE " . $prefix . "final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        $command->bindParam(":updated_by", $current_user, PDO::PARAM_STR);
                        $command->bindParam(":score", $the_score, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    } elseif ($value == 1) {
                        $fm_id = $cek->id;
                        //$sql="UPDATE ".$prefix."final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $sql = "DELETE FROM final_mark WHERE id = :id";

                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    } else {
                        $fm_id = $cek->id;
                        $sql = "UPDATE " . $prefix . "final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        $command->bindParam(":updated_by", $current_user, PDO::PARAM_STR);
                        $command->bindParam(":score", $value, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    }
                } else {
                    $sql = "INSERT INTO " . $prefix . "final_mark (lesson_id, user_id, created_at, updated_at, created_by, nilai, tipe,semester, tahun_ajaran) VALUES(:lid,:sid,NOW(),NOW(),:created_by,:score,'uts_k', :semester,:tahun_ajaran)";
                    $command = Yii::app()->db->createCommand($sql);
                    $command->bindParam(":lid", $lid, PDO::PARAM_STR);
                    $command->bindParam(":sid", $key, PDO::PARAM_STR);
                    $command->bindParam(":created_by", $current_user, PDO::PARAM_STR);
                    $command->bindParam(":score", $value, PDO::PARAM_STR);
                    $command->bindParam(":semester", $semester, PDO::PARAM_STR);
                    $command->bindParam(":tahun_ajaran", $tahun_ajaran, PDO::PARAM_STR);
                    if ($command->execute()) {
                        $dt++;
                    }
                }
            }

        }

        foreach ($result_kd1_ket as $key => $value) {
            if (!empty($value)) {
                $cek = FinalMark::model()->findByAttributes(array('user_id' => $key, 'lesson_id' => $lid, 'tipe' => 'kd1_ket', 'semester' => $semester, 'tahun_ajaran' => $tahun_ajaran));
                if (!empty($cek)) {
                    if ($value > 100) {
                        $the_score = 0;
                        $fm_id = $cek->id;
                        $sql = "UPDATE " . $prefix . "final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        $command->bindParam(":updated_by", $current_user, PDO::PARAM_STR);
                        $command->bindParam(":score", $the_score, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    } elseif ($value == 1) {
                        $fm_id = $cek->id;
                        //$sql="UPDATE ".$prefix."final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $sql = "DELETE FROM final_mark WHERE id = :id";

                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    } else {
                        $fm_id = $cek->id;
                        $sql = "UPDATE " . $prefix . "final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        $command->bindParam(":updated_by", $current_user, PDO::PARAM_STR);
                        $command->bindParam(":score", $value, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    }
                } else {
                    $sql = "INSERT INTO " . $prefix . "final_mark (lesson_id, user_id, created_at, updated_at, created_by, nilai, tipe,semester, tahun_ajaran) VALUES(:lid,:sid,NOW(),NOW(),:created_by,:score,'kd1_ket', :semester,:tahun_ajaran)";
                    $command = Yii::app()->db->createCommand($sql);
                    $command->bindParam(":lid", $lid, PDO::PARAM_STR);
                    $command->bindParam(":sid", $key, PDO::PARAM_STR);
                    $command->bindParam(":created_by", $current_user, PDO::PARAM_STR);
                    $command->bindParam(":score", $value, PDO::PARAM_STR);
                    $command->bindParam(":semester", $semester, PDO::PARAM_STR);
                    $command->bindParam(":tahun_ajaran", $tahun_ajaran, PDO::PARAM_STR);
                    if ($command->execute()) {
                        $dt++;
                    }
                }
            }

        }
        foreach ($result_kd2_ket as $key => $value) {
            if (!empty($value)) {
                $cek = FinalMark::model()->findByAttributes(array('user_id' => $key, 'lesson_id' => $lid, 'tipe' => 'kd2_ket', 'semester' => $semester, 'tahun_ajaran' => $tahun_ajaran));
                if (!empty($cek)) {
                    if ($value > 100) {
                        $the_score = 0;
                        $fm_id = $cek->id;
                        $sql = "UPDATE " . $prefix . "final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        $command->bindParam(":updated_by", $current_user, PDO::PARAM_STR);
                        $command->bindParam(":score", $the_score, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    } elseif ($value == 1) {
                        $fm_id = $cek->id;
                        //$sql="UPDATE ".$prefix."final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $sql = "DELETE FROM final_mark WHERE id = :id";

                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    } else {
                        $fm_id = $cek->id;
                        $sql = "UPDATE " . $prefix . "final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        $command->bindParam(":updated_by", $current_user, PDO::PARAM_STR);
                        $command->bindParam(":score", $value, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    }
                } else {
                    $sql = "INSERT INTO " . $prefix . "final_mark (lesson_id, user_id, created_at, updated_at, created_by, nilai, tipe,semester, tahun_ajaran) VALUES(:lid,:sid,NOW(),NOW(),:created_by,:score,'kd2_ket', :semester,:tahun_ajaran)";
                    $command = Yii::app()->db->createCommand($sql);
                    $command->bindParam(":lid", $lid, PDO::PARAM_STR);
                    $command->bindParam(":sid", $key, PDO::PARAM_STR);
                    $command->bindParam(":created_by", $current_user, PDO::PARAM_STR);
                    $command->bindParam(":score", $value, PDO::PARAM_STR);
                    $command->bindParam(":semester", $semester, PDO::PARAM_STR);
                    $command->bindParam(":tahun_ajaran", $tahun_ajaran, PDO::PARAM_STR);
                    if ($command->execute()) {
                        $dt++;
                    }
                }
            }

        }
        foreach ($result_kd3_ket as $key => $value) {
            if (!empty($value)) {
                $cek = FinalMark::model()->findByAttributes(array('user_id' => $key, 'lesson_id' => $lid, 'tipe' => 'kd3_ket', 'semester' => $semester, 'tahun_ajaran' => $tahun_ajaran));
                if (!empty($cek)) {
                    if ($value > 100) {
                        $the_score = 0;
                        $fm_id = $cek->id;
                        $sql = "UPDATE " . $prefix . "final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        $command->bindParam(":updated_by", $current_user, PDO::PARAM_STR);
                        $command->bindParam(":score", $the_score, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    } elseif ($value == 1) {
                        $fm_id = $cek->id;
                        //$sql="UPDATE ".$prefix."final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $sql = "DELETE FROM final_mark WHERE id = :id";

                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    } else {
                        $fm_id = $cek->id;
                        $sql = "UPDATE " . $prefix . "final_mark SET sync_status = 2, nilai = :score, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";
                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":id", $fm_id, PDO::PARAM_STR);
                        $command->bindParam(":updated_by", $current_user, PDO::PARAM_STR);
                        $command->bindParam(":score", $value, PDO::PARAM_STR);
                        if ($command->execute()) {
                            $dt++;
                        }
                    }
                } else {
                    $sql = "INSERT INTO " . $prefix . "final_mark (lesson_id, user_id, created_at, updated_at, created_by, nilai, tipe,semester, tahun_ajaran) VALUES(:lid,:sid,NOW(),NOW(),:created_by,:score,'kd3_ket', :semester,:tahun_ajaran)";
                    $command = Yii::app()->db->createCommand($sql);
                    $command->bindParam(":lid", $lid, PDO::PARAM_STR);
                    $command->bindParam(":sid", $key, PDO::PARAM_STR);
                    $command->bindParam(":created_by", $current_user, PDO::PARAM_STR);
                    $command->bindParam(":score", $value, PDO::PARAM_STR);
                    $command->bindParam(":semester", $semester, PDO::PARAM_STR);
                    $command->bindParam(":tahun_ajaran", $tahun_ajaran, PDO::PARAM_STR);
                    if ($command->execute()) {
                        $dt++;
                    }
                }
            }

        }


        //print_r($result);
        //print_r($siswa);
        //echo count($result);
        //echo "</pre>";
        Yii::app()->user->setFlash('success', 'Input Nilai Siswa Berhasil !');
        $this->redirect(array('lesson/NilaiRapor', 'id' => $id));
    }

}
