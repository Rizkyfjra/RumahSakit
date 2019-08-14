<?php
  $countNotif = (Yii::app()->user->YiiCNotif)? Yii::app()->user->YiiCNotif : 0;
?>
        <nav class="navbar navbar-default navbar-pn navbar-fixed-top">
          <div class="container-fluid">
            <div class="navbar-header">
              <div class="navbar-brand">
                  <div class="brand-primary">
                    <button class="btn btn-link btn-md hidden-md hidden-lg" id="menu-toggle"><i class="fa fa-bars"></i></button>
                    Sistem Rumah Sakit
                  </div>
                  <?php
                  $nama_sekolah = Option::model()->findAll(array('condition'=>'key_config LIKE "%school_name%"'));
                      $sc_name = "SISTEM RUMKIT";
                      if(!empty($nama_sekolah)){
                        if(!empty($nama_sekolah[0]->value)){
                          $sc_name = strtoupper($nama_sekolah[0]->value);
                        }
                      }
                  ?>
                  <div class="brand-secondary hidden-xs"><?php echo $sc_name; ?></div>
              </div>
            </div>
            <?php if (Yii::app()->controller->id!='site' && Yii::app()->controller->id!='assignment') { ?>
              <div class="nav navbar-nav navbar-search">
                    <?php
                    $ket=NULL;
                    $kata=NULL;
                    $urlAction = "#";
                    $searchOption = "<option>Kosong</option>";
                    $isSelected1 = "";
                    $isSelected2 = "";
                    $isSelected3 = "";
                    $isSelected4 = "";
                    $selectName = "";
                    $searchInputName = "";

                    if(isset($_GET['pilihan'])){
                      $ket = $_GET['pilihan'];
                    }

                    if(isset($_GET['keyword'])){
                      $kata=$_GET['keyword'];
                    }

                    switch ($ket) {
                      case 1:
                        $isSelected1 = "selected";
                        $isSelected2 = "";
                        $isSelected3 = "";
                        $isSelected4 = "";
                        $isSelected5 = "";
                        break;

                      case 2:
                        $isSelected1 = "";
                        $isSelected2 = "selected";
                        $isSelected3 = "";
                        $isSelected4 = "";
                        $isSelected5 = "";
                        break;

                      case 3:
                        $isSelected1 = "";
                        $isSelected2 = "";
                        $isSelected3 = "selected";
                        $isSelected4 = "";
                        $isSelected5 = "";
                        break;

                      case 4:
                        $isSelected1 = "";
                        $isSelected2 = "";
                        $isSelected3 = "";
                        $isSelected4 = "selected";
                        $isSelected5 = "";
                        break;

                      case 5:
                        $isSelected1 = "";
                        $isSelected2 = "";
                        $isSelected3 = "";
                        $isSelected4 = "";
                        $isSelected5 = "selected";
                        break; 

                       default:
                         $isSelected1 = "";
                         $isSelected2 = "";
                         $isSelected3 = "";
                         $isSelected4 = "";
                         $isSelected5 = "";
                         break;

                    }

                    switch (Yii::app()->controller->id) {
                      case 'lesson':
                        $urlAction = "lesson/filterLesson";
                        $searchOption = "<option  ".$isSelected1." value=\"1\">Nama Pelajaran</option><option " .$isSelected2." value=\"2\">Kelas</option>";
                        $selectName = "tipe";
                        $searchInputName = "nama";
                        break;

                      case 'quiz':
                        $urlAction = "quiz/filterKuis";
                        $searchOption = "<option  ".$isSelected1." value=\"1\">Nama Ulangan</option><option " .$isSelected2." value=\"2\">Pelajaran</option><option " .$isSelected3." value=\"3\">Kelas</option>";
                        $selectName = "tipe";
                        $searchInputName = "nama";
                        break;

                      case 'user':
                        $urlAction = "user/filter";
                        $searchOption = "<option  ".$isSelected1." value=\"1\">NIK/NIP</option><option " .$isSelected3." value=\"3\">Nama</option><option " .$isSelected2." value=\"2\">Email</option><option " .$isSelected5." value=\"5\">Kelas</option><option " .$isSelected4." value=\"4\">Role ID</option>";
                        $selectName = "pilihan";
                        $searchInputName = "keyword";
                        break;
                    }

                    ?>

                    <?php $form=$this->beginWidget('CActiveForm', array(
                      'action'=>Yii::app()->createUrl($urlAction),
                      'method'=>'get',
                      'htmlOptions'=>array(
                                        'class'=>'navbar-form-search hidden-xs',
                                        'autocomplete'=>'off'
                                    )
                     )); ?>
                    <input type="text" name="<?php echo $searchInputName; ?>" id="nama" style="display:inline-block; width:25%;" class="navbar-form-search-box" value="<?php echo $kata;?>" placeholder="Pencarian...">
                    <?php if(!Yii::app()->user->YiiTeacher){ ?>
                    <select style="display:inline-block; width:15%;" class="tipe form-control input-pn input-lg" name="<?php echo $selectName; ?>" id="tipe">
                      <?php echo $searchOption;?>
                    </select>
                    <?php }else{ ?>
                      <select style="display:inline-block; width:12%;" class="tipe form-control input-pn input-lg" name="tipe" id="tipe">
                        <option <?php if($ket == 1) echo "selected";?> value="1">Nama Pelajaran</option>
                        <option <?php if($ket == 2) echo "selected";?> value="2">Pelajaran</option>
                      </select>
                    <?php }?>
                    <input type="submit" class="btn btn-pn-round btn-primary" value="Cari">
                  <?php $this->endWidget(); ?>
              </div>
              <?php } ?>
              <ul class="nav navbar-nav navbar-right">
                <li class="dropdown dropdown-notifications">
                  <a href="#notifications-panel" class="dropdown-toggle notification-badge" type="button" data-toggle="dropdown">
                    <div class="notification-bell">
                      <i class="fa fa-bell"></i>
                      <span class="badge badge-notify" id="notif"><?php echo $countNotif ?></span>
                    </div>
                  </a>

                  <div class="dropdown-container">

                    <div class="dropdown-toolbar">
                      <div class="dropdown-toolbar-actions">

                      </div>
                      <h3 class="dropdown-toolbar-title">Pemberitahuan</h3>
                    </div>

                    <ul class="dropdown-menu" id="notif-list">

                    </ul>

                    <div class="dropdown-footer text-center">
                      <a href="<?php echo $this->createUrl('/notification/index') ?>">Lihat Semua Pemberitahuan</a>
                    </div>
                    

                  </div>
                </li>
                <?php if(Yii::app()->user->YiiAdmin){ ?>
                <li>
                  <a href="<?php echo $this->createUrl('/option'); ?>" class="logout-button">
                    <button type="button" class="btn btn-pn-gray btn-pn-round btn-md" name="button"><i class="fa fa-gear"></i> <span class="hidden-xs hidden-sm">KONFIGURASI</span></button>
                  </a>
                </li>
                <?php } ?>
                <li>
                  <a href="<?php echo $this->createUrl('/site/logout'); ?>" class="logout-button">
                    <button type="button" class="btn btn-pn-red btn-pn-round btn-md" name="button"><i class="fa fa-sign-out"></i> <span class="hidden-xs hidden-sm">KELUAR</span></button>
                  </a>
                </li>
              </ul>
          </div>
        </nav>
        <script>

          var ajax_call_01 = function() {
            url_load = "<?php echo $this->createUrl('/site/cekLive') ?>";
            $.ajax({
              url: url_load,
              type: "POST",
              success: function(msg){
                 // if (msg > 0){
                 //    $("#notif").text(msg);
                 //  }
              }
            });
          };
          ajax_call_01();


          // var ajax_call_01 = function() {
          //   url_load = "<?php echo $this->createUrl('/site/autoNotif') ?>";
          //   $.ajax({
          //     url: url_load,
          //     type: "POST",
          //     success: function(msg){
          //        if (msg > 0){
          //           $("#notif").text(msg);
          //         }
          //     }
          //   });
          // };
          // ajax_call_01();

          // var ajax_call_02 = function() {
          //   url_load = "<?php echo $this->createUrl('/notification/ajaxlist') ?>";
          //   $.ajax({
          //     url: url_load,
          //     type: "POST",
          //     success: function(msg){
          //         $("#notif-list").html(msg);
          //     }
          //   });
          // };
          // ajax_call_02();

          var interval = 1000 * 60 * 0.1;

          setInterval(ajax_call_01, interval);
          // setInterval(ajax_call_02, interval);
        </script>
