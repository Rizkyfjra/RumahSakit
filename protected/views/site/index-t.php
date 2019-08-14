<?php
$this->pageTitle=Yii::app()->name;

$this->breadcrumbs=array(
  'My Timeline',
);

$style = <<<CSS

CSS;

Yii::app()->clientscript->registerCSS('fixtimeline', $style);
//Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/jquery.jscroll.min.js',CClientScript::POS_HEAD);
?>

<div class="page-header">
	<h1>My Timeline</h1>
</div>

<div class="row">
	<div class="col-md-8">
	<?php 
	$this->widget('booster.widgets.TbTabs', array(
		'type' => 'tabs', // 'tabs' or 'pills'
		'tabs' => array(
			array(
				//'active' => true,
				'label' => 'Activity',
				// 'content' => 'Content',
				'content'=>$this->renderPartial( '_timeline', array(
					'asg2' => $activity, 'pages_activity' => $pages_activity
	                ), true ),
				),
			array(
				'active' => true,
				'label' => 'Late',
				// 'content' => 'Content',
				'content'=>$this->renderPartial( '_timeline', array(
					'models' => $late_asg, 'pages' => $pages
	                ), true ),
				),
				//'content'=>CHtml::link(' Relawan',array('site/login')),
				//),
			array(
				'label' => 'Upcoming',
				'content'=>$this->renderPartial( '_timeline', array(
					'models' => $upcoming_asg, 'pages' => $pages_upasg
	                ), true ),
				),
			array(
				'label' => 'Nilai', 'content' => 'Content',
				'content'=>$this->renderPartial( '_timeline', array(
					'asg3' => $scores, 'pages_scores' => $pages_scores
	                ), true ),
				),
			),
		));
	?>
	</div>
	
	<div class="col-md-4">
		<div class="panel panel-info">
		  <div class="panel-heading">
		    <h3 class="panel-title">Pengumuman</h3>
		  </div>
		  <div class="panel-body">
		  	<?php $this->widget('booster.widgets.TbListView', array(
                'dataProvider'=>$berita,
                'itemView'=>'/announcements/_view',
                'summaryText'=>'',
            )); ?>
		  </div>
		</div>

		<div class="panel panel-warning">
		  <div class="panel-heading">
		    <h3 class="panel-title">Notifikasi</h3>
		  </div>
		  <div class="panel-body">
		  	<?php $this->widget('booster.widgets.TbListView', array(
                'dataProvider'=>$berita,
                'itemView'=>'/announcements/_view',
                'summaryText'=>'',
            )); ?>
		  </div>
		</div>

	</div>
</div>

<script type="text/javascript">
$('#assignment1').jscroll({
    autoTrigger: true
});</script>