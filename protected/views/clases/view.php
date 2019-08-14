<?php
/* @var $this ClasesController */
/* @var $model Clases */

$optSchoolType = Option::model()->findByAttributes(array('key_config' => 'school_name'))->value;

$this->breadcrumbs = array(
    'Kelas' => array('index'),
    $model->name,
);

$this->menu = array(
    array('label' => 'List Clases', 'url' => array('index')),
    array('label' => 'Create Clases', 'url' => array('create')),
    array('label' => 'Update Clases', 'url' => array('update', 'id' => $model->id)),
    array('label' => 'Delete Clases', 'url' => '#', 'linkOptions' => array('submit' => array('delete', 'id' => $model->id), 'confirm' => 'Are you sure you want to delete this item?')),
    array('label' => 'Manage Clases', 'url' => array('admin')),
);


$kelas = ClassDetail::model()->findAll();
$fiturRekap = Option::model()->findAll(array('condition' => 'key_config LIKE "%fitur_rekap%"'));
?>

<h1>Daftar Pasien Kelas <?php echo $model->name; ?> 
    <?php if (Yii::app()->user->YiiKepsek || Yii::app()->user->YiiAdmin || Yii::app()->user->YiiTeacher) { ?>
        
        <?php if (strpos($optSchoolType, 'RIZKY FAJAR ANUGRAH') !== false) { ?>
        <span class="pull-right"><?php echo CHtml::link('LEGER', array('legeruas', 'id' => $model->id), array('class' => 'btn btn-primary')); ?></span>
        <?php }  ?>
        <span class="pull-right"><?php echo CHtml::link('Update Desc', array('UpdateDesc', 'id' => $model->id), array('class' => 'btn btn-success')); ?></span>
       <span class="pull-right"><?php echo CHtml::link('Download Data Profile', array('/userProfile/ShowTable', 'id' => $model->id), array('class' => 'btn btn-danger')); ?></span>
        <!-- <span class="pull-right"><?php echo CHtml::link('Print All UAS V2 (A4)', array('raporuasall', 'id' => $model->id, 'ver' => '2', 'ppr' => 'a4'), array('class' => 'btn btn-danger')); ?></span>
        <span class="pull-right"><?php echo CHtml::link('Print All UAS V2 (B5)', array('raporuasall', 'id' => $model->id, 'ver' => '2', 'ppr' => 'b5'), array('class' => 'btn btn-danger')); ?></span>
        <span class="pull-right"><?php echo CHtml::link('Print All UAS V1 (A4)', array('raporuasall', 'id' => $model->id, 'ver' => '1', 'ppr' => 'a4'), array('class' => 'btn btn-danger')); ?></span>
        <span class="pull-right"><?php echo CHtml::link('Print All UAS V1 (B5)', array('raporuasall', 'id' => $model->id, 'ver' => '1', 'ppr' => 'b5'), array('class' => 'btn btn-danger')); ?></span>
        <span class="pull-right"><?php echo CHtml::link('Print All UTS', array('raporutsall', 'id' => $model->id), array('class' => 'btn btn-danger')); ?></span>
        --><span class="pull-right"><?php echo CHtml::link('Edit', array('update', 'id' => $model->id), array('class' => 'btn btn-primary')); ?></span>
        <br/>
    <?php } ?>
    <?php if (Yii::app()->user->YiiKepsek || Yii::app()->user->YiiAdmin || Yii::app()->user->YiiTeacher) { ?>
        <!-- <span class="pull-right"><?php //echo CHtml::link('Print Semua <i class="fa fa-print"></i>', array('printAll','id'=>$model->id),array('class'=>'btn btn-default')); ?></span> -->
        <span class="pull-right">
            <?php
            if (empty($fiturRekap) || $fiturRekap[0]->value != 2) {
                //echo CHtml::link('Download Semua <i class="fa fa-hand-o-down"></i>', array('downloadAll','id'=>$model->id),array('class'=>'btn btn-success'));
            }
            ?>
        </span>
        <span class="pull-right"><?php //echo CHtml::link('Leger <i class="fa fa-hand-o-down"></i>', array('downloadLeger','id'=>$model->id),array('class'=>'btn btn-warning')); ?></span>
    <?php } ?>
</h1>
<?php
//if(!empty($tugas)){ 
echo "<form method='POST' name='checkform' id='checkform' action='" . Yii::app()->createUrl("Clases/pindahKelas") . "'>";
?>
<table class="table table-hover table-responsive well table-bordered">
    <th>No</th>
    <th>Nama</th>
    <th>NIP/No Pasien</th>
    <?php if (empty($fiturRekap) || $fiturRekap[0]->value != 2) { ?>
        <th></th>
    <?php } ?>
<?php if (Yii::app()->user->YiiWali || Yii::app()->user->YiiKepsek || Yii::app()->user->YiiAdmin) { ?>
        <th class="text-center">
            <input type="submit" value="Pindah Kelas" class="btn btn-danger">
            <br/>
            <select name="pindahKelas">
                <?php foreach ($kelas as $kls) { ?>
                    <?php if ($model->id == $kls->id) { ?>
                        <option value="<?php echo $kls->id; ?>" selected><?php echo $kls->name; ?></option>
                    <?php } else { ?> 
                        <option value="<?php echo $kls->id; ?>"><?php echo $kls->name; ?></option>
                    <?php } ?>
    <?php } ?>
            </select>
            </br>
            <input type="checkbox" id="selectAll">
        </th>
    <?php } ?>
    <?php $no = 1; ?>
