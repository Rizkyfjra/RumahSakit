<b>C. Pengetahuan dan Keterampilan</b>
	<table border="5" style="width:100%;border: 2px solid black;">
		<tr>
			<th rowspan="2" align="center" width="5%">No.</th>
			<th rowspan="2" align="center" width="30%">Mata Pelajaran</th>
			
			<th colspan="2" align="center">Pengetahuan</th>
			<th colspan="2" align="center">Keterampilan</th>
		</tr>
		<tr>
			<th width="10%" align="center">Nilai</th>
			<th align="center">Predikat</th>
			<th align="center">Deskripsi</th>
			
		</tr>
		<tr>
			<td colspan="7"><b>Kelompok A (Umum)</b></td>
		</tr>
		<?php
			$no = 1;
			if(!empty($peluas1)){
				foreach ($peluas1 as $key => $rowpeluas1) {
					if(!empty($rowpeluas1['nilai-keterampilan']) && !empty($rowpeluas1['nilai-keterampilan'])){
						
						if(!empty($rowpeluas1['nilai-keterampilan'])){
							if($rowpeluas1['nilai-keterampilan']<75){
								$rowpeluas1['p-nk'] = "D";
								$peluas1[$key]['p-nk'] = "D";
							}elseif($rowpeluas1['nilai-keterampilan']>=75 && $rowpeluas1['nilai-keterampilan']<84){
								$rowpeluas1['p-nk'] = "C";
								$peluas1[$key]['p-nk'] = "C";
							}elseif($rowpeluas1['nilai-keterampilan']>=84 && $rowpeluas1['nilai-keterampilan']<93){
								$rowpeluas1['p-nk'] = "B";
								$peluas1[$key]['p-nk'] = "B";
							}elseif($rowpeluas1['nilai-keterampilan']>=93 && $rowpeluas1['nilai-keterampilan']<=100){
								$rowpeluas1['p-nk'] = "A";
								$peluas1[$key]['p-nk'] = "A";
							}else{
								$rowpeluas1['p-nk'] = "-";
								$peluas1[$key]['p-nk'] = "-";
							}
						}

						if($rowpeluas1['kelompok']==1){
		?>
		<tr>
			<td align="center" style="padding-top:2px;padding-bottom:2px;"><?php echo $no; ?></td>
			<td><?php echo $rowpeluas1['name']; ?></td>
			
			<?php if(!empty($rowpeluas1['nilai-keterampilan']) && $rowpeluas1['nilai-keterampilan']!="-") { ?>
				<td align="center"><?php echo $rowpeluas1['nilai-keterampilan']; ?></td>
			<?php }else{ ?>
				<td align="center">-</td>
			<?php } ?>
			<?php if(!empty($rowpeluas1['p-np']) && $rowpeluas1['p-np']!="-") { ?>
				<td align="center"><?php echo $rowpeluas1['p-np']; ?></td>
			<?php }else{ ?>
				<td align="center">-</td>
			<?php } ?>
			
<?php if(!empty($rowpeluas1['desc-desc_keterampilan']) && $rowpeluas1['desc-desc_keterampilan']!="-") { ?>
				<td><?php echo $rowpeluas1['desc-desc_keterampilan']; ?></td>
			<?php }else{ ?>
				<?php if(!empty($rowpeluas1['nilai-kddescription']) && $rowpeluas1['nilai-kddescription']!="-") { ?>
				<td align="left">
					<?php 
					if (!empty($rowpeluas1['p-nk']) && $rowpeluas1['p-nk']=="A") {
						echo "Sangat terampil ".strtolower(implode(",", $rowpeluas1['nilai-kddescription']))."";
					} else if(!empty($rowpeluas1['p-nk']) && $rowpeluas1['p-nk']=="B"){
						echo "Terampil ".strtolower(implode(",", $rowpeluas1['nilai-kddescription']))."";
					} else if(!empty($rowpeluas1['p-nk']) && $rowpeluas1['p-nk']=="C"){
						echo "Cukup terampil ".strtolower(implode(",", $rowpeluas1['nilai-kddescription']))."";
					} else if(!empty($rowpeluas1['p-nk']) && $rowpeluas1['p-nk']=="D"){
						echo "Kurang terampil ".strtolower(implode(",", $rowpeluas1['nilai-kddescription']))."";
					}?>
				</td>
				<?php } else {?>
				<td align="center">-</td>
				<?php }?>
			<?php } ?>
		</tr>
		<?php
							$no++;
						}
					}
				}
			}
		?>
		<tr>
			<td colspan="7"><b>Kelompok B (Umum)</b></td>
		</tr>
		<?php
			$no = 1;
			if(!empty($peluas1)){
				foreach ($peluas1 as $key => $rowpeluas1) {
					if(!empty($rowpeluas1['nilai-keterampilan']) && !empty($rowpeluas1['nilai-keterampilan'])){
						
						if(!empty($rowpeluas1['nilai-keterampilan'])){
							if($rowpeluas1['nilai-keterampilan']<75){
								$rowpeluas1['p-nk'] = "D";
								$peluas1[$key]['p-nK'] = "D";
							}elseif($rowpeluas1['nilai-keterampilan']>=75 && $rowpeluas1['nilai-keterampilan']<84){
								$rowpeluas1['p-nk'] = "C";
								$peluas1[$key]['p-nK'] = "C";
							}elseif($rowpeluas1['nilai-keterampilan']>=84 && $rowpeluas1['nilai-keterampilan']<93){
								$rowpeluas1['p-nk'] = "B";
								$peluas1[$key]['p-nK'] = "B";
							}elseif($rowpeluas1['nilai-keterampilan']>=93 && $rowpeluas1['nilai-keterampilan']<=100){
								$rowpeluas1['p-nk'] = "A";
								$peluas1[$key]['p-nK'] = "A";
							}else{
								$rowpeluas1['p-nk'] = "-";
								$peluas1[$key]['p-nK'] = "-";
							}
						}

						if($rowpeluas1['kelompok']==2){
		?>
		<tr>
			<td align="center" style="padding-top:2px;padding-bottom:2px;"><?php echo $no; ?></td>
			<td><?php echo $rowpeluas1['name']; ?></td>
			
			<?php if(!empty($rowpeluas1['nilai-keterampilan']) && $rowpeluas1['nilai-keterampilan']!="-") { ?>
				<td align="center"><?php echo $rowpeluas1['nilai-keterampilan']; ?></td>
			<?php }else{ ?>
				<td align="center">-</td>
			<?php } ?>
			<?php if(!empty($rowpeluas1['p-np']) && $rowpeluas1['p-np']!="-") { ?>
				<td align="center"><?php echo $rowpeluas1['p-np']; ?></td>
			<?php }else{ ?>
				<td align="center">-</td>
			<?php } ?>
			
	<?php if(!empty($rowpeluas1['desc-desc_keterampilan']) && $rowpeluas1['desc-desc_keterampilan']!="-") { ?>
				<td><?php echo $rowpeluas1['desc-desc_keterampilan']; ?></td>
			<?php }else{ ?>
				<?php if(!empty($rowpeluas1['nilai-kddescription']) && $rowpeluas1['nilai-kddescription']!="-") { ?>
				<td align="left">
					<?php 
					if (!empty($rowpeluas1['p-nk']) && $rowpeluas1['p-nk']=="A") {
						echo "Sangat terampil ".strtolower(implode(",", $rowpeluas1['nilai-kddescription']))."";
					} else if(!empty($rowpeluas1['p-nk']) && $rowpeluas1['p-nk']=="B"){
						echo "Terampil ".strtolower(implode(",", $rowpeluas1['nilai-kddescription']))."";
					} else if(!empty($rowpeluas1['p-nk']) && $rowpeluas1['p-nk']=="C"){
						echo "Cukup terampil ".strtolower(implode(",", $rowpeluas1['nilai-kddescription']))."";
					} else if(!empty($rowpeluas1['p-nk']) && $rowpeluas1['p-nk']=="D"){
						echo "Kurang terampil ".strtolower(implode(",", $rowpeluas1['nilai-kddescription']))."";
					}?>
				</td>
				<?php } else {?>
				<td align="center">-</td>
				<?php }?>
			<?php } ?>
		</tr>
		<?php
							$no++;
						}
					}
				}
			}
		?>
		<tr>
			<td colspan="7"><b>Kelompok C (Peminatan)</b></td>
		</tr>
		<?php
			$no = 1;
			if(!empty($peluas1)){
				foreach ($peluas1 as $key => $rowpeluas1) {
					if(!empty($rowpeluas1['nilai-keterampilan']) && !empty($rowpeluas1['nilai-keterampilan'])){
						
						if(!empty($rowpeluas1['nilai-keterampilan'])){
							if($rowpeluas1['nilai-keterampilan']<75){
								$rowpeluas1['p-nk'] = "D";
								$peluas1[$key]['p-nk'] = "D";
							}elseif($rowpeluas1['nilai-keterampilan']>=75 && $rowpeluas1['nilai-keterampilan']<84){
								$rowpeluas1['p-nk'] = "C";
								$peluas1[$key]['p-nk'] = "C";
							}elseif($rowpeluas1['nilai-keterampilan']>=84 && $rowpeluas1['nilai-keterampilan']<93){
								$rowpeluas1['p-nk'] = "B";
								$peluas1[$key]['p-nk'] = "B";
							}elseif($rowpeluas1['nilai-keterampilan']>=93 && $rowpeluas1['nilai-keterampilan']<=100){
								$rowpeluas1['p-nk'] = "A";
								$peluas1[$key]['p-nk'] = "A";
							}else{
								$rowpeluas1['p-nk'] = "-";
								$peluas1[$key]['p-nk'] = "-";
							}
						}

						if($rowpeluas1['kelompok']==3){
							if(strpos(strtolower($rowpeluas1['name']), "peminatan") !== false){
								$tmp_nama = explode(" ", $rowpeluas1['name']);
								$tmp_nama_count = count($tmp_nama);

								$rowpeluas1['name'] = "";
								for($i=0; $i<$tmp_nama_count; $i++){
									if(strtolower($tmp_nama[$i]) != "peminatan"){
										$rowpeluas1['name'] = $rowpeluas1['name']." ".$tmp_nama[$i];
									}
								}
							}elseif(strpos(strtolower($rowpeluas1['name']), "lintas minat") !== false){
								$tmp_nama = explode(" ", $rowpeluas1['name']);
								$tmp_nama_count = count($tmp_nama);

								$rowpeluas1['name'] = "";
								for($i=0; $i<$tmp_nama_count; $i++){
									if(strtolower($tmp_nama[$i]) != "lintas"){
										if(!empty($tmp_nama[$i+1])){
											if(strtolower($tmp_nama[$i+1]) != "minat"){
												$rowpeluas1['name'] = $rowpeluas1['name']." ".$tmp_nama[$i+1];
											}
										}
									}
								}
								$rowpeluas1['name'] = $rowpeluas1['name']." "."(Lintas Minat)";
							}
		?>
		<tr>
			<td align="center" style="padding-top:2px;padding-bottom:2px;"><?php echo $no; ?></td>
			<td><?php echo $rowpeluas1['name']; ?></td>
			
			<?php if(!empty($rowpeluas1['nilai-keterampilan']) && $rowpeluas1['nilai-keterampilan']!="-") { ?>
				<td align="center"><?php echo $rowpeluas1['nilai-keterampilan']; ?></td>
			<?php }else{ ?>
				<td align="center">-</td>
			<?php } ?>
			<?php if(!empty($rowpeluas1['p-np']) && $rowpeluas1['p-np']!="-") { ?>
				<td align="center"><?php echo $rowpeluas1['p-np']; ?></td>
			<?php }else{ ?>
				<td align="center">-</td>
			<?php } ?>
			
	<?php if(!empty($rowpeluas1['desc-desc_keterampilan']) && $rowpeluas1['desc-desc_keterampilan']!="-") { ?>
				<td><?php echo $rowpeluas1['desc-desc_keterampilan']; ?></td>
			<?php }else{ ?>
				<?php if(!empty($rowpeluas1['nilai-kddescription']) && $rowpeluas1['nilai-kddescription']!="-") { ?>
				<td align="left">
					<?php 
					if (!empty($rowpeluas1['p-nk']) && $rowpeluas1['p-nk']=="A") {
						echo "Sangat terampil ".strtolower(implode(",", $rowpeluas1['nilai-kddescription']))."";
					} else if(!empty($rowpeluas1['p-nk']) && $rowpeluas1['p-nk']=="B"){
						echo "Terampil ".strtolower(implode(",", $rowpeluas1['nilai-kddescription']))."";
					} else if(!empty($rowpeluas1['p-nk']) && $rowpeluas1['p-nk']=="C"){
						echo "Cukup terampil ".strtolower(implode(",", $rowpeluas1['nilai-kddescription']))."";
					} else if(!empty($rowpeluas1['p-nk']) && $rowpeluas1['p-nk']=="D"){
						echo "Kurang terampil ".strtolower(implode(",", $rowpeluas1['nilai-kddescription']))."";
					}?>
				</td>
				<?php } else {?>
				<td align="center">-</td>
				<?php }?>
			<?php } ?>
		</tr>
		<?php
							$no++;
						}
					}
				}
			}
		?>
	</table>
	<br/>
	<p>
				<b>Tabel Interval Predikat</b>
				<table border="5" style="width:100%;border: 2px solid black;">
					<tr valign="center">
						<th rowspan="2" align="center">KKM</th>
						<th colspan="4" align="center">Predikat</th>
					</tr>
					<tr valign="center">
						<th width="20%" align="center">D = Kurang</th>
						<th width="20%" align="center">C = Cukup</th>
						<th width="20%" align="center">B = Baik</th>
						<th width="20%" align="center">A = Sangat Baik</th>
					</tr>
					<tr>
						
						<td align="center">&#060; 75</td>
						<td align="center">75 - 83</td>
						<td align="center">84 - 92</td>
						<td align="center">93 - 100</td>
					</tr>
				</table>
			</p>

	<table border="0" style="width:100%;border: 0px solid black;margin-top: 0.5px;margin-bottom: 0.5px;">
		<tr>
			<td width="30%">
				<p class="text-center">
					Mengetahui<br/>
					Orang Tua/Wali
					<br>
					<br>
					<br>
					<br>
					<br>
					................................
				</p>
			</td>
			<td width="30%">
				<p class="text-center">
					<br/>
					Kepala Sekolah
					<br>
					<br>
					<br>
					<br>
					<br>
					<b><?php echo $kepsek;?></b>
					<br>
					<b>NIP. <?php echo $nip = str_replace('-', ' ', $nik);?></b>
				</p>
			</td>
			<td width="30%">
				<p class="text-center">
					Bandung, <?php echo(DateToIndo(date('2016-12-24')));?><br/>
					Wali Kelas
					<br>
					<br>
					<br>
					<br>
					<br>
					<b>
						<?php
							if(!empty($model->teacher_id)){
								echo $model->teacher->display_name;
							}

						?>
					</b>
					<br>
					<b>NIP.
						<?php
							if(!empty($model->teacher_id)){
								echo $nip = str_replace('-', ' ', $model->teacher->username);
							}
						?>
					</b>
				</p>
			</td>
		</tr>
	</table>