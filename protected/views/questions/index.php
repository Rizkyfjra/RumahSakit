<?php
/* @var $this QuestionsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Pertanyaan',
);

/*$this->menu=array(
	array('label'=>'Create Questions', 'url'=>array('create')),
	array('label'=>'Manage Questions', 'url'=>array('admin')),
);*/

$pil='';
$satu='';
$dua='';
$mpl=NULL;
$teks=NULL;

if(isset($_GET['pilihan'])){
	$pil = $_GET['pilihan'];
	switch ($pil) {
		case 1:
			$satu="selected";
			break;
		case 2:
			$dua="selected";
			break;
		default:
			$pil ='';
			break;
	}
}

if(isset($_GET['keyword'])){
	$teks = $_GET['keyword'];
}

if(isset($_GET['pelajaran'])){
	$mpl=$_GET['pelajaran'];
}

if(Yii::app()->user->YiiTeacher){
	$mapel = Lesson::model()->findAll(array('condition'=>'user_id = '.Yii::app()->user->id));
	$guru = User::model()->findAll(array('condition'=>'id = '.Yii::app()->user->id));
}else{
	$mapel = Lesson::model()->findAll();
	$guru = User::model()->findAll(array('condition'=>'role_id = 1'));
}
$lesson = array();
foreach ($mapel as $value) {
	if($value->moving_class == 1){
		$lesson[$value->id]=$value->name." (".$value->grade->name.")";
	}else{
		$lesson[$value->id]=$value->name." (".$value->class->name.")";
	}
}

$guruguru = array();
foreach ($guru as $valueguru) {
	$guruguru[$valueguru->id]=$valueguru->display_name;
}

?>

<h1>Daftar Pertanyaan</h1>
<div class="row">
		<div class="col-md-4">
			<p><b>Cari Pertanyaan</b></p>
			<?php 
				$filter_url = Yii::app()->createUrl("/questions/filter");
			?>

			<form action="<?php echo $filter_url;?>" method="get">
				<div class="form-group">
					<select class="form-control" name="pilihan" id="pil">
						<option value="0">--- Pilihan ---</option>
						<option value="1" <?php echo $satu;?> >Judul</option>
						<option value="2" <?php echo $dua;?> >Guru</option>
					</select>
				</div>
				<div class="form-group">
					<input id="teks" type="text" name="keyword" class="form-control" value="<?php echo $teks;?>">
				</div>
				<div class="form-group">
					<select class="form-control" name="pelajaran" id="mapel" >
						<?php
							echo "<option value=0>--- Pilihan --- </option>";
							foreach ($guruguru as $key => $value) {
							 	if($mpl == $key){
							 		echo "<option value=".$key." selected>".$value."</option>";
								}else{
									echo "<option value=".$key.">".$value."</option>";
								}
							} 
						?>
					</select>
				</div>
				<div class="form-group">
					<input type="submit" value="Cari" class="btn btn-primary">
				</div>

			</form>
		</div>
	</div>
<?php if(Yii::app()->user->YiiTeacher || Yii::app()->user->YiiAdmin){?>	
<p class="text-right"><?php echo CHtml::link('Import Soal XML', array('questions/bulkxml'),array('class'=>'btn btn-info'))?> <?php echo CHtml::link('Tambah Pertanyaan', array('questions/create'),array('class'=>'btn btn-success'))?></p>
<?php } ?>
<div class="row">
	<div class="col-md-12">
		<?php 
				$format_url = Yii::app()->createUrl("/questions/deleteQuestion");
		?>
		<form action="<?php echo $format_url;?>" method="post" onsubmit="return confirm('Yakin Menghapus Soal?');"> 
			<p class="text-right"><input type="submit" value="Hapus" class="btn btn-danger"></p>
		<table class="table table-hover table-responsive">
			<tr class="danger">
			<th>Kompetensi Dasar</th>
			<th>Guru</th>
			<th>Tipe Pertanyaan</th>
			<!--<th>Pelajaran</th> -->
			 <th class="text-center">
				<br>
				<input type="checkbox" id="selectAll">
			</th>
			</tr>
			<?php if(!empty($dataProvider->getData())){ ?>
				<?php foreach ($dataProvider->getData() as $value) { ?>
					<tr class="active">
						<td><?php echo CHtml::link($value->title, array('view','id'=>$value->id));?></td>
						<td>
							<?php
								if(!empty($value->teacher->display_name)){ 
									echo $value->teacher->display_name;
								}
							?>
						</td>
						<td>
							<?php
								if(!empty($value->type == 1)){
									echo "Isian";
								}elseif($value->type == 2){
									echo "Essay";
								}else{
									echo "Pilihan Ganda";
								}
							?>
						</td>
						<!-- <td>
							<?php 
								//if(!empty($lesson_id)){
								//	echo ucfirst($value->lesson->name);
								//}
							?>
						</td> -->
						<td class="text-center"><input type="checkbox" class="soal" name="soal[]" value="<?php echo $value->id;?>"></td>
					</tr>
				<?php }?>
			<?php } ?>	
		</table>
		</form>
		<div class="text-center">
			<?php
			  $this->widget('CLinkPager', array(
			                'pages'=>$dataProvider->pagination,
			                ));
			?>
		</div>
	</div>
</div>
<script type="text/javascript">
	var ck = "<?php echo $mpl;?>";
	console.log(ck);

    $('#selectAll').click(function(event) {  //on click 
        if(this.checked) { // check select status
            $('.soal').each(function() { //loop through each checkbox
                this.checked = true;  //select all checkboxes with class "checkbox1"               
            });
        }else{
            $('.soal').each(function() { //loop through each checkbox
                this.checked = false; //deselect all checkboxes with class "checkbox1"                       
            });         
        }
    });
    

	if(ck == 0){
		$("#mapel").hide();
		$("#teks").show();
	}else{
		$("#mapel").show();
		$("#teks").hide();
	}

	$("#pil").change(function (){
		if($(this).val() == "2"){
			$("#mapel").show();
			$("#teks").val("");
			$("#teks").hide();
		}else{
			$("#mapel").hide();
			$('#mapel option:eq(0)').attr('selected','selected');
			$("#teks").show();
		}
	});
</script>