<?php
/* @var $this QuizController */
/* @var $model Quiz */

$this->breadcrumbs=array(
	'Ulangan'=>array('index'),
	$sq->quiz->title=>array('/quiz/view','id'=>$sq->quiz_id),
	'Mark View'=>array('index'),
	$sq->id=>array('/quiz/markView', 'id'=>$sq->id)

);

/*$this->menu=array(
	array('label'=>'List Quiz', 'url'=>array('index')),
	array('label'=>'Create Quiz', 'url'=>array('create')),
	array('label'=>'Update Quiz', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Quiz', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Quiz', 'url'=>array('admin')),
);*/
//$total = count($total_question);
?>

<h1>Nilai Kuis <?php echo $sq->quiz->title; ?></h1>

<div class="row">
<div class="col-md-5  toppad  pull-right col-md-offset-3 ">
	
<br>
</div>
<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xs-offset-0 col-sm-offset-0 col-md-offset-3 col-lg-offset-3 toppad" >


  <div class="panel panel-info">
    <div class="panel-heading">
      <h3 class="panel-title"><?php echo $sq->user->display_name; ?></h3>
    </div>
    <div class="panel-body">
      <div class="row">
      	<div class="col-md-3 col-lg-3 " align="center"> <img alt="User Pic" src="<?php echo Yii::app()->baseUrl;?>/images/user-2.png" height="100px" class="img-circle"> </div>
        <div class=" col-md-9 col-lg-9 "> 
          <table class="table table-user-information table-responsive">
            <tbody>
	          <tr>
	            <td>Kuis</td>
	            <td><?php echo $sq->quiz->title; ?></td>
	          </tr>
	          <tr>
	            <td>Kelas</td>
	            <?php
	            	if(!empty($sq->user->class_id)){
	            		$kelas=$sq->user->class->name;
	            	}else{
	            		$kelas=NULL;
	            	} 
	            ?>
	            <td><?php echo $kelas; ?></td>
	          </tr>
	          <tr>
	            <td>Nilai</td>
	            <td><?php echo $sq->score; ?></td>
	          </tr>
	          <tr>
	            <td>Jumlah Pertanyaan</td>
	            <td><?php echo $sq->quiz->total_question; ?></td>
	          </tr>
	          <tr>
                <td>Jumlah Jawaban Benar</td>
                <td><?php echo $sq->right_answer; ?></td>
              </tr>
              <tr>
                <td>Jumlah Jawaban Salah</td>
                <td><?php echo $sq->wrong_answer; ?></td>
              </tr>
              <tr>
                <td>Tidak Dijawab</td>
                <td><?php echo $sq->unanswered; ?></td>
              </tr>
         
            </tbody>
          </table>
          
        </div>
      </div>
    </div>
    
  </div>
</div>
</div>