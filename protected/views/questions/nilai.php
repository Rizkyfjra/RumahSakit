<div class="container">
	<h3>Update Nilai</h3>
	<div class="col-md-6 well">
		<form method="get">
		<div class="form-group">	
			<label>Nama Ulangan</label>
			<input type="text" name="quiz" class="form-control" required>
		</div>
		<!-- <div class="form-group">
			<label>Kondisi</label>
			<input type="text" name="kondisi" class="form-control" required>
		</div> -->

		<div class="form-group">
			<input type="submit" class="btn btn-success" value="Cari">
		</div>	
		</form>
	</div>
	<?php if(!empty($model)){ ?>
		<table class="table table-hover table-bordered">
			<th>No</th>
			<th>Nama Siswa</th>
			<th>Nama Kuis</th>
			<th>Nilai</th>
			<!-- <th>Bulk</th> -->
			<?php $no = 1;?>
			<?php foreach ($model as $value) { ?>
				<tr>
					<td><?php echo $no;?></td>
					<td>
						<?php
							if(!empty($value->student_id)){ 
								echo $value->user->display_name;
							}
						?>
					</td>
					<td>
						<?php
							if(!empty($value->quiz_id)){ 
								echo $value->quiz->title;
							}
						?>
					</td>
					<td><?php echo $value->score;?></td>
					<!-- <td><input type="checkbox" value="<?php //echo $value->id?>" name="hasil[]"></td> -->
				</tr>
				<?php $no++;?>
			<?php }?>
		</table>
		<div class="text-center">
		<?php
		  $this->widget('CLinkPager', array(
		                'pages'=>$pages,
		                'maxButtonCount'=>5,
		                ));
		?>
		</div>
	<?php } ?>
</div>