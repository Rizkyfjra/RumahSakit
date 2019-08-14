<?php
/* @var $this LessonController */
/* @var $model Lesson */

$this->breadcrumbs=array(
  'Pelajaran'=>array('index'),
  $model->name,
);

Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/chosen.jquery.js',CClientScript::POS_HEAD);
Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/css/chosen.css');

$copy_url = null;
/*$this->menu=array(
  array('label'=>'List Lesson', 'url'=>array('index')),
  array('label'=>'Create Lesson', 'url'=>array('create')),
  array('label'=>'Update Lesson', 'url'=>array('update', 'id'=>$model->id)),
  array('label'=>'Delete Lesson', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
  array('label'=>'Manage Lesson', 'url'=>array('admin')),
);*/

/*$hrf = range('A', 'Z');
print_r($hrf[0]);*/

$chap = '';
$assign = '';
$quiz = '';
$lks = '';

switch ($type) {
  case "materi":
      $chap = 'active';
    break;
    case "tugas":
      $assign = 'active';
    break;
    case "ulangan":
      $quiz = 'active';
    break;
    case "lks":
      $lks = 'active';
    break;
    default:
      $lks = 'active';
}

/*echo "<pre>";
print_r($datas);
echo "</pre>";*/
$fiturUlangan = Option::model()->findAll(array('condition'=>'key_config LIKE "%fitur_ulangan%"'));
$fiturTugas = Option::model()->findAll(array('condition'=>'key_config LIKE "%fitur_tugas%"'));
$fiturMateri = Option::model()->findAll(array('condition'=>'key_config LIKE "%fitur_materi%"'));
$fiturRekap = Option::model()->findAll(array('condition'=>'key_config LIKE "%fitur_rekap%"'));

?>

<div class="row">
<div class="col-md-12">
      <?php if(!Yii::app()->user->YiiStudent){ ?>
        <p class="text-right">
            <button type="button" title="Tambah Siswa Dari Tabel" class="btn btn-default btn-responsive" data-toggle="modal" data-target="#newStudent">
              <i class="fa fa-user"></i> <i class="fa fa-plus"></i>
            </button><span><button type="button" title="Tambah Siswa Dari Excel" class="btn btn-primary btn-responsive" data-toggle="modal" data-target="#copyExcel">
             <i class="fa fa-user"></i> <i class="fa fa-file-excel-o"></i></i>
            </button></span>
        </p>
      <?php } ?>
  <div class="panel panel-info">
    <div class="panel-heading">
      <?php
                if($model->moving_class == 1){
                  $nama_kelas = $model->grade->name;
                }else{
                  $nama_kelas = $model->class->name; 
                }
               
      ?>
      <h3 class="panel-title"><?php echo $model->name.", ".$model->users->display_name.", ".$nama_kelas." "; ?>
               <?php if(Yii::app()->user->YiiAdmin){ ?> 
              <span style="float: right;margin-top:-5px;"><?php echo CHtml::link("<i class='glyphicon glyphicon-remove'></i>",array('hapus','id'=>$model->id),array('class'=>'btn btn-danger btn-xs',"confirm"=>"Anda yakin akan menghapus pelajaran ini ?"));?></span>
              <span style="float: right;margin-top:-5px;"><?php echo CHtml::link("<i class='glyphicon glyphicon-edit'></i>",array('update','id'=>$model->id),array('class'=>'btn btn-success btn-xs'));?></span>
              <?php } ?>
      </h3>
    </div>
    <div class="panel-body">

       <?php if(!Yii::app()->user->YiiStudent) { ?>
            
            <span class="pull-right">
              <?php 
               echo '<a href="'.Yii::app()->createUrl('/clases/UpdateDesc/'.$model->class->id.'?lesson_id='.$model->id).'"><button type="button" class="btn btn-danger pull-right">Update Desc</button></a>';  
               echo '<a href="'.Yii::app()->createUrl('/lesson/NilaiKetSikapDua/'.$model->id).'"><button type="button" class="btn btn-primary pull-right">Input Nilai Rapor KET</button></a>';
               echo '<a href="'.Yii::app()->createUrl('/lesson/NilaiKetSikap/'.$model->id).'"><button type="button" class="btn btn-primary pull-right">Input Nilai Rapor PENG</button></a>';
               echo '<a href="'.Yii::app()->createUrl('/lesson/NilaiKdDua/'.$model->id).'"><button type="button" class="btn btn-danger pull-right">Input Deskripsi KD KET</button></a>';  
               echo '<a href="'.Yii::app()->createUrl('/lesson/NilaiKd/'.$model->id).'"><button type="button" class="btn btn-danger pull-right">Input Deskripsi KD PENG</button></a>';
             
              
                if(empty($fiturMateri) || $fiturMateri[0]->value != 2){
                  echo CHtml::link('Tambah Materi', array('/chapters/create','lesson_id'=>$model->id),array('class'=>'btn btn-info'));
                }
              ?>
              <?php if(empty($fiturRekap) || $fiturRekap[0]->value != 2) { ?>
                <?php echo CHtml::link('Rekap Nilai',array('/lesson/rekapNilai', 'id'=>$model->id),array('class'=>'btn btn-success')); ?>
              <?php } ?>

               <button type="button" class="btn btn-warning btn-responsive" data-toggle="modal" data-target="#penilaianModal">
                Buat Penilaian <i class="fa fa-clipboard"></i>
              </button>
              <?php //echo CHtml::link('Tambah Tugas', array('/assignment/create'),array('class'=>'btn btn-warning'));?>
              <?php //echo CHtml::link('Tambah Ulangan', array('/quiz/create'),array('class'=>'btn btn-info'));?>
              <?php 
                if(empty($fiturUlangan) || $fiturUlangan[0]->value != 2){ 
                  echo CHtml::link('Bank Soal', array('/questions'),array('class'=>'btn btn-success'));
                }
              ?>
            </span>
       <hr>        
      <?php } ?>
         
    <!--    <table class="table table-user-information table-responsive">
        <tbody>
          <tr>
            <td>Nama Pelajaran</td>
            <td><?php //echo $model->name; ?></td>
          </tr> 
        </tbody>
      </table> -->
      <div class="row">
        <div class="col-md-2">
        <ul class="nav nav-pills nav-stacked">
          <li class="<?php echo $chap;?>">
            <?php
              if(empty($fiturMateri) || $fiturMateri[0]->value != 2){ 
                echo CHtml::link('Materi', array('view','id'=>$model->id,'type'=>'materi'));
              }
            ?> 
          </li>
          <li class="<?php echo $assign;?>">
            <?php
              if(empty($fiturTugas) || $fiturTugas[0]->value != 2){  
                echo CHtml::link('Tugas', array('view','type'=>'tugas','id'=>$model->id));
              }
            ?>
          </li>
          <li class="<?php echo $quiz;?>">
            <?php
              if(empty($fiturUlangan) || $fiturUlangan[0]->value != 2){  
                echo CHtml::link('Ulangan', array('view','type'=>'ulangan','id'=>$model->id));
              }
            ?>
          </li>
          <li class="<?php echo $lks;?>">
            <?php echo CHtml::link('Siswa', array('view','type'=>'lks','id'=>$model->id))?>
          </li>
        </ul>
        </div>
        <div class="col-md-6">
            <?php if(!empty($datas)){ ?>
                <?php if($type == "materi"){ ?>
                    <?php if(empty($fiturMateri) || $fiturMateri[0]->value != 2){ ?>
                      <ol>
                      <?php foreach ($datas as $mtr) { ?>
                        <?php
                        if (!Yii::app()->user->YiiStudent) {
                         echo "<li>".CHtml::link($mtr->title, array('/chapters/view','id'=>$mtr->id))." ".CHtml::link(' <i class="fa fa-times"></i>', array('/chapters/hapus','id'=>$mtr->id),array('class'=>'btn btn-danger btn-sm','title'=>'Hapus','confirm'=>'Yakin Menghapus Materi Ini?'))."</li>";
                        } else {
                         echo "<li>".CHtml::link($mtr->title, array('/chapters/view','id'=>$mtr->id))."</li>"; 
                        }
                        ?>
                      <?php } ?>
                      </ol>
                    <?php } ?>  
                <?php } elseif($type == "tugas"){ ?>
                  <?php if(empty($fiturTugas) || $fiturTugas[0]->value != 2){ ?>
                    <ol>
                      <?php foreach ($datas as $mtr) { ?>
                        <?php 
                         if (!Yii::app()->user->YiiStudent) {
                            echo "<li>".CHtml::link($mtr->title, array('/assignment/view','id'=>$mtr->id))." ".CHtml::link(' <i class="fa fa-times"></i>', array('/assignment/hapus','id'=>$mtr->id),array('class'=>'btn btn-danger btn-sm','title'=>'Hapus','confirm'=>'Yakin Menghapus Tugas Ini?'))."</li>";
                         } else {
                            echo "<li>".CHtml::link($mtr->title, array('/assignment/view','id'=>$mtr->id))."</li>";
                         } 
                        ?>
                      <?php } ?>
                    </ol>
                  <?php } ?>  
                <?php }elseif($type == "ulangan"){ ?>
                  <?php if(empty($fiturUlangan) || $fiturUlangan[0]->value != 2){ ?>
                    <ol>
                      <?php foreach ($datas as $mtr) { ?>
                        <?php
                         if (!Yii::app()->user->YiiStudent) {
                            echo "<li>".CHtml::link($mtr->title, array('/quiz/view','id'=>$mtr->id))." ".CHtml::link(' <i class="fa fa-times"></i>', array('/quiz/hapus','id'=>$mtr->id),array('class'=>'btn btn-danger btn-sm','title'=>'Hapus','confirm'=>'Yakin Menghapus Ulangan Ini?'))."</li>";
                         } else {
                            echo "<li>".CHtml::link($mtr->title, array('/quiz/view','id'=>$mtr->id))."</li>";
                         }
                        
                        ?>
                      <?php } ?>
                    </ol>
                  <?php } ?>  
                <?php }elseif($type == "lks" or empty($type)){ ?>
                  <ol>
                    <?php foreach ($datas as $lkss) { ?>
                      <?php 
                      if (!Yii::app()->user->YiiStudent) {
                        echo "<li>".CHtml::link($lkss->student->display_name, array('/user/view','id'=>$lkss->user_id))." ".CHtml::link(' <i class="fa fa-times"></i>', array('/lesson/hapusMurid','id'=>$lkss->id),array('class'=>'btn btn-danger btn-sm','title'=>'Hapus','confirm'=>'Yakin Menghapus Siswa Ini?'))."</li>";
                      } else {
                        //echo "<li>".CHtml::link($lkss->student->display_name, array('/user/view','id'=>$lkss->user_id))."</li>";
                      }
                      ?>
                    <?php } ?>
                  </ol>  
                <?php } ?>
            <?php } ?> 
        </div>
      </div>
      <?php if(!Yii::app()->user->YiiStudent){ ?>
      <div class="row">
<!--         <div class="col-md-12 text-right">
          
          <button type="button" class="btn btn-warning btn-responsive" data-toggle="modal" data-target="#penilaianModal">
            Buat Penilaian <i class="fa fa-clipboard"></i>
          </button>
          <?php //echo CHtml::link('Tambah Tugas', array('/assignment/create'),array('class'=>'btn btn-warning'));?>
          <?php //echo CHtml::link('Tambah Ulangan', array('/quiz/create'),array('class'=>'btn btn-info'));?>
          <?php 
            if(empty($fiturUlangan) || $fiturUlangan[0]->value != 2){ 
              echo CHtml::link('Bank Soal', array('/questions'),array('class'=>'btn btn-success'));
            }
          ?>
          <?php //echo CHtml::link('LKS', array('/lks/index','id_lesson'=>$model->id),array('class'=>'btn btn-primary'));?>
        </div> -->
        <div class="modal fade" id="penilaianModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><b>Bentuk Penilaian</b></h4>
              </div>
              <div class="modal-body">
                <?php 
                  if(empty($fiturTugas) || $fiturTugas[0]->value != 2){
                    echo CHtml::link('Tugas <i class="fa fa-list-alt"></i>', array('/assignment/create','lesson_id'=>$model->id),array('class'=>'btn  btn-info btn-responsive'));
                  }
                ?>
                <?php 
                  if(empty($fiturUlangan) || $fiturUlangan[0]->value != 2){
                    echo CHtml::link('Ulangan <i class="fa fa-language"></i>', array('/quiz/create','lesson_id'=>$model->id),array('class'=>'btn  btn-primary btn-responsive'));
                  }
                ?>
                <?php
                  if(empty($fiturTugas) || $fiturTugas[0]->value != 2){ 
                    echo CHtml::link('Praktek/Offline <i class="fa fa-pencil-square-o"></i>', array('/assignment/create','type'=>1,'lesson_id'=>$model->id),array('class'=>'btn  btn-success btn-responsive'));
                  }
                ?>
                <?php
                  if(empty($fiturTugas) || $fiturTugas[0]->value != 2){ 
                    echo CHtml::link('Keterampilan <i class="fa fa-magic"></i>', array('/skill/create','lesson_id'=>$model->id),array('class'=>'btn  btn-warning btn-responsive'));
                  }
                ?>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="modal fade" id="newStudent" tabindex="-1" role="dialog" aria-labelledby="myNewModalLabel">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myNewModalLabel"><b>Pilih Siswa</b></h4>
              </div>
              <div class="modal-body">
                  <?php if(!empty($students)){ ?>
                    <?php $table_url = Yii::app()->createUrl('/lesson/addFromTable/'.$model->id);?>
                    <div class="table-responsive">
                      <form method="post" action="<?php echo $table_url;?>" onsubmit="return confirm('Yakin menambahkan siswa ke kelas ini ?');">
                        <p class="text-right"><input type="reset" value="Reset" class="btn btn-warning"> <span><input type="submit" value="Tambah" class="btn btn-success"></span></p>
                      <table class="table table-bordered">
                        <tbody>
                          <th>NIS</th>
                          <th>Nama Siswa</th>
                          <th><input type="checkbox" id="selectAll"></th>
                          <?php foreach ($students as $value) { ?>
                            <tr>
                              <td><?php echo $value->username;?></td>
                              <td><?php echo $value->display_name;?></td>
                              <td><input type="checkbox" name="siswa[]" value="<?php echo $value->id;?>" class="murid"></td>
                            </tr>
                          <?php } ?> 
                        </tbody>
                      </table>
                      </form>
                    </div>
                  <?php } ?> 
                      <!-- <label>Siswa</label>
                        <select data-placeholder="Pilih Siswa..." class="chosen-select" style="width: 200px;" multiple name="siswa[]" >
                          <?php //foreach ($students as $key => $value) { ?>
                            <option value="<?php //echo $key;?>"><?php //echo $value;?></option>
                          <?php //} ?>                  
                        </select> -->
                    
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="modal fade" id="copyExcel" tabindex="-1" role="dialog" aria-labelledby="myNewCopy">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myNewCopy"><b>Data Siswa</b></h4>
              </div>
              <div class="modal-body idata">
                <?php $copy_url = Yii::app()->createUrl('/lesson/copyExcel/'.$model->id); ?>
                <!-- <form method="post" action="<?php echo $copy_url;?>" onsubmit="return confirm('Yakin menambahkan siswa ke kelas ini ?');"> -->
                <div class="form-group">
                    <textarea id="datamasuk" class="form-control" cols="5" rows="5" name="datasiswa">1516.10.084  Agis Cantini
1516.10.085 Anna Oktaviani (Contoh)</textarea>
                </div>
                  <button class="btn btn-success" id="tambah">Tambah</button>
                <!-- </form> -->    
              </div>
              <div class="modal-footer">
                <span></span><button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
              </div>
              
            </div>
          </div>
        </div>

        <div class="modal fade" id="inputData" tabindex="-1" role="dialog" aria-labelledby="dataExcel">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="dataExcel"><b>Data Siswa</b></h4>
              </div>
              <div class="modal-body" style="max-height: calc(100vh - 212px);overflow-y: auto;">
                <?php $input_url = Yii::app()->createUrl('/lesson/inputData/'.$model->id); ?>
                <form method="post" action="<?php echo $input_url;?>" onsubmit="return confirm('Yakin menambahkan siswa ke kelas ini ?');">
                  
                    <table class="table">
                      <tbody id="siswas">
                        <th class="success text-center">NOMOR INDUK</th>
                        <th class="success text-center" id="tanda">NAMA LENGKAP</th>
                      </tbody>
                    </table>
                  
                  <input type="submit" class="btn btn-success" value="Simpan">
                </form>    
              </div>
              <div class="modal-footer">
                <span><button type="button" class="btn btn-default" id="kembali">Kembali</button></span><button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
              </div>
              
            </div>
          </div>
        </div>

      </div>
      <?php } ?>
    </div>
  </div>
</div>
                   
</div>
<script type="text/javascript">
  var url_post = "<?php echo $copy_url;?>";
  var ds;
  var obj;

  $("#tambah").click(function(){
    ds = $("#datamasuk").val();
    $.ajax({
        url: url_post,
        type: "POST",
        data: {datasiswa:ds},
        success: function(resp){
           //console.log(resp);
           obj = jQuery.parseJSON(resp);
           //console.log(obj.messages);
           if(obj.messages == "success"){
              $('#copyExcel').modal('hide');
              $('#inputData').modal('show');
              
              $.each(obj.data, function(key,value){
                //console.log(value.nomor_induk);
                $('#siswas').append('<tr class="tambahan"><td><input type="text" name="nis[]" value="'+value.nomor_induk+'" class="form-control"></td> <td><input type="text" name="nama[]" value="'+value.nama_lengkap+'" class="form-control"></td></tr>');
              });
              
              
           }
           console.log(obj);
        }
      });
    //  console.log(ds);
  });
  
  $("#kembali").click(function(){
    $('#copyExcel').modal('show');
    $('#inputData').modal('hide');
    $("tr").remove(".tambahan");
  });

  var config = {
        '.chosen-select'           : {},
        '.chosen-select-deselect'  : {allow_single_deselect:true},
        '.chosen-select-no-single' : {disable_search_threshold:10},
        '.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
        '.chosen-select-width'     : {width:"95%"}
      }
    for (var selector in config) {
      $(selector).chosen(config[selector]);
    }

  $('#selectAll').click(function(event) {  //on click 
        if(this.checked) { // check select status
            $('.murid').each(function() { //loop through each checkbox
                this.checked = true;  //select all checkboxes with class "checkbox1"               
            });
        }else{
            $('.murid').each(function() { //loop through each checkbox
                this.checked = false; //deselect all checkboxes with class "checkbox1"                       
            });         
        }
    });  
</script>