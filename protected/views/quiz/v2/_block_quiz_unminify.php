<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl ?>/libraries/bootstrap/css/bootstrap.min.css"/>
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl ?>/libraries/bootstrap-select/css/bootstrap-select.min.css"/>
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl ?>/libraries/bootstrap-notifications/bootstrap-notifications.min.css"/>
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl ?>/libraries/font-awesome/css/font-awesome.min.css"/>
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl ?>/libraries/jquery-chosen/jquery.chosen.css"/>
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl ?>/libraries/jquery-datetime/jquery.datetimepicker.css"/>
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl ?>/css/main.css"/>
        <script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl ?>/libraries/jquery/jquery.min.js"></script>
        <script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl ?>/libraries/md5.js"></script> 
        <script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl ?>/libraries/enc-base64-min.js"></script>     
<?php
  $passcode = NULL;
  if(!empty($model->passcode)){
    $passcode = $model->passcode;
  }
?>
<div class="container-fluid">
  <div class="row">
    <div class="col-lg-12 container-soal-floating">
      <!-- The overlay -->
      <div id="overlay" class="overlay">
        <div class="overlay-content text-center">
          <img src="<?php echo Yii::app()->theme->baseUrl ?>/images/cross.png" alt="" width="150px">
          <h1>Ujian ini diblock</h1>
          <p>Silakan masukan kode untuk membuka ujian ini</p>
          <div class="container">
            <div class="row">
              <div class="col-md-4 col-md-offset-4">
                <input id="kode" type="password" class="form-control input-lg input-pn-center" placeholder="Kode Akses" style="margin-bottom: 5px;">
                <button class="btn btn-pn-primary btn-block">Buka Akses</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
var passcode = "<?php echo md5($passcode) ?>";
     $("#kode").change(function(){
          kode = $("#kode").val();
          kode =  CryptoJS.MD5(kode);
          if(kode == passcode){
            localStorage.setItem('status',"0");
            window.location.href = "<?php echo Yii::app()->baseUrl ?>/quiz/startQuiz/<?php echo $model->id;?>";
          }else{
            // location.reload();
            alert("passcode salah!");
          } 
        });
  </script>