<?php foreach ($siswa as $key) { ?>
        <tr>
            <td><?php echo $no; ?></td>
            <td><?php echo CHtml::link($key->display_name, array('user/view', 'id' => $key->id)); ?></td>
            <td><?php echo CHtml::link($key->username, array('user/view', 'id' => $key->id)); ?></td>
            <?php
            // if (Yii::app()->user->YiiTeacher) {
            //          $kelasnyawali = ClassDetail::model()->findAll(array('condition' => 'teacher_id = ' . Yii::app()->user->id));
            //         	}
            ?>
            <?php if (empty($fiturRekap) || $fiturRekap[0]->value != 2) { ?>
                <td>
                    <?php if (Yii::app()->user->YiiAdmin || Yii::app()->user->YiiKepsek || Yii::app()->user->YiiWali) { ?>

                        <?php //echo CHtml::link("Print Raport", array('clases/raportSiswa','id'=>$key->id,'type'=>1),array('class'=>'btn btn-primary'));?>
                        <?php echo CHtml::link("Biodata", array('clases/RaporBiodata', 'id' => $key->id), array('class' => 'btn btn-success')); ?>
                        <?php 
                        // $user_ortu = User::model()->findByAttributes(array('child_id' => $key->id));
                        // if ($user_ortu) {
                        //     echo CHtml::link("Akun Ortu", array('clases/AkunOrtu', 'id' => $key->id), array('class' => 'btn btn-warning')); 
                        // }
                        ?>
                        <?php 
                             if (strpos($optSchoolType, 'SMP') !== false) {
                             echo CHtml::link("Print UTS", array('clases/RaporLkhbs', 'id' => $key->id, 'uts' => true), array('class' => 'btn btn-info'));
                             } else {
                             echo CHtml::link("Print UTS", array('clases/RaporUts', 'id' => $key->id, 'uts' => true), array('class' => 'btn btn-info'));  
                             }
                         ?>
                        <?php 
                        
                        if (strpos($optSchoolType, 'SMP') !== false) {
                            echo CHtml::link("LKHBS", array('clases/RaporLkhbs', 'id' => $key->id), array('class' => 'btn btn-info')); 
                        }

                        ?>
                        <br/><br/>
                        <?php echo CHtml::link("Print UAS V1 (B5)", array('clases/RaporUAS', 'id' => $key->id, 'ver' => '1', 'ppr' => 'b5', 'reg' => $no), array('class' => 'btn btn-info')); ?>
                        <?php echo CHtml::link("Print UAS V1 (A4)", array('clases/RaporUAS', 'id' => $key->id, 'ver' => '1', 'ppr' => 'a4', 'reg' => $no), array('class' => 'btn btn-info')); ?>
                        <br/>
                        <?php echo CHtml::link("Print UAS V2 (B5)", array('clases/RaporUAS', 'id' => $key->id, 'ver' => '2', 'ppr' => 'b5', 'reg' => $no), array('class' => 'btn btn-info')); ?>
                        <?php echo CHtml::link("Print UAS V2 (A4)", array('clases/RaporUAS', 'id' => $key->id, 'ver' => '2', 'ppr' => 'a4', 'reg' => $no), array('class' => 'btn btn-info')); ?>
                        <br/>
                        <?php echo CHtml::link("Print UAS V3 (B5)", array('clases/RaporUAS', 'id' => $key->id, 'ver' => '3', 'ppr' => 'b5', 'reg' => $no), array('class' => 'btn btn-info')); ?>
                        <?php echo CHtml::link("Print UAS V3 (A4)", array('clases/RaporUAS', 'id' => $key->id, 'ver' => '3', 'ppr' => 'a4', 'reg' => $no), array('class' => 'btn btn-info')); ?>
                        <br/>
                        <?php echo CHtml::link("Print UAS V4 (B5)", array('clases/RaporUAS', 'id' => $key->id, 'ver' => '4', 'ppr' => 'b5', 'reg' => $no), array('class' => 'btn btn-info')); ?>
                        <?php echo CHtml::link("Print UAS V4 (A4)", array('clases/RaporUAS', 'id' => $key->id, 'ver' => '4', 'ppr' => 'a4', 'reg' => $no), array('class' => 'btn btn-info')); ?>
                        <br/>
                        <?php echo CHtml::link("Print UAS V5 (B5)", array('clases/RaporUAS', 'id' => $key->id, 'ver' => '5', 'ppr' => 'b5', 'reg' => $no), array('class' => 'btn btn-info')); ?>
                        <?php echo CHtml::link("Print UAS V5 (A4)", array('clases/RaporUAS', 'id' => $key->id, 'ver' => '5', 'ppr' => 'a4', 'reg' => $no), array('class' => 'btn btn-info')); ?>

                    <?php } ?>
                </td>
            <?php } ?>
            <td class="text-center">
                <?php if (Yii::app()->user->YiiWali || Yii::app()->user->YiiKepsek || Yii::app()->user->YiiAdmin) { ?>
                    <input type="checkbox" class="pindah" name="pindahSiswa[]" value="<?php echo $key->id; ?>"><br>
                <?php } ?>
            </td>
        </tr>
        <?php $no++; ?>
    <?php } ?>
</table>
<?php echo "</form>"; ?>
<?php /* $this->widget('zii.widgets.CDetailView', array(
  'data'=>$model,
  'attributes'=>array(
  'id',
  'name',
  ),
  )); */ ?>
<script>
    $('#selectAll').click(function (event) {  //on click
        if (this.checked) { // check select status
            $('.pindah').each(function () { //loop through each checkbox
                this.checked = true;  //select all checkboxes with class "checkbox1"
            });
        } else {
            $('.pindah').each(function () { //loop through each checkbox
                this.checked = false; //deselect all checkboxes with class "checkbox1"
            });
        }
    });
</script>
