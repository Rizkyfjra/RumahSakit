<div class="login-page-container">
    <div class="login-page">
        <div class="container">
            <div class="col-md-10 col-md-push-1">
                <?php
                if ($berhasil != '') {
                    echo '<div class="alert alert-success notice">'
                    . '<strong>' . $berhasil . '</strong>'
                    . '<a href="#" class="close" data-dismiss="alert">×</a>'
                    . '</div>';
                }
                ?>
                <div class="login-box">
                    <div class="login-box-header">
                        <h1>Edubox</h1>
                        <div class="school-name">Upload Nilai Ujian Bersama</div>
                    </div>
                    <div class="col-card">
                        <form enctype="multipart/form-data" id="json-form" method="post">	 
                            <input value="test" name="Activities" type="hidden">
                            <div class="form-group">
                                <label for="Activities_Pilih_Excel_:">Pilih  File :</label>
                                <input id="jsonfile" type="file" value="" name="jsonfile[]" accept=".json">	  	 
                            </div>
                            <div class="form-group">
                                <input id="Import" name="Import" class="btn btn-success" type="submit" value="Import">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
