<?php
$this->pageTitle=Yii::app()->name;

$this->breadcrumbs=array(
  'My Timeline',
);

$style = <<<CSS

CSS;

Yii::app()->clientscript->registerCSS('fixtimeline', $style);
//Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/jquery.jscroll.min.js',CClientScript::POS_HEAD);

$activity = '';
$late = '';
$upcoming = '';
$nilai = '';

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
		$activity = 'active';
}
?>

<div class="page-header">
	<h1>My Timeline</h1>
</div>

<div class="row">
	<div class="col-md-8">
	<ul class="nav nav-tabs">
			<li class="<?php echo $activity; ?>"><?php echo CHtml::link('Activity',array('site/role', 'role'=>'activity')); ?></li> 
			<li class="<?php echo $late; ?>"><?php echo CHtml::link('Late',array('site/role', 'role'=>'late')); ?></li> 
			<li class="<?php echo $upcoming; ?>"><?php echo CHtml::link('Upcoming',array('site/role', 'role'=>'upcoming')); ?></li>
			<li class="<?php echo $nilai; ?>"><?php echo CHtml::link('Nilai',array('site/role', 'role'=>'nilai')); ?></li>
		</ul>
		<?php if(empty($models)) { ?>
    		<h3 class="text-center">Tidak ada hasil</h3>
  		<?php }  else  { 

  			if ($role == 'activity' or empty($role)) {
  				$this->renderPartial('_stat2', array(
					'models'=>$models,
					'pages'=>$pages,
					'summaryText'=>'',
					//'itemsTagName'=>'ul',
				    //'itemsCssClass'=>'thumbnails',
					//'pagerCssClass'=>'pagination text-center cleafix',

				));
  			} elseif ($role == 'late' or $role == 'upcoming') {
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

  		} ?>
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
