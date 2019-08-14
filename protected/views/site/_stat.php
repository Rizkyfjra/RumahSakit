<?php 
/*echo "<pre>";
print_r($models);
echo "</pre>";*/
?>
<?php if (empty($models)) { ?>
<!-- <div class="panel panel-default" id="assignment">
  <div class="panel-body">
	  <div class="col-xs-2">
		  <img src="<?php echo Yii::app()->baseUrl;?>/images/pencil.png" class="img-responsive img-rounded">
	  </div>
	  <div class="col-xs-10">
	  	<h3><?php echo $data->title; ?></h3>
	  	<p>Due Date : <span class="bg-info"><?php echo Yii::app()->dateFormatter->format("d MMM yyyy (HH:mm)",$data->due_date); ?></span></p>
	  	<?php echo $data->content; ?>
	  </div>
  </div>
  <div class="panel-footer text-muted">
  	<i class="fa fa-fw fa-calendar"></i><?php echo Yii::app()->dateFormatter->format("d MMM yyyy",$data->created_at); ?>
  	<i class="fa fa-fw fa-clock-o"></i><?php echo Yii::app()->dateFormatter->format("HH:mm",$data->created_at); ?>
  </div>
</div> -->
<?php 
} else {
	if(Yii::app()->user->YiiStudent){

	echo "<div id='assignment1'>";
	echo "<p class='text-right'>".CHtml::link('Lihat Semua', array('assignment/index'),array('class'=>'btn btn-default'))."</p>";
	foreach($models as $data) { ?>
		<li class="list-group-item">
			<?php echo CHtml::link("$data->title", array("/assignment/view","id"=>$data->id)); ?>
			<span class="badge pull-right"><?php echo Yii::app()->dateFormatter->format("d MMM yyyy",$data->created_at); ?></span>
		</li>
		<!-- <div class="panel panel-default" id="assignment2">
		  <div class="panel-body">
			  <div class="col-xs-2">
				  <img src="<?php echo Yii::app()->baseUrl;?>/images/pencil.png" class="img-responsive img-rounded">
			  </div>
			  <div class="col-xs-10">
			  	<h3><?php echo CHtml::link("$data->title", array("/assignment/view","id"=>$data->id)); ?></h3>
			  	<p>Due Date : <span class="bg-info"><?php echo Yii::app()->dateFormatter->format("d MMM yyyy (HH:mm)",$data->due_date); ?></span></p>
			  	<?php echo $data->content; ?>
			  </div>
		  </div>
		  <div class="panel-footer text-muted">
		  	<i class="fa fa-fw fa-calendar"></i><?php echo Yii::app()->dateFormatter->format("d MMM yyyy",$data->created_at); ?>
		  	<i class="fa fa-fw fa-clock-o"></i><?php echo Yii::app()->dateFormatter->format("HH:mm",$data->created_at); ?>
		  </div>
		</div> -->

	<?php
		}
		echo "</div>";
	}elseif(Yii::app()->user->YiiTeacher){ 
		echo "<div id='assignment1'>";
		echo "<p class='text-right'>".CHtml::link('Lihat Semua', array('assignment/index'),array('class'=>'btn btn-default'))."</p>";
		foreach($models as $data) { ?>
			<?php
				$cekTS = StudentAssignment::model()->findAll(array('condition'=>'assignment_id = '.$data->id.' and status = 1'));
				$tTugas = count($cekTS);
				$cekTNilai = StudentAssignment::model()->findAll(array('condition'=>'assignment_id = '.$data->id.' and status = 1 and score is null'));
				$tNilai = count($cekTNilai);
			?>
			<li class="list-group-item">
				<?php echo CHtml::link("$data->title", array("/assignment/view","id"=>$data->id)); ?>
				<span class="badge pull-right"><?php echo Yii::app()->dateFormatter->format("d MMM yyyy",$data->created_at); ?></span>
			</li>
			<!-- <div class="panel panel-default" id="assignment2">
			  <div class="panel-body">
				  <div class="col-xs-2">
					  <img src="<?php echo Yii::app()->baseUrl;?>/images/pencil.png" class="img-responsive img-rounded">
				  </div>
				  <div class="col-xs-10">
				  	<h4><?php echo CHtml::link($data->title, array("/assignment/view","id"=>$data->id)); ?></h4>
				  	<p>Batas Pengumpulan : <span class="bg-info"><?php echo Yii::app()->dateFormatter->format("d MMM yyyy (HH:mm)",$data->due_date); ?></span></p>
				  	<p><?php echo CHtml::link('Terkumpul <span class="badge">'.$tTugas.'</span>', array('/assignment/view','id'=>$data->id,'type'=>2));?> / <?php echo CHtml::link('Belum Dinilai <span class="badge">'.$tNilai.'</span>', array('/assignment/view','id'=>$data->id, 'type'=>1));?></p>
				  	<?php //echo $data->content; ?>
				  </div>
			  </div>
			  <div class="panel-footer text-muted">
			  	<i class="fa fa-fw fa-calendar"></i><?php echo Yii::app()->dateFormatter->format("d MMM yyyy",$data->updated_at); ?>
			  	<i class="fa fa-fw fa-clock-o"></i><?php echo Yii::app()->dateFormatter->format("HH:mm",$data->updated_at); ?>
			  </div>
			</div> -->
	<?php }
		echo "</div>";
	}	
	?>

<?php if (!empty($pages)) { ?>
<!-- <div class='pagination text-center cleafix'> -->
	<div class='text-center'>
<?php 
$pages_a = $pages;
/*$this->widget('ext.yiinfinite-scroll.YiinfiniteScroller', array(
	'contentSelector' => '#assignment1',
    'itemSelector' => 'div#assignment2',
    //'navigationLinkText' => 'Load More',
    'donetext' => 'Akhir Scroll',
    'pages' => $pages_a,
));*/
?>
<?php
  $this->widget('CLinkPager', array(
                'pages'=>$pages,
                'maxButtonCount'=>3,
                ));
?>
</div>
<?php }
	} ?>


