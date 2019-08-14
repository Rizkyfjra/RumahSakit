<?php

class QuestionsController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';

    /**
     * @return array action filters
     */
    public function filters() {
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
    public function accessRules() {
        $cekFitur = Option::model()->findAll(array('condition' => 'key_config LIKE "%fitur_ulangan%"'));
        $status = 1;

        if (!empty($cekFitur)) {
            if ($cekFitur[0]->value == 2) {
                $status = 2;
            }
        }

        return array(
            /* array('allow',  // allow all users to perform 'index' and 'view' actions
              'actions'=>array('index','view'),
              'users'=>array('*'),
              ), */
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('index', 'view', 'create', 'update', 'bulkSoal', 'updateNilai', 'filter', 'formatQuestion', 'bulk', 'downloadFile', 'bulkUlangan', 'deleteQuestion', 'bulkExcel', 'bulkBanks', 'bulkxml', 'ajaxparsinghtml', 'ajaxparsingtabel', 'ajaxBulkBanks', 'xml_to_array'),
                'expression' => "(Yii::app()->user->YiiTeacher && $status == 1)",
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('index', 'view', 'filter', 'formatQuestion', 'downloadFile', 'Bulkxml'),
                'expression' => "(Yii::app()->user->YiiKepsek && $status == 1)",
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('index', 'view', 'filter', 'formatQuestion', 'downloadFile', 'bulkUlangan', 'Bulkxml'),
                'expression' => "(Yii::app()->user->YiiWali && $status == 1)",
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('index', 'view', 'admin', 'delete', 'create', 'update', 'bulkSoal', 'updateNilai', 'filter', 'formatQuestion', 'bulk', 'bulkExcel', 'downloadFile', 'bulkUlangan', 'deleteQuestion', 'Bulkxml', 'bulkBanks', 'ajaxparsinghtml', 'ajaxparsingtabel', 'ajaxBulkBanks', 'xml_to_array'),
                'expression' => "(Yii::app()->user->YiiAdmin && $status == 1)",
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id, $quiz = NULL) {
        $model = $this->loadModel($id);

        if ($model->trash == 1) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }

        if(Yii::app()->user->YiiTeacher && $model->created_by != Yii::app()->user->id) {
            Yii::app()->user->setFlash('success', 'Maaf Anda Tidak Punya Hak Akses');
           $this->redirect(array('/site/index'));
        }

        $this->render('v2/view', array(
            'model' => $this->loadModel($id),
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate($lesson_id = null) {
        $model = new Questions;
        $activity = new Activities;
        $usr = User::model()->findByPk(Yii::app()->user->id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        $idsoalnya = array();

        $indexSoal = 0;


        if (isset($_POST['Questions']) || isset($_POST['pil']) || isset($_POST['answer'])) {

            // echo count($_POST['Questions']);
            // echo "<pre>";
            // 	print_r($_POST['Questions']);
            // echo "</pre>";
            foreach ($_POST['Questions']['soalnya'] as $quest) {
                $model = new Questions;
                $_POST["Questions"]['soalnya'][$indexSoal]["text"] = str_replace("[math]", "$$", $_POST["Questions"]['soalnya'][$indexSoal]["text"]);
                $_POST["Questions"]['soalnya'][$indexSoal]["text"] = str_replace("[/math]", "$$", $_POST["Questions"]['soalnya'][$indexSoal]["text"]);


                $model->text = $_POST["Questions"]['soalnya'][$indexSoal]["text"];
                $model->quiz_id = NULL;
                $model->title = $_POST['Questions']['title'];

                if ($_POST['Questions']['type'] == NULL) {

                    $model->type = NULL;
                    $pil = $_POST['pil'][$indexSoal];
                    if (!empty($_POST['answer'][$indexSoal])) {
                        $kunci = $_POST['answer'][$indexSoal];
                    } else {
                        $kunci = NULL;
                    }

                    $jawaban = NULL;
                    if (!empty($kunci)) {
                        foreach ($pil as $key => $value) {
                            if ($kunci - 1 == $key) {
                                $jawaban = $value;
                            }
                        }
                    }

                    $pil = str_replace("[math]", "$$", $pil);
                    $pil = str_replace("[/math]", "$$", $pil);


                    $jawaban = str_replace("[math]", "$$", $jawaban);
                    $jawaban = str_replace("[/math]", "$$", $jawaban);

                    $pil = json_encode($pil);
                    $pil = str_replace(",\"\"", "", $pil);
                    $model->choices = $pil;
                    $model->key_answer = $jawaban;
                } else {
                    $model->type = $_POST['Questions']['type'];
                }




                $model->created_by = Yii::app()->user->id;
                $model->teacher_id = Yii::app()->user->id;

                if ($model->save()) {
                    $idsoalnya[] = $model->id;
                }
                $indexSoal++;
            }



            if (!empty($_POST['Questions']['quiz_id'])) {
                $qid = $_POST['Questions']['quiz_id'];
                $cekQuiz = Quiz::model()->findByPk($qid);
            }

            if (!empty($qid)) {
                if (!empty($cekQuiz->question)) {
                    $cekQuiz->question = $cekQuiz->question . "," . implode(',', $idsoalnya);
                } else {
                    $cekQuiz->question = implode(',', $idsoalnya);
                }
                $totQuestion = explode(',', $cekQuiz->question);
                $tq = count($totQuestion);
                $cekQuiz->total_question = $tq;
                $cekQuiz->sync_status = 2;
                $cekQuiz->save();
            }


            if (!empty(Yii::app()->session['returnURL'])) {
                Yii::app()->user->setFlash('success', 'Pertanyaan Berhasil Dibuat !');
                $this->redirect(Yii::app()->session['returnURL']);
                Yii::app()->session->remove('returnURL');
            } else {
                if (!empty($qid)) {
                    $this->redirect(array('/quiz/view', 'id' => $qid));
                } else {
                    $this->redirect(array('view', 'id' => $model->id));
                }
            }
        }

        $this->render('v2/form-word', array(
            'model' => $model, 'quiz_id' => $lesson_id
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $model = $this->loadModel($id);

        if ($model->trash == 1) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }

        $query = "select created_by FROM quiz WHERE FIND_IN_SET('".$id."', question);";
        $command = Yii::app()->db->createCommand($query);
        $result = $command->queryAll();

        $punyaDia = array();

        if (!empty($result)) {
            foreach ($result as $key => $value) {
                // print_r($value);
                // echo $value['created_by'];
                if ($value['created_by'] == Yii::app()->user->id) {
                   array_push($punyaDia, "ada");
                }
            }
        }

       
        if (!Yii::app()->user->YiiAdmin) {
            
        
            if(empty($punyaDia)) {
                Yii::app()->user->setFlash('success', 'Maaf Anda Tidak Punya Hak Akses');
               $this->redirect(array('/site/index'));
            }

        }

        $old_file = $model->file;
        $old_gmb = $model->choices_files;

        $activity = new Activities;
        $usr = User::model()->findByPk(Yii::app()->user->id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Questions']) || isset($_POST['pil']) || isset($_POST['answer'])) {

            $_POST["Questions"]["text"] = str_replace("[math]", "$$", $_POST["Questions"]["text"]);
            $_POST["Questions"]["text"] = str_replace("[/math]", "$$", $_POST["Questions"]["text"]);

            $model->attributes = $_POST['Questions'];

            if ($model->type == NULL || $model->type == '3') {

                if (!isset($_POST['pil'])) {
                    Yii::app()->user->setFlash('error', 'Untuk soal PG pilihan jawaban harus Disi !');
                    $this->redirect("#");
                }

                $pil = json_encode(array_values($_POST['pil']));
                $matching = $_POST['pil'];

                if (!empty($_POST['answer'])) {
                    $kunci = $_POST['answer'];
                } else {
                    $kunci = NULL;
                }

                $jawaban = NULL;
                $gambar = NULL;

                $tmp = json_decode($model->choices_files, true);
                $tmp_old = json_decode($model->choices_files, true);


                if (!empty($_POST['remove_pict'])) {
                    $remove_pict = explode(",", $_POST['remove_pict']);

                    $tmp_berubah = 'yes';
                    foreach ($remove_pict as $value) {
                        if ($value == 'kosong') {
                            $value = 0;
                        }

                        unset($tmp[$value]);
                        if ($value < $kunci) {
                            $kunci -= 1;
                        }
                    }
                    $path_image = Clases::model()->path_image($model->id);

                    $tmp_update = array();
                    foreach ($remove_pict as $value) {

                        if ($value == 'kosong') {
                            $value = 0;
                        }

                        foreach ($tmp as $key2 => $value2) {

                            if ($value < $key2) {
                                $indexnow = $key2 - 1;
                                $tmp_update[$indexnow] = $value2;

                                if (!file_exists(Yii::app()->basePath . '/../images/question' . $path_image . '/' . $indexnow)) {
                                    mkdir(Yii::app()->basePath . '/../images/question' . $path_image . '/' . $indexnow, 0775, true);
                                }

                                rename(Yii::app()->basePath . '/../images/question' . $path_image . '/' . $key2 . '/' . $value2, Yii::app()->basePath . '/../images/question' . $path_image . '/' . $indexnow . '/' . $value2);
                                if (isset($tmp_old[$value])) {
                                    unlink(Yii::app()->basePath . '/../images/question' . $path_image . '/' . $value . '/' . $tmp_old[$value]);
                                }
                            } else {
                                $tmp_update[$key2] = $value2;
                            }
                        }
                    }

                    $tmp = $tmp_update;
                }


                if (!empty($gambar)) {
                    $gmb = array();

                    foreach ($gambar['error'] as $k => $v) {
                        if ($v == UPLOAD_ERR_OK) {
                            $name = $gambar['name'][$k];
                            $temp_name = $gambar['tmp_name'][$k];
                            array_push($gmb, $name);
                            $tmp[$k] = $name;
                        }
                    }
                    if (!empty($tmp)) {
                        $model->choices_files = json_encode($tmp);
                    } elseif (!empty($tmp_berubah)) {
                        $model->choices_files = json_encode($tmp);
                    } else {
                        $model->choices_files = $old_gmb;
                    }
                } else {
                    if (!empty($tmp_berubah)) {
                        $model->choices_files = json_encode($tmp);
                    } else {
                        $model->choices_files = $old_gmb;
                    }
                }

                if (!empty($kunci)) {
                    foreach ($matching as $key => $value) {
                        if ($kunci - 1 == $key) {
                            $jawaban = $value;
                        }
                    }
                }

                $pil = str_replace("[math]", "$$", $pil);
                $pil = str_replace("[/math]", "$$", $pil);
                $pil = str_replace("[\/math]", "$$", $pil);

                $jawaban = str_replace("[math]", "$$", $jawaban);
                $jawaban = str_replace("[/math]", "$$", $jawaban);

                $model->key_answer = $jawaban;
                $model->choices = $pil;
            } else {

                //do nothing
            }

            if (!empty($model->quiz_id)) {
                $qid = $model->quiz_id;
                $cekQuiz = Quiz::model()->findByPk($qid);
                $model->quiz_id = NULL;
            }

            $uploadedFile = CUploadedFile::getInstance($model, 'file');
            if (!empty($uploadedFile)) {
                $model->file = $uploadedFile;
            } else {
                $model->file = $old_file;
            }

            $model->sync_status = 2;
            if ($model->save()) {
                $path_image = Clases::model()->path_image($model->id);

                $activity->activity_type = 'Update Soal';
                $activity->content = 'Guru' . $usr->display_name . ' Mengupdate Soal ' . $model->title;
                $activity->created_by = Yii::app()->user->id;
                $activity->save();

                if (!empty($uploadedFile)) {
                    if (!file_exists(Yii::app()->basePath . '/../images/question' . $path_image)) {
                        mkdir(Yii::app()->basePath . '/../images/question' . $path_image, 0775, true);
                    }
                }
                if (!empty($uploadedFile)) {
                    $uploadedFile->saveAs(Yii::app()->basePath . '/../images/question' . $path_image . '/' . $uploadedFile);
                }

                if (!empty($gambar)) {
                    foreach ($gambar['error'] as $k => $v) {
                        if ($v == UPLOAD_ERR_OK) {
                            $name = $gambar['name'][$k];
                            $temp_name = $gambar['tmp_name'][$k];
                            if (!file_exists(Yii::app()->basePath . '/../images/question' . $path_image . '/' . $k)) {
                                mkdir(Yii::app()->basePath . '/../images/question' . $path_image . '/' . $k, 0775, true);
                            }

                            move_uploaded_file($temp_name, Yii::app()->basePath . '/../images/question' . $path_image . '/' . $k . '/' . $name);
                        }
                    }
                }

                Yii::app()->user->setFlash('success', 'Pertanyaan Berhasil Diubah !');

                if (!empty(Yii::app()->session['returnURL'])) {
                    $this->redirect(Yii::app()->session['returnURL']);
                    Yii::app()->session->remove('returnURL');
                } else {
                    if (!empty($qid)) {
                        $this->redirect(array('/quiz/view', 'id' => $qid));
                    } else {
                        $this->redirect(array('view', 'id' => $model->id));
                    }
                }
            }
        }

        if (empty($cek)) {
            $this->render('v2/form-backup', array(
                'model' => $model,
            ));
        }
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        if (Yii::app()->user->YiiTeacher) {
            $term = ' teacher_id = ' . Yii::app()->user->id . ' and trash is null';
        } else {
            $term = 'trash is null';
        }

        $dataProvider = new CActiveDataProvider('Questions', array(
            'criteria' => array(
                'order' => 't.created_at DESC',
                'condition' => $term),
            'pagination' => array('pageSize' => 15),
        ));

        Yii::app()->session->remove('returnURL');
        $this->render('v2/list', array(
            'dataProvider' => $dataProvider,
        ));
    }

    public function actionFilter() {
        $term = '';
        if (isset($_GET)) {
            $tipe = $_GET['pilihan'];
            switch ($tipe) {
                case 1:
                    $keyword = strtolower($_GET['keyword']);
                    $cnd = "lower(title) LIKE '%" . $keyword . "%'" . " and trash is null";
                    break;
                case 2:
                    $pelajaran = $_GET['pelajaran'];
                    $cnd = "created_by = " . $pelajaran . " and trash is null ";
                    break;

                default:
                    $cnd = '';
                    break;
            }

            if (Yii::app()->user->YiiTeacher) {
                $term = '((share_status IS NULL or teacher_id = ' . Yii::app()->user->id . ') or (share_status = 2 and share_teacher LIKE "%,' . Yii::app()->user->id . ',%") or share_status = 3) and ' . $cnd;
            } else {
                $term = $cnd;
            }
        } else {
            if (Yii::app()->user->YiiTeacher) {
                $term = '((share_status IS NULL or teacher_id = ' . Yii::app()->user->id . ') or (share_status = 2 and share_teacher LIKE "%,' . Yii::app()->user->id . ',%") or share_status = 3) and trash is null';
            }
        }

        $dataProvider = new CActiveDataProvider('Questions', array(
            'criteria' => array(
                'order' => 't.created_at DESC',
                'condition' => $term),
            'pagination' => array('pageSize' => 10),
        ));

        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    public function actionBulkSoal() {
        if (isset($_POST['soal'])) {
            $quiz = Quiz::model()->findByPk($_POST['quiz']);

            $soal = $_POST['soal'];
            $count_id = count($soal);

            $data = implode(',', $soal);

            if (empty($_POST['soal']) || $count_id == NULL) {
                Yii::app()->user->setFlash('error', 'Harap Checklist Soal !');
                $this->redirect(array('/quiz/view', 'id' => $quiz->id));
            }

            if (!empty($quiz->question)) {
                $old_soal = explode(',', $quiz->question);
                $result = array_merge($old_soal, $soal);
                $quiz->question = implode(',', $result);
                $total = count($result);
                $quiz->total_question = $total;
            } else {
                $quiz->question = $data;
                $quiz->total_question = $count_id;
            }

            $quiz->sync_status = NULL;
            if ($quiz->save()) {
                Yii::app()->user->setFlash('success', '' . $count_id . ' Pertanyaan Berhasil Ditambahkan !');
                $this->redirect(array('/quiz/view', 'id' => $quiz->id));
            }

            $this->redirect(array('/quiz/view', 'id' => $quiz->id));
        } else {
            Yii::app()->user->setFlash('error', 'Harap Checklist Soal !');
            $this->redirect(Yii::app()->request->urlReferrer);
        }
    }

    public function actionUpdateNilai() {
        if (isset($_GET['quiz'])) {
            $qid = strtolower($_GET['quiz']);
            if (!Yii::app()->user->YiiTeacher) {
                $quiz = Quiz::model()->findAll(array("condition" => "lower(title) LIKE '%" . $qid . "%'"));
            } else {
                $quiz = Quiz::model()->findAll(array("condition" => "lower(title) LIKE '%" . $qid . "%' AND created_by = " . Yii::app()->user->id));
            }

            $ids = array();
            $quiz_id = NULL;
            $total_question = NULL;

            if (!empty($quiz)) {
                foreach ($quiz as $value) {
                    array_push($ids, $value->id);
                    $total_question = $value->total_question;
                }
                $quiz_id = implode(',', $ids);
            }

            $criteria = new CDbCriteria();
            $criteria->mergeWith(array(
                "order" => "created_at ASC",
                "condition" => "quiz_id IN(" . $quiz_id . ")",
            ));

            $count = StudentQuiz::model()->count($criteria);
            $pages = new CPagination($count);

            // results per page
            $pages->pageSize = 50;
            $perpage = $pages->pageSize;
            $pages->applyLimit($criteria);

            $data = StudentQuiz::model()->findAll($criteria);

            $cek = NULL;
            $gagal = NULL;

            if (!empty($data)) {

                foreach ($data as $key) {
                    $user = StudentQuiz::model()->findByPk($key->id);
                    $benar = NULL;
                    $salah = NULL;
                    $kosong = NULL;
                    $total_jawab = NULL;
                    if (!empty($key->student_answer)) {
                        $jawaban = unserialize($key->student_answer);
                        foreach ($jawaban as $k => $val) {
                            $soal = Questions::model()->findByPk($k);
                            if (strtolower($soal->key_answer) == strtolower($val)) {
                                $benar = $benar + 1;
                            } else {
                                $salah = $salah + 1;
                            }
                        }
                    }

                    $nilai = round(($benar / $total_question) * 100);

                    $user->right_answer = $benar;
                    $user->wrong_answer = $salah;
                    $user->score = $nilai;
                    $user->sync_status = 2;

                    if ($user->save()) {
                        $cek++;
                    } else {
                        $gagal++;
                    }
                }
            }

            Yii::app()->user->setFlash('success', 'Update Nilai ' . $cek . ' Siswa Berhasil !');
            $model = StudentQuiz::model()->findAll($criteria);
        } else {
            $model = null;
            $pages = null;
        }

        $this->render('nilai', array(
            'model' => $model,
            'pages' => $pages)
        );
    }

    public function actionFormatQuestion() {
        if (isset($_POST['soal'])) {
            $soals = $_POST['soal'];

            $no = 0;
            $total = 0;

            foreach ($soals as $value) {
                $cekSoal = Questions::model()->findByPk($value);
                if (!empty($cekSoal)) {
                    $path_image = Clases::model()->path_image($cekSoal->id);
                    if (!empty($cekSoal->file)) {
                        $cekSoal->file;
                        if (!file_exists(Yii::app()->basePath . '/../images/question' . $path_image)) {
                            mkdir(Yii::app()->basePath . '/../images/question' . $path_image, 0775, true);
                        }

                        if (file_exists(Yii::app()->basePath . '/../images/question/' . $cekSoal->id . '/' . $cekSoal->file)) {
                            $old = Yii::app()->basePath . '/../images/question/' . $cekSoal->id . '/' . $cekSoal->file;

                            copy($old, Yii::app()->basePath . '/../images/question' . $path_image . $cekSoal->file);
                        }
                    }

                    if (!empty($cekSoal->choices)) {
                        if (@unserialize($cekSoal->choices)) {
                            $butir = unserialize($cekSoal->choices);
                            if ($butir !== false) {
                                $finalButir = json_encode($butir);
                                $cekSoal->choices = $finalButir;
                            }
                        }
                    }

                    if (!empty($cekSoal->choices_files)) {
                        if (@unserialize($cekSoal->choices_files)) {
                            $gambar = unserialize($cekSoal->choices_files);
                            $tmp = array();
                            if (!empty($gambar)) {
                                if (empty($cekSoal->file)) {
                                    foreach ($gambar as $key => $value) {
                                        $tmp[$key] = $value;
                                        if (file_exists(Yii::app()->basePath . '/../images/question/' . $cekSoal->id . '/' . $key . '/' . $value)) {
                                            $old_gambar = Yii::app()->basePath . '/../images/question/' . $cekSoal->id . '/' . $key . '/' . $value;
                                            if (!file_exists(Yii::app()->basePath . '/../images/question' . $path_image . $key)) {
                                                mkdir(Yii::app()->basePath . '/../images/question' . $path_image . $key, 0775, true);
                                            }
                                            copy($old_gambar, Yii::app()->basePath . '/../images/question' . $path_image . $key . '/' . $value);
                                        }
                                    }
                                }
                                $cekSoal->choices_files = json_encode($gambar);
                            } else {
                                $cekSoal->choices_files = NULL;
                            }
                        }
                    }

                    $cekSoal->sync_status = 2;

                    if ($cekSoal->save()) {
                        $no++;
                    }

                    $total++;
                }
            }
            Yii::app()->user->setFlash('success', 'Update ' . $no . ' Dari ' . $total . ' Soal Berhasil');
            $this->redirect(array('index'));
        }
    }

    public function actionDeleteQuestion() {
        if (isset($_POST['soal'])) {
            $soals = $_POST['soal'];

            $no = 0;
            $total = count($soals);

            foreach ($soals as $value) {
                $cekSoal = Questions::model()->findByPk($value);

                if (!empty($cekSoal)) {
                    $cekSoal->trash = 1;
                    $cekSoal->sync_status = 2;

                    if ($cekSoal->save()) {
                        $no++;
                    }
                }
            }

            Yii::app()->user->setFlash('success', 'Hapus ' . $no . ' Soal Berhasil !');

            if (!empty(Yii::app()->session['returnURL'])) {
                $this->redirect(Yii::app()->session['returnURL']);
                Yii::app()->session->remove('returnURL');
            } else {
                $this->redirect(Yii::app()->request->urlReferrer);
            }
        }
    }

    public function actionBulkBanks() {
        if (isset($_POST['delete']) && isset($_POST['soal'])) {
            $soals = $_POST['soal'];

            $no = 0;
            foreach ($soals as $value) {
                $cekSoal = Questions::model()->findByPk($value);

                if (!empty($cekSoal)) {
                    $cekSoal->trash = 1;
                    $cekSoal->sync_status = 2;

                    if ($cekSoal->save()) {
                        $no++;
                    }
                }
            }
            Yii::app()->user->setFlash('success', 'Hapus ' . $no . ' Soal Berhasil !');
        } elseif (isset($_POST['share']) && (isset($_POST['soal']) or isset($_POST['single-id'])) && isset($_POST['share_status'])) {
            if ($_POST['share_status'] != 2) {
                if (isset($_POST['soal'])) {
                    $soals = $_POST['soal'];
                    $shareStatus = $_POST['share_status'];

                    $no = 0;
                    foreach ($soals as $value) {
                        $cekSoal = Questions::model()->findByPk($value);

                        if (!empty($cekSoal)) {
                            $cekSoal->share_status = $shareStatus;
                            $cekSoal->share_teacher = NULL;
                            $cekSoal->sync_status = 2;

                            if ($cekSoal->save()) {
                                $no++;
                            }
                        }
                    }
                } elseif (isset($_POST['single-id'])) {
                    $id = $_POST['single-id'];
                    $shareStatus = $_POST['share_status'];

                    $cekSoal = Questions::model()->findByPk($id);

                    if (!empty($cekSoal)) {
                        $cekSoal->share_status = $shareStatus;
                        $cekSoal->share_teacher = NULL;
                        $cekSoal->sync_status = 2;

                        if ($cekSoal->save()) {
                            $no = 1;
                        }
                    }
                }
            } elseif ($_POST['share_status'] == 2 && isset($_POST['guru'])) {
                if (isset($_POST['soal'])) {
                    $soals = $_POST['soal'];
                    $gurus = $_POST['guru'];

                    $shareStatus = $_POST['share_status'];
                    $shareTeacher = "," . implode(",", $gurus) . ",";

                    $no = 0;
                    foreach ($soals as $value) {
                        $cekSoal = Questions::model()->findByPk($value);

                        if (!empty($cekSoal)) {
                            $cekSoal->share_status = $shareStatus;
                            $cekSoal->share_teacher = $shareTeacher;
                            $cekSoal->sync_status = 2;

                            if ($cekSoal->save()) {
                                $no++;
                            }
                        }
                    }
                } elseif (isset($_POST['single-id'])) {
                    $id = $_POST['single-id'];
                    $gurus = $_POST['guru'];

                    $shareStatus = $_POST['share_status'];
                    $shareTeacher = "," . implode(",", $gurus) . ",";

                    $cekSoal = Questions::model()->findByPk($id);

                    if (!empty($cekSoal)) {
                        $cekSoal->share_status = $shareStatus;
                        $cekSoal->share_teacher = $shareTeacher;
                        $cekSoal->sync_status = 2;

                        if ($cekSoal->save()) {
                            $no = 1;
                        }
                    }
                }
            }
            Yii::app()->user->setFlash('success', 'Membagikan ' . $no . ' Soal Berhasil !');
        }

        if (!empty(Yii::app()->session['returnURL'])) {
            $this->redirect(Yii::app()->session['returnURL']);
            Yii::app()->session->remove('returnURL');
        } else {
            $this->redirect(Yii::app()->request->urlReferrer);
        }
    }

    public function actionAjaxBulkBanks($id = null) {
        if (empty($id)) {
            $this->renderPartial('v2/_ajax_bulk_banks', array(
            ));
        } else {
            $model = $this->loadModel($id);

            $this->renderPartial('v2/_ajax_bulk_banks', array(
                'model' => $model,
            ));
        }
    }

    public function actionBulk($id = null) {
        $model = new Activities;

        if (!empty($id)) {
            $quiz = Quiz::model()->findByPk($id);
        }

        if (isset($_POST['Activities'])) {
            $model->attributes = $_POST['Activities'];
            $filelist = CUploadedFile::getInstancesByName('csvfile');
            $prefix = Yii::app()->params['tablePrefix'];

            $ulangan = $_POST['ulangan'];

            $no = 0;
            $urutan = 1;

            $id_questions = array();
            if ($model->validate()) {
                $cek_id = "";
                foreach ($filelist as $file) {
                    try {
                        $transaction = Yii::app()->db->beginTransaction();
                        $handle = fopen("$file->tempName", "r");
                        $head = fgetcsv($handle, 10000, '#');
                        $row = 1;
                        $id_lot = array();
                        $nama_file = array();
                        $csv_array = array();
                        $string_id = "";
                        $nama_ulangan = "";

                        while (($data = fgetcsv($handle, 10000, "#")) !== FALSE) {
                            if ($row >= 1) {
                                $column = array_combine($head, $data);
                                $row2 = $row;
                                $gabungan = array();
                                $pilihan1 = NULL;
                                $pilihan2 = NULL;
                                $pilihan3 = NULL;
                                $pilihan4 = NULL;
                                $pilihan5 = NULL;
                                if (!empty($column['PERTANYAAN'])) {
                                    $pertanyaan = $column['PERTANYAAN'];
                                    $tipe = $column['tipe'];
                                    if (strtolower($tipe) == "isian") {
                                        $type = 1;
                                    } elseif (strtolower($tipe) == "esai") {
                                        $type = 2;
                                    } else {
                                        $type = NULL;
                                    }

                                    $pilihan1 = $column['A'];
                                    $pilihan2 = $column['B'];
                                    $pilihan3 = $column['C'];
                                    $pilihan4 = $column['D'];
                                    $pilihan5 = $column['E'];
                                    $kunci = $column['kunci_jawaban'];

                                    if ($type == NULL) {
                                        $kunci = trim($kunci);
                                        $kunci = strtoupper($kunci);
                                        $kunci_string = $column[$kunci];
                                        $kunci = $kunci_string;
                                    }

                                    if (empty($pertanyaan)) {
                                        Yii::app()->user->setFlash('error', "Baris $row2 . kolom pertanyaan harus di isi");
                                        $this->redirect(array('bulk'));
                                    }

                                    if (empty($column['kunci_jawaban'])) {
                                        $kunci = NULL;
                                    }

                                    if ($type == NULL) {
                                        $arr_temp = array();

                                        if (empty($pilihan1)) {
                                            Yii::app()->user->setFlash('error', "Baris $row2 . kolom pilihan1 harus di isi");
                                            $this->redirect(array('bulk'));
                                        }

                                        if (empty($pilihan2)) {
                                            Yii::app()->user->setFlash('error', "Baris $row2 . kolom pilihan2 harus di isi");
                                            $this->redirect(array('bulk'));
                                        }

                                        array_push($arr_temp, $pilihan1, $pilihan2, $pilihan3, $pilihan4, $pilihan5);

                                        foreach ($arr_temp as $raw => $isi) {
                                            if (!empty($isi)) {
                                                array_push($gabungan, $isi);
                                            }
                                        }
                                    }

                                    $new = new Questions;

                                    $new->title = "Soal " . $ulangan . " (" . $urutan . ")";
                                    $new->text = $pertanyaan;
                                    $new->type = $type;

                                    if ($type == NULL) {
                                        $new->choices = json_encode($gabungan);
                                        $new->key_answer = $kunci;
                                    } elseif ($type == 1) {
                                        $new->key_answer = $kunci;
                                    }

                                    if (!empty($id)) {
                                        $new->teacher_id = $quiz->created_by;
                                        $new->created_by = $quiz->created_by;
                                    } else {
                                        $new->teacher_id = Yii::app()->user->id;
                                        $new->created_by = Yii::app()->user->id;
                                    }

                                    if ($new->save()) {
                                        $idq = Yii::app()->db->getLastInsertId();
                                        array_push($id_questions, $idq);
                                        $no++;
                                    }
                                }
                            }
                            $row++;
                            $urutan++;
                        }

                        if (!empty($id_questions)) {
                            Yii::app()->user->setFlash('success', "Import " . $no . " Soal Berhasil!");
                        } else {
                            Yii::app()->user->setFlash('error', "Import Soal Gagal. Soal Kosong !");
                        }

                        $transaction->commit();
                    } catch (Exception $error) {
                        echo "<pre>";
                        print_r($error);
                        echo "</pre>";

                        $transaction->rollback();
                    }
                }

                if (!empty($id_questions) && !empty($id)) {
                    $total_soal = count($id_questions);
                    if (!empty($quiz->total_question)) {
                        $total_lama = $quiz->total_question;
                    } else {
                        $total_lama = 0;
                    }

                    if (!empty($quiz->question)) {
                        $raw_soal = explode(',', $quiz->question);
                        $combine_soal = array_merge($raw_soal, $id_questions);
                        $soal_quiz = implode(',', $combine_soal);
                    } else {
                        $soal_quiz = implode(',', $id_questions);
                    }

                    $quiz->total_question = $total_lama + $total_soal;
                    $quiz->question = $soal_quiz;

                    if ($quiz->save()) {
                        $this->redirect(array('/quiz/view', 'id' => $id));
                    }
                } else {
                    $this->redirect(array('/questions/bulk'));
                }
            } else {
                Yii::app()->user->setFlash('error', "Csv Gagal!");
            }
        }

        if (!empty($sukses)) {
            $row2 = $row - 1;
            Yii::app()->user->setFlash('success', "Berhasil buat $row2 record soal!");
        }

        $this->render('bulk', array(
            'model' => $model,
        ));
    }

    public function actionBulkExcel($id = null) {
        $model = new Activities;

        if (!empty($id)) {
            $quiz = Quiz::model()->findByPk($id);
        }

        if (isset($_POST['Activities'])) {
            $model->attributes = $_POST['Activities'];

            $xlsFile = CUploadedFile::getInstancesByName('csvfile');
            $prefix = Yii::app()->params['tablePrefix'];

            $ulangan = $_POST['ulangan'];

            $no = 0;
            $urutan = 1;

            $id_questions = array();
            if ($model->validate()) {
                foreach ($xlsFile as $excelFile) {
                    try {
                        $transaction = Yii::app()->db->beginTransaction();
                        $data = Yii::app()->yexcel->readActiveSheet($excelFile->tempName, "r");
                        $data_raw = array();
                        $raw = array();
                        $gambar = array();
                        $pilihan = NULL;
                        $row = 1;

                        $highestRow = $data->getHighestRow();
                        $highestColumn = $data->getHighestColumn();
                        $range = 'A2:H' . $highestRow . '';

                        $text = $data->rangeToArray($range);
                        $head = $data->rangeToArray('A1:H1');

                        foreach ($text as $key => $val) {
                            $column = array_combine($head[0], $val);
                            $row2 = $row;

                            $gabungan = array();
                            $pilihan1 = NULL;
                            $pilihan2 = NULL;
                            $pilihan3 = NULL;
                            $pilihan4 = NULL;
                            $pilihan5 = NULL;
                            $kunci = NULL;

                            if (!empty($column['PERTANYAAN'])) {

                                $pertanyaan = $column['PERTANYAAN'];
                                $tipe = $column['tipe'];
                                if (strtolower($tipe) == "isian") {
                                    $type = 1;
                                } elseif (strtolower($tipe) == "esai") {
                                    $type = 2;
                                } else {
                                    $type = NULL;
                                }

                                $pilihan1 = $column['A'];
                                $pilihan2 = $column['B'];
                                $pilihan3 = $column['C'];
                                $pilihan4 = $column['D'];
                                $pilihan5 = $column['E'];
                                $kunci = $column['kunci_jawaban'];

                                if ($type == NULL) {
                                    $kunci = trim($kunci);
                                    $kunci = strtoupper($kunci);
                                    $kunci_string = $column[$kunci];
                                    $kunci = $kunci_string;
                                }

                                if (empty($pertanyaan)) {
                                    Yii::app()->user->setFlash('error', "Baris $row2 . kolom pertanyaan harus di isi");
                                    $this->redirect(array('bulk'));
                                }

                                if (empty($column['kunci_jawaban'])) {
                                    $kunci = NULL;
                                }

                                if ($type == NULL) {
                                    $arr_temp = array();

                                    if (empty($pilihan1)) {
                                        Yii::app()->user->setFlash('error', "Baris $row2 . kolom pilihan1 harus di isi");
                                        $this->redirect(array('bulk'));
                                    }

                                    if (empty($pilihan2)) {
                                        Yii::app()->user->setFlash('error', "Baris $row2 . kolom pilihan2 harus di isi");
                                        $this->redirect(array('bulk'));
                                    }

                                    array_push($arr_temp, $pilihan1, $pilihan2, $pilihan3, $pilihan4, $pilihan5);
                                    foreach ($arr_temp as $raw => $isi) {
                                        if (!empty($isi)) {
                                            array_push($gabungan, $isi);
                                        }
                                    }
                                }

                                $new = new Questions;

                                $new->title = "Soal " . $ulangan . " (" . $urutan . ")";
                                $new->text = $pertanyaan;
                                $new->type = $type;

                                if ($type == NULL) {
                                    $new->choices = json_encode($gabungan);
                                    $new->key_answer = $kunci;
                                } elseif ($type == 1) {
                                    $new->key_answer = $kunci;
                                }

                                if (!empty($id)) {
                                    $new->teacher_id = $quiz->created_by;
                                    $new->created_by = $quiz->created_by;
                                } else {
                                    $new->teacher_id = Yii::app()->user->id;
                                    $new->created_by = Yii::app()->user->id;
                                }

                                if ($new->save()) {
                                    $idq = Yii::app()->db->getLastInsertId();

                                    array_push($id_questions, $idq);
                                    $no++;
                                }
                            }
                            $row++;
                            $urutan++;
                        }

                        if (!empty($id_questions)) {
                            Yii::app()->user->setFlash('success', "Import " . $no . " Soal Berhasil!");
                        } else {
                            Yii::app()->user->setFlash('error', "Import Soal Gagal. Soal Kosong !");
                        }
                        $transaction->commit();
                    } catch (Exception $error) {
                        $transaction->rollback();
                        throw new CHttpException($error);
                    }
                }

                if (!empty($id_questions) && !empty($id)) {
                    $total_soal = count($id_questions);
                    if (!empty($quiz->total_question)) {
                        $total_lama = $quiz->total_question;
                    } else {
                        $total_lama = 0;
                    }

                    if (!empty($quiz->question)) {
                        $raw_soal = explode(',', $quiz->question);
                        $combine_soal = array_merge($raw_soal, $id_questions);
                        $soal_quiz = implode(',', $combine_soal);
                    } else {
                        $soal_quiz = implode(',', $id_questions);
                    }

                    $quiz->total_question = $total_lama + $total_soal;
                    $quiz->question = $soal_quiz;

                    if ($quiz->save()) {
                        $this->redirect(array('/quiz/view', 'id' => $id));
                    }
                } else {
                    $this->redirect(array('/questions/bulkExcel'));
                }
            } else {
                Yii::app()->user->setFlash('error', "Import Gagal !");
            }
        }

        $this->render('bulk-excel', array(
            'model' => $model,
        ));
    }

    public function actionBulkUlangan() {

        $model = new Activities;

        if (isset($_POST['Activities'])) {
            $model->attributes = $_POST['Activities'];

            $filelist = CUploadedFile::getInstancesByName('csvfile');
            $prefix = Yii::app()->params['tablePrefix'];

            $ulangan = $_POST['ulangan'];
            $tipe_quiz = $_POST['tipe_quiz'];

            if (!empty($_POST['add_to_summary'])) {
                $add_to_summary = NULL;
            } else {
                $add_to_summary = 1;
            }

            if (!empty($_POST['show_nilai'])) {
                $show_nilai = 1;
            } else {
                $show_nilai = NULL;
            }

            $waktu = $_POST['waktu'];
            if (!empty($_POST['acak'])) {
                $acak = $_POST['acak'];
            } else {
                $acak = NULL;
            }

            $semester = $_POST['semester'];
            $no = 0;
            $urutan = 1;

            $id_questions = array();
            if ($model->validate()) {
                $cek_id = "";
                foreach ($filelist as $file) {
                    try {
                        $transaction = Yii::app()->db->beginTransaction();
                        $handle = fopen("$file->tempName", "r");
                        $head = fgetcsv($handle, 10000, '#');
                        $row = 1;
                        $id_lot = array();
                        $nama_file = array();
                        $csv_array = array();
                        $string_id = "";
                        $nama_ulangan = "";

                        while (($data = fgetcsv($handle, 10000, "#")) !== FALSE) {
                            if ($row >= 1) {
                                $column = array_combine($head, $data);
                                $row2 = $row;
                                $gabungan = array();
                                if (!empty($column['PERTANYAAN'])) {
                                    $pertanyaan = $column['PERTANYAAN'];
                                    $pilihan1 = $column['A'];
                                    $pilihan2 = $column['B'];
                                    $pilihan3 = $column['C'];
                                    $pilihan4 = $column['D'];
                                    $pilihan5 = $column['E'];
                                    $kunci = $column['kunci_jawaban'];

                                    $kunci = trim($kunci);
                                    $kunci = strtoupper($kunci);
                                    $kunci_string = $column[$kunci];
                                    $kunci = $kunci_string;

                                    array_push($gabungan, $pilihan1, $pilihan2, $pilihan3, $pilihan4, $pilihan5);
                                    //$email = $column['email'];
                                    //$password = $column['password'];

                                    if (empty($pertanyaan)) {
                                        Yii::app()->user->setFlash('error', "Baris $row2 . kolom pertanyaan harus di isi");
                                        $this->redirect(array('bulk'));
                                    }

                                    if (empty($pilihan1)) {
                                        Yii::app()->user->setFlash('error', "Baris $row2 . kolom pilihan1 harus di isi");
                                        $this->redirect(array('bulk'));
                                    }

                                    if (empty($pilihan2)) {
                                        Yii::app()->user->setFlash('error', "Baris $row2 . kolom pilihan2 harus di isi");
                                        $this->redirect(array('bulk'));
                                    }

                                    if (empty($pilihan3)) {
                                        Yii::app()->user->setFlash('error', "Baris $row2 . kolom pilihan3 harus di isi");
                                        $this->redirect(array('bulk'));
                                    }

                                    if (empty($pilihan4)) {
                                        Yii::app()->user->setFlash('error', "Baris $row2 . kolom pilihan4 harus di isi");
                                        $this->redirect(array('bulk'));
                                    }

                                    if (empty($pilihan5)) {
                                        Yii::app()->user->setFlash('error', "Baris $row2 . kolom pilihan5 harus di isi");
                                        $this->redirect(array('bulk'));
                                    }

                                    if (empty($kunci)) {
                                        Yii::app()->user->setFlash('error', "Baris $row2 . kolom kunci_jawaban harus di isi");
                                        $this->redirect(array('bulk'));
                                    }

                                    $new = new Questions;

                                    $new->title = "Soal " . $ulangan . " (" . $urutan . ")";
                                    $new->text = $pertanyaan;
                                    $new->choices = json_encode($gabungan);
                                    $new->key_answer = $kunci;
                                    $new->teacher_id = Yii::app()->user->id;

                                    if ($new->save()) {
                                        $idq = Yii::app()->db->getLastInsertId();
                                        array_push($id_questions, $idq);
                                        $no++;
                                    }
                                }
                            }
                            $row++;
                            $urutan++;
                        }

                        if (!empty($id_questions)) {
                            Yii::app()->user->setFlash('success', "Import " . $no . " Soal Berhasil!");
                        } else {
                            Yii::app()->user->setFlash('error', "Import Soal Gagal. Soal Kosong !");
                        }

                        $transaction->commit();
                    } catch (Exception $error) {
                        echo "<pre>";
                        print_r($error);
                        echo "</pre>";

                        $transaction->rollback();
                    }
                }
                if (!empty($id_questions)) {
                    if (!empty($_POST['pelajaran'])) {
                        $pelajaran = $_POST['pelajaran'];

                        foreach ($pelajaran as $value) {
                            $cekLesson = Lesson::model()->findByPk($value);
                            $newQuiz = new Quiz;
                            $newQuiz->title = $ulangan;
                            $newQuiz->lesson_id = $value;
                            $newQuiz->total_question = count($id_questions);
                            $newQuiz->add_to_summary = $add_to_summary;
                            $newQuiz->quiz_type = $tipe_quiz;
                            $newQuiz->end_time = $waktu;
                            $newQuiz->random = $acak;
                            $newQuiz->semester = $semester;
                            $newQuiz->created_by = $cekLesson->user_id;
                            $newQuiz->question = implode(',', $id_questions);
                            $newQuiz->year = date('Y');
                            $newQuiz->show_nilai = $show_nilai;
                            $newQuiz->repeat_quiz = 1;

                            if ($newQuiz->save()) {
                                Yii::app()->user->setFlash('success', 'Import ' . $no . ' Soal Berhasil!');
                            }
                        }
                    } else {
                        Yii::app()->user->setFlash('error', 'Tidak Ada Soal !');
                        $pelajaran = NULL;
                    }
                }
                $this->redirect(array('/site/index'));
            } else {
                Yii::app()->user->setFlash('error', "Csv Gagal!");
            }
        }

        if (!empty($sukses)) {
            $row2 = $row - 1;
            Yii::app()->user->setFlash('success', "Berhasil buat $row2 record soal!");
        }

        $this->render('bulk-2', array(
            'model' => $model,
        ));
    }

    public function actionDownloadFile() {
        $dir_path = Yii::getPathOfAlias('webroot') . '/images/';

        $filePlace = Yii::app()->basePath . '/../images/format-soal.xlsx';
        $fileName = "format-soal.xlsx";

        if (file_exists($filePlace)) {
            return Yii::app()->getRequest()->sendFile($fileName, @file_get_contents($filePlace));
        } else {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new Questions('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Questions']))
            $model->attributes = $_GET['Questions'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Questions the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = Questions::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Questions $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'questions-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionBulkxml() {
        $model = new Activities;
        if (isset($_POST['Activities'])) {
            $first = false;
            $filelist = CUploadedFile::getInstancesByName('xmlfile');
            $urutan = 1;
            $ulangan = $_POST['ulangan'];
            foreach ($filelist as $file) {
                $xml = simplexml_load_file($file->tempName, 'SimpleXMLElement', LIBXML_NOCDATA) or die("Error: Cannot create object");
                foreach ($xml->question as $value) {
                    if ($first) {
                        $gabungan = array();
                        $pilihan1 = NULL;
                        $pilihan2 = NULL;
                        $pilihan3 = NULL;
                        $pilihan4 = NULL;
                        $pilihan5 = NULL;
                        $jawabannya = null;
                        $jawaban = "";
                        if (!empty($value->questiontext->text)) {
                            $pertanyaan = trim($value->questiontext->text, "\n");
                            $pertanyaan = trim($pertanyaan);

                            $tipe = null;
                            if (strtolower($tipe) == "isian") {
                                $type = 1;
                            } elseif (strtolower($tipe) == "esai") {
                                $type = 2;
                            } else {
                                $type = NULL;
                            }

                            foreach ($value->answer as $jawab) {
                                $jawaban = (string) $jawab->text;
                                $jawaban = trim($jawaban, "\n");
                                $jawaban = trim($jawaban);

                                array_push($gabungan, $jawaban);

                                if ($jawab->attributes()['fraction'] == 100.00) {
                                    $jawabannya = $jawaban;
                                }
                            }
                            if ($jawabannya != null) {
                                $kunci = $jawabannya;
                            } else if ($jawabannya == null) {
                                $kunci = trim((string) $value->answer->text, "\n");
                                $kunci = trim($kunci);
                            }

                            $new = new Questions;
                            $new->title = "Soal " . $ulangan . " (" . $urutan . ")";
                            $new->text = $pertanyaan;
                            $new->text = str_replace("[math]", "$$", $new->text);
                            $new->text = str_replace("[/math]", "$$", $new->text);
                            $new->type = $type;

                            if ($type == NULL) {
                                $new->choices = json_encode($gabungan);
                                $new->choices = str_replace("[math]", "$$", $new->choices);
                                $new->choices = str_replace("[/math]", "$$", $new->choices);
                                $new->key_answer = $kunci;
                            } elseif ($type == 1) {
                                $new->key_answer = $kunci;
                            }

                            if (!empty($id)) {
                                $new->teacher_id = $quiz->created_by;
                                $new->created_by = $quiz->created_by;
                            } else {
                                $new->teacher_id = Yii::app()->user->id;
                                $new->created_by = Yii::app()->user->id;
                            }

                            $urutan++;
                            if ($new->save()) {

                                Yii::app()->user->setFlash('success', "Impor Soal Berhasil!");
                            } else {
                                Yii::app()->user->setFlash('error', "Import Soal Gagal!");
                            }
                        }
                    } else {
                        $first = true;
                    }
                }
            }
        }
        $this->render('bulk-xml', array(
            'model' => $model,
        ));
    }

    public function actionAjaxparsinghtml() {
        
        $_POST['wordhtml'] = str_replace("&nbsp;", "", $_POST['wordhtml']);
        $received_str = $_POST['wordhtml'];

        $dom = new DOMDocument();
        $dom->loadHTML($received_str);
        $dom->preserveWhiteSpace = false;

        $paragraphs = $dom->getElementsByTagName('p');
        $soal = "";
        $jawabanA = "";
        $jawabanB = "";
        $jawabanC = "";
        $jawabanD = "";
        $jawabanE = "";
        $doAppenSoal = true;
        $doAppenJawabanA = true;
        $doAppenJawabanB = true;
        $doAppenJawabanC = true;
        $doAppenJawabanD = true;
        $doAppenJawabanE = true;

        

        $result = array();

        foreach ($paragraphs as $paragraph) {
            if (strpos($dom->saveHTML($paragraph), 'KUNCI :') == true) {
                $jawaban = $dom->saveHTML($paragraph);
                $doAppenSoal = false;
            } else {
                if ($doAppenSoal) {
                    $soal = $soal . $dom->saveHTML($paragraph);
                }
            }

            if (strpos($dom->saveHTML($paragraph), 'B. ') == true) {
                $doAppenJawabanA = false;
            } else {
                if ($doAppenJawabanA && $doAppenSoal == false && strpos($dom->saveHTML($paragraph), 'KUNCI :') == false) {
                    $jawabanA = $jawabanA . $dom->saveHTML($paragraph);
                }
            }

            if (strpos($dom->saveHTML($paragraph), 'C. ') == true) {
                $doAppenJawabanB = false;
            } else {
                if ($doAppenJawabanB && $doAppenSoal == false && $doAppenJawabanA == false && strpos($dom->saveHTML($paragraph), 'KUNCI :') == false) {
                    $jawabanB = $jawabanB . $dom->saveHTML($paragraph);
                }
            }

            if (strpos($dom->saveHTML($paragraph), 'D. ') == true) {
                $doAppenJawabanC = false;
            } else {
                if ($doAppenJawabanC && $doAppenSoal == false && $doAppenJawabanA == false && $doAppenJawabanB == false && strpos($dom->saveHTML($paragraph), 'KUNCI :') == false) {
                    $jawabanC = $jawabanC . $dom->saveHTML($paragraph);
                }
            }

            if (strpos($dom->saveHTML($paragraph), 'E. ') == true) {
                $doAppenJawabanD = false;
            } else {
                if ($doAppenJawabanD && $doAppenSoal == false && $doAppenJawabanA == false && $doAppenJawabanB == false && $doAppenJawabanC == false && strpos($dom->saveHTML($paragraph), 'KUNCI :') == false) {
                    $jawabanD = $jawabanD . $dom->saveHTML($paragraph);
                }
            }

            if ($doAppenJawabanE && $doAppenSoal == false && $doAppenJawabanA == false && $doAppenJawabanB == false && $doAppenJawabanC == false && $doAppenJawabanD == false && strpos($dom->saveHTML($paragraph), 'KUNCI :') == false) {
                $jawabanE = $jawabanE . $dom->saveHTML($paragraph);
            }
        }

        $jawaban_arr = explode(": ", $jawaban);
        $jawaban = $jawaban_arr[1];
        $jawaban = $jawaban[0];
        $result['jawaban'] = $jawaban;

        $result['soal'] = $soal;
        $result['soal'] = str_replace("Â", "", $result['soal']);
        $result['soal'] = str_replace(".&nbsp;", "", $result['soal']);

        $result['jawabanA'] = $jawabanA;
        $result['jawabanA'] = str_replace("A. ", "", $result['jawabanA']);
        $result['jawabanA'] = str_replace("Â", "", $result['jawabanA']);
        $result['jawabanA'] = str_replace(".&nbsp;", "", $result['jawabanA']);

        $result['jawabanB'] = $jawabanB;
        $result['jawabanB'] = str_replace("B. ", "", $result['jawabanB']);
        $result['jawabanB'] = str_replace("Â", "", $result['jawabanB']);
        $result['jawabanB'] = str_replace(".&nbsp;", "", $result['jawabanB']);

        $result['jawabanC'] = $jawabanC;
        $result['jawabanC'] = str_replace("C. ", "", $result['jawabanC']);
        $result['jawabanC'] = str_replace("Â", "", $result['jawabanC']);
        $result['jawabanC'] = str_replace(".&nbsp;", "", $result['jawabanC']);

        $result['jawabanD'] = $jawabanD;
        $result['jawabanD'] = str_replace("D. ", "", $result['jawabanD']);
        $result['jawabanD'] = str_replace("Â", "", $result['jawabanD']);
        $result['jawabanC'] = str_replace(".&nbsp;", "", $result['jawabanC']);

        $result['jawabanE'] = $jawabanE;
        $result['jawabanE'] = str_replace("E. ", "", $result['jawabanE']);
        $result['jawabanE'] = str_replace("Â", "", $result['jawabanE']);
        $result['jawabanE'] = str_replace(".&nbsp;", "", $result['jawabanE']);

        echo json_encode($result);
    }

    public function actionAjaxparsingtabelbackup() {
        $received_str = $_POST['wordhtml'];

        $dom = new DOMDocument();
        $dom->loadHTML($received_str);
        $dom->preserveWhiteSpace = false; // important!


        $nodelist = $dom->getElementsByTagName('table');


        $the_result = array();
        $the_question = array();

        foreach ($nodelist as $tab) {
            // if (($tab->nodeName == 'table')  && ($tab->getElementsByTagName('tr').length > 1) ){
            if ($tab->nodeName == 'table') {

                $lewati = 0;
                foreach ($tab->childNodes as $pp) {
                    if ($pp->hasChildNodes()) {
                        foreach ($pp->childNodes as $ppp) {
                            if ($ppp->nodeName == 'tr') {
                                $lewati++;
                            }
                        }
                    }
                }



                if ($lewati > 1) {
                    continue;
                }

                $soal = "";
                $jawaban = "";
                $jawabanA = "";
                $jawabanB = "";
                $jawabanC = "";
                $jawabanD = "";
                $jawabanE = "";
                $doAppenSoal = true;
                $doAppenJawabanA = true;
                $doAppenJawabanB = true;
                $doAppenJawabanC = true;
                $doAppenJawabanD = true;
                $doAppenJawabanE = true;

                // $paragraphs = str_replace("<table", "<p><table",  $paragraphs);	
                // $paragraphs = str_replace("</table>", "</table></p>",  $paragraphs);	
                foreach ($tab->childNodes as $para) {
                    if ($para->hasChildNodes()) {
                        foreach ($para->childNodes as $paragraph1) {
                            if ($paragraph1->hasChildNodes()) {
                                foreach ($paragraph1->childNodes as $paragraph2) {

                                    if ($paragraph2->hasChildNodes()) {
                                        foreach ($paragraph2->childNodes as $paragraph) {
                                            if (strpos($dom->saveHTML($paragraph), 'KUNCI :') == true) {
                                                // echo "-------SOALNYA------------";
                                                $jawaban = $dom->saveHTML($paragraph);
                                                $doAppenSoal = false;
                                            } else {
                                                if ($doAppenSoal) {
                                                    $soal = $soal . $dom->saveHTML($paragraph);
                                                }
                                            }

                                            if (strpos($dom->saveHTML($paragraph), 'B.') == true) {
                                                // echo "-------SOALNYA------------";
                                                $doAppenJawabanA = false;
                                            } else {
                                                if ($doAppenJawabanA && $doAppenSoal == false && strpos($dom->saveHTML($paragraph), 'KUNCI :') == false) {
                                                    $jawabanA = $jawabanA . $dom->saveHTML($paragraph);
                                                }
                                            }

                                            if (strpos($dom->saveHTML($paragraph), 'C.') == true) {
                                                // echo "-------SOALNYA------------";
                                                $doAppenJawabanB = false;
                                            } else {
                                                if ($doAppenJawabanB && $doAppenSoal == false && $doAppenJawabanA == false && strpos($dom->saveHTML($paragraph), 'KUNCI :') == false) {
                                                    $jawabanB = $jawabanB . $dom->saveHTML($paragraph);
                                                }
                                            }

                                            if (strpos($dom->saveHTML($paragraph), 'D.') == true) {
                                                // echo "-------SOALNYA------------";
                                                $doAppenJawabanC = false;
                                            } else {
                                                if ($doAppenJawabanC && $doAppenSoal == false && $doAppenJawabanA == false && $doAppenJawabanB == false && strpos($dom->saveHTML($paragraph), 'KUNCI :') == false) {
                                                    $jawabanC = $jawabanC . $dom->saveHTML($paragraph);
                                                }
                                            }

                                            if (strpos($dom->saveHTML($paragraph), 'E.') == true) {
                                                // echo "-------SOALNYA------------";
                                                $doAppenJawabanD = false;
                                            } else {
                                                if ($doAppenJawabanD && $doAppenSoal == false && $doAppenJawabanA == false && $doAppenJawabanB == false && $doAppenJawabanC == false && strpos($dom->saveHTML($paragraph), 'KUNCI :') == false) {
                                                    $jawabanD = $jawabanD . $dom->saveHTML($paragraph);
                                                }
                                            }

                                            if ($doAppenJawabanE && $doAppenSoal == false && $doAppenJawabanA == false && $doAppenJawabanB == false && $doAppenJawabanC == false && $doAppenJawabanD == false && strpos($dom->saveHTML($paragraph), 'KUNCI :') == false) {
                                                $jawabanE = $jawabanE . $dom->saveHTML($paragraph);
                                            }

                                            // echo $dom->saveHTML($paragraph)."+++++++";
                                            // echo $jawaban."</br>";
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                // echo "#########";

                $jawaban = str_replace("<span lang=\"en-US\">", "", $jawaban);
                $jawaban = str_replace("</span>", "", $jawaban);
                $jawaban = strip_tags($jawaban);
                $jawaban_arr = explode(": ", $jawaban);
                $jawaban = $jawaban_arr[1];
                $jawaban = $jawaban[0];
                $result['jawaban'] = $jawaban;

                $soal = utf8_decode($soal);
                $result['soal'] = $soal;
                $result['soal'] = str_replace("Â", "", $result['soal']);
                $result['soal'] = str_replace("<table", "<table border=\"1\"", $result['soal']);
                // $result['soal'] = substr($result['soal'], 2);

                $jawabanA = utf8_decode($jawabanA);
                $result['jawabanA'] = $jawabanA;
                $result['jawabanA'] = str_replace("A.", "", $result['jawabanA']);
                $result['jawabanA'] = str_replace("Â", "", $result['jawabanA']);
                $result['jawabanA'] = str_replace("<table", "<table border=\"1\"", $result['jawabanA']);


                $jawabanB = utf8_decode($jawabanB);
                $result['jawabanB'] = $jawabanB;
                $result['jawabanB'] = str_replace("B.", "", $result['jawabanB']);
                $result['jawabanB'] = str_replace("Â", "", $result['jawabanB']);
                $result['jawabanB'] = str_replace("<table", "<table border=\"1\"", $result['jawabanB']);

                $jawabanC = utf8_decode($jawabanC);
                $result['jawabanC'] = $jawabanC;
                $result['jawabanC'] = str_replace("C.", "", $result['jawabanC']);
                $result['jawabanC'] = str_replace("Â", "", $result['jawabanC']);
                $result['jawabanC'] = str_replace("<table", "<table border=\"1\"", $result['jawabanC']);

                $jawabanD = utf8_decode($jawabanD);
                $result['jawabanD'] = $jawabanD;
                $result['jawabanD'] = str_replace("D.", "", $result['jawabanD']);
                $result['jawabanD'] = str_replace("Â", "", $result['jawabanD']);
                $result['jawabanD'] = str_replace("<table", "<table border=\"1\"", $result['jawabanD']);

                $jawabanE = utf8_decode($jawabanE);
                $result['jawabanE'] = $jawabanE;
                $result['jawabanE'] = str_replace("E.", "", $result['jawabanE']);
                $result['jawabanE'] = str_replace("Â", "", $result['jawabanE']);
                $result['jawabanE'] = str_replace("<table", "<table border=\"1\"", $result['jawabanE']);

                $the_result[] = $result;
            }
        }




        echo json_encode($the_result);
    }

    function showDOMNode(DOMNode $domNode) {
        foreach ($domNode->childNodes as $node) {

            echo $node->nodeName . ':' . $node->nodeValue;
            if ($node->hasChildNodes()) {
                showDOMNode($node);
            }
        }
    }

    public function actionAjaxparsingtabel() {
        $_POST['wordhtml'] = str_replace("&nbsp;", "", $_POST['wordhtml']);
        $received_str = $_POST['wordhtml'];
        $received_str = trim(preg_replace("/(\[.+?\])((?:[^\[])+)/", "$1" . PHP_EOL . "$2" . PHP_EOL, str_replace(PHP_EOL, "", $received_str)));
        // $received_str = preg_replace('/\s+/', '', $received_str);

        $dom = new DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML($received_str);
        libxml_clear_errors();
        $dom->preserveWhiteSpace = false; // important!



        $nodelist = $dom->getElementsByTagName('table');


        $the_result = array();
        $the_question = array();

        foreach ($nodelist as $tab) {
            // if (($tab->nodeName == 'table')  && ($tab->getElementsByTagName('tr').length > 1) ){
            if ($tab->nodeName == 'table') {


                $liNumber = 0;
                $lewati = 0;
                foreach ($tab->childNodes as $pp) {
                    if ($pp->hasChildNodes()) {
                        foreach ($pp->childNodes as $ppp) {
                            if ($ppp->nodeName == 'tr') {
                                $lewati++;
                            }
                        }
                    }
                }



                if ($lewati > 1) {
                    continue;
                }

                // $the_result[] = $dom->saveHTML($tab);


                $soal = "";
                $jawaban = "";
                $jawabanA = "";
                $jawabanB = "";
                $jawabanC = "";
                $jawabanD = "";
                $jawabanE = "";
                $doAppenSoal = true;
                $doAppenJawabanA = true;
                $doAppenJawabanB = true;
                $doAppenJawabanC = true;
                $doAppenJawabanD = true;
                $doAppenJawabanE = true;

                // $paragraphs = str_replace("<table", "<p><table",  $paragraphs);	
                // $paragraphs = str_replace("</table>", "</table></p>",  $paragraphs);	
                foreach ($tab->childNodes as $para) {
                    if ($para->hasChildNodes()) {
                        foreach ($para->childNodes as $paragraph1) {
                            if ($paragraph1->hasChildNodes()) {
                                foreach ($paragraph1->childNodes as $paragraph2) {

                                    if ($paragraph2->hasChildNodes()) {
                                        foreach ($paragraph2->childNodes as $paragraph) {
                                            if ($paragraph->nodeName == "ol" and $paragraph->hasChildNodes()) {
                                                foreach ($paragraph->childNodes as $lis) {
                                                    if ($lis->hasChildNodes() and $lis->nodeName == 'li') {
                                                        $liNumber++;
                                                        foreach ($lis->childNodes as $li) {
                                                            switch ($liNumber) {
                                                                case 1 :
                                                                    $jawabanA = $jawabanA . $dom->saveHTML($li);
                                                                    break;

                                                                case 2 :
                                                                    $jawabanB = $jawabanB . $dom->saveHTML($li);
                                                                    break;


                                                                case 3 :
                                                                    $jawabanC = $jawabanC . $dom->saveHTML($li);
                                                                    break;

                                                                case 4 :
                                                                    $jawabanD = $jawabanD . $dom->saveHTML($li);
                                                                    break;


                                                                case 5 :
                                                                    $jawabanE = $jawabanE . $dom->saveHTML($li);
                                                                    break;

                                                                default:
                                                                    # donotihng
                                                                    break;
                                                            }
                                                        }
                                                    }
                                                }
                                            } else {

                                                if ($liNumber != 0) {
                                                    switch ($liNumber) {
                                                        case 1 :
                                                            $jawabanA = $jawabanA . $dom->saveHTML($paragraph);
                                                            break;

                                                        case 2 :
                                                            $jawabanB = $jawabanB . $dom->saveHTML($paragraph);
                                                            break;


                                                        case 3 :
                                                            $jawabanC = $jawabanC . $dom->saveHTML($paragraph);
                                                            break;

                                                        case 4 :
                                                            $jawabanD = $jawabanD . $dom->saveHTML($paragraph);
                                                            break;


                                                        case 5 :
                                                            $jawabanE = $jawabanE . $dom->saveHTML($paragraph);
                                                            break;

                                                        default:
                                                            # donotihng
                                                            break;
                                                    }
                                                } else {

                                                    if (strpos($dom->saveHTML($paragraph), 'KUNCI :') == true) {
                                                        // echo "-------SOALNYA------------";
                                                        $jawaban = $dom->saveHTML($paragraph);
                                                        $doAppenSoal = false;
                                                    } else {
                                                        if ($doAppenSoal) {
                                                            $soal = $soal . $dom->saveHTML($paragraph);
                                                        }
                                                    }

                                                    if (strpos($dom->saveHTML($paragraph), 'B.') == true) {
                                                        // echo "-------SOALNYA------------";
                                                        $doAppenJawabanA = false;
                                                    } else {
                                                        if ($doAppenJawabanA && $doAppenSoal == false && strpos($dom->saveHTML($paragraph), 'KUNCI :') == false) {
                                                            $jawabanA = $jawabanA . $dom->saveHTML($paragraph);
                                                        }
                                                    }

                                                    if (strpos($dom->saveHTML($paragraph), 'C.') == true) {
                                                        // echo "-------SOALNYA------------";
                                                        $doAppenJawabanB = false;
                                                    } else {
                                                        if ($doAppenJawabanB && $doAppenSoal == false && $doAppenJawabanA == false && strpos($dom->saveHTML($paragraph), 'KUNCI :') == false) {
                                                            $jawabanB = $jawabanB . $dom->saveHTML($paragraph);
                                                        }
                                                    }

                                                    if (strpos($dom->saveHTML($paragraph), 'D.') == true) {
                                                        // echo "-------SOALNYA------------";
                                                        $doAppenJawabanC = false;
                                                    } else {
                                                        if ($doAppenJawabanC && $doAppenSoal == false && $doAppenJawabanA == false && $doAppenJawabanB == false && strpos($dom->saveHTML($paragraph), 'KUNCI :') == false) {
                                                            $jawabanC = $jawabanC . $dom->saveHTML($paragraph);
                                                        }
                                                    }

                                                    if (strpos($dom->saveHTML($paragraph), 'E.') == true) {
                                                        // echo "-------SOALNYA------------";
                                                        $doAppenJawabanD = false;
                                                    } else {
                                                        if ($doAppenJawabanD && $doAppenSoal == false && $doAppenJawabanA == false && $doAppenJawabanB == false && $doAppenJawabanC == false && strpos($dom->saveHTML($paragraph), 'KUNCI :') == false) {
                                                            $jawabanD = $jawabanD . $dom->saveHTML($paragraph);
                                                        }
                                                    }

                                                    if ($doAppenJawabanE && $doAppenSoal == false && $doAppenJawabanA == false && $doAppenJawabanB == false && $doAppenJawabanC == false && $doAppenJawabanD == false && strpos($dom->saveHTML($paragraph), 'KUNCI :') == false) {
                                                        $jawabanE = $jawabanE . $dom->saveHTML($paragraph);
                                                    }
                                                }
                                            }
                                            // echo $dom->saveHTML($paragraph)."+++++++";
                                            // echo $jawaban."</br>";
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                // echo "#########";

                $jawaban = str_replace("<span lang=\"en-US\">", "", $jawaban);
                $jawaban = str_replace("</span>", "", $jawaban);
                $jawaban = strip_tags($jawaban);
                $jawaban_arr = explode(": ", $jawaban);
                $jawaban = $jawaban_arr[1];
                $jawaban = $jawaban[0];
                $result['jawaban'] = $jawaban;

                // $soal = utf8_decode($soal);
                $soal =  iconv("UTF-8", "ISO-8859-1//TRANSLIT", $soal);

                $result['soal'] = $soal;
                $result['soal'] = str_replace("Â", "", $result['soal']);
                $result['soal'] = str_replace("<table", "<table border=\"1\"", $result['soal']);
                $result['soal'] = str_replace("<td", "<td align=\"center\"", $result['soal']);
                // $result['soal'] = substr($result['soal'], 2);
                // $jawabanA = utf8_decode($jawabanA);	

                $jawabanA =  iconv("UTF-8", "ISO-8859-1//TRANSLIT", $jawabanA);
                $result['jawabanA'] = $jawabanA;
                $result['jawabanA'] = str_replace("A.", "", $result['jawabanA']);
                $result['jawabanA'] = str_replace("Â", "", $result['jawabanA']);
                $result['jawabanA'] = str_replace("<table", "<table border=\"1\"", $result['jawabanA']);
                $result['jawabanA'] = str_replace("<td", "<td align=\"center\"", $result['jawabanA']);


                // $jawabanB = utf8_decode($jawabanB);	
                $jawabanB =  iconv("UTF-8", "ISO-8859-1//TRANSLIT", $jawabanB);
                $result['jawabanB'] = $jawabanB;
                $result['jawabanB'] = str_replace("B.", "", $result['jawabanB']);
                $result['jawabanB'] = str_replace("Â", "", $result['jawabanB']);
                $result['jawabanB'] = str_replace("<table", "<table border=\"1\"", $result['jawabanB']);
                $result['jawabanB'] = str_replace("<td", "<td align=\"center\"", $result['jawabanB']);

                // $jawabanC = utf8_decode($jawabanC);	
                $jawabanC =  iconv("UTF-8", "ISO-8859-1//TRANSLIT", $jawabanC);
                $result['jawabanC'] = $jawabanC;
                $result['jawabanC'] = str_replace("C.", "", $result['jawabanC']);
                $result['jawabanC'] = str_replace("Â", "", $result['jawabanC']);
                $result['jawabanC'] = str_replace("<table", "<table border=\"1\"", $result['jawabanC']);
                $result['jawabanC'] = str_replace("<td", "<td align=\"center\"", $result['jawabanC']);

                // $jawabanD = utf8_decode($jawabanD);	
                $jawabanD =  iconv("UTF-8", "ISO-8859-1//TRANSLIT", $jawabanD);
                $result['jawabanD'] = $jawabanD;
                $result['jawabanD'] = str_replace("D.", "", $result['jawabanD']);
                $result['jawabanD'] = str_replace("Â", "", $result['jawabanD']);
                $result['jawabanD'] = str_replace("<table", "<table border=\"1\"", $result['jawabanD']);
                $result['jawabanD'] = str_replace("<td", "<td align=\"center\"", $result['jawabanD']);

                // $jawabanE = utf8_decode($jawabanE);
                $jawabanE =  iconv("UTF-8", "ISO-8859-1//TRANSLIT", $jawabanE);	
                $result['jawabanE'] = $jawabanE;
                $result['jawabanE'] = str_replace("E.", "", $result['jawabanE']);
                $result['jawabanE'] = str_replace("Â", "", $result['jawabanE']);
                $result['jawabanE'] = str_replace("<table", "<table border=\"1\"", $result['jawabanE']);
                $result['jawabanE'] = str_replace("<td", "<td align=\"center\"", $result['jawabanE']);

                $the_result[] = $result;
            }
        }




        echo json_encode($the_result);
    }

    public function xml_to_array($root) {
        $result = array();

        if ($root->hasAttributes()) {
            $attrs = $root->attributes;
            foreach ($attrs as $attr) {
                $result['@attributes'][$attr->name] = $attr->value;
            }
        }

        if ($root->hasChildNodes()) {
            $children = $root->childNodes;
            if ($children->length == 1) {
                $child = $children->item(0);
                if ($child->nodeType == XML_TEXT_NODE) {
                    $result['_value'] = $child->nodeValue;
                    return count($result) == 1 ? $result['_value'] : $result;
                }
            }
            $groups = array();
            foreach ($children as $child) {
                if (!isset($result[$child->nodeName])) {
                    $result[$child->nodeName] = $this->xml_to_array($child);
                } else {
                    if (!isset($groups[$child->nodeName])) {
                        $result[$child->nodeName] = array($result[$child->nodeName]);
                        $groups[$child->nodeName] = 1;
                    }
                    $result[$child->nodeName][] = $this->xml_to_array($child);
                }
            }
        }

        return $result;
    }

}
