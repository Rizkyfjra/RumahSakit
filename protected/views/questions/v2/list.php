<?php
  $mpl=NULL;
  if(isset($_GET['pelajaran'])){
    $mpl=$_GET['pelajaran'];
  }
?>
<div class="container-fluid">
  <div class="row">
    <?php
      // $this->renderPartial('v2/_breadcrumb_soal_list', array(
        
      // ));
    ?>
    <div class="col-md-12">
      <div id="bc1" class="btn-group btn-breadcrumb">
      <?php echo CHtml::link('<i class="fa fa-home"></i> Beranda',array('/site/index'), array('class'=>'btn btn-default')); ?>
      <?php echo CHtml::link('<div>Bank Soal</div>',array('#'), array('class'=>'btn btn-success')); ?>
      </div>
    </div>

    <div class="col-lg-12">
        <h3>Bank Soal
          <small class="hidden-xs">Daftar Soal</small>
          <div class="pull-right">
            <a href="<?php echo $this->createUrl('/questions/create') ?>" class="btn btn-sm btn-pn-primary btn-pn-round">
              <span class="hidden-sm hidden-xs"><i class="fa fa-plus-circle"></i> TAMBAH SOAL</span>
              <span class="hidden-md hidden-lg"><i class="fa fa-plus-circle"></i></span>
            </a>
          </div>
        </h3>
        <form action="<?php echo Yii::app()->createUrl("/questions/deleteQuestion") ?>" method="post" onsubmit="return confirm('Yakin Menghapus Soal Yang Dipilih?');"> 
          <div class="row">
            <div class="col-md-12">
              <div class="col-card">
                <div class="row">
                  <div class="col-md-12">
                    <div class="btn-group pull-right">
                      <input type="submit" value="Hapus" class="btn btn-danger">
                    </div>
                  </div>
                </div>
                <div class="table-responsive">
                  <table class="table table-hover">
                    <thead>
                      <tr>
                        <th>No.</th>
                        <th width="20%">Kompetensi Dasar</th>
                        <th width="30%">Nama Guru</th>
                        <th>Tipe Pertanyaan</th>
                        <th width="10%">Aksi</th>
                        <th width="5%">
                          <div class="text-center">
                            <input type="checkbox" id="selectAll">
                          </div>
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                        if(!empty($dataProvider->getData())){
                          $questions = $dataProvider->getData();

                          if(empty($_GET['Questions_page'])){
                            $no = 1;
                          }else{
                            $no = 1 + (($_GET['Questions_page']-1) * 15);
                          }

                          foreach ($questions as $value) {
                      ?>
                      <tr>
                        <td><?php echo $no ?></td>
                        <td><?php echo CHtml::link(CHtml::encode($value->title), array('/questions/view', 'id'=>$value->id)); ?></td>
                        <td>
                          <?php
                            if(!empty($value->teacher->display_name)){ 
                              echo $value->teacher->display_name;
                            }
                          ?>
                        </td>
                        <td>
                          <?php
                            if(!empty($value->type == 1)){
                              echo "Isian";
                            }elseif($value->type == 2){
                              echo "Essay";
                            }else{
                              echo "Pilihan Ganda";
                            }
                          ?>
                        </td>
                        <td>
                          <div class="btn-group">
                            <?php echo CHtml::link('<i class="fa fa-eye"></i>',array('view', 'id'=>$value->id), array('class'=>'btn btn-success btn-xs')); ?>
                            <?php echo CHtml::link('<i class="fa fa-pencil"></i>',array('update', 'id'=>$value->id), array('class'=>'btn btn-primary btn-xs')); ?>
                          </div>
                        </td>
                        <td>
                          <div class="text-center">
                            <input type="checkbox" class="soal" name="soal[]" value="<?php echo $value->id;?>">
                          </div>
                        </td>
                      </tr>
                      <?php
                            $no++;
                          }
                        }
                      ?>
                    </tbody>
                  </table>
                  <div class="text-center">
                    <?php
                      $this->widget('CLinkPager', array(
                                    'pages'=>$dataProvider->pagination,
                                    ));
                    ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<script>
  $('#selectAll').click(function(event) {
      if(this.checked) {
          $('.soal').each(function() {
              this.checked = true;
          });
      }else{
          $('.soal').each(function() {
              this.checked = false;
          });
      }
  });
    
  var ck = "<?php echo $mpl;?>";
  console.log(ck);

  if(ck == 0){
    $("#mapel").hide();
    $("#teks").show();
  }else{
    $("#mapel").show();
    $("#teks").hide();
  }

  $("#pil").change(function (){
    if($(this).val() == "2"){
      $("#mapel").show();
      $("#teks").val("");
      $("#teks").hide();
    }else{
      $("#mapel").hide();
      $('#mapel option:eq(0)').attr('selected','selected');
      $("#teks").show();
    }
  });
</script>