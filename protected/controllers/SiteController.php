<?php

class SiteController extends Controller {

    // custom layout
    public $layout = '//layouts/column1';

    /**
     * Declares class-based actions.
     */
    public function actions() {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ),
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page' => array(
                'class' => 'CViewAction',
            ),
        );
    }

    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }

    public function accessRules() {
        return array(
            array('allow', // allow all users to perform 'index' and 'view' actions
                'actions' => array('login', 'error', 'CekLive', 'hasilujian', 'chartkd', 'mapkd', 'uploadnilai', 'downloadkd', 'ceknilaiujian'),
                'users' => array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('index', 'view', 'logout', 'autoNotif', 'autoNotif2', 'cekData'),
                // 'users'=>array('@'),
                'expression' => "(!Yii::app()->user->isGuest)",
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('hasilkd', 'chartkd'),
                'expression' => 'Yii::app()->user->YiiAdmin',
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }


      // Statistical Functions
    // ------------------------------------------------------------------------
    public function statsRTable($n) {
        $rTable = array(0, 0.996917334, 0.95, 0.878339448, 0.811401352, 0.754492234, 0.7067344, 0.666383605, 0.631896864, 0.602068777, 0.575982985, 0.552942659, 0.532412804, 0.513977484, 0.497309034, 0.482146015, 0.468277303, 0.455530502, 0.443763399, 0.432857556, 0.422713503, 0.413247029, 0.404386321, 0.396069727, 0.388243995, 0.380862857, 0.373885908, 0.367277681, 0.361006904, 0.355045884, 0.349370006, 0.343957288, 0.338788053, 0.333844617, 0.329111042, 0.324572913, 0.320217167, 0.316031926, 0.312006366, 0.308130601, 0.304395578, 0.300792993, 0.297315209, 0.293955192, 0.29070645, 0.287562981, 0.284519225, 0.281570024, 0.278710589, 0.275936458, 0.273243479, 0.270627772, 0.268085715, 0.265613918, 0.26320921, 0.260868604, 0.258589308, 0.256368692, 0.254204284, 0.252093751, 0.250034898, 0.248025651, 0.246064049, 0.244148241, 0.242276472, 0.240447082, 0.238658496, 0.236909221, 0.235197837, 0.233522997, 0.231883419, 0.230277884, 0.22870523, 0.227164349, 0.225654188, 0.224173738, 0.22272204, 0.221298173, 0.219901261, 0.218530464, 0.217184978, 0.215864035, 0.214566897, 0.213292858, 0.212041241, 0.210811396, 0.209602699, 0.208414551, 0.207246376, 0.206097622, 0.204967756, 0.203856266, 0.20276266, 0.201686463, 0.200627219, 0.199584486, 0.198557841, 0.197546874, 0.196551191, 0.195570409, 0.194604162, 0.193652093, 0.19271386, 0.19178913, 0.190877583, 0.189978909, 0.189092807, 0.188218987, 0.187357169, 0.186507079, 0.185668454, 0.18484104, 0.184024587, 0.183218858, 0.182423618, 0.181638642, 0.180863711, 0.180098614, 0.179343147, 0.178597103, 0.177860291, 0.177132523, 0.176413614, 0.175703388, 0.175001669, 0.17430829, 0.173623087, 0.172945901, 0.172276576, 0.171614962, 0.170960911, 0.170314281, 0.169674932, 0.169042729, 0.168417539, 0.167799235, 0.16718769, 0.166582782, 0.165984392, 0.165392404, 0.164806705, 0.164227183, 0.163653732, 0.163086246, 0.162524622, 0.161968759, 0.161418561, 0.160873932, 0.160334778, 0.159801008, 0.159272533, 0.158749267, 0.158231123, 0.15771802, 0.157209876, 0.156706612, 0.156208149, 0.155714413, 0.155225329, 0.154740824, 0.154260827, 0.153785269, 0.153314083, 0.1528472, 0.152384557, 0.151926089, 0.151471734, 0.151021431, 0.15057512, 0.150132743, 0.149694241, 0.149259559, 0.148828642, 0.148401435, 0.147977886, 0.147557943, 0.147141554, 0.14672867, 0.146319243, 0.145913223, 0.145510565, 0.145111222, 0.144715148, 0.1443223, 0.143932634, 0.143546106, 0.143162676, 0.142782303, 0.142404944, 0.142030562, 0.141659117, 0.141290571, 0.140924886, 0.140562026, 0.140201954, 0.139844636, 0.139490035, 0.139138118, 0.138788851, 0.138442201, 0.138098135, 0.137756622, 0.13741763, 0.137081128, 0.136747086, 0.136415473, 0.136086262, 0.135759423, 0.135434927, 0.135112747, 0.134792855, 0.134475224, 0.134159828, 0.133846641, 0.133535637, 0.133226791, 0.132920078, 0.132615473, 0.132312953, 0.132012493, 0.131714071, 0.131417664, 0.131123249, 0.130830803, 0.130540306, 0.130251735, 0.129965069, 0.129680288, 0.12939737, 0.129116296, 0.128837046, 0.128559599, 0.128283938, 0.128010041, 0.127737892, 0.127467471, 0.12719876, 0.126931742, 0.126666398, 0.126402711, 0.126140664, 0.12588024, 0.125621422, 0.125364194, 0.125108539, 0.124854443, 0.124601888, 0.124350859, 0.124101342, 0.123853321, 0.12360678, 0.123361706, 0.123118084, 0.1228759, 0.122635139, 0.122395788, 0.122157833, 0.12192126, 0.121686056, 0.121452209, 0.121219704, 0.120988529, 0.120758672, 0.12053012, 0.120302861, 0.120076883, 0.119852173, 0.119628719, 0.119406511, 0.119185537, 0.118965785, 0.118747244, 0.118529903, 0.118313751, 0.118098777, 0.11788497, 0.117672321, 0.117460818, 0.117250452, 0.117041212, 0.116833087, 0.11662607, 0.116420149, 0.116215314, 0.116011557, 0.115808868, 0.115607238, 0.115406657, 0.115207117, 0.115008608, 0.114811121, 0.114614649, 0.114419181, 0.11422471, 0.114031228, 0.113838725, 0.113647194, 0.113456626, 0.113267014, 0.11307835, 0.112890624, 0.112703831, 0.112517962, 0.112333009, 0.112148966, 0.111965824, 0.111783576, 0.111602216, 0.111421735, 0.111242127, 0.111063385, 0.110885501, 0.11070847, 0.110532284, 0.110356936, 0.11018242, 0.11000873, 0.109835858, 0.109663798, 0.109492545, 0.109322092, 0.109152432, 0.108983559, 0.108815468, 0.108648152, 0.108481606, 0.108315823, 0.108150798, 0.107986525, 0.107822998, 0.107660212, 0.107498161, 0.107336839, 0.107176242, 0.107016363, 0.106857197, 0.10669874, 0.106540985, 0.106383928, 0.106227564, 0.106071887, 0.105916893, 0.105762575, 0.105608931, 0.105455954, 0.10530364, 0.105151984, 0.105000981, 0.104850627, 0.104700918, 0.104551847, 0.104403411, 0.104255606, 0.104108427, 0.103961869, 0.103815929, 0.103670602, 0.103525883, 0.103381768, 0.103238254, 0.103095335, 0.102953009, 0.10281127, 0.102670115, 0.10252954, 0.102389541, 0.102250113, 0.102111254, 0.101972959, 0.101835224, 0.101698046, 0.101561421, 0.101425345, 0.101289814, 0.101154825, 0.101020375, 0.100886459, 0.100753074, 0.100620217, 0.100487885, 0.100356072, 0.100224778, 0.100093997, 0.099963727, 0.099833964, 0.099704705, 0.099575947, 0.099447686, 0.09931992, 0.099192645, 0.099065858, 0.098939556, 0.098813736, 0.098688395, 0.098563529, 0.098439136, 0.098315213, 0.098191757, 0.098068764, 0.097946233, 0.09782416, 0.097702542, 0.097581376, 0.09746066, 0.097340391, 0.097220567, 0.097101183, 0.096982239, 0.09686373, 0.096745655, 0.09662801, 0.096510794, 0.096394003, 0.096277635, 0.096161688, 0.096046158, 0.095931044, 0.095816343, 0.095702052, 0.095588169, 0.095474692, 0.095361618, 0.095248945, 0.09513667, 0.095024791, 0.094913306, 0.094802213, 0.094691509, 0.094581191, 0.094471258, 0.094361708, 0.094252538, 0.094143746, 0.09403533, 0.093927287, 0.093819617, 0.093712315, 0.093605381, 0.093498812, 0.093392606, 0.093286761, 0.093181276, 0.093076147, 0.092971374, 0.092866953, 0.092762883, 0.092659163, 0.09255579, 0.092452762, 0.092350077, 0.092247733, 0.092145729, 0.092044063, 0.091942732, 0.091841736, 0.091741071, 0.091640737, 0.091540731, 0.091441052, 0.091341698, 0.091242667, 0.091143957, 0.091045567, 0.090947495, 0.09084974, 0.090752298, 0.09065517, 0.090558353, 0.090461845, 0.090365645, 0.090269752, 0.090174163, 0.090078877, 0.089983893, 0.089889208, 0.089794822, 0.089700732, 0.089606938, 0.089513437, 0.089420228, 0.08932731, 0.089234681, 0.089142339, 0.089050283, 0.088958512, 0.088867025, 0.088775819, 0.088684893, 0.088594246, 0.088503876, 0.088413782, 0.088323963, 0.088234417, 0.088145143, 0.088056139, 0.087967405, 0.087878938, 0.087790737, 0.087702802, 0.08761513, 0.08752772);

        if ($n > 2) {
            return round($rTable[$n], 2);
        } elseif ($n > 500) {
            return 0;
        } else {
            return 0;
        }
    }

    public function statsMatrixSort($arrMatrixScore, $arrStudentScore, $countQuestion) {
        if (!empty($arrMatrixScore) && !empty($arrStudentScore) && $countQuestion > 0) {
            $n = count($arrStudentScore);
            if ($n > 0) {
                // Insertion Sort Descending
                for ($i = 1; $i < $n - 1; $i++) {
                    $j = $i + 1;

                    $sisipScore = $arrStudentScore[$j];
                    for ($k = 0; $k < $countQuestion; $k++) {
                        $sisipMatrix[$k] = $arrMatrixScore[$k][$j];
                    }

                    while ($j > 0 && $sisipScore > $arrStudentScore[$j - 1]) {
                        $arrStudentScore[$j] = $arrStudentScore[$j - 1];
                        for ($k = 0; $k < $countQuestion; $k++) {
                            $arrMatrixScore[$k][$j] = $arrMatrixScore[$k][$j - 1];
                        }
                        $j--;
                    }
                    $arrStudentScore[$j] = $sisipScore;
                    for ($k = 0; $k < $countQuestion; $k++) {
                        $arrMatrixScore[$k][$j] = $sisipMatrix[$k];
                    }
                }

                $retMatrixSort = array($arrMatrixScore, $arrStudentScore);
            } else {
                $retMatrixSort = FALSE;
            }
        } else {
            $retMatrixSort = FALSE;
        }

        return $retMatrixSort;
    }

    public function statsVars($arrScore) {
        $sumXi = 0;
        $sumXi2 = 0;

        $n = count($arrScore);
        if (($n - 1) > 0) {
            for ($i = 0; $i < $n; $i++) {
                $sumXi = $sumXi + $arrScore[$i];
                $sumXi2 = $sumXi2 + pow($arrScore[$i], 2);
            }
            $retVars = (($n * $sumXi2) - pow($sumXi, 2)) / ($n * ($n - 1));
        } else {
            $retVars = 0;
        }

        return round($retVars, 2);
    }

    public function statsSTDEV($arrScore) {
        if (!empty($arrScore)) {
            $vars = $this->statsVars($arrScore);
            if ($vars > 0) {
                $retSTDEV = sqrt($vars);
            } else {
                $retSTDEV = 0;
            }
        } else {
            $retSTDEV = 0;
        }

        return $retSTDEV;
    }

    public function statsPearson($arrQuestionScore, $arrStudentScore) {
        $arrCountQuestion = count($arrQuestionScore);
        $arrCountStudent = count($arrStudentScore);

        if ($arrCountQuestion == $arrCountStudent) {
            $mean1 = array_sum($arrQuestionScore) / $arrCountQuestion;
            $mean2 = array_sum($arrStudentScore) / $arrCountQuestion;

            $a = 0;
            $b = 0;
            $a2 = 0;
            $b2 = 0;
            $axb = 0;

            for ($i = 0; $i < $arrCountQuestion; $i++) {
                $a = $arrQuestionScore[$i] - $mean1;
                $b = $arrStudentScore[$i] - $mean2;

                $a2 = $a2 + pow($a, 2);
                $b2 = $b2 + pow($b, 2);

                $axb = $axb + ($a * $b);
            }

            if (sqrt($a2 * $b2) != 0) {
                $retPearson = $axb / sqrt($a2 * $b2);
            } else {
                $retPearson = 0;
            }
        } else {
            $retPearson = 0;
        }

        return round($retPearson, 2);
    }

    public function statsValidity($n, $rxy) {
        $rTable = $this->statsRTable($n - 2);

        if ($rxy > $rTable) {
            $retValidity = "Valid";
        } else {
            $retValidity = "Tidak Valid";
        }

        return $retValidity;
    }

    public function statsValidityCategory($rxy) {
        if ($rxy <= 0) {
            $retValidityCategory = "Tidak Valid";
        } elseif ($rxy > 0 && $rxy <= 0.2) {
            $retValidityCategory = "Sangat Rendah";
        } elseif ($rxy > 0.2 && $rxy <= 0.4) {
            $retValidityCategory = "Rendah";
        } elseif ($rxy > 0.4 && $rxy <= 0.6) {
            $retValidityCategory = "Sedang";
        } elseif ($rxy > 0.6 && $rxy <= 0.8) {
            $retValidityCategory = "Tinggi";
        } elseif ($rxy > 0.8 && $rxy <= 1) {
            $retValidityCategory = "Sangat Tinggi";
        } else {
            $retValidityCategory = "ERROR!";
        }

        return $retValidityCategory;
    }

    public function statsReliability($n, $varQuestionTotal, $varStudentTotal) {
        if ($n - 1 > 0 && $varStudentTotal > 0) {
            $retRealiability = ($n / ($n - 1)) * (1 - ($varQuestionTotal / $varStudentTotal));
        } else {
            $retRealiability = 0;
        }

        return round($retRealiability, 2);
    }

    public function statsReliabilityCategory($reliabilityScore) {
        if (($reliabilityScore <= 0 || $reliabilityScore > 0) && $reliabilityScore <= 0.2) {
            $retRealiabilityCategory = "Sangat Rendah";
        } elseif ($reliabilityScore > 0.2 && $reliabilityScore <= 0.4) {
            $retRealiabilityCategory = "Rendah";
        } elseif ($reliabilityScore > 0.4 && $reliabilityScore <= 0.6) {
            $retRealiabilityCategory = "Cukup";
        } elseif ($reliabilityScore > 0.6 && $reliabilityScore <= 0.8) {
            $retRealiabilityCategory = "Tinggi";
        } elseif ($reliabilityScore > 0.8 && $reliabilityScore <= 1) {
            $retRealiabilityCategory = "Sangat Tinggi";
        } else {
            $retRealiabilityCategory = "ERROR!";
        }

        return $retRealiabilityCategory;
    }

    public function statsKesukaranCatergory($kesukaranScore) {
        if ($kesukaranScore <= 0) {
            $retKesukaranCategory = "Terlalu Sukar";
        } elseif ($kesukaranScore > 0 && $kesukaranScore <= 0.3) {
            $retKesukaranCategory = "Sukar";
        } elseif ($kesukaranScore > 0.3 && $kesukaranScore <= 0.7) {
            $retKesukaranCategory = "Sedang";
        } elseif ($kesukaranScore > 0.7 && $kesukaranScore <= 1) {
            $retKesukaranCategory = "Mudah";
        } else {
            $retKesukaranCategory = "Terlalu Mudah";
        }

        return $retKesukaranCategory;
    }

    public function statsDayaPembeda($arrPerQuestionScoreSorted, $arrStudentScoreSorted) {
        if (!empty($arrPerQuestionScoreSorted) && !empty($arrStudentScoreSorted)) {
            $n = count($arrStudentScoreSorted);
            if ($n > 0) {
                // Get 30% Limit of Student
                $batas = floor(($n * 30) / 100);
                if ($batas < 1) {
                    $batas = 1;
                }

                // Count Upper Limit Score
                $sumBatasAtas = 0;
                for ($i = 0; $i < $batas; $i++) {
                    $sumBatasAtas = $sumBatasAtas + $arrPerQuestionScoreSorted[$i];
                }

                // Count Lower Limit Score
                $sumBatasBawah = 0;
                for ($i = ($n - 1); $i > (($n - 1) - $batas); $i--) {
                    $sumBatasBawah = $sumBatasBawah + $arrPerQuestionScoreSorted[$i];
                }

                // Count Daya Pembeda
                $retDayaPembeda = ((2 * ($sumBatasAtas - $sumBatasBawah)) / $n);
            } else {
                $retDayaPembeda = 0;
            }
        } else {
            $retDayaPembeda = 0;
        }

        return round($retDayaPembeda, 2);
    }

    public function statsDayaPembedaCategory($dayaPembedaScore) {
        if ($dayaPembedaScore <= 0) {
            $retDayaPembedaCategory = "Sangat Kurang (Ditolak)";
        } elseif ($dayaPembedaScore > 0 && $dayaPembedaScore <= 0.2) {
            $retDayaPembedaCategory = "Kurang (Perbaiki)";
        } elseif ($dayaPembedaScore > 0.2 && $dayaPembedaScore <= 0.4) {
            $retDayaPembedaCategory = "Cukup";
        } elseif ($dayaPembedaScore > 0.4 && $dayaPembedaScore <= 0.7) {
            $retDayaPembedaCategory = "Baik";
        } else {
            $retDayaPembedaCategory = "Sangat Baik";
        }

        return $retDayaPembedaCategory;
    }

    public function convertModelToArray($models) {
        if (is_array($models))
            $arrayMode = TRUE;
        else {
            $models = array($models);
            $arrayMode = FALSE;
        }

        $result = array();
        foreach ($models as $model) {
            $attributes = $model->getAttributes();
            $relations = array();
            foreach ($model->relations() as $key => $related) {
                if ($model->hasRelated($key)) {
                    $relations[$key] = $this->convertModelToArray($model->$key);
                }
            }
            $all = array_merge($attributes, $relations);

            if ($arrayMode)
                array_push($result, $all);
            else
                $result = $all;
        }
        return $result;
    }

    // ------------------------------------------------------------------------

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionCheckIn() {
        if (Yii::app()->user->YiiStudent) {
            //print_r($_POST);exit;
            $modelAbsensi = new Absensi;
            $modelAbsensi->user_id = $_POST['id_user'];
            $modelAbsensi->type = 'login';
            $modelAbsensi->created_date = new CDbExpression('NOW()');
            $modelAbsensi->status = 0;
            $modelAbsensi->alasan = $_POST['alasan'];
            $modelAbsensi->save(false);

            Yii::app()->user->setFlash('success', 'Absen Masuk telah dilakukan.');
            $this->redirect(array('site/index'));
        }
    }

    public function actionsetAbsenStatus() {
        if (isset($_GET['type'])) {
            $user_id = $_GET['user_id'];
            $type = $_GET['type'];
            if ($type == 'sakit') {
                $status = 99;
            } elseif ($type == 'izin') {
                $status = 100;
            } else {
                $status = 101;
            }

            //echo $status;exit;

            $modelAbsensi = new Absensi;
            $modelAbsensi->user_id = $user_id;
            $modelAbsensi->type = $type;
            $modelAbsensi->created_date = new CDbExpression('NOW()');
            $modelAbsensi->status = $status;
            $modelAbsensi->save(false);
            $this->redirect(array('site/LihatAbsen'));
        }
    }

    public function actionCheckOut($id) {
        $modelAbsensi = new Absensi;
        $userAbsensi = $modelAbsensi->findAll('user_id = :id AND DATE(NOW()) = DATE(created_date)', array(':id' => $id));
        foreach ($userAbsensi as $user) {
            $user->status = 1;
            $user->type = 'logout';
            $user->save(false);

            Yii::app()->user->setFlash('success', 'Anda berhasil Absen Keluar.');
            $this->redirect(array('site/index'));
        }
    }

    public function actionLihatAbsen() {
        $modelAbsensi = new Absensi;
        $teacher_id = NULL;
        $criteria = new CDbCriteria;
        $teacher_data = Clases::model()->findAll('teacher_id = :t_id', array(':t_id' => Yii::app()->user->id));
        if (!empty($teacher_data)) {
            foreach ($teacher_data as $row) {
                $teacher_id = $row->teacher_id;
            }
        }

        $class_data = Clases::model()->findAll();
        $jam_keluar = "10:00:00";
        date_default_timezone_set("Asia/Bangkok");

        if (time() > strtotime($jam_keluar)) {
            if ((isset($_GET['date_search']) && !empty($_GET['date_search'])) && (isset($_GET['class_id']) && !empty($_GET['class_id']))) {
                date_default_timezone_set("Asia/Bangkok");
                $class_id = $_GET['class_id'];
                //$date = new DateTime();
                $time = $_GET['date_search'];

                $sql = "SELECT  a.id as real_user_id,
								a.display_name,
								b.user_id as absensi_user_id,
								b.type,b.alasan,b.created_date,b.status,
								c.name
								FROM absensi b
							RIGHT JOIN users a ON a.id = b.user_id
							RIGHT JOIN class c ON a.class_id = c.id
							WHERE ((DATE(b.created_date) = '$time') OR b.user_id is null) AND a.role_id = 2
							AND a.class_id = $class_id
							";
            } else {
                if (isset($_GET['class_id'])) {
                    $class_id = $_GET['class_id'];
                    $sql = "SELECT  a.id as real_user_id,
									a.display_name,
									b.user_id as absensi_user_id,
									b.type,b.alasan,b.created_date,b.status,
									c.name
									FROM absensi b
							RIGHT JOIN users a ON a.id = b.user_id
							RIGHT JOIN class c ON a.class_id = c.id
							WHERE (DATE(NOW()) = DATE(b.created_date) OR b.user_id is null ) AND a.role_id = 2
							AND a.class_id= $class_id
							";
                } else {
                    $sql = "SELECT  a.id as real_user_id,
									a.display_name,
									b.user_id as absensi_user_id,
									b.type,b.alasan,b.created_date,b.status,
									c.name
									FROM absensi b
							RIGHT JOIN users a ON a.id = b.user_id
							RIGHT JOIN class c ON a.class_id = c.id
							WHERE (DATE(NOW()) = DATE(b.created_date) OR b.user_id is null) AND a.role_id = 2
							AND a.class_id=(select id from class where teacher_id=$teacher_id)
							";
                }
            }
        } else {
            if ((isset($_GET['date_search']) && !empty($_GET['date_search'])) && (isset($_GET['class_id']) && !empty($_GET['class_id']))) {
                date_default_timezone_set("Asia/Bangkok");
                $class_id = $_GET['class_id'];
                $time = $_GET['date_search'];

                //$userAbsensi = $modelAbsensi->findAll("DATE(created_date) = '$time' ");
                $sql = "SELECT * FROM absensi b
							RIGHT JOIN users a ON a.id = b.user_id
							RIGHT JOIN class c ON a.class_id = c.id
							WHERE (DATE(b.created_date) = '$time') AND a.role_id = 2
							AND a.class_id = $class_id
							";
            } else {
                if (isset($_GET['class_id'])) {
                    $class_id = $_GET['class_id'];
                    $sql = "SELECT * FROM absensi b
							RIGHT JOIN users a ON a.id = b.user_id
							RIGHT JOIN class c ON a.class_id = c.id
							WHERE (DATE(NOW()) = DATE(b.created_date)) AND a.role_id = 2
							AND a.class_id= $class_id
							";
                } else {
                    $sql = "SELECT * FROM absensi b
							RIGHT JOIN users a ON a.id = b.user_id
							RIGHT JOIN class c ON a.class_id = c.id
							WHERE (DATE(NOW()) = DATE(b.created_date)) AND a.role_id = 2
							AND a.class_id=(select id from class where teacher_id=$teacher_id)
							";
                }
            }
        }
        $userAbsensi = Yii::app()->db->createCommand($sql)->setFetchMode(PDO::FETCH_OBJ)->queryAll();
        //echo '<pre>';print_r($userAbsensi);exit;
        $this->render('absensi', array('userAbsensi' => $userAbsensi, 'class_data' => $class_data));
    }

    public function actionIndex() {
        date_default_timezone_set("Asia/Jakarta");

        $objDateTime = date("Y-m-d H:i:s");
        $prefix = Yii::app()->params['tablePrefix'];

        $isAbsenMasuk = '';
        $isAbsenKeluar = '';

        $options = Option::model()->findAll();

        if (!Yii::app()->user->isGuest) {
            $detail = User::model()->findByPk(Yii::app()->user->id);

            $current_user = Yii::app()->user->id;
            $modelUser = User::model()->findByPk($current_user);

            if (empty($options)) {
                $this->redirect(array('/option/atur'));
            } else {
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
            }
        } else {
            $detail = NULL;

            $current_user = NULL;
            $modelUser = NULL;
        }

        $criteriaQuiz = new CDbCriteria();
        $criteriaQuizDone = new CDbCriteria();
        $criteriaAssignment = new CDbCriteria();
        $criteriaAssignmentDone = new CDbCriteria();

        if (Yii::app()->user->YiiStudent) {
            $modelAbsensi = new Absensi;

            $isAbsenMasuk = $modelAbsensi->findAll('user_id = :id AND DATE(NOW()) = DATE(created_date) AND status = 0', array(':id' => Yii::app()->user->id));
            $isAbsenKeluar = $modelAbsensi->findAll('user_id = :id AND DATE(NOW()) = DATE(created_date) AND status = 1', array(':id' => Yii::app()->user->id));

            $class_student_id = $modelUser->class_id;

            $criteriaQuiz->mergeWith(array(
                'order' => 'id DESC',
                'select' => 't.*',
                'join' => 'JOIN ' . $prefix . 'lesson_mc AS lm ON lm.lesson_id = t.lesson_id ',
                'condition' => 't.semester = ' . $optSemester . ' and t.year = ' . $optTahunAjaran . ' and lm.user_id = ' . $current_user . ' and t.status is not null and t.trash is null and t.status != 2',
            ));

            $criteriaQuizDone->mergeWith(array(
                'condition' => 'student_id = ' . $current_user . ' and t.trash is null'
            ));

            $criteriaAssignment->mergeWith(array(
                'order' => 'due_date ASC',
                'join' => 'JOIN ' . $prefix . 'lesson_mc AS lm ON lm.lesson_id = t.lesson_id ',
                'condition' => 't.semester = ' . $optSemester . ' and t.year = ' . $optTahunAjaran . ' and due_date > "' . $objDateTime . '" and t.assignment_type is null and lm.user_id = ' . $current_user . ' and t.status is null and t.trash is null'
            ));

            $criteriaAssignmentDone->mergeWith(array(
                'condition' => 'student_id = ' . $current_user . ' and t.trash is null'
            ));
        } elseif (Yii::app()->user->YiiTeacher) {
            $criteriaQuiz->mergeWith(array(
                'order' => 'id DESC',
                'select' => 't.*',
                'join' => 'JOIN ' . $prefix . 'lesson AS l ON l.id = t.lesson_id ',
                'condition' => 't.semester = ' . $optSemester . ' and t.year = ' . $optTahunAjaran . ' and l.user_id = ' . $current_user . ' and t.quiz_type in (0,1,2) and t.trash is null',
            ));

            $criteriaQuizDone->mergeWith(array(
                'condition' => 'student_id = ' . $current_user . ' and t.trash is null'
            ));

            $criteriaAssignmentDone->mergeWith(array(
                'condition' => 'student_id = ' . $current_user . ' and t.trash is null'
            ));

            $criteriaAssignment->mergeWith(array(
                'order' => 'due_date ASC',
                'join' => 'JOIN ' . $prefix . 'lesson AS l ON l.id = t.lesson_id ',
                'condition' => 't.semester = ' . $optSemester . ' and t.year = ' . $optTahunAjaran . ' and due_date > "' . $objDateTime . '" and l.user_id = ' . $current_user . ' and t.trash is null'
            ));
        } elseif (Yii::app()->user->YiiAdmin) {
            $criteriaQuiz->mergeWith(array(
                'order' => 'id DESC',
                'select' => 't.*',
                'join' => 'JOIN ' . $prefix . 'lesson AS l ON l.id = t.lesson_id ',
                'condition' => 't.semester = ' . $optSemester . ' and t.year = ' . $optTahunAjaran . ' and t.trash is null',
            ));

            $criteriaAssignment->mergeWith(array(
                'order' => 'due_date ASC',
                'join' => 'JOIN ' . $prefix . 'lesson AS l ON l.id = t.lesson_id ',
                'condition' => 't.semester = ' . $optSemester . ' and t.year = ' . $optTahunAjaran . ' and due_date > "' . $objDateTime . '" and t.trash is null'
            ));

            $criteriaQuizDone->mergeWith(array(
                'condition' => 'student_id = ' . $current_user . ' and t.trash is null'
            ));

            $criteriaAssignmentDone->mergeWith(array(
                'condition' => 'student_id = ' . $current_user . ' and t.trash is null'
            ));
        }
        $countQuiz = Quiz::model()->count($criteriaQuiz);
        $countAssignment = Assignment::model()->count($criteriaAssignment);

        $pagesQuiz = new CPagination($countQuiz);
        if (Yii::app()->user->YiiStudent) {
            $pagesQuiz->pageSize = 200;
        } else {
            $pagesQuiz->pageSize = 2;
        }

        $pagesQuiz->applyLimit($criteriaQuiz);
        $modelQuiz = Quiz::model()->findAll($criteriaQuiz);

        $pagesAssignment = new CPagination($countAssignment);
        if (Yii::app()->user->YiiStudent) {
            $pagesAssignment->pageSize = 200;
        } else {
            $pagesAssignment->pageSize = 2;
        }

        $pagesAssignment->applyLimit($criteriaAssignment);
        $modelAssignment = Assignment::model()->findAll($criteriaAssignment);

        $modelAssignmentDone = StudentAssignment::model()->findAll($criteriaAssignmentDone);
        $modelQuizDone = StudentQuiz::model()->findAll($criteriaQuizDone);

        Yii::app()->session->remove('returnURL');
        $this->render('v2/index', array(
            'modelQuiz' => $modelQuiz,
            'modelQuizDone' => $modelQuizDone,
            'modelAssignment' => $modelAssignment,
            'modelAssignmentDone' => $modelAssignmentDone,
            'pagesQuiz' => $pagesQuiz,
            'pagesAssignment' => $pagesAssignment,
            'isAbsenMasuk' => $isAbsenMasuk,
            'isAbsenKeluar' => $isAbsenKeluar,
            'detail' => $detail,
        ));

        // echo "<pre>";
        // print_r($modelQuiz);
        // print_r($modelAssignment);
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError() {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    /**
     * Displays the contact page
     */
    public function actionContact() {
        $hari = date('Y-m-d', time());
        //$hari = '2015-08-20';
        $kumpulTugas10 = new CActiveDataProvider('StudentAssignment', array(
            'criteria' => array(
                'condition' => 'created_at LIKE "%' . $hari . '%"'),
            'pagination' => false,
        ));


        $uploadMateri10 = new CActiveDataProvider('Chapters', array(
            'criteria' => array(
                'condition' => 'created_at LIKE "%' . $hari . '%"'),
            'pagination' => false,
        ));


        $nilai10 = new CActiveDataProvider('StudentAssignment', array(
            'criteria' => array(
                'condition' => 'created_at LIKE "%' . $hari . '%" AND score IS NOT NULL'),
            'pagination' => false,
        ));

        $kuis10 = new CActiveDataProvider('Quiz', array(
            'criteria' => array(
                'condition' => 'created_at LIKE "%' . $hari . '%"'),
            'pagination' => false,
        ));

        $nilaiKuis10 = new CActiveDataProvider('StudentQuiz', array(
            'criteria' => array(
                'condition' => 'created_at LIKE "%' . $hari . '%"'),
            'pagination' => false,
        ));

        $soal10 = new CActiveDataProvider('Questions', array(
            'criteria' => array(
                'condition' => 'created_at LIKE "%' . $hari . '%"'),
            'pagination' => false,
        ));


        $user = new CActiveDataProvider('User', array(
            'pagination' => false));
        $acts = Activities::model()->findAll(array('condition' => 'created_at LIKE "%' . $hari . '%"'));

        $ulog = UserLogs::model()->findAll(array('condition' => 'created_at LIKE "%' . $hari . '%" and type LIKE "%login%"'));

        $this->render('contact', array(
            'kumpulTugas10' => $kumpulTugas10,
            'uploadMateri10' => $uploadMateri10,
            'nilai10' => $nilai10,
            'kuis10' => $kuis10,
            'nilaiKuis10' => $nilaiKuis10,
            'soal10' => $soal10,
            'user' => $user,
            'act' => $acts,
            'ulog' => $ulog,
        ));
    }

    public function actionLog() {
        $hari = date('Y-m-d', time());
        $model = new Activities;
        if (isset($_POST['Activities'])) {

            $model->attributes = $_POST['Activities'];
            $hari = date('Y-m-d', strtotime($model->activity_type));
            //print_r($hari);
        }
        //$hari = '2015-08-20';
        $kumpulTugas10 = new CActiveDataProvider('StudentAssignment', array(
            'criteria' => array(
                'condition' => 'created_at LIKE "%' . $hari . '%"'),
            'pagination' => false,
        ));


        // $uploadMateri10 = new CActiveDataProvider('Chapters',array(
        // 				'criteria'=>array(
        // 					'condition'=>'created_at LIKE "%'.$hari.'%"'),
        // 				'pagination'=>false,
        // 				));
        // $uploadTugas10 = new CActiveDataProvider('Assignment',array(
        // 				'criteria'=>array(
        // 					'condition'=>'created_at LIKE "%'.$hari.'%"'),
        // 				'pagination'=>false,
        // 				));


        $nilai10 = new CActiveDataProvider('StudentAssignment', array(
            'criteria' => array(
                'condition' => 'created_at LIKE "%' . $hari . '%" AND score IS NOT NULL'),
            'pagination' => false,
        ));

        // $kuis10 = new CActiveDataProvider('Quiz',array(
        // 	'criteria'=>array(
        // 		'condition'=>'created_at LIKE "%'.$hari.'%"'),
        // 	'pagination'=>false,
        // 	));

        $kuis10 = new CActiveDataProvider('activities', array(
            'criteria' => array(
                'condition' => 'created_at LIKE "%' . $hari . '%" AND activity_type = "new_quiz"'),
            'pagination' => false,
        ));

        $nilaiKuis10 = new CActiveDataProvider('StudentQuiz', array(
            'criteria' => array(
                'condition' => 'created_at LIKE "%' . $hari . '%"'),
            'pagination' => false,
        ));

        $soal10 = new CActiveDataProvider('Questions', array(
            'criteria' => array(
                'condition' => 'created_at LIKE "%' . $hari . '%"'),
            'pagination' => false,
        ));


        $user = new CActiveDataProvider('User', array(
            'pagination' => false));
        $acts = Activities::model()->findAll(array('condition' => 'created_at LIKE "%' . $hari . '%"'));

        $ulog = UserLogs::model()->findAll(array('condition' => 'created_at LIKE "%' . $hari . '%" and type LIKE "%login%"'));

        $o_f_quiz = new CActiveDataProvider('activities', array(
            'criteria' => array(
                'condition' => 'created_at LIKE "%' . $hari . '%" AND activity_type = "open_form_create_quiz"'),
            'pagination' => false,
        ));

        $o_f_tugas = new CActiveDataProvider('activities', array(
            'criteria' => array(
                'condition' => 'created_at LIKE "%' . $hari . '%" AND activity_type = "open_form_create_assignment"'),
            'pagination' => false,
        ));

        $o_f_materi = new CActiveDataProvider('activities', array(
            'criteria' => array(
                'condition' => 'created_at LIKE "%' . $hari . '%" AND activity_type = "open_form_new_chapter"'),
            'pagination' => false,
        ));

        $uploadMateri10 = new CActiveDataProvider('activities', array(
            'criteria' => array(
                'condition' => 'created_at LIKE "%' . $hari . '%" AND activity_type = "new_chapter"'),
            'pagination' => false,
        ));

        $uploadTugas10 = new CActiveDataProvider('activities', array(
            'criteria' => array(
                'condition' => 'created_at LIKE "%' . $hari . '%" AND activity_type = "new_assignment"'),
            'pagination' => false,
        ));

        $this->render('contact', array(
            'kumpulTugas10' => $kumpulTugas10,
            'uploadMateri10' => $uploadMateri10,
            'nilai10' => $nilai10,
            'kuis10' => $kuis10,
            'nilaiKuis10' => $nilaiKuis10,
            'soal10' => $soal10,
            'uploadTugas10' => $uploadTugas10,
            'user' => $user,
            'act' => $acts,
            'ulog' => $ulog,
            'model' => $model,
            'hari' => $hari,
            'o_f_quiz' => $o_f_quiz,
            'o_f_tugas' => $o_f_tugas,
            'o_f_materi' => $o_f_materi
        ));
    }

    /**
     * Displays the login page
     */
    public function actionLogin() {
        $model = new LoginForm;
        $user_log = new UserLogs;

        if (Option::model()->findByAttributes(array('key_config' => 'school_name'))) {
            $nama_sekolah = Option::model()->findByAttributes(array('key_config' => 'school_name'))->value;
        } else {
            $nama_sekolah = "Sekolah Pinisi";
        }


        $berita_term = 'type = 1';
        $berita = new CActiveDataProvider('Announcements', array(
            'criteria' => array(
                'order' => 'created_at DESC',
                'condition' => $berita_term
            ),
            'pagination' => array('pageSize' => 3,),
            'totalItemCount' => 3,)
        );

        if (!Yii::app()->user->isGuest) {
            $this->redirect(array('/site/index'));
        }

        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if (isset($_POST['LoginForm'])) {
            $model->attributes = $_POST['LoginForm'];
            // validate user input and redirect to the previous page if valid
            if ($model->validate() && $model->login()) {
                $cekAktif = User::model()->findByPk(Yii::app()->user->id);
                if ($cekAktif->trash == 1) {
                    Yii::app()->user->setFlash('error', 'Login Gagal User Telah Dihapus!');
                    Yii::app()->user->logout();
                    //$this->redirect(Yii::app()->homeUrl);
                }

                if ($cekAktif->role_id == 2) {
                    Yii::app()->user->setFlash('error', 'Siswa dilarang masuk menggunakan versi ini!');
                    Yii::app()->user->logout();
                    //$this->redirect(Yii::app()->homeUrl);
                }

                if ($cekAktif->role_id == 3) {
                    Yii::app()->user->setFlash('error', 'Orang tua/Kepsek dilarang masuk menggunakan versi ini!');
                    Yii::app()->user->logout();
                    //$this->redirect(Yii::app()->homeUrl);
                }

                $user_log->user_id = Yii::app()->user->id;
                $user_log->type = 'login';
                $user_log->created_at = new CDbExpression('NOW()');
                $user_log->save();

                // if(Yii::app()->session['semester']){
                // 	$optSemester = Yii::app()->session['semester'];
                // } else {
                // 	$optSemester=Option::model()->findByAttributes(array('key_config'=>'semester'))->value;
                // }
                // 	if(Yii::app()->session['tahun_ajaran']){
                // 	$optTahunAjaran = Yii::app()->session['tahun_ajaran'];
                // } else {
                // 	$optTahunAjaran=Option::model()->findByAttributes(array('key_config'=>'tahun_ajaran'))->value;
                // }

                $optSemester = Option::model()->findByAttributes(array('key_config' => 'semester'));
                if (!empty($optSemester)) {
                    Yii::app()->session['semester'] = $optSemester->value;
                }


                $optTahunAjaran = Option::model()->findByAttributes(array('key_config' => 'tahun_ajaran'));
                if (!empty($optTahunAjaran)) {
                    Yii::app()->session['tahun_ajaran'] = $optTahunAjaran->value;
                }

                Yii::app()->user->setFlash('success', 'Login Berhasil');
                // echo $optTahunAjaran;
                $this->redirect(Yii::app()->user->returnUrl);
            }

            Yii::app()->user->setFlash('error', 'Login Gagal!');
        }
        // display the login form
        $this->render('v2/login', array('model' => $model, 'nama_sekolah' => $nama_sekolah, 'berita' => $berita));
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout() {
        $user_log = new UserLogs;
        $user_log->user_id = Yii::app()->user->id;
        $user_log->type = 'logout';
        $user_log->created_at = new CDbExpression('NOW()');
        $user_log->save();

        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }

    public function actionForget() {

        if (isset($_POST['username'])) {

            $username = $_POST['username'];

            if (strpos($username, '@') !== false) {
                $modelUser = User::model()->findAll('LOWER(email)=?', array(strtolower($username)));
            } else {
                //Otherwise we search using the username
                $modelUser = User::model()->findAll('LOWER(username)=?', array(strtolower($username)));
            }

            if (!empty($modelUser)) {

                foreach ($modelUser as $get) {
                    $user_id = $get->id;
                    $email = $get->email;
                }


                $cekAdmin = User::model()->findAll(array('condition' => 'role_id = 99'));

                foreach ($cekAdmin as $admin) {
                    $model = new Notification;

                    $model->user_id = $user_id;

                    $model->user_id_to = $admin->id;
                    $model->content = "Saya mengajukan reset password.";
                    $model->tipe = "reset-pwd";
                    $model->relation_id = $user_id;


                    //$random_password = User::model()->generateRandomString(5);


                    if ($model->save()) {
                        //$this->redirect(array('view','id'=>$model->id));
                        Yii::app()->user->setFlash('success', 'Pengajuan Reset password berhasil. Mohon tunggu konfirmasi admin');
                    } else {
                        Yii::app()->user->setFlash('error', 'gagal save');
                    }
                }
            } else {
                Yii::app()->user->setFlash('error', 'NIP/NIK tidak ditemukan.');
            }
        }

        $this->render('forget', array(//'model'=>$model,
        ));
    }

    public function actionAutoNotif() {

        $count = 0;
        if (!Yii::app()->user->isGuest) {
            $current_user = Yii::app()->user->id;


            $modelNotif = Notification::model()->findAll(array("condition" => "user_id_to = $current_user and read_at is null"));
            if (!empty($modelNotif)) {
                $count_add = count($modelNotif);
                //Yii::app()->user->setState('__yiiCNotif',$count);
                $count += $count_add;

                //echo $count;
            } else {
                //Yii::app()->user->setState('__yiiCNotif',12);
                //echo $count;
            }

            if (Yii::app()->user->YiiStudent) {

                $modelUser = User::model()->findByPk($current_user);
                $class_student_id = $modelUser->class_id;
                if (!empty($class_student_id)) {
                    $modelNotifmany = Notification::model()->findAll(array("condition" => "class_id_to = $class_student_id and read_id not like '%,$current_user,%'"));
                    if (!empty($modelNotifmany)) {
                        /* $i = 0;
                          foreach ($modelNotifmany as $value) {
                          $read_id = $value->read_id;

                          $arr_read_id = unserialize($read_id);

                          if (in_array($current_user, $arr_read_id)){
                          // data has read
                          } else {
                          $i++;
                          }
                          }

                          $count += $i; */
                        $count_add2 = count($modelNotifmany);
                        $count += $count_add2;
                        //echo "-1";
                    } else {
                        //echo "-2";
                    }
                }
            } else {
                //echo -3;
            }
        } else {
            //echo $count;
        }
        echo $count;
    }

    public function actionAutoNotif2() {

        $count = 0;
        if (!Yii::app()->user->isGuest) {
            $current_user = Yii::app()->user->id;


            $modelNotif = Notification::model()->findAll(array("condition" => "user_id_to = $current_user and read_at is null"));
            if (!empty($modelNotif)) {
                $count_add = count($modelNotif);
                //Yii::app()->user->setState('__yiiCNotif',$count);
                $count += $count_add;

                //echo $count;
            } else {
                //Yii::app()->user->setState('__yiiCNotif',12);
                //echo $count;
            }

            if (Yii::app()->user->YiiStudent) {

                $modelUser = User::model()->findByPk($current_user);
                $class_student_id = $modelUser->class_id;
                if (!empty($class_student_id)) {
                    $modelNotifmany = Notification::model()->findAll(array("condition" => "class_id_to = $class_student_id and read_id not like '%,$current_user,%'"));
                    if (!empty($modelNotifmany)) {
                        /* $i = 0;
                          foreach ($modelNotifmany as $value) {
                          $read_id = $value->read_id;

                          $arr_read_id = unserialize($read_id);

                          if (in_array($current_user, $arr_read_id)){
                          // data has read
                          } else {
                          $i++;
                          }
                          }

                          $count += $i; */
                        $count_add2 = count($modelNotifmany);
                        $count += $count_add2;
                        //echo "-1";
                    } else {
                        //echo "-2";
                    }
                }
            } else {
                //echo -3;
            }
        } else {
            //echo $count;
        }
        echo $count;
    }

    public function actionNotifMsg() {

        $count = 0;
        $data = array();
        if (!Yii::app()->user->isGuest) {
            $current_user = Yii::app()->user->id;


            $modelNotif = Notification::model()->findAll(array("condition" => "user_id_to = $current_user and read_at is null"));
            if (!empty($modelNotif)) {
                $count_add = count($modelNotif);
                //Yii::app()->user->setState('__yiiCNotif',$count);
                $count += $count_add;

                //echo $count;
            } else {
                //Yii::app()->user->setState('__yiiCNotif',12);
                //echo $count;
            }

            if (Yii::app()->user->YiiStudent) {

                $modelUser = User::model()->findByPk($current_user);
                $class_student_id = $modelUser->class_id;
                if (!empty($class_student_id)) {
                    $modelNotifmany = Notification::model()->findAll(array("condition" => "class_id_to = $class_student_id and read_id not like '%,$current_user,%'"));
                    if (!empty($modelNotifmany)) {
                        foreach ($modelNotifmany as $key) {
                            $data['content'] = $key->content;
                            $data['tipe'] = $key->tipe;
                            $data['relation_id'] = $key->relation_id;
                        }
                        /* $i = 0;
                          foreach ($modelNotifmany as $value) {
                          $read_id = $value->read_id;

                          $arr_read_id = unserialize($read_id);

                          if (in_array($current_user, $arr_read_id)){
                          // data has read
                          } else {
                          $i++;
                          }
                          }

                          $count += $i; */
                        $count_add2 = count($modelNotifmany);
                        $count += $count_add2;
                        //echo "-1";
                    } else {
                        //echo "-2";
                    }
                }
            } else {
                //echo -3;
            }
        } else {
            //echo $count;
        }
        //echo $count;
        echo json_encode($data);
    }

    public function actionUserStatus() {
        /* if (!Yii::app()->user->isGuest) {

          } else {

          } */
        echo json_encode($_POST['user_id']);
    }

    public function actionAcak() {
        $user_id = $_POST['user'];
        $quiz_id = $_POST['quiz'];
        $cekStatus = new UserLogs;
        //$status = $_POST['status'];
        //$cekStatus = UserLogs::model()->findByAttributes(array('user_id'=>$user_id,'quiz_id'=>$quiz_id));

        /* if(empty($cekStatus)){
          $cekStatus = new UserLogs;
          } */


        //$user = User::model()->findByPk($user_id);
        //$quiz = Quiz::model()->findByPk($quiz_id);
        /* $cekStatus->user_id = $user_id;
          $cekStatus->quiz_id = $quiz_id;
          $cekStatus->created_at = new CDbExpression('NOW()');
          $cekStatus->status = 1;
          $cekStatus->type = "inactive";
          if($cekStatus->save()){
          echo "Sip";
          }else{
          echo "Gagal Keleus";
          } */

        echo "inactive";
        //echo "<pre>";
        //echo "Berhasil";
        //print_r($cekStatus->status);
        //echo "</pre>";
    }

    public function actionCekData() {
        if ($_POST['quiz'] !== 'undefined') {
            $quiz_id = $_POST['quiz'];
            if (!Yii::app()->user->isGuest) {
                if ($quiz_id != "undefined") {
                    $cek = StudentQuiz::model()->findByAttributes(array('quiz_id' => $quiz_id, 'student_id' => Yii::app()->user->id));
                } else {
                    $cek = NULL;
                }

                if (!empty($cek->id)) {
                    echo $hasil = 1;
                } else {
                    echo $hasil = 2;
                }
            }
        } else {
            echo $hasil = 0;
        }
    }

    public function actionCekLogin() {

        Yii::app()->request->getUserHostAddress();
    }

    public function actionCekLive() {

        echo "Live";
    }

    public function actionTimeline() {
        $berita = new CActiveDataProvider('Announcements', array(
            'criteria' => array(
                'order' => 'created_at DESC',
            ),
            'pagination' => array('pageSize' => 3,),
            'totalItemCount' => 3,)
        );
        $prefix = Yii::app()->params['tablePrefix'];

        if (Yii::app()->user->YiiStudent) {
            $current_user = Yii::app()->user->id;
            $modelUser = User::model()->findByPk($current_user);
            $class_student_id = $modelUser->class_id;
            $term_conditon = "and l.class_id = $class_student_id";
            $term_conditon_done = "and t.id NOT IN (select t.id from " . $prefix . "assignment t JOIN " . $prefix . "lesson AS l ON l.id = t.lesson_id JOIN " . $prefix . "class_detail AS c ON c.id = l.class_id JOIN " . $prefix . "student_assignment sa on t.id = sa.assignment_id and sa.student_id = $current_user)";


            date_default_timezone_set("Asia/Jakarta");
            $objDateTime = date("Y-m-d H:i:s");

            //## late criteria
            $criteria = new CDbCriteria();
            $criteria->mergeWith(array(
                'order' => 'due_date DESC',
                'join' => 'JOIN ' . $prefix . 'lesson AS l ON l.id = t.lesson_id JOIN ' . $prefix . 'class_detail AS c ON c.id = l.class_id',
                'condition' => "due_date < '$objDateTime' $term_conditon $term_conditon_done"
            ));
            $count = Assignment::model()->count($criteria);
            $pages = new CPagination($count);

            // results per page
            $pages->pageSize = 3;
            $perpage = $pages->pageSize;
            $pages->applyLimit($criteria);
            $late_asg = Assignment::model()->findAll($criteria);


            //## upcoming criteria
            $criteria = new CDbCriteria();
            $criteria->mergeWith(array(
                'order' => 'due_date ASC',
                'join' => 'JOIN ' . $prefix . 'lesson AS l ON l.id = t.lesson_id JOIN ' . $prefix . 'class_detail AS c ON c.id = l.class_id',
                'condition' => "due_date > '$objDateTime' $term_conditon $term_conditon_done"
            ));
            $count = Assignment::model()->count($criteria);
            $pages_upasg = new CPagination($count);

            // results per page
            $pages_upasg->pageSize = 3;
            $perpage = $pages_upasg->pageSize;
            $pages_upasg->applyLimit($criteria);
            $upcoming_asg = Assignment::model()->findAll($criteria);

            //## activity criteria
            $criteria = new CDbCriteria();
            $criteria->mergeWith(array(
                'order' => 'created_at ASC',
            ));
            $count = Activities::model()->count($criteria);
            $pages_activity = new CPagination($count);

            // results per page
            $pages_activity->pageSize = 1;
            $perpage = $pages_activity->pageSize;
            $pages_activity->applyLimit($criteria);
            $activity = Activities::model()->findAll($criteria);


            //## score criteria
            $criteria = new CDbCriteria();
            $criteria->mergeWith(array(
                'order' => 't.id DESC',
                'select' => 't.id, a.title as title, t.score',
                'join' => 'JOIN ' . $prefix . 'assignment AS a ON a.id = t.assignment_id',
                'condition' => 'student_id = ' . $current_user,
            ));
            $count = StudentAssignment::model()->count($criteria);
            $pages_scores = new CPagination($count);

            // results per page
            $pages_scores->pageSize = 3;
            $perpage = $pages_scores->pageSize;
            $pages_scores->applyLimit($criteria);
            $scores = StudentAssignment::model()->findAll($criteria);
        } else {
            $late_asg = null;
            $objDateTime = null;
            $upcoming_asg = null;
            $activity = null;
            $scores = null;
            $pages = null;
            $pages_upasg = null;
            $pages_activity = null;
            $pages_scores = null;
        }


        $this->render('index-t', array(
            'berita' => $berita,
            'late_asg' => $late_asg,
            'objDateTime' => $objDateTime,
            'upcoming_asg' => $upcoming_asg,
            'activity' => $activity,
            'scores' => $scores,
            'late_asg' => $late_asg,
            'pages' => $pages,
            'pages_upasg' => $pages_upasg,
            'pages_activity' => $pages_activity,
            'pages_scores' => $pages_scores,
        ));
    }

    public function actionTimeline2() {
        $berita = new CActiveDataProvider('Announcements', array(
            'criteria' => array(
                'order' => 'created_at DESC',
            ),
            'pagination' => array('pageSize' => 3,),
            'totalItemCount' => 3,)
        );
        $prefix = Yii::app()->params['tablePrefix'];
        if (Yii::app()->user->YiiStudent) {
            $current_user = Yii::app()->user->id;
            $modelUser = User::model()->findByPk($current_user);
            $class_student_id = $modelUser->class_id;
            $term_conditon = "and l.class_id = $class_student_id";
            $term_conditon_done = "and t.id NOT IN (select t.id from " . $prefix . "assignment t JOIN " . $prefix . "lesson AS l ON l.id = t.lesson_id JOIN " . $prefix . "class_detail AS c ON c.id = l.class_id JOIN " . $prefix . "student_assignment sa on t.id = sa.assignment_id and sa.student_id = $current_user)";


            date_default_timezone_set("Asia/Jakarta");
            $objDateTime = date("Y-m-d H:i:s");

            //## late criteria
            $criteria = new CDbCriteria();
            $criteria->mergeWith(array(
                'order' => 'due_date DESC',
                'join' => 'JOIN ' . $prefix . 'lesson AS l ON l.id = t.lesson_id JOIN ' . $prefix . 'class_detail AS c ON c.id = l.class_id',
                'condition' => "due_date < '$objDateTime' $term_conditon $term_conditon_done"
            ));
            $count = Assignment::model()->count($criteria);
            $pages = new CPagination($count);

            // results per page
            $pages->pageSize = 3;
            $perpage = $pages->pageSize;
            $pages->applyLimit($criteria);
            $late_asg = Assignment::model()->findAll($criteria);


            $upcoming_asg = null;
            $activity = null;
            $scores = null;
            $pages_upasg = null;
            $pages_activity = null;
            $pages_scores = null;
        } else {
            $late_asg = null;
            $objDateTime = null;
            $upcoming_asg = null;
            $activity = null;
            $scores = null;
            $pages = null;
            $pages_upasg = null;
            $pages_activity = null;
            $pages_scores = null;
        }


        $this->render('index-t', array(
            'berita' => $berita,
            'late_asg' => $late_asg,
            'objDateTime' => $objDateTime,
            'upcoming_asg' => $upcoming_asg,
            'activity' => $activity,
            'scores' => $scores,
            'late_asg' => $late_asg,
            'pages' => $pages,
            'pages_upasg' => $pages_upasg,
            'pages_activity' => $pages_activity,
            'pages_scores' => $pages_scores,
        ));
    }

    public function actionRole($role = null) {
        $berita = new CActiveDataProvider('Announcements', array(
            'criteria' => array(
                'order' => 'created_at DESC',
            ),
            'pagination' => array('pageSize' => 3,),
            'totalItemCount' => 3,)
        );
        $prefix = Yii::app()->params['tablePrefix'];
        if (Yii::app()->user->YiiStudent) {
            $current_user = Yii::app()->user->id;
            $modelUser = User::model()->findByPk($current_user);
            $class_student_id = $modelUser->class_id;
            $term_conditon = "and l.class_id = $class_student_id";
            $term_conditon_done = "and t.id NOT IN (select t.id from " . $prefix . "assignment t JOIN " . $prefix . "lesson AS l ON l.id = t.lesson_id JOIN " . $prefix . "class_detail AS c ON c.id = l.class_id JOIN " . $prefix . "student_assignment sa on t.id = sa.assignment_id and sa.student_id = $current_user)";


            date_default_timezone_set("Asia/Jakarta");
            $objDateTime = date("Y-m-d H:i:s");

            if (empty($role) or $role == 'activity') {
                $criteria = new CDbCriteria();
                $criteria->mergeWith(array(
                    'order' => 'created_at ASC',
                ));
                $count = Activities::model()->count($criteria);
                $pages = new CPagination($count);

                // results per page
                $pages->pageSize = 3;
                $perpage = $pages->pageSize;
                $pages->applyLimit($criteria);
                $models = Activities::model()->findAll($criteria);
            } elseif ($role == 'late') {
                $criteria = new CDbCriteria();
                $criteria->mergeWith(array(
                    'order' => 'due_date DESC',
                    'join' => 'JOIN ' . $prefix . 'lesson AS l ON l.id = t.lesson_id JOIN ' . $prefix . 'class_detail AS c ON c.id = l.class_id',
                    'condition' => "due_date < '$objDateTime' $term_conditon $term_conditon_done"
                ));
                $count = Assignment::model()->count($criteria);
                $pages = new CPagination($count);

                // results per page
                $pages->pageSize = 3;
                $perpage = $pages->pageSize;
                $pages->applyLimit($criteria);
                $models = Assignment::model()->findAll($criteria);
            } elseif ($role == 'upcoming') {
                $criteria = new CDbCriteria();
                $criteria->mergeWith(array(
                    'order' => 'due_date ASC',
                    'join' => 'JOIN ' . $prefix . 'lesson AS l ON l.id = t.lesson_id JOIN ' . $prefix . 'class_detail AS c ON c.id = l.class_id',
                    'condition' => "due_date > '$objDateTime' $term_conditon $term_conditon_done"
                ));
                $count = Assignment::model()->count($criteria);
                $pages = new CPagination($count);

                // results per page
                $pages->pageSize = 3;
                $perpage = $pages->pageSize;
                $pages->applyLimit($criteria);
                $models = Assignment::model()->findAll($criteria);
            } elseif ($role == 'nilai') {
                $criteria = new CDbCriteria();
                $criteria->mergeWith(array(
                    'order' => 't.id DESC',
                    'select' => 't.id, a.title as title, t.score',
                    'join' => 'JOIN ' . $prefix . 'assignment AS a ON a.id = t.assignment_id',
                    'condition' => 'student_id = ' . $current_user,
                ));
                $count = StudentAssignment::model()->count($criteria);
                $pages = new CPagination($count);

                // results per page
                $pages->pageSize = 3;
                $perpage = $pages->pageSize;
                $pages->applyLimit($criteria);
                $models = StudentAssignment::model()->findAll($criteria);
            }
        } else {
            $models = null;
            $pages = null;
        }


        $this->render('itimeline', array(
            'models' => $models,
            'role' => $role,
            'pages' => $pages,
            'berita' => $berita,
        ));
    }

    public function actionAllTest() {
        $model = new Activities;

        if (isset($_POST['Activities'])) {
            //echo "<pre>";
            print_r($json);
            //echo "</pre>";
        }

        $this->render('test-page', array(
            'model' => $model,
        ));
    }

    // public function actionTestCommand(){
    // 	echo "<pre>";
    // 	## First converts your presentation to PDF
    // 	//unoconv -f pdf presentation.ppt
    // 	## Then convert your PDF to jpg
    // 	//convert presentation.pdf presentation_%03d.jpg
    // 	//putenv('PATH=/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin:/usr/games:/usr/local/games:/opt/node/bin');
    // 	//putenv("/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin:/usr/games:/usr/local/games:/usr/lib/jvm/java-7-oracle/bin:/usr/lib/jvm/java-7-oracle/db/bin:/usr/lib/jvm/java-7-oracle/jre/bin:/home/bagusekos/Documents/android-studio/bin");
    // 	//$cmd = "unoconv -f pdf /home/bagusekos/Documents/BANGUN_RUANG.ppt";
    // 	$cmd = "unoconv -f pdf /home/bagusekos/Documents/BANGUN_RUANG.ppt";
    // 	//echo passthru($cmd);
    // 	exec($cmd,$result);
    // 	print_r($result);
    // 	echo "</pre>";
    // }

    /**
     * Displays the login page
     */
    public function actionHasilujian($type = "uts", $school = 'all', $class = 'all', $quiz = 'all') {
        $optSemester = Option::model()->findByAttributes(array('key_config' => 'semester'))->value;
        $optTahunAjaran = Option::model()->findByAttributes(array('key_config' => 'tahun_ajaran'))->value;
        $school_list = array('all' => 'Semua Sekolah...');
        $quiz_list = array('all' => 'Semua Ujian...');
        $class_list = array();
        $array_type = array(
            "uts" => 4,
            "uas" => 5,
            "us" => 6
        );

        $term = 't.quiz_id IN (select id from quiz where trash is null and quiz_type="' . $array_type[$type] . '") AND t.semester = ' . $optSemester . ' AND t.year = ' . $optTahunAjaran;
        //definisi dropdown
        $datas = StudentQuiz::model()->findAll(array(
            'select' => 'quiz_id, school_name, class',
            'order' => 't.school_name, t.class ASC',
            'condition' => $term
                )
        );
        foreach ($datas as $data) {
            if (array_key_exists($data->school_name, $school_list) == False) {
                $school_list[$data->school_name] = $data->school_name;
            }
            if (!isset($class_list[$data->school_name]))
                $class_list[$data->school_name] = array('all' => 'Semua Kelas...');
            if (in_array($data->class, $class_list[$data->school_name]) == False)
                $class_list[$data->school_name][$data->class] = $data->class;

            if (array_key_exists($data->quiz_id, $quiz_list) == False)
                $quiz_list[$data->quiz_id] = $data->quiz->title;
        }

        //definisi data table
        if ($school != "all") {
            $term .= ' AND t.school_name = "' . $school . '"';
        }
        if ($class != "all") {
            $term .= ' AND t.class = "' . $class . '"';
        }
        if ($quiz != "all") {
            $term .= ' AND t.quiz_id = "' . $quiz . '"';
        }
        $dataProvider = new CActiveDataProvider('StudentQuiz', array(
            'criteria' => array(
                'order' => 't.school_name, t.class, t.display_name ASC',
                'join' => 'JOIN quiz as q ON q.id = t.quiz_id',
                'condition' => $term
            ),
            'pagination' => array('pageSize' => 20)
                )
        );

        
        

        if ($school != "all" && $quiz != "all") {

        $dataProviderCount = new CActiveDataProvider('StudentQuiz', array(
            'criteria' => array(
                // 'select' => 'count(t.id) as jumlah',
                'order' => 't.school_name, t.class, t.display_name ASC',
                'join' => 'JOIN quiz as q ON q.id = t.quiz_id',
                'condition' => $term
            ),
            'pagination' => array('pageSize' => 20000000)
            )
        );

        } else {
            $dataProviderCount = null;
        }


        // display the login form
        $this->render('v2/result', array(
            'dataProviderCount' => $dataProviderCount,
            'dataProvider' => $dataProvider,
            'type' => $type,
            'school_list' => $school_list,
            'class_list' => $class_list,
            'quiz_list' => $quiz_list,
            'school' => $school,
            'class' => $class,
            'quiz' => $quiz
        ));
    }

    public function actionUploadNilai() {
        $berhasil = '';
        if (isset($_POST['Activities'])) {
            $inputjson = CUploadedFile::getInstancesByName('jsonfile');
            $stundentquizes = json_decode(file_get_contents($inputjson[0]->tempName), true);

            foreach ($stundentquizes as $student_quiz) {
                $model = new StudentQuiz;
                foreach ($student_quiz as $key => $value) {
                    if ($key == 'id')
                        $value = NULL;
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
                        $id[] = $model->id;
                    }
                }
            }

            if (!empty($id)) {
//                Yii::app()->user->setFlash('success', "Import Nilai id " . implode(", ", $id) . " Berhasil!");
                $berhasil = "Import Nilai id " . implode(", ", $id) . " Berhasil!";
            } else {
                $berhasil = "Nilai yang anda upload sudah ada di server kami!";
            }
        }

        $this->render('v2/uploadnilai', array('berhasil' => $berhasil));
    }

    /**
     * Displays the login page
     */
    public function actionHasilkd($type = "uts", $school = 'all', $class = 'all', $quiz = 'all') {
        $optSemester = Option::model()->findByAttributes(array('key_config' => 'semester'))->value;
        $optTahunAjaran = Option::model()->findByAttributes(array('key_config' => 'tahun_ajaran'))->value;
        $school_list = array('all' => 'Semua Sekolah...');
        $quiz_list = array('all' => 'Semua Ujian...');
        $class_list = array();
        $ids = array();
        $array_type = array(
            "uts" => 4,
            "uas" => 5,
            "us" => 6
        );

        $term = 't.quiz_id IN (select id from quiz where trash is null and quiz_type="' . $array_type[$type] . '") AND t.semester = ' . $optSemester . ' AND t.year = ' . $optTahunAjaran;
        //definisi dropdown
        $datas = StudentQuiz::model()->findAll(array(
            'select' => 'id, quiz_id, school_name, class',
            'order' => 't.school_name, t.class ASC',
            'condition' => $term
                )
        );
        foreach ($datas as $data) {
            # list sekolah
            if (array_key_exists($data->school_name, $school_list) == False)
                $school_list[$data->school_name] = $data->school_name;
            # list kelas
            if (!isset($class_list[$data->school_name]))
                $class_list[$data->school_name] = array('all' => 'Semua Kelas...');
            if (in_array($data->class, $class_list[$data->school_name]) == False)
                $class_list[$data->school_name][$data->class] = $data->class;
            # list quiz
            if (array_key_exists($data->quiz_id, $quiz_list) == False)
                $quiz_list[$data->quiz_id] = $data->quiz->title;

            $ids[] = $data->id;
        }

        if ($school != "all") {
            $term .= ' AND t.school_name = "' . $school . '"';
        }
        if ($class != "all") {
            $term .= ' AND t.class = "' . $class . '"';
        }
        if ($quiz != "all") {
            $term .= ' AND t.quiz_id = "' . $quiz . '"';
        }


        $queryAll = array(
            'order' => 't.school_name, t.class, t.display_name ASC',
            'condition' => $term
        );
        $dataProvider = new CActiveDataProvider('StudentQuiz', array(
            'criteria' => $queryAll,
            'pagination' => array('pageSize' => 100)
                )
        );


        if ($school != "all" && $quiz != "all") {

        $dataProviderCount = new CActiveDataProvider('StudentQuiz', array(
           'criteria' => $queryAll,
            'pagination' => array('pageSize' => 2000000)
                )
        );

        } else {
            $dataProviderCount = null;
        }

        //definisi total kd
        $student_quiz = array();
        $kd = array();
        $score = array();
        if ($school != "all" || $quiz != "all") {
            $student_query = array(
                'order' => 't.school_name, t.class, t.display_name ASC',
                'condition' => $term
            );
            $student_quiz = StudentQuiz::model()->findAll($student_query);
            $ids = array();
            foreach ($student_quiz as $student) {
                $ids[] = $student->id;

                $score[] = $student->score;
                $kds = json_decode($student->kd);
                if (!empty($kds)) {
                    foreach ($kds as $key => $values) {
                        foreach ($values as $k => $v) {
                            if (array_key_exists($k, $kd) AND array_key_exists($key, $kd[$k])) {
                                $kd[$k][$key] = $kd[$k][$key] + $v;
                            } else {
                                $kd[$k][$key] = $v;
                            }
                        }
                    }
                }
            }
        }
        $kunci_jawaban =  array();
        if($quiz != 'all') {
            $quiz_temp = Quiz::model()->findByPk($quiz);
            $raw = explode(',', $quiz_temp->question);
            foreach ($raw as $sl) {
                $cekSoal = Questions::model()->findByPk($sl);

                if (!empty($cekSoal)) {
                    $cekSoal->key_answer = preg_replace("/\r|\n/", "", $cekSoal->key_answer);
                    $cekSoal->key_answer = strip_tags($cekSoal->key_answer, "<img>");
                    $cekSoal->key_answer = preg_replace('/\s+/', '', $cekSoal->key_answer);
                    $cekSoal->key_answer = str_replace("/>", "", $cekSoal->key_answer);
                    $cekSoal->key_answer = str_replace(">", "", $cekSoal->key_answer);
                    
                    $kunci_jawaban[$cekSoal->id] = $cekSoal->key_answer;
                }   
            }
        }

        // display the login form
        $this->render('v2/resultkd', array(
            'dataProviderCount' => $dataProviderCount,
            'dataProvider' => $dataProvider,
            'score' => $score,
            'kd' => $kd,
            'type' => $type,
            'school_list' => $school_list,
            'class_list' => $class_list,
            'quiz_list' => $quiz_list,
            'school' => $school,
            'class' => $class,
            'quiz' => $quiz,
            'ids' => $ids,
            'kunci_jawaban' => $kunci_jawaban
        ));
    }

    /**
     * Displays the login page
     */
    public function actionChartkd($type = "uts", $school = 'all', $class = 'all', $quiz = 'all') {
        $optSemester = Option::model()->findByAttributes(array('key_config' => 'semester'))->value;
        $optTahunAjaran = Option::model()->findByAttributes(array('key_config' => 'tahun_ajaran'))->value;
        $school_list = array('all' => 'Semua Sekolah...');
        $quiz_list = array('all' => 'Semua Ujian...');
        $class_list = array();
        $array_type = array(
            "uts" => 4,
            "uas" => 5,
            "us" => 6
        );

        $term = 't.quiz_id IN (select id from quiz where quiz_type="' . $array_type[$type] . '") AND t.semester = ' . $optSemester . ' AND t.year = ' . $optTahunAjaran;
        //definisi dropdown
        $datas = StudentQuiz::model()->findAll(array(
            'select' => 'quiz_id, school_name, class',
            'order' => 't.school_name, t.class ASC',
            'condition' => $term
                )
        );
        foreach ($datas as $data) {
            # list sekolah
            if (array_key_exists($data->school_name, $school_list) == False) {
                $school_list[$data->school_name] = $data->school_name;
            }
            # list kelas
            if (!isset($class_list[$data->school_name]))
                $class_list[$data->school_name] = array('all' => 'Semua Kelas...');

            if (in_array($data->class, $class_list[$data->school_name]) == False) {
                $class_list[$data->school_name][$data->class] = $data->class;
            }
            # list quiz
            if (array_key_exists($data->quiz_id, $quiz_list) == False) {
                $quiz_list[$data->quiz_id] = $data->quiz->title;
            }
        }

        if ($school != "all") {
            $term .= ' AND t.school_name = "' . $school . '"';
        }
        if ($class != "all") {
            $term .= ' AND t.class = "' . $class . '"';
        }
        if ($quiz != "all") {
            $term .= ' AND t.quiz_id = "' . $quiz . '"';
        }

        //definisi total kd
        $quiz_stat = StudentQuizStat::model()->findAll(array(
            'condition' => 'quiz_id=' . $quiz .' AND quiz_type=' . $array_type[$type] . ' AND school_name ="'. $school . '" AND class_name="' . $class . '" AND year=' . $optTahunAjaran . " AND semester=" . $optSemester 
        ));

        if (!empty($quiz_stat)) {
            $kd = json_decode($quiz_stat[0]->kd);
            $score = json_decode($quiz_stat[0]->score);
            $total_school = json_decode($quiz_stat[0]->total_school);
            $total_class = json_decode($quiz_stat[0]->total_class);
            $max = $quiz_stat[0]->max;
            $min = $quiz_stat[0]->min;
        } else {
            $student_query = array(
                'select' => 'quiz_id, kd, score, school_name, class',
                'order' => 't.school_name, t.class, t.display_name ASC',
                'condition' => $term
            );
            $student_quiz = StudentQuiz::model()->findAll($student_query);
            $kd = array();
            $score = array();
            $total_school = array();
            $total_class = array();
            $max = 0;
            $min = 0;
            foreach ($student_quiz as $student) {
                $score[] = $student->score;
                if ($min == 0) {
                    $min = $student->score;
                }
                if ($student->score > $max) {
                    $max = $student->score;
                }
                if ($student->score < $min) {
                    $min = $student->score;
                }
                $kds = json_decode($student->kd);
                $listkd = array();
                if ($quiz != "all") {
                    $quizes = Quiz::model()->findByPk($student->quiz_id);
                    $lessonkd = LessonKd::model()->findAll(array(
                        'condition' => 'lesson_id = "' . $quizes->lesson_id . '"'
                    ));
                    foreach ($lessonkd as $kade) {
                        $listkd[$kade->title] = $kade->description;
                    }
                }
                if (!empty($kds)) {
                    foreach ($kds as $key => $values) {
                        foreach ($values as $k => $v) {
                            if (array_key_exists($k, $kd) AND array_key_exists($key, $kd[$k])) {
                                $kd[$k][$key] = $kd[$k][$key] + $v;
                            } else {
                                $kd[$k][$key] = $v;
                            }
                            if ($quiz != "all" && isset($listkd[$k])) {
                                $kd[$k]['desc'] = $listkd[$k];
                            }
                        }
                    }
                }

                # list sekolah
                if (array_key_exists($student->school_name, $total_school) == False) {
                    $total_school[$student->school_name] = 1;
                } else {
                    $total_school[$student->school_name] = $total_school[$student->school_name] + 1;
                }

                if (array_key_exists($student->class, $total_class)) {
                    $total_class[$student->class] = $total_class[$student->class] + 1;
                } else {
                    $total_class[$student->class] = 1;
                }
            }
        }
        // display the login form
        $this->render('v2/chartkd', array(
            'kd' => $kd,
            'score' => $score,
            'min' => $min,
            'max' => $max,
            'type' => $type,
            'school_list' => $school_list,
            'class_list' => $class_list,
            'quiz_list' => $quiz_list,
            'total_school' => $total_school,
            'school' => $school,
            'class' => $class,
            'total_class' => $total_class,
            'quiz' => $quiz
        ));
    }

    /**
     * Displays the login page
     */
    public function actionMapkd($type = "uts", $school = 'all', $class = 'all', $quiz = 'all') {
        $optSemester = Option::model()->findByAttributes(array('key_config' => 'semester'))->value;
        $optTahunAjaran = Option::model()->findByAttributes(array('key_config' => 'tahun_ajaran'))->value;
        $school_list = array('all' => 'Semua Sekolah...');
        $quiz_list = array('all' => 'Semua Ujian...');
        $class_list = array();
        $array_type = array(
            "uts" => 4,
            "uas" => 5,
            "us" => 6
        );

        $term = 't.quiz_id IN (select id from quiz where quiz_type="' . $array_type[$type] . '") AND t.semester = ' . $optSemester . ' AND t.year = ' . $optTahunAjaran;
        //definisi dropdown
        $datas = StudentQuiz::model()->findAll(array(
            'select' => 'quiz_id, school_name, class',
            'order' => 't.school_name, t.class ASC',
            'condition' => $term
                )
        );
        foreach ($datas as $data) {
            # list sekolah
            if (array_key_exists($data->school_name, $school_list) == False)
                $school_list[$data->school_name] = $data->school_name;
            # list kelas
            if (!isset($class_list[$data->school_name]))
                $class_list[$data->school_name] = array('all' => 'Semua Kelas...');
            if (in_array($data->class, $class_list[$data->school_name]) == False)
                $class_list[$data->school_name][$data->class] = $data->class;
            # list quiz
            if (array_key_exists($data->quiz_id, $quiz_list) == False) {
                $quiz_list[$data->quiz_id] = $data->quiz->title;
            }
        }

        if ($school != "all") {
            $term .= ' AND t.school_name = "' . $school . '"';
        }
        if ($class != "all") {
            $term .= ' AND t.class = "' . $class . '"';
        }
        if ($quiz != "all") {
            $term .= ' AND t.quiz_id = "' . $quiz . '"';
        }

        //definisi total kd
        $student_query = array(
            'select' => 'school_name, AVG(score) As score',
            'order' => 't.school_name, t.class, t.display_name ASC',
            'group' => 'school_name',
            'condition' => $term
        );
        $student_quiz = StudentQuiz::model()->findAll($student_query);
        $kd = array();
        $location = array();
        foreach ($student_quiz as $student) {
            $school_loc = SchoolLocation::model()->findByAttributes(array('school_name' => $student->school_name));
            if ($school_loc) {
                $location[$student->school_name] = array(
                    'score' => $student->score,
                    'lat' => $school_loc->lat,
                    'lng' => $school_loc->lng,
                );
            }
        }
        // display the login form
        $this->render('v2/mapkd', array(
            'location' => $location,
            'type' => $type,
            'school_list' => $school_list,
            'class_list' => $class_list,
            'quiz_list' => $quiz_list,
            'school' => $school,
            'class' => $class,
            'quiz' => $quiz
        ));
    }

    /**
     * Displays the login page
     */
    public function actionDownloadkd($type = "uts", $school = 'all', $class = 'all', $quiz = 'all', $print=1) {
        $optSemester = Option::model()->findByAttributes(array('key_config' => 'semester'))->value;
        $optTahunAjaran = Option::model()->findByAttributes(array('key_config' => 'tahun_ajaran'))->value;
        $school_list = array('all' => 'Semua Sekolah...');
        $quiz_list = array('all' => 'Semua Ujian...');
        $class_list = array();
        $array_type = array(
            "uts" => 4,
            "uas" => 5,
            "us" => 6
        );

        $term = 't.quiz_id IN (select id from quiz where quiz_type="' . $array_type[$type] . '") AND t.semester = ' . $optSemester . ' AND t.year = ' . $optTahunAjaran;

        if ($school != "all") {
            $term .= ' AND t.school_name = "' . $school . '"';
        }
        if ($class != "all") {
            $term .= ' AND t.class = "' . $class . '"';
        }
        if ($quiz != "all") {
            $term .= ' AND t.quiz_id = "' . $quiz . '"';
        }

        //definisi total kd
        $student_quiz = array();
        $kd = array();
        $score = array();
         $score = array();
        if ($school != "all" || $quiz != "all") {
            $student_query = array(
                'select' => 'id, kd, score',
                'order' => 't.school_name, t.class, t.display_name ASC',
                'condition' => $term
            );
            $student_quiz = StudentQuiz::model()->findAll($student_query);
            $ids = array();
            foreach ($student_quiz as $student) {
                $ids[] = $student->id;

                $score[] = $student->score;
                $kds = json_decode($student->kd);
                if (!empty($kds)) {
                    foreach ($kds as $key => $values) {
                        foreach ($values as $k => $v) {
                            if (array_key_exists($k, $kd) AND array_key_exists($key, $kd[$k])) {
                                $kd[$k][$key] = $kd[$k][$key] + $v;
                            } else {
                                $kd[$k][$key] = $v;
                            }
                        }
                    }
                }
            }
        }
        $queryAll = array(
            'order' => 't.school_name, t.class, t.display_name ASC',
            'condition' => $term
        );
        $dataProvider = new CActiveDataProvider('StudentQuiz', array(
            'criteria' => $queryAll,
            'pagination' => array('pageSize' => 99999999999)
                )
        );

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

        // display the login form
        $this->render('v2/downloadkd', array(
            'dataProvider' => $dataProvider,
            'score' => $score,
            'kd' => $kd,
            'type' => $type,
            'school_list' => $school_list,
            'class_list' => $class_list,
            'quiz_list' => $quiz_list,
            'school' => $school,
            'class' => $class,
            'quiz' => $quiz,
            'print' => $print
        ));
    }

    public function actionCekNilaiUjian() {
        if ($_POST['id']) {
            $id = $_POST['id'];
            $student_quiz = StudentQuiz::model()->findByPk($id);

            $jawaban = json_decode($student_quiz->student_answer);
            $qid = $student_quiz->quiz_id;
            $quiz = Quiz::model()->findByPk($qid);

            $total_pertanyaan = $quiz->total_question;
            $benar = 0;
            $salah = 0;
            $kosong = 0;
            $jwb_salah = NULL;
            $total_jawab = NULL;
            $jawaban_fix = array();
            $kunci_jawaban = array();
            $raw = explode(',', $quiz->question);
            $soal_pg = 0;
            $soal_essay = 0;
            foreach ($raw as $sl) {
                $cekSoal = Questions::model()->findByPk($sl);

                if (!empty($cekSoal)) {
                    $cekSoal->key_answer = preg_replace("/\r|\n/", "", $cekSoal->key_answer);
                    $cekSoal->key_answer = strip_tags($cekSoal->key_answer, "<img>");
                    $cekSoal->key_answer = preg_replace('/\s+/', '', $cekSoal->key_answer);
                    $cekSoal->key_answer = str_replace("/>", "", $cekSoal->key_answer);
                    $cekSoal->key_answer = str_replace(">", "", $cekSoal->key_answer);
                    
                    $kunci_jawaban[$cekSoal->id] = $cekSoal->key_answer;
                    $kd_saol[$cekSoal->id] = $cekSoal->kd;
                    if ($cekSoal->type != 2) {
                        $soal_pg++;
                    } else {
                        $soal_essay++;
                    }
                }
            }
            
            if (!empty($jawaban)) {
                $kd = array('benar' => array(), 'salah' => array());
                foreach ($jawaban as $key => $value) {
                    if (strpos($value, '<img') !== false) {
                            $doc = new DOMDocument();
                            $doc->loadHTML($value);
                            $tags = $doc->getElementsByTagName('img');
                            if (count($tags) > 0) {
                                $tag = $tags->item(0);
                                $old_src = $tag->getAttribute('src');
                                $old_src = str_replace(" ", "+", $old_src);
                                $new_src_url = $old_src;
                                $tag->setAttribute('src', $new_src_url);
                                $value = $doc->saveHTML($tag);
                            }
                            
                     }

                    if (!empty($kunci_jawaban)) {
                        $value = preg_replace("/\r|\n/", "", $value);
                        $value = strip_tags($value, "<img>");
                        $value = preg_replace('/\s+/', '', $value);
                        $value = str_replace("/>", "", $value);
                        $value = str_replace(">", "", $value);
                        
                        $jawaban_fix[] = $value;
                        
                        foreach ($kunci_jawaban as $k => $v){
                            if (strtolower($v) == strtolower($value)) {
                               $benar = $benar + 1;
//                               if(isset($kd_saol[$k])){
//                                   if (array_key_exists($kd_saol[$k], $kd['benar'])) {
//                                        $kd['benar'][$kd_saol[$k]] = $kd['benar'][$kd_saol[$k]] + 1;
//                                    } else {
//                                        $kd['benar'][$kd_saol[$k]] = 1;
//                                    }
//                               }
                               
                               $jwb_salah = false;
                               break;
                            } else {
                               $jwb_salah = true;
                            }
                        }
                        if ($jwb_salah ==  true){
                            $salah = $salah + 1;
//                            if ($cekJawaban->kd) {
//                                if (array_key_exists($cekJawaban->kd, $kd['salah'])) {
//                                    $kd['salah'][$cekJawaban->kd] = $kd['salah'][$cekJawaban->kd] + 1;
//                                } else {
//                                    $kd['salah'][$cekJawaban->kd] = 1;
//                                }
//                            }
                        }
                    }
                }
                $total_jawab = count((array)$jawaban);
                $kosong = $quiz->total_question - $total_jawab;
                
                if ($soal_pg > 0) {
                    $score = round(($benar / $soal_pg) * 100, 2);
                    $student_quiz->score = $score;
                    $student_quiz->right_answer = $benar;
                    $student_quiz->wrong_answer = $salah;
                    $student_quiz->unanswered = $kosong;
//                    $student_quiz->kd = json_encode($kd);
                }

                $student_quiz->student_answer = json_encode($jawaban);
                $student_quiz->attempt = $student_quiz->attempt + 1;
                $student_quiz->indikasi = NULL;
                
                if ($student_quiz->save()) {
                    echo "1";
                } else {
                    echo "0";
                }
            }
        }
    }

}

?>
