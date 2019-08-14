<?php
	ob_start();
	set_time_limit(6000);

	$dbhost 	= "localhost";
	$dbuser 	= "pinisi";
	$dbpass 	= "pinisi_db_345";
	$dbname 	= "pinisi";
	$school		= "PINISI SCHOOL";

	$conn = mysqli_connect($dbhost, $dbuser, $dbpass);
	if($conn){
		$dbconn = mysqli_select_db($conn, $dbname);
		if(!$dbconn){
			echo "Error Database Not Found!";
			exit();
		}
	}else{
		echo "Error Database Connection!";
		exit();
	}

	// -----------------------------------------------
	// 01 Stats From table activities
	// -----------------------------------------------
	$table = "activities";
	$query = "SELECT * FROM ".$table." WHERE stats_status IS NULL";
	$execs = mysqli_query($conn, $query);

	if($execs){
		$numrows = mysqli_num_rows($execs);
		if($numrows > 0){
			while($datas = mysqli_fetch_array($execs)){
				$curlSender = curl_init();

				curl_setopt($curlSender, CURLOPT_URL,"http://stats.pinisi.io/analytics.php");
				curl_setopt($curlSender, CURLOPT_POST, 1);

				curl_setopt($curlSender, CURLOPT_POSTFIELDS,
					http_build_query(array(
					    'table' => $table,
					    'id_school' => $school,
						'id_real' => trim($datas['id']),
						'activity_type' => trim($datas['activity_type']),
						'content' => trim($datas['content']),
						'created_by' => trim($datas['created_by']),
						'updated_by' => trim($datas['updated_by']),
						'created_at' => trim($datas['created_at']),
						'updated_at' => trim($datas['updated_at']),
					))
				);
				curl_setopt($curlSender, CURLOPT_RETURNTRANSFER, true);

				$curlOutput = curl_exec($curlSender);
				curl_close($curlSender);

				if($curlOutput == "OK"){
					$queryUpdate = "UPDATE ".$table." SET stats_status = 1 WHERE id = ".trim($datas['id']);
					$execUpdate = mysqli_query($conn, $queryUpdate);

					if(!$execUpdate){
						echo "Error Update from table ".$table." With id = ".trim($datas['id'])."\n";
					}else{
						echo "Successfully Sending statistics from table ".$table." With id = ".trim($datas['id'])."\n";
					}
				}else{
					echo $curlOutput."\n";
				}
			}
		}else{
			echo "Nothing to Sync from table ".$table."\n";
		}
	}

	// -----------------------------------------------
	// 02 Stats From table questions
	// -----------------------------------------------
	$table = "questions";
	$query = "SELECT * FROM ".$table." WHERE stats_status IS NULL";
	$execs = mysqli_query($conn, $query);

	if($execs){
		$numrows = mysqli_num_rows($execs);
		if($numrows > 0){
			while($datas = mysqli_fetch_array($execs)){
				$curlSender = curl_init();

				curl_setopt($curlSender, CURLOPT_URL,"http://stats.pinisi.io/analytics.php");
				curl_setopt($curlSender, CURLOPT_POST, 1);

				curl_setopt($curlSender, CURLOPT_POSTFIELDS,
					http_build_query(array(
					    'table' => $table,
					    'id_school' => $school,
						'id_real' => trim($datas['id']),
						'id_quiz' => trim($datas['quiz_id']),
						'id_lesson' => trim($datas['lesson_id']),
						'title' => trim($datas['title']),
						'text' => trim($datas['text']),
						'choices' => trim($datas['choices']),
						'key_answer' => trim($datas['key_answer']),
						'created_at' => trim($datas['created_at']),
						'updated_at' => trim($datas['updated_at']),
						'teacher_id' => trim($datas['teacher_id']),
						'created_by' => trim($datas['created_by']),
						'updated_by' => trim($datas['updated_by']),
						'file' => trim($datas['file']),
						'type' => trim($datas['type']),
						'point' => trim($datas['point']),
						'choices_files' => trim($datas['choices_files']),
						'id_lama' => trim($datas['id_lama']),
						'share_status' => trim($datas['share_status']),
						'share_teacher' => trim($datas['share_teacher']),
						'trash' => trim($datas['trash']),
					))
				);
				curl_setopt($curlSender, CURLOPT_RETURNTRANSFER, true);

				$curlOutput = curl_exec($curlSender);
				curl_close($curlSender);

				if($curlOutput == "OK"){
					$queryUpdate = "UPDATE ".$table." SET stats_status = 1 WHERE id = ".trim($datas['id']);
					$execUpdate = mysqli_query($conn, $queryUpdate);

					if(!$execUpdate){
						echo "Error Update from table ".$table." With id = ".trim($datas['id'])."\n";
					}else{
						echo "Successfully Sending statistics from table ".$table." With id = ".trim($datas['id'])."\n";
					}
				}else{
					echo $curlOutput."\n";
				}
			}
		}else{
			echo "Nothing to Sync from table ".$table."\n";
		}
	}

	// -----------------------------------------------
	// 03 Stats From table quiz
	// -----------------------------------------------
	$table = "quiz";
	$query = "SELECT * FROM ".$table." WHERE stats_status IS NULL";
	$execs = mysqli_query($conn, $query);

	if($execs){
		$numrows = mysqli_num_rows($execs);
		if($numrows > 0){
			while($datas = mysqli_fetch_array($execs)){
				$curlSender = curl_init();

				curl_setopt($curlSender, CURLOPT_URL,"http://stats.pinisi.io/analytics.php");
				curl_setopt($curlSender, CURLOPT_POST, 1);

				curl_setopt($curlSender, CURLOPT_POSTFIELDS,
					http_build_query(array(
					    'table' => $table,
					    'id_school' => $school,
						'id_real' => trim($datas['id']),
						'id_lesson' => trim($datas['lesson_id']),
						'id_chapter' => trim($datas['chapter_id']),
						'title' => trim($datas['title']),
						'quiz_type' => trim($datas['quiz_type']),
						'created_at' => trim($datas['created_at']),
						'updated_at' => trim($datas['updated_at']),
						'created_by' => trim($datas['created_by']),
						'updated_by' => trim($datas['updated_by']),
						'start_time' => trim($datas['start_time']),
						'finish_time' => trim($datas['finish_time']),
						'end_time' => trim($datas['end_time']),
						'total_question' => trim($datas['total_question']),
						'status' => trim($datas['status']),
						'add_to_summary' => trim($datas['add_to_summary']),
						'repeat_quiz' => trim($datas['repeat_quiz']),
						'question' => trim($datas['question']),
						'semester' => trim($datas['semester']),
						'year' => trim($datas['year']),
						'random' => trim($datas['random']),
						'show_nilai' => trim($datas['show_nilai']),
						'id_bersama' => trim($datas['id_bersama']),
						'pg_prosentase' => trim($datas['pg_prosentase']),
						'esai_prosentase' => trim($datas['esai_prosentase']),
						'passcode' => trim($datas['passcode']),
						'random_opt' => trim($datas['random_opt']),
						'trash' => trim($datas['trash']),
					))
				);
				curl_setopt($curlSender, CURLOPT_RETURNTRANSFER, true);

				$curlOutput = curl_exec($curlSender);
				curl_close($curlSender);

				if($curlOutput == "OK"){
					$queryUpdate = "UPDATE ".$table." SET stats_status = 1 WHERE id = ".trim($datas['id']);
					$execUpdate = mysqli_query($conn, $queryUpdate);

					if(!$execUpdate){
						echo "Error Update from table ".$table." With id = ".trim($datas['id'])."\n";
					}else{
						echo "Successfully Sending statistics from table ".$table." With id = ".trim($datas['id'])."\n";
					}
				}else{
					echo $curlOutput."\n";
				}
			}
		}else{
			echo "Nothing to Sync from table ".$table."\n";
		}
	}

	// -----------------------------------------------
	// 04 Stats From table student_quiz
	// -----------------------------------------------
	$table = "student_quiz";
	$query = "SELECT * FROM ".$table." WHERE stats_status IS NULL";
	$execs = mysqli_query($conn, $query);

	if($execs){
		$numrows = mysqli_num_rows($execs);
		if($numrows > 0){
			while($datas = mysqli_fetch_array($execs)){
				$curlSender = curl_init();

				curl_setopt($curlSender, CURLOPT_URL,"http://stats.pinisi.io/analytics.php");
				curl_setopt($curlSender, CURLOPT_POST, 1);

				curl_setopt($curlSender, CURLOPT_POSTFIELDS,
					http_build_query(array(
					    'table' => $table,
					    'id_school' => $school,
						'id_real' => trim($datas['id']),
						'id_quiz' => trim($datas['quiz_id']),
						'id_student' => trim($datas['student_id']),
						'created_at' => trim($datas['created_at']),
						'updated_at' => trim($datas['updated_at']),
						'score' => trim($datas['score']),
						'essay_score' => trim($datas['essay_score']),
						'pg_score' => trim($datas['pg_score']),
						'right_answer' => trim($datas['right_answer']),
						'wrong_answer' => trim($datas['wrong_answer']),
						'unanswered' => trim($datas['unanswered']),
						'student_answer' => trim($datas['student_answer']),
						'attempt' => trim($datas['attempt']),
						'indikasi' => trim($datas['indikasi']),
						'trash' => trim($datas['trash']),
					))
				);
				curl_setopt($curlSender, CURLOPT_RETURNTRANSFER, true);

				$curlOutput = curl_exec($curlSender);
				curl_close($curlSender);

				if($curlOutput == "OK"){
					$queryUpdate = "UPDATE ".$table." SET stats_status = 1 WHERE id = ".trim($datas['id']);
					$execUpdate = mysqli_query($conn, $queryUpdate);

					if(!$execUpdate){
						echo "Error Update from table ".$table." With id = ".trim($datas['id'])."\n";
					}else{
						echo "Successfully Sending statistics from table ".$table." With id = ".trim($datas['id'])."\n";
					}
				}else{
					echo $curlOutput."\n";
				}
			}
		}else{
			echo "Nothing to Sync from table ".$table."\n";
		}
	}

	// -----------------------------------------------
	// 05 Stats From table users
	// -----------------------------------------------
	$table = "users";
	$query = "SELECT * FROM ".$table." WHERE stats_status IS NULL";
	$execs = mysqli_query($conn, $query);

	if($execs){
		$numrows = mysqli_num_rows($execs);
		if($numrows > 0){
			while($datas = mysqli_fetch_array($execs)){
				$curlSender = curl_init();

				curl_setopt($curlSender, CURLOPT_URL,"http://stats.pinisi.io/analytics.php");
				curl_setopt($curlSender, CURLOPT_POST, 1);

				curl_setopt($curlSender, CURLOPT_POSTFIELDS,
					http_build_query(array(
					    'table' => $table,
					    'id_school' => $school,
						'id_real' => trim($datas['id']),
						'id_role' => trim($datas['role_id']),
						'created_at' => trim($datas['created_at']),
						'updated_at' => trim($datas['updated_at']),
						'trash' => trim($datas['trash']),
					))
				);
				curl_setopt($curlSender, CURLOPT_RETURNTRANSFER, true);

				$curlOutput = curl_exec($curlSender);
				curl_close($curlSender);

				if($curlOutput == "OK"){
					$queryUpdate = "UPDATE ".$table." SET stats_status = 1 WHERE id = ".trim($datas['id']);
					$execUpdate = mysqli_query($conn, $queryUpdate);

					if(!$execUpdate){
						echo "Error Update from table ".$table." With id = ".trim($datas['id'])."\n";
					}else{
						echo "Successfully Sending statistics from table ".$table." With id = ".trim($datas['id'])."\n";
					}
				}else{
					echo $curlOutput."\n";
				}
			}
		}else{
			echo "Nothing to Sync from table ".$table."\n";
		}
	}

	// -----------------------------------------------
	// 06 Stats From table user_logs
	// -----------------------------------------------
	$table = "user_logs";
	$query = "SELECT * FROM ".$table." WHERE stats_status IS NULL";
	$execs = mysqli_query($conn, $query);

	if($execs){
		$numrows = mysqli_num_rows($execs);
		if($numrows > 0){
			while($datas = mysqli_fetch_array($execs)){
				$curlSender = curl_init();

				curl_setopt($curlSender, CURLOPT_URL,"http://stats.pinisi.io/analytics.php");
				curl_setopt($curlSender, CURLOPT_POST, 1);

				curl_setopt($curlSender, CURLOPT_POSTFIELDS,
					http_build_query(array(
					    'table' => $table,
					    'id_school' => $school,
						'id_real' => trim($datas['id']),
						'id_user' => trim($datas['user_id']),
						'type' => trim($datas['type']),
						'created_at' => trim($datas['created_at']),
						'quiz_id' => trim($datas['quiz_id']),
						'status' => trim($datas['status']),
					))
				);
				curl_setopt($curlSender, CURLOPT_RETURNTRANSFER, true);

				$curlOutput = curl_exec($curlSender);
				curl_close($curlSender);

				if($curlOutput == "OK"){
					$queryUpdate = "UPDATE ".$table." SET stats_status = 1 WHERE id = ".trim($datas['id']);
					$execUpdate = mysqli_query($conn, $queryUpdate);

					if(!$execUpdate){
						echo "Error Update from table ".$table." With id = ".trim($datas['id'])."\n";
					}else{
						echo "Successfully Sending statistics from table ".$table." With id = ".trim($datas['id'])."\n";
					}
				}else{
					echo $curlOutput."\n";
				}
			}
		}else{
			echo "Nothing to Sync from table ".$table."\n";
		}
	}

?>