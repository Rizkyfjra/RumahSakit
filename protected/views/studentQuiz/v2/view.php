<?php
/* @var $this StudentQuizController */
/* @var $model StudentQuiz */

$this->breadcrumbs=array(
	'Ulangan Siswa'=>array('index'),
	$model->user->display_name,
);

/*$this->menu=array(
	array('label'=>'List StudentQuiz', 'url'=>array('index')),
	array('label'=>'Create StudentQuiz', 'url'=>array('create')),
	array('label'=>'Update StudentQuiz', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete StudentQuiz', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage StudentQuiz', 'url'=>array('admin')),
);*/
?>
<h1>Nilai Kuis <?php echo $model->quiz->title; ?>
</h1>

<div class="row">
<div class="col-md-5  toppad  pull-right col-md-offset-3 ">
	
<br>
</div>
<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xs-offset-0 col-sm-offset-0 col-md-offset-3 col-lg-offset-3 toppad" >


  <div class="panel panel-info">
    <div class="panel-heading">
      <h3 class="panel-title"><?php echo $model->user->display_name; ?></h3>
    </div>
    <div class="panel-body">
      <div class="row">
      	<div class="col-md-3 col-lg-3 " align="center"> <img alt="User Pic" src="<?php echo Yii::app()->baseUrl;?>/images/user-2.png" height="100px" class="img-circle"> </div>
        <div class=" col-md-9 col-lg-9 "> 
          <table class="table table-user-information table-responsive">
            <tbody>
	          <tr>
	            <td>Nama Ulanganasdfsd</td>
	            <td><?php echo $model->quiz->title; ?></td>
	          </tr>
	          <tr>
	            <td>Kelas</td>
	            <?php
	            	if(!empty($model->user->class_id)){
	            		$kelas=$model->user->class->name;
	            	}else{
	            		$kelas=NULL;
	            	} 
	            ?>
	            <td><?php echo $kelas; ?></td>
	          </tr>
	          <?php if(Yii::app()->user->YiiStudent){ ?>
              <?php if($model->quiz->show_nilai == 1){ ?>
    	          <tr>
    	            <td>Nilai</td>
    	            <td><?php echo $model->score; ?></td>
    	          </tr>
                 <?php if(!empty($model->essay_score)){ ?>
                  <tr>
                    <td>Nilai Essay</td>
                    <td><?php echo $model->essay_score;?></td>
                  </tr>
                 <?php } ?>  
    	          <tr>
    	            <td>Jumlah Pertanyaan</td>
    	            <td><?php echo $model->quiz->total_question; ?></td>
    	          </tr>
    	          <tr>
                    <td>Jumlah Jawaban Benar</td>
                    <td><?php echo $model->right_answer; ?></td>
                  </tr>
                  <tr>
                    <td>Jumlah Jawaban Salah</td>
                    <td><?php echo $model->wrong_answer; ?></td>
                  </tr>
                  <tr>
                    <td>Tidak Dijawab</td>
                    <td><?php echo $model->unanswered; ?></td>
                  </tr>
              <?php } ?>   
              <?php }else{ ?>
                <tr>
                <td>Nilai</td>
                <td><?php echo $model->score; ?></td>
                </tr>
                 <?php if(!empty($model->essay_score)){ ?>
                  <tr>
                    <td>Nilai Essay</td>
                    <td><?php echo $model->essay_score;?></td>
                  </tr>
                 <?php } ?>  
              <tr>
                <td>Jumlah Pertanyaan</td>
                <td><?php echo $model->quiz->total_question; ?></td>
                </tr>
                <tr>
                    <td>Jumlah Jawaban Benar</td>
                    <td><?php echo $model->right_answer; ?></td>
                  </tr>
                  <tr>
                    <td>Jumlah Jawaban Salah</td>
                    <td><?php echo $model->wrong_answer; ?></td>
                  </tr>
                  <tr>
                    <td>Tidak Dijawab</td>
                    <td><?php echo $model->unanswered; ?></td>
                  </tr>
              <?php } ?>
         
            </tbody>
          </table>
          
        </div>
      </div>
      <hr>
    </div>
    
  </div>
