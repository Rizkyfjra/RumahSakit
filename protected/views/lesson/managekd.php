<?php
/* @var $this LessonController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
  'Pelajaran',
);

/*$this->menu=array(
  array('label'=>'Create Lesson', 'url'=>array('create')),
  array('label'=>'Manage Lesson', 'url'=>array('admin')),
);*/
?>

<div class="col-md-12">
<div class="row panel-medidu" style="padding:10px;text-align:center;">
  <table class="table table-bordered table-condensed table-responisve well">
    <tbody>
      <tr>
        <th class="info">No</th>
        <th class="info">ID Lesson List</th>
        <th class="info">Nama Pelajaran</th>
        <th class="info">Kelas</th>
        <th class="info">Status</th>  
        <th class="info">Action</th>          
      </tr>
  <?php 
  $no = 1;
  foreach ($dataProvider as $value) { ?>
      <tr>
        <td><?php echo $no; ?></td>
        <td><?php echo $value['id']; ?></td>
        <td><?php echo $value['lesson_name']; ?></td>
        <td><?php echo $value['level']; ?></td>
        <td>Sudah</td>
        <td><?php echo CHtml::link('Update <i class="fa fa-hand-pointer-o"></i>', array('nilaikd', 'id'=>$value['id'],'level'=>$value['level']),array('class'=>'btn btn-info')); ?></td>
      </tr>
  <?php $no++; ?>
  <?php } ?>
    </tbody>
  </table>

</div>

<div class="text-center">
  <?php
    // $this->widget('CLinkPager', array(
    //               'pages'=>$dataProvider->pagination,
    //               ));
  ?>
</div>  
</div>