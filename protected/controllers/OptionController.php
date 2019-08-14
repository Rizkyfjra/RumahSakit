<?php

class OptionController extends Controller
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';

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
            /*array('allow',  // allow all users to perform 'index' and 'view' actions
                'actions'=>array('index','view'),
                'users'=>array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions'=>array('create','update'),
                'users'=>array('@'),
            ),*/
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('index', 'atur', 'aturLocal', 'pull', 'pullquiz'),
                'expression' => 'Yii::app()->user->YiiAdmin',
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('aturLocal'),
                'expression' => 'Yii::app()->user->YiiTeacher',
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
    public function actionView($id)
    {
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model = new Option;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Option'])) {
            $model->attributes = $_POST['Option'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    public function actionAturLocal()
    {
        $model = new Option;

        if (isset($_POST['yt0'])) {
            // echo "nasdf";
            foreach ($_POST as $key => $value) {
                if ($key == 'semester') {
                    // Yii::app()->session->set('semester', $value);
                    Yii::app()->session['semester'] = $value;
                }

                if ($key == 'tahun_ajaran') {
                    // Yii::app()->session->set('tahun_ajaran', $value);
                    Yii::app()->session['tahun_ajaran'] = $value;
                }

                if ($key == 'titimangsa') {
                    // Yii::app()->session->set('titimangsa', $value);
                    Yii::app()->session['titimangsa'] = $value;
                }

                if ($key == 'walikelas') {
                    // Yii::app()->session->set('titimangsa', $value);
                    Yii::app()->session['walikelas'] = $value;
                }

                if ($key == 'nipwali') {
                    // Yii::app()->session->set('titimangsa', $value);
                    Yii::app()->session['nipwali'] = $value;
                }
            }


            $this->render('v2/atur_local', array(
                'model' => $model,
            ));
        } else {
            $this->render('v2/atur_local', array(
                'model' => $model,
            ));
        }


    }

    public function actionAtur()
    {
        $model = new Option;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['yt0'])) {
            //echo "<pre>";
            //print_r($_POST);
            $total_prosentase = 0;
            foreach ($_POST as $key => $value) {
                //$model=new Option;
                $nama_sekolah = Option::model()->findAll(array('condition' => 'key_config LIKE "%school_name%"'));
                $kepala_sekolah = Option::model()->findAll(array('condition' => 'key_config LIKE "%kepsek_id%"'));
                $alamat_sekolah = Option::model()->findAll(array('condition' => 'key_config LIKE "%school_address%"'));
                $kurikulum_sekolah = Option::model()->findAll(array('condition' => 'key_config LIKE "%kurikulum%"'));
                $ulangan = Option::model()->findAll(array('condition' => 'key_config LIKE "%fitur_ulangan%"'));
                $tugas = Option::model()->findAll(array('condition' => 'key_config LIKE "%fitur_tugas%"'));
                $materi = Option::model()->findAll(array('condition' => 'key_config LIKE "%fitur_materi%"'));
                $rekap = Option::model()->findAll(array('condition' => 'key_config LIKE "%fitur_rekap%"'));
                $semester = Option::model()->findAll(array('condition' => 'key_config LIKE "%semester%"'));
                $tahun_ajaran = Option::model()->findAll(array('condition' => 'key_config LIKE "%tahun_ajaran%"'));
                $nilai_harian = Option::model()->findAll(array('condition' => 'key_config LIKE "%nilai_harian%"'));
                $nilai_uts = Option::model()->findAll(array('condition' => 'key_config LIKE "%nilai_uts%"'));
                $nilai_uas = Option::model()->findAll(array('condition' => 'key_config LIKE "%nilai_uas%"'));
                $kd_sikap = Option::model()->findAll(array('condition' => 'key_config LIKE "%kd_sikap%"'));
                $npsn = Option::model()->findAll(array('condition' => 'key_config LIKE "%npsn%"'));
                $nss = Option::model()->findAll(array('condition' => 'key_config LIKE "%nss%"'));
                $kelurahan = Option::model()->findAll(array('condition' => 'key_config LIKE "%kelurahan%"'));
                $kecamatan = Option::model()->findAll(array('condition' => 'key_config LIKE "%kecamatan%"'));
                $kota_kabupaten = Option::model()->findAll(array('condition' => 'key_config LIKE "%kota_kabupaten%"'));
                $provinsi = Option::model()->findAll(array('condition' => 'key_config LIKE "%provinsi%"'));
                $website = Option::model()->findAll(array('condition' => 'key_config LIKE "%website%"'));
                $email = Option::model()->findAll(array('condition' => 'key_config LIKE "%email%"'));
                $server = Option::model()->findAll(array('condition' => 'key_config LIKE "%server%"'));


                if ($key == 'server') {
                    if (empty($server)) {
                        $server = new Option;
                        $server->key_config = $key;
                        $server->value = $value;
                        $server->save();
                    } else {
                        $updateserver = Option::model()->findByPk($server[0]->id);
                        $updateserver->key_config = $key;
                        $updateserver->value = $value;
                        $updateserver->save();
                    }
                }

                if ($key == 'email') {
                    if (empty($email)) {
                        $email = new Option;
                        $email->key_config = $key;
                        $email->value = $value;
                        $email->save();
                    } else {
                        $updateemail = Option::model()->findByPk($email[0]->id);
                        $updateemail->key_config = $key;
                        $updateemail->value = $value;
                        $updateemail->save();
                    }
                }

                if ($key == 'website') {
                    if (empty($website)) {
                        $website = new Option;
                        $website->key_config = $key;
                        $website->value = $value;
                        $website->save();
                    } else {
                        $updatewebsite = Option::model()->findByPk($website[0]->id);
                        $updatewebsite->key_config = $key;
                        $updatewebsite->value = $value;
                        $updatewebsite->save();
                    }
                }

                if ($key == 'provinsi') {
                    if (empty($provinsi)) {
                        $provinsi = new Option;
                        $provinsi->key_config = $key;
                        $provinsi->value = $value;
                        $provinsi->save();
                    } else {
                        $updateprovinsi = Option::model()->findByPk($provinsi[0]->id);
                        $updateprovinsi->key_config = $key;
                        $updateprovinsi->value = $value;
                        $updateprovinsi->save();
                    }
                }


                if ($key == 'kota_kabupaten') {
                    if (empty($kota_kabupaten)) {
                        $kota_kabupaten = new Option;
                        $kota_kabupaten->key_config = $key;
                        $kota_kabupaten->value = $value;
                        $kota_kabupaten->save();
                    } else {
                        $updatekota_kabupaten = Option::model()->findByPk($kota_kabupaten[0]->id);
                        $updatekota_kabupaten->key_config = $key;
                        $updatekota_kabupaten->value = $value;
                        $updatekota_kabupaten->save();
                    }
                }

                if ($key == 'kecamatan') {
                    if (empty($kecamatan)) {
                        $kecamatan = new Option;
                        $kecamatan->key_config = $key;
                        $kecamatan->value = $value;
                        $kecamatan->save();
                    } else {
                        $updatekecamatan = Option::model()->findByPk($kecamatan[0]->id);
                        $updatekecamatan->key_config = $key;
                        $updatekecamatan->value = $value;
                        $updatekecamatan->save();
                    }
                }

                if ($key == 'kelurahan') {
                    if (empty($kelurahan)) {
                        $kelurahan = new Option;
                        $kelurahan->key_config = $key;
                        $kelurahan->value = $value;
                        $kelurahan->save();
                    } else {
                        $updatekelurahan = Option::model()->findByPk($kelurahan[0]->id);
                        $updatekelurahan->key_config = $key;
                        $updatekelurahan->value = $value;
                        $updatekelurahan->save();
                    }
                }

                if ($key == 'npsn') {
                    if (empty($npsn)) {
                        $npsn = new Option;
                        $npsn->key_config = $key;
                        $npsn->value = $value;
                        $npsn->save();
                    } else {
                        $updatenpsn = Option::model()->findByPk($npsn[0]->id);
                        $updatenpsn->key_config = $key;
                        $updatenpsn->value = $value;
                        $updatenpsn->save();
                    }
                }

                 if ($key == 'nss') {
                    if (empty($nss)) {
                        $nss = new Option;
                        $nss->key_config = $key;
                        $nss->value = $value;
                        $nss->save();
                    } else {
                        $updatenss = Option::model()->findByPk($nss[0]->id);
                        $updatenss->key_config = $key;
                        $updatenss->value = $value;
                        $updatenss->save();
                    }
                }


                if ($key == 'kd_sikap') {
                    // echo "<pre>";
                    // 	print_r($value);
                    // echo "</pre>";
                    $index_kd = 0;
                    $array_kd = array();
                    $json_kd = "";

                    foreach ($value['name'] as $key_kd => $value_kd) {
                        $array_kd[$index_kd]['name'] = $value_kd;
                        $array_kd[$index_kd]['indikator'][] = $value['indikator'][$index_kd];
                        $index_kd++;
                    }

                    if (!empty($array_kd)) {
                        $json_kd = json_encode($array_kd);
                    }


                    if (empty($kd_sikap)) {
                        $kd_sikap = new Option;
                        $kd_sikap->key_config = $key;
                        $kd_sikap->value = $json_kd;
                        $kd_sikap->save();
                    } else {
                        $updateKd_sikap = Option::model()->findByPk($kd_sikap[0]->id);
                        $updateKd_sikap->key_config = $key;
                        $updateKd_sikap->value = $json_kd;
                        $updateKd_sikap->save();
                    }
                }


                 if ($key == 'tahun_url') {
                    // echo "<pre>";
                    //  print_r($value);
                    // echo "</pre>";
                    $index_kd = 0;
                    $array_tahun = array();
                    $json_kd = "";

                    foreach ($value['tahun'] as $key_kd => $value_kd) {
                        $array_tahun[$index_kd]['tahun'] = $value_kd;
                        $array_tahun[$index_kd]['url'][] = $value['url'][$index_kd];
                        $index_kd++;
                    }

                    if (!empty($array_tahun)) {
                        $json_kd = json_encode($array_tahun);
                    }


                    if (empty($tahun_url)) {
                        $tahun_url = new Option;
                        $tahun_url->key_config = $key;
                        $tahun_url->value = $json_kd;
                        $tahun_url->save();
                    } else {
                        $updatetahun_url = Option::model()->findByPk($tahun_url[0]->id);
                        $updatetahun_url->key_config = $key;
                        $updatetahun_url->value = $json_kd;
                        $updatetahun_url->save();
                    }
                }

                if ($key == 'school_name') {
                    if (empty($nama_sekolah)) {
                        $school_name = new Option;
                        $school_name->key_config = $key;
                        $school_name->value = $value;
                        $school_name->save();
                    } else {
                        $updateSekolah = Option::model()->findByPk($nama_sekolah[0]->id);
                        $updateSekolah->key_config = $key;
                        $updateSekolah->value = $value;
                        $updateSekolah->save();
                    }
                }

                if ($key == 'kepsek_id') {
                    if (empty($kepala_sekolah)) {
                        $kepala_sekolah = new Option;
                        $kepala_sekolah->key_config = $key;
                        $kepala_sekolah->value = $value;
                        $kepala_sekolah->save();
                    } else {
                        $updateKepalaSekolah = Option::model()->findByPk($kepala_sekolah[0]->id);
                        $updateKepalaSekolah->key_config = $key;
                        $updateKepalaSekolah->value = $value;
                        $updateKepalaSekolah->save();
                    }
                }

                if ($key == 'school_address') {
                    if (empty($alamat_sekolah)) {
                        $alamat_sekolah = new Option;
                        $alamat_sekolah->key_config = $key;
                        $alamat_sekolah->value = $value;
                        $alamat_sekolah->save();
                    } else {
                        $updateAlamat = Option::model()->findByPk($alamat_sekolah[0]->id);
                        $updateAlamat->key_config = $key;
                        $updateAlamat->value = $value;
                        $updateAlamat->save();
                    }
                }

                if ($key == 'kurikulum') {
                    if (empty($kurikulum_sekolah)) {
                        $kurikulum_sekolah = new Option;
                        $kurikulum_sekolah->key_config = $key;
                        $kurikulum_sekolah->value = $value;
                        $kurikulum_sekolah->save();
                    } else {
                        $updateKurikulum = Option::model()->findByPk($kurikulum_sekolah[0]->id);
                        $updateKurikulum->key_config = $key;
                        $updateKurikulum->value = $value;
                        $updateKurikulum->save();
                    }
                }


                if ($key == 'fitur_ulangan') {
                    if (empty($ulangan)) {
                        $ulangan = new Option;
                        $ulangan->key_config = $key;
                        $ulangan->value = $value;
                        $ulangan->save();
                    } else {
                        $updateUlangan = Option::model()->findByPk($ulangan[0]->id);
                        $updateUlangan->key_config = $key;
                        $updateUlangan->value = $value;
                        $updateUlangan->save();
                    }
                }


                if ($key == 'fitur_tugas') {
                    if (empty($tugas)) {
                        $tugas = new Option;
                        $tugas->key_config = $key;
                        $tugas->value = $value;
                        $tugas->save();
                    } else {
                        $updateTugas = Option::model()->findByPk($tugas[0]->id);
                        $updateTugas->key_config = $key;
                        $updateTugas->value = $value;
                        $updateTugas->save();
                    }
                }

                if ($key == 'fitur_materi') {
                    if (empty($materi)) {
                        $materi = new Option;
                        $materi->key_config = $key;
                        $materi->value = $value;
                        $materi->save();
                    } else {
                        $updateMateri = Option::model()->findByPk($materi[0]->id);
                        $updateMateri->key_config = $key;
                        $updateMateri->value = $value;
                        $updateMateri->save();
                    }
                }

                if ($key == 'fitur_rekap') {
                    if (empty($rekap)) {
                        $rekap = new Option;
                        $rekap->key_config = $key;
                        $rekap->value = $value;
                        $rekap->save();
                    } else {
                        $updateRekap = Option::model()->findByPk($rekap[0]->id);
                        $updateRekap->key_config = $key;
                        $updateRekap->value = $value;
                        $updateRekap->save();
                    }

                }

                if ($key == 'semester') {
                    if (empty($semester)) {
                        $semester = new Option;
                        $semester->key_config = $key;
                        $semester->value = $value;
                        $semester->save();
                        Yii::app()->session['semester'] = $value;
                    } else {
                        $updatesemester = Option::model()->findByPk($semester[0]->id);
                        $updatesemester->key_config = $key;
                        $updatesemester->value = $value;
                        $updatesemester->save();
                        Yii::app()->session['semester'] = $value;
                    }
                }

                if ($key == 'tahun_ajaran') {
                    if (empty($tahun_ajaran)) {
                        $tahun_ajaran = new Option;
                        $tahun_ajaran->key_config = $key;
                        $tahun_ajaran->value = $value;
                        if ($tahun_ajaran->save()) {
                            //$this->redirect(array('/clases/addExcel'));
                        }
                        Yii::app()->session['tahun_ajaran'] = $value;
                    } else {
                        $updatetahun_ajaran = Option::model()->findByPk($tahun_ajaran[0]->id);
                        $updatetahun_ajaran->key_config = $key;
                        $updatetahun_ajaran->value = $value;
                        $updatetahun_ajaran->save();
                        Yii::app()->session['tahun_ajaran'] = $value;
                    }
                }

                if ($key == 'nilai_harian') {
                    $total_prosentase = $total_prosentase + $value;
                    if (empty($nilai_harian)) {
                        $nilai_harian = new Option;
                        $nilai_harian->key_config = $key;
                        $nilai_harian->value = $value;
                        if ($nilai_harian->save()) {
                            //$this->redirect(array('/clases/addExcel'));
                        }
                    } else {
                        $updatenilai_harian = Option::model()->findByPk($nilai_harian[0]->id);
                        $updatenilai_harian->key_config = $key;
                        $updatenilai_harian->value = $value;
                        $updatenilai_harian->save();
                    }
                }

                if ($key == 'nilai_uts') {
                    $total_prosentase = $total_prosentase + $value;
                    if (empty($nilai_uts)) {
                        $nilai_uts = new Option;
                        $nilai_uts->key_config = $key;
                        $nilai_uts->value = $value;
                        if ($nilai_uts->save()) {
                            //$this->redirect(array('/clases/addExcel'));
                        }
                    } else {
                        $updatenilai_uts = Option::model()->findByPk($nilai_uts[0]->id);
                        $updatenilai_uts->key_config = $key;
                        $updatenilai_uts->value = $value;
                        $updatenilai_uts->save();
                    }
                }

                if ($key == 'nilai_uas') {
                    $total_prosentase = $total_prosentase + $value;
                    if (empty($nilai_uas)) {
                        $nilai_uas = new Option;
                        $nilai_uas->key_config = $key;
                        $nilai_uas->value = $value;
                        if ($nilai_uas->save()) {
                            $this->redirect(array('/clases/addExcel'));
                        }
                    } else {
                        $updatenilai_uas = Option::model()->findByPk($nilai_uas[0]->id);
                        $updatenilai_uas->key_config = $key;
                        $updatenilai_uas->value = $value;
                        $updatenilai_uas->save();

                    }
                }

            }
            if ($total_prosentase > 100) {
                Yii::app()->user->setFlash('error', 'Total Prosentase Nilai Tidak Boleh Lebih Dari 100% !');
            } else {
                Yii::app()->user->setFlash('success', 'Pengaturan Berhasil Disimpan!');
            }
            //echo "</pre>";

        }

        $this->render('v2/atur', array(
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
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Option'])) {
            $model->attributes = $_POST['Option'];
            $model->sync_status = "2";
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('update', array(
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
        $dataProvider = new CActiveDataProvider('Option', array(
            'pagination' => false,
        ));
        $this->render('v2/list', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $model = new Option('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Option']))
            $model->attributes = $_GET['Option'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Option the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = Option::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Option $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'option-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    /**
     * Lists all models.
     */
    public function actionPull()
    {
        $pull = shell_exec("git pull origin pinisiv2 2>&1");
        echo $pull;
        Yii::app()->user->setFlash('success', '' . $pull);
        $this->redirect(array('index'));
    }

    /**
     * Lists all models.
     */
    public function actionPullquiz()
    {
//        $prefix = Yii::app()->params['tablePrefix'];
//        $dataProvider = new CActiveDataProvider('Lesson', array(
//            'criteria' => array(
//                'order' => 'c.class_id DESC',
//                'select' => 't.*',
//                'join' => 'JOIN ' . $prefix . 'class_detail as c ON c.id = t.class_id',
//                'group' => 'c.class_id, t.list_id'
//            ),
//            'pagination' => array('pageSize' => 10)
//        ));
//
//        $this->render('v2/lessonlist', array(
//            'dataProvider' => $dataProvider,
//        ));
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

        $term = 't.quiz_type in (4,5,6) AND t.trash is null';
        $term = $term . " AND t.semester = " . $optSemester . " AND t.year = " . $optTahunAjaran;

        $prefix = Yii::app()->params['tablePrefix'];
        $dataProvider = new CActiveDataProvider('Quiz', array(
            'criteria' => array(
                'order' => 'id DESC',
                'select' => array('t.*'),
                'join' => 'JOIN ' . $prefix . 'lesson AS l ON l.id = t.lesson_id JOIN ' . $prefix . 'class_detail AS c ON l.class_id = c.id',
                'condition' => $term
            ),
            'pagination' => array('pageSize' => 5)
        ));


        $lesson_list = Lesson::model()->findAll(array(
                'order' => 'c.class_id DESC',
                'select' => 't.*',
                'join' => 'JOIN ' . $prefix . 'class_detail as c ON c.id = t.class_id',
                'group' => 't.list_id, c.class_id'
            )
        );

        Yii::app()->session->remove('returnURL');
        $this->render('v2/lessonlist', array(
            'dataProvider' => $dataProvider,
            'lessonlist' => $lesson_list,
            'optSemester' => $optSemester,
            'optTahunAjaran' => $optTahunAjaran,
        ));
    }
}
