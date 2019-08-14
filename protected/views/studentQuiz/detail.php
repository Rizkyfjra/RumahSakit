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
<h1>Nilai Kuis <?php echo $model->quiz->title; ?></h1>

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
	            <td>Nama Ulangan</td>
	            <td><?php echo $model->quiz->title; ?></td>
	          </tr>
            <tr>
              <td>Nilai</td>
              <td><?php echo $model->score; ?></td>
            </tr>
            <tr>
              <td>Kode Soal</td>
              <td>
                <?php 
                  if(!empty($soal)){
                    echo $soal->id;
                  } 
                ?>
              </td>
            </tr>
	          <tr>
	            <td>Judul Soal</td>
	            <td><?php
	            	if(!empty($soal)){
	            		echo $soal->title;
	            	}
	            ?>
              </td>
	          </tr>
            </tbody>
          </table>
          
        </div>
      </div>
      <hr>
    </div>
    
  </div>
  <div class="row well">
  <p>Beri Nilai</p>
  <form method="post" action="<?php echo Yii::app()->baseUrl;?>/studentQuiz/nilai">
    <input type="number" name="nilai" class="form-control">
    <input type="hidden" value="<?php echo $model->id;?>" name="sc">
    <br>
    <input type="submit" value="Nilai" class="btn btn-primary">
  </form> 
  </div>
  <h3>Jawaban Siswa</h3>
  <?php $jawaban=json_decode($model->student_answer);?>
  <?php if(!empty($jawaban)){ ?>
    <?php foreach ($jawaban as $key => $value) { ?>
        <?php if($soal->id == $key){ ?>
          <div class="row well">
            <?php echo $value;?>
          </div>
        <?php } ?>
    <?php } ?>
  <?php } ?>
</div>

</div>
<script type="text/javascript">
localStorage.clear();
console.log(localStorage);
</script>