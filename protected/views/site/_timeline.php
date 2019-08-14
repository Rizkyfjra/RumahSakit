<?php 
/*echo "<pre>";
$data = $asg->data;
foreach ($data as $value) {
	print_r($value);
}
echo "</pre>";*/
if (!empty($models)){
	// $this->widget('zii.widgets.CListView', array(
	// 	'id'=>'test',
	// 	'dataProvider'=>$asg,
	// 	'itemView'=>'_stat',
	// 	//'pagerCssClass'=>'hidden',
	// 	'pager'=> array(
	//         'class'=>'ext.yiinfinite-scroll.YiinfiniteScroller',
	//         'contentSelector' => '#test',
 //            'itemSelector' => 'div.test',
	//     ),
            
	// ));

	$this->renderPartial('_stat', array(
				'models'=>$models,
				'pages'=>$pages,
				'summaryText'=>'',
				//'itemsTagName'=>'ul',
			    //'itemsCssClass'=>'thumbnails',
				//'pagerCssClass'=>'pagination text-center cleafix',

	));

} elseif (!empty($asg)) {
	$this->widget('booster.widgets.TbListView', array(
		'dataProvider'=>$asg,
		'itemView'=>'_stat',
	));
} elseif (!empty($asg2)) {
	// if (!empty($pages_activity)){
	// 	$this->renderPartial('_stat2', array(
	// 		'models'=>$asg2,
	// 		'pages'=>$pages_activity,
	// 		'summaryText'=>'',
	// 		//'itemsTagName'=>'ul',
	// 	    //'itemsCssClass'=>'thumbnails',
	// 		//'pagerCssClass'=>'pagination text-center cleafix',

	// 	));
	// } else {
	// 	$this->widget('booster.widgets.TbListView', array(
	// 		'dataProvider'=>$asg2,
	// 		'itemView'=>'_stat2',
	// 	));
	// }
} elseif (!empty($asg3)) {
	// if (!empty($pages_scores)){
	// 	$this->renderPartial('_stat3', array(
	// 		'models'=>$asg3,
	// 		'pages'=>$pages_scores,
	// 		'summaryText'=>'',
	// 		//'itemsTagName'=>'ul',
	// 	    //'itemsCssClass'=>'thumbnails',
	// 		//'pagerCssClass'=>'pagination text-center cleafix',

	// 	));
	// } else {
	// 	$this->widget('booster.widgets.TbListView', array(
	// 		'dataProvider'=>$asg3,
	// 		'itemView'=>'_stat3',
	// 	));
	// }
}

?>