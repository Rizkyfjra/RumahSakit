
--------------------------------------
-- Scott Huang's 4rd widget 
-- Learn from EExcelView(Nikola Kostadinov) & EExcelViewBehavior(Jonathan Urzúa)
-- Sep 10,2014  ;  Xiamen, China
-- Zhiliang.Huang@gmail.com
--------------------------------------

New Features:
    1. Allow export grid color to excel. WYSWYG(What your see, what your get).
        1.1 Allow set header color, or even header column css
        1.2 Allow leverage your grid body columns css to render to excel.
    2. Allow free pane, default freeze cell B2.
    3. Allow define max export rows

More features you can add by yourselves after digest the code. Just like me extend the EExcelView.
    
Please add below cssStyle at the end of your file css\screen.css.   (Suggested, but not must, you can define your own css)
    td.pink{        background: #FFCCCC;}
    td.green{        background: #CCFF99;}
    td.lightgreen{        background: #CCFFCC;}
    td.yellow{        background: #FFFF99;}
    td.white{        background: #FFFFFF;}
    td.grey{        background: grey;}
    td.blue{        background: blue; color: white}
    td.lightblue{        background: #6666FF; color: white}
    td.notice {
    background:#FFF6BF;
    color:#514721;
    }

Examples 1: Normal way, define your grid in view, add behavior in controller, according flag to export to excel when needed.

In Control, add below function firstly:
<?php
    public function behaviors() {
        return array(
            'hzlexportgrid' => array(
                'class' => 'ext.Hzl.exportgrid.HzlExportGridBehavior',
            ),
        );
    }
?>

When need export to excel, use below code in control:
<?php
    if ($model->Confirm_Export) {//$model->Confirm_Export just a var in my own code, you can delete this line. Usually, I use this flag to identify whether need export or not)
    
                    //public function toExcel($model = null, $columns = array(), $title = null, $documentDetails = array(), $exportType = 'Excel2007') {
                    $this->toExcel(NPIHelper::getGSMAnalysisReport($GSMList), $yourColumnArray, 'WWSP Web Port - NPI GSM Analysys - ' . yii::app()->user->name . " - " . date('Ymd-His'));
                    
                    //or , if you try to customze and change the default settings.
                    $this->toExcel(NPIHelper::getGSMAnalysisReport($GSMList), $yourColumnArray, 'WWSP Web Port - NPI GSM Analysys - ' . yii::app()->user->name . " - " . date('Ymd-His'),
                    $domumentDetails = array(
                        'maxRow' => 5000, //define how many rows you want export.  Default is 0, according your dataset.
                        'freezePane' => 'B2', //default to B2, you can change to D2 or others cell value
                        'additionaArg'=> //important flag for change default color
                        array(
                'headerColor' => array('color' => 'FFFFFF', 'background' => '519CC6'), //header default cssClass //7CB5D5
                'headerColumnCssClass' => array(//header columns.  You can set as array() if no plan to customize
                    1 => array('color' => 'FFFFFF', 'background' => 'blue'),//column 1 will set to blue
                    'Region' => array('color' => 'FFFFFF', 'background' => 'blue')//column Region will set to blue too
                ), //define each column's cssClass for header line only.  
                'renderCssClass' => true,//turn on /off for body color
                'cssClass' =>//body color
                array(
                    'pink' => array('color' => '', 'background' => 'FFCCCC'),
                    'green' => array('color' => '', 'background' => 'CCFF99'),
                    'lightgreen' => array('color' => '', 'background' => 'CCFFCC'),
                    'yellow' => array('color' => '', 'background' => 'FFFF99'),
                    'white' => array('color' => '', 'background' => 'FFFFFF'),
                    'grey' => array('color' => 'FFFFFF', 'background' => '808080'),
                    'blue' => array('color' => 'FFFFFF', 'background' => 'blue'),
                    'lightblue' => array('color' => 'FFFFFF', 'background' => '6666FF'),
                    'notice' => array('color' => '514721', 'background' => 'FFF6BF'),
                    'header' => array('color' => 'FFFFFF', 'background' => '519CC6'),
                    //above for customzed columns per your real data css class
                    'odd' => array('color' => '', 'background' => 'E5F1F4'),//for odd row
                    'even' => array('color' => '', 'background' => 'F8F8F8'),//for even row
                )
                    ))//important to customze your own configuration if necessary
                    );
                }

?>

In View:
<?php
$columns = your columns array();
$columns[] = array('name' => 'FO_Due_PCT',
                'header' => 'FO Overdue%',
                'value' => '$data["FO_Due_PCT"]."%"',
                'cssClassExpression' => 'NPIHelper::notReadyColor($data["FO_Due_PCT"])'
                //it return green or yellow or pink css name, will render in export grid
                //you can use your function to auto return css name, 
                //To simplify, you can just use 'cssClassExpression' => 'green' 
    );


$this->widget('bootstrap.widgets.TbExtendedGridView'//you can use normal view(zii.widgets.grid.CGridView) or YiiBooser View(This example) or ext.Hzl.exportgrid.HzlExportGrid 
        , array(
    'id' => 'ShowMileStone-grid',
    //'fixedHeader' => true,
    'template' => "{pager}{summary}{items}{summary}{pager}",
    //'type' => 'bordered hover',
    'dataProvider' => your provider,
    'columns' => $columns,
        //'filter' => $model,
        )
);
        ?>

Example 2: Export in view directly
<?php
$arg = array(
    'dataProvider' => $model->search(),
    'grid_mode' => 'export', // 'grid',
    'freezePane' => 'B2', // or 'D2', any cell value
    'maxRow'=> 5000,// or 0
    //'additionalArg' => array(your config),
    'title' => 'Title',
    'filename' => 'report.xlsx',
    'stream' => true,
    'exportType' => 'Excel2007',
    'columns' => $columnArray,
);
$this->widget('ext.Hzl.exportgrid.HzlExportGrid', $arg); 
?>