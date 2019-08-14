<?php
	$nama_sekolah = Option::model()->findAll(array('condition'=>'key_config LIKE "%school_name%"'));
	$kota_kabupaten = Option::model()->findAll(array('condition'=>'key_config LIKE "%kota_kabupaten%"'));	
	$alamat_sekolah = Option::model()->findAll(array('condition'=>'key_config LIKE "%school_address%"'));
	$kepala_sekolah = Option::model()->findAll(array('condition'=>'key_config LIKE "%kepsek_id%"'));

	$semester_name = array('GANJIL', 'GENAP');
    $sc_name = "PINISI SCHOOL";
    if(!empty($nama_sekolah)){
        if(!empty($nama_sekolah[0]->value)){
    		$sc_name = strtoupper($nama_sekolah[0]->value);
        }
    }


    if(!empty($kepala_sekolah) and !empty($kepala_sekolah[0]->value)){
		$user_kepsek = User::model()->findByPk($kepala_sekolah[0]->value);
		$kepsek = $user_kepsek->display_name;
		$nik = $user_kepsek->username;
	}else{
		$kepsek = "Kepala Sekolah";
		$nik = "022";
	}

    // echo "<pre>";
    // 	print_r($model);
    // echo "</pre>";
    ?>
<style>
	.print-out{
		font-size: 11px;
        font-family: 'Times New Roman'
	}
	.page {
		border: 1px solid #ddd;
		width: 49%;
		float:left;
		height: 500px;
		padding: 5px;
		margin: 3px
	}
    h2{
        margin-top: 0px;
    }
</style>
<div class="row print-out">
	<div class="col-md-10">
    	<div class="col-card">
    		<div class="row">
	    		<div class="col-md-6 page">
		    			<!-- <div class="text-center">
		    			<table>
		    				<tr>
		    					<td width="15%">
		    						
		    						<img src="<?php //echo Yii::app()->baseUrl.'/images/kemenag.png' ?>" width="100%">
		    					</td>
		    					<td>
		    						<?php 
		    						// echo strtoupper(@Option::model()->findByAttributes(array('key_config' => 'cop'))->value;)?> 
		    						
				            		Kementrian Agama Kab. Lumajang<br />
						            <big><?php //echo $sc_name; ?></big><br />
						            <small>
						            	<?php 
											// if(!empty($alamat_sekolah[0]->value)){ 
											// 	echo $alamat_sekolah[0]->value;
											// }	
										?>
						            </small>
		    					</td>
		    					<td width="15%">
		    					&nbsp;</td>
		    				</tr>
		    			</table>
			            </div> -->
		            	<table class="table">
		            	<tr>
		            		<td class="text-center">
			            		<h3>KARTU PESERTA</h3>
			            		<?php echo strtoupper($model->title); ?><br />
			            		TAHUN PELAJARAN <?php echo $year-1 . "/" . $year ;?><br />
		            		</td>
		            	</tr>
		            	<tr>
		            		<td>
		            			<div class="text-center">
		            				<h2>
		            					<small>Nama Peserta</small><br />
		            					<?php echo $siswa->display_name; ?>
		            				</h2>
		            				<table class="no-style text-left">
		            					<tr>
		            						<td width="50%">No Peserta</td>
		            						<td>: <?php echo $siswa->username; ?></td>
		            					</tr>
		            					<!-- <tr>
		            						<td>Kelamin</td>
		            						<td>: Laki-laki</td>
		            					</tr> -->
		            					<tr>
		            						<td>Kelas</td>
		            						<td>: <?php echo $siswa->class->name; ?></td>
		            					</tr>
		            					<tr>
		            						<td>Username</td>
		            						<td>: <?php echo $siswa->email; ?></td>
		            					</tr>
		            					<tr>
		            						<td>Password</td>
		            						<td>: <?php echo $siswa->reset_password; ?></td>
		            					</tr>
		            					<tr>
		            						<td>Ruang</td>
		            						<td>: R. <?php echo $ruang->detail->no_room; ?></td>
		            					</tr>
		            				</table>
		            				<br />
		            				<table class="no-style pull-right text-left">
		            					<tr>
		            						<td>
		            							<?php if (!empty($kota_kabupaten[0]->value)) {
			            								echo $kota_kabupaten[0]->value . '<br />';
                                            	} ?>
		            							Kepala Sekolah
		            							<br />
		            							<br />
		            							<br />
		            							<?php if (!empty($kepala_sekolah[0]->value)) {
			            								echo $kepsek . '<br />';
		            								echo $nik;
                                            	} ?>
		            						</td>
		            					</tr>
		            				</table>
		            			</div>
		            		</td>
		            	</tr>
		    			</table>
	    		</div>
	    		<div class="col-md-6 page">
		    			<div class="text-center">
				            <?php echo strtoupper($model->title); ?><br />
							SEMESTER <?php echo $semester_name[$semester-1];?> TAHUN PELAJARAN <?php echo $year-1 . "/" . $year ;?>
							<br />
							<br />
			            </div>
		            	<table class="table">
			            	<thead>
				            	<tr>
				            		<th>No.</th>
				            		<th>Hari, Tanggal</th>
				            		<th>Waktu</th>
				            		<th>Mata Pelajaran</th>
				            	</tr>
			            	</thead>
			            	<tbody>
			            	<?php foreach ($jadwal as $key => $jdwl) { ?>
			            		<tr>
				            		<td><?php echo $key+1 ?>.</td>
				            		<td><?php echo $jdwl['date'] ?></td>
				            		<td>
				            			<table>
				            				<?php 
				            				 if (!empty($jdwl['lesson_time'])) {
					            				 foreach (json_decode($jdwl['lesson_time']) as $time) {
	                                            		$waktu = explode("-", $time);
	                                        	    	if (count($waktu)==2) {
	                                        	    		echo "<tr>";
		                                        	    	echo "<td>" . $waktu[0] . "-</td>";
		                                        	    	echo "<td>" . $waktu[1] . "</td>";
		                                        	    	echo "</tr>";
	                                        	    	}
	                                        	    	
	                                        	}    	
                                       		 }?>
				            			</table>
				            		</td>
				            		<td>
				            			<table>
				            			<?php 
				            				foreach (json_decode($jdwl['lesson_id']) as $lesson) {
                                            	$lessons = LessonList::model()->find(array(
                                               		'select' => 'name',
                                                	'condition' => 'id = "' . $lesson . '"'));
                                        	    	echo "<tr><td>" . $lessons->name . "</td></tr>";
                                        }?>
				            			</table>
				            		</td>
				            	</tr>
				            <?php } ?>
			            	</tbody>
		            	</table>
	    		</div>
    		</div>		
    	</div>
    </div>
    <div class="col-md-2">
    	<div class="col-card">
    		<button data-toggle="print" data-target=".print-out" class="btn btn-block no-print"><i class="fa fa-print"></i> Cetak</button>
    		<a href="javascript:history.back()" class="btn btn-danger btn-block no-print" ><i class="fa fa-arrow-left"></i> Kembali</a>
    	</div>
    </div>
</div>