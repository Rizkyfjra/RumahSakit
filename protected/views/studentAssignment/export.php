<?php /*$this->widget('EExcelWriter', array(
    'dataProvider' => $model->search(),
    'title'        => 'EExcelWriter',
    'stream'       => FALSE,
    'fileName'     => Yii::app()->baseUrl.'/images/file.xls',
    'columns'      => array(
        array(
            'header' => 'id',
            'name' => 'ID',
        ),
        'column1',
        'column2',
    ),
));*/
/*$this->widget('ext.EExcelView.EExcelView', array(
     'dataProvider'=> $dataProvider,
     'title'=>'Title',
     'autoWidth'=>false,
     'template'=>"{summary}\n{items}\n{exportbuttons}\n{pager}",
     'exportType'=>'Excel2007',
     'filename'=>Yii::app()->baseUrl.'/images/file.xlsx',
));*/

/*$arg = array(
    'dataProvider' =>$dataProvider->search(),
    'grid_mode' => 'export', // 'grid',
    'freezePane' => 'B2', // or 'D2', any cell value
    'maxRow'=> 5000,// or 0
    //'additionalArg' => array(your config),
    'title' => 'Title',
    'filename' => 'report.xlsx',
    'stream' => true,
    'exportType' => 'Excel2007',
);
$this->widget('ext.Hzl.exportgrid.HzlExportGrid', $arg); */

$data = array(
    1 => array ('Name', 'Surname'),
    array('Schwarz', 'Oliver'),
    array('Test', 'Peter')
);
Yii::import('application.extensions.phpexcel.JPhpExcel');
$xls = new JPhpExcel('UTF-8', false, 'My Test Sheet');
$xls->addArray($data);
$xls->generateXML('my-test');

?>