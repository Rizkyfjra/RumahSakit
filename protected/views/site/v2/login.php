        <div class="login-page-container">
          <div class="login-page">
              <div class="container">
                  <div class="col-md-8 col-md-push-2">
                      <div class="login-box">
                        <div class="login-box-header">
                            <h1>Sistem Rumah Sakit<br>
                            "SIMRS"</h1>
                            <div class="school-name"><?php echo $nama_sekolah ?></div>
                           <!--  <div class="school-name"><?php 

                            // echo $_SERVER['HTTP_USER_AGENT'] . "\n\n";

                            // if (strpos($_SERVER['HTTP_USER_AGENT'], 'electron-quick-start') !== false) {
                            // // $browser = get_browser(null, true);
                            // // print_r($browser);
                            //     echo "benar";
                            // } else {
                            //     echo "salah";
                            // }


                            ?></div> -->
                        </div>
                        <?php $form=$this->beginWidget('CActiveForm', array(
                            'id'=>'login-form',
                            'enableClientValidation'=>false,
                            'clientOptions'=>array(
                            'validateOnSubmit'=>true,
                          ),
                        )); ?>
                        <div class="login-box-form">
                            <h2 class="login-heading">Masuk ke Sistem</h2>
                            <div class="col-md-8 col-md-push-2">
                                <div class="login-form">
                                    <form action="dashboard.php" method="get">
                                        <input type="hidden" name="page" value="home">
                                        <div class="form-group">
                                             <?php echo $form->textField($model,'username',array('class'=>'form-control input-lg input-pn input-pn-login input-pn-center', 'placeholder'=>'NIS, NIP, atau Email anda')); ?>
                                        </div>
                                        <?php echo $form->error($model,'username'); ?>
                                        <div class="form-group">
                                            <?php echo $form->passwordField($model,'password',array('class'=>'form-control input-lg input-pn input-pn-login input-pn-center', 'placeholder'=>'Kata Sandi')); ?>
                                        </div>
                                        <?php echo $form->error($model,'password'); ?>
                                        <button type="submit" class="btn btn-pn-primary btn-pn-round btn-lg btn-block">MASUK</button>
                                    </form>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <a href="#" data-toggle="modal" data-target="#modalForgotPassword">Lupa Password?</a>
                            <?php //include '_modal_forgot_password.php'; ?>
                        </div>
                        <?php $this->endWidget(); ?>

                        <?php
                        $tahun_url = Option::model()->findAll(array('condition' => 'key_config LIKE "%tahun_url%"'));
                        if (!empty($tahun_url)) {
                        ?>


                         <div class="news-box"  >
                            <h2 class="login-heading">URL</h2>
                            <div class="news-card-container">

                                <?php
                                    $tahun_url = $tahun_url[0]->value;  

                                        if (!empty($tahun_url)) {
                                                $arr_tahun = json_decode($tahun_url);
                                        } else {
                                                $arr_tahun = array();
                                        }

                                foreach ($arr_tahun as $key => $value) {        

                                ?>
                         
                                <div class="news-card">
                                    <div class="news-card-title">
                                        <h3>
                                            <?php echo $value->tahun ?>
                                        </h3>
                                    </div>
                                </div>
                     
                                <?php } ?> 

                            </div>
                        </div>

                        <?php } ?>

                        <div class="news-box">
                            <h2 class="login-heading">Informasi Terbaru</h2>
                            <div class="news-card-container">
                                <?php
                                  if(!empty($berita->getData())){
                                    $beritaFront = $berita->getData();

                                    foreach ($beritaFront as $value) {
                                ?>
                                <div class="news-card">
                                    <div class="news-card-title">
                                        <h3>
                                            <?php echo $value->title ?>
                                        </h3>
                                    </div>
                                    <div class="news-card-content">
                                        <div class="news-card-text">
                                          <?php
                                            $string = $value->content;

                                            // if (strlen($string) > 140) {
                                            //     $stringCut = substr($string, 0, 140);
                                            //     $string = substr($stringCut, 0, strrpos($stringCut, ' ')).'...  <a href="'.echo $this->createUrl('/announcements/view/'.$value->id).'">Lebih Lanjut</a>';
                                            // }

                                            echo $string;
                                          ?>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                    }
                                  }else{
                                ?>
                                <div class="news-card">
                                    <div class="news-card-title">
                                        <h3>
                                            <center>Belum Ada Informasi Terbaru</center>
                                        </h3>
                                    </div>
                                </div>
                                <?php
                                  }
                                ?>
                            </div>
                        </div>
                      </div>
                  </div>
              </div>
          </div>
        </div>
