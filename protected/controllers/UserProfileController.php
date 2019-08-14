<?php

class UserProfileController extends Controller {

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
                'actions' => array(),
                'users' => array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('view', 'update'),
                'users' => array('@'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('BulkOrtu','update', 'view', 'importjarak', 'showtable'),
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
        $model_user = User::model()->findByPk($id);

        if ($model_user->trash != NULL) {
            Yii::app()->user->setFlash('error', "Maaf user sudah dihapus dari sistem!");
            $this->redirect(array('site/index'));
            exit();
        }

        if (Yii::app()->user->YiiStudent and Yii::app()->user->id != $model_user->id) {
            Yii::app()->user->setFlash('error', "Maaf anda tidak punya izin akses!");
            $this->redirect(array('site/index'));
            exit();
        }

        $profil_user = UserProfile::model()->findByAttributes(array('user_id' => $id));
        if (!empty($profil_user)) {
            $model = $this->loadModel($profil_user->id);

            $this->render('view', array(
                'model' => $this->loadModel($profil_user->id),
                'user_id' => $id,
                'nama' => $model_user->display_name,
            ));
        } else {
            Yii::app()->user->setFlash('error', "Maaf profil masih kosong, silakan update terlebih dahulu!");
            $this->redirect(array('update', 'id' => $id));
            exit();
        }
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    // public function actionCreate($id)
    // {
    // 	$model_user=User::model()->findByPk($id);
    // 	if ($model_user->trash != NULL){
    // 		Yii::app()->user->setFlash('error', "Maaf user sudah dihapus dari sistem!");
    // 		$this->redirect(array('site/index'));
    // 		exit();
    // 	}
    // 	if (Yii::app()->user->YiiStudent and Yii::app()->user->id != $model_user->id){
    // 		Yii::app()->user->setFlash('error', "Maaf anda tidak punya izin akses!");
    // 		$this->redirect(array('site/index'));
    // 		exit();
    // 	}
    // 	$model=new UserProfile;
    // 	// Uncomment the following line if AJAX validation is needed
    // 	// $this->performAjaxValidation($model);
    // 	if(isset($_POST['UserProfile']))
    // 	{
    // 		$model->attributes=$_POST['UserProfile'];
    // 		if($model->save())
    // 			$this->redirect(array('view','id'=>$model->id));
    // 	}
    // 	$this->render('create',array(
    // 		'model'=>$model,
    // 		'user_id'=>$id,
    // 	));
    // }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $model_user = User::model()->findByPk($id);

        if ($model_user->trash != NULL) {
            Yii::app()->user->setFlash('error', "Maaf user sudah dihapus dari sistem!");
            $this->redirect(array('site/index'));
            exit();
        }

        if (Yii::app()->user->YiiStudent and Yii::app()->user->id != $model_user->id) {
            Yii::app()->user->setFlash('error', "Maaf anda tidak punya izin akses!");
            $this->redirect(array('site/index'));
            exit();
        }

        $profil_user = UserProfile::model()->findByAttributes(array('user_id' => $id));
        if (!empty($profil_user)) {
            $model = $this->loadModel($profil_user->id);
            $model->sync_status = 2;
        } else {
            $model = new UserProfile;
        }

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['UserProfile'])) {
            // echo "<pre>";
            // 	print_r($_POST['UserProfile']);
            // echo "</pre>";


            $model->attributes = $_POST['UserProfile'];
            $model->user_id = $id;
            $model->tgl_lahir = $_POST['UserProfile']['tgl_lahir'];
            $no_telp_ortu = $_POST['UserProfile']['no_telp_ortu'];
            $no_telp_wali = $_POST['UserProfile']['no_telp_wali'];
            
            if ($model->save()) {
                $user_model = new User;
                if (isset($no_telp_ortu)) {
                    $user_model->role_id = 3; //role orang tua
                    $user_model->child_id = $model->user_id; //id user anak
                    $user_model->display_name = isset($model->ayah_nama) ? $model->ayah_nama : $model->ibu_nama;
                    $user_model->username = $no_telp_ortu;
                    $user_model->encrypted_password = "edubox"; //default password
                    $user_model->password2 = "edubox"; //default password
                    $user_model->email = $no_telp_ortu . "@pinisi.com"; //default email
                } 
                else if (isset($no_telp_wali)){
                    $user_model->role_id = 3; //role orang tua
                    $user_model->child_id = $model->user_id; //id user anak
                    $user_model->display_name = isset($model->wali_nama) ? $model->wali_nama : $model->ayah_nama;
                    $user_model->username = $no_telp_wali;
                    $user_model->encrypted_password = "edubox"; //default password
                    $user_model->password2 = "edubox"; //default password
                    $user_model->email = $no_telp_wali . "@pinisi.com"; //default email
                }
                $user_model->save();
                $this->redirect(array('view', 'id' => $model->user_id));
                
            }
        }

        $this->render('update', array(
            'model' => $model,
            'user_id' => $id,
            'nama' => $model_user->display_name,
        ));
    }

    public function actionBulkOrtu() {

        $model = new Activities;

        $sukses = 0;
        $fail = 0;

        $xlsFile = CUploadedFile::getInstancesByName('csvfile');
        $prefix = Yii::app()->params['tablePrefix'];


        if (isset($_POST['Activities'])) {
            // echo "asdf";

            foreach ($xlsFile as $file) {
                    // try {
                        // $transaction = Yii::app()->db->beginTransaction();
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

                        foreach ($text as $key => $val) {
                            
                            $column = array_combine($head[0], $val);
                            $row2 = $row;

                            $detail_user = User::model()->findByAttributes(array('username' => $column['nis']));
                            
                            $profil_user = UserProfile::model()->findByAttributes(array('user_id' => $detail_user->id));
                            
                            // if (!empty($profil_user)) {
                            //     $model = $this->loadModel($profil_user->id);
                            //     $model->sync_status = 2;
                            // } else {
                            //     $model = new UserProfile;
                            // }




                            $column['nama_ortu'] = "ortu ".$detail_user->display_name;

                            
                            $user_id = $detail_user->id;
                            $no_telp_ortu = $column['hp_ortu'];
                            $ayah_nama = $column['nama_ortu'];


                             $insert2 = "INSERT INTO user_profile (user_id,ayah_nama,no_telp_ortu) values(:user_id,:ayah_nama,:no_telp_ortu)";

                                $insertCommand2 = Yii::app()->db->createCommand($insert2);

                                $insertCommand2->bindParam(":user_id", $user_id, PDO::PARAM_STR);
                                $insertCommand2->bindParam(":ayah_nama", $ayah_nama, PDO::PARAM_STR);
                                $insertCommand2->bindParam(":no_telp_ortu", $no_telp_ortu, PDO::PARAM_STR);


                            if ($insertCommand2->execute()) {
                                // $user_model = new User;

                                $role_id = 3; //role orang tua
                                $child_id = $detail_user->id; //id user anak
                                $display_name = $column['nama_ortu'];
                                $username = $column['hp_ortu'];
                                
                                $ph = new PasswordHash(Yii::app()->params['phpass']['iteration_count_log2'], Yii::app()->params['phpass']['portable_hashes']);
                                $passwd = $ph->HashPassword("edubox");

                                $encrypted_password = $passwd; //default password
                                $reset_password = "edubox";
                                
                                $email = $column['hp_ortu'] . "@pinisi.com"; //default email
                                



                                echo $detail_user->display_name." berhasil profile </br>";


                                $insert = "INSERT INTO " . $prefix . "users (email,username,encrypted_password,role_id,created_at,updated_at,reset_password,display_name,child_id) values(:email,:username,:encrypted_password,:role_id,NOW(),NOW(),:reset_password,:display_name,:child_id)";

                                $insertCommand = Yii::app()->db->createCommand($insert);

                                $insertCommand->bindParam(":email", $email, PDO::PARAM_STR);
                                $insertCommand->bindParam(":username", $username, PDO::PARAM_STR);
                                $insertCommand->bindParam(":encrypted_password", $encrypted_password, PDO::PARAM_STR);
                                $insertCommand->bindParam(":role_id", $role_id, PDO::PARAM_STR);
                                $insertCommand->bindParam(":reset_password", $reset_password, PDO::PARAM_STR);
                                $insertCommand->bindParam(":display_name", $display_name, PDO::PARAM_STR);
                                $insertCommand->bindParam(":child_id", $child_id, PDO::PARAM_STR);


                                if ($insertCommand->execute()) {
                                    echo $detail_user->display_name." berhasil </br>";
                                } else {
                                   echo $detail_user->display_name." gagal </br>";
                                }

                                
                            } else {
                                echo $detail_user->display_name." gagal profile </br>";
                            }


                            // echo "<pre>";
                            // print_r($column);
                            // echo "</pre>";
                        }
                    // }catch (Exception $error) {
                    //     //echo "<pre>";
                    //     //print_r($error);
                    //     //echo "</pre>";
                    //     $transaction->rollback();
                    //     throw new CHttpException($error);
                    // }

            }
        } else {
            $this->render('bulkortu', array(
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
    // public function actionIndex()
    // {
    // 	$dataProvider=new CActiveDataProvider('UserProfile');
    // 	$this->render('index',array(
    // 		'dataProvider'=>$dataProvider,
    // 	));
    // }

    /**
     * Manages all models.
     */
    // public function actionAdmin()
    // {
    // 	$model=new UserProfile('search');
    // 	$model->unsetAttributes();  // clear any default values
    // 	if(isset($_GET['UserProfile']))
    // 		$model->attributes=$_GET['UserProfile'];
    // 	$this->render('admin',array(
    // 		'model'=>$model,
    // 	));
    // }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return UserProfile the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = UserProfile::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    public function actionImportjarak() {

        $model = new Activities;
        $sukses = 0;
        $fail = 0;


        if (isset($_POST['Activities'])) {
            $model->attributes = $_POST['Activities'];
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
                        $highestRow = $data->getHighestRow();
                        $highestColumn = $data->getHighestColumn();
                        $range = 'A2:B' . $highestRow . '';
                        $text = $data->rangeToArray($range);
                        $head = $data->rangeToArray('A1:B1');
                        $suskes = 0;
                        $fail = 0;

                        foreach ($text as $key => $val) {
                            $column = array_combine($head[0], $val);
                            $row2 = $row;

                            if (!empty($column['NIP/NIS'])) {
                                $length = 5;
                                $length2 = 9;

                                $nik = $column['NIP/NIS'];
                                $siswa = User::model()->findAll(array('condition' => 'username = \'' . $nik . '\''));

                                $sql2 = "UPDATE " . $prefix . "user_profile SET sync_status = 2, jarak_tempat_tgl_ke_sekolah =" . htmlspecialchars(trim($column['jarak_tempat_tgl_ke_sekolah'])) . " WHERE user_id = " . $siswa[0]->id;
                                $command2 = Yii::app()->db->createCommand($sql2);

                                if ($command2->execute()) {
                                    $sukses++;
                                } else {
                                    $fail++;
                                }
                            }
                            $row++;
                            $urutan++;
                        }
                        Yii::app()->user->setFlash('success', "Berhasil Import " . $sukses . " Nilai Siswa, Gagal import  " . $fail . " nilai !");
                        $transaction->commit();
                    } catch (Exception $error) {
                        $transaction->rollback();
                        throw new CHttpException($error);
                    }
                }
            } else {
                Yii::app()->user->setFlash('error', "Import Gagal!");
            }
        }

        $this->render('importjarak', array(
            'model' => $model,
        ));
    }

    public function actionShowTable($id) {
        $sql = "SELECT u.`id`,u.`username`,u.`display_name`,p.*
				FROM `users` as u
				JOIN `user_profile` as p ON u.`id` = p.`user_id`
				WHERE p.`j_kelamin` != ''
				AND u.`class_id` = " . $id . " ";

        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();

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

        echo "<html>
				<head>
					<script type='text/javascript' src='" . Yii::app()->theme->baseUrl . "/js/jquery-1.11.2.min.js'></script>
				</head>
				<body>
					<br/>
					<div id='dvData'>
						<table border='1'>
							<tr>
								<th>No</th>
								<th>NIS</th>
								<th>Nama Lengkap</th>
								<th>Jenis Kelamin</th>
								<th>NISN</th>
								<th>Nomor Seri Ijazah</th>
								<th>Nomor Seri SKHUN</th>
								<th>Nomor Ujian Nasional</th>
								<th>NIK</th>
								<th>Tempat Lahir</th>
								<th>Tanggal Lahir</th>
								<th>Agama</th>
								<th>Berkebutuhan Khusus</th>
								<th>Alamat Tempat Tinggal</th>
								<th>Dusun</th>
								<th>RT</th>
								<th>RW</th>
								<th>Kelurahan/Desa</th>
								<th>Kode POS</th>
								<th>Kecamatan</th>
								<th>Kabupaten/Kota</th>
								<th>Provinsi</th>
								<th>Alat Transportasi Ke Sekolah</th>
								<th>Jenis Tinggal</th>
								<th>No Telpon/HP</th>
								<th>Email Pribadi</th>
								<th>Apakah Sebagai Penerima KPS</th>
								<th>No. KPS</th>
								<th>Nama Ayah</th>
								<th>Tahun Lahir Ayah</th>
								<th>Berkebutuhan Khusus Ayah</th>
								<th>Perkerjaan Ayah</th>
								<th>Pendidikan Ayah</th>
								<th>Penghasilan Bulanan Ayah (Rp)</th>
								<th>Nama Ibu</th>
								<th>Tahun Lahir Ibu</th>
								<th>Berkebutuhan Khusus Ibu</th>
								<th>Perkerjaan Ibu</th>
								<th>Pendidikan Ibu</th>
								<th>Penghasilan Bulanan Ibu (Rp)</th>
								<th>Nama Wali</th>
								<th>Tahun Lahir Wali</th>
								<th>Berkebutuhan Khusus Wali</th>
								<th>Perkerjaan Wali</th>
								<th>Pendidikan Wali</th>
								<th>Penghasilan Bulanan Wali (Rp)</th>
								<th>Tinggi Badan (Cm)</th>
								<th>Berat Badan (Kg)</th>
								<th>Jarak Tempat Tinggal Ke Sekolah (Meter)</th>
								<th>Jika Lebih Dari 1 Km (Km)</th>
								<th>Waktu Tempuh Berangkat Ke Sekolah (Menit)</th>
								<th>Jika Lebih dari 60 Menit (Jam)</th>
								<th>Jumlah Sudara Kandung</th>
								<th>Jenis Prestasi Ke-1</th>
								<th>Tingkat Prestasi Ke-1</th>
								<th>Nama Prestasi Ke-1</th>
								<th>Tahun Prestasi Ke-1</th>
								<th>Penyelenggara Prestasi Ke-1</th>
								<th>Jenis Prestasi Ke-2</th>
								<th>Tingkat Prestasi Ke-2</th>
								<th>Nama Prestasi Ke-2</th>
								<th>Tahun Prestasi Ke-2</th>
								<th>Penyelenggara Prestasi Ke-2</th>
								<th>Jenis Prestasi Ke-3</th>
								<th>Tingkat Prestasi Ke-3</th>
								<th>Nama Prestasi Ke-3</th>
								<th>Tahun Prestasi Ke-3</th>
								<th>Penyelenggara Prestasi Ke-3</th>
								<th>Jenis Prestasi Ke-4</th>
								<th>Tingkat Prestasi Ke-4</th>
								<th>Nama Prestasi Ke-4</th>
								<th>Tahun Prestasi Ke-4</th>
								<th>Penyelenggara Prestasi Ke-4</th>
								<th>Jenis Beasiswa Ke-1</th>
								<th>Sumber Beasiswa Ke-1</th>
								<th>Tahun Mulai Beasiswa Ke-1</th>
								<th>Tahun Selesai Beasiswa Ke-1</th>
								<th>Jenis Beasiswa Ke-2</th>
								<th>Sumber Beasiswa Ke-2</th>
								<th>Tahun Mulai Beasiswa Ke-2</th>
								<th>Tahun Selesai Beasiswa Ke-2</th>
								<th>Jenis Beasiswa Ke-3</th>
								<th>Sumber Beasiswa Ke-3</th>
								<th>Tahun Mulai Beasiswa Ke-3</th>
								<th>Tahun Selesai Beasiswa Ke-3</th>
								<th>Jenis Beasiswa Ke-4</th>
								<th>Sumber Beasiswa Ke-4</th>
								<th>Tahun Mulai Beasiswa Ke-4</th>
								<th>Tahun Selesai Beasiswa Ke-4</th>
								<th>Peminatan</th>
								<th>Lintas Minat Ke-01</th>
								<th>Lintas Minat Ke-02</th>
								<th>Ekstrakurikuler Ke-01</th>
								<th>Ekstarkurikuler Ke-02</th>
								<th>Status keluarga</th>
								<th>Anak Ke</th>
								<th>Sekolah Asal</th>
								<th>Kelas Diterima</th>
								<th>Tanggal Diterima</th>
								<th>Alamat Ortu</th>
								<th>No Telp Ortu</th>
								<th>Alamat Wali</th>
								<th>No Telp Wali</th>
								<th>Pekerjaan wali</th>
							</tr>";

        $no = 1;
        foreach ($rows as $key => $item) {
            echo "<tr>";

            echo "<td>" . $no . "</td>";
            $no++;

            if (!empty($item['username']) && $item['username'] != "-") {
                echo "<td>" . strtoupper($item['username']) . "</td>";
            } else {
                echo "<td>-</td>";
            }

            if (!empty($item['display_name']) && $item['display_name'] != "-") {
                echo "<td>" . strtoupper($item['display_name']) . "</td>";
            } else {
                echo "<td>-</td>";
            }

            if (!empty($item['j_kelamin']) && $item['j_kelamin'] != "-") {
                echo "<td>" . strtoupper($item['j_kelamin']) . "</td>";
            } else {
                echo "<td>-</td>";
            }

            if (!empty($item['nisn']) && $item['nisn'] != "-") {
                echo "<td>" . strtoupper($item['nisn']) . "</td>";
            } else {
                echo "<td>-</td>";
            }

            if (!empty($item['no_seri_ijazah']) && $item['no_seri_ijazah'] != "-") {
                if ($item['no_seri_ijazah'] == "001") {
                    echo "<td>BELUM ADA</td>";
                } else {
                    echo "<td>" . strtoupper($item['no_seri_ijazah']) . "</td>";
                }
            } else {
                echo "<td>-</td>";
            }

            if (!empty($item['no_seri_skhun']) && $item['no_seri_skhun'] != "-") {
                if ($item['no_seri_skhun'] == "001") {
                    echo "<td>BELUM ADA</td>";
                } else {
                    echo "<td>" . strtoupper($item['no_seri_skhun']) . "</td>";
                }
            } else {
                echo "<td>-</td>";
            }

            if (!empty($item['no_un']) && $item['no_un'] != "-") {
                if ($item['no_un'] == "001") {
                    echo "<td>BELUM ADA</td>";
                } else {
                    echo "<td>" . strtoupper($item['no_un']) . "</td>";
                }
            } else {
                echo "<td>-</td>";
            }

            if (!empty($item['nik']) && $item['nik'] != "-") {
                if ($item['nik'] == "001") {
                    echo "<td>BELUM ADA</td>";
                } else {
                    echo "<td>" . strtoupper($item['nik']) . "</td>";
                }
            } else {
                echo "<td>-</td>";
            }

            if (!empty($item['tempat_lahir']) && $item['tempat_lahir'] != "-") {
                echo "<td>" . strtoupper($item['tempat_lahir']) . "</td>";
            } else {
                echo "<td>-</td>";
            }

            if (!empty($item['tgl_lahir']) && $item['tgl_lahir'] != "-") {
                echo "<td>" . strtoupper($item['tgl_lahir']) . "</td>";
            } else {
                echo "<td>-</td>";
            }

            if (!empty($item['agama']) && $item['agama'] != "-") {
                echo "<td>" . strtoupper($item['agama']) . "</td>";
            } else {
                echo "<td>-</td>";
            }

            if (!empty($item['berkebutuhan_khusus']) && $item['berkebutuhan_khusus'] != "-") {
                echo "<td>" . strtoupper($item['berkebutuhan_khusus']) . "</td>";
            } else {
                echo "<td>-</td>";
            }

            if (!empty($item['alamat_tinggal']) && $item['alamat_tinggal'] != "-") {
                echo "<td>" . strtoupper($item['alamat_tinggal']) . "</td>";
            } else {
                echo "<td>-</td>";
            }

            if (!empty($item['alamat_dusun']) && $item['alamat_dusun'] != "-") {
                echo "<td>" . strtoupper($item['alamat_dusun']) . "</td>";
            } else {
                echo "<td>-</td>";
            }

            if (!empty($item['alamat_rt']) && $item['alamat_rt'] != "-") {
                echo "<td>" . strtoupper($item['alamat_rt']) . "</td>";
            } else {
                echo "<td>-</td>";
            }

            if (!empty($item['alamat_rw']) && $item['alamat_rw'] != "-") {
                echo "<td>" . strtoupper($item['alamat_rw']) . "</td>";
            } else {
                echo "<td>-</td>";
            }

            if (!empty($item['alamat_kelurahan']) && $item['alamat_kelurahan'] != "-") {
                echo "<td>" . strtoupper($item['alamat_kelurahan']) . "</td>";
            } else {
                echo "<td>-</td>";
            }

            if (!empty($item['alamat_kodepos']) && $item['alamat_kodepos'] != "-") {
                echo "<td>" . strtoupper($item['alamat_kodepos']) . "</td>";
            } else {
                echo "<td>-</td>";
            }

            if (!empty($item['alamat_kecamatan']) && $item['alamat_kecamatan'] != "-") {
                echo "<td>" . strtoupper($item['alamat_kecamatan']) . "</td>";
            } else {
                echo "<td>-</td>";
            }

            if (!empty($item['alamat_kota']) && $item['alamat_kota'] != "-") {
                echo "<td>" . strtoupper($item['alamat_kota']) . "</td>";
            } else {
                echo "<td>-</td>";
            }

            if (!empty($item['alamat_provinsi']) && $item['alamat_provinsi'] != "-") {
                echo "<td>" . strtoupper($item['alamat_provinsi']) . "</td>";
            } else {
                echo "<td>-</td>";
            }

            if (!empty($item['alat_transportasi']) && $item['alat_transportasi'] != "-") {
                echo "<td>" . strtoupper($item['alat_transportasi']) . "</td>";
            } else {
                echo "<td>-</td>";
            }

            if (!empty($item['jenis_tinggal']) && $item['jenis_tinggal'] != "-") {
                echo "<td>" . strtoupper($item['jenis_tinggal']) . "</td>";
            } else {
                echo "<td>-</td>";
            }

            if (!empty($item['no_telpon']) && $item['no_telpon'] != "-") {
                echo "<td>" . strtoupper($item['no_telpon']) . "</td>";
            } else {
                echo "<td>-</td>";
            }

            if (!empty($item['email']) && $item['email'] != "-") {
                echo "<td>" . strtoupper($item['email']) . "</td>";
            } else {
                echo "<td>-</td>";
            }

            if (!empty($item['penerima_kps']) && $item['penerima_kps'] != "-") {
                echo "<td>" . strtoupper($item['penerima_kps']) . "</td>";
            } else {
                echo "<td>-</td>";
            }

            if (!empty($item['no_kps']) && $item['no_kps'] != "-") {
                echo "<td>" . strtoupper($item['no_kps']) . "</td>";
            } else {
                echo "<td>-</td>";
            }

            if (!empty($item['ayah_nama']) && $item['ayah_nama'] != "-") {
                echo "<td>" . strtoupper($item['ayah_nama']) . "</td>";
            } else {
                echo "<td>-</td>";
            }

            if (!empty($item['ayah_thn_lahir']) && $item['ayah_thn_lahir'] != "-") {
                echo "<td>" . strtoupper($item['ayah_thn_lahir']) . "</td>";
            } else {
                echo "<td>-</td>";
            }

            if (!empty($item['ayah_berkebutuhan_khusus']) && $item['ayah_berkebutuhan_khusus'] != "-") {
                echo "<td>" . strtoupper($item['ayah_berkebutuhan_khusus']) . "</td>";
            } else {
                echo "<td>-</td>";
            }

            if (!empty($item['ayah_pekerjaan']) && $item['ayah_pekerjaan'] != "-") {
                echo "<td>" . strtoupper($item['ayah_pekerjaan']) . "</td>";
            } else {
                echo "<td>-</td>";
            }

            if (!empty($item['ayah_pendidikan']) && $item['ayah_pendidikan'] != "-") {
                echo "<td>" . strtoupper($item['ayah_pendidikan']) . "</td>";
            } else {
                echo "<td>-</td>";
            }

            if (!empty($item['ayah_penghasilan']) && $item['ayah_penghasilan'] != "-" && $item['ayah_penghasilan'] != "0") {
                echo "<td>" . strtoupper($item['ayah_penghasilan']) . "</td>";
            } else {
                echo "<td>-</td>";
            }

            if (!empty($item['ibu_nama']) && $item['ibu_nama'] != "-") {
                echo "<td>" . strtoupper($item['ibu_nama']) . "</td>";
            } else {
                echo "<td>-</td>";
            }

            if (!empty($item['ibu_thn_lahir']) && $item['ibu_thn_lahir'] != "-") {
                echo "<td>" . strtoupper($item['ibu_thn_lahir']) . "</td>";
            } else {
                echo "<td>-</td>";
            }

            if (!empty($item['ibu_berkebutuhan_khusus']) && $item['ibu_berkebutuhan_khusus'] != "-") {
                echo "<td>" . strtoupper($item['ibu_berkebutuhan_khusus']) . "</td>";
            } else {
                echo "<td>-</td>";
            }

            if (!empty($item['ibu_pekerjaan']) && $item['ibu_pekerjaan'] != "-") {
                echo "<td>" . strtoupper($item['ibu_pekerjaan']) . "</td>";
            } else {
                echo "<td>-</td>";
            }

            if (!empty($item['ibu_pendidikan']) && $item['ibu_pendidikan'] != "-") {
                echo "<td>" . strtoupper($item['ibu_pendidikan']) . "</td>";
            } else {
                echo "<td>-</td>";
            }

            if (!empty($item['ibu_penghasilan']) && $item['ibu_penghasilan'] != "-" && $item['ibu_penghasilan'] != "0") {
                echo "<td>" . strtoupper($item['ibu_penghasilan']) . "</td>";
            } else {
                echo "<td>-</td>";
            }

            if (!empty($item['wali_nama']) && $item['wali_nama'] != "-") {
                echo "<td>" . strtoupper($item['wali_nama']) . "</td>";
            } else {
                echo "<td>-</td>";
            }

            if (!empty($item['wali_thn_lahir']) && $item['wali_thn_lahir'] != "-") {
                echo "<td>" . strtoupper($item['wali_thn_lahir']) . "</td>";
            } else {
                echo "<td>-</td>";
            }

            if (!empty($item['wali_berkebutuhan_khusus']) && $item['wali_berkebutuhan_khusus'] != "-") {
                echo "<td>" . strtoupper($item['wali_berkebutuhan_khusus']) . "</td>";
            } else {
                echo "<td>-</td>";
            }

            if (!empty($item['wali_pekerjaan']) && $item['wali_pekerjaan'] != "-") {
                echo "<td>" . strtoupper($item['wali_pekerjaan']) . "</td>";
            } else {
                echo "<td>-</td>";
            }

            if (!empty($item['wali_pendidikan']) && $item['wali_pendidikan'] != "-") {
                echo "<td>" . strtoupper($item['wali_pendidikan']) . "</td>";
            } else {
                echo "<td>-</td>";
            }

            if (!empty($item['wali_penghasilan']) && $item['wali_penghasilan'] != "-" && $item['wali_penghasilan'] != "0") {
                echo "<td>" . strtoupper($item['wali_penghasilan']) . "</td>";
            } else {
                echo "<td>-</td>";
            }

            if (!empty($item['tinggi_badan']) && $item['tinggi_badan'] != "-") {
                echo "<td>" . strtoupper($item['tinggi_badan']) . "</td>";
            } else {
                echo "<td>-</td>";
            }

            if (!empty($item['berat_badan']) && $item['berat_badan'] != "-") {
                echo "<td>" . strtoupper($item['berat_badan']) . "</td>";
            } else {
                echo "<td>-</td>";
            }

            if (!empty($item['jarak_tempat_tgl_ke_sekolah']) && $item['jarak_tempat_tgl_ke_sekolah'] != "-") {
                echo "<td>" . strtoupper($item['jarak_tempat_tgl_ke_sekolah']) . "</td>";
            } else {
                echo "<td>-</td>";
            }

            if (!empty($item['jarak_tempat_tgl_ke_sekolah_lebih']) && $item['jarak_tempat_tgl_ke_sekolah_lebih'] != "-") {
                echo "<td>" . strtoupper($item['jarak_tempat_tgl_ke_sekolah_lebih']) . "</td>";
            } else {
                echo "<td>-</td>";
            }

            if (!empty($item['waktu_tempuh_ke_sekolah']) && $item['waktu_tempuh_ke_sekolah'] != "-") {
                echo "<td>" . strtoupper($item['waktu_tempuh_ke_sekolah']) . "</td>";
            } else {
                echo "<td>-</td>";
            }

            if (!empty($item['waktu_tempuh_ke_sekolah_lebih']) && $item['waktu_tempuh_ke_sekolah_lebih'] != "-") {
                echo "<td>" . strtoupper($item['waktu_tempuh_ke_sekolah_lebih']) . "</td>";
            } else {
                echo "<td>-</td>";
            }

            if (!empty($item['jumlah_saudara_kandung']) && $item['jumlah_saudara_kandung'] != "-") {
                echo "<td>" . strtoupper($item['jumlah_saudara_kandung']) . "</td>";
            } else {
                echo "<td>-</td>";
            }

            if (!empty($item['prestasi_01_jenis']) && $item['prestasi_01_jenis'] != "-") {
                echo "<td>" . strtoupper($item['prestasi_01_jenis']) . "</td>";
            } else {
                echo "<td>-</td>";
            }

            if (!empty($item['prestasi_01_tingkat']) && $item['prestasi_01_tingkat'] != "-") {
                echo "<td>" . strtoupper($item['prestasi_01_tingkat']) . "</td>";
            } else {
                echo "<td>-</td>";
            }

            if (!empty($item['prestasi_01_nama']) && $item['prestasi_01_nama'] != "-") {
                echo "<td>" . strtoupper($item['prestasi_01_nama']) . "</td>";
            } else {
                echo "<td>-</td>";
            }

            if (!empty($item['prestasi_01_tahun']) && $item['prestasi_01_tahun'] != "-") {
                echo "<td>" . strtoupper($item['prestasi_01_tahun']) . "</td>";
            } else {
                echo "<td>-</td>";
            }

            if (!empty($item['prestasi_01_penyelenggara']) && $item['prestasi_01_penyelenggara'] != "-") {
                echo "<td>" . strtoupper($item['prestasi_01_penyelenggara']) . "</td>";
            } else {
                echo "<td>-</td>";
            }

            if (!empty($item['prestasi_02_jenis']) && $item['prestasi_02_jenis'] != "-") {
                echo "<td>" . strtoupper($item['prestasi_02_jenis']) . "</td>";
            } else {
                echo "<td>-</td>";
            }

            if (!empty($item['prestasi_02_tingkat']) && $item['prestasi_02_tingkat'] != "-") {
                echo "<td>" . strtoupper($item['prestasi_02_tingkat']) . "</td>";
            } else {
                echo "<td>-</td>";
            }

            if (!empty($item['prestasi_02_nama']) && $item['prestasi_02_nama'] != "-") {
                echo "<td>" . strtoupper($item['prestasi_02_nama']) . "</td>";
            } else {
                echo "<td>-</td>";
            }

            if (!empty($item['prestasi_02_tahun']) && $item['prestasi_02_tahun'] != "-") {
                echo "<td>" . strtoupper($item['prestasi_02_tahun']) . "</td>";
            } else {
                echo "<td>-</td>";
            }

            if (!empty($item['prestasi_02_penyelenggara']) && $item['prestasi_02_penyelenggara'] != "-") {
                echo "<td>" . strtoupper($item['prestasi_02_penyelenggara']) . "</td>";
            } else {
                echo "<td>-</td>";
            }

            if (!empty($item['prestasi_03_jenis']) && $item['prestasi_03_jenis'] != "-") {
                echo "<td>" . strtoupper($item['prestasi_03_jenis']) . "</td>";
            } else {
                echo "<td>-</td>";
            }

            if (!empty($item['prestasi_03_tingkat']) && $item['prestasi_03_tingkat'] != "-") {
                echo "<td>" . strtoupper($item['prestasi_03_tingkat']) . "</td>";
            } else {
                echo "<td>-</td>";
            }

            if (!empty($item['prestasi_03_nama']) && $item['prestasi_03_nama'] != "-") {
                echo "<td>" . strtoupper($item['prestasi_03_nama']) . "</td>";
            } else {
                echo "<td>-</td>";
            }

            if (!empty($item['prestasi_03_tahun']) && $item['prestasi_03_tahun'] != "-") {
                echo "<td>" . strtoupper($item['prestasi_03_tahun']) . "</td>";
            } else {
                echo "<td>-</td>";
            }

            if (!empty($item['prestasi_03_penyelenggara']) && $item['prestasi_03_penyelenggara'] != "-") {
                echo "<td>" . strtoupper($item['prestasi_03_penyelenggara']) . "</td>";
            } else {
                echo "<td>-</td>";
            }

            if (!empty($item['prestasi_04_jenis']) && $item['prestasi_04_jenis'] != "-") {
                echo "<td>" . strtoupper($item['prestasi_04_jenis']) . "</td>";
            } else {
                echo "<td>-</td>";
            }

            if (!empty($item['prestasi_04_tingkat']) && $item['prestasi_04_tingkat'] != "-") {
                echo "<td>" . strtoupper($item['prestasi_04_tingkat']) . "</td>";
            } else {
                echo "<td>-</td>";
            }

            if (!empty($item['prestasi_04_nama']) && $item['prestasi_04_nama'] != "-") {
                echo "<td>" . strtoupper($item['prestasi_04_nama']) . "</td>";
            } else {
                echo "<td>-</td>";
            }

            if (!empty($item['prestasi_04_tahun']) && $item['prestasi_04_tahun'] != "-") {
                echo "<td>" . strtoupper($item['prestasi_04_tahun']) . "</td>";
            } else {
                echo "<td>-</td>";
            }

            if (!empty($item['prestasi_04_penyelenggara']) && $item['prestasi_04_penyelenggara'] != "-") {
                echo "<td>" . strtoupper($item['prestasi_04_penyelenggara']) . "</td>";
            } else {
                echo "<td>-</td>";
            }

            if (!empty($item['beasiswa_01_jenis']) && $item['beasiswa_01_jenis'] != "-") {
                echo "<td>" . strtoupper($item['beasiswa_01_jenis']) . "</td>";
            } else {
                echo "<td>-</td>";
            }

            if (!empty($item['beasiswa_01_sumber']) && $item['beasiswa_01_sumber'] != "-") {
                echo "<td>" . strtoupper($item['beasiswa_01_sumber']) . "</td>";
            } else {
                echo "<td>-</td>";
            }

            if (!empty($item['beasiswa_01_thn_mulai']) && $item['beasiswa_01_thn_mulai'] != "-") {
                echo "<td>" . strtoupper($item['beasiswa_01_thn_mulai']) . "</td>";
            } else {
                echo "<td>-</td>";
            }

            if (!empty($item['beasiswa_01_thn_selesai']) && $item['beasiswa_01_thn_selesai'] != "-") {
                echo "<td>" . strtoupper($item['beasiswa_01_thn_selesai']) . "</td>";
            } else {
                echo "<td>-</td>";
            }

            if (!empty($item['beasiswa_02_jenis']) && $item['beasiswa_02_jenis'] != "-") {
                echo "<td>" . strtoupper($item['beasiswa_02_jenis']) . "</td>";
            } else {
                echo "<td>-</td>";
            }

            if (!empty($item['beasiswa_02_sumber']) && $item['beasiswa_02_sumber'] != "-") {
                echo "<td>" . strtoupper($item['beasiswa_02_sumber']) . "</td>";
            } else {
                echo "<td>-</td>";
            }

            if (!empty($item['beasiswa_02_thn_mulai']) && $item['beasiswa_02_thn_mulai'] != "-") {
                echo "<td>" . strtoupper($item['beasiswa_02_thn_mulai']) . "</td>";
            } else {
                echo "<td>-</td>";
            }

            if (!empty($item['beasiswa_02_thn_selesai']) && $item['beasiswa_02_thn_selesai'] != "-") {
                echo "<td>" . strtoupper($item['beasiswa_02_thn_selesai']) . "</td>";
            } else {
                echo "<td>-</td>";
            }

            if (!empty($item['beasiswa_03_jenis']) && $item['beasiswa_03_jenis'] != "-") {
                echo "<td>" . strtoupper($item['beasiswa_03_jenis']) . "</td>";
            } else {
                echo "<td>-</td>";
            }

            if (!empty($item['beasiswa_03_sumber']) && $item['beasiswa_03_sumber'] != "-") {
                echo "<td>" . strtoupper($item['beasiswa_03_sumber']) . "</td>";
            } else {
                echo "<td>-</td>";
            }

            if (!empty($item['beasiswa_03_thn_mulai']) && $item['beasiswa_03_thn_mulai'] != "-") {
                echo "<td>" . strtoupper($item['beasiswa_03_thn_mulai']) . "</td>";
            } else {
                echo "<td>-</td>";
            }

            if (!empty($item['beasiswa_03_thn_selesai']) && $item['beasiswa_03_thn_selesai'] != "-") {
                echo "<td>" . strtoupper($item['beasiswa_03_thn_selesai']) . "</td>";
            } else {
                echo "<td>-</td>";
            }

            if (!empty($item['beasiswa_04_jenis']) && $item['beasiswa_04_jenis'] != "-") {
                echo "<td>" . strtoupper($item['beasiswa_04_jenis']) . "</td>";
            } else {
                echo "<td>-</td>";
            }

            if (!empty($item['beasiswa_04_sumber']) && $item['beasiswa_04_sumber'] != "-") {
                echo "<td>" . strtoupper($item['beasiswa_04_sumber']) . "</td>";
            } else {
                echo "<td>-</td>";
            }

            if (!empty($item['beasiswa_04_thn_mulai']) && $item['beasiswa_04_thn_mulai'] != "-") {
                echo "<td>" . strtoupper($item['beasiswa_04_thn_mulai']) . "</td>";
            } else {
                echo "<td>-</td>";
            }

            if (!empty($item['beasiswa_04_thn_selesai']) && $item['beasiswa_04_thn_selesai'] != "-") {
                echo "<td>" . strtoupper($item['beasiswa_04_thn_selesai']) . "</td>";
            } else {
                echo "<td>-</td>";
            }

            if (!empty($item['peminatan']) && $item['peminatan'] != "-") {
                echo "<td>" . strtoupper($item['peminatan']) . "</td>";
            } else {
                echo "<td>-</td>";
            }

            if (!empty($item['lintas_minat_01']) && $item['lintas_minat_01'] != "-") {
                echo "<td>" . strtoupper($item['lintas_minat_01']) . "</td>";
            } else {
                echo "<td>-</td>";
            }

            if (!empty($item['lintas_minat_02']) && $item['lintas_minat_02'] != "-") {
                echo "<td>" . strtoupper($item['lintas_minat_02']) . "</td>";
            } else {
                echo "<td>-</td>";
            }

            if (!empty($item['ekskul_01']) && $item['ekskul_01'] != "-") {
                echo "<td>" . strtoupper($item['ekskul_01']) . "</td>";
            } else {
                echo "<td>-</td>";
            }

            if (!empty($item['ekskul_02']) && $item['ekskul_02'] != "-") {
                echo "<td>" . strtoupper($item['ekskul_02']) . "</td>";
            } else {
                echo "<td>-</td>";
            }

            if (!empty($item['status_keluarga']) && $item['status_keluarga'] != "-") {
                echo "<td>" . strtoupper($item['status_keluarga']) . "</td>";
            } else {
                echo "<td>-</td>";
            }
            if (!empty($item['anak_ke']) && $item['anak_ke'] != "-") {
                echo "<td>" . strtoupper($item['anak_ke']) . "</td>";
            } else {
                echo "<td>-</td>";
            }
            if (!empty($item['sekolah_asal']) && $item['sekolah_asal'] != "-") {
                echo "<td>" . strtoupper($item['sekolah_asal']) . "</td>";
            } else {
                echo "<td>-</td>";
            }
            if (!empty($item['kelas_diterima']) && $item['kelas_diterima'] != "-") {
                echo "<td>" . strtoupper($item['kelas_diterima']) . "</td>";
            } else {
                echo "<td>-</td>";
            }
            if (!empty($item['tanggal_diterima']) && $item['tanggal_diterima'] != "-") {
                echo "<td>" . strtoupper($item['tanggal_diterima']) . "</td>";
            } else {
                echo "<td>-</td>";
            }
            if (!empty($item['alamat_ortu']) && $item['alamat_ortu'] != "-") {
                echo "<td>" . strtoupper($item['alamat_ortu']) . "</td>";
            } else {
                echo "<td>-</td>";
            }
            if (!empty($item['no_telp_ortu']) && $item['no_telp_ortu'] != "-") {
                echo "<td>" . strtoupper($item['no_telp_ortu']) . "</td>";
            } else {
                echo "<td>-</td>";
            }
            if (!empty($item['alamat_wali']) && $item['alamat_wali'] != "-") {
                echo "<td>" . strtoupper($item['alamat_wali']) . "</td>";
            } else {
                echo "<td>-</td>";
            }
            if (!empty($item['no_telp_wali']) && $item['no_telp_wali'] != "-") {
                echo "<td>" . strtoupper($item['no_telp_wali']) . "</td>";
            } else {
                echo "<td>-</td>";
            }
            if (!empty($item['pekerjaan_wali']) && $item['pekerjaan_wali'] != "-") {
                echo "<td>" . strtoupper($item['pekerjaan_wali']) . "</td>";
            } else {
                echo "<td>-</td>";
            }


            echo "</tr>";
        }

        echo "			</table>
					</div>
				</body>
			</html>";
    }

    /**
     * Performs the AJAX validation.
     * @param UserProfile $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'user-profile-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
