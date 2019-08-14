<?php
/* @var $this ClasesController */
/* @var $model Clases */

$this->breadcrumbs = array(
    'Kelas' => array('index'),
    $model->name,
);

$this->menu = array(
    array('label' => 'List Clases', 'url' => array('index')),
    array('label' => 'Create Clases', 'url' => array('create')),
    array('label' => 'Update Clases', 'url' => array('update', 'id' => $model->id)),
    array('label' => 'Delete Clases', 'url' => '#', 'linkOptions' => array('submit' => array('delete', 'id' => $model->id), 'confirm' => 'Are you sure you want to delete this item?')),
    array('label' => 'Manage Clases', 'url' => array('admin')),
);


$kelas = ClassDetail::model()->findAll();
$fiturRekap = Option::model()->findAll(array('condition' => 'key_config LIKE "%fitur_rekap%"'));
?>

<h1>Input Nilai WaliKelas Siswa Kelas <?php echo $model->name; ?></h1>
<?php
echo "<form method='POST' name='checkform' id='checkform'>";
?>
<div class="col-md-12">
    <div class="col-card">
        <h4>*Baca Keterangan Pilihan Sikap Spiritual dan Sikap Sosial di bawah form ini</h4>
        <div class="table-responsive">
            <table class="table table-hover table-bordered">
                <th>No</th>
                <th>NIS</th>
                <th>Nama Siswa</th>
                <th>Sikap Spiritual</th>
                <th>Sikap Sosial</th>
                <th>Ekstrakurikuler 1</th>
                <th>Ekstrakurikuler 2</th>
                <th>Ekstrakurikuler 3</th>
                <th>Ekstrakurikuler 4</th>
                <th>Prestasi 1</th>
                <th>Prestasi 2</th>
                <th>Prestasi 3</th>
                <th>Prestasi 4</th>
                <th>Sakit</th>
                <th>Izin</th>
                <th>Alfa</th>
                <th>Catatan WaliKelas</th>
                <?php $no = 1; ?>
                <?php foreach ($siswa as $key) { ?>
                    <tr>
                        <td><?php echo $no; ?></td>
                        <td><?php echo $key->username; ?></td>
                        <td><?php echo $key->display_name; ?></td>
                        <td>
                            <div style="width: 300px">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Predikat</label>
                                    </div>
                                     <div class="form-group">    
                                         <select class="form-inline s-spirit" name="mark[<?php echo $key->id; ?>][Sikap Spiritual - Predikat]" >
                                            <?php if (@FinalMark::model()->findByAttributes(array('user_id' => $key->id, 'tipe' => 'Sikap Spiritual - Predikat'))->nilai_desc == "Sangat Baik") { ?>
                                            <option value="Sangat Baik" selected>Sangat Baik</option>
                                            <option value="Baik">Baik</option>
                                            <?php } else { ?>
                                            <option value="Sangat Baik">Sangat Baik</option>
                                            <option value="Baik" selected>Baik</option>        
                                            <?php }?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Deskripsi</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Selalu Dilakukan</label><br />
                                        <select class="form-inline s-spirit" name="sel-spi[]" data-id="<?php echo $key->id; ?>">
                                            <option></option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                            <option value="7">7</option>
                                            <option value="8">8</option>
                                            <option value="9">9</option>
                                            <option value="10">10</option>
                                        </select>
                                        <select class="form-inline s-spirit" name="sel-spi[]" data-id="<?php echo $key->id; ?>">
                                            <option></option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                            <option value="7">7</option>
                                            <option value="8">8</option>
                                            <option value="9">9</option>
                                            <option value="10">10</option>
                                        </select>
                                        <select class="form-inline s-spirit" name="sel-spi[]" data-id="<?php echo $key->id; ?>">
                                            <option></option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                            <option value="7">7</option>
                                            <option value="8">8</option>
                                            <option value="9">9</option>
                                            <option value="10">10</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Mulai Berkembang</label>
                                        <select class="form-inline s-spirit" name="men-spi[]" data-id="<?php echo $key->id; ?>">
                                            <option></option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                            <option value="7">7</option>
                                            <option value="8">8</option>
                                            <option value="9">9</option>
                                            <option value="10">10</option>
                                        </select>
                                        <select class="form-inline s-spirit" name="men-spi[]" data-id="<?php echo $key->id; ?>">
                                            <option></option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                            <option value="7">7</option>
                                            <option value="8">8</option>
                                            <option value="9">9</option>
                                            <option value="10">10</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <textarea 
                                            class="form-control" 
                                            style="height: 290px" 
                                            readonly="readonly"
                                            name="mark[<?php echo $key->id ?>][Sikap Spiritual - Deskripsi]"><?php echo @FinalMark::model()->findByAttributes(array('user_id' => $key->id, 'tipe' => 'Sikap Spiritual - Deskripsi'))->nilai_desc; ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div style="width: 300px">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Predikat</label>
                                    </div>
                                     <div class="form-group">    
                                         <select class="form-inline s-spirit" name="mark[<?php echo $key->id; ?>][Sikap Sosial - Predikat]" >
                                            <?php if (@FinalMark::model()->findByAttributes(array('user_id' => $key->id, 'tipe' => 'Sikap Sosial - Predikat'))->nilai_desc == "Sangat Baik") { ?>
                                            <option value="Sangat Baik" selected>Sangat Baik</option>
                                            <option value="Baik">Baik</option>
                                            <?php } else { ?>
                                            <option value="Sangat Baik">Sangat Baik</option>
                                            <option value="Baik" selected>Baik</option>        
                                            <?php }?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Deskripsi</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Selalu Dilakukan
                                            <br /><small>sangat baik</small></label><br />
                                        <select  class="form-inline s-sosial" name="sb-sos[]" data-id="<?php echo $key->id; ?>">
                                            <option></option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                            <option value="7">7</option>
                                            <option value="8">8</option>
                                            <option value="9">9</option>
                                            <option value="10">10</option>
                                        </select>
                                        <select class="form-inline s-sosial" name="sb-sos[]" data-id="<?php echo $key->id; ?>">
                                            <option></option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                            <option value="7">7</option>
                                            <option value="8">8</option>
                                            <option value="9">9</option>
                                            <option value="10">10</option>
                                        </select>
                                        <select class="form-inline s-sosial" name="sb-sos[]" data-id="<?php echo $key->id; ?>">
                                            <option></option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                            <option value="7">7</option>
                                            <option value="8">8</option>
                                            <option value="9">9</option>
                                            <option value="10">10</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for=""><small> baik</small></label><br />
                                        <select  class="form-inline s-sosial" name="b-sos[]" data-id="<?php echo $key->id; ?>">
                                            <option></option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                            <option value="7">7</option>
                                            <option value="8">8</option>
                                            <option value="9">9</option>
                                            <option value="10">10</option>
                                        </select>
                                        <select class="form-inline s-sosial" name="b-sos[]" data-id="<?php echo $key->id; ?>">
                                            <option></option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                            <option value="7">7</option>
                                            <option value="8">8</option>
                                            <option value="9">9</option>
                                            <option value="10">10</option>
                                        </select>
                                        <select class="form-inline s-sosial" name="b-sos[]" data-id="<?php echo $key->id; ?>">
                                            <option></option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                            <option value="7">7</option>
                                            <option value="8">8</option>
                                            <option value="9">9</option>
                                            <option value="10">10</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for=""><small>kurang</small></label><br />
                                        <select  class="form-inline s-sosial" name="k-sos[]" data-id="<?php echo $key->id; ?>">
                                            <option></option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                            <option value="7">7</option>
                                            <option value="8">8</option>
                                        </select>
                                        <select class="form-inline s-sosial" name="k-sos[]" data-id="<?php echo $key->id; ?>">
                                            <option></option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                            <option value="7">7</option>
                                            <option value="8">8</option>
                                        </select>
                                        <select class="form-inline s-sosial" name="k-sos[]" data-id="<?php echo $key->id; ?>">
                                            <option></option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                            <option value="7">7</option>
                                            <option value="8">8</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Mulai Berkembang</label>
                                        <select class="form-inline s-sosial" name="men-sos[]" data-id="<?php echo $key->id; ?>">
                                            <option></option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                            <option value="7">7</option>
                                            <option value="8">8</option>
                                        </select>
                                        <select class="form-inline s-sosial" name="men-sos[]" data-id="<?php echo $key->id; ?>">
                                            <option></option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                            <option value="7">7</option>
                                            <option value="8">8</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <textarea 
                                            class="form-control" 
                                            style="height: 150px" 
                                            readonly="readonly"
                                            name="mark[<?php echo $key->id; ?>][Sikap Sosial - Deskripsi]"><?php echo @FinalMark::model()->findByAttributes(array('user_id' => $key->id, 'tipe' => 'Sikap Sosial - Deskripsi'))->nilai_desc; ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <label for="">Nama</label>
                                <input 
                                    class="form-control" 
                                    type="text" 
                                    style="width: 200px !important;" 
                                    name="mark[<?php echo $key->id; ?>][Ekstrakurikuler 1 - Nama]"
                                    value="<?php echo @FinalMark::model()->findByAttributes(array('user_id' => $key->id, 'tipe' => 'Ekstrakurikuler 1 - Nama'))->nilai_desc; ?>"/>
                            </div>
                            <div class="form-group">
                                <label for="">Nilai</label>
                                <input 
                                    class="form-control" 
                                    type="text" 
                                    style="width: 200px !important;" 
                                    name="mark[<?php echo $key->id; ?>][Ekstrakurikuler 1 - Nilai]"
                                    value="<?php echo @FinalMark::model()->findByAttributes(array('user_id' => $key->id, 'tipe' => 'Ekstrakurikuler 1 - Nilai'))->nilai_desc; ?>"/>
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <label for="">Nama</label>
                                <input 
                                    class="form-control" 
                                    type="text" 
                                    style="width: 200px !important;" 
                                    name="mark[<?php echo $key->id; ?>][Ekstrakurikuler 2 - Nama]"
                                    value="<?php echo @FinalMark::model()->findByAttributes(array('user_id' => $key->id, 'tipe' => 'Ekstrakurikuler 2 - Nama'))->nilai_desc; ?>"/>
                            </div>
                            <div class="form-group">
                                <label for="">Nilai</label>
                                <input 
                                    class="form-control" 
                                    type="text" 
                                    style="width: 200px !important;" 
                                    name="mark[<?php echo $key->id; ?>][Ekstrakurikuler 2 - Nilai]"
                                    value="<?php echo @FinalMark::model()->findByAttributes(array('user_id' => $key->id, 'tipe' => 'Ekstrakurikuler 2 - Nilai'))->nilai_desc; ?>"/>
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <label for="">Nama</label>
                                <input 
                                    class="form-control" 
                                    type="text" 
                                    style="width: 200px !important;" 
                                    name="mark[<?php echo $key->id; ?>][Ekstrakurikuler 3 - Nama]"
                                    value="<?php echo @FinalMark::model()->findByAttributes(array('user_id' => $key->id, 'tipe' => 'Ekstrakurikuler 3 - Nama'))->nilai_desc; ?>"/>
                            </div>
                            <div class="form-group">
                                <label for="">Nilai</label>
                                <input 
                                    class="form-control" 
                                    type="text" 
                                    style="width: 200px !important;" 
                                    name="mark[<?php echo $key->id; ?>][Ekstrakurikuler 3 - Nilai]"
                                    value="<?php echo @FinalMark::model()->findByAttributes(array('user_id' => $key->id, 'tipe' => 'Ekstrakurikuler 3 - Nilai'))->nilai_desc; ?>"/>
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <label for="">Nama</label>
                                <input 
                                    class="form-control" 
                                    type="text" 
                                    style="width: 200px !important;" 
                                    name="mark[<?php echo $key->id; ?>][Ekstrakurikuler 4 - Nama]"
                                    value="<?php echo @FinalMark::model()->findByAttributes(array('user_id' => $key->id, 'tipe' => 'Ekstrakurikuler 4 - Nama'))->nilai_desc; ?>"/>
                            </div>
                            <div class="form-group">
                                <label for="">Nilai</label>
                                <input 
                                    class="form-control" 
                                    type="text" 
                                    style="width: 200px !important;" 
                                    name="mark[<?php echo $key->id; ?>][Ekstrakurikuler 4 - Nilai]"
                                    value="<?php echo @FinalMark::model()->findByAttributes(array('user_id' => $key->id, 'tipe' => 'Ekstrakurikuler 4 - Nilai'))->nilai_desc; ?>"/>
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <label for="">Jenis Kegiatan</label>
                                <input 
                                    class="form-control" 
                                    type="text" 
                                    name="mark[<?php echo $key->id; ?>][Prestasi 1 - Kegiatan]" 
                                    value="<?php echo @FinalMark::model()->findByAttributes(array('user_id' => $key->id, 'tipe' => 'Prestasi 1 - Kegiatan'))->nilai_desc; ?>"/>
                            </div>
                            <div class="form-group">
                                <label for="">Keterangan</label>
                                <textarea 
                                    class="form-control" 
                                    style="width: 200px !important;" 
                                    name="mark[<?php echo $key->id; ?>][Prestasi 1 - Keterangan]"><?php echo @FinalMark::model()->findByAttributes(array('user_id' => $key->id, 'tipe' => 'Prestasi 1 - Keterangan'))->nilai_desc; ?></textarea>
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <label for="">Jenis Kegiatan</label>
                                <input 
                                    class="form-control" 
                                    type="text" 
                                    name="mark[<?php echo $key->id; ?>][Prestasi 2 - Kegiatan]"
                                    value="<?php echo @FinalMark::model()->findByAttributes(array('user_id' => $key->id, 'tipe' => 'Prestasi 2 - Kegiatan'))->nilai_desc; ?>"/>
                            </div>
                            <div class="form-group">
                                <label for="">Keterangan</label>
                                <textarea 
                                    class="form-control" 
                                    style="width: 200px !important;" 
                                    name="mark[<?php echo $key->id; ?>][Prestasi 2 - Keterangan]"><?php echo @FinalMark::model()->findByAttributes(array('user_id' => $key->id, 'tipe' => 'Prestasi 2 - Keterangan'))->nilai_desc; ?></textarea>
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <label for="">Jenis Kegiatan</label>
                                <input 
                                    class="form-control" 
                                    type="text" 
                                    name="mark[<?php echo $key->id; ?>][Prestasi 3 - Kegiatan]"
                                    value="<?php echo @FinalMark::model()->findByAttributes(array('user_id' => $key->id, 'tipe' => 'Prestasi 4 - Kegiatan'))->nilai_desc; ?>"/>
                            </div>
                            <div class="form-group">
                                <label for="">Keterangan</label>
                                <textarea 
                                    class="form-control" 
                                    style="width: 200px !important;" 
                                    name="mark[<?php echo $key->id; ?>][Prestasi 3 - Keterangan]"><?php echo @FinalMark::model()->findByAttributes(array('user_id' => $key->id, 'tipe' => 'Prestasi 4 - Keterangan'))->nilai_desc; ?></textarea>
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <label for="">Jenis Kegiatan</label>
                                <input 
                                    class="form-control" 
                                    type="text" 
                                    name="mark[<?php echo $key->id; ?>][Prestasi 4 - Kegiatan]"
                                    value="<?php echo @FinalMark::model()->findByAttributes(array('user_id' => $key->id, 'tipe' => 'Prestasi 4 - Kegiatan'))->nilai_desc; ?>"/>
                            </div>
                            <div class="form-group">
                                <label for="">Keterangan</label>
                                <textarea 
                                    class="form-control" 
                                    style="width: 200px !important;" 
                                    name="mark[<?php echo $key->id; ?>][Prestasi 4 - Keterangan]"><?php echo @FinalMark::model()->findByAttributes(array('user_id' => $key->id, 'tipe' => 'Prestasi 4 - Keterangan'))->nilai_desc; ?></textarea>
                            </div>
                        </td>
                        <td>
                            <input 
                                type="number" 
                                class="form-control" 
                                style="width: 75px !important;" 
                                name="mark[<?php echo $key->id; ?>][Absensi Sakit]"
                                value="<?php echo @FinalMark::model()->findByAttributes(array('user_id' => $key->id, 'tipe' => 'Absensi Sakit'))->nilai_desc; ?>"/>
                        </td>
                        <td>
                            <input 
                                type="number" 
                                class="form-control" 
                                style="width: 75px !important;" 
                                name="mark[<?php echo $key->id; ?>][Absensi Izin]"
                                value="<?php echo @FinalMark::model()->findByAttributes(array('user_id' => $key->id, 'tipe' => 'Absensi Izin'))->nilai_desc; ?>"/>
                        </td>
                        <td>
                            <input 
                                type="number" 
                                class="form-control" 
                                style="width: 75px !important;" 
                                name="mark[<?php echo $key->id; ?>][Absensi Alfa]"
                                value="<?php echo @FinalMark::model()->findByAttributes(array('user_id' => $key->id, 'tipe' => 'Absensi Alfa'))->nilai_desc; ?>"/>
                        </td>
                        <td>
                            <textarea 
                                class="form-control" 
                                style="width: 200px !important;height: 160px;" 
                                name="mark[<?php echo $key->id; ?>][Catatan Wali Kelas]"><?php echo @FinalMark::model()->findByAttributes(array('user_id' => $key->id, 'tipe' => 'Catatan Wali Kelas'))->nilai_desc; ?></textarea>
                        </td>
                    </tr>
                    <?php $no++; ?>
                <?php } ?>
            </table>
        </div>
        <input type="submit" class="btn btn-pn-primary btn-lg btn-pn-round btn-block next-step" value="Simpan">
    </div>
</div>
<?php echo "</form>"; ?>

<div class="col-md-6">
    <div class="col-card">
        <div class="table-responsive">
            <table class="table table-hover">
                <tr><th>PILLIHAN DESKRIPSI SIKAP SPIRITUAL</th></tr>
                <tr><td>1. berdoa sebelum dan sesudah melakukan kegiatan</td></tr>
                <tr><td>2. menjalankan ibadah sesuai dengan agamanya</td></tr>
                <tr><td>3. memberi salam pada saat awal dan akhir kegiatan</td></tr>
                <tr><td>4. bersyukur atas nikmat dan karunia Tuhan Yang Maha Esa</td></tr>
                <tr><td>5. mensyukuri kemampuan manusia dalam mengendalikan diri</td></tr>
                <tr><td>6. bersyukur ketika berhasil mengerjakan sesuatu</td></tr>
                <tr><td>7. berserah diri (tawakal) kepada Tuhan setelah berikhtiar atau melakukan usaha </td></tr>
                <tr><td>8. memelihara hubungan baik dengan sesama umat</td></tr>
                <tr><td>9. bersyukur sebagai bangsa Indonesia </td></tr>
                <tr><td>10. menghormati orang lain yang menjalankan ibadah sesuai dengan agamanya</td></tr>
            </table>
        </div>
    </div>
</div>
<div class="col-md-6">
    <div class="col-card">
        <div class="table-responsive">
            <table class="table table-hover">
                <tr><th>PILLIHAN DESKRIPSI SIKAP SOSIAL</th></tr>
                <tr><td>1. jujur</td></tr>
                <tr><td>2. disiplin </td></tr>
                <tr><td>3. tanggung jawab </td></tr>
                <tr><td>4. santun </td></tr>
                <tr><td>5. percaya diri </td></tr>
                <tr><td>6. peduli </td></tr>
                <tr><td>7. toleransi</td></tr>
                <tr><td>8. gotong royong</td></tr>
                <tr><td>9. ikhlas</td></tr>
                <tr><td>10. ihsan</td></tr>
            </table>
        </div>
    </div>
</div>

<script>
    var desc_spirit = ['-',
        'berdoa sebelum dan sesudah melakukan kegiatan',
        'menjalankan ibadah sesuai dengan agamanya',
        'memberi salam pada saat awal dan akhir kegiatan',
        'bersyukur atas nikmat dan karunia Tuhan Yang Maha Esa',
        'mensyukuri kemampuan manusia dalam mengendalikan diri',
        'bersyukur ketika berhasil mengerjakan sesuatu',
        'berserah diri (tawakal) kepada Tuhan setelah berikhtiar atau melakukan usaha ',
        'memelihara hubungan baik dengan sesama umat',
        'bersyukur sebagai bangsa Indonesia ',
        'menghormati orang lain yang menjalankan ibadah sesuai dengan agamanya'];
    
    var desc_sosial = ['-',
        'jujur',
        'disiplin ',
        'tanggung jawab ',
        'santun ',
        'percaya diri ',
        'peduli ',
        'toleransi',
        'gotong royong',
        'ikhlas',
        'ihsan'];

    $(document).ready(function () {
        $('select.s-spirit').on('change', function () {
            //Sikap Spirit
            var desc_spi = '';
            var data_id = $(this).attr('data-id');

            //Sikap Spirit Selalu dilakukan
            var sel_spi = [];
            $('select[name="sel-spi[]"][data-id="' + data_id + '"]').each(function () {
                if ($(this).val() !== '')
                    sel_spi.push(desc_spirit[$(this).val()]);
            });

            if (sel_spi.length > 0) {
                desc_spi += 'Selalu ' + sel_spi.join(", ");
            }

            //Sikap Spirit Mulai Meningkat
            var men_spi = [];
            $('select[name="men-spi[]"][data-id="' + data_id + '"]').each(function () {
                if ($(this).val() !== '')
                    men_spi.push(desc_spirit[$(this).val()]);
            });

            if (men_spi.length > 0) {
                desc_spi += '. Sedangkan ' + men_spi.join(", ") + ' mulai meningkat';
            }

            $('textarea[name="mark[' + data_id + '][Sikap Spiritual - Deskripsi]"]').val(desc_spi);

        });
        
        $('select.s-sosial').on('change', function () {
            //Sikap Sosial
            var desc_sos = '';
            var data_id = $(this).attr('data-id');

            //Sikap Sosial Selalu dilakukan sangat baik
            var sb_sos = [];
            $('select[name="sb-sos[]"][data-id="' + data_id + '"]').each(function () {
                if ($(this).val() !== '')
                    sb_sos.push(desc_sosial[$(this).val()]);
            });
            
            //Sikap Sosial Selalu dilakukan baik
            var b_sos = [];
            $('select[name="b-sos[]"][data-id="' + data_id + '"]').each(function () {
                if ($(this).val() !== '')
                    b_sos.push(desc_sosial[$(this).val()]);
            });
            
            //Sikap Sosial Selalu dilakukan kurang
            var k_sos = [];
            $('select[name="k-sos[]"][data-id="' + data_id + '"]').each(function () {
                if ($(this).val() !== '')
                    k_sos.push(desc_sosial[$(this).val()]);
            });
            
//            if (sb_sos.length > 0 || b_sos.length > 0 || k_sos.length > 0) {
//                desc_sos += 'Selalu menunjukan ';
//            }
            
            if (sb_sos.length > 0) {
                desc_sos += 'Sangat baik dalam sikap ' + sb_sos.join(", ") + '.';
            }
            
            if (b_sos.length > 0) {
                desc_sos += 'Baik dalam sikap ' + b_sos.join(", ") + '.';
            }
            
            if (k_sos.length > 0) {
                desc_sos += 'Kurang dalam sikap '  + k_sos.join(", ");
            }

            //Sikap Sosial Mulai Meningkat
            var men_sos = [];
            $('select[name="men-sos[]"][data-id="' + data_id + '"]').each(function () {
                if ($(this).val() !== '')
                    men_sos.push(desc_sosial[$(this).val()]);
            });

            if (men_sos.length > 0) {
                desc_sos += '. Sedangkan sikap ' + men_sos.join(", ") + ' mulai berkembang';
            }

            $('textarea[name="mark[' + data_id + '][Sikap Sosial - Deskripsi]"]').val(desc_sos);

        });
    });
</script>

