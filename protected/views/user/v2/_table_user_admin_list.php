<tr>
  <th class="text-center"><?php echo $no; ?></th>
  <td>
    <?php echo CHtml::link(CHtml::encode($value->username), array('/user/view', 'id'=>$value->id)); ?>
  </td>
  <td>
    <?php echo CHtml::link(CHtml::encode($value->display_name), array('/user/view', 'id'=>$value->id)); ?>
  </td>
  <td>
    <?php echo $value->id; ?>
  </td>
  <td>
    <?php echo $value->email; ?>
  </td>
  <td>
    <?php echo $value->reset_password; ?>
  </td>
  <td>
    <?php
      if($value->role_id == 2){
        echo "Siswa";
      }elseif($value->role_id == 1){
        echo "Guru";
      }elseif($value->role_id == 3){
        echo "Kepala Sekolah";
      }elseif($value->role_id == 4){
        echo "Wali Kelas";  
      }elseif($value->role_id == 99){
        echo "Administrator";
      }
    ?>
  </td>
  <td>
    <?php
      if(!empty($value->class_id)){
        $kls = ClassDetail::model()->findAll(array("condition"=>"id = ".$value->class_id));
        if(!empty($kls)){
          echo $kls[0]->name;
        }
      }
    ?>
  </td>
  <td>
    <div class="btn-group">
      <?php echo CHtml::link('<i class="fa fa-eye"></i>',array('view', 'id'=>$value->id), array('class'=>'btn btn-success btn-xs')); ?>
      <?php echo CHtml::link('<i class="fa fa-pencil"></i>',array('update', 'id'=>$value->id), array('class'=>'btn btn-primary btn-xs')); ?>
      <?php echo CHtml::link('<i class="fa fa-trash"></i>',array('hapus', 'id'=>$value->id), array('class'=>'btn btn-danger btn-xs','title'=>'Hapus','confirm'=>'Yakin Menghapus Pengguna Ini?')); ?>
    </div>
  </td>
</tr>
