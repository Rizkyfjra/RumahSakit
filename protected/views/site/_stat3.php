<?php 
/*echo "<pre>";
print_r($data);
echo "</pre>";*/
?>
<?php if (empty($models)) { ?>

<?php 
} else {
	if(Yii::app()->user->YiiStudent){
	echo "<div id='scores1'>";
	echo "<p class='text-right'>".CHtml::link('Lihat Semua', array('studentAssignment/index'),array('class'=>'btn btn-default'))."</p>";
	foreach($models as $data) { ?>
		<li class="list-group-item">
			<?php echo CHtml::link("$data->title", array("/studentAssignment/view","id"=>$data->id)); ?>
			<span class="badge pull-right"><?php echo Yii::app()->dateFormatter->format("d MMM yyyy",$data->created_at); ?></span>
		</li>
	
<?php
	} 
	echo "</div>";
	}elseif(Yii::app()->user->YiiTeacher){
		echo "<div id='scores1'>";
		echo "<p class='text-right'>".CHtml::link('Lihat Semua', array('quiz/index'),array('class'=>'btn btn-default'))."</p>";
		foreach($models as $data) { ?>
			<?php
				$cekQS = StudentQuiz::model()->findAll(array('condition'=>'quiz_id = '.$data->id));
				$tQuiz = count($cekQS);
			?>
			<li class="list-group-item">
				<?php echo CHtml::link($data->title, array("/quiz/view","id"=>$data->id)); ?>
				<span class="badge pull-right"><?php echo Yii::app()->dateFormatter->format("d MMM yyyy",$data->created_at); ?></span>
			</li>
			
<?php	
		}
	echo "</div>";
	}
?>
<?php if (!empty($pages)) { ?>
<!-- <div class='pagination text-center cleafix'> -->
	<div class='text-center'>
<?php 
/*$this->widget('ext.yiinfinite-scroll.YiinfiniteScroller', array(
	'contentSelector' => '#scores1',
    'itemSelector' => 'div#scores2',
    //'navigationLinkText' => 'Load More',
    'donetext' => 'Akhir Scroll',
    'pages' => $pages,
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