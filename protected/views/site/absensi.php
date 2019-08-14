<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/datepicker.css" />
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/datepicker.js"></script>
 <script type="text/javascript">
            // When the document is ready
            $(document).ready(function () {
                
                $('#example1').datepicker({
                    format: "yyyy-mm-dd"
                });  
            
            });
        </script>
<?php if(!Yii::app()->user->YiiStudent) 
{	
$this->breadcrumbs=array(
'Absensi',
);
?>
<?php 
if(isset($_GET['date_search']) and !empty($_GET['date_search']))
{
	$date_selected = $_GET['date_search'];	
}	
else
{
	$date_selected = date('Y-m-d');
}
?>
Date : <?php echo $date_selected ?>

<br>
<br>
<form action="" method="GET">
<input size="25%" name="date_search" type="text" placeholder="masukan tanggal absen" class="datepicker"  id="example1"> Kelas :
<select name="class_id" type="text" placeholder="masukan kelas">
<?php 
if(isset($_GET['class_id']))
{
	$class_id = $_GET['class_id'];
}
else
{
	$class_id = 1;
}
foreach($class_data as $classes) 
{
?>
<option <?php if($class_id == $classes->id) echo 'selected' ?> value="<?php echo $classes->id ?>"><?php echo $classes->name ?></option>
<?php
}
?>
</select>
<button type='submit' class="btn btn-primary">Cari</button>
</form>
<br>
<table border=1 class="table-stripped">
<tr>
<td><b>Nama Murid</b></td>
<td><b>Kelas</b></td>
<td><b>Jam Absen</b></td>
<td><b>Alasan Telat</b></td>
<td><b>Status</b></td>
<td></td>
</tr>
<?php 
foreach($userAbsensi as $row)
{	
?>
<tr>
<td><?php echo $row->display_name ?></td>
<td><?php echo $row->name ?></td>
<td><?php if(!empty($row->created_date) and ($row->status == 1 or $row->status == 0)) { echo date('H:i', strtotime($row->created_date)); } else { echo '--'; } ?></td>
<td><?php if(!empty($row->alasan)) { echo $row->alasan; } else { echo '--'; } ?></td>
<td><?php if(!empty($row->status)) { if($row->status == 0) echo 'Belum Logout'; elseif($row->status == 99) echo 'Sakit'; elseif($row->status == 100) echo 'Izin'; elseif($row->status == 101) echo 'Alpa'; else echo 'Sudah Logout'; } else echo '--'?></td>
<td><?php if(!empty($row->status) or !empty($row->alasan)) { ?>
<button disabled class="btn btn-warning">Sakit</button> <button disabled  class="btn btn-success">Izin</button> <button disabled  class="btn btn-danger">Alpa</button> <?php } 
else { ?> 
<button onclick="redirect('<?php echo Yii::app()->createUrl('site/setAbsenStatus/?user_id='.$row->real_user_id.'&type=sakit') ?>','Konfirmasi Absen Sakit ?');" class="btn btn-warning">Sakit</button> <button onclick="redirect('<?php echo Yii::app()->createUrl('site/setAbsenStatus/?user_id='.$row->real_user_id.'&type=izin') ?>','Konfirmasi Absen Izin ?');" class="btn btn-success">Izin</button> <button onclick="redirect('<?php echo Yii::app()->createUrl('site/setAbsenStatus/?user_id='.$row->real_user_id.'&type=alpa') ?>','Konfirmasi Absen Alpa ?');" class="btn btn-danger">Alpa</button>
<?php } ?>
</td>
</tr>
<?php
}
?>
</table>

<?php	
}
?>

<script>
function redirect(url,text) {
  if (confirm(text)) {
    window.location.href=url;
  }
  return false;
}
</script>