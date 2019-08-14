<?php

class ClasesController extends Controller {

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
                'actions' => array('Suggest'),
                'users' => array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('index', 'view'),
                'users' => array('@'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('create', 'update', 'raportSiswa', 'pindahKelas', 'downloadNilai', 'downloadAll'),
                'expression' => 'Yii::app()->user->YiiKepsek',
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('DownloadAbsen', 'raportSiswa', 'pindahKelas', 'downloadNilai', 'downloadAll'),
                'expression' => 'Yii::app()->user->YiiWali',
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('LegerDh','AkunOrtu','UpdateDesc','RaporPindah', 'legerUas', 'DownloadAbsen', 'raportSiswa', 'pindahKelas', 'downloadNilai', 'downloadAll', 'importnilaiwalikelas', 'inputnilaiwalikelas'),
                'expression' => 'Yii::app()->user->YiiTeacher',
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('LegerDh','AkunOrtu','RaporPindah', 'RaporUtsdh', 'RaporBiodata', 'DownloadAbsen', 'create', 'update', 'admin', 'delete', 'raportSiswa', 'pindahKelas', 'bulk', 'downloadNilai', 'downloadAll', 'downloadFile', 'addExcel', 'raporUts', 'RaporUas', 'RaporLkhbs', 'raporUtsall', 'raporUasall', 'legerUas', 'Importnilairapor', 'importnilaiwalikelas', 'UpdateDesc', 'inputnilaiwalikelas'),
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

        $siswa = User::model()->findAll(array('condition' => 'class_id = ' . $id . ' and trash is null order by display_name ASC'));
        $this->render('view', array(
            'model' => ClassDetail::model()->findByPk($id),
            'siswa' => $siswa,
        ));
    }

    public function actionDownloadAbsen($id) {
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




        //------


        $user = User::model()->findByPk(Yii::app()->user->id);
        $prefix = Yii::app()->params['tablePrefix'];
        $siswa = User::model()->findAll(array('condition' => 'class_id = ' . $id . ' and trash is null order by display_name ASC'));
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

        // if(!empty($keyword)){
        // 	$objPHPExcel->setActiveSheetIndex(0)
        // ->setCellValue('C2','Daftar User '.$keyword);
        // } else {
        // 	$objPHPExcel->setActiveSheetIndex(0)
        // ->setCellValue('C2','Daftar User ');
        // }
        // $objPHPExcel->getActiveSheet()->getStyle('C2')->applyFromArray($styleArray);
        // Add some data
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'No')
                ->setCellValue('B1', 'ID User')
                ->setCellValue('C1', 'NIP/NIS')
                ->setCellValue('D1', 'Nama Lengkap')
                ->setCellValue('E1', 'Sikap Spiritual - Predikat')
                ->setCellValue('F1', 'Sikap Spiritual - Deskripsi')
                ->setCellValue('G1', 'Sikap Sosial - Predikat')
                ->setCellValue('H1', 'Sikap Sosial - Deskripsi')
                ->setCellValue('I1', 'Ekstrakurikuler 1 - Nama')
                ->setCellValue('J1', 'Ekstrakurikuler 1 - Nilai')
                ->setCellValue('K1', 'Ekstrakurikuler 2 - Nama')
                ->setCellValue('L1', 'Ekstrakurikuler 2 - Nilai')
                ->setCellValue('M1', 'Ekstrakurikuler 3 - Nama')
                ->setCellValue('N1', 'Ekstrakurikuler 3 – Nilai')
                ->setCellValue('O1', 'Ekstrakurikuler 4 - Nama')
                ->setCellValue('P1', 'Ekstrakurikuler 4 – Nilai')
                ->setCellValue('Q1', 'Prestasi 1 - Jenis Kegiatan')
                ->setCellValue('R1', 'Prestasi 1 - Keterangan')
                ->setCellValue('S1', 'Prestasi 2 - Jenis Kegiatan')
                ->setCellValue('T1', 'Prestasi 2 - Keterangan')
                ->setCellValue('U1', 'Prestasi 3 - Jenis Kegiatan')
                ->setCellValue('V1', 'Prestasi 3 - Keterangan')
                ->setCellValue('W1', 'Prestasi 4 - Jenis Kegiatan')
                ->setCellValue('X1', 'Prestasi 4 - Keterangan')
                ->setCellValue('Y1', 'Absensi Sakit')
                ->setCellValue('Z1', 'Absensi Izin')
                ->setCellValue('AA1', 'Absensi Alfa')
                ->setCellValue('AB1', 'Catatan Wali Kelas');
        // ->getStyle('B10:B11')->applyFromArray($style);
        // $objPHPExcel->getActiveSheet()->getStyle('C10:C11')->applyFromArray($style);
        // $objPHPExcel->getActiveSheet()->getStyle('A10:A11')->applyFromArray($style);
        // $objPHPExcel->getActiveSheet()->getStyle('D10:D11')->applyFromArray($style);
        // $objPHPExcel->getActiveSheet()->getStyle('E10:E11')->applyFromArray($style);
        // $objPHPExcel->getActiveSheet()->getStyle('F10:F11')->applyFromArray($style);

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


        $objPHPExcel->getActiveSheet()
                ->getColumnDimension('R')
                ->setAutoSize(true);


        $objPHPExcel->getActiveSheet()
                ->getColumnDimension('S')
                ->setAutoSize(true);


        $objPHPExcel->getActiveSheet()
                ->getColumnDimension('T')
                ->setAutoSize(true);


        $objPHPExcel->getActiveSheet()
                ->getColumnDimension('U')
                ->setAutoSize(true);


        $objPHPExcel->getActiveSheet()
                ->getColumnDimension('V')
                ->setAutoSize(true);


        $objPHPExcel->getActiveSheet()
                ->getColumnDimension('W')
                ->setAutoSize(true);


        $objPHPExcel->getActiveSheet()
                ->getColumnDimension('X')
                ->setAutoSize(true);


        $objPHPExcel->getActiveSheet()
                ->getColumnDimension('Y')
                ->setAutoSize(true);


        $objPHPExcel->getActiveSheet()
                ->getColumnDimension('Z')
                ->setAutoSize(true);


        $objPHPExcel->getActiveSheet()
                ->getColumnDimension('AA')
                ->setAutoSize(true);


        $objPHPExcel->getActiveSheet()
                ->getColumnDimension('AB')
                ->setAutoSize(true);

