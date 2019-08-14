<?php

class UserController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column1';

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
        return array(
            array('allow', // allow all users to perform 'index' and 'view' actions
                'actions' => array('suggest'),
                'users' => array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('view', 'update', 'gantiPassword', 'updateinfo'),
                'users' => array('@'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('Updatesolution','SuggestWali', 'index', 'admin', 'delete', 'create', 'bulk', 'filter', 'downloadUser', 'hapus','hapusAll', 'downloadFile', 'updateinfo', 'importnisn'),
                'expression' => 'Yii::app()->user->YiiAdmin',
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
    public function actionView($id) {
        $model = $this->loadModel($id);
        if ($model->trash != NULL) {
            Yii::app()->user->setFlash('error', "Maaf user sudah dihapus dari sistem!");
            $this->redirect(array('site/index'));
        }


        if (Yii::app()->user->YiiStudent and Yii::app()->user->id != $model->id) {
            Yii::app()->user->setFlash('error', "Maaf anda tidak punya izin akses!");
            $this->redirect(array('site/index'));
        }

        $this->render('v2/view', array(
            'model' => $this->loadModel($id),
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new User;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['User'])) {
            $model->attributes = $_POST['User'];
            if ($model->role_id == 2) {
                $model->scenario = 'sc_student';
            }

            $uploadedFile = CUploadedFile::getInstance($model, 'image');
            $model->image = $uploadedFile;

            /* if($model->role_id == 4){
              if (!empty($model->class_id)) {
              $count_cid = explode(":", $model->class_id);
              if (count($count_cid) >= 2){
              list($a, $b) = explode(":", $model->class_id);
              $class_id = str_replace(")","",$b);
              $model->class_id = $class_id;
              }
              $kelas = Clases::model()->findByPk($class_id);
              $model->class_id = NULL;
              }
              } */
            // if (!empty($_POST['class_id'])){
            $model->class_id = $_POST['class_id'];
            if ($model->save()) {
                /* $kelas->teacher_id = $model->id;
                  $kelas->save(); */
                if (!empty($uploadedFile)) {
                    if (!file_exists(Yii::app()->basePath . '/../images/user/' . $model->id)) {
                        mkdir(Yii::app()->basePath . '/../images/user/' . $model->id, 0775, true);
                    }


                    $uploadedFile->saveAs(Yii::app()->basePath . '/../images/user/' . $model->id . '/' . $uploadedFile);
                }
                $this->redirect(array('view', 'id' => $model->id));
            }
            // } else {
            // Yii::app()->user->setFlash('error', "Maaf cllas id kosongh!");
            // }
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
    public function actionUpdate($id) {
        $model = $this->loadModel($id);
        $old_image = $model->image;
        $hash = $model->encrypted_password;
        $activity = new Activities;
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        if (Yii::app()->user->YiiStudent and Yii::app()->user->id != $model->id) {
            Yii::app()->user->setFlash('error', "Maaf anda tidak punya izin akses!");
            $this->redirect(array('view', 'id' => $model->id));
        } else {

            if (isset($_POST['User'])) {
                $model->attributes = $_POST['User'];
                /* if(!password_verify($model->old_password, $hash)){
                  Yii::app()->user->setFlash('error','Password Lama Tidak Sesuai');
                  $this->redirect(array('update','id'=>$model->id));
                  } */

                $uploadedFile = CUploadedFile::getInstance($model, 'image');

                if (!empty($uploadedFile)) {
                    $model->image = $uploadedFile;
                } else {
                    $model->image = $old_image;
                }

                $model->sync_status = 2;
                if ($model->save()) {
                    if (!empty($uploadedFile)) {

                        if (!file_exists(Yii::app()->basePath . '/../images/user/' . $model->id)) {
                            mkdir(Yii::app()->basePath . '/../images/user/' . $model->id, 0775, true);
                        }

                        $uploadedFile->saveAs(Yii::app()->basePath . '/../images/user/' . $model->id . '/' . $uploadedFile);
                    }

                    $activity->activity_type = "Update Profil";
                    $activity->created_by = Yii::app()->user->id;
                    $activity->save();
                    Yii::app()->user->setFlash('success', "Update User Berhasil!");
                    $this->redirect(array('view', 'id' => $model->id));
                }
            }

            $this->render('v2/form', array(
                'model' => $model,
            ));
        }
    }

     public function actionUpdatesolution() {
         $dataProvider = new CActiveDataProvider('User', array(
            'criteria' => array(
                'condition' => 't.trash is null',
            ),
            'pagination' => array('pageSize' => 100000)
          ));

            $users = $dataProvider->getData();

            foreach ($users as $key => $value) {
                
                $model = $this->loadModel($value->id);
               
                $model->id_absen_solution = $value->id;
                $model->sync_status = 2;

                if ($model->save()) {
                    Yii::app()->user->setFlash('success', "Update User Berhasil!");
                }

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
        $dataProvider = new CActiveDataProvider('User', array(
            'criteria' => array(
                'condition' => 't.trash is null',
            ),
            'pagination' => array('pageSize' => 15)
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
            $keyword = strtolower($_GET['keyword']);
            switch ($tipe) {
                case 1:
                    $term = "lower(username) LIKE '%" . $keyword . "%' AND trash is null";
                    break;
                case 2:
                    $term = "lower(email) LIKE '%" . $keyword . "%' AND trash is null";
                    break;
                case 3:
                    $term = "lower(display_name) LIKE '%" . $keyword . "%' AND trash is null";
                    break;
                case 4:
                    $term = "role_id = '" . $keyword . "' AND trash is null";
                    break;
                case 5:
                    $kls = ClassDetail::model()->findAll(array("condition" => "name LIKE '%" . $keyword . "%'"));
                    $cid = array();
                    $id_kelas = NULL;
                    $term = '';
                    if (!empty($kls)) {
                        foreach ($kls as $key) {
                            array_push($cid, $key->id);
                        }
                        $id_kelas = implode(',', $cid);
                        $term = 'class_id IN (' . $id_kelas . ') AND trash is null';
                    }

                    break;

                default:
                    $term = 'trash is null';
                    break;
            }
        }

        $dataProvider = new CActiveDataProvider('User', array(
            'criteria' => array(
                'condition' => $term,
            ),
            'pagination' => array('pageSize' => 15)
        ));

        $this->render('v2/list', array(
            'dataProvider' => $dataProvider,
        ));
    }

    public function actionGantiPassword($id) {

        $model = $this->loadModel($id);

        if (!Yii::app()->user->YiiAdmin && $model->id != Yii::app()->user->id) {
            Yii::app()->user->setFlash('error', 'Maaf Anda Tidak Punya Akses Ke Akun Ini !');
            $this->redirect(array('/site/index'));
        }
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['User'])) {
            $model->attributes = $_POST['User'];
            $model->sync_status = 2;
            $ph = new PasswordHash(Yii::app()->params['phpass']['iteration_count_log2'], Yii::app()->params['phpass']['portable_hashes']);
            if (isset($_POST['User']['new_password'], $_POST['User']['password2'], $_POST['User']['old_password'])) {
                if ($_POST['User']['new_password'] !== $_POST['User']['password2']) {
                    Yii::app()->user->setFlash('error', 'Password Baru & Ulang Password Tidak Sama !');
                } elseif (!empty($_POST['User']['old_password']) && !$ph->CheckPassword($_POST['User']['old_password'], $model->encrypted_password)) {
                    Yii::app()->user->setFlash('error', 'Password Lama Tidak Sama !');
                } else {
                    if (empty($_POST['User']['old_password'])) {
                        Yii::app()->user->setFlash('error', 'Password Lama Tidak Boleh Kosong !');
                    } else {
                        if (!empty($_POST['User']['new_password'])) {
                            $model->encrypted_password = $_POST['User']['new_password'];
                            $model->reset_password = "Dirubah";
                        }

                        if ($model->save()) {
                            Yii::app()->user->setFlash('success', 'Password Berhasil Diubah !');
                            //$this->redirect(array('view','id'=>$model->id));
                            $this->redirect(array('/site/index'));
                        }
                    }
                }
            } else {
                $model->reset_password = "Dirubah";
                $model->sync_status = 2;
                if ($model->save()) {
                    Yii::app()->user->setFlash('success', 'Password Berhasil Diubah !');
                    //$this->redirect(array('view','id'=>$model->id));
                    $this->redirect(array('/site/index'));
                }
            }
            /* if($model->save()){
              Yii::app()->user->setFlash('success', 'Profile updated');
              $this->redirect(array('view','id'=>$model->user_id));
              } */

            //Yii::app()->user->setFlash('danger', 'Failed updated profile');
        }

        $this->render('v2/password', array(
            'model' => $model,
        ));
    }

    public function actionDownloadUser($pilihan, $keyword) {
        $term = '';
        if (!empty($pilihan)) {
            $tipe = $pilihan;
            $teks = strtolower($keyword);
            switch ($tipe) {
                case 1:
                    $term = "lower(username) LIKE '%" . $keyword . "%' AND trash is null";
                    break;
                case 2:
                    $term = "lower(email) LIKE '%" . $keyword . "%' AND trash is null";
                    break;
                case 3:
                    $term = "lower(display_name) LIKE '%" . $keyword . "%' AND trash is null";
                    break;
                case 4:
                    $term = "role_id = '" . $keyword . "' AND trash is null";
                    break;
                case 5:
                    $kls = ClassDetail::model()->findAll(array("condition" => "name LIKE '%" . $keyword . "%'"));
                    $cid = array();
                    $id_kelas = NULL;
                    $term = '';
                    if (!empty($kls)) {
                        foreach ($kls as $key) {
                            array_push($cid, $key->id);
                        }
                        $id_kelas = implode(',', $cid);
                        $term = 'class_id IN (' . $id_kelas . ') AND trash is null';
                    }

                    break;

                default:
                    $term = 'trash is null';
                    break;
            }
        } else {
             $term = 'trash is null';
        }
        $user = User::model()->findByPk(Yii::app()->user->id);
        $prefix = Yii::app()->params['tablePrefix'];
        $siswa = User::model()->findAll(array('condition' => $term, 'order' => 'display_name'));
        Yii::import('ext.phpexcel.XPHPExcel');
        $objPHPExcel = XPHPExcel::createPHPExcel();
        $objPHPExcel->getProperties()->setCreator($user->display_name)
                ->setLastModifiedBy($user->display_name)
                ->setTitle("Daftar User ")
                ->setSubject("")
                ->setDescription("")
                ->setKeywords("")
                ->setCategory("Daftar User");

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

        if (!empty($keyword)) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('C2', 'Daftar User ' . $keyword);
        } else {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('C2', 'Daftar User ');
        }


        $objPHPExcel->getActiveSheet()->getStyle('C2')->applyFromArray($styleArray);

        // Add some data
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A10', 'No')
                ->setCellValue('B10', 'ID User')
                ->setCellValue('C10', 'NIP/NIS')
                ->setCellValue('D10', 'Nama Lengkap')
                ->setCellValue('E10', 'Email')
                ->setCellValue('F10', 'Password')
                ->mergeCells('A10:A11')
                ->mergeCells('B10:B11')
                ->mergeCells('C10:C11')
                ->mergeCells('D10:D11')
                ->mergeCells('E10:E11')
                ->mergeCells('F10:F11')
                ->getStyle('B10:B11')->applyFromArray($style);
        $objPHPExcel->getActiveSheet()->getStyle('C10:C11')->applyFromArray($style);
        $objPHPExcel->getActiveSheet()->getStyle('A10:A11')->applyFromArray($style);
        $objPHPExcel->getActiveSheet()->getStyle('D10:D11')->applyFromArray($style);
        $objPHPExcel->getActiveSheet()->getStyle('E10:E11')->applyFromArray($style);
        $objPHPExcel->getActiveSheet()->getStyle('F10:F11')->applyFromArray($style);

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

        $huruf = range('D', 'Z');
        $no = 12;
        $counter = 1;
        $cell = 0;
        foreach ($siswa as $key) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $no . '', '' . $counter . '')
                    ->setCellValue('B' . $no . '', '' . $key->id . '')
                    ->setCellValue('C' . $no . '', '' . $key->username . '')
                    ->setCellValue('D' . $no . '', '' . $key->display_name . '')
                    ->setCellValue('E' . $no . '', '' . $key->email . '')
                    ->setCellValue('F' . $no . '', '' . $key->reset_password . '');

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
        header('Content-Disposition: attachment;filename="Daftar-User.xls"');
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

    public function actionHapus($id) {
        $model = $this->loadModel($id);

        if ($model->role_id == 99) {
            Yii::app()->user->setFlash('error', 'User Adalah Admin Tidak Bisa Dihapus!');
            $this->redirect(array('index'));
        }

        $model->trash = 1;
        $model->sync_status = 2;
        $model->username = $model->username . "->D-" . date('Y-m-d H:i:s');
        $model->email = "deleted_".$model->email ;

        $modelLm = LessonMc::model()->findAll(array("condition" => "user_id = '$model->id'"));
        if(!empty($modelLm)) {
            // $modelLm->delete();
            foreach ($modelLm as $get) {
                 $modeldel = LessonMc::model()->findByPk($get->id);
                 $modeldel->delete();
            }
        }

        $modelQ = StudentQuiz::model()->findAll(array("condition" => "student_id = '$model->id'"));
        // $modelQ->delete();
        if(!empty($modelQ)) {
            foreach ($modelQ as $get) {
                 $modeldelQ = StudentQuiz::model()->findByPk($get->id);
                 $modeldelQ->trash = 1;
                 $modeldelQ->save();
            }
        }

        if ($model->save()) {
            Yii::app()->user->setFlash('success', 'Berhasil Menghapus User !');
            $this->redirect(array('index'));
        }
    }

    public function actionHapusAll() {
       
         $idid = array();          
         if (!empty($_POST['users'])) {
                foreach ($_POST['users'] as $key) {
                    array_push($idid, $key);
                }
            }
        $ids = implode(',', $idid); 

        // print($ids);
        // $model = User::model()->findAll(array('condition' => 'id IN ('.$ids.')'));

        // foreach ($model as $key => $value) {
        //        print_r($value);
        // }   

        $modelLm = LessonMc::model()->findAll(array('condition' => 'user_id in ('.$ids.')'));
        if(!empty($modelLm)) {
            // $modelLm->delete();
            foreach ($modelLm as $getlm) {
                 $modeldel = LessonMc::model()->findByPk($getlm->id);
                 $modeldel->delete();
            }
        }

        $modelQ = StudentQuiz::model()->findAll(array('condition' => 'student_id in ('.$ids.')'));
        // $modelQ->delete();
        if(!empty($modelQ)) {
            foreach ($modelQ as $getsq) {
                 $modeldelQ = StudentQuiz::model()->findByPk($getsq->id);
                 $modeldelQ->trash = 1;
                 $modeldelQ->save();
            }
        }


        $users_deleted = 0;
        $model = User::model()->findAll(array('condition' => 'id in ('.$ids.')'));

        if(!empty($model)) {

            foreach ($model as $getuser) {
                if ($getuser->role_id == 99) {
                    Yii::app()->user->setFlash('error', 'User Adalah Admin Tidak Bisa Dihapus!');
                    $this->redirect(array('index'));
                }

                $getuser->trash = 1;
                $getuser->sync_status = 2;
                $getuser->username = $getuser->username . "->D-" . date('Y-m-d H:i:s');
                $getuser->email = "deleted_".$getuser->email ;

                if ($getuser->save()) {
                    $users_deleted = $users_deleted + 1;
                }
            }
        }

         Yii::app()->user->setFlash('success', 'Berhasil Menghapus User '.$users_deleted.' !');
         $this->redirect(array('index'));
    }

    public function actionDownloadFile() {
        $dir_path = Yii::getPathOfAlias('webroot') . '/images/';

        $filePlace = Yii::app()->basePath . '/../images/format-user.xlsx';
        //echo $fileName;
        $fileName = "format-user.xlsx";

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
        $model = new User('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['User']))
            $model->attributes = $_GET['User'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return User the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = User::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param User $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'user-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionSuggest() {
        $request = trim($_GET['term']);
        if ($request != '') {
            $model = User::model()->findAll(array("condition" => "lower(display_name) like lower('$request%') and role_id != 2"));
            $data = array();
            foreach ($model as $get) {
                /* if(!empty($get->kecamatan)){
                  $kec=$get->district;
                  $kab=$kec->regency;

                  $data[]=$get->title.', '.$kec->name.', '.$kab->name.' (ID:'.$get->id.')';
                  }
                  else{ */
                $data[] = $get->display_name . ' | ' . $get->username . ' (ID:' . $get->id . ')';
                //}
            }
            $this->layout = 'empty';
            echo json_encode($data);
        }
    }

    public function actionSuggestWali() {
        $request = trim($_GET['term']);
        if ($request != '') {
            $model = User::model()->findAll(array("condition" => "lower(display_name) like lower('$request%') and role_id = 1"));
            $data = array();
            foreach ($model as $get) {
                /* if(!empty($get->kecamatan)){
                  $kec=$get->district;
                  $kab=$kec->regency;

                  $data[]=$get->title.', '.$kec->name.', '.$kab->name.' (ID:'.$get->id.')';
                  }
                  else{ */
                $data[] = $get->display_name . ' | ' . $get->username . ' (ID:' . $get->id . ')';
                //}
            }
            $this->layout = 'empty';
            echo json_encode($data);
        }
    }

    public function actionBulk() {

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

                            if (!empty($column['NOMOR_INDUK'])) {
                                //$nik = $column['nip'];
                                $nama = $column['NAMA_LENGKAP'];
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
                                for ($i = 0; $i < $length; $i++) {
                                    $randomString .= $characters[rand(0, $charactersLength - 1)];
                                }

                                for ($x = 0; $x < $length2; $x++) {
                                    $randomNik .= $characters2[rand(0, $charactersLength2 - 1)];
                                }

                                $password = $randomString;
                                $rawrole = $column['ROLE'];
                                if (strtolower($rawrole) == "guru") {
                                    $role = 1;
                                } elseif (strtolower($rawrole) == "siswa") {
                                    $role = 2;
                                } elseif (strtolower($rawrole) == "walikelas") {
                                    $role = 4;
                                } elseif (strtolower($rawrole) == "kepalasekolah") {
                                    $role = 5;
                                } else {
                                    $role = 2;
                                }

                                $kelas = $column['ID_KELAS'];
                                if (empty($column['EMAIL'])) {
                                    $email = $randomString . "@mail.id";
                                } else {
                                    $email = $column['EMAIL'];
                                }

                                $nik = $column['NOMOR_INDUK'];
                                $nik_final = str_replace('-', '', trim($nik));
                                /* if(empty($nik)){
                                  $nik = $randomNik;
                                  }else{
                                  $nik = $column['nip'];
                                  } */

                                $ph = new PasswordHash(Yii::app()->params['phpass']['iteration_count_log2'], Yii::app()->params['phpass']['portable_hashes']);
                                $passwd = $ph->HashPassword($password);

                                if ($role == 2) {
                                    if (empty($kelas)) {
                                        Yii::app()->user->setFlash('error', "Baris $row2, Kolom Kelas Harus Di Isi!");
                                        $this->redirect(array('bulk'));
                                    }
                                }

                                if (empty($nama)) {
                                    Yii::app()->user->setFlash('error', "Baris $row2, Kolom Nama Harus Di Isi!");
                                    $this->redirect(array('bulk'));
                                }

                                /* if (empty($email)){
                                  Yii::app()->user->setFlash('error', "Baris $row2 . kolom email harus di isi");
                                  $this->redirect(array('bulk'));
                                  } */

                                if (empty($password)) {
                                    Yii::app()->user->setFlash('error', "Baris $row2, Kolom Password Harus Di Isi!");
                                    $this->redirect(array('bulk'));
                                }

                                if (empty($role)) {
                                    Yii::app()->user->setFlash('error', "Baris $row2, Kolom Role Harus Di Isi!");
                                    $this->redirect(array('bulk'));
                                }

                                if (!is_numeric($role)) {
                                    Yii::app()->user->setFlash('error', "Baris $row2, Kolom Role Harus Numeric (Foreign Key)!");
                                    $this->redirect(array('bulk'));
                                }

                                if (!empty($kelas) and ! is_numeric($kelas)) {
                                    Yii::app()->user->setFlash('error', "Baris $row2, Kolom Kelas Harus Numeric (Foreign Key)!");
                                    $this->redirect(array('bulk'));
                                }

                                $cekUE = User::model()->findAll(array("condition" => "username = '$nik' or email = '$email'"));

                                if (!empty($cekUE)) {
                                    Yii::app()->user->setFlash('error', "Baris $row2, Username atau E-Mail Sudah Terdaftar!");
                                    $this->redirect(array('bulk'));
                                }


                                $insert = "INSERT INTO " . $prefix . "users (email,username,encrypted_password,role_id,created_at,updated_at,class_id,reset_password,display_name) values(:email,:username,:encrypted_password,:role_id,NOW(),NOW(),:class_id,:reset_password,:display_name)";

                                $insertCommand = Yii::app()->db->createCommand($insert);

                                $insertCommand->bindParam(":email", $email, PDO::PARAM_STR);
                                $insertCommand->bindParam(":username", $nik_final, PDO::PARAM_STR);
                                $insertCommand->bindParam(":encrypted_password", $passwd, PDO::PARAM_STR);
                                $insertCommand->bindParam(":role_id", $role, PDO::PARAM_STR);
                                $insertCommand->bindParam(":class_id", $kelas, PDO::PARAM_STR);
                                $insertCommand->bindParam(":reset_password", $password, PDO::PARAM_STR);
                                $insertCommand->bindParam(":display_name", $nama, PDO::PARAM_STR);


                                if ($insertCommand->execute()) {
                                    $sukses++;
                                } else {
                                    $fail++;
                                }
                            }

                            $row++;
                            $urutan++;
                        }
                        Yii::app()->user->setFlash('success', "Import " . $sukses . " Pengguna Berhasil !");
                        $transaction->commit();
                    } catch (Exception $error) {
                        //echo "<pre>";
                        //print_r($error);
                        //echo "</pre>";
                        $transaction->rollback();
                        throw new CHttpException($error);
                    }

                    /* try{
                      $transaction = Yii::app()->db->beginTransaction();
                      $handle = fopen("$file->tempName", "r");
                      $head = fgetcsv($handle, 1000, '#');
                      $row = 1;
                      $id_lot = array();
                      $nama_file = array();
                      $csv_array = array();
                      $string_id = "";

                      while (($data = fgetcsv($handle, 1000, "#")) !== FALSE) {
                      if($row>=1){

                      $column = array_combine($head, $data);
                      $row2 = $row;

                      if (!empty($column['nip'])){
                      //$nik = $column['nip'];
                      $nama = $column['nama'];
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
                      for ($i = 0; $i < $length; $i++) {
                      $randomString .= $characters[rand(0, $charactersLength - 1)];
                      }

                      for ($x = 0; $x < $length2; $x++) {
                      $randomNik .= $characters2[rand(0, $charactersLength2 - 1)];
                      }

                      $password = $randomString;
                      $rawrole = $column['role'];
                      if(strtolower($rawrole) == "guru"){
                      $role = 1;
                      }elseif(strtolower($rawrole) == "siswa"){
                      $role = 2;
                      }elseif(strtolower($rawrole) == "walikelas"){
                      $role = 4;
                      }elseif(strtolower($rawrole) == "kepalasekolah"){
                      $role = 5;
                      }else{
                      $role = 2;
                      }

                      $kelas = $column['kelas_id'];
                      if(empty($column['email'])){
                      $email = $randomString."@mail.id";
                      }else{
                      $email = $column['email'];
                      }

                      $nik = $column['nip'];
                      $nik_final = str_replace('-', '', trim($nik));

                      $ph=new PasswordHash(Yii::app()->params['phpass']['iteration_count_log2'], Yii::app()->params['phpass']['portable_hashes']);
                      $passwd = $ph->HashPassword($password);

                      if ($role == 2){
                      if (empty($kelas)){
                      Yii::app()->user->setFlash('error', "Baris $row2 . kolom kelas harus di isi");
                      $this->redirect(array('bulk'));
                      }
                      }

                      if (empty($nama)){
                      Yii::app()->user->setFlash('error', "Baris $row2 . kolom nama harus di isi");
                      $this->redirect(array('bulk'));
                      }

                      if (empty($password)){
                      Yii::app()->user->setFlash('error', "Baris $row2 . kolom password harus di isi");
                      $this->redirect(array('bulk'));
                      }

                      if (empty($role)){
                      Yii::app()->user->setFlash('error', "Baris $row2 . kolom role harus di isi");
                      $this->redirect(array('bulk'));
                      }

                      if (!is_numeric($role)){
                      Yii::app()->user->setFlash('error', "Baris $row2 . kolom role harus numeric (foreign key)");
                      $this->redirect(array('bulk'));
                      }

                      if (!empty($kelas) and !is_numeric($kelas)){
                      Yii::app()->user->setFlash('error', "Baris $row2 . kolom kelas harus numeric (foreign key)");
                      $this->redirect(array('bulk'));
                      }

                      $cekUE=User::model()->findAll(array("condition"=>"username = '$nik' or email = '$email'"));

                      if (!empty($cekUE)){
                      Yii::app()->user->setFlash('error', "Baris $row2 . username atau email sudah terdaftar di database");
                      $this->redirect(array('bulk'));
                      }


                      $insert="INSERT INTO ".$prefix."users (email,username,encrypted_password,role_id,created_at,updated_at,class_id,reset_password,display_name) values(:email,:username,:encrypted_password,:role_id,NOW(),NOW(),:class_id,:reset_password,:display_name)";

                      $insertCommand=Yii::app()->db->createCommand($insert);

                      $insertCommand->bindParam(":email",$email,PDO::PARAM_STR);
                      $insertCommand->bindParam(":username",$nik_final,PDO::PARAM_STR);
                      $insertCommand->bindParam(":encrypted_password",$passwd,PDO::PARAM_STR);
                      $insertCommand->bindParam(":role_id",$role,PDO::PARAM_STR);
                      $insertCommand->bindParam(":class_id",$kelas,PDO::PARAM_STR);
                      $insertCommand->bindParam(":reset_password",$password,PDO::PARAM_STR);
                      $insertCommand->bindParam(":display_name",$nama,PDO::PARAM_STR);


                      if($insertCommand->execute()){
                      $sukses = "ya";
                      }else{
                      $sukses = "tidak";
                      }

                      }

                      }
                      $row++;
                      }
                      //Yii::app()->user->setFlash('success', "Csv Berhasil!");
                      $transaction->commit();
                      }catch(Exception $error){
                      echo "<pre>";
                      print_r($error);
                      echo "</pre>";
                      $transaction->rollback();
                      } */
                }
                //$this->redirect(array('import_form'));
            } else {
                Yii::app()->user->setFlash('error', "Import Gagal!");
            }
        }

        /* if (!empty($sukses)){
          $row2 = $row - 1;
          Yii::app()->user->setFlash('success', "Berhasil buat $row2 record user!");
          } */


        $this->render('bulk', array(
            'model' => $model,
        ));
    }

    // public function actionUpdateInfo($id)
    // {
    // 	//$model=UserProfile::model()->findByPk($id);
    // 	$model_init =new UserProfile;
    // 	$model_users =new User;
    // 	$activity =new Activities;
    // 	$cekUser = $model_init->findAll('user_id = :id',array(':id' => $id));
    // 	if(empty($cekUser))
    // 	{
    // 		$model = new UserProfile;
    // 		$user = $model_users->findByPk($id);
    // 		//echo 'ga ada';exit;
    // 	}
    // 	else
    // 	{
    // 		foreach($cekUser as $row)
    // 		{
    // 			$profile_id = $row->id;
    // 		}
    // 		$model = $model_init->findByPk($profile_id);
    // 		$user = $model_users->findByPk($id);
    // 		//echo '<pre>';print_r($model);exit;
    // 	}
    // 	if(isset($_POST['UserProfile']))
    // 	{
    // 		if(isset($_POST['ajax']) && $_POST['ajax']==='user-form')
    // 		{
    // 			echo CActiveForm::validate($models);
    // 			Yii::app()->end();
    // 		}
    // 		$model->attributes=$_POST['UserProfile'];
    // 		$model->tgl_lahir=$this->dateUSA($_POST['UserProfile']['tgl_lahir']);
    // 		$model->tgl_diterima=$this->dateUSA($_POST['UserProfile']['tgl_diterima']);
    // 		$model->user_id = $id;
    // 		$model->anak_ke = $_POST['UserProfile']['anak_ke'];
    // 		 //echo '<pre>';print_r($model->attributes);exit;
    // 		$model->save(false);
    // 		$activity->activity_type="Update Informasi Detail";
    // 		$activity->created_by=Yii::app()->user->id;
    // 		$activity->save();
    // 		Yii::app()->user->setFlash('success', "Data Siswa berhasil di-update!");
    // 		$this->redirect(array('view','id'=>$id));
    // 	}
    // 	// if (Yii::app()->user->YiiStudent and Yii::app()->user->id != $model->id)
    // 	// {
    // 	// 	Yii::app()->user->setFlash('error', "Maaf anda tidak punya izin akses!");
    // 	// 	$this->redirect(array('view','id'=>$model->id));
    // 	// }
    // 	$this->render('update_info',array(
    // 			'model'=>$model,
    // 			'user' => $user,
    // 			'id'=>$id,
    // 		));
    // }

    private function dateUSA($tanggal) {
        $date = date_create($tanggal);
        return date_format($date, 'Y-m-d');
    }

    public function actionImportnisn() {

        $model = new Activities;
        //$kelas = ClassDetail::model()->findByPk($id);
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
                        $range = 'A2:B' . $highestRow . '';
                        //$text = $data->toArray(null, true, true, true);
                        $text = $data->rangeToArray($range);
                        $head = $data->rangeToArray('A1:B1');
                        $suskes = 0;
                        $fail = 0;



                        foreach ($text as $key => $val) {

                            $column = array_combine($head[0], $val);
                            $row2 = $row;

                            if (!empty($column['NIS'])) {

                                $length = 5;
                                $length2 = 9;

                                $nik = $column['NIS'];
                                // echo $nik."</br>";
                                $nik_final = str_replace('-', '', trim($nik));
                                $siswa = User::model()->findAll(array('condition' => 'username = \'' . $nik_final . '\''));

                                if (!empty($siswa)) {

                                    if (strlen($column['NISN']) == 7) {
                                        $nisn = '000' . $column['NISN'];
                                    } else if (strlen($column['NISN']) == 8) {
                                        $nisn = '00' . $column['NISN'];
                                    } else {
                                        $nisn = $column['NISN'];
                                    }
                                    // echo "<pre>";
                                    //  echo $siswa[0]->id."</br>";
                                    // 			 echo $nisn;
                                    // 			 // echo strlen($column['NISN']);
                                    // 			echo "</pre>";

                                    $insert1 = "INSERT INTO `user_profile` (`user_id`, `nisn`) VALUES (" . $siswa[0]->id . ",'" . $nisn . "')";

                                    $insertCommand1 = Yii::app()->db->createCommand($insert1);


                                    if ($insertCommand1->execute()) {
                                        $sukses++;
                                    } else {
                                        $fail++;
                                    }
                                }
                            }
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

        /* if (!empty($sukses)){
          $row2 = $row - 1;
          Yii::app()->user->setFlash('success', "Berhasil buat $row2 record user!");
          } */


        $this->render('importnisn', array(
            'model' => $model,
        ));
    }

}
