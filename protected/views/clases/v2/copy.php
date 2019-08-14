<div class="container-fluid">
  <div class="row">
    <?php
      // $this->renderPartial('v2/_breadcrumb_clases_copy', array(

      // ));
    ?>
    <div class="col-md-12">
	  <div id="bc1" class="btn-group btn-breadcrumb">
		<?php echo CHtml::link('<i class="fa fa-home"></i> Beranda',array('/site/index'), array('class'=>'btn btn-default')); ?>
		<?php echo CHtml::link('<div>Kelas</div>',array('/clases/index'), array('class'=>'btn btn-default')); ?>
		<?php echo CHtml::link('<div>Pendaftaran Kelas</div>',array('#'), array('class'=>'btn btn-success')); ?>
	  </div>
	</div>

  <div class="col-lg-12">
	 <form method="post" action="" onsubmit="return confirm('Yakin selesai menambahkan daftar kelas ?');">
	  <div class="row clearfix">&nbsp;</div>
    <div class="row">
      <div class="col-md-10 col-md-offset-1">
	    	<div class="col-card">
	            <div class="panel-body">
		            <div class="row">
						      <h3>Daftarkan Kelas</h3>
		            	<div class="form-group" id="initial">
							      <div class="col-md-12">
								      <label>Nama Kelas</label>
							      </div>
							      <div class="col-md-12">
								      <div class="input-group">
										    <input type="text" class="form-control" name="kelas[]">
									      <div class="input-group-addon">
										      <select name="grade[]">
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
    												<option value="11">11</option>
    												<option value="12">12</option>
										      </select>
									      </div>
									    </div>
								  </div>
								  <div class="clearfix"></div>
							</div>
			        <div class="col-md-12 col-lg-12">
	               <a href="#" type="button" id="tambah" class="btn btn-primary btn-block btn-sm"><i class="fa fa-plus-circle"></i> Tambah Kelas</a>
			        </div>
              <br/>
              <hr/>
		          <div class="col-md-12 col-lg-12">
			             <button type="submit" class="btn btn-pn-primary btn-lg btn-pn-round btn-block next-step">Daftarkan Kelas</button>
				      </div>
		        </div>
		      </div>
		    </div>
	    </div>
    </div>
   </form>
  </div>
 </div>
</div>
<script type="text/javascript">
	$("#tambah").click(function(){
		$('#initial').append('<br/><div class="col-md-12"><div class="input-group"><input type="text" class="form-control" name="kelas[]"><div class="input-group-addon"><select name="grade[]"><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option></select></div></div></div><div class="clearfix"></div>');
	});
</script>