        $huruf = range('D', 'Z');
        $no = 2;
        $counter = 1;
        $cell = 0;
        foreach ($siswa as $key) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $no . '', '' . $counter . '')
                    ->setCellValue('B' . $no . '', '' . $key->id . '')
                    ->setCellValue('C' . $no . '', '' . $key->username . '')
                    ->setCellValue('D' . $no . '', '' . $key->display_name . '');

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

    public function actionAddExcel() {
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

        if (isset($_POST['kelas']) || isset($_POST['grade'])) {
            if (!empty($_POST['kelas'])) {
                $list = $_POST['kelas'];
                $grade = $_POST['grade'];
                $gabungan = array_combine($list, $grade);
                $sukses = 0;
                $kelasTerdaftar = "";
                foreach ($gabungan as $key => $value) {

                    // echo "<pre>";
                    // 	print_r($value);
                    // echo "</pre>";

                    $cekKelas = ClassDetail::model()->findAll(array('condition' => 'name LIKE "%' . $key . '%"'));

                    $class_detail = new ClassDetail;
                    $class_detail->name = $key;
                    $class_detail->class_id = $value;

                    if ($cekKelas) {
                        $kelasTerdaftar .= $key;
                    } else {
                        if ($class_detail->save()) {
                            $sukses++;
                        }
                    }
                }
                if ($sukses > 0) {
                    Yii::app()->user->setFlash('success', $sukses . ' Kelas Berhasil Terdaftar!');
                    if ($kelasTerdaftar != "") {
                        Yii::app()->user->setFlash('error', 'Kelas ' . $kelasTerdaftar . ' Gagal Terdaftar!');
                    }
                    $this->redirect('index');
                } else {
                    //Yii::app()->user->setFlash('success',$sukses.' Kelas Berhasil Terdaftar!');
                    Yii::app()->user->setFlash('error', 'Kelas ' . $kelasTerdaftar . ' Gagal Terdaftar, kelas sudah terdaftar sebelumnya!');
                    $this->redirect('index');
                }
            }
        }
        $this->render('v2/copy');
    }

    public function actionFinalizeData() {
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

        if (isset($_POST['kelas'])) {
            if (!empty($_POST['kelas'])) {
                $list = $_POST['kelas'];
                $data = preg_split('/\r\n|[\r\n]/', $list);
                $sukses = 0;
                foreach ($data as $value) {
                    $kelas = new Clases;
                    $kelas->name = $value;

                    if ($kelas->save()) {
                        $sukses++;
                    }
                }

                if ($sukses > 0) {
                    Yii::app()->user->setFlash('success', 'Daftar Kelas Berhasil!');
                    $this->redirect('/site/index');
                }
            }
        }
        $this->render('copy-final');
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
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

        $model = new Clases;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Clases'])) {
            $model->attributes = $_POST['Clases'];
            $model->kelompok = 1;
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
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

        $model = ClassDetail::model()->findByPk($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['ClassDetail'])) {

            $model->attributes = $_POST['ClassDetail'];
            $model->teacher_id = $_POST['ClassDetail']['teacher_id'];
            // echo "<pre>";
            // 	print_r($_POST['ClassDetail']);
            // 	print_r($model->attributes);
            // echo "</pre>";
            $model->sync_status = 2;
            //$model->sync_status=NULL;
            //$model->kelompok = 1;
            if ($model->save()) {
                Yii::app()->user->setFlash('success', 'Data Berhasil Dirubah!');
                $this->redirect(array('view', 'id' => $model->id));
                //print_r($model->getErrors());
            }
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
    public function actionDelete($id) {
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
    public function actionIndex() {
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

        $term = '';
        if (Yii::app()->user->YiiWali) {
            $term = 'teacher_id = ' . Yii::app()->user->id;
        }

        $dataProvider = new CActiveDataProvider('ClassDetail', array(
            'criteria' => array(
                'condition' => $term),
        ));

        $this->render('v2/list', array(
            'dataProvider' => $dataProvider,
        ));
    }

    public function actionPindahKelas() {
        // if(Yii::app()->session['semester']){
        // 	$optSemester = Yii::app()->session['semester'];
        // } else {
        $optSemester = Option::model()->findByAttributes(array('key_config' => 'semester'))->value;
        // }
        // 	if(Yii::app()->session['tahun_ajaran']){
        // 	$optTahunAjaran = Yii::app()->session['tahun_ajaran'];
        // } else {
        $optTahunAjaran = Option::model()->findByAttributes(array('key_config' => 'tahun_ajaran'))->value;
        // }

        if (isset($_POST['pindahKelas']) || isset($_POST['pindahSiswa'])) {
            // echo "<pre>";

            $prefix = Yii::app()->params['tablePrefix'];

            $total = 0;
            $pindahKelas = $_POST['pindahKelas'];
            $pindahSiswa = $_POST['pindahSiswa'];

            foreach ($pindahSiswa as $key) {
                $siswa = User::model()->findByPk($key);

                if (!empty($siswa) && $siswa->class_id != $pindahKelas) {
                    $user_id = $siswa->id;
                    $class_id = $siswa->class_id;

                    $class_history = ClassHistory::model()->findByAttributes(array('user_id' => $user_id, 'semester' => $optSemester, 'year' => $optTahunAjaran));
                    if (empty($class_history)) {
                        $sql_history = "INSERT INTO " . $prefix . "class_history (user_id, class_id, semester, year, sync_status) VALUES (" . $user_id . ", " . $class_id . ", '" . $optSemester . "', '" . $optTahunAjaran . "', NULL)";
                        $sql_history_exec = Yii::app()->db->createCommand($sql_history)->execute();
                    } else {
                        $sql_history = "UPDATE " . $prefix . "class_history SET sync_status = 2 WHERE id = " . $class_history->id;
                        $sql_history_exec = Yii::app()->db->createCommand($sql_history)->execute();
                    }

                    $siswa->class_id = $pindahKelas;
                    $siswa->sync_status = 2;
                    if ($siswa->save()) {
                        $total = $total + 1;
                    }
                }
            }

            // $gabungan = array_combine($_POST['siswa'],$_POST['pindah']);
            // foreach ($gabungan as $key => $value) {
            // 	$siswa = User::model()->findByPk($key);
            // 	if(!empty($siswa) && $siswa->class_id != $value){
            // 		$user_id = $siswa->id;
            // 		$class_id = $siswa->class_id;
            // 		$sql_history = "INSERT INTO ".$prefix."class_history (user_id, class_id, semester, year) VALUES (".$user_id.", ".$class_id.", '".$optSemester."', '".$optTahunAjaran."')";
            // 		$sql_history_exec = Yii::app()->db->createCommand($sql_history);
            // 		if($sql_history_exec->execute()){
            // 			$siswa->class_id = $value;
            // 			if($siswa->save()){
            // 				$total=$total+1;
            // 			}
            // 		}
            // 	}
            // }
            // print_r($gabungan);
            // echo "</pre>";

            Yii::app()->user->setFlash('success', $total . ' Siswa Berhasil Pindah Kelas');
            $this->redirect(array('index'));
        }
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
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

        $model = new Clases('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Clases']))
            $model->attributes = $_GET['Clases'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Clases the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
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

        $model = Clases::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Clases $model the model to be validated
     */
    protected function performAjaxValidation($model) {
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

        if (isset($_POST['ajax']) && $_POST['ajax'] === 'clases-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionSuggest() {
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
            $model = Clases::model()->findAll(array("condition" => "lower(name) like lower('$request%')"));
            $data = array();
            foreach ($model as $get) {
                /* if(!empty($get->kecamatan)){
                  $kec=$get->district;
                  $kab=$kec->regency;

                  $data[]=$get->title.', '.$kec->name.', '.$kab->name.' (ID:'.$get->id.')';
                  }
                  else{ */
                $data[] = $get->name . ' (ID:' . $get->id . ')';
                //}
            }
            $this->layout = 'empty';
            echo json_encode($data);
        }
    }

    public function actionRaporUts($id) {
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

        $base = Yii::app()->baseUrl;
        $user = User::model()->findByPk($id);
        $user_data = UserProfile::model()->findByAttributes(array('user_id' => $id));
        $my_lesson = LessonMc::model()->findAll(array('condition' => 'user_id = ' . $id . ' and trash is null AND semester = ' . $optSemester . " AND year = " . $optTahunAjaran, 'order' => 'lesson_id'));
        $lesson_list = array();
        $ll = NULL;
        $mapel = NULL;
        $mapel1 = NULL;
        $mapel2 = NULL;
        $mapel3 = NULL;
        $mapel4 = NULL;

        if (!empty($my_lesson)) {
            foreach ($my_lesson as $value) {
                array_push($lesson_list, $value->lesson_id);
            }
        }

        if (!empty($lesson_list)) {
            $ll = implode(',', $lesson_list);
            $mapel = Lesson::model()->findAll(array('condition' => 'class_id = ' . $user->class_id . ' and trash is null AND semester = ' . $optSemester . " AND year = " . $optTahunAjaran));
            $mapel1 = Lesson::model()->findAll(array('condition' => 'class_id = ' . $user->class_id . ' and kelompok = 1 and moving_class = 0 and id in (' . $ll . ') and trash is null AND semester = ' . $optSemester . " AND year = " . $optTahunAjaran));
            $mapel2 = Lesson::model()->findAll(array('condition' => 'class_id = ' . $user->class_id . ' and kelompok = 2 and moving_class = 0 and id in (' . $ll . ') and trash is null AND semester = ' . $optSemester . " AND year = " . $optTahunAjaran));
            $mapel3 = Lesson::model()->findAll(array('condition' => 'class_id = ' . $user->class_id . ' and kelompok = 3 and moving_class = 0 and id in (' . $ll . ') and trash is null AND semester = ' . $optSemester . " AND year = " . $optTahunAjaran));
            $mapel4 = Lesson::model()->findAll(array('condition' => 'kelompok = 3 and moving_class = 1 and id in (' . $ll . ') AND semester = ' . $optSemester . " AND year = " . $optTahunAjaran));
        }

        $nama = str_replace(" ", "-", $user->display_name);
        $model = ClassDetail::model()->findByPk($user->class_id);
        /* Default DOMPDF File Path */

        $template_directory = Yii::app()->theme->baseUrl;
        $template_file = Yii::app()->theme->basePath . '/css/print.bootstrap.min.css';
        $inline_style = file_get_contents($template_file);
        $icon_sprite = Yii::app()->theme->basePath . '/images/glyphicons-halflings.png';
        $inline_style = str_replace('../images/glyphicons-halflings.png', $icon_sprite, $inline_style);


        $sql = "SELECT l.`id`,l.`name`,fm.`nilai`,fm.`nilai_desc`,fm.`tipe`,l.`kelompok`,l.`list_id`,lm.`presensi_hadir`,lm.`presensi_sakit`,lm.`presensi_izin`,lm.`presensi_alfa`
				FROM `final_mark` as fm
				join `users` as u on u.`id` = fm.`user_id`
				join `lesson` as l on fm.`lesson_id` = l.`id`
				join `lesson_mc` as lm on fm.`lesson_id` = lm.`lesson_id`
				WHERE fm.`user_id` = " . $id . "
				AND fm.`semester` = " . $optSemester . "
				AND fm.`tahun_ajaran` = " . $optTahunAjaran . "
				AND l.`semester` = " . $optSemester . "
				AND l.`year` = " . $optTahunAjaran . "
				AND lm.`user_id` = " . $id . "
				AND l.`trash` is null
				order by l.`list_id`";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();

        $arr = array();

        foreach ($rows as $key => $item) {
            // $item["nilai-".$item["tipe"]] = $item["nilai"];
            $arr[$item['id']]["name"] = $item["name"];
            $arr[$item['id']]["kelompok"] = $item["kelompok"];
            $arr[$item['id']]["desc-" . $item["tipe"]] = $item["nilai_desc"];
            if (!empty($item["presensi_hadir"])) {
                $arr[$item['id']]["presensi_hadir"] = $item["presensi_hadir"];
            }

            if (!empty($item["presensi_sakit"])) {
                $arr[$item['id']]["presensi_sakit"] = $item["presensi_sakit"];
            }

            if (!empty($item["presensi_izin"])) {
                $arr[$item['id']]["presensi_izin"] = $item["presensi_izin"];
            }

            if (!empty($item["presensi_alfa"])) {
                $arr[$item['id']]["presensi_alfa"] = $item["presensi_alfa"];
            }
            $arr[$item['id']]["nilai-" . $item["tipe"]] = $item["nilai"];
        }

        //ksort($arr, SORT_NUMERIC);
        $sql = "SELECT fm.`nilai_desc`,fm.`tipe`
				FROM `final_mark` as fm
				join `users` as u on u.`id` = fm.`user_id`
				WHERE fm.`user_id` = " . $id . "
				AND fm.`semester` = " . $optSemester . "
				AND fm.`tahun_ajaran` = " . $optTahunAjaran;
        $command = Yii::app()->db->createCommand($sql);
        $rows2 = $command->queryAll();

        $arr2 = array();

        foreach ($rows2 as $key => $item) {
            $arr2[$item["tipe"]] = $item["nilai_desc"];
        }

        // echo "<pre>";
        // print_r($arr2);
        // echo "</pre>";

        if (!empty($_GET['reg'])) {
            $urut = $_GET['reg'];
        } else {
            $urut = "";
        }

        //ksort($arr, SORT_NUMERIC);
        // echo "<pre>";
        // print_r($arr);
        // echo "</pre>";

        $this->renderPartial('/clases/raport-siswa-uts', array('inline_style' => $inline_style, 'siswa' => $user, 'mapel' => $mapel, 'model' => $model, 'mapel1' => $mapel1, 'mapel2' => $mapel2, 'mapel3' => $mapel3, 'mapel4' => $mapel4, 'my_lesson' => $my_lesson, 'peluts' => $arr, 'peluts2' => $arr2, 'profil' => $user_data));
        // $this->renderPartial('/clases/raport-siswa-uts',array('inline_style'=>$inline_style,'siswa'=>$user,'mapel'=>$mapel,'model'=>$model, 'mapel1'=>$mapel1, 'mapel2'=>$mapel2, 'mapel3'=>$mapel3, 'mapel4'=>$mapel4,'my_lesson'=>$my_lesson,'peluts'=>$arr,'profil'=>$user_data));
    }


    public function actionAkunOrtu($id) {
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

         $template_directory = Yii::app()->theme->baseUrl;
        $template_file = Yii::app()->theme->basePath . '/css/print.bootstrap.min.css';
        $inline_style = file_get_contents($template_file);
        $icon_sprite = Yii::app()->theme->basePath . '/images/glyphicons-halflings.png';
        $inline_style = str_replace('../images/glyphicons-halflings.png', $icon_sprite, $inline_style);

        $base = Yii::app()->baseUrl;
        $user = User::model()->findByPk($id);
        $user_ortu = User::model()->findByAttributes(array('child_id' => $id));

        // echo "<pre>";
        //     print_r($user);
        // echo "</pre>";  

        // echo "<pre>";
        //     print_r($user_ortu);
        // echo "</pre>";

        $this->renderPartial('/clases/surat-akun-ortu', array('inline_style' => $inline_style, 'siswa' => $user, 'ortu' => $user_ortu));
        // $this->renderPartial('/clases/raport-siswa-uts',array('inline_style'=>$inline_style,'siswa'=>$user,'mapel'=>$mapel,'model'=>$model, 'mapel1'=>$mapel1, 'mapel2'=>$mapel2, 'mapel3'=>$mapel3, 'mapel4'=>$mapel4,'my_lesson'=>$my_lesson,'peluts'=>$arr,'profil'=>$user_data));
    }

    public function actionRaporUas($id) {
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

        $optSchoolType = Option::model()->findByAttributes(array('key_config' => 'school_name'))->value;

        $base = Yii::app()->baseUrl;
        $template_directory = Yii::app()->theme->baseUrl;
        $template_file = Yii::app()->theme->basePath . '/css/print.bootstrap.min.css';
        $inline_style = file_get_contents($template_file);
        $icon_sprite = Yii::app()->theme->basePath . '/images/glyphicons-halflings.png';
        $inline_style = str_replace('../images/glyphicons-halflings.png', $icon_sprite, $inline_style);

        $user = User::model()->findByPk($id);
        $user_data = UserProfile::model()->findByAttributes(array('user_id' => $id));
        $my_lesson = LessonMc::model()->findAll(array('condition' => 'user_id = ' . $id . ' and trash is null AND semester = ' . $optSemester . " AND year = " . $optTahunAjaran, 'order' => 'lesson_id'));
        $lesson_list = array();
        $ll = NULL;
        $mapel = NULL;
        $mapel1 = NULL;
        $mapel2 = NULL;
        $mapel3 = NULL;
        $mapel4 = NULL;

        if (!empty($my_lesson)) {
            foreach ($my_lesson as $value) {
                array_push($lesson_list, $value->lesson_id);
            }
        }

        if (!empty($lesson_list)) {
            $ll = implode(',', $lesson_list);
            $mapel = Lesson::model()->findAll(array('condition' => 'class_id = ' . $user->class_id . ' and trash is null AND semester = ' . $optSemester . " AND year = " . $optTahunAjaran));
            $mapel1 = Lesson::model()->findAll(array('condition' => 'class_id = ' . $user->class_id . ' and kelompok = 1 and moving_class = 0 and id in (' . $ll . ') and trash is null AND semester = ' . $optSemester . " AND year = " . $optTahunAjaran));
            $mapel2 = Lesson::model()->findAll(array('condition' => 'class_id = ' . $user->class_id . ' and kelompok = 2 and moving_class = 0 and id in (' . $ll . ') and trash is null AND semester = ' . $optSemester . " AND year = " . $optTahunAjaran));
            $mapel3 = Lesson::model()->findAll(array('condition' => 'class_id = ' . $user->class_id . ' and kelompok = 3 and moving_class = 0 and id in (' . $ll . ') and trash is null AND semester = ' . $optSemester . " AND year = " . $optTahunAjaran));
            $mapel4 = Lesson::model()->findAll(array('condition' => 'kelompok = 3 and moving_class = 1 and id in (' . $ll . ') AND semester = ' . $optSemester . " AND year = " . $optTahunAjaran));
        }

        $nama = str_replace(" ", "-", $user->display_name);
        $model = ClassDetail::model()->findByPk($user->class_id);

        $sql = "SELECT l.`id`,l.`name`,fm.`nilai`,fm.`nilai_desc`,fm.`tipe`,l.`kelompok`,l.`list_id`
				FROM `final_mark` as fm
				join `users` as u on u.`id` = fm.`user_id`
				join `lesson` as l on fm.`lesson_id` = l.`id`
				WHERE fm.`user_id` = " . $id . "
				AND fm.`tipe` != 'rnh'
				AND fm.`tipe` != 'uts'
				AND fm.`semester` = " . $optSemester . "
				AND fm.`tahun_ajaran` = " . $optTahunAjaran . "
				AND l.`semester` = " . $optSemester . "
				AND l.`year` = " . $optTahunAjaran . "
				AND l.`trash` is null
				order by l.`list_id`";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();

        $arr = array();

        foreach ($rows as $key => $item) {
            $arr[$item['id']]["name"] = $item["name"];
            $arr[$item['id']]["kelompok"] = $item["kelompok"];
            $arr[$item['id']]["nilai-" . $item["tipe"]] = $item["nilai"];
            $arr[$item['id']]["desc-" . $item["tipe"]] = $item["nilai_desc"];

            $sql9 = "SELECT lk.`description`
				FROM `lesson_kd` as lk
				WHERE lk.`lesson_id` = " . $item["id"] . "
				AND lk.`title` != 'KD1_ket'
				AND lk.`title` != 'KD2_ket'
				AND lk.`title` != 'KD3_ket'
				AND lk.`title` != 'KD4_ket'
				AND lk.`title` != 'KD5_ket'
				AND lk.`title` != 'KD6_ket'
				AND lk.`title` != 'KD7_ket'";
            $command9 = Yii::app()->db->createCommand($sql9);
            $rows9 = $command9->queryAll();

            if (!empty($rows9)) {
                $itung = 0;
                foreach ($rows9 as $key9 => $value9) {
                    $arr[$item["id"]]['nilai-kddescription'][$itung] = $value9['description'];
                    $itung++;
                    if (count($arr[$item["id"]]['nilai-kddescription']) > 7) {
                        unset($arr[$item["id"]]['nilai-kddescription'][$itung]);
                    }
                }
            }

            $sql10 = "SELECT lk.`description`
				FROM `lesson_kd` as lk
				WHERE lk.`lesson_id` = " . $item["id"] . "
				AND lk.`title` != 'KD1'
				AND lk.`title` != 'KD2'
				AND lk.`title` != 'KD3'
				AND lk.`title` != 'KD4'
				AND lk.`title` != 'KD5'
				AND lk.`title` != 'KD6'
				AND lk.`title` != 'KD7'";
            $command10 = Yii::app()->db->createCommand($sql10);
            $rows10 = $command10->queryAll();

            if (!empty($rows10)) {
                $itung = 0;
                foreach ($rows10 as $key10 => $value10) {
                    $arr[$item["id"]]['nilai-kddescription-ket'][$itung] = $value10['description'];
                    $itung++;
                    if (count($arr[$item["id"]]['nilai-kddescription-ket']) > 7) {
                        unset($arr[$item["id"]]['nilai-kddescription-ket'][$itung]);
                    }
                }
            }


            if ($item["tipe"] == "kd1" or $item["tipe"] == "kd2" or $item["tipe"] == "kd3" or $item["tipe"] == "kd4" or $item["tipe"] == "kd5" or $item["tipe"] == "kd6" or $item["tipe"] == "kd7") {
                $arr[$item["id"]]['rnh'][] = $item['nilai'];
                // if($item['description'] != null ){
                // 		$arr[$item["id"]]['nilai-kddescription'][]=$item['description'];
                // }
            }


            if ($item["tipe"] == "kd1_ket" or $item["tipe"] == "kd2_ket" or $item["tipe"] == "kd3_ket" or $item["tipe"] == "kd4_ket" or $item["tipe"] == "kd5_ket" or $item["tipe"] == "kd6_ket" or $item["tipe"] == "kd7_ket") {
                $arr[$item["id"]]['rnh_ket'][] = $item['nilai'];
            }


            if ($item['tipe'] == "tugas") {
                $arr[$item["id"]]['rnt'] = $item['nilai'];
            }

            if ($item['tipe'] == "uas") {
                $arr[$item["id"]]['nuas'] = $item['nilai'];
            }
        }

        foreach ($arr as $key => $value) {
            if (empty($arr[$key]['nilai-pengetahuan'])) {
                if (!empty($arr[$key]['nuas']) && !empty($arr[$key]['rnh'])) {

                    if (!empty($arr[$key]['rnt'])) {
                        $r_nh = (2 * (round(array_sum($arr[$key]['rnh']) / count($arr[$key]['rnh']))) + $arr[$key]['rnt']) / 3;
                        $arr[$key]['nilai-pengetahuan'] = round((2 * round($r_nh) + $arr[$key]['nuas']) / 3);
                    } else {
                        $r_nh = round(array_sum($arr[$key]['rnh']) / count($arr[$key]['rnh']));
                        $arr[$key]['nilai-pengetahuan'] = round((2 * round($r_nh) + $arr[$key]['nuas']) / 3);
                    }
                } else {
                    $arr[$key]['nilai-pengetahuan'] = "-";
                }
            }

            if (empty($arr[$key]['nilai-keterampilan'])) {

                if (!empty($arr[$key]['rnh_ket'])) {
                    $arr[$key]['nilai-keterampilan'] = round(array_sum($arr[$key]['rnh_ket']) / count($arr[$key]['rnh_ket']));
                } else {

                    $arr[$key]['nilai-keterampilan'] = "-";
                }
            }
        }






        $sql = "SELECT fm.`nilai_desc`,fm.`tipe`
				FROM `final_mark` as fm
				join `users` as u on u.`id` = fm.`user_id`
				WHERE fm.`user_id` = " . $id . "
				AND fm.`semester` = " . $optSemester . "
				AND fm.`tahun_ajaran` = " . $optTahunAjaran;
        $command = Yii::app()->db->createCommand($sql);
        $rows2 = $command->queryAll();

        $arr2 = array();

        foreach ($rows2 as $key => $item) {
            $arr2[$item["tipe"]] = $item["nilai_desc"];
        }

        if (!empty($_GET['reg'])) {
            $urut = $_GET['reg'];
        } else {
            $urut = "";
        }

        $prefix_paper = "";
        if (!empty($_GET['ver'])) {
            if (strtolower($_GET['ver']) == "1") {
                $prefix_paper = "-v1";
            } elseif (strtolower($_GET['ver']) == "2") {
                $prefix_paper = "-v2";
            } elseif (strtolower($_GET['ver']) == "3") {
                $prefix_paper = "-v3";
            } elseif (strtolower($_GET['ver']) == "4") {
                $prefix_paper = "-v4";
            } elseif (strtolower($_GET['ver']) == "5") {
                $prefix_paper = "-v5";
            } else {
                throw new CHttpException(404, 'The requested page does not exist.');
            }
        } else {
            $prefix_paper = "-v1";
        }
        if (!empty($_GET['ppr'])) {
            if (strtolower($_GET['ppr']) == "a4") {
                $prefix_paper = $prefix_paper . "-a4-one";
            } elseif (strtolower($_GET['ppr']) == "b5") {
                $prefix_paper = $prefix_paper . "-b5-one";
            } else {
                throw new CHttpException(404, 'The requested page does not exist.');
            }
        } else {
            $prefix_paper = $prefix_paper . "-a4-one";
        }

        if (!empty($_GET['debug'])) {
            # code...
            echo "<pre>";
            print_r($arr);
            echo "</pre>";
        } else {

            if (strpos($optSchoolType, 'SMP') !== false) {
                $this->renderPartial('/clases/raport-siswa-uas-smp' . $prefix_paper, array('inline_style' => $inline_style, 'siswa' => $user, 'mapel' => $mapel, 'model' => $model, 'mapel1' => $mapel1, 'mapel2' => $mapel2, 'mapel3' => $mapel3, 'mapel4' => $mapel4, 'my_lesson' => $my_lesson, 'peluas1' => $arr, 'peluas2' => $arr2, 'urut' => $urut, 'profil' => $user_data, 'optSchoolType' => $optSchoolType));
            } else {
                $this->renderPartial('/clases/raport-siswa-uas' . $prefix_paper, array('inline_style' => $inline_style, 'siswa' => $user, 'mapel' => $mapel, 'model' => $model, 'mapel1' => $mapel1, 'mapel2' => $mapel2, 'mapel3' => $mapel3, 'mapel4' => $mapel4, 'my_lesson' => $my_lesson, 'peluas1' => $arr, 'peluas2' => $arr2, 'urut' => $urut, 'profil' => $user_data, 'optSchoolType' => $optSchoolType));
            }
        }
    }

    public function actionRaporLkhbs($id) {
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

        $base = Yii::app()->baseUrl;
        $template_directory = Yii::app()->theme->baseUrl;
        $template_file = Yii::app()->theme->basePath . '/css/print.bootstrap.min.css';
        $inline_style = file_get_contents($template_file);
        $icon_sprite = Yii::app()->theme->basePath . '/images/glyphicons-halflings.png';
        $inline_style = str_replace('../images/glyphicons-halflings.png', $icon_sprite, $inline_style);

        $user = User::model()->findByPk($id);
        $user_data = UserProfile::model()->findByAttributes(array('user_id' => $id));
        $my_lesson = LessonMc::model()->findAll(array('condition' => 'user_id = ' . $id . ' and trash is null AND semester = ' . $optSemester . " AND year = " . $optTahunAjaran, 'order' => 'lesson_id'));
        $lesson_list = array();
        $ll = NULL;
        $mapel = NULL;
        $mapel1 = NULL;
        $mapel2 = NULL;
        $mapel3 = NULL;
        $mapel4 = NULL;

        if (!empty($my_lesson)) {
            foreach ($my_lesson as $value) {
                array_push($lesson_list, $value->lesson_id);
            }
        }

        if (!empty($lesson_list)) {
            $ll = implode(',', $lesson_list);
            $mapel = Lesson::model()->findAll(array('condition' => 'class_id = ' . $user->class_id . ' and trash is null AND semester = ' . $optSemester . " AND year = " . $optTahunAjaran));
            $mapel1 = Lesson::model()->findAll(array('condition' => 'class_id = ' . $user->class_id . ' and kelompok = 1 and moving_class = 0 and id in (' . $ll . ') and trash is null AND semester = ' . $optSemester . " AND year = " . $optTahunAjaran));
            $mapel2 = Lesson::model()->findAll(array('condition' => 'class_id = ' . $user->class_id . ' and kelompok = 2 and moving_class = 0 and id in (' . $ll . ') and trash is null AND semester = ' . $optSemester . " AND year = " . $optTahunAjaran));
            $mapel3 = Lesson::model()->findAll(array('condition' => 'class_id = ' . $user->class_id . ' and kelompok = 3 and moving_class = 0 and id in (' . $ll . ') and trash is null AND semester = ' . $optSemester . " AND year = " . $optTahunAjaran));
            $mapel4 = Lesson::model()->findAll(array('condition' => 'kelompok = 3 and moving_class = 1 and id in (' . $ll . ') AND semester = ' . $optSemester . " AND year = " . $optTahunAjaran));
        }

        $nama = str_replace(" ", "-", $user->display_name);
        $model = ClassDetail::model()->findByPk($user->class_id);

        $sql = "SELECT l.`id`,l.`name`,fm.`nilai`,fm.`nilai_desc`,fm.`tipe`,l.`kelompok`,l.`list_id`
				FROM `final_mark` as fm
				join `users` as u on u.`id` = fm.`user_id`
				join `lesson` as l on fm.`lesson_id` = l.`id`
				WHERE fm.`user_id` = " . $id . "
				AND fm.`tipe` != 'rnh'
				AND fm.`tipe` != 'uts'
				AND fm.`semester` = " . $optSemester . "
				AND fm.`tahun_ajaran` = " . $optTahunAjaran . "
				AND l.`semester` = " . $optSemester . "
				AND l.`year` = " . $optTahunAjaran . "
				AND l.`trash` is null
				order by l.`list_id`";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();

        $arr = array();

        foreach ($rows as $key => $item) {
            $arr[$item['id']]["name"] = $item["name"];
            $arr[$item['id']]["kelompok"] = $item["kelompok"];
            $arr[$item['id']]["nilai-" . $item["tipe"]] = $item["nilai"];
            $arr[$item['id']]["desc-" . $item["tipe"]] = $item["nilai_desc"];

            $sql9 = "SELECT lk.`description`
				FROM `lesson_kd` as lk
				WHERE lk.`lesson_id` = " . $item["id"] . "
				AND lk.`title` != 'KD1_ket'
				AND lk.`title` != 'KD2_ket'
				AND lk.`title` != 'KD3_ket'
				AND lk.`title` != 'KD4_ket'
				AND lk.`title` != 'KD5_ket'
				AND lk.`title` != 'KD6_ket'
				AND lk.`title` != 'KD7_ket'";
            $command9 = Yii::app()->db->createCommand($sql9);
            $rows9 = $command9->queryAll();

            if (!empty($rows9)) {
                $itung = 0;
                foreach ($rows9 as $key9 => $value9) {
                    $arr[$item["id"]]['nilai-kddescription'][$itung] = $value9['description'];
                    $itung++;
                    if (count($arr[$item["id"]]['nilai-kddescription']) > 7) {
                        unset($arr[$item["id"]]['nilai-kddescription'][$itung]);
                    }
                }
            }

            $sql10 = "SELECT lk.`description`
				FROM `lesson_kd` as lk
				WHERE lk.`lesson_id` = " . $item["id"] . "
				AND lk.`title` != 'KD1'
				AND lk.`title` != 'KD2'
				AND lk.`title` != 'KD3'
				AND lk.`title` != 'KD4'
				AND lk.`title` != 'KD5'
				AND lk.`title` != 'KD6'
				AND lk.`title` != 'KD7'";
            $command10 = Yii::app()->db->createCommand($sql10);
            $rows10 = $command10->queryAll();

            if (!empty($rows10)) {
                $itung = 0;
                foreach ($rows10 as $key10 => $value10) {
                    $arr[$item["id"]]['nilai-kddescription-ket'][$itung] = $value10['description'];
                    $itung++;
                    if (count($arr[$item["id"]]['nilai-kddescription-ket']) > 7) {
                        unset($arr[$item["id"]]['nilai-kddescription-ket'][$itung]);
                    }
                }
            }


            if ($item["tipe"] == "kd1" or $item["tipe"] == "kd2" or $item["tipe"] == "kd3" or $item["tipe"] == "kd4" or $item["tipe"] == "kd5" or $item["tipe"] == "kd6" or $item["tipe"] == "kd7") {
                $arr[$item["id"]]['rnh'][] = $item['nilai'];
                // if($item['description'] != null ){
                // 		$arr[$item["id"]]['nilai-kddescription'][]=$item['description'];
                // }
            }


            if ($item["tipe"] == "kd1_ket" or $item["tipe"] == "kd2_ket" or $item["tipe"] == "kd3_ket" or $item["tipe"] == "kd4_ket" or $item["tipe"] == "kd5_ket" or $item["tipe"] == "kd6_ket" or $item["tipe"] == "kd7_ket") {
                $arr[$item["id"]]['rnh_ket'][] = $item['nilai'];
            }


            if ($item['tipe'] == "tugas") {
                $arr[$item["id"]]['rnt'] = $item['nilai'];
            }

            if ($item['tipe'] == "uas") {
                $arr[$item["id"]]['nuas'] = $item['nilai'];
            }
        }

        foreach ($arr as $key => $value) {
            if (empty($arr[$key]['nilai-pengetahuan'])) {
                if (!empty($arr[$key]['nuas']) && !empty($arr[$key]['rnh'])) {

                    if (!empty($arr[$key]['rnt'])) {
                        $r_nh = (2 * (round(array_sum($arr[$key]['rnh']) / count($arr[$key]['rnh']))) + $arr[$key]['rnt']) / 3;
                        $arr[$key]['nilai-pengetahuan'] = round((2 * round($r_nh) + $arr[$key]['nuas']) / 3);
                    } else {
                        $r_nh = round(array_sum($arr[$key]['rnh']) / count($arr[$key]['rnh']));
                        $arr[$key]['nilai-pengetahuan'] = round((2 * round($r_nh) + $arr[$key]['nuas']) / 3);
                    }
                } else {
                    $arr[$key]['nilai-pengetahuan'] = "-";
                }
            }

            if (empty($arr[$key]['nilai-keterampilan'])) {

                if (!empty($arr[$key]['rnh_ket'])) {
                    $arr[$key]['nilai-keterampilan'] = round(array_sum($arr[$key]['rnh_ket']) / count($arr[$key]['rnh_ket']));
                } else {

                    $arr[$key]['nilai-keterampilan'] = "-";
                }
            }
        }



        // echo "<pre>";
        // print_r($arr);
        // echo "</pre>";


        $sql = "SELECT fm.`nilai_desc`,fm.`tipe`
				FROM `final_mark` as fm
				join `users` as u on u.`id` = fm.`user_id`
				WHERE fm.`user_id` = " . $id . "
				AND fm.`semester` = " . $optSemester . "
				AND fm.`tahun_ajaran` = " . $optTahunAjaran;
        $command = Yii::app()->db->createCommand($sql);
        $rows2 = $command->queryAll();

        $arr2 = array();

        foreach ($rows2 as $key => $item) {
            $arr2[$item["tipe"]] = $item["nilai_desc"];
        }

        if (!empty($_GET['reg'])) {
            $urut = $_GET['reg'];
        } else {
            $urut = "";
        }



        // echo "<pre>";
        // 	print_r($arr);
        // echo "</pre>";

           if (!empty($_GET['debug'])) {
            # code...
            echo "<pre>";
            print_r($arr);
            echo "</pre>";
        } else {

                if (isset($_GET['uts'])) {
                    $this->renderPartial('/clases/raport-siswa-uts-smpdh', array('inline_style' => $inline_style, 'siswa' => $user, 'mapel' => $mapel, 'model' => $model, 'mapel1' => $mapel1, 'mapel2' => $mapel2, 'mapel3' => $mapel3, 'mapel4' => $mapel4, 'my_lesson' => $my_lesson, 'peluas1' => $arr, 'peluas2' => $arr2, 'urut' => $urut, 'profil' => $user_data));
                } else {
                    $this->renderPartial('/clases/raport-siswa-lkhbs-v3', array('inline_style' => $inline_style, 'siswa' => $user, 'mapel' => $mapel, 'model' => $model, 'mapel1' => $mapel1, 'mapel2' => $mapel2, 'mapel3' => $mapel3, 'mapel4' => $mapel4, 'my_lesson' => $my_lesson, 'peluas1' => $arr, 'peluas2' => $arr2, 'urut' => $urut, 'profil' => $user_data));
                }
         }
    }

    public function actionRaporUtsdh($id) {
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

        $base = Yii::app()->baseUrl;
        $template_directory = Yii::app()->theme->baseUrl;
        $template_file = Yii::app()->theme->basePath . '/css/print.bootstrap.min.css';
        $inline_style = file_get_contents($template_file);
        $icon_sprite = Yii::app()->theme->basePath . '/images/glyphicons-halflings.png';
        $inline_style = str_replace('../images/glyphicons-halflings.png', $icon_sprite, $inline_style);

        $user = User::model()->findByPk($id);
        $user_data = UserProfile::model()->findByAttributes(array('user_id' => $id));
        $my_lesson = LessonMc::model()->findAll(array('condition' => 'user_id = ' . $id . ' and trash is null AND semester = ' . $optSemester . " AND year = " . $optTahunAjaran, 'order' => 'lesson_id'));
        $lesson_list = array();
        $ll = NULL;
        $mapel = NULL;
        $mapel1 = NULL;
        $mapel2 = NULL;
        $mapel3 = NULL;
        $mapel4 = NULL;

        if (!empty($my_lesson)) {
            foreach ($my_lesson as $value) {
                array_push($lesson_list, $value->lesson_id);
            }
        }

        if (!empty($lesson_list)) {
            $ll = implode(',', $lesson_list);
            $mapel = Lesson::model()->findAll(array('condition' => 'class_id = ' . $user->class_id . ' and trash is null AND semester = ' . $optSemester . " AND year = " . $optTahunAjaran));
            $mapel1 = Lesson::model()->findAll(array('condition' => 'class_id = ' . $user->class_id . ' and kelompok = 1 and moving_class = 0 and id in (' . $ll . ') and trash is null AND semester = ' . $optSemester . " AND year = " . $optTahunAjaran));
            $mapel2 = Lesson::model()->findAll(array('condition' => 'class_id = ' . $user->class_id . ' and kelompok = 2 and moving_class = 0 and id in (' . $ll . ') and trash is null AND semester = ' . $optSemester . " AND year = " . $optTahunAjaran));
            $mapel3 = Lesson::model()->findAll(array('condition' => 'class_id = ' . $user->class_id . ' and kelompok = 3 and moving_class = 0 and id in (' . $ll . ') and trash is null AND semester = ' . $optSemester . " AND year = " . $optTahunAjaran));
            $mapel4 = Lesson::model()->findAll(array('condition' => 'kelompok = 3 and moving_class = 1 and id in (' . $ll . ') AND semester = ' . $optSemester . " AND year = " . $optTahunAjaran));
        }

        $nama = str_replace(" ", "-", $user->display_name);
        $model = ClassDetail::model()->findByPk($user->class_id);

        $sql = "SELECT l.`id`,l.`name`,fm.`nilai`,fm.`nilai_desc`,fm.`tipe`,l.`kelompok`,l.`list_id`
				FROM `final_mark` as fm
				join `users` as u on u.`id` = fm.`user_id`
				join `lesson` as l on fm.`lesson_id` = l.`id`
				WHERE fm.`user_id` = " . $id . "
				AND fm.`tipe` != 'rnh'
				AND fm.`tipe` != 'uts'
				AND fm.`semester` = " . $optSemester . "
				AND fm.`tahun_ajaran` = " . $optTahunAjaran . "
				AND l.`semester` = " . $optSemester . "
				AND l.`year` = " . $optTahunAjaran . "
				AND l.`trash` is null
				order by l.`list_id`";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();

        $arr = array();

        foreach ($rows as $key => $item) {
            $arr[$item['id']]["name"] = $item["name"];
            $arr[$item['id']]["kelompok"] = $item["kelompok"];
            $arr[$item['id']]["nilai-" . $item["tipe"]] = $item["nilai"];
            $arr[$item['id']]["desc-" . $item["tipe"]] = $item["nilai_desc"];

            $sql9 = "SELECT lk.`description`
				FROM `lesson_kd` as lk
				WHERE lk.`lesson_id` = " . $item["id"] . "
				AND lk.`title` != 'KD1_ket'
				AND lk.`title` != 'KD2_ket'
				AND lk.`title` != 'KD3_ket'
				AND lk.`title` != 'KD4_ket'
				AND lk.`title` != 'KD5_ket'
				AND lk.`title` != 'KD6_ket'
				AND lk.`title` != 'KD7_ket'";
            $command9 = Yii::app()->db->createCommand($sql9);
            $rows9 = $command9->queryAll();

            if (!empty($rows9)) {
                $itung = 0;
                foreach ($rows9 as $key9 => $value9) {
                    $arr[$item["id"]]['nilai-kddescription'][$itung] = $value9['description'];
                    $itung++;
                    if (count($arr[$item["id"]]['nilai-kddescription']) > 7) {
                        unset($arr[$item["id"]]['nilai-kddescription'][$itung]);
                    }
                }
            }

            $sql10 = "SELECT lk.`description`
				FROM `lesson_kd` as lk
				WHERE lk.`lesson_id` = " . $item["id"] . "
				AND lk.`title` != 'KD1'
				AND lk.`title` != 'KD2'
				AND lk.`title` != 'KD3'
				AND lk.`title` != 'KD4'
				AND lk.`title` != 'KD5'
				AND lk.`title` != 'KD6'
				AND lk.`title` != 'KD7'";
            $command10 = Yii::app()->db->createCommand($sql10);
            $rows10 = $command10->queryAll();

            if (!empty($rows10)) {
                $itung = 0;
                foreach ($rows10 as $key10 => $value10) {
                    $arr[$item["id"]]['nilai-kddescription-ket'][$itung] = $value10['description'];
                    $itung++;
                    if (count($arr[$item["id"]]['nilai-kddescription-ket']) > 7) {
                        unset($arr[$item["id"]]['nilai-kddescription-ket'][$itung]);
                    }
                }
            }


            if ($item["tipe"] == "kd1" or $item["tipe"] == "kd2" or $item["tipe"] == "kd3" or $item["tipe"] == "kd4" or $item["tipe"] == "kd5" or $item["tipe"] == "kd6" or $item["tipe"] == "kd7") {
                $arr[$item["id"]]['rnh'][] = $item['nilai'];
                // if($item['description'] != null ){
                // 		$arr[$item["id"]]['nilai-kddescription'][]=$item['description'];
                // }
            }


            if ($item["tipe"] == "kd1_ket" or $item["tipe"] == "kd2_ket" or $item["tipe"] == "kd3_ket" or $item["tipe"] == "kd4_ket" or $item["tipe"] == "kd5_ket" or $item["tipe"] == "kd6_ket" or $item["tipe"] == "kd7_ket") {
                $arr[$item["id"]]['rnh_ket'][] = $item['nilai'];
            }


            if ($item['tipe'] == "tugas") {
                $arr[$item["id"]]['rnt'] = $item['nilai'];
            }

            if ($item['tipe'] == "uas") {
                $arr[$item["id"]]['nuas'] = $item['nilai'];
            }
        }

        foreach ($arr as $key => $value) {
            if (empty($arr[$key]['nilai-pengetahuan'])) {
                if (!empty($arr[$key]['nuas']) && !empty($arr[$key]['rnh'])) {

                    if (!empty($arr[$key]['rnt'])) {
                        $r_nh = (2 * (round(array_sum($arr[$key]['rnh']) / count($arr[$key]['rnh']))) + $arr[$key]['rnt']) / 3;
                        $arr[$key]['nilai-pengetahuan'] = round((2 * round($r_nh) + $arr[$key]['nuas']) / 3);
                    } else {
                        $r_nh = round(array_sum($arr[$key]['rnh']) / count($arr[$key]['rnh']));
                        $arr[$key]['nilai-pengetahuan'] = round((2 * round($r_nh) + $arr[$key]['nuas']) / 3);
                    }
                } else {
                    $arr[$key]['nilai-pengetahuan'] = "-";
                }
            }

            if (empty($arr[$key]['nilai-keterampilan'])) {

                if (!empty($arr[$key]['rnh_ket'])) {
                    $arr[$key]['nilai-keterampilan'] = round(array_sum($arr[$key]['rnh_ket']) / count($arr[$key]['rnh_ket']));
                } else {

                    $arr[$key]['nilai-keterampilan'] = "-";
                }
            }
        }



        // echo "<pre>";
        // print_r($arr);
        // echo "</pre>";


        $sql = "SELECT fm.`nilai_desc`,fm.`tipe`
				FROM `final_mark` as fm
				join `users` as u on u.`id` = fm.`user_id`
				WHERE fm.`user_id` = " . $id . "
				AND fm.`semester` = " . $optSemester . "
				AND fm.`tahun_ajaran` = " . $optTahunAjaran;
        $command = Yii::app()->db->createCommand($sql);
        $rows2 = $command->queryAll();

        $arr2 = array();

        foreach ($rows2 as $key => $item) {
            $arr2[$item["tipe"]] = $item["nilai_desc"];
        }

        if (!empty($_GET['reg'])) {
            $urut = $_GET['reg'];
        } else {
            $urut = "";
        }



        // echo "<pre>";
        // 	print_r($arr);
        // echo "</pre>";


        $this->renderPartial('/clases/raport-siswa-uts-smpdh', array('inline_style' => $inline_style, 'siswa' => $user, 'mapel' => $mapel, 'model' => $model, 'mapel1' => $mapel1, 'mapel2' => $mapel2, 'mapel3' => $mapel3, 'mapel4' => $mapel4, 'my_lesson' => $my_lesson, 'peluas1' => $arr, 'peluas2' => $arr2, 'urut' => $urut, 'profil' => $user_data));
    }

    public function actionRaporBiodata($id) {
        $optSemester = Option::model()->findByAttributes(array('key_config' => 'semester'))->value;
        $optTahunAjaran = Option::model()->findByAttributes(array('key_config' => 'tahun_ajaran'))->value;
        $optSchoolType = Option::model()->findByAttributes(array('key_config' => 'school_name'))->value;

        $base = Yii::app()->baseUrl;
        $template_directory = Yii::app()->theme->baseUrl;
        $template_file = Yii::app()->theme->basePath . '/css/print.bootstrap.min.css';
        $inline_style = file_get_contents($template_file);
        $icon_sprite = Yii::app()->theme->basePath . '/images/glyphicons-halflings.png';
        $inline_style = str_replace('../images/glyphicons-halflings.png', $icon_sprite, $inline_style);

        $user = User::model()->findByPk($id);
        $user_data = UserProfile::model()->findByAttributes(array('user_id' => $id));


        // echo "<pre>";
        // print_r($user_data);
        // echo "</pre>";

        if (strpos($optSchoolType, 'SMP') !== false) {
            $this->renderPartial('/clases/raport-biodata-smp', array('inline_style' => $inline_style, 'siswa' => $user, 'profil' => $user_data));
        } else {
            $this->renderPartial('/clases/raport-biodata', array('inline_style' => $inline_style, 'siswa' => $user, 'profil' => $user_data));
        }
    }

    public function actionRaporPindah($id) {
        $optSemester = Option::model()->findByAttributes(array('key_config' => 'semester'))->value;
        $optTahunAjaran = Option::model()->findByAttributes(array('key_config' => 'tahun_ajaran'))->value;
        $optSchoolType = Option::model()->findByAttributes(array('key_config' => 'school_name'))->value;

        $base = Yii::app()->baseUrl;
        $template_directory = Yii::app()->theme->baseUrl;
        $template_file = Yii::app()->theme->basePath . '/css/print.bootstrap.min.css';
        $inline_style = file_get_contents($template_file);
        $icon_sprite = Yii::app()->theme->basePath . '/images/glyphicons-halflings.png';
        $inline_style = str_replace('../images/glyphicons-halflings.png', $icon_sprite, $inline_style);

        $user = User::model()->findByPk($id);
        $user_data = UserProfile::model()->findByAttributes(array('user_id' => $id));


        // echo "<pre>";
        // print_r($user_data);
        // echo "</pre>";

        if (strpos($optSchoolType, 'SMP') !== false) {
            $this->renderPartial('/clases/raport-siswa-uas-smp' . $prefix_paper, array('inline_style' => $inline_style, 'siswa' => $user, 'mapel' => $mapel, 'model' => $model, 'mapel1' => $mapel1, 'mapel2' => $mapel2, 'mapel3' => $mapel3, 'mapel4' => $mapel4, 'my_lesson' => $my_lesson, 'peluas1' => $arr, 'peluas2' => $arr2, 'urut' => $urut, 'profil' => $user_data));
        } else {
            $this->renderPartial('/clases/raport-pindah', array('inline_style' => $inline_style, 'siswa' => $user, 'profil' => $user_data));
        }
    }

    public function actionRaporUtsall($id) {
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

        $getsiswa = User::model()->findAll(array('condition' => 'class_id = ' . $id . ''));
        $base = Yii::app()->baseUrl;

        $template_directory = Yii::app()->theme->baseUrl;
        $template_file = Yii::app()->theme->basePath . '/css/print.bootstrap.min.css';
        $inline_style = file_get_contents($template_file);
        $icon_sprite = Yii::app()->theme->basePath . '/images/glyphicons-halflings.png';
        $inline_style = str_replace('../images/glyphicons-halflings.png', $icon_sprite, $inline_style);
        $model = ClassDetail::model()->findByPk($id);
        foreach ($getsiswa as $siswa) {
            // echo "<pre>";
            // echo $siswa->id;
            //print_r($siswa);
            // echo "</pre>";



            $user[] = $siswa;
            $user_data[] = UserProfile::model()->findByAttributes(array('user_id' => $siswa->id));

            // $nama = str_replace(" ", "-" , $user->display_name);
            // $model = ClassDetail::model()->findByPk($user->class_id);
            // /*Default DOMPDF File Path*/



            $sql = "SELECT l.`id`,l.`name`,fm.`nilai`,fm.`tipe`,l.`kelompok`,l.`list_id`
				FROM `final_mark` as fm
				join `users` as u on u.`id` = fm.`user_id`
				join `lesson` as l on fm.`lesson_id` = l.`id`
				WHERE fm.`user_id` = " . $siswa->id . "
				AND fm.`semester`= " . $optSemester . "
				AND fm.`tahun_ajaran` = " . $optTahunAjaran . "
				AND l.`semester` = " . $optTahunAjaran . "
				AND l.`year` = " . $optTahunAjaran . "
				order by l.`list_id`";
            $command = Yii::app()->db->createCommand($sql);
            $rows = $command->queryAll();

            $arr = array();

            foreach ($rows as $key => $item) {
                // $item["nilai-".$item["tipe"]] = $item["nilai"];
                $arr[$item['id']]["name"] = $item["name"];
                $arr[$item['id']]["kelompok"] = $item["kelompok"];
                $arr[$item['id']]["nilai-" . $item["tipe"]] = $item["nilai"];
            }

            $data[] = $arr;

            // //ksort($arr, SORT_NUMERIC);
            // 	// echo "<pre>";
            // 	// print_r($arr);
            // 	// echo "</pre>";
        }

        $this->renderPartial('/clases/raport-siswa-uts-all', array('inline_style' => $inline_style, 'siswa' => $user, 'peluts' => $data, 'profil' => $user_data, 'model' => $model));
    }

    public function actionRaporUasall($id) {
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

        $getsiswa = User::model()->findAll(array('condition' => 'class_id = ' . $id . ''));
        $base = Yii::app()->baseUrl;

        $template_directory = Yii::app()->theme->baseUrl;
        $template_file = Yii::app()->theme->basePath . '/css/print.bootstrap.min.css';
        $inline_style = file_get_contents($template_file);
        $icon_sprite = Yii::app()->theme->basePath . '/images/glyphicons-halflings.png';
        $inline_style = str_replace('../images/glyphicons-halflings.png', $icon_sprite, $inline_style);

        $model = ClassDetail::model()->findByPk($id);
        foreach ($getsiswa as $siswa) {
            // echo "<pre>";
            // echo $siswa->id;
            //print_r($siswa);
            // echo "</pre>";



            $user[] = $siswa;
            $user_data[] = UserProfile::model()->findByAttributes(array('user_id' => $siswa->id));

            // $nama = str_replace(" ", "-" , $user->display_name);
            // $model = ClassDetail::model()->findByPk($user->class_id);
            // /*Default DOMPDF File Path*/



            $sql = "SELECT l.`id`,l.`name`,fm.`nilai`,fm.`nilai_desc`,fm.`tipe`,l.`kelompok`,l.`list_id`
					FROM `final_mark` as fm
					join `users` as u on u.`id` = fm.`user_id`
					join `lesson` as l on fm.`lesson_id` = l.`id`
					WHERE fm.`user_id` = " . $siswa->id . "
					AND fm.`tipe` != 'rnh'
					AND fm.`tipe` != 'uts'
					AND fm.`semester` = " . $optSemester . "
					AND fm.`tahun_ajaran` = " . $optTahunAjaran . "
					AND l.`semester` = " . $optSemester . "
					AND l.`year` = " . $optTahunAjaran . "
					order by l.`list_id`";
            $command = Yii::app()->db->createCommand($sql);
            $rows = $command->queryAll();

            $arr = array();

            foreach ($rows as $key => $item) {
                // $item["nilai-".$item["tipe"]] = $item["nilai"];
                $arr[$item['id']]["name"] = $item["name"];
                $arr[$item['id']]["kelompok"] = $item["kelompok"];
                $arr[$item['id']]["nilai-" . $item["tipe"]] = $item["nilai"];
                $arr[$item['id']]["desc-" . $item["tipe"]] = $item["nilai_desc"];
            }

            $data1[] = $arr;

            $sql = "SELECT fm.`nilai_desc`,fm.`tipe`
				FROM `final_mark` as fm
				join `users` as u on u.`id` = fm.`user_id`
				WHERE fm.`user_id` = " . $siswa->id . "
				AND fm.`semester` = " . $optSemester . "
				AND fm.`tahun_ajaran` = " . $optTahunAjaran;
            $command = Yii::app()->db->createCommand($sql);
            $rows2 = $command->queryAll();

            $arr2 = array();

            foreach ($rows2 as $key => $item) {
                // $item["nilai-".$item["tipe"]] = $item["nilai"];
                // $arr[$item['id']]["name"] = $item["name"];
                // $arr[$item['id']]["kelompok"] = $item["kelompok"];
                // $arr[$item['id']]["nilai-".$item["tipe"]] = $item["nilai"];
                // $leger_arr[$item["name"]]["kelompok"] = $item["kelompok"];
                // $leger_arr[$item["name"]]["nilai-".$item["tipe"]] = $item["nilai"];
                $arr2[$item["tipe"]] = $item["nilai_desc"];
            }

            $data2[] = $arr2;
            // //ksort($arr, SORT_NUMERIC);
        }
        // echo "<pre>";
        // print_r($data1);
        // echo "</pre>";
        // echo "<pre>";
        // print_r($data2);
        // echo "</pre>";

        $prefix_paper = "";
        if (!empty($_GET['ver'])) {
            if (strtolower($_GET['ver']) == "1") {
                $prefix_paper = "-v1";
            } elseif (strtolower($_GET['ver']) == "2") {
                $prefix_paper = "-v2";
            } else {
                throw new CHttpException(404, 'The requested page does not exist.');
            }
        } else {
            $prefix_paper = "-v1";
        }
        if (!empty($_GET['ppr'])) {
            if (strtolower($_GET['ppr']) == "a4") {
                $prefix_paper = $prefix_paper . "-a4-all";
            } elseif (strtolower($_GET['ppr']) == "b5") {
                $prefix_paper = $prefix_paper . "-b5-all";
            } else {
                throw new CHttpException(404, 'The requested page does not exist.');
            }
        } else {
            $prefix_paper = $prefix_paper . "-a4-all";
        }

        $this->renderPartial('/clases/raport-siswa-uas' . $prefix_paper, array('inline_style' => $inline_style, 'siswa' => $user, 'peluas1' => $data1, 'peluas2' => $data2, 'profil' => $user_data, 'model' => $model));
    }



    public function actionLegerUas($id)
    {
                if(Yii::app()->session['semester']){
                    $optSemester = Yii::app()->session['semester'];
                    $optSemester_db =Option::model()->findByAttributes(array('key_config'=>'semester'))->value;
                } else {
                    $optSemester=Option::model()->findByAttributes(array('key_config'=>'semester'))->value;
                }
                if(Yii::app()->session['tahun_ajaran']){
                    $optTahunAjaran = Yii::app()->session['tahun_ajaran'];
                    $optTahunAjaran_db =Option::model()->findByAttributes(array('key_config'=>'tahun_ajaran'))->value;
                } else {
                    $optTahunAjaran=Option::model()->findByAttributes(array('key_config'=>'tahun_ajaran'))->value;
                }

        
                    if(  $optTahunAjaran == $optTahunAjaran_db ){
                        $getsiswa = User::model()->findAll(array('condition'=>'class_id = '.$id.' and trash is null','order' => 'display_name '));
                    } else {
                        $getsiswa = ClassHistory::model()->findAll(array('condition'=>'class_id = '.$id.' and year = '.$optTahunAjaran,'order' => 'id'));
                    }           

        
        


        $base=Yii::app()->baseUrl;

        $template_directory=Yii::app()->theme->baseUrl;
        $template_file=Yii::app()->theme->basePath.'/css/print.bootstrap.min.css';
        $inline_style=file_get_contents($template_file);
        $icon_sprite = Yii::app()->theme->basePath.'/images/glyphicons-halflings.png';
        $inline_style=str_replace('../images/glyphicons-halflings.png', $icon_sprite, $inline_style);
        $model = ClassDetail::model()->findByPk($id);


        if (!empty($_GET['print'])) {
            $file="leger.xls";
            header("Content-type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename=$file");# code...
        }
        

        echo "<html>";

        echo "<div id='dvData'>";
        echo "<table border='1'>";
        echo "<tr>";
        echo "<th>";
            echo "NIS Siswa Kelas ".$getsiswa[0]->class->name;;
        echo "</th>";
        echo "<th>";
            echo "Nama Siswa Kelas ".$getsiswa[0]->class->name;;
        echo "</th>";
        echo "<th colspan='2'>";
            echo "Pendidikan Agama dan Budi Pekerti (A)";
        echo "</th>";
        echo "<th colspan='2'>";
            echo "Pendidikan Pancasila dan Kewarganegaraan (A)";
        echo "</th>";
        echo "<th colspan='2'>";
            echo "Bahasa Indonesia (A)";
        echo "</th>";
        echo "<th colspan='2'>";
            echo "Matematika (A)";
        echo "</th>";
        echo "<th colspan='2'>";
            echo "Sejarah Indonesia (A)";
        echo "</th>";
        echo "<th colspan='2'>";
            echo "Bahasa Inggris (A)";
        echo "</th>";
        echo "<th colspan='2'>";
            echo "Seni Budaya (B)";
        echo "</th>";
        echo "<th colspan='2'>";
            echo "Pendidikan Jasmani, Olah Raga, dan Kesehatan (B)";
        echo "</th>";
        echo "<th colspan='2'>";
            echo "Prakarya dan Kewirausahaan (B)";
        echo "</th>";
        echo "<th colspan='2'>";
            echo "Bahasa Sunda (B)";
        echo "</th>";
        echo "<th colspan='2'>";
            echo "Pendidikan Lingkungan Hidup (B)";
        echo "</th>";
        echo "</th>";
        echo "<th colspan='2'>";
        echo "IPS (C)";
        echo "</th>";
        echo "<th colspan='2'>";
        echo "IPA (C)";
        echo "</th>";
        echo "<th colspan='2'>";
            echo "Peminatan Matematika (C)";
        echo "</th>";
        echo "<th colspan='2'>";
            echo "Biologi (C)";
        echo "</th>";
        echo "<th colspan='2'>";
            echo "Fisika (C)";
        echo "</th>";
        echo "<th colspan='2'>";
            echo "Kimia (C)";
        echo "</th>";
        echo "<th colspan='2'>";
            echo "Geografi (C)";
        echo "</th>";
        echo "<th colspan='2'>";
            echo "Sejarah (C)";
        echo "</th>";
        echo "<th colspan='2'>";
            echo "Sosiologi (C)";
        echo "</th>";
        echo "<th colspan='2'>";
            echo "Ekonomi (C)";
        echo "</th>";
        echo "<th colspan='2'>";
            echo "Lintas Minat Bahasa Inggris  (C)";
        echo "</th>";
        echo "<th colspan='2'>";
            echo "Lintas Minat Bahasa Jepang (C)";
        echo "</th>";
        echo "<th colspan='2'>";
            echo "Lintas Minat Biologi (C)";
        echo "</th>";
        echo "<th colspan='2'>";
            echo "Lintas Minat Ekonomi (C)";
        echo "</th>";
        echo "<th colspan='2'>";
            echo "Lintas Minat Geografi (C)";
        echo "</th>";
        echo "<th colspan='2'>";
            echo "Lintas Minat Sosiologi (C)";
        echo "</th>";
        echo "<th colspan='2'>";
            echo "Lintas Minat Bahasa Indonesia (C)";
        echo "</th>";
        echo "<th colspan='2'>";
            echo "Lintas Fisika (C)";
        echo "</th>";
        echo "<th colspan='2'>";
            echo "Bahasa dan Sastra Indonesia (C)";
        echo "</th>";
        echo "<th colspan='2'>";
            echo "Bahasa dan Sastra Inggris (C)";
        echo "</th>";
        echo "<th colspan='2'>";
            echo "Antropologi (C)";
        echo "</th>";
        echo "<th colspan='2'>";
            echo "Bahasa Arab (C)";
        echo "</th>";
        echo "<th colspan='2'>";
            echo "Pendidikan Agama dan Budi Pekerti (A)";
        echo "</th>";
        echo "<th colspan='2'>";
            echo "Pendidikan Pancasila dan Kewarganegaraan (A)";
        echo "</th>";
        echo "<th colspan='2'>";
            echo "Bahasa Indonesia (A)";
        echo "</th>";
        echo "<th colspan='2'>";
            echo "Matematika (A)";
        echo "</th>";
        echo "<th colspan='2'>";
            echo "Sejarah Indonesia (A)";
        echo "</th>";
        echo "<th colspan='2'>";
            echo "Bahasa Inggris (A)";
        echo "</th>";
        echo "<th colspan='2'>";
            echo "Seni Budaya (B)";
        echo "</th>";
        echo "<th colspan='2'>";
            echo "Pendidikan Lingkungan Hidup (B)";
        echo "</th>";
        echo "<th colspan='2'>";
            echo "Pendidikan Jasmani, Olah Raga, dan Kesehatan (B)";
        echo "</th>";
        echo "<th colspan='2'>";
            echo "Prakarya dan Kewirausahaan (B)";
        echo "</th>";
        echo "<th colspan='2'>";
            echo "Bahasa Sunda (B)";
        echo "</th>";
        echo "<th colspan='2'>";
            echo "IPS (C)";
        echo "</th>";
        echo "<th colspan='2'>";
            echo "IPA (C)";
        echo "</th>";
        echo "<th colspan='2'>";
            echo "Peminatan Matematika (C)";
        echo "</th>";
        echo "<th colspan='2'>";
            echo "Biologi (C)";
        echo "</th>";
        echo "<th colspan='2'>";
            echo "Fisika (C)";
        echo "</th>";
        echo "<th colspan='2'>";
            echo "Kimia (C)";
        echo "</th>";
        echo "<th colspan='2'>";
            echo "Geografi (C)";
        echo "</th>";
        echo "<th colspan='2'>";
            echo "Sejarah (C)";
        echo "</th>";
        echo "<th colspan='2'>";
            echo "Sosiologi (C)";
        echo "</th>";
        echo "<th colspan='2'>";
            echo "Ekonomi (C)";
        echo "</th>";
        echo "<th colspan='2'>";
            echo "Lintas Minat Bahasa Inggris  (C)";
        echo "</th>";
        echo "<th colspan='2'>";
            echo "Lintas Minat Bahasa Jepang (C)";
        echo "</th>";
        echo "<th colspan='2'>";
            echo "Lintas Minat Biologi (C)";
        echo "</th>";
        echo "<th colspan='2'>";
            echo "Lintas Minat Ekonomi (C)";
        echo "</th>";
        echo "<th colspan='2'>";
            echo "Lintas Minat Geografi (C)";
        echo "</th>";
        echo "<th colspan='2'>";
            echo "Lintas Minat Sosiologi (C)";
        echo "</th>";
        echo "<th colspan='2'>";
            echo "Lintas Minat Bahasa Indonesia (C)";
        echo "</th>";
        echo "<th colspan='2'>";
            echo "Lintas Fisika (C)";
        echo "</th>";
        echo "<th colspan='2'>";
            echo "Bahasa dan Sastra Indonesia (C)";
        echo "</th>";
        echo "<th colspan='2'>";
            echo "Bahasa dan Sastra Inggris (C)";
        echo "</th>";
        echo "<th colspan='2'>";
            echo "Antropologi (C)";
        echo "</th>";
        echo "<th colspan='2'>";
            echo "Bahasa Arab (C)";
        echo "</th>";
        echo "<th colspan='2' rowspan='2'>";
            echo "Sikap Sosial - Predikat";
        echo "</th>";
        echo "<th colspan='2' rowspan='2'>";
            echo "Sikap Sosial - Deskripsi";
        echo "</th>";
        echo "<th colspan='2' rowspan='2'>";
            echo "Sikap Spritual - Predikat";
        echo "</th>";
        echo "<th colspan='2' rowspan='2'>";
            echo "Sikap Spritual - Deskripsi";
        echo "</th>";
        echo "<th colspan='2' rowspan='2'>";
            echo "Absensi Sakit";
        echo "</th>";
        echo "<th colspan='2' rowspan='2'>";
            echo "Absensi Izin";
        echo "</th>";
        echo "<th colspan='2' rowspan='2'>";
            echo "Absensi Alfa";
        echo "</th>";
        echo "<th colspan='2' rowspan='2'>";
            echo "Ekskul 1 - Nama";
        echo "</th>";
        echo "<th colspan='2' rowspan='2'>";
            echo "Ekskul 1 - Nilai";
        echo "</th>";
        echo "<th colspan='2' rowspan='2'>";
            echo "Ekskul 1 - Deskripsi";
        echo "</th>";
        echo "<th colspan='2' rowspan='2'>";
            echo "Ekskul 2 - Nama";
        echo "</th>";
        echo "<th colspan='2' rowspan='2'>";
            echo "Ekskul 2 - Nilai";
        echo "</th>";
        echo "<th colspan='2' rowspan='2'>";
            echo "Ekskul 2 - Deskripsi";
        echo "</th>";
        echo "<th colspan='2' rowspan='2'>";
            echo "Ekskul 3 - Nama";
        echo "</th>";
        echo "<th colspan='2' rowspan='2'>";
            echo "Ekskul 3 - Nilai";
        echo "</th>";
        echo "<th colspan='2' rowspan='2'>";
            echo "Ekskul 3 - Deskripsi";
        echo "</th>";
        echo "<th colspan='2' rowspan='2'>";
            echo "Prestasi 1 - Jenis Kegiatan";
        echo "</th>";
        echo "<th colspan='2' rowspan='2'>";
            echo "Prestasi 1 - Keterangan";
        echo "</th>";
        echo "<th colspan='2' rowspan='2'>";
            echo "Prestasi 2 - Jenis Kegiatan";
        echo "</th>";
        echo "<th colspan='2' rowspan='2'>";
            echo "Prestasi 2 - Keterangan";
        echo "</th>";
        echo "<th colspan='2' rowspan='2'>";
            echo "Prestasi 3 - Jenis Kegiatan";
        echo "</th>";
        echo "<th colspan='2' rowspan='2'>";
            echo "Prestasi 3 - Keterangan";
        echo "</th>";
        echo "<th colspan='2' rowspan='2'>";
            echo "Prestasi 4 - Jenis Kegiatan";
        echo "</th>";
        echo "<th colspan='2' rowspan='2'>";
            echo "Prestasi 4 - Keterangan";
        echo "</th>";
        echo "<th colspan='2' rowspan='2'>";
            echo "Catatan Wali Kelas";
        echo "</th>";


        echo "</tr>";
        echo "<tr>";
            echo "<td>";
                echo " ";
            echo "</td>";
            echo "<td>";
                echo " ";
            echo "</td>";
            echo "<td>";
                echo "P";
            echo "</td>";
            echo "<td>";
                echo "K";
            echo "</td>";
            echo "<td>";
                echo "P";
            echo "</td>";
            echo "<td>";
                echo "K";
            echo "</td>";
            echo "<td>";
                echo "P";
            echo "</td>";
            echo "<td>";
                echo "K";
            echo "</td>";
            echo "<td>";
                echo "P";
            echo "</td>";
            echo "<td>";
                echo "K";
            echo "</td>";
            echo "<td>";
                echo "P";
            echo "</td>";
            echo "<td>";
                echo "K";
            echo "</td>";
            echo "<td>";
                echo "P";
            echo "</td>";
            echo "<td>";
                echo "K";
            echo "</td>";
            echo "<td>";
                echo "P";
            echo "</td>";
            echo "<td>";
                echo "K";
            echo "</td>";
            echo "<td>";
                echo "P";
            echo "</td>";
            echo "<td>";
                echo "K";
            echo "</td>";
            echo "<td>";
                echo "P";
            echo "</td>";
            echo "<td>";
                echo "K";
            echo "</td>";
            echo "<td>";
                echo "P";
            echo "</td>";
            echo "<td>";
                echo "K";
            echo "</td>";
            echo "<td>";
                echo "P";
            echo "</td>";
            echo "<td>";
                echo "K";
            echo "</td>";
            echo "<td>";
                echo "P";
            echo "</td>";
            echo "<td>";
                echo "K";
            echo "</td>";
            echo "<td>";
                echo "P";
            echo "</td>";
            echo "<td>";
                echo "K";
            echo "</td>";
            echo "<td>";
                echo "P";
            echo "</td>";
            echo "<td>";
                echo "K";
            echo "</td>";
            echo "<td>";
                echo "P";
            echo "</td>";
            echo "<td>";
                echo "K";
            echo "</td>";
            echo "<td>";
                echo "P";
            echo "</td>";
            echo "<td>";
                echo "K";
            echo "</td>";
            echo "<td>";
                echo "P";
            echo "</td>";
            echo "<td>";
                echo "K";
            echo "</td>";
            echo "<td>";
                echo "P";
            echo "</td>";
            echo "<td>";
                echo "K";
            echo "</td>";
            echo "<td>";
                echo "P";
            echo "</td>";
            echo "<td>";
                echo "K";
            echo "</td>";
            echo "<td>";
                echo "P";
            echo "</td>";
            echo "<td>";
                echo "K";
            echo "</td>";
            echo "<td>";
                echo "P";
            echo "</td>";
            echo "<td>";
                echo "K";
            echo "</td>";
            echo "<td>";
                echo "P";
            echo "</td>";
            echo "<td>";
                echo "K";
            echo "</td>";
            echo "<td>";
                echo "P";
            echo "</td>";
            echo "<td>";
                echo "K";
            echo "</td>";
            echo "<td>";
                echo "P";
            echo "</td>";
            echo "<td>";
                echo "K";
            echo "</td>";
            echo "<td>";
                echo "P";
            echo "</td>";
            echo "<td>";
                echo "K";
            echo "</td>";
            echo "<td>";
                echo "P";
            echo "</td>";
            echo "<td>";
                echo "K";
            echo "</td>";
            echo "<td>";
                echo "P";
            echo "</td>";
            echo "<td>";
                echo "K";
            echo "</td>";
            echo "<td>";
                echo "P";
            echo "</td>";
            echo "<td>";
                echo "K";
            echo "</td>";
            echo "<td>";
                echo "P";
            echo "</td>";
            echo "<td>";
                echo "K";
            echo "</td>";
            echo "<td>";
                echo "P";
            echo "</td>";
            echo "<td>";
                echo "K";
            echo "</td>";
            echo "<td>";
                echo "P";
            echo "</td>";
            echo "<td>";
                echo "K";
            echo "</td>";
            echo "<td>";
                echo "P";
            echo "</td>";
            echo "<td>";
                echo "K";
            echo "</td>";
            echo "<td>";
                echo "P";
            echo "</td>";
            echo "<td>";
                echo "K";
            echo "</td>";
            echo "<td>";
                echo "P";
            echo "</td>";
            echo "<td>";
                echo "K";
            echo "</td>";
            echo "<td>";
                echo "P";
            echo "</td>";
            echo "<td>";
                echo "K";
            echo "</td>";
            echo "<td>";
                echo "P";
            echo "</td>";
            echo "<td>";
                echo "K";
            echo "</td>";
            echo "<td>";
                echo "P";
            echo "</td>";
            echo "<td>";
                echo "K";
            echo "</td>";
            echo "<td>";
                echo "P";
            echo "</td>";
            echo "<td>";
                echo "K";
            echo "</td>";
            echo "<td>";
                echo "P";
            echo "</td>";
            echo "<td>";
                echo "K";
            echo "</td>";
            echo "<td>";
                echo "P";
            echo "</td>";
            echo "<td>";
                echo "K";
            echo "</td>";
            echo "<td>";
                echo "P";
            echo "</td>";
            echo "<td>";
                echo "K";
            echo "</td>";
            echo "<td>";
                echo "P";
            echo "</td>";
            echo "<td>";
                echo "K";
            echo "</td>";
            echo "<td>";
                echo "P";
            echo "</td>";
            echo "<td>";
                echo "K";
            echo "</td>";
            echo "<td>";
                echo "P";
            echo "</td>";
            echo "<td>";
                echo "K";
            echo "</td>";
            echo "<td>";
                echo "P";
            echo "</td>";
            echo "<td>";
                echo "K";
            echo "</td>";
            echo "<td>";
                echo "P";
            echo "</td>";
            echo "<td>";
                echo "K";
            echo "</td>";
            echo "<td>";
                echo "P";
            echo "</td>";
            echo "<td>";
                echo "K";
            echo "</td>";
            echo "<td>";
                echo "P";
            echo "</td>";
            echo "<td>";
                echo "K";
            echo "</td>";
            echo "<td>";
                echo "P";
            echo "</td>";
            echo "<td>";
                echo "K";
            echo "</td>";
            echo "<td>";
                echo "P";
            echo "</td>";
            echo "<td>";
                echo "K";
            echo "</td>";
            echo "<td>";
                echo "P";
            echo "</td>";
            echo "<td>";
                echo "K";
            echo "</td>";
            echo "<td>";
                echo "P";
            echo "</td>";
            echo "<td>";
                echo "K";
            echo "</td>";
            echo "<td>";
                echo "P";
            echo "</td>";
            echo "<td>";
                echo "K";
            echo "</td>";
            echo "<td>";
                echo "P";
            echo "</td>";
            echo "<td>";
                echo "K";
            echo "</td>";
            echo "<td>";
                echo "P";
            echo "</td>";
            echo "<td>";
                echo "K";
            echo "</td>";
            echo "<td>";
                echo "P";
            echo "</td>";
            echo "<td>";
                echo "K";
            echo "</td>";
            echo "<td>";
                echo "P";
            echo "</td>";
            echo "<td>";
                echo "K";
            echo "</td>";
            echo "<td>";
                echo "P";
            echo "</td>";
            echo "<td>";
                echo "K";
            echo "</td>";
        echo "</tr>";

        
        foreach ($getsiswa as $siswa) {
            // echo "<pre>";
            // echo $siswa->id;
            //print_r($siswa);
            // echo "</pre>";

                if( $optTahunAjaran == $optTahunAjaran_db ){
                    //echo $siswa->display_name;
                } else {
                    $siswa = $siswa->user;
                }

         $user[]=$siswa;
         $user_data[]=UserProfile::model()->findByAttributes(array('user_id'=>$siswa->id));

        // $nama = str_replace(" ", "-" , $user->display_name);
         // $model = ClassDetail::model()->findByPk($user->class_id);
        // /*Default DOMPDF File Path*/



        $sql="SELECT l.`id`,l.`name`,fm.`nilai`,fm.`nilai_desc`,fm.`tipe`,l.`kelompok`,l.`list_id`,lk.`description`
                FROM `final_mark` as fm
                join `users` as u on u.`id` = fm.`user_id`
                join `lesson` as l on fm.`lesson_id` = l.`id`
                left join `lesson_kd` as lk on fm.`lesson_id` = lk.`lesson_id` AND fm.`tipe` = lk.`title`
                WHERE fm.`user_id` = ".$siswa->id."
                AND fm.`semester` = ".$optSemester."
                AND fm.`tahun_ajaran` = ".$optTahunAjaran."
                AND l.`semester` = ".$optSemester."
                AND l.`year` = ".$optTahunAjaran."
                AND l.`trash` is null
                order by l.`list_id`";
        $command =Yii::app()->db->createCommand($sql);
        $rows=$command->queryAll();

        $arr = array();
        $leger_arr = array();
        $rnh = array();
        $rnh_ket = array();
        $nh = 0;
        $nuas = 0;
        $npeng_m = 0;
        $rnt = 0;
        $r_ket = 0;
        $r_nh = 0;

        foreach($rows as $key => $item)
        {
           // $item["nilai-".$item["tipe"]] = $item["nilai"];
           // $arr[$item['id']]["name"] = $item["name"];
           // $arr[$item['id']]["kelompok"] = $item["kelompok"];
           // $arr[$item['id']]["nilai-".$item["tipe"]] = $item["nilai"];

            // if(!empty($leger_arr[$item["name"]]['nilai-kddescription']) && count($leger_arr[$item["name"]]['nilai-kddescription']) <7 ) {
                $sql9="SELECT lk.`description`
                FROM `lesson_kd` as lk
                WHERE lk.`lesson_id` = ".$item["id"]."
                AND lk.`title` != 'KD1_ket'
                AND lk.`title` != 'KD2_ket'
                AND lk.`title` != 'KD3_ket'
                AND lk.`title` != 'KD4_ket'
                AND lk.`title` != 'KD5_ket'
                AND lk.`title` != 'KD6_ket'
                AND lk.`title` != 'KD7_ket'";
                $command9 =Yii::app()->db->createCommand($sql9);
                $rows9=$command9->queryAll();

                if(!empty($rows9)){
                    $itung = 0;
                    foreach ($rows9 as $key9 => $value9) {
                        $leger_arr[$item["name"]]['nilai-kddescription'][$itung] = $value9['description'];
                        $itung++;
                        if(count($leger_arr[$item["name"]]['nilai-kddescription']) > 7 ) {
                            unset($leger_arr[$item["name"]]['nilai-kddescription'][$itung]);
                        }
                    }
                }
            // }

           $leger_arr[$item["name"]]["kelompok"] = $item["kelompok"];
           $leger_arr[$item["name"]]["nilai-".$item["tipe"]] = $item["nilai"];
           $leger_arr[$item['name']]["desc-".$item["tipe"]] = $item["nilai_desc"];

           if ($item["tipe"] == "kd1" or $item["tipe"] == "kd2" or $item["tipe"] == "kd3" or $item["tipe"] == "kd4" or $item["tipe"] == "kd5" or $item["tipe"] == "kd6" or  $item["tipe"] == "kd7" )
            { 
                $leger_arr[$item["name"]]['rnh'][]=$item['nilai'];
                // if($item['description'] != null ){
                //      $leger_arr[$item["name"]]['nilai-kddescription'][]=$item['description'];
                // }
            }   


            if ($item["tipe"] == "kd1_ket" or $item["tipe"] == "kd2_ket" or $item["tipe"] == "kd3_ket" or $item["tipe"] == "kd4_ket" or $item["tipe"] == "kd5_ket" or $item["tipe"] == "kd6_ket" or  $item["tipe"] == "kd7_ket" )
            { 
                $leger_arr[$item["name"]]['rnh_ket'][]=$item['nilai'];
            }   


            if ($item['tipe'] == "tugas"){
                $leger_arr[$item["name"]]['rnt'] = $item['nilai'];
            }

            if ($item['tipe'] == "uas"){
                $leger_arr[$item["name"]]['nuas'] = $item['nilai'];
            }


        }

    


        $data[] = $arr;

        // //ksort($arr, SORT_NUMERIC);


                

                foreach ($leger_arr as $key => $value) {
                    if (empty($leger_arr[$key]['nilai-pengetahuan'])) { 
                        if (!empty($leger_arr[$key]['nuas']) && !empty($leger_arr[$key]['rnh']) )  {
                            
                            if(!empty($leger_arr[$key]['rnt'])){
                                $r_nh = (2*(round(array_sum($leger_arr[$key]['rnh']) / count($leger_arr[$key]['rnh']))) + $leger_arr[$key]['rnt'])/3;   
                                $leger_arr[$key]['nilai-pengetahuan']  = round((2*round($r_nh)+$leger_arr[$key]['nuas'])/3);
                            } else {
                                $r_nh = round(array_sum($leger_arr[$key]['rnh']) / count($leger_arr[$key]['rnh']));
                                $leger_arr[$key]['nilai-pengetahuan']  = round((2*round($r_nh)+$leger_arr[$key]['nuas'])/3);
                            }
                        } else {
                            $leger_arr[$key]['nilai-pengetahuan']  = "-";

                        }
                    }   

                    if (empty($leger_arr[$key]['nilai-keterampilan'])) {

                        if (!empty($leger_arr[$key]['rnh_ket']))  {
                            $leger_arr[$key]['nilai-keterampilan'] = round(array_sum($leger_arr[$key]['rnh_ket']) / count($leger_arr[$key]['rnh_ket']));
                                                

                            } else {

                                $leger_arr[$key]['nilai-keterampilan'] = "-";
                            }
                    }   


                    if (!empty($leger_arr[$key]['desc-desc_pengetahuan'])) {
                        $leger_arr[$key]['nilai-kddescription'] = array();
                        $leger_arr[$key]['nilai-kddescription'][] = $leger_arr[$key]['desc-desc_pengetahuan'];
                    }

                    if (!empty($leger_arr[$key]['desc-desc_keterampilan'])) {
                        $leger_arr[$key]['nilai-kddescription_ket'][] = $leger_arr[$key]['desc-desc_keterampilan'];
                    }

                    // if (empty($leger_arr[$key]['desc-desc_keterampilan'])) { 
                    //  $leger_arr[$key]['nilai-kddescriptionket'] = $leger_arr[$key]['desc-desc_keterampilan'];
                    // }        


                }

                // echo "<pre>";
                // print_r($leger_arr);
                // echo "</pre>";



            echo "<tr>";
            echo "<td>";
                echo $siswa->username;
            echo "</td>";
            echo "<td>";
                echo $siswa->display_name;
            echo "</td>";
            echo "<td>";
                if (!empty($leger_arr['Pendidikan Agama dan Budi Pekerti']['nilai-pengetahuan'])) {
                    echo $leger_arr['Pendidikan Agama dan Budi Pekerti']['nilai-pengetahuan'];
                } else {
                    echo "-";
                }
            echo "</td>";
            echo "<td>";
                if (!empty($leger_arr['Pendidikan Agama dan Budi Pekerti']['nilai-keterampilan'])) {
                    echo $leger_arr['Pendidikan Agama dan Budi Pekerti']['nilai-keterampilan'];
                } else {
                    echo "-";
                }
            echo "</td>";

            echo "<td>";
                if (!empty($leger_arr['Pendidikan Pancasila dan Kewarganegaraan']['nilai-pengetahuan'])) {
                    echo $leger_arr['Pendidikan Pancasila dan Kewarganegaraan']['nilai-pengetahuan'];
                } else {
                    echo "-";
                }
            echo "</td>";
            echo "<td>";
                if (!empty($leger_arr['Pendidikan Pancasila dan Kewarganegaraan']['nilai-keterampilan'])) {
                    echo $leger_arr['Pendidikan Pancasila dan Kewarganegaraan']['nilai-keterampilan'];
                } else {
                    echo "-";
                }
            echo "</td>";

            echo "<td>";
                if (!empty($leger_arr['Bahasa Indonesia']['nilai-pengetahuan'])) {
                    echo $leger_arr['Bahasa Indonesia']['nilai-pengetahuan'];
                } else {
                    echo "-";
                }
            echo "</td>";
            echo "<td>";
                if (!empty($leger_arr['Bahasa Indonesia']['nilai-keterampilan'])) {
                    echo $leger_arr['Bahasa Indonesia']['nilai-keterampilan'];
                } else {
                    echo "-";
                }
            echo "</td>";

            echo "<td>";
                if (!empty($leger_arr['Matematika']['nilai-pengetahuan'])) {
                    echo $leger_arr['Matematika']['nilai-pengetahuan'];
                } else {
                    echo "-";
                }
            echo "</td>";
            echo "<td>";
                if (!empty($leger_arr['Matematika']['nilai-keterampilan'])) {
                    echo $leger_arr['Matematika']['nilai-keterampilan'];
                } else {
                    echo "-";
                }
            echo "</td>";

            echo "<td>";
                if (!empty($leger_arr['Sejarah Indonesia']['nilai-pengetahuan'])) {
                    echo $leger_arr['Sejarah Indonesia']['nilai-pengetahuan'];
                } else {
                    echo "-";
                }
            echo "</td>";
            echo "<td>";
                if (!empty($leger_arr['Sejarah Indonesia']['nilai-keterampilan'])) {
                    echo $leger_arr['Sejarah Indonesia']['nilai-keterampilan'];
                } else {
                    echo "-";
                }
            echo "</td>";

            echo "<td>";
                if (!empty($leger_arr['Bahasa Inggris']['nilai-pengetahuan'])) {
                    echo $leger_arr['Bahasa Inggris']['nilai-pengetahuan'];
                } else {
                    echo "-";
                }
            echo "</td>";
            echo "<td>";
                if (!empty($leger_arr['Bahasa Inggris']['nilai-keterampilan'])) {
                    echo $leger_arr['Bahasa Inggris']['nilai-keterampilan'];
                } else {
                    echo "-";
                }
            echo "</td>";

            echo "<td>";
                if (!empty($leger_arr['Seni Budaya']['nilai-pengetahuan'])) {
                    echo $leger_arr['Seni Budaya']['nilai-pengetahuan'];
                } else {
                    echo "-";
                }
            echo "</td>";
            echo "<td>";
                if (!empty($leger_arr['Seni Budaya']['nilai-keterampilan'])) {
                    echo $leger_arr['Seni Budaya']['nilai-keterampilan'];
                } else {
                    echo "-";
                }
            echo "</td>";

            echo "<td>";
                if (!empty($leger_arr['Pendidikan Jasmani, Olah Raga, dan Kesehatan']['nilai-pengetahuan'])) {
                    echo $leger_arr['Pendidikan Jasmani, Olah Raga, dan Kesehatan']['nilai-pengetahuan'];
                } else {
                    echo "-";
                }
            echo "</td>";
            echo "<td>";
                if (!empty($leger_arr['Pendidikan Jasmani, Olah Raga, dan Kesehatan']['nilai-keterampilan'])) {
                    echo $leger_arr['Pendidikan Jasmani, Olah Raga, dan Kesehatan']['nilai-keterampilan'];
                } else {
                    echo "-";
                }
            echo "</td>";

            echo "<td>";
                if (!empty($leger_arr['Prakarya dan Kewirausahaan']['nilai-pengetahuan'])) {
                    echo $leger_arr['Prakarya dan Kewirausahaan']['nilai-pengetahuan'];
                } else {
                    echo "-";
                }
            echo "</td>";
            echo "<td>";
                if (!empty($leger_arr['Prakarya dan Kewirausahaan']['nilai-keterampilan'])) {
                    echo $leger_arr['Prakarya dan Kewirausahaan']['nilai-keterampilan'];
                } else {
                    echo "-";
                }
            echo "</td>";

            echo "<td>";
                if (!empty($leger_arr['Bahasa Sunda']['nilai-pengetahuan'])) {
                    echo $leger_arr['Bahasa Sunda']['nilai-pengetahuan'];
                } else {
                    echo "-";
                }
            echo "</td>";
            echo "<td>";
                if (!empty($leger_arr['Bahasa Sunda']['nilai-keterampilan'])) {
                    echo $leger_arr['Bahasa Sunda']['nilai-keterampilan'];
                } else {
                    echo "-";
                }
            echo "</td>";

            echo "<td>";
                if (!empty($leger_arr['Pendidikan Lingkungan Hidup']['nilai-pengetahuan'])) {
                    echo $leger_arr['Pendidikan Lingkungan Hidup']['nilai-pengetahuan'];
                } else {
                    echo "-";
                }
            echo "</td>";
            echo "<td>";
                if (!empty($leger_arr['Pendidikan Lingkungan Hidup']['nilai-keterampilan'])) {
                    echo $leger_arr['Pendidikan Lingkungan Hidup']['nilai-keterampilan'];
                } else {
                    echo "-";
                }
            echo "</td>";


             echo "<td>";
                if (!empty($leger_arr['Ilmu Pengetahuan Sosial']['nilai-pengetahuan'])) {
                    echo $leger_arr['Ilmu Pengetahuan Sosial']['nilai-pengetahuan'];
                } else {
                    echo "-";
                }
            echo "</td>";
            echo "<td>";
                if (!empty($leger_arr['Ilmu Pengetahuan Sosial']['nilai-keterampilan'])) {
                    echo $leger_arr['Ilmu Pengetahuan Sosial']['nilai-keterampilan'];
                } else {
                    echo "-";
                }
            echo "</td>";


             echo "<td>";
                if (!empty($leger_arr['Ilmu Pengetahuan Alam']['nilai-pengetahuan'])) {
                    echo $leger_arr['Ilmu Pengetahuan Alam']['nilai-pengetahuan'];
                } else {
                    echo "-";
                }
            echo "</td>";
            echo "<td>";
                if (!empty($leger_arr['Ilmu Pengetahuan Alam']['nilai-keterampilan'])) {
                    echo $leger_arr['Ilmu Pengetahuan Alam']['nilai-keterampilan'];
                } else {
                    echo "-";
                }
            echo "</td>";



            echo "<td>";
                if (!empty($leger_arr['Peminatan Matematika']['nilai-pengetahuan'])) {
                    echo $leger_arr['Peminatan Matematika']['nilai-pengetahuan'];
                } else {
                    echo "-";
                }
            echo "</td>";
            echo "<td>";
                if (!empty($leger_arr['Peminatan Matematika']['nilai-keterampilan'])) {
                    echo $leger_arr['Peminatan Matematika']['nilai-keterampilan'];
                } else {
                    echo "-";
                }
            echo "</td>";

            echo "<td>";
                if (!empty($leger_arr['Biologi']['nilai-pengetahuan'])) {
                    echo $leger_arr['Biologi']['nilai-pengetahuan'];
                } else {
                    echo "-";
                }
            echo "</td>";
            echo "<td>";
                if (!empty($leger_arr['Biologi']['nilai-keterampilan'])) {
                    echo $leger_arr['Biologi']['nilai-keterampilan'];
                } else {
                    echo "-";
                }
            echo "</td>";

            echo "<td>";
                if (!empty($leger_arr['Fisika']['nilai-pengetahuan'])) {
                    echo $leger_arr['Fisika']['nilai-pengetahuan'];
                } else {
                    echo "-";
                }
            echo "</td>";
            echo "<td>";
                if (!empty($leger_arr['Fisika']['nilai-keterampilan'])) {
                    echo $leger_arr['Fisika']['nilai-keterampilan'];
                } else {
                    echo "-";
                }
            echo "</td>";

            echo "<td>";
                if (!empty($leger_arr['Kimia']['nilai-pengetahuan'])) {
                    echo $leger_arr['Kimia']['nilai-pengetahuan'];
                } else {
                    echo "-";
                }
            echo "</td>";
            echo "<td>";
                if (!empty($leger_arr['Kimia']['nilai-keterampilan'])) {
                    echo $leger_arr['Kimia']['nilai-keterampilan'];
                } else {
                    echo "-";
                }
            echo "</td>";

            echo "<td>";
                if (!empty($leger_arr['Geografi']['nilai-pengetahuan'])) {
                    echo $leger_arr['Geografi']['nilai-pengetahuan'];
                } else {
                    echo "-";
                }
            echo "</td>";
            echo "<td>";
                if (!empty($leger_arr['Geografi']['nilai-keterampilan'])) {
                    echo $leger_arr['Geografi']['nilai-keterampilan'];
                } else {
                    echo "-";
                }
            echo "</td>";

            echo "<td>";
                if (!empty($leger_arr['Sejarah']['nilai-pengetahuan'])) {
                    echo $leger_arr['Sejarah']['nilai-pengetahuan'];
                } else {
                    echo "-";
                }
            echo "</td>";
            echo "<td>";
                if (!empty($leger_arr['Sejarah']['nilai-keterampilan'])) {
                    echo $leger_arr['Sejarah']['nilai-keterampilan'];
                } else {
                    echo "-";
                }
            echo "</td>";

            echo "<td>";
                if (!empty($leger_arr['Sosiologi']['nilai-pengetahuan'])) {
                    echo $leger_arr['Sosiologi']['nilai-pengetahuan'];
                } else {
                    echo "-";
                }
            echo "</td>";
            echo "<td>";
                if (!empty($leger_arr['Sosiologi']['nilai-keterampilan'])) {
                    echo $leger_arr['Sosiologi']['nilai-keterampilan'];
                } else {
                    echo "-";
                }
            echo "</td>";

            echo "<td>";
                if (!empty($leger_arr['Ekonomi']['nilai-pengetahuan'])) {
                    echo $leger_arr['Ekonomi']['nilai-pengetahuan'];
                } else {
                    echo "-";
                }
            echo "</td>";
            echo "<td>";
                if (!empty($leger_arr['Ekonomi']['nilai-keterampilan'])) {
                    echo $leger_arr['Ekonomi']['nilai-keterampilan'];
                } else {
                    echo "-";
                }
            echo "</td>";

            echo "<td>";
                if (!empty($leger_arr['Lintas Minat Bahasa Inggris']['nilai-pengetahuan'])) {
                    echo $leger_arr['Lintas Minat Bahasa Inggris']['nilai-pengetahuan'];
                } else {
                    echo "-";
                }
            echo "</td>";
            echo "<td>";
                if (!empty($leger_arr['Lintas Minat Bahasa Inggris']['nilai-keterampilan'])) {
                    echo $leger_arr['Lintas Minat Bahasa Inggris']['nilai-keterampilan'];
                } else {
                    echo "-";
                }
            echo "</td>";


            echo "<td>";
                if (!empty($leger_arr['Lintas Minat Bahasa Jepang']['nilai-pengetahuan'])) {
                    echo $leger_arr['Lintas Minat Bahasa Jepang']['nilai-pengetahuan'];
                } else {
                    echo "-";
                }
            echo "</td>";
            echo "<td>";
                if (!empty($leger_arr['Lintas Minat Bahasa Jepang']['nilai-keterampilan'])) {
                    echo $leger_arr['Lintas Minat Bahasa Jepang']['nilai-keterampilan'];
                } else {
                    echo "-";
                }
            echo "</td>";

            echo "<td>";
                if (!empty($leger_arr['Lintas Minat Biologi']['nilai-pengetahuan'])) {
                    echo $leger_arr['Lintas Minat Biologi']['nilai-pengetahuan'];
                } else {
                    echo "-";
                }
            echo "</td>";
            echo "<td>";
                if (!empty($leger_arr['Lintas Minat Biologi']['nilai-keterampilan'])) {
                    echo $leger_arr['Lintas Minat Biologi']['nilai-keterampilan'];
                } else {
                    echo "-";
                }
            echo "</td>";

            echo "<td>";
                if (!empty($leger_arr['Lintas Minat Ekonomi']['nilai-pengetahuan'])) {
                    echo $leger_arr['Lintas Minat Ekonomi']['nilai-pengetahuan'];
                } else {
                    echo "-";
                }
            echo "</td>";
            echo "<td>";
                if (!empty($leger_arr['Lintas Minat Ekonomi']['nilai-keterampilan'])) {
                    echo $leger_arr['Lintas Minat Ekonomi']['nilai-keterampilan'];
                } else {
                    echo "-";
                }
            echo "</td>";

            echo "<td>";
                if (!empty($leger_arr['Lintas Minat Geografi']['nilai-pengetahuan'])) {
                    echo $leger_arr['Lintas Minat Geografi']['nilai-pengetahuan'];
                } else {
                    echo "-";
                }
            echo "</td>";
            echo "<td>";
                if (!empty($leger_arr['Lintas Minat Geografi']['nilai-keterampilan'])) {
                    echo $leger_arr['Lintas Minat Geografi']['nilai-keterampilan'];
                } else {
                    echo "-";
                }
            echo "</td>";

            echo "<td>";
                if (!empty($leger_arr['Lintas Minat Sosiologi']['nilai-pengetahuan'])) {
                    echo $leger_arr['Lintas Minat Sosiologi']['nilai-pengetahuan'];
                } else {
                    echo "-";
                }
            echo "</td>";
            echo "<td>";
                if (!empty($leger_arr['Lintas Minat Sosiologi']['nilai-keterampilan'])) {
                    echo $leger_arr['Lintas Minat Sosiologi']['nilai-keterampilan'];
                } else {
                    echo "-";
                }
            echo "</td>";

            echo "<td>";
                if (!empty($leger_arr['Lintas Minat Bahasa Indonesia']['nilai-pengetahuan'])) {
                    echo $leger_arr['Lintas Minat Bahasa Indonesia']['nilai-pengetahuan'];
                } else {
                    echo "-";
                }
            echo "</td>";
            echo "<td>";
                if (!empty($leger_arr['Lintas Minat Bahasa Indonesia']['nilai-keterampilan'])) {
                    echo $leger_arr['Lintas Minat Bahasa Indonesia']['nilai-keterampilan'];
                } else {
                    echo "-";
                }
            echo "</td>";

            echo "<td>";
                if (!empty($leger_arr['Lintas Minat Fisika']['nilai-pengetahuan'])) {
                    echo $leger_arr['Lintas Minat Fisika']['nilai-pengetahuan'];
                } else {
                    echo "-";
                }
            echo "</td>";
            echo "<td>";
                if (!empty($leger_arr['Lintas Minat Fisika']['nilai-keterampilan'])) {
                    echo $leger_arr['Lintas Minat Fisika']['nilai-keterampilan'];
                } else {
                    echo "-";
                }
            echo "</td>";

            echo "<td>";
                if (!empty($leger_arr['Bahasa dan Sastra Indonesia']['nilai-pengetahuan'])) {
                    echo $leger_arr['Bahasa dan Sastra Indonesia']['nilai-pengetahuan'];
                } else {
                    echo "-";
                }
            echo "</td>";
            echo "<td>";
                if (!empty($leger_arr['Bahasa dan Sastra Indonesia']['nilai-keterampilan'])) {
                    echo $leger_arr['Bahasa dan Sastra Indonesia']['nilai-keterampilan'];
                } else {
                    echo "-";
                }
            echo "</td>";


            echo "<td>";
                if (!empty($leger_arr['Bahasa dan Sastra Inggris']['nilai-pengetahuan'])) {
                    echo $leger_arr['Bahasa dan Sastra Inggris']['nilai-pengetahuan'];
                } else {
                    echo "-";
                }
            echo "</td>";
            echo "<td>";
                if (!empty($leger_arr['Bahasa dan Sastra Inggris']['nilai-keterampilan'])) {
                    echo $leger_arr['Bahasa dan Sastra Inggris']['nilai-keterampilan'];
                } else {
                    echo "-";
                }
            echo "</td>";

            echo "<td>";
                if (!empty($leger_arr['Antropologi']['nilai-pengetahuan'])) {
                    echo $leger_arr['Antropologi']['nilai-pengetahuan'];
                } else {
                    echo "-";
                }
            echo "</td>";
            echo "<td>";
                if (!empty($leger_arr['Antropologi']['nilai-keterampilan'])) {
                    echo $leger_arr['Antropologi']['nilai-keterampilan'];
                } else {
                    echo "-";
                }
            echo "</td>";

            echo "<td>";
                if (!empty($leger_arr['Bahasa Arab']['nilai-pengetahuan'])) {
                    echo $leger_arr['Bahasa Arab']['nilai-pengetahuan'];
                } else {
                    echo "-";
                }
            echo "</td>";
            echo "<td>";
                if (!empty($leger_arr['Bahasa Arab']['nilai-keterampilan'])) {
                    echo $leger_arr['Bahasa Arab']['nilai-keterampilan'];
                } else {
                    echo "-";
                }
            echo "</td>";


            echo "<td>";
                if (!empty($leger_arr['Pendidikan Agama dan Budi Pekerti']['nilai-kddescription'])) {
                    echo implode(",",$leger_arr['Pendidikan Agama dan Budi Pekerti']['nilai-kddescription']);
                } else {
                    echo "-";
                }
            echo "</td>";
            echo "<td>";
                if (!empty($leger_arr['Pendidikan Agama dan Budi Pekerti']['nilai-kddescription_ket'])) {
                    echo implode(",",$leger_arr['Pendidikan Agama dan Budi Pekerti']['nilai-kddescription_ket']);
                } else {
                    echo "-";
                }
            echo "</td>";

            echo "<td>";
                if (!empty($leger_arr['Pendidikan Pancasila dan Kewarganegaraan']['nilai-kddescription'])) {
                    echo implode(",",$leger_arr['Pendidikan Pancasila dan Kewarganegaraan']['nilai-kddescription']);
                } else {
                    echo "-";
                }
            echo "</td>";
            echo "<td>";
                if (!empty($leger_arr['Pendidikan Pancasila dan Kewarganegaraan']['nilai-kddescription_ket'])) {
                    echo implode(",",$leger_arr['Pendidikan Pancasila dan Kewarganegaraan']['nilai-kddescription_ket']);
                } else {
                    echo "-";
                }
            echo "</td>";

            echo "<td>";
                if (!empty($leger_arr['Bahasa Indonesia']['nilai-kddescription'])) {
                    echo implode(",",$leger_arr['Bahasa Indonesia']['nilai-kddescription']);
                } else {
                    echo "-";
                }
            echo "</td>";
            echo "<td>";
                if (!empty($leger_arr['Bahasa Indonesia']['nilai-kddescription_ket'])) {
                    echo implode(",",$leger_arr['Bahasa Indonesia']['nilai-kddescription_ket']);
                } else {
                    echo "-";
                }
            echo "</td>";

            echo "<td>";
                if (!empty($leger_arr['Matematika']['nilai-kddescription'])) {
                    echo implode(",",$leger_arr['Matematika']['nilai-kddescription']);
                } else {
                    echo "-";
                }
            echo "</td>";
            echo "<td>";
                if (!empty($leger_arr['Matematika']['nilai-kddescription_ket'])) {
                    echo implode(",",$leger_arr['Matematika']['nilai-kddescription_ket']);
                } else {
                    echo "-";
                }
            echo "</td>";

            echo "<td>";
                if (!empty($leger_arr['Sejarah Indonesia']['nilai-kddescription'])) {
                    echo implode(",",$leger_arr['Sejarah Indonesia']['nilai-kddescription']);
                } else {
                    echo "-";
                }
            echo "</td>";
            echo "<td>";
                if (!empty($leger_arr['Sejarah Indonesia']['nilai-kddescription_ket'])) {
                    echo implode(",",$leger_arr['Sejarah Indonesia']['nilai-kddescription_ket']);
                } else {
                    echo "-";
                }
            echo "</td>";

            echo "<td>";
                if (!empty($leger_arr['Bahasa Inggris']['nilai-kddescription'])) {
                    echo implode(",",$leger_arr['Bahasa Inggris']['nilai-kddescription']);
                } else {
                    echo "-";
                }
            echo "</td>";
            echo "<td>";
                if (!empty($leger_arr['Bahasa Inggris']['nilai-kddescription_ket'])) {
                    echo implode(",",$leger_arr['Bahasa Inggris']['nilai-kddescription_ket']);
                } else {
                    echo "-";
                }
            echo "</td>";

            echo "<td>";
                if (!empty($leger_arr['Seni Budaya']['nilai-kddescription'])) {
                    echo implode(",",$leger_arr['Seni Budaya']['nilai-kddescription']);
                } else {
                    echo "-";
                }
            echo "</td>";
            echo "<td>";
                if (!empty($leger_arr['Seni Budaya']['nilai-kddescription_ket'])) {
                    echo implode(",",$leger_arr['Seni Budaya']['nilai-kddescription_ket']);
                } else {
                    echo "-";
                }
            echo "</td>";

            echo "<td>";
                if (!empty($leger_arr['Pendidikan Lingkungan Hidup']['nilai-kddescription'])) {
                    echo implode(",",$leger_arr['Pendidikan Lingkungan Hidup']['nilai-kddescription']);
                } else {
                    echo "-";
                }
            echo "</td>";
            echo "<td>";
                if (!empty($leger_arr['Pendidikan Lingkungan Hidup']['nilai-kddescription_ket'])) {
                    echo implode(",",$leger_arr['Pendidikan Lingkungan Hidup']['nilai-kddescription_ket']);
                } else {
                    echo "-";
                }
            echo "</td>";

            echo "<td>";
                if (!empty($leger_arr['Pendidikan Jasmani, Olah Raga, dan Kesehatan']['nilai-kddescription'])) {
                    echo implode(",",$leger_arr['Pendidikan Jasmani, Olah Raga, dan Kesehatan']['nilai-kddescription']);
                } else {
                    echo "-";
                }
            echo "</td>";
            echo "<td>";
                if (!empty($leger_arr['Pendidikan Jasmani, Olah Raga, dan Kesehatan']['nilai-kddescription_ket'])) {
                    echo implode(",",$leger_arr['Pendidikan Jasmani, Olah Raga, dan Kesehatan']['nilai-kddescription_ket']);
                } else {
                    echo "-";
                }
            echo "</td>";

            echo "<td>";
                if (!empty($leger_arr['Prakarya dan Kewirausahaan']['nilai-kddescription'])) {
                    echo implode(",",$leger_arr['Prakarya dan Kewirausahaan']['nilai-kddescription']);
                } else {
                    echo "-";
                }
            echo "</td>";
            echo "<td>";
                if (!empty($leger_arr['Prakarya dan Kewirausahaan']['nilai-kddescription_ket'])) {
                    echo implode(",",$leger_arr['Prakarya dan Kewirausahaan']['nilai-kddescription_ket']);
                } else {
                    echo "-";
                }
            echo "</td>";

            echo "<td>";
                if (!empty($leger_arr['Bahasa Sunda']['nilai-kddescription'])) {
                    echo implode(",",$leger_arr['Bahasa Sunda']['nilai-kddescription']);
                } else {
                    echo "-";
                }
            echo "</td>";
            echo "<td>";
                if (!empty($leger_arr['Bahasa Sunda']['nilai-kddescription_ket'])) {
                    echo implode(",",$leger_arr['Bahasa Sunda']['nilai-kddescription_ket']);
                } else {
                    echo "-";
                }
            echo "</td>";

             echo "<td>";
                if (!empty($leger_arr['Ilmu Pengetahuan Sosial']['nilai-kddescription'])) {
                    echo implode(",",$leger_arr['Ilmu Pengetahuan Sosial']['nilai-kddescription']);
                } else {
                    echo "-";
                }
            echo "</td>";
            echo "<td>";
                if (!empty($leger_arr['Ilmu Pengetahuan Sosial']['nilai-kddescription_ket'])) {
                    echo implode(",",$leger_arr['Ilmu Pengetahuan Sosial']['nilai-kddescription_ket']);
                } else {
                    echo "-";
                }
            echo "</td>";

            echo "<td>";
                if (!empty($leger_arr['Ilmu Pengetahuan Alam']['nilai-kddescription'])) {
                    echo implode(",",$leger_arr['Ilmu Pengetahuan Alam']['nilai-kddescription']);
                } else {
                    echo "-";
                }
            echo "</td>";
            echo "<td>";
                if (!empty($leger_arr['Ilmu Pengetahuan Alam']['nilai-kddescription_ket'])) {
                    echo implode(",",$leger_arr['Ilmu Pengetahuan Alam']['nilai-kddescription_ket']);
                } else {
                    echo "-";
                }
            echo "</td>";

            echo "<td>";
                if (!empty($leger_arr['Peminatan Matematika']['nilai-kddescription'])) {
                    echo implode(",",$leger_arr['Peminatan Matematika']['nilai-kddescription']);
                } else {
                    echo "-";
                }
            echo "</td>";
            echo "<td>";
                if (!empty($leger_arr['Peminatan Matematika']['nilai-kddescription_ket'])) {
                    echo implode(",",$leger_arr['Peminatan Matematika']['nilai-kddescription_ket']);
                } else {
                    echo "-";
                }
            echo "</td>";

            echo "<td>";
                if (!empty($leger_arr['Biologi']['nilai-kddescription'])) {
                    echo implode(",",$leger_arr['Biologi']['nilai-kddescription']);
                } else {
                    echo "-";
                }
            echo "</td>";
            echo "<td>";
                if (!empty($leger_arr['Biologi']['nilai-kddescription_ket'])) {
                    echo implode(",",$leger_arr['Biologi']['nilai-kddescription_ket']);
                } else {
                    echo "-";
                }
            echo "</td>";

            echo "<td>";
                if (!empty($leger_arr['Fisika']['nilai-kddescription'])) {
                    echo implode(",",$leger_arr['Fisika']['nilai-kddescription']);
                } else {
                    echo "-";
                }
            echo "</td>";
            echo "<td>";
                if (!empty($leger_arr['Fisika']['nilai-kddescription_ket'])) {
                    echo implode(",",$leger_arr['Fisika']['nilai-kddescription_ket']);
                } else {
                    echo "-";
                }
            echo "</td>";

            echo "<td>";
                if (!empty($leger_arr['Kimia']['nilai-kddescription'])) {
                    echo implode(",",$leger_arr['Kimia']['nilai-kddescription']);
                } else {
                    echo "-";
                }
            echo "</td>";
            echo "<td>";
                if (!empty($leger_arr['Kimia']['nilai-kddescription_ket'])) {
                    echo implode(",",$leger_arr['Kimia']['nilai-kddescription_ket']);
                } else {
                    echo "-";
                }
            echo "</td>";

            echo "<td>";
                if (!empty($leger_arr['Geografi']['nilai-kddescription'])) {
                    echo implode(",",$leger_arr['Geografi']['nilai-kddescription']);
                } else {
                    echo "-";
                }
            echo "</td>";
            echo "<td>";
                if (!empty($leger_arr['Geografi']['nilai-kddescription_ket'])) {
                    echo implode(",",$leger_arr['Geografi']['nilai-kddescription_ket']);
                } else {
                    echo "-";
                }
            echo "</td>";

            echo "<td>";
                if (!empty($leger_arr['Sejarah']['nilai-kddescription'])) {
                    echo implode(",",$leger_arr['Sejarah']['nilai-kddescription']);
                } else {
                    echo "-";
                }
            echo "</td>";
            echo "<td>";
                if (!empty($leger_arr['Sejarah']['nilai-kddescription_ket'])) {
                    echo implode(",",$leger_arr['Sejarah']['nilai-kddescription_ket']);
                } else {
                    echo "-";
                }
            echo "</td>";

            echo "<td>";
                if (!empty($leger_arr['Sosiologi']['nilai-kddescription'])) {
                    echo implode(",",$leger_arr['Sosiologi']['nilai-kddescription']);
                } else {
                    echo "-";
                }
            echo "</td>";
            echo "<td>";
                if (!empty($leger_arr['Sosiologi']['nilai-kddescription_ket'])) {
                    echo implode(",",$leger_arr['Sosiologi']['nilai-kddescription_ket']);
                } else {
                    echo "-";
                }
            echo "</td>";

            echo "<td>";
                if (!empty($leger_arr['Ekonomi']['nilai-kddescription'])) {
                    echo implode(",",$leger_arr['Ekonomi']['nilai-kddescription']);
                } else {
                    echo "-";
                }
            echo "</td>";
            echo "<td>";
                if (!empty($leger_arr['Ekonomi']['nilai-kddescription_ket'])) {
                    echo implode(",",$leger_arr['Ekonomi']['nilai-kddescription_ket']);
                } else {
                    echo "-";
                }
            echo "</td>";

            echo "<td>";
                if (!empty($leger_arr['Lintas Minat Bahasa Inggris']['nilai-kddescription'])) {
                    echo implode(",",$leger_arr['Lintas Minat Bahasa Inggris']['nilai-kddescription']);
                } else {
                    echo "-";
                }
            echo "</td>";
            echo "<td>";
                if (!empty($leger_arr['Lintas Minat Bahasa Inggris']['nilai-kddescription_ket'])) {
                    echo implode(",",$leger_arr['Lintas Minat Bahasa Inggris']['nilai-kddescription_ket']);
                } else {
                    echo "-";
                }
            echo "</td>";


            echo "<td>";
                if (!empty($leger_arr['Lintas Minat Bahasa Jepang']['nilai-kddescription'])) {
                    echo implode(",",$leger_arr['Lintas Minat Bahasa Jepang']['nilai-kddescription']);
                } else {
                    echo "-";
                }
            echo "</td>";
            echo "<td>";
                if (!empty($leger_arr['Lintas Minat Bahasa Jepang']['nilai-kddescription_ket'])) {
                    echo implode($leger_arr['Lintas Minat Bahasa Jepang']['nilai-kddescription_ket']);
                } else {
                    echo "-";
                }
            echo "</td>";

            echo "<td>";
                if (!empty($leger_arr['Lintas Minat Biologi']['nilai-kddescription'])) {
                    echo implode(",",$leger_arr['Lintas Minat Biologi']['nilai-kddescription']);
                } else {
                    echo "-";
                }
            echo "</td>";
            echo "<td>";
                if (!empty($leger_arr['Lintas Minat Biologi']['nilai-kddescription_ket'])) {
                    echo implode(",",$leger_arr['Lintas Minat Biologi']['nilai-kddescription_ket']);
                } else {
                    echo "-";
                }
            echo "</td>";

            echo "<td>";
                if (!empty($leger_arr['Lintas Minat Ekonomi']['nilai-kddescription'])) {
                    echo implode(",",$leger_arr['Lintas Minat Ekonomi']['nilai-kddescription']);
                } else {
                    echo "-";
                }
            echo "</td>";
            echo "<td>";
                if (!empty($leger_arr['Lintas Minat Ekonomi']['nilai-kddescription_ket'])) {
                    echo implode(",",$leger_arr['Lintas Minat Ekonomi']['nilai-kddescription_ket']);
                } else {
                    echo "-";
                }
            echo "</td>";

            echo "<td>";
                if (!empty($leger_arr['Lintas Minat Geografi']['nilai-kddescription'])) {
                    echo implode(",",$leger_arr['Lintas Minat Geografi']['nilai-kddescription']);
                } else {
                    echo "-";
                }
            echo "</td>";
            echo "<td>";
                if (!empty($leger_arr['Lintas Minat Geografi']['nilai-kddescription_ket'])) {
                    echo implode(",",$leger_arr['Lintas Minat Geografi']['nilai-kddescription_ket']);
                } else {
                    echo "-";
                }
            echo "</td>";

            echo "<td>";
                if (!empty($leger_arr['Lintas Minat Sosiologi']['nilai-kddescription'])) {
                    echo implode(",",$leger_arr['Lintas Minat Sosiologi']['nilai-kddescription']);
                } else {
                    echo "-";
                }
            echo "</td>";
            echo "<td>";
                if (!empty($leger_arr['Lintas Minat Sosiologi']['nilai-kddescription_ket'])) {
                    echo implode(",",$leger_arr['Lintas Minat Sosiologi']['nilai-kddescription_ket']);
                } else {
                    echo "-";
                }
            echo "</td>";

            echo "<td>";
                if (!empty($leger_arr['Lintas Minat Bahasa Indonesia']['nilai-kddescription'])) {
                    echo implode(",",$leger_arr['Lintas Minat Bahasa Indonesia']['nilai-kddescription']);
                } else {
                    echo "-";
                }
            echo "</td>";
            echo "<td>";
                if (!empty($leger_arr['Lintas Minat Bahasa Indonesia']['nilai-kddescription_ket'])) {
                    echo implode(",",$leger_arr['Lintas Minat Bahasa Indonesia']['nilai-kddescription_ket']);
                } else {
                    echo "-";
                }
            echo "</td>";

            echo "<td>";
                if (!empty($leger_arr['Lintas Minat Fisika']['nilai-kddescription'])) {
                    echo implode(",",$leger_arr['Lintas Minat Fisika']['nilai-kddescription']);
                } else {
                    echo "-";
                }
            echo "</td>";
            echo "<td>";
                if (!empty($leger_arr['Lintas Minat Fisika']['nilai-kddescription_ket'])) {
                    echo implode(",",$leger_arr['Lintas Minat Fisika']['nilai-kddescription_ket']);
                } else {
                    echo "-";
                }
            echo "</td>";

            echo "<td>";
                if (!empty($leger_arr['Bahasa dan Sastra Indonesia']['nilai-kddescription'])) {
                    echo $leger_arr['Bahasa dan Sastra Indonesia']['nilai-kddescription'];
                } else {
                    echo "-";
                }
            echo "</td>";
            echo "<td>";
                if (!empty($leger_arr['Bahasa dan Sastra Indonesia']['nilai-kddescription_ket'])) {
                    echo $leger_arr['Bahasa dan Sastra Indonesia']['nilai-kddescription_ket'];
                } else {
                    echo "-";
                }
            echo "</td>";


            echo "<td>";
                if (!empty($leger_arr['Bahasa dan Sastra Inggris']['nilai-kddescription'])) {
                    echo $leger_arr['Bahasa dan Sastra Inggris']['nilai-kddescription'];
                } else {
                    echo "-";
                }
            echo "</td>";
            echo "<td>";
                if (!empty($leger_arr['Bahasa dan Sastra Inggris']['nilai-kddescription_ket'])) {
                    echo $leger_arr['Bahasa dan Sastra Inggris']['nilai-kddescription_ket'];
                } else {
                    echo "-";
                }
            echo "</td>";

            echo "<td>";
                if (!empty($leger_arr['Antropologi']['nilai-kddescription'])) {
                    echo $leger_arr['Antropologi']['nilai-kddescription'];
                } else {
                    echo "-";
                }
            echo "</td>";
            echo "<td>";
                if (!empty($leger_arr['Antropologi']['nilai-kddescription_ket'])) {
                    echo $leger_arr['Antropologi']['nilai-kddescription_ket'];
                } else {
                    echo "-";
                }
            echo "</td>";

            echo "<td>";
                if (!empty($leger_arr['Bahasa Arab']['nilai-kddescription'])) {
                    echo $leger_arr['Bahasa Arab']['nilai-kddescription'];
                } else {
                    echo "-";
                }
            echo "</td>";
            echo "<td>";
                if (!empty($leger_arr['Bahasa Arab']['nilai-kddescription_ket'])) {
                    echo $leger_arr['Bahasa Arab']['nilai-kddescription_ket'];
                } else {
                    echo "-";
                }
            echo "</td>";


            $sql="SELECT fm.`nilai_desc`,fm.`tipe`
                FROM `final_mark` as fm
                join `users` as u on u.`id` = fm.`user_id`
                WHERE fm.`user_id` = ".$siswa->id."
                AND fm.`semester` = ".$optSemester."
                AND fm.`tahun_ajaran` = ".$optTahunAjaran;
            $command =Yii::app()->db->createCommand($sql);
            $rows2=$command->queryAll();

            $arr2 = array();
            $leger_arr2 = array();

            foreach($rows2 as $key => $item)
            {
               // $item["nilai-".$item["tipe"]] = $item["nilai"];
               // $arr[$item['id']]["name"] = $item["name"];
               // $arr[$item['id']]["kelompok"] = $item["kelompok"];
               // $arr[$item['id']]["nilai-".$item["tipe"]] = $item["nilai"];

               // $leger_arr[$item["name"]]["kelompok"] = $item["kelompok"];
               // $leger_arr[$item["name"]]["nilai-".$item["tipe"]] = $item["nilai"];
               $leger_arr[$item["tipe"]] = $item["nilai_desc"];
            }

            $data[] = $arr;

            // //ksort($arr, SORT_NUMERIC);


                // echo "<pre>";
                // print_r($leger_arr);
                // echo "</pre>";

                    echo "<td colspan='2'>";
                        if (!empty($leger_arr['Sikap Sosial - Predikat'])) {
                            echo $leger_arr['Sikap Sosial - Predikat'];
                        } else {
                            echo "-";
                        }
                    echo "</td>";

                    echo "<td colspan='2'>";
                        if (!empty($leger_arr['Sikap Sosial - Deskripsi'])) {
                            echo $leger_arr['Sikap Sosial - Deskripsi'];
                        } else {
                            echo "-";
                        }
                    echo "</td>";

                    echo "<td colspan='2'>";
                        if (!empty($leger_arr['Sikap Spiritual - Predikat'])) {
                            echo $leger_arr['Sikap Spiritual - Predikat'];
                        } else {
                            echo "-";
                        }
                    echo "</td>";

                    echo "<td colspan='2'>";
                        if (!empty($leger_arr['Sikap Spiritual - Deskripsi'])) {
                            echo $leger_arr['Sikap Spiritual - Deskripsi'];
                        } else {
                            echo "-";
                        }
                    echo "</td>";


                    echo "<td colspan='2'>";
                        if (!empty($leger_arr['Absensi Sakit'])) {
                            echo $leger_arr['Absensi Sakit'];
                        } else {
                            echo "-";
                        }
                    echo "</td>";

                    echo "<td colspan='2'>";
                        if (!empty($leger_arr['Absensi Izin'])) {
                            echo $leger_arr['Absensi Izin'];
                        } else {
                            echo "-";
                        }
                    echo "</td>";

                    echo "<td colspan='2'>";
                        if (!empty($leger_arr['Absensi Alfa'])) {
                            echo $leger_arr['Absensi Alfa'];
                        } else {
                            echo "-";
                        }
                    echo "</td>";

                    echo "<td colspan='2'>";
                        if (!empty($leger_arr['Ekstrakurikuler 1 - Nama'])) {
                            echo $leger_arr['Ekstrakurikuler 1 - Nama'];
                        } else {
                            echo "-";
                        }
                    echo "</td>";

                    echo "<td colspan='2'>";
                        if (!empty($leger_arr['Ekstrakurikuler 1 - Nilai'])) {
                            echo $leger_arr['Ekstrakurikuler 1 - Nilai'];
                        } else {
                            echo "-";
                        }
                    echo "</td>";

                    echo "<td colspan='2'>";
                        if (!empty($leger_arr['Ekstrakurikuler 1 - Deskripsi'])) {
                            echo $leger_arr['Ekstrakurikuler 1 - Deskripsi'];
                        } else {
                            echo "-";
                        }
                    echo "</td>";

                    echo "<td colspan='2'>";
                        if (!empty($leger_arr['Ekstrakurikuler 2 - Nama'])) {
                            echo $leger_arr['Ekstrakurikuler 2 - Nama'];
                        } else {
                            echo "-";
                        }
                    echo "</td>";

                    echo "<td colspan='2'>";
                        if (!empty($leger_arr['Ekstrakurikuler 2 - Nilai'])) {
                            echo $leger_arr['Ekstrakurikuler 2 - Nilai'];
                        } else {
                            echo "-";
                        }
                    echo "</td>";

                    echo "<td colspan='2'>";
                        if (!empty($leger_arr['Ekstrakurikuler 2 - Deskripsi'])) {
                            echo $leger_arr['Ekstrakurikuler 2 - Deskripsi'];
                        } else {
                            echo "-";
                        }
                    echo "</td>";

                    echo "<td colspan='2'>";
                        if (!empty($leger_arr['Ekstrakurikuler 3 - Nama'])) {
                            echo $leger_arr['Ekstrakurikuler 3 - Nama'];
                        } else {
                            echo "-";
                        }
                    echo "</td>";

                    echo "<td colspan='2'>";
                        if (!empty($leger_arr['Ekstrakurikuler 3 – Nilai'])) {
                            echo $leger_arr['Ekstrakurikuler 3 – Nilai'];
                        } else {
                            echo "-";
                        }
                    echo "</td>";

                    echo "<td colspan='2'>";
                        if (!empty($leger_arr['Ekstrakurikuler 3 - Deskripsi'])) {
                            echo $leger_arr['Ekstrakurikuler 3 - Deskripsi'];
                        } else {
                            echo "-";
                        }
                    echo "</td>";

                    echo "<td colspan='2'>";
                        if (!empty($leger_arr['Prestasi 1 - Jenis Kegiatan'])) {
                            echo $leger_arr['Prestasi 1 - Jenis Kegiatan'];
                        } else {
                            echo "-";
                        }
                    echo "</td>";

                    echo "<td colspan='2'>";
                        if (!empty($leger_arr['Prestasi 1 - Keterangan'])) {
                            echo $leger_arr['Prestasi 1 - Keterangan'];
                        } else {
                            echo "-";
                        }
                    echo "</td>";

                    echo "<td colspan='2'>";
                        if (!empty($leger_arr['Prestasi 2 - Jenis Kegiatan'])) {
                            echo $leger_arr['Prestasi 2 - Jenis Kegiatan'];
                        } else {
                            echo "-";
                        }
                    echo "</td>";

                    echo "<td colspan='2'>";
                        if (!empty($leger_arr['Prestasi 2 - Keterangan'])) {
                            echo $leger_arr['Prestasi 2 - Keterangan'];
                        } else {
                            echo "-";
                        }
                    echo "</td>";

                    echo "<td colspan='2'>";
                        if (!empty($leger_arr['Prestasi 3 - Jenis Kegiatan'])) {
                            echo $leger_arr['Prestasi 3 - Jenis Kegiatan'];
                        } else {
                            echo "-";
                        }
                    echo "</td>";

                    echo "<td colspan='2'>";
                        if (!empty($leger_arr['Prestasi 3 - Keterangan'])) {
                            echo $leger_arr['Prestasi 3 - Keterangan'];
                        } else {
                            echo "-";
                        }
                    echo "</td>";


                    echo "<td colspan='2'>";
                        if (!empty($leger_arr['Prestasi 4 - Jenis Kegiatan'])) {
                            echo $leger_arr['Prestasi 4 - Jenis Kegiatan'];
                        } else {
                            echo "-";
                        }
                    echo "</td>";

                    echo "<td colspan='2'>";
                        if (!empty($leger_arr['Prestasi 4 - Keterangan'])) {
                            echo $leger_arr['Prestasi 4 - Keterangan'];
                        } else {
                            echo "-";
                        }
                    echo "</td>";

                    echo "<td colspan='2'>";
                        if (!empty($leger_arr['Catatan Wali Kelas'])) {
                            echo $leger_arr['Catatan Wali Kelas'];
                        } else {
                            echo "-";
                        }
                    echo "</td>";


                echo "</tr>";
            }

        echo "</table>";
        echo "</body>";
        echo "</div>";
        // $this->renderPartial('/clases/');
        // $this->renderPartial('/clases/raport-siswa-uts-all',array('inline_style'=>$inline_style,'siswa'=>$user,'peluts'=>$data,'profil'=>$user_data,'model'=>$model));

    }





    public function actionLegerUasBackup($id) {
        if (Yii::app()->session['semester']) {
            $optSemester = Yii::app()->session['semester'];
            $optSemester_db = Option::model()->findByAttributes(array('key_config' => 'semester'))->value;
        } else {
            $optSemester = Option::model()->findByAttributes(array('key_config' => 'semester'))->value;
        }
        if (Yii::app()->session['tahun_ajaran']) {
            $optTahunAjaran = Yii::app()->session['tahun_ajaran'];
            $optTahunAjaran_db = Option::model()->findByAttributes(array('key_config' => 'tahun_ajaran'))->value;
        } else {
            $optTahunAjaran = Option::model()->findByAttributes(array('key_config' => 'tahun_ajaran'))->value;
        }


        if ($optTahunAjaran == $optTahunAjaran_db) {
            $getsiswa = User::model()->findAll(array('condition' => 'class_id = ' . $id . '', 'order' => 'display_name	'));
        } else {
            $getsiswa = ClassHistory::model()->findAll(array('condition' => 'class_id = ' . $id . '', 'order' => 'id'));
        }





        $base = Yii::app()->baseUrl;

        $template_directory = Yii::app()->theme->baseUrl;
        $template_file = Yii::app()->theme->basePath . '/css/print.bootstrap.min.css';
        $inline_style = file_get_contents($template_file);
        $icon_sprite = Yii::app()->theme->basePath . '/images/glyphicons-halflings.png';
        $inline_style = str_replace('../images/glyphicons-halflings.png', $icon_sprite, $inline_style);
        $model = ClassDetail::model()->findByPk($id);


        if (!empty($_GET['print'])) {
            $file = "leger.xls";
            header("Content-type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename=$file"); # code...
        }


        echo "<html>";

        echo "<div id='dvData'>";
        echo "<table border='1'>";
        echo "<tr>";
        echo "<th>";
        echo "Nama Siswa Kelas " . $getsiswa[0]->class->name;
        ;
        echo "</th>";
        echo "<th colspan='2'>";
        echo "Pendidikan Agama dan Budi Pekerti (A)";
        echo "</th>";
        echo "<th colspan='2'>";
        echo "Pendidikan Pancasila dan Kewarganegaraan (A)";
        echo "</th>";
        echo "<th colspan='2'>";
        echo "Bahasa Indonesia (A)";
        echo "</th>";
        echo "<th colspan='2'>";
        echo "Matematika (A)";
        echo "</th>";
        echo "<th colspan='2'>";
        echo "Sejarah Indonesia (A)";
        echo "</th>";
        echo "<th colspan='2'>";
        echo "Bahasa Inggris (A)";
        echo "</th>";
        echo "<th colspan='2'>";
        echo "Seni dan Budaya (B)";
        echo "</th>";
        echo "<th colspan='2'>";
        echo "Pendidikan Jasmani, Olah Raga, dan Kesehatan (B)";
        echo "</th>";
        echo "<th colspan='2'>";
        echo "Prakarya (B)";
        echo "</th>";
        echo "<th colspan='2'>";
        echo "Bahasa Sunda (B)";
        echo "</th>";
        echo "<th colspan='2'>";
        echo "Pendidikan Lingkungan Hidup (B)";
        echo "</th>";
        echo "<th colspan='2'>";
        echo "Peminatan Matematika (C)";
        echo "</th>";
        echo "<th colspan='2'>";
        echo "IPS (C)";
        echo "</th>";
        echo "<th colspan='2'>";
        echo "IPA (C)";
        echo "</th>";
        echo "<th colspan='2'>";
        echo "Biologi (C)";
        echo "</th>";
        echo "<th colspan='2'>";
        echo "Fisika (C)";
        echo "</th>";
        echo "<th colspan='2'>";
        echo "Kimia (C)";
        echo "</th>";
        echo "<th colspan='2'>";
        echo "Geografi (C)";
        echo "</th>";
        echo "<th colspan='2'>";
        echo "Sejarah (C)";
        echo "</th>";
        echo "<th colspan='2'>";
        echo "Sosiologi (C)";
        echo "</th>";
        echo "<th colspan='2'>";
        echo "Ekonomi (C)";
        echo "</th>";
        echo "<th colspan='2'>";
        echo "Lintas Minat Bahasa Inggris  (C)";
        echo "</th>";
        echo "<th colspan='2'>";
        echo "Lintas Minat Bahasa Jepang (C)";
        echo "</th>";
        echo "<th colspan='2'>";
        echo "Lintas Minat Biologi (C)";
        echo "</th>";
        echo "<th colspan='2'>";
        echo "Lintas Minat Ekonomi (C)";
        echo "</th>";
        echo "<th colspan='2'>";
        echo "Lintas Minat Geografi (C)";
        echo "</th>";
        echo "<th colspan='2'>";
        echo "Lintas Minat Sosiologi (C)";
        echo "</th>";
        echo "<th colspan='2'>";
        echo "Lintas Minat Bahasa Indonesia (C)";
        echo "</th>";
        echo "<th colspan='2'>";
        echo "Lintas Fisika (C)";
        echo "</th>";
        echo "<th colspan='2'>";
        echo "Bahasa dan Sastra Indonesia (C)";
        echo "</th>";
        echo "<th colspan='2'>";
        echo "Bahasa dan Sastra Inggris (C)";
        echo "</th>";
        echo "<th colspan='2'>";
        echo "Antropologi (C)";
        echo "</th>";
        echo "<th colspan='2'>";
        echo "Bahasa Arab (C)";
        echo "</th>";
        echo "<th colspan='2'>";
        echo "Pendidikan Agama dan Budi Pekerti (A)";
        echo "</th>";
        echo "<th colspan='2'>";
        echo "Pendidikan Pancasila dan Kewarganegaraan (A)";
        echo "</th>";
        echo "<th colspan='2'>";
        echo "Bahasa Indonesia (A)";
        echo "</th>";
        echo "<th colspan='2'>";
        echo "Matematika (A)";
        echo "</th>";
        echo "<th colspan='2'>";
        echo "Sejarah Indonesia (A)";
        echo "</th>";
        echo "<th colspan='2'>";
        echo "Bahasa Inggris (A)";
        echo "</th>";
        echo "<th colspan='2'>";
        echo "Seni dan Budaya (B)";
        echo "</th>";
        echo "<th colspan='2'>";
        echo "Pendidikan Lingkungan Hidup (B)";
        echo "</th>";
        echo "<th colspan='2'>";
        echo "Pendidikan Jasmani, Olah Raga, dan Kesehatan (B)";
        echo "</th>";
        echo "<th colspan='2'>";
        echo "Prakarya (B)";
        echo "</th>";
        echo "<th colspan='2'>";
        echo "Bahasa Sunda (B)";
        echo "</th>";
        echo "<th colspan='2'>";
        echo "Peminatan Matematika (C)";
        echo "</th>";
        echo "<th colspan='2'>";
        echo "Biologi (C)";
        echo "</th>";
        echo "<th colspan='2'>";
        echo "Fisika (C)";
        echo "</th>";
        echo "<th colspan='2'>";
        echo "Kimia (C)";
        echo "</th>";
        echo "<th colspan='2'>";
        echo "Geografi (C)";
        echo "</th>";
        echo "<th colspan='2'>";
        echo "Sejarah (C)";
        echo "</th>";
        echo "<th colspan='2'>";
        echo "Sosiologi (C)";
        echo "</th>";
        echo "<th colspan='2'>";
        echo "Ekonomi (C)";
        echo "</th>";
        echo "<th colspan='2'>";
        echo "Lintas Minat Bahasa Inggris  (C)";
        echo "</th>";
        echo "<th colspan='2'>";
        echo "Lintas Minat Bahasa Jepang (C)";
        echo "</th>";
        echo "<th colspan='2'>";
        echo "Lintas Minat Biologi (C)";
        echo "</th>";
        echo "<th colspan='2'>";
        echo "Lintas Minat Ekonomi (C)";
        echo "</th>";
        echo "<th colspan='2'>";
        echo "Lintas Minat Geografi (C)";
        echo "</th>";
        echo "<th colspan='2'>";
        echo "Lintas Minat Sosiologi (C)";
        echo "</th>";
        echo "<th colspan='2'>";
        echo "Lintas Minat Bahasa Indonesia (C)";
        echo "</th>";
        echo "<th colspan='2'>";
        echo "Lintas Fisika (C)";
        echo "</th>";
        echo "<th colspan='2'>";
        echo "Bahasa dan Sastra Indonesia (C)";
        echo "</th>";
        echo "<th colspan='2'>";
        echo "Bahasa dan Sastra Inggris (C)";
        echo "</th>";
        echo "<th colspan='2'>";
        echo "Antropologi (C)";
        echo "</th>";
        echo "<th colspan='2'>";
        echo "Bahasa Arab (C)";
        echo "</th>";
        echo "<th colspan='2' rowspan='2'>";
        echo "Sikap Sosial - Predikat";
        echo "</th>";
        echo "<th colspan='2' rowspan='2'>";
        echo "Sikap Sosial - Deskripsi";
        echo "</th>";
        echo "<th colspan='2' rowspan='2'>";
        echo "Sikap Spritual - Predikat";
        echo "</th>";
        echo "<th colspan='2' rowspan='2'>";
        echo "Sikap Spritual - Deskripsi";
        echo "</th>";
        echo "<th colspan='2' rowspan='2'>";
        echo "Absensi Sakit";
        echo "</th>";
        echo "<th colspan='2' rowspan='2'>";
        echo "Absensi Izin";
        echo "</th>";
        echo "<th colspan='2' rowspan='2'>";
        echo "Absensi Alfa";
        echo "</th>";
        echo "<th colspan='2' rowspan='2'>";
        echo "Ekskul 1 - Nama";
        echo "</th>";
        echo "<th colspan='2' rowspan='2'>";
        echo "Ekskul 1 - Nilai";
        echo "</th>";
        echo "<th colspan='2' rowspan='2'>";
        echo "Ekskul 1 - Deskripsi";
        echo "</th>";
        echo "<th colspan='2' rowspan='2'>";
        echo "Ekskul 2 - Nama";
        echo "</th>";
        echo "<th colspan='2' rowspan='2'>";
        echo "Ekskul 2 - Nilai";
        echo "</th>";
        echo "<th colspan='2' rowspan='2'>";
        echo "Ekskul 2 - Deskripsi";
        echo "</th>";
        echo "<th colspan='2' rowspan='2'>";
        echo "Ekskul 3 - Nama";
        echo "</th>";
        echo "<th colspan='2' rowspan='2'>";
        echo "Ekskul 3 - Nilai";
        echo "</th>";
        echo "<th colspan='2' rowspan='2'>";
        echo "Ekskul 3 - Deskripsi";
        echo "</th>";
        echo "<th colspan='2' rowspan='2'>";
        echo "Prestasi 1 - Jenis Kegiatan";
        echo "</th>";
        echo "<th colspan='2' rowspan='2'>";
        echo "Prestasi 1 - Keterangan";
        echo "</th>";
        echo "<th colspan='2' rowspan='2'>";
        echo "Prestasi 2 - Jenis Kegiatan";
        echo "</th>";
        echo "<th colspan='2' rowspan='2'>";
        echo "Prestasi 2 - Keterangan";
        echo "</th>";
        echo "<th colspan='2' rowspan='2'>";
        echo "Prestasi 3 - Jenis Kegiatan";
        echo "</th>";
        echo "<th colspan='2' rowspan='2'>";
        echo "Prestasi 3 - Keterangan";
        echo "</th>";
        echo "<th colspan='2' rowspan='2'>";
        echo "Prestasi 4 - Jenis Kegiatan";
        echo "</th>";
        echo "<th colspan='2' rowspan='2'>";
        echo "Prestasi 4 - Keterangan";
        echo "</th>";
        echo "<th colspan='2' rowspan='2'>";
        echo "Catatan Wali Kelas";
        echo "</th>";


        echo "</tr>";
        echo "<tr>";
        echo "<td>";
        echo "TIPE";
        echo "</td>";
        echo "<td>";
        echo "P";
        echo "</td>";
        echo "<td>";
        echo "K";
        echo "</td>";
        echo "<td>";
        echo "P";
        echo "</td>";
        echo "<td>";
        echo "K";
        echo "</td>";
        echo "<td>";
        echo "P";
        echo "</td>";
        echo "<td>";
        echo "K";
        echo "</td>";
        echo "<td>";
        echo "P";
        echo "</td>";
        echo "<td>";
        echo "K";
        echo "</td>";
        echo "<td>";
        echo "P";
        echo "</td>";
        echo "<td>";
        echo "K";
        echo "</td>";
        echo "<td>";
        echo "P";
        echo "</td>";
        echo "<td>";
        echo "K";
        echo "</td>";
         echo "<td>";
        echo "P";
        echo "</td>";
        echo "<td>";
        echo "K";
        echo "</td>";
        echo "<td>";
        echo "P";
        echo "</td>";
        echo "<td>";
        echo "K";
        echo "</td>";
        echo "<td>";
        echo "P";
        echo "</td>";
        echo "<td>";
        echo "K";
        echo "</td>";
        echo "<td>";
        echo "P";
        echo "</td>";
        echo "<td>";
        echo "K";
        echo "</td>";
        echo "<td>";
        echo "P";
        echo "</td>";
        echo "<td>";
        echo "K";
        echo "</td>";
        echo "<td>";
        echo "P";
        echo "</td>";
        echo "<td>";
        echo "K";
        echo "</td>";
        echo "<td>";
        echo "P";
        echo "</td>";
        echo "<td>";
        echo "K";
        echo "</td>";
         echo "<td>";
        echo "P";
        echo "</td>";
        echo "<td>";
        echo "K";
        echo "</td>";
        echo "<td>";
        echo "P";
        echo "</td>";
        echo "<td>";
        echo "K";
        echo "</td>";
        echo "<td>";
        echo "P";
        echo "</td>";
        echo "<td>";
        echo "K";
        echo "</td>";
        echo "<td>";
        echo "P";
        echo "</td>";
        echo "<td>";
        echo "K";
        echo "</td>";
        echo "<td>";
        echo "P";
        echo "</td>";
        echo "<td>";
        echo "K";
        echo "</td>";
        echo "<td>";
        echo "P";
        echo "</td>";
        echo "<td>";
        echo "K";
        echo "</td>";
        echo "<td>";
        echo "P";
        echo "</td>";
        echo "<td>";
        echo "K";
        echo "</td>";
        echo "<td>";
        echo "P";
        echo "</td>";
        echo "<td>";
        echo "K";
        echo "</td>";
        echo "<td>";
        echo "P";
        echo "</td>";
        echo "<td>";
        echo "K";
        echo "</td>";
        echo "<td>";
        echo "P";
        echo "</td>";
        echo "<td>";
        echo "K";
        echo "</td>";
        echo "<td>";
        echo "P";
        echo "</td>";
        echo "<td>";
        echo "K";
        echo "</td>";
        echo "<td>";
        echo "P";
        echo "</td>";
        echo "<td>";
        echo "K";
        echo "</td>";
        echo "<td>";
        echo "P";
        echo "</td>";
        echo "<td>";
        echo "K";
        echo "</td>";
        echo "<td>";
        echo "P";
        echo "</td>";
        echo "<td>";
        echo "K";
        echo "</td>";
        echo "<td>";
        echo "P";
        echo "</td>";
        echo "<td>";
        echo "K";
        echo "</td>";
        echo "<td>";
        echo "P";
        echo "</td>";
        echo "<td>";
        echo "K";
        echo "</td>";
        echo "<td>";
        echo "P";
        echo "</td>";
        echo "<td>";
        echo "K";
        echo "</td>";
        echo "<td>";
        echo "P";
        echo "</td>";
        echo "<td>";
        echo "K";
        echo "</td>";
        echo "<td>";
        echo "P";
        echo "</td>";
        echo "<td>";
        echo "K";
        echo "</td>";
        echo "<td>";
        echo "P";
        echo "</td>";
        echo "<td>";
        echo "K";
        echo "</td>";
        echo "<td>";
        echo "P";
        echo "</td>";
        echo "<td>";
        echo "K";
        echo "</td>";
        echo "<td>";
        echo "P";
        echo "</td>";
        echo "<td>";
        echo "K";
        echo "</td>";
        echo "<td>";
        echo "P";
        echo "</td>";
        echo "<td>";
        echo "K";
        echo "</td>";
        echo "<td>";
        echo "P";
        echo "</td>";
        echo "<td>";
        echo "K";
        echo "</td>";
        echo "<td>";
        echo "P";
        echo "</td>";
        echo "<td>";
        echo "K";
        echo "</td>";
        echo "<td>";
        echo "P";
        echo "</td>";
        echo "<td>";
        echo "K";
        echo "</td>";
        echo "<td>";
        echo "P";
        echo "</td>";
        echo "<td>";
        echo "K";
        echo "</td>";
        echo "<td>";
        echo "P";
        echo "</td>";
        echo "<td>";
        echo "K";
        echo "</td>";
        echo "<td>";
        echo "P";
        echo "</td>";
        echo "<td>";
        echo "K";
        echo "</td>";
        echo "<td>";
        echo "P";
        echo "</td>";
        echo "<td>";
        echo "K";
        echo "</td>";
        echo "<td>";
        echo "P";
        echo "</td>";
        echo "<td>";
        echo "K";
        echo "</td>";
        echo "<td>";
        echo "P";
        echo "</td>";
        echo "<td>";
        echo "K";
        echo "</td>";
        echo "<td>";
        echo "P";
        echo "</td>";
        echo "<td>";
        echo "K";
        echo "</td>";
        echo "<td>";
        echo "P";
        echo "</td>";
        echo "<td>";
        echo "K";
        echo "</td>";
        echo "<td>";
        echo "P";
        echo "</td>";
        echo "<td>";
        echo "K";
        echo "</td>";
        echo "<td>";
        echo "P";
        echo "</td>";
        echo "<td>";
        echo "K";
        echo "</td>";
        echo "<td>";
        echo "P";
        echo "</td>";
        echo "<td>";
        echo "K";
        echo "</td>";
        echo "<td>";
        echo "P";
        echo "</td>";
        echo "<td>";
        echo "K";
        echo "</td>";
        echo "<td>";
        echo "P";
        echo "</td>";
        echo "<td>";
        echo "K";
        echo "</td>";
        echo "<td>";
        echo "P";
        echo "</td>";
        echo "<td>";
        echo "K";
        echo "</td>";
        echo "<td>";
        echo "P";
        echo "</td>";
        echo "<td>";
        echo "K";
        echo "</td>";
        echo "<td>";
        echo "P";
        echo "</td>";
        echo "<td>";
        echo "K";
        echo "</td>";
        echo "<td>";
        echo "P";
        echo "</td>";
        echo "<td>";
        echo "K";
        echo "</td>";
        echo "</tr>";


        foreach ($getsiswa as $siswa) {
            // echo "<pre>";
            // echo $siswa->id;
            //print_r($siswa);
            // echo "</pre>";

            if ($optTahunAjaran == $optTahunAjaran_db) {
                //echo $siswa->display_name;
            } else {
                $siswa = $siswa->user;
            }

            $user[] = $siswa;
            $user_data[] = UserProfile::model()->findByAttributes(array('user_id' => $siswa->id));

            // $nama = str_replace(" ", "-" , $user->display_name);
            // $model = ClassDetail::model()->findByPk($user->class_id);
            // /*Default DOMPDF File Path*/



            $sql = "SELECT l.`id`,l.`name`,fm.`nilai`,fm.`nilai_desc`,fm.`tipe`,l.`kelompok`,l.`list_id`,lk.`description`
				FROM `final_mark` as fm
				join `users` as u on u.`id` = fm.`user_id`
				join `lesson` as l on fm.`lesson_id` = l.`id`
				left join `lesson_kd` as lk on fm.`lesson_id` = lk.`lesson_id` AND fm.`tipe` = lk.`title`
				WHERE fm.`user_id` = " . $siswa->id . "
				AND fm.`semester` = " . $optSemester . "
				AND fm.`tahun_ajaran` = " . $optTahunAjaran . "
				AND l.`semester` = " . $optSemester . "
				AND l.`year` = " . $optTahunAjaran . "
				AND l.`trash` is null
				order by l.`list_id`";
            $command = Yii::app()->db->createCommand($sql);
            $rows = $command->queryAll();

            $arr = array();
            $leger_arr = array();
            $rnh = array();
            $rnh_ket = array();
            $nh = 0;
            $nuas = 0;
            $npeng_m = 0;
            $rnt = 0;
            $r_ket = 0;
            $r_nh = 0;

            foreach ($rows as $key => $item) {
                // $item["nilai-".$item["tipe"]] = $item["nilai"];
                // $arr[$item['id']]["name"] = $item["name"];
                // $arr[$item['id']]["kelompok"] = $item["kelompok"];
                // $arr[$item['id']]["nilai-".$item["tipe"]] = $item["nilai"];
                // if(!empty($leger_arr[$item["name"]]['nilai-kddescription']) && count($leger_arr[$item["name"]]['nilai-kddescription']) <7 ) {
                $sql9 = "SELECT lk.`description`
				FROM `lesson_kd` as lk
				WHERE lk.`lesson_id` = " . $item["id"] . "
				AND lk.`title` != 'KD1_ket'
				AND lk.`title` != 'KD2_ket'
				AND lk.`title` != 'KD3_ket'
				AND lk.`title` != 'KD4_ket'
				AND lk.`title` != 'KD5_ket'
				AND lk.`title` != 'KD6_ket'
				AND lk.`title` != 'KD7_ket'";
                $command9 = Yii::app()->db->createCommand($sql9);
                $rows9 = $command9->queryAll();

                if (!empty($rows9)) {
                    $itung = 0;
                    foreach ($rows9 as $key9 => $value9) {
                        $leger_arr[$item["name"]]['nilai-kddescription'][$itung] = $value9['description'];
                        $itung++;
                        if (count($leger_arr[$item["name"]]['nilai-kddescription']) > 7) {
                            unset($leger_arr[$item["name"]]['nilai-kddescription'][$itung]);
                        }
                    }
                }
                // }

                $leger_arr[$item["name"]]["kelompok"] = $item["kelompok"];
                $leger_arr[$item["name"]]["nilai-" . $item["tipe"]] = $item["nilai"];
                $leger_arr[$item['name']]["desc-" . $item["tipe"]] = $item["nilai_desc"];

                if ($item["tipe"] == "kd1" or $item["tipe"] == "kd2" or $item["tipe"] == "kd3" or $item["tipe"] == "kd4" or $item["tipe"] == "kd5" or $item["tipe"] == "kd6" or $item["tipe"] == "kd7") {
                    $leger_arr[$item["name"]]['rnh'][] = $item['nilai'];
                    // if($item['description'] != null ){
                    // 		$leger_arr[$item["name"]]['nilai-kddescription'][]=$item['description'];
                    // }
                }


                if ($item["tipe"] == "kd1_ket" or $item["tipe"] == "kd2_ket" or $item["tipe"] == "kd3_ket" or $item["tipe"] == "kd4_ket" or $item["tipe"] == "kd5_ket" or $item["tipe"] == "kd6_ket" or $item["tipe"] == "kd7_ket") {
                    $leger_arr[$item["name"]]['rnh_ket'][] = $item['nilai'];
                }


                if ($item['tipe'] == "tugas") {
                    $leger_arr[$item["name"]]['rnt'] = $item['nilai'];
                }

                if ($item['tipe'] == "uas") {
                    $leger_arr[$item["name"]]['nuas'] = $item['nilai'];
                }
            }




            $data[] = $arr;

            // //ksort($arr, SORT_NUMERIC);




            foreach ($leger_arr as $key => $value) {
                if (empty($leger_arr[$key]['nilai-pengetahuan'])) {
                    if (!empty($leger_arr[$key]['nuas']) && !empty($leger_arr[$key]['rnh'])) {

                        if (!empty($leger_arr[$key]['rnt'])) {
                            $r_nh = (2 * (round(array_sum($leger_arr[$key]['rnh']) / count($leger_arr[$key]['rnh']))) + $leger_arr[$key]['rnt']) / 3;
                            $leger_arr[$key]['nilai-pengetahuan'] = round((2 * round($r_nh) + $leger_arr[$key]['nuas']) / 3);
                        } else {
                            $r_nh = round(array_sum($leger_arr[$key]['rnh']) / count($leger_arr[$key]['rnh']));
                            $leger_arr[$key]['nilai-pengetahuan'] = round((2 * round($r_nh) + $leger_arr[$key]['nuas']) / 3);
                        }
                    } else {
                        $leger_arr[$key]['nilai-pengetahuan'] = "-";
                    }
                }

                if (empty($leger_arr[$key]['nilai-keterampilan'])) {

                    if (!empty($leger_arr[$key]['rnh_ket'])) {
                        $leger_arr[$key]['nilai-keterampilan'] = round(array_sum($leger_arr[$key]['rnh_ket']) / count($leger_arr[$key]['rnh_ket']));
                    } else {

                        $leger_arr[$key]['nilai-keterampilan'] = "-";
                    }
                }


                if (!empty($leger_arr[$key]['desc-desc_pengetahuan'])) {
                    $leger_arr[$key]['nilai-kddescription'] = array();
                    $leger_arr[$key]['nilai-kddescription'][] = $leger_arr[$key]['desc-desc_pengetahuan'];
                }

                if (!empty($leger_arr[$key]['desc-desc_keterampilan'])) {
                    $leger_arr[$key]['nilai-kddescription_ket'][] = $leger_arr[$key]['desc-desc_keterampilan'];
                }

                // if (empty($leger_arr[$key]['desc-desc_keterampilan'])) {	
                // 	$leger_arr[$key]['nilai-kddescriptionket'] = $leger_arr[$key]['desc-desc_keterampilan'];
                // }		
            }

            // echo "<pre>";
            // print_r($leger_arr);
            // echo "</pre>";



            echo "<tr>";
            echo "<td>";
            echo $siswa->display_name;
            echo "</td>";
            echo "<td>";
            if (!empty($leger_arr['Pendidikan Agama dan Budi Pekerti']['nilai-pengetahuan'])) {
                echo $leger_arr['Pendidikan Agama dan Budi Pekerti']['nilai-pengetahuan'];
            } else {
                echo "-";
            }
            echo "</td>";
            echo "<td>";
            if (!empty($leger_arr['Pendidikan Agama dan Budi Pekerti']['nilai-keterampilan'])) {
                echo $leger_arr['Pendidikan Agama dan Budi Pekerti']['nilai-keterampilan'];
            } else {
                echo "-";
            }
            echo "</td>";

            echo "<td>";
            if (!empty($leger_arr['Pendidikan Pancasila dan Kewarganegaraan']['nilai-pengetahuan'])) {
                echo $leger_arr['Pendidikan Pancasila dan Kewarganegaraan']['nilai-pengetahuan'];
            } else {
                echo "-";
            }
            echo "</td>";
            echo "<td>";
            if (!empty($leger_arr['Pendidikan Pancasila dan Kewarganegaraan']['nilai-keterampilan'])) {
                echo $leger_arr['Pendidikan Pancasila dan Kewarganegaraan']['nilai-keterampilan'];
            } else {
                echo "-";
            }
            echo "</td>";

            echo "<td>";
            if (!empty($leger_arr['Bahasa Indonesia']['nilai-pengetahuan'])) {
                echo $leger_arr['Bahasa Indonesia']['nilai-pengetahuan'];
            } else {
                echo "-";
            }
            echo "</td>";
            echo "<td>";
            if (!empty($leger_arr['Bahasa Indonesia']['nilai-keterampilan'])) {
                echo $leger_arr['Bahasa Indonesia']['nilai-keterampilan'];
            } else {
                echo "-";
            }
            echo "</td>";

            echo "<td>";
            if (!empty($leger_arr['Matematika']['nilai-pengetahuan'])) {
                echo $leger_arr['Matematika']['nilai-pengetahuan'];
            } else {
                echo "-";
            }
            echo "</td>";
            echo "<td>";
            if (!empty($leger_arr['Matematika']['nilai-keterampilan'])) {
                echo $leger_arr['Matematika']['nilai-keterampilan'];
            } else {
                echo "-";
            }
            echo "</td>";

            echo "<td>";
            if (!empty($leger_arr['Sejarah Indonesia']['nilai-pengetahuan'])) {
                echo $leger_arr['Sejarah Indonesia']['nilai-pengetahuan'];
            } else {
                echo "-";
            }
            echo "</td>";
            echo "<td>";
            if (!empty($leger_arr['Sejarah Indonesia']['nilai-keterampilan'])) {
                echo $leger_arr['Sejarah Indonesia']['nilai-keterampilan'];
            } else {
                echo "-";
            }
            echo "</td>";

            echo "<td>";
            if (!empty($leger_arr['Bahasa Inggris']['nilai-pengetahuan'])) {
                echo $leger_arr['Bahasa Inggris']['nilai-pengetahuan'];
            } else {
                echo "-";
            }
            echo "</td>";
            echo "<td>";
            if (!empty($leger_arr['Bahasa Inggris']['nilai-keterampilan'])) {
                echo $leger_arr['Bahasa Inggris']['nilai-keterampilan'];
            } else {
                echo "-";
            }
            echo "</td>";

            echo "<td>";
            if (!empty($leger_arr['Seni dan Budaya']['nilai-pengetahuan'])) {
                echo $leger_arr['Seni dan Budaya']['nilai-pengetahuan'];
            } else {
                echo "-";
            }
            echo "</td>";
            echo "<td>";
            if (!empty($leger_arr['Seni dan Budaya']['nilai-keterampilan'])) {
                echo $leger_arr['Seni dan Budaya']['nilai-keterampilan'];
            } else {
                echo "-";
            }
            echo "</td>";

            echo "<td>";
            if (!empty($leger_arr['Pendidikan Jasmani, Olah Raga, dan Kesehatan']['nilai-pengetahuan'])) {
                echo $leger_arr['Pendidikan Jasmani, Olah Raga, dan Kesehatan']['nilai-pengetahuan'];
            } else {
                echo "-";
            }
            echo "</td>";
            echo "<td>";
            if (!empty($leger_arr['Pendidikan Jasmani, Olah Raga, dan Kesehatan']['nilai-keterampilan'])) {
                echo $leger_arr['Pendidikan Jasmani, Olah Raga, dan Kesehatan']['nilai-keterampilan'];
            } else {
                echo "-";
            }
            echo "</td>";

            echo "<td>";
            if (!empty($leger_arr['Prakarya']['nilai-pengetahuan'])) {
                echo $leger_arr['Prakarya']['nilai-pengetahuan'];
            } else {
                echo "-";
            }
            echo "</td>";
            echo "<td>";
            if (!empty($leger_arr['Prakarya']['nilai-keterampilan'])) {
                echo $leger_arr['Prakarya']['nilai-keterampilan'];
            } else {
                echo "-";
            }
            echo "</td>";

            echo "<td>";
            if (!empty($leger_arr['Bahasa Sunda']['nilai-pengetahuan'])) {
                echo $leger_arr['Bahasa Sunda']['nilai-pengetahuan'];
            } else {
                echo "-";
            }
            echo "</td>";
            echo "<td>";
            if (!empty($leger_arr['Bahasa Sunda']['nilai-keterampilan'])) {
                echo $leger_arr['Bahasa Sunda']['nilai-keterampilan'];
            } else {
                echo "-";
            }
            echo "</td>";

            echo "<td>";
            if (!empty($leger_arr['Pendidikan Lingkungan Hidup']['nilai-pengetahuan'])) {
                echo $leger_arr['Pendidikan Lingkungan Hidup']['nilai-pengetahuan'];
            } else {
                echo "-";
            }
            echo "</td>";
            echo "<td>";
            if (!empty($leger_arr['Pendidikan Lingkungan Hidup']['nilai-keterampilan'])) {
                echo $leger_arr['Pendidikan Lingkungan Hidup']['nilai-keterampilan'];
            } else {
                echo "-";
            }
            echo "</td>";

            echo "<td>";
            if (!empty($leger_arr['Peminatan Matematika']['nilai-pengetahuan'])) {
                echo $leger_arr['Peminatan Matematika']['nilai-pengetahuan'];
            } else {
                echo "-";
            }
            echo "</td>";
            echo "<td>";
            if (!empty($leger_arr['Peminatan Matematika']['nilai-keterampilan'])) {
                echo $leger_arr['Peminatan Matematika']['nilai-keterampilan'];
            } else {
                echo "-";
            }
            echo "</td>";

              echo "<td>";
            if (!empty($leger_arr['Ilmu Pengetahuan Sosial']['nilai-pengetahuan'])) {
                echo $leger_arr['Ilmu Pengetahuan Sosial']['nilai-pengetahuan'];
            } else {
                echo "-";
            }
            echo "</td>";
            echo "<td>";
            if (!empty($leger_arr['Ilmu Pengetahuan Sosial']['nilai-keterampilan'])) {
                echo $leger_arr['Ilmu Pengetahuan Sosial']['nilai-keterampilan'];
            } else {
                echo "-";
            }
            echo "</td>";


             echo "<td>";
            if (!empty($leger_arr['Ilmu Pengetahuan Alam']['nilai-pengetahuan'])) {
                echo $leger_arr['Ilmu Pengetahuan Alam']['nilai-pengetahuan'];
            } else {
                echo "-";
            }
            echo "</td>";
            echo "<td>";
            if (!empty($leger_arr['Ilmu Pengetahuan Alam']['nilai-keterampilan'])) {
                echo $leger_arr['Ilmu Pengetahuan Alam']['nilai-keterampilan'];
            } else {
                echo "-";
            }
            echo "</td>";

            echo "<td>";
            if (!empty($leger_arr['Biologi']['nilai-pengetahuan'])) {
                echo $leger_arr['Biologi']['nilai-pengetahuan'];
            } else {
                echo "-";
            }
            echo "</td>";
            echo "<td>";
            if (!empty($leger_arr['Biologi']['nilai-keterampilan'])) {
                echo $leger_arr['Biologi']['nilai-keterampilan'];
            } else {
                echo "-";
            }
            echo "</td>";

            echo "<td>";
            if (!empty($leger_arr['Fisika']['nilai-pengetahuan'])) {
                echo $leger_arr['Fisika']['nilai-pengetahuan'];
            } else {
                echo "-";
            }
            echo "</td>";
            echo "<td>";
            if (!empty($leger_arr['Fisika']['nilai-keterampilan'])) {
                echo $leger_arr['Fisika']['nilai-keterampilan'];
            } else {
                echo "-";
            }
            echo "</td>";

            echo "<td>";
            if (!empty($leger_arr['Kimia']['nilai-pengetahuan'])) {
                echo $leger_arr['Kimia']['nilai-pengetahuan'];
            } else {
                echo "-";
            }
            echo "</td>";
            echo "<td>";
            if (!empty($leger_arr['Kimia']['nilai-keterampilan'])) {
                echo $leger_arr['Kimia']['nilai-keterampilan'];
            } else {
                echo "-";
            }
            echo "</td>";

            echo "<td>";
            if (!empty($leger_arr['Geografi']['nilai-pengetahuan'])) {
                echo $leger_arr['Geografi']['nilai-pengetahuan'];
            } else {
                echo "-";
            }
            echo "</td>";
            echo "<td>";
            if (!empty($leger_arr['Geografi']['nilai-keterampilan'])) {
                echo $leger_arr['Geografi']['nilai-keterampilan'];
            } else {
                echo "-";
            }
            echo "</td>";

            echo "<td>";
            if (!empty($leger_arr['Sejarah']['nilai-pengetahuan'])) {
                echo $leger_arr['Sejarah']['nilai-pengetahuan'];
            } else {
                echo "-";
            }
            echo "</td>";
            echo "<td>";
            if (!empty($leger_arr['Sejarah']['nilai-keterampilan'])) {
                echo $leger_arr['Sejarah']['nilai-keterampilan'];
            } else {
                echo "-";
            }
            echo "</td>";

            echo "<td>";
            if (!empty($leger_arr['Sosiologi']['nilai-pengetahuan'])) {
                echo $leger_arr['Sosiologi']['nilai-pengetahuan'];
            } else {
                echo "-";
            }
            echo "</td>";
            echo "<td>";
            if (!empty($leger_arr['Sosiologi']['nilai-keterampilan'])) {
                echo $leger_arr['Sosiologi']['nilai-keterampilan'];
            } else {
                echo "-";
            }
            echo "</td>";

            echo "<td>";
            if (!empty($leger_arr['Ekonomi']['nilai-pengetahuan'])) {
                echo $leger_arr['Ekonomi']['nilai-pengetahuan'];
            } else {
                echo "-";
            }
            echo "</td>";
            echo "<td>";
            if (!empty($leger_arr['Ekonomi']['nilai-keterampilan'])) {
                echo $leger_arr['Ekonomi']['nilai-keterampilan'];
            } else {
                echo "-";
            }
            echo "</td>";

            echo "<td>";
            if (!empty($leger_arr['Lintas Minat Bahasa Inggris']['nilai-pengetahuan'])) {
                echo $leger_arr['Lintas Minat Bahasa Inggris']['nilai-pengetahuan'];
            } else {
                echo "-";
            }
            echo "</td>";
            echo "<td>";
            if (!empty($leger_arr['Lintas Minat Bahasa Inggris']['nilai-keterampilan'])) {
                echo $leger_arr['Lintas Minat Bahasa Inggris']['nilai-keterampilan'];
            } else {
                echo "-";
            }
            echo "</td>";


            echo "<td>";
            if (!empty($leger_arr['Lintas Minat Bahasa Jepang']['nilai-pengetahuan'])) {
                echo $leger_arr['Lintas Minat Bahasa Jepang']['nilai-pengetahuan'];
            } else {
                echo "-";
            }
            echo "</td>";
            echo "<td>";
            if (!empty($leger_arr['Lintas Minat Bahasa Jepang']['nilai-keterampilan'])) {
                echo $leger_arr['Lintas Minat Bahasa Jepang']['nilai-keterampilan'];
            } else {
                echo "-";
            }
            echo "</td>";

            echo "<td>";
            if (!empty($leger_arr['Lintas Minat Biologi']['nilai-pengetahuan'])) {
                echo $leger_arr['Lintas Minat Biologi']['nilai-pengetahuan'];
            } else {
                echo "-";
            }
            echo "</td>";
            echo "<td>";
            if (!empty($leger_arr['Lintas Minat Biologi']['nilai-keterampilan'])) {
                echo $leger_arr['Lintas Minat Biologi']['nilai-keterampilan'];
            } else {
                echo "-";
            }
            echo "</td>";

            echo "<td>";
            if (!empty($leger_arr['Lintas Minat Ekonomi']['nilai-pengetahuan'])) {
                echo $leger_arr['Lintas Minat Ekonomi']['nilai-pengetahuan'];
            } else {
                echo "-";
            }
            echo "</td>";
            echo "<td>";
            if (!empty($leger_arr['Lintas Minat Ekonomi']['nilai-keterampilan'])) {
                echo $leger_arr['Lintas Minat Ekonomi']['nilai-keterampilan'];
            } else {
                echo "-";
            }
            echo "</td>";

            echo "<td>";
            if (!empty($leger_arr['Lintas Minat Geografi']['nilai-pengetahuan'])) {
                echo $leger_arr['Lintas Minat Geografi']['nilai-pengetahuan'];
            } else {
                echo "-";
            }
            echo "</td>";
            echo "<td>";
            if (!empty($leger_arr['Lintas Minat Geografi']['nilai-keterampilan'])) {
                echo $leger_arr['Lintas Minat Geografi']['nilai-keterampilan'];
            } else {
                echo "-";
            }
            echo "</td>";

            echo "<td>";
            if (!empty($leger_arr['Lintas Minat Sosiologi']['nilai-pengetahuan'])) {
                echo $leger_arr['Lintas Minat Sosiologi']['nilai-pengetahuan'];
            } else {
                echo "-";
            }
            echo "</td>";
            echo "<td>";
            if (!empty($leger_arr['Lintas Minat Sosiologi']['nilai-keterampilan'])) {
                echo $leger_arr['Lintas Minat Sosiologi']['nilai-keterampilan'];
            } else {
                echo "-";
            }
            echo "</td>";

            echo "<td>";
            if (!empty($leger_arr['Lintas Minat Bahasa Indonesia']['nilai-pengetahuan'])) {
                echo $leger_arr['Lintas Minat Bahasa Indonesia']['nilai-pengetahuan'];
            } else {
                echo "-";
            }
            echo "</td>";
            echo "<td>";
            if (!empty($leger_arr['Lintas Minat Bahasa Indonesia']['nilai-keterampilan'])) {
                echo $leger_arr['Lintas Minat Bahasa Indonesia']['nilai-keterampilan'];
            } else {
                echo "-";
            }
            echo "</td>";

            echo "<td>";
            if (!empty($leger_arr['Lintas Minat Fisika']['nilai-pengetahuan'])) {
                echo $leger_arr['Lintas Minat Fisika']['nilai-pengetahuan'];
            } else {
                echo "-";
            }
            echo "</td>";
            echo "<td>";
            if (!empty($leger_arr['Lintas Minat Fisika']['nilai-keterampilan'])) {
                echo $leger_arr['Lintas Minat Fisika']['nilai-keterampilan'];
            } else {
                echo "-";
            }
            echo "</td>";

            echo "<td>";
            if (!empty($leger_arr['Bahasa dan Sastra Indonesia']['nilai-pengetahuan'])) {
                echo $leger_arr['Bahasa dan Sastra Indonesia']['nilai-pengetahuan'];
            } else {
                echo "-";
            }
            echo "</td>";
            echo "<td>";
            if (!empty($leger_arr['Bahasa dan Sastra Indonesia']['nilai-keterampilan'])) {
                echo $leger_arr['Bahasa dan Sastra Indonesia']['nilai-keterampilan'];
            } else {
                echo "-";
            }
            echo "</td>";


            echo "<td>";
            if (!empty($leger_arr['Bahasa dan Sastra Inggris']['nilai-pengetahuan'])) {
                echo $leger_arr['Bahasa dan Sastra Inggris']['nilai-pengetahuan'];
            } else {
                echo "-";
            }
            echo "</td>";
            echo "<td>";
            if (!empty($leger_arr['Bahasa dan Sastra Inggris']['nilai-keterampilan'])) {
                echo $leger_arr['Bahasa dan Sastra Inggris']['nilai-keterampilan'];
            } else {
                echo "-";
            }
            echo "</td>";

            echo "<td>";
            if (!empty($leger_arr['Antropologi']['nilai-pengetahuan'])) {
                echo $leger_arr['Antropologi']['nilai-pengetahuan'];
            } else {
                echo "-";
            }
            echo "</td>";
            echo "<td>";
            if (!empty($leger_arr['Antropologi']['nilai-keterampilan'])) {
                echo $leger_arr['Antropologi']['nilai-keterampilan'];
            } else {
                echo "-";
            }
            echo "</td>";

            echo "<td>";
            if (!empty($leger_arr['Bahasa Arab']['nilai-pengetahuan'])) {
                echo $leger_arr['Bahasa Arab']['nilai-pengetahuan'];
            } else {
                echo "-";
            }
            echo "</td>";
            echo "<td>";
            if (!empty($leger_arr['Bahasa Arab']['nilai-keterampilan'])) {
                echo $leger_arr['Bahasa Arab']['nilai-keterampilan'];
            } else {
                echo "-";
            }
            echo "</td>";


            echo "<td>";
            if (!empty($leger_arr['Pendidikan Agama dan Budi Pekerti']['nilai-kddescription'])) {
                echo implode(",", $leger_arr['Pendidikan Agama dan Budi Pekerti']['nilai-kddescription']);
            } else {
                echo "-";
            }
            echo "</td>";
            echo "<td>";
            if (!empty($leger_arr['Pendidikan Agama dan Budi Pekerti']['nilai-kddescription_ket'])) {
                echo implode(",", $leger_arr['Pendidikan Agama dan Budi Pekerti']['nilai-kddescription_ket']);
            } else {
                echo "-";
            }
            echo "</td>";

            echo "<td>";
            if (!empty($leger_arr['Pendidikan Pancasila dan Kewarganegaraan']['nilai-kddescription'])) {
                echo implode(",", $leger_arr['Pendidikan Pancasila dan Kewarganegaraan']['nilai-kddescription']);
            } else {
                echo "-";
            }
            echo "</td>";
            echo "<td>";
            if (!empty($leger_arr['Pendidikan Pancasila dan Kewarganegaraan']['nilai-kddescription_ket'])) {
                echo implode(",", $leger_arr['Pendidikan Pancasila dan Kewarganegaraan']['nilai-kddescription_ket']);
            } else {
                echo "-";
            }
            echo "</td>";

            echo "<td>";
            if (!empty($leger_arr['Bahasa Indonesia']['nilai-kddescription'])) {
                echo implode(",", $leger_arr['Bahasa Indonesia']['nilai-kddescription']);
            } else {
                echo "-";
            }
            echo "</td>";
            echo "<td>";
            if (!empty($leger_arr['Bahasa Indonesia']['nilai-kddescription_ket'])) {
                echo implode(",", $leger_arr['Bahasa Indonesia']['nilai-kddescription_ket']);
            } else {
                echo "-";
            }
            echo "</td>";

            echo "<td>";
            if (!empty($leger_arr['Matematika']['nilai-kddescription'])) {
                echo implode(",", $leger_arr['Matematika']['nilai-kddescription']);
            } else {
                echo "-";
            }
            echo "</td>";
            echo "<td>";
            if (!empty($leger_arr['Matematika']['nilai-kddescription_ket'])) {
                echo implode(",", $leger_arr['Matematika']['nilai-kddescription_ket']);
            } else {
                echo "-";
            }
            echo "</td>";

            echo "<td>";
            if (!empty($leger_arr['Sejarah Indonesia']['nilai-kddescription'])) {
                echo implode(",", $leger_arr['Sejarah Indonesia']['nilai-kddescription']);
            } else {
                echo "-";
            }
            echo "</td>";
            echo "<td>";
            if (!empty($leger_arr['Sejarah Indonesia']['nilai-kddescription_ket'])) {
                echo implode(",", $leger_arr['Sejarah Indonesia']['nilai-kddescription_ket']);
            } else {
                echo "-";
            }
            echo "</td>";

            echo "<td>";
            if (!empty($leger_arr['Bahasa Inggris']['nilai-kddescription'])) {
                echo implode(",", $leger_arr['Bahasa Inggris']['nilai-kddescription']);
            } else {
                echo "-";
            }
            echo "</td>";
            echo "<td>";
            if (!empty($leger_arr['Bahasa Inggris']['nilai-kddescription_ket'])) {
                echo implode(",", $leger_arr['Bahasa Inggris']['nilai-kddescription_ket']);
            } else {
                echo "-";
            }
            echo "</td>";

            echo "<td>";
            if (!empty($leger_arr['Seni dan Budaya']['nilai-kddescription'])) {
                echo implode(",", $leger_arr['Seni dan Budaya']['nilai-kddescription']);
            } else {
                echo "-";
            }
            echo "</td>";
            echo "<td>";
            if (!empty($leger_arr['Seni dan Budaya']['nilai-kddescription_ket'])) {
                echo implode(",", $leger_arr['Seni dan Budaya']['nilai-kddescription_ket']);
            } else {
                echo "-";
            }
            echo "</td>";

            echo "<td>";
            if (!empty($leger_arr['Pendidikan Lingkungan Hidup']['nilai-kddescription'])) {
                echo implode(",", $leger_arr['Pendidikan Lingkungan Hidup']['nilai-kddescription']);
            } else {
                echo "-";
            }
            echo "</td>";
            echo "<td>";
            if (!empty($leger_arr['Pendidikan Lingkungan Hidup']['nilai-kddescription_ket'])) {
                echo implode(",", $leger_arr['Pendidikan Lingkungan Hidup']['nilai-kddescription_ket']);
            } else {
                echo "-";
            }
            echo "</td>";

            echo "<td>";
            if (!empty($leger_arr['Pendidikan Jasmani, Olah Raga, dan Kesehatan']['nilai-kddescription'])) {
                echo implode(",", $leger_arr['Pendidikan Jasmani, Olah Raga, dan Kesehatan']['nilai-kddescription']);
            } else {
                echo "-";
            }
            echo "</td>";
            echo "<td>";
            if (!empty($leger_arr['Pendidikan Jasmani, Olah Raga, dan Kesehatan']['nilai-kddescription_ket'])) {
                echo implode(",", $leger_arr['Pendidikan Jasmani, Olah Raga, dan Kesehatan']['nilai-kddescription_ket']);
            } else {
                echo "-";
            }
            echo "</td>";

            echo "<td>";
            if (!empty($leger_arr['Prakarya']['nilai-kddescription'])) {
                echo implode(",", $leger_arr['Prakarya']['nilai-kddescription']);
            } else {
                echo "-";
            }
            echo "</td>";
            echo "<td>";
            if (!empty($leger_arr['Prakarya']['nilai-kddescription_ket'])) {
                echo implode(",", $leger_arr['Prakarya']['nilai-kddescription_ket']);
            } else {
                echo "-";
            }
            echo "</td>";

            echo "<td>";
            if (!empty($leger_arr['Bahasa Sunda']['nilai-kddescription'])) {
                echo implode(",", $leger_arr['Bahasa Sunda']['nilai-kddescription']);
            } else {
                echo "-";
            }
            echo "</td>";
            echo "<td>";
            if (!empty($leger_arr['Bahasa Sunda']['nilai-kddescription_ket'])) {
                echo implode(",", $leger_arr['Bahasa Sunda']['nilai-kddescription_ket']);
            } else {
                echo "-";
            }
            echo "</td>";

            echo "<td>";
            if (!empty($leger_arr['Peminatan Matematika']['nilai-kddescription'])) {
                echo implode(",", $leger_arr['Peminatan Matematika']['nilai-kddescription']);
            } else {
                echo "-";
            }
            echo "</td>";
            echo "<td>";
            if (!empty($leger_arr['Peminatan Matematika']['nilai-kddescription_ket'])) {
                echo implode(",", $leger_arr['Peminatan Matematika']['nilai-kddescription_ket']);
            } else {
                echo "-";
            }
            echo "</td>";

            echo "<td>";
            if (!empty($leger_arr['Biologi']['nilai-kddescription'])) {
                echo implode(",", $leger_arr['Biologi']['nilai-kddescription']);
            } else {
                echo "-";
            }
            echo "</td>";
            echo "<td>";
            if (!empty($leger_arr['Biologi']['nilai-kddescription_ket'])) {
                echo implode(",", $leger_arr['Biologi']['nilai-kddescription_ket']);
            } else {
                echo "-";
            }
            echo "</td>";

            echo "<td>";
            if (!empty($leger_arr['Fisika']['nilai-kddescription'])) {
                echo implode(",", $leger_arr['Fisika']['nilai-kddescription']);
            } else {
                echo "-";
            }
            echo "</td>";
            echo "<td>";
            if (!empty($leger_arr['Fisika']['nilai-kddescription_ket'])) {
                echo implode(",", $leger_arr['Fisika']['nilai-kddescription_ket']);
            } else {
                echo "-";
            }
            echo "</td>";

            echo "<td>";
            if (!empty($leger_arr['Kimia']['nilai-kddescription'])) {
                echo implode(",", $leger_arr['Kimia']['nilai-kddescription']);
            } else {
                echo "-";
            }
            echo "</td>";
            echo "<td>";
            if (!empty($leger_arr['Kimia']['nilai-kddescription_ket'])) {
                echo implode(",", $leger_arr['Kimia']['nilai-kddescription_ket']);
            } else {
                echo "-";
            }
            echo "</td>";

            echo "<td>";
            if (!empty($leger_arr['Geografi']['nilai-kddescription'])) {
                echo implode(",", $leger_arr['Geografi']['nilai-kddescription']);
            } else {
                echo "-";
            }
            echo "</td>";
            echo "<td>";
            if (!empty($leger_arr['Geografi']['nilai-kddescription_ket'])) {
                echo implode(",", $leger_arr['Geografi']['nilai-kddescription_ket']);
            } else {
                echo "-";
            }
            echo "</td>";

            echo "<td>";
            if (!empty($leger_arr['Sejarah']['nilai-kddescription'])) {
                echo implode(",", $leger_arr['Sejarah']['nilai-kddescription']);
            } else {
                echo "-";
            }
            echo "</td>";
            echo "<td>";
            if (!empty($leger_arr['Sejarah']['nilai-kddescription_ket'])) {
                echo implode(",", $leger_arr['Sejarah']['nilai-kddescription_ket']);
            } else {
                echo "-";
            }
            echo "</td>";

            echo "<td>";
            if (!empty($leger_arr['Sosiologi']['nilai-kddescription'])) {
                echo implode(",", $leger_arr['Sosiologi']['nilai-kddescription']);
            } else {
                echo "-";
            }
            echo "</td>";
            echo "<td>";
            if (!empty($leger_arr['Sosiologi']['nilai-kddescription_ket'])) {
                echo implode(",", $leger_arr['Sosiologi']['nilai-kddescription_ket']);
            } else {
                echo "-";
            }
            echo "</td>";

            echo "<td>";
            if (!empty($leger_arr['Ekonomi']['nilai-kddescription'])) {
                echo implode(",", $leger_arr['Ekonomi']['nilai-kddescription']);
            } else {
                echo "-";
            }
            echo "</td>";
            echo "<td>";
            if (!empty($leger_arr['Ekonomi']['nilai-kddescription_ket'])) {
                echo implode(",", $leger_arr['Ekonomi']['nilai-kddescription_ket']);
            } else {
                echo "-";
            }
            echo "</td>";

            echo "<td>";
            if (!empty($leger_arr['Lintas Minat Bahasa Inggris']['nilai-kddescription'])) {
                echo implode(",", $leger_arr['Lintas Minat Bahasa Inggris']['nilai-kddescription']);
            } else {
                echo "-";
            }
            echo "</td>";
            echo "<td>";
            if (!empty($leger_arr['Lintas Minat Bahasa Inggris']['nilai-kddescription_ket'])) {
                echo implode(",", $leger_arr['Lintas Minat Bahasa Inggris']['nilai-kddescription_ket']);
            } else {
                echo "-";
            }
            echo "</td>";


            echo "<td>";
            if (!empty($leger_arr['Lintas Minat Bahasa Jepang']['nilai-kddescription'])) {
                echo implode(",", $leger_arr['Lintas Minat Bahasa Jepang']['nilai-kddescription']);
            } else {
                echo "-";
            }
            echo "</td>";
            echo "<td>";
            if (!empty($leger_arr['Lintas Minat Bahasa Jepang']['nilai-kddescription_ket'])) {
                echo implode($leger_arr['Lintas Minat Bahasa Jepang']['nilai-kddescription_ket']);
            } else {
                echo "-";
            }
            echo "</td>";

            echo "<td>";
            if (!empty($leger_arr['Lintas Minat Biologi']['nilai-kddescription'])) {
                echo implode(",", $leger_arr['Lintas Minat Biologi']['nilai-kddescription']);
            } else {
                echo "-";
            }
            echo "</td>";
            echo "<td>";
            if (!empty($leger_arr['Lintas Minat Biologi']['nilai-kddescription_ket'])) {
                echo implode(",", $leger_arr['Lintas Minat Biologi']['nilai-kddescription_ket']);
            } else {
                echo "-";
            }
            echo "</td>";

            echo "<td>";
            if (!empty($leger_arr['Lintas Minat Ekonomi']['nilai-kddescription'])) {
                echo implode(",", $leger_arr['Lintas Minat Ekonomi']['nilai-kddescription']);
            } else {
                echo "-";
            }
            echo "</td>";
            echo "<td>";
            if (!empty($leger_arr['Lintas Minat Ekonomi']['nilai-kddescription_ket'])) {
                echo implode(",", $leger_arr['Lintas Minat Ekonomi']['nilai-kddescription_ket']);
            } else {
                echo "-";
            }
            echo "</td>";

            echo "<td>";
            if (!empty($leger_arr['Lintas Minat Geografi']['nilai-kddescription'])) {
                echo implode(",", $leger_arr['Lintas Minat Geografi']['nilai-kddescription']);
            } else {
                echo "-";
            }
            echo "</td>";
            echo "<td>";
            if (!empty($leger_arr['Lintas Minat Geografi']['nilai-kddescription_ket'])) {
                echo implode(",", $leger_arr['Lintas Minat Geografi']['nilai-kddescription_ket']);
            } else {
                echo "-";
            }
            echo "</td>";

            echo "<td>";
            if (!empty($leger_arr['Lintas Minat Sosiologi']['nilai-kddescription'])) {
                echo implode(",", $leger_arr['Lintas Minat Sosiologi']['nilai-kddescription']);
            } else {
                echo "-";
            }
            echo "</td>";
            echo "<td>";
            if (!empty($leger_arr['Lintas Minat Sosiologi']['nilai-kddescription_ket'])) {
                echo implode(",", $leger_arr['Lintas Minat Sosiologi']['nilai-kddescription_ket']);
            } else {
                echo "-";
            }
            echo "</td>";

            echo "<td>";
            if (!empty($leger_arr['Lintas Minat Bahasa Indonesia']['nilai-kddescription'])) {
                echo implode(",", $leger_arr['Lintas Minat Bahasa Indonesia']['nilai-kddescription']);
            } else {
                echo "-";
            }
            echo "</td>";
            echo "<td>";
            if (!empty($leger_arr['Lintas Minat Bahasa Indonesia']['nilai-kddescription_ket'])) {
                echo implode(",", $leger_arr['Lintas Minat Bahasa Indonesia']['nilai-kddescription_ket']);
            } else {
                echo "-";
            }
            echo "</td>";

            echo "<td>";
            if (!empty($leger_arr['Lintas Minat Fisika']['nilai-kddescription'])) {
                echo implode(",", $leger_arr['Lintas Minat Fisika']['nilai-kddescription']);
            } else {
                echo "-";
            }
            echo "</td>";
            echo "<td>";
            if (!empty($leger_arr['Lintas Minat Fisika']['nilai-kddescription_ket'])) {
                echo implode(",", $leger_arr['Lintas Minat Fisika']['nilai-kddescription_ket']);
            } else {
                echo "-";
            }
            echo "</td>";

            echo "<td>";
            if (!empty($leger_arr['Bahasa dan Sastra Indonesia']['nilai-kddescription'])) {
                echo $leger_arr['Bahasa dan Sastra Indonesia']['nilai-kddescription'];
            } else {
                echo "-";
            }
            echo "</td>";
            echo "<td>";
            if (!empty($leger_arr['Bahasa dan Sastra Indonesia']['nilai-kddescription_ket'])) {
                echo $leger_arr['Bahasa dan Sastra Indonesia']['nilai-kddescription_ket'];
            } else {
                echo "-";
            }
            echo "</td>";


            echo "<td>";
            if (!empty($leger_arr['Bahasa dan Sastra Inggris']['nilai-kddescription'])) {
                echo $leger_arr['Bahasa dan Sastra Inggris']['nilai-kddescription'];
            } else {
                echo "-";
            }
            echo "</td>";
            echo "<td>";
            if (!empty($leger_arr['Bahasa dan Sastra Inggris']['nilai-kddescription_ket'])) {
                echo $leger_arr['Bahasa dan Sastra Inggris']['nilai-kddescription_ket'];
            } else {
                echo "-";
            }
            echo "</td>";

            echo "<td>";
            if (!empty($leger_arr['Antropologi']['nilai-kddescription'])) {
                echo $leger_arr['Antropologi']['nilai-kddescription'];
            } else {
                echo "-";
            }
            echo "</td>";
            echo "<td>";
            if (!empty($leger_arr['Antropologi']['nilai-kddescription_ket'])) {
                echo $leger_arr['Antropologi']['nilai-kddescription_ket'];
            } else {
                echo "-";
            }
            echo "</td>";

            echo "<td>";
            if (!empty($leger_arr['Bahasa Arab']['nilai-kddescription'])) {
                echo $leger_arr['Bahasa Arab']['nilai-kddescription'];
            } else {
                echo "-";
            }
            echo "</td>";
            echo "<td>";
            if (!empty($leger_arr['Bahasa Arab']['nilai-kddescription_ket'])) {
                echo $leger_arr['Bahasa Arab']['nilai-kddescription_ket'];
            } else {
                echo "-";
            }
            echo "</td>";


            $sql = "SELECT fm.`nilai_desc`,fm.`tipe`
				FROM `final_mark` as fm
				join `users` as u on u.`id` = fm.`user_id`
				WHERE fm.`user_id` = " . $siswa->id . "
				AND fm.`semester` = " . $optSemester . "
				AND fm.`tahun_ajaran` = " . $optTahunAjaran;
            $command = Yii::app()->db->createCommand($sql);
            $rows2 = $command->queryAll();

            $arr2 = array();
            $leger_arr2 = array();

            foreach ($rows2 as $key => $item) {
                // $item["nilai-".$item["tipe"]] = $item["nilai"];
                // $arr[$item['id']]["name"] = $item["name"];
                // $arr[$item['id']]["kelompok"] = $item["kelompok"];
                // $arr[$item['id']]["nilai-".$item["tipe"]] = $item["nilai"];
                // $leger_arr[$item["name"]]["kelompok"] = $item["kelompok"];
                // $leger_arr[$item["name"]]["nilai-".$item["tipe"]] = $item["nilai"];
                $leger_arr[$item["tipe"]] = $item["nilai_desc"];
            }

            $data[] = $arr;

            // //ksort($arr, SORT_NUMERIC);
            // echo "<pre>";
            // print_r($leger_arr);
            // echo "</pre>";

            echo "<td colspan='2'>";
            if (!empty($leger_arr['Sikap Sosial - Predikat'])) {
                echo $leger_arr['Sikap Sosial - Predikat'];
            } else {
                echo "-";
            }
            echo "</td>";

            echo "<td colspan='2'>";
            if (!empty($leger_arr['Sikap Sosial - Deskripsi'])) {
                echo $leger_arr['Sikap Sosial - Deskripsi'];
            } else {
                echo "-";
            }
            echo "</td>";

            echo "<td colspan='2'>";
            if (!empty($leger_arr['Sikap Spiritual - Predikat'])) {
                echo $leger_arr['Sikap Spiritual - Predikat'];
            } else {
                echo "-";
            }
            echo "</td>";

            echo "<td colspan='2'>";
            if (!empty($leger_arr['Sikap Spiritual - Deskripsi'])) {
                echo $leger_arr['Sikap Spiritual - Deskripsi'];
            } else {
                echo "-";
            }
            echo "</td>";


            echo "<td colspan='2'>";
            if (!empty($leger_arr['Absensi Sakit'])) {
                echo $leger_arr['Absensi Sakit'];
            } else {
                echo "-";
            }
            echo "</td>";

            echo "<td colspan='2'>";
            if (!empty($leger_arr['Absensi Izin'])) {
                echo $leger_arr['Absensi Izin'];
            } else {
                echo "-";
            }
            echo "</td>";

            echo "<td colspan='2'>";
            if (!empty($leger_arr['Absensi Alfa'])) {
                echo $leger_arr['Absensi Alfa'];
            } else {
                echo "-";
            }
            echo "</td>";

            echo "<td colspan='2'>";
            if (!empty($leger_arr['Ekstrakurikuler 1 - Nama'])) {
                echo $leger_arr['Ekstrakurikuler 1 - Nama'];
            } else {
                echo "-";
            }
            echo "</td>";

            echo "<td colspan='2'>";
            if (!empty($leger_arr['Ekstrakurikuler 1 - Nilai'])) {
                echo $leger_arr['Ekstrakurikuler 1 - Nilai'];
            } else {
                echo "-";
            }
            echo "</td>";

            echo "<td colspan='2'>";
            if (!empty($leger_arr['Ekstrakurikuler 1 - Deskripsi'])) {
                echo $leger_arr['Ekstrakurikuler 1 - Deskripsi'];
            } else {
                echo "-";
            }
            echo "</td>";

            echo "<td colspan='2'>";
            if (!empty($leger_arr['Ekstrakurikuler 2 - Nama'])) {
                echo $leger_arr['Ekstrakurikuler 2 - Nama'];
            } else {
                echo "-";
            }
            echo "</td>";

            echo "<td colspan='2'>";
            if (!empty($leger_arr['Ekstrakurikuler 2 - Nilai'])) {
                echo $leger_arr['Ekstrakurikuler 2 - Nilai'];
            } else {
                echo "-";
            }
            echo "</td>";

            echo "<td colspan='2'>";
            if (!empty($leger_arr['Ekstrakurikuler 2 - Deskripsi'])) {
                echo $leger_arr['Ekstrakurikuler 2 - Deskripsi'];
            } else {
                echo "-";
            }
            echo "</td>";

            echo "<td colspan='2'>";
            if (!empty($leger_arr['Ekstrakurikuler 3 - Nama'])) {
                echo $leger_arr['Ekstrakurikuler 3 - Nama'];
            } else {
                echo "-";
            }
            echo "</td>";

            echo "<td colspan='2'>";
            if (!empty($leger_arr['Ekstrakurikuler 3 – Nilai'])) {
                echo $leger_arr['Ekstrakurikuler 3 – Nilai'];
            } else {
                echo "-";
            }
            echo "</td>";

            echo "<td colspan='2'>";
            if (!empty($leger_arr['Ekstrakurikuler 3 - Deskripsi'])) {
                echo $leger_arr['Ekstrakurikuler 3 - Deskripsi'];
            } else {
                echo "-";
            }
            echo "</td>";

            echo "<td colspan='2'>";
            if (!empty($leger_arr['Prestasi 1 - Jenis Kegiatan'])) {
                echo $leger_arr['Prestasi 1 - Jenis Kegiatan'];
            } else {
                echo "-";
            }
            echo "</td>";

            echo "<td colspan='2'>";
            if (!empty($leger_arr['Prestasi 1 - Keterangan'])) {
                echo $leger_arr['Prestasi 1 - Keterangan'];
            } else {
                echo "-";
            }
            echo "</td>";

            echo "<td colspan='2'>";
            if (!empty($leger_arr['Prestasi 2 - Jenis Kegiatan'])) {
                echo $leger_arr['Prestasi 2 - Jenis Kegiatan'];
            } else {
                echo "-";
            }
            echo "</td>";

            echo "<td colspan='2'>";
            if (!empty($leger_arr['Prestasi 2 - Keterangan'])) {
                echo $leger_arr['Prestasi 2 - Keterangan'];
            } else {
                echo "-";
            }
            echo "</td>";

            echo "<td colspan='2'>";
            if (!empty($leger_arr['Prestasi 3 - Jenis Kegiatan'])) {
                echo $leger_arr['Prestasi 3 - Jenis Kegiatan'];
            } else {
                echo "-";
            }
            echo "</td>";

            echo "<td colspan='2'>";
            if (!empty($leger_arr['Prestasi 3 - Keterangan'])) {
                echo $leger_arr['Prestasi 3 - Keterangan'];
            } else {
                echo "-";
            }
            echo "</td>";


            echo "<td colspan='2'>";
            if (!empty($leger_arr['Prestasi 4 - Jenis Kegiatan'])) {
                echo $leger_arr['Prestasi 4 - Jenis Kegiatan'];
            } else {
                echo "-";
            }
            echo "</td>";

            echo "<td colspan='2'>";
            if (!empty($leger_arr['Prestasi 4 - Keterangan'])) {
                echo $leger_arr['Prestasi 4 - Keterangan'];
            } else {
                echo "-";
            }
            echo "</td>";

            echo "<td colspan='2'>";
            if (!empty($leger_arr['Catatan Wali Kelas'])) {
                echo $leger_arr['Catatan Wali Kelas'];
            } else {
                echo "-";
            }
            echo "</td>";


            echo "</tr>";
        }

        echo "</table>";
        echo "</body>";
        echo "</div>";
        // $this->renderPartial('/clases/');
        // $this->renderPartial('/clases/raport-siswa-uts-all',array('inline_style'=>$inline_style,'siswa'=>$user,'peluts'=>$data,'profil'=>$user_data,'model'=>$model));
    }

    public function actionLegerDh($id) {
        if (Yii::app()->session['semester']) {
            $optSemester = Yii::app()->session['semester'];
            $optSemester_db = Option::model()->findByAttributes(array('key_config' => 'semester'))->value;
        } else {
            $optSemester = Option::model()->findByAttributes(array('key_config' => 'semester'))->value;
        }
        if (Yii::app()->session['tahun_ajaran']) {
            $optTahunAjaran = Yii::app()->session['tahun_ajaran'];
            $optTahunAjaran_db = Option::model()->findByAttributes(array('key_config' => 'tahun_ajaran'))->value;
        } else {
            $optTahunAjaran = Option::model()->findByAttributes(array('key_config' => 'tahun_ajaran'))->value;
        }


        if ($optTahunAjaran == $optTahunAjaran_db) {
            $getsiswa = User::model()->findAll(array('condition' => 'class_id = ' . $id . '', 'order' => 'display_name  '));
        } else {
            $getsiswa = ClassHistory::model()->findAll(array('condition' => 'class_id = ' . $id . '', 'order' => 'id'));
        }





        $base = Yii::app()->baseUrl;

        $template_directory = Yii::app()->theme->baseUrl;
        $template_file = Yii::app()->theme->basePath . '/css/print.bootstrap.min.css';
        $inline_style = file_get_contents($template_file);
        $icon_sprite = Yii::app()->theme->basePath . '/images/glyphicons-halflings.png';
        $inline_style = str_replace('../images/glyphicons-halflings.png', $icon_sprite, $inline_style);
        $model = ClassDetail::model()->findByPk($id);


        if (!empty($_GET['print'])) {
            $file = "leger.xls";
            header("Content-type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename=$file"); # code...
        }


        echo "<html>";

        echo "<div id='dvData'>";
        echo "<table border='1'>";
        echo "<tr>";
        echo "<th>";
        echo "Nama Siswa Kelas " . $getsiswa[0]->class->name;
        ;
        echo "</th>";
        echo "<th colspan='4'>";
        echo "Pendidikan Agama dan Budi Pekerti (A)";
        echo "</th>";
        echo "<th colspan='4'>";
        echo "Pendidikan Pancasila dan Kewarganegaraan (A)";
        echo "</th>";
        echo "<th colspan='4'>";
        echo "Bahasa Indonesia (A)";
        echo "</th>";
        echo "<th colspan='4'>";
        echo "Matematika (A)";
        echo "</th>";
        echo "<th colspan='4'>";
        echo "Bahasa Inggris (A)";
        echo "</th>";
        echo "<th colspan='4'>";
        echo "Seni dan Budaya (B)";
        echo "</th>";
        echo "<th colspan='4'>";
        echo "Pendidikan Jasmani, Olah Raga, dan Kesehatan (B)";
        echo "</th>";
        echo "<th colspan='4'>";
        echo "Prakarya (B)";
        echo "</th>";
        echo "<th colspan='4'>";
        echo "Bahasa Sunda (B)";
        echo "</th>";
        echo "<th colspan='4'>";
        echo "IPS (C)";
        echo "</th>";
        echo "<th colspan='4'>";
        echo "IPA (C)";
        echo "</th>";
       
        
       


        echo "</tr>";
        echo "<tr>";
        echo "<td>";
        echo "TIPE";
        echo "</td>";
        echo "<td>";
        echo "P";
        echo "</td>";
        echo "<td>";
        echo "K";
        echo "</td>";
        echo "<td>";
        echo "P";
        echo "</td>";
        echo "<td>";
        echo "K";
        echo "</td>";
        echo "<td>";
        echo "P";
        echo "</td>";
        echo "<td>";
        echo "K";
        echo "</td>";
        echo "<td>";
        echo "P";
        echo "</td>";
        echo "<td>";
        echo "K";
        echo "</td>";
         echo "<td>";
        echo "P";
        echo "</td>";
        echo "<td>";
        echo "K";
        echo "</td>";
        echo "<td>";
        echo "P";
        echo "</td>";
        echo "<td>";
        echo "K";
        echo "</td>";
        echo "<td>";
        echo "P";
        echo "</td>";
        echo "<td>";
        echo "K";
        echo "</td>";
        echo "<td>";
        echo "P";
        echo "</td>";
        echo "<td>";
        echo "K";
        echo "</td>";
        echo "<td>";
        echo "P";
        echo "</td>";
        echo "<td>";
        echo "K";
        echo "</td>";
        echo "<td>";
        echo "P";
        echo "</td>";
        echo "<td>";
        echo "K";
         echo "</td>";
        echo "<td>";
        echo "P";
        echo "</td>";
        echo "<td>";
        echo "K";
        echo "</td>";
       
        echo "<td>";
        echo "P";
        echo "</td>";
        echo "<td>";
        echo "K";
        echo "</td>";
        echo "<td>";
        echo "P";
        echo "</td>";
        echo "<td>";
        echo "K";
        echo "</td>";
        echo "<td>";
        echo "P";
        echo "</td>";
        echo "<td>";
        echo "K";
        echo "</td>";
        echo "<td>";
        echo "P";
        echo "</td>";
        echo "<td>";
        echo "K";
        echo "</td>";
         echo "<td>";
        echo "P";
        echo "</td>";
        echo "<td>";
        echo "K";
        echo "</td>";
        echo "<td>";
        echo "P";
        echo "</td>";
        echo "<td>";
        echo "K";
        echo "</td>";
        echo "<td>";
        echo "P";
        echo "</td>";
        echo "<td>";
        echo "K";
        echo "</td>";
        echo "<td>";
        echo "P";
        echo "</td>";
        echo "<td>";
        echo "K";
        echo "</td>";
        echo "<td>";
        echo "P";
        echo "</td>";
        echo "<td>";
        echo "K";
        echo "</td>";
        echo "<td>";
        echo "P";
        echo "</td>";
        echo "<td>";
        echo "K";
         echo "</td>";
        echo "<td>";
        echo "P";
        echo "</td>";
        echo "<td>";
        echo "K";
        echo "</td>";
        echo "</tr>";


        foreach ($getsiswa as $siswa) {
            // echo "<pre>";
            // echo $siswa->id;
            //print_r($siswa);
            // echo "</pre>";

            if ($optTahunAjaran == $optTahunAjaran_db) {
                //echo $siswa->display_name;
            } else {
                $siswa = $siswa->user;
            }

            $user[] = $siswa;
            $user_data[] = UserProfile::model()->findByAttributes(array('user_id' => $siswa->id));

            // $nama = str_replace(" ", "-" , $user->display_name);
            // $model = ClassDetail::model()->findByPk($user->class_id);
            // /*Default DOMPDF File Path*/



            $sql = "SELECT l.`id`,l.`name`,fm.`nilai`,fm.`nilai_desc`,fm.`tipe`,l.`kelompok`,l.`list_id`,lk.`description`
                FROM `final_mark` as fm
                join `users` as u on u.`id` = fm.`user_id`
                join `lesson` as l on fm.`lesson_id` = l.`id`
                left join `lesson_kd` as lk on fm.`lesson_id` = lk.`lesson_id` AND fm.`tipe` = lk.`title`
                WHERE fm.`user_id` = " . $siswa->id . "
                AND fm.`semester` = " . $optSemester . "
                AND fm.`tahun_ajaran` = " . $optTahunAjaran . "
                AND l.`semester` = " . $optSemester . "
                AND l.`year` = " . $optTahunAjaran . "
                AND l.`trash` is null
                order by l.`list_id`";
            $command = Yii::app()->db->createCommand($sql);
            $rows = $command->queryAll();

            $arr = array();
            $leger_arr = array();
            $rnh = array();
            $rnh_ket = array();
            $nh = 0;
            $nuas = 0;
            $npeng_m = 0;
            $rnt = 0;
            $r_ket = 0;
            $r_nh = 0;

            foreach ($rows as $key => $item) {
                // $item["nilai-".$item["tipe"]] = $item["nilai"];
                // $arr[$item['id']]["name"] = $item["name"];
                // $arr[$item['id']]["kelompok"] = $item["kelompok"];
                // $arr[$item['id']]["nilai-".$item["tipe"]] = $item["nilai"];
                // if(!empty($leger_arr[$item["name"]]['nilai-kddescription']) && count($leger_arr[$item["name"]]['nilai-kddescription']) <7 ) {
                $sql9 = "SELECT lk.`description`
                FROM `lesson_kd` as lk
                WHERE lk.`lesson_id` = " . $item["id"] . "
                AND lk.`title` != 'KD1_ket'
                AND lk.`title` != 'KD2_ket'
                AND lk.`title` != 'KD3_ket'
                AND lk.`title` != 'KD4_ket'
                AND lk.`title` != 'KD5_ket'
                AND lk.`title` != 'KD6_ket'
                AND lk.`title` != 'KD7_ket'";
                $command9 = Yii::app()->db->createCommand($sql9);
                $rows9 = $command9->queryAll();

                if (!empty($rows9)) {
                    $itung = 0;
                    foreach ($rows9 as $key9 => $value9) {
                        $leger_arr[$item["name"]]['nilai-kddescription'][$itung] = $value9['description'];
                        $itung++;
                        if (count($leger_arr[$item["name"]]['nilai-kddescription']) > 7) {
                            unset($leger_arr[$item["name"]]['nilai-kddescription'][$itung]);
                        }
                    }
                }
                // }

                $leger_arr[$item["name"]]["kelompok"] = $item["kelompok"];
                $leger_arr[$item["name"]]["nilai-" . $item["tipe"]] = $item["nilai"];
                $leger_arr[$item['name']]["desc-" . $item["tipe"]] = $item["nilai_desc"];

                if ($item["tipe"] == "kd1" or $item["tipe"] == "kd2" or $item["tipe"] == "kd3" or $item["tipe"] == "kd4" or $item["tipe"] == "kd5" or $item["tipe"] == "kd6" or $item["tipe"] == "kd7") {
                    $leger_arr[$item["name"]]['rnh'][] = $item['nilai'];
                    // if($item['description'] != null ){
                    //      $leger_arr[$item["name"]]['nilai-kddescription'][]=$item['description'];
                    // }
                }


                if ($item["tipe"] == "kd1_ket" or $item["tipe"] == "kd2_ket" or $item["tipe"] == "kd3_ket" or $item["tipe"] == "kd4_ket" or $item["tipe"] == "kd5_ket" or $item["tipe"] == "kd6_ket" or $item["tipe"] == "kd7_ket") {
                    $leger_arr[$item["name"]]['rnh_ket'][] = $item['nilai'];
                }


                if ($item['tipe'] == "tugas") {
                    $leger_arr[$item["name"]]['rnt'] = $item['nilai'];
                }

                if ($item['tipe'] == "uas") {
                    $leger_arr[$item["name"]]['nuas'] = $item['nilai'];
                }
            }




            $data[] = $arr;

            // //ksort($arr, SORT_NUMERIC);




            foreach ($leger_arr as $key => $value) {
                if (empty($leger_arr[$key]['nilai-pengetahuan'])) {
                    if (!empty($leger_arr[$key]['nuas']) && !empty($leger_arr[$key]['rnh'])) {

                        if (!empty($leger_arr[$key]['rnt'])) {
                            $r_nh = (2 * (round(array_sum($leger_arr[$key]['rnh']) / count($leger_arr[$key]['rnh']))) + $leger_arr[$key]['rnt']) / 3;
                            $leger_arr[$key]['nilai-pengetahuan'] = round((2 * round($r_nh) + $leger_arr[$key]['nuas']) / 3);
                        } else {
                            $r_nh = round(array_sum($leger_arr[$key]['rnh']) / count($leger_arr[$key]['rnh']));
                            $leger_arr[$key]['nilai-pengetahuan'] = round((2 * round($r_nh) + $leger_arr[$key]['nuas']) / 3);
                        }
                    } else {
                        $leger_arr[$key]['nilai-pengetahuan'] = "-";
                    }
                }

                if (empty($leger_arr[$key]['nilai-keterampilan'])) {

                    if (!empty($leger_arr[$key]['rnh_ket'])) {
                        $leger_arr[$key]['nilai-keterampilan'] = round(array_sum($leger_arr[$key]['rnh_ket']) / count($leger_arr[$key]['rnh_ket']));
                    } else {

                        $leger_arr[$key]['nilai-keterampilan'] = "-";
                    }
                }


                if (!empty($leger_arr[$key]['desc-desc_pengetahuan'])) {
                    $leger_arr[$key]['nilai-kddescription'] = array();
                    $leger_arr[$key]['nilai-kddescription'][] = $leger_arr[$key]['desc-desc_pengetahuan'];
                }

                if (!empty($leger_arr[$key]['desc-desc_keterampilan'])) {
                    $leger_arr[$key]['nilai-kddescription_ket'][] = $leger_arr[$key]['desc-desc_keterampilan'];
                }

                // if (empty($leger_arr[$key]['desc-desc_keterampilan'])) { 
                //  $leger_arr[$key]['nilai-kddescriptionket'] = $leger_arr[$key]['desc-desc_keterampilan'];
                // }        
            }

            // echo "<pre>";
            // print_r($leger_arr);
            // echo "</pre>";



            echo "<tr>";
            echo "<td>";
            echo $siswa->display_name;
            echo "</td>";
            echo "<td>";
            if (!empty($leger_arr['Pendidikan Agama dan Budi Pekerti']['nilai-uts_p'])) {
                echo $leger_arr['Pendidikan Agama dan Budi Pekerti']['nilai-uts_p'];
            } else {
                echo "-";
            }
            echo "</td>";
            echo "<td>";
            if (!empty($leger_arr['Pendidikan Agama dan Budi Pekerti']['nilai-uts_k'])) {
                echo $leger_arr['Pendidikan Agama dan Budi Pekerti']['nilai-uts_k'];
            } else {
                echo "-";
            }
            echo "</td>";

            echo "<td>";
            if (!empty($leger_arr['Pendidikan Agama dan Budi Pekerti']['nilai-uas'])) {
                echo $leger_arr['Pendidikan Agama dan Budi Pekerti']['nilai-uas'];
            } else {
                echo "-";
            }
            echo "</td>";
            echo "<td>";
            if (!empty($leger_arr['Pendidikan Agama dan Budi Pekerti']['nilai-kd3_ket'])) {
                echo $leger_arr['Pendidikan Agama dan Budi Pekerti']['nilai-kd3_ket'];
            } else {
                echo "-";
            }
            echo "</td>";


            echo "<td>";
            if (!empty($leger_arr['Pendidikan Pancasila dan Kewarganegaraan']['nilai-uts_p'])) {
                echo $leger_arr['Pendidikan Pancasila dan Kewarganegaraan']['nilai-uts_p'];
            } else {
                echo "-";
            }
            echo "</td>";
            echo "<td>";
            if (!empty($leger_arr['Pendidikan Pancasila dan Kewarganegaraan']['nilai-uts_k'])) {
                echo $leger_arr['Pendidikan Pancasila dan Kewarganegaraan']['nilai-uts_k'];
            } else {
                echo "-";
            }
            echo "</td>";


             echo "<td>";
            if (!empty($leger_arr['Pendidikan Pancasila dan Kewarganegaraan']['nilai-uas'])) {
                echo $leger_arr['Pendidikan Pancasila dan Kewarganegaraan']['nilai-uas'];
            } else {
                echo "-";
            }
            echo "</td>";
            echo "<td>";
            if (!empty($leger_arr['Pendidikan Pancasila dan Kewarganegaraan']['nilai-kd3_ket'])) {
                echo $leger_arr['Pendidikan Pancasila dan Kewarganegaraan']['nilai-kd3_ket'];
            } else {
                echo "-";
            }
            echo "</td>";

            echo "<td>";
            if (!empty($leger_arr['Bahasa Indonesia']['nilai-uts_p'])) {
                echo $leger_arr['Bahasa Indonesia']['nilai-uts_p'];
            } else {
                echo "-";
            }
            echo "</td>";
            echo "<td>";
            if (!empty($leger_arr['Bahasa Indonesia']['nilai-uts_k'])) {
                echo $leger_arr['Bahasa Indonesia']['nilai-uts_k'];
            } else {
                echo "-";
            }
            echo "</td>";


            echo "<td>";
            if (!empty($leger_arr['Bahasa Indonesia']['nilai-uas'])) {
                echo $leger_arr['Bahasa Indonesia']['nilai-uas'];
            } else {
                echo "-";
            }
            echo "</td>";
            echo "<td>";
            if (!empty($leger_arr['Bahasa Indonesia']['nilai-kd3_ket'])) {
                echo $leger_arr['Bahasa Indonesia']['nilai-kd3_ket'];
            } else {
                echo "-";
            }
            echo "</td>";

            echo "<td>";
            if (!empty($leger_arr['Matematika']['nilai-uts_p'])) {
                echo $leger_arr['Matematika']['nilai-uts_p'];
            } else {
                echo "-";
            }
            echo "</td>";
            echo "<td>";
            if (!empty($leger_arr['Matematika']['nilai-uts_k'])) {
                echo $leger_arr['Matematika']['nilai-uts_k'];
            } else {
                echo "-";
            }
            echo "</td>";




             echo "<td>";
            if (!empty($leger_arr['Matematika']['nilai-uas'])) {
                echo $leger_arr['Matematika']['nilai-uas'];
            } else {
                echo "-";
            }
            echo "</td>";
            echo "<td>";
            if (!empty($leger_arr['Matematika']['nilai-kd3_ket'])) {
                echo $leger_arr['Matematika']['nilai-kd3_ket'];
            } else {
                echo "-";
            }
            echo "</td>";


            echo "<td>";
            if (!empty($leger_arr['Bahasa Inggris']['nilai-uts_p'])) {
                echo $leger_arr['Bahasa Inggris']['nilai-uts_p'];
            } else {
                echo "-";
            }
            echo "</td>";
            echo "<td>";
            if (!empty($leger_arr['Bahasa Inggris']['nilai-uts_k'])) {
                echo $leger_arr['Bahasa Inggris']['nilai-uts_k'];
            } else {
                echo "-";
            }
            echo "</td>";


             echo "<td>";
            if (!empty($leger_arr['Bahasa Inggris']['nilai-uas'])) {
                echo $leger_arr['Bahasa Inggris']['nilai-uas'];
            } else {
                echo "-";
            }
            echo "</td>";
            echo "<td>";
            if (!empty($leger_arr['Bahasa Inggris']['nilai-kd3_ket'])) {
                echo $leger_arr['Bahasa Inggris']['nilai-kd3_ket'];
            } else {
                echo "-";
            }
            echo "</td>";

            echo "<td>";
            if (!empty($leger_arr['Seni dan Budaya']['nilai-uts_p'])) {
                echo $leger_arr['Seni dan Budaya']['nilai-uts_p'];
            } else {
                echo "-";
            }
            echo "</td>";
            echo "<td>";
            if (!empty($leger_arr['Seni dan Budaya']['nilai-uts_k'])) {
                echo $leger_arr['Seni dan Budaya']['nilai-uts_k'];
            } else {
                echo "-";
            }
            echo "</td>";

             echo "<td>";
            if (!empty($leger_arr['Seni dan Budaya']['nilai-uas'])) {
                echo $leger_arr['Seni dan Budaya']['nilai-uas'];
            } else {
                echo "-";
            }
            echo "</td>";
            echo "<td>";
            if (!empty($leger_arr['Seni dan Budaya']['nilai-kd3_ket'])) {
                echo $leger_arr['Seni dan Budaya']['nilai-kd3_ket'];
            } else {
                echo "-";
            }
            echo "</td>";

            echo "<td>";
            if (!empty($leger_arr['Pendidikan Jasmani, Olah Raga, dan Kesehatan']['nilai-uts_p'])) {
                echo $leger_arr['Pendidikan Jasmani, Olah Raga, dan Kesehatan']['nilai-uts_p'];
            } else {
                echo "-";
            }
            echo "</td>";
            echo "<td>";
            if (!empty($leger_arr['Pendidikan Jasmani, Olah Raga, dan Kesehatan']['nilai-uts_k'])) {
                echo $leger_arr['Pendidikan Jasmani, Olah Raga, dan Kesehatan']['nilai-uts_k'];
            } else {
                echo "-";
            }
            echo "</td>";

             echo "<td>";
            if (!empty($leger_arr['Pendidikan Jasmani, Olah Raga, dan Kesehatan']['nilai-uas'])) {
                echo $leger_arr['Pendidikan Jasmani, Olah Raga, dan Kesehatan']['nilai-uas'];
            } else {
                echo "-";
            }
            echo "</td>";
            echo "<td>";
            if (!empty($leger_arr['Pendidikan Jasmani, Olah Raga, dan Kesehatan']['nilai-kd3_ket'])) {
                echo $leger_arr['Pendidikan Jasmani, Olah Raga, dan Kesehatan']['nilai-kd3_ket'];
            } else {
                echo "-";
            }
            echo "</td>";

            echo "<td>";
            if (!empty($leger_arr['Prakarya']['nilai-uts_p'])) {
                echo $leger_arr['Prakarya']['nilai-uts_p'];
            } else {
                echo "-";
            }
            echo "</td>";
            echo "<td>";
            if (!empty($leger_arr['Prakarya']['nilai-uts_k'])) {
                echo $leger_arr['Prakarya']['nilai-uts_k'];
            } else {
                echo "-";
            }
            echo "</td>";

            echo "<td>";
            if (!empty($leger_arr['Prakarya']['nilai-uas'])) {
                echo $leger_arr['Prakarya']['nilai-uas'];
            } else {
                echo "-";
            }
            echo "</td>";
            echo "<td>";
            if (!empty($leger_arr['Prakarya']['nilai-kd3_ket'])) {
                echo $leger_arr['Prakarya']['nilai-kd3_ket'];
            } else {
                echo "-";
            }
            echo "</td>";

            echo "<td>";
            if (!empty($leger_arr['Bahasa Sunda']['nilai-uts_p'])) {
                echo $leger_arr['Bahasa Sunda']['nilai-uts_p'];
            } else {
                echo "-";
            }
            echo "</td>";
            echo "<td>";
            if (!empty($leger_arr['Bahasa Sunda']['nilai-uts_k'])) {
                echo $leger_arr['Bahasa Sunda']['nilai-uts_k'];
            } else {
                echo "-";
            }
            echo "</td>";


             echo "<td>";
            if (!empty($leger_arr['Bahasa Sunda']['nilai-uas'])) {
                echo $leger_arr['Bahasa Sunda']['nilai-uas'];
            } else {
                echo "-";
            }
            echo "</td>";
            echo "<td>";
            if (!empty($leger_arr['Bahasa Sunda']['nilai-kd3_ket'])) {
                echo $leger_arr['Bahasa Sunda']['nilai-kd3_ket'];
            } else {
                echo "-";
            }
            echo "</td>";

           

              echo "<td>";
            if (!empty($leger_arr['Ilmu Pengetahuan Sosial']['nilai-uts_p'])) {
                echo $leger_arr['Ilmu Pengetahuan Sosial']['nilai-uts_p'];
            } else {
                echo "-";
            }
            echo "</td>";
            echo "<td>";
            if (!empty($leger_arr['Ilmu Pengetahuan Sosial']['nilai-uts_k'])) {
                echo $leger_arr['Ilmu Pengetahuan Sosial']['nilai-uts_k'];
            } else {
                echo "-";
            }
            echo "</td>";


              echo "<td>";
            if (!empty($leger_arr['Ilmu Pengetahuan Sosial']['nilai-uas'])) {
                echo $leger_arr['Ilmu Pengetahuan Sosial']['nilai-uas'];
            } else {
                echo "-";
            }
            echo "</td>";
            echo "<td>";
            if (!empty($leger_arr['Ilmu Pengetahuan Sosial']['nilai-kd3_ket'])) {
                echo $leger_arr['Ilmu Pengetahuan Sosial']['nilai-kd3_ket'];
            } else {
                echo "-";
            }
            echo "</td>";


             echo "<td>";
            if (!empty($leger_arr['Ilmu Pengetahuan Alam']['nilai-uts_p'])) {
                echo $leger_arr['Ilmu Pengetahuan Alam']['nilai-uts_p'];
            } else {
                echo "-";
            }
            echo "</td>";
            echo "<td>";
            if (!empty($leger_arr['Ilmu Pengetahuan Alam']['nilai-uts_k'])) {
                echo $leger_arr['Ilmu Pengetahuan Alam']['nilai-uts_k'];
            } else {
                echo "-";
            }
            echo "</td>";

              echo "<td>";
            if (!empty($leger_arr['Ilmu Pengetahuan Alam']['nilai-uas'])) {
                echo $leger_arr['Ilmu Pengetahuan Alam']['nilai-uas'];
            } else {
                echo "-";
            }
            echo "</td>";
            echo "<td>";
            if (!empty($leger_arr['Ilmu Pengetahuan Alam']['nilai-kd3_ket'])) {
                echo $leger_arr['Ilmu Pengetahuan Alam']['nilai-kd3_ket'];
            } else {
                echo "-";
            }
            echo "</td>";

           


         
        }

        echo "</table>";
        echo "</body>";
        echo "</div>";
        // $this->renderPartial('/clases/');
        // $this->renderPartial('/clases/raport-siswa-uts-all',array('inline_style'=>$inline_style,'siswa'=>$user,'peluts'=>$data,'profil'=>$user_data,'model'=>$model));
    }

    public function actionRaportSiswa($id, $type = NULL) {
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

        $cekFitur = Option::model()->findAll(array('condition' => '(key_config) LIKE "%fitur_rekap%"'));
        $cekKurikulum = Option::model()->findAll(array('condition' => 'lower(key_config) LIKE "%kurikulum%"'));
        //$status = 1;

        if (!empty($cekFitur)) {
            if ($cekFitur[0]->value == 2) {
                Yii::app()->user->setFlash('error', 'Fitur Ini Dimatikan !');
                $this->redirect(array('/site/index'));
            }
        }

        $base = Yii::app()->baseUrl;
        $user = User::model()->findByPk($id);
        $my_lesson = LessonMc::model()->findAll(array('condition' => 'user_id = ' . $id . ' and semester =' . $optSemester . ' and year = ' . $optTahunAjaran . ' and trash is null', 'order' => 'lesson_id'));
        $lesson_list = array();
        $ll = NULL;
        $mapel = NULL;
        $mapel1 = NULL;
        $mapel2 = NULL;
        $mapel4 = NULL;

        if (!empty($my_lesson)) {
            foreach ($my_lesson as $value) {
                array_push($lesson_list, $value->lesson_id);
            }
        }

        if (!empty($lesson_list)) {
            $ll = implode(',', $lesson_list);
            $mapel = Lesson::model()->findAll(array('condition' => 'class_id = ' . $user->class_id . ' and semester = ' . $optSemester . ' and year = ' . $optTahunAjaran . ' and trash is null'));
            $mapel1 = Lesson::model()->findAll(array('condition' => 'class_id = ' . $user->class_id . ' and semester = ' . $optSemester . ' and year = ' . $optTahunAjaran . ' and kelompok = 1 and moving_class = 0 and id in (' . $ll . ') and trash is null'));
            $mapel2 = Lesson::model()->findAll(array('condition' => 'class_id = ' . $user->class_id . ' and semester = ' . $optSemester . ' and year = ' . $optTahunAjaran . ' and kelompok = 2 and moving_class = 0 and id in (' . $ll . ') and trash is null'));
            $mapel3 = Lesson::model()->findAll(array('condition' => 'class_id = ' . $user->class_id . ' and semester = ' . $optSemester . ' and year = ' . $optTahunAjaran . ' and kelompok = 3 and moving_class = 0 and id in (' . $ll . ') and trash is null'));
            $mapel4 = Lesson::model()->findAll(array('condition' => 'kelompok = 3 and semester = ' . $optSemester . ' and year = ' . $optTahunAjaran . ' and moving_class = 1 and id in (' . $ll . ')'));
        }

        $nama = str_replace(" ", "-", $user->display_name);
        $model = Clases::model()->findByPk($user->class_id);
        /* Default DOMPDF File Path */

        $template_directory = Yii::app()->theme->baseUrl;
        $template_file = Yii::app()->theme->basePath . '/css/print.bootstrap.min.css';
        $inline_style = file_get_contents($template_file);
        $icon_sprite = Yii::app()->theme->basePath . '/images/glyphicons-halflings.png';
        $inline_style = str_replace('../images/glyphicons-halflings.png', $icon_sprite, $inline_style);

        $pdf = Yii::app()->dompdf;
        //$paper_size = array(0,0,360,360);
        $pdf->dompdf->set_paper('tabloid', 'portrait');
        //$pdf->dompdf->set_paper($paper_size);
        if (!empty($cekKurikulum) || $cekKurikulum[0]->value == '2013') {
            if ($type == NULL) {
                $pdf->generate($this->renderPartial('/clases/raport-siswa3', array('inline_style' => $inline_style, 'siswa' => $user, 'mapel' => $mapel, 'model' => $model, 'mapel1' => $mapel1, 'mapel2' => $mapel2, 'mapel3' => $mapel3, 'mapel4' => $mapel4, 'my_lesson' => $my_lesson), true, true), 'raport-siswa-' . $nama . '.pdf', false);
            } else {
                $this->renderPartial('/clases/raport-siswa3', array('inline_style' => $inline_style, 'siswa' => $user, 'mapel' => $mapel, 'model' => $model, 'mapel1' => $mapel1, 'mapel2' => $mapel2, 'mapel3' => $mapel3, 'mapel4' => $mapel4, 'my_lesson' => $my_lesson));
            }
        } else {
            if ($type == NULL) {
                $pdf->generate($this->renderPartial('/clases/raport-siswa', array('inline_style' => $inline_style, 'siswa' => $user, 'mapel' => $mapel, 'my_lesson' => $my_lesson), true, true), 'raport-siswa-' . $nama . '.pdf', false);
            } else {
                $this->renderPartial('/clases/raport-siswa', array('inline_style' => $inline_style, 'siswa' => $user, 'mapel' => $mapel, 'my_lesson' => $my_lesson));
            }
        }
    }

    public function actionBulk() {
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

                            if (!empty($column['NAMA_KELAS'])) {
                                $nama = $column['NAMA_KELAS'];

                                if (empty($nama)) {
                                    Yii::app()->user->setFlash('error', "Baris $row2 . kolom nama harus di isi");
                                    $this->redirect(array('bulk'));
                                }

                                $insert = "INSERT INTO " . $prefix . "class (name) values(:name)";

                                $insertCommand = Yii::app()->db->createCommand($insert);

                                $insertCommand->bindParam(":name", $nama, PDO::PARAM_STR);

                                /* if($modelUser->save()){
                                  $sukses = "ya";
                                  }else{
                                  $sukses = "tidak";
                                  } */

                                if ($insertCommand->execute()) {
                                    $sukses++;
                                } else {
                                    $fail++;
                                }
                            }

                            $row++;
                            $urutan++;
                        }

                        Yii::app()->user->setFlash('success', "Import " . $sukses . " Kelas Berhasil !");
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

                      if (!empty($column['nama'])){
                      $nama = $column['nama'];

                      if (empty($nama)){
                      Yii::app()->user->setFlash('error', "Baris $row2 . kolom nama harus di isi");
                      $this->redirect(array('bulk'));
                      }

                      $insert="INSERT INTO ".$prefix."class (name) values(:name)";

                      $insertCommand=Yii::app()->db->createCommand($insert);

                      $insertCommand->bindParam(":name",$nama,PDO::PARAM_STR);


                      if($insertCommand->execute()){
                      $sukses = "ya";
                      }else{
                      $sukses = "tidak";
                      }

                      }

                      }
                      $row++;
                      }
                      Yii::app()->user->setFlash('success', "Csv Berhasil!");
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
          Yii::app()->user->setFlash('success', "Berhasil buat $row2 record kelas!");
          } */


        $this->render('bulk', array(
            'model' => $model,
        ));
    }

    public function actionDownloadFile() {
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

        $filePlace = Yii::app()->basePath . '/../images/format-import-kelas.xlsx';
        //echo $fileName;
        $fileName = "format-import-kelas.xlsx";

        if (file_exists($filePlace)) {
            return Yii::app()->getRequest()->sendFile($fileName, @file_get_contents($filePlace));
        } else {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
    }

    public function actionDownloadNilai($id) {
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

        $user = User::model()->findByPk($id);
        //$user->display_name;
        //$model = $this->loadModel($id);
        //$mapel = $model->lesson;
        $kelas = $user->class_id;
        $kls = Clases::model()->findByPk($kelas);
        $prefix = Yii::app()->params['tablePrefix'];
        $siswa = User::model()->findAll(array('condition' => 'class_id = ' . $kelas . ' and trash is null', 'order' => 'display_name'));
        $mapel = Lesson::model()->findAll(array("condition" => "semester = '.$optSemester.' and year = '.$optTahunAjaran.' and class_id = " . $kelas));
        Yii::import('ext.phpexcel.XPHPExcel');
        $objPHPExcel = XPHPExcel::createPHPExcel();
        $objPHPExcel->getProperties()->setCreator($user->display_name)
                ->setLastModifiedBy($user->display_name)
                ->setTitle("Raport " . $user->display_name . "-" . $kls->name . " SMA N 11")
                ->setSubject("")
                ->setDescription("")
                ->setKeywords("")
                ->setCategory($user->display_name);

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
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
            ),
            'font' => array(
                'bold' => true,
                'size' => 11,
                'name' => 'Verdana'
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
                ->setCellValue('A2', 'Nama Sekolah : ')
                ->setCellValue('A3', 'Alamat : ')
                ->setCellValue('A4', 'Nama Peserta Didik : ')
                ->setCellValue('A5', 'Nomor Induk/NISN : ')
                ->setCellValue('B2', 'SMA NEGERI 11 BANDUNG')
                ->setCellValue('B3', 'Jl. Kembar Baru No. 23 Bandung')
                ->setCellValue('B4', $user->display_name)
                ->setCellValue('B5', $user->username)
                ->setCellValue('D2', 'Nomor Seri : ')
                ->setCellValue('D3', 'Kelas : ')
                ->setCellValue('D4', 'Semester : ')
                ->setCellValue('D5', 'Tahun Pelajaran : ')
                ->setCellValue('E2', 'A' . substr($user->username, 0, 2) . '10' . substr($user->username, -4))
                ->setCellValue('E3', $kls->name)
                ->setCellValue('E4', '1 (Satu) Gasal')
                ->setCellValue('E5', '2015/2016');

        $objPHPExcel->getActiveSheet()->getStyle('A2:B2')->applyFromArray($style3);
        $objPHPExcel->getActiveSheet()->getStyle('A3:B3')->applyFromArray($style3);
        $objPHPExcel->getActiveSheet()->getStyle('A4:B4')->applyFromArray($style3);
        $objPHPExcel->getActiveSheet()->getStyle('A5:B5')->applyFromArray($style3);
        $objPHPExcel->getActiveSheet()->getStyle('D2:E2')->applyFromArray($style3);
        $objPHPExcel->getActiveSheet()->getStyle('D3:E3')->applyFromArray($style3);
        $objPHPExcel->getActiveSheet()->getStyle('D4:E4')->applyFromArray($style3);
        $objPHPExcel->getActiveSheet()->getStyle('D5:E5')->applyFromArray($style3);

        // Add some data
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A9', 'No')
                ->setCellValue('B9', '')
                ->setCellValue('D9', 'Angka')
                ->setCellValue('E9', 'Predikat')
                ->setCellValue('F9', 'Angka')
                ->setCellValue('G9', 'Predikat')
                ->setCellValue('H9', 'Predikat')
                ->setCellValue('B7', 'Nama Pelajaran')
                ->setCellValue('C7', 'UTS')
                ->setCellValue('D7', 'Pengetahuan')
                ->setCellValue('F7', 'Keterampilan')
                ->setCellValue('H7', 'Sikap')
                //->setCellValue('D7', $kata)
                ->mergeCells('A7:A8')
                ->mergeCells('B7:B8')
                ->mergeCells('C7:C8')
                ->mergeCells('D7:E8')
                ->mergeCells('F7:G8')
                ->mergeCells('H7:H8')
                ->getStyle('B7:B8')->applyFromArray($style);
        $objPHPExcel->getActiveSheet()->getStyle('C7:C8')->applyFromArray($style);
        $objPHPExcel->getActiveSheet()->getStyle('A7:A8')->applyFromArray($style);
        $objPHPExcel->getActiveSheet()->getStyle('D7:E8')->applyFromArray($style);
        $objPHPExcel->getActiveSheet()->getStyle('F7:G8')->applyFromArray($style);
        $objPHPExcel->getActiveSheet()->getStyle('H7:H8')->applyFromArray($style);
        $objPHPExcel->getActiveSheet()->getStyle('A9:H9')->applyFromArray($style2);

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
        $no = 10;
        $counter = 1;
        $cell = 0;
        foreach ($mapel as $key) {
            $uts = Quiz::model()->findAll(array("condition" => "lesson_id = " . $key->id . " AND quiz_type = 1 and semester = " . $optSemester . " and year = " . $optTahunAjaran));
            $nilaiUts = 0;
            if (!empty($uts)) {
                $cekUts = StudentQuiz::model()->findByAttributes(array('student_id' => $id, 'quiz_id' => $uts[0]->id));
                if (!empty($cekUts)) {
                    $nilaiUts = $cekUts->score;
                }
            }

            $pengetahuan = Assignment::model()->findAll(array("condition" => "semester = " . $optSemester . " and year = " . $optTahunAjaran . " and lower(title) LIKE '%pengetahuan%' AND lesson_id = " . $key->id . " AND assignment_type = 1"));
            $nilaiPengetahuan = 0;
            $predikatPengetahuan = NULL;
            if (!empty($pengetahuan)) {
                $cekNilaiPengetahuan = OfflineMark::model()->findByAttributes(array('student_id' => $id, 'assignment_id' => $pengetahuan[0]->id));
                if (!empty($cekNilaiPengetahuan)) {
                    $nilaiPengetahuan = $cekNilaiPengetahuan->score;
                    $predikatPengetahuan = Clases::model()->predikat($nilaiPengetahuan);
                }
            }

            $keterampilan = Assignment::model()->findAll(array("condition" => "semester = " . $optSemester . " and year = " . $optTahunAjaran . " and lower(title) LIKE '%keterampilan%' AND lesson_id = " . $key->id . " AND assignment_type = 1"));
            $nilaiKeterampilan = 0;
            $predikatKeterampilan = NULL;
            if (!empty($keterampilan)) {
                $cekNilaiKeterampilan = OfflineMark::model()->findByAttributes(array('student_id' => $id, 'assignment_id' => $keterampilan[0]->id));
                if (!empty($cekNilaiKeterampilan)) {
                    $nilaiKeterampilan = $cekNilaiKeterampilan->score;
                    $predikatKeterampilan = Clases::model()->predikat($nilaiKeterampilan);
                }
            }

            $sikap = Assignment::model()->findAll(array("condition" => "semester = " . $optSemester . " and year = " . $optTahunAjaran . " and lower(title) LIKE '%sikap%' AND lesson_id = " . $key->id . " AND assignment_type = 1"));
            $nilaiSikap = 0;
            $predikatSikap = NULL;
            if (!empty($sikap)) {
                $cekNilaiSikap = OfflineMark::model()->findByAttributes(array('student_id' => $id, 'assignment_id' => $sikap[0]->id));
                if (!empty($cekNilaiSikap)) {
                    $nilaiSikap = $cekNilaiSikap->score;
                    $predikatSikap = Clases::model()->nilaiHuruf($nilaiSikap);
                }
            }

            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $no . '', '' . $counter . '')
                    ->setCellValue('B' . $no . '', '' . $key->name . '')
                    ->setCellValue('C' . $no . '', '' . $nilaiUts . '')
                    ->setCellValue('D' . $no . '', '' . $nilaiPengetahuan . '')
                    ->setCellValue('E' . $no . '', '' . $predikatPengetahuan . '')
                    ->setCellValue('F' . $no . '', '' . $nilaiKeterampilan . '')
                    ->setCellValue('G' . $no . '', '' . $predikatKeterampilan . '')
                    ->setCellValue('H' . $no . '', '' . $predikatSikap . '');
            $objPHPExcel->getActiveSheet()->getStyle('A' . $no . '')->applyFromArray($style2);
            $objPHPExcel->getActiveSheet()->getStyle('B' . $no . '')->applyFromArray($style2);
            $objPHPExcel->getActiveSheet()->getStyle('C' . $no . '')->applyFromArray($style2);
            $objPHPExcel->getActiveSheet()->getStyle('D' . $no . '')->applyFromArray($style2);
            $objPHPExcel->getActiveSheet()->getStyle('E' . $no . '')->applyFromArray($style2);
            $objPHPExcel->getActiveSheet()->getStyle('F' . $no . '')->applyFromArray($style2);
            $objPHPExcel->getActiveSheet()->getStyle('G' . $no . '')->applyFromArray($style2);
            $objPHPExcel->getActiveSheet()->getStyle('H' . $no . '')->applyFromArray($style2);

            $no++;
            $counter++;
            $cell++;
        }

        // $objPHPExcel->setActiveSheetIndex(0)
        //             ->setCellValue('A'.$no.'', 'Mengetahui')
        //             ->setCellValue('B'.$no.'', ''.$key->name.'')
        //             ->setCellValue('C'.$no.'', ''.$nilaiUts.'')
        //             ->setCellValue('D'.$no.'', ''.$nilaiPengetahuan.'')
        //             ->setCellValue('E'.$no.'', ''.$predikatPengetahuan.'')
        //             ->setCellValue('F'.$no.'', ''.$nilaiKeterampilan.'')
        //             ->setCellValue('G'.$no.'', ''.$predikatKeterampilan.'')
        //$objPHPExcel->getActiveSheet()->getStyle('A12:'.$next[10].$no++)->applyFromArray($style2);
        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle('Raport Siswa');


        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);


        // Redirect output to a clientâ€™s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Raport Siswa "' . $user->display_name . '-Kelas"' . $kls->name . '".xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        Yii::app()->end();
    }

    public function actionDownloadAll($id) {
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
        $status = 1;

        if (!empty($cekFitur)) {
            if ($cekFitur[0]->value == 2) {
                Yii::app()->user->setFlash('error', 'Fitur Ini Dimatikan !');
                $this->redirect(array('/site/index'));
            }
        }

        $user = User::model()->findByPk(Yii::app()->user->id);
        $kelas = Clases::model()->findByPk($id);
        $prefix = Yii::app()->params['tablePrefix'];
        $siswa = User::model()->findAll(array('condition' => 'class_id = ' . $id . ' and trash is null', 'order' => 'display_name'));
        $mapel = Lesson::model()->findAll(array("condition" => "semester = " . $optSemester . " and year = " . $optTahunAjaran . " and class_id = " . $id));
        Yii::import('ext.phpexcel.XPHPExcel');
        $objPHPExcel = XPHPExcel::createPHPExcel();
        $objPHPExcel->getProperties()->setCreator($user->display_name)
                ->setLastModifiedBy($user->display_name)
                ->setTitle("Raport Siswa " . $kelas->name . " SMA N 11")
                ->setSubject("")
                ->setDescription("")
                ->setKeywords("")
                ->setCategory($user->display_name);

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
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
            ),
            'font' => array(
                'bold' => true,
                'size' => 11,
                'name' => 'Verdana'
            ),
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        );

        $style2 = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        );
        $i = 0;
        $sheet = $objPHPExcel->getActiveSheet();

        foreach ($siswa as $murid) {
            $objWorkSheet = $objPHPExcel->createSheet($i);

            $student = User::model()->findByPk($murid->id);

            //$objWorkSheet->setActiveSheetIndex($i)
            $objWorkSheet->setCellValue('A2', 'Nama Sekolah : ')
                    ->setCellValue('A3', 'Alamat : ')
                    ->setCellValue('A4', 'Nama Peserta Didik : ')
                    ->setCellValue('A5', 'Nomor Induk/NISN : ')
                    ->setCellValue('B2', 'SMA NEGERI 11 BANDUNG')
                    ->setCellValue('B3', 'Jl. Kembar Baru No. 23 Bandung')
                    ->setCellValue('B4', $student->display_name)
                    ->setCellValue('B5', $student->username)
                    ->setCellValue('D2', 'Nomor Seri : ')
                    ->setCellValue('D3', 'Kelas : ')
                    ->setCellValue('D4', 'Semester : ')
                    ->setCellValue('D5', 'Tahun Pelajaran : ')
                    ->setCellValue('E2', 'A' . substr($student->username, 0, 2) . '10' . substr($student->username, -4))
                    ->setCellValue('E3', $kelas->name)
                    ->setCellValue('E4', '1 (Satu) Gasal')
                    ->setCellValue('E5', '2014/2015');

            $objWorkSheet->getStyle('A2:B2')->applyFromArray($style3);
            $objWorkSheet->getStyle('A3:B3')->applyFromArray($style3);
            $objWorkSheet->getStyle('A4:B4')->applyFromArray($style3);
            $objWorkSheet->getStyle('A5:B5')->applyFromArray($style3);
            $objWorkSheet->getStyle('D2:E2')->applyFromArray($style3);
            $objWorkSheet->getStyle('D3:E3')->applyFromArray($style3);
            $objWorkSheet->getStyle('D4:E4')->applyFromArray($style3);
            $objWorkSheet->getStyle('D5:E5')->applyFromArray($style3);

            // Add some data
            //$objWorkSheet->setActiveSheetIndex(0)

            $objWorkSheet->setCellValue('A9', 'No')
                    ->setCellValue('B9', '')
                    ->setCellValue('D9', 'Angka')
                    ->setCellValue('E9', 'Predikat')
                    ->setCellValue('F9', 'Angka')
                    ->setCellValue('G9', 'Predikat')
                    ->setCellValue('H9', 'Predikat')
                    ->setCellValue('B7', 'Nama Pelajaran')
                    ->setCellValue('C7', 'UTS')
                    ->setCellValue('D7', 'Pengetahuan')
                    ->setCellValue('F7', 'Keterampilan')
                    ->setCellValue('H7', 'Sikap')
                    //->setCellValue('D7', $kata)
                    ->mergeCells('A7:A8')
                    ->mergeCells('B7:B8')
                    ->mergeCells('C7:C8')
                    ->mergeCells('D7:E8')
                    ->mergeCells('F7:G8')
                    ->mergeCells('H7:H8')
                    ->getStyle('B7:B8')->applyFromArray($style);
            $objWorkSheet->getStyle('C7:C8')->applyFromArray($style);
            $objWorkSheet->getStyle('A7:A8')->applyFromArray($style);
            $objWorkSheet->getStyle('D7:E8')->applyFromArray($style);
            $objWorkSheet->getStyle('F7:G8')->applyFromArray($style);
            $objWorkSheet->getStyle('H7:H8')->applyFromArray($style);
            $objWorkSheet->getStyle('A9:H9')->applyFromArray($style2);


            $objWorkSheet->getColumnDimension('B')
                    ->setAutoSize(true);


            $objWorkSheet->getColumnDimension('C')
                    ->setAutoSize(true);


            $objWorkSheet->getColumnDimension('D')
                    ->setAutoSize(true);


            $objWorkSheet->getColumnDimension('E')
                    ->setAutoSize(true);


            $objWorkSheet->getColumnDimension('F')
                    ->setAutoSize(true);

            $huruf = range('D', 'Z');
            $no = 10;
            $counter = 1;
            $cell = 0;
            foreach ($mapel as $key) {
                $uts = Quiz::model()->findAll(array("condition" => "semester = " . $optSemester . " and year = " . $optTahunAjaran . " and lesson_id = " . $key->id . " AND quiz_type = 1"));
                $nilaiUts = '-';
                $nilaiPengetahuan = '-';
                $nilaiKeterampilan = '-';
                $nilaiSikap = '-';
                $stat1 = NULL;
                $stat2 = NULL;
                $stat3 = NULL;
                $stat4 = NULL;

                if (!empty($uts)) {
                    $cekUts = StudentQuiz::model()->findByAttributes(array('student_id' => $student->id, 'quiz_id' => $uts[0]->id));
                    if (!empty($cekUts)) {
                        $nilaiUts = $cekUts->score;
                    } else {
                        $stat1 = 1;
                    }
                } else {
                    $stat1 = 1;
                }

                $pengetahuan = Assignment::model()->findAll(array("condition" => "semester = " . $optSemester . " and year = " . $optTahunAjaran . " and lower(title) LIKE '%pengetahuan%' AND lesson_id = " . $key->id . " AND assignment_type = 1"));

                $predikatPengetahuan = NULL;
                if (!empty($pengetahuan)) {
                    $cekNilaiPengetahuan = OfflineMark::model()->findByAttributes(array('student_id' => $student->id, 'assignment_id' => $pengetahuan[0]->id));
                    if (!empty($cekNilaiPengetahuan)) {
                        $nilaiPengetahuan = $cekNilaiPengetahuan->score;
                        $predikatPengetahuan = Clases::model()->predikat($nilaiPengetahuan);
                    } else {
                        $stat2 = 1;
                    }
                } else {
                    $stat2 = 1;
                }

                $keterampilan = Assignment::model()->findAll(array("condition" => "semester = " . $optSemester . " and year = " . $optTahunAjaran . " and lower(title) LIKE '%keterampilan%' AND lesson_id = " . $key->id . " AND assignment_type = 1"));

                $predikatKeterampilan = NULL;
                if (!empty($keterampilan)) {
                    $cekNilaiKeterampilan = OfflineMark::model()->findByAttributes(array('student_id' => $student->id, 'assignment_id' => $keterampilan[0]->id));
                    if (!empty($cekNilaiKeterampilan)) {
                        $nilaiKeterampilan = $cekNilaiKeterampilan->score;
                        $predikatKeterampilan = Clases::model()->predikat($nilaiKeterampilan);
                    } else {
                        $stat3 = 1;
                    }
                } else {
                    $stat3 = 1;
                }

                $sikap = Assignment::model()->findAll(array("condition" => "semester = " . $optSemester . " and year = " . $optTahunAjaran . " and lower(title) LIKE '%sikap%' AND lesson_id = " . $key->id . " AND assignment_type = 1"));

                $predikatSikap = NULL;
                if (!empty($sikap)) {
                    $cekNilaiSikap = OfflineMark::model()->findByAttributes(array('student_id' => $student->id, 'assignment_id' => $sikap[0]->id));
                    if (!empty($cekNilaiSikap)) {
                        $nilaiSikap = $cekNilaiSikap->score;
                        $predikatSikap = Clases::model()->nilaiHuruf($nilaiSikap);
                    } else {
                        $stat4 = 1;
                    }
                } else {
                    $stat4 = 1;
                }

                //$objWorkSheet->setActiveSheetIndex(0)
                if (($nilaiUts != '-') || ($stat2 != 1) || ($stat3 != 1) || ($stat4 != 1)) {
                    $objWorkSheet->setCellValue('A' . $no . '', '' . $counter . '')
                            ->setCellValue('B' . $no . '', '' . $key->name . '')
                            ->setCellValue('C' . $no . '', '' . $nilaiUts . '')
                            ->setCellValue('D' . $no . '', '' . $nilaiPengetahuan . '')
                            ->setCellValue('E' . $no . '', '' . $predikatPengetahuan . '')
                            ->setCellValue('F' . $no . '', '' . $nilaiKeterampilan . '')
                            ->setCellValue('G' . $no . '', '' . $predikatKeterampilan . '')
                            ->setCellValue('H' . $no . '', '' . $predikatSikap . '');
                    $objWorkSheet->getStyle('A' . $no . '')->applyFromArray($style2);
                    $objWorkSheet->getStyle('B' . $no . '')->applyFromArray($style2);
                    $objWorkSheet->getStyle('C' . $no . '')->applyFromArray($style2);
                    $objWorkSheet->getStyle('D' . $no . '')->applyFromArray($style2);
                    $objWorkSheet->getStyle('E' . $no . '')->applyFromArray($style2);
                    $objWorkSheet->getStyle('F' . $no . '')->applyFromArray($style2);
                    $objWorkSheet->getStyle('G' . $no . '')->applyFromArray($style2);
                    $objWorkSheet->getStyle('H' . $no . '')->applyFromArray($style2);

                    $no++;
                    $counter++;
                    $cell++;
                }
            }

            $objWorkSheet->setTitle("" . $student->username . "");
            $i++;
        }

        //$objPHPExcel->getActiveSheet()->getStyle('A12:'.$next[10].$no++)->applyFromArray($style2);
        // Rename worksheet
        //$objPHPExcel->getActiveSheet()->setTitle('Raport Siswa');
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);


        // Redirect output to a clientâ€™s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Raport Siswa "' . $kelas->name . '.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        Yii::app()->end();
    }

    public function actionImportnilairapor($id) {
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
        $kelas = ClassDetail::model()->findByPk($id);
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
                        $range = 'A2:ED' . $highestRow . '';
                        //$text = $data->toArray(null, true, true, true);
                        $text = $data->rangeToArray($range);
                        $head = $data->rangeToArray('A1:ED1');
                        $suskes = 0;
                        $fail = 0;

                        $head_mapel = array_slice($head[0], 4, 50);
                        $head_nonmapel = array_slice($head[0], 54, 30);
                        $head_desc = array_slice($head[0], 84, 50);
                        // echo "<pre>";
                        // 	print_r($head);
                        // 	print_r($head_nonmapel);
                        // 	print_r($head_desc);
                        // echo "</pre>";

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
                            // if ($column['Angka Ptahu'] == '0') {
                            // 	$column['Angka Ptahu'] = 1;
                            // }

                            if ((empty($column['Prestasi 1 - Jenis Kegiatan'])) && (!empty($column['TOEIC']))) {
                                $column['Prestasi 1 - Jenis Kegiatan'] = $column['TOEIC'];

                                if ((empty($column['Prestasi 1 - Keterangan'])) && (!empty($column['TOEIC DESC']))) {
                                    $column['Prestasi 1 - Keterangan'] = $column['TOEIC DESC'];
                                }
                            } elseif (!empty($column['Prestasi 1 - Jenis Kegiatan'])) {
                                if ((empty($column['Prestasi 2 - Jenis Kegiatan'])) && (!empty($column['TOEIC']))) {
                                    $column['Prestasi 2 - Jenis Kegiatan'] = $column['TOEIC'];

                                    if ((empty($column['Prestasi 2 - Keterangan'])) && (!empty($column['TOEIC DESC']))) {
                                        $column['Prestasi 2 - Keterangan'] = $column['TOEIC DESC'];
                                    }
                                }
                            } elseif (!empty($column['Prestasi 2 - Jenis Kegiatan'])) {
                                if ((empty($column['Prestasi 3 - Jenis Kegiatan'])) && (!empty($column['TOEIC']))) {
                                    $column['Prestasi 3 - Jenis Kegiatan'] = $column['TOEIC'];

                                    if ((empty($column['Prestasi 3 - Keterangan'])) && (!empty($column['TOEIC DESC']))) {
                                        $column['Prestasi 3 - Keterangan'] = $column['TOEIC DESC'];
                                    }
                                }
                            } elseif (!empty($column['Prestasi 3 - Jenis Kegiatan'])) {
                                if ((empty($column['Prestasi 4 - Jenis Kegiatan'])) && (!empty($column['TOEIC']))) {
                                    $column['Prestasi 4 - Jenis Kegiatan'] = $column['TOEIC'];

                                    if ((empty($column['Prestasi 4 - Keterangan'])) && (!empty($column['TOEIC DESC']))) {
                                        $column['Prestasi 4 - Keterangan'] = $column['TOEIC DESC'];
                                    }
                                }
                            }



                            if (!empty($column['NIP/NIS'])) {

                                $length = 5;
                                $length2 = 9;

                                $nik = $column['NIP/NIS'];
                                // echo $nik."</br>";
                                $nik_final = str_replace('-', '', trim($nik));
                                $siswa = User::model()->findAll(array('condition' => 'username = \'' . $nik_final . '\''));


                                foreach ($head_mapel as $value_head) {
                                    //echo $value_head."</br>";
                                    $name_mapel = explode("(", $value_head);
                                    $name_mapel = $name_mapel[0];
                                    $name_mapel = substr($name_mapel, 0, -1);
                                    //echo $name_mapel."</br>";
                                    $pengetahuan_mapel = explode("-", $value_head);
                                    $pengetahuan_mapel = $pengetahuan_mapel[1];
                                    $pengetahuan_mapel = substr($pengetahuan_mapel, 1);
                                    if (trim($pengetahuan_mapel) == 'P') {
                                        $pengetahuan_mapel = 'pengetahuan';
                                    } else {
                                        $pengetahuan_mapel = 'keterampilan';
                                    }
                                    //echo $pengetahuan_mapel."</br>";

                                    if (!empty($column[$value_head])) {
                                        $mapel = Lesson::model()->findAll(array('condition' => 'class_id = ' . $id . ' and trash is null and name = \'' . htmlspecialchars(trim($name_mapel)) . '\' AND semester = ' . $optSemester . ' AND year = ' . $optTahunAjaran));
                                        if (!empty($mapel)) {
                                            //echo $mapel->nama;
                                            $cekpengetahuan = FinalMark::model()->findAll(array("condition" => "user_id = " . htmlspecialchars(trim($siswa[0]->id)) . " AND lesson_id = " . htmlspecialchars(trim($mapel[0]->id)) . " AND tipe = '" . htmlspecialchars(trim($pengetahuan_mapel)) . "' AND semester = " . $optSemester . " AND tahun_ajaran = " . $optTahunAjaran));

                                            if (empty($cekpengetahuan)) {
                                                $insert1 = "INSERT INTO " . $prefix . "final_mark (user_id,lesson_id,tipe,semester,tahun_ajaran,nilai,created_at,updated_at,created_by,updated_by) values('" . htmlspecialchars(trim($siswa[0]->id)) . "','" . htmlspecialchars(trim($mapel[0]->id)) . "','" . htmlspecialchars(trim($pengetahuan_mapel)) . "'," . $optSemester . "," . $optTahunAjaran . ",'" . htmlspecialchars(trim($column[$value_head])) . "',NOW(),NOW(),'" . Yii::app()->user->id . "','" . Yii::app()->user->id . "')";

                                                $insertCommand1 = Yii::app()->db->createCommand($insert1);


                                                if ($insertCommand1->execute()) {
                                                    $sukses++;
                                                } else {
                                                    $fail++;
                                                }
                                            } else {
                                                $sql1 = "UPDATE " . $prefix . "final_mark SET sync_status = 2, nilai = " . htmlspecialchars(trim($column[$value_head])) . ", updated_at = NOW(), updated_by = " . Yii::app()->user->id . " WHERE user_id = " . htmlspecialchars(trim($siswa[0]->id)) . " AND lesson_id = " . htmlspecialchars(trim($mapel[0]->id)) . " AND tipe = '" . htmlspecialchars(trim($pengetahuan_mapel)) . "' AND semester = " . $optSemester . " AND tahun_ajaran = " . $optTahunAjaran;
                                                $command1 = Yii::app()->db->createCommand($sql1);
                                                if ($command1->execute()) {
                                                    $sukses++;
                                                } else {
                                                    $fail++;
                                                }
                                            }

                                            // echo "<pre>";
                                            // print_r($mapel);
                                            // echo "</pre>";
                                        }
                                    }
                                }

                                foreach ($head_nonmapel as $value_nohead) {
                                    //echo $value_head."</br>";
                                    // $name_mapel = explode("(", $value_head);
                                    // $name_mapel = $name_mapel[0];
                                    // $name_mapel = substr($name_mapel, 0, -1);
                                    // //echo $name_mapel."</br>";
                                    // $pengetahuan_mapel = explode("-", $value_head);
                                    // $pengetahuan_mapel = $pengetahuan_mapel[1];
                                    // $pengetahuan_mapel = substr($pengetahuan_mapel, 1);
                                    // if ($pengetahuan_mapel=='P') {
                                    // 	$pengetahuan_mapel = 'pengetahuan';
                                    // } else {
                                    // 	$pengetahuan_mapel = 'keterampilan';
                                    // }
                                    //echo $pengetahuan_mapel."</br>";

                                    if (($value_nohead != 'TOEIC') && ($value_nohead != 'TOEIC DESC')) {
                                        if (!empty($column[$value_nohead])) {

                                            $ceknopengetahuan = FinalMark::model()->findAll(array("condition" => "user_id = " . htmlspecialchars(trim($siswa[0]->id)) . " AND tipe = '" . htmlspecialchars(trim($value_nohead)) . "' AND semester = " . $optSemester . " AND tahun_ajaran = " . $optTahunAjaran));

                                            if (empty($ceknopengetahuan)) {
                                                $insert1 = "INSERT INTO " . $prefix . "final_mark (user_id,tipe,semester,tahun_ajaran,nilai_desc,created_at,updated_at,created_by,updated_by) values('" . htmlspecialchars(trim($siswa[0]->id)) . "','" . htmlspecialchars(trim($value_nohead)) . "'," . $optSemester . "," . $optTahunAjaran . ",'" . htmlspecialchars(trim($column[$value_nohead])) . "',NOW(),NOW(),'" . Yii::app()->user->id . "','" . Yii::app()->user->id . "')";

                                                $insertCommand1 = Yii::app()->db->createCommand($insert1);


                                                if ($insertCommand1->execute()) {
                                                    $sukses++;
                                                } else {
                                                    $fail++;
                                                }
                                            } else {
                                                $sql2 = "UPDATE " . $prefix . "final_mark SET sync_status = 2, nilai_desc = '" . htmlspecialchars(trim($column[$value_nohead])) . "', updated_at = NOW(), updated_by = " . Yii::app()->user->id . " WHERE user_id = " . htmlspecialchars(trim($siswa[0]->id)) . "  AND tipe = '" . htmlspecialchars(trim($value_nohead)) . "' AND semester = " . $optSemester . " AND tahun_ajaran = " . $optTahunAjaran;
                                                $command2 = Yii::app()->db->createCommand($sql2);
                                                if ($command2->execute()) {
                                                    $sukses++;
                                                } else {
                                                    $fail++;
                                                }
                                            }

                                            // echo "<pre>";
                                            // print_r($mapel);
                                            // echo "</pre>";
                                        }
                                    }
                                }


                                foreach ($head_desc as $value_desc) {
                                    //echo $value_head."</br>";
                                    $name_mapel = explode("(", $value_desc);
                                    $name_mapel = $name_mapel[0];
                                    $name_mapel = substr($name_mapel, 0, -1);
                                    //echo $name_mapel."</br>";
                                    $pengetahuan_mapel = explode("-", $value_desc);
                                    $pengetahuan_mapel = $pengetahuan_mapel[1];
                                    $pengetahuan_mapel = substr($pengetahuan_mapel, 1);
                                    if (trim($pengetahuan_mapel) == 'P') {
                                        $pengetahuan_mapel = 'desc_pengetahuan';
                                    } else {
                                        $pengetahuan_mapel = 'desc_keterampilan';
                                    }
                                    // echo $pengetahuan_mapel."<br/>";

                                    if (!empty($column[$value_desc])) {
                                        $mapel = Lesson::model()->findAll(array('condition' => 'class_id = ' . $id . ' and trash is null and name = \'' . $name_mapel . '\' AND semester = ' . $optSemester . ' AND year = ' . $optTahunAjaran));
                                        if (!empty($mapel)) {
                                            //echo $mapel->nama;
                                            $cekpengetahuan = FinalMark::model()->findAll(array("condition" => "user_id = " . htmlspecialchars(trim($siswa[0]->id)) . " AND lesson_id = " . htmlspecialchars(trim($mapel[0]->id)) . " AND tipe = '" . htmlspecialchars(trim($pengetahuan_mapel)) . "' AND semester = " . $optSemester . " AND tahun_ajaran = " . $optTahunAjaran));

                                            if (empty($cekpengetahuan)) {
                                                $insert2 = "INSERT INTO " . $prefix . "final_mark (user_id,lesson_id,tipe,semester,tahun_ajaran,created_at,updated_at,created_by,updated_by,nilai_desc) values(" . htmlspecialchars(trim($siswa[0]->id)) . "," . htmlspecialchars(trim($mapel[0]->id)) . ",'" . htmlspecialchars(trim($pengetahuan_mapel)) . "'," . $optSemester . "," . $optTahunAjaran . ",NOW(),NOW()," . Yii::app()->user->id . "," . Yii::app()->user->id . ", '" . htmlspecialchars(trim($column[$value_desc])) . "')";
                                                // echo $insert2;
                                                // exit();
                                                $insertCommand2 = Yii::app()->db->createCommand($insert2);


                                                if ($insertCommand2->execute()) {
                                                    $sukses++;
                                                } else {
                                                    $fail++;
                                                }
                                            } else {
                                                $sql2 = "UPDATE " . $prefix . "final_mark SET sync_status = 2, nilai_desc = '" . htmlspecialchars(trim($column[$value_desc])) . "', updated_at = NOW(), updated_by = " . Yii::app()->user->id . " WHERE user_id = " . htmlspecialchars(trim($siswa[0]->id)) . " AND lesson_id = " . htmlspecialchars(trim($mapel[0]->id)) . " AND tipe = '" . htmlspecialchars(trim($pengetahuan_mapel)) . "' AND semester = " . $optSemester . " AND tahun_ajaran = " . $optTahunAjaran;
                                                $command2 = Yii::app()->db->createCommand($sql2);
                                                if ($command2->execute()) {
                                                    $sukses++;
                                                } else {
                                                    $fail++;
                                                }
                                            }

                                            // echo "<pre>";
                                            // print_r($mapel);
                                            // echo "</pre>";
                                        }
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

        /* if (!empty($sukses)){
          $row2 = $row - 1;
          Yii::app()->user->setFlash('success', "Berhasil buat $row2 record user!");
          } */


        $this->render('importnilairapor', array(
            'model' => $model,
        ));
    }

    public function actionImportnilaiWalikelas($id) {
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
        $kelas = ClassDetail::model()->findByPk($id);
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
                        $range = 'A2:ED' . $highestRow . '';
                        //$text = $data->toArray(null, true, true, true);
                        $text = $data->rangeToArray($range);
                        $head = $data->rangeToArray('A1:ED1');
                        $suskes = 0;
                        $fail = 0;


                        $head_nonmapel = array_slice($head[0], 4, 40);
                        // echo "<pre>";
                        // 	// print_r($head);
                        // 	print_r($head_nonmapel);
                        // 	// print_r($head_desc);
                        // echo "</pre>";

                        foreach ($text as $key => $val) {
                            //echo "<pre>";
                            //print_r($val);
                            $column = array_combine($head[0], $val);
                            $row2 = $row;



                            if (!empty($column['NIP/NIS'])) {

                                $length = 5;
                                $length2 = 9;

                                $nik = $column['NIP/NIS'];
                                // echo $nik."</br>";
                                $nik_final = str_replace('-', '', trim($nik));
                                $siswa = User::model()->findAll(array('condition' => 'username = \'' . $nik_final . '\''));




                                foreach ($head_nonmapel as $value_nohead) {
                                    //echo $value_head."</br>";
                                    // $name_mapel = explode("(", $value_head);
                                    // $name_mapel = $name_mapel[0];
                                    // $name_mapel = substr($name_mapel, 0, -1);
                                    // //echo $name_mapel."</br>";
                                    // $pengetahuan_mapel = explode("-", $value_head);
                                    // $pengetahuan_mapel = $pengetahuan_mapel[1];
                                    // $pengetahuan_mapel = substr($pengetahuan_mapel, 1);
                                    // if ($pengetahuan_mapel=='P') {
                                    // 	$pengetahuan_mapel = 'pengetahuan';
                                    // } else {
                                    // 	$pengetahuan_mapel = 'keterampilan';
                                    // }
                                    //echo $pengetahuan_mapel."</br>";

                                    if (($value_nohead != 'TOEIC') && ($value_nohead != 'TOEIC DESC')) {
                                        if (!empty($column[$value_nohead])) {

                                            $ceknopengetahuan = FinalMark::model()->findAll(array("condition" => "user_id = " . htmlspecialchars(trim($siswa[0]->id)) . " AND tipe = '" . htmlspecialchars(trim($value_nohead)) . "' AND semester = " . $optSemester . " AND tahun_ajaran = " . $optTahunAjaran));

                                            if (empty($ceknopengetahuan)) {
                                                $insert1 = "INSERT INTO " . $prefix . "final_mark (user_id,tipe,semester,tahun_ajaran,nilai_desc,created_at,updated_at,created_by,updated_by) values('" . htmlspecialchars(trim($siswa[0]->id)) . "','" . htmlspecialchars(trim($value_nohead)) . "'," . $optSemester . "," . $optTahunAjaran . ",'" . htmlspecialchars(trim($column[$value_nohead])) . "',NOW(),NOW(),'" . Yii::app()->user->id . "','" . Yii::app()->user->id . "')";

                                                $insertCommand1 = Yii::app()->db->createCommand($insert1);


                                                if ($insertCommand1->execute()) {
                                                    $sukses++;
                                                } else {
                                                    $fail++;
                                                }
                                            } else {




                                                $sql2 = "UPDATE " . $prefix . "final_mark SET nilai_desc = '" . htmlspecialchars(trim($column[$value_nohead])) . "', updated_at = NOW(), updated_by = " . Yii::app()->user->id . " WHERE user_id = " . htmlspecialchars(trim($siswa[0]->id)) . "  AND tipe = '" . htmlspecialchars(trim($value_nohead)) . "' AND semester = " . $optSemester . " AND tahun_ajaran = " . $optTahunAjaran;
                                                $command2 = Yii::app()->db->createCommand($sql2);
                                                if ($command2->execute()) {
                                                    $sukses++;
                                                } else {
                                                    $fail++;
                                                }
                                            }

                                            // echo "<pre>";
                                            // print_r($mapel);
                                            // echo "</pre>";
                                        }
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

        /* if (!empty($sukses)){
          $row2 = $row - 1;
          Yii::app()->user->setFlash('success', "Berhasil buat $row2 record user!");
          } */


        $this->render('importnilaiwalikelas', array(
            'model' => $model,
        ));
    }

    public function actionUpdateDesc($id) {
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

        $getsiswa = User::model()->findAll(array('condition' => 'class_id = ' . $id . ''));
        $base = Yii::app()->baseUrl;

        $template_directory = Yii::app()->theme->baseUrl;
        $template_file = Yii::app()->theme->basePath . '/css/print.bootstrap.min.css';
        $inline_style = file_get_contents($template_file);
        $icon_sprite = Yii::app()->theme->basePath . '/images/glyphicons-halflings.png';
        $inline_style = str_replace('../images/glyphicons-halflings.png', $icon_sprite, $inline_style);
        $model = ClassDetail::model()->findByPk($id);

        $kd = array('kd1', 'kd2', 'kd3', 'kd4', 'kd5', 'kd6', 'kd7');


        // $desc_peng_a = "Sangat memahami dalam";
        // $desc_peng_b = "Memahami dalam";
        // $desc_peng_c = "Cukup memahami dalam";
        // $desc_peng_d = "Perlu ditingkatkan dalam memahami";

        $desc_peng_a = "Sangat memahami dalam ";
        $desc_peng_b = "Memahami dalam ";
        $desc_peng_c = "Mulai memahami dalam ";
        $desc_peng_d = "Mulai memahami dalam ";

        $count_peng_a = 0;
        $count_peng_b = 0;
        $count_peng_c = 0;
        $count_peng_d = 0;

        // $desc_ket_a = "Sangat terampil dalam ";
        // $desc_ket_b = "Terampil dalam ";
        // $desc_ket_c = "Cukup terampil dalam ";
        // $desc_ket_d = "Perlu ditingkatkan dalam ";


        $desc_ket_a = "Sangat baik dalam ";
        $desc_ket_b = "Baik dalam ";
        $desc_ket_c = "Mulai dapat ";
        $desc_ket_d = "Mulai dapat ";

        $count_ket_a = 0;
        $count_ket_b = 0;
        $count_ket_c = 0;
        $count_ket_d = 0;

        foreach ($getsiswa as $siswa) {

            $user[] = $siswa;
            $user_data[] = UserProfile::model()->findByAttributes(array('user_id' => $siswa->id));

            if (isset($_GET['lesson_id'])) {
                $sql = "
					 	SELECT l.`id`,l.`name`,fm.`nilai`,fm.`tipe`,l.`kelompok`,l.`list_id`,lk.`description`
							FROM `final_mark` as fm
							join `users` as u on u.`id` = fm.`user_id`
							join `lesson` as l on fm.`lesson_id` = l.`id`
			                                join `lesson_kd` as lk on fm.`lesson_id` = lk.`lesson_id` and fm.`tipe` = lk.`title`
							WHERE fm.`user_id` = " . $siswa->id . "
							AND l.semester = " . $optSemester . "
							AND l.year = " . $optTahunAjaran . "
							AND fm.`lesson_id` = " . $_GET['lesson_id'] . "
			                                AND fm.`tipe`
			 				in ('kd1','kd2','kd3','kd4','kd5','kd6','kd7','kd1_ket','kd2_ket','kd3_ket','kd4_ket','kd5_ket','kd6_ket','kd7_ket')
							order by l.`list_id`
					 ";
            } else {
                $sql = "
					 	SELECT l.`id`,l.`name`,fm.`nilai`,fm.`tipe`,l.`kelompok`,l.`list_id`,lk.`description`
							FROM `final_mark` as fm
							join `users` as u on u.`id` = fm.`user_id`
							join `lesson` as l on fm.`lesson_id` = l.`id`
			                                join `lesson_kd` as lk on fm.`lesson_id` = lk.`lesson_id` and fm.`tipe` = lk.`title`
							WHERE fm.`user_id` = " . $siswa->id . "
							AND l.semester = " . $optSemester . "
							AND l.year = " . $optTahunAjaran . "
			                                AND fm.`tipe`
			 				in ('kd1','kd2','kd3','kd4','kd5','kd6','kd7','kd1_ket','kd2_ket','kd3_ket','kd4_ket','kd5_ket','kd6_ket','kd7_ket')
							order by l.`list_id`
					 ";
            }


            $command = Yii::app()->db->createCommand($sql);
            $rows = $command->queryAll();


            $leger_arr = array();

            foreach ($rows as $key => $item) {
                // $item["nilai-".$item["tipe"]] = $item["nilai"];
                // $arr[$item['id']]["name"] = $item["name"];
                // $arr[$item['id']]["kelompok"] = $item["kelompok"];
                // $arr[$item['id']]["nilai-".$item["tipe"]] = $item["nilai"];

                $arr = array();
                $sql2 = "SELECT * FROM `lesson_kd` WHERE `lesson_id` = '" . $item['id'] . "' AND `title` = '" . $item['tipe'] . "'";
                $command2 = Yii::app()->db->createCommand($sql2);
                $rows2 = $command2->queryAll();


                $leger_arr[$item["name"]]["kelompok"] = $item["kelompok"];
                $leger_arr[$item["name"]]["nilai-" . $item["tipe"]] = $item["nilai"];
                $leger_arr[$item["name"]]["desc-" . $item["tipe"]] = $item["description"];

                // foreach ($rows2 as $key2 => $item2) {
                // 	 $leger_arr[$item["name"]]["desc-".$item["tipe"]] = $item2["description"];
                // }

                foreach ($kd as $value_kd) {

                    if (!empty($leger_arr[$item['name']]['nilai-' . $value_kd]) && !empty($leger_arr[$item['name']]['desc-' . $value_kd])) {
                        if ($id != 0) {
                            if ($leger_arr[$item['name']]['nilai-' . $value_kd] < 75) {
                                $desc_peng_d .= $leger_arr[$item['name']]['desc-' . $value_kd] . ", ";
                                $count_peng_d ++;
                            } elseif ($leger_arr[$item['name']]['nilai-' . $value_kd] >= 75 && $leger_arr[$item['name']]['nilai-' . $value_kd] < 80) {
                                $desc_peng_c .= $leger_arr[$item['name']]['desc-' . $value_kd] . ", ";
                                $count_peng_c ++;
                            } elseif ($leger_arr[$item['name']]['nilai-' . $value_kd] >= 80 && $leger_arr[$item['name']]['nilai-' . $value_kd] < 91) {
                                $desc_peng_b .= $leger_arr[$item['name']]['desc-' . $value_kd] . ", ";
                                $count_peng_b ++;
                            } elseif ($leger_arr[$item['name']]['nilai-' . $value_kd] >= 91 && $leger_arr[$item['name']]['nilai-' . $value_kd] <= 100) {
                                $desc_peng_a .= $leger_arr[$item['name']]['desc-' . $value_kd] . ", ";
                                $count_peng_a ++;
                            } else {
                                //$leger_arr['p-np'] = "";
                            }
                        } else {

                            if ($leger_arr[$item['name']]['nilai-' . $value_kd] < 78) {
                                $desc_peng_d .= $leger_arr[$item['name']]['desc-' . $value_kd] . ", ";
                                $count_peng_d ++;
                            } elseif ($leger_arr[$item['name']]['nilai-' . $value_kd] >= 78 && $leger_arr[$item['name']]['nilai-' . $value_kd] < 81) {
                                $desc_peng_c .= $leger_arr[$item['name']]['desc-' . $value_kd] . ", ";
                                $count_peng_c ++;
                            } elseif ($leger_arr[$item['name']]['nilai-' . $value_kd] >= 81 && $leger_arr[$item['name']]['nilai-' . $value_kd] < 91) {
                                $desc_peng_b .= $leger_arr[$item['name']]['desc-' . $value_kd] . ", ";
                                $count_peng_b ++;
                            } elseif ($leger_arr[$item['name']]['nilai-' . $value_kd] >= 91 && $leger_arr[$item['name']]['nilai-' . $value_kd] <= 100) {
                                $desc_peng_a .= $leger_arr[$item['name']]['desc-' . $value_kd] . ", ";
                                $count_peng_a ++;
                            } else {
                                //$leger_arr['p-np'] = "";
                            }
                        }
                    }
                }


                foreach ($kd as $value_kd) {

                    if (!empty($leger_arr[$item['name']]['nilai-' . $value_kd . '_ket']) && !empty($leger_arr[$item['name']]['desc-' . $value_kd . '_ket'])) {
                        if ($id != 0) {
                            if ($leger_arr[$item['name']]['nilai-' . $value_kd . '_ket'] < 75) {
                                $desc_ket_d .= $leger_arr[$item['name']]['desc-' . $value_kd . '_ket'] . ", ";
                                $count_ket_d ++;
                            } elseif ($leger_arr[$item['name']]['nilai-' . $value_kd . '_ket'] >= 75 && $leger_arr[$item['name']]['nilai-' . $value_kd . '_ket'] < 80) {
                                $desc_ket_c .= $leger_arr[$item['name']]['desc-' . $value_kd . '_ket'] . ", ";
                                $count_ket_c ++;
                            } elseif ($leger_arr[$item['name']]['nilai-' . $value_kd . '_ket'] >= 80 && $leger_arr[$item['name']]['nilai-' . $value_kd . '_ket'] < 91) {
                                $desc_ket_b .= $leger_arr[$item['name']]['desc-' . $value_kd . '_ket'] . ", ";
                                $count_ket_b ++;
                            } elseif ($leger_arr[$item['name']]['nilai-' . $value_kd . '_ket'] >= 91 && $leger_arr[$item['name']]['nilai-' . $value_kd . '_ket'] <= 100) {
                                $desc_ket_a .= $leger_arr[$item['name']]['desc-' . $value_kd . '_ket'] . ", ";
                                $count_ket_a ++;
                            } else {
                                //$leger_arr['p-np'] = "";
                            }
                        } else {

                            if ($leger_arr[$item['name']]['nilai-' . $value_kd . '_ket'] < 78) {
                                $desc_ket_d .= $leger_arr[$item['name']]['desc-' . $value_kd . '_ket'] . ", ";
                                $count_ket_d ++;
                            } elseif ($leger_arr[$item['name']]['nilai-' . $value_kd . '_ket'] >= 78 && $leger_arr[$item['name']]['nilai-' . $value_kd . '_ket'] < 81) {
                                $desc_ket_c .= $leger_arr[$item['name']]['desc-' . $value_kd . '_ket'] . ", ";
                                $count_ket_c ++;
                            } elseif ($leger_arr[$item['name']]['nilai-' . $value_kd . '_ket'] >= 81 && $leger_arr[$item['name']]['nilai-' . $value_kd . '_ket'] < 91) {
                                $desc_ket_b .= $leger_arr[$item['name']]['desc-' . $value_kd . '_ket'] . ", ";
                                $count_ket_b ++;
                            } elseif ($leger_arr[$item['name']]['nilai-' . $value_kd . '_ket'] >= 91 && $leger_arr[$item['name']]['nilai-' . $value_kd . '_ket'] <= 100) {
                                $desc_ket_a .= $leger_arr[$item['name']]['desc-' . $value_kd . '_ket'] . ", ";
                                $count_ket_a ++;
                            } else {
                                //$leger_arr['p-np'] = "";
                            }
                        }
                    }
                }

                if ($count_peng_a == 0) {
                    $desc_peng_a = "";
                }
                if ($count_peng_b == 0) {
                    $desc_peng_b = "";
                }
                if ($count_peng_c == 0) {
                    $desc_peng_c = "";
                }
                if ($count_peng_d == 0) {
                    $desc_peng_d = "";
                }

                if ($count_ket_a == 0) {
                    $desc_ket_a = "";
                }
                if ($count_ket_b == 0) {
                    $desc_ket_b = "";
                }
                if ($count_ket_c == 0) {
                    $desc_ket_c = "";
                }
                if ($count_ket_d == 0) {
                    $desc_ket_d = "";
                }

                $desc_pengetahuan_final = $desc_peng_a . " " . $desc_peng_b . " " . $desc_peng_c . " " . $desc_peng_d;

                $desc_pengetahuan_final = substr($desc_pengetahuan_final, 0, -3);
                $desc_pengetahuan_final .= ".";

                $leger_arr[$item["name"]]["desc_pengetahuan"] = trim(preg_replace('/\s+/', ' ', $desc_pengetahuan_final));

                $desc_ket_final = $desc_ket_a . " " . $desc_ket_b . " " . $desc_ket_c . " " . $desc_ket_d;

                $desc_ket_final = substr($desc_ket_final, 0, -3);
                $desc_ket_final .= ".";

                $leger_arr[$item["name"]]["desc_keterampilan"] = trim(preg_replace('/\s+/', ' ', $desc_ket_final));


                // $cekpengetahuan = FinalMark::model()->findAll(array("condition"=>"user_id = ".htmlspecialchars(trim($siswa->id))." AND lesson_id = ".$item['id']." AND tipe = 'desc_pengetahuan' AND semester = '2' AND tahun_ajaran = '2016'"));
                //   if (empty($cekpengetahuan)) {
                $insert1 = "INSERT INTO final_mark (user_id,lesson_id,tipe,semester,tahun_ajaran,nilai_desc,created_at,updated_at,created_by,updated_by) values('" . htmlspecialchars(trim($siswa->id)) . "','" . $item['id'] . "','desc_pengetahuan',$optSemester,$optTahunAjaran,'" . $leger_arr[$item["name"]]["desc_pengetahuan"] . "',NOW(),NOW(),'" . Yii::app()->user->id . "','" . Yii::app()->user->id . "')";

                echo $insert1 . ";</br>";
                $insertCommand1 = Yii::app()->db->createCommand($insert1);

                if ($insertCommand1->execute()) {
                    echo "Berhasil" . "</br>";
                } else {
                    echo "Gagal" . "</br>";
                }
                // exit();
                // }
                // $cekketerampilan = FinalMark::model()->findAll(array("condition"=>"user_id = ".htmlspecialchars(trim($siswa->id))." AND lesson_id = ".$item['id']." AND tipe = 'desc_keterampilan' AND semester = '2' AND tahun_ajaran = '2016'"));
                // if(empty($cekketerampilan)){

                $insert2 = "INSERT INTO final_mark (user_id,lesson_id,tipe,semester,tahun_ajaran,nilai_desc,created_at,updated_at,created_by,updated_by) values('" . htmlspecialchars(trim($siswa->id)) . "','" . $item['id'] . "','desc_keterampilan',$optSemester,$optTahunAjaran,'" . $leger_arr[$item["name"]]["desc_keterampilan"] . "',NOW(),NOW(),'" . Yii::app()->user->id . "','" . Yii::app()->user->id . "')";

                echo $insert2 . ";</br>";

                $insertCommand2 = Yii::app()->db->createCommand($insert2);

                if ($insertCommand2->execute()) {
                    echo "Berhasil_ket" . "</br>";
                } else {
                    echo "Gagal_ket" . "</br>";
                }

                // }


                 // $desc_peng_a = "Memiliki kemampuan sangat baik dalam ";
                 // $desc_peng_b = "Memiliki kemampuan baik dalam ";
                 //  $desc_peng_c = "Memiliki kemampuan cukup dalam ";
                 // $desc_peng_d = "Memiliki kemampuan kurang dalam ";    



        $desc_peng_a = "Sangat memahami dalam ";
        $desc_peng_b = "Memahami dalam ";
        $desc_peng_c = "Mulai memahami dalam ";
        $desc_peng_d = "Mulai memahami dalam ";    

                $count_peng_a = 0;
                $count_peng_b = 0;
                $count_peng_c = 0;
                $count_peng_d = 0;

        // $desc_ket_a = "Memiliki keterampilan sangat baik dalam ";
        // $desc_ket_b = "Memiliki keterampilan baik dalam ";
        // $desc_ket_c = "Memiliki keterampilan cukup dalam ";
        // $desc_ket_d = "Memiliki keterampilan kurang dalam ";

        $desc_ket_a = "Sangat baik dalam ";
        $desc_ket_b = "Baik dalam ";
        $desc_ket_c = "Mulai dapat ";
        $desc_ket_d = "Mulai dapat ";


                $count_ket_a = 0;
                $count_ket_b = 0;
                $count_ket_c = 0;
                $count_ket_d = 0;
            }


            // $data[] = $arr;
            // echo "<pre>";
            // 	print_r($leger_arr);
            // echo "</pre>";
        }

        // $this->renderPartial('/clases/');
        // $this->renderPartial('/clases/raport-siswa-uts-all',array('inline_style'=>$inline_style,'siswa'=>$user,'peluts'=>$data,'profil'=>$user_data,'model'=>$model));
    }

    public function actionInputnilaiWalikelas($id) {
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

        if (isset($_POST['mark'])) {
            $usermarks = $_POST['mark'];
            foreach ($usermarks as $user => $value) {
                foreach ($value as $tipe => $desc) {
                    $finalmark = FinalMark::model()->findByAttributes(array(
                        'user_id' => $user,
                        'tipe' => $tipe));
                    
                    if ($desc != null) {
                        if (empty($finalmark)) {
                            $finalmark = new FinalMark;
                        }
                        $finalmark->user_id = $user;
                        $finalmark->lesson_id = 0;
                        $finalmark->tipe = $tipe;
                        $finalmark->nilai_desc = $desc;
                        $finalmark->nilai = 0;
                        $finalmark->semester = $optSemester;
                        $finalmark->tahun_ajaran = $optTahunAjaran;
                        if ($finalmark->save()) {
                            Yii::app()->user->setFlash('success', "Berhasil Input Nilai Siswa!");
                        }
                    } else {
                        if (!empty($finalmark)) {
                            $finalmark->delete();
                        }
                    }
                    
                }
            }
        }

        $siswa = User::model()->findAll(array('condition' => 'class_id = ' . $id . ' and trash is null order by display_name ASC'));
        $this->render('inputnilaiwalikelas', array(
            'model' => ClassDetail::model()->findByPk($id),
            'siswa' => $siswa,
        ));
    }

}
