<?php

class AbsensiController extends Controller {

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
        return array(
            array('allow', // allow all users to perform 'index' and 'view' actions
                'actions' => array('index', 'view', 'AbsenSiswa'),
                'users' => array('@'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('RekapAbsenSiswa','RekapAbsenGuru','create', 'update', 'bulkMateri', 'bulkTugas', 'bulkUlangan'),
                'expression' => 'Yii::app()->user->YiiTeacher',
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('RekapAbsenSiswa','RekapAbsenGuru','create', 'AbsenGuru', 'update', 'admin', 'delete', 'bulkMateri', 'bulkTugas', 'bulkUlangan'),
                'expression' => 'Yii::app()->user->YiiAdmin',
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actionView($id) {
        $model = $this->loadModel($id);
        $materis = '';
        $tugases = '';
        $ulangans = '';

        if (!empty($model->chapters)) {
            $materis = $model->chapters;
            $materi = Chapters::model()->findAll(array('condition' => 'id_lesson = ' . $model->lesson_id . ' and id not in (' . $materis . ')'));
        } else {
            $materi = Chapters::model()->findAll(array('condition' => 'id_lesson = ' . $model->lesson_id));
        }

        if (!empty($model->assignments)) {
            $tugases = $model->assignments;
            $tugas = Assignment::model()->findAll(array('condition' => 'lesson_id = ' . $model->lesson_id . ' and id not in (' . $tugases . ')'));
        } else {
            $tugas = Assignment::model()->findAll(array('condition' => 'lesson_id = ' . $model->lesson_id));
        }

        if (!empty($model->quizes)) {
            $ulangans = $model->quizes;

            if (!Yii::app()->user->YiiStudent) {
                $ulangan = Quiz::model()->findAll(array('condition' => 'lesson_id = ' . $model->lesson_id . ' and id not in (' . $ulangans . ')'));
            } else {
                $ulangan = Quiz::model()->findAll(array('condition' => 'lesson_id = ' . $model->lesson_id . ' and id not in (' . $ulangans . ') and status = 1'));
            }
        } else {
            if (!Yii::app()->user->YiiStudent) {
                $ulangan = Quiz::model()->findAll(array('condition' => 'lesson_id = ' . $model->lesson_id));
            } else {
                $ulangan = Quiz::model()->findAll(array('condition' => 'lesson_id = ' . $model->lesson_id . ' and status = 1'));
            }
        }

        $this->render('view', array(
            'model' => $this->loadModel($id),
            'materi' => $materi,
            'tugas' => $tugas,
            'ulangan' => $ulangan
        ));
    }

    public function actionAbsenSiswa() {

        if (!empty($_GET['datetime'])) {
            $mydate = new DateTime($_GET['datetime']);
            $tomorow = new DateTime($_GET['datetime']);
        } else {
            $mydate = new DateTime();
            $tomorow = new DateTime();
        }


        $mydate = $mydate->format('Y-m-d');
        $tomorow->modify('+1 day');
        $tomorow = $tomorow->format('Y-m-d');

        $absensi = AbsensiSolution::model()->findAll(array('condition' => ' datetime > \'' . $mydate . '\' and datetime < \'' . $tomorow . '\' '));

        $result = array();

        // $query = AbsensiSolution::find()->where(['>', 'datetime', new Expression('NOW()')]);
        // $absensi = new ActiveDataProvider(['query' => $query]);

        foreach ($absensi as $key => $value) {


          if (isset($value->guru->role_id)) {  
          if ($value->guru->role_id == '2') {
           

            if (isset($value->guru->display_name)) {
                    $result[$value->user_id]["display_name"] = $value->guru->display_name;
                    $kelasnya = ClassDetail::model()->findByPk($value->guru->class_id);
                    $result[$value->user_id]["kelasnya"] = $kelasnya;
                    $result[$value->user_id]["idUsernya"] = $value->guru->id;
                    // $absensi_harian = AbsensiHarian::model()->findAll(array('condition' => ' tgl = \'' . $mydate .'\' and user_id = ' . $value->guru->id));
                    // $result[$value->user_id]["absensi_harian"] = $absensi_harian;
                } else {
                    // $result[$value->user_id]["display_name"] = $value->guru->display_name;   
                }

                if (isset($value->guru->username)) {
                    $result[$value->user_id]["username"] = $value->guru->username;
                } else {
                    // $result[$value->user_id]["display_name"] = $value->guru->display_name;   
                }

                if (!empty($result[$value->user_id]["datetime"][0])) {
                    $date_one = strtotime($value->datetime);
                    $date_two = strtotime($result[$value->user_id]["datetime"][0]);

                    $dt = new DateTime($value->datetime);
                    $thetime_two = $dt->format('H:i:s');

                    $date_diff = round(abs($date_one - $date_two) / 60);
                    // if ($date_diff > 360) {
                     if (strtotime($thetime_two) > strtotime('11:00')) {
                        $result[$value->user_id]["datetime_gohome"][] = $value->datetime;
                        $value->datetime = strtotime($value->datetime);
                        if (date("H:i:s", $value->datetime) > date("H:i:s", strtotime("16:00:00"))) {
                            $result[$value->user_id]["isOvertime"][] = "ya";
                            $result[$value->user_id]["howlong"][] = $date_diff;
                        } else {
                            $result[$value->user_id]["isOvertime"][] = "tidak";
                            $result[$value->user_id]["howlong"][] = $date_diff;
                        }
                    } else {

                        $result[$value->user_id]["datetime"][] = $value->datetime;
                        $value->datetime = strtotime($value->datetime);
                        if (date("H:i:s", $value->datetime) > date("H:i:s", strtotime("07:00:00"))) {
                            $result[$value->user_id]["isLate"][] = "ya";
                        } else {
                            $result[$value->user_id]["isLate"][] = "tidak";
                        }
                    }
                } else {

                    $result[$value->user_id]["datetime"][] = $value->datetime;
                    $value->datetime = strtotime($value->datetime);
                    if (date("H:i:s", $value->datetime) > date("H:i:s", strtotime("07:00:00"))) {
                        $result[$value->user_id]["isLate"][] = "ya";
                    } else {
                        $result[$value->user_id]["isLate"][] = "tidak";
                    }
                }
            }
           }
        }




        // echo "<pre>";
        //      print_r($result);
        // echo "</pre>";


        $this->render('view-siswa', array(
            'absensi' => $result
        ));
    }

   

    public function actionAbsenGuru() {

        if (!empty($_GET['datetime'])) {
            $mydate = new DateTime($_GET['datetime']);
            $tomorow = new DateTime($_GET['datetime']);
        } else {
            $mydate = new DateTime();
            $tomorow = new DateTime();
        }


        $mydate = $mydate->format('Y-m-d');
        $tomorow->modify('+1 day');
        $tomorow = $tomorow->format('Y-m-d');

        $absensi = AbsensiSolution::model()->findAll(array('condition' => ' datetime > \'' . $mydate . '\' and datetime < \'' . $tomorow . '\' '));

        $result = array();

        // $query = AbsensiSolution::find()->where(['>', 'datetime', new Expression('NOW()')]);
        // $absensi = new ActiveDataProvider(['query' => $query]);

        foreach ($absensi as $key => $value) {

          if (isset($value->guru->role_id)) {   
          if ($value->guru->role_id != '2') {
           

            if (isset($value->guru->display_name)) {
                    $result[$value->user_id]["display_name"] = $value->guru->display_name;
                    $result[$value->user_id]["idUsernya"] = $value->guru->id;
                } else {
                    // $result[$value->user_id]["display_name"] = $value->guru->display_name;	
                }

                if (isset($value->guru->username)) {
                    $result[$value->user_id]["username"] = $value->guru->username;
                } else {
                    // $result[$value->user_id]["display_name"] = $value->guru->display_name;	
                }

                if (!empty($result[$value->user_id]["datetime"][0])) {
                    $date_one = strtotime($value->datetime);
                    $date_two = strtotime($result[$value->user_id]["datetime"][0]);

                     $dt = new DateTime($value->datetime);
                    $thetime_two = $dt->format('H:i:s');


                    $date_diff = round(abs($date_one - $date_two) / 60);
                    // if ($date_diff > 360) {
                    if (strtotime($thetime_two) > strtotime('11:00')) {
                        $result[$value->user_id]["datetime_gohome"][] = $value->datetime;
                        $value->datetime = strtotime($value->datetime);
                        if (date("H:i:s", $value->datetime) > date("H:i:s", strtotime("16:00:00"))) {
                            $result[$value->user_id]["isOvertime"][] = "ya";
                            $result[$value->user_id]["howlong"][] = $date_diff;
                        } else {
                            $result[$value->user_id]["isOvertime"][] = "tidak";
                            $result[$value->user_id]["howlong"][] = $date_diff;
                        }
                    } else {

                        $result[$value->user_id]["datetime"][] = $value->datetime;
                        $value->datetime = strtotime($value->datetime);
                        if (date("H:i:s", $value->datetime) > date("H:i:s", strtotime("07:00:00"))) {
                            $result[$value->user_id]["isLate"][] = "ya";
                        } else {
                            $result[$value->user_id]["isLate"][] = "tidak";
                        }
                    }
                } else {

                    $result[$value->user_id]["datetime"][] = $value->datetime;
                    $value->datetime = strtotime($value->datetime);
                    if (date("H:i:s", $value->datetime) > date("H:i:s", strtotime("07:00:00"))) {
                        $result[$value->user_id]["isLate"][] = "ya";
                    } else {
                        $result[$value->user_id]["isLate"][] = "tidak";
                    }
                }
            }
            }
        }




        // echo "<pre>";
        // 		print_r($result);
        // echo "</pre>";


        $this->render('view', array(
            'absensi' => $result
        ));
    }


     public function actionRekapAbsenGuru() {

        if (!empty($_GET['datetime'])) {
            $mydate = new DateTime($_GET['datetime']);
            $tomorow = new DateTime($_GET['datetime']);
        } else {
            $mydate = new DateTime();
            $tomorow = new DateTime();
        }

      

        // $mydate = new DateTime("2018/02/20 13:26");
        // $tomorow = new DateTime("2018/02/21 13:26");
       
        $final_result = array();

        for ($i=0; $i < 31; $i++) { 

             if (!empty($_GET['datetime'])) {
                    $mydate = new DateTime($_GET['datetime']);
                    $tomorow = new DateTime($_GET['datetime']);
                } else {
                    $mydate = new DateTime();
                    $tomorow = new DateTime();
                }

            if ($i==0) {
                $mydate = $mydate->format('Y-m-d');
                $tomorow->modify('+1 day');
                $tomorow = $tomorow->format('Y-m-d'); 
            } else {
                // $mydate = $mydate->format('Y-m-d');
                // $tomorow->modify('+1 day');
                // $tomorow = $tomorow->format('Y-m-d'); 
                $penambah = $i;
                $penambahsatu = $i+1;
                $mydate->modify('+'.$penambah.' day');
                $mydate = $mydate->format('Y-m-d');
                $tomorow->modify('+'.$penambahsatu.' day');
                $tomorow = $tomorow->format('Y-m-d'); 


            }

           // $mydate->modify('+1 day');
           // $besok = $tomorow->modify('+1 day');
           // $tanggal = $mydate->format('Y-m-d');
           // $tanggal_besok = $besok->format('Y-m-d');
           // echo $tanggal." ".$tanggal_besok."</br>";
        

        // $mydate = $mydate->format('Y-m-d');
        // $tomorow->modify('+1 day');
        // $tomorow = $tomorow->format('Y-m-d');

        $absensi = AbsensiSolution::model()->findAll(array('condition' => ' datetime > \'' . $mydate . '\' and datetime < \'' . $tomorow . '\' '));

        $result = array();

        // $query = AbsensiSolution::find()->where(['>', 'datetime', new Expression('NOW()')]);
        // $absensi = new ActiveDataProvider(['query' => $query]);

        foreach ($absensi as $key => $value) {
          if (isset($value->guru->role_id)) {   
          if ($value->guru->role_id != '2') {  
            if (isset($value->guru->display_name)) {
                $result[$value->user_id]["display_name"] = $value->guru->display_name;
                $result[$value->user_id]["idUsernya"] = $value->guru->id;
            } else {
                // $result[$value->user_id]["display_name"] = $value->guru->display_name;   
            }

            if (isset($value->guru->username)) {
                $result[$value->user_id]["username"] = $value->guru->username;
            } else {
                // $result[$value->user_id]["display_name"] = $value->guru->display_name;   
            }

            if (!empty($result[$value->user_id]["datetime"][0])) {
                $date_one = strtotime($value->datetime);
                $date_two = strtotime($result[$value->user_id]["datetime"][0]);
                $date_diff = round(abs($date_one - $date_two) / 60);
                if ($date_diff > 360) {
                    $result[$value->user_id]["datetime_gohome"][] = $value->datetime;
                    $value->datetime = strtotime($value->datetime);
                    if (date("H:i:s", $value->datetime) > date("H:i:s", strtotime("16:00:00"))) {
                        $result[$value->user_id]["isOvertime"][] = "ya";
                        $result[$value->user_id]["howlong"][] = $date_diff;
                    } else {
                        $result[$value->user_id]["isOvertime"][] = "tidak";
                        $result[$value->user_id]["howlong"][] = $date_diff;
                    }
                } else {

                    $result[$value->user_id]["datetime"][] = $value->datetime;
                    $value->datetime = strtotime($value->datetime);
                    if (date("H:i:s", $value->datetime) > date("H:i:s", strtotime("07:00:00"))) {
                        $result[$value->user_id]["isLate"][] = "ya";
                    } else {
                        $result[$value->user_id]["isLate"][] = "tidak";
                    }
                }
            } else {

                $result[$value->user_id]["datetime"][] = $value->datetime;
                $value->datetime = strtotime($value->datetime);
                if (date("H:i:s", $value->datetime) > date("H:i:s", strtotime("07:00:00"))) {
                    $result[$value->user_id]["isLate"][] = "ya";
                } else {
                    $result[$value->user_id]["isLate"][] = "tidak";
                }
            }

          } 
          } 
        }

        // echo $mydate."</br>";
        // echo $tomorow."</br>";

        // echo "<pre>";
        //      print_r($result);
        // echo "</pre>";

        

       

        foreach ($result as $key => $value) {

            if (isset($value['display_name'])) {
                $final_result[$key]["display_name"] = $value['display_name'];
            } else {
                $final_result[$key]["display_name"] = "";
            }


              if (isset($value['username'])) {
                $final_result[$key]["username"] = $value['username'];
            } else {
                $final_result[$key]["username"] = "";
            }


            if (!empty($value['datetime'])) {
                
                if (isset($final_result[$key]['hadir'])) {
                    $final_result[$key]['hadir'] = $final_result[$key]['hadir'] +  1;
                } else {
                    $final_result[$key]['hadir'] = 1;
                }
                


                if (!empty($value['isLate'])) {
                    if ($value['isLate'][0] == 'ya') {
                       
                       if (isset($final_result[$key]['telat'])) {
                          $final_result[$key]['telat'] =  $final_result[$key]['telat'] + 1;
                       } else {
                         $final_result[$key]['telat'] =   1;
                       }
                        
                   

                    } elseif ($value['isLate'][0] == 'tidak')  {
                   
                       if (isset($final_result[$key]['tepat'])) {
                          $final_result[$key]['tepat'] =  $final_result[$key]['tepat'] + 1;
                       } else {
                         $final_result[$key]['tepat'] =   1;
                       }
                   
                    }
                }


            } else {
                 $final_result[$key]['hadir'] = 0;
                 $final_result[$key]['telat'] = 0;
                 $final_result[$key]['tepat'] = 0;
            }
           
        }


          

    


        }


        // echo "<pre>";
        //      print_r($final_result);
        // echo "</pre>";


        $this->render('viewrekap', array(
            'absensi' => $final_result
        ));


    }

    public function actionRekapAbsenSiswa() {

        if (!empty($_GET['datetime'])) {
            $mydate = new DateTime($_GET['datetime']);
            $tomorow = new DateTime($_GET['datetime']);
        } else {
            $mydate = new DateTime();
            $tomorow = new DateTime();
        }

      

        // $mydate = new DateTime("2018/02/20 13:26");
        // $tomorow = new DateTime("2018/02/21 13:26");
       
        $final_result = array();

        for ($i=0; $i < 31; $i++) { 
           // $mydate->modify('+1 day');
           // $besok = $tomorow->modify('+1 day');
           // $tanggal = $mydate->format('Y-m-d');
           // $tanggal_besok = $besok->format('Y-m-d');
           // echo $tanggal." ".$tanggal_besok."</br>";

             if (!empty($_GET['datetime'])) {
                    $mydate = new DateTime($_GET['datetime']);
                    $tomorow = new DateTime($_GET['datetime']);
                } else {
                    $mydate = new DateTime();
                    $tomorow = new DateTime();
                }

            if ($i==0) {
                $mydate = $mydate->format('Y-m-d');
                $tomorow->modify('+1 day');
                $tomorow = $tomorow->format('Y-m-d'); 
            } else {
                // $mydate = $mydate->format('Y-m-d');
                // $tomorow->modify('+1 day');
                // $tomorow = $tomorow->format('Y-m-d'); 
                $penambah = $i;
                $penambahsatu = $i+1;
                $mydate->modify('+'.$penambah.' day');
                $mydate = $mydate->format('Y-m-d');
                $tomorow->modify('+'.$penambahsatu.' day');
                $tomorow = $tomorow->format('Y-m-d'); 


            }
        

        // $mydate = $mydate->format('Y-m-d');
        // $tomorow->modify('+1 day');
        // $tomorow = $tomorow->format('Y-m-d');

        $absensi = AbsensiSolution::model()->findAll(array('condition' => ' datetime > \'' . $mydate . '\' and datetime < \'' . $tomorow . '\' '));

        $result = array();

        // $query = AbsensiSolution::find()->where(['>', 'datetime', new Expression('NOW()')]);
        // $absensi = new ActiveDataProvider(['query' => $query]);

        foreach ($absensi as $key => $value) {
          if (isset($value->guru->role_id)) { 
          if ($value->guru->role_id == '2') {  
            if (isset($_GET['class_id'])) {
            if ( $value->guru->class_id == $_GET['class_id']) {  
            if (isset($value->guru->display_name)) {
                $result[$value->user_id]["display_name"] = $value->guru->display_name;
                $kelasnya = ClassDetail::model()->findByPk($value->guru->class_id);
                $result[$value->user_id]["kelasnya"] = $kelasnya;
                $result[$value->user_id]["idUsernya"] = $value->guru->id;

            } else {
                // $result[$value->user_id]["display_name"] = $value->guru->display_name;   
            }

            if (isset($value->guru->username)) {
                $result[$value->user_id]["username"] = $value->guru->username;
            } else {
                // $result[$value->user_id]["display_name"] = $value->guru->display_name;   
            }

            if (!empty($result[$value->user_id]["datetime"][0])) {
                $date_one = strtotime($value->datetime);
                $date_two = strtotime($result[$value->user_id]["datetime"][0]);
                $date_diff = round(abs($date_one - $date_two) / 60);
                if ($date_diff > 360) {
                    $result[$value->user_id]["datetime_gohome"][] = $value->datetime;
                    $value->datetime = strtotime($value->datetime);
                    if (date("H:i:s", $value->datetime) > date("H:i:s", strtotime("16:00:00"))) {
                        $result[$value->user_id]["isOvertime"][] = "ya";
                        $result[$value->user_id]["howlong"][] = $date_diff;
                    } else {
                        $result[$value->user_id]["isOvertime"][] = "tidak";
                        $result[$value->user_id]["howlong"][] = $date_diff;
                    }
                } else {

                    $result[$value->user_id]["datetime"][] = $value->datetime;
                    $value->datetime = strtotime($value->datetime);
                    if (date("H:i:s", $value->datetime) > date("H:i:s", strtotime("07:00:00"))) {
                        $result[$value->user_id]["isLate"][] = "ya";
                    } else {
                        $result[$value->user_id]["isLate"][] = "tidak";
                    }
                }
            } else {

                $result[$value->user_id]["datetime"][] = $value->datetime;
                $value->datetime = strtotime($value->datetime);
                if (date("H:i:s", $value->datetime) > date("H:i:s", strtotime("07:00:00"))) {
                    $result[$value->user_id]["isLate"][] = "ya";
                } else {
                    $result[$value->user_id]["isLate"][] = "tidak";
                }
            }

            }

            } else {


                 if (isset($value->guru->display_name)) {
                $result[$value->user_id]["display_name"] = $value->guru->display_name;
                $kelasnya = ClassDetail::model()->findByPk($value->guru->class_id);
                $result[$value->user_id]["kelasnya"] = $kelasnya;
                $result[$value->user_id]["idUsernya"] = $value->guru->id;

            } else {
                // $result[$value->user_id]["display_name"] = $value->guru->display_name;   
            }

            if (isset($value->guru->username)) {
                $result[$value->user_id]["username"] = $value->guru->username;
            } else {
                // $result[$value->user_id]["display_name"] = $value->guru->display_name;   
            }

            if (!empty($result[$value->user_id]["datetime"][0])) {
                $date_one = strtotime($value->datetime);
                $date_two = strtotime($result[$value->user_id]["datetime"][0]);
                $date_diff = round(abs($date_one - $date_two) / 60);
                if ($date_diff > 360) {
                    $result[$value->user_id]["datetime_gohome"][] = $value->datetime;
                    $value->datetime = strtotime($value->datetime);
                    if (date("H:i:s", $value->datetime) > date("H:i:s", strtotime("16:00:00"))) {
                        $result[$value->user_id]["isOvertime"][] = "ya";
                        $result[$value->user_id]["howlong"][] = $date_diff;
                    } else {
                        $result[$value->user_id]["isOvertime"][] = "tidak";
                        $result[$value->user_id]["howlong"][] = $date_diff;
                    }
                } else {

                    $result[$value->user_id]["datetime"][] = $value->datetime;
                    $value->datetime = strtotime($value->datetime);
                    if (date("H:i:s", $value->datetime) > date("H:i:s", strtotime("07:00:00"))) {
                        $result[$value->user_id]["isLate"][] = "ya";
                    } else {
                        $result[$value->user_id]["isLate"][] = "tidak";
                    }
                }
            } else {

                $result[$value->user_id]["datetime"][] = $value->datetime;
                $value->datetime = strtotime($value->datetime);
                if (date("H:i:s", $value->datetime) > date("H:i:s", strtotime("07:00:00"))) {
                    $result[$value->user_id]["isLate"][] = "ya";
                } else {
                    $result[$value->user_id]["isLate"][] = "tidak";
                }
            }



            }

           }
           } 
        }

        // echo $mydate."</br>";
        // echo $tomorow."</br>";

        // echo "<pre>";
        //      print_r($result);
        // echo "</pre>";

        

       

        foreach ($result as $key => $value) {

            if (isset($value['display_name'])) {
                $final_result[$key]["display_name"] = $value['display_name'];
            } else {
                $final_result[$key]["display_name"] = "";
            }

            if (isset($value['kelasnya'])) {
                $final_result[$key]["kelasnya"] = $value['kelasnya'];
            } else {
                $final_result[$key]["kelasnya"] = "";
            }


              if (isset($value['username'])) {
                $final_result[$key]["username"] = $value['username'];
            } else {
                $final_result[$key]["username"] = "";
            }


            if (!empty($value['datetime'])) {
                
                if (isset($final_result[$key]['hadir'])) {
                    $final_result[$key]['hadir'] = $final_result[$key]['hadir'] +  1;
                } else {
                    $final_result[$key]['hadir'] = 1;
                }
                


                if (!empty($value['isLate'])) {
                    if ($value['isLate'][0] == 'ya') {
                       
                       if (isset($final_result[$key]['telat'])) {
                          $final_result[$key]['telat'] =  $final_result[$key]['telat'] + 1;
                       } else {
                         $final_result[$key]['telat'] =   1;
                       }
                        
                   

                    } elseif ($value['isLate'][0] == 'tidak')  {
                   
                       if (isset($final_result[$key]['tepat'])) {
                          $final_result[$key]['tepat'] =  $final_result[$key]['tepat'] + 1;
                       } else {
                         $final_result[$key]['tepat'] =   1;
                       }
                   
                    }
                }


            } else {
                 $final_result[$key]['hadir'] = 0;
                 $final_result[$key]['telat'] = 0;
                 $final_result[$key]['tepat'] = 0;
            }
           
        }


          

    


        }


        // echo "<pre>";
        //      print_r($final_result);
        // echo "</pre>";


        $this->render('viewrekap-siswa', array(
            'absensi' => $final_result
        ));


    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new Lks;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Lks'])) {
            $model->attributes = $_POST['Lks'];

            if (Yii::app()->user->YiiTeacher) {
                $model->teacher_id = Yii::app()->user->id;
            } else {
                if (!empty($model->teacher_id)) {
                    list($a, $b) = explode(":", $model->teacher_id);
                    $user_id = str_replace(")", "", $b);
                    $model->teacher_id = $user_id;
                }
            }

            /* echo "<pre>";
              print_r($model);
              echo "</pre>"; */
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
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Lks'])) {
            $model->attributes = $_POST['Lks'];
            $model->sync_status = 2;
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
    public function actionDelete($id) {
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex($id_lesson = null) {
        $term = '';

        if (!empty($id_lesson)) {
            if (Yii::app()->user->YiiTeacher) {
                $term = ' lesson_id = ' . $id_lesson . ' and teacher_id = ' . Yii::app()->user->id;
            } else {
                $term = ' lesson_id = ' . $id_lesson;
            }
        }

        $dataProvider = new CActiveDataProvider('Lks', array(
            'criteria' => array(
                'condition' => $term,
            )
        ));

        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    public function actionBulkMateri() {
        if (isset($_POST['materi'])) {
            $lks = Lks::model()->findByPk($_POST['lks']);

            $materi = $_POST['materi'];
            $count_id = count($materi);

            //if(!empty($sola)){
            $data = implode(',', $materi);
            //}else{
            //	$data = null;
            //	$count_id = null;
            //}

            if (empty($_POST['materi'])) {
                Yii::app()->user->setFlash('danger', 'Harap Checklist Materi !');
            }

            if (!empty($lks->chapters)) {
                $old_materi = explode(',', $lks->chapters);
                $result = array_merge($old_materi, $materi);
                $lks->chapters = implode(',', $result);
                $total = count($result);
                //$lks->total_question=$total;
            } else {
                $lks->chapters = $data;
                //$lks->total_question=$count_id;
            }


            if ($lks->save()) {
                Yii::app()->user->setFlash('success', '' . $count_id . ' Materi Berhasil Ditambahkan !');
                $this->redirect(array('/lks/view', 'id' => $lks->id));
            }

            $this->redirect(array('/lks/view', 'id' => $lks->id));
            /* echo "<pre>";
              print_r($data);
              echo $count_id;
              print_r($quiz);
              echo "</pre>"; */
        }
    }

    public function actionBulkTugas() {
        if (isset($_POST['tugas'])) {
            $lks = Lks::model()->findByPk($_POST['lks']);

            $tugas = $_POST['tugas'];
            $count_id = count($tugas);

            //if(!empty($sola)){
            $data = implode(',', $tugas);
            //}else{
            //	$data = null;
            //	$count_id = null;
            //}

            if (empty($_POST['tugas'])) {
                Yii::app()->user->setFlash('danger', 'Harap Checklist Tugas !');
            }

            if (!empty($lks->assignments)) {
                $old_tugas = explode(',', $lks->assignments);
                $result = array_merge($old_tugas, $tugas);
                $lks->assignments = implode(',', $result);
                $total = count($result);
                //$lks->total_question=$total;
            } else {
                $lks->assignments = $data;
                //$lks->total_question=$count_id;
            }


            if ($lks->save()) {
                Yii::app()->user->setFlash('success', '' . $count_id . ' Tugas Berhasil Ditambahkan !');
                $this->redirect(array('/lks/view', 'id' => $lks->id));
            }

            $this->redirect(array('/lks/view', 'id' => $lks->id));
            /* echo "<pre>";
              print_r($data);
              echo $count_id;
              print_r($quiz);
              echo "</pre>"; */
        }
    }

    public function actionBulkUlangan() {
        if (isset($_POST['ulangan'])) {
            $lks = Lks::model()->findByPk($_POST['lks']);

            $ulangan = $_POST['ulangan'];
            $count_id = count($ulangan);

            //if(!empty($sola)){
            $data = implode(',', $ulangan);
            //}else{
            //	$data = null;
            //	$count_id = null;
            //}

            if (empty($_POST['Ulangan'])) {
                Yii::app()->user->setFlash('danger', 'Harap Checklist Ulangan !');
            }

            if (!empty($lks->quizes)) {
                $old_ulangan = explode(',', $lks->quizes);
                $result = array_merge($old_ulangan, $ulangan);
                $lks->quizes = implode(',', $result);
                $total = count($result);
                //$lks->total_question=$total;
            } else {
                $lks->quizes = $data;
                //$lks->total_question=$count_id;
            }


            if ($lks->save()) {
                Yii::app()->user->setFlash('success', '' . $count_id . ' Ulangan Berhasil Ditambahkan !');
                $this->redirect(array('/lks/view', 'id' => $lks->id));
            }

            $this->redirect(array('/lks/view', 'id' => $lks->id));
            /* echo "<pre>";
              print_r($data);
              echo $count_id;
              print_r($quiz);
              echo "</pre>"; */
        }
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new Lks('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Lks']))
            $model->attributes = $_GET['Lks'];

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
    public function loadModel($id) {
        $model = Lks::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Lks $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'lks-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
