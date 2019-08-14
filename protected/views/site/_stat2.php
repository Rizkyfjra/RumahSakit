<?php 
/*echo "<pre>";
print_r($data);
echo "</pre>";*/
?>
<?php if (empty($models)) { ?>
<li class="list-group-item">Tidak Ada Hasil</span></li>


<?php 
} else {
	echo "<div id='activity1'>";
	echo "<p class='text-right'>".CHtml::link('Lihat Semua', array('activity/index'),array('class'=>'btn btn-default'))."</p>";
		foreach($models as $data) { ?>
		<li class="list-group-item">
			<?php if(!empty($data->lesson)){ ?>
		  		<?php echo $data->lesson->name; ?>
		  	<?php } ?>
			<span class="badge pull-right"><?php echo Yii::app()->dateFormatter->format("d MMM yyyy",$data->created_at); ?></span>
		</li>
		

<?php
		} 
	echo "</div>";
?>
<?php if (!empty($pages)) { ?>
<!-- <div class='pagination text-center cleafix'> -->
	<div class='text-center'>
<?php 
/*$this->widget('ext.yiinfinite-scroll.YiinfiniteScroller', array(
	'contentSelector' => '#activity1',
    'itemSelector' => 'div#activity2',
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