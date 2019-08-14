
<?php if(Yii::app()->user->isGuest) { ?>

<div class="jumbotron">
	<!-- <h1>Selamat Datang di SMP Negeri 1 Cangkuang </h1>-->
	<h1><?php echo CHtml::encode(Yii::app()->name); ?></h1>
	  <img src="<?php echo Yii::app()->theme->baseUrl;?>/images/MIMHa Logo.png" class="thumnbnails" height="300px">
	  <br>
	   <p>E-Learning MTs Miftahul Huda
	  </p> 
	  <p> 
	  	<!-- Jl. Sidomukti No 29 Kel Sukaluyu Kec Cibeunying Kaler Kota Bandung--> 
	  	Jl. Cikadut No. 252 A.H Nasution Kec Mandalajati Kota Bandung 40194 Telp. (022) 7237649
	  </p>
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


?>
<?php if(!Yii::app()->user->isGuest){ ?>
<div id="notif-flash">
	<div id="alrt"class="alert alert-warning hidden" role="alert">Masih ada data ulangan yang tersisa <a href="#" id="lanjut">Lanjut Mengerjakan</a> atau <a href="" id="hapusData">Hapus</a></div>
</div>
<?php } ?>

<div class="page-header">
	<!-- <h1>Selamat Datang di <?php //echo CHtml::encode(Yii::app()->name); ?></h1> -->
	<?php if(!Yii::app()->user->YiiStudent){ ?>
	<h4>
		<?php if(Yii::app()->user->YiiTeacher){ ?>
			<?php echo CHtml::link('Buat Materi <i class="fa fa-plus"></i>', array('/chapters/create'),array('class'=>'btn btn-danger'));?> 
			<?php echo CHtml::link('Buat Tugas <i class="fa fa-plus"></i>', array('/assignment/create'),array('class'=>'btn btn-warning'));?> 
			<?php echo CHtml::link('Buat Ulangan <i class="fa fa-plus"></i>', array('/quiz/create'),array('class'=>'btn btn-info'));?>
			<?php echo CHtml::link('Buku Raport', array('/lesson/raport'),array('class'=>'btn btn-success btn-lg'));?> 
		<?php }else{ ?> 
			<?php echo CHtml::link('Buat Pengumuman <i class="fa fa-plus"></i>', array('/announcements/create'),array('class'=>'btn btn-danger'));?> 
			<?php echo CHtml::link('Buku Raport', array('/clases'),array('class'=>'btn btn-success btn-lg'));?>
		<?php } ?>
	</h4>
	<?php } ?>
	<p>
		<?php
			/*$HOME=Yii::app()->basePath;
			$command=exec('echo $HOME');
			echo $command;*/
		?>
	</p>
</div>
<div class="row">
	<div class="col-md-2">
	<?php if(Yii::app()->user->YiiStudent){ ?>
	<ul class="nav nav-pills nav-stacked">
			<!-- <li class="<?php //echo $activity; ?>"><?php //echo CHtml::link('Aktivitas',array('site/index', 'role'=>'activity')); ?></li> --> 
			<li class="<?php echo $upcoming; ?>"><?php echo CHtml::link('Tugas Terbaru',array('site/index', 'role'=>'upcoming')); ?></li>
			<li class="<?php echo $late; ?>"><?php echo CHtml::link('Tugas Telat',array('site/index', 'role'=>'late')); ?></li> 
			<li class="<?php echo $nilai; ?>"><?php echo CHtml::link('Tugas Sudah Dinilai',array('site/index', 'role'=>'nilai')); ?></li>
			<li class="<?php echo $kuis; ?>"><?php echo CHtml::link('Ulangan',array('site/index', 'role'=>'kuis')); ?></li>
	</ul>
	<?php }elseif(Yii::app()->user->YiiTeacher){ ?>
		<ul class="nav nav-pills nav-stacked">
			<!-- <li class="<?php //echo $activity; ?>"><?php //echo CHtml::link('Aktivitas',array('site/index', 'role'=>'activity')); ?></li>  -->
			<li class="<?php echo $late; ?>"><?php echo CHtml::link('Tugas',array('site/index', 'role'=>'late')); ?></li> 
			<li class="<?php echo $nilai; ?>"><?php echo CHtml::link('Ulangan',array('site/index', 'role'=>'nilai')); ?></li>
		</ul>
	<?php } ?>
	</div>
	<div class="col-md-6">
		<?php if(empty($models)) { ?>
    		<h3 class="text-center">Tidak ada hasil</h3>
  		<?php }  else  { 
  			if(Yii::app()->user->YiiTeacher){
  				if ($role == 'activity') {
	  				$this->renderPartial('_stat2', array(
						'models'=>$models,
						'pages'=>$pages,
						'summaryText'=>'',
						//'itemsTagName'=>'ul',
					    //'itemsCssClass'=>'thumbnails',
						//'pagerCssClass'=>'pagination text-center cleafix',

					));
	  			} elseif ($role == 'late' or empty($role)) {
	  				$this->renderPartial('_stat', array(
						'models'=>$models,
						'pages'=>$pages,
						'summaryText'=>'',
						//'itemsTagName'=>'ul',
					    //'itemsCssClass'=>'thumbnails',
						//'pagerCssClass'=>'pagination text-center cleafix',

					));
	  			} elseif ($role == 'nilai') {
	  				$this->renderPartial('_stat3', array(
						'models'=>$models,
						'pages'=>$pages,
						'summaryText'=>'',
						//'itemsTagName'=>'ul',
					    //'itemsCssClass'=>'thumbnails',
						//'pagerCssClass'=>'pagination text-center cleafix',

					));
	  			}		
  			}elseif(Yii::app()->user->YiiStudent){
  				if ($role == 'activity') {
	  				$this->renderPartial('_stat2', array(
						'models'=>$models,
						'pages'=>$pages,
						'summaryText'=>'',
						//'itemsTagName'=>'ul',
					    //'itemsCssClass'=>'thumbnails',
						//'pagerCssClass'=>'pagination text-center cleafix',

					));
	  			} elseif ($role == 'late' or $role == 'upcoming' or empty($role)) {
	  				$this->renderPartial('_stat', array(
						'models'=>$models,
						'pages'=>$pages,
						'summaryText'=>'',
						//'itemsTagName'=>'ul',
					    //'itemsCssClass'=>'thumbnails',
						//'pagerCssClass'=>'pagination text-center cleafix',

					));
	  			} elseif ($role == 'nilai') {
	  				$this->renderPartial('_stat3', array(
						'models'=>$models,
						'pages'=>$pages,
						'summaryText'=>'',
						//'itemsTagName'=>'ul',
					    //'itemsCssClass'=>'thumbnails',
						//'pagerCssClass'=>'pagination text-center cleafix',

					));
	  			} elseif ($role == 'kuis') {
	  				$this->renderPartial('_stat4', array(
						'models'=>$models,
						'pages'=>$pages,
						'summaryText'=>'',
						//'itemsTagName'=>'ul',
					    //'itemsCssClass'=>'thumbnails',
						//'pagerCssClass'=>'pagination text-center cleafix',

					));
	  			}
  			}
  			

  		} ?>
	</div>
	
	<div class="col-md-4">
		<div class="panel panel-info">
		  <div class="panel-heading">
		    <h3 class="panel-title">Pengumuman</h3>
		  </div>
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


<?php //$url_load = $this->createUrl('site/autoNotif');?>
<?php //$url_notif = $this->createUrl('notification/index');?>
<?php } ?>

<?php if(!Yii::app()->user->isGuest){ ?>
	<?php $url_load = $this->createUrl('site/autoNotif');?>
	<?php $url_notif = $this->createUrl('notification/index');?>
	<?php $url_data = $this->createUrl('site/cekData');?>
	<?php $url_quiz = $this->createUrl('quiz/startQuiz');?>
<script type="text/javascript">
  //var ajax_call = function() {
    //your jQuery ajax code
    console.log(localStorage);
    url_load = '<?php echo $url_load; ?>';
    url_notif = '<?php echo $url_notif; ?>';
    url_data = '<?php echo $url_data;?>';
    url_quiz = '<?php echo $url_quiz;?>';
    //console.log("coba"+url_load);
    $("#hapusData").click(function(){
    	localStorage.clear();
    	//console.log("berhasil");
    });
    $.ajax({
      url: url_load,
      type: "POST",
      //data: "id=1",
      success: function(msg){
         //console.log(msg);
         if(msg > 0){
         	//console.log(msg);
         	//$('#ntf').text(msg);
         	$("#notif-flash").append('<div class="alert alert-info" role="alert"><a href="'+url_notif+'">Ada '+msg+' Notifikasi Baru</a></div>');
         }
         //console.log(msg)
      }
    });
    
    if(typeof(localStorage.questionsundefinedqid) !== "undefined"){
    	$.ajax({
	      url: url_data,
	      type: "POST",
	      data: {quiz:localStorage.questionsundefinedqid},
	      success: function(msg){
	         //console.log(msg);
	         //console.log(msg);
	         if(msg > 0){
	         	console.log(msg);
	         	//$('#ntf').text(msg);
	         	$("#alrt").removeClass('hidden');
	         	$("#lanjut").attr("href",url_quiz+"/"+localStorage.questionsundefinedqid);
	         }
	         //console.log(msg)
	      }
	    });
    }
    
  //};

  var interval = 1000 * 60 * 0.1; // where X is your every X minutes

  //setInterval(ajax_call, interval);
  </script>
  <?php } ?>