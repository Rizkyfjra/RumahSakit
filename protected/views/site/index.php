
<?php if(Yii::app()->user->isGuest) { 		
	//echo $_SERVER['SERVER_ADDR'];
	$nama_sekolah = Option::model()->findAll(array('condition'=>'key_config LIKE "%school_name%"'));
	$sc_name = "PINISI SCHOOL";
	if(!empty($nama_sekolah)){
		if(!empty($nama_sekolah[0]->value)){
			$sc_name = strtoupper($nama_sekolah[0]->value);
		}
	}
?>

<div class="jumbotron medidu-jumbotron">
	<div class="container">
			<div class="row text-right"><?php echo CHtml::link('Login', array('/site/login'),array('class'=>'btn btn-lg btn-primary visible-xs'));?></div>
			<div class="row">			
				<div class="col-md-8">
					<h1>Selamat Datang</h1>
					<h2><?php echo $sc_name; ?></h2>						
				</div>
				<div class="col-md-4">					
					<div class="panel panel-medidu">
						<div class="panel-heading">Pengumuman</div>

						<div class="panel-body">
							<ul>
						  	<?php $this->widget('booster.widgets.TbListView', array(
				                'dataProvider'=>$berita,
				                'itemView'=>'/announcements/_view',
				                'summaryText'=>'',
				            )); ?>
				            </ul>
						</div>
					</div>
				</div>				
			</div>
			<div class="row">
			<?php //if($_SERVER['HTTP_HOST'] == "pinisi.co"){ ?>
				<img src="<?php echo Yii::app()->theme->baseUrl;?>/img/home.png" alt="" class="img-responsive" >
			<?php //}else{ ?>
				<!-- <img src="<?php //echo Yii::app()->theme->baseUrl;?>/img/home1.png" alt="" class="img-responsive" >  -->
			<?php //} ?>
			</div>
		  
			
		</div>
		<!-- <h1>Selamat Datang di SMP Negeri 1 Cangkuang </h1>
		<h1><?php //echo CHtml::encode(Yii::app()->name); ?></h1>
		  <img src="<?php //echo Yii::app()->theme->baseUrl;?>/images/MIMHa Logo.png" class="thumnbnails" height="300px">
		  <br>
		   <p>E-Learning MTs Miftahul Huda
		  </p> 
		  <p> 
		  	Jl. Sidomukti No 29 Kel Sukaluyu Kec Cibeunying Kaler Kota Bandung 
		  	Jl. Cikadut No. 252 A.H Nasution Kec Mandalajati Kota Bandung 40194 Telp. (022) 7237649
		  </p>-->
</div>


<?php } else { ?>   
<?php
/*echo "<pre>";
print_r($_SESSION);
echo "</pre>";*/
$this->pageTitle=Yii::app()->name.' - Dashboard';
$this->breadcrumbs=array(
  'Dashboard',
);
/*$style = <<<CSS
.panel-body {
	min-height: 300px;
}
CSS;
Yii::app()->clientscript->registerCSS('fixpanelheight', $style);

$asgColumns = array(
	'id',
	array(
		'header' => 'Nama Tugas',
		'type' => 'raw',
		'value' => 'CHtml::link($data->title,Yii::app()->createUrl("assignment/view", array("id"=>$data->id)))',
		),
	// array(
	// 	'header' => 'Konten',
	// 	'name' => 'content',
	// 	),
	array(
		'header' => 'Pelajaran',
		'name' => 'lesson_id',
		'value' => '$data->lesson->name',
		),
	array(
		'header' => 'Kelas',
		'name' => 'lesson_id',
		'value' => '$data->lesson->class->name',
		),
	array(
		'header' => 'Batas Pengumpulan',
		'name' => 'due_date',
		),
	array(
		'header' => 'Status',
		'type' => 'raw',
		'value' => function($data){
			// $id = $data->id;
			$current_user = Yii::app()->user->id;
			$assignment_id = $data->id;
			$modelCek=StudentAssignment::model()->findAll(array("condition"=>"assignment_id = $assignment_id and student_id = $current_user"));
			if (!empty($modelCek)){
				$status = "<span class='glyphicon glyphicon-ok'></span>";
			} else {
				$status = "<span class='glyphicon glyphicon-remove'></span>";
			}
			return $status;

		}
		),
	);

$scoreColumns = array(
	'id',
	array(
		'header' => 'Nama Tugas',
		'name' => 'title',
		'type'=>'raw',
		'value'=>'CHtml::link("$data->title", array("/studentAssignment/view","id"=>$data->id))'
		),
	array(
		'header' => 'Nilai',
		'name' => 'score',
		),
	);

$newsColumns = array(
	'id',
	array(
		'header' => 'Nama Pengumuman',
		'name' => '$data->title',
		'type'=>'raw',
		'value'=>'CHtml::link("$data->title", array("/announcements/view","id"=>$data->id))'
		),
	);*/

$style = <<<CSS

CSS;

Yii::app()->clientscript->registerCSS('fixtimeline', $style);
//Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/jquery.jscroll.min.js',CClientScript::POS_HEAD);

$activity = '';
$late = '';
$upcoming = '';
$nilai = '';
$kuis = '';

if(Yii::app()->user->YiiStudent){
	switch ($role) {
	case "activity":
			$activity = 'active';
		break;
		case "late":
			$late = 'active';
		break;
		case "upcoming":
			$upcoming = 'active';
		break;
		case "nilai":
			$nilai = 'active';
		break;
		case "kuis":
			$kuis = 'active';
		break;

		default:
			$upcoming = 'active';
	}
}elseif(Yii::app()->user->YiiTeacher){
	switch ($role) {
		case "activity":
				$activity = 'active';
			break;
		case "late":
			$late = 'active';
		break;
		case "upcoming":
			$upcoming = 'active';
		break;
		case "nilai":
			$nilai = 'active';
		break;

		default:
			$late = 'active';
	}
}

// $nama_sekolah = Option::model()->findAll(array('condition'=>'key_config LIKE "%school_name%"'));
// $kepala_sekolah = Option::model()->findAll(array('condition'=>'key_config LIKE "%kepsek_id%"'));
// $alamat_sekolah = Option::model()->findAll(array('condition'=>'key_config LIKE "%school_address%"'));
// $kurikulum_sekolah = Option::model()->findAll(array('condition'=>'key_config LIKE "%kurikulum%"'));
$fiturUlangan = Option::model()->findAll(array('condition'=>'key_config LIKE "%fitur_ulangan%"'));
$fiturTugas = Option::model()->findAll(array('condition'=>'key_config LIKE "%fitur_tugas%"'));
$fiturMateri = Option::model()->findAll(array('condition'=>'key_config LIKE "%fitur_materi%"'));
$fiturRekap = Option::model()->findAll(array('condition'=>'key_config LIKE "%fitur_rekap%"'));

?>
<?php if(!Yii::app()->user->isGuest){ ?>
<div id="notif-flash">
	<div id="alrt"class="alert alert-warning hidden" role="alert">Masih ada data ulangan yang tersisa <a href="#" id="lanjut"><h1>LANJUT MENGERJAKANNNN</h1></a> atau <a href="" id="hapusData">Batal Mengerjakan</a></div>
</div>
<?php } ?>

<div class="page-header">
	<div class="text-center">
		<!-- <div class="bs-example bs-example-popover" data-example-id="static-popovers"> 
			<div class="popover top"> 
				<div class="arrow"></div> 
				<h3 class="popover-title">Popover top</h3> 
				<div class="popover-content"> 
					<p>Sed posuere consectetur est at lobortis. Aenean eu leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum.</p> 
				</div> 
			</div> 
		</div>  -->
	</div>
	<!-- <img src="<?php //echo Yii::app()->baseUrl;?>/images/ajax-loader.gif" id="loading-indicator" style="display:none" /> -->
	<!-- <h1>Selamat Datang di <?php //echo CHtml::encode(Yii::app()->name); ?></h1> -->
	</div>
	<?php if(!Yii::app()->user->YiiStudent){ ?>
	 <h4>
		
		<?php //if(Yii::app()->user->YiiTeacher){ ?>
			<button type="button" class="btn btn-primary btn-responsive" data-toggle="modal" data-target="#penilaianModal">
			  Buat Penilaian <i class="fa fa-clipboard"></i>
			</button>
			<?php 
				//if(empty($fiturMateri) || $fiturMateri[0]->value != 2){
					//echo CHtml::link('Buat Materi <i class="fa fa-plus"></i>', array('/chapters/create'),array('class'=>'btn btn-info btn-responsive'));
				//}
			?> 
			<?php //echo CHtml::link('Buat Penilaian <i class="fa fa-plus"></i>', "#penilaian",array('class'=>'btn btn-warning btn-responsive'));?> 
			<?php //echo CHtml::link('Buat Ulangan <i class="fa fa-plus"></i>', array('/quiz/create'),array('class'=>'btn  btn-info btn-responsive'));?>
			<?php
				//if(empty($fiturUlangan) || $fiturUlangan[0]->value != 2){ 
					//echo CHtml::link('Bank Soal <i class="fa fa-folder"></i>', array('/questions'),array('class'=>'btn btn-info btn-responsive'));
				//}
			?> 
			<?php 
				//if(empty($fiturRekap) || $fiturRekap[0]->value != 2){
					//echo CHtml::link('Rekap Nilai <i class="fa fa-graduation-cap"></i>', array('/lesson/raport'),array('class'=>'btn btn-success btn-lg'));
				//}
			?> 
		<?php //}else{ ?>
			<?php //if(!Yii::app()->user->YiiWali){ ?> 
				<?php //echo CHtml::link('Buat Pengumuman <i class="fa fa-plus"></i>', array('/announcements/create'),array('class'=>'btn btn-danger btn-responsive'));?> 
				<?php 
					//if(empty($fiturUlangan) || $fiturUlangan[0]->value != 2){ 
						//echo CHtml::link('Bank Soal <i class="fa fa-folder"></i>', array('/questions'),array('class'=>'btn btn-info btn-responsive'));
					//}
				?> 
			<?php //} ?>
			<?php //echo CHtml::link('Log Info <i class="fa fa-info-circle"></i>', array('site/log'),array('class'=>'btn btn-warning'));?>
			<?php //echo CHtml::link('Setting <i class="fa fa-cogs"></i>', array('/option'),array('class'=>'btn btn-info'));?>
			<?php
				if(Yii::app()->user->YiiAdmin){ 
					if(empty($fiturRekap) || $fiturRekap[0]->value != 2){
						echo CHtml::link('Buat Pengumuman <i class="fa fa-plus"></i>', array('/announcements/create'),array('class'=>'btn btn-info btn-responsive'));
						echo CHtml::link('Tambah Pelajaran <i class="fa fa-plus"></i>', array('/lesson/create'),array('class'=>'btn btn-danger btn-responsive'));
						echo CHtml::link('Raport <i class="fa fa-graduation-cap"></i>', array('/clases'),array('class'=>'btn btn-success btn-lg'));
						// echo CHtml::link('Deskripsi KD <i class="fa fa-graduation-cap"></i>', array('/lesson/managekd'),array('class'=>'btn btn-success btn-lg'));
					}
				}
			?>

		<?php //} ?>
		
	</h4> 
	<?php } ?>
	<p>
		<?php
			/*$HOME=Yii::app()->basePath;
			$command=exec('echo $HOME');
			echo $command;*/
		?>
	</p>
	<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Kamu telat untuk absen...</h4>
      </div>
      <div class="modal-body">
		<form role="form" action="<?php echo Yii::app()->createUrl('site/CheckIn');?>" method="POST">
		<div class="form-group">
		<label>Alasan telat:</label>
		<input type="text" class="form-control" name="alasan">
		</div>
		<input type="hidden" name="id_user" value="<?php echo Yii::app()->user->id ?>">
		<button type="submit" class="btn btn-default">Absen Masuk</button>
		</form>
      </div>
     
    </div>

  </div>
</div>

<div class="modal fade" id="penilaianModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><b>Bentuk Penilaian</b></h4>
      </div>
      <div class="modal-body">
      	<?php 
      		if(empty($fiturTugas) || $fiturTugas[0]->value != 2){
      			echo CHtml::link('Tugas <i class="fa fa-list-alt"></i>', array('/assignment/create'),array('class'=>'btn  btn-info btn-responsive'));
      		}
      	?>
        <?php 
        	if(empty($fiturUlangan) || $fiturUlangan[0]->value != 2){
        		echo CHtml::link('Ulangan <i class="fa fa-language"></i>', array('/quiz/create'),array('class'=>'btn  btn-primary btn-responsive'));
        	}
        ?>
        <?php 
        	if(empty($fiturTugas) || $fiturTugas[0]->value != 2){
        		echo CHtml::link('Praktek/Offline <i class="fa fa-pencil-square-o"></i>', array('/assignment/create','type'=>1),array('class'=>'btn  btn-success btn-responsive'));
      		}
        ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>
	<div align="right">	
	<?php 
	 $jam_masuk = "08:00:00";
	 $jam_keluar = "10:00:00";
	if(Yii::app()->user->YiiStudent)
	{
		
		$t1 = time();
		$t2 = strtotime($jam_keluar);
		
		$interval = $t2 - $t1;
		
		if(empty($isAbsenMasuk) and empty($isAbsenKeluar))
		{
			if(time() > strtotime($jam_masuk))
			{
	?>	
		<!-- <button class="btn btn-warning btn-responsive" data-toggle="modal" data-target="#myModal">Absen Masuk</button> -->
	<?php
			}
			else
			{
	?>
		<!-- <form method="POST" action="<?php //echo Yii::app()->createUrl('site/CheckIn');?>">	
		<input type="hidden" name="id_user" value="<?php //echo Yii::app()->user->id ?>">
		<input type="hidden" name="alasan" value="''">
		<button data-content="Klik untuk Absen Masuk." class="btn btn-success pop" type="submit">Absen Masuk</button>
		</form> -->
			<?php			
			}
		}
		elseif(empty($isAbsenMasuk) and !empty($isAbsenKeluar))
		{
		?>
			<button disabled class="btn btn-primary btn-responsive">Sudah Absen</button>
		<?php		
		}
		else
		{
			if(time() < strtotime($jam_keluar))
			{
		?>
			<span style="position:relative;top:-28px;right:-120px;" id="countdown" class="timer"></span>
			<button readonly class="btn btn-danger btn-responsive">Absen Keluar </button>
		<?php
			}
			elseif(empty($isAbsenKeluar))
			{
		?>
			<button class="btn btn-success btn-responsive" onclick="location.href = '<?php echo Yii::app()->createUrl('site/CheckOut/'.Yii::app()->user->id);?>' ">Absen Keluar</button>
		<?php		
			}	
		}
	}
	?>
	<?php if(!Yii::app()->user->YiiStudent){ ?>
	<div class="container">
		<?php if(time() > strtotime($jam_keluar))
		{
		?>
		<!-- <button data-content="Absen hari ini telah terkumpul." class="btn btn-default pop btn-responsive" onclick="location.href='<?php //echo Yii::app()->createUrl('site/LihatAbsen') ?>'">Lihat Absen</button> -->
		<?php
		}
		else
		{
		?>
		<!-- <button class="btn btn-default btn-responsive" onclick="location.href='<?php //echo Yii::app()->createUrl('site/LihatAbsen') ?>'">Lihat Absen</button> -->
		<?php
		}
		?>
		&nbsp;
		<?php if(Yii::app()->user->YiiAdmin){ ?>
		<!-- <button class="btn btn-lg btn-primary" id="sinkron">Sinkronisasi</button> -->
		
			<!-- <button type="button" id="sinkronisasi" class="btn btn-lg btn-primary btn-responsive">Sinkronisasi</button>
			<span> <button type="button" id="sinkronisasilive" class="btn btn-lg btn-primary btn-responsive">Sinkronisasi Live</button></span> -->
		
		
		<?php //echo CHtml::link('Sinkronisasi', array('/api/startSinkronisasi'),array('class'=>'btn btn-primary btn-lg'));?>
		<?php } ?>
	</div>
	<?php } ?>
	</div>
</div>

<script>
$(document).ready(function () {
    $('.pop').each(function () {
        var $elem = $(this);
        $elem.popover({
            placement: 'left',
            trigger: 'manual',
            html: true,
            //container: $elem,
			animation: true,
			
        }).popover('show');
    });
    
    //$("#sinkronisasi").on("click",);
});
	console.log(window.location.hostname);
	var sinkronisasi_url = "<?php echo Yii::app()->baseUrl;?>/api/sinkronisasi?cekStatus=";
	var sinkronisasi_url_live = "<?php echo Yii::app()->baseUrl;?>/api/liveSinkronisasi?cekStatus=";
	console.log(sinkronisasi_url_live);
    function getSinkronisasi(type){
    	//waitingDialog.show('Harap Tunggu!!! Aplikasi Sedang Melakukan Sinkronisasi');
    	
    	$.ajax({
	      url: sinkronisasi_url+type,
	      type: "POST",
	      //data: "id=1",
	      success: function(resp){
	        console.log(resp);
		        switch(resp){
		         	case "tugas":
		         		/*sinkronisasi_url = sinkronisasi_url+resp;*/
		         		//console.log(sinkronisasi_url);
		         		break;
		         	case "tugasSiswa":
		         		/*sinkronisasi_url = sinkronisasi_url+resp;*/
		         		//console.log(sinkronisasi_url+resp);
		         		break;
		         	case "materi":
		         		/*sinkronisasi_url = sinkronisasi_url+resp;*/
		         		//console.log(sinkronisasi_url+resp);
		         		break;
		         	case "materiFile":
		         		/*sinkronisasi_url = sinkronisasi_url+resp;*/
		         		//console.log(sinkronisasi_url+resp);
		         		break;
		         	case "quiz":
		         		/*sinkronisasi_url = sinkronisasi_url+resp;*/
		         		//console.log(sinkronisasi_url+resp);
		         		break;
		         	case "quizSiswa":
		         		/*sinkronisasi_url = sinkronisasi_url+resp;*/
		         		//console.log(sinkronisasi_url+resp);
		         		break;
		         	case "soal":
		         		/*sinkronisasi_url = sinkronisasi_url+resp;*/
		         		//console.log(sinkronisasi_url+resp);
		         		break;
		         	case "lks":
		         		/*sinkronisasi_url = sinkronisasi_url+resp;*/
		         		//console.log(sinkronisasi_url+resp);
		         		break;
		         	case "pengumuman":
		         		/*sinkronisasi_url = sinkronisasi_url+resp;*/
		         		//console.log(sinkronisasi_url+resp);
		         		break;
		         	case "nilaiOffline":
		         		/*sinkronisasi_url = sinkronisasi_url+resp;*/
		         		//console.log(sinkronisasi_url+resp);
		         		break;
		         	case "aktivitas":
		         		/*sinkronisasi_url = sinkronisasi_url+resp;*/
		         		//console.log(sinkronisasi_url+resp);
		         		break;
		         	case "notifikasi":
		         		/*sinkronisasi_url = sinkronisasi_url+resp;*/
		         		//console.log(sinkronisasi_url+resp);
		         		break;
		         	case "kelas":
		         		/*sinkronisasi_url = sinkronisasi_url+resp;*/
		         		//console.log(sinkronisasi_url+resp);
		         		break;
		         	case "pelajaran":
		         		/*sinkronisasi_url = sinkronisasi_url+resp;*/
		         		//console.log(sinkronisasi_url+resp);
		         		break;
		         	case "absensi":
		         		/*sinkronisasi_url = sinkronisasi_url+resp;*/
		         		//console.log(sinkronisasi_url+resp);
		         		break;														
		        }
		        //console.log(resp);
		        if(resp != "selesai"){
		        	getSinkronisasi(resp);
		        }else{
		        	waitingDialog.hide();
		        }
		        //console.log(sinkronisasi_url);
	      }
	    });
    }

    function getSinkronisasiLive(type){
    	//waitingDialog.show('Harap Tunggu!!! Aplikasi Sedang Melakukan Sinkronisasi');
    	console.log(sinkronisasi_url_live);

    	$.ajax({
	      url: sinkronisasi_url_live+type,
	      type: "POST",
	      //data: "id=1",
	      success: function(resp){
	        console.log(resp);
		        switch(resp){
		         	case "tugas":
		         		/*sinkronisasi_url = sinkronisasi_url+resp;*/
		         		//console.log(sinkronisasi_url);
		         		break;
		         	case "tugasSiswa":
		         		/*sinkronisasi_url = sinkronisasi_url+resp;*/
		         		//console.log(sinkronisasi_url+resp);
		         		break;
		         	case "materi":
		         		/*sinkronisasi_url = sinkronisasi_url+resp;*/
		         		//console.log(sinkronisasi_url+resp);
		         		break;
		         	case "materiFile":
		         		/*sinkronisasi_url = sinkronisasi_url+resp;*/
		         		//console.log(sinkronisasi_url+resp);
		         		break;
		         	case "quiz":
		         		/*sinkronisasi_url = sinkronisasi_url+resp;*/
		         		//console.log(sinkronisasi_url+resp);
		         		break;
		         	case "quizSiswa":
		         		/*sinkronisasi_url = sinkronisasi_url+resp;*/
		         		//console.log(sinkronisasi_url+resp);
		         		break;
		         	case "soal":
		         		/*sinkronisasi_url = sinkronisasi_url+resp;*/
		         		//console.log(sinkronisasi_url+resp);
		         		break;
		         	case "lks":
		         		/*sinkronisasi_url = sinkronisasi_url+resp;*/
		         		//console.log(sinkronisasi_url+resp);
		         		break;
		         	case "pengumuman":
		         		/*sinkronisasi_url = sinkronisasi_url+resp;*/
		         		//console.log(sinkronisasi_url+resp);
		         		break;
		         	case "nilaiOffline":
		         		/*sinkronisasi_url = sinkronisasi_url+resp;*/
		         		//console.log(sinkronisasi_url+resp);
		         		break;
		         	case "aktivitas":
		         		/*sinkronisasi_url = sinkronisasi_url+resp;*/
		         		//console.log(sinkronisasi_url+resp);
		         		break;
		         	case "notifikasi":
		         		/*sinkronisasi_url = sinkronisasi_url+resp;*/
		         		//console.log(sinkronisasi_url+resp);
		         		break;
		         	case "kelas":
		         		/*sinkronisasi_url = sinkronisasi_url+resp;*/
		         		//console.log(sinkronisasi_url+resp);
		         		break;
		         	case "pelajaran":
		         		/*sinkronisasi_url = sinkronisasi_url+resp;*/
		         		//console.log(sinkronisasi_url+resp);
		         		break;
		         	case "absensi":
		         		/*sinkronisasi_url = sinkronisasi_url+resp;*/
		         		//console.log(sinkronisasi_url+resp);
		         		break;														
		        }
		        //console.log(resp);
		        if(resp != "selesai"){
		        	getSinkronisasiLive(resp);
		        }else{
		        	waitingDialog.hide();
		        }
		        //console.log(sinkronisasi_url);
	      }
	    });
    }

    $("#sinkronisasi").click(function(){
    	waitingDialog.show('Harap Tunggu!!! Aplikasi Sedang Melakukan Sinkronisasi');
    	
    	//console.log(sinkronisasi_url);
    	getSinkronisasi();
    	//setTimeout(function () {waitingDialog.hide();}, 5000);
    });

    $("#sinkronisasilive").click(function(){
    	waitingDialog.show('Harap Tunggu!!! Aplikasi Sedang Melakukan Sinkronisasi');
    	
    	//console.log(sinkronisasi_url);
    	getSinkronisasiLive('user');
    	//setTimeout(function () {waitingDialog.hide();}, 5000);
    });
//var hms = '<?php //echo $interval; ?>';   // your input string
//var a = hms.split(':'); // split it at the colons
// minutes are worth 60 seconds. Hours are worth 60 minutes.

//var seconds = (+a[0]) * 60 * 60 + (+a[1]) * 60 + (+a[2]); 
// var seconds = '<?php //echo $interval; ?>';
// function timer() {
    // var days        = Math.floor(seconds/24/60/60);
    // var hoursLeft   = Math.floor((seconds) - (days*86400));
    // var hours       = Math.floor(hoursLeft/3600);
    // var minutesLeft = Math.floor((hoursLeft) - (hours*3600));
    // var minutes     = Math.floor(minutesLeft/60);
    // var remainingSeconds = seconds % 60;
    // if (remainingSeconds < 10) {
        // remainingSeconds = "0" + remainingSeconds; 
    // }
    // document.getElementById('countdown').innerHTML = 'Time remain : ['+ hours + ":" + minutes + ":" + remainingSeconds +']';
    // if (seconds == 0) {
        // clearInterval(countdownTimer);
        // document.getElementById('countdown').innerHTML = "Completed";
    // } else {
        // seconds--;
    // }
// }
// var countdownTimer = setInterval('timer()', 1000);

</script>
<style>
.pop .popover {
    margin-top:7px;
}
</style>


	<div class="col-xs-12 col-sm-8 col-md-8 padt10 panel-medidu">
					
					
		<div class="media">
			<a class="pull-left" href="#">
				<?php $user = User::model()->findByPk(Yii::app()->user->id);?>
				<!-- <img class="media-object dp img-circle" src="<?php //echo Yii::app()->theme->baseUrl;?>/img/profile.jpg" style="width: 120px;height:120px;"> -->
				<?php if(($user->image))
	              	{
	              		$img_url = Yii::app()->baseUrl.'/images/user/'.$user->id.'/'.$user->image;
	              	} else {
	              		$img_url = Yii::app()->baseUrl.'/images/user-2.png';
	              	}
              	?>
				<img class="media-object dp img-circle" src="<?php echo $img_url;?>" style="width: 120px;height:120px;">
			</a>
			<p class="text-right"><?php echo CHtml::link('<i class="glyphicon glyphicon-edit"></i>', array('user/update','id'=>Yii::app()->user->id),array('title'=>'Edit'))?></p>
			<div class="media-body">
				<h3 class="media-heading"><?php echo CHtml::link($detail->display_name, array('user/view','id'=>Yii::app()->user->id));?></h3>
				<?php if(Yii::app()->user->YiiStudent){ ?>
					<p><?php //echo $detail->class->name;?></p>
				<?php }elseif (Yii::app()->user->YiiTeacher) { ?>
					<p>Dokter</p>
				<?php }elseif(Yii::app()->user->YiiKepsek){ ?>
					<p>Dirut</p>
				<?php }elseif(Yii::app()->user->YiiAdmin){ ?>
					<p>Adminitrasi</p>
				<?php } ?>
				<hr style="margin:8px auto;height:1px;">
				<h5><?php echo $detail->username;?></h5>							
			</div>
		</div>		
		<div>
		<?php if(Yii::app()->user->YiiStudent) { ?>
		
			<ul class="nav nav-tabs">
				<?php if(Yii::app()->user->YiiStudent){ ?>
				
						<!-- <li class="<?php //echo $activity; ?>"><?php //echo CHtml::link('Aktivitas',array('site/index', 'role'=>'activity')); ?></li> --> 
						<li class="<?php echo $upcoming; ?>">
							<?php
								if(empty($fiturTugas) || $fiturTugas[0]->value != 2){ 
									echo CHtml::link('<span class="glyphicon glyphicon-th-list"></span>Tugas Terbaru',array('site/index', 'role'=>'upcoming')); 
								}
							?>
						</li>
						<li class="<?php echo $late; ?>">
							<?php
								if(empty($fiturTugas) || $fiturTugas[0]->value != 2){ 
									echo CHtml::link('<span class="glyphicon glyphicon-time"></span>Tugas Telat',array('site/index', 'role'=>'late')); 
								}
							?>
						</li> 
						<li class="<?php echo $nilai; ?>">
							<?php
								if(empty($fiturTugas) || $fiturTugas[0]->value != 2){ 
									echo CHtml::link('<span class="glyphicon glyphicon-ok"></span>Tugas Sudah Dinilai',array('site/index', 'role'=>'nilai')); 
								}
							?>
						</li>
						<li class="<?php echo $kuis; ?>">
							<?php
								if(empty($fiturUlangan) || $fiturUlangan[0]->value != 2){ 
									echo CHtml::link('<span class="glyphicon glyphicon-list-alt"></span>Ulangan',array('site/index', 'role'=>'kuis')); 
								}
							?>
						</li>
				
				<?php }elseif(Yii::app()->user->YiiTeacher){ ?>
					
						<!-- <li class="<?php //echo $activity; ?>"><?php //echo CHtml::link('Aktivitas',array('site/index', 'role'=>'activity')); ?></li>  -->
						<li class="<?php echo $late; ?>">
							<?php
								if(empty($fiturTugas) || $fiturTugas[0]->value != 2){ 
									echo CHtml::link('<span class="glyphicon glyphicon-th-list"></span>Tugas',array('site/index', 'role'=>'late')); 
								}
							?>
						</li> 
						<li class="<?php echo $nilai; ?>">
							<?php
								if(empty($fiturUlangan) || $fiturUlangan[0]->value != 2){ 
									echo CHtml::link('<span class="glyphicon glyphicon-list-alt"></span>Ulangan',array('site/index', 'role'=>'nilai'));
								}
							?>
						</li>
					
				<?php } ?>
			  <!-- <li class="active"><a href="#a" data-toggle="tab"><span class="glyphicon glyphicon-list"></span>Aktivitas</a></li>
			  <li><a href="#b" data-toggle="tab"><span class="glyphicon glyphicon-time"></span>Tugas Terlambat</a></li>
			  <li><a href="#c" data-toggle="tab"><span class="glyphicon glyphicon-edit"></span>Tugas Selanjutnya</a></li>
			  <li><a href="#d" data-toggle="tab"><span class="glyphicon glyphicon-check"></span>Tugas sudah Dinilai</a></li> -->
			</ul>
			<div class="tab-content ">
				<?php if(empty($models)) { ?>
	    		<h3 class="text-center">Tidak ada hasil</h3>
		  		<?php }  else  { 
		  			if(Yii::app()->user->YiiTeacher){
		  				if ($role == 'activity') {
			  				$this->renderPartial('_stat2', array(
								'models'=>$models,
								//'pages'=>$pages,
								'pages'=>NULL,
								'summaryText'=>'',
								//'itemsTagName'=>'ul',
							    //'itemsCssClass'=>'thumbnails',
								//'pagerCssClass'=>'pagination text-center cleafix',

							));
			  			} elseif ($role == 'late' or empty($role)) {
			  				if(empty($fiturTugas) || $fiturTugas[0]->value != 2){
				  				$this->renderPartial('_stat', array(
									'models'=>$models,
									//'pages'=>$pages,
									'pages'=>NULL,
									'summaryText'=>'',
									//'itemsTagName'=>'ul',
								    //'itemsCssClass'=>'thumbnails',
									//'pagerCssClass'=>'pagination text-center cleafix',

								));
							}	
			  			} elseif ($role == 'nilai') {
			  				if(empty($fiturUlangan) || $fiturUlangan[0]->value != 2){
				  				$this->renderPartial('_stat3', array(
									'models'=>$models,
									//'pages'=>$pages,
									'pages'=>NULL,
									'summaryText'=>'',
									//'itemsTagName'=>'ul',
								    //'itemsCssClass'=>'thumbnails',
									//'pagerCssClass'=>'pagination text-center cleafix',

								));
							}	
			  			}		
		  			}elseif(Yii::app()->user->YiiStudent){
		  				if ($role == 'activity') {
		  					'<div class="tab-pane active" id="a">';
		  						'<ul class="list-group pull-left">';
					  				$this->renderPartial('_stat2', array(
										'models'=>$models,
										//'pages'=>$pages,
										'pages'=>NULL,
										'summaryText'=>'',
										//'itemsTagName'=>'ul',
									    //'itemsCssClass'=>'thumbnails',
										//'pagerCssClass'=>'pagination text-center cleafix',

									));
								'</ul>';
							'</div>';
			  			} elseif ($role == 'late' or $role == 'upcoming' or empty($role)) {
			  				if(empty($fiturTugas) || $fiturTugas[0]->value != 2){
				  				'<div class="tab-pane" id="b">';
					  				'<ul class="list-group pull-left">';
						  				$this->renderPartial('_stat', array(
											'models'=>$models,
											//'pages'=>$pages,
											'pages'=>NULL,
											'summaryText'=>'',
											//'itemsTagName'=>'ul',
										    //'itemsCssClass'=>'thumbnails',
											//'pagerCssClass'=>'pagination text-center cleafix',

										));
									'</ul>';
								'</div>';
							}	
			  			} elseif ($role == 'nilai') {
			  				if(empty($fiturTugas) || $fiturTugas[0]->value != 2){
				  				'<div class="tab-pane" id="c">';
					  				'<ul class="list-group pull-left">';
						  				$this->renderPartial('_stat3', array(
											'models'=>$models,
											//'pages'=>$pages,
											'pages'=>NULL,
											'summaryText'=>'',
											//'itemsTagName'=>'ul',
										    //'itemsCssClass'=>'thumbnails',
											//'pagerCssClass'=>'pagination text-center cleafix',

										));
									'</ul>';
								'</div>';
							}			
			  			} elseif ($role == 'kuis') {
			  				if(empty($fiturUlangan) || $fiturUlangan[0]->value != 2){
				  				'<div class="tab-pane" id="d">';
					  				'<ul class="list-group pull-left">';
						  				$this->renderPartial('_stat4', array(
											'models'=>$models,
											//'pages'=>$pages,
											'pages'=>NULL,
											'summaryText'=>'',
											//'itemsTagName'=>'ul',
										    //'itemsCssClass'=>'thumbnails',
											//'pagerCssClass'=>'pagination text-center cleafix',

										));
									'</ul>';
								'</div>';
							}	
			  			}
		  			}
		  			

		  		} ?>
			  
		</div>
		<?php }else{ ?>
			<?php if(!empty($mylesson)){ ?>
				<h3 class="well text-center">PELAJARAN SAYA</h3>
				<div class="row panel-medidu" style="padding:10px;text-align:center;">
				<div class="col-md-12">
					<?php foreach ($mylesson as $lsn) { ?>
						<?php 
							$lsn_url = Yii::app()->createUrl('/lesson/view/'.$lsn->id);
							if($lsn->moving_class==1){
								$nama_kelas = $lsn->grade->name;
							}else{
								$nama_kelas = $lsn->class->name;
							}
						?>
						<div class="col-md-4" style="margin-bottom: 10px;">
							<a href="<?php echo $lsn_url;?>">
								<?php if(stristr(strtolower($lsn->name), 'matematika')){ ?>
									<div class="panel-pelajaran bg2">
										<p><?php echo $lsn->name." (".$nama_kelas.")";?></p>
									</div>
								<?php }elseif (stristr(strtolower($lsn->name), 'ipa')) { ?>
									<div class="panel-pelajaran bg4">
										<p><?php echo $lsn->name." (".$nama_kelas.")";?></p>
									</div>
								<?php }elseif(stristr(strtolower($lsn->name), 'komputer')) { ?>
									<div class="panel-pelajaran bg3">
										<p><?php echo $lsn->name." (".$nama_kelas.")";?></p>
									</div>
								<?php }else{ ?>
									<div class="panel-pelajaran bg1">
										<p><?php echo $lsn->name." (".$nama_kelas.")";?></p>
									</div>
								<?php } ?>
							</a>
						</div>
					<?php } ?>
				</div>

				</div>
				<div>
					<p class="text-right"><?php echo CHtml::link('Lihat Semua', array('/lesson/index'), array('class'=>'btn btn-default'));?>
				</div>
			<?php } ?>
		<?php } ?>
		
	</div>
</div>
<div class="col-md-4 col-sm-8 padt10 col-xs-12">					
		<div class="panel panel-medidu">
			<div class="panel-heading">Pengumuman</div>
			<div class="panel-body">
			<ul>
				<?php $this->widget('booster.widgets.TbListView', array(
	                'dataProvider'=>$berita,
	                'itemView'=>'/announcements/_view',
	                'summaryText'=>'',
	            )); ?>	
			</ul>
			</div>
		</div>
		
	</div>

<?php //$url_load = $this->createUrl('site/autoNotif');?>
<?php //$url_notif = $this->createUrl('notification/index');?>
<?php } ?>

<?php if(!Yii::app()->user->isGuest){ ?>
	<?php $url_load = $this->createUrl('site/autoNotif2');?>
	<?php $url_notif = $this->createUrl('notification/index');?>
	<?php $url_data = $this->createUrl('site/cekData');?>
	<?php $url_quiz = $this->createUrl('quiz/startQuiz');?>
<script type="text/javascript">
  //var ajax_call = function() {
    //your jQuery ajax code
    console.log(localStorage);
    
    url_notif = '<?php echo $url_notif; ?>';
    url_data = '<?php echo $url_data;?>';
    url_quiz = '<?php echo $url_quiz;?>';
    //console.log("coba"+url_load);
    $("#hapusData").click(function(){
    	localStorage.clear();
    	//console.log("berhasil");
    });
    var ajax_call_2 = function() {
    	url_load = '<?php echo $url_load; ?>';
	    $.ajax({
	      url: url_load,
	      type: "POST",
	      //data: "id=1",
	      success: function(msg){
	         //console.log(msg);
	         if(msg > 0){
	         	//console.log(msg);
	         	//$('#ntf').text(msg);
	         	if($("#dg").length == 0){
	         		$("#notif-flash").append('<div id="dg" class="alert alert-info" role="alert"><a href="'+url_notif+'">Ada '+msg+' Notifikasi Baru</a></div>');
	         	}
	         }
	         //console.log(msg)
	      }
	    });
    };
    
    if(typeof(localStorage.questionsundefinedqid) !== "undefined"){

    	if(localStorage["questionsundefinedStudentQuiz[student_id]"]!=<?php echo Yii::app()->user->id;?>){
    		localStorage.clear();
    	}
    	
    	$.ajax({
	      url: url_data,
	      type: "POST",
	      data: {quiz:localStorage.questionsundefinedqid},
	      success: function(msg){
	         //console.log(msg);
	         //console.log(msg);
	         if(msg == 2){
	         	console.log(msg);
	         	//$('#ntf').text(msg);
	         	$("#alrt").removeClass('hidden');
	         	$("#lanjut").attr("href",url_quiz+"/"+localStorage.questionsundefinedqid);
	         } else if (msg == 1) {
	         	console.log(msg);
	         	localStorage.clear();
	         }
	         //console.log(msg)
	      }
	    });
    }
    
  //};

  var interval = 1000 * 60 * 0.1; // where X is your every X minutes

  setInterval(ajax_call_2, interval);
  $('#sinkron').click(function(){
  	$("#loading-indicator").show(0).delay(5000).hide(0);
  	//$('#sinkron').hide();
  });

  if(typeof(localStorage) !== "undefined") {
    // Code for localStorage/sessionStorage.
	} else {
	   alert ("Peringatan, borwser anda tidak support untuk penyimpanan sementara, silahkan hubungi administrator.");
	}
  </script>
  <?php } ?>