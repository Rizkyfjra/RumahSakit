<?php

class AdminController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column1';

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
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('BackupDb','backup','control','poweroff','restart','Restartreal','Poweroffreal','Parsing','Bulkimportsiswa','bulkimportsiswalm','bulkaddlistid','BulkimportsiswaMore'),
				'expression' => 'Yii::app()->user->YiiAdmin',
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

		/* backup the db OR just a table */
		public function actionBackupDb($host = 'localhost',$user = 'root' ,$pass = 'root',$name = 'sman22_backup',$tables = '*')
		{
			
			$link = mysqli_connect($host,$user,$pass,$name);
			// mysqli_select_db($name,$link);
			
			//get all of the tables
			if($tables == '*')
			{
				$tables = array();
				$result = mysqli_query($link,'SHOW TABLES');
				while($row = mysqli_fetch_row($result))
				{
					$tables[] = $row[0];
				}
			}
			else
			{
				$tables = is_array($tables) ? $tables : explode(',',$tables);
			}
			
			$return = "";
			//cycle through
			foreach($tables as $table)
			{
				$result = mysqli_query($link,'SELECT * FROM '.$table);
				$num_fields = mysqli_num_fields($result);
				
				$return.= 'DROP TABLE '.$table.';';
				$row2 = mysqli_fetch_row(mysqli_query($link,'SHOW CREATE TABLE '.$table));
				$return.= "\n\n".$row2[1].";\n\n";
				
				for ($i = 0; $i < $num_fields; $i++) 
				{
					while($row = mysqli_fetch_row($result))
					{
						$return.= 'INSERT INTO '.$table.' VALUES(';
						for($j=0; $j < $num_fields; $j++) 
						{
							$row[$j] = addslashes($row[$j]);
							// $row[$j] = ereg_replace("\n","\\n",$row[$j]);
							if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
							if ($j < ($num_fields-1)) { $return.= ','; }
						}
						$return.= ");\n";
					}
				}
				$return.="\n\n\n";
			}
			
			//save file
			$handle = fopen('db-backup-'.time().'-'.(md5(implode(',',$tables))).'.sql','w+');
			fwrite($handle,$return);
			fclose($handle);
		}

	public  function actionBackup($quiz_id) {
				$tables = 'questions';
			    if ($tables == '*') {
			        $tables = array();
			        $tables = Yii::app()->db->schema->getTableNames();
			    } else {
			        $tables = is_array($tables) ? $tables : explode(',', $tables);
			    }
			    $return = '';

			    foreach ($tables as $table) {
			        $result = Yii::app()->db->createCommand('SELECT `quiz_id`, `lesson_id`, `title`, `text`, `choices`, `key_answer`, `created_at`, `updated_at`, `teacher_id`, `created_by`, `updated_by`, `file`, `type`, `point`, `choices_files`, `id_lama`, `share_status`, `share_teacher`, `trash`, `sync_status`, `stats_status` FROM ' . $table .' where id in ('.$ids.')')->query();
			        // $return.= 'DROP TABLE IF EXISTS ' . $table . ';';
			        // $row2 = Yii::app()->db->createCommand('SHOW CREATE TABLE ' . $table)->queryRow();
			        $return.= "\n";
			        foreach ($result as $row) {
			            $return.= 'INSERT INTO ' . $table . ' (`quiz_id`, `lesson_id`, `title`, `text`, `choices`, `key_answer`, `created_at`, `updated_at`, `teacher_id`, `created_by`, `updated_by`, `file`, `type`, `point`, `choices_files`, `id_lama`, `share_status`, `share_teacher`, `trash`, `sync_status`, `stats_status`) VALUES(';
			            foreach ($row as $data) {
			                $data = addslashes($data);

			                // Updated to preg_replace to suit PHP5.3 +
			                $data = preg_replace("/\n/", "\\n", $data);
			                if (isset($data)) {
			                    $return.= '"' . $data . '"';
			                } else {
			                    $return.= '""';
			                }
			                $return.= ',';
			            }
			            $return = substr($return, 0, strlen($return) - 1);
			            $return.= ");\n";
			        }
			        $return.="\n\n\n";
			    }
			    // echo "<pre>";
			    // 	print_r($return);
			    // echo "</pre>";

			    $backup_name = "ujian.sql";
		        header('Content-Type: application/octet-stream');   
		        header("Content-Transfer-Encoding: Binary"); 
		        header("Content-disposition: attachment; filename=\"".$backup_name."\"");  
		        echo $return; exit;

			    //save file
			    // $handle = fopen($filepath, 'w+');
			    // fwrite($handle, $return);
			    // fclose($handle);
			}

	public function actionControl()

	{

		echo '<a class="btn btn-default" href="'.Yii::app()->createUrl("Admin/poweroff").'" role="button">Power OFF</a>';

		echo "<br>";

		echo '<a class="btn btn-default" href="'.Yii::app()->createUrl("Admin/restart").'" role="button">Restart</a>';

	}

	public function actionPoweroffreal()

	{

		// echo "Ini Fungsi Power OFF";

		// echo "<br>";

		// echo '<a class="btn btn-default" href="'.Yii::app()->createUrl("Admin/control").'" role="button">Control</a>';



		exec('sudo /sbin/poweroff');

	}



	public function actionPoweroff()

	{

		// echo "Ini Fungsi Power OFF";

		// echo "<br>";

		// echo '<a class="btn btn-default" href="'.Yii::app()->createUrl("Admin/control").'" role="button">Control</a>';

		echo "Edubox akan mati dalam beberapa detik....";



		sleep(10);





		$this->redirect(array('Admin/Poweroffreal'));

	}



	public function actionRestartreal()

	{

		// echo "Ini Fungsi Restart";

		// echo "<br>";

		// echo '<a class="btn btn-default" href="'.Yii::app()->createUrl("Admin/control").'" role="button">Control</a>';





		exec('sudo /sbin/reboot');



	}





	public function actionRestart()

	{

		// echo "Ini Fungsi Restart";

		// echo "<br>";

		// echo '<a class="btn btn-default" href="'.Yii::app()->createUrl("Admin/control").'" role="button">Control</a>';

		echo "Pinisi Exambox akan restart dalam beberapa detik....";



		sleep(10);



		$this->redirect(array('Admin/Restartreal'));

	}

	public function actionParsing()

	{

		$xml=simplexml_load_file(Yii::app()->basePath.'/../images/quiz.xml','SimpleXMLElement', LIBXML_NOCDATA) or die("Error: Cannot create object");
		foreach ($xml->question as $value) {
			//echo "<pre>";
			// print_r($value);
			// print_r($value->questiontext->text);
			echo "----------------------------- </br>";
			echo $value->questiontext->text."</br>";
			echo "Jawaban </br>";
			$jawabannya = null ;
			foreach ($value->answer as $jawab) {
				// print_r($jawab->text);
				echo $jawab->text."</br>";
				if ($jawab->attributes()['fraction'] == 100.00) {
					$jawabannya = $jawab->text;
				} 
			}
			if ($jawabannya!= null) {
				echo "jawabannya (".$jawabannya.") </br>";
			} else if ($jawabannya == null) {
				echo "jawabannya (".$value->answer->text.") </br>";
			}
			
			echo "</br>";
			//echo "</pre>";
		}
		// echo "<pre>";
		// 	print_r($xml->question[40]->questiontext);
		// 	echo "</pre>";
	}


	public function actionBulkimportsiswaMore($id) {
		
		$sql="SELECT * FROM `lesson` WHERE `id` >= '".$id."' ";
		$command =Yii::app()->db->createCommand($sql);
		$rows=$command->queryAll();

		foreach ($rows as $value) {
			$cekExist = LessonMc::model()->findAll(array('condition'=>'lesson_id = '.$value['id'].''));
			if (empty($cekExist)) {
				$getListSiswa = User::model()->findAll(array('condition'=>'class_id = '.$value['class_id'].' and trash is null'));

				if (!empty($getListSiswa)) {

					echo "<pre>";
					print_r ($value);
					echo "</pre>";
					foreach ($getListSiswa as $siswa) {
						
							echo $siswa['display_name'];
							echo "</br>";

						  $join = "INSERT INTO lesson_mc (lesson_id,user_id) values(".$value['id'].",".$siswa['id'].")";
						  $joinCommand=Yii::app()->db->createCommand($join);
						  if($joinCommand->execute()){
								echo "sukses";
								echo "</br>";
						  } else {
						  		echo "gagal";
								echo "</br>";
						  }	
					}	
				}
				
			} else {
				echo "tidak ada";
			}


			
		}
		
	}

	public function actionBulkimportsiswa() {
		
		$sql="SELECT * FROM `lesson` WHERE `id` >= '1' ";
		$command =Yii::app()->db->createCommand($sql);
		$rows=$command->queryAll();

		foreach ($rows as $value) {
			$cekExist = LessonMc::model()->findAll(array('condition'=>'lesson_id = '.$value['id'].''));
			if (empty($cekExist)) {
				$getListSiswa = User::model()->findAll(array('condition'=>'class_id = '.$value['class_id'].' and trash is null'));

				if (!empty($getListSiswa)) {

					echo "<pre>";
					print_r ($value);
					echo "</pre>";
					foreach ($getListSiswa as $siswa) {
						
							echo $siswa['display_name'];
							echo "</br>";

						  $join = "INSERT INTO lesson_mc (lesson_id,user_id) values(".$value['id'].",".$siswa['id'].")";
						  $joinCommand=Yii::app()->db->createCommand($join);
						  if($joinCommand->execute()){
								echo "sukses";
								echo "</br>";
						  } else {
						  		echo "gagal";
								echo "</br>";
						  }	
					}	
				}
				
			} else {
				echo "tidak ada";
			}


			
		}
		
	}

	public function actionBulkaddlistid() {
		
		$sql="SELECT * FROM `lesson` WHERE `list_id` IS NULL";
		$command =Yii::app()->db->createCommand($sql);
		$rows=$command->queryAll();

		foreach ($rows as $value) {
			$cekExist = LessonList::model()->findAll(array('condition'=>'t.name = \''.$value['name'].'\' and t.group = '.$value['kelompok'].' '));
			if (!empty($cekExist)) {
				

			

					echo "<pre>";
					print_r ($value);
					//print_r ($cekExist);
					echo "</pre>";
					echo $cekExist[0]->id;

						 $sql1="UPDATE lesson SET sync_status = 2, list_id = ".$cekExist[0]->id." WHERE id = ".$value['id']." ";
						 $command1=Yii::app()->db->createCommand($sql1);
									if($command1->execute()){
										echo "sukses";
										echo "</br>";
									}else{
										echo "gagal";
										echo "</br>";
									}

					// foreach ($getListSiswa as $siswa) {
						
					// 		echo $siswa['display_name'];
					// 		echo "</br>";

					// 	  // $join = "INSERT INTO lesson_mc (lesson_id,user_id) values(".$value['id'].",".$siswa['id'].")";
					// 	  // $joinCommand=Yii::app()->db->createCommand($join);
					// 	  // if($joinCommand->execute()){
					// 			// echo "sukses";
					// 			// echo "</br>";
					// 	  // } else {
					// 	  // 		echo "gagal";
					// 			// echo "</br>";
					// 	  // }	
					// }	
				
				
			}


			
		}

		
		
	}

	public function actionBulkimportsiswalm() {	
				

		$model=new Activities;

		$sukses = 0;
		$fail = 0;

        
		if(isset($_POST['Activities']))
		{	
			$model->attributes=$_POST['Activities'];
			//$filelist=CUploadedFile::getInstancesByName('csvfile');
			$xlsFile=CUploadedFile::getInstancesByName('csvfile');
			$prefix = Yii::app()->params['tablePrefix'];

			// To validate
			$urutan = 1;
			if($model->validate())
			{
				$cek_id = "";
				foreach($xlsFile as $file)
				{
					try{
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
						$range  = 'A2:L'.$highestRow.'';
						//$text = $data->toArray(null, true, true, true);
						$text = $data->rangeToArray($range);
						$head = $data->rangeToArray('A1:L1'); 

						foreach ($text as $key => $val) {
							echo "<pre>";
							//print_r($val);
						   	$column = array_combine($head[0], $val);
						   	$row2 = $row;
							print_r($column);
							//print_r($coordinate[1]);
							//print_r($gambar);
							echo "</pre>";

							if (!empty($column['NO INDUK'])){
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
								$lm1 = $column['Nama Lintas M1'];
								$lm2 = $column['Nama Lintas M2'];
								$nisn = $column['NISN'];
								$kelas = $column['KELAS'];
								//$id_mapel = $column['ID Mapel'];
								$nik_final = str_replace('-', '', trim($nik));
								$tipe1 = 'rnh';
								$tipe2 = 'uts';
								//$rnh = $column['Nilai Harian'];
								//$uts = $column['Nilai UTS']; 
								
								$siswa = User::model()->findAll(array('condition'=>'username = '.$nik_final));



								//$cekUts = FinalMark::model()->findAll(array("condition"=>"user_id = ".$siswa[0]->id." AND lesson_id = ".$id_mapel." AND tipe = 'uts' AND semester = '2' AND tahun_ajaran = '2016'"));
								//$cekRnh = FinalMark::model()->findAll(array("condition"=>"user_id = ".$siswa[0]->id." AND lesson_id = ".$id_mapel." AND tipe = 'rnh' AND semester = '2' AND tahun_ajaran = '2016'"));	

								// $ph=new PasswordHash(Yii::app()->params['phpass']['iteration_count_log2'], Yii::app()->params['phpass']['portable_hashes']);
								// $passwd = $ph->HashPassword($password); 

								// if ($role == 2){
								// 	if (empty($kelas)){
								// 		Yii::app()->user->setFlash('error', "Baris $row2 . kolom kelas harus di isi");
								// 		$this->redirect(array('bulk')); 
								// 	}
								// }

								if (empty($nama)){
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


								if(!empty($siswa)){
									echo "ada : ".$siswa[0]->display_name;
									echo "</br>";
								} else {
									echo "tidak";
								}

								if (strlen($nisn)==7) {
									$nisn = '000'.$nisn;
								} else  if (strlen($nisn)==8){
									$nisn = '00'.$nisn;
								} else  if (strlen($nisn)==9){
									$nisn = '0'.$nisn;
								} else if(strlen($nisn)==6) {
									$nisn = '0000'.$nisn;
								}
								
								echo $nisn;
								echo "</br>";
								echo "asdfsdf ".strlen($nisn);
								if (strlen($nisn)!=0) {
									 $join1 = "INSERT INTO user_profile (user_id,nisn) values(".$siswa[0]->id.",'".$nisn."')";
								     $joinCommand1=Yii::app()->db->createCommand($join1);
									  if($joinCommand1->execute()){
											echo $join1;
											echo "sukses";
											echo "</br>";
									  } else {
									  		echo "gagal";
											echo "</br>";
									  }	
								}

								
								// if (empty($cekRnh)) {
								// 	$insert1="INSERT INTO ".$prefix."final_mark (user_id,lesson_id,tipe,semester,tahun_ajaran,nilai,created_at,updated_at,created_by,updated_by) values('".$siswa[0]->id."','".$id_mapel."','".$tipe1."','2','2016','".$rnh."',NOW(),NOW(),'".Yii::app()->user->id."','".Yii::app()->user->id."')";
		 				
					 		// 		$insertCommand1=Yii::app()->db->createCommand($insert1);
					 				
					 			
								// 	if($insertCommand1->execute()){
								// 		$sukses++;
								// 	}else{
								// 		$fail++;
								// 	}	
								// } else {
								// 	$sql1="UPDATE ".$prefix."final_mark SET nilai = ".$rnh.", updated_at = NOW(), updated_by = ".Yii::app()->user->id." WHERE user_id = ".$siswa[0]->id." AND lesson_id = ".$id_mapel." AND tipe = 'rnh' AND semester = '2' AND tahun_ajaran = '2016'";
								// 	$command1=Yii::app()->db->createCommand($sql1);
								// 	if($command1->execute()){
								// 		$sukses++;
								// 	}else{
								// 		$fail++;
								// 	}
								// }
								

								// if (empty($cekUts)) {
								// 	$insert2="INSERT INTO ".$prefix."final_mark (user_id,lesson_id,tipe,semester,tahun_ajaran,nilai,created_at,updated_at,created_by,updated_by) values('".$siswa[0]->id."','".$id_mapel."','".$tipe2."','2','2016','".$uts."',NOW(),NOW(),'".Yii::app()->user->id."','".Yii::app()->user->id."')";
		 				
					 		// 		$insertCommand2=Yii::app()->db->createCommand($insert2);
					 				
								// 	if($insertCommand2->execute()){
								// 		$sukses++;
								// 	}else{
								// 		$fail++;
								// 	}
								// } else {
								// 	$sql2="UPDATE ".$prefix."final_mark SET nilai = ".$uts.", updated_at = NOW(), updated_by = ".Yii::app()->user->id." WHERE user_id = ".$siswa[0]->id." AND lesson_id = ".$id_mapel." AND tipe = 'uts' AND semester = '2' AND tahun_ajaran = '2016'";
								// 	$command2=Yii::app()->db->createCommand($sql2);
								// 	if($command2->execute()){
								// 		$sukses++;
								// 	}else{
								// 		$fail++;
								// 	}
								// }
								
								
							} 

							//$row++;
							//$urutan++;	
						}
						//Yii::app()->user->setFlash('success', "Berhasil Import ".$sukses." Nilai Siswa  !");
						// $transaction->commit();
					
					}catch(Exception $error){
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


		$this->render('//lesson/importsiswalm',array(
			'model'=>$model,

		));


	}
	
}
