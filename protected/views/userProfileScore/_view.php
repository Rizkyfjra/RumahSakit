<?php
/* @var $this UserProfileScoreController */
/* @var $data UserProfileScore */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('user_id')); ?>:</b>
	<?php echo CHtml::encode($data->user_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('smt_01_pai')); ?>:</b>
	<?php echo CHtml::encode($data->smt_01_pai); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('smt_01_pkn')); ?>:</b>
	<?php echo CHtml::encode($data->smt_01_pkn); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('smt_01_bindo')); ?>:</b>
	<?php echo CHtml::encode($data->smt_01_bindo); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('smt_01_bingg')); ?>:</b>
	<?php echo CHtml::encode($data->smt_01_bingg); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('smt_01_mat')); ?>:</b>
	<?php echo CHtml::encode($data->smt_01_mat); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('smt_01_ipa')); ?>:</b>
	<?php echo CHtml::encode($data->smt_01_ipa); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('smt_01_ips')); ?>:</b>
	<?php echo CHtml::encode($data->smt_01_ips); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('smt_01_seni')); ?>:</b>
	<?php echo CHtml::encode($data->smt_01_seni); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('smt_01_or')); ?>:</b>
	<?php echo CHtml::encode($data->smt_01_or); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('smt_01_tik')); ?>:</b>
	<?php echo CHtml::encode($data->smt_01_tik); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('smt_02_pai')); ?>:</b>
	<?php echo CHtml::encode($data->smt_02_pai); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('smt_02_pkn')); ?>:</b>
	<?php echo CHtml::encode($data->smt_02_pkn); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('smt_02_bindo')); ?>:</b>
	<?php echo CHtml::encode($data->smt_02_bindo); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('smt_02_bingg')); ?>:</b>
	<?php echo CHtml::encode($data->smt_02_bingg); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('smt_02_mat')); ?>:</b>
	<?php echo CHtml::encode($data->smt_02_mat); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('smt_02_ipa')); ?>:</b>
	<?php echo CHtml::encode($data->smt_02_ipa); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('smt_02_ips')); ?>:</b>
	<?php echo CHtml::encode($data->smt_02_ips); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('smt_02_seni')); ?>:</b>
	<?php echo CHtml::encode($data->smt_02_seni); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('smt_02_or')); ?>:</b>
	<?php echo CHtml::encode($data->smt_02_or); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('smt_02_tik')); ?>:</b>
	<?php echo CHtml::encode($data->smt_02_tik); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('smt_03_pai')); ?>:</b>
	<?php echo CHtml::encode($data->smt_03_pai); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('smt_03_pkn')); ?>:</b>
	<?php echo CHtml::encode($data->smt_03_pkn); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('smt_03_bindo')); ?>:</b>
	<?php echo CHtml::encode($data->smt_03_bindo); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('smt_03_bingg')); ?>:</b>
	<?php echo CHtml::encode($data->smt_03_bingg); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('smt_03_mat')); ?>:</b>
	<?php echo CHtml::encode($data->smt_03_mat); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('smt_03_ipa')); ?>:</b>
	<?php echo CHtml::encode($data->smt_03_ipa); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('smt_03_ips')); ?>:</b>
	<?php echo CHtml::encode($data->smt_03_ips); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('smt_03_seni')); ?>:</b>
	<?php echo CHtml::encode($data->smt_03_seni); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('smt_03_or')); ?>:</b>
	<?php echo CHtml::encode($data->smt_03_or); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('smt_03_tik')); ?>:</b>
	<?php echo CHtml::encode($data->smt_03_tik); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('smt_04_pai')); ?>:</b>
	<?php echo CHtml::encode($data->smt_04_pai); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('smt_04_pkn')); ?>:</b>
	<?php echo CHtml::encode($data->smt_04_pkn); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('smt_04_bindo')); ?>:</b>
	<?php echo CHtml::encode($data->smt_04_bindo); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('smt_04_bingg')); ?>:</b>
	<?php echo CHtml::encode($data->smt_04_bingg); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('smt_04_mat')); ?>:</b>
	<?php echo CHtml::encode($data->smt_04_mat); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('smt_04_ipa')); ?>:</b>
	<?php echo CHtml::encode($data->smt_04_ipa); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('smt_04_ips')); ?>:</b>
	<?php echo CHtml::encode($data->smt_04_ips); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('smt_04_seni')); ?>:</b>
	<?php echo CHtml::encode($data->smt_04_seni); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('smt_04_or')); ?>:</b>
	<?php echo CHtml::encode($data->smt_04_or); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('smt_04_tik')); ?>:</b>
	<?php echo CHtml::encode($data->smt_04_tik); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('smt_05_pai')); ?>:</b>
	<?php echo CHtml::encode($data->smt_05_pai); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('smt_05_pkn')); ?>:</b>
	<?php echo CHtml::encode($data->smt_05_pkn); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('smt_05_binddo')); ?>:</b>
	<?php echo CHtml::encode($data->smt_05_binddo); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('smt_05_bingg')); ?>:</b>
	<?php echo CHtml::encode($data->smt_05_bingg); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('smt_05_mat')); ?>:</b>
	<?php echo CHtml::encode($data->smt_05_mat); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('smt_05_ipa')); ?>:</b>
	<?php echo CHtml::encode($data->smt_05_ipa); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('smt_05_ips')); ?>:</b>
	<?php echo CHtml::encode($data->smt_05_ips); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('smt_05_seni')); ?>:</b>
	<?php echo CHtml::encode($data->smt_05_seni); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('smt_05_or')); ?>:</b>
	<?php echo CHtml::encode($data->smt_05_or); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('smt_05_tik')); ?>:</b>
	<?php echo CHtml::encode($data->smt_05_tik); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('smt_06_pai')); ?>:</b>
	<?php echo CHtml::encode($data->smt_06_pai); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('smt_06_pkn')); ?>:</b>
	<?php echo CHtml::encode($data->smt_06_pkn); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('smt_06_bindo')); ?>:</b>
	<?php echo CHtml::encode($data->smt_06_bindo); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('smt_06_bingg')); ?>:</b>
	<?php echo CHtml::encode($data->smt_06_bingg); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('smt_06_mat')); ?>:</b>
	<?php echo CHtml::encode($data->smt_06_mat); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('smt_06_ipa')); ?>:</b>
	<?php echo CHtml::encode($data->smt_06_ipa); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('smt_06_ips')); ?>:</b>
	<?php echo CHtml::encode($data->smt_06_ips); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('smt_06_seni')); ?>:</b>
	<?php echo CHtml::encode($data->smt_06_seni); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('smt_06_or')); ?>:</b>
	<?php echo CHtml::encode($data->smt_06_or); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('smt_06_tik')); ?>:</b>
	<?php echo CHtml::encode($data->smt_06_tik); ?>
	<br />

	*/ ?>

</div>