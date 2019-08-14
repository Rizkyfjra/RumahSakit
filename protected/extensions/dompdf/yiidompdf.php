<?php
 
require_once dirname(__FILE__).'/dompdf_config.inc.php';
Yii::registerAutoloader('DOMPDF_autoload');
 
class yiidompdf extends CApplicationComponent
{
    public $dompdf;
     
    public function init()
    {
        if($this->dompdf===null)
            $this->dompdf= new DOMPDF();
        return $this->dompdf;
    }
     
    public function generate($file, $filename='') 
    {
        $this->dompdf->load_html($file);
        $this->dompdf->render();
        $this->dompdf->stream($filename);
        //,array('Attachment'=>$download) , $download=true
    }

    public function generate2($file, $filename='',$location) 
    {
        $this->dompdf->load_html($file);
        $this->dompdf->render();
        $pdf = $this->dompdf->output();
        //$file_location = $_SERVER['DOCUMENT_ROOT']."/twitgreen-yii/pdfReports/".$filename;
        $file_location = $location."/".$filename;
        file_put_contents($file_location,$pdf);
        chmod($file_location, 0777); 
        //,array('Attachment'=>$download) , $download=true
    }
}
?>