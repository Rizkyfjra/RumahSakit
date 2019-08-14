<?php
$this->breadcrumbs=array(
	'Log Info',
);

?>

<div class="container">
	<?php 
		$satuKali=0;
		$duaKali=0;
		$tigaKali=0;
		$lebihTiga=0;
		$aktif=0;
		$hlogin=0;
		$buatTugas = 0;
		$buatMateri = 0;
		$buatKuis = 0;
		$kerjakanTugas = 0;
		$kerjakanKuis = 0;
		$o_f_quiz_count = 0;
		$o_f_tugas_count = 0;
		$o_f_materi_count = 0;

		$kumpulTugas10=$kumpulTugas10->getData();	

		$uploadMateri=$uploadMateri10->getData();

		$uploadTugas=$uploadTugas10->getData();
		
		$nilai10=$nilai10->getData();

		$kuis10=$kuis10->getData();

		$nilaiKuis10=$nilaiKuis10->getData();

		$soal10=$soal10->getData();

		$usr = $user->getData();

		$o_f_quiz = $o_f_quiz->getData();

		$o_f_tugas = $o_f_tugas->getData();

		$o_f_materi = $o_f_materi->getData();

		foreach ($usr as $u) {
			$ids=0;
			foreach ($kumpulTugas10 as $a) {
				if($u->id == $a->student_id){
					$ids++;
					$kerjakanTugas++;
				}
			}

			foreach ($uploadMateri as $b) {
				if($u->id == $b->created_by){
					$ids++;
					$buatMateri++;
				}
			}

			foreach ($nilai10 as $c) {
				if($u->id == $c->student_id){
					$ids++;
					$kerjakanTugas++;
				}
			}

			foreach ($kuis10 as $d) {
				if($u->id == $d->created_by){
					$ids++;
					$buatKuis++;
				}
			}

			foreach ($nilaiKuis10 as $e) {
				if($u->id == $e->student_id){
					$ids++;
					$kerjakanKuis++;
				}
				
			}

			foreach ($soal10 as $f) {
				if($u->id == $f->teacher_id){
					$ids++;
				}
			}

			foreach ($act as $akt) {
				if($u->id == $akt->created_by){
					$aktif++;
				}
			}

			foreach ($ulog as $ul) {
				if($u->id == $ul->user_id){
					$hlogin++;
				}
			}

			foreach ($uploadTugas as $x) {
				if($u->id == $x->created_by){
					$ids++;
					$buatTugas++;
				}
			}

			foreach ($o_f_quiz as $c_openformquiz) {
				if($u->id == $c_openformquiz->created_by){
					$ids++;
					$o_f_quiz_count++;
				}
			}

			foreach ($o_f_tugas as $c_openformtugas) {
				if($u->id == $c_openformtugas->created_by){
					$ids++;
					$o_f_tugas_count++;
				}
			}

			foreach ($o_f_materi as $c_openformmateri) {
				if($u->id == $c_openformmateri->created_by){
					$ids++;
					$o_f_materi_count++;
				}
			}

			//echo $u->id." = ".$ids."<br>";

			if($ids == 1){
				$satuKali++;
			}

			if($ids == 2){
				$duaKali++;
			}

			if($ids == 3){
				$tigaKali++;
			}

			if($ids > 3){
				$lebihTiga++;
			}
		}

		/*$hash = '$2a$08$8fA6vZ9RtalAuVN87EgNheTLYzbpe5cg82QZmViHFCLn.7.wdmI3y';

		if (password_verify('17@gustus45', $hash)) {
		    echo 'Password is valid!';
		} else {
		    echo 'Invalid password.';
		}*/
	?>
	<form method="post">
		<div class="col-md-4">
			<div class="form-group">
				<label>Tanggal</label>
				<?php
				/*$this->widget('ext.YiiDateTimePicker.jqueryDateTime', array(
			        'model' => $model,
			        'attribute' => 'activity_type',
			        'options' => array(), //DateTimePicker options
			        'htmlOptions' => array(
			        	'class'=>'form-control'),
			    ));*/
				$this->widget('zii.widgets.jui.CJuiDatePicker',array(
				    'model' => $model,
				    'attribute' => 'activity_type',
				    // additional javascript options for the date picker plugin
				    'options'=>array(
				        'showAnim'=>'fadeIn',//'slide','fold','slideDown','fadeIn','blind','bounce','clip','drop'
				    	'dateFormat'=>'dd-mm-yy'
				    ),
				    'htmlOptions'=>array(
				        'class'=>'form-control',
				    ),
				));
			    ?>
				<?php //echo $form->error($model,'due_date'); ?>
			</div>
			<div class="form-group">
				<input type="submit" value="Cari" class="btn btn-primary">
			</div>
		</div>
	</form>
	<div>
		<?php
			$this->Widget('ext.highcharts.HighchartsWidget', array(
			   'options' => array(
			      'title' => array('text' => $hari),
			      'xAxis' => array(
			         'categories' => array('Login', 'Buka Form', 'Created')
			      ),
			      'yAxis' => array(
			         'title' => array('text' => 'Jumlah user')
			      ),
			      'series' => array(
			         array('name' => 'Ulangan', 'data' => array($hlogin, $o_f_quiz_count, $buatKuis)),
			         array('name' => 'Tugas', 'data' => array($hlogin, $o_f_tugas_count, $buatTugas)),
			         array('name' => 'Materi', 'data' => array($hlogin, $o_f_materi_count, $buatMateri))
			        // array('name' => 'John', 'data' => array(5, 7, 3))
			      )
			   )
			));
			// echo "</br>";
			// $this->Widget('ext.highcharts.HighchartsWidget', array(
			//    'options' => array(
			//       'title' => array('text' => 'Assignment Flow'),
			//       'xAxis' => array(
			//          'categories' => array('Login', 'Buka Form', 'Created')
			//       ),
			//       'yAxis' => array(
			//          'title' => array('text' => 'Jumlah user')
			//       ),
			//       'series' => array(
			//          array('name' => 'Today', 'data' => array($hlogin, 2, $buatTugas))
			//         // array('name' => 'John', 'data' => array(5, 7, 3))
			//       )
			//    )
			// ));
		?>
	</div>
	<!-- <table class="table table-bordered table-hover well">
		<tbody>
			<tr>
				<th>Aktivitas</th>
				<th>Total User</th>
			</tr>
			
			<tr>
				<td class="info">Login</td>
				<td class="info"><?php //echo number_format($hlogin,0,',','.');?></td>
			</tr>
			<tr>
				<td class="success">Membuat Materi</td>
				<td class="success"><?php //echo number_format($buatMateri,0,',','.');?></td>
			</tr>
			<tr>
				<td class="success">Membuat Tugas</td>
				<td class="success"><?php //echo number_format($buatTugas,0,',','.');?></td>
			</tr>
			<tr>
				<td class="success">Membuat Kuis</td>
				<td class="success"><?php //echo number_format($buatKuis,0,',','.');?></td>
			</tr>
			<tr>
				<td class="danger">Mengerjakan Tugas</td>
				<td class="danger"><?php //echo number_format($kerjakanTugas,0,',','.');?></td>
			</tr>
			<tr>
				<td class="danger">Mengerjakan Kuis</td>
				<td class="danger"><?php //echo number_format($kerjakanKuis,0,',','.');?></td>
			</tr>
		</tbody>
	</table> -->
	<!-- <p>Hasil :</p>
	<p> >3 Kali : <?php //echo $lebihTiga;?></p>
	<p>3 Kali :	<?php //echo $tigaKali;?></p>
	<p>2 Kali : <?php //echo $duaKali;?></p>
	<p>1 Kali : <?php //echo $satuKali;?></p>
	<p> >Aktivitas : <?php //echo $aktif;;?></p>
	<p> >Hanya Login : <?php //echo $hlogin;?></p>
	<p> >User : <?php //echo $user->totalItemCount;?></p> -->
</div>
