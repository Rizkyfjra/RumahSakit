<?php
  if(!empty($value->lesson_id)){
    if(!$value->lesson->moving_class == 1){
      $idkelasnya = $value->lesson->class->id;
    }else{
      $idkelasnya = $value->lesson->grade->id;
    }
    
    $cekTotalSiswa=User::model()->findAll(array('condition'=>'class_id = '.$idkelasnya));
    $totalSiswa=count($cekTotalSiswa);
  }else{
    $totalSiswa = 0;
  }

  if(!Yii::app()->user->YiiStudent){
    $cekTotalSudah=StudentQuiz::model()->findAll(array('condition'=>'quiz_id = '.$value->id));
    $totalSudah=count($cekTotalSudah);

    if($totalSiswa != 0){
      $totalBelum = $totalSiswa - $totalSudah;
    } else {
      $totalBelum = 0;
    }

    if($totalSiswa!=0){
      $persenSudah = round(($totalSudah/$totalSiswa) * 100, 2);
      $persenBelum = round(($totalBelum/$totalSiswa) * 100, 2);
    }else{
      $persenSudah = 0;
      $persenBelum = 0;
    }
  }

  if(!empty($value->title)){
    $judul = ucwords(strtolower($value->title));
    
    $judul = str_replace("Uas", "UAS", $judul);
    $judul = str_replace("Uts", "UTS", $judul);
    $judul = str_replace("Uh", "UH", $judul);
    $judul = str_replace("Us", "US", $judul);
    $judul = str_replace("bk", "BK", $judul);
    
    $judul = str_replace("Tp", "TP", $judul);
    $judul = str_replace("To", "TO", $judul);
    $judul = str_replace("Ta", "TA", $judul);
  }
?>
<tr>
  <th class="text-center"><?php echo $no; ?></th>
  <td class="collapsible-row-container">
    <?php echo CHtml::link(CHtml::encode($judul), array('/quiz/view', 'id'=>$value->id)); ?>
  </td>
  <td>
    <?php if($value->status==1){ ?>
      <span id="status-<?php echo $value->id ?>" class="label label-success">Ditampilkan</span>
    <?php } else if($value->status==2){ ?>
      <span id="status-<?php echo $value->id ?>" class="label label-danger">Ditutup</span>
    <?php } else { ?>
      <span id="status-<?php echo $value->id ?>" class="label label-warning">Draft</span>
    <?php } ?>
  </td>
  <?php if(!Yii::app()->user->YiiStudent){ ?>  
  <td>
    <span class="text-green text-bold"><?php echo $persenSudah; ?>%</span>
  </td>
  <td>
    <span class="text-red text-bold"><?php echo $persenBelum; ?>%</span>
  </td>
  <?php } ?>
  <td>
    <?php 
      if(!empty($value->lesson_id)){
        echo ucwords($value->lesson->name);
      } 
    ?>
  </td>
  <td>
    <?php 
      if(!empty($value->lesson_id)){
        if($value->lesson->moving_class == 1){
          echo strtoupper($value->lesson->grade->name);
        }else{
          echo strtoupper($value->lesson->class->name);
        } 
      } 
    ?>
  </td>
  <td>
    <?php
      if(!empty($value->created_by)){
        echo $value->teacher->display_name;
      }
    ?>
  </td>
  <?php if(!Yii::app()->user->YiiStudent){ ?>
  <td>
    <div class="btn-group">
      <?php echo CHtml::link('<i class="fa fa-eye"></i>',array('view', 'id'=>$value->id), array('class'=>'btn btn-success btn-xs')); ?>
      <?php echo CHtml::link('<i class="fa fa-pencil"></i>',array('update', 'id'=>$value->id), array('class'=>'btn btn-primary btn-xs')); ?>
      <?php echo CHtml::link('<i class="fa fa-trash"></i>',array('hapus', 'id'=>$value->id), array('class'=>'btn btn-danger btn-xs','title'=>'Hapus','confirm'=>'Yakin Menghapus Ujian Ini?')); ?>
    </div>
  </td>
  <td>
    <div class="text-center">
      <input type="checkbox" name="quiz[]" class="quiz" value="<?php echo $value->id ?>">
    </div>
  </td>
  <?php
    }else{
      $cekQuiz = StudentQuiz::model()->findByAttributes(array('quiz_id'=>$value->id,'student_id'=>Yii::app()->user->id));

      if(!empty($cekQuiz)){
        if($cekQuiz->attempt == $value->repeat_quiz){      
  ?>
  <td>
    <div class="btn-group">
      <?php echo CHtml::link('<i class="fa fa-times"></i> Sudah Mengerjakan',array('#'), array('class'=>'btn btn-danger btn-xs')); ?>
    </div>
  </td>
  <?php
        }else{
          if($value->status == 1){          
  ?>
  <td>
    <div class="btn-group">
      <?php echo CHtml::link('<i class="fa fa-pencil"></i> Mulai',array('startQuiz', 'id'=>$value->id, 'sq'=>$cekQuiz->id), array('class'=>'btn btn-pn-primary btn-xs')); ?>
    </div>
  </td>
  <?php
          }
        }
      }else{
        if($model->status == 1){        
  ?>
  <td>
    <div class="btn-group">
      <?php echo CHtml::link('<i class="fa fa-pencil"></i> Mulai',array('startQuiz', 'id'=>$value->id), array('class'=>'btn btn-pn-primary btn-xs')); ?>
    </div>
  </td>
  <?php
        }
      }
    }
  ?>
</tr>
