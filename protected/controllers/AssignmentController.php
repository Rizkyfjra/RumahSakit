<?php

class AssignmentController extends Controller
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
        $cekFitur = Option::model()->findAll(array('condition' => 'key_config LIKE "%fitur_tugas%"'));
        $status = 1;

        if (!empty($cekFitur)) {
            if ($cekFitur[0]->value == 2) {
                $status = 2;
            }
        }

        return array(
            array('allow',  // allow all users to perform 'index' and 'view' actions
                'actions' => array('suggestSiswa'),
                'users' => array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('index', 'list', 'view', 'download', 'filterTugas'),
                //'users'=>array('@'),
                'expression' => "(!Yii::app()->user->isGuest && $status == 1)",
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('DownloadNilai','admin', 'delete', 'create', 'update', 'filter', 'filterTugas', 'addMark', 'copy', 'hapus', 'hapusoffline'),
                'expression' => "(Yii::app()->user->YiiAdmin && $status == 1)",
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('DownloadNilai','create', 'update', 'filter', 'filterTugas', 'addMark', 'copy', 'hapus', 'hapusoffline'),
                'expression' => "(Yii::app()->user->YiiTeacher && $status == 1)",
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

        $studentAssignment = new StudentAssignment;
        $notif = new Notification;
        $activity = new Activities;
        $model = $this->loadModel($id);
        $user = User::model()->findByPk(Yii::app()->user->id);

        if ($model->trash == 1) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }

        if (Yii::app()->user->YiiStudent) {
            $user_kelas = $user->class_id;
            $cekLs = LessonMc::model()->findByAttributes(array('user_id' => Yii::app()->user->id, 'lesson_id' => $model->lesson_id, 'semester' => $optSemester, 'year' => $optTahunAjaran));
        }

        $mapel = Lesson::model()->findByAttributes(array('id' => $model->lesson_id, 'semester' => $optSemester, 'year' => $optTahunAjaran));
        $kelas = ClassDetail::model()->findByAttributes(array('id' => $mapel->class_id));

        if (Yii::app()->user->YiiStudent AND $cekLs->lesson_id != $model->lesson_id) {
            Yii::app()->user->setFlash('error', "Maaf anda tidak punya izin akses!");
            $this->redirect(array('site/index'));
        }

        if ($model->lesson->moving_class == 1) {
            $cekUser = LessonMc::model()->findAll(array('condition' => 'lesson_id = ' . $model->lesson->id . " AND semester = " . $optSemester . " AND year = " . $optTahunAjaran));
            $user_ids = array();
            $list_user_id = NULL;
            if (!empty($cekUser)) {
                foreach ($cekUser as $cekUsr) {
                    array_push($user_ids, $cekUsr->user_id);
                }
                $list_user_id = implode(',', $user_ids);
            }

            $siswa = User::model()->findAll(array('condition' => 'id IN (' . $list_user_id . ') and trash is null'));
        } else {
            $siswa = User::model()->findAll(array('condition' => 'class_id = ' . $kelas->id . ' and trash is null'));
        }


        if ($type == 1) {
            $term = 't.status = 1 AND assignment_id = ' . $id . ' AND score is null';
        } else {
            $term = 't.status = 1 AND assignment_id = ' . $id;
        }


        $studentTasks = new CActiveDataProvider('StudentAssignment', array(
            'criteria' => array(
                'order' => 'student_id ASC',
                'condition' => $term),
            'pagination' => array('pageSize' => 50),
        ));
        $id_sa = NULL;

        if (isset($_POST['StudentAssignment'])) {
            if (isset($_POST['save'])) {
                $studentAssignment->status = null;
            } elseif (isset($_POST['upload'])) {
                $studentAssignment->status = 1;
            }

            $_POST['StudentAssignment']['content'] = str_replace("[math]", "$$", $_POST['StudentAssignment']['content']);
            $_POST['StudentAssignment']['content'] = str_replace("[/math]", "$$", $_POST['StudentAssignment']['content']);

            $studentAssignment->attributes = $_POST['StudentAssignment'];

            $uploadedFile = CUploadedFile::getInstance($studentAssignment, 'file');
            $studentAssignment->file = $uploadedFile;
            $student_id = Yii::app()->user->id;
            $cekTugas = StudentAssignment::model()->findByAttributes(array('assignment_id' => $model->id, 'student_id' => $student_id));
            $studentAssignment->assignment_id = $model->id;

            if ($studentAssignment->save()) {
                if (!file_exists(Yii::app()->basePath . '/../images/students/' . $student_id)) {
                    mkdir(Yii::app()->basePath . '/../images/students/' . $student_id, 0775, true);
                }

                if (!empty($uploadedFile)) {
                    $uploadedFile->saveAs(Yii::app()->basePath . '/../images/students/' . $student_id . '/' . $uploadedFile);
                }
                $id_sa = $studentAssignment->id;

                if ($studentAssignment->status == 1) {
                    $notif->content = "Tugas " . $model->title . " Sudah Dikumpulkan";
                    $notif->user_id = Yii::app()->user->id;
                    $notif->user_id_to = $model->teacher->id;
                    $notif->relation_id = $studentAssignment->id;
                    $notif->tipe = 'submit-question';
                    $notif->save();

                    $activity->activity_type = "Upload Tugas " . $model->title;
                    $activity->created_by = Yii::app()->user->id;
                    $activity->save();
                }
                if ($studentAssignment->status == 1) {
                    Yii::app()->user->setFlash('success', 'Tugas Berhasil Dikumpul');
                } else {
                    Yii::app()->user->setFlash('success', 'Tugas Berhasil Disimpan');
                }
            }
            //$this->redirect(array('view','id'=>$model->id));
        }

        // echo "<pre>";
        // 	print_r($studentTasks->getdata());
        // echo "</pre>";

        $this->render('v2/view', array(
            'model' => $this->loadModel($id),
            'studentAssignment' => $studentAssignment,
            'id_sa' => $id_sa,
            'studentTasks' => $studentTasks,
            'type' => $type,
            'siswa' => $siswa,
            'mapel' => $mapel,
        ));
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
        
        
        $user = User::model()->findByPk(Yii::app()->user->id);
        $user->display_name;
        $model = $this->loadModel($id);
        $mapel = $model->lesson;
        
        $online = @Option::model()->findByAttributes(array('key_config' => 'online'))->value;
        // if(!$online && ($model->quiz_type == 4 || $model->quiz_type == 5 || $model->quiz_type == 6)){
        //     throw new CHttpException(404, 'The requested page does not exist.');
        // }
        $kelas = $mapel->class;
        $prefix = Yii::app()->params['tablePrefix'];
        //$siswa = User::model()->findAll(array('condition'=>'class_id = '.$kelas->id.' and trash is null','order'=>'display_name'));
        $siswa = LessonMc::model()->findAll(array(
            'join' => 'join users u on u.id = t.user_id',
            'order' => 'u.display_name',
            'condition' => 't.lesson_id = ' . $mapel->id . ' and t.trash is null AND t.semester = ' . $optSemester . " AND t.year = " . $optTahunAjaran
        ));
        Yii::import('ext.phpexcel.XPHPExcel');
        $objPHPExcel = XPHPExcel::createPHPExcel();
        $objPHPExcel->getProperties()->setCreator($user->display_name)
                ->setLastModifiedBy($user->display_name)
                ->setTitle("Daftar Nilai " . $model->title)
                ->setSubject("")
                ->setDescription("")
                ->setKeywords("")
                ->setCategory($model->title);

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


        if ($model->lesson->moving_class == 1) {
            $kelasnya = "Moving Class";
        } else {
            $kelasnya = $kelas->name;
        }

        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('C2', 'Daftar Nilai ' . $model->title);
        /* $objPHPExcel->setActiveSheetIndex(0)
          ->setCellValue('C3','Bandung 40194');
          $objPHPExcel->setActiveSheetIndex(0)
          ->setCellValue('C4','Telp');
          $objPHPExcel->setActiveSheetIndex(0)
          ->setCellValue('C5','Website'); */
        $objPHPExcel->getActiveSheet()->getStyle('C2')->applyFromArray($styleArray);

        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A6', 'Kelas : ' . $kelasnya)
                ->setCellValue('A7', 'Mata Pelajaran : ' . $mapel->name)
                ->setCellValue('A8', 'Nama : ' . $model->title)
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
                // ->setCellValue('D10', 'Nilai Pilihan Ganda')
                // ->setCellValue('E10', 'Nilai Uraian')
                ->setCellValue('D10', 'Tanggal Dikumpulkan')
                ->setCellValue('E10', 'Tepat Waktu')
                ->setCellValue('F10', 'Nilai')
                ->mergeCells('A10:A11')
                ->mergeCells('B10:B11')
                ->mergeCells('C10:C11')
                ->mergeCells('D10:D11')
                ->mergeCells('E10:E11')
                ->mergeCells('F10:F11')
                //->mergeCells('E10:E11')
                //->mergeCells('F10:F11')
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
            $totNilai = StudentAssignment::model()->findByAttributes(array('student_id' => $key->user_id, 'assignment_id' => $model->id));
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $no . '', '' . $counter . '')
                    ->setCellValue('B' . $no . '', '' . $key->student->username . '')
                    ->setCellValue('C' . $no . '', '' . $key->student->display_name . '');
            if (!empty($totNilai)) {
               

                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('D' . $no . '', '' . $totNilai->created_at . '');

                    if(!empty($model->due_date > $totNilai->created_at)){
                                              $objPHPExcel->setActiveSheetIndex(0)
                                                 ->setCellValue('E' . $no . '', 'Ya');
                                        } else {
                                              $objPHPExcel->setActiveSheetIndex(0)
                                                 ->setCellValue('E' . $no . '', 'Tidak');
                                        }        

              

                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('F' . $no . '', '' . $totNilai->score . '');
                
            }


            $no++;
            $counter++;
            $cell++;
        }
        //$objPHPExcel->getActiveSheet()->getStyle('A12:'.$next[10].$no++)->applyFromArray($style2);
        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle('Rekap Nilai');


        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);


        // Redirect output to a clientâ€™s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Daftar-Nilai"' . $model->title . '-Kelas"' . $kelasnya . '".xls"');
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

    public function actionFilter($id)
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
        $term = 't.status = 1 AND assignment_id = ' . $id;
        $tipe = null;
        $nama = null;
        //$teacher_id=null;
        if (isset($_GET)) {
            $tipe = $_GET['tipe'];
            $nama = strtolower($_GET['nama']);

            switch ($tipe) {
                case 1:
                    $term = 't.status = 1 AND assignment_id = ' . $id . ' AND lower(l.name) like "%' . $nama . '%"';
                    break;
                case 2:
                    $term = 't.status = 1 AND assignment_id = ' . $id . ' AND lower(c.name) like "%' . $nama . '%"';
                    break;
                case 3:
                    $term = 't.status = 1 AND assignment_id = ' . $id . ' AND lower(r.display_name) like "%' . $nama . '%"';
                    break;
                /*case 4:
                    $term='assignment_id = '.$id.' AND lower(u.title) like "%'.$nama.'%"';
                    break;	*/
                default:
                    $term = 't.status = 1 AND assignment_id = ' . $id;
                    break;
            }
        }

        $studentTasks = new CActiveDataProvider('StudentAssignment', array(
            'criteria' => array(
                'order' => 'created_at DESC',
                'select' => array('t.*, u.title as title, u.due_date as due_date, l.name as lesson_name, c.name as class_name, r.display_name as display_name'),
                'join' => 'JOIN ' . $prefix . 'assignment AS u ON u.id = t.assignment_id JOIN ' . $prefix . 'lesson AS l ON l.id = u.lesson_id JOIN ' . $prefix . 'class AS c ON c.id = l.class_id JOIN ' . $prefix . 'users AS r ON r.id =t.student_id',
                'condition' => $term),
            'pagination' => array('pageSize' => 15),
        ));
        $id_sa = NULL;

        $this->render('filter-view', array(
            'model' => $this->loadModel($id),
            'id_sa' => $id_sa,
            'studentTasks' => $studentTasks,
        ));
    }

    public function actionFilterTugas()
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

        $term = '';
        $tipe = null;
        $nama = null;
        $usr = Yii::app()->user->id;
        $prefix = Yii::app()->params['tablePrefix'];
        //$teacher_id=null;
        if (isset($_GET)) {
            $tipe = $_GET['tipe'];
            $nama = strtolower($_GET['nama']);
            if (Yii::app()->user->YiiTeacher) {
                switch ($tipe) {
                    case 1:
                        $term = 't.created_by = ' . $usr . ' AND lower(t.title) like "%' . $nama . '%"';
                        break;
                    case 2:
                        $term = 't.created_by = ' . $usr . ' AND lower(l.name) like "%' . $nama . '%"';
                        break;
                    case 3:
                        $term = 't.created_by = ' . $usr . ' AND lower(c.name) like "%' . $nama . '%"';
                        break;
                    default:
                        $term = '';
                        break;
                }
            } elseif (Yii::app()->user->YiiStudent) {
                $kelas = User::model()->findByPk($usr);
                $kls = $kelas->class_id;
                switch ($tipe) {
                    case 1:
                        $term = 'c.id = ' . $kls . ' AND lower(t.title) like "%' . $nama . '%"';
                        break;
                    case 2:
                        $term = 'c.id = ' . $kls . ' AND lower(l.name) like "%' . $nama . '%"';
                        break;
                    case 3:
                        $term = 'c.id = ' . $kls . ' AND lower(c.name) like "%' . $nama . '%"';
                        break;
                    default:
                        $term = '';
                        break;
                }
            } else {
                $term = '';
            }

        }
        $term = $term . " AND t.semester = " . $optSemester . " AND t.year = " . $optTahunAjaran;

        $dataProvider = new CActiveDataProvider('Assignment', array(
            'criteria' => array(
                'order' => 'created_at DESC',
                'select' => array('t.*, l.name as lesson_name, c.name as class_name'),
                'join' => 'JOIN ' . $prefix . 'lesson AS l ON l.id = t.lesson_id JOIN ' . $prefix . 'class AS c ON c.id = l.class_id',
                'condition' => $term),
            'pagination' => array('pageSize' => 15),
        ));
        $this->render('filter-tugas', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate($type = null, $lesson_id = null)
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

        $activity = new Activities;
        $users = User::model()->findByPk(Yii::app()->user->id);
        /*$lessons=new CActiveDataProvider('Lesson',array(
            'criteria'=>array(
                'select'=>'t.*, c.name as class_name',
                'join'=>'JOIN class AS c ON c.id = t.class_id',
                'condition'=>'user_id = '.Yii::app()->user->id.''),
            ));*/

        if (Yii::app()->user->YiiTeacher) {
            $lessons = Lesson::model()->findAll(array('condition' => 'trash is null and user_id = ' . Yii::app()->user->id . " AND semester = " . $optSemester . " AND year = " . $optTahunAjaran));
        } else {
            $lessons = Lesson::model()->findAll(array('condition' => 'trash is null and semester = ' . $optSemester . " AND year = " . $optTahunAjaran));
        }

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Assignment']) || isset($_POST['lks_id'])) {

            $_POST['Assignment']['content'] = str_replace("[math]", "$$", $_POST['Assignment']['content']);
            $_POST['Assignment']['content'] = str_replace("[/math]", "$$", $_POST['Assignment']['content']);

            $model->attributes = $_POST['Assignment'];
            //$uploadedFile = CUploadedFile::getInstance($model, 'file');
            //$model->file = $uploadedFile;
            if (empty($type)) {
                $gambar = $_FILES['files'];

                $tmp = array();
                if (!empty($gambar)) {
                    $gmb = array();
                    foreach ($gambar['error'] as $k => $v) {
                        if ($v == UPLOAD_ERR_OK) {
                            $name = $gambar['name'][$k];
                            $temp_name = $gambar['tmp_name'][$k];
                            array_push($gmb, $name);
                            $tmp[$k] = $name;
                            //$tmp=array($k=>$v);
                            //$gmb = $gmb+$tmp;
                        }
                    }
                    //$model->choices_files=serialize($gmb);
                    $model->file = json_encode($tmp);
                }
            }

            date_default_timezone_set("Asia/Jakarta");
            $now_time = date("Y-m-d H:i:s");
            $now_time = new DateTime($now_time);
            $due_date_cek = new DateTime($model->due_date);

            if (empty($type)) {
                if ($model->due_date == '0000-00-00 00:00:00') {
                    Yii::app()->user->setFlash('error', "Maaf batas akhir pengumpulan tidak boleh kosong!");
                    $this->redirect(array('create'));
                }

                if ($due_date_cek < $now_time) {
                    Yii::app()->user->setFlash('error', "Maaf batas akhir pengumpulan tidak boleh kurang dari waktu sekarang!");
                    $this->redirect(array('create'));
                }
            } else {
                $model->assignment_type = 1;
            }

            $model->semester = $optSemester;
            $model->year = $optTahunAjaran;

            $lks_id = $_POST['lks_id'];
            if (!empty($lks_id)) {
                $lks = Lks::model()->findByPk($lks_id);
            }

            if (isset($_POST['simpan'])) {
                $model->status = null;
            } elseif (isset($_POST['tampil'])) {
                $model->status = 1;
            }

            if (!empty($lesson_id)) {
                $model->lesson_id = $lesson_id;
            }
            // echo "<pre>";
            // print_r($_FILES['files']);
            // echo "</pre>";
            if ($model->save()) {
                if (!empty($lks_id)) {
                    if (!empty($lks->assignments)) {
                        $lks->assignments = $lks->assignments . "," . $model->id;
                    } else {
                        $lks->assignments = $model->id;
                    }
                    $totTugas = explode(',', $lks->assignments);
                    $tm = count($totTugas);
                    //$cekQuiz->total_question=$tq;
                    $lks->save();
                }
                if (empty($type)) {
                    if (empty($model->recipient)) {
                        $siswa = LessonMc::model()->findAll(array('condition' => 'lesson_id = ' . $model->lesson_id . " AND semester = " . $optSemester . " AND year = " . $optTahunAjaran));
                        $kelas = Lesson::model()->findByPk($model->lesson_id);

                        if (!empty($siswa)) {
                            foreach ($siswa as $murid) {

                                $notif = new Notification;
                                $notif->content = "Guru " . $users->display_name . " Menambah Tugas Baru";
                                $notif->user_id = Yii::app()->user->id;
                                $notif->relation_id = $model->id;
                                //$notif->class_id_to=$kelas->class_id;
                                $notif->user_id_to = $murid->user_id;
                                $notif->tipe = "assignment";
                                $notif->save();
                            }
                        }

                        // $activity->activity_type="Buat Tugas ".$kelas->name;
                        $activity->activity_type = "new_assignment";
                        $activity->created_by = Yii::app()->user->id;
                        $activity->save();
                    } else {
                        $notif = new Notification;
                        $notif->content = "Guru " . $users->display_name . " Menambah Tugas Baru";
                        $notif->user_id = Yii::app()->user->id;
                        $notif->relation_id = $model->id;
                        $notif->user_id_to = $model->recipient;
                        $notif->tipe = "assignment";
                        $notif->save();

                        $siswa = User::model()->findByPk($model->recipient);
                        $activity->activity_type = "Buat Tugas Perseorangan " . $siswa->display_name;
                        $activity->created_by = Yii::app()->user->id;
                        $activity->save();
                    }
                }

                if (!file_exists(Yii::app()->basePath . '/../images/assignment/' . $model->id)) {
                    mkdir(Yii::app()->basePath . '/../images/assignment/' . $model->id, 0775, true);
                }

                //if(!empty($uploadedFile)){
                //	$uploadedFile->saveAs(Yii::app()->basePath.'/../images/assignment/'.$model->id.'/'.$uploadedFile);
                //}
                if (empty($type)) {
                    if (!empty($gambar)) {

                        foreach ($gambar['error'] as $k => $v) {
                            if ($v == UPLOAD_ERR_OK) {
                                $name = $gambar['name'][$k];
                                $temp_name = $gambar['tmp_name'][$k];

                                move_uploaded_file($temp_name, Yii::app()->basePath . '/../images/assignment/' . $model->id . '/' . $name);

                            }
                        }
                    }
                }

                Yii::app()->user->setFlash('success', 'Tugas Berhasil Dibuat !');


                if (!empty(Yii::app()->session['returnURL'])) {
                    $this->redirect(Yii::app()->session['returnURL']);
                    Yii::app()->session->remove('returnURL');
                } else {
                    if (!empty($lks_id)) {
                        $this->redirect(array('/lks/view', 'id' => $lks_id));
                    } else {
                        $this->redirect(array('view', 'id' => $model->id));
                    }
                }
            }
        }


        $activity->activity_type = "open_form_create_assignment";
        $activity->created_by = Yii::app()->user->id;
        $activity->save();
        $this->render('v2/form', array(
            'model' => $model,
            'lessons' => $lessons,
        ));
    }

    public function actionCopy($id)
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

        $tugas = $this->loadModel($id);
        $model = new Assignment;
        if (Yii::app()->user->YiiTeacher) {
            $lessons = Lesson::model()->findAll(array('condition' => 'user_id = ' . Yii::app()->user->id . ' AND trash is NULL AND semester = ' . $optSemester . " AND year = " . $optTahunAjaran));
        } else {
            $lessons = Lesson::model()->findAll(array('condition' => 'trash is NULL AND semester = ' . $optSemester . " AND year = " . $optTahunAjaran));
        }

        if (isset($_POST['Assignment'])) {
            $model->attributes = $_POST['Assignment'];

            $model->content = $tugas->content;
            $model->file = $tugas->file;
            // $model->chapter_type = $materi->chapter_type;
            $model->status = $tugas->status;
            $model->semester = $tugas->semester;
            $model->year = $tugas->year;


            if ($model->save()) {

                if (!empty($tugas->file)) {
                    if (!file_exists(Yii::app()->basePath . '/../images/assignment/' . $model->id)) {
                        mkdir(Yii::app()->basePath . '/../images/assignment/' . $model->id, 0775, true);
                    }
                    $tugasfilearray = json_decode($tugas->file);

                    foreach ($tugasfilearray as $tugasFile) {
                        copy(Yii::app()->basePath . '/../images/assignment/' . $tugas->id . '/' . $tugasFile, Yii::app()->basePath . '/../images/assignment/' . $model->id . '/' . $tugasFile);
                    }
                }

                Yii::app()->user->setFlash('success', 'Tugas Berhasil Disalin!');
                $this->redirect(array('view', 'id' => $model->id));
            }
        }

        $this->render('v2/copy', array(
            'tugas' => $tugas,
            'model' => $model,
            'lessons' => $lessons,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id, $type = null)
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

        if ($model->trash == 1) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        $old_file = $model->file;
        $notif = new Notification;
        $activity = new Activities;
        $users = User::model()->findByPk(Yii::app()->user->id);
        /*$lessons=new CActiveDataProvider('Lesson',array(
            'criteria'=>array(
                'select'=>'t.*, c.name as class_name',
                'join'=>'JOIN class AS c ON c.id = t.class_id',
                'condition'=>'user_id = '.Yii::app()->user->id.''),
            ));*/
        $lessons = Lesson::model()->findAll(array('condition' => 'user_id = ' . Yii::app()->user->id . " AND semester = " . $optSemester . " AND year = " . $optTahunAjaran));
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        if (Yii::app()->user->id != $model->created_by and !Yii::app()->user->YiiAdmin) {
            Yii::app()->user->setFlash('error', "Maaf anda tidak punya izin akses!");
            $this->redirect(array('view', 'id' => $model->id));

        } else {

            if (isset($_POST['Assignment'])) {

                $_POST['Assignment']['content'] = str_replace("[math]", "$$", $_POST['Assignment']['content']);
                $_POST['Assignment']['content'] = str_replace("[/math]", "$$", $_POST['Assignment']['content']);
                $model->attributes = $_POST['Assignment'];
                //$uploadedFile = CUploadedFile::getInstance($model, 'file');
                //if(!empty($uploadedFile)){
                //	$model->file = $uploadedFile;
                //}else{
                //	$model->file = $old_file;
                //}
                if (empty($type)) {
                    $gambar = $_FILES['files'];

                    $tmp = array();
                    if (!empty($gambar)) {
                        $gmb = array();
                        foreach ($gambar['error'] as $k => $v) {
                            if ($v == UPLOAD_ERR_OK) {
                                $name = $gambar['name'][$k];
                                $temp_name = $gambar['tmp_name'][$k];
                                array_push($gmb, $name);
                                $tmp[$k] = $name;
                                //$tmp=array($k=>$v);
                                //$gmb = $gmb+$tmp;
                            }
                        }
                        //$model->choices_files=serialize($gmb);
                        if (!empty($tmp)) {
                            $model->file = json_encode($tmp);
                        } else {
                            $model->file = $old_file;
                        }
                    } else {
                        $model->file = $old_file;
                    }
                }
                $model->sync_status = NULL;
                date_default_timezone_set("Asia/Jakarta");
                $now_time = date("Y-m-d H:i:s");
                $now_time = new DateTime($now_time);
                $due_date_cek = new DateTime($model->due_date);
                if ($model->assignment_type == NULL) {
                    if ($model->due_date == '0000-00-00 00:00:00') {
                        Yii::app()->user->setFlash('error', "Maaf batas akhir pengumpulan tidak boleh kosong!");
                        $this->redirect(array('update', 'id' => $model->id));
                    }

                    if ($due_date_cek < $now_time) {
                        Yii::app()->user->setFlash('error', "Maaf batas akhir pengumpulan tidak boleh kurang dari waktu sekarang!");
                        $this->redirect(array('update', 'id' => $model->id));
                    }
                } else {
                    $model->assignment_type = 1;
                }

                $model->sync_status = 2;
                if ($model->save()) {
                    $kelas = Lesson::model()->findByPk($model->lesson_id);
                    $notif->content = "Guru " . $users->display_name . " Mengubah Tugas " . $model->title;
                    $notif->user_id = Yii::app()->user->id;
                    $notif->relation_id = $model->id;
                    $notif->tipe = "assignment";
                    $notif->class_id_to = $kelas->class_id;
                    $notif->save();

                    $activity->activity_type = "Update Tugas " . $kelas->name;
                    $activity->created_by = Yii::app()->user->id;
                    $activity->save();

                    if (!file_exists(Yii::app()->basePath . '/../images/assignment/' . $model->id)) {
                        mkdir(Yii::app()->basePath . '/../images/assignment/' . $model->id, 0775, true);
                    }

                    //if(!empty($uploadedFile)){
                    //	$uploadedFile->saveAs(Yii::app()->basePath.'/../images/assignment/'.$model->id.'/'.$uploadedFile);
                    //}
                    if (empty($type)) {
                        if (!empty($gambar)) {

                            foreach ($gambar['error'] as $k => $v) {
                                if ($v == UPLOAD_ERR_OK) {
                                    $name = $gambar['name'][$k];
                                    $temp_name = $gambar['tmp_name'][$k];

                                    move_uploaded_file($temp_name, Yii::app()->basePath . '/../images/assignment/' . $model->id . '/' . $name);

                                }
                            }
                        }
                    }

                    Yii::app()->user->setFlash('success', 'Tugas Berhasil Diubah !');

                    if (!empty(Yii::app()->session['returnURL'])) {
                        $this->redirect(Yii::app()->session['returnURL']);
                        Yii::app()->session->remove('returnURL');
                    } else {
                        $this->redirect(array('view', 'id' => $model->id));
                    }
                }
            }

            $this->render('v2/form', array(
                'model' => $model,
                'lessons' => $lessons,
            ));
        }
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

        $model = $this->loadModel($id);

        $model->trash = 1;
        $model->sync_status = 2;

        if ($model->save()) {
            Yii::app()->user->setFlash('error', 'Tugas Berhasil Dihapus !');

            if (!empty(Yii::app()->session['returnURL'])) {
                $this->redirect(Yii::app()->session['returnURL']);
                Yii::app()->session->remove('returnURL');
            } else {
                $this->redirect(Yii::app()->request->urlReferrer);
            }
        }
    }

    public function actionHapusoffline($id)
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

        $model->trash = 1;
        $model->sync_status = 2;

        if ($model->save()) {
            Yii::app()->user->setFlash('error', 'Tugas Berhasil Dihapus !');

            if (!empty(Yii::app()->session['returnURL'])) {
                $this->redirect(Yii::app()->session['returnURL']);
                Yii::app()->session->remove('returnURL');
            } else {
                $this->redirect(Yii::app()->request->urlReferrer);
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
        $nilai = $_POST['score'];
        $siswa = $_POST['student_id'];
        $aid = $_POST['assignment_id'];
        $lid = $_POST['lesson_id'];
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
                    $command->bindParam(":lid", $lid, PDO::PARAM_STR);
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
        $this->redirect(array('view', 'id' => $id));
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
    public function actionIndex($type = NULL, $l_id = NULL)
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
        $prefix = Yii::app()->params['tablePrefix'];
        if (Yii::app()->user->YiiTeacher) {
            $term_condition = "l.user_id = $current_user";
        } elseif (Yii::app()->user->YiiStudent) {
            $modelUser = User::model()->findByPk($current_user);
            $class_student_id = $modelUser->class_id;

            $term_condition = "l.class_id = $class_student_id and assignment_type is null and (recipient is null or recipient = " . Yii::app()->user->id . ")";
            $term_condition2 = "assignment_type is null and recipient = " . Yii::app()->user->id;

            if (!empty($l_id)) {
                $term_condition = "t.lesson_id = " . $l_id . " and assignment_type is null and recipient is null or recipient = " . Yii::app()->user->id;
                $term_condition2 = "l.class_id = $class_student_id and assignment_type is null and t.lesson_id = " . $l_id . " and recipient = " . Yii::app()->user->id;
            }
        } else {
            $term_condition = '';
        }

        if (empty($term_condition)) {
            $term_condition = "t.semester = " . $optSemester . " AND t.year = " . $optTahunAjaran . " AND t.trash IS NULL";
        } else {
            $term_condition = $term_condition . " AND  t.semester = " . $optSemester . " AND t.year = " . $optTahunAjaran . " AND t.trash IS NULL";
        }

        /*$tugasIndividu = new CActiveDataProvider('Assignment', array(
                'criteria'=>array(
                    'order'=>'t.due_date DESC',
                    'join' => 'JOIN lesson AS l ON l.id = t.lesson_id JOIN class AS c ON c.id = l.class_id',
                    'condition'=>''.$term_condition2,
                ),
                'pagination'=>array(
                    'pageSize'=>'12'
                ),
        ));*/

        $dataProvider = new CActiveDataProvider('Assignment', array(
            'criteria' => array(
                'order' => 't.due_date DESC',
                'join' => 'JOIN ' . $prefix . 'lesson AS l ON l.id = t.lesson_id ',
                'condition' => '' . $term_condition,
            ),
            'pagination' => array(
                'pageSize' => '5'
            ),
        ));

        Yii::app()->session->remove('returnURL');
        $this->render('v2/index', array(
            'dataProvider' => $dataProvider,
            //'tugasIndividu'=>$tugasIndividu,
            'type' => $type,
        ));
    }

    public function actionList()
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
        $prefix = Yii::app()->params['tablePrefix'];
        if (Yii::app()->user->YiiTeacher) {
            $term_condition = "l.user_id = $current_user";
        } elseif (Yii::app()->user->YiiStudent) {
            $modelUser = User::model()->findByPk($current_user);
            $class_student_id = $modelUser->class_id;
            $term_condition = "l.class_id = $class_student_id and assignment_type is null and recipient is null or recipient = " . Yii::app()->user->id;
            $term_condition2 = "assignment_type is null and recipient = " . Yii::app()->user->id;

            if (!empty($l_id)) {
                $term_condition = "t.lesson_id = " . $l_id . " and assignment_type is null and recipient is null or recipient = " . Yii::app()->user->id;
                $term_condition2 = "l.class_id = $class_student_id and assignment_type is null and t.lesson_id = " . $l_id . " and recipient = " . Yii::app()->user->id;
            }
        } else {
            $term_condition = '';
        }

        if (empty($term_condition)) {
            $term_condition = "t.semester = " . $optSemester . " AND t.year = " . $optTahunAjaran . " AND t.trash IS NULL";
        } else {
            $term_condition = $term_condition . " AND t.semester = " . $optSemester . " AND t.year = " . $optTahunAjaran . " AND t.trash IS NULL";
        }

        /*$tugasIndividu = new CActiveDataProvider('Assignment', array(
                'criteria'=>array(
                    'order'=>'t.due_date DESC',
                    'join' => 'JOIN lesson AS l ON l.id = t.lesson_id JOIN class AS c ON c.id = l.class_id',
                    'condition'=>''.$term_condition2,
                ),
                'pagination'=>array(
                    'pageSize'=>'12'
                ),
        ));*/

        $dataProvider = new CActiveDataProvider('Assignment', array(
            'criteria' => array(
                'order' => 't.due_date DESC',
                'join' => 'JOIN ' . $prefix . 'lesson AS l ON l.id = t.lesson_id ',
                'condition' => '' . $term_condition,
            ),
            'pagination' => array(
                'pageSize' => '15'
            ),
        ));

        Yii::app()->session->remove('returnURL');
        $this->render('v2/list', array(
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

        $model = new Assignment('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Assignment']))
            $model->attributes = $_GET['Assignment'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    public function actionDownload($id, $nama = null)
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

        $cekFile = Assignment::model()->findByPk($id);
        if (!empty($cekFile)) {
            $name = $cekFile->file;
            //$lesson_id = $cekFile->idChapter->id_lesson;
        } else {
            $name = NULL;
            //$lesson_id = NULL;
        }

        //$dir_path = Yii::getPathOfAlias('webroot') . '/images/resums/';
        if (!empty($nama)) {
            $files = json_decode($name, true);
            foreach ($files as $value) {
                if ($value == $nama) {
                    $fileName = Yii::app()->basePath . '/../images/assignment/' . $cekFile->id . '/' . $nama;
                }
            }

        } else {
            $fileName = Yii::app()->basePath . '/../images/assignment/' . $cekFile->id . '/' . $name;
        }

        if (file_exists($fileName)) {
            if (!empty($nama)) {
                return Yii::app()->getRequest()->sendFile($nama, @file_get_contents($fileName));
            } else {
                return Yii::app()->getRequest()->sendFile($name, @file_get_contents($fileName));
            }

        } else {
            throw new CHttpException(404, 'File Tidak Ditemukan.');
        }

    }

    public function actionSuggestSiswa()
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
        //$kelas=$_GET['idk'];
        /*$mapel=Lesson::model()->findByPk($mp);
        $kelas=Clases::model()->findByPk($mapel->class_id);
        $nk=$kelas->id;*/
        //echo $request;
        //echo $nk;
        if ($request != '') {

            if (Yii::app()->user->YiiTeacher || Yii::app()->user->YiiKepsek) {
                $current_user = Yii::app()->user->id;
                //$term_conditon = " AND class_id = $nk";
                $term_conditon = " and class_id is not null";
            } else {
                //$term_conditon = " AND class_id = $nk";
            }
            $model = User::model()->findAll(array("condition" => "lower(display_name) like lower('$request%') $term_conditon"));
            $data = array();
            foreach ($model as $get) {
                $kelas = Clases::model()->findByPk($get->class_id);
                $data[] = $get->display_name . ' (ID:' . $get->id . ')';

            }
            $this->layout = 'empty';
            echo json_encode($data);
        }
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Assignment the loaded model
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

        $model = Assignment::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Assignment $model the model to be validated
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

        if (isset($_POST['ajax']) && $_POST['ajax'] === 'assignment-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
