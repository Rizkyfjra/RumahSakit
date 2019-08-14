<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta http-equiv="Content-Language" content="id">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta http-equiv="Copyright" content="EduBox Team">

        <meta name="language" content="Indonesia">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <meta name="description" content="Edubox, Aplikasi Ujian Online Tanpa Internet">
        <meta name="keywords" content="Pinisi, Edubox, Pinisi Edubox, Aplikasi Kelas Maya, Aplikasi Kelas Online, Aplikasi Administrasi Guru">
        <meta name="author" content="Edubox Team">

        <meta name="robots" content="index,all">
        <meta name="spiders" content="all">
        <meta name="webcrawlers" content="all">
        <meta name="rating" content="general">
        <meta name="revisit-after" content="7">

        <!-- Registered By Engine -->
        <title>Sistem Rumah Sakit</title>

        <!-- Stylesheet -->
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl ?>/libraries/bootstrap/css/bootstrap.min.css"/>
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl ?>/libraries/bootstrap-select/css/bootstrap-select.min.css"/>
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl ?>/libraries/bootstrap-notifications/bootstrap-notifications.min.css"/>
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl ?>/libraries/font-awesome/css/font-awesome.min.css"/>
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl ?>/libraries/jquery-chosen/jquery.chosen.css"/>
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl ?>/libraries/jquery-datetime/jquery.datetimepicker.css"/>
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl ?>/css/main.css"/>
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl ?>/css/buttons.dataTables.min.css"/>
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl ?>/css/jquery.dataTables.min.css"/>

            <script type="text/x-mathjax-config">
              MathJax.Hub.Config({
                tex2jax: {
                  inlineMath: [ ['$','$'], ["\\(","\\)"] ],
                  processEscapes: true
                }
              });
            </script>

        <!-- Libraries -->
        <script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl ?>/libraries/jquery/jquery.min.js"></script>
        <script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl ?>/libraries/jquery-chosen/jquery.chosen.js"></script>
        <script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl ?>/libraries/jquery-chained/jquery.chained.js"></script>
        <script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl ?>/libraries/jquery-countdown/jquery.countdown.js"></script>        
        <script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl ?>/libraries/jquery-datetime/jquery.datetimepicker.js"></script>
        <script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl ?>/libraries/vue/vue.development.js"></script>
        <script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl ?>/libraries/bootstrap/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl ?>/libraries/bootstrap-select/js/bootstrap-select.min.js"></script>
        <script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl ?>/libraries/tinymce/js/tinymce/tinymce.min.js"></script>
        <script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl ?>/libraries/tinymce/js/tinymce/jquery.tinymce.min.js"></script>
        <script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl ?>/libraries/MathJax/MathJax.js?config=TeX-AMS-MML_HTMLorMML"></script>
        <script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl ?>/libraries/sisyphus/sisyphus.min.js"></script>
        <script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl ?>/libraries/readmore/readmore.min.js"></script>
        <script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl ?>/libraries/canvasjs-1.8.0/canvasjs.min.js"></script>
        <script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl ?>/libraries/fabric.min.js"></script>
        <script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl ?>/libraries/bootbox.min.js"></script>
        <script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl ?>/scripts/main.js"></script> 
        <script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl ?>/libraries/md5.js"></script> 
        <script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl ?>/libraries/enc-base64-min.js"></script>     
        <script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl ?>/libraries/jquery-print.js"></script> 

        <script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl ?>/libraries/datatable/jquery.dataTables.min.js"></script> 
        <script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl ?>/libraries/datatable/dataTables.buttons.min.js"></script> 
        <script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl ?>/libraries/datatable/buttons.flash.min.js"></script> 
        <script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl ?>/libraries/datatable/buttons.html5.min.js"></script> 
        <script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl ?>/libraries/datatable/buttons.print.min.js"></script> 
        <script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl ?>/libraries/datatable/jszip.min.js"></script> 
        <script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl ?>/libraries/datatable/pdfmake.min.js"></script> 
        <script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl ?>/libraries/datatable/vfs_fonts.js"></script> 
        <!-- <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDv-yvQ2ICjoK2x6H0aGUCAttA-jdTDO_w"></script>   -->
      <!--  <script type="text/javascript" src="<?php //echo Yii::app()->theme->baseUrl ?>/libraries/gmap3.js"></script>     -->

        <!-- FavIco -->
        <link rel="shortcut icon" href="<?php echo Yii::app()->theme->baseUrl ?>/icons/pinisi.ico" type="image/vnd.microsoft.icon">
    </head>
    <?php
        if(Yii::app()->user->isGuest) {
            echo $content;
        } else {
            $role = Yii::app()->user->role;
            
            if ($role == 1 ){
                $textdash = "Dokter";
            } elseif ($role == 2) {
                $textdash = "Pasien";
            } elseif ($role == 3) {
                $textdash = "Dirut";
            } elseif ($role == 4) {
                $textdash = "Staf";    
            } else {
                $textdash = "Adminitrasi";
            }

            $user = User::model()->findByPk(Yii::app()->user->id);
            if($user->image){
                $img_url = Yii::app()->baseUrl.'/images/user/'.$user->id.'/'.$user->image;
            }else{
                $img_url = Yii::app()->baseUrl.'/images/user-2.png';
            }
    ?>
    <body>
        <div id="wrapper">
            <!-- Navbar -->
            <?php include 'navbar.php'; ?>

            <!-- Sidebar -->
            <?php include 'sidebar.php'; ?>

            <!-- Page Content -->
            <div id="page-content-wrapper">
                <?php if(Yii::app()->user->hasFlash('success')){ ?>
                  <div class="alert alert-success notice">
                    <strong><?php echo Yii::app()->user->getFlash('success');?></strong>
                    <a href="#" class="close" data-dismiss="alert">&times;</a>
                  </div>
                  <?php if (Yii::app()->user->hasFlash('error')) {?>
                  <div class="alert alert-danger notice">
                    <strong><?php echo Yii::app()->user->getFlash('error');?></strong>
                    <a href="#" class="close" data-dismiss="alert">&times;</a>
                  </div>
                <?php } ?>
                <?php } elseif (Yii::app()->user->hasFlash('error')) {?>
                  <div class="alert alert-danger notice">
                    <strong><?php echo Yii::app()->user->getFlash('error');?></strong>
                    <a href="#" class="close" data-dismiss="alert">&times;</a>
                  </div>
                <?php } ?>
                
                <?php
                    echo $content;
                ?>
            </div>
        </div>
        <div class="modal-loading"></div>
    </body>

    <!-- External Libraries -->
    <script>
        tinymce.init({
            selector: '.rich-textarea',
            height: 200,
            theme: 'modern',
            invalid_elements :'script',
            valid_styles : { '*' : 'color,font-size,font-weight,font-style,text-decoration' },
            entity_encoding: 'raw',
            entities: '160,nbsp,38,amp,60,lt,62,gt',
            plugins: [
                "advlist autolink lists link image charmap print preview anchor",
                "searchreplace visualblocks code fullscreen contextmenu",
                "insertdatetime media table contextmenu paste jbimages"
            ],
            contextmenu: "cut copy paste | jbimages inserttable | cell row column deletetable | code preview",
            toolbar: "undo redo | styleselect fontsizeselect | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist | jbimages | preview",
            contextmenu_never_use_native: true,            
            paste_data_images: true,
            relative_urls: false,
            image_advtab: true,
        });
        tinymce.init({
            selector: '.simple-textarea',
            height: 100,
            theme: 'modern',
            invalid_elements :'script',
            valid_styles : { '*' : 'color,font-size,font-weight,font-style,text-decoration' },
            entity_encoding: 'raw',
            entities: '160,nbsp,38,amp,60,lt,62,gt',
            plugins: [
                "advlist autolink lists link image charmap print preview anchor",
                "searchreplace visualblocks code fullscreen contextmenu",
                "insertdatetime media table contextmenu paste jbimages"
            ],
            contextmenu: "cut copy paste | jbimages inserttable | cell row column deletetable | code preview",
            toolbar: "undo redo | styleselect fontsizeselect | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist | jbimages | preview",
            contextmenu_never_use_native: true,
            paste_data_images: true,
            relative_urls: false,
            menubar: false,
        });
        tinymce.init({
            selector: '.textarea-input',
            height: 100,
            theme: 'modern',
            invalid_elements :'script',
            valid_styles : { '*' : 'color,font-size,font-weight,font-style,text-decoration' },
            entity_encoding: 'raw',
            entities: '160,nbsp,38,amp,60,lt,62,gt',
            plugins: [
                "advlist autolink lists link image charmap print preview anchor",
                "searchreplace visualblocks code fullscreen contextmenu",
                "insertdatetime media table contextmenu paste jbimages"
            ],
            contextmenu: "cut copy paste | jbimages inserttable | cell row column deletetable | code preview",
            toolbar: "undo redo | styleselect fontsizeselect | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist | jbimages | preview",
            contextmenu_never_use_native: true,
            paste_data_images: true,
            relative_urls: false,
        });

        $("#menu-toggle").click(function(e) {
          e.preventDefault();
          $("#wrapper").toggleClass("toggled");
        });  

        $(function () {
            $(this).on('click', '[data-toggle="print"]', function(){
                var target = $(this).data('target');
            $(target).print();
            });
        });      
    </script>
    <?php
        }
    ?>
</html>
