<?php

/**
 * ApiController class file
 * @author Joachim Werner <joachim.werner@diggin-data.de>
 */

/**
 * ApiController
 *
 * @uses Controller
 * @author Joachim Werner <joachim.werner@diggin-data.de>
 * @author
 * @see http://www.gen-x-design.com/archives/making-restful-requests-in-php/
 * @license (tbd)
 */
require_once Yii::app()->basePath . '/extensions/SSDB.php';
class ApiController extends Controller {

    private $method = ' ';
    private $format = 'json';

    public function actionTestUpload() {
        //$post_data = json_decode(urldecode(file_get_contents("php://input")));
        print_r($_FILES);
    }

    public function actionTestssdb(){
    	// try{
     //        $ssdb = new SimpleSSDB('127.0.0.1', 8888);
    	// }catch(Exception $e){
     //   	    die(__LINE__ . ' ' . $e->getMessage());
    	// }
    	// $ret = $ssdb->set('key', 'value');
    	// echo $ssdb->get('key');
    }

    public function actionTestInput() {
        $post_data = json_decode(urldecode(file_get_contents("php://input")));
        //print_r($post_data);
    }

    public function actionGetQuiz() {
        $status = true;
        $prefix = Yii::app()->params['tablePrefix'];
        $type = array(
            "uts" => 4,
            "uas" => 5,
            "us" => 6
        );

        //delarasi kondisi sql
        $condition = 't.trash is null';
        if (isset($_GET['quiz_id'])) {
            $condition .= ' and t.id =' . $_GET['quiz_id'];
        }
        if (isset($_GET['grade'])) {
            $condition .= ' and c.class_id =' . $_GET['grade'];
        }
        if (isset($_GET['lesson'])) {
            $condition .= ' and l.list_id =' . $_GET['lesson'];
        }
        if (isset($_GET['type'])) {
            $condition .= ' and t.quiz_type =' . $type[$_GET['type']];
        }
        if (isset($_GET['year'])) {
            $condition .= ' and t.year =' . $_GET['year'];
        }
        if (isset($_GET['semester'])) {
            $condition .= ' and t.semester =' . $_GET['semester'];
        }

        $criteria = new CDbCriteria();
        $criteria->mergeWith(array(
            'order' => 'created_at DESC',
            'select' => 't.*',
            'join' => 'JOIN ' . $prefix . 'lesson as l ON l.id = t.lesson_id JOIN ' . $prefix . 'class_detail as c ON c.id = l.class_id',
            'condition' => $condition,
        ));
        $count = Quiz::model()->count($criteria);

        if ($count > 0) {
            $pages = new CPagination($count);
            // results per page
            $pages->CurrentPage = isset($_GET['row']) ? $_GET['row'] : 0;
            $pages->pageSize = isset($_GET['limit']) ? $_GET['limit'] : 500;
            $perpage = $pages->pageSize;
            $pages->applyLimit($criteria);
            $totalpage = ceil($count / $perpage);
            $models = Quiz::model()->findAll($criteria);

            $data['success'] = 1;
            $data['message'] = 'Record(s) Found.';
            $data['totalCount'] = $count;

            foreach ($models as $row) {
                $data_fix['id'] = $row->id;
                $data_fix['title'] = $row->title;
                $data_fix['lesson'] = $row->lesson->name;
                $data_fix['lesson_list'] = $row->lesson->list_id;
                $data_fix['chapter_id'] = $row->chapter_id;
                $data_fix['quiz_type'] = $row->quiz_type;
                $data_fix['created_by'] = $row->created_by;
                $data_fix['start_time'] = $row->start_time;
                $data_fix['finish_time'] = $row->finish_time;
                $data_fix['end_time'] = $row->end_time;
                $data_fix['total_question'] = $row->total_question;
                $data_fix['status'] = $row->status;
                $data_fix['add_to_summary'] = $row->add_to_summary;
                $data_fix['repeat_quiz'] = $row->repeat_quiz;
                $data_fix['semester'] = $row->semester;
                $data_fix['year'] = $row->year;
                $data_fix['random'] = $row->random;
                $data_fix['show_nilai'] = $row->show_nilai;
                $data_fix['id_bersama'] = $row->id_bersama;
                $data_fix['random_opt'] = $row->random_opt;
                $data_fix['passcode'] = $row->passcode;
                if (!empty($row->lesson_id)) {
                    $data_fix['class'] = $row->lesson->class->name;
                    $data_fix['grade'] = $row->lesson->class->class_id;
                }
                if ($row->question) {
                    $question = Questions::model()->findAll(array('condition' => 'id IN(' . $row->question . ')'));
                    $data_fix['questions'] = CJSON::encode($question);
                }
                $data['data']['result'][] = $data_fix;
            }
        } else {
            $models = NULL;
        }

        if ($status == false) {
            $data['success'] = 0;
            $data['message'] = 'ERROR : Missing or Invalid Parameter !';
            $data['data'] = NULL;
            $json = json_encode($data, JSON_PRETTY_PRINT);
            header('Content-Type: application/x-javascript');
            echo $json;
        } elseif ($models == NULL) {
            $data['success'] = 0;
            $data['message'] = 'ERROR : No Data Found';
            $data['data'] = NULL;
            $json = json_encode($data, JSON_PRETTY_PRINT);
            header('Content-Type: application/x-javascript');
            echo $json;
        } else {
            $json = json_encode($data, JSON_PRETTY_PRINT);
            header('Content-Type: application/json');
            echo $json;
        }
    }

    public function actionPullQuiz() {
        $server = Option::model()->find(array('condition' => 'key_config LIKE "%server%"'));
        $url = $server->value . "/api/getquiz?year=" . $_GET['year'] . "&semester=" . $_GET['semester'] . "&type=" . $_GET['type'] . "&grade=" . $_GET['grade'] . "&lesson=" . $_GET['lesson'];
        $datas = json_decode($this->file_get_contents_curl($url), true);

        if ($datas['data']['result']) {
            $quizes = $datas['data']['result'];
            foreach ($quizes as $quiz) {
                //insert question
                $idsoal = array();
                if ($quiz["questions"]) {
                    $questions = json_decode($quiz["questions"], true);
                    foreach ($questions as $question) {
                        $model = new Questions;
                        $model->text = $question['text'];
                        $model->quiz_id = $question['quiz_id'];
                        $model->title = $question['title'];
                        $model->choices = $question['choices'];
                        $model->type = $question['type'];
                        $model->key_answer = $question['key_answer'];
                        $model->created_by = $question['created_by'];
                        $model->teacher_id = $question['teacher_id'];
                        $model->kd = $question['kd'];

                        if ($model->save()) {
                            $idsoal[] = $model->id;
                        }
                    }
                }
                if (isset($idsoal) AND $quiz["grade"]) {
                    $classes = ClassDetail::model()->findAll(array('condition' => 'class_id =' . $quiz["grade"] . ''));
                    foreach ($classes as $class) {
                        $lesson = Lesson::model()->find(array(
                            'condition' => 'class_id =' . $class->id . ' and list_id = ' . $quiz["lesson_list"] . ' and semester = ' . $_GET['semester'] . ' and year = ' . $_GET['year'] . ' and trash is NULL'
                        ));
                        if ($lesson) {
                            $cekquiz = Quiz::model()->findByAttributes(array('lesson_id' => $lesson->id, 'quiz_type' => $quiz["quiz_type"], 'semester' => $quiz["semester"], 'year' => $quiz["year"], 'trash' => NULL));
                            if (empty($cekquiz)) {
                                $model = new Quiz;
                                $model->status = $quiz["status"];
                                $model->lesson_id = $lesson->id;
                                $model->title = $quiz["title"];
                                $model->repeat_quiz = $quiz["repeat_quiz"];
                                $model->quiz_type = $quiz["quiz_type"];
                                $model->year = $quiz["year"];
                                $model->semester = $quiz["semester"];
                                $model->created_by = $quiz["created_by"];
                                $model->random = $quiz["random"];
                                $model->show_nilai = 0;
                                $model->random_opt = $quiz["random_opt"];
                                $model->total_question = $quiz["total_question"];
                                $model->end_time = $quiz["end_time"];
                                $model->finish_time = $quiz["finish_time"];
                                $model->start_time = $quiz["start_time"];
                                $model->id_bersama = $quiz["id"];
                                $model->question = implode(",", $idsoal);

                                // $length = 5;
                                // $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                                // $charactersLength = strlen($characters);

                                // $randomString = '';
                                // for ($i = 0; $i < $length; $i++) {
                                //     $randomString .= $characters[rand(0, $charactersLength - 1)];
                                // }
                                //$model->passcode = $randomString;
                                $model->passcode = $quiz["passcode"];

                                if ($model->save()) {
                                    $idquiz[$model->id]["title"] = $model->title;
                                    $idquiz[$model->id]["class"] = $class->name;
                                    $idquiz[$model->id]["questions"] = $idsoal;
                                }
                            }
                        }
                    }
                }
            }
        }

        if (isset($idquiz)) {
            $data['success'] = 1;
            $data['message'] = 'Success : Datas Has Been Insert';
            $data['result']['quiz'] = $idquiz;
            $json = json_encode($data, JSON_PRETTY_PRINT);
            header('Content-Type: application/x-javascript');
            echo $json;
        } else {
            $data['success'] = 0;
            $data['message'] = 'Error : No Data Found';
            $json = json_encode($data, JSON_PRETTY_PRINT);
            header('Content-Type: application/x-javascript');
            echo $json;
        }
    }

    public function actionSetScore_old() {
        $post_data = json_decode(urldecode(file_get_contents("php://input")));
        $id = null;
        $model = new StudentQuiz;
        foreach ($post_data as $key => $value) {
            $model->$key = $value;
        }
        $cekNilai = StudentQuiz::model()->findByAttributes(array(
            'quiz_id' => $model->quiz_id,
            'display_name' => $model->display_name,
            'class' => $model->class,
            'school_name' => $model->school_name,
            'semester' => $model->semester,
            'year' => $model->year,
            'trash' => NULL));
        if (empty($cekNilai)) {
            if ($model->save()) {
                $id = $model->id;
            }
        }
        $json = json_encode($id, JSON_PRETTY_PRINT);
        header('Content-Type: application/x-javascript');
        echo $json;
    }

    public function actionSetScore() {
        $post_data = urldecode(file_get_contents("php://input"));
        $ssdb = new SimpleSSDB('127.0.0.1', 8888);
        $queue_name = @Option::model()->findByAttributes(array('key_config' => 'queue_name'))->value;
        $push = $ssdb->qpush($queue_name, $post_data);
        if(!$push) {
            $json = "Something error!!";
        } else {
            $json = "Your data will be update in a minutes";
        }
        header('Content-Type: application/x-javascript');
        echo $json;
    }

    public function actionSetDailyAbsent() {
        $this->checkToken();
        $post_data = json_decode(urldecode(file_get_contents("php://input")));
        $id = null;
        foreach ($post_data as $absen) {
            $model = new AbsensiHarian;
            foreach ($absen as $key => $value) {
                $model->$key = $value;
            }
            if (!empty($model)) {
                if ($model->save()) {
                    $id[] = $model->id;
                }
            }
        }
        $json = json_encode("Berhasil menambah absen harian " . implode(", ", $id), JSON_PRETTY_PRINT);
        header('Content-Type: application/x-javascript');
        header("Access-Control-Allow-Origin: *");
        echo $json;
    }

    public function actionGetDailyAbsent() {
        $status = true;
        //cek token
        $this->checkToken();


        $Semester = isset($_GET['semester']) ? $_GET['semester'] : Option::model()->findByAttributes(array('key_config' => 'semester'))->value;
        $TahunAjaran = isset($_GET['year']) ? $_GET['year'] : Option::model()->findByAttributes(array('key_config' => 'tahun_ajaran'))->value;

        $condition = 'id IS NOT NULL';
        if (isset($_GET['user_id'])) {
            $condition .= ' and t.user_id =' . $_GET['user_id'];
        }
        if (isset($_GET['tgl'])) {
            $condition .= ' and t.tgl = "' . $_GET['tgl'] . '"';
        }

        $criteria = new CDbCriteria();
        $criteria->mergeWith(array(
            'order' => 'tgl DESC',
            'select' => 't.*',
            'condition' => $condition,
        ));
        $count = AbsensiHarian::model()->count($criteria);

        if ($count > 0) {
            $pages = new CPagination($count);
            // results per page
            $pages->CurrentPage = isset($_GET['start']) ? $_GET['start'] : 0;
            $pages->pageSize = isset($_GET['rows']) ? $_GET['rows'] : 10;
            $perpage = $pages->pageSize;
            $pages->applyLimit($criteria);
            $totalpage = ceil($count / $perpage);
            $models = AbsensiHarian::model()->findAll($criteria);

            $data['success'] = 1;
            $data['message'] = 'Record(s) Found.';
            $data['totalCount'] = $count;
            foreach ($models as $row) {
                $data_fix['id'] = $row->id;
                $data_fix['tanggal'] = $row->tgl;
                $data_fix['absen'] = $row->absen;
                $data_fix['ket'] = $row->ket;
                
                $score_siswa = $this->actionScore($_GET['user_id'],$row->tgl,$Semester,$TahunAjaran);

                if ($score_siswa["success"]!=0) {
                    $data_fix['score'] = $score_siswa['data']['result']['quizes'][0]['score'];
                    if (!empty($score_siswa['data']['result']['quizes'])) {
                        $nilai = 0;
                        $index_nilai = 0;
                        $data_fix['score_all'] = $score_siswa['data']['result']['quizes'];
                        foreach ($score_siswa['data']['result']['quizes'] as $key => $value) {
                            $nilai = $nilai + $value['score'];
                            $data_fix['score'] = $nilai;
                            $index_nilai = $index_nilai + 1;
                            $data_fix['index_nilai'] = $index_nilai;

                            $data_fix['rata_nilai'] = $nilai / $index_nilai;

                            $data_fix['predikat'] = Clases::model()->predikat(round($nilai),70); 
                        }
                        // $data_fix['score'] = $score_siswa['data']['result']['quizes'][0]['score'];
                    } else {
                        $data_fix['score'] = "-";
                     $data_fix['predikat'] = "-";
                     $data_fix['rata_nilai'] = "-";
                     $data_fix['index_nilai'] = "-";
                    }
                } else {
                    $data_fix['score'] = "-";
                     $data_fix['predikat'] = "-";
                     $data_fix['rata_nilai'] = "-";
                     $data_fix['index_nilai'] = "-";
                }
                // $data_fix['score'] = $this->actionScore($_GET['user_id'],$row->tgl,$Semester,$TahunAjaran);
                
                if (!empty($row->user->id)) {
                    $data_fix['user']['id'] = $row->user_id;
                    $data_fix['user']['name'] = $row->user->display_name;
                }
                $data['data']['result'][] = $data_fix;
            }
        } else {
            $models = NULL;
        }

        if ($status == false) {
            $data['success'] = 0;
            $data['message'] = 'ERROR : Missing or Invalid Parameter !';
            $data['data'] = NULL;
            $json = json_encode($data, JSON_PRETTY_PRINT);
            header('Content-Type: application/x-javascript');
            header("Access-Control-Allow-Origin: *");
            echo $json;
        } elseif ($models == NULL) {
            $data['success'] = 0;
            $data['message'] = 'ERROR : No Data Found';
            $data['data'] = NULL;
            $json = json_encode($data, JSON_PRETTY_PRINT);
            header('Content-Type: application/x-javascript');
            header("Access-Control-Allow-Origin: *");
            echo $json;
        } else {
            $json = json_encode($data, JSON_PRETTY_PRINT);
            header('Content-Type: application/json');
            header("Access-Control-Allow-Origin: *");
            echo $json;
        }
    }


      public function actionGetDailyFingerPrint() {
        $status = true;
        //cek token
        $this->checkToken();

        // pakai id absen jangan id user

        $usernya = User::model()->findByPk($_GET['user_id']);

        $Semester = isset($_GET['semester']) ? $_GET['semester'] : Option::model()->findByAttributes(array('key_config' => 'semester'))->value;
        $TahunAjaran = isset($_GET['year']) ? $_GET['year'] : Option::model()->findByAttributes(array('key_config' => 'tahun_ajaran'))->value;

        $condition = 'id IS NOT NULL';
        if (isset($_GET['user_id'])) {
            $condition .= ' and t.user_id =' . $usernya->id_absen_solution;
        }
        if (isset($_GET['tgl'])) {
            $condition .= ' and t.datetime = "' . $_GET['tgl'] . '"';
        }

        $criteria = new CDbCriteria();
        $criteria->mergeWith(array(
            'order' => 'datetime ASC',
            'select' => 't.*',
            'condition' => $condition,
        ));
        $count = AbsensiSolution::model()->count($criteria);

        if ($count > 0) {
            $pages = new CPagination($count);
            // results per page
            $pages->CurrentPage = isset($_GET['start']) ? $_GET['start'] : 0;
            $pages->pageSize = isset($_GET['rows']) ? $_GET['rows'] : 10000;
            $perpage = $pages->pageSize;
            $pages->applyLimit($criteria);
            $totalpage = ceil($count / $perpage);
            $models = AbsensiSolution::model()->findAll($criteria);

            $data['success'] = 1;
            $data['message'] = 'Record(s) Found.';
            $data['totalCount'] = $count;
            $data['data']['totalPage'] = $totalpage;
            $data['data']['perPage'] = $perpage;


            $date_array = array();

            foreach ($models as $row) {

                $s = $row->datetime;
                $dt = new DateTime($s);

                $thedate = $dt->format('Y-m-d');
                $thetime = $dt->format('H:i:s');
                $counts = array_count_values($date_array);

                if (!in_array($thedate, $date_array)) {

                array_push($date_array, $thedate);    

                $data_fix['id'] = $row->id;
                $data_fix['tanggal'] = $thedate;
                $data_fix['datang'] = $thetime;
                $data_fix['pulang'] = "-";
                $data_fix['absen'] = "Hadir";
                $data_fix['ket'] = "ket";
                
                $score_siswa = $this->actionScore($_GET['user_id'],$row->datetime,$Semester,$TahunAjaran);

                if ($score_siswa["success"]!=0) {
                    $data_fix['score'] = $score_siswa['data']['result']['quizes'][0]['score'];
                    if (!empty($score_siswa['data']['result']['quizes'])) {
                        $nilai = 0;
                        $index_nilai = 0;
                        $data_fix['score_all'] = $score_siswa['data']['result']['quizes'];
                        foreach ($score_siswa['data']['result']['quizes'] as $key => $value) {
                            $nilai = $nilai + $value['score'];
                            $data_fix['score'] = $nilai;
                            $index_nilai = $index_nilai + 1;
                            $data_fix['index_nilai'] = $index_nilai;

                            $data_fix['rata_nilai'] = $nilai / $index_nilai;

                            $data_fix['predikat'] = Clases::model()->predikat(round($nilai),70); 
                        }
                        // $data_fix['score'] = $score_siswa['data']['result']['quizes'][0]['score'];
                    } else {
                     $data_fix['score'] = "-";
                     $data_fix['predikat'] = "-";
                     $data_fix['rata_nilai'] = "-";
                     $data_fix['index_nilai'] = "-";
                    }
                } else {
                     $data_fix['score'] = "-";
                     $data_fix['predikat'] = "-";
                     $data_fix['rata_nilai'] = "-";
                     $data_fix['index_nilai'] = "-";
                }
                // $data_fix['score'] = $this->actionScore($_GET['user_id'],$row->tgl,$Semester,$TahunAjaran);
                
                if (!empty($row->user_id)) {
                    $data_fix['user']['id'] = $row->user_id;
                    $data_fix['user']['name'] = $row->guru->display_name;
                }
                $data['data']['result'][] = $data_fix;


                } else {
                    
                   if (($counts[$thedate] == 1) && (strtotime($thetime) > strtotime('11:00'))  ) {
                         array_push($date_array, $thedate);    

                        $data_fix['id'] = $row->id;
                        $data_fix['tanggal'] = $thedate;
                        $data_fix['datang'] = "-";
                        $data_fix['pulang'] = $thetime;
                        $data_fix['absen'] = "Hadir";
                        $data_fix['ket'] = "ket";
                        
                        $score_siswa = $this->actionScore($_GET['user_id'],$row->datetime,$Semester,$TahunAjaran);

                        if ($score_siswa["success"]!=0) {
                            $data_fix['score'] = $score_siswa['data']['result']['quizes'][0]['score'];
                            if (!empty($score_siswa['data']['result']['quizes'])) {
                                $nilai = 0;
                                $index_nilai = 0;
                                $data_fix['score_all'] = $score_siswa['data']['result']['quizes'];
                                foreach ($score_siswa['data']['result']['quizes'] as $key => $value) {
                                    $nilai = $nilai + $value['score'];
                                    $data_fix['score'] = $nilai;
                                    $index_nilai = $index_nilai + 1;
                                    $data_fix['index_nilai'] = $index_nilai;

                                    $data_fix['rata_nilai'] = $nilai / $index_nilai;

                                    $data_fix['predikat'] = Clases::model()->predikat(round($nilai),70); 
                                }
                                // $data_fix['score'] = $score_siswa['data']['result']['quizes'][0]['score'];
                            } else {
                                $data_fix['score'] = "-";
                             $data_fix['predikat'] = "-";
                             $data_fix['rata_nilai'] = "-";
                             $data_fix['index_nilai'] = "-";
                            }
                        } else {
                            $data_fix['score'] = "-";
                             $data_fix['predikat'] = "-";
                             $data_fix['rata_nilai'] = "-";
                             $data_fix['index_nilai'] = "-";
                        }
                        // $data_fix['score'] = $this->actionScore($_GET['user_id'],$row->tgl,$Semester,$TahunAjaran);
                        
                        if (!empty($row->user_id)) {
                            $data_fix['user']['id'] = $row->user_id;
                            $data_fix['user']['name'] = $row->guru->display_name;
                        }
                        $data['data']['result'][] = $data_fix;
                    } 
                }
            } 
        } else {
            $models = NULL;
        }

        if ($status == false) {
            $data['success'] = 0;
            $data['message'] = 'ERROR : Missing or Invalid Parameter !';
            $data['data'] = NULL;
            $json = json_encode($data, JSON_PRETTY_PRINT);
            header('Content-Type: application/x-javascript');
            header("Access-Control-Allow-Origin: *");
            echo $json;
        } elseif ($models == NULL) {
            $data['success'] = 0;
            $data['message'] = 'ERROR : No Data Found';
            $data['data'] = NULL;
            $json = json_encode($data, JSON_PRETTY_PRINT);
            header('Content-Type: application/x-javascript');
            header("Access-Control-Allow-Origin: *");
            echo $json;
        } else {
            $json = json_encode($data, JSON_PRETTY_PRINT);
            header('Content-Type: application/json');
            header("Access-Control-Allow-Origin: *");
            echo $json;
        }
    }

    public function actionTarqiReport(){
        $this->checkToken();

        $sql = "SELECT u.id as user_id, u.display_name, l.name, a.pertemuan_ke FROM `absensi` as a join `lesson` as l on l.id = a.id_lesson join `users` as u on u.id = l.user_id";
        $command = Yii::app()->db->createCommand($sql);

        try {
            $result = $command->queryAll();
            foreach ($result as $value) {
                $hasil[$value['display_name']."-".$value['user_id']][$value['name']][$value['pertemuan_ke']] = true; 
            }
            $data['data']['result'][] = $hasil;
            $json = json_encode($data, JSON_PRETTY_PRINT);
            echo $json;

        } catch (Exception $ex) {
            // Handle exception
        }
    }

    public function actionTarqiReportRekap(){
        $this->checkToken();

        $sql = "select users.id,users.display_name, rekap.jumlahnya from users LEFT OUTER JOIN (select idnya, namanya, sum(jumlahnya) as jumlahnya from (SELECT u.id as idnya, u.display_name as namanya, l.name, a.pertemuan_ke,count(distinct(a.pertemuan_ke)) as jumlahnya FROM `absensi` as a JOIN `lesson` as l on l.id = a.id_lesson join `users` as u on u.id = l.user_id group by l.name) as subq group by idnya) as rekap on rekap.idnya = users.id where users.role_id = 1 and users.trash is null";
        $command = Yii::app()->db->createCommand($sql);

        try {
            $result = $command->queryAll();
            $data['data']['result'] = $result;
            $json = json_encode($data, JSON_PRETTY_PRINT);
            echo $json;

        } catch (Exception $ex) {
            // Handle exception
        }
    }


    public function actionTarqiReportByid(){
        $this->checkToken();

        if (isset($_GET['user_id'])) {

            $sql = "SELECT u.id as user_id,u.display_name, l.name, a.pertemuan_ke FROM `absensi` as a join `lesson` as l on l.id = a.id_lesson join `users` as u on u.id = l.user_id where u.id = ".$_GET['user_id'];
            $command = Yii::app()->db->createCommand($sql);

            try {
                $result = $command->queryAll();
                foreach ($result as $value) {
                    $hasil[$value['display_name']."-".$value['user_id']][$value['name']][$value['pertemuan_ke']] = true; 
                }
                $data['data']['result'][] = $hasil;
                $json = json_encode($data, JSON_PRETTY_PRINT);
                echo $json;

            } catch (Exception $ex) {
                // Handle exception
            }
        }
    }

    public function actionGetAbsent() {
        $status = true;
        //cek token
        $this->checkToken();

        $condition = 'id IS NOT NULL';
        if (isset($_GET['user_id'])) {
            $condition .= ' and t.user_id =' . $_GET['user_id'];
        }

        if (isset($_GET['id_lesson'])) {
            $condition .= ' and t.id_lesson =' . $_GET['lesson_id'];
        }

        $criteria = new CDbCriteria();
        $criteria->mergeWith(array(
            'order' => 'created_at DESC',
            'select' => 't.*',
            'condition' => $condition,
        ));
        $count = Absensi::model()->count($criteria);

        if ($count > 0) {
            $pages = new CPagination($count);
            // results per page
            $pages->CurrentPage = isset($_GET['start']) ? $_GET['start'] : 0;
            $pages->pageSize = isset($_GET['rows']) ? $_GET['rows'] : 10;
            $perpage = $pages->pageSize;
            $pages->applyLimit($criteria);
            $totalpage = ceil($count / $perpage);
            $models = Absensi::model()->findAll($criteria);

            $data['success'] = 1;
            $data['message'] = 'Record(s) Found.';
            $data['totalCount'] = $count;
            foreach ($models as $row) {
                $data_fix['id'] = $row->id;
                $data_fix['absen'] = $row->absen;
                $data_fix['created_at'] = $row->created_at;
                $data_fix['pertemuan_ke'] = $row->pertemuan_ke;
                $data_fix['keterangan'] = $row->ket;
                if (!empty($row->user->id)) {
                    $data_fix['user']['id'] = $row->user_id;
                    $data_fix['user']['name'] = $row->user->display_name;
                }
                if (!empty($row->lesson->id)) {
                    $data_fix['lesson']['id'] = $row->id_lesson;
                    $data_fix['lesson']['name'] = $row->lesson->name;
                    $data_fix['user']['class'] = $row->lesson->class->name;
                    $data_fix['user']['grade'] = $row->lesson->class->class_id;
                }
                $data['data']['result'][] = $data_fix;
            }
        } else {
            $models = NULL;
        }

        if ($status == false) {
            $data['success'] = 0;
            $data['message'] = 'ERROR : Missing or Invalid Parameter !';
            $data['data'] = NULL;
            $json = json_encode($data, JSON_PRETTY_PRINT);
            header('Content-Type: application/x-javascript');
            echo $json;
        } elseif ($models == NULL) {
            $data['success'] = 0;
            $data['message'] = 'ERROR : No Data Found';
            $data['data'] = NULL;
            $json = json_encode($data, JSON_PRETTY_PRINT);
            header('Content-Type: application/x-javascript');
            echo $json;
        } else {
            $json = json_encode($data, JSON_PRETTY_PRINT);
            header('Content-Type: application/json');
            echo $json;
        }
    }

    public function actionGetAnnouncements() {
        $status = true;
        //cek token
        $this->checkToken();
        $condition = "";
        if (isset($_GET['id'])) {
            $condition .= ' and t.id =' . $_GET['id'];
        }
        $criteria = new CDbCriteria();
        $criteria->mergeWith(array(
            'order' => 'created_at DESC',
            'select' => 't.*',
            'condition' => $condition,
        ));
        $count = Announcements::model()->count($criteria);

        if ($count > 0) {
            $pages = new CPagination($count);
            // results per page
            $pages->CurrentPage = isset($_GET['start']) ? $_GET['start'] : 0;
            $pages->pageSize = isset($_GET['rows']) ? $_GET['rows'] : 10;
            $perpage = $pages->pageSize;
            $pages->applyLimit($criteria);
            $totalpage = ceil($count / $perpage);
            $models = Announcements::model()->findAll($criteria);

            $data['success'] = 1;
            $data['message'] = 'Record(s) Found.';
            $data['totalCount'] = $count;
            foreach ($models as $row) {
                $data_fix['id'] = $row->id;
                $data_fix['title'] = $row->title;
                $data_fix['created_at'] = $row->created_at;
                $data_fix['content'] = $row->content;
                $data_fix['type'] = $row->type;
                $data_fix['author_id'] = $row->author_id;

                $data['data']['result'][] = $data_fix;
            }
        } else {
            $models = NULL;
        }

        if ($status == false) {
            $data['success'] = 0;
            $data['message'] = 'ERROR : Missing or Invalid Parameter !';
            $data['data'] = NULL;
            $json = json_encode($data, JSON_PRETTY_PRINT);
            header('Content-Type: application/x-javascript');
            header("Access-Control-Allow-Origin: *");
            echo $json;
        } elseif ($models == NULL) {
            $data['success'] = 0;
            $data['message'] = 'ERROR : No Data Found';
            $data['data'] = NULL;
            $json = json_encode($data, JSON_PRETTY_PRINT);
            header('Content-Type: application/x-javascript');
            header("Access-Control-Allow-Origin: *");
            echo $json;
        } else {
            $json = json_encode($data, JSON_PRETTY_PRINT);
            header('Content-Type: application/json');
            header("Access-Control-Allow-Origin: *");
            echo $json;
        }
    }

    public function actionGetStudentFinalMark() {
        $status = true;
        //cek token
        $this->checkToken();
        $Semester = isset($_GET['semester']) ? $_GET['semester'] : Option::model()->findByAttributes(array('key_config' => 'semester'))->value;
        $TahunAjaran = isset($_GET['year']) ? $_GET['year'] : Option::model()->findByAttributes(array('key_config' => 'tahun_ajaran'))->value;

        $condition = 'tahun_ajaran = ' . $TahunAjaran;
        $condition .= ' and semester = ' . $Semester;
        if (isset($_GET['user_id'])) {
            $condition .= ' and user_id = ' . $_GET['user_id'];
        }
        $models = FinalMark::model()->findAll(array('condition' => $condition));
        if (!empty($models)) {
            $data['success'] = 1;
            $data['message'] = 'Record(s) Found.';
            foreach ($models as $row) {
                $data_fix[$row->tipe] = $row->nilai_desc;
            }
            $data['data']['result'] = $data_fix;
        }

        if ($status == false) {
            $data['success'] = 0;
            $data['message'] = 'ERROR : Missing or Invalid Parameter !';
            $data['data'] = NULL;
            $json = json_encode($data, JSON_PRETTY_PRINT);
            header('Content-Type: application/x-javascript');
            header("Access-Control-Allow-Origin: *");
            echo $json;
        } elseif ($models == NULL) {
            $data['success'] = 0;
            $data['message'] = 'ERROR : No Data Found';
            $data['data'] = NULL;
            $json = json_encode($data, JSON_PRETTY_PRINT);
            header('Content-Type: application/x-javascript');
            header("Access-Control-Allow-Origin: *");
            echo $json;
        } else {
            $json = json_encode($data, JSON_PRETTY_PRINT);
            header('Content-Type: application/json');
            header("Access-Control-Allow-Origin: *");
            echo $json;
        }
    }

    public function actionGetStudentQuizes() {
        $status = true;
        //cek token
        $this->checkToken();
        $Semester = isset($_GET['semester']) ? $_GET['semester'] : Option::model()->findByAttributes(array('key_config' => 'semester'))->value;
        $TahunAjaran = isset($_GET['year']) ? $_GET['year'] : Option::model()->findByAttributes(array('key_config' => 'tahun_ajaran'))->value;

        $condition = 'q.year = ' . $TahunAjaran;
        $condition .= ' and q.semester = ' . $Semester;
        if (isset($_GET['user_id'])) {
            $condition .= ' and t.student_id = ' . $_GET['user_id'];
        }
        if (isset($_GET['created_at'])) {
            $condition .= ' and DATE_FORMAT(t.created_at, "%Y-%m-%d") ="' . $_GET['created_at'] . '"';
        }


        $criteria = new CDbCriteria();
        $criteria->mergeWith(array(
            'join' => 'JOIN quiz AS q ON q.id = t.quiz_id ',
            'condition' => $condition,
        ));

        $models = StudentQuiz::model()->findAll($criteria);

        if (!empty($models)) {
            $data['success'] = 1;
            $data['message'] = 'Record(s) Found.';
            $i = 0;
            foreach ($models as $row) {
                $data_fix['nisn'] = $row->nisn;
                $data_fix['display_name'] = $row->display_name;
                $data_fix['class'] = $row->class;
                $data_fix['school_name'] = $row->school_name;
                if ($row->quiz->id) {
                    $data_fix['quizes'][$i]['id'] = $row->quiz->id;
                    $data_fix['quizes'][$i]['created_at'] = $row->created_at;
                    $data_fix['quizes'][$i]['title'] = $row->quiz->title;
                    $data_fix['quizes'][$i]['right_answer'] = $row->right_answer;
                    $data_fix['quizes'][$i]['wrong_answer'] = $row->wrong_answer;
                    $data_fix['quizes'][$i]['unanswered'] = $row->unanswered;
                    $data_fix['quizes'][$i]['essay_score'] = $row->essay_score;
                    $data_fix['quizes'][$i]['pg_score'] = $row->pg_score;
                    $data_fix['quizes'][$i]['score'] = $row->score;
                    $data_fix['quizes'][$i]['kd'] = json_decode($row->kd);
                }
                $i++;
            }
            $data['data']['result'] = $data_fix;
        }

        if ($status == false) {
            $data['success'] = 0;
            $data['message'] = 'ERROR : Missing or Invalid Parameter !';
            $data['data'] = NULL;
            $json = json_encode($data, JSON_PRETTY_PRINT);
            header('Content-Type: application/x-javascript');
            header("Access-Control-Allow-Origin: *");
            echo $json;
        } elseif ($models == NULL) {
            $data['success'] = 0;
            $data['message'] = 'ERROR : No Data Found';
            $data['data'] = NULL;
            $json = json_encode($data, JSON_PRETTY_PRINT);
            header('Content-Type: application/x-javascript');
            header("Access-Control-Allow-Origin: *");
            echo $json;
        } else {
            $json = json_encode($data, JSON_PRETTY_PRINT);
            header('Content-Type: application/json');
            header("Access-Control-Allow-Origin: *");
            echo $json;
        }
    }


     public function actionScore($user_id,$created_at,$Semester,$TahunAjaran) {
        $status = true;

        $condition = 'q.year = ' . $TahunAjaran;
        $condition .= ' and q.semester = ' . $Semester;
        if (isset($user_id)) {
            $condition .= ' and t.student_id = ' . $user_id;
        }
        if (isset($created_at)) {
            $condition .= ' and DATE_FORMAT(t.created_at, "%Y-%m-%d") ="' . $created_at . '"';
        }


        $criteria = new CDbCriteria();
        $criteria->mergeWith(array(
            'join' => 'JOIN quiz AS q ON q.id = t.quiz_id ',
            'condition' => $condition,
        ));

        $models = StudentQuiz::model()->findAll($criteria);

        if (!empty($models)) {
            $data['success'] = 1;
            $data['message'] = 'Record(s) Found.';
            $i = 0;
            foreach ($models as $row) {
                $data_fix['nisn'] = $row->nisn;
                $data_fix['display_name'] = $row->display_name;
                $data_fix['class'] = $row->class;
                $data_fix['school_name'] = $row->school_name;
                if ($row->quiz->id) {
                    $data_fix['quizes'][$i]['id'] = $row->quiz->id;
                    $data_fix['quizes'][$i]['created_at'] = $row->created_at;
                    $data_fix['quizes'][$i]['title'] = $row->quiz->title;
                    $data_fix['quizes'][$i]['right_answer'] = $row->right_answer;
                    $data_fix['quizes'][$i]['wrong_answer'] = $row->wrong_answer;
                    $data_fix['quizes'][$i]['unanswered'] = $row->unanswered;
                    $data_fix['quizes'][$i]['essay_score'] = $row->essay_score;
                    $data_fix['quizes'][$i]['pg_score'] = $row->pg_score;
                    $data_fix['quizes'][$i]['score'] = $row->score;
                    $data_fix['quizes'][$i]['kd'] = json_decode($row->kd);
                }
                $i++;
            }
            $data['data']['result'] = $data_fix;
        }

        if ($status == false) {
            $data['success'] = 0;
            $data['message'] = 'ERROR : Missing or Invalid Parameter !';
            $data['data'] = NULL;
            // $json = json_encode($data, JSON_PRETTY_PRINT);
            // header('Content-Type: application/x-javascript');
            // header("Access-Control-Allow-Origin: *");
            return $data;
        } elseif ($models == NULL) {
            $data['success'] = 0;
            $data['message'] = 'ERROR : No Data Found';
            $data['data'] = NULL;
            // $json = json_encode($data, JSON_PRETTY_PRINT);
            // header('Content-Type: application/x-javascript');
            // header("Access-Control-Allow-Origin: *");
            return $data;
        } else {
            // $json = json_encode($data, JSON_PRETTY_PRINT);
            // header('Content-Type: application/json');
            // header("Access-Control-Allow-Origin: *");
            return $data;
        }
    }

    public function actionSetAbsentSolution() {
        $this->checkToken();
        $post_data = json_decode(urldecode(file_get_contents("php://input")));
        $id = null;
        foreach ($post_data as $absen) {
            $model = new AbsensiSolution;
            foreach ($absen as $key => $value) {
                $model->$key = $value;
            }
            if (!empty($model)) {
                if ($model->save()) {
                    $id[] = $model->id;
                }
            }
        }
        $json = json_encode("Berhasil menambah absen harian " . implode(", ", $id), JSON_PRETTY_PRINT);
        header('Content-Type: application/x-javascript');
        header("Access-Control-Allow-Origin: *");
        echo $json;
    }

    public function actionGetAbsentSolution() {
        $status = true;
        //cek token
        $this->checkToken();

        $condition = 'id IS NOT NULL';
        if (isset($_GET['user_id'])) {
            $condition .= ' and t.user_id =' . $_GET['user_id'];
        }
        if (isset($_GET['datetime'])) {
            $condition .= ' and DATE_FORMAT(t.datetime, "%Y-%m-%d") ="' . $_GET['datetime'] . '"';
        }

        $criteria = new CDbCriteria();
        $criteria->mergeWith(array(
            'order' => 'datetime DESC',
            'select' => 't.*',
            'condition' => $condition,
        ));
        $count = AbsensiSolution::model()->count($criteria);

        if ($count > 0) {
            $pages = new CPagination($count);
            // results per page
            $pages->CurrentPage = isset($_GET['start']) ? $_GET['start'] : 0;
            $pages->pageSize = isset($_GET['rows']) ? $_GET['rows'] : 100000000000;
            $perpage = $pages->pageSize;
            $pages->applyLimit($criteria);
            $totalpage = ceil($count / $perpage);
            $models = AbsensiSolution::model()->findAll($criteria);

            $data['success'] = 1;
            $data['message'] = 'Record(s) Found.';
            $data['totalCount'] = $count;
            foreach ($models as $row) {
                $data_fix['id'] = $row->id;
                $data_fix['datetime'] = $row->datetime;
                $data_fix['ver'] = $row->ver;
                $data_fix['stat'] = $row->stat;
                if (!empty($row->guru->id)) {
                    $data_fix['user']['id'] = $row->guru->id;
                    $data_fix['user']['solution_id'] = $row->user_id;
                    $data_fix['user']['name'] = $row->guru->display_name;
                    if (!empty($row->guru->class->id)) {
                        $data_fix['user']['class_id'] = $row->guru->class->id;
                        $data_fix['user']['class_name'] = $row->guru->class->name;
                    }
                }
                $data['data']['result'][] = $data_fix;
            }
        } else {
            $models = NULL;
        }

        if ($status == false) {
            $data['success'] = 0;
            $data['message'] = 'ERROR : Missing or Invalid Parameter !';
            $data['data'] = NULL;
            $json = json_encode($data, JSON_PRETTY_PRINT);
            header('Content-Type: application/x-javascript');
            header("Access-Control-Allow-Origin: *");
            echo $json;
        } elseif ($models == NULL) {
            $data['success'] = 0;
            $data['message'] = 'ERROR : No Data Found';
            $data['data'] = NULL;
            $json = json_encode($data, JSON_PRETTY_PRINT);
            header('Content-Type: application/x-javascript');
            header("Access-Control-Allow-Origin: *");
            echo $json;
        } else {
            $json = json_encode($data, JSON_PRETTY_PRINT);
            header('Content-Type: application/json');
            header("Access-Control-Allow-Origin: *");
            echo $json;
        }
    }

    public function actionList() {
        if (isset($_GET['model'])) {
            $status = true;
            $data = array();

            //cek token		
            $this->checkToken();

            switch ($_GET['model']) {
                case 'user':
                    $model = new User;
                    $criteria = new CDbCriteria;

                    if (isset($_GET['type'])) {
                        switch ($_GET['type']) {
                            case 'students':
                                $criteria->join = 'INNER JOIN class ON `t`.class_id = class.id AND `t`.trash is null ';
                                $criteria->select = "class.*,t.*";
                                $criteria->condition = "role_id = 2";
                                break;

                            case 'teachers':
                                $criteria->condition = "role_id = 1 AND trash is null ";
                                break;

                            case 'class':
                                if (isset($_GET['class_id']))
                                    $criteria->condition = "class_id = " . $_GET['class_id'] . " AND trash is null  ";
                                else
                                    $status = false;
                                break;

                            default:
                                $status = false;
                                break;
                        }
                    }
                    else {
                        $status = false;
                    }

                    $count = User::model()->count($criteria);
                    // results per page
                    $pages = new CPagination($count);
                    $pages->pageSize = 100;
                    $perpage = $pages->pageSize;
                    $pages->applyLimit($criteria);
                    $totalpage = ceil($count / $perpage);
                    $models = $model->findAll($criteria);

                    $data['success'] = 1;
                    $data['message'] = 'Record(s) Found.';
                    $data['totalCount'] = $count;

                    $data['data']['totalPage'] = $totalpage;
                    $data['data']['perPage'] = $perpage;

                    foreach ($models as $get) {
                        $data_fix['id'] = $get->id;
                        $data_fix['username'] = $get->username;
                        $data_fix['display_name'] = $get->display_name;
                        $data_fix['email'] = $get->email;
                        $data_fix['role_id'] = $get->role_id;
                        $data_fix['class_id'] = $get->class_id;
                        if (isset($get->class->name)) {
                            $data_fix['class_name'] = $get->class->name;
                        } else {
                            $data_fix['class_name'] = null;
                        }
                        $data['data']['results'][] = $data_fix;
                        //$data['data']['results'][] = $get->attributes;
                    }
                    break;

                // --------------------------------- lesson API ------------------------------
                case 'lesson':
                    //echo Yii::app()->user->YiiTeacher;exit();
                    $model = new Lesson;

                    $current_user = Yii::app()->session['id_user'];
                    if (Yii::app()->session['role'] == 1) {
                        $term_condition = "user_id = $current_user and semester=" . $_GET['semester'] . " and year =" . $_GET['tahun_ajaran'] . " and trash is null";

                        $criteria = new CDbCriteria;
                        $criteria->condition = $term_condition;
                        $count = Lesson::model()->count($criteria);
                        // results per page
                        $pages = new CPagination($count);
                        $pages->pageSize = 1000000;
                        $perpage = $pages->pageSize;
                        $pages->applyLimit($criteria);
                        $totalpage = ceil($count / $perpage);
                        $models = $model->findAll($criteria);

                        $data['success'] = 1;
                        $data['message'] = 'Record(s) Found.';
                        $data['totalCount'] = $count;

                        $data['data']['totalPage'] = $totalpage;
                        $data['data']['perPage'] = $perpage;
                    } elseif (Yii::app()->session['role'] == 2) {


                        $term_conditon = 't.semester = "' . $_GET['semester'] . '" and t.year = "' . $_GET['tahun_ajaran'] . '" and lm.user_id = "' . $current_user . '" AND t.trash IS NULL';
                        $dataProvider = new CActiveDataProvider('Lesson', array(
                            'criteria' => array(
                                'join' => 'JOIN lesson_mc AS lm ON lm.lesson_id = t.id ',
                                'condition' => '' . $term_conditon,
                            ),
                        ));

                        $models = $dataProvider->getData();
                    } else {
                        $term_condition = " semester=" . $_GET['semester'] . " and year =" . $_GET['tahun_ajaran'] . " and trash is null";

                        $criteria = new CDbCriteria;
                        $criteria->condition = $term_condition;
                        $count = Lesson::model()->count($criteria);
                        // results per page
                        $pages = new CPagination($count);
                        $pages->pageSize = 1000000;
                        $perpage = $pages->pageSize;
                        $pages->applyLimit($criteria);
                        $totalpage = ceil($count / $perpage);
                        $models = $model->findAll($criteria);

                        $data['success'] = 1;
                        $data['message'] = 'Record(s) Found.';
                        $data['totalCount'] = $count;

                        $data['data']['totalPage'] = $totalpage;
                        $data['data']['perPage'] = $perpage;
                    }


                    foreach ($models as $get) {
                        $cek = User::model()->findAll(array('condition' => 'class_id = ' . $get->class->id));
                        $total = count($cek);
                        $data_fix['id'] = $get->id;
                        $data_fix['lesson'] = $get->name;
                        $data_fix['user_id'] = $get->user_id;
                        $data_fix['class'] = $get->class->name;
                        $data_fix['class_id'] = $get->class->id;

                        if (Yii::app()->session['role'] != 2) {
                            $data_fix['total_siswa'] = $total;
                            $data_fix['isStudent'] = false;
                        } else {
                            
                        }

                        $data['data']['result'][] = $data_fix;
                    }
                    break;
                case 'lessonmc':
                    //echo Yii::app()->user->YiiTeacher;exit();
                    $model = new LessonMc;
                    $join = "JOIN lesson AS l ON l.id = t.lesson_id ";
                    $term_condition = " t.semester=" . $_GET['semester'] . " and t.year =" . $_GET['year'] . " and t.trash is null";
                    if (isset($_GET['student_id'])) {
                        $term_condition .= ' and t.user_id =' . $_GET['student_id'];
                    }
                    if (isset($_GET['teacher_id'])) {
                        $term_condition .= ' and l.user_id =' . $_GET['teacher_id'];
                    }
                    if (isset($_GET['lesson_id'])) {
                        $term_condition .= ' and t.lesson_id =' . $_GET['lesson_id'];
                    }

                    $criteria = new CDbCriteria;
                    $criteria->join = $join;
                    $criteria->condition = $term_condition;
                    $count = LessonMc::model()->count($criteria);
                        // results per page
                    $pages = new CPagination($count);
                    $pages->pageSize = 1000000;
                    $perpage = $pages->pageSize;
                    $pages->applyLimit($criteria);
                    $totalpage = ceil($count / $perpage);
                    $models = $model->findAll($criteria);

                    $data['success'] = 1;
                    $data['message'] = 'Record(s) Found.';
                    $data['totalCount'] = $count;

                    $data['data']['totalPage'] = $totalpage;
                    $data['data']['perPage'] = $perpage;
                        
                    foreach ($models as $get) {
                        $data_fix['id'] = $get->id;
                        $data_fix['lesson']['id'] = $get->lesson_id;
                        $data_fix['teacher']['id'] = $get->lesson->user_id;
                        $data_fix['student']['id'] = $get->user_id;
                        $data_fix['class']['id'] = $get->class_id;
                        $data_fix['presensi_hadir'] = $get->presensi_hadir;
                        $data_fix['presensi_sakit'] = $get->presensi_sakit;
                        $data_fix['presensi_izin'] = $get->presensi_izin;
                        $data_fix['presensi_alfa'] = $get->presensi_alfa;
                       
                        if (isset($get->lesson)) {
                            $data_fix['lesson']['name'] = $get->lesson->name;
                        }
                        if (isset($get->teacher)) {
                            $data_fix['teacher']['display_name'] = $get->lesson->users->display_name;
                        }
                        if (isset($get->student)) {
                            $data_fix['student']['display_name'] = $get->student->display_name;
                        }
                        if (isset($get->class)) {
                            $data_fix['class']['name'] = $get->class->name;
                        }

                        $data['data']['result'][] = $data_fix;
                    }
                    break;
                    
                case 'class':
                    $model = new Clases;
                    $criteria = new CDbCriteria;
                    $count = Clases::model()->count($criteria);
                    // results per page
                    $pages = new CPagination($count);
                    $pages->pageSize = 6;
                    $perpage = $pages->pageSize;
                    $pages->applyLimit($criteria);
                    $totalpage = ceil($count / $perpage);
                    $models = $model->findAll($criteria);
                    break;

                // --------------------------------- quiz API ------------------------------
                case 'quiz' :

                    $model = new Quiz;

                    $optSemester = Option::model()->findByAttributes(array('key_config' => 'semester'))->value;
                    $optTahunAjaran = Option::model()->findByAttributes(array('key_config' => 'tahun_ajaran'))->value;
                    $current_user = Yii::app()->session['id_user'];
                    $modelUser = User::model()->findByPk($current_user);
                    //if(Yii::app()->user->YiiTeacher || Yii::app()->user->YiiAdmin){
                    if ($modelUser->role_id == "1") {
                        $term = 'created_by = ' . Yii::app()->user->id . ' and trash is null';
                    } elseif ($modelUser->role_id == "2") {
                        // $cekUsr=User::model()->findByPk(Yii::app()->user->id);
                        // $kls=$modelUser->class_id;
                        // $data['data']['debug'] = $kls;
                        // $lesson_ids = LessonMc::model()->findAll(array('condition'=>'user_id = '.Yii::app()->user->id));
                        // foreach ($lesson_ids as $value) {
                        // 	$lessons_ids[] = $value->lesson_id;
                        // }
                        // $lessons_ids = implode(",", $lessons_ids);
                        // $term='t.status = 1 AND c.id = '.$kls.' and t.trash is null';
                        //$term='status = 1 AND lesson_id IN ('.$lessons_ids.') and trash is null';
                    } else {
                        $term = 't.trash is null';
                        // $data['data']['debug'] = "salah";
                    }
                    $prefix = Yii::app()->params['tablePrefix'];

                    if ($modelUser->role_id != "2") {
                        $dataProvider = new CActiveDataProvider('Quiz', array(
                            'criteria' => array(
                                'order' => 't.id DESC',
                                'condition' => $term),
                            'pagination' => array('pageSize' => 1000)
                        ));
                    } else {
                        // $dataProvider=new CActiveDataProvider('Quiz',array(
                        // 	'criteria'=>array(
                        // 		'select'=>array('t.*'),
                        // 		'join'=>'JOIN '.$prefix.'lesson AS l ON l.id = t.lesson_id JOIN '.$prefix.'class_detail AS c ON l.class_id = c.id',
                        // 		'order'=>'t.id DESC',
                        // 		'condition'=>$term),
                        // 	'pagination'=>array('pageSize'=>1000)
                        // 	));


                        $criteria = new CDbCriteria();
                        $criteria->mergeWith(array(
                            'order' => 'created_at DESC',
                            'select' => 't.*',
                            //'join'=>'JOIN '.$prefix.'lesson as l ON l.id = t.lesson_id JOIN '.$prefix.'class_detail as c ON c.id = l.class_id',
                            'join' => 'JOIN lesson_mc AS lm ON lm.lesson_id = t.lesson_id ',
                            //'condition'=>'l.class_id = '.$class_student_id.' and t.status is not null and t.trash is null and t.status != 2',
                            'condition' => 't.semester = "' . $optSemester . '" and t.year = "' . $optTahunAjaran . '" and lm.user_id = ' . $current_user . ' and t.status is not null and t.trash is null and t.status != 2',
                                //'condition'=>'t.status is not null',
                        ));
                        $count = Quiz::model()->count($criteria);
                        $pages = new CPagination($count);

                        // results per page
                        $pages->pageSize = 1000;
                        $perpage = $pages->pageSize;
                        $pages->applyLimit($criteria);


                        // $dataProvider=new CActiveDataProvider('Quiz',array(
                        // 	'criteria'=>array(
                        // 		'order'=>'lesson_id',
                        // 		'condition'=>$term),
                        // 	'pagination'=>array('pageSize'=>100)
                        // ));
                    }
                    // results per page
                    if ($modelUser->role_id != "2") {
                        if (!empty($dataProvider->getData())) {
                            $models = $dataProvider->getData();
                        }
                    } else {
                        $models = Quiz::model()->findAll($criteria);
                    }

                    $count = count($models);
                    $pages = new CPagination($count);
                    $pages->pageSize = 100000;
                    $perpage = $pages->pageSize;
                    //$pages->applyLimit($criteria);
                    $totalpage = ceil($count / $perpage);
                    //$models = $model->findAll($criteria);

                    $data['success'] = 1;
                    $data['message'] = 'Record(s) Found.';
                    $data['totalCount'] = $count;

                    $data['data']['totalPage'] = $totalpage;
                    $data['data']['perPage'] = $perpage;

                    foreach ($models as $get) {
                        //if (Yii::app()->user->YiiStudent) {
                        if (Yii::app()->session['role'] == 2) {
                            $data_fix['isStudent'] = true;

                            $cekQuiz = StudentQuiz::model()->findByAttributes(array('quiz_id' => $get->id, 'student_id' => Yii::app()->session['id_user']));
                            if (!empty($cekQuiz)) {
                                $data_fix['status'] = "Sudah Mengerjakan";
                            } else {
                                $data_fix['status'] = "Belum Mengerjakan";
                            }
                        } else {
                            // $cek=StudentAssignment::model()->findAll(array('condition'=>'assignment_id = '.$get->id.' and status = 1 and score is null'));
                            // $total=count($cek);
                            // $data_fix['status'] = '';
                            // $data_fix['belum_dinilai'] = $total;
                            $data_fix['isStudent'] = false;

                            if (!empty($get->lesson_id)) {
                                if ($get->lesson->moving_class == 1) {
                                    $data_fix['class'] = $get->lesson->grade->name;
                                } else {
                                    $data_fix['class'] = $get->lesson->class->name;
                                }
                            }
                            $data_fix['teacher'] = $get->teacher->display_name;

                            // $data_fix['student_number'] = $get->due_date;
                            // $data_fix['student_number_done'] = $get->due_date;
                            // $data_fix['passcode'] = $get->due_date;
                            // $data_fix['time'] = $get->due_date;
                            // $data_fix['publish_status'] = $get->due_date;
                        }
                        //$data['data']['result'][] = $get->attributes;


                        $data_fix['id'] = $get->id;
                        $data_fix['title'] = $get->title;
                        $data_fix['time_in_minute'] = $get->end_time;
                        $data_fix['total_question'] = $get->total_question;
                        $data_fix['lesson'] = $get->lesson->name;
                        $data_fix['status_tampil'] = $get->status;

                        $data['data']['result'][] = $data_fix;
                    }



                    break;

                case 'nilai' :
                    $criteria = new CDbCriteria;
                    if (isset($_GET['type'])) {
                        switch ($_GET['type']) {
                            case 'tugas':
                                $model = new StudentAssignment;
                                // if user = students
                                //if(Yii::app()->user->YiiStudent)
                                if (Yii::app()->session['role'] == 2) {
                                    $criteria->select = "t.*, u.title as title, u.due_date as due_date, l.name as lesson_name, c.name as class_name";
                                    $criteria->join = 'JOIN assignment AS u ON u.id = t.assignment_id JOIN lesson AS l ON l.id = u.lesson_id JOIN class AS c ON c.id = l.class_id';
                                    $criteria->order = 't.updated_at DESC';
                                    $criteria->condition = "t.student_id =" . Yii::app()->session['id_user'];
                                    $data['data']['isStudent'] = true;
                                    $data['data']['isAdmin'] = false;
                                }
                                //elseif(Yii::app()->user->YiiTeacher) 
                                elseif (Yii::app()->session['role'] == 1) {
                                    $criteria->select = "t.*, u.title as title, u.due_date as due_date, l.name as lesson_name, c.name as class_name";
                                    $criteria->join = 'JOIN assignment AS u ON u.id = t.assignment_id JOIN lesson AS l ON l.id = u.lesson_id JOIN class AS c ON c.id = l.class_id';
                                    $criteria->condition = "t.status = 1 and u.created_by = " . Yii::app()->session['id_user'];
                                    $data['data']['isStudent'] = false;
                                    $data['data']['isAdmin'] = false;
                                } else {
                                    $criteria->select = "t.*, u.title as title, u.due_date as due_date";
                                    $criteria->join = 'JOIN assignment AS u ON u.id = t.assignment_id';
                                }
                                $count = StudentAssignment::model()->count($criteria);
                                // results per page
                                $pages = new CPagination($count);
                                $pages->pageSize = 12;
                                $perpage = $pages->pageSize;
                                $pages->applyLimit($criteria);
                                $totalpage = ceil($count / $perpage);
                                $models = $model->findAll($criteria);

                                $data['success'] = 1;
                                $data['message'] = 'Record(s) Found.';
                                $data['totalCount'] = $count;

                                $data['data']['totalPage'] = $totalpage;
                                $data['data']['perPage'] = $perpage;


                                foreach ($models as $get) {
                                    //if(Yii::app()->user->YiiStudent)
                                    if (Yii::app()->session['role'] == 2) {
                                        $data_fix['id_nilai'] = $get->id;
                                        $data_fix['title'] = $get->title;
                                        $data_fix['lesson_name'] = $get->teacher_assign->lesson->name;
                                        $data_fix['class'] = $get->teacher_assign->lesson->class->name;
                                        $data_fix['updated_at'] = date('d M Y G:i:s', strtotime($get->updated_at));

                                        if (!empty($get->score)) {
                                            $data_fix['status_score'] = "Sudah Mengumpulkan dan Diberi Nilai";
                                        } elseif (empty($get->score) && $get->status == NULL) {
                                            $data_fix['status_score'] = "Belum Mengumpulkan";
                                        } elseif (empty($get->score) && $get->status == 1) {
                                            $data_fix['status_score'] = "Sudah Mengumpulkan dan Belum Diberi Nilai";
                                        }

                                        $data_fix['score'] = $get->score;
                                        // $data_fix['isStudent'] = true;
                                        // $data_fix['isAdmin'] = false;
                                        $data['data']['result'][] = $data_fix;
                                    }
                                    //else if(Yii::app()->user->YiiTeacher)
                                    else if (Yii::app()->session['role'] == 1) {
                                        $data_fix['id_nilai'] = $get->id;
                                        $data_fix['student_name'] = $get->student->display_name;
                                        $data_fix['id_tugas'] = $get->assignment_id;
                                        $data_fix['title'] = $get->title;
                                        $data_fix['lesson_name'] = $get->teacher_assign->lesson->name;
                                        $data_fix['class'] = $get->teacher_assign->lesson->class->name;
                                        $data_fix['updated_at'] = date('d M Y G:i:s', strtotime($get->updated_at));

                                        if (!empty($get->due_date > $get->created_at)) {
                                            $data_fix['tepat_waktu'] = "Ya";
                                        } else {
                                            $data_fix['tepat_waktu'] = "Tidak";
                                        }
                                        $data_fix['score'] = $get->score;
                                        $data_fix['isStudent'] = false;
                                        $data_fix['isAdmin'] = false;
                                        $data['data']['result'][] = $data_fix;
                                    }
                                }

                                break;

                            case 'kuis':
                                $model = new StudentQuiz;
                                if (Yii::app()->session['role'] == 2) {
                                    $criteria->select = "t.*, u.title as title, l.name as lesson_name, c.name as class_name";
                                    $criteria->join = 'JOIN quiz AS u ON u.id = t.quiz_id JOIN lesson AS l ON l.id = u.lesson_id JOIN class AS c ON c.id = l.class_id';
                                    $criteria->condition = "t.student_id = " . Yii::app()->session['id_user'];
                                    $data['data']['isStudent'] = true;
                                    $data['data']['isAdmin'] = false;
                                } elseif (Yii::app()->session['role'] == 1) {
                                    $criteria->select = "t.*, u.title as title, l.name as lesson_name, c.name as class_name";
                                    $criteria->join = 'JOIN quiz AS u ON u.id = t.quiz_id JOIN lesson AS l ON l.id = u.lesson_id JOIN class AS c ON c.id = l.class_id';
                                    $criteria->condition = "u.created_by = " . Yii::app()->session['id_user'];
                                    $data['data']['isStudent'] = false;
                                    $data['data']['isAdmin'] = false;
                                } else {
                                    $criteria->select = "t.*, u.title as title, u.due_date as due_date";
                                    $criteria->join = "JOIN assignment AS u ON u.id = t.assignment_id";
                                }
                                $count = StudentQuiz::model()->count($criteria);
                                // results per page
                                $pages = new CPagination($count);
                                $pages->pageSize = 10;
                                $perpage = $pages->pageSize;
                                $pages->applyLimit($criteria);
                                $totalpage = ceil($count / $perpage);
                                $models = $model->findAll($criteria);

                                $data['success'] = 1;
                                $data['message'] = 'Record(s) Found.';
                                $data['totalCount'] = $count;

                                $data['data']['totalPage'] = $totalpage;
                                $data['data']['perPage'] = $perpage;

                                foreach ($models as $get) {
                                    if (Yii::app()->session['role'] == 2) {
                                        $data_fix['studentQuiz_id'] = $get->id;
                                        $data_fix['student_name'] = $get->user->display_name;
                                        $data_fix['quiz_title'] = $get->quiz->title;
                                        $data_fix['lesson_name'] = $get->quiz->lesson->name;
                                        $data_fix['class'] = $get->quiz->lesson->class->name;
                                        $data_fix['updated_at'] = date('d M Y G:i:s', strtotime($get->updated_at));
                                        $data_fix['score'] = $get->score;
                                        $data_fix['isStudent'] = true;
                                        $data_fix['isAdmin'] = false;
                                        $data['data']['result'][] = $data_fix;
                                    } else if (Yii::app()->session['role'] == 1) {
                                        $data_fix['studentQuiz_id'] = $get->id;
                                        $data_fix['student_name'] = $get->user->display_name;
                                        $data_fix['id_kuis'] = $get->quiz_id;
                                        $data_fix['quiz_title'] = $get->quiz->title;
                                        $data_fix['lesson_name'] = $get->quiz->lesson->name;
                                        $data_fix['class'] = $get->quiz->lesson->class->name;
                                        $data_fix['updated_at'] = date('d M Y G:i:s', strtotime($get->updated_at));

                                        $data_fix['score'] = $get->score;
                                        $data_fix['isStudent'] = false;
                                        $data_fix['isAdmin'] = false;
                                        $data['data']['result'][] = $data_fix;
                                    }
                                }

                                break;

                            default:
                                $status = false;
                                break;
                        }
                    } else {
                        $status = false;
                    }

                    break;

                // --------------------------------- chapter API ------------------------------
                case 'chapter' :


                    if (Yii::app()->session['role'] == 2) {
                        $model = new Chapters;
                        $current_user = Yii::app()->session['id_user'];
                        $term_conditon = 't.semester = "' . $_GET['semester'] . '" and t.year = "' . $_GET['tahun_ajaran'] . '" and lm.user_id = "' . $current_user . '" AND t.trash IS NULL';
                        $dataProvider = new CActiveDataProvider('chapters', array(
                            'criteria' => array(
                                'join' => 'JOIN lesson_mc AS lm ON lm.lesson_id = t.id_lesson ',
                                'condition' => '' . $term_conditon,
                            ),
                        ));

                        $models = $dataProvider->getData();
                    } else {

                        $model = new Chapters;
                        $criteria = new CDbCriteria;
                        $count = Chapters::model()->count($criteria);
                        // results per page
                        $pages = new CPagination($count);
                        $pages->pageSize = 10000;
                        $perpage = $pages->pageSize;
                        $pages->applyLimit($criteria);
                        $totalpage = ceil($count / $perpage);
                        $models = $model->findAll($criteria);

                        $data['success'] = 1;
                        $data['message'] = 'Record(s) Found.';
                        $data['totalCount'] = $count;

                        $data['data']['totalPage'] = $totalpage;
                        $data['data']['perPage'] = $perpage;
                    }



                    foreach ($models as $get) {
                        $data_fix['id_chapter'] = $get->id;
                        $data_fix['id_lesson'] = $get->id_lesson;
                        $data_fix['lesson'] = $get->mapel->name;
                        $data_fix['title'] = $get->title;
                        $data_fix['created_at'] = $get->created_at;
                        $data['data']['result'][] = $data_fix;
                        //$data['data']['result'][] = $get->attributes;
                    }
                    break;

                case 'studentquiz' :
                    $model = new StudentQuiz;
                    $criteria = new CDbCriteria;
                    if (!empty($_GET['quiz_id'])) {
                        $criteria->condition = "quiz_id =" . $_GET['quiz_id'];
                    }


                    if (!empty($_GET['meonly'])) {
                        $criteria->condition = "quiz_id =" . $_GET['quiz_id'] . " and student_id = " . Yii::app()->session['id_user'];
                    }

                    $count = StudentQuiz::model()->count($criteria);
                    // results per page
                    $pages = new CPagination($count);
                    $pages->pageSize = 1000;
                    $perpage = $pages->pageSize;
                    $pages->applyLimit($criteria);
                    $totalpage = ceil($count / $perpage);
                    $models = $model->findAll($criteria);

                    $data['success'] = 100;
                    $data['message'] = 'Record(s) Found.';
                    $data['totalCount'] = $count;

                    $data['data']['totalPage'] = $totalpage;
                    $data['data']['perPage'] = $perpage;

                    foreach ($models as $get) {
                        $data_fix['student_id'] = $get->student_id;
                        $data_fix['student_name'] = $get->user->display_name;
                        $data_fix['created_at'] = $get->user->created_at;
                        $data_fix['score'] = $get->score;
                        $data_fix['right_answer'] = $get->right_answer;
                        $data_fix['wrong_answer'] = $get->wrong_answer;
                        $data_fix['unanswered'] = $get->unanswered;
                        $data['data']['result'][] = $data_fix;
                    }
                    break;

                case 'option' :
                    $model = new Option;
                    $criteria = new CDbCriteria;
                    $count = Option::model()->count($criteria);
                    // results per page
                    $pages = new CPagination($count);
                    $pages->pageSize = 100;
                    $perpage = $pages->pageSize;
                    $pages->applyLimit($criteria);
                    $totalpage = ceil($count / $perpage);
                    $models = $model->findAll($criteria);

                    $data['success'] = 1;
                    $data['message'] = 'Record(s) Found.';
                    $data['totalCount'] = $count;

                    $data['data']['totalPage'] = $totalpage;
                    $data['data']['perPage'] = $perpage;

                    foreach ($models as $get) {
                        $data_fix['key_config'] = $get->key_config;
                        $data_fix['value'] = $get->value;
                        $data['data']['result'][] = $data_fix;
                    }
                    break;

                case 'naon':

                    $term_conditon = ' trash IS NULL';

                    $dataProvider = new CActiveDataProvider('Lesson', array(
                        'criteria' => array(
                        //'join'=>'JOIN users AS u ON u.id = t.user_id JOIN class_detail AS cd ON cd.id = u.class_id ',
                            'condition' => '' . $term_conditon,
                        ),
                        'pagination'=>array(
                            'pageSize'=>100000000000, // or another reasonable high value...
                        ),
                    ));



                    $models_lesson = $dataProvider->getData();
                    $models = $dataProvider->getData();
                    $model = $dataProvider->getData();

                    foreach ($models_lesson as $lesson) {
                            $lesson_ids[] = $lesson->id;
                        }

                        $data['data']['result'][] = $lesson_ids;

                    break;

                case 'absensi' :
                    $current_user = Yii::app()->session['id_user'];
                    $role_users = Yii::app()->session['role'];
                    if(isset($_GET['user_id'])){
                        $current_user = $_GET['user_id'];
                    }


                    if ($role_users == "99") {
                        $term_conditon = 'trash IS NULL';
                    } else {
                        $term_conditon = 'semester = "' . $_GET['semester'] . '" and year = "' . $_GET['tahun_ajaran'] . '" and user_id = "' . $current_user . '" AND trash IS NULL';
                    }


                    $dataProvider = new CActiveDataProvider('Lesson', array(
                        'criteria' => array(
//                            'join'=>'JOIN users AS u ON u.id = t.user_id JOIN class_detail AS cd ON cd.id = u.class_id ',
                            'condition' => '' . $term_conditon,
                        ),
                        'pagination'=>array(
                            'pageSize'=>100000000000, // or another reasonable high value...
                        ),
                    ));

                    $models_lesson = $dataProvider->getData();
                    $models =  array();
                    $count =  0;
                    $totalpage =  0;
                    $perpage =  0;
                    if($models_lesson) {
                        foreach ($models_lesson as $lesson) {
                            $lesson_ids[] = $lesson->id;
                        }

                        $lesson_ids = implode(",", $lesson_ids);

                        $model = new Absensi;
                        $criteria = new CDbCriteria;
                        $sql = "id_lesson in (" . $lesson_ids . ")";
                        if(isset($_GET['created_at'])){
                            $sql .= " AND created_at ='" . $_GET['created_at'] . "'" ;
                        }
                        $criteria->condition = $sql;
                        
                        $count = Absensi::model()->count($criteria);
                        // results per page
                        $pages = new CPagination($count);
                        $pages->pageSize = 100000000;
                        $perpage = $pages->pageSize;
                        $pages->applyLimit($criteria);
                        $totalpage = ceil($count / $perpage);
                        $models = $model->findAll($criteria);
                    }
                    $data['success'] = 1;
                    $data['message'] = 'Record(s) Found.';
                    $data['totalCount'] = $count;

                    $data['data']['totalPage'] = $totalpage;
                    $data['data']['perPage'] = $perpage;

                    foreach ($models as $get) {
                        $user = new User;
                        $users = $user->findByPk($get->user_id);

                        $data_fix['user_id'] = $get->user_id;
                        $data_fix['nama'] = $users->display_name;
                        $data_fix['nama_kelas'] = $users->class->name;
                        $data_fix['id_lesson'] = $get->id_lesson;
                        $data_fix['lesson_name'] = $get->lesson->name;
                        $data_fix['absen'] = $get->absen;
                        $data_fix['created_at'] = $get->created_at;
                        $data_fix['pertemuan_ke'] = $get->pertemuan_ke;
                        $data_fix['ket'] = $get->ket;
                        $data_fix['created_date'] = $get->created_date;
                        $data_fix['lesson_ids'] = $term_conditon;
                        $data['data']['result'][] = $data_fix;
                    }
                    break;

                case 'absensi_siswa' :
                    $model = new Absensi;
                    $criteria = new CDbCriteria;
                    $sql = array();
                    if(isset($_GET['id_lesson'])){
                        $sql[] = "id_lesson = '" . $_GET['id_lesson'] . "'";
                    }
                    if(isset($_GET['user_id'])){
                        $sql[] = "user_id ='" . $_GET['user_id'] . "'" ;
                    }
                    if(isset($_GET['created_at'])){
                        $sql[] = "created_at ='" . $_GET['created_at'] . "'" ;
                    }
                    $criteria->condition = implode(" AND ", $sql);
                        
                    $count = Absensi::model()->count($criteria);
                    // results per page
                    $pages = new CPagination($count);
                    $pages->pageSize = 100;
                    $perpage = $pages->pageSize;
                    $pages->applyLimit($criteria);
                    $totalpage = ceil($count / $perpage);
                    $models = $model->findAll($criteria);
                    
                    $data['success'] = 1;
                    $data['message'] = 'Record(s) Found.';
                    $data['totalCount'] = $count;

                    $data['data']['totalPage'] = $totalpage;
                    $data['data']['perPage'] = $perpage;

                    foreach ($models as $get) {
                        $user = new User;
                        $users = $user->findByPk($get->user_id);

                        $data_fix['user_id'] = $get->user_id;
                        $data_fix['nama'] = $users->display_name;
                        $data_fix['nama_kelas'] = $users->class->name;
                        $data_fix['id_lesson'] = $get->id_lesson;
                        $data_fix['lesson_name'] = $get->lesson->name;
                        $data_fix['absen'] = $get->absen;
                        $data_fix['created_at'] = $get->created_at;
                        $data_fix['pertemuan_ke'] = $get->pertemuan_ke;
                        $data_fix['ket'] = $get->ket;
                        $data_fix['created_date'] = $get->created_date;
                        $data['data']['result'][] = $data_fix;
                    }
                    break;

                case 'lesson_list' :
                    $model = new LessonList;
                    $criteria = new CDbCriteria;
                    $count = LessonList::model()->count($criteria);
                    // results per page
                    $pages = new CPagination($count);
                    $pages->pageSize = 10000;
                    $perpage = $pages->pageSize;
                    $pages->applyLimit($criteria);
                    $totalpage = ceil($count / $perpage);
                    $models = $model->findAll($criteria);

                    $data['success'] = 1;
                    $data['message'] = 'Record(s) Found.';
                    $data['totalCount'] = $count;

                    $data['data']['totalPage'] = $totalpage;
                    $data['data']['perPage'] = $perpage;

                    foreach ($models as $get) {
                        $data_fix['id'] = $get->id;
                        $data_fix['name'] = $get->name;
                        $data_fix['group'] = $get->group;
                        $data['data']['result'][] = $data_fix;
                    }
                    break;



                case 'class_detail' :
                    $model = new ClassDetail;
                    $criteria = new CDbCriteria;
                    if (Yii::app()->session['role'] == "1") {
                        $criteria->condition = "teacher_id =" . Yii::app()->session['id_user'];
                    }



                    $count = ClassDetail::model()->count($criteria);
                    // results per page
                    $pages = new CPagination($count);
                    $pages->pageSize = 10000;
                    $perpage = $pages->pageSize;
                    $pages->applyLimit($criteria);
                    $totalpage = ceil($count / $perpage);
                    $models = $model->findAll($criteria);

                    $data['success'] = 1;
                    $data['message'] = 'Record(s) Found.';
                    $data['totalCount'] = $count;

                    $data['data']['totalPage'] = $totalpage;
                    $data['data']['perPage'] = $perpage;

                    foreach ($models as $get) {
                        $data_fix['id'] = $get->id;
                        $data_fix['name'] = $get->name;
                        $data_fix['class_id'] = $get->class_id;
                        $data_fix['teacher_id'] = $get->teacher_id;
                        $data['data']['result'][] = $data_fix;
                    }
                    break;

                default:
                    //$this->_sendResponse(501, sprintf('Error: Mode <b>list</b> is not implemented for model <b>%s</b>',$_GET['model']) );
                    $data['success'] = 0;
                    $data['message'] = 'ERROR : Invalid Parameter';
                    $data['data'] = NULL;
                    $json = json_encode($data, JSON_PRETTY_PRINT);
                    header('Content-Type: application/x-javascript');
                    echo $json;
                    exit();
            }

            if ($status == false) {
                //$this->_sendResponse(501, sprintf('No items where found for model <b>%s</b>', $_GET['model']) );
                $data['success'] = 0;
                $data['message'] = 'ERROR : Missing or Invalid Parameter !';
                $data['data'] = NULL;
                $json = json_encode($data, JSON_PRETTY_PRINT);
                header('Content-Type: application/x-javascript');
                header("Access-Control-Allow-Origin: *");
                echo $json;
                exit();
            } elseif ($models == NULL) {
                //$this->_sendResponse(501, sprintf('Error: Mode <b>list</b> is not implemented for model <b>%s</b>',$_GET['model']));
                //$data['hiyai'] ='x';
                $data['success'] = 0;
                $data['message'] = 'ERROR : No Data Found';
                $data['data'] = NULL;
                $json = json_encode($data, JSON_PRETTY_PRINT);
                header('Content-Type: application/x-javascript');
                header("Access-Control-Allow-Origin: *");
                echo $json;
                exit();
            } else if (is_null($model)) {
                //$this->_sendResponse(501, sprintf('No items where found for model <b>%s</b>', $_GET['model']) );
                $data['success'] = 0;
                $data['message'] = 'ERROR : No Data Found';
                $data['data'] = NULL;
                $json = json_encode($data, JSON_PRETTY_PRINT);
                header('Content-Type: application/x-javascript');
                header("Access-Control-Allow-Origin: *");
                echo $json;
                exit();
            }


            $json = json_encode($data, JSON_PRETTY_PRINT);
            header('Content-Type: application/json');
            header("Access-Control-Allow-Origin: *");
            echo $json;

            //$this->_sendResponse(200, $json );
        } else
            $this->_sendResponse(501, sprintf('Error: No model selected.'));
    }

// }}}
    // {{{ actionView
    /* Shows a single item
     *
     * @access public
     * @return void
     */

    public function actionSchoollist() {
        $data['success'] = 1;
        $data['message'] = 'Record(s) Found.';
        $data_fix[0] = array(
            'school_name' => 'Demo',
            'url_live' => 'api.pinisi.io',
            'ip_address_live' => null,
            'url_local' => null,
            'ip_address_local' => null
        );

        $data_fix[1] = array(
            'school_name' => 'SMAN 24 Bandung',
            'url_live' => 'sman24.pinisi.io',
            'ip_address' => null,
            'url_local' => 'usdj.sman24.bdg',
            'ip_address_local' => '192.168.2.24'
        );

        $data_fix[2] = array(
            'school_name' => 'SMAN 8 Bandung',
            'url_live' => 'sman8.pinisi.io',
            'ip_address' => null,
            'url_local' => 'usdj.sman8.bdg',
            'ip_address_local' => '192.168.2.8'
        );

        $data['data']['result'][] = $data_fix;
        $json = json_encode($data, JSON_PRETTY_PRINT);
        header('Content-Type: application/json');
        echo $json;
    }

    public function actionView() {
        $status = true;
        $data = array();
        //cek token
        $this->checkToken();

        if (!isset($_GET['id']))
            $this->_sendResponse(500, 'Error: Parameter <b>id</b> is missing');
        $id = $_GET['id'];

        switch ($_GET['model']) {
            // Find respective model
            case 'lesson':
                $model = new Lesson;
                $models = $model->findByPk($id);

                if (isset($_GET['type'])) {
                    $type = $_GET['type'];
                } else {
                    $type = NULL;
                    $status = false;
                }

                if (Yii::app()->session['role'] == 2) {
                    $term = "lesson_id = " . $id . " AND status is not null";
                } else {
                    $term = "lesson_id = " . $id;
                }

                switch ($type) {
                    case 'materi' :
                        $datas = Chapters::model()->findAll(array("condition" => "id_lesson = $id"));
                        break;

                    case 'tugas' :
                        $datas = Assignment::model()->findAll(array("condition" => "lesson_id =  $id AND assignment_type IS NULL AND recipient IS NULL or recipient = " . Yii::app()->session['id_user']));
                        break;

                    case 'ulangan' :
                        $datas = Quiz::model()->findAll(array("condition" => $term));
                        break;

                    case 'lks' :
                        $datas = Lks::model()->findAll("lesson_id=" . $id);
                        break;

                    default :

                        break;
                }

                $ti = Assignment::model()->findAll(array("condition" => "lesson_id =  $id AND assignment_type IS NULL AND recipient = " . Yii::app()->session['id_user']));


                $data['success'] = 1;
                $data['message'] = 'Record(s) Found.';

                $data_fix['lesson_id'] = $models->id;
                $data_fix['lesson_name'] = $models->name;
                $data_fix['teacher_name'] = $models->users->display_name;
                $data_fix['class_name'] = $models->class->name;
                $data_fix['type'] = $type;

                foreach ($datas as $get) {
                    $data_term['id'] = $get->id;
                    $data_term['term'] = $get->title;

                    $data_fix['term'][] = $data_term;
                }

                $data['data']['result'] = $data_fix;
                break;

            case 'lks' : // LKS view

                $modelx = new Lks;
                $model = $modelx->findByPk($id);
                $models = $modelx->findByPk($id);
                $materis = '';
                $tugases = '';
                $ulangans = '';

                if (!empty($models->chapters)) {
                    $materis = $models->chapters;
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

                    if (Yii::app()->session['role'] == 1) {
                        $ulangan = Quiz::model()->findAll(array('condition' => 'lesson_id = ' . $model->lesson_id . ' and id not in (' . $ulangans . ')'));
                    } else {
                        $ulangan = Quiz::model()->findAll(array('condition' => 'lesson_id = ' . $model->lesson_id . ' and id not in (' . $ulangans . ') and status = 1'));
                    }
                } else {
                    if (Yii::app()->session['role'] == 1) {
                        $ulangan = Quiz::model()->findAll(array('condition' => 'lesson_id = ' . $model->lesson_id));
                    } else {
                        $ulangan = Quiz::model()->findAll(array('condition' => 'lesson_id = ' . $model->lesson_id . ' and status = 1'));
                    }
                }

                //echo '<pre>';print_r($model);echo '</pre>';
                //echo '<pre>';print_r($materi);echo '</pre>';
                //echo '<pre>';print_r($tugas);echo '</pre>';
                //echo '<pre>';print_r($ulangan);echo '</pre>';
                //exit;

                $data['success'] = 1;
                $data['message'] = 'Record(s) Found.';
                $data_fix['title'] = $model->title;
                $data_fix['lesson_name'] = $model->pelajaran->name;
                $data_fix['lesson_class'] = $model->pelajaran->class->name;
                $data_fix['teacher_name'] = $model->pelajaran->users->display_name;

                if (!empty($model->chapters)) {
                    $bahan1 = explode(',', $model->chapters);
                    foreach ($bahan1 as $bhn1) {
                        $cek1 = Chapters::model()->findByPk($bhn1);
                        $temp['chapter_id'] = $cek1->id;
                        $temp['chapter_title'] = $cek1->title;
                        $data_fix['term']['chapter'][] = $temp;
                    }
                    unset($temp);
                }

                if (!empty($model->assignments)) {
                    $bahan2 = explode(',', $model->assignments);
                    foreach ($bahan2 as $bhn2) {
                        $cek2 = Assignment::model()->findByPk($bhn2);
                        $temp['assignment_id'] = $cek2->id;
                        $temp['assignment_title'] = $cek2->title;
                        $data_fix['term']['assignment'][] = $temp;
                    }
                    unset($temp);
                }

                if (!empty($model->quizes)) {
                    $bahan3 = explode(',', $model->quizes);
                    foreach ($bahan3 as $bhn3) {
                        $cek3 = Quiz::model()->findByPk($bhn3);
                        $temp['quiz_id'] = $cek3->id;
                        $temp['quiz_title'] = $cek3->title;
                        $data_fix['term']['quiz'][] = $temp;
                    }
                    unset($temp);
                }


                $data['data']['result'] = $data_fix;
                break;

            case 'assignment' :
                if (isset($_GET['type'])) {
                    $type = $_GET['type'];
                } else {
                    $type = NULL;
                }

                $model = new Assignment;
                $studentAssignment = new StudentAssignment;
                $notif = new Notification;
                $activity = new Activities;
                $models = $model->findByPk($id);
                $user = User::model()->findByPk(Yii::app()->session['role']);
                $user_kelas = $user->class_id;
                $mapel = Lesson::model()->findByAttributes(array('id' => $models->lesson_id));
                $kelas = Clases::model()->findByAttributes(array('id' => $mapel->class_id));

                if ($type == 1) {
                    $term = 't.status = 1 AND assignment_id = ' . $id . ' AND score is null';
                    /* elseif($type==2){
                      $term='t.status = 1 AND assignment_id = '.$id; */
                } else {
                    $term = 't.status = 1 AND assignment_id = ' . $id;
                }

                $criteria = new CDbCriteria;
                $criteria->condition = $term;
                $criteria->order = 'student_id ASC';

                $studentTasks = studentAssignment::model()->findAll($criteria);
                $cekTugas = StudentAssignment::model()->findByAttributes(array('assignment_id' => $models->id, 'student_id' => Yii::app()->session['id_user']));

                $data['success'] = 1;
                $data['message'] = 'Record(s) Found.';

                $data_fix['assignment_id'] = $models->id;
                $data_fix['assignment_title'] = $models->title;
                $data_fix['due_date'] = $models->due_date;
                $data_fix['assignment_content'] = $models->content;
                $data_fix['assignment_file'] = $models->file;
                $data_fix['teacher_name'] = $models->teacher->display_name;
                if (!empty($cekTugas)) {
                    if (!empty($cekTugas->score)) {
                        $data_fix['score'] = $cekTugas->score;
                        $data_fix['isScored'] = true;
                    } else {
                        $data_fix['score'] = null;
                        $data_fix['isScored'] = false;
                    }
                    if ($cekTugas->status == NULL) {
                        $data_fix['status'] = 'draft';
                    }
                    if ($cekTugas->status == 1) {
                        $data_fix['status'] = 'sent';
                    }
                } else {
                    $data_fix['score'] = null;
                    $data_fix['isScored'] = null;
                    $data_fix['status'] = null;
                }
                //print_r($cekTugas);exit;

                $data['data']['result'] = $data_fix;
                break;

            case 'chapter' :

                $model = new Chapters;
                $models = $model->findByPk($id);

                $chapterFiles = ChapterFiles::model()->findAll(array("condition" => "id_chapter = $id"));
                $cekFile = ChapterFiles::model()->findByAttributes(array('id_chapter' => $models->id));

                $data['success'] = 1;
                $data['message'] = 'Record(s) Found.';

                $data_fix['chapter_id'] = $models->id;
                $data_fix['chapter_title'] = $models->title;
                $data_fix['chapter_type'] = $models->chapter_type;
                $data_fix['chapter_content'] = $models->content;
                $data_fix['lesson_id'] = $models->mapel->id;
                if (!empty($cekFile->file)) {
                    $data_fix['chapterExist'] = true;
                    $data_fix['chapterFiles_id'] = $cekFile->id;
                    $data_fix['chapterFiles_files'] = $cekFile->file;
                    $data_fix['chapterFiles_type'] = $cekFile->type;
                    if ($cekFile->type == 'mp4' || $cekFile->type == '3gp' || $cekFile->type == 'avi' || $cekFile->type == 'mpeg') {
                        $data_fix['chapterVideo'] = true;
                        $data_fix['chapterPdf'] = false;
                        $data_fix['chapterPhoto'] = false;
                    } elseif ($cekFile->type == 'jpg' || $cekFile->type == 'png' || $cekFile->type == 'gif' || $cekFile->type == 'bmp' || $cekFile->type == 'jpeg' || $cekFile->type == 'tif') {
                        $data_fix['chapterVideo'] = false;
                        $data_fix['chapterPdf'] = false;
                        $data_fix['chapterPhoto'] = true;
                    } else {
                        $data_fix['chapterVideo'] = false;
                        $data_fix['chapterPdf'] = true;
                        $data_fix['chapterPhoto'] = false;
                    }
                } else {
                    $data_fix['chapterExist'] = false;
                    $data_fix['chapterFiles_id'] = null;
                    $data_fix['chapterFiles_files'] = null;
                    $data_fix['chapterFiles_type'] = null;
                }

                $data['data']['result'] = $data_fix;
                break;

            case 'user' :

                $model = new User;
                $models = $model->findByPk($id);

                $data['success'] = 1;
                $data['message'] = 'Record(s) Found.';   

                $data_fix['user_id'] = $models->id;
                $data_fix['nama'] = $models->display_name;

                if (isset($models->spp)) {
                    // $data_fix['spp'] = $models->spp;

                    $curl = curl_init();

                    $ids = explode(",", $models->spp);

                    $arr_ids['ids'] = $ids;

                    $the_ids = json_encode($arr_ids); 

                    curl_setopt_array($curl, array(
                      CURLOPT_PORT => "3000",
                      CURLOPT_URL => "http://35.198.223.223:3000/transaction/getByIds",
                      CURLOPT_RETURNTRANSFER => true,
                      CURLOPT_ENCODING => "",
                      CURLOPT_MAXREDIRS => 10,
                      CURLOPT_TIMEOUT => 30,
                      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                      CURLOPT_CUSTOMREQUEST => "POST",
                      CURLOPT_POSTFIELDS => $the_ids,
                      CURLOPT_HTTPHEADER => array(
                        "Cache-Control: no-cache",
                        "Content-Type: application/json"
                      ),
                    ));

                    $response = curl_exec($curl);
                    $err = curl_error($curl);

                    curl_close($curl);

                    if ($err) {
                      $data_fix['spp'] =  "cURL Error #:" . $err;
                    } else {
                      $data_fix['spp'] =  $response;
                    }
                }

                if (isset($models->dsp)) {
                    $curl = curl_init();

                    $ids = explode(",", $models->dsp);

                    $arr_ids['ids'] = $ids;

                    $the_ids = json_encode($arr_ids); 

                    curl_setopt_array($curl, array(
                      CURLOPT_PORT => "3000",
                      CURLOPT_URL => "http://35.198.223.223:3000/transaction/getByIds",
                      CURLOPT_RETURNTRANSFER => true,
                      CURLOPT_ENCODING => "",
                      CURLOPT_MAXREDIRS => 10,
                      CURLOPT_TIMEOUT => 30,
                      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                      CURLOPT_CUSTOMREQUEST => "POST",
                      CURLOPT_POSTFIELDS => $the_ids,
                      CURLOPT_HTTPHEADER => array(
                        "Cache-Control: no-cache",
                        "Content-Type: application/json"
                      ),
                    ));

                    $response = curl_exec($curl);
                    $err = curl_error($curl);

                    curl_close($curl);

                    if ($err) {
                      $data_fix['dsp'] =  "cURL Error #:" . $err;
                    } else {
                      $data_fix['dsp'] =  $response;
                    }
                }

                $data['data']['result'] = $data_fix;
                break;    

            /* QUIZ SINGLE API */
            case 'quiz' :
                $cek = Quiz::model()->findByPk($id);
                $models = Quiz::model()->findByPk($id);

                if (Yii::app()->session['role'] == 2) {
                    $usr = User::model()->findByPk(Yii::app()->session['id_user']);
                    $kelas = $usr->class_id;
                }

                if (Yii::app()->session['role'] == 2 && $kelas != $cek->lesson->class->id) {
                    // Yii::app()->user->setFlash('success', 'Maaf Anda Tidak Punya Hak Akses');
                    // $this->redirect(array('index'));
                    $data_fix['limited_access'] = true;
                } else {
                    $data_fix['limited_access'] = false;
                }

                $criteria = new CDbCriteria();

                if (!empty($cek->question)) {
                    //$criteria->condition = 'teacher_id = :tid AND id NOT IN('.$cek->question.') and lesson_id = '.$cek->lesson_id;
                    $criteria->condition = 'teacher_id = :tid AND id NOT IN(' . $cek->question . ')';
                } else {
                    //$criteria->condition = 'teacher_id = :tid and lesson_id = '.$cek->lesson_id;
                    $criteria->condition = 'teacher_id = :tid';
                }

                $criteria->order = 'id DESC';
                $criteria->params = array(':tid' => $cek->created_by);

                $item_count = Questions::model()->count($criteria);

                $pages = new CPagination($item_count);
                $pages->setPageSize(20);
                $pages->applyLimit($criteria);
                $questions = Questions::model()->findAll($criteria);

                $total_question = Questions::model()->findAll(array('condition' => 'quiz_id = ' . $id));
                $student_quiz = StudentQuiz::model()->findAll(array('condition' => 'quiz_id = ' . $id));

                $kriteria = new CDbCriteria();
                $kriteria->select = 'max(score) as score, student_id';
                $kriteria->condition = 'quiz_id = ' . $id;
                $max = StudentQuiz::model()->findAll($kriteria);

                $minimal = new CDbCriteria();
                $minimal->select = 'min(score) as score, student_id';
                $minimal->condition = 'quiz_id = ' . $id;
                $min = StudentQuiz::model()->findAll($minimal);

                $avg = new CDbCriteria();
                $avg->select = 'avg(score) as score';
                $avg->condition = 'quiz_id = ' . $id;
                $rata = StudentQuiz::model()->findAll($avg);

                $data['success'] = 1;
                $data['message'] = 'Record(s) Found.';

                $data_fix['title'] = $models->title;
                $data_fix['lesson'] = $models->lesson->name;
                $data_fix['end_time'] = $models->end_time;
                $data_fix['repeat_quiz'] = $models->repeat_quiz;
                $data_fix['total_question'] = $models->total_question;
                if ($models->random == 1) {
                    $data_fix['random'] = "Ya";
                } else {
                    $data_fix['random'] = "Tidak";
                }

                if ($models->status == NULL) {
                    $data_fix['ShowStatus'] = false;
                    $data_fix['ShowInfo'] = 'Belum Ditampilkan';
                } elseif ($models->status == 1) {
                    $data_fix['ShowStatus'] = true;
                    $data_fix['ShowInfo'] = 'Sudah Ditampilkan';
                } else {
                    $data_fix['ShowStatus'] = false;
                    $data_fix['ShowInfo'] = 'Sudah Ditutup';
                }

                $cekQuiz = StudentQuiz::model()->findByAttributes(array('quiz_id' => $id, 'student_id' => Yii::app()->session['id_user']));

                if (!empty($cekQuiz)) {
                    if ($cekQuiz->attempt == $models->repeat_quiz) {
                        $data_fix['FinishStatus'] = true;
                    } else {
                        $data_fix['FinishStatus'] = false;
                    }
                } else {
                    $data_fix['FinishStatus'] = false;
                }

                $data['data']['result'] = $data_fix;
                break;

            case 'startQuiz' :
                $model = Quiz::model()->findByPk($id);
                $models = Quiz::model()->findByPk($id);

                if ($model->question == NULL) {
                    // Yii::app()->user->setFlash('success','Belum Ada Soal di Ulangan Ini');
                    // $this->redirect(array('view','id'=>$id));
                    $data_fix['isNullQuestion'] = true;
                } else {
                    $data_fix['isNullQuestion'] = false;
                }

                if (Yii::app()->user->YiiStudent && $model->status != 1) {
                    // Yii::app()->user->setFlash('success', 'Kuis Belum Dibuka Atau Sudah Selesai');
                    // $this->redirect(array('/quiz/view','id'=>$id));
                    //Yii::app()->request->urlReferrer;
                    $data_fix['isAvailableQuiz'] = false;
                } else {
                    $data_fix['isAvailableQuiz'] = true;
                }

                $student_quiz = new StudentQuiz;

                $criteria = new CDbCriteria();
                $criteria->condition = 'quiz_id is null and id IN (' . $model->question . ') ';
                if ($model->random == 1) {
                    $criteria->order = 'RAND()';
                } else {
                    $criteria->order = 'FIELD(id, ' . $model->question . ');';
                }
                //$criteria->params = array (':id'=>$id);

                $item_count = Questions::model()->count($criteria);

                $pages = new CPagination($item_count);
                $pages->setPageSize(100);
                $pages->applyLimit($criteria);

                $questions = Questions::model()->findAll($criteria);

                if (!empty($model->question)) {
                    $pertanyaan = explode(',', $model->question);
                } else {
                    $pertanyaan['question'] = NULL;
                }
                $cekQuiz = StudentQuiz::model()->findByAttributes(array('student_id' => Yii::app()->session['user_id'], 'quiz_id' => $model->id));
                $total_pertanyaan = count($pertanyaan);

                $data['success'] = 1;
                $data['message'] = 'Record(s) Found.';

                if (Yii::app()->session['role'] == 2 && !empty($cekQuiz)) {
                    $data_fix['cekQuiz_attempt'] = $cekQuiz->attempt;
                } else {
                    $data_fix['cekQuiz_attempt'] = null;
                }
                $data_fix['repeat_quiz'] = $models->repeat_quiz;

                $data_fix['title'] = $models->title;
                $data_fix['total_question'] = $total_pertanyaan;
                $data_fix['end_time'] = $models->end_time;
                $data_fix['passcode'] = $models->passcode;
                if ($models->random == 1) {
                    $data_fix['random'] = "Ya";
                } else {
                    $data_fix['random'] = "Tidak";
                }

                if ($models->status == NULL) {
                    $data_fix['ShowStatus'] = false;
                    $data_fix['ShowInfo'] = 'Belum Ditampilkan';
                } elseif ($models->status == 1) {
                    $data_fix['ShowStatus'] = true;
                    $data_fix['ShowInfo'] = 'Sudah Ditampilkan';
                } else {
                    $data_fix['ShowStatus'] = false;
                    $data_fix['ShowInfo'] = 'Sudah Ditutup';
                }

                $cekQuiz = StudentQuiz::model()->findByAttributes(array('quiz_id' => $id, 'student_id' => Yii::app()->session['id_user']));

                if (!empty($cekQuiz)) {
                    if ($cekQuiz->attempt == $models->repeat_quiz) {
                        $data_fix['FinishStatus'] = true;
                    } else {
                        $data_fix['FinishStatus'] = false;
                    }
                } else {
                    $data_fix['FinishStatus'] = false;
                }


                //risky code.
                $index = 1;
                foreach ($pertanyaan as $key) {
                    $detail = Questions::model()->findByPk($key);
                    $path_image = Clases::model()->path_image($key);
                    if (!empty($detail)) {

                        $term['id'] = $detail->id;
                        $term['text'] = $detail->text;
                        $term['type'] = $detail->type;
                        if ($detail->type == NULL) {
                            $term['isNullType'] = true;
                        } else {
                            $term['isNullType'] = false;
                        }

                        $term['file'] = $detail->file;
                        if ($detail->file == NULL) {
                            $term['isNullFile'] = true;
                        } else {
                            $term['isNullFile'] = false;
                        }

                        $term['choices'] = json_decode($detail->choices);
                        $term['choices_files'] = json_decode($detail->choices_files);
                        $term['path_image'] = $path_image;
                        $term['unique'] = 'step' . $index;
                        $index++;
                        $data_fix['term_detail'][] = $term;
                    }

                    //$data_fix['end_time'][] = $path_image;
                }

                $data['data']['result'] = $data_fix;

                break;


            case 'tugas':
                $model = new StudentAssignment;
                $models = $model->findByPk($id);


                $data['success'] = 1;
                $data['message'] = 'Record(s) Found.';

                $data_fix['id'] = $models->id;
                $data_fix['student_id'] = $models->student_id;
                $data_fix['nama_siswa'] = ucfirst($models->student->display_name);
                $data_fix['nama_tugas'] = ucfirst($models->teacher_assign->title);
                $data_fix['due_date'] = date('d M Y G:i:s', strtotime($models->teacher_assign->due_date));
                $data_fix['dikumpulkan'] = date('d M Y G:i:s', strtotime($models->created_at));
                $data_fix['score'] = $models->score;
                $data_fix['content'] = $models->content;
                $data_fix['file'] = $models->file;
                if (!empty($models->file))
                    $data_fix['isExistFile'] = true;
                else
                    $data_fix['isExistFile'] = false;
                if (!empty($models->teacher_assign->due_date > $models->created_at)) {
                    $data_fix['isOnTime'] = 'Ya';
                } else {
                    $data_fix['isOnTime'] = 'Tidak';
                }

                if (Yii::app()->session['role'] == 1) {
                    $data_fix['isStudent'] = false;
                } else {
                    $data_fix['isStudent'] = true;
                }

                $data['data']['result'] = $data_fix;


                break;


            default:
                //$this->_sendResponse(501, sprintf('Error: Mode <b>list</b> is not implemented for model <b>%s</b>',$_GET['model']) );
                $data['success'] = 0;
                $data['message'] = 'ERROR : Invalid Parameter';
                $data['data'] = NULL;
                $json = json_encode($data, JSON_PRETTY_PRINT);
                header('Content-Type: application/x-javascript');
                echo $json;
                exit();
        }

        if ($status == false) {
            //$this->_sendResponse(501, sprintf('No items where found for model <b>%s</b>', $_GET['model']) );
            $data['success'] = 0;
            $data['message'] = 'ERROR : Missing or Invalid Parameter !';
            $data['data'] = NULL;
            $json = json_encode($data, JSON_PRETTY_PRINT);
            header('Content-Type: application/x-javascript');
            header("Access-Control-Allow-Origin: *");
            echo $json;
            exit();
        }

        if ($models == NULL) {
            $data['success'] = 0;
            $data['message'] = 'ERROR : No Data Found';
            $data['data'] = NULL;
            $json = json_encode($data, JSON_PRETTY_PRINT);
            header('Content-Type: application/x-javascript');
            header("Access-Control-Allow-Origin: *");
            echo $json;
            exit();
        } else if (is_null($models)) {
            $data['success'] = 0;
            $data['message'] = 'ERROR : No Data Found';
            $data['data'] = NULL;
            $json = json_encode($data, JSON_PRETTY_PRINT);
            header('Content-Type: application/x-javascript');
            header("Access-Control-Allow-Origin: *");
            echo $json;
            exit();
        }

        //code when success...
        $json = json_encode($data, JSON_PRETTY_PRINT);
        header('Content-Type: application/json');
        header("Access-Control-Allow-Origin: *");
        echo $json;
    }

    /*
     * Creates a new item
     *
     * @access public
     * @return void
     */

    public function actionPost() {

        switch ($_GET['model']) {
            case 'login':
                $model = new User;
                $post_data = json_decode(urldecode(file_get_contents("php://input")));
                //print_r($post_data);exit;

                if (isset($_GET['type'])) {
                    if ($_GET['type'] == 'android') {
                        $username = $_POST['username'];
                        $password = $_POST['password'];
                    } elseif ($_GET['type'] == 'angular') {
                        $username = $post_data->username;
                        $password = $post_data->password;
                    }
                }

                //echo $username;exit;

                $ph = new PasswordHash(Yii::app()->params['phpass']['iteration_count_log2'], Yii::app()->params['phpass']['portable_hashes']);

                $criteria = new CDbCriteria;
                $criteria->condition = 'username=:username OR email=:username';
                $criteria->params = array(':username' => $username);

                $user = $model->findAll($criteria);

                if (!empty($user)) {
                    foreach ($user as $data) {
                        if ($ph->CheckPassword($password, $data->encrypted_password)) {
                            $role = $data->role_id;

                            $token = new OauthToken;

                            $tokens = $token->findAll("id_user = :id", array(':id' => $data->id));
                            if (empty($tokens)) {
                                $token->id_user = $data->id;
                                $token->token = $this->random(50);

                                date_default_timezone_set('Asia/Jakarta');
                                $datetime = new DateTime();
                                $datetime->modify('+180 day');
                                $token->expired_date = $datetime->format('Y-m-d H:i:s');
                                $token->save(false);

                                $json['tag'] = 'login';
                                $json['msg'] = 'Login Success ( New Token )';
                                $json['error'] = 'FALSE';
                                $json['uid'] = $data->id;
                                $json['user']['name'] = $data->display_name;
                                $json['user']['email'] = $data->email;
                                $json['user']['created_at'] = $data->created_at;
                                $json['user']['updated_at'] = $data->updated_at;

                                if (isset($data->child_id)) {
                                    $json['user']['child_id'] = $data->child_id;
                                } else {
                                    $json['user']['child_id'] = null;
                                }

                                $json['role'] = $role;
                                $json['token'] = $token->token;
                            } else {
                                date_default_timezone_set('Asia/Jakarta');
                                $today = new DateTime();
                                foreach ($tokens as $get) {
                                    $expired_date = new DateTime($get->expired_date);
                                    $update_token = $token->findByPk($get->id);

                                    if ($today >= $expired_date) {
                                        $old_token = $update_token->token;
                                        $update_token->token = $this->random(25);
                                        $update_token->last_token = $old_token;
                                        $today->modify('+1 day');
                                        $update_token->expired_date = $today->format('Y-m-d H:i:s');
                                        $update_token->save(false);

                                        $json['tag'] = 'login';
                                        $json['msg'] = 'Login Success. ( Change to New Token )';
                                        $json['error'] = 'FALSE';
                                        $json['uid'] = $data->id;
                                        $json['user']['name'] = $data->display_name;
                                        $json['user']['email'] = $data->email;
                                        $json['user']['created_at'] = $data->created_at;
                                        $json['user']['updated_at'] = $data->updated_at;

                                        if (isset($data->child_id)) {
                                            $json['user']['child_id'] = $data->child_id;
                                        } else {
                                            $json['user']['child_id'] = null;
                                        }

                                        $json['role'] = $role;
                                        $json['token'] = $update_token->token;
                                    } else {
                                        $json['tag'] = 'login';
                                        $json['msg'] = 'Login Success. (Same Token)';
                                        $json['error'] = 'FALSE';
                                        $json['uid'] = $data->id;
                                        $json['user']['name'] = $data->display_name;
                                        $json['user']['email'] = $data->email;
                                        $json['user']['created_at'] = $data->created_at;
                                        $json['user']['updated_at'] = $data->updated_at;

                                        if (isset($data->child_id)) {
                                            $children = explode(",", $data->child_id);
                                            $json['user']['children'] = array();
                                            if (count($children) > 1) {
                                                foreach ($children as $key => $value) {
                                                    $data_anak = array();
                                                    $child  = User::model()->findByPk($value);
                                                    $data_anak['child_id'] = $child->id;
                                                    $data_anak['child_name'] = $child->display_name;
                                                    array_push($json['user']['children'], $data_anak);
                                                    // $json['user']['children'] = $data_anak;
                                                    // $json['user']['children']['child_class'] = $child->kelas->name;
                                                }
                                            }else{
                                                $data_anak = array();
                                                $child  = User::model()->findByPk($data->child_id);
                                                $data_anak['child_id'] = $child->id;
                                                $data_anak['child_name'] = $child->display_name;
                                                array_push($json['user']['children'], $data_anak);
                                                // $json['user']['child_id'] = $data->child_id;    
                                            }
                                        } else {
                                            $json['user']['child_id'] = null;
                                        }

                                        $json['role'] = $role;
                                        $json['token'] = $get->token;
                                    }
                                }
                            }
                        } else {
                            $json['error'] = 'TRUE';
                            $json['error_msg'] = 'Username/Password salah';
                            $json['token'] = NULL;
                        }
                    }
                } else {
                    $json['error'] = 'TRUE';
                    $json['error_msg'] = 'Username belum terdaftar';
                    $json['token'] = NULL;
                }

                header("Access-Control-Allow-Origin: *");
                echo json_encode($json);
                break;

            case 'register':
                $model = new User;
                $post_data = json_decode(urldecode(file_get_contents("php://input")));
                //print_r($post_data);exit;

                if (isset($_GET['type'])) {
                    if ($_GET['type'] == 'android') {
                        $username = $_POST['username'];
                        $display_name = $_POST['display_name'];
                        $email = $_POST['email'];
                        $role_id = "1";
                        $password = $_POST['password'];
                        $password2 = $_POST['password2'];
                    } elseif ($_GET['type'] == 'angular') {
                        $username = $post_data->username;
                        $password = $post_data->password;
                    }
                }

                //echo $username;exit;
                // $ph=new PasswordHash(Yii::app()->params['phpass']['iteration_count_log2'], Yii::app()->params['phpass']['portable_hashes']);

                $criteria = new CDbCriteria;
                $criteria->condition = 'username=:username OR email=:username';
                $criteria->params = array(':username' => $username);

                $user = $model->findAll($criteria);

                if (!empty($user)) {

                    $json['error'] = 'TRUE';
                    $json['error_msg'] = 'Username/email sudah terdaftar';
                    // $json['token'] = NULL;
                } else {

                    $model->username = $username;
                    $model->email = $email;
                    $model->display_name = $display_name;
                    $model->role_id = $role_id;
                    $model->encrypted_password = $password;
                    $model->password2 = $password2;
                    if ($model->save()) {

                        $token = new OauthToken;
                        $token->id_user = $model->id;
                        $token->token = $this->random(50);

                        date_default_timezone_set('Asia/Jakarta');
                        $datetime = new DateTime();
                        $datetime->modify('+180 day');
                        $token->expired_date = $datetime->format('Y-m-d H:i:s');
                        $token->save(false);

                        $json['success'] = 1;
                        $json['tag'] = 'register';
                        $json['msg'] = 'Registrasi Success ( New Token )';
                        $json['error'] = 'FALSE';
                        $json['uid'] = $model->id;
                        $json['user']['name'] = $model->display_name;
                        $json['user']['email'] = $model->email;
                        $json['user']['created_at'] = $model->created_at;
                        $json['user']['updated_at'] = $model->updated_at;
                        $json['role'] = $model->role_id;
                        $json['token'] = $token->token;
                    } else {
                        $json['error'] = 'TRUE';
                        $json['error_msg'] = 'Registrasi Gagal.';
                        // $json['model'] = ;
                    }




                    // $json = json_encode($data,JSON_PRETTY_PRINT);
                    // $json['error'] = 'TRUE';
                    // $json['error_msg'] = 'Username belum terdaftar';
                    // $json['token'] = NULL;
                }

                header("Access-Control-Allow-Origin: *");
                echo json_encode($json);
                break;


            case 'tambah_pelajaran':
                $this->checkToken();
                $model = new Lesson;
                $optSemester = Option::model()->findByAttributes(array('key_config' => 'semester'))->value;
                $optTahunAjaran = Option::model()->findByAttributes(array('key_config' => 'tahun_ajaran'))->value;

                $post_data = json_decode(urldecode(file_get_contents("php://input")));
                //print_r($post_data);exit;

                if (isset($_GET['type'])) {
                    if ($_GET['type'] == 'android') {
                        // $username = $_POST['username'];
                        // $display_name = $_POST['display_name'];
                        // $email = $_POST['email'];
                        // $role_id = "1";
                        // $password = $_POST['password'];	
                        // $password2 = $_POST['password2'];	
                    } elseif ($_GET['type'] == 'angular') {
                        // $username = $post_data->username;
                        // $password = $post_data->password;
                    }
                }

                if (isset($_POST['list_id'])) {
                    $model->user_id = "(ID:" . Yii::app()->session['id_user'] . ")";
                    $model->list_id = $_POST['list_id'];
                    $model->class_id = $_POST['class_id'];

                    $cekNama = LessonList::model()->findByPk($model->list_id);
                    $model->name = $cekNama->name;
                    $model->kelompok = $cekNama->group;

                    $model->semester = $optSemester;
                    $model->year = $optTahunAjaran;
                    if ($model->save()) {

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

                        $json['success'] = 1;
                        $json['tag'] = 'addLesson';
                        $json['msg'] = 'Tambah Pelajaran Berhasil';
                        $json['error'] = 'FALSE';
                    } else {
                        $json['error'] = 'TRUE';
                        $json['error_msg'] = 'Tambah Pelajaran Gagal';
                    }
                }

                echo json_encode($json);
                break;

            case 'register_siswa':
                $model = new User;
                $this->checkToken();
                $post_data = json_decode(file_get_contents("php://input"));


                if (isset($_GET['type'])) {
                    if ($_GET['type'] == 'android') {
                        // $username = $_POST['username'];
                        // $display_name = $_POST['display_name'];
                        // $email = $_POST['email'];
                        // $role_id = "1";
                        // $password = $_POST['password'];	
                        // $password2 = $_POST['password2'];
                        $class_id = $post_data->class_id;
                    } elseif ($_GET['type'] == 'angular') {
                        // $username = $post_data->username;
                        // $password = $post_data->password;
                    }
                }


                $prefix = Yii::app()->params['tablePrefix'];
                $length = 5;
                $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $charactersLength = strlen($characters);

                $sukses = 0;

                foreach ($post_data->siswa as $siswa) {
                    // foreach ($result as $key => $value) {
                    $randomString = '';

                    for ($i = 0; $i < $length; $i++) {
                        $randomString .= $characters[rand(0, $charactersLength - 1)];
                    }

                    $nis = $siswa->nis;
                    $nama = $siswa->name;
                    $email = $randomString . "@mail.id";
                    $password = $randomString;
                    $role = 2;
                    $ph = new PasswordHash(Yii::app()->params['phpass']['iteration_count_log2'], Yii::app()->params['phpass']['portable_hashes']);
                    $passwd = $ph->HashPassword($password);


                    $cekUE = User::model()->findAll(array("condition" => "username = '$nis' or email = '$email'"));

                    if (!empty($cekUE)) {

                        //do nothing 
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
                            $sukses++;
                        }
                    }
                }

                if ($sukses > 0) {
                    // Yii::app()->user->setFlash('success','Siswa Berhasil Didaftarkan!');
                    // $this->redirect(array('view','id'=>$id));
                    $json['success'] = 1;
                    $json['countSuccess'] = $sukses;
                    $json['tag'] = 'register';
                    $json['msg'] = 'Siswa Berhasil Didaftarkan';
                } else {
                    // Yii::app()->user->setFlash('error','Siswa Gagal Didaftarkan!');
                    // $this->redirect(array('view','id'=>$id));
                    $json['error'] = 'TRUE';
                    $json['error_msg'] = 'Siswa Gagal Didaftarkan.';
                }







                echo json_encode($json);
                break;



            case 'update_user_spp':
                        // $this->checkToken();
                        $model  = User::model()->findByPk($_POST['user_id']);
                        $modelProfile=UserProfile::model()->findByAttributes(array('user_id'=>$_POST['user_id']));
                                               
                        if (isset($model->spp) && isset($_POST['transaction_id'])) {
                           $arr_spp = explode(',', $model->spp);
                           array_push($arr_spp, $_POST['transaction_id']);
                           $model->spp = implode(",", $arr_spp);

                        }
                        
                    if(empty($model))
                    {
                             $json['error'] = 'TRUE';
                             $json['error_msg'] = 'User tidak ditemukan.';
                             // $json['token'] = NULL;
                    }
                    else
                    {
                        
                        if($model->save()){
                            $json['success'] = true;
                            $json['msg'] = 'SPP Berhasil Diupdate';
                        } else {
                            $json['success'] = false;
                            $json['msg'] = 'Update gagal';
                            // $json['model'] = ;
                        }
                                  
                    }
                        
                    echo json_encode($json);
            break;    

            case 'class_detail':
                $this->checkToken();
                $model = new ClassDetail;
                $post_data = json_decode(urldecode(file_get_contents("php://input")));
                //print_r($post_data);exit;

                if (isset($_GET['type'])) {
                    if ($_GET['type'] == 'android') {
                        $name = $_POST['name'];
                        $class_id = $_POST['level'];
                    } elseif ($_GET['type'] == 'angular') {
                        $username = $post_data->username;
                        $password = $post_data->password;
                    }
                }



                $model->name = $name;
                $model->class_id = $class_id;
                $model->teacher_id = Yii::app()->session['id_user'];

                if ($model->save()) {

                    $json['success'] = 1;
                    $json['tag'] = 'addClass';
                    $json['id_kelas'] = $model->id;
                    $json['msg'] = 'Tambah Kelas Success ( New Token )';
                } else {
                    $json['error'] = 'TRUE';
                    $json['error_msg'] = 'Tambah Kelas Gagal.';
                    // $json['model'] = ;
                }


                echo json_encode($json);
                break;

            //post ASSIGNMENT file	
            case 'student_assignment_file':
                //$studentAssignment=new StudentAssignment;
                //echo $_FILES['file']['tmp_name'];exit;
                $notif = new Notification;
                $activity = new Activities;
                $user = User::model()->findByPk(Yii::app()->session['id_user']);
                $student_id = Yii::app()->session['id_user'];
                $studentAssignment = new StudentAssignment;
                //$pk_id = (int)Yii::app()->session['temp_id'] + 1;
                //print_r(Yii::app()->session['form_input']);exit;
                //print_r($studentAssignment);exit;
                $studentAssignment->assignment_id = Yii::app()->session['form_input']['assignment_id'];
                $studentAssignment->content = Yii::app()->session['form_input']['content'];
                $studentAssignment->file = $_FILES['file']['name'];
                ;
                $studentAssignment->student_id = Yii::app()->session['form_input']['student_id'];
                $studentAssignment->status = 1;
                if ($studentAssignment->save(false)) {
                    if (!file_exists(Yii::app()->basePath . '/../images/students/' . $student_id)) {
                        mkdir(Yii::app()->basePath . '/../images/students/' . $student_id, 0775, true);
                    }

                    if (!empty($_FILES['file']['name'])) {
                        //$uploadedFile->saveAs(Yii::app()->basePath.'/../images/students/'.$student_id.'/'.$_FILES['file']['name']);
                        move_uploaded_file($_FILES['file']['tmp_name'], Yii::app()->basePath . '/../images/students/' . $student_id . '/' . $_FILES['file']['name']);
                    }
                }

                break;

            //post form data
            case 'student_assignment_form':
                $post_data = json_decode(urldecode(file_get_contents("php://input")));
                //print_r($post_data);exit;
                // $studentAssignment=new StudentAssignment;
                // $notif=new Notification;
                // $activity=new Activities;
                // $model=Assignment::model()->findByPk($post_data->assignment_id);		
                // $user=User::model()->findByPk(Yii::app()->session['id_user']);
                // $user_kelas = $user->class_id;
                // $mapel=Lesson::model()->findByAttributes(array('id'=>$model->lesson_id));
                // $kelas=Clases::model()->findByAttributes(array('id'=>$mapel->class_id));
                // if(isset($_POST['save'])){
                // $studentAssignment->status=null;
                // }elseif(isset($_POST['upload'])){
                // $studentAssignment->status=1;
                // }
                // $studentAssignment->student_id= Yii::app()->session['id_user'];
                // $studentAssignment->assignment_id= $post_data->assignment_id;
                // $studentAssignment->content = $post_data->jawaban;	
                //$studentAssignment->status= null;
                // $studentAssignment->save(false);
                // Yii::app()->session['temp_id'] = $studentAssignment->getPrimaryKey();

                Yii::app()->session['form_input'] = array(
                            'student_id' => Yii::app()->session['id_user'],
                            'assignment_id' => $post_data->assignment_id,
                            'content' => $post_data->jawaban,
                            'state' => $post_data->state
                );

                //Yii::app()->session['form_input']['student_id'] = Yii::app()->session['id_user'];
                // Yii::app()->session['form_input']['assignment_id'] = $post_data->assignment_id;
                // Yii::app()->session['form_input']['content'] = $post_data->jawaban;
                // Yii::app()->session['form_input']['state'] = $post_data->state;

                break;

            //post StudentQuiz
            case 'studentquiz' :

                $this->checkToken();
                $post_data = json_decode(file_get_contents("php://input"));
                //print_r($post_data);
                $temp = null;
                $temp2 = null;

                if (!empty($post_data->jawaban)) {
                    foreach ($post_data->jawaban as $jawaban) {
                        // $jawaban = explode('|',$row);
                        $temp['id_question'] = $jawaban->id_question;
                        $temp['pilihan'] = $jawaban->pilihan;
                        $data_jawaban[] = $temp;

                        if (!empty($temp['pilihan'])) {
                            $temp2[$temp['id_question']] = $temp['pilihan'];
                        } else {
                            $temp2 = null;
                        }
                    }
                }

                $activity = new Activities;
                $usr = User::model()->findByPk(Yii::app()->session['id_user']);
                $notif = new Notification;
                $cekUsr = StudentQuiz::model()->findByAttributes(array('quiz_id' => $post_data->quizId, 'student_id' => Yii::app()->session['id_user']));

                if (!$cekUsr) {

                    $student_quiz = new StudentQuiz;

                    //$student_quiz->attributes=$_POST['StudentQuiz'];
                    $student_quiz->quiz_id = $post_data->quizId;
                    $student_quiz->student_id = Yii::app()->session['id_user'];
                    $qid = $student_quiz->quiz_id;
                    $quiz = Quiz::model()->findByPk($qid);

                    $total_pertanyaan = $quiz->total_question;
                    $benar = 0;
                    $salah = 0;
                    $kosong = 0;
                    $total_jawab = NULL;
                    $jawaban = NULL;

                    if (!empty($data_jawaban)) {
                        foreach ($data_jawaban as $row) {
                            $cekJawaban = Questions::model()->findByAttributes(array('id' => $row['id_question']));
                            if (!empty($cekJawaban)) {

                                // $soal->key_answer = str_replace('\r', '', $soal->key_answer) ;
                                $cekJawaban->key_answer = preg_replace("/\r|\n/", "", $cekJawaban->key_answer);
                                // $value = str_replace('\r', '', $value);
                                $row = preg_replace("/\r|\n/", "", $row);


                                if (strtolower($cekJawaban->key_answer) == strtolower($row['pilihan'])) {
                                    $benar = $benar + 1;
                                } else {
                                    $salah = $salah + 1;
                                }
                            } else {
                                $kosong = $total_pertanyaan - ($benar + $salah);
                            }
                        }
                        $total_jawab = count($data_jawaban);
                        $kosong = $quiz->total_question - $total_jawab;
                    } else {
                        $kosong = $total_pertanyaan;
                    }
                    $score = round(($benar / $total_pertanyaan) * 100);

                    $student_quiz->score = $score;
                    $student_quiz->right_answer = $benar;
                    $student_quiz->wrong_answer = $salah;
                    $student_quiz->unanswered = $kosong;

                    $student_quiz->attempt = $student_quiz->attempt + 1;

                    if (($temp2 != 'null') && !empty($temp2)) {

                        $student_quiz->student_answer = json_encode($temp2);

                        if (Yii::app()->session['role'] == 2) {
                            if ($student_quiz->save(false)) {
                                $activity->activity_type = 'Mengerjakan Kuis';
                                $activity->content = 'Siswa ' . $usr->display_name . ' Mengerjakan Ulangan ' . $quiz->title;
                                $activity->created_by = Yii::app()->session['id_user'];
                                $activity->save(false);

                                $notif->content = "Siswa " . $usr->display_name . " Selesai Mengerjakan Ulangan " . $quiz->title;
                                $notif->user_id = Yii::app()->session['id_user'];
                                $notif->relation_id = $student_quiz->id;
                                $notif->user_id_to = $quiz->created_by;
                                $notif->tipe = "submit-quiz";
                                $notif->save(false);
                                $data['success'] = 1;
                                $data['message'] = 'Submit Berhasil.';
                                $json = json_encode($data, JSON_PRETTY_PRINT);
                                header('Content-Type: application/json');
                                echo $json;
                            } else {
                                $data['success'] = 0;
                                $data['message'] = 'Submit Gagal.';
                                $json = json_encode($data, JSON_PRETTY_PRINT);
                                header('Content-Type: application/json');
                                echo $json;
                            }
                        } else {
                            $data['success'] = 0;
                            $data['message'] = 'Submit Gagal. Hanya siswa yang boleh submit.';
                            $json = json_encode($data, JSON_PRETTY_PRINT);
                            header('Content-Type: application/json');
                            echo $json;
                        }
                    } else {
                        $data['success'] = 0;
                        $data['message'] = 'Submit Gagal.';
                        $json = json_encode($data, JSON_PRETTY_PRINT);
                        header('Content-Type: application/json');
                        echo $json;
                    }
                } else {
                    $data['success'] = 0;
                    $data['message'] = 'Submit Gagal. User telah submit sebelumnya';
                    $json = json_encode($data, JSON_PRETTY_PRINT);
                    header('Content-Type: application/json');
                    echo $json;
                }

                break;

            //post absesni

            case 'absensi' :

                $this->checkToken();
                $post_data = json_decode(file_get_contents("php://input"));
                //print_r($post_data);
                $temp = null;
                $temp2 = null;
                $berhasil = 0;
                $data_message = "";

                if (!empty($post_data->absensi)) {
                    foreach ($post_data->absensi as $absensi) {


                        $activity = new Activities;
                        $usr = User::model()->findByPk(Yii::app()->session['id_user']);
                        $notif = new Notification;
                        $cekUsr = Absensi::model()->findByAttributes(array('user_id' => $absensi->user_id, 'id_lesson' => $absensi->id_lesson, 'pertemuan_ke' => $absensi->pertemuan_ke));

                        if (!$cekUsr) {

                            $data_absensi = new Absensi;

                            $data_absensi->user_id = $absensi->user_id;
                            $data_absensi->id_lesson = $absensi->id_lesson;
                            $data_absensi->absen = $absensi->absen;
                            $data_absensi->pertemuan_ke = $absensi->pertemuan_ke;
                            $data_absensi->ket = $absensi->ket;

                            if (!isset($absensi->created_at)) {
                                $absensi->created_at = "2018-01-01 22:21:39 ";
                            }

                            $data_absensi->created_at = $absensi->created_at;

                            if (Yii::app()->session['role'] != 2) {
                                if ($data_absensi->save(false)) {
                                    $berhasil++;
                                } else {
                                    $data_message = 'Submit Gagal.';
                                }
                            } else {
                                $berhasil = 0;
                                $data_message = 'Submit Gagal. Hanya guru yang boleh submit absensi.';
                            }
                        } else {

                            $cekUsr->user_id = $absensi->user_id;
                            $cekUsr->id_lesson = $absensi->id_lesson;
                            $cekUsr->absen = $absensi->absen;
                            $cekUsr->pertemuan_ke = $absensi->pertemuan_ke;
                            $cekUsr->ket = $absensi->ket;
                            $cekUsr->sync_status = "2";

                            if (!isset($absensi->created_at)) {
                                $absensi->created_at = "2018-01-01 22:21:39 ";
                            }
                            
                            $cekUsr->created_at = $absensi->created_at;


                            if (Yii::app()->session['role'] != 2) {
                                if ($cekUsr->save(false)) {
                                    $berhasil++;
                                } else {
                                    $data_message = 'Submit Gagal.';
                                }
                            } else {
                                $berhasil = 0;
                                $data_message = 'Submit Gagal. Hanya guru yang boleh submit absensi.';
                            }
                        }
                    }
                }

                if ($berhasil != 0) {
                    $data['success'] = 1;
                    $data['message'] = 'Submit ' . $berhasil . ' data Berhasil.';
                    $json = json_encode($data, JSON_PRETTY_PRINT);
                    header('Content-Type: application/json');
                    echo $json;
                } else {
                    $data['success'] = 0;
                    $data['message'] = $data_message;
                    $json = json_encode($data, JSON_PRETTY_PRINT);
                    header('Content-Type: application/json');
                    echo $json;
                }


                break;


            case 'absensi_harian' :

                $this->checkToken();
                $post_data = json_decode(file_get_contents("php://input"));
                //print_r($post_data);
                $temp = null;
                $temp2 = null;
                $berhasil = 0;
                $data_message = "";

                if (!empty($post_data->absensi_harian)) {
                    foreach ($post_data->absensi_harian as $absensi) {


                        $activity = new Activities;
                        $usr = User::model()->findByPk(Yii::app()->session['id_user']);
                        $notif = new Notification;
                        $cekUsr = AbsensiHarian::model()->findByAttributes(array('user_id' => $absensi->user_id, 'tgl' => $absensi->tgl));

                        if (!$cekUsr) {

                            $data_absensi = new AbsensiHarian;

                            $data_absensi->user_id = $absensi->user_id;
                            $data_absensi->absen = $absensi->absen;
                            $data_absensi->tgl = $absensi->tgl;
                            $data_absensi->ket = $absensi->ket;

                            if (Yii::app()->session['role'] != 2) {
                                if ($data_absensi->save(false)) {
                                    $berhasil++;
                                } else {
                                    $data_message = 'Submit Gagal.';
                                }
                            } else {
                                $berhasil = 0;
                                $data_message = 'Submit Gagal. Hanya guru yang boleh submit absensi.';
                            }
                        } else {
                            $cekUsr->user_id = $absensi->user_id;
                            $cekUsr->absen = $absensi->absen;
                            $cekUsr->tgl = $absensi->tgl;
                            $cekUsr->ket = $absensi->ket;

                            if (Yii::app()->session['role'] != 2) {
                                if ($cekUsr->save(false)) {
                                    $berhasil++;
                                } else {
                                    $data_message = 'Submit Gagal.';
                                }
                            } else {
                                $berhasil = 0;
                                $data_message = 'Submit Gagal. Hanya guru yang boleh submit absensi.';
                            }
                        }
                    }
                }

                if ($berhasil != 0) {
                    $data['success'] = 1;
                    $data['message'] = 'Submit ' . $berhasil . ' data Berhasil.';
                    $json = json_encode($data, JSON_PRETTY_PRINT);
                    header('Content-Type: application/json');
                    echo $json;
                } else {
                    $data['success'] = 0;
                    $data['message'] = $data_message;
                    $json = json_encode($data, JSON_PRETTY_PRINT);
                    header('Content-Type: application/json');
                    echo $json;
                }


                break;

            //end post absesni	
            //post final_mark


            case 'final_mark' :

                $this->checkToken();
                $post_data = json_decode(file_get_contents("php://input"));
                //print_r($post_data);
                $temp = null;
                $temp2 = null;
                $berhasil = 0;
                $data_message = "";

                if (!empty($post_data->nilai)) {
                    foreach ($post_data->nilai as $nilai) {


                        $activity = new Activities;
                        $usr = User::model()->findByPk(Yii::app()->session['id_user']);
                        $notif = new Notification;
                        $cekUsr = FinalMark::model()->findByAttributes(array('user_id' => $nilai->user_id));

                        if (!$cekUsr) {

                            $data_nilai = new FinalMark;

                            $data_nilai->user_id = $nilai->user_id;
                            $data_nilai->lesson_id = $nilai->id_lesson;
                            $data_nilai->tipe = $nilai->tipe;
                            $data_nilai->semester = $nilai->semester;
                            $data_nilai->tahun_ajaran = $nilai->tahun_ajaran;
                            $data_nilai->nilai = $nilai->nilai;
                            $data_nilai->created_by = $nilai->created_by;
                            $data_nilai->created_at = $nilai->created_at;

                            if (Yii::app()->session['role'] != 2) {
                                if ($data_nilai->save(false)) {
                                    $berhasil++;
                                    $data_message = 'Submit Gagal.';
                                } else {
                                    $data_message = 'Submit Gagal masuk database.';
                                }
                            } else {
                                $berhasil = 0;
                                $data_message = 'Submit Gagal. Hanya guru yang boleh submit absensi.';
                            }
                        } else {
                            $data_message = 'Submit Gagal. User telah submit sebelumnya';
                        }
                    }
                }

                if ($berhasil != 0) {
                    $data['success'] = 1;
                    $data['message'] = 'Submit ' . $berhasil . ' data Berhasil.';
                    $json = json_encode($data, JSON_PRETTY_PRINT);
                    header('Content-Type: application/json');
                    echo $json;
                } else {
                    $data['success'] = 0;
                    $data['message'] = $data_message;
                    $json = json_encode($data, JSON_PRETTY_PRINT);
                    header('Content-Type: application/json');
                    echo $json;
                }


                break;



            //end post final matk
            //post QUIZ
            case 'quiz':
                $model = new Quiz;
                $activity = new Activities;

                if (isset($_POST['save'])) {
                    $model->status = null;
                } elseif (isset($_POST['show'])) {
                    $model->status = 1;
                } elseif (isset($_POST['close'])) {
                    $model->status = 2;
                }
                if (empty($model->repeat_quiz)) {
                    $model->repeat_quiz = 1;
                }

                // Try to assign POST values to attributes
                foreach ($_POST as $var => $value) {
                    // Does the model have this attribute?
                    if ($model->hasAttribute($var)) {
                        $model->$var = $value;
                    } else {
                        // No, raise an error
                        $this->_sendResponse(500, sprintf('Parameter <b>%s</b> is not allowed for model <b>%s</b>', $var, $_GET['model']));
                    }
                }
                if ($model->save()) {
                    $activity->activity_type = 'Buat Kuis Baru';
                    $activity->content = 'Guru' . $usr->display_name . ' Membuat Kuis Baru';
                    $activity->created_by = Yii::app()->user->id;
                    $activity->save();
                    $this->_sendResponse(200);
                } else {
                    // Errors occurred
                    $msg = "<h1>Error</h1>";
                    $msg .= sprintf("Couldn't create model <b>%s</b>", $_GET['model']);
                    $msg .= "<ul>";
                    foreach ($model->errors as $attribute => $attr_errors) {
                        $msg .= "<li>Attribute: $attribute</li>";
                        $msg .= "<ul>";
                        foreach ($attr_errors as $attr_error) {
                            $msg .= "<li>$attr_error</li>";
                        }
                        $msg .= "</ul>";
                    }
                    $msg .= "</ul>";
                    $this->_sendResponse(500, $msg);
                }
                break;

            case 'chapter' :
                $model = new Chapters;
                $model2 = new ChapterFiles;
                $notif = new Notification;
                $activity = new Activities;

                foreach ($_POST as $var => $value) {
                    // Does the model have this attribute?
                    if ($model->hasAttribute($var)) {
                        $model->$var = $value;
                    } else {
                        // No, raise an error
                        $this->_sendResponse(500, sprintf('Parameter <b>%s</b> is not allowed for model <b>%s</b>', $var, $_GET['model']));
                    }
                }

                $model->created_by = Yii::app()->user->id;

                if ($model->chapter_type == 1) {
                    $model2->scenario = 'video';
                } elseif ($model->chapter_type == 2) {
                    $model2->scenario = 'gambar';
                } elseif ($model->chapter_type == 3) {
                    $model2->scenario = 'dokumen';
                }
                if ($model->save()) {

                    $model2->id_chapter = $model->id;
                    //$uploadedFile = CUploadedFile::getInstance($model2, 'file');

                    if (!empty($uploadedFile)) {
                        $model2->file = $uploadedFile;
                        $ext = pathinfo($uploadedFile, PATHINFO_EXTENSION);
                        $model2->type = $ext;
                    }

                    $model2->created_by = Yii::app()->user->id;
                    if ($model2->save()) {
                        $kelas = Lesson::model()->findByPk($model->id_lesson);
                        $notif->content = "Guru " . $name . " Menambah Materi Baru";
                        $notif->user_id = Yii::app()->user->id;
                        $notif->class_id_to = $kelas->class_id;
                        $notif->tipe = 'add-chapter';
                        $notif->relation_id = $model->id;
                        $notif->save();

                        $activity->activity_type = "Upload Materi " . $model->title . " " . $kelas->name;
                        $activity->created_by = Yii::app()->user->id;
                        $activity->save();

                        if (!file_exists(Yii::app()->basePath . '/../images/chapters/' . $model->id_lesson)) {
                            mkdir(Yii::app()->basePath . '/../images/chapters/' . $model->id_lesson, 0775, true);
                        }

                        if (!empty($uploadedFile)) {
                            $uploadedFile->saveAs(Yii::app()->basePath . '/../images/chapters/' . $model->id_lesson . '/' . $uploadedFile);
                        }
                    }
                }
                break;


            default:
                $this->_sendResponse(501, sprintf('Mode <b>create</b> is not implemented for model <b>%s</b>', $_GET['model']));
                exit;
        }
        // echo '<pre>';
        // print_r($_POST);
        // print_r($_FILES);
        // echo '</pre>';
        // exit();
        //echo 'sip';exit();
    }

// }}}
    // {{{ actionUpdate
    /**
     * Update a single item
     *
     * @access public
     * @return void
     */
    public function actionUpdate() {

        if (!isset($_GET['id']))
            $this->_sendResponse(500, 'Error: Parameter <b>id</b> is missing');

        // Get PUT parameters
        parse_str(file_get_contents('php://input'), $put_vars);

        //echo '<pre>';
        //print_r($put_vars);
        //echo '</pre>';
        //exit();

        switch ($_GET['model']) {
            // Find respective model
            case 'checkup':

                $model = new HospitalCheckUp;
                $models = $model->findByPk($_GET['id']);

                //$models = Yii::app()->db->createCommand($sql)->queryAll();
                break;

            default:
                $this->_sendResponse(501, sprintf('Error: Mode <b>update</b> is not implemented for model <b>%s</b>', $_GET['model']));
                exit;
        }

        if (is_null($models))
            $this->_sendResponse(400, sprintf("Error: Didn't find any model <b>%s</b> with ID <b>%s</b>.", $_GET['model'], $_GET['id']));

        // Try to assign PUT parameters to attributes
        foreach ($put_vars as $var => $value) {
            // Does model have this attribute?
            if ($models->hasAttribute($var)) {
                $models->$var = $value;
            } else {
                // No, raise error
                $this->_sendResponse(500, sprintf('Parameter <b>%s</b> is not allowed for model <b>%s</b>', $var, $_GET['model']));
            }
        }
        // Try to save the model
        if ($models->save()) {
            $this->_sendResponse(200);
        } else {
            $msg = "<h1>Error</h1>";
            $msg .= sprintf("Couldn't update model <b>%s</b>", $_GET['model']);
            $msg .= "<ul>";
            foreach ($model->errors as $attribute => $attr_errors) {
                $msg .= "<li>Attribute: $attribute</li>";
                $msg .= "<ul>";
                foreach ($attr_errors as $attr_error) {
                    $msg .= "<li>$attr_error</li>";
                }
                $msg .= "</ul>";
            }
            $msg .= "</ul>";
            $this->_sendResponse(500, $msg);
        }
    }

// }}}
    // {{{ actionDelete
    /**
     * Deletes a single item
     *
     * @access public
     * @return void
     */
    public function actionDelete() {
        switch ($_GET['model']) {
            // Load the respective model
            case 'checkup':

                $model = new HospitalCheckUp;
                $models = $model->findByPk($_GET['id']);

                //$models = Yii::app()->db->createCommand($sql)->queryAll();
                break;

            default: // {{{
                $this->_sendResponse(501, sprintf('Error: Mode <b>delete</b> is not implemented for model <b>%s</b>', $_GET['model']));
                exit; // }}}
        }
        // Was a model found?
        if (is_null($models)) {
            // No, raise an error
            $this->_sendResponse(400, sprintf("Error: Didn't find any model <b>%s</b> with ID <b>%s</b>.", $_GET['model'], $_GET['id']));
        }
        // Delete the model
        $num = $models->delete();
        if ($num > 0)
            $this->_sendResponse(200);
        else
            $this->_sendResponse(500, sprintf("Error: Couldn't delete model <b>%s</b> with ID <b>%s</b>.", $_GET['model'], $_GET['id']));
    }

// }}}
    // }}} End Actionscd
    // {{{ Other Methods
    // {{{ _sendResponse
    /**
     * Sends the API response
     *
     * @param int $status
     * @param string $body
     * @param string $content_type
     * @access private
     * @return void
     */
    private function _sendResponse($status = 200, $body = '', $content_type = 'text/html') {
        $status_header = 'HTTP/1.1 ' . $status . ' ' . $this->_getStatusCodeMessage($status);
        // set the status
        header($status_header);
        // set the content type
        header('Content-type: ' . $content_type);

        // pages with body are easy
        if ($body != '') {
            // send the body
            echo $body;
            exit;
        } // we need to create the body if none is passed
        else {
            // create some body messages
            $message = 'none passed';

            // this is purely optional, but makes the pages a little nicer to read
            // for your users.  Since you won't likely send a lot of different status codes,
            // this also shouldn't be too ponderous to maintain
            switch ($status) {
                case 401:
                    $message = 'You must be authorized to view this page.';
                    break;
                case 404:
                    $message = 'The requested URL ' . $_SERVER['REQUEST_URI'] . ' was not found.';
                    break;
                case 500:
                    $message = 'The server encountered an error processing your request.';
                    break;
                case 501:
                    $message = 'The requested method is not implemented.';
                    break;
            }

            // servers don't always have a signature turned on (this is an apache directive "ServerSignature On")
            $signature = ($_SERVER['SERVER_SIGNATURE'] == '') ? $_SERVER['SERVER_SOFTWARE'] . ' Server at ' . $_SERVER['SERVER_NAME'] . ' Port ' . $_SERVER['SERVER_PORT'] : $_SERVER['SERVER_SIGNATURE'];

            // this should be templatized in a real-world solution
            $body = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
						<html>
							<head>
								<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
								<title>' . $status . ' ' . $this->_getStatusCodeMessage($status) . '</title>
							</head>
							<body>
								<h1>' . $this->_getStatusCodeMessage($status) . '</h1>
								<p>' . $message . '</p>
								<hr />
								<address>' . $signature . '</address>
							</body>
						</html>';

            echo $body;
            exit;
        }
    }

// }}}
    // {{{ _getStatusCodeMessage
    /**
     * Gets the message for a status code
     *
     * @param mixed $status
     * @access private
     * @return string
     */
    private function _getStatusCodeMessage($status) {
        // these could be stored in a .ini file and loaded
        // via parse_ini_file()... however, this will suffice
        // for an example
        $codes = Array(
            100 => 'Continue',
            101 => 'Switching Protocols',
            200 => 'OK',
            201 => 'Created',
            202 => 'Accepted',
            203 => 'Non-Authoritative Information',
            204 => 'No Content',
            205 => 'Reset Content',
            206 => 'Partial Content',
            300 => 'Multiple Choices',
            301 => 'Moved Permanently',
            302 => 'Found',
            303 => 'See Other',
            304 => 'Not Modified',
            305 => 'Use Proxy',
            306 => '(Unused)',
            307 => 'Temporary Redirect',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            406 => 'Not Acceptable',
            407 => 'Proxy Authentication Required',
            408 => 'Request Timeout',
            409 => 'Conflict',
            410 => 'Gone',
            411 => 'Length Required',
            412 => 'Precondition Failed',
            413 => 'Request Entity Too Large',
            414 => 'Request-URI Too Long',
            415 => 'Unsupported Media Type',
            416 => 'Requested Range Not Satisfiable',
            417 => 'Expectation Failed',
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
            504 => 'Gateway Timeout',
            505 => 'HTTP Version Not Supported'
        );

        return (isset($codes[$status])) ? $codes[$status] : '';
    }

// }}}
    // {{{ _checkAuth
    /**
     * Checks if a request is authorized
     *
     * @access private
     * @return void
     */
    public function hashPassword($phrase) {
        return hash('md5', $phrase);
    }

    /*
      private function _checkAuth()
      {

      //cek code via GET
      if(!(isset($_GET['c_id'])) and (isset($_GET['c_key'])))
      {
      // Error: Unauthorized
      $this->_sendResponse(401);
      }
      $c_id = $_GET['c_id'];
      $c_key = $_GET['c_key'];
      //	$value_key = $_GET['value'];

      // Find the user
      //$app_id=Application::model()->find('LOWER(client_id)=?',array(strtolower($c_id)));
      //$app_key=Application::model()->find('LOWER(client_key)=?',array(strtolower($c_key)));
      $app = new Application;

      $criteria = new CDbCriteria();
      $criteria->condition = "client_id = '".$c_id."' ";

      $model = $app->findall($criteria);
      //$query = $app->find('client_key',$value_key);

      //simpan value db ke variabel $app
      foreach($model as $app);

      //pecah value scope ke array
      $scope = explode("," , $app->scope);

      if($app->client_id === null) {
      // Error: Unauthorized
      $this->_sendResponse(401,'<b>Error</b> : Invalid Client! '.$app->client_id.'  ');
      }
      else if($app->client_key !== $c_key) {
      // Error: Unauthorized
      $this->_sendResponse(403, '<b>Error</b> : Invalid Key ! ' );

      }

      $status = false;

      foreach($scope as $key =>$value)
      {
      if($scope[$key] === $this->method)
      {
      $status = true;
      }
      //echo $scope[$key];
      //echo '<br>';
      }

      if($status == false)
      {
      $this->_sendResponse(401, '<b>Error</b> : Restricted Area ! '.$this->method.' ' );
      }
      }
      /*

      // }}}
      // {{{ _getObjectEncoded
      /**
     * Returns the json or xml encoded array
     *
     * @param mixed $model
     * @param mixed $array Data to be encoded
     * @access private
     * @return void
     */

    public function actionResetSync(){
        
        $sql = "
        UPDATE absensi SET sync_status = 1;
        UPDATE absensi_harian SET sync_status = 1;
        UPDATE absensi_solution_x_onehundred_c SET sync_status = 1;
        UPDATE actual_table_name SET sync_status = 1;
        UPDATE activities SET sync_status = 1;
        UPDATE announcements SET sync_status = 1;
        UPDATE assignment SET sync_status = 1;
        UPDATE chapters SET sync_status = 1;
        UPDATE chapter_files SET sync_status = 1;
        UPDATE class SET sync_status = 1;
        UPDATE class_detail SET sync_status = 1;
        UPDATE class_history SET sync_status = 1;
        UPDATE final_mark SET sync_status = 1;
        UPDATE lesson SET sync_status = 1;
        UPDATE lesson_kd SET sync_status = 1;
        UPDATE lesson_list SET sync_status = null;
        UPDATE lesson_mc SET sync_status = 1;
        UPDATE lks SET sync_status = 1;
        UPDATE notification SET sync_status = 1;
        UPDATE oauth_token SET sync_status = 1;
        UPDATE offline_mark SET sync_status = 1;
        UPDATE options SET sync_status = 1;
        UPDATE questions SET sync_status = 1;
        UPDATE quiz SET sync_status = 1;
        UPDATE rpp SET sync_status = 1;
        UPDATE session SET sync_status = 1;
        UPDATE skill SET sync_status = 1;
        UPDATE student_assignment SET sync_status = 1;
        UPDATE student_quiz SET sync_status = 1;
        UPDATE student_quiz_temp SET sync_status = 1;
        UPDATE student_skill SET sync_status = 1;
        UPDATE users SET sync_status = 1;
        UPDATE user_logs SET sync_status = 1;
        UPDATE user_profile SET sync_status = 1;
        ";
        $command = Yii::app()->db->createCommand($sql);

        try {
            $result = $command->execute();
            // foreach ($result as $value) {
            //     $hasil[$value['display_name']."-".$value['user_id']][$value['name']][$value['pertemuan_ke']] = true; 
            // }
            // $data['data']['result'][] = $hasil;
            // $json = json_encode($data, JSON_PRETTY_PRINT);
            // echo $json;
            // print_r($result);
            // echo "berhasil";
            Yii::app()->user->setFlash('success', 'Berhasil reset sinkronasi');
            $this->redirect(array('/option'));

        } catch (Exception $ex) {
            // Handle exception
            // echo "<pre>";
            // print_r($ex);
            // echo "</pre>";
            // echo "gagal";
             Yii::app()->user->setFlash('success', 'Gagal reset sinkronasi');
            $this->redirect(array('/option'));
        }
    }

    public function actionResetByclass(){
        
        // echo $_GET['class_id'];


        // $sql_reset_other = "
        // UPDATE absensi SET sync_status = 1;
        // UPDATE absensi_harian SET sync_status = 1;
        // UPDATE absensi_solution_x_onehundred_c SET sync_status = 1;
        // UPDATE actual_table_name SET sync_status = 1;
        // UPDATE activities SET sync_status = 1;
        // UPDATE announcements SET sync_status = 1;
        // UPDATE assignment SET sync_status = 1;
        // UPDATE chapters SET sync_status = 1;
        // UPDATE chapter_files SET sync_status = 1;
        // UPDATE class SET sync_status = 1;
        // UPDATE class_detail SET sync_status = 1;
        // UPDATE class_history SET sync_status = 1;
        // UPDATE final_mark SET sync_status = 1;
        // UPDATE lesson SET sync_status = 1;
        // UPDATE lesson_kd SET sync_status = 1;
        // UPDATE lesson_list SET sync_status = 1;
        // UPDATE lesson_mc SET sync_status = 1;
        // UPDATE lks SET sync_status = 1;
        // UPDATE notification SET sync_status = 1;
        // UPDATE oauth_token SET sync_status = 1;
        // UPDATE offline_mark SET sync_status = 1;
        // UPDATE options SET sync_status = 1;
        // UPDATE questions SET sync_status = 1;
        // UPDATE quiz SET sync_status = 1;
        // UPDATE rpp SET sync_status = 1;
        // UPDATE session SET sync_status = 1;
        // UPDATE skill SET sync_status = 1;
        // UPDATE student_assignment SET sync_status = 1;
        // UPDATE student_quiz SET sync_status = 1;
        // UPDATE student_quiz_temp SET sync_status = 1;
        // UPDATE student_skill SET sync_status = 1;
        // UPDATE users SET sync_status = 1;
        // UPDATE user_logs SET sync_status = 1;
        // UPDATE user_profile SET sync_status = 1;
        // ";
        // $command_reset_other = Yii::app()->db->createCommand($sql_reset_other);
        // $command_reset_other->execute();



        
        $sql = "select id from lesson where class_id = ".$_GET['class_id'].";";
        $command = Yii::app()->db->createCommand($sql);
        $result = $command->queryAll();    
        $arr_lesson_ids = array();
            foreach ($result as $value) {
                // $hasil[$value['display_name']."-".$value['user_id']][$value['name']][$value['pertemuan_ke']] = true; 
                // echo $value['id'];
                array_push($arr_lesson_ids,$value['id']);
            }

        $lesson_ids = implode(",", $arr_lesson_ids);   
        // echo $lesson_ids; 


        
       


        if (empty($arr_lesson_ids)) {
             $sql2 = "
        UPDATE users SET sync_status = null where class_id = ".$_GET['class_id'].";
        UPDATE class_detail SET sync_status = null where id = ".$_GET['class_id'].";
        UPDATE lesson_list SET sync_status = null;
        UPDATE options SET sync_status = null;
        UPDATE users SET sync_status = null where role_id = 1;
        UPDATE lesson SET sync_status = null where class_id = ".$_GET['class_id'].";
      
        ";  
        } else {

        $sql1 = "select id from quiz where lesson_id in (".$lesson_ids.");";
        $command1 = Yii::app()->db->createCommand($sql1);
        $result1 = $command1->queryAll();    
        $arr_quiz_ids = array();

            foreach ($result1 as $value1) {
                // $hasil[$value['display_name']."-".$value['user_id']][$value['name']][$value['pertemuan_ke']] = true; 
                array_push($arr_quiz_ids,$value1['id']);
            }  

        $quiz_ids = implode(",", $arr_quiz_ids);
        // echo $quiz_ids;



        $sql99 = "select question from quiz where id in (".$quiz_ids.");";
        $command99 = Yii::app()->db->createCommand($sql99);
        $result99 = $command99->queryAll();    
        $arr_questions_ids = array();

            foreach ($result99 as $value99) {
                // $hasil[$value['display_name']."-".$value['user_id']][$value['name']][$value['pertemuan_ke']] = true; 
                if ($value99['question']) {
                    $arr_value = explode(",", $value99['question']);
                    $arr_questions_ids = array_merge($arr_questions_ids,$arr_value);
                }
                
            }  

        $questions_ids = implode(",", $arr_questions_ids);
        // echo $quiz_ids;


             $sql2 = "
                UPDATE users SET sync_status = null where class_id = ".$_GET['class_id'].";
                UPDATE class_detail SET sync_status = null where id = ".$_GET['class_id'].";
                UPDATE lesson_list SET sync_status = null;
                UPDATE options SET sync_status = null;
                UPDATE users SET sync_status = null where role_id = 1;
                UPDATE lesson SET sync_status = null where class_id = ".$_GET['class_id'].";
              
                UPDATE lesson_mc SET sync_status = null where lesson_id  in (".$lesson_ids.");
                UPDATE quiz SET sync_status = null where lesson_id  in (".$lesson_ids.");
                UPDATE questions SET sync_status = null where id  in (".$questions_ids.");

                UPDATE student_quiz SET sync_status = null where quiz_id  in (".$quiz_ids.");
                ";
        }

       

        $command2 = Yii::app()->db->createCommand($sql2);

        try {
            $result2 = $command2->execute();    
            // $data->queryRow();     
            // foreach ($result as $value) {
            //     $hasil[$value['display_name']."-".$value['user_id']][$value['name']][$value['pertemuan_ke']] = true; 
            // }
            // $data['data']['result'][] = $hasil;
            // $json = json_encode($data, JSON_PRETTY_PRINT);
            // echo $json;
            // print_r($result);
            // echo "berhasil";
            Yii::app()->user->setFlash('success', 'Berhasil reset sinkronasi');
            $this->redirect(array('/clases'));
            // foreach ($result2 as $value2) {
            //     // $hasil[$value['display_name']."-".$value['user_id']][$value['name']][$value['pertemuan_ke']] = true; 
            //     echo $value['id'];
            // }
        } catch (Exception $ex) {
            // Handle exception
            // echo "<pre>";
            // print_r($ex);
            // echo "</pre>";
            // echo "gagal";
             Yii::app()->user->setFlash('success', 'Gagal reset sinkronasi');
            $this->redirect(array('/clases'));
        }
    } 

    public function actionResetBylesson(){
        
        // echo $_GET['class_id'];
        
        $sql = "select id from lesson where id = ".$_GET['lesson_id'].";";
        $command = Yii::app()->db->createCommand($sql);
        $result = $command->queryAll();    
        $arr_lesson_ids = array();
            foreach ($result as $value) {
                // $hasil[$value['display_name']."-".$value['user_id']][$value['name']][$value['pertemuan_ke']] = true; 
                // echo $value['id'];
                array_push($arr_lesson_ids,$value['id']);
            }

        $lesson_ids = implode(",", $arr_lesson_ids);   
        // echo $lesson_ids; 



        if (empty($arr_lesson_ids)) {
             $sql2 = "
        UPDATE lesson_list SET sync_status = null;
        UPDATE options SET sync_status = null;
        UPDATE users SET sync_status = null where role_id = 1;
        UPDATE lesson SET sync_status = null where id = ".$_GET['lesson_id'].";
        ";  
        } else {

        $sql1 = "select id from quiz where lesson_id in (".$lesson_ids.");";
        $command1 = Yii::app()->db->createCommand($sql1);
        $result1 = $command1->queryAll();    
        $arr_quiz_ids = array();

            foreach ($result1 as $value1) {
                // $hasil[$value['display_name']."-".$value['user_id']][$value['name']][$value['pertemuan_ke']] = true; 
                array_push($arr_quiz_ids,$value1['id']);
            }  

        $quiz_ids = implode(",", $arr_quiz_ids);
        // echo $quiz_ids;


        $sql99 = "select question from quiz where id in (".$quiz_ids.");";
        $command99 = Yii::app()->db->createCommand($sql99);
        $result99 = $command99->queryAll();    
        $arr_questions_ids = array();

            foreach ($result99 as $value99) {
                // $hasil[$value['display_name']."-".$value['user_id']][$value['name']][$value['pertemuan_ke']] = true; 
                if ($value99['question']) {
                    $arr_value = explode(",", $value99['question']);
                    $arr_questions_ids = array_merge($arr_questions_ids,$arr_value);
                }
                
            }  

        $questions_ids = implode(",", $arr_questions_ids);
        // echo $quiz_ids;


             $sql2 = "
                
                UPDATE lesson_list SET sync_status = null;
                UPDATE options SET sync_status = null;
                UPDATE users SET sync_status = null where role_id = 1;
                UPDATE lesson SET sync_status = null where id = ".$_GET['lesson_id'].";
              
                UPDATE lesson_mc SET sync_status = null where lesson_id in (".$lesson_ids.");
                UPDATE quiz SET sync_status = null where lesson_id  in (".$lesson_ids.");
                UPDATE questions SET sync_status = null where id  in (".$questions_ids.");

                UPDATE student_quiz SET sync_status = null where quiz_id  in (".$quiz_ids.");
                ";
        }

       

        $command2 = Yii::app()->db->createCommand($sql2);

        try {
            $result2 = $command2->execute();    
            // $data->queryRow();     
            // foreach ($result as $value) {
            //     $hasil[$value['display_name']."-".$value['user_id']][$value['name']][$value['pertemuan_ke']] = true; 
            // }
            // $data['data']['result'][] = $hasil;
            // $json = json_encode($data, JSON_PRETTY_PRINT);
            // echo $json;
            // print_r($result);
            // echo "berhasil";
            Yii::app()->user->setFlash('success', 'Berhasil reset sinkronasi');
            $this->redirect(array('/lesson'));
            // foreach ($result2 as $value2) {
            //     // $hasil[$value['display_name']."-".$value['user_id']][$value['name']][$value['pertemuan_ke']] = true; 
            //     echo $value['id'];
            // }
        } catch (Exception $ex) {
            // Handle exception
            // echo "<pre>";
            // print_r($ex);
            // echo "</pre>";
            // echo "gagal";
             Yii::app()->user->setFlash('success', 'Gagal reset sinkronasi');
            $this->redirect(array('/lesson'));
        }
    } 


     public function actionCloseByclass(){
        
        // echo $_GET['class_id'];

        $quiz_ids = "";

        $lesson_ids = "";
        
        $sql = "select id from lesson where class_id = ".$_GET['class_id'].";";
        $command = Yii::app()->db->createCommand($sql);
        $result = $command->queryAll();    
        $arr_lesson_ids = array();
            foreach ($result as $value) {
                // $hasil[$value['display_name']."-".$value['user_id']][$value['name']][$value['pertemuan_ke']] = true; 
                // echo $value['id'];
                array_push($arr_lesson_ids,$value['id']);
            }

        $lesson_ids = implode(",", $arr_lesson_ids);   
        // echo $lesson_ids; 


        
       


        if (empty($arr_lesson_ids)) {
             $sql2 = "
        UPDATE users SET sync_status = 1 where class_id = ".$_GET['class_id'].";
        UPDATE class_detail SET sync_status = 1 where id = ".$_GET['class_id'].";
        UPDATE lesson_list SET sync_status = 1;
        UPDATE options SET sync_status = 1;
        UPDATE users SET sync_status = 1 where role_id = 1;
        UPDATE lesson SET sync_status = 1 where class_id = ".$_GET['class_id'].";
      
        ";  
        } else {

        $sql1 = "select id from quiz where lesson_id in (".$lesson_ids.");";
        $command1 = Yii::app()->db->createCommand($sql1);
        $result1 = $command1->queryAll();    
        $arr_quiz_ids = array();

            foreach ($result1 as $value1) {
                // $hasil[$value['display_name']."-".$value['user_id']][$value['name']][$value['pertemuan_ke']] = true; 
                array_push($arr_quiz_ids,$value1['id']);
            }  

        $quiz_ids = implode(",", $arr_quiz_ids);
        // echo $quiz_ids;


             $sql2 = "
                UPDATE users SET sync_status = 1 where class_id = ".$_GET['class_id'].";
                UPDATE class_detail SET sync_status = 1 where id = ".$_GET['class_id'].";
                UPDATE lesson_list SET sync_status = 1;
                UPDATE options SET sync_status = 1;
                UPDATE users SET sync_status = 1 where role_id = 1;
                UPDATE lesson SET sync_status = 1 where class_id = ".$_GET['class_id'].";
              
                UPDATE lesson_mc SET sync_status = 1 where lesson_id  in (".$lesson_ids.");
                UPDATE quiz SET sync_status = 1 where lesson_id  in (".$lesson_ids.");

                UPDATE student_quiz SET sync_status = 1 where quiz_id  in (".$quiz_ids.");
                ";
        }

       

        $command2 = Yii::app()->db->createCommand($sql2);

        try {
            $result2 = $command2->execute();    
            // $data->queryRow();     
            // foreach ($result as $value) {
            //     $hasil[$value['display_name']."-".$value['user_id']][$value['name']][$value['pertemuan_ke']] = true; 
            // }
            // $data['data']['result'][] = $hasil;
            // $json = json_encode($data, JSON_PRETTY_PRINT);
            // echo $json;
            // print_r($result);
            // echo "berhasil";
            Yii::app()->user->setFlash('success', 'Berhasil reset sinkronasi');
            $this->redirect(array('/clases'));
            // foreach ($result2 as $value2) {
            //     // $hasil[$value['display_name']."-".$value['user_id']][$value['name']][$value['pertemuan_ke']] = true; 
            //     echo $value['id'];
            // }
        } catch (Exception $ex) {
            // Handle exception
            // echo "<pre>";
            // print_r($ex);
            // echo "</pre>";
            // echo "gagal";
             Yii::app()->user->setFlash('success', 'Gagal reset sinkronasi');
            $this->redirect(array('/clases'));
        }
    }


    public function actionCloseBylesson(){
        
        // echo $_GET['class_id'];
        
        $sql = "select id from lesson where id = ".$_GET['lesson_id'].";";
        $command = Yii::app()->db->createCommand($sql);
        $result = $command->queryAll();    
        $arr_lesson_ids = array();
            foreach ($result as $value) {
                // $hasil[$value['display_name']."-".$value['user_id']][$value['name']][$value['pertemuan_ke']] = true; 
                // echo $value['id'];
                array_push($arr_lesson_ids,$value['id']);
            }

        $lesson_ids = implode(",", $arr_lesson_ids);   
        // echo $lesson_ids; 



        if (empty($arr_lesson_ids)) {
             $sql2 = "
        UPDATE lesson_list SET sync_status = 1;
        UPDATE options SET sync_status = 1;
        UPDATE users SET sync_status = 1 where role_id = 1;
        UPDATE lesson SET sync_status = 1 where id = ".$_GET['lesson_id'].";
        ";  
        } else {

        $sql1 = "select id from quiz where lesson_id in (".$lesson_ids.");";
        $command1 = Yii::app()->db->createCommand($sql1);
        $result1 = $command1->queryAll();    
        $arr_quiz_ids = array();

            foreach ($result1 as $value1) {
                // $hasil[$value['display_name']."-".$value['user_id']][$value['name']][$value['pertemuan_ke']] = true; 
                array_push($arr_quiz_ids,$value1['id']);
            }  

        $quiz_ids = implode(",", $arr_quiz_ids);
        // echo $quiz_ids;


             $sql2 = "
                
                UPDATE lesson_list SET sync_status = 1;
                UPDATE options SET sync_status = 1;
                UPDATE users SET sync_status = 1 where role_id = 1;
                UPDATE lesson SET sync_status = 1 where id = ".$_GET['lesson_id'].";
              
                UPDATE lesson_mc SET sync_status = 1 where lesson_id in (".$lesson_ids.");
                UPDATE quiz SET sync_status = 1 where lesson_id  in (".$lesson_ids.");

                UPDATE student_quiz SET sync_status = 1 where quiz_id  in (".$quiz_ids.");
                ";
        }

       

        $command2 = Yii::app()->db->createCommand($sql2);

        try {
            $result2 = $command2->execute();    
            // $data->queryRow();     
            // foreach ($result as $value) {
            //     $hasil[$value['display_name']."-".$value['user_id']][$value['name']][$value['pertemuan_ke']] = true; 
            // }
            // $data['data']['result'][] = $hasil;
            // $json = json_encode($data, JSON_PRETTY_PRINT);
            // echo $json;
            // print_r($result);
            // echo "berhasil";
            Yii::app()->user->setFlash('success', 'Berhasil reset sinkronasi');
            $this->redirect(array('/lesson'));
            // foreach ($result2 as $value2) {
            //     // $hasil[$value['display_name']."-".$value['user_id']][$value['name']][$value['pertemuan_ke']] = true; 
            //     echo $value['id'];
            // }
        } catch (Exception $ex) {
            // Handle exception
            // echo "<pre>";
            // print_r($ex);
            // echo "</pre>";
            // echo "gagal";
             Yii::app()->user->setFlash('success', 'Gagal reset sinkronasi');
            $this->redirect(array('/lesson'));
        }
    } 


    public function actionSyncdata(){
        // $url = @Option::model()->findByAttributes(array('key_config' => 'sync_url'))->value;
        $url = "localhost:8888/send_command";
        $client_to_server = $url . "?mode=client_to_server&last_day=30";
        $server_to_client = $url . "?mode=server_to_client&last_day=30";

        $result1 = $this->file_get_contents_curl($client_to_server);
        $result2 = $this->file_get_contents_curl($server_to_client);

        Yii::app()->user->setFlash('success', 'Berhasil sinkronasi data');
        $this->redirect(array('/option/index'));
        // echo $result1 . " in last 30 days";
        // echo " <br/> ";
        // echo $result2 . " in last 30 days";
    }

    private function file_get_contents_curl($url) {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);

        $data = curl_exec($ch);

        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        //print_r($data);exit;

        if ($http_status != 200) {
            throw new CHttpException(500, 'Error..! Something gone wrong.');
        }

        return $data;
    }

    public function checkToken() {
        if (isset($_GET['token']) or isset($_POST['token'])) {
           
             if (isset($_GET['token'])) {
                $token = $_GET['token'];
             } else {
                $token = $_POST['token'];
             }

            //echo $token;exit;
            $model = new OauthToken;

            $criteria = new CDbCriteria;
            $criteria->condition = "token = :token";
            $criteria->params = array(":token" => $token);

            $models = $model->findAll($criteria);

            if (!empty($models)) {
                date_default_timezone_set('Asia/Jakarta');

                $today = new DateTime();
                foreach ($models as $time) {
                    $expired_date = new DateTime($time->expired_date);
                }

                if ($today >= $expired_date) {
                    $data['success'] = 0;
                    $data['message'] = 'ERROR : Expired TOKEN. Please Re-login.';
                    $data['data']['result'] = NULL;
                    $json = json_encode($data, JSON_PRETTY_PRINT);
                    header('Content-Type: application/x-javascript');
                    echo $json;
                    exit();
                } else {
                    foreach ($models as $get) {
                        Yii::app()->session['role'] = $get->User->role_id;
                        Yii::app()->session['id_user'] = $get->id_user;
                        //echo Yii::app()->session['role'];exit;
                    }
                }
            } else {
                $data['success'] = 0;
                $data['message'] = 'ERROR : Invalid TOKEN.';
                $data['data']['result'] = NULL;
                $json = json_encode($data, JSON_PRETTY_PRINT);
                header('Content-Type: application/x-javascript');
                echo $json;
                exit();
            }
        } else {
            $data['success'] = 0;
            $data['message'] = 'ERROR : No TOKEN.';
            $data['data']['result'] = NULL;
            $json = json_encode($data, JSON_PRETTY_PRINT);
            header('Content-Type: application/x-javascript');
            echo $json;
            exit();
        }
    }

    private function random($length) {
        $bytes = openssl_random_pseudo_bytes($length * 2);
        return substr(str_replace(array('/', '+', '='), '', base64_encode($bytes)), 0, $length);
    }

    private function _getObjectEncoded($model, $array) {
        if (isset($_GET['format']))
            $this->format = $_GET['format'];

        if ($this->format == 'json') {
            return CJSON::encode($array);
        } elseif ($this->format == 'xml') {
            $result = '<?xml version="1.0">';
            $result .= "\n<$model>\n";
            foreach ($array as $key => $value)
                $result .= "    <$key>" . utf8_encode($value) . "</$key>\n";
            $result .= '</' . $model . '>';
            return $result;
        } else {
            return;
        }
    }

// }}}
    // }}} End Other Methods

    public function actionSinkronisasiData($type = NULL) {
        //if($_SERVER['HTTP_HOST'] == "exambox.pinisi"){
        $prefix = Yii::app()->params['tablePrefix'];
        $all_data = array();
        switch ($type) {
            case 'user':
                $cekUser = User::model()->findAll(array('condition' => 'sync_status is null', 'order' => 'id ASC'));

                if (!empty($cekUser)) {
                    $total_user = count($cekUser);
                    $data_user = array();
                    $raw_user = array();
                    $ids = array();
                    foreach ($cekUser as $key) {
                        $raw_user['id'] = $key->id;
                        $raw_user['email'] = $key->email;
                        $raw_user['username'] = $key->username;
                        $raw_user['encrypted_password'] = $key->encrypted_password;
                        $raw_user['role_id'] = $key->role_id;
                        $raw_user['created_at'] = $key->created_at;
                        $raw_user['updated_at'] = $key->updated_at;
                        $raw_user['class_id'] = $key->class_id;
                        $raw_user['reset_password'] = $key->reset_password;
                        $raw_user['display_name'] = $key->display_name;
                        array_push($data_user, $raw_user);
                        array_push($ids, $key->id);
                    }
                    $wid = implode(',', $ids);
                    $updateLocal = "UPDATE " . $prefix . "users SET sync_status = 2 AND id IN(" . $wid . ")";

                    $updateLocalCommand = Yii::app()->db->createCommand($updateLocal);
                    $updateLocalCommand->execute();
                } else {
                    $total_user = NULL;
                    $data_user = NULL;
                }

                $all_data['messages'] = 'success';
                $all_data['data'] = $data_user;
                echo json_encode($all_data, JSON_PRETTY_PRINT);
                break;
            case 'tugas':
                $cekTugas = Assignment::model()->findAll(array('condition' => 'sync_status is null', 'order' => 'id ASC'));

                if (!empty($cekTugas)) {
                    $total_tugas = count($cekTugas);
                    $data_tugas = array();
                    $raw_tugas = array();
                    $ids = array();
                    foreach ($cekTugas as $tgs) {
                        $raw_tugas['id'] = $tgs->id;
                        $raw_tugas['title'] = $tgs->title;
                        $raw_tugas['content'] = $tgs->content;
                        $raw_tugas['created_at'] = $tgs->created_at;
                        $raw_tugas['updated_at'] = $tgs->updated_at;
                        $raw_tugas['created_by'] = $tgs->created_by;
                        $raw_tugas['updated_by'] = $tgs->updated_by;
                        $raw_tugas['due_date'] = $tgs->due_date;
                        $raw_tugas['lesson_id'] = $tgs->lesson_id;
                        $raw_tugas['file'] = $tgs->file;
                        $raw_tugas['assignment_type'] = $tgs->assignment_type;
                        $raw_tugas['add_to_summary'] = $tgs->add_to_summary;
                        $raw_tugas['recipient'] = $tgs->recipient;
                        $raw_tugas['semester'] = $tgs->semester;
                        $raw_tugas['year'] = $tgs->year;
                        $raw_tugas['status'] = $tgs->status;
                        array_push($data_tugas, $raw_tugas);
                        array_push($ids, $tgs->id);
                    }
                    $wid = implode(',', $ids);
                    $updateLocal = "UPDATE " . $prefix . "assignment SET sync_status = 2 AND id IN(" . $wid . ")";

                    $updateLocalCommand = Yii::app()->db->createCommand($updateLocal);
                    $updateLocalCommand->execute();
                } else {
                    $total_tugas = NULL;
                    $data_tugas = NULL;
                }

                $all_data['messages'] = 'success';
                $all_data['data'] = $data_tugas;
                echo json_encode($all_data, JSON_PRETTY_PRINT);
                break;

            case 'tugasSiswa':
                $cekTugasSiswa = StudentAssignment::model()->findAll(array('condition' => 'sync_status is null', 'order' => 'id ASC'));

                if (!empty($cekTugasSiswa)) {
                    $total_ts = count($cekTugasSiswa);
                    $data_ts = array();
                    $raw_ts = array();
                    $ids = array();
                    foreach ($cekTugasSiswa as $ts) {
                        $raw_ts['id'] = $ts->id;
                        $raw_ts['assignment_id'] = $ts->assignment_id;
                        $raw_ts['content'] = $ts->content;
                        $raw_ts['file'] = $ts->file;
                        $raw_ts['student_id'] = $ts->student_id;
                        $raw_ts['score'] = $ts->score;
                        $raw_ts['created_at'] = $ts->created_at;
                        $raw_ts['updated_at'] = $ts->updated_at;
                        $raw_ts['status'] = $ts->status;
                        array_push($data_ts, $raw_ts);
                        array_push($ids, $ts->id);
                    }
                    $wid = implode(',', $ids);
                    $updateLocal = "UPDATE " . $prefix . "student_assignment SET sync_status = 2 AND id IN (" . $wid . ")";

                    $updateLocalCommand = Yii::app()->db->createCommand($updateLocal);
                    $updateLocalCommand->execute();
                } else {
                    $total_ts = NULL;
                    $data_ts = NULL;
                }

                $all_data['messages'] = 'success';
                $all_data['data'] = $data_ts;
                echo json_encode($all_data, JSON_PRETTY_PRINT);
                break;

            case 'materi':
                $cekMateri = Chapters::model()->findAll(array('condition' => 'sync_status is null', 'order' => 'id ASC'));

                if (!empty($cekMateri)) {
                    $total_materi = count($cekMateri);
                    $data_materi = array();
                    $raw_materi = array();
                    $ids = array();
                    foreach ($cekMateri as $materi) {
                        $raw_materi['id'] = $materi->id;
                        $raw_materi['id_lesson'] = $materi->id_lesson;
                        $raw_materi['title'] = $materi->title;
                        $raw_materi['description'] = $materi->description;
                        $raw_materi['created_at'] = $materi->created_at;
                        $raw_materi['updated_at'] = $materi->updated_at;
                        $raw_materi['created_by'] = $materi->created_by;
                        $raw_materi['updated_by'] = $materi->updated_by;
                        $raw_materi['content'] = $materi->content;
                        $raw_materi['chapter_type'] = $materi->chapter_type;
                        $raw_materi['semester'] = $materi->semester;
                        $raw_materi['year'] = $materi->year;
                        array_push($data_materi, $raw_materi);
                        array_push($ids, $materi->id);
                    }
                    $wid = implode(',', $ids);
                    $json_data_materi = json_encode($data_materi);
                    $updateLocal = "UPDATE " . $prefix . "chapters SET sync_status = 2 AND id IN (" . $wid . ")";

                    $updateLocalCommand = Yii::app()->db->createCommand($updateLocal);
                    $updateLocalCommand->execute();
                } else {
                    $total_materi = NULL;
                    $data_materi = NULL;
                }

                $all_data['messages'] = 'success';
                $all_data['data'] = $data_materi;
                echo json_encode($all_data, JSON_PRETTY_PRINT);
                break;

            case 'fileMateri':
                $cekFileMateri = ChapterFiles::model()->findAll(array('condition' => 'sync_status is null', 'order' => 'id ASC'));

                if (!empty($cekFileMateri)) {
                    $total_fm = count($cekFileMateri);
                    $data_fm = array();
                    $raw_fm = array();
                    $ids = array();
                    foreach ($cekFileMateri as $cfm) {
                        $raw_fm['id'] = $cfm->id;
                        $raw_fm['id_lesson'] = $cfm->id_lesson;
                        $raw_fm['title'] = $cfm->title;
                        $raw_fm['description'] = $cfm->description;
                        $raw_fm['created_at'] = $cfm->created_at;
                        $raw_fm['updated_at'] = $cfm->updated_at;
                        $raw_fm['created_by'] = $cfm->created_by;
                        $raw_fm['updated_by'] = $cfm->updated_by;
                        $raw_fm['content'] = $cfm->content;
                        $raw_fm['chapter_type'] = $cfm->chapter_type;
                        $raw_fm['semester'] = $cfm->semester;
                        $raw_fm['year'] = $cfm->year;
                        array_push($data_fm, $raw_fm);
                        array_push($ids, $cfm->id);
                    }
                    $wid = implode(',', $ids);
                    $updateLocal = "UPDATE " . $prefix . "chapter_files SET sync_status = 2 AND id IN (" . $wid . ")";

                    $updateLocalCommand = Yii::app()->db->createCommand($updateLocal);
                    $updateLocalCommand->execute();
                } else {
                    $total_fm = NULL;
                    $data_fm = NULL;
                }
                $all_data['messages'] = 'success';
                $all_data['data'] = $data_fm;
                echo json_encode($all_data, JSON_PRETTY_PRINT);
                break;

            case 'quiz':
                $cekQuiz = Quiz::model()->findAll(array('condition' => 'sync_status is null', 'order' => 'id ASC'));

                if (!empty($cekQuiz)) {
                    $total_quiz = count($cekQuiz);
                    $data_quiz = array();
                    $raw_quiz = array();
                    $ids = array();
                    foreach ($cekQuiz as $quiz) {
                        $raw_quiz['id'] = $quiz->id;
                        $raw_quiz['title'] = $quiz->title;
                        $raw_quiz['lesson_id'] = $quiz->lesson_id;
                        $raw_quiz['chapter_id'] = $quiz->chapter_id;
                        $raw_quiz['quiz_type'] = $quiz->quiz_type;
                        $raw_quiz['created_at'] = $quiz->created_at;
                        $raw_quiz['updated_at'] = $quiz->updated_at;
                        $raw_quiz['created_by'] = $quiz->created_by;
                        $raw_quiz['updated_by'] = $quiz->updated_by;
                        $raw_quiz['start_time'] = $quiz->start_time;
                        $raw_quiz['end_time'] = $quiz->end_time;
                        $raw_quiz['total_question'] = $quiz->total_question;
                        $raw_quiz['status'] = $quiz->status;
                        $raw_quiz['add_to_summary'] = $quiz->add_to_summary;
                        $raw_quiz['repeat_quiz'] = $quiz->repeat_quiz;
                        $raw_quiz['question'] = $quiz->question;
                        $raw_quiz['semester'] = $quiz->semester;
                        $raw_quiz['year'] = $quiz->year;
                        $raw_quiz['random'] = $quiz->random;
                        $raw_quiz['show_nilai'] = $quiz->show_nilai;
                        array_push($data_quiz, $raw_quiz);
                        array_push($ids, $quiz->id);
                    }
                    $wid = implode(',', $ids);
                    $updateLocal = "UPDATE " . $prefix . "quiz SET sync_status = 2 AND id IN (" . $wid . ")";

                    $updateLocalCommand = Yii::app()->db->createCommand($updateLocal);
                    $updateLocalCommand->execute();
                } else {
                    $total_quiz = NULL;
                    $data_quiz = NULL;
                }
                $all_data['messages'] = 'success';
                $all_data['data'] = $data_quiz;
                echo json_encode($all_data, JSON_PRETTY_PRINT);
                break;

            case 'quizSiswa':
                $cekQuizSiswa = StudentQuiz::model()->findAll(array('condition' => 'sync_status is null', 'order' => 'id ASC'));

                if (!empty($cekQuizSiswa)) {
                    $total_qs = count($cekQuizSiswa);
                    $data_qs = array();
                    $raw_qs = array();
                    $ids = array();
                    foreach ($cekQuizSiswa as $qs) {
                        $raw_qs['id'] = $qs->id;
                        $raw_qs['quiz_id'] = $qs->quiz_id;
                        $raw_qs['student_id'] = $qs->student_id;
                        $raw_qs['created_at'] = $qs->created_at;
                        $raw_qs['updated_at'] = $qs->updated_at;
                        $raw_qs['score'] = $qs->score;
                        $raw_qs['right_answer'] = $qs->right_answer;
                        $raw_qs['wrong_answer'] = $qs->wrong_answer;
                        $raw_qs['unanswered'] = $qs->unanswered;
                        $raw_qs['student_answer'] = $qs->student_answer;
                        $raw_qs['attempt'] = $qs->attempt;
                        array_push($data_qs, $raw_qs);
                        array_push($ids, $qs->id);
                    }
                    $wid = implode(',', $ids);
                    $updateLocal = "UPDATE " . $prefix . "student_quiz SET sync_status = 2 AND id IN (" . $wid . ")";

                    $updateLocalCommand = Yii::app()->db->createCommand($updateLocal);
                    $updateLocalCommand->execute();
                } else {
                    $total_qs = NULL;
                    $data_qs = NULL;
                }
                $all_data['messages'] = 'success';
                $all_data['data'] = $data_qs;
                echo json_encode($all_data, JSON_PRETTY_PRINT);
                break;

            case 'soal':
                $cekSoal = Questions::model()->findAll(array('condition' => 'sync_status is null', 'order' => 'id ASC'));

                if (!empty($cekSoal)) {
                    $total_soal = count($cekSoal);
                    $data_soal = array();
                    $raw_soal = array();
                    $ids = array();
                    foreach ($cekSoal as $soal) {
                        $raw_soal['id'] = $soal->id;
                        $raw_soal['quiz_id'] = $soal->quiz_id;
                        $raw_soal['lesson_id'] = $soal->lesson_id;
                        $raw_soal['title'] = $soal->title;
                        $raw_soal['text'] = $soal->text;
                        $raw_soal['choices'] = $soal->choices;
                        $raw_soal['key_answer'] = $soal->key_answer;
                        $raw_soal['created_at'] = $soal->created_at;
                        $raw_soal['updated_at'] = $soal->updated_at;
                        $raw_soal['teacher_id'] = $soal->teacher_id;
                        $raw_soal['created_by'] = $soal->created_by;
                        $raw_soal['updated_by'] = $soal->updated_by;
                        $raw_soal['file'] = $soal->file;
                        $raw_soal['type'] = $soal->type;
                        $raw_soal['choices_files'] = $soal->choices_files;
                        array_push($data_soal, $raw_soal);
                        array_push($ids, $soal->id);
                    }
                    $wid = implode(',', $ids);
                    $updateLocal = "UPDATE " . $prefix . "questions SET sync_status = 2 AND id IN (" . $wid . ")";

                    $updateLocalCommand = Yii::app()->db->createCommand($updateLocal);
                    $updateLocalCommand->execute();
                } else {
                    $total_soal = NULL;
                    $data_soal = NULL;
                }
                $all_data['messages'] = 'success';
                $all_data['data'] = $data_soal;
                echo json_encode($all_data, JSON_PRETTY_PRINT);
                break;

            case 'lks':
                $cekLks = Lks::model()->findAll(array('condition' => 'sync_status is null', 'order' => 'id ASC'));

                if (!empty($cekLks)) {
                    $total_lks = count($cekLks);
                    $data_lks = array();
                    $raw_lks = array();
                    $ids = array();
                    foreach ($cekLks as $lks) {
                        $raw_lks['id'] = $lks->id;
                        $raw_lks['title'] = $lks->title;
                        $raw_lks['lesson_id'] = $lks->lesson_id;
                        $raw_lks['assignments'] = $lks->assignments;
                        $raw_lks['chapters'] = $lks->chapters;
                        $raw_lks['quizes'] = $lks->quizes;
                        $raw_lks['created_at'] = $lks->created_at;
                        $raw_lks['updated_at'] = $lks->updated_at;
                        $raw_lks['created_by'] = $lks->created_by;
                        $raw_lks['updated_by'] = $lks->updated_by;
                        $raw_lks['teacher_id'] = $lks->teacher_id;
                        array_push($data_lks, $raw_lks);
                        array_push($ids, $lks->id);
                    }
                    $wid = implode(',', $ids);
                    $updateLocal = "UPDATE " . $prefix . "lks SET sync_status = 2 AND id IN (" . $wid . ")";

                    $updateLocalCommand = Yii::app()->db->createCommand($updateLocal);
                    $updateLocalCommand->execute();
                } else {
                    $total_lks = NULL;
                    $data_lks = NULL;
                }
                $all_data['messages'] = 'success';
                $all_data['data'] = $data_lks;
                echo json_encode($all_data, JSON_PRETTY_PRINT);
                break;

            case 'pengumuman':
                $cekPengumuman = Announcements::model()->findAll(array('condition' => 'sync_status is null', 'order' => 'id ASC'));

                if (!empty($cekPengumuman)) {
                    $total_pengumuman = count($cekPengumuman);
                    $data_pengumuman = array();
                    $raw_pengumuman = array();
                    $ids = array();
                    foreach ($cekPengumuman as $pengumuman) {
                        $raw_pengumuman['id'] = $pengumuman->id;
                        $raw_pengumuman['author_id'] = $pengumuman->author_id;
                        $raw_pengumuman['title'] = $pengumuman->title;
                        $raw_pengumuman['created_at'] = $pengumuman->created_at;
                        $raw_pengumuman['updated_at'] = $pengumuman->updated_at;
                        $raw_pengumuman['content'] = $pengumuman->created_by;
                        $raw_pengumuman['type'] = $pengumuman->updated_by;
                        array_push($data_pengumuman, $raw_pengumuman);
                        array_push($ids, $pengumuman->id);
                    }
                    $wid = implode(',', $ids);
                    $updateLocal = "UPDATE " . $prefix . "announcements SET sync_status = 2 AND id IN (" . $wid . ")";

                    $updateLocalCommand = Yii::app()->db->createCommand($updateLocal);
                    $updateLocalCommand->execute();
                } else {
                    $total_pengumuman = NULL;
                    $data_pengumuman = NULL;
                }
                $all_data['messages'] = 'success';
                $all_data['data'] = $data_pengumuman;
                echo json_encode($all_data, JSON_PRETTY_PRINT);
                break;

            case 'aktivitas':
                $cekAktivitas = Activities::model()->findAll(array('condition' => 'sync_status is null', 'order' => 'id ASC'));

                if (!empty($cekAktivitas)) {
                    $total_aktivitas = count($cekAktivitas);
                    $data_aktivitas = array();
                    $raw_aktivitas = array();
                    $ids = array();
                    foreach ($cekAktivitas as $aktivitas) {
                        $raw_aktivitas['id'] = $aktivitas->id;
                        $raw_aktivitas['activity_type'] = $aktivitas->activity_type;
                        $raw_aktivitas['content'] = $aktivitas->content;
                        $raw_aktivitas['created_at'] = $aktivitas->created_at;
                        $raw_aktivitas['updated_at'] = $aktivitas->updated_at;
                        $raw_aktivitas['created_by'] = $aktivitas->created_by;
                        $raw_aktivitas['updated_by'] = $aktivitas->updated_by;
                        array_push($data_aktivitas, $raw_aktivitas);
                        array_push($ids, $aktivitas->id);
                    }
                    $wid = implode(',', $ids);
                    $updateLocal = "UPDATE " . $prefix . "activities SET sync_status = 2 AND id IN (" . $wid . ")";

                    $updateLocalCommand = Yii::app()->db->createCommand($updateLocal);
                    $updateLocalCommand->execute();
                } else {
                    $total_aktivitas = NULL;
                    $data_aktivitas = NULL;
                }
                $all_data['messages'] = 'success';
                $all_data['data'] = $data_aktivitas;
                echo json_encode($all_data, JSON_PRETTY_PRINT);
                break;

            case 'notif':
                $cekNotif = Notification::model()->findAll(array('condition' => 'sync_status is null', 'order' => 'id ASC'));

                if (!empty($cekNotif)) {
                    $total_notif = count($cekNotif);
                    $data_notif = array();
                    $raw_notif = array();
                    $ids = array();
                    foreach ($cekNotif as $notif) {
                        $raw_notif['id'] = $notif->id;
                        $raw_notif['content'] = $notif->content;
                        $raw_notif['user_id'] = $notif->user_id;
                        $raw_notif['user_id_to'] = $notif->user_id_to;
                        $raw_notif['tipe'] = $notif->tipe;
                        $raw_notif['created_at'] = $notif->created_at;
                        $raw_notif['updated_at'] = $notif->updated_at;
                        $raw_notif['read_at'] = $notif->read_at;
                        $raw_notif['status'] = $notif->status;
                        $raw_notif['relation_id'] = $notif->relation_id;
                        $raw_notif['class_id_to'] = $notif->class_id_to;
                        $raw_notif['read_id'] = $notif->read_id;
                        array_push($data_notif, $raw_notif);
                        array_push($ids, $notif->id);
                    }
                    $wid = implode(',', $ids);
                    $updateLocal = "UPDATE " . $prefix . "notification SET sync_status = 2 AND id IN (" . $wid . ")";

                    $updateLocalCommand = Yii::app()->db->createCommand($updateLocal);
                    $updateLocalCommand->execute();
                } else {
                    $total_notif = NULL;
                    $data_notif = NULL;
                }
                $all_data['messages'] = 'success';
                $all_data['data'] = $data_notif;
                echo json_encode($all_data, JSON_PRETTY_PRINT);

                break;

            case 'noffline':
                $cekNilaiOffline = OfflineMark::model()->findAll(array('condition' => 'sync_status is null', 'order' => 'id ASC'));

                if (!empty($cekNilaiOffline)) {
                    $total_nilai_offline = count($cekNilaiOffline);
                    $data_noffline = array();
                    $raw_noffline = array();
                    $ids = array();
                    foreach ($cekNilaiOffline as $noffline) {
                        $raw_noffline['id'] = $noffline->id;
                        $raw_noffline['lesson_id'] = $noffline->lesson_id;
                        $raw_noffline['assignment_id'] = $noffline->assignment_id;
                        $raw_noffline['student_id'] = $noffline->student_id;
                        $raw_noffline['created_at'] = $noffline->created_at;
                        $raw_noffline['updated_at'] = $noffline->updated_at;
                        $raw_noffline['created_by'] = $noffline->created_by;
                        $raw_noffline['updated_by'] = $noffline->updated_by;
                        $raw_noffline['score'] = $noffline->score;
                        $raw_noffline['file'] = $noffline->file;
                        $raw_noffline['mark_type'] = $noffline->mark_type;
                        array_push($data_noffline, $raw_noffline);
                        array_push($ids, $noffline->id);
                    }
                    $wid = implode(',', $ids);
                    $updateLocal = "UPDATE " . $prefix . "offline_mark SET sync_status = 2 AND id IN (" . $wid . ")";

                    $updateLocalCommand = Yii::app()->db->createCommand($updateLocal);
                    $updateLocalCommand->execute();
                } else {
                    $total_nilai_offline = NULL;
                    $data_noffline = NULL;
                }
                $all_data['messages'] = 'success';
                $all_data['data'] = $data_noffline;
                echo json_encode($all_data, JSON_PRETTY_PRINT);
                break;

            case 'kelas':
                $cekKelas = Clases::model()->findAll(array('condition' => 'sync_status is null', 'order' => 'id ASC'));

                if (!empty($cekKelas)) {
                    $total_kelas = count($cekKelas);
                    $data_kelas = array();
                    $raw_kelas = array();
                    $ids = array();
                    foreach ($cekKelas as $kelas) {
                        $raw_kelas['id'] = $kelas->id;
                        $raw_kelas['name'] = $kelas->name;
                        $raw_kelas['teacher_id'] = $kelas->teacher_id;
                        array_push($data_kelas, $raw_kelas);
                        array_push($ids, $kelas->id);
                    }
                    $wid = implode(',', $ids);
                    $updateLocal = "UPDATE " . $prefix . "clases SET sync_status = 2 AND id IN (" . $wid . ")";

                    $updateLocalCommand = Yii::app()->db->createCommand($updateLocal);
                    $updateLocalCommand->execute();
                } else {
                    $total_kelas = NULL;
                    $data_kelas = NULL;
                }
                $all_data['messages'] = 'success';
                $all_data['data'] = $data_kelas;
                echo json_encode($all_data, JSON_PRETTY_PRINT);
                break;

            case 'pelajaran':
                $cekMapel = Lesson::model()->findAll(array('condition' => 'sync_status is null', 'order' => 'id ASC'));

                if (!empty($cekMapel)) {
                    $total_mapel = count($cekMapel);
                    $data_pelajaran = array();
                    $raw_pelajaran = array();
                    $ids = array();
                    foreach ($cekMapel as $pelajaran) {
                        $raw_pelajaran['id'] = $pelajaran->id;
                        $raw_pelajaran['name'] = $pelajaran->name;
                        $raw_pelajaran['user_id'] = $pelajaran->user_id;
                        $raw_pelajaran['class_id'] = $pelajaran->class_id;
                        $raw_pelajaran['created_at'] = $pelajaran->created_at;
                        $raw_pelajaran['updated_at'] = $pelajaran->updated_at;
                        $raw_pelajaran['created_by'] = $pelajaran->created_by;
                        $raw_pelajaran['updated_by'] = $pelajaran->updated_by;
                        array_push($data_pelajaran, $raw_pelajaran);
                        array_push($ids, $pelajaran->id);
                    }
                    $wid = implode(',', $ids);
                    $updateLocal = "UPDATE " . $prefix . "lesson SET sync_status = 2 AND id IN (" . $wid . ")";

                    $updateLocalCommand = Yii::app()->db->createCommand($updateLocal);
                    $updateLocalCommand->execute();
                } else {
                    $total_mapel = NULL;
                    $data_pelajaran = NULL;
                }
                $all_data['messages'] = 'success';
                $all_data['data'] = $data_pelajaran;
                echo json_encode($all_data, JSON_PRETTY_PRINT);
                break;

            case 'absensi':
                $cekAbsensi = Absensi::model()->findAll(array('condition' => 'sync_status is null', 'order' => 'id ASC'));

                if (!empty($cekAbsensi)) {
                    $total_absensi = count($cekAbsensi);
                    $data_absensi = array();
                    $raw_absensi = array();
                    $ids = array();
                    foreach ($cekAbsensi as $absensi) {
                        $raw_absensi['id'] = $absensi->id;
                        $raw_absensi['user_id'] = $absensi->user_id;
                        $raw_absensi['type'] = $absensi->type;
                        $raw_absensi['status'] = $absensi->status;
                        $raw_absensi['alasan'] = $absensi->alasan;
                        $raw_absensi['created_date'] = $absensi->created_date;
                        array_push($data_absensi, $raw_absensi);
                        array_push($ids, $absensi->id);
                    }
                    $wid = implode(',', $ids);
                    $updateLocal = "UPDATE " . $prefix . "absensi SET sync_status = 2 WHERE id IN (" . $wid . ")";

                    $updateLocalCommand = Yii::app()->db->createCommand($updateLocal);
                    $updateLocalCommand->execute();
                } else {
                    $total_absensi = NULL;
                    $data_absensi = NULL;
                }
                $all_data['messages'] = 'success';
                $all_data['data'] = $data_absensi;
                echo json_encode($all_data, JSON_PRETTY_PRINT);
                break;

            default:
                echo "selesai";
                break;
        }
    }

    public function actionSinkronisasi($cekStatus = null) {

        //if($_SERVER['HTTP_HOST'] == "exambox.pinisi"){
        //$url = "http://exambox.pinisi.co/api/sinkronisasidata";
        $post_url = "http://exambox.pinisi.co/api/postlocaldata";
        //}elseif($_SERVER['HTTP_HOST'] == "exambox.pinisi.co"){
        //$url = "http://exambox.pinisi/api/sinkronisasidata";
        //$post_url = "http://exambox.pinisi/api/postlocaldata";
        //}else{
        //$url = "http://exambox.pinisi/api/sinkronisasidata";
        //$post_url = "http://exambox.pinisi/api/postlocaldata";
        //}
        //$i = 14;
        //$cekStatus = NULL;
        date_default_timezone_set("Asia/Jakarta");
        $now_time = date("H:i");
        //echo $now_time;
        //if(($now_time >= "08:00") && ($now_time <= "18:00")){
        //do {
        $prefix = Yii::app()->params['tablePrefix'];
        switch ($cekStatus) {
            case NULL:
                $cekUser = User::model()->findAll(array('condition' => 'sync_status is null', 'order' => 'id ASC'));

                if (!empty($cekUser)) {
                    $total_user = count($cekUser);
                    $pass_user_data = array();
                    $rpud = array();
                    $ids = array();
                    //$tes = array();
                    foreach ($cekUser as $nud) {
                        $rpud['id'] = $nud->id;
                        $rpud['email'] = $nud->email;
                        $rpud['username'] = $nud->username;
                        $rpud['encrypted_password'] = $nud->encrypted_password;
                        $rpud['role_id'] = $nud->role_id;
                        $rpud['created_at'] = $nud->created_at;
                        $rpud['updated_at'] = $nud->updated_at;
                        $rpud['class_id'] = $nud->class_id;
                        $rpud['reset_password'] = $nud->reset_password;
                        $rpud['display_name'] = $nud->display_name;
                        array_push($pass_user_data, $rpud);
                        array_push($ids, $nud->id);
                        //array_push($tes, $nud->id);
                    }
                    $json_user_data = json_encode($pass_user_data, JSON_PRETTY_PRINT);
                    /* echo "<pre>";
                      echo $json_user_data;
                      echo "</pre>"; */

                    $post_user_url = $post_url . "?type=user";
                    $curlUser = curl_init();
                    $wid = implode(',', $ids);

                    curl_setopt_array($curlUser, array(
                        CURLOPT_URL => $post_user_url,
                        CURLOPT_VERBOSE => true,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 3000,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS => $json_user_data,
                        CURLOPT_HTTPHEADER => array(
                            "content-type: application/json"
                        ),
                    ));

                    $responseUser = curl_exec($curlUser);
                    $errUser = curl_error($curlUser);

                    curl_close($curlUser);

                    if ($errUser) {
                        echo "cURL Error #:" . $errUser;
                    } else {
                        $dataUser = json_decode($responseUser);
                        // 	//echo $data->messages;
                        if (!empty($dataUser)) {
                            if ($dataUser->gagal == NULL) {
                                $updateLocal = "UPDATE " . $prefix . "users SET sync_status = 2 WHERE id IN (" . $wid . ")";

                                $updateLocalCommand = Yii::app()->db->createCommand($updateLocal);
                                $updateLocalCommand->execute();
                            }
                        }

                        // 	//$cekStatus = "tugas";
                        echo "tugas";
                    }
                } else {
                    $total_user = NULL;
                    //$cekStatus = "tugas";
                    echo "tugas";
                }
                break;

            case "tugas":
                $cekTugas = Assignment::model()->findAll(array('condition' => 'sync_status is null', 'order' => 'id ASC'));

                if (!empty($cekTugas)) {
                    $total_tugas = count($cekTugas);
                    $data_tugas = array();
                    $raw_tugas = array();
                    $ids = array();
                    foreach ($cekTugas as $tgs) {
                        $raw_tugas['id'] = $tgs->id;
                        $raw_tugas['title'] = $tgs->title;
                        $raw_tugas['content'] = $tgs->content;
                        $raw_tugas['created_at'] = $tgs->created_at;
                        $raw_tugas['updated_at'] = $tgs->updated_at;
                        $raw_tugas['created_by'] = $tgs->created_by;
                        $raw_tugas['updated_by'] = $tgs->updated_by;
                        $raw_tugas['due_date'] = $tgs->due_date;
                        $raw_tugas['lesson_id'] = $tgs->lesson_id;
                        $raw_tugas['file'] = $tgs->file;
                        $raw_tugas['assignment_type'] = $tgs->assignment_type;
                        $raw_tugas['add_to_summary'] = $tgs->add_to_summary;
                        $raw_tugas['recipient'] = $tgs->recipient;
                        $raw_tugas['semester'] = $tgs->semester;
                        $raw_tugas['year'] = $tgs->year;
                        $raw_tugas['status'] = $tgs->status;
                        array_push($data_tugas, $raw_tugas);
                        array_push($ids, $tgs->id);
                    }
                    $json_data_tugas = json_encode($data_tugas, JSON_PRETTY_PRINT);
                    //echo $json_data_tugas;
                    $wid = implode(',', $ids);

                    $post_tugas_url = $post_url . "?type=tugas";
                    $curlTugas = curl_init();

                    curl_setopt_array($curlTugas, array(
                        CURLOPT_URL => $post_tugas_url,
                        CURLOPT_VERBOSE => true,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 3000,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS => $json_data_tugas,
                        CURLOPT_HTTPHEADER => array(
                            "content-type: application/json"
                        ),
                    ));

                    $responseTugas = curl_exec($curlTugas);
                    $errTugas = curl_error($curlTugas);

                    curl_close($curlTugas);

                    if ($errTugas) {
                        echo "cURL Error #:" . $errTugas;
                    } else {
                        $dataTugas = json_decode($responseTugas);
                        //echo $data->messages;
                        if (!empty($dataTugas)) {
                            if ($dataTugas->gagal == NULL) {
                                $updateLocal = "UPDATE " . $prefix . "assignment SET sync_status = 2 WHERE id IN (" . $wid . ")";

                                $updateLocalCommand = Yii::app()->db->createCommand($updateLocal);
                                $updateLocalCommand->execute();
                            }
                        }
                        //$cekStatus = "tugasSiswa";
                        echo "tugasSiswa";
                    }
                } else {
                    $total_tugas = NULL;
                    //$cekStatus = "tugasSiswa";
                    echo "tugasSiswa";
                }
                break;

            case "tugasSiswa":
                $cekTugasSiswa = StudentAssignment::model()->findAll(array('condition' => 'sync_status is null', 'order' => 'id ASC'));

                if (!empty($cekTugasSiswa)) {
                    $total_ts = count($cekTugasSiswa);
                    $data_tugas_siswa = array();
                    $raw_tugas_siswa = array();
                    $ids = array();
                    foreach ($cekTugasSiswa as $ts) {
                        $raw_tugas_siswa['id'] = $ts->id;
                        $raw_tugas_siswa['assignment_id'] = $ts->assignment_id;
                        $raw_tugas_siswa['content'] = $ts->content;
                        $raw_tugas_siswa['file'] = $ts->file;
                        $raw_tugas_siswa['student_id'] = $ts->student_id;
                        $raw_tugas_siswa['score'] = $ts->score;
                        $raw_tugas_siswa['created_at'] = $ts->created_at;
                        $raw_tugas_siswa['updated_at'] = $ts->updated_at;
                        $raw_tugas_siswa['status'] = $ts->status;
                        array_push($data_tugas_siswa, $raw_tugas_siswa);
                        array_push($ids, $ts->id);
                    }
                    $json_data_tugas_siswa = json_encode($data_tugas_siswa, JSON_PRETTY_PRINT);
                    //echo $json_data_tugas_siswa;
                    $wid = implode(',', $ids);

                    $post_tugas_siswa_url = $post_url . "?type=tugasSiswa";
                    $curlTugasSiswa = curl_init();

                    curl_setopt_array($curlTugasSiswa, array(
                        CURLOPT_URL => $post_tugas_siswa_url,
                        CURLOPT_VERBOSE => true,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 3000,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS => $json_data_tugas_siswa,
                        CURLOPT_HTTPHEADER => array(
                            "content-type: application/json"
                        ),
                    ));

                    $responseTugasSiswa = curl_exec($curlTugasSiswa);
                    $errTugasSiswa = curl_error($curlTugasSiswa);

                    curl_close($curlTugasSiswa);

                    if ($errTugasSiswa) {
                        echo "cURL Error #:" . $errTugas;
                    } else {
                        $dataTugasSiswa = json_decode($responseTugasSiswa);
                        //echo $data->messages;
                        if (!empty($dataTugasSiswa)) {
                            if ($dataTugasSiswa->gagal == NULL) {
                                $updateLocal = "UPDATE " . $prefix . "student_assignment SET sync_status = 2 WHERE id IN(" . $wid . ")";

                                $updateLocalCommand = Yii::app()->db->createCommand($updateLocal);
                                $updateLocalCommand->execute();
                            }
                        }

                        //$cekStatus = "materi";
                        echo "materi";
                    }
                } else {
                    $total_ts = NULL;
                    //$cekStatus = "materi";
                    echo "materi";
                }
                break;

            case "materi":
                $cekMateri = Chapters::model()->findAll(array('condition' => 'sync_status is null', 'order' => 'id ASC'));

                if (!empty($cekMateri)) {
                    $total_materi = count($cekMateri);
                    $data_materi = array();
                    $raw_materi = array();
                    $ids = array();
                    foreach ($cekMateri as $materi) {
                        $raw_materi['id'] = $materi->id;
                        $raw_materi['id_lesson'] = $materi->id_lesson;
                        $raw_materi['title'] = $materi->title;
                        $raw_materi['description'] = $materi->description;
                        $raw_materi['created_at'] = $materi->created_at;
                        $raw_materi['updated_at'] = $materi->updated_at;
                        $raw_materi['created_by'] = $materi->created_by;
                        $raw_materi['updated_by'] = $materi->updated_by;
                        $raw_materi['content'] = $materi->content;
                        $raw_materi['chapter_type'] = $materi->chapter_type;
                        $raw_materi['semester'] = $materi->semester;
                        $raw_materi['year'] = $materi->year;
                        array_push($data_materi, $raw_materi);
                        array_push($ids, $materi->id);
                    }
                    $json_data_materi = json_encode($data_materi, JSON_PRETTY_PRINT);
                    //echo $json_data_materi;
                    $wid = implode(',', $ids);

                    $post_materi = $post_url . "?type=materi";
                    $curlMateri = curl_init();

                    curl_setopt_array($curlMateri, array(
                        CURLOPT_URL => $post_materi,
                        CURLOPT_VERBOSE => true,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 3000,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS => $json_data_materi,
                        CURLOPT_HTTPHEADER => array(
                            "content-type: application/json"
                        ),
                    ));

                    $responseMateri = curl_exec($curlMateri);
                    $errMateri = curl_error($curlMateri);

                    curl_close($curlMateri);

                    if ($errMateri) {
                        echo "cURL Error #:" . $errMateri;
                    } else {
                        $dataMateri = json_decode($responseMateri);
                        //echo $data->messages;
                        if (!empty($dataMateri)) {
                            if ($dataMateri->gagal == NULL) {
                                $updateLocal = "UPDATE " . $prefix . "chapters SET sync_status = 2 WHERE id IN(" . $wid . ")";

                                $updateLocalCommand = Yii::app()->db->createCommand($updateLocal);
                                $updateLocalCommand->execute();
                            }
                        }
                        //print_r($dataMateri);
                        //$cekStatus = "materiFile";
                        echo "materiFile";
                    }
                } else {
                    $total_materi = NULL;
                    //$cekStatus = "materiFile";
                    echo "materiFile";
                }
                break;

            case "materiFile":
                $cekFileMateri = ChapterFiles::model()->findAll(array('condition' => 'sync_status is null', 'order' => 'id ASC'));

                if (!empty($cekFileMateri)) {
                    $total_fm = count($cekFileMateri);
                    $data_fm = array();
                    $raw_fm = array();
                    $ids = array();
                    foreach ($cekFileMateri as $cfm) {
                        $raw_fm['id'] = $cfm->id;
                        $raw_fm['id_chapter'] = $cfm->id_chapter;
                        $raw_fm['created_at'] = $cfm->created_at;
                        $raw_fm['updated_at'] = $cfm->updated_at;
                        $raw_fm['created_by'] = $cfm->created_by;
                        $raw_fm['updated_by'] = $cfm->updated_by;
                        $raw_fm['file'] = $cfm->file;
                        $raw_fm['type'] = $cfm->type;
                        $raw_fm['content'] = $cfm->content;
                        array_push($data_fm, $raw_fm);
                        array_push($ids, $cfm->id);
                    }
                    $json_data_fm = json_encode($data_fm);
                    //echo $json_data_fm;
                    $wid = implode(',', $ids);
                    $post_fm = $post_url . "?type=fileMateri";
                    $curlFileMateri = curl_init();

                    curl_setopt_array($curlFileMateri, array(
                        CURLOPT_URL => $post_fm,
                        CURLOPT_VERBOSE => true,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 3000,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS => $json_data_fm,
                        CURLOPT_HTTPHEADER => array(
                            "content-type: application/json"
                        ),
                    ));

                    $responseFileMateri = curl_exec($curlFileMateri);
                    $errFileMateri = curl_error($curlFileMateri);

                    curl_close($curlFileMateri);

                    if ($errFileMateri) {
                        echo "cURL Error #:" . $errFileMateri;
                    } else {
                        $dataFileMateri = json_decode($responseFileMateri);
                        //echo $data->messages;
                        if (!empty($dataFileMateri)) {
                            if ($dataFileMateri->gagal == NULL) {
                                $updateLocal = "UPDATE " . $prefix . "chapter_files SET sync_status = 2 WHERE id IN(" . $wid . ")";

                                $updateLocalCommand = Yii::app()->db->createCommand($updateLocal);
                                $updateLocalCommand->execute();
                            }
                        }

                        //$cekStatus = "quiz";
                        echo "quiz";
                    }
                } else {
                    $total_fm = NULL;
                    //$cekStatus = "quiz";
                    echo "quiz";
                }
                break;

            case "quiz":
                $cekQuiz = Quiz::model()->findAll(array('condition' => 'sync_status is null', 'order' => 'id ASC'));

                if (!empty($cekQuiz)) {
                    $total_quiz = count($cekQuiz);
                    $data_quiz = array();
                    $raw_quiz = array();
                    $ids = array();
                    foreach ($cekQuiz as $quiz) {
                        $raw_quiz['id'] = $quiz->id;
                        $raw_quiz['title'] = $quiz->title;
                        $raw_quiz['lesson_id'] = $quiz->lesson_id;
                        $raw_quiz['chapter_id'] = $quiz->chapter_id;
                        $raw_quiz['quiz_type'] = $quiz->quiz_type;
                        $raw_quiz['created_at'] = $quiz->created_at;
                        $raw_quiz['updated_at'] = $quiz->updated_at;
                        $raw_quiz['created_by'] = $quiz->created_by;
                        $raw_quiz['updated_by'] = $quiz->updated_by;
                        $raw_quiz['start_time'] = $quiz->start_time;
                        $raw_quiz['end_time'] = $quiz->end_time;
                        $raw_quiz['total_question'] = $quiz->total_question;
                        $raw_quiz['status'] = $quiz->status;
                        $raw_quiz['add_to_summary'] = $quiz->add_to_summary;
                        $raw_quiz['repeat_quiz'] = $quiz->repeat_quiz;
                        $raw_quiz['question'] = $quiz->question;
                        $raw_quiz['semester'] = $quiz->semester;
                        $raw_quiz['year'] = $quiz->year;
                        $raw_quiz['random'] = $quiz->random;
                        $raw_quiz['show_nilai'] = $quiz->show_nilai;
                        $raw_quiz['trash'] = $quiz->trash;
                        array_push($data_quiz, $raw_quiz);
                        array_push($ids, $quiz->id);
                    }
                    $json_data_quiz = json_encode($data_quiz, JSON_PRETTY_PRINT);
                    /* echo "<pre>";
                      echo $json_data_quiz;
                      echo "</pre>"; */

                    $wid = implode(',', $ids);

                    $post_quiz = $post_url . "?type=quiz";
                    $curlQuiz = curl_init();

                    curl_setopt_array($curlQuiz, array(
                        CURLOPT_URL => $post_quiz,
                        CURLOPT_VERBOSE => true,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 3000,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS => $json_data_quiz,
                        CURLOPT_HTTPHEADER => array(
                            "content-type: application/json"
                        ),
                    ));

                    $responseQuiz = curl_exec($curlQuiz);
                    $errQuiz = curl_error($curlQuiz);

                    curl_close($curlQuiz);

                    if ($errQuiz) {
                        echo "cURL Error #:" . $errQuiz;
                    } else {
                        $dataQuiz = json_decode($responseQuiz);
                        //echo $data->messages;
                        if (!empty($dataQuiz)) {
                            if ($dataQuiz->gagal == NULL) {
                                $updateLocal = "UPDATE " . $prefix . "quiz SET sync_status = 2 WHERE id IN(" . $wid . ")";

                                $updateLocalCommand = Yii::app()->db->createCommand($updateLocal);
                                $updateLocalCommand->execute();
                            }
                        }

                        //$cekStatus = "quizSiswa";
                        echo "quizSiswa";
                    }
                } else {
                    $total_quiz = NULL;
                    //$cekStatus = "quizSiswa";
                    echo "quizSiswa";
                }
                break;

            case "quizSiswa":
                $cekQuizSiswa = StudentQuiz::model()->findAll(array('condition' => 'sync_status is null', 'order' => 'id ASC'));

                if (!empty($cekQuizSiswa)) {
                    $total_qs = count($cekQuizSiswa);
                    $data_qs = array();
                    $raw_qs = array();
                    $ids = array();
                    foreach ($cekQuizSiswa as $qs) {
                        $raw_qs['id'] = $qs->id;
                        $raw_qs['quiz_id'] = $qs->quiz_id;
                        $raw_qs['student_id'] = $qs->student_id;
                        $raw_qs['created_at'] = $qs->created_at;
                        $raw_qs['updated_at'] = $qs->updated_at;
                        $raw_qs['score'] = $qs->score;
                        $raw_qs['right_answer'] = $qs->right_answer;
                        $raw_qs['wrong_answer'] = $qs->wrong_answer;
                        $raw_qs['unanswered'] = $qs->unanswered;
                        $raw_qs['student_answer'] = $qs->student_answer;
                        $raw_qs['attempt'] = $qs->attempt;
                        array_push($data_qs, $raw_qs);
                        array_push($ids, $qs->id);
                    }
                    $json_data_qs = json_encode($data_qs);
                    //echo $json_data_qs;
                    $wid = implode(',', $ids);

                    $post_qs = $post_url . "?type=quizSiswa";
                    $curlQs = curl_init();

                    curl_setopt_array($curlQs, array(
                        CURLOPT_URL => $post_qs,
                        CURLOPT_VERBOSE => true,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 3000,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS => $json_data_qs,
                        CURLOPT_HTTPHEADER => array(
                            "content-type: application/json"
                        ),
                    ));

                    $responseQs = curl_exec($curlQs);
                    $errQs = curl_error($curlQs);

                    curl_close($curlQs);

                    if ($errQs) {
                        echo "cURL Error #:" . $errQs;
                    } else {
                        $dataQs = json_decode($responseQs);
                        //echo $data->messages;
                        if (!empty($dataQs)) {
                            if ($dataQs->gagal == NULL) {
                                $updateLocal = "UPDATE " . $prefix . "student_quiz SET sync_status = 2 WHERE id IN(" . $wid . ")";

                                $updateLocalCommand = Yii::app()->db->createCommand($updateLocal);
                                $updateLocalCommand->execute();
                            }
                        }

                        //$cekStatus = "soal";
                        echo "soal";
                    }
                } else {
                    $total_qs = NULL;
                    //$cekStatus = "soal";
                    echo "soal";
                }
                break;

            case "soal":
                $cekSoal = Questions::model()->findAll(array('condition' => 'sync_status is null', 'order' => 'id ASC'));

                if (!empty($cekSoal)) {
                    $total_soal = count($cekSoal);
                    $data_soal = array();
                    $raw_soal = array();
                    $ids = array();
                    foreach ($cekSoal as $soal) {
                        $raw_soal['id'] = $soal->id;
                        $raw_soal['quiz_id'] = $soal->quiz_id;
                        $raw_soal['lesson_id'] = $soal->lesson_id;
                        $raw_soal['title'] = $soal->title;
                        $raw_soal['text'] = $soal->text;
                        $raw_soal['choices'] = $soal->choices;
                        $raw_soal['key_answer'] = $soal->key_answer;
                        $raw_soal['created_at'] = $soal->created_at;
                        $raw_soal['updated_at'] = $soal->updated_at;
                        $raw_soal['teacher_id'] = $soal->teacher_id;
                        $raw_soal['created_by'] = $soal->created_by;
                        $raw_soal['updated_by'] = $soal->updated_by;
                        $raw_soal['file'] = $soal->file;
                        $raw_soal['type'] = $soal->type;
                        $raw_soal['choices_files'] = $soal->choices_files;
                        array_push($data_soal, $raw_soal);
                        array_push($ids, $soal->id);
                    }
                    $json_data_soal = json_encode($data_soal);
                    //echo $json_data_soal;
                    $wid = implode(',', $ids);

                    $post_soal = $post_url . "?type=soal";
                    $curlSoal = curl_init();

                    curl_setopt_array($curlSoal, array(
                        CURLOPT_URL => $post_soal,
                        CURLOPT_VERBOSE => true,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 3000,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS => $json_data_soal,
                        CURLOPT_HTTPHEADER => array(
                            "content-type: application/json"
                        ),
                    ));

                    $responseSoal = curl_exec($curlSoal);
                    $errSoal = curl_error($curlSoal);

                    curl_close($curlSoal);

                    if ($errSoal) {
                        echo "cURL Error #:" . $errSoal;
                    } else {
                        $dataSoal = json_decode($responseSoal);
                        //echo $data->messages;
                        if (!empty($dataSoal)) {
                            if ($dataSoal->gagal == NULL) {
                                $updateLocal = "UPDATE " . $prefix . "questions SET sync_status = 2 WHERE id IN(" . $wid . ")";

                                $updateLocalCommand = Yii::app()->db->createCommand($updateLocal);
                                $updateLocalCommand->execute();
                            }
                        }

                        //$cekStatus = "lks";
                        echo "lks";
                    }
                } else {
                    $total_soal = NULL;
                    echo "lks";
                    //$cekStatus = "lks";
                }

                break;
            case "lks":
                $cekLks = Lks::model()->findAll(array('condition' => 'sync_status is null', 'order' => 'id ASC'));

                if (!empty($cekLks)) {
                    $total_lks = count($cekLks);
                    $data_lks = array();
                    $raw_lks = array();
                    $ids = array();
                    foreach ($cekLks as $lks) {
                        $raw_lks['id'] = $lks->id;
                        $raw_lks['title'] = $lks->title;
                        $raw_lks['lesson_id'] = $lks->lesson_id;
                        $raw_lks['assignments'] = $lks->assignments;
                        $raw_lks['chapters'] = $lks->chapters;
                        $raw_lks['quizes'] = $lks->quizes;
                        $raw_lks['created_at'] = $lks->created_at;
                        $raw_lks['updated_at'] = $lks->updated_at;
                        $raw_lks['created_by'] = $lks->created_by;
                        $raw_lks['updated_by'] = $lks->updated_by;
                        $raw_lks['teacher_id'] = $lks->teacher_id;
                        array_push($data_lks, $raw_lks);
                        array_push($ids, $lks->id);
                    }
                    $json_data_lks = json_encode($data_lks);
                    //echo $json_data_tugas;
                    $wid = implode(',', $ids);

                    $post_lks = $post_url . "?type=lks";
                    $curlLks = curl_init();

                    curl_setopt_array($curlLks, array(
                        CURLOPT_URL => $post_lks,
                        CURLOPT_VERBOSE => true,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 3000,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS => $json_data_lks,
                        CURLOPT_HTTPHEADER => array(
                            "content-type: application/json"
                        ),
                    ));

                    $responseLks = curl_exec($curlLks);
                    $errLks = curl_error($curlLks);

                    curl_close($curlLks);

                    if ($errLks) {
                        echo "cURL Error #:" . $errLks;
                    } else {
                        $dataLks = json_decode($responseLks);
                        //echo $data->messages;
                        if (!empty($dataLks)) {
                            if ($dataLks->gagal == NULL) {
                                $updateLocal = "UPDATE " . $prefix . "lks SET sync_status = 2 WHERE id IN(" . $wid . ")";

                                $updateLocalCommand = Yii::app()->db->createCommand($updateLocal);
                                $updateLocalCommand->execute();
                            }
                        }

                        //$cekStatus = "pengumuman";
                        echo "pengumuman";
                    }
                } else {
                    $total_lks = NULL;
                    //$cekStatus = "pengumuman";
                    echo "pengumuman";
                }
                break;

            case "pengumuman":
                $cekPengumuman = Announcements::model()->findAll(array('condition' => 'sync_status is null', 'order' => 'id ASC'));

                if (!empty($cekPengumuman)) {
                    $total_pengumuman = count($cekPengumuman);
                    $data_pengumuman = array();
                    $raw_pengumuman = array();
                    $ids = array();
                    foreach ($cekPengumuman as $pengumuman) {
                        $raw_pengumuman['id'] = $pengumuman->id;
                        $raw_pengumuman['author_id'] = $pengumuman->author_id;
                        $raw_pengumuman['title'] = $pengumuman->title;
                        $raw_pengumuman['created_at'] = $pengumuman->created_at;
                        $raw_pengumuman['updated_at'] = $pengumuman->updated_at;
                        $raw_pengumuman['content'] = $pengumuman->created_by;
                        $raw_pengumuman['type'] = $pengumuman->updated_by;
                        array_push($data_pengumuman, $raw_pengumuman);
                        array_push($ids, $pengumuman->id);
                    }
                    $json_data_pengumuman = json_encode($data_pengumuman);
                    //echo $json_data_tugas;
                    $wid = implode(',', $ids);

                    $post_pengumuman = $post_url . "?type=pengumuman";
                    $curlPengumuman = curl_init();

                    curl_setopt_array($curlPengumuman, array(
                        CURLOPT_URL => $post_pengumuman,
                        CURLOPT_VERBOSE => true,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 3000,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS => $json_data_pengumuman,
                        CURLOPT_HTTPHEADER => array(
                            "content-type: application/json"
                        ),
                    ));

                    $responsePengumuman = curl_exec($curlPengumuman);
                    $errPengumuman = curl_error($curlPengumuman);

                    curl_close($curlPengumuman);

                    if ($errPengumuman) {
                        echo "cURL Error #:" . $errPengumuman;
                    } else {
                        $dataPengumuman = json_decode($responsePengumuman);
                        //echo $data->messages;
                        if (!empty($dataPengumuman)) {
                            if ($dataPengumuman->gagal == NULL) {
                                $updateLocal = "UPDATE " . $prefix . "announcements SET sync_status = 2 WHERE id IN(" . $wid . ")";

                                $updateLocalCommand = Yii::app()->db->createCommand($updateLocal);
                                $updateLocalCommand->execute();
                            }
                        }

                        //$cekStatus = "nilaiOffline";
                        echo "nilaiOffline";
                    }
                } else {
                    $total_pengumuman = NULL;
                    //$cekStatus = "nilaiOffline";
                    echo "nilaiOffline";
                }
                break;

            case "nilaiOffline":
                $cekNilaiOffline = OfflineMark::model()->findAll(array('condition' => 'sync_status is null', 'order' => 'id ASC'));

                if (!empty($cekNilaiOffline)) {
                    $total_nilai_offline = count($cekNilaiOffline);
                    $data_noffline = array();
                    $raw_noffline = array();
                    $ids = array();
                    foreach ($cekNilaiOffline as $noffline) {
                        $raw_noffline['id'] = $noffline->id;
                        $raw_noffline['lesson_id'] = $noffline->lesson_id;
                        $raw_noffline['assignment_id'] = $noffline->assignment_id;
                        $raw_noffline['student_id'] = $noffline->student_id;
                        $raw_noffline['created_at'] = $noffline->created_at;
                        $raw_noffline['updated_at'] = $noffline->updated_at;
                        $raw_noffline['created_by'] = $noffline->created_by;
                        $raw_noffline['updated_by'] = $noffline->updated_by;
                        $raw_noffline['score'] = $noffline->score;
                        $raw_noffline['file'] = $noffline->file;
                        $raw_noffline['mark_type'] = $noffline->mark_type;
                        array_push($data_noffline, $raw_noffline);
                        array_push($ids, $noffline->id);
                    }
                    $json_data_noffline = json_encode($data_noffline);
                    //echo $json_data_tugas;
                    $wid = implode(',', $ids);

                    $post_noffline = $post_url . "?type=noffline";
                    $curlNoffline = curl_init();

                    curl_setopt_array($curlNoffline, array(
                        CURLOPT_URL => $post_noffline,
                        CURLOPT_VERBOSE => true,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 3000,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS => $json_data_noffline,
                        CURLOPT_HTTPHEADER => array(
                            "content-type: application/json"
                        ),
                    ));

                    $responseNoffline = curl_exec($curlNoffline);
                    $errNoffline = curl_error($curlNoffline);

                    curl_close($curlNoffline);

                    if ($errNoffline) {
                        echo "cURL Error #:" . $errNoffline;
                    } else {
                        $dataNoffline = json_decode($responseNoffline);
                        //echo $data->messages;
                        if (!empty($dataNoffline)) {
                            if ($dataNoffline->gagal == NULL) {
                                $updateLocal = "UPDATE " . $prefix . "offline_mark SET sync_status = 2 WHERE id IN(" . $wid . ")";

                                $updateLocalCommand = Yii::app()->db->createCommand($updateLocal);
                                $updateLocalCommand->execute();
                            }
                        }

                        //$cekStatus = "aktivitas";
                        echo "aktivitas";
                    }
                } else {
                    $total_nilai_offline = NULL;
                    //$cekStatus = "aktivitas";
                    echo "aktivitas";
                }
                break;

            case "aktivitas":
                $cekAktivitas = Activities::model()->findAll(array('condition' => 'sync_status is null', 'order' => 'id ASC'));

                if (!empty($cekAktivitas)) {
                    $total_aktivitas = count($cekAktivitas);
                    $data_aktivitas = array();
                    $raw_aktivitas = array();
                    $ids = array();
                    foreach ($cekAktivitas as $aktivitas) {
                        $raw_aktivitas['id'] = $aktivitas->id;
                        $raw_aktivitas['activity_type'] = $aktivitas->activity_type;
                        $raw_aktivitas['content'] = $aktivitas->content;
                        $raw_aktivitas['created_at'] = $aktivitas->created_at;
                        $raw_aktivitas['updated_at'] = $aktivitas->updated_at;
                        $raw_aktivitas['created_by'] = $aktivitas->created_by;
                        $raw_aktivitas['updated_by'] = $aktivitas->updated_by;
                        array_push($data_aktivitas, $raw_aktivitas);
                        array_push($ids, $aktivitas->id);
                    }
                    $json_data_aktivitas = json_encode($data_aktivitas);
                    //echo $json_data_tugas;
                    $wid = implode(',', $ids);

                    $post_aktivitas = $post_url . "?type=aktivitas";
                    $curlAktivitas = curl_init();

                    curl_setopt_array($curlAktivitas, array(
                        CURLOPT_URL => $post_aktivitas,
                        CURLOPT_VERBOSE => true,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 3000,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS => $json_data_aktivitas,
                        CURLOPT_HTTPHEADER => array(
                            "content-type: application/json"
                        ),
                    ));

                    $responseAktivitas = curl_exec($curlAktivitas);
                    $errAktivitas = curl_error($curlAktivitas);

                    curl_close($curlAktivitas);

                    if ($errAktivitas) {
                        echo "cURL Error #:" . $errAktivitas;
                    } else {
                        $dataAktivitas = json_decode($responseAktivitas);
                        //echo $data->messages;
                        if (!empty($dataAktivitas)) {
                            if ($dataAktivitas->gagal == NULL) {
                                $updateLocal = "UPDATE " . $prefix . "activities SET sync_status = 2 WHERE id IN(" . $wid . ")";

                                $updateLocalCommand = Yii::app()->db->createCommand($updateLocal);
                                $updateLocalCommand->execute();
                            }
                        }

                        //$cekStatus = "notifikasi";
                        echo "notifikasi";
                    }
                } else {
                    $total_aktivitas = NULL;
                    //$cekStatus = "notifikasi";
                    echo "notifikasi";
                }
                break;

            case "notifikasi":
                $cekNotif = Notification::model()->findAll(array('condition' => 'sync_status is null', 'order' => 'id ASC'));

                if (!empty($cekNotif)) {
                    $total_notif = count($cekNotif);
                    $data_notif = array();
                    $raw_notif = array();
                    $ids = array();
                    foreach ($cekNotif as $notif) {
                        $raw_notif['id'] = $notif->id;
                        $raw_notif['content'] = $notif->content;
                        $raw_notif['user_id'] = $notif->user_id;
                        $raw_notif['user_id_to'] = $notif->user_id_to;
                        $raw_notif['tipe'] = $notif->tipe;
                        $raw_notif['created_at'] = $notif->created_at;
                        $raw_notif['updated_at'] = $notif->updated_at;
                        $raw_notif['read_at'] = $notif->read_at;
                        $raw_notif['status'] = $notif->status;
                        $raw_notif['relation_id'] = $notif->relation_id;
                        $raw_notif['class_id_to'] = $notif->class_id_to;
                        $raw_notif['read_id'] = $notif->read_id;
                        array_push($data_notif, $raw_notif);
                        array_push($ids, $notif->id);
                    }
                    $json_data_notif = json_encode($data_notif);
                    //echo $json_data_notif;
                    $wid = implode(',', $ids);

                    $post_notif = $post_url . "?type=notif";
                    $curlNotif = curl_init();

                    curl_setopt_array($curlNotif, array(
                        CURLOPT_URL => $post_notif,
                        CURLOPT_VERBOSE => true,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 3000,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS => $json_data_notif,
                        CURLOPT_HTTPHEADER => array(
                            "content-type: application/json"
                        ),
                    ));

                    $responseNotif = curl_exec($curlNotif);
                    $errNotif = curl_error($curlNotif);

                    curl_close($curlNotif);

                    if ($errNotif) {
                        echo "cURL Error #:" . $errNotif;
                    } else {
                        $dataNotif = json_decode($responseNotif);
                        //echo $data->messages;
                        if (!empty($dataNotif)) {
                            if ($dataNotif->gagal == NULL) {
                                $updateLocal = "UPDATE " . $prefix . "notification SET sync_status = 2 WHERE id IN(" . $wid . ")";

                                $updateLocalCommand = Yii::app()->db->createCommand($updateLocal);
                                $updateLocalCommand->execute();
                            }
                        }

                        //$cekStatus = "kelas";
                        echo "kelas";
                    }
                } else {
                    $total_notif = NULL;
                    //$cekStatus = "kelas";
                    echo "kelas";
                }
                break;

            case "kelas":
                $cekKelas = Clases::model()->findAll(array('condition' => 'sync_status is null', 'order' => 'id ASC'));

                if (!empty($cekKelas)) {
                    $total_kelas = count($cekKelas);
                    $data_kelas = array();
                    $raw_kelas = array();
                    $ids = array();
                    foreach ($cekKelas as $kelas) {
                        $raw_kelas['id'] = $kelas->id;
                        $raw_kelas['name'] = $kelas->name;
                        $raw_kelas['teacher_id'] = $kelas->teacher_id;
                        array_push($data_kelas, $raw_kelas);
                        array_push($ids, $kelas->id);
                    }
                    $json_data_kelas = json_encode($data_kelas);
                    //echo $json_data_kelas;
                    $wid = implode(',', $ids);

                    $post_kelas = $post_url . "?type=kelas";
                    $curlKelas = curl_init();

                    curl_setopt_array($curlKelas, array(
                        CURLOPT_URL => $post_kelas,
                        CURLOPT_VERBOSE => true,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 3000,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS => $json_data_kelas,
                        CURLOPT_HTTPHEADER => array(
                            "content-type: application/json"
                        ),
                    ));

                    $responseKelas = curl_exec($curlKelas);
                    $errKelas = curl_error($curlKelas);

                    curl_close($curlKelas);

                    if ($errKelas) {
                        echo "cURL Error #:" . $errKelas;
                    } else {
                        $dataKelas = json_decode($responseKelas);
                        //echo $data->messages;
                        if (!empty($dataMateri)) {
                            if ($dataKelas->gagal == NULL) {
                                $updateLocal = "UPDATE " . $prefix . "class SET sync_status = 2 WHERE id IN(" . $wid . ")";

                                $updateLocalCommand = Yii::app()->db->createCommand($updateLocal);
                                $updateLocalCommand->execute();
                            }
                        }

                        //$cekStatus = "pelajaran";
                        echo "pelajaran";
                    }
                } else {
                    $total_kelas = NULL;
                    //$cekStatus = "pelajaran";
                    echo "pelajaran";
                }
                break;

            case "pelajaran":
                $cekMapel = Lesson::model()->findAll(array('condition' => 'sync_status is null', 'order' => 'id ASC'));

                if (!empty($cekMapel)) {
                    $total_mapel = count($cekMapel);
                    $data_pelajaran = array();
                    $raw_pelajaran = array();
                    $ids = array();
                    foreach ($cekMapel as $pelajaran) {
                        $raw_pelajaran['id'] = $pelajaran->id;
                        $raw_pelajaran['name'] = $pelajaran->name;
                        $raw_pelajaran['user_id'] = $pelajaran->user_id;
                        $raw_pelajaran['class_id'] = $pelajaran->class_id;
                        $raw_pelajaran['created_at'] = $pelajaran->created_at;
                        $raw_pelajaran['updated_at'] = $pelajaran->updated_at;
                        $raw_pelajaran['created_by'] = $pelajaran->created_by;
                        $raw_pelajaran['updated_by'] = $pelajaran->updated_by;
                        array_push($data_pelajaran, $raw_pelajaran);
                        array_push($ids, $pelajaran->id);
                    }
                    $json_data_pelajaran = json_encode($data_pelajaran);
                    //echo $json_data_tugas;
                    $wid = implode(',', $ids);

                    $post_pelajaran = $post_url . "?type=pelajaran";
                    $curlPelajaran = curl_init();

                    curl_setopt_array($curlPelajaran, array(
                        CURLOPT_URL => $post_pelajaran,
                        CURLOPT_VERBOSE => true,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 3000,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS => $json_data_pelajaran,
                        CURLOPT_HTTPHEADER => array(
                            "content-type: application/json"
                        ),
                    ));

                    $responsePelajaran = curl_exec($curlPelajaran);
                    $errPelajaran = curl_error($curlPelajaran);

                    curl_close($curlPelajaran);

                    if ($errPelajaran) {
                        echo "cURL Error #:" . $errPelajaran;
                    } else {
                        $dataPelajaran = json_decode($responsePelajaran);
                        //echo $data->messages;
                        if (!empty($dataPelajaran)) {
                            if ($dataPelajaran->gagal == NULL) {
                                $updateLocal = "UPDATE " . $prefix . "lesson SET sync_status = 2 WHERE id IN(" . $wid . ")";

                                $updateLocalCommand = Yii::app()->db->createCommand($updateLocal);
                                $updateLocalCommand->execute();
                            }
                        }

                        //$cekStatus = "absensi";
                        echo "absensi";
                    }
                } else {
                    $total_mapel = NULL;
                    echo "absensi";
                }
                break;

            case "absensi":
                $cekAbsensi = Absensi::model()->findAll(array('condition' => 'sync_status is null', 'order' => 'id ASC'));

                if (!empty($cekAbsensi)) {
                    $total_absensi = count($cekAbsensi);
                    $data_absensi = array();
                    $raw_absensi = array();
                    $ids = array();
                    foreach ($cekAbsensi as $absensi) {
                        $raw_absensi['id'] = $absensi->id;
                        $raw_absensi['user_id'] = $absensi->user_id;
                        $raw_absensi['type'] = $absensi->type;
                        $raw_absensi['status'] = $absensi->status;
                        $raw_absensi['alasan'] = $absensi->alasan;
                        $raw_absensi['created_date'] = $absensi->created_date;
                        array_push($data_absensi, $raw_absensi);
                        array_push($ids, $absensi->id);
                    }
                    $json_data_absensi = json_encode($data_absensi);
                    //echo $json_data_tugas;
                    $wid = implode(',', $ids);

                    $post_absensi = $post_url . "?type=absensi";
                    $curlAbsensi = curl_init();

                    curl_setopt_array($curlAbsensi, array(
                        CURLOPT_URL => $post_absensi,
                        CURLOPT_VERBOSE => true,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 3000,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS => $json_data_absensi,
                        CURLOPT_HTTPHEADER => array(
                            "content-type: application/json"
                        ),
                    ));

                    $responseAbsensi = curl_exec($curlAbsensi);
                    $errAbsensi = curl_error($curlAbsensi);

                    curl_close($curlAbsensi);

                    if ($errAbsensi) {
                        echo "cURL Error #:" . $errAbsensi;
                    } else {
                        $dataAbsensi = json_decode($responseAbsensi);
                        //echo $data->messages;
                        if (!empty($dataAbsensi)) {
                            if ($dataAbsensi->gagal == NULL) {
                                $updateLocal = "UPDATE " . $prefix . "absensi SET sync_status = 2 WHERE id IN(" . $wid . ")";

                                $updateLocalCommand = Yii::app()->db->createCommand($updateLocal);
                                $updateLocalCommand->execute();
                            }
                        }

                        //$cekStatus = "Selesai";
                        echo "selesai";
                    }
                } else {
                    $total_absensi = NULL;
                    echo "selesai";
                    //$cekStatus = "Selesai";
                }
                break;

            default:
                //echo "selesai";
                break;
        }
        //$i++;
        //} while ($i <= 15);
        //echo $i;
        //echo $cekStatus;
        //}else{
        //}
    }

    public function actionLiveSinkronisasi($cekStatus = null) {
        $url = "http://exambox.pinisi.co/api/sinkronisasiData";

        $berhasil = NULL;
        $gagal = NULL;
        $prefix = Yii::app()->params['tablePrefix'];
        switch ($cekStatus) {
            case 'user':
                $user_url = $url . "?type=user";
                //  Initiate curl
                $curlUser = curl_init();

                curl_setopt_array($curlUser, array(
                    CURLOPT_URL => $user_url,
                    CURLOPT_VERBOSE => true,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 3000,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                ));
                // Execute
                $json_user = curl_exec($curlUser);
                // Closing
                curl_close($curlUser);

                $responseUser = json_decode($json_user, true);
                //print_r($responseUser);
                if (!empty($responseUser['data'])) {
                    //print_r($responseUser);
                    $id_gagal = array();

                    foreach ($responseUser['data'] as $value) {

                        $cekUserLocal = User::model()->findByPk($value['id']);
                        $user_id = $value['id'];
                        $username = $value['username'];
                        $email = $value['email'];
                        $encrypted_password = $value['encrypted_password'];
                        $role_id = $value['role_id'];
                        $created_at = $value['created_at'];
                        $updated_at = $value['updated_at'];
                        $class_id = $value['class_id'];
                        $reset_password = $value['reset_password'];
                        $display_name = $value['display_name'];
                        $sync_status = 1;

                        if (!empty($cekUserLocal)) {

                            $update = "UPDATE " . $prefix . "users SET email = :email, username = :username, encrypted_password = :encrypted_password, role_id = :role_id, created_at = :created_at, updated_at = :updated_at, class_id = :class_id, reset_password = :reset_password, display_name = :display_name, sync_status = :sync_status WHERE id = :id";

                            $updateCommand = Yii::app()->db->createCommand($update);

                            $updateCommand->bindParam(":id", $user_id, PDO::PARAM_STR);
                            $updateCommand->bindParam(":email", $email, PDO::PARAM_STR);
                            $updateCommand->bindParam(":username", $username, PDO::PARAM_STR);
                            $updateCommand->bindParam(":encrypted_password", $encrypted_password, PDO::PARAM_STR);
                            $updateCommand->bindParam(":role_id", $role_id, PDO::PARAM_STR);
                            $updateCommand->bindParam(":created_at", $created_at, PDO::PARAM_STR);
                            $updateCommand->bindParam(":updated_at", $updated_at, PDO::PARAM_STR);
                            $updateCommand->bindParam(":class_id", $class_id, PDO::PARAM_STR);
                            $updateCommand->bindParam(":reset_password", $reset_password, PDO::PARAM_STR);
                            $updateCommand->bindParam(":display_name", $display_name, PDO::PARAM_STR);
                            $updateCommand->bindParam(":sync_status", $sync_status, PDO::PARAM_STR);
                            if ($updateCommand->execute()) {
                                $berhasil = $berhasil + 1;
                            } else {
                                $gagal = $gagal + 1;
                                array_push($id_gagal, $user_id);
                            }
                        } else {

                            $insert = "INSERT INTO " . $prefix . "users (email,username,encrypted_password,role_id,created_at,updated_at,class_id,reset_password,display_name,sync_status) values(:email,:username,:encrypted_password,:role_id,:created_at,:updated_at,:class_id,:reset_password,:display_name,:sync_status)";

                            $insertCommand = Yii::app()->db->createCommand($insert);

                            $insertCommand->bindParam(":email", $email, PDO::PARAM_STR);
                            $insertCommand->bindParam(":username", $username, PDO::PARAM_STR);
                            $insertCommand->bindParam(":encrypted_password", $encrypted_password, PDO::PARAM_STR);
                            $insertCommand->bindParam(":role_id", $role_id, PDO::PARAM_STR);
                            $insertCommand->bindParam(":created_at", $created_at, PDO::PARAM_STR);
                            $insertCommand->bindParam(":updated_at", $updated_at, PDO::PARAM_STR);
                            $insertCommand->bindParam(":class_id", $class_id, PDO::PARAM_STR);
                            $insertCommand->bindParam(":reset_password", $reset_password, PDO::PARAM_STR);
                            $insertCommand->bindParam(":display_name", $display_name, PDO::PARAM_STR);
                            $insertCommand->bindParam(":sync_status", $sync_status, PDO::PARAM_STR);

                            if ($insertCommand->execute()) {
                                $berhasil = $berhasil + 1;
                            } else {
                                $gagal = $gagal + 1;
                                array_push($id_gagal, $user_id);
                            }
                        }
                    }
                    echo "tugas";
                } else {
                    echo "tugas";
                }
                //$cekStatus = "tugas";

                break;

            case "tugas":
                $tugas_url = $url . "?type=tugas";
                //  Initiate curl
                $curlTugas = curl_init();

                curl_setopt_array($curlTugas, array(
                    CURLOPT_URL => $tugas_url,
                    CURLOPT_VERBOSE => true,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 3000,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                ));
                // Execute
                $json_tugas = curl_exec($curlTugas);
                // Closing
                curl_close($curlTugas);

                $responseTugas = json_decode($json_tugas, true);
                $id_gagal = array();
                //print_r($responseTugas);
                if (!empty($responseTugas['data'])) {
                    foreach ($responseTugas['data'] as $value) {
                        $cekTugasLocal = Assignment::model()->findByPk($value['id']);
                        $tugas_id = $value['id'];
                        $title = $value['title'];
                        $content = $value['content'];
                        $created_at = $value['created_at'];
                        $updated_at = $value['updated_at'];
                        $created_by = $value['created_by'];
                        $updated_by = $value['updated_by'];
                        $due_date = $value['due_date'];
                        $lesson_id = $value['lesson_id'];
                        $file = $value['file'];
                        $assignment_type = $value['assignment_type'];
                        $add_to_summary = $value['add_to_summary'];
                        $recipient = $value['recipient'];
                        $semester = $value['semester'];
                        $year = $value['year'];
                        $status = $value['status'];
                        $sync_status = 2;

                        if (!empty($cekTugasLocal)) {

                            $update = "UPDATE " . $prefix . "assignment SET content = :content, title = :title, created_at = :created_at, updated_at = :updated_at, created_by = :created_by, updated_by = :updated_by, due_date = :due_date, lesson_id = :lesson_id, file = :file, assignment_type = :assignment_type, add_to_summary = :add_to_summary, sync_status = :sync_status, recipient = :recipient, semester = :semester, year = :year, status = :status, sync_status = :sync_status WHERE id = :id";

                            $updateCommand = Yii::app()->db->createCommand($update);

                            $updateCommand->bindParam(":id", $tugas_id, PDO::PARAM_STR);
                            $updateCommand->bindParam(":content", $content, PDO::PARAM_STR);
                            $updateCommand->bindParam(":title", $title, PDO::PARAM_STR);
                            $updateCommand->bindParam(":created_at", $created_at, PDO::PARAM_STR);
                            $updateCommand->bindParam(":updated_at", $updated_at, PDO::PARAM_STR);
                            $updateCommand->bindParam(":created_by", $created_by, PDO::PARAM_STR);
                            $updateCommand->bindParam(":updated_by", $updated_by, PDO::PARAM_STR);
                            $updateCommand->bindParam(":due_date", $due_date, PDO::PARAM_STR);
                            $updateCommand->bindParam(":lesson_id", $lesson_id, PDO::PARAM_STR);
                            $updateCommand->bindParam(":file", $file, PDO::PARAM_STR);
                            $updateCommand->bindParam(":assignment_type", $assignment_type, PDO::PARAM_STR);
                            $updateCommand->bindParam(":add_to_summary", $add_to_summary, PDO::PARAM_STR);
                            $updateCommand->bindParam(":recipient", $recipient, PDO::PARAM_STR);
                            $updateCommand->bindParam(":semester", $semester, PDO::PARAM_STR);
                            $updateCommand->bindParam(":year", $year, PDO::PARAM_STR);
                            $updateCommand->bindParam(":status", $status, PDO::PARAM_STR);
                            $updateCommand->bindParam(":sync_status", $sync_status, PDO::PARAM_STR);
                            if ($updateCommand->execute()) {
                                $berhasil = $berhasil + 1;
                            } else {
                                $gagal = $gagal + 1;
                                array_push($id_gagal, $tugas_id);
                            }
                        } else {

                            $insert = "INSERT INTO " . $prefix . "assignment (content,title,created_at,updated_at,created_by,updated_by,due_date,lesson_id,file,assignment_type,add_to_summary,recipient,semester,year,status,sync_status) values(:content,:title,:created_at,:updated_at,:created_by,:updated_by,:due_date,:lesson_id,:file,:assignment_type,:add_to_summary,:recipient,:semester,:year,:status,:sync_status)";

                            $insertCommand = Yii::app()->db->createCommand($insert);

                            $insertCommand->bindParam(":content", $content, PDO::PARAM_STR);
                            $insertCommand->bindParam(":title", $title, PDO::PARAM_STR);
                            $insertCommand->bindParam(":created_at", $created_at, PDO::PARAM_STR);
                            $insertCommand->bindParam(":updated_at", $updated_at, PDO::PARAM_STR);
                            $insertCommand->bindParam(":created_by", $created_by, PDO::PARAM_STR);
                            $insertCommand->bindParam(":updated_by", $updated_by, PDO::PARAM_STR);
                            $insertCommand->bindParam(":due_date", $due_date, PDO::PARAM_STR);
                            $insertCommand->bindParam(":lesson_id", $lesson_id, PDO::PARAM_STR);
                            $insertCommand->bindParam(":file", $file, PDO::PARAM_STR);
                            $insertCommand->bindParam(":assignment_type", $assignment_type, PDO::PARAM_STR);
                            $insertCommand->bindParam(":add_to_summary", $add_to_summary, PDO::PARAM_STR);
                            $insertCommand->bindParam(":recipient", $recipient, PDO::PARAM_STR);
                            $insertCommand->bindParam(":semester", $semester, PDO::PARAM_STR);
                            $insertCommand->bindParam(":year", $year, PDO::PARAM_STR);
                            $insertCommand->bindParam(":status", $status, PDO::PARAM_STR);
                            $insertCommand->bindParam(":sync_status", $sync_status, PDO::PARAM_STR);

                            if ($insertCommand->execute()) {
                                $berhasil = $berhasil + 1;
                            } else {
                                $gagal = $gagal + 1;
                                array_push($id_gagal, $tugas_id);
                            }
                        }
                    }
                    echo "tugasSiswa";
                } else {
                    echo "tugasSiswa";
                }
                //$cekStatus = "tugasSiswa";

                break;

            case "tugasSiswa":
                $ts_url = $url . "?type=tugasSiswa";
                //  Initiate curl
                $curlTs = curl_init();
                // Disable SSL verification
                curl_setopt_array($curlTs, array(
                    CURLOPT_URL => $ts_url,
                    CURLOPT_VERBOSE => true,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 3000,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                ));
                // Execute
                $json_ts = curl_exec($curlTs);
                // Closing
                curl_close($curlTs);

                $responseTs = json_decode($json_ts, true);
                $id_gagal = array();
                //print_r($responseTs);
                if (!empty($responseTs['data'])) {
                    foreach ($responseTs['data'] as $value) {
                        $cekTsLokal = StudentAssignment::model()->findByPk($value['id']);
                        $ts_id = $value['id'];
                        $assignment_id = $value['assignment_id'];
                        $content = $value['content'];
                        $created_at = $value['created_at'];
                        $updated_at = $value['updated_at'];
                        $file = $value['file'];
                        $student_id = $value['student_id'];
                        $score = $value['score'];
                        $status = $value['status'];
                        $sync_status = 2;

                        if (!empty($cekTsLokal)) {

                            $update = "UPDATE " . $prefix . "student_assignment SET content = :content, assignment_id = :assignment_id, created_at = :created_at, updated_at = :updated_at, file = :file, student_id = :student_id, score = :score, status = :status, sync_status = :sync_status WHERE id = :id";

                            $updateCommand = Yii::app()->db->createCommand($update);

                            $updateCommand->bindParam(":id", $ts_id, PDO::PARAM_STR);
                            $updateCommand->bindParam(":content", $content, PDO::PARAM_STR);
                            $updateCommand->bindParam(":assignment_id", $assignment_id, PDO::PARAM_STR);
                            $updateCommand->bindParam(":created_at", $created_at, PDO::PARAM_STR);
                            $updateCommand->bindParam(":updated_at", $updated_at, PDO::PARAM_STR);
                            $updateCommand->bindParam(":file", $file, PDO::PARAM_STR);
                            $updateCommand->bindParam(":student_id", $student_id, PDO::PARAM_STR);
                            $updateCommand->bindParam(":score", $score, PDO::PARAM_STR);
                            $updateCommand->bindParam(":status", $status, PDO::PARAM_STR);
                            $updateCommand->bindParam(":sync_status", $sync_status, PDO::PARAM_STR);
                            if ($updateCommand->execute()) {
                                $berhasil = $berhasil + 1;
                            } else {
                                $gagal = $gagal + 1;
                                array_push($id_gagal, $ts_id);
                            }
                        } else {

                            $insert = "INSERT INTO " . $prefix . "student_assignment (content,assignment_id,created_at,updated_at,file,student_id,score,status,sync_status) values(:content,:assignment_id,:created_at,:updated_at,:file,:student_id,:score,:status,:sync_status)";

                            $insertCommand = Yii::app()->db->createCommand($insert);

                            $insertCommand->bindParam(":content", $content, PDO::PARAM_STR);
                            $insertCommand->bindParam(":assignment_id", $assignment_id, PDO::PARAM_STR);
                            $insertCommand->bindParam(":created_at", $created_at, PDO::PARAM_STR);
                            $insertCommand->bindParam(":updated_at", $updated_at, PDO::PARAM_STR);
                            $insertCommand->bindParam(":file", $file, PDO::PARAM_STR);
                            $insertCommand->bindParam(":student_id", $student_id, PDO::PARAM_STR);
                            $insertCommand->bindParam(":score", $score, PDO::PARAM_STR);
                            $insertCommand->bindParam(":status", $status, PDO::PARAM_STR);
                            $insertCommand->bindParam(":sync_status", $sync_status, PDO::PARAM_STR);

                            if ($insertCommand->execute()) {
                                $berhasil = $berhasil + 1;
                            } else {
                                $gagal = $gagal + 1;
                                array_push($id_gagal, $ts_id);
                            }
                        }
                    }
                    echo "materi";
                } else {
                    echo "materi";
                }
                //$cekStatus = "materi";

                break;

            case "materi":
                $materi_url = $url . "?type=materi";
                //  Initiate curl
                $curlMateri = curl_init();
                // Disable SSL verification
                curl_setopt_array($curlMateri, array(
                    CURLOPT_URL => $materi_url,
                    CURLOPT_VERBOSE => true,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 3000,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                ));
                // Execute
                $json_materi = curl_exec($curlMateri);
                // Closing
                curl_close($curlMateri);

                $responseMateri = json_decode($json_materi, true);
                $id_gagal = array();
                //print_r($responseMateri);

                if (!empty($responseMateri['data'])) {
                    foreach ($responseMateri['data'] as $value) {
                        $cekMateriLokal = Chapters::model()->findByPk($value['id']);
                        $materi_id = $value['id'];
                        $id_lesson = $value['id_lesson'];
                        $title = $value['title'];
                        $description = $value['description'];
                        $created_at = $value['created_at'];
                        $updated_at = $value['updated_at'];
                        $created_by = $value['created_by'];
                        $updated_by = $value['updated_by'];
                        $content = $value['content'];
                        $chapter_type = $value['chapter_type'];
                        $semester = $value['semester'];
                        $year = $value['year'];
                        $sync_status = 2;

                        if (!empty($cekMateriLokal)) {

                            $update = "UPDATE " . $prefix . "chapters SET id_lesson = :id_lesson, title = :title, description = :description, created_at = :created_at, updated_at = :updated_at, created_by = :created_by, updated_by = :updated_by, content = :content, chapter_type = :chapter_type, semester	 = :semester	, year = :year, sync_status = :sync_status WHERE id = :id";

                            $updateCommand = Yii::app()->db->createCommand($update);

                            $updateCommand->bindParam(":id", $materi_id, PDO::PARAM_STR);
                            $updateCommand->bindParam(":id_lesson", $id_lesson, PDO::PARAM_STR);
                            $updateCommand->bindParam(":title", $title, PDO::PARAM_STR);
                            $updateCommand->bindParam(":description", $description, PDO::PARAM_STR);
                            $updateCommand->bindParam(":created_at", $created_at, PDO::PARAM_STR);
                            $updateCommand->bindParam(":updated_at", $updated_at, PDO::PARAM_STR);
                            $updateCommand->bindParam(":created_by", $created_by, PDO::PARAM_STR);
                            $updateCommand->bindParam(":updated_by", $updated_by, PDO::PARAM_STR);
                            $updateCommand->bindParam(":content", $content, PDO::PARAM_STR);
                            $updateCommand->bindParam(":chapter_type", $chapter_type, PDO::PARAM_STR);
                            $updateCommand->bindParam(":semester	", $semester, PDO::PARAM_STR);
                            $updateCommand->bindParam(":year", $year, PDO::PARAM_STR);
                            $updateCommand->bindParam(":sync_status", $sync_status, PDO::PARAM_STR);
                            if ($updateCommand->execute()) {
                                $berhasil = $berhasil + 1;
                            } else {
                                $gagal = $gagal + 1;
                                array_push($id_gagal, $materi_id);
                            }
                        } else {

                            $insert = "INSERT INTO " . $prefix . "chapters (id_lesson,title,description,created_at,updated_at,created_by,updated_by,content,chapter_type,semester,year,sync_status) values(:id_lesson,:title,:description,:created_at,:updated_at,:created_by,:updated_by,:content,:chapter_type,:semester,:year,:sync_status)";

                            $insertCommand = Yii::app()->db->createCommand($insert);

                            $insertCommand->bindParam(":id_lesson", $id_lesson, PDO::PARAM_STR);
                            $insertCommand->bindParam(":title", $title, PDO::PARAM_STR);
                            $insertCommand->bindParam(":description", $description, PDO::PARAM_STR);
                            $insertCommand->bindParam(":created_at", $created_at, PDO::PARAM_STR);
                            $insertCommand->bindParam(":updated_at", $updated_at, PDO::PARAM_STR);
                            $insertCommand->bindParam(":created_by", $created_by, PDO::PARAM_STR);
                            $insertCommand->bindParam(":updated_by", $updated_by, PDO::PARAM_STR);
                            $insertCommand->bindParam(":content", $content, PDO::PARAM_STR);
                            $insertCommand->bindParam(":chapter_type", $chapter_type, PDO::PARAM_STR);
                            $insertCommand->bindParam(":semester", $semester, PDO::PARAM_STR);
                            $insertCommand->bindParam(":year", $year, PDO::PARAM_STR);
                            $insertCommand->bindParam(":sync_status", $sync_status, PDO::PARAM_STR);

                            if ($insertCommand->execute()) {
                                $berhasil = $berhasil + 1;
                            } else {
                                $gagal = $gagal + 1;
                                array_push($id_gagal, $materi_id);
                            }
                        }
                    }
                    echo "materiFile";
                } else {
                    echo "materiFile";
                }
                //$cekStatus = "materiFile";

                break;

            case "materiFile":
                $fm_url = $url . "?type=fileMateri";
                //  Initiate curl
                $curlMf = curl_init();
                // Disable SSL verification
                curl_setopt_array($curlMf, array(
                    CURLOPT_URL => $fm_url,
                    CURLOPT_VERBOSE => true,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 3000,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                ));
                // Execute
                $json_mf = curl_exec($curlMf);
                // Closing
                curl_close($curlMf);

                $responseMf = json_decode($json_mf, true);
                $id_gagal = array();
                //print_r($responseMf);

                if (!empty($responseMf['data'])) {
                    foreach ($responseMf['data'] as $value) {
                        $cekMfLokal = ChapterFiles::model()->findByPk($value['id']);
                        $mf_id = $value['id'];
                        $id_chapter = $value['id_chapter'];
                        $file = $value['file'];
                        $type = $value['type'];
                        $created_at = $value['created_at'];
                        $updated_at = $value['updated_at'];
                        $created_by = $value['created_by'];
                        $updated_by = $value['updated_by'];
                        $content = $value['content'];
                        $sync_status = 2;

                        if (!empty($cekMfLokal)) {

                            $update = "UPDATE " . $prefix . "chapter_files SET id_chapter = :id_chapter, file = :file, type = :type, created_at = :created_at, updated_at = :updated_at, created_by = :created_by, updated_by = :updated_by, content = :content, sync_status = :sync_status WHERE id = :id";

                            $updateCommand = Yii::app()->db->createCommand($update);

                            $updateCommand->bindParam(":id", $mf_id, PDO::PARAM_STR);
                            $updateCommand->bindParam(":id_chapter", $id_chapter, PDO::PARAM_STR);
                            $updateCommand->bindParam(":file", $file, PDO::PARAM_STR);
                            $updateCommand->bindParam(":type", $type, PDO::PARAM_STR);
                            $updateCommand->bindParam(":created_at", $created_at, PDO::PARAM_STR);
                            $updateCommand->bindParam(":updated_at", $updated_at, PDO::PARAM_STR);
                            $updateCommand->bindParam(":created_by", $created_by, PDO::PARAM_STR);
                            $updateCommand->bindParam(":updated_by", $updated_by, PDO::PARAM_STR);
                            $updateCommand->bindParam(":content", $content, PDO::PARAM_STR);
                            $updateCommand->bindParam(":sync_status", $sync_status, PDO::PARAM_STR);
                            if ($updateCommand->execute()) {
                                $berhasil = $berhasil + 1;
                            } else {
                                $gagal = $gagal + 1;
                                array_push($id_gagal, $mf_id);
                            }
                        } else {

                            $insert = "INSERT INTO " . $prefix . "chapter_files (id_chapter,file,type,created_at,updated_at,created_by,updated_by,content,sync_status) values(:id_chapter,:file,:type,:created_at,:updated_at,:created_by,:updated_by,:content,:sync_status)";

                            $insertCommand = Yii::app()->db->createCommand($insert);

                            $insertCommand->bindParam(":content", $content, PDO::PARAM_STR);
                            $insertCommand->bindParam(":id_chapter", $id_chapter, PDO::PARAM_STR);
                            $insertCommand->bindParam(":file", $file, PDO::PARAM_STR);
                            $insertCommand->bindParam(":type", $type, PDO::PARAM_STR);
                            $insertCommand->bindParam(":created_at", $created_at, PDO::PARAM_STR);
                            $insertCommand->bindParam(":updated_at", $updated_at, PDO::PARAM_STR);
                            $insertCommand->bindParam(":created_by", $created_by, PDO::PARAM_STR);
                            $insertCommand->bindParam(":updated_by", $updated_by, PDO::PARAM_STR);
                            $insertCommand->bindParam(":sync_status", $sync_status, PDO::PARAM_STR);

                            if ($insertCommand->execute()) {
                                $berhasil = $berhasil + 1;
                            } else {
                                $gagal = $gagal + 1;
                                array_push($id_gagal, $mf_id);
                            }
                        }
                    }
                    echo "quiz";
                } else {
                    echo "quiz";
                }
                //$cekStatus = "quiz";

                break;

            case "quiz":
                $quiz_url = $url . "?type=quiz";
                //  Initiate curl
                $curlQuiz = curl_init();
                // Disable SSL verification
                curl_setopt_array($curlQuiz, array(
                    CURLOPT_URL => $quiz_url,
                    CURLOPT_VERBOSE => true,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 3000,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                ));
                // Execute
                $json_quiz = curl_exec($curlQuiz);
                // Closing
                curl_close($curlQuiz);

                $responseQuiz = json_decode($json_quiz, true);
                $id_gagal = array();
                //print_r($responseQuiz);

                if (!empty($responseQuiz['data'])) {
                    foreach ($responseQuiz['data'] as $value) {
                        $cekQuizLokal = Quiz::model()->findByPk($value['id']);
                        $quiz_id = $value['id'];
                        $title = $value['title'];
                        $lesson_id = $value['lesson_id'];
                        $chapter_id = $value['chapter_id'];
                        $quiz_type = $value['quiz_type'];
                        $created_at = $value['created_at'];
                        $updated_at = $value['updated_at'];
                        $created_by = $value['created_by'];
                        $updated_by = $value['updated_by'];
                        $start_time = $value['start_time'];
                        $end_time = $value['end_time'];
                        $total_question = $value['total_question'];
                        $status = $value['status'];
                        $add_to_summary = $value['add_to_summary'];
                        $repeat_quiz = $value['repeat_quiz'];
                        $question = $value['question'];
                        $semester = $value['semester'];
                        $year = $value['year'];
                        $random = $value['random'];
                        $random = $value['show_nilai'];
                        $sync_status = 2;

                        if (!empty($cekQuizLokal)) {

                            $update = "UPDATE " . $prefix . "quiz SET title = :title, lesson_id = :lesson_id, chapter_id = :chapter_id, quiz_type = :quiz_type, created_at = :created_at, updated_at = :updated_at, created_by = :created_by, updated_by = :updated_by, start_time = :start_time, end_time = :end_time, total_question = :total_question, status = :status, add_to_summary = :add_to_summary, repeat_quiz = :repeat_quiz, question = :question, semester = :semester, year = :year, random = :random, sync_status = :sync_status, show_nilai = :show_nilai WHERE id = :id";

                            $updateCommand = Yii::app()->db->createCommand($update);

                            $updateCommand->bindParam(":id", $quiz_id, PDO::PARAM_STR);
                            $updateCommand->bindParam(":title", $title, PDO::PARAM_STR);
                            $updateCommand->bindParam(":lesson_id", $lesson_id, PDO::PARAM_STR);
                            $updateCommand->bindParam(":chapter_id", $chapter_id, PDO::PARAM_STR);
                            $updateCommand->bindParam(":quiz_type", $quiz_type, PDO::PARAM_STR);
                            $updateCommand->bindParam(":created_at", $created_at, PDO::PARAM_STR);
                            $updateCommand->bindParam(":updated_at", $updated_at, PDO::PARAM_STR);
                            $updateCommand->bindParam(":created_by", $created_by, PDO::PARAM_STR);
                            $updateCommand->bindParam(":updated_by", $updated_by, PDO::PARAM_STR);
                            $updateCommand->bindParam(":start_time", $start_time, PDO::PARAM_STR);
                            $updateCommand->bindParam(":end_time", $end_time, PDO::PARAM_STR);
                            $updateCommand->bindParam(":total_question", $total_question, PDO::PARAM_STR);
                            $updateCommand->bindParam(":status", $status, PDO::PARAM_STR);
                            $updateCommand->bindParam(":add_to_summary", $add_to_summary, PDO::PARAM_STR);
                            $updateCommand->bindParam(":repeat_quiz", $repeat_quiz, PDO::PARAM_STR);
                            $updateCommand->bindParam(":question", $question, PDO::PARAM_STR);
                            $updateCommand->bindParam(":semester", $semester, PDO::PARAM_STR);
                            $updateCommand->bindParam(":year", $year, PDO::PARAM_STR);
                            $updateCommand->bindParam(":random", $random, PDO::PARAM_STR);
                            $updateCommand->bindParam(":sync_status", $sync_status, PDO::PARAM_STR);
                            $updateCommand->bindParam(":show_nilai", $show_nilai, PDO::PARAM_STR);
                            if ($updateCommand->execute()) {
                                $berhasil = $berhasil + 1;
                            } else {
                                $gagal = $gagal + 1;
                                array_push($id_gagal, $quiz_id);
                            }
                        } else {

                            $insert = "INSERT INTO " . $prefix . "quiz (title,lesson_id,chapter_id,quiz_type,created_at,updated_at,created_by,updated_by,start_time,end_time,total_question,status,add_to_summary,repeat_quiz,question,semester,year,random,sync_status) values(:title,:lesson_id,:chapter_id,:quiz_type,:created_at,:updated_at,:created_by,:updated_by,:start_time,:end_time,:total_question,:status,:add_to_summary,:repeat_quiz,:question,:semester,:year,:random,:sync_status)";

                            $insertCommand = Yii::app()->db->createCommand($insert);

                            $insertCommand->bindParam(":title", $title, PDO::PARAM_STR);
                            $insertCommand->bindParam(":lesson_id", $lesson_id, PDO::PARAM_STR);
                            $insertCommand->bindParam(":chapter_id", $chapter_id, PDO::PARAM_STR);
                            $insertCommand->bindParam(":quiz_type", $quiz_type, PDO::PARAM_STR);
                            $insertCommand->bindParam(":created_at", $created_at, PDO::PARAM_STR);
                            $insertCommand->bindParam(":updated_at", $updated_at, PDO::PARAM_STR);
                            $insertCommand->bindParam(":created_by", $created_by, PDO::PARAM_STR);
                            $insertCommand->bindParam(":updated_by", $updated_by, PDO::PARAM_STR);
                            $insertCommand->bindParam(":start_time", $start_time, PDO::PARAM_STR);
                            $insertCommand->bindParam(":end_time", $end_time, PDO::PARAM_STR);
                            $insertCommand->bindParam(":total_question", $total_question, PDO::PARAM_STR);
                            $insertCommand->bindParam(":status", $status, PDO::PARAM_STR);
                            $insertCommand->bindParam(":add_to_summary", $add_to_summary, PDO::PARAM_STR);
                            $insertCommand->bindParam(":repeat_quiz", $repeat_quiz, PDO::PARAM_STR);
                            $insertCommand->bindParam(":question", $question, PDO::PARAM_STR);
                            $insertCommand->bindParam(":semester", $semester, PDO::PARAM_STR);
                            $insertCommand->bindParam(":year", $year, PDO::PARAM_STR);
                            $insertCommand->bindParam(":random", $random, PDO::PARAM_STR);
                            $insertCommand->bindParam(":sync_status", $sync_status, PDO::PARAM_STR);

                            if ($insertCommand->execute()) {
                                $berhasil = $berhasil + 1;
                            } else {
                                $gagal = $gagal + 1;
                                array_push($id_gagal, $quiz_id);
                            }
                        }
                    }
                    echo "quizSiswa";
                } else {
                    echo "quizSiswa";
                }
                //$cekStatus = "quizSiswa";

                break;

            case "quizSiswa":
                $qs_url = $url . "?type=quizSiswa";
                //  Initiate curl
                $curlQs = curl_init();
                // Disable SSL verification
                curl_setopt_array($curlQs, array(
                    CURLOPT_URL => $qs_url,
                    CURLOPT_VERBOSE => true,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 3000,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                ));
                // Execute
                $json_qs = curl_exec($curlQs);
                // Closing
                curl_close($curlQs);

                $responseQs = json_decode($json_qs, true);
                $id_gagal = array();
                //print_r($responseQs);

                if (!empty($responseQs['data'])) {
                    foreach ($responseQs['data'] as $value) {
                        $cekQzLokal = StudentQuiz::model()->findByPk($value['id']);
                        $qz_id = $value['id'];
                        $quiz_id = $value['quiz_id'];
                        $student_id = $value['student_id'];
                        $created_at = $value['created_at'];
                        $updated_at = $value['updated_at'];
                        $score = $value['score'];
                        $right_answer = $value['right_answer'];
                        $wrong_answer = $value['wrong_answer'];
                        $unanswered = $value['unanswered'];
                        $student_answer = $value['student_answer'];
                        $attempt = $value['attempt'];
                        $sync_status = 2;

                        if (!empty($cekQzLokal)) {

                            $update = "UPDATE " . $prefix . "student_quiz SET quiz_id = :quiz_id, student_id = :student_id, created_at = :created_at, updated_at = :updated_at, score = :score, right_answer = :right_answer, wrong_answer = :wrong_answer, unanswered = :unanswered, student_answer = :student_answer, attempt = :attempt, sync_status = :sync_status WHERE id = :id";

                            $updateCommand = Yii::app()->db->createCommand($update);

                            $updateCommand->bindParam(":id", $qz_id, PDO::PARAM_STR);
                            $updateCommand->bindParam(":quiz_id", $quiz_id, PDO::PARAM_STR);
                            $updateCommand->bindParam(":student_id", $student_id, PDO::PARAM_STR);
                            $updateCommand->bindParam(":created_at", $created_at, PDO::PARAM_STR);
                            $updateCommand->bindParam(":updated_at", $updated_at, PDO::PARAM_STR);
                            $updateCommand->bindParam(":score", $score, PDO::PARAM_STR);
                            $updateCommand->bindParam(":right_answer", $right_answer, PDO::PARAM_STR);
                            $updateCommand->bindParam(":wrong_answer", $wrong_answer, PDO::PARAM_STR);
                            $updateCommand->bindParam(":unanswered", $unanswered, PDO::PARAM_STR);
                            $updateCommand->bindParam(":student_answer", $student_answer, PDO::PARAM_STR);
                            $updateCommand->bindParam(":attempt", $attempt, PDO::PARAM_STR);
                            $updateCommand->bindParam(":sync_status", $sync_status, PDO::PARAM_STR);
                            if ($updateCommand->execute()) {
                                $berhasil = $berhasil + 1;
                            } else {
                                $gagal = $gagal + 1;
                                array_push($id_gagal, $qz_id);
                            }
                        } else {

                            $insert = "INSERT INTO " . $prefix . "student_quiz (quiz_id,student_id,created_at,updated_at,score,right_answer,wrong_answer,unanswered,student_answer,attempt,sync_status) values(:quiz_id,:student_id,:created_at,:updated_at,:score,:right_answer,:wrong_answer,:unanswered,:student_answer,:attempt,:sync_status)";

                            $insertCommand = Yii::app()->db->createCommand($insert);

                            $insertCommand->bindParam(":unanswered", $unanswered, PDO::PARAM_STR);
                            $insertCommand->bindParam(":quiz_id", $quiz_id, PDO::PARAM_STR);
                            $insertCommand->bindParam(":student_id", $student_id, PDO::PARAM_STR);
                            $insertCommand->bindParam(":created_at", $created_at, PDO::PARAM_STR);
                            $insertCommand->bindParam(":updated_at", $updated_at, PDO::PARAM_STR);
                            $insertCommand->bindParam(":score", $score, PDO::PARAM_STR);
                            $insertCommand->bindParam(":right_answer", $right_answer, PDO::PARAM_STR);
                            $insertCommand->bindParam(":wrong_answer", $wrong_answer, PDO::PARAM_STR);
                            $insertCommand->bindParam(":student_answer", $student_answer, PDO::PARAM_STR);
                            $insertCommand->bindParam(":attempt", $attempt, PDO::PARAM_STR);
                            $insertCommand->bindParam(":sync_status", $sync_status, PDO::PARAM_STR);

                            if ($insertCommand->execute()) {
                                $berhasil = $berhasil + 1;
                            } else {
                                $gagal = $gagal + 1;
                                array_push($id_gagal, $qz_id);
                            }
                        }
                    }
                    echo "soal";
                } else {
                    echo "soal";
                }
                //$cekStatus = "soal";

                break;

            case "soal":
                $soal_url = $url . "?type=soal";
                //  Initiate curl
                $curlSoal = curl_init();
                // Disable SSL verification
                curl_setopt_array($curlSoal, array(
                    CURLOPT_URL => $soal_url,
                    CURLOPT_VERBOSE => true,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 3000,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                ));
                // Execute
                $json_soal = curl_exec($curlSoal);
                // Closing
                curl_close($curlSoal);

                $responseSoal = json_decode($json_soal, true);
                $id_gagal = array();
                //print_r($responseSoal);

                if (!empty($responseSoal['data'])) {
                    foreach ($responseSoal['data'] as $value) {
                        $cekQuestionLokal = Questions::model()->findByPk($value['id']);
                        $question_id = $value['id'];
                        $id_quiz = $value['quiz_id'];
                        $lesson_id = $value['lesson_id'];
                        $title = $value['title'];
                        $text = $value['text'];
                        $choices = $value['choices'];
                        $key_answer = $value['key_answer'];
                        $created_at = $value['created_at'];
                        $updated_at = $value['updated_at'];
                        $teacher_id = $value['teacher_id'];
                        $created_by = $value['created_by'];
                        $updated_by = $value['updated_by'];
                        $file = $value['file'];
                        $type = $value['type'];
                        $choices_files = $value['choices_files'];
                        $sync_status = 2;

                        if (!empty($cekQuestionLokal)) {

                            $update = "UPDATE " . $prefix . "questions SET quiz_id = :quiz_id, lesson_id = :lesson_id, title = :title, text = :text, choices = :choices, key_answer = :key_answer, created_at = :created_at, updated_at = :updated_at, teacher_id = :teacher_id, created_by = :created_by, updated_by = :updated_by, file = :file, type = :type, choices_files = :choices_files, sync_status = :sync_status WHERE id = :id";

                            $updateCommand = Yii::app()->db->createCommand($update);

                            $updateCommand->bindParam(":id", $question_id, PDO::PARAM_STR);
                            $updateCommand->bindParam(":quiz_id", $id_quiz, PDO::PARAM_STR);
                            $updateCommand->bindParam(":lesson_id", $lesson_id, PDO::PARAM_STR);
                            $updateCommand->bindParam(":title", $title, PDO::PARAM_STR);
                            $updateCommand->bindParam(":text", $text, PDO::PARAM_STR);
                            $updateCommand->bindParam(":choices", $choices, PDO::PARAM_STR);
                            $updateCommand->bindParam(":key_answer", $key_answer, PDO::PARAM_STR);
                            $updateCommand->bindParam(":created_at", $created_at, PDO::PARAM_STR);
                            $updateCommand->bindParam(":updated_at", $updated_at, PDO::PARAM_STR);
                            $updateCommand->bindParam(":teacher_id", $teacher_id, PDO::PARAM_STR);
                            $updateCommand->bindParam(":created_by", $created_by, PDO::PARAM_STR);
                            $updateCommand->bindParam(":updated_by", $updated_by, PDO::PARAM_STR);
                            $updateCommand->bindParam(":file", $file, PDO::PARAM_STR);
                            $updateCommand->bindParam(":type", $type, PDO::PARAM_STR);
                            $updateCommand->bindParam(":choices_files", $choices_files, PDO::PARAM_STR);
                            $updateCommand->bindParam(":sync_status", $sync_status, PDO::PARAM_STR);
                            if ($updateCommand->execute()) {
                                $berhasil = $berhasil + 1;
                            } else {
                                $gagal = $gagal + 1;
                                array_push($id_gagal, $question_id);
                            }
                        } else {

                            $insert = "INSERT INTO " . $prefix . "questions (quiz_id,lesson_id,title,text,choices,key_answer,created_at,updated_at,teacher_id,created_by,updated_by,file,type,choices_files,sync_status) values(:quiz_id,:lesson_id,:title,:text,:choices,:key_answer,:created_at,:updated_at,:teacher_id,:created_by,:updated_by,:file,:type,:choices_files,:sync_status)";

                            $insertCommand = Yii::app()->db->createCommand($insert);

                            $insertCommand->bindParam(":updated_at", $updated_at, PDO::PARAM_STR);
                            $insertCommand->bindParam(":quiz_id", $id_quiz, PDO::PARAM_STR);
                            $insertCommand->bindParam(":lesson_id", $lesson_id, PDO::PARAM_STR);
                            $insertCommand->bindParam(":title", $title, PDO::PARAM_STR);
                            $insertCommand->bindParam(":text", $text, PDO::PARAM_STR);
                            $insertCommand->bindParam(":choices", $choices, PDO::PARAM_STR);
                            $insertCommand->bindParam(":key_answer", $key_answer, PDO::PARAM_STR);
                            $insertCommand->bindParam(":created_at", $created_at, PDO::PARAM_STR);
                            $insertCommand->bindParam(":teacher_id", $teacher_id, PDO::PARAM_STR);
                            $insertCommand->bindParam(":created_by", $created_by, PDO::PARAM_STR);
                            $insertCommand->bindParam(":updated_by", $updated_by, PDO::PARAM_STR);
                            $insertCommand->bindParam(":file", $file, PDO::PARAM_STR);
                            $insertCommand->bindParam(":type", $type, PDO::PARAM_STR);
                            $insertCommand->bindParam(":choices_files", $choices_files, PDO::PARAM_STR);
                            $insertCommand->bindParam(":sync_status", $sync_status, PDO::PARAM_STR);

                            if ($insertCommand->execute()) {
                                $berhasil = $berhasil + 1;
                            } else {
                                $gagal = $gagal + 1;
                                array_push($id_gagal, $question_id);
                            }
                        }
                    }
                    echo "lks";
                } else {
                    echo "lks";
                }
                //$cekStatus = "lks";

                break;
            case "lks":
                $lks_url = $url . "?type=lks";
                //  Initiate curl
                $curlLks = curl_init();
                // Disable SSL verification
                curl_setopt_array($curlLks, array(
                    CURLOPT_URL => $lks_url,
                    CURLOPT_VERBOSE => true,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 3000,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                ));
                // Execute
                $json_lks = curl_exec($curlLks);
                // Closing
                curl_close($curlLks);

                $responseLks = json_decode($json_lks, true);
                $id_gagal = array();
                if (!empty($responseLks['data'])) {
                    foreach ($responseLks['data'] as $value) {
                        $cekLksLokal = Lks::model()->findByPk($value['id']);
                        $lks_id = $value['id'];
                        $title = $value['title'];
                        $lesson_id = $value['lesson_id'];
                        $assignments = $value['assignments'];
                        $chapters = $value['chapters'];
                        $quizes = $value['quizes'];
                        $created_at = $value['created_at'];
                        $updated_at = $value['updated_at'];
                        $teacher_id = $value['teacher_id'];
                        $created_by = $value['created_by'];
                        $updated_by = $value['updated_by'];
                        $file = $value['file'];
                        $type = $value['type'];
                        $choices_files = $value['choices_files'];
                        $sync_status = 2;

                        if (!empty($cekLksLokal)) {

                            $update = "UPDATE " . $prefix . "lks SET title = :title, lesson_id = :lesson_id, assignments = :assignments, chapters = :chapters, quizes = :quizes, created_at = :created_at, updated_at = :updated_at, teacher_id = :teacher_id, created_by = :created_by, updated_by = :updated_by, sync_status = :sync_status WHERE id = :id";

                            $updateCommand = Yii::app()->db->createCommand($update);

                            $updateCommand->bindParam(":id", $lks_id, PDO::PARAM_STR);
                            $updateCommand->bindParam(":title", $title, PDO::PARAM_STR);
                            $updateCommand->bindParam(":lesson_id", $lesson_id, PDO::PARAM_STR);
                            $updateCommand->bindParam(":assignments", $assignments, PDO::PARAM_STR);
                            $updateCommand->bindParam(":chapters", $chapters, PDO::PARAM_STR);
                            $updateCommand->bindParam(":quizes", $quizes, PDO::PARAM_STR);
                            $updateCommand->bindParam(":created_at", $created_at, PDO::PARAM_STR);
                            $updateCommand->bindParam(":updated_at", $updated_at, PDO::PARAM_STR);
                            $updateCommand->bindParam(":teacher_id", $teacher_id, PDO::PARAM_STR);
                            $updateCommand->bindParam(":created_by", $created_by, PDO::PARAM_STR);
                            $updateCommand->bindParam(":updated_by", $updated_by, PDO::PARAM_STR);
                            $updateCommand->bindParam(":sync_status", $sync_status, PDO::PARAM_STR);
                            if ($updateCommand->execute()) {
                                $berhasil = $berhasil + 1;
                            } else {
                                $gagal = $gagal + 1;
                                array_push($id_gagal, $lks_id);
                            }
                        } else {

                            $insert = "INSERT INTO " . $prefix . "lks (title,lesson_id,assignments,chapters,quizes,created_at,updated_at,teacher_id,created_by,sync_status) values(:title,:lesson_id,:assignments,:chapters,:quizes,:created_at,:updated_at,:teacher_id,:created_by,:sync_status)";

                            $insertCommand = Yii::app()->db->createCommand($insert);

                            $insertCommand->bindParam(":updated_at", $updated_at, PDO::PARAM_STR);
                            $insertCommand->bindParam(":title", $title, PDO::PARAM_STR);
                            $insertCommand->bindParam(":lesson_id", $lesson_id, PDO::PARAM_STR);
                            $insertCommand->bindParam(":assignments", $assignments, PDO::PARAM_STR);
                            $insertCommand->bindParam(":chapters", $chapters, PDO::PARAM_STR);
                            $insertCommand->bindParam(":quizes", $quizes, PDO::PARAM_STR);
                            $insertCommand->bindParam(":updated_at", $updated_at, PDO::PARAM_STR);
                            $insertCommand->bindParam(":created_at", $created_at, PDO::PARAM_STR);
                            $insertCommand->bindParam(":teacher_id", $teacher_id, PDO::PARAM_STR);
                            $insertCommand->bindParam(":created_by", $created_by, PDO::PARAM_STR);
                            $insertCommand->bindParam(":updated_by", $updated_by, PDO::PARAM_STR);
                            $insertCommand->bindParam(":sync_status", $sync_status, PDO::PARAM_STR);

                            if ($insertCommand->execute()) {
                                $berhasil = $berhasil + 1;
                            } else {
                                $gagal = $gagal + 1;
                                array_push($id_gagal, $lks_id);
                            }
                        }
                    }
                    echo "pengumuman";
                } else {
                    echo "pengumuman";
                }
                //$cekStatus = "pengumuman";

                break;

            case "pengumuman":
                $pengumuman_url = $url . "?type=pengumuman";
                //  Initiate curl
                $curlPengumuman = curl_init();
                // Disable SSL verification
                curl_setopt_array($curlPengumuman, array(
                    CURLOPT_URL => $pengumuman_url,
                    CURLOPT_VERBOSE => true,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 3000,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                ));
                // Execute
                $json_pengumuman = curl_exec($curlPengumuman);
                // Closing
                curl_close($curlPengumuman);

                $responsePengumuman = json_decode($json_pengumuman, true);
                $id_gagal = array();
                if (!empty($responsePengumuman['data'])) {
                    foreach ($responsePengumuman['data'] as $value) {
                        $cekPengumumanLokal = Announcements::model()->findByPk($value['id']);
                        $pengumuman_id = $value['id'];
                        $author_id = $value['author_id'];
                        $title = $value['title'];
                        $content = $value['content'];
                        $created_at = $value['created_at'];
                        $updated_at = $value['updated_at'];
                        $type = $value['type'];
                        $sync_status = 2;

                        if (!empty($cekPengumumanLokal)) {

                            $update = "UPDATE " . $prefix . "pengumuman SET author_id = :author_id, title = :title, content = :content, created_at = :created_at, updated_at = :updated_at, type = :type, created_by = :created_by, updated_by = :updated_by, sync_status = :sync_status WHERE id = :id";

                            $updateCommand = Yii::app()->db->createCommand($update);

                            $updateCommand->bindParam(":id", $pengumuman_id, PDO::PARAM_STR);
                            $updateCommand->bindParam(":author_id", $author_id, PDO::PARAM_STR);
                            $updateCommand->bindParam(":title", $title, PDO::PARAM_STR);
                            $updateCommand->bindParam(":content", $content, PDO::PARAM_STR);
                            $updateCommand->bindParam(":created_at", $created_at, PDO::PARAM_STR);
                            $updateCommand->bindParam(":updated_at", $updated_at, PDO::PARAM_STR);
                            $updateCommand->bindParam(":type", $type, PDO::PARAM_STR);
                            $updateCommand->bindParam(":sync_status", $sync_status, PDO::PARAM_STR);
                            if ($updateCommand->execute()) {
                                $berhasil = $berhasil + 1;
                            } else {
                                $gagal = $gagal + 1;
                                array_push($id_gagal, $pengumuman_id);
                            }
                        } else {

                            $insert = "INSERT INTO " . $prefix . "pengumuman (author_id,title,content,created_at,updated_at,type,created_by,sync_status) values(:author_id,:title,:content,:created_at,:updated_at,:type,:sync_status)";

                            $insertCommand = Yii::app()->db->createCommand($insert);

                            $insertCommand->bindParam(":updated_at", $updated_at, PDO::PARAM_STR);
                            $insertCommand->bindParam(":author_id", $author_id, PDO::PARAM_STR);
                            $insertCommand->bindParam(":title", $title, PDO::PARAM_STR);
                            $insertCommand->bindParam(":content", $content, PDO::PARAM_STR);
                            $insertCommand->bindParam(":updated_at", $updated_at, PDO::PARAM_STR);
                            $insertCommand->bindParam(":created_at", $created_at, PDO::PARAM_STR);
                            $insertCommand->bindParam(":type", $type, PDO::PARAM_STR);
                            $insertCommand->bindParam(":sync_status", $sync_status, PDO::PARAM_STR);

                            if ($insertCommand->execute()) {
                                $berhasil = $berhasil + 1;
                            } else {
                                $gagal = $gagal + 1;
                                array_push($id_gagal, $pengumuman_id);
                            }
                        }
                    }
                    echo "nilaiOffline";
                } else {
                    echo "nilaiOffline";
                }
                //$cekStatus = "nilaiOffline";

                break;

            case "nilaiOffline":
                $noffline_url = $url . "?type=noffline";
                //  Initiate curl
                $curlNoffline = curl_init();
                // Disable SSL verification
                curl_setopt_array($curlNoffline, array(
                    CURLOPT_URL => $noffline_url,
                    CURLOPT_VERBOSE => true,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 3000,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                ));
                // Execute
                $json_noffline = curl_exec($curlNoffline);
                // Closing
                curl_close($curlNoffline);

                $responseNoffline = json_decode($json_noffline, true);
                $id_gagal = array();
                if (!empty($responseNoffline['data'])) {
                    foreach ($responseNoffline['data'] as $value) {
                        $cekNofflineLokal = OfflineMark::model()->findByPk($value['id']);
                        $noffline_id = $value['id'];
                        $lesson_id = $value['lesson_id'];
                        $student_id = $value['student_id'];
                        $score = $value['score'];
                        $file = $value['file'];
                        $created_at = $value['created_at'];
                        $updated_at = $value['updated_at'];
                        $created_by = $value['created_by'];
                        $updated_by = $value['updated_by'];
                        $mark_type = $value['mark_type'];
                        $sync_status = 2;

                        if (!empty($cekNofflineLokal)) {

                            $update = "UPDATE " . $prefix . "offline_mark SET lesson_id = :lesson_id, student_id = :student_id, score = :score, file = :file, created_at = :created_at, updated_at = :updated_at, created_by = :created_by, updated_by = :updated_by, mark_type = :mark_type, sync_status = :sync_status WHERE id = :id";

                            $updateCommand = Yii::app()->db->createCommand($update);

                            $updateCommand->bindParam(":id", $noffline_id, PDO::PARAM_STR);
                            $updateCommand->bindParam(":lesson_id", $lesson_id, PDO::PARAM_STR);
                            $updateCommand->bindParam(":student_id", $student_id, PDO::PARAM_STR);
                            $updateCommand->bindParam(":score", $score, PDO::PARAM_STR);
                            $updateCommand->bindParam(":file", $type, PDO::PARAM_STR);
                            $updateCommand->bindParam(":created_at", $created_at, PDO::PARAM_STR);
                            $updateCommand->bindParam(":updated_at", $updated_at, PDO::PARAM_STR);
                            $updateCommand->bindParam(":created_by", $created_by, PDO::PARAM_STR);
                            $updateCommand->bindParam(":updated_by", $updated_by, PDO::PARAM_STR);
                            $updateCommand->bindParam(":mark_type", $mark_type, PDO::PARAM_STR);
                            $updateCommand->bindParam(":sync_status", $sync_status, PDO::PARAM_STR);
                            if ($updateCommand->execute()) {
                                $berhasil = $berhasil + 1;
                            } else {
                                $gagal = $gagal + 1;
                                array_push($id_gagal, $noffline_id);
                            }
                        } else {

                            $insert = "INSERT INTO " . $prefix . "offline_mark (lesson_id,score,file,created_at,updated_at,created_by,updated_by,mark_type,sync_status) values(:lesson_id,:student_id,:score,:file,:created_at,:updated_at,:created_by,:updated_by,:mark_type,:sync_status)";

                            $insertCommand = Yii::app()->db->createCommand($insert);

                            $insertCommand->bindParam(":updated_at", $updated_at, PDO::PARAM_STR);
                            $insertCommand->bindParam(":lesson_id", $lesson_id, PDO::PARAM_STR);
                            $insertCommand->bindParam(":student_id", $student_id, PDO::PARAM_STR);
                            $insertCommand->bindParam(":score", $score, PDO::PARAM_STR);
                            $insertCommand->bindParam(":file", $type, PDO::PARAM_STR);
                            $insertCommand->bindParam(":updated_at", $updated_at, PDO::PARAM_STR);
                            $insertCommand->bindParam(":created_at", $created_at, PDO::PARAM_STR);
                            $insertCommand->bindParam(":created_by", $created_by, PDO::PARAM_STR);
                            $insertCommand->bindParam(":updated_by", $updated_by, PDO::PARAM_STR);
                            $insertCommand->bindParam(":mark_type", $mark_type, PDO::PARAM_STR);
                            $insertCommand->bindParam(":sync_status", $sync_status, PDO::PARAM_STR);

                            if ($insertCommand->execute()) {
                                $berhasil = $berhasil + 1;
                            } else {
                                $gagal = $gagal + 1;
                                array_push($id_gagal, $noffline_id);
                            }
                        }
                    }
                    echo "aktivitas";
                } else {
                    echo "aktivitas";
                }
                //$cekStatus = "aktivitas";

                break;

            case "aktivitas":
                $aktivitas_url = $url . "?type=aktivitas";
                //  Initiate curl
                $curlAktivitas = curl_init();
                // Disable SSL verification
                curl_setopt_array($curlAktivitas, array(
                    CURLOPT_URL => $aktivitas_url,
                    CURLOPT_VERBOSE => true,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 3000,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                ));
                // Execute
                $json_aktivitas = curl_exec($curlAktivitas);
                // Closing
                curl_close($curlAktivitas);

                $responseAktivitas = json_decode($json_aktivitas, true);

                $id_gagal = array();
                if (!empty($responseAktivitas['data'])) {
                    foreach ($responseAktivitas['data'] as $value) {
                        $cekAktivitasLokal = Activities::model()->findByPk($value['id']);
                        $aktivitas_id = $value['id'];
                        $activity_type = $value['activity_type'];
                        $content = $value['content'];
                        $created_by = $value['created_by'];
                        $updated_by = $value['updated_by'];
                        $created_at = $value['created_at'];
                        $updated_at = $value['updated_at'];
                        $sync_status = 2;

                        if (!empty($cekAktivitasLokal)) {

                            $update = "UPDATE " . $prefix . "activities SET activity_type = :activity_type, content = :content, created_by = :created_by, updated_by = :updated_by, created_at = :created_at, updated_at = :updated_at, sync_status = :sync_status WHERE id = :id";

                            $updateCommand = Yii::app()->db->createCommand($update);

                            $updateCommand->bindParam(":id", $aktivitas_id, PDO::PARAM_STR);
                            $updateCommand->bindParam(":activity_type", $activity_type, PDO::PARAM_STR);
                            $updateCommand->bindParam(":content", $content, PDO::PARAM_STR);
                            $updateCommand->bindParam(":created_by", $created_by, PDO::PARAM_STR);
                            $updateCommand->bindParam(":created_at", $created_at, PDO::PARAM_STR);
                            $updateCommand->bindParam(":updated_at", $updated_at, PDO::PARAM_STR);
                            $updateCommand->bindParam(":updated_by", $type, PDO::PARAM_STR);
                            $updateCommand->bindParam(":sync_status", $sync_status, PDO::PARAM_STR);
                            if ($updateCommand->execute()) {
                                $berhasil = $berhasil + 1;
                            } else {
                                $gagal = $gagal + 1;
                                array_push($id_gagal, $aktivitas_id);
                            }
                        } else {

                            $insert = "INSERT INTO " . $prefix . "activities (activity_type,content,created_by,updated_by,created_at,updated_at,sync_status) values(:activity_type,:content,:created_by,:updated_by,:created_at,:updated_at,:sync_status)";

                            $insertCommand = Yii::app()->db->createCommand($insert);

                            $insertCommand->bindParam(":updated_at", $updated_at, PDO::PARAM_STR);
                            $insertCommand->bindParam(":activity_type", $activity_type, PDO::PARAM_STR);
                            $insertCommand->bindParam(":content", $content, PDO::PARAM_STR);
                            $insertCommand->bindParam(":created_by", $created_by, PDO::PARAM_STR);
                            $insertCommand->bindParam(":updated_at", $updated_at, PDO::PARAM_STR);
                            $insertCommand->bindParam(":created_at", $created_at, PDO::PARAM_STR);
                            $insertCommand->bindParam(":updated_by", $type, PDO::PARAM_STR);
                            $insertCommand->bindParam(":sync_status", $sync_status, PDO::PARAM_STR);

                            if ($insertCommand->execute()) {
                                $berhasil = $berhasil + 1;
                            } else {
                                $gagal = $gagal + 1;
                                array_push($id_gagal, $aktivitas_id);
                            }
                        }
                    }
                    //echo "<pre>";
                    //print_r($responseAktivitas['data']);
                    //echo "<pre>";
                    //echo json_encode(array('messages'=>'success','berhasil'=>$berhasil,'gagal'=>$gagal,'id_gagal'=>$id_gagal));
                    echo "notifikasi";
                } else {
                    echo "notifikasi";
                }
                //$cekStatus = "notifikasi";

                break;

            case "notifikasi":
                $notif_url = $url . "?type=notif";
                //  Initiate curl
                $curlNotif = curl_init();
                // Disable SSL verification
                curl_setopt_array($curlNotif, array(
                    CURLOPT_URL => $notif_url,
                    CURLOPT_VERBOSE => true,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 3000,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                ));
                // Execute
                $json_notif = curl_exec($curlNotif);
                // Closing
                curl_close($curlNotif);

                $responseNotif = json_decode($json_notif, true);
                $id_gagal = array();
                if (!empty($responseNotif['data'])) {
                    foreach ($responseNotif['data'] as $value) {
                        $cekNotifLokal = Notification::model()->findByPk($value['id']);
                        $notif_id = $value['id'];
                        $content = $value['content'];
                        $user_id = $value['user_id'];
                        $user_id_to = $value['user_id_to'];
                        $tipe = $value['tipe'];
                        $created_at = $value['created_at'];
                        $updated_at = $value['updated_at'];
                        $read_at = $value['read_at'];
                        $status = $value['status'];
                        $relation_id = $value['relation_id'];
                        $class_id_to = $value['class_id_to'];
                        $read_id = $value['read_id'];
                        $sync_status = 2;

                        if (!empty($cekNotifLokal)) {

                            $update = "UPDATE " . $prefix . "notification SET content = :content, user_id = :user_id, user_id_to = :user_id_to, tipe = :tipe, created_at = :created_at, updated_at = :updated_at, read_at = :read_at, status = :status, relation_id = :relation_id, class_id_to = :class_id_to, read_id = :read_id, sync_status = :sync_status WHERE id = :id";

                            $updateCommand = Yii::app()->db->createCommand($update);

                            $updateCommand->bindParam(":id", $notif_id, PDO::PARAM_STR);
                            $updateCommand->bindParam(":content", $content, PDO::PARAM_STR);
                            $updateCommand->bindParam(":user_id", $user_id, PDO::PARAM_STR);
                            $updateCommand->bindParam(":user_id_to", $user_id_to, PDO::PARAM_STR);
                            $updateCommand->bindParam(":tipe", $tipe, PDO::PARAM_STR);
                            $updateCommand->bindParam(":created_at", $created_at, PDO::PARAM_STR);
                            $updateCommand->bindParam(":updated_at", $updated_at, PDO::PARAM_STR);
                            $updateCommand->bindParam(":read_at", $read_at, PDO::PARAM_STR);
                            $updateCommand->bindParam(":status", $status, PDO::PARAM_STR);
                            $updateCommand->bindParam(":relation_id", $relation_id, PDO::PARAM_STR);
                            $updateCommand->bindParam(":class_id_to", $class_id_to, PDO::PARAM_STR);
                            $updateCommand->bindParam(":read_id", $read_id, PDO::PARAM_STR);
                            $updateCommand->bindParam(":sync_status", $sync_status, PDO::PARAM_STR);
                            if ($updateCommand->execute()) {
                                $berhasil = $berhasil + 1;
                            } else {
                                $gagal = $gagal + 1;
                                array_push($id_gagal, $notif_id);
                            }
                        } else {

                            $insert = "INSERT INTO " . $prefix . "notification (content,user_id,user_id_to,tipe,created_at,updated_at,read_at,status,relation_id,class_id_to,read_id,sync_status) values(:content,:user_id,:user_id_to,:tipe,:created_at,:updated_at,:read_at,:status,:relation_id,:class_id_to,:read_id,:sync_status)";

                            $insertCommand = Yii::app()->db->createCommand($insert);

                            $insertCommand->bindParam(":updated_at", $updated_at, PDO::PARAM_STR);
                            $insertCommand->bindParam(":content", $content, PDO::PARAM_STR);
                            $insertCommand->bindParam(":user_id", $user_id, PDO::PARAM_STR);
                            $insertCommand->bindParam(":user_id_to", $user_id_to, PDO::PARAM_STR);
                            $insertCommand->bindParam(":tipe", $tipe, PDO::PARAM_STR);
                            $insertCommand->bindParam(":updated_at", $updated_at, PDO::PARAM_STR);
                            $insertCommand->bindParam(":created_at", $created_at, PDO::PARAM_STR);
                            $insertCommand->bindParam(":read_at", $read_at, PDO::PARAM_STR);
                            $insertCommand->bindParam(":status", $status, PDO::PARAM_STR);
                            $insertCommand->bindParam(":relation_id", $relation_id, PDO::PARAM_STR);
                            $insertCommand->bindParam(":class_id_to", $class_id_to, PDO::PARAM_STR);
                            $insertCommand->bindParam(":read_id", $read_id, PDO::PARAM_STR);
                            $insertCommand->bindParam(":sync_status", $sync_status, PDO::PARAM_STR);

                            if ($insertCommand->execute()) {
                                $berhasil = $berhasil + 1;
                            } else {
                                $gagal = $gagal + 1;
                                array_push($id_gagal, $user_id);
                            }
                        }
                    }
                    echo "kelas";
                } else {
                    echo "kelas";
                }
                //$cekStatus = "kelas";

                break;

            case "kelas":
                $curlKelas = $url . "?type=kelas";
                //  Initiate curl
                $curlKelas = curl_init();
                // Disable SSL verification
                curl_setopt_array($curlKelas, array(
                    CURLOPT_URL => $curlKelas,
                    CURLOPT_VERBOSE => true,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 3000,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                ));
                // Execute
                $json_kelas = curl_exec($curlKelas);
                // Closing
                curl_close($curlKelas);

                $responseKelas = json_decode($json_kelas, true);
                $id_gagal = array();
                if (!empty($responseKelas['data'])) {
                    foreach ($responseKelas['data'] as $value) {
                        $cekKelasLokal = Clases::model()->findByPk($value['id']);
                        $kelas_id = $value['id'];
                        $name = $value['name'];
                        $teacher_id = $value['teacher_id'];
                        $sync_status = 2;

                        if (!empty($cekKelasLokal)) {

                            $update = "UPDATE " . $prefix . "class SET name = :name, teacher_id = :teacher_id, sync_status = :sync_status WHERE id = :id";

                            $updateCommand = Yii::app()->db->createCommand($update);

                            $updateCommand->bindParam(":id", $kelas_id, PDO::PARAM_STR);
                            $updateCommand->bindParam(":name", $name, PDO::PARAM_STR);
                            $updateCommand->bindParam(":teacher_id", $teacher_id, PDO::PARAM_STR);
                            $updateCommand->bindParam(":sync_status", $sync_status, PDO::PARAM_STR);
                            if ($updateCommand->execute()) {
                                $berhasil = $berhasil + 1;
                            } else {
                                $gagal = $gagal + 1;
                                array_push($id_gagal, $kelas_id);
                            }
                        } else {

                            $insert = "INSERT INTO " . $prefix . "class (name,teacher_id,sync_status) values(:name,:teacher_id,:sync_status)";

                            $insertCommand = Yii::app()->db->createCommand($insert);

                            $insertCommand->bindParam(":name", $name, PDO::PARAM_STR);
                            $insertCommand->bindParam(":teacher_id", $teacher_id, PDO::PARAM_STR);
                            $insertCommand->bindParam(":sync_status", $sync_status, PDO::PARAM_STR);

                            if ($insertCommand->execute()) {
                                $berhasil = $berhasil + 1;
                            } else {
                                $gagal = $gagal + 1;
                                array_push($id_gagal, $kelas_id);
                            }
                        }
                    }
                    echo "pelajaran";
                } else {
                    echo "pelajaran";
                }
                //$cekStatus = "pelajaran";

                break;

            case "pelajaran":
                $pelajaran_url = $url . "?type=pelajaran";
                //  Initiate curl
                $curlPelajaran = curl_init();
                // Disable SSL verification
                curl_setopt_array($curlPelajaran, array(
                    CURLOPT_URL => $pelajaran_url,
                    CURLOPT_VERBOSE => true,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 3000,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                ));
                // Execute
                $json_pelajaran = curl_exec($curlPelajaran);
                // Closing
                curl_close($curlPelajaran);

                $responsePelajaran = json_decode($json_pelajaran, true);
                $id_gagal = array();
                if (!empty($responsePelajaran['data'])) {
                    foreach ($responsePelajaran['data'] as $value) {
                        $cekMapelLokal = Clases::model()->findByPk($value['id']);
                        $mapel_id = $value['id'];
                        $name = $value['name'];
                        $user_id = $value['user_id'];
                        $class_id = $value['class_id'];
                        $created_at = $value['created_at'];
                        $updated_at = $value['updated_at'];
                        $created_by = $value['created_by'];
                        $updated_by = $value['updated_by'];
                        $sync_status = 2;

                        if (!empty($cekMapelLokal)) {

                            $update = "UPDATE " . $prefix . "lesson SET name = :name, user_id = :user_id, class_id = :class_id, created_at = :created_at, updated_at = :updated_at, created_by = :created_by, updated_by = :updated_by, sync_status = :sync_status WHERE id = :id";

                            $updateCommand = Yii::app()->db->createCommand($update);

                            $updateCommand->bindParam(":id", $mapel_id, PDO::PARAM_STR);
                            $updateCommand->bindParam(":name", $name, PDO::PARAM_STR);
                            $updateCommand->bindParam(":user_id", $user_id, PDO::PARAM_STR);
                            $updateCommand->bindParam(":class_id", $class_id, PDO::PARAM_STR);
                            $updateCommand->bindParam(":created_at", $created_at, PDO::PARAM_STR);
                            $updateCommand->bindParam(":updated_at", $updated_at, PDO::PARAM_STR);
                            $updateCommand->bindParam(":created_by", $created_by, PDO::PARAM_STR);
                            $updateCommand->bindParam(":updated_by", $updated_by, PDO::PARAM_STR);
                            $updateCommand->bindParam(":sync_status", $sync_status, PDO::PARAM_STR);
                            if ($updateCommand->execute()) {
                                $berhasil = $berhasil + 1;
                            } else {
                                $gagal = $gagal + 1;
                                array_push($id_gagal, $mapel_id);
                            }
                        } else {

                            $insert = "INSERT INTO " . $prefix . "lesson (name,user_id,class_id,created_at,updated_at,created_by,updated_by,sync_status) values(:name,:user_id,:class_id,:created_at,:updated_at,:created_by,:updated_by,:sync_status)";

                            $insertCommand = Yii::app()->db->createCommand($insert);

                            $insertCommand->bindParam(":name", $name, PDO::PARAM_STR);
                            $insertCommand->bindParam(":user_id", $user_id, PDO::PARAM_STR);
                            $insertCommand->bindParam(":class_id", $class_id, PDO::PARAM_STR);
                            $insertCommand->bindParam(":created_at", $created_at, PDO::PARAM_STR);
                            $insertCommand->bindParam(":updated_at", $updated_at, PDO::PARAM_STR);
                            $insertCommand->bindParam(":created_by", $created_by, PDO::PARAM_STR);
                            $insertCommand->bindParam(":updated_by", $updated_by, PDO::PARAM_STR);
                            $insertCommand->bindParam(":sync_status", $sync_status, PDO::PARAM_STR);

                            if ($insertCommand->execute()) {
                                $berhasil = $berhasil + 1;
                            } else {
                                $gagal = $gagal + 1;
                                array_push($id_gagal, $mapel_id);
                            }
                        }
                    }
                    echo "absensi";
                } else {
                    echo "absensi";
                }
                //$cekStatus = "absensi";

                break;

            case "absensi":
                $absensi_url = $url . "?type=absensi";
                //  Initiate curl
                $curlAbsensi = curl_init();
                // Disable SSL verification
                curl_setopt_array($curlAbsensi, array(
                    CURLOPT_URL => $absensi_url,
                    CURLOPT_VERBOSE => true,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 3000,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                ));
                // Execute
                $json_absensi = curl_exec($curlAbsensi);
                // Closing
                curl_close($curlAbsensi);

                $responseAbsensi = json_decode($json_absensi, true);
                $id_gagal = array();
                if (!empty($responseAbsensi['data'])) {
                    foreach ($responseAbsensi['data'] as $value) {
                        $cekAbsensiLokal = Clases::model()->findByPk($value['id']);
                        $absensi_id = $value['id'];
                        $type = $value['type'];
                        $user_id = $value['user_id'];
                        $status = $value['status'];
                        $created_at = $value['created_at'];
                        $alasan = $value['alasan'];
                        $created_date = $value['created_date'];
                        $sync_status = 2;

                        if (!empty($cekAbsensiLokal)) {

                            $update = "UPDATE " . $prefix . "absensi SET type = :type, user_id = :user_id, status = :status, created_at = :created_at, alasan = :alasan, created_date = :created_date, sync_status = :sync_status WHERE id = :id";

                            $updateCommand = Yii::app()->db->createCommand($update);

                            $updateCommand->bindParam(":id", $absensi_id, PDO::PARAM_STR);
                            $updateCommand->bindParam(":type", $type, PDO::PARAM_STR);
                            $updateCommand->bindParam(":user_id", $user_id, PDO::PARAM_STR);
                            $updateCommand->bindParam(":status", $status, PDO::PARAM_STR);
                            $updateCommand->bindParam(":created_at", $created_at, PDO::PARAM_STR);
                            $updateCommand->bindParam(":alasan", $alasan, PDO::PARAM_STR);
                            $updateCommand->bindParam(":created_date", $created_date, PDO::PARAM_STR);
                            $updateCommand->bindParam(":sync_status", $sync_status, PDO::PARAM_STR);
                            if ($updateCommand->execute()) {
                                $berhasil = $berhasil + 1;
                            } else {
                                $gagal = $gagal + 1;
                                array_push($id_gagal, $absensi_id);
                            }
                        } else {

                            $insert = "INSERT INTO " . $prefix . "absensi (type,user_id,status,created_at,alasan,created_date,sync_status) values(:type,:user_id,:status,:created_at,:alasan,:created_date,:sync_status)";

                            $insertCommand = Yii::app()->db->createCommand($insert);

                            $insertCommand->bindParam(":type", $type, PDO::PARAM_STR);
                            $insertCommand->bindParam(":user_id", $user_id, PDO::PARAM_STR);
                            $insertCommand->bindParam(":status", $status, PDO::PARAM_STR);
                            $insertCommand->bindParam(":created_at", $created_at, PDO::PARAM_STR);
                            $insertCommand->bindParam(":alasan", $alasan, PDO::PARAM_STR);
                            $insertCommand->bindParam(":created_date", $created_date, PDO::PARAM_STR);
                            $insertCommand->bindParam(":sync_status", $sync_status, PDO::PARAM_STR);

                            if ($insertCommand->execute()) {
                                $berhasil = $berhasil + 1;
                            } else {
                                $gagal = $gagal + 1;
                                array_push($id_gagal, $absensi_id);
                            }
                        }
                    }
                    echo "selesai";
                } else {
                    echo "selesai";
                }
                //$cekStatus = "Selesai";

                break;

            default:
                //echo "selesai";
                break;
        }
    }

    public function actionPostLocalData($type = null) {
        $data = file_get_contents('php://input');
        //print $data;
        $berhasil = NULL;
        $gagal = NULL;
        $prefix = Yii::app()->params['tablePrefix'];
        switch ($type) {
            case 'user':
                $data_user_local = json_decode($data);
                $id_gagal = array();

                foreach ($data_user_local as $user) {
                    $cekUserLive = User::model()->findByPk($user->id);
                    $user_id = $user->id;
                    $username = $user->username;
                    $email = $user->email;
                    $encrypted_password = $user->encrypted_password;
                    $role_id = $user->role_id;
                    $created_at = $user->created_at;
                    $updated_at = $user->updated_at;
                    $class_id = $user->class_id;
                    $reset_password = $user->reset_password;
                    $display_name = $user->display_name;
                    $sync_status = 2;

                    if (!empty($cekUserLive)) {

                        $update = "UPDATE " . $prefix . "users SET email = :email, username = :username, encrypted_password = :encrypted_password, role_id = :role_id, created_at = :created_at, updated_at = :updated_at, class_id = :class_id, reset_password = :reset_password, display_name = :display_name, sync_status = :sync_status WHERE id = :id";

                        $updateCommand = Yii::app()->db->createCommand($update);

                        $updateCommand->bindParam(":id", $user_id, PDO::PARAM_STR);
                        $updateCommand->bindParam(":email", $email, PDO::PARAM_STR);
                        $updateCommand->bindParam(":username", $username, PDO::PARAM_STR);
                        $updateCommand->bindParam(":encrypted_password", $encrypted_password, PDO::PARAM_STR);
                        $updateCommand->bindParam(":role_id", $role_id, PDO::PARAM_STR);
                        $updateCommand->bindParam(":created_at", $created_at, PDO::PARAM_STR);
                        $updateCommand->bindParam(":updated_at", $updated_at, PDO::PARAM_STR);
                        $updateCommand->bindParam(":class_id", $class_id, PDO::PARAM_STR);
                        $updateCommand->bindParam(":reset_password", $reset_password, PDO::PARAM_STR);
                        $updateCommand->bindParam(":display_name", $display_name, PDO::PARAM_STR);
                        $updateCommand->bindParam(":sync_status", $sync_status, PDO::PARAM_STR);
                        if ($updateCommand->execute()) {
                            $berhasil = $berhasil + 1;
                        } else {
                            $gagal = $gagal + 1;
                            array_push($id_gagal, $user_id);
                        }
                    } else {

                        $insert = "INSERT INTO " . $prefix . "users (email,username,encrypted_password,role_id,created_at,updated_at,class_id,reset_password,display_name,sync_status) values(:email,:username,:encrypted_password,:role_id,:created_at,:updated_at,:class_id,:reset_password,:display_name,:sync_status)";

                        $insertCommand = Yii::app()->db->createCommand($insert);

                        $insertCommand->bindParam(":email", $email, PDO::PARAM_STR);
                        $insertCommand->bindParam(":username", $username, PDO::PARAM_STR);
                        $insertCommand->bindParam(":encrypted_password", $encrypted_password, PDO::PARAM_STR);
                        $insertCommand->bindParam(":role_id", $role_id, PDO::PARAM_STR);
                        $insertCommand->bindParam(":created_at", $created_at, PDO::PARAM_STR);
                        $insertCommand->bindParam(":updated_at", $updated_at, PDO::PARAM_STR);
                        $insertCommand->bindParam(":class_id", $class_id, PDO::PARAM_STR);
                        $insertCommand->bindParam(":reset_password", $reset_password, PDO::PARAM_STR);
                        $insertCommand->bindParam(":display_name", $display_name, PDO::PARAM_STR);
                        $insertCommand->bindParam(":sync_status", $sync_status, PDO::PARAM_STR);

                        if ($insertCommand->execute()) {
                            $berhasil = $berhasil + 1;
                        } else {
                            $gagal = $gagal + 1;
                            array_push($id_gagal, $user_id);
                        }
                    }
                }

                echo json_encode(array('messages' => 'success', 'berhasil' => $berhasil, 'gagal' => $gagal, 'id_gagal' => $id_gagal));
                break;
            case 'tugas':
                $data_tugas_local = json_decode($data);
                $id_gagal = array();

                foreach ($data_tugas_local as $tgs) {
                    $cekTugasLive = Assignment::model()->findByPk($tgs->id);
                    $tugas_id = $tgs->id;
                    $title = $tgs->title;
                    $content = $tgs->content;
                    $created_at = $tgs->created_at;
                    $updated_at = $tgs->updated_at;
                    $created_by = $tgs->created_by;
                    $updated_by = $tgs->updated_by;
                    $due_date = $tgs->due_date;
                    $lesson_id = $tgs->lesson_id;
                    $file = $tgs->file;
                    $assignment_type = $tgs->assignment_type;
                    $add_to_summary = $tgs->add_to_summary;
                    $recipient = $tgs->recipient;
                    $semester = $tgs->semester;
                    $year = $tgs->year;
                    $status = $tgs->status;
                    $sync_status = 2;

                    if (!empty($cekTugasLive)) {

                        $update = "UPDATE " . $prefix . "assignment SET content = :content, title = :title, created_at = :created_at, updated_at = :updated_at, created_by = :created_by, updated_by = :updated_by, due_date = :due_date, lesson_id = :lesson_id, file = :file, assignment_type = :assignment_type, add_to_summary = :add_to_summary, sync_status = :sync_status, recipient = :recipient, semester = :semester, year = :year, status = :status, sync_status = :sync_status WHERE id = :id";

                        $updateCommand = Yii::app()->db->createCommand($update);

                        $updateCommand->bindParam(":id", $tugas_id, PDO::PARAM_STR);
                        $updateCommand->bindParam(":content", $content, PDO::PARAM_STR);
                        $updateCommand->bindParam(":title", $title, PDO::PARAM_STR);
                        $updateCommand->bindParam(":created_at", $created_at, PDO::PARAM_STR);
                        $updateCommand->bindParam(":updated_at", $updated_at, PDO::PARAM_STR);
                        $updateCommand->bindParam(":created_by", $created_by, PDO::PARAM_STR);
                        $updateCommand->bindParam(":updated_by", $updated_by, PDO::PARAM_STR);
                        $updateCommand->bindParam(":due_date", $due_date, PDO::PARAM_STR);
                        $updateCommand->bindParam(":lesson_id", $lesson_id, PDO::PARAM_STR);
                        $updateCommand->bindParam(":file", $file, PDO::PARAM_STR);
                        $updateCommand->bindParam(":assignment_type", $assignment_type, PDO::PARAM_STR);
                        $updateCommand->bindParam(":add_to_summary", $add_to_summary, PDO::PARAM_STR);
                        $updateCommand->bindParam(":recipient", $recipient, PDO::PARAM_STR);
                        $updateCommand->bindParam(":semester", $semester, PDO::PARAM_STR);
                        $updateCommand->bindParam(":year", $year, PDO::PARAM_STR);
                        $updateCommand->bindParam(":status", $status, PDO::PARAM_STR);
                        $updateCommand->bindParam(":sync_status", $sync_status, PDO::PARAM_STR);
                        if ($updateCommand->execute()) {
                            $berhasil = $berhasil + 1;
                        } else {
                            $gagal = $gagal + 1;
                            array_push($id_gagal, $tugas_id);
                        }
                    } else {

                        $insert = "INSERT INTO " . $prefix . "assignment (content,title,created_at,updated_at,created_by,updated_by,due_date,lesson_id,file,assignment_type,add_to_summary,recipient,semester,year,status,sync_status) values(:content,:title,:created_at,:updated_at,:created_by,:updated_by,:due_date,:lesson_id,:file,:assignment_type,:add_to_summary,:recipient,:semester,:year,:status,:sync_status)";

                        $insertCommand = Yii::app()->db->createCommand($insert);

                        $insertCommand->bindParam(":content", $content, PDO::PARAM_STR);
                        $insertCommand->bindParam(":title", $title, PDO::PARAM_STR);
                        $insertCommand->bindParam(":created_at", $created_at, PDO::PARAM_STR);
                        $insertCommand->bindParam(":updated_at", $updated_at, PDO::PARAM_STR);
                        $insertCommand->bindParam(":created_by", $created_by, PDO::PARAM_STR);
                        $insertCommand->bindParam(":updated_by", $updated_by, PDO::PARAM_STR);
                        $insertCommand->bindParam(":due_date", $due_date, PDO::PARAM_STR);
                        $insertCommand->bindParam(":lesson_id", $lesson_id, PDO::PARAM_STR);
                        $insertCommand->bindParam(":file", $file, PDO::PARAM_STR);
                        $insertCommand->bindParam(":assignment_type", $assignment_type, PDO::PARAM_STR);
                        $insertCommand->bindParam(":add_to_summary", $add_to_summary, PDO::PARAM_STR);
                        $insertCommand->bindParam(":recipient", $recipient, PDO::PARAM_STR);
                        $insertCommand->bindParam(":semester", $semester, PDO::PARAM_STR);
                        $insertCommand->bindParam(":year", $year, PDO::PARAM_STR);
                        $insertCommand->bindParam(":status", $status, PDO::PARAM_STR);
                        $insertCommand->bindParam(":sync_status", $sync_status, PDO::PARAM_STR);

                        if ($insertCommand->execute()) {
                            $berhasil = $berhasil + 1;
                        } else {
                            $gagal = $gagal + 1;
                            array_push($id_gagal, $tugas_id);
                        }
                    }
                }

                echo json_encode(array('messages' => 'success', 'berhasil' => $berhasil, 'gagal' => $gagal, 'id_gagal' => $id_gagal));
                break;

            case 'tugasSiswa':
                $data_ts_local = json_decode($data);
                $id_gagal = array();

                foreach ($data_ts_local as $ts) {
                    $cekTsLive = StudentAssignment::model()->findByPk($ts->id);
                    $ts_id = $ts->id;
                    $assignment_id = $ts->assignment_id;
                    $content = $ts->content;
                    $created_at = $ts->created_at;
                    $updated_at = $ts->updated_at;
                    $file = $ts->file;
                    $student_id = $ts->student_id;
                    $score = $ts->score;
                    $status = $ts->status;
                    $sync_status = 2;

                    if (!empty($cekTsLive)) {

                        $update = "UPDATE " . $prefix . "student_assignment SET content = :content, assignment_id = :assignment_id, created_at = :created_at, updated_at = :updated_at, file = :file, student_id = :student_id, score = :score, status = :status, sync_status = :sync_status WHERE id = :id";

                        $updateCommand = Yii::app()->db->createCommand($update);

                        $updateCommand->bindParam(":id", $ts_id, PDO::PARAM_STR);
                        $updateCommand->bindParam(":content", $content, PDO::PARAM_STR);
                        $updateCommand->bindParam(":assignment_id", $assignment_id, PDO::PARAM_STR);
                        $updateCommand->bindParam(":created_at", $created_at, PDO::PARAM_STR);
                        $updateCommand->bindParam(":updated_at", $updated_at, PDO::PARAM_STR);
                        $updateCommand->bindParam(":file", $file, PDO::PARAM_STR);
                        $updateCommand->bindParam(":student_id", $student_id, PDO::PARAM_STR);
                        $updateCommand->bindParam(":score", $score, PDO::PARAM_STR);
                        $updateCommand->bindParam(":status", $status, PDO::PARAM_STR);
                        $updateCommand->bindParam(":sync_status", $sync_status, PDO::PARAM_STR);
                        if ($updateCommand->execute()) {
                            $berhasil = $berhasil + 1;
                        } else {
                            $gagal = $gagal + 1;
                            array_push($id_gagal, $ts_id);
                        }
                    } else {

                        $insert = "INSERT INTO " . $prefix . "student_assignment (content,assignment_id,created_at,updated_at,file,student_id,score,status,sync_status) values(:content,:assignment_id,:created_at,:updated_at,:file,:student_id,:score,:status,:sync_status)";

                        $insertCommand = Yii::app()->db->createCommand($insert);

                        $insertCommand->bindParam(":content", $content, PDO::PARAM_STR);
                        $insertCommand->bindParam(":assignment_id", $assignment_id, PDO::PARAM_STR);
                        $insertCommand->bindParam(":created_at", $created_at, PDO::PARAM_STR);
                        $insertCommand->bindParam(":updated_at", $updated_at, PDO::PARAM_STR);
                        $insertCommand->bindParam(":file", $file, PDO::PARAM_STR);
                        $insertCommand->bindParam(":student_id", $student_id, PDO::PARAM_STR);
                        $insertCommand->bindParam(":score", $score, PDO::PARAM_STR);
                        $insertCommand->bindParam(":status", $status, PDO::PARAM_STR);
                        $insertCommand->bindParam(":sync_status", $sync_status, PDO::PARAM_STR);

                        if ($insertCommand->execute()) {
                            $berhasil = $berhasil + 1;
                        } else {
                            $gagal = $gagal + 1;
                            array_push($id_gagal, $ts_id);
                        }
                    }
                }

                echo json_encode(array('messages' => 'success', 'berhasil' => $berhasil, 'gagal' => $gagal, 'id_gagal' => $id_gagal));
                break;

            case 'materi':
                $data_materi_local = json_decode($data);
                $id_gagal = array();
                //print_r($data);
                foreach ($data_materi_local as $materi) {
                    $cekMateriLive = Chapters::model()->findByPk($materi->id);
                    $materi_id = $materi->id;
                    $id_lesson = $materi->id_lesson;
                    $title = $materi->title;
                    $description = $materi->description;
                    $created_at = $materi->created_at;
                    $updated_at = $materi->updated_at;
                    $created_by = $materi->created_by;
                    $updated_by = $materi->updated_by;
                    $content = $materi->content;
                    $chapter_type = $materi->chapter_type;
                    $semester = $materi->semester;
                    $year = $materi->year;
                    $sync_status = 2;

                    if (!empty($cekMateriLive)) {

                        $update = "UPDATE " . $prefix . "chapters SET id_lesson = :id_lesson, title = :title, description = :description, created_at = :created_at, updated_at = :updated_at, created_by = :created_by, updated_by = :updated_by, content = :content, chapter_type = :chapter_type, semester	 = :semester	, year = :year, sync_status = :sync_status WHERE id = :id";

                        $updateCommand = Yii::app()->db->createCommand($update);

                        $updateCommand->bindParam(":id", $materi_id, PDO::PARAM_STR);
                        $updateCommand->bindParam(":id_lesson", $id_lesson, PDO::PARAM_STR);
                        $updateCommand->bindParam(":title", $title, PDO::PARAM_STR);
                        $updateCommand->bindParam(":description", $description, PDO::PARAM_STR);
                        $updateCommand->bindParam(":created_at", $created_at, PDO::PARAM_STR);
                        $updateCommand->bindParam(":updated_at", $updated_at, PDO::PARAM_STR);
                        $updateCommand->bindParam(":created_by", $created_by, PDO::PARAM_STR);
                        $updateCommand->bindParam(":updated_by", $updated_by, PDO::PARAM_STR);
                        $updateCommand->bindParam(":content", $content, PDO::PARAM_STR);
                        $updateCommand->bindParam(":chapter_type", $chapter_type, PDO::PARAM_STR);
                        $updateCommand->bindParam(":semester	", $semester, PDO::PARAM_STR);
                        $updateCommand->bindParam(":year", $year, PDO::PARAM_STR);
                        $updateCommand->bindParam(":sync_status", $sync_status, PDO::PARAM_STR);
                        if ($updateCommand->execute()) {
                            $berhasil = $berhasil + 1;
                        } else {
                            $gagal = $gagal + 1;
                            array_push($id_gagal, $materi_id);
                        }
                    } else {

                        $insert = "INSERT INTO " . $prefix . "chapters (id_lesson,title,description,created_at,updated_at,created_by,updated_by,content,chapter_type,semester,year,sync_status) values(:id_lesson,:title,:description,:created_at,:updated_at,:created_by,:updated_by,:content,:chapter_type,:semester,:year,:sync_status)";

                        $insertCommand = Yii::app()->db->createCommand($insert);

                        $insertCommand->bindParam(":id_lesson", $id_lesson, PDO::PARAM_STR);
                        $insertCommand->bindParam(":title", $title, PDO::PARAM_STR);
                        $insertCommand->bindParam(":description", $description, PDO::PARAM_STR);
                        $insertCommand->bindParam(":created_at", $created_at, PDO::PARAM_STR);
                        $insertCommand->bindParam(":updated_at", $updated_at, PDO::PARAM_STR);
                        $insertCommand->bindParam(":created_by", $created_by, PDO::PARAM_STR);
                        $insertCommand->bindParam(":updated_by", $updated_by, PDO::PARAM_STR);
                        $insertCommand->bindParam(":content", $content, PDO::PARAM_STR);
                        $insertCommand->bindParam(":chapter_type", $chapter_type, PDO::PARAM_STR);
                        $insertCommand->bindParam(":semester", $semester, PDO::PARAM_STR);
                        $insertCommand->bindParam(":year", $year, PDO::PARAM_STR);
                        $insertCommand->bindParam(":sync_status", $sync_status, PDO::PARAM_STR);

                        if ($insertCommand->execute()) {
                            $berhasil = $berhasil + 1;
                        } else {
                            $gagal = $gagal + 1;
                            array_push($id_gagal, $materi_id);
                        }
                    }
                }

                echo json_encode(array('messages' => 'success', 'berhasil' => $berhasil, 'gagal' => $gagal, 'id_gagal' => $id_gagal));
                break;

            case 'fileMateri':
                $data_mf_local = json_decode($data);
                $id_gagal = array();

                foreach ($data_mf_local as $mf) {
                    $cekMfLive = ChapterFiles::model()->findByPk($mf->id);
                    $mf_id = $mf->id;
                    $id_chapter = $mf->id_chapter;
                    $file = $mf->file;
                    $type = $mf->type;
                    $created_at = $mf->created_at;
                    $updated_at = $mf->updated_at;
                    $created_by = $mf->created_by;
                    $updated_by = $mf->updated_by;
                    $content = $mf->content;
                    $sync_status = 2;

                    if (!empty($cekMfLive)) {

                        $update = "UPDATE " . $prefix . "chapter_files SET id_chapter = :id_chapter, file = :file, type = :type, created_at = :created_at, updated_at = :updated_at, created_by = :created_by, updated_by = :updated_by, content = :content, sync_status = :sync_status WHERE id = :id";

                        $updateCommand = Yii::app()->db->createCommand($update);

                        $updateCommand->bindParam(":id", $mf_id, PDO::PARAM_STR);
                        $updateCommand->bindParam(":id_chapter", $id_chapter, PDO::PARAM_STR);
                        $updateCommand->bindParam(":file", $file, PDO::PARAM_STR);
                        $updateCommand->bindParam(":type", $type, PDO::PARAM_STR);
                        $updateCommand->bindParam(":created_at", $created_at, PDO::PARAM_STR);
                        $updateCommand->bindParam(":updated_at", $updated_at, PDO::PARAM_STR);
                        $updateCommand->bindParam(":created_by", $created_by, PDO::PARAM_STR);
                        $updateCommand->bindParam(":updated_by", $updated_by, PDO::PARAM_STR);
                        $updateCommand->bindParam(":content", $content, PDO::PARAM_STR);
                        $updateCommand->bindParam(":sync_status", $sync_status, PDO::PARAM_STR);
                        if ($updateCommand->execute()) {
                            $berhasil = $berhasil + 1;
                        } else {
                            $gagal = $gagal + 1;
                            array_push($id_gagal, $mf_id);
                        }
                    } else {

                        $insert = "INSERT INTO " . $prefix . "chapter_files (id_chapter,file,type,created_at,updated_at,created_by,updated_by,content,sync_status) values(:id_chapter,:file,:type,:created_at,:updated_at,:created_by,:updated_by,:content,:sync_status)";

                        $insertCommand = Yii::app()->db->createCommand($insert);

                        $insertCommand->bindParam(":content", $content, PDO::PARAM_STR);
                        $insertCommand->bindParam(":id_chapter", $id_chapter, PDO::PARAM_STR);
                        $insertCommand->bindParam(":file", $file, PDO::PARAM_STR);
                        $insertCommand->bindParam(":type", $type, PDO::PARAM_STR);
                        $insertCommand->bindParam(":created_at", $created_at, PDO::PARAM_STR);
                        $insertCommand->bindParam(":updated_at", $updated_at, PDO::PARAM_STR);
                        $insertCommand->bindParam(":created_by", $created_by, PDO::PARAM_STR);
                        $insertCommand->bindParam(":updated_by", $updated_by, PDO::PARAM_STR);
                        $insertCommand->bindParam(":sync_status", $sync_status, PDO::PARAM_STR);

                        if ($insertCommand->execute()) {
                            $berhasil = $berhasil + 1;
                        } else {
                            $gagal = $gagal + 1;
                            array_push($id_gagal, $mf_id);
                        }
                    }
                }

                echo json_encode(array('messages' => 'success', 'berhasil' => $berhasil, 'gagal' => $gagal, 'id_gagal' => $id_gagal));
                break;

            case 'quiz':
                $data_quiz_local = json_decode($data);
                $id_gagal = array();

                foreach ($data_quiz_local as $quiz) {
                    $cekQuizLive = Quiz::model()->findByPk($quiz->id);
                    $quiz_id = $quiz->id;
                    $title = $quiz->title;
                    $lesson_id = $quiz->lesson_id;
                    $chapter_id = $quiz->chapter_id;
                    $quiz_type = $quiz->quiz_type;
                    $created_at = $quiz->created_at;
                    $updated_at = $quiz->updated_at;
                    $created_by = $quiz->created_by;
                    $updated_by = $quiz->updated_by;
                    $start_time = $quiz->start_time;
                    $end_time = $quiz->end_time;
                    $total_question = $quiz->total_question;
                    $status = $quiz->status;
                    $add_to_summary = $quiz->add_to_summary;
                    $repeat_quiz = $quiz->repeat_quiz;
                    $question = $quiz->question;
                    $semester = $quiz->semester;
                    $year = $quiz->year;
                    $random = $quiz->random;
                    $show_nilai = $quiz->show_nilai;
                    $sync_status = 2;

                    if (!empty($cekQuizLive)) {

                        $update = "UPDATE " . $prefix . "quiz SET title = :title, lesson_id = :lesson_id, chapter_id = :chapter_id, quiz_type = :quiz_type, created_at = :created_at, updated_at = :updated_at, created_by = :created_by, updated_by = :updated_by, start_time = :start_time, end_time = :end_time, total_question = :total_question, status = :status, add_to_summary = :add_to_summary, repeat_quiz = :repeat_quiz, question = :question, semester = :semester, year = :year, random = :random, sync_status = :sync_status, show_nilai = :show_nilai WHERE id = :id";

                        $updateCommand = Yii::app()->db->createCommand($update);

                        $updateCommand->bindParam(":id", $quiz_id, PDO::PARAM_STR);
                        $updateCommand->bindParam(":title", $title, PDO::PARAM_STR);
                        $updateCommand->bindParam(":lesson_id", $lesson_id, PDO::PARAM_STR);
                        $updateCommand->bindParam(":chapter_id", $chapter_id, PDO::PARAM_STR);
                        $updateCommand->bindParam(":quiz_type", $quiz_type, PDO::PARAM_STR);
                        $updateCommand->bindParam(":created_at", $created_at, PDO::PARAM_STR);
                        $updateCommand->bindParam(":updated_at", $updated_at, PDO::PARAM_STR);
                        $updateCommand->bindParam(":created_by", $created_by, PDO::PARAM_STR);
                        $updateCommand->bindParam(":updated_by", $updated_by, PDO::PARAM_STR);
                        $updateCommand->bindParam(":start_time", $start_time, PDO::PARAM_STR);
                        $updateCommand->bindParam(":end_time", $end_time, PDO::PARAM_STR);
                        $updateCommand->bindParam(":total_question", $total_question, PDO::PARAM_STR);
                        $updateCommand->bindParam(":status", $status, PDO::PARAM_STR);
                        $updateCommand->bindParam(":add_to_summary", $add_to_summary, PDO::PARAM_STR);
                        $updateCommand->bindParam(":repeat_quiz", $repeat_quiz, PDO::PARAM_STR);
                        $updateCommand->bindParam(":question", $question, PDO::PARAM_STR);
                        $updateCommand->bindParam(":semester", $semester, PDO::PARAM_STR);
                        $updateCommand->bindParam(":year", $year, PDO::PARAM_STR);
                        $updateCommand->bindParam(":random", $random, PDO::PARAM_STR);
                        $updateCommand->bindParam(":sync_status", $sync_status, PDO::PARAM_STR);
                        $updateCommand->bindParam(":show_nilai", $show_nilai, PDO::PARAM_STR);
                        if ($updateCommand->execute()) {
                            $berhasil = $berhasil + 1;
                        } else {
                            $gagal = $gagal + 1;
                            array_push($id_gagal, $quiz_id);
                        }
                    } else {

                        $insert = "INSERT INTO " . $prefix . "quiz (title,lesson_id,chapter_id,quiz_type,created_at,updated_at,created_by,updated_by,start_time,end_time,total_question,status,add_to_summary,repeat_quiz,question,semester,year,random,sync_status) values(:title,:lesson_id,:chapter_id,:quiz_type,:created_at,:updated_at,:created_by,:updated_by,:start_time,:end_time,:total_question,:status,:add_to_summary,:repeat_quiz,:question,:semester,:year,:random,:sync_status)";

                        $insertCommand = Yii::app()->db->createCommand($insert);

                        $insertCommand->bindParam(":title", $title, PDO::PARAM_STR);
                        $insertCommand->bindParam(":lesson_id", $lesson_id, PDO::PARAM_STR);
                        $insertCommand->bindParam(":chapter_id", $chapter_id, PDO::PARAM_STR);
                        $insertCommand->bindParam(":quiz_type", $quiz_type, PDO::PARAM_STR);
                        $insertCommand->bindParam(":created_at", $created_at, PDO::PARAM_STR);
                        $insertCommand->bindParam(":updated_at", $updated_at, PDO::PARAM_STR);
                        $insertCommand->bindParam(":created_by", $created_by, PDO::PARAM_STR);
                        $insertCommand->bindParam(":updated_by", $updated_by, PDO::PARAM_STR);
                        $insertCommand->bindParam(":start_time", $start_time, PDO::PARAM_STR);
                        $insertCommand->bindParam(":end_time", $end_time, PDO::PARAM_STR);
                        $insertCommand->bindParam(":total_question", $total_question, PDO::PARAM_STR);
                        $insertCommand->bindParam(":status", $status, PDO::PARAM_STR);
                        $insertCommand->bindParam(":add_to_summary", $add_to_summary, PDO::PARAM_STR);
                        $insertCommand->bindParam(":repeat_quiz", $repeat_quiz, PDO::PARAM_STR);
                        $insertCommand->bindParam(":question", $question, PDO::PARAM_STR);
                        $insertCommand->bindParam(":semester", $semester, PDO::PARAM_STR);
                        $insertCommand->bindParam(":year", $year, PDO::PARAM_STR);
                        $insertCommand->bindParam(":random", $random, PDO::PARAM_STR);
                        $insertCommand->bindParam(":sync_status", $sync_status, PDO::PARAM_STR);

                        if ($insertCommand->execute()) {
                            $berhasil = $berhasil + 1;
                        } else {
                            $gagal = $gagal + 1;
                            array_push($id_gagal, $quiz_id);
                        }
                    }
                }

                echo json_encode(array('messages' => 'success', 'berhasil' => $berhasil, 'gagal' => $gagal, 'id_gagal' => $id_gagal));
                break;

            case 'quizSiswa':
                $data_qz_local = json_decode($data);
                $id_gagal = array();

                foreach ($data_qz_local as $qz) {
                    $cekQzLive = StudentQuiz::model()->findByPk($qz->id);
                    $qz_id = $qz->id;
                    $quiz_id = $qz->quiz_id;
                    $student_id = $qz->student_id;
                    $created_at = $qz->created_at;
                    $updated_at = $qz->updated_at;
                    $score = $qz->score;
                    $right_answer = $qz->right_answer;
                    $wrong_answer = $qz->wrong_answer;
                    $unanswered = $qz->unanswered;
                    $student_answer = $qz->student_answer;
                    $attempt = $qz->attempt;
                    $sync_status = 2;

                    if (!empty($cekQzLive)) {

                        $update = "UPDATE " . $prefix . "student_quiz SET quiz_id = :quiz_id, student_id = :student_id, created_at = :created_at, updated_at = :updated_at, score = :score, right_answer = :right_answer, wrong_answer = :wrong_answer, unanswered = :unanswered, student_answer = :student_answer, attempt = :attempt, sync_status = :sync_status WHERE id = :id";

                        $updateCommand = Yii::app()->db->createCommand($update);

                        $updateCommand->bindParam(":id", $qz_id, PDO::PARAM_STR);
                        $updateCommand->bindParam(":quiz_id", $quiz_id, PDO::PARAM_STR);
                        $updateCommand->bindParam(":student_id", $student_id, PDO::PARAM_STR);
                        $updateCommand->bindParam(":created_at", $created_at, PDO::PARAM_STR);
                        $updateCommand->bindParam(":updated_at", $updated_at, PDO::PARAM_STR);
                        $updateCommand->bindParam(":score", $score, PDO::PARAM_STR);
                        $updateCommand->bindParam(":right_answer", $right_answer, PDO::PARAM_STR);
                        $updateCommand->bindParam(":wrong_answer", $wrong_answer, PDO::PARAM_STR);
                        $updateCommand->bindParam(":unanswered", $unanswered, PDO::PARAM_STR);
                        $updateCommand->bindParam(":student_answer", $student_answer, PDO::PARAM_STR);
                        $updateCommand->bindParam(":attempt", $attempt, PDO::PARAM_STR);
                        $updateCommand->bindParam(":sync_status", $sync_status, PDO::PARAM_STR);
                        if ($updateCommand->execute()) {
                            $berhasil = $berhasil + 1;
                        } else {
                            $gagal = $gagal + 1;
                            array_push($id_gagal, $qz_id);
                        }
                    } else {

                        $insert = "INSERT INTO " . $prefix . "student_quiz (quiz_id,student_id,created_at,updated_at,score,right_answer,wrong_answer,unanswered,student_answer,attempt,sync_status) values(:quiz_id,:student_id,:created_at,:updated_at,:score,:right_answer,:wrong_answer,:unanswered,:student_answer,:attempt,:sync_status)";

                        $insertCommand = Yii::app()->db->createCommand($insert);

                        $insertCommand->bindParam(":unanswered", $unanswered, PDO::PARAM_STR);
                        $insertCommand->bindParam(":quiz_id", $quiz_id, PDO::PARAM_STR);
                        $insertCommand->bindParam(":student_id", $student_id, PDO::PARAM_STR);
                        $insertCommand->bindParam(":created_at", $created_at, PDO::PARAM_STR);
                        $insertCommand->bindParam(":updated_at", $updated_at, PDO::PARAM_STR);
                        $insertCommand->bindParam(":score", $score, PDO::PARAM_STR);
                        $insertCommand->bindParam(":right_answer", $right_answer, PDO::PARAM_STR);
                        $insertCommand->bindParam(":wrong_answer", $wrong_answer, PDO::PARAM_STR);
                        $insertCommand->bindParam(":student_answer", $student_answer, PDO::PARAM_STR);
                        $insertCommand->bindParam(":attempt", $attempt, PDO::PARAM_STR);
                        $insertCommand->bindParam(":sync_status", $sync_status, PDO::PARAM_STR);

                        if ($insertCommand->execute()) {
                            $berhasil = $berhasil + 1;
                        } else {
                            $gagal = $gagal + 1;
                            array_push($id_gagal, $qz_id);
                        }
                    }
                }

                echo json_encode(array('messages' => 'success', 'berhasil' => $berhasil, 'gagal' => $gagal, 'id_gagal' => $id_gagal));
                break;

            case 'soal':
                $data_qs_local = json_decode($data);
                $id_gagal = array();
                //print $data;
                //echo json_decode($data);
                //echo $data_qs_local;
                foreach ($data_qs_local as $quest) {
                    $cekQuestionLive = Questions::model()->findByPk($quest->id);
                    $question_id = $quest->id;
                    $id_quiz = $quest->quiz_id;
                    $lesson_id = $quest->lesson_id;
                    $title = $quest->title;
                    $text = $quest->text;
                    $choices = $quest->choices;
                    $key_answer = $quest->key_answer;
                    $created_at = $quest->created_at;
                    $updated_at = $quest->updated_at;
                    $teacher_id = $quest->teacher_id;
                    $created_by = $quest->created_by;
                    $updated_by = $quest->updated_by;
                    $file = $quest->file;
                    $type = $quest->type;
                    $choices_files = $quest->choices_files;
                    $sync_status = 2;

                    if (!empty($cekQuestionLive)) {

                        $update = "UPDATE " . $prefix . "questions SET quiz_id = :quiz_id, lesson_id = :lesson_id, title = :title, text = :text, choices = :choices, key_answer = :key_answer, created_at = :created_at, updated_at = :updated_at, teacher_id = :teacher_id, created_by = :created_by, updated_by = :updated_by, file = :file, type = :type, choices_files = :choices_files, sync_status = :sync_status WHERE id = :id";

                        $updateCommand = Yii::app()->db->createCommand($update);

                        $updateCommand->bindParam(":id", $question_id, PDO::PARAM_STR);
                        $updateCommand->bindParam(":quiz_id", $id_quiz, PDO::PARAM_STR);
                        $updateCommand->bindParam(":lesson_id", $lesson_id, PDO::PARAM_STR);
                        $updateCommand->bindParam(":title", $title, PDO::PARAM_STR);
                        $updateCommand->bindParam(":text", $text, PDO::PARAM_STR);
                        $updateCommand->bindParam(":choices", $choices, PDO::PARAM_STR);
                        $updateCommand->bindParam(":key_answer", $key_answer, PDO::PARAM_STR);
                        $updateCommand->bindParam(":created_at", $created_at, PDO::PARAM_STR);
                        $updateCommand->bindParam(":updated_at", $updated_at, PDO::PARAM_STR);
                        $updateCommand->bindParam(":teacher_id", $teacher_id, PDO::PARAM_STR);
                        $updateCommand->bindParam(":created_by", $created_by, PDO::PARAM_STR);
                        $updateCommand->bindParam(":updated_by", $updated_by, PDO::PARAM_STR);
                        $updateCommand->bindParam(":file", $file, PDO::PARAM_STR);
                        $updateCommand->bindParam(":type", $type, PDO::PARAM_STR);
                        $updateCommand->bindParam(":choices_files", $choices_files, PDO::PARAM_STR);
                        $updateCommand->bindParam(":sync_status", $sync_status, PDO::PARAM_STR);
                        if ($updateCommand->execute()) {
                            $berhasil = $berhasil + 1;
                        } else {
                            $gagal = $gagal + 1;
                            array_push($id_gagal, $question_id);
                        }
                    } else {

                        $insert = "INSERT INTO " . $prefix . "questions (quiz_id,lesson_id,title,text,choices,key_answer,created_at,updated_at,teacher_id,created_by,updated_by,file,type,choices_files,sync_status) values(:quiz_id,:lesson_id,:title,:text,:choices,:key_answer,:created_at,:updated_at,:teacher_id,:created_by,:updated_by,:file,:type,:choices_files,:sync_status)";

                        $insertCommand = Yii::app()->db->createCommand($insert);

                        $insertCommand->bindParam(":updated_at", $updated_at, PDO::PARAM_STR);
                        $insertCommand->bindParam(":quiz_id", $id_quiz, PDO::PARAM_STR);
                        $insertCommand->bindParam(":lesson_id", $lesson_id, PDO::PARAM_STR);
                        $insertCommand->bindParam(":title", $title, PDO::PARAM_STR);
                        $insertCommand->bindParam(":text", $text, PDO::PARAM_STR);
                        $insertCommand->bindParam(":choices", $choices, PDO::PARAM_STR);
                        $insertCommand->bindParam(":key_answer", $key_answer, PDO::PARAM_STR);
                        $insertCommand->bindParam(":created_at", $created_at, PDO::PARAM_STR);
                        $insertCommand->bindParam(":teacher_id", $teacher_id, PDO::PARAM_STR);
                        $insertCommand->bindParam(":created_by", $created_by, PDO::PARAM_STR);
                        $insertCommand->bindParam(":updated_by", $updated_by, PDO::PARAM_STR);
                        $insertCommand->bindParam(":file", $file, PDO::PARAM_STR);
                        $insertCommand->bindParam(":type", $type, PDO::PARAM_STR);
                        $insertCommand->bindParam(":choices_files", $choices_files, PDO::PARAM_STR);
                        $insertCommand->bindParam(":sync_status", $sync_status, PDO::PARAM_STR);

                        if ($insertCommand->execute()) {
                            $berhasil = $berhasil + 1;
                        } else {
                            $gagal = $gagal + 1;
                            array_push($id_gagal, $question_id);
                        }
                    }
                }
                echo json_encode(array('messages' => 'success', 'berhasil' => $berhasil, 'gagal' => $gagal, 'id_gagal' => $id_gagal));
                break;

            case 'lks':
                $data_lks_local = json_decode($data);
                $id_gagal = array();

                foreach ($data_lks_local as $lks) {
                    $cekLksLive = Lks::model()->findByPk($lks->id);
                    $lks_id = $lks->id;
                    $title = $lks->title;
                    $lesson_id = $lks->lesson_id;
                    $assignments = $lks->assignments;
                    $chapters = $lks->chapters;
                    $quizes = $lks->quizes;
                    $created_at = $lks->created_at;
                    $updated_at = $lks->updated_at;
                    $teacher_id = $lks->teacher_id;
                    $created_by = $lks->created_by;
                    $updated_by = $lks->updated_by;
                    $file = $lks->file;
                    $type = $lks->type;
                    $choices_files = $lks->choices_files;
                    $sync_status = 2;

                    if (!empty($cekLksLive)) {

                        $update = "UPDATE " . $prefix . "lks SET title = :title, lesson_id = :lesson_id, assignments = :assignments, chapters = :chapters, quizes = :quizes, created_at = :created_at, updated_at = :updated_at, teacher_id = :teacher_id, created_by = :created_by, updated_by = :updated_by, sync_status = :sync_status WHERE id = :id";

                        $updateCommand = Yii::app()->db->createCommand($update);

                        $updateCommand->bindParam(":id", $lks_id, PDO::PARAM_STR);
                        $updateCommand->bindParam(":title", $title, PDO::PARAM_STR);
                        $updateCommand->bindParam(":lesson_id", $lesson_id, PDO::PARAM_STR);
                        $updateCommand->bindParam(":assignments", $assignments, PDO::PARAM_STR);
                        $updateCommand->bindParam(":chapters", $chapters, PDO::PARAM_STR);
                        $updateCommand->bindParam(":quizes", $quizes, PDO::PARAM_STR);
                        $updateCommand->bindParam(":created_at", $created_at, PDO::PARAM_STR);
                        $updateCommand->bindParam(":updated_at", $updated_at, PDO::PARAM_STR);
                        $updateCommand->bindParam(":teacher_id", $teacher_id, PDO::PARAM_STR);
                        $updateCommand->bindParam(":created_by", $created_by, PDO::PARAM_STR);
                        $updateCommand->bindParam(":updated_by", $updated_by, PDO::PARAM_STR);
                        $updateCommand->bindParam(":sync_status", $sync_status, PDO::PARAM_STR);
                        if ($updateCommand->execute()) {
                            $berhasil = $berhasil + 1;
                        } else {
                            $gagal = $gagal + 1;
                            array_push($id_gagal, $lks_id);
                        }
                    } else {

                        $insert = "INSERT INTO " . $prefix . "lks (title,lesson_id,assignments,chapters,quizes,created_at,updated_at,teacher_id,created_by,sync_status) values(:title,:lesson_id,:assignments,:chapters,:quizes,:created_at,:updated_at,:teacher_id,:created_by,:sync_status)";

                        $insertCommand = Yii::app()->db->createCommand($insert);

                        $insertCommand->bindParam(":updated_at", $updated_at, PDO::PARAM_STR);
                        $insertCommand->bindParam(":title", $title, PDO::PARAM_STR);
                        $insertCommand->bindParam(":lesson_id", $lesson_id, PDO::PARAM_STR);
                        $insertCommand->bindParam(":assignments", $assignments, PDO::PARAM_STR);
                        $insertCommand->bindParam(":chapters", $chapters, PDO::PARAM_STR);
                        $insertCommand->bindParam(":quizes", $quizes, PDO::PARAM_STR);
                        $insertCommand->bindParam(":updated_at", $updated_at, PDO::PARAM_STR);
                        $insertCommand->bindParam(":created_at", $created_at, PDO::PARAM_STR);
                        $insertCommand->bindParam(":teacher_id", $teacher_id, PDO::PARAM_STR);
                        $insertCommand->bindParam(":created_by", $created_by, PDO::PARAM_STR);
                        $insertCommand->bindParam(":updated_by", $updated_by, PDO::PARAM_STR);
                        $insertCommand->bindParam(":sync_status", $sync_status, PDO::PARAM_STR);

                        if ($insertCommand->execute()) {
                            $berhasil = $berhasil + 1;
                        } else {
                            $gagal = $gagal + 1;
                            array_push($id_gagal, $lks_id);
                        }
                    }
                }

                echo json_encode(array('messages' => 'success', 'berhasil' => $berhasil, 'gagal' => $gagal, 'id_gagal' => $id_gagal));
                break;

            case 'pengumuman':
                $data_pengumuman_local = json_decode($data);
                $id_gagal = array();

                foreach ($data_pengumuman_local as $pengumuman) {
                    $cekPengumumanLive = Announcements::model()->findByPk($pengumuman->id);
                    $pengumuman_id = $pengumuman->id;
                    $author_id = $pengumuman->author_id;
                    $title = $pengumuman->title;
                    $content = $pengumuman->content;
                    $created_at = $pengumuman->created_at;
                    $updated_at = $pengumuman->updated_at;
                    $type = $pengumuman->type;
                    $sync_status = 2;

                    if (!empty($cekPengumumanLive)) {

                        $update = "UPDATE " . $prefix . "pengumuman SET author_id = :author_id, title = :title, content = :content, created_at = :created_at, updated_at = :updated_at, type = :type, created_by = :created_by, updated_by = :updated_by, sync_status = :sync_status WHERE id = :id";

                        $updateCommand = Yii::app()->db->createCommand($update);

                        $updateCommand->bindParam(":id", $pengumuman_id, PDO::PARAM_STR);
                        $updateCommand->bindParam(":author_id", $author_id, PDO::PARAM_STR);
                        $updateCommand->bindParam(":title", $title, PDO::PARAM_STR);
                        $updateCommand->bindParam(":content", $content, PDO::PARAM_STR);
                        $updateCommand->bindParam(":created_at", $created_at, PDO::PARAM_STR);
                        $updateCommand->bindParam(":updated_at", $updated_at, PDO::PARAM_STR);
                        $updateCommand->bindParam(":type", $type, PDO::PARAM_STR);
                        $updateCommand->bindParam(":sync_status", $sync_status, PDO::PARAM_STR);
                        if ($updateCommand->execute()) {
                            $berhasil = $berhasil + 1;
                        } else {
                            $gagal = $gagal + 1;
                            array_push($id_gagal, $pengumuman_id);
                        }
                    } else {

                        $insert = "INSERT INTO " . $prefix . "pengumuman (author_id,title,content,created_at,updated_at,type,created_by,sync_status) values(:author_id,:title,:content,:created_at,:updated_at,:type,:sync_status)";

                        $insertCommand = Yii::app()->db->createCommand($insert);

                        $insertCommand->bindParam(":updated_at", $updated_at, PDO::PARAM_STR);
                        $insertCommand->bindParam(":author_id", $author_id, PDO::PARAM_STR);
                        $insertCommand->bindParam(":title", $title, PDO::PARAM_STR);
                        $insertCommand->bindParam(":content", $content, PDO::PARAM_STR);
                        $insertCommand->bindParam(":updated_at", $updated_at, PDO::PARAM_STR);
                        $insertCommand->bindParam(":created_at", $created_at, PDO::PARAM_STR);
                        $insertCommand->bindParam(":type", $type, PDO::PARAM_STR);
                        $insertCommand->bindParam(":sync_status", $sync_status, PDO::PARAM_STR);

                        if ($insertCommand->execute()) {
                            $berhasil = $berhasil + 1;
                        } else {
                            $gagal = $gagal + 1;
                            array_push($id_gagal, $pengumuman_id);
                        }
                    }
                }

                echo json_encode(array('messages' => 'success', 'berhasil' => $berhasil, 'gagal' => $gagal, 'id_gagal' => $id_gagal));
                break;

            case 'aktivitas':
                $data_aktivitas_local = json_decode($data);
                $id_gagal = array();

                foreach ($data_aktivitas_local as $aktivitas) {
                    $cekAktivitasLive = Activities::model()->findByPk($aktivitas->id);
                    $aktivitas_id = $aktivitas->id;
                    $activity_type = $aktivitas->activity_type;
                    $content = $aktivitas->content;
                    $created_by = $aktivitas->created_by;
                    $updated_by = $aktivitas->updated_by;
                    $created_at = $aktivitas->created_at;
                    $updated_at = $aktivitas->updated_at;
                    $sync_status = 2;

                    if (!empty($cekAktivitasLive)) {

                        $update = "UPDATE " . $prefix . "activities SET activity_type = :activity_type, content = :content, created_by = :created_by, updated_by = :updated_by, created_at = :created_at, updated_at = :updated_at, sync_status = :sync_status WHERE id = :id";

                        $updateCommand = Yii::app()->db->createCommand($update);

                        $updateCommand->bindParam(":id", $aktivitas_id, PDO::PARAM_STR);
                        $updateCommand->bindParam(":activity_type", $activity_type, PDO::PARAM_STR);
                        $updateCommand->bindParam(":content", $content, PDO::PARAM_STR);
                        $updateCommand->bindParam(":created_by", $created_by, PDO::PARAM_STR);
                        $updateCommand->bindParam(":created_at", $created_at, PDO::PARAM_STR);
                        $updateCommand->bindParam(":updated_at", $updated_at, PDO::PARAM_STR);
                        $updateCommand->bindParam(":updated_by", $type, PDO::PARAM_STR);
                        $updateCommand->bindParam(":sync_status", $sync_status, PDO::PARAM_STR);
                        if ($updateCommand->execute()) {
                            $berhasil = $berhasil + 1;
                        } else {
                            $gagal = $gagal + 1;
                            array_push($id_gagal, $aktivitas_id);
                        }
                    } else {

                        $insert = "INSERT INTO " . $prefix . "activities (activity_type,content,created_by,updated_by,created_at,updated_at,sync_status) values(:activity_type,:content,:created_by,:updated_by,:created_at,:updated_at,:sync_status)";

                        $insertCommand = Yii::app()->db->createCommand($insert);

                        $insertCommand->bindParam(":updated_at", $updated_at, PDO::PARAM_STR);
                        $insertCommand->bindParam(":activity_type", $activity_type, PDO::PARAM_STR);
                        $insertCommand->bindParam(":content", $content, PDO::PARAM_STR);
                        $insertCommand->bindParam(":created_by", $created_by, PDO::PARAM_STR);
                        $insertCommand->bindParam(":updated_at", $updated_at, PDO::PARAM_STR);
                        $insertCommand->bindParam(":created_at", $created_at, PDO::PARAM_STR);
                        $insertCommand->bindParam(":updated_by", $type, PDO::PARAM_STR);
                        $insertCommand->bindParam(":sync_status", $sync_status, PDO::PARAM_STR);

                        if ($insertCommand->execute()) {
                            $berhasil = $berhasil + 1;
                        } else {
                            $gagal = $gagal + 1;
                            array_push($id_gagal, $aktivitas_id);
                        }
                    }
                }

                echo json_encode(array('messages' => 'success', 'berhasil' => $berhasil, 'gagal' => $gagal, 'id_gagal' => $id_gagal));
                break;

            case 'notif':
                $data_notif_local = json_decode($data);
                $id_gagal = array();

                foreach ($data_notif_local as $notif) {
                    $cekNotifLive = Notification::model()->findByPk($notif->id);
                    $notif_id = $notif->id;
                    $content = $notif->content;
                    $user_id = $notif->user_id;
                    $user_id_to = $notif->user_id_to;
                    $tipe = $notif->tipe;
                    $created_at = $notif->created_at;
                    $updated_at = $notif->updated_at;
                    $read_at = $notif->read_at;
                    $status = $notif->status;
                    $relation_id = $notif->relation_id;
                    $class_id_to = $notif->class_id_to;
                    $read_id = $notif->read_id;
                    $sync_status = 2;

                    if (!empty($cekNotifLive)) {

                        $update = "UPDATE " . $prefix . "notification SET content = :content, user_id = :user_id, user_id_to = :user_id_to, tipe = :tipe, created_at = :created_at, updated_at = :updated_at, read_at = :read_at, status = :status, relation_id = :relation_id, class_id_to = :class_id_to, read_id = :read_id, sync_status = :sync_status WHERE id = :id";

                        $updateCommand = Yii::app()->db->createCommand($update);

                        $updateCommand->bindParam(":id", $notif_id, PDO::PARAM_STR);
                        $updateCommand->bindParam(":content", $content, PDO::PARAM_STR);
                        $updateCommand->bindParam(":user_id", $user_id, PDO::PARAM_STR);
                        $updateCommand->bindParam(":user_id_to", $user_id_to, PDO::PARAM_STR);
                        $updateCommand->bindParam(":tipe", $tipe, PDO::PARAM_STR);
                        $updateCommand->bindParam(":created_at", $created_at, PDO::PARAM_STR);
                        $updateCommand->bindParam(":updated_at", $updated_at, PDO::PARAM_STR);
                        $updateCommand->bindParam(":read_at", $read_at, PDO::PARAM_STR);
                        $updateCommand->bindParam(":status", $status, PDO::PARAM_STR);
                        $updateCommand->bindParam(":relation_id", $relation_id, PDO::PARAM_STR);
                        $updateCommand->bindParam(":class_id_to", $class_id_to, PDO::PARAM_STR);
                        $updateCommand->bindParam(":read_id", $read_id, PDO::PARAM_STR);
                        $updateCommand->bindParam(":sync_status", $sync_status, PDO::PARAM_STR);
                        if ($updateCommand->execute()) {
                            $berhasil = $berhasil + 1;
                        } else {
                            $gagal = $gagal + 1;
                            array_push($id_gagal, $notif_id);
                        }
                    } else {

                        $insert = "INSERT INTO " . $prefix . "notification (content,user_id,user_id_to,tipe,created_at,updated_at,read_at,status,relation_id,class_id_to,read_id,sync_status) values(:content,:user_id,:user_id_to,:tipe,:created_at,:updated_at,:read_at,:status,:relation_id,:class_id_to,:read_id,:sync_status)";

                        $insertCommand = Yii::app()->db->createCommand($insert);

                        $insertCommand->bindParam(":updated_at", $updated_at, PDO::PARAM_STR);
                        $insertCommand->bindParam(":content", $content, PDO::PARAM_STR);
                        $insertCommand->bindParam(":user_id", $user_id, PDO::PARAM_STR);
                        $insertCommand->bindParam(":user_id_to", $user_id_to, PDO::PARAM_STR);
                        $insertCommand->bindParam(":tipe", $tipe, PDO::PARAM_STR);
                        $insertCommand->bindParam(":updated_at", $updated_at, PDO::PARAM_STR);
                        $insertCommand->bindParam(":created_at", $created_at, PDO::PARAM_STR);
                        $insertCommand->bindParam(":read_at", $read_at, PDO::PARAM_STR);
                        $insertCommand->bindParam(":status", $status, PDO::PARAM_STR);
                        $insertCommand->bindParam(":relation_id", $relation_id, PDO::PARAM_STR);
                        $insertCommand->bindParam(":class_id_to", $class_id_to, PDO::PARAM_STR);
                        $insertCommand->bindParam(":read_id", $read_id, PDO::PARAM_STR);
                        $insertCommand->bindParam(":sync_status", $sync_status, PDO::PARAM_STR);

                        if ($insertCommand->execute()) {
                            $berhasil = $berhasil + 1;
                        } else {
                            $gagal = $gagal + 1;
                            array_push($id_gagal, $user_id);
                        }
                    }
                }

                echo json_encode(array('messages' => 'success', 'berhasil' => $berhasil, 'gagal' => $gagal, 'id_gagal' => $id_gagal));
                break;

            case 'noffline':
                $data_noffline_local = json_decode($data);
                $id_gagal = array();

                foreach ($data_noffline_local as $noffline) {
                    $cekNofflineLive = OfflineMark::model()->findByPk($noffline->id);
                    $noffline_id = $noffline->id;
                    $lesson_id = $noffline->lesson_id;
                    $student_id = $noffline->student_id;
                    $score = $noffline->score;
                    $file = $noffline->file;
                    $created_at = $noffline->created_at;
                    $updated_at = $noffline->updated_at;
                    $created_by = $noffline->created_by;
                    $updated_by = $noffline->updated_by;
                    $mark_type = $noffline->mark_type;
                    $sync_status = 2;

                    if (!empty($cekNofflineLive)) {

                        $update = "UPDATE " . $prefix . "offline_mark SET lesson_id = :lesson_id, student_id = :student_id, score = :score, file = :file, created_at = :created_at, updated_at = :updated_at, created_by = :created_by, updated_by = :updated_by, mark_type = :mark_type, sync_status = :sync_status WHERE id = :id";

                        $updateCommand = Yii::app()->db->createCommand($update);

                        $updateCommand->bindParam(":id", $noffline_id, PDO::PARAM_STR);
                        $updateCommand->bindParam(":lesson_id", $lesson_id, PDO::PARAM_STR);
                        $updateCommand->bindParam(":student_id", $student_id, PDO::PARAM_STR);
                        $updateCommand->bindParam(":score", $score, PDO::PARAM_STR);
                        $updateCommand->bindParam(":file", $type, PDO::PARAM_STR);
                        $updateCommand->bindParam(":created_at", $created_at, PDO::PARAM_STR);
                        $updateCommand->bindParam(":updated_at", $updated_at, PDO::PARAM_STR);
                        $updateCommand->bindParam(":created_by", $created_by, PDO::PARAM_STR);
                        $updateCommand->bindParam(":updated_by", $updated_by, PDO::PARAM_STR);
                        $updateCommand->bindParam(":mark_type", $mark_type, PDO::PARAM_STR);
                        $updateCommand->bindParam(":sync_status", $sync_status, PDO::PARAM_STR);
                        if ($updateCommand->execute()) {
                            $berhasil = $berhasil + 1;
                        } else {
                            $gagal = $gagal + 1;
                            array_push($id_gagal, $noffline_id);
                        }
                    } else {

                        $insert = "INSERT INTO " . $prefix . "offline_mark (lesson_id,score,file,created_at,updated_at,created_by,updated_by,mark_type,sync_status) values(:lesson_id,:student_id,:score,:file,:created_at,:updated_at,:created_by,:updated_by,:mark_type,:sync_status)";

                        $insertCommand = Yii::app()->db->createCommand($insert);

                        $insertCommand->bindParam(":updated_at", $updated_at, PDO::PARAM_STR);
                        $insertCommand->bindParam(":lesson_id", $lesson_id, PDO::PARAM_STR);
                        $insertCommand->bindParam(":student_id", $student_id, PDO::PARAM_STR);
                        $insertCommand->bindParam(":score", $score, PDO::PARAM_STR);
                        $insertCommand->bindParam(":file", $type, PDO::PARAM_STR);
                        $insertCommand->bindParam(":updated_at", $updated_at, PDO::PARAM_STR);
                        $insertCommand->bindParam(":created_at", $created_at, PDO::PARAM_STR);
                        $insertCommand->bindParam(":created_by", $created_by, PDO::PARAM_STR);
                        $insertCommand->bindParam(":updated_by", $updated_by, PDO::PARAM_STR);
                        $insertCommand->bindParam(":mark_type", $mark_type, PDO::PARAM_STR);
                        $insertCommand->bindParam(":sync_status", $sync_status, PDO::PARAM_STR);

                        if ($insertCommand->execute()) {
                            $berhasil = $berhasil + 1;
                        } else {
                            $gagal = $gagal + 1;
                            array_push($id_gagal, $noffline_id);
                        }
                    }
                }

                echo json_encode(array('messages' => 'success', 'berhasil' => $berhasil, 'gagal' => $gagal, 'id_gagal' => $id_gagal));
                break;

            case 'kelas':
                $data_kelas_local = json_decode($data);
                $id_gagal = array();

                foreach ($data_kelas_local as $kelas) {
                    $cekKelasLive = Clases::model()->findByPk($kelas->id);
                    $kelas_id = $kelas->id;
                    $name = $kelas->name;
                    $teacher_id = $kelas->teacher_id;
                    $sync_status = 2;

                    if (!empty($cekKelasLive)) {

                        $update = "UPDATE " . $prefix . "class SET name = :name, teacher_id = :teacher_id, sync_status = :sync_status WHERE id = :id";

                        $updateCommand = Yii::app()->db->createCommand($update);

                        $updateCommand->bindParam(":id", $kelas_id, PDO::PARAM_STR);
                        $updateCommand->bindParam(":name", $name, PDO::PARAM_STR);
                        $updateCommand->bindParam(":teacher_id", $teacher_id, PDO::PARAM_STR);
                        $updateCommand->bindParam(":sync_status", $sync_status, PDO::PARAM_STR);
                        if ($updateCommand->execute()) {
                            $berhasil = $berhasil + 1;
                        } else {
                            $gagal = $gagal + 1;
                            array_push($id_gagal, $kelas_id);
                        }
                    } else {

                        $insert = "INSERT INTO " . $prefix . "class (name,teacher_id,sync_status) values(:name,:teacher_id,:sync_status)";

                        $insertCommand = Yii::app()->db->createCommand($insert);

                        $insertCommand->bindParam(":name", $name, PDO::PARAM_STR);
                        $insertCommand->bindParam(":teacher_id", $teacher_id, PDO::PARAM_STR);
                        $insertCommand->bindParam(":sync_status", $sync_status, PDO::PARAM_STR);

                        if ($insertCommand->execute()) {
                            $berhasil = $berhasil + 1;
                        } else {
                            $gagal = $gagal + 1;
                            array_push($id_gagal, $kelas_id);
                        }
                    }
                }

                echo json_encode(array('messages' => 'success', 'berhasil' => $berhasil, 'gagal' => $gagal, 'id_gagal' => $id_gagal));
                break;

            case 'pelajaran':
                $data_mapel_local = json_decode($data);
                $id_gagal = array();

                foreach ($data_mapel_local as $mapel) {
                    $cekMapelLive = Clases::model()->findByPk($mapel->id);
                    $mapel_id = $mapel->id;
                    $name = $mapel->name;
                    $user_id = $mapel->user_id;
                    $class_id = $mapel->class_id;
                    $created_at = $mapel->created_at;
                    $updated_at = $mapel->updated_at;
                    $created_by = $mapel->created_by;
                    $updated_by = $mapel->updated_by;
                    $sync_status = 2;

                    if (!empty($cekMapelLive)) {

                        $update = "UPDATE " . $prefix . "lesson SET name = :name, user_id = :user_id, class_id = :class_id, created_at = :created_at, updated_at = :updated_at, created_by = :created_by, updated_by = :updated_by, sync_status = :sync_status WHERE id = :id";

                        $updateCommand = Yii::app()->db->createCommand($update);

                        $updateCommand->bindParam(":id", $mapel_id, PDO::PARAM_STR);
                        $updateCommand->bindParam(":name", $name, PDO::PARAM_STR);
                        $updateCommand->bindParam(":user_id", $user_id, PDO::PARAM_STR);
                        $updateCommand->bindParam(":class_id", $class_id, PDO::PARAM_STR);
                        $updateCommand->bindParam(":created_at", $created_at, PDO::PARAM_STR);
                        $updateCommand->bindParam(":updated_at", $updated_at, PDO::PARAM_STR);
                        $updateCommand->bindParam(":created_by", $created_by, PDO::PARAM_STR);
                        $updateCommand->bindParam(":updated_by", $updated_by, PDO::PARAM_STR);
                        $updateCommand->bindParam(":sync_status", $sync_status, PDO::PARAM_STR);
                        if ($updateCommand->execute()) {
                            $berhasil = $berhasil + 1;
                        } else {
                            $gagal = $gagal + 1;
                            array_push($id_gagal, $mapel_id);
                        }
                    } else {

                        $insert = "INSERT INTO " . $prefix . "lesson (name,user_id,class_id,created_at,updated_at,created_by,updated_by,sync_status) values(:name,:user_id,:class_id,:created_at,:updated_at,:created_by,:updated_by,:sync_status)";

                        $insertCommand = Yii::app()->db->createCommand($insert);

                        $insertCommand->bindParam(":name", $name, PDO::PARAM_STR);
                        $insertCommand->bindParam(":user_id", $user_id, PDO::PARAM_STR);
                        $insertCommand->bindParam(":class_id", $class_id, PDO::PARAM_STR);
                        $insertCommand->bindParam(":created_at", $created_at, PDO::PARAM_STR);
                        $insertCommand->bindParam(":updated_at", $updated_at, PDO::PARAM_STR);
                        $insertCommand->bindParam(":created_by", $created_by, PDO::PARAM_STR);
                        $insertCommand->bindParam(":updated_by", $updated_by, PDO::PARAM_STR);
                        $insertCommand->bindParam(":sync_status", $sync_status, PDO::PARAM_STR);

                        if ($insertCommand->execute()) {
                            $berhasil = $berhasil + 1;
                        } else {
                            $gagal = $gagal + 1;
                            array_push($id_gagal, $mapel_id);
                        }
                    }
                }

                echo json_encode(array('messages' => 'success', 'berhasil' => $berhasil, 'gagal' => $gagal, 'id_gagal' => $id_gagal));
                break;

            case 'absensi':
                $data_absensi_local = json_decode($data);
                $id_gagal = array();

                foreach ($data_absensi_local as $absensi) {
                    $cekAbsensiLive = Clases::model()->findByPk($absensi->id);
                    $absensi_id = $absensi->id;
                    $type = $absensi->type;
                    $user_id = $absensi->user_id;
                    $status = $absensi->status;
                    $created_at = $absensi->created_at;
                    $alasan = $absensi->alasan;
                    $created_date = $absensi->created_date;
                    $sync_status = 2;

                    if (!empty($cekAbsensiLive)) {

                        $update = "UPDATE " . $prefix . "absensi SET type = :type, user_id = :user_id, status = :status, created_at = :created_at, alasan = :alasan, created_date = :created_date, sync_status = :sync_status WHERE id = :id";

                        $updateCommand = Yii::app()->db->createCommand($update);

                        $updateCommand->bindParam(":id", $absensi_id, PDO::PARAM_STR);
                        $updateCommand->bindParam(":type", $type, PDO::PARAM_STR);
                        $updateCommand->bindParam(":user_id", $user_id, PDO::PARAM_STR);
                        $updateCommand->bindParam(":status", $status, PDO::PARAM_STR);
                        $updateCommand->bindParam(":created_at", $created_at, PDO::PARAM_STR);
                        $updateCommand->bindParam(":alasan", $alasan, PDO::PARAM_STR);
                        $updateCommand->bindParam(":created_date", $created_date, PDO::PARAM_STR);
                        $updateCommand->bindParam(":sync_status", $sync_status, PDO::PARAM_STR);
                        if ($updateCommand->execute()) {
                            $berhasil = $berhasil + 1;
                        } else {
                            $gagal = $gagal + 1;
                            array_push($id_gagal, $absensi_id);
                        }
                    } else {

                        $insert = "INSERT INTO " . $prefix . "absensi (type,user_id,status,created_at,alasan,created_date,sync_status) values(:type,:user_id,:status,:created_at,:alasan,:created_date,:sync_status)";

                        $insertCommand = Yii::app()->db->createCommand($insert);

                        $insertCommand->bindParam(":type", $type, PDO::PARAM_STR);
                        $insertCommand->bindParam(":user_id", $user_id, PDO::PARAM_STR);
                        $insertCommand->bindParam(":status", $status, PDO::PARAM_STR);
                        $insertCommand->bindParam(":created_at", $created_at, PDO::PARAM_STR);
                        $insertCommand->bindParam(":alasan", $alasan, PDO::PARAM_STR);
                        $insertCommand->bindParam(":created_date", $created_date, PDO::PARAM_STR);
                        $insertCommand->bindParam(":sync_status", $sync_status, PDO::PARAM_STR);

                        if ($insertCommand->execute()) {
                            $berhasil = $berhasil + 1;
                        } else {
                            $gagal = $gagal + 1;
                            array_push($id_gagal, $absensi_id);
                        }
                    }
                }

                echo json_encode(array('messages' => 'success', 'berhasil' => $berhasil, 'gagal' => $gagal, 'id_gagal' => $id_gagal));
                break;

            default:

                break;
        }

        //print "fasdfs";
        //var_dump(json_decode(file_get_contents("php://input")));
    }

    public function actionGetColumnName($cekStatus = null) {
        //$post_url = "http://exambox.pinisi.co/api/postlocaldata";
        $post_url = "http://schoolraspi.com/api/postdatatolive";

        $prefix = Yii::app()->params['tablePrefix'];
        $table_name = $prefix;

        switch ($cekStatus) {
            case 'user':
                $table_name .= 'users';
                break;

            case "tugas":
                $table_name .= 'assignment';
                break;

            case "materi":
                $table_name .= 'chapters';
                break;

            case "materiFile":
                $table_name .= 'chapter_files';
                break;

            case "quiz":
                $table_name .= 'quiz';
                break;

            case "quizSiswa":
                $table_name .= 'student_quiz';
                break;

            case "soal":
                $table_name .= 'questions';
                break;

            case "lks":
                $table_name .= 'lks';
                break;

            case "pengumuman":
                $table_name .= 'announcements';
                break;

            case "nilaiOffline":
                $table_name .= 'offline_mark';
                break;

            case "aktivitas":
                $table_name .= 'activities';
                break;

            case "notifikasi":
                $table_name .= 'notification';
                break;

            case "kelas":
                $table_name .= 'class';
                break;

            case "pelajaran":
                $table_name .= 'lesson';
                break;

            case "absensi":
                $table_name .= 'absensi';
                break;

            default:
                $table_name .= 'users';
                break;
        }
        //echo $table_name;
        $sql = "SELECT * FROM $table_name WHERE sync_status IS NULL";
        $command = Yii::app()->db->createCommand($sql);

        try {
            $result = $command->queryAll();
        } catch (Exception $ex) {
            // Handle exception
        }
        echo "<pre>";
        if (!empty($result)) {
            //print_r($result);
            $total_row = count($result);
            $pass_data = array();
            $raw_data = array();
            $ids = array();
            foreach ($result as $columnName => $columnValue) {
                $raw_data[$columnName] = $columnValue;
                array_push($pass_data, $raw_data);
                //array_push($ids, $nud->id);
            }
            //$json_data = json_encode(array('table_name'=>$table_name,'data'=>$pass_data),JSON_PRETTY_PRINT);
            $json_data = json_encode($pass_data, JSON_PRETTY_PRINT);
            print_r($pass_data);
            //echo $post_url;
            //echo $json_data;
            //$post_data_url = $post_url."?type=user";
            $post_data_url = $post_url;
            $curl = curl_init();
            $wid = implode(',', $ids);

            /* curl_setopt_array($curl, array(
              CURLOPT_URL => $post_data_url,
              CURLOPT_VERBOSE => true,
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => "",
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 3000,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => "POST",
              CURLOPT_POSTFIELDS => $json_data,
              CURLOPT_HTTPHEADER => array(
              "content-type: application/json"
              ),
              ));

              $response = curl_exec($curl);
              $err = curl_error($curl);

              curl_close($curl);

              if ($err) {
              echo "cURL Error #:" . $err;
              } else {
              $dataResponse = json_decode($response);
              print_r($response);
              // if(!empty($dataUser)){
              // 	if($dataUser->gagal == NULL){
              // 		$updateLocal="UPDATE ".$prefix."users SET sync_status = 1 WHERE id IN (".$wid.")";

              // 			$updateLocalCommand=Yii::app()->db->createCommand($updateLocal);
              // 			$updateLocalCommand->execute();
              // 	}
              // }

              //echo "tugas";
              } */
        }
        echo "</pre>";

        /* foreach (Yii::app()->db->schema->getTable($table_name)->getColumnNames() as $columnName)
          {
          echo "<pre>";
          print_r($columnName);
          echo "</pre>";
          } */
    }

    public function actionPostDataToLive($type = null) {
        $json_data = file_get_contents('php://input');
        //print $data;
        $berhasil = NULL;
        $gagal = NULL;
        $prefix = Yii::app()->params['tablePrefix'];
        //echo json_encode(array('messages'=>'success','berhasil'=>$berhasil,'gagal'=>$gagal,'id_gagal'=>$id_gagal));
        //echo "<pre>";
        $local_data = json_decode($json_data);
        //echo $json_data;
        print_r($local_data);
        //$table_name = $local_data->table_name;
        //$data = $local_data->data;
        //echo $table_name;
        //print_r($data);
        // foreach ($data as $key => $value) {
        //  	print_r($key);
        // }
        //echo "</pre>";
    }

}

/* vim:set ai sw=4 sts=4 et fdm=marker fdc=4: */
?>
