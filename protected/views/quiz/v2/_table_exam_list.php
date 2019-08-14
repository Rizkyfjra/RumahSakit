<?php
  $choices = json_decode($detail->choices);
  $jumChoices = count($choices);
?>
<tr>
  <td><?php echo $no; ?></td>
  <td class="collapsible-row-container">
    <div class="collapsible-row">
      <div>
        <?php echo $detail->text; ?>
      </div>
      <br/>
      <div class="clearfix"></div>
      <div class="exam-answer">
        <h4>Pilihan Jawaban</h4>
        <?php
          for($i=0; $i<$jumChoices; $i++){
            if($choices[$i]==$detail->key_answer){
        ?>
        <div class="alert alert-success" role="alert">
        <?php
            }else{
        ?>
        <div class="alert alert-danger" role="alert">
        <?php
            }
        ?>
          <?php
            echo $choices[$i]
          ?>
        </div>
        <?php
          }
        ?>
      </div>
      <br/><br/>
    </div>
  </td>
  <td>
    <span class="text-green text-bold"><?php echo $persenBenar; ?>%</span>
  </td>
  <td>
    <span class="text-red text-bold"><?php echo $persenSalah; ?>%</span>
  </td>
  <td>
    <div class="btn-group">
      <?php if(Yii::app()->user->YiiTeacher || Yii::app()->user->YiiAdmin){ ?>
      <a href="<?php echo $this->createUrl('/questions/update/'.$detail->id.'?quiz_id='.$quiz_id) ?>" class="btn btn-primary btn-xs">
        <i class="fa fa-pencil"></i>
      </a>
      <a href="<?php echo $this->createUrl('/quiz/deleteQuestion?quiz='.$quiz_id.'&question='.$key) ?>" class="btn btn-danger btn-xs" onclick="return confirm('Yakin Menghapus Soal Ini Dari Ujian ?');">
        <i class="fa fa-trash"></i>
      </a>
      <?php } ?>
    </div>
  </td>
</tr>