</div>
<?php if(Yii::app()->user->YiiStudent){ ?>
  <?php if( $model->quiz->show_nilai == 1){ ?>
      
      <?php } ?>
    <?php }else{ ?>
      <div class="row">
          <form method="post" action="<?php echo Yii::app()->createUrl('/studentQuiz/nilai')?>">
          <table class="table table-hover table-bordered table-responsive table-condensed well ">
            <th>No Soal</th>
            <th>Tipe Soal</th>
            <th>Judul Soal</th>
            <th>Pertanyaan</th>
            <th>Pilihan</th>
            <th>Kunci Jawaban</th>
            <th>Jawaban Siswa</th>
            <th><input type="submit" value="Nilai" class="btn btn-primary"></th>
            <!-- <th>Poin Soal</th> -->
            <input type="hidden" value="<?php echo $model->id;?>" name="sc">
            <?php $jawaban=json_decode($model->student_answer,true);?>
            <?php $no=1;?>
            <?php if(!empty($jawaban)){ ?>
              <?php foreach ($jawaban as $key => $value) { ?>
                <?php $soal = Questions::model()->findByPk($key);?>
                <?php if(empty($soal->id_lama)){ ?>
                  <?php $path_image = Clases::model()->path_image($key);?>
                <?php }else{ ?> 
                  <?php $path_image = Clases::model()->path_image($soal->id_lama);?>
                <?php } ?>
                <tr>
                  <td><?php echo $no;?></td>
                  <td>
                    <?php
                      if($soal->type == 1){ 
                        echo "Isian";
                      }elseif ($soal->type ==2) {
                        echo CHtml::link('Esai', array('detail','id'=>$model->id,'soal'=>$soal->id));
                      }else{
                        echo "Pilihan Ganda";
                      }
                    ?>
                  </td>
                  <td>
                    <?php
                      if($soal->type == 2){ 
                        echo CHtml::link($soal->title, array('detail','id'=>$model->id,'soal'=>$soal->id));
                      }else{
                        echo $soal->title;
                      }
                    ?>
                  </td>
                  <td><?php echo $soal->text;?></td>
                  <?php $pilihan = json_decode($soal->choices,true);?>
                  <?php $gambar = json_decode($soal->choices_files,true);?>
                  <td>
                    <?php
                      if(!empty($pilihan)){
                          echo "<ol type='A'>";
                          foreach ($pilihan as $k => $val) {
                            echo "<li>".$val;
                            if(!empty($gambar[$k])){ ?>
                              <?php if(empty($soal->id_lama)){ ?>
                                <?php $img_pil = Yii::app()->baseUrl.'/images/question/'.$path_image.$k.'/'.$gambar[$k];?>
                              <?php }else{ ?>
                                <?php $img_pil = Yii::app()->baseUrl.'/images/question/remote/'.$path_image.$k.'/'.$gambar[$k];?>
                              <?php } ?>
                              <img src="<?php echo $img_pil;?>" class="img-responsive">
                            <?php }  
                            echo "</li>";
                          }
                          echo "</ol>";
                      }
                    ?>
                  </td>
                  <td><?php echo $soal->key_answer;?></td>
                  <td><?php echo $value;?></td>
                  <td>
                    <?php if($soal->type != 2){ ?>
                    <div style="font-size: 20px">
                      <?php
                        if($soal->key_answer == $value){
                          echo "<span class='label label-success label-as-badge'>Benar</span>";
                        }else{
                          echo "<span class='label label-danger label-as-badge'>asdfasdf</span>";
                        }
                      ?>
                    <?php }else{ ?>
                       <!-- <input type="number" name="nilai[]" class="form-control">  -->
                    <?php } ?>
                    </div>
                  </td>
                  <!-- <td></td> -->
                </tr>
                <?php $no++;?>  
              <?php } ?>
            <?php } ?>
          </table>
          </form>
        </div>
    <?php } ?>  
</div>
<script type="text/javascript">
//localStorage.clear();
console.log(localStorage);

$('img').click(function(){
  alert('naon');
});

</script>