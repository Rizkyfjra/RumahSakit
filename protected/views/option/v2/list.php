<?php
$nama_sekolah = Option::model()->findAll(array('condition' => 'key_config LIKE "%school_name%"'));
$kepala_sekolah = Option::model()->findAll(array('condition' => 'key_config LIKE "%kepsek_id%"'));
$alamat_sekolah = Option::model()->findAll(array('condition' => 'key_config LIKE "%school_address%"'));
$kurikulum_sekolah = Option::model()->findAll(array('condition' => 'key_config LIKE "%kurikulum%"'));
$ulangan = Option::model()->findAll(array('condition' => 'key_config LIKE "%fitur_ulangan%"'));
$tugas = Option::model()->findAll(array('condition' => 'key_config LIKE "%fitur_tugas%"'));
$materi = Option::model()->findAll(array('condition' => 'key_config LIKE "%fitur_materi%"'));
$rekap = Option::model()->findAll(array('condition' => 'key_config LIKE "%fitur_rekap%"'));
$semester = Option::model()->findAll(array('condition' => 'key_config LIKE "%semester%"'));
$tahun_ajaran = Option::model()->findAll(array('condition' => 'key_config LIKE "%tahun_ajaran%"'));
$nilai_harian = Option::model()->findAll(array('condition' => 'key_config LIKE "%nilai_harian%"'));
$nilai_uts = Option::model()->findAll(array('condition' => 'key_config LIKE "%nilai_uts%"'));
$nilai_uas = Option::model()->findAll(array('condition' => 'key_config LIKE "%nilai_uas%"'));
?>

<div class="container-fluid">
    <div class="row">
        <?php
        // $this->renderPartial('v2/_breadcrumb_option_list', array(

        // ));
        ?>
        <div class="col-md-12">
            <div id="bc1" class="btn-group btn-breadcrumb">
                <?php echo CHtml::link('<i class="fa fa-home"></i> Beranda', array('/site/index'), array('class' => 'btn btn-default')); ?>
                <?php echo CHtml::link('<div>Konfigurasi</div>', array('#'), array('class' => 'btn btn-success')); ?>
            </div>
        </div>

        <div class="col-lg-12">
            <h3>Konfigurasi
                <small class="hidden-xs">Rumkit</small>
                <div class="pull-right">
                    <a href="<?php echo $this->createUrl('/clases') ?>" class="btn btn-primary"><i
                                class="fa fa-eye"></i> Lihat Kelas</a>
                    <div class="btn-group">
                        <a href="<?php echo $this->createUrl('/admin/restart') ?>" class="btn btn-success"
                           onclick="return confirm('Yakin Ingin Me-Restart Smart Edubox ?')"><i
                                    class="fa fa-refresh"></i> Restart</a>
                        <a href="<?php echo $this->createUrl('/admin/poweroff') ?>" class="btn btn-danger"
                           onclick="return confirm('Yakin Ingin Mematikan Smart Edubox ?')"><i
                                    class="fa fa-power-off"></i> Matikan</a>
                    </div>
                    <a href="<?php echo $this->createUrl('/option/atur') ?>" class="btn btn-primary"><i
                                class="fa fa-wrench"></i> Atur</a>
                    <a href="<?php echo $this->createUrl('/option/aturLocal') ?>" class="btn btn-primary"><i
                                class="fa fa-wrench"></i> Atur LOCAL</a>
                    <!-- <a href="<?php echo $this->createUrl('/option/pull') ?>" class="btn btn-danger"><i
                                class="fa fa-download"></i> Update Aplikasi</a> -->
                    <a href="<?php echo $this->createUrl('/option/pullquiz') ?>" class="btn btn-pn-primary"><i
                                class="fa fa-download"></i> Import Ujian</a>
                    <a href="<?php echo $this->createUrl('/api/resetsync') ?>" class="ldg btn btn-primary"><i
                                class="fa fa-refresh"></i> Reset Sync</a>
                    <a href="<?php echo $this->createUrl('/api/syncdata') ?>" class="ldg btn btn-pn-primary"><i
                                class="fa fa-refresh"></i> Sync Data</a>
                </div>
            </h3>
            <div class="row">
                <div class="col-md-12">
                    <div class="col-card">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th width="55%">Konfigurasi</th>
                                    <th width="45%">Keterangan</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                if (!empty($dataProvider->getData())) {
                                    ?>
                                    <tr>
                                        <td>Nama Rumah Sakit</td>
                                        <td>:
                                            <?php
                                            if (!empty($nama_sekolah[0]->value)) {
                                                echo $nama_sekolah[0]->value;
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Direktur Utama</td>
                                        <td>:
                                            <?php
                                            if (!empty($kepala_sekolah[0]->value)) {
                                                $kepsek = User::model()->findByPk($kepala_sekolah[0]->value);
                                                echo $kepsek->display_name;
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Alamat Rumah Sakit</td>
                                        <td>:
                                            <?php
                                            if (!empty($alamat_sekolah[0]->value)) {
                                                echo $alamat_sekolah[0]->value;
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Tahun Berdiri</td>
                                        <td>:
                                            <?php
                                            if (!empty($kurikulum_sekolah[0]->value)) {
                                                echo $kurikulum_sekolah[0]->value;
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Fitur SMS</td>
                                        <td>:
                                            <?php
                                            if (!empty($ulangan[0]->value)) {
                                                if ($ulangan[0]->value == '1') {
                                                    echo "ON";
                                                } else {
                                                    echo "OFF";
                                                }
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                   
                                    <?php
                                    if ($kurikulum_sekolah[0]->value != 2013) {
                                        ?>
                                        <tr>
                                            <td>Prosentase Nilai</td>
                                            <td>
                                                <p>
                                                    NILAI HARIAN :
                                                    <span>
                            <?php
                            if (!empty($nilai_harian[0]->value)) {
                                echo $nilai_harian[0]->value . "%";
                            }
                            ?>
                          </span>
                                                </p>
                                                <p>
                                                    NILAI UTS :
                                                    <span>
                          <?php
                          if (!empty($nilai_uts[0]->value)) {
                              echo $nilai_uts[0]->value . "%";
                          }
                          ?>
                          </span>
                                                </p>
                                                <p>
                                                    NILAI UAS :
                                                    <span>
                          <?php
                          if (!empty($nilai_uas[0]->value)) {
                              echo $nilai_uas[0]->value . "%";
                          }
                          ?>
                          </span>
                                                </p>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(".ldg").on("click",function(){
        $('.modal-loading').addClass("show");
    });
</script>
