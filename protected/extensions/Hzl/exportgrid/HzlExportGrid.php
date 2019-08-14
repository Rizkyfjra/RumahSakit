<?php

Yii::import('zii.widgets.grid.CGridView');

/**
 * @author Nikola Kostadinov,   Scott Huang(China Xiamen)
 * @license MIT License
 * @version 0.3 ==> Improve to verson 1.0 at 20140910
 */
class HzlExportGrid extends CGridView {

    //Document properties
    public $freezePane = 'B2'; //add by Scott
    public $maxRow = 0; //add by Scott
    public $rowCount = 0; //add by Scott
    public $colCount = 0; //add by Scott
    public $additionalArg = array(); //add by Scott
    public $creator = 'Scott Huang'; // 'Nikola Kostadinov';
    public $title = null;
    public $subject = 'Subject';
    public $description = '';
    public $category = '';
    //the PHPExcel object
    public $objPHPExcel = null;
    public $libPath = 'ext.phpexcel.Classes.PHPExcel'; //the path to the PHP excel lib
    //config
    public $autoWidth = true;
    public $exportType = 'Excel5';
    public $disablePaging = true;
    public $filename = null; //export FileName
    public $stream = true; //stream to browser
    public $grid_mode = 'grid'; //Whether to display grid ot export it to selected format. Possible values(grid, export)
    public $grid_mode_var = 'grid_mode'; //GET var for the grid mode
    //buttons config
    public $exportButtonsCSS = 'summary';
    public $exportButtons = array('Excel2007');
    public $exportText = 'Export to: ';
    //callbacks
    public $onRenderHeaderCell = null;
    public $onRenderDataCell = null;
    public $onRenderFooterCell = null;
    //data
    // For performance reason, it's good to have it static and populate it once in all the execution
    public static $data = null;
    //mime types used for streaming
    public $mimeTypes = array(
        'Excel5' => array(
            'Content-type' => 'application/vnd.ms-excel',
            'extension' => 'xls',
            'caption' => 'Excel(*.xls)',
        ),
        'Excel2007' => array(
            'Content-type' => 'application/vnd.ms-excel',
            'extension' => 'xlsx',
            'caption' => 'Excel(*.xlsx)',
        ),
        'PDF' => array(
            'Content-type' => 'application/pdf',
            'extension' => 'pdf',
            'caption' => 'PDF(*.pdf)',
        ),
        'HTML' => array(
            'Content-type' => 'text/html',
            'extension' => 'html',
            'caption' => 'HTML(*.html)',
        ),
        'CSV' => array(
            'Content-type' => 'application/csv',
            'extension' => 'csv',
            'caption' => 'CSV(*.csv)',
        )
    );

    public function init() {
        if (isset($_GET[$this->grid_mode_var])) {
            $this->grid_mode = $_GET[$this->grid_mode_var];
        }


        if (isset($_GET['exportType']))
            $this->exportType = $_GET['exportType'];

        $lib = Yii::getPathOfAlias($this->libPath) . '.php';
        if ($this->grid_mode == 'export' and !file_exists($lib)) {
            $this->grid_mode = 'grid';
            //echo "PHP Excel lib not found($lib). Export disabled !", CLogger::LEVEL_WARNING, 'EExcelview';
            Yii::log("PHP Excel lib not found($lib). Export disabled !", CLogger::LEVEL_WARNING, 'HZLEXPORTGRID');
        }

        if ($this->grid_mode == 'export') {
            $this->title = $this->title ? $this->title : Yii::app()->getController()->getPageTitle();
            $this->initColumns();
            //parent::init();
            //Autoload fix
            spl_autoload_unregister(array('YiiBase', 'autoload'));
            Yii::import($this->libPath, true);
            $this->objPHPExcel = new PHPExcel();
            spl_autoload_register(array('YiiBase', 'autoload'));
            // Creating a workbook
            $this->objPHPExcel->getProperties()->setCreator($this->creator);
            $this->objPHPExcel->getProperties()->setTitle($this->title);
            $this->objPHPExcel->getProperties()->setSubject($this->subject);
            $this->objPHPExcel->getProperties()->setDescription($this->description);
            $this->objPHPExcel->getProperties()->setCategory($this->category);

            $this->colCount = count($this->columns);
            $this->additionalArg = array_merge(
                    array(
                'headerColor' => array('color' => 'FFFFFF', 'background' => '519CC6'), //header default cssClass //7CB5D5
                'headerColumnCssClass' => array(
//                    1 => array('color' => 'FFFFFF', 'background' => 'blue'),
//                    'Region' => array('color' => 'FFFFFF', 'background' => 'blue')
                ), //define each column's cssClass for header line only.  You can set as blank.
                'renderCssClass' => true,
                'cssClass' =>
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
                    'odd' => array('color' => '', 'background' => 'E5F1F4'),
                    'even' => array('color' => '', 'background' => 'F8F8F8'),
                )
                    ), $this->additionalArg); //use user additional arg to overwrite default additional arg, by scott
        } else
            parent::init();
    }

    public function renderHeader() {
        $a = 0;

        //color
        if ($this->additionalArg["headerColor"]) {
            $lineRange = "A1:" . $this->columnName($this->colCount) . "1";
            if ($this->additionalArg["headerColor"]["color"])
            //$this->objPHPExcel->getActiveSheet()->getStyle($this->columnName($a) . ($row + 2))->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
                $this->objPHPExcel->getActiveSheet()->getStyle($lineRange)->getFont()->getColor()->setARGB($this->additionalArg["headerColor"]["color"]);
            //background
            if ($this->additionalArg["headerColor"]["background"]) {
                $this->objPHPExcel->getActiveSheet()->getStyle($lineRange)->getFill()->getStartColor()->setRGB($this->additionalArg["headerColor"]["background"]);
                $this->objPHPExcel->getActiveSheet()->getStyle($lineRange)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            }
        }

        foreach ($this->columns as $column) {
            $a = $a + 1;
            if ($column instanceof CButtonColumn)
                $head = $column->header;
            elseif ($column->header === null && $column->name !== null) {
                if ($column->grid->dataProvider instanceof CActiveDataProvider)
                    $head = $column->grid->dataProvider->model->getAttributeLabel($column->name);
                else
                    $head = $column->name;
            } else
                $head = trim($column->header) !== '' ? $column->header : $column->grid->blankDisplay;

            $head = str_replace("&nbsp", "", $head); //add by Scott


            $cssClass = null;
            // if ($this->additionalArg["renderCssClass"] == true) {
            if ($this->additionalArg["headerColumnCssClass"]) {
                //$cssClass = $this->evaluateExpression($column->cssClassExpression, array('data' => $data[$row]));
                $cssClass = isset($this->additionalArg["headerColumnCssClass"][$a]); //by int
                if (!$cssClass) {//if no, try find by name
                    $cssClass = isset($this->additionalArg["headerColumnCssClass"][$head]);
                    if ($cssClass)
                        $cssClass = $this->additionalArg["headerColumnCssClass"][$head];
                } else {
                    $cssClass = $this->additionalArg["headerColumnCssClass"][$a];
                }
                if ($cssClass) {
                    //color
                    if (isset($cssClass["color"]) and $cssClass["color"])
                    //$this->objPHPExcel->getActiveSheet()->getStyle($this->columnName($a) . ($row + 2))->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
                        $this->objPHPExcel->getActiveSheet()->getStyle($this->columnName($a) . "1")->getFont()->getColor()->setARGB($cssClass["color"]);
                    //background
                    if (isset($cssClass["background"]) and $cssClass["background"]) {
                        $this->objPHPExcel->getActiveSheet()->getStyle($this->columnName($a) . "1")->getFill()->getStartColor()->setRGB($cssClass["background"]);
                        $this->objPHPExcel->getActiveSheet()->getStyle($this->columnName($a) . "1")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
                    }
                }
            }
            // }

            $cell = $this->objPHPExcel->getActiveSheet()->setCellValue($this->columnName($a) . "1", $head, true);
            if (is_callable($this->onRenderHeaderCell))
                call_user_func_array($this->onRenderHeaderCell, array($cell, $head));
        }
    }

    public function renderBody() {
        if ($this->disablePaging) //if needed disable paging to export all data
            $this->dataProvider->pagination = false;

        //$data = $this->dataProvider->getData();
        self::$data = $this->dataProvider->getData();
        //$n = count($data);
        $n = count(self::$data);
        $this->rowCount = $n;


        if ($this->maxRow > 0)
            $n = ($n > $this->maxRow ? $this->maxRow : $n); //by scott, add max row parameter

        if ($this->maxRow > 0 and $this->rowCount > $this->maxRow) {
            //delete unnecessary rows.
            for ($i = $this->maxRow; $i < $this->rowCount; ++$i)
                unset(self::$data[$i]);
        }

//        if ($n > 1000) {//turn off renderClass if row > 1000 to expedite speed.
//            $this->additionalArg = array_merge($this->additionalArg, array('renderCssClass' => false,));
//        }

        if ($n > 0) {
            for ($row = 0; $row < $n; ++$row)
                $this->renderRow($row);
        }
        return $n;
    }

    public function renderRow($row) {
//        $data = $this->dataProvider->getData();

        $a = 0;


        if ($this->additionalArg["renderCssClass"] == true) {//use odd and even
            $lineRange = "A" . ($row + 2) . ":" . $this->columnName($this->colCount) . ($row + 2);
            if (($row + 1) % 2 == 1 and isset($this->additionalArg["cssClass"]["odd"])) {
                if ($this->additionalArg["cssClass"]["odd"]["color"])
                //$this->objPHPExcel->getActiveSheet()->getStyle($this->columnName($a) . ($row + 2))->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
                    $this->objPHPExcel->getActiveSheet()->getStyle($lineRange)->getFont()->getColor()->setARGB($this->additionalArg["cssClass"]["odd"]["color"]);
                //background
                if ($this->additionalArg["cssClass"]["odd"]["background"]) {
                    $this->objPHPExcel->getActiveSheet()->getStyle($lineRange)->getFill()->getStartColor()->setRGB($this->additionalArg["cssClass"]["odd"]["background"]);
                    $this->objPHPExcel->getActiveSheet()->getStyle($lineRange)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
                }
            } else if (($row + 1) % 2 == 0 and isset($this->additionalArg["cssClass"]["even"])) {
                if ($this->additionalArg["cssClass"]["even"]["color"])
                //$this->objPHPExcel->getActiveSheet()->getStyle($this->columnName($a) . ($row + 2))->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
                    $this->objPHPExcel->getActiveSheet()->getStyle($lineRange)->getFont()->getColor()->setARGB($this->additionalArg["cssClass"]["even"]["color"]);
                //background
                if ($this->additionalArg["cssClass"]["even"]["background"]) {
                    $this->objPHPExcel->getActiveSheet()->getStyle($lineRange)->getFill()->getStartColor()->setRGB($this->additionalArg["cssClass"]["even"]["background"]);
                    $this->objPHPExcel->getActiveSheet()->getStyle($lineRange)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
                }
            }
        }

        foreach ($this->columns as $n => $column) {
            if ($column instanceof CLinkColumn) {
                if ($column->labelExpression !== null)
                //$value = $column->evaluateExpression($column->labelExpression, array('data' => $data[$row], 'row' => $row));
                    $value = $column->evaluateExpression($column->labelExpression, array('data' => self::$data[$row], 'row' => $row));
                else
                    $value = $column->label;
            } elseif ($column instanceof CButtonColumn)
                $value = ""; //Dont know what to do with buttons
            elseif ($column->value !== null)
            //$value = $this->evaluateExpression($column->value, array('data' => $data[$row]));
                $value = $this->evaluateExpression($column->value, array('data' => self::$data[$row]));
            elseif ($column->name !== null) {
                //$value=$data[$row][$column->name];
                //$value = CHtml::value($data[$row], $column->name);
                $value = CHtml::value(self::$data[$row], $column->name);
                $value = $value === null ? "" : $column->grid->getFormatter()->format($value, 'raw');
            }
            $a++;

            $cssClass = null;
            if ($this->additionalArg["renderCssClass"] == true) {
                if ($column->cssClassExpression) {
                    //$cssClass = $this->evaluateExpression($column->cssClassExpression, array('data' => $data[$row]));
                    $cssClass = $this->evaluateExpression($column->cssClassExpression, array('data' => self::$data[$row]));
                    if ($cssClass and $cssClass != "noChange" and $cssClass != "NoNeedChangeColor") {
                        //color
                        if (isset($this->additionalArg["cssClass"][$cssClass])) {
                            if ($this->additionalArg["cssClass"][$cssClass]["color"])
                            //$this->objPHPExcel->getActiveSheet()->getStyle($this->columnName($a) . ($row + 2))->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
                                $this->objPHPExcel->getActiveSheet()->getStyle($this->columnName($a) . ($row + 2))->getFont()->getColor()->setARGB($this->additionalArg["cssClass"][$cssClass]["color"]);
                            //background
                            if ($this->additionalArg["cssClass"][$cssClass]["background"]) {
                                $this->objPHPExcel->getActiveSheet()->getStyle($this->columnName($a) . ($row + 2))->getFill()->getStartColor()->setRGB($this->additionalArg["cssClass"][$cssClass]["background"]);
                                $this->objPHPExcel->getActiveSheet()->getStyle($this->columnName($a) . ($row + 2))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
                            }
                        }
                    }
                }
            }
            $cell = $this->objPHPExcel->getActiveSheet()->setCellValue($this->columnName($a) . ($row + 2), strip_tags($value), true);
            if (is_callable($this->onRenderDataCell))
            //call_user_func_array($this->onRenderDataCell, array($cell, $data[$row], $value));
                call_user_func_array($this->onRenderDataCell, array($cell, self::$data[$row], $value));
        }
        // As we are done with this row we DONT need this specific record
        unset(self::$data[$row]);
    }

    public function renderFooter($row) {
        $a = 0;
        foreach ($this->columns as $n => $column) {
            $a = $a + 1;
            if ($column->footer) {
                $footer = trim($column->footer) !== '' ? $column->footer : $column->grid->blankDisplay;

                $cell = $this->objPHPExcel->getActiveSheet()->setCellValue($this->columnName($a) . ($row + 2), $footer, true);
                if (is_callable($this->onRenderFooterCell))
                    call_user_func_array($this->onRenderFooterCell, array($cell, $footer));
            }
        }
    }

    public function run() {
        if ($this->grid_mode == 'export') {
            $this->renderHeader();
            $row = $this->renderBody();
            $this->renderFooter($row);

            if ($this->maxRow > 0 and $this->rowCount > $this->maxRow) {
                $cell = $this->objPHPExcel->getActiveSheet()->setCellValue("a" . ($row + 3), "Only export max rows: " . $this->maxRow, true); //add by Scott
//                // As we are done with this row we DONT need this record
//                for ($i = $this->maxRow; $i < $this->rowCount; ++$i)
//                    unset(self::$data[$i]);
            }
            if ($this->freezePane)
                $this->objPHPExcel->getActiveSheet()->freezePane($this->freezePane); //add by Scott
//set auto width
            if ($this->autoWidth)
                foreach ($this->columns as $n => $column)
                    $this->objPHPExcel->getActiveSheet()->getColumnDimension($this->columnName($n + 1))->setAutoSize(true);
            //create writer for saving
            $objWriter = PHPExcel_IOFactory::createWriter($this->objPHPExcel, $this->exportType);
            if (!$this->stream)
                $objWriter->save($this->filename);
            else { //output to browser
                if (!$this->filename)
                    $this->filename = $this->title;
                $this->cleanOutput();
                header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                header('Pragma: public');
                header('Content-type: ' . $this->mimeTypes[$this->exportType]['Content-type']);
                header('Content-Disposition: attachment; filename="' . $this->filename . '.' . $this->mimeTypes[$this->exportType]['extension'] . '"');
                header('Cache-Control: max-age=0');
                $objWriter->save('php://output');
                Yii::app()->end();
            }
        } else
            parent::run();
    }

    /**
     * Returns the coresponding excel column.(Abdul Rehman from yii forum)
     * 
     * @param int $index
     * @return string
     */
    public function columnName($index) {
        --$index;
        if ($index >= 0 && $index < 26)
            return chr(ord('A') + $index);
        else if ($index > 25)
            return ($this->columnName($index / 26)) . ($this->columnName($index % 26 + 1));
        else
            throw new Exception("Invalid Column # " . ($index + 1));
    }

    public function renderExportButtons() {
        foreach ($this->exportButtons as $key => $button) {
            $item = is_array($button) ? CMap::mergeArray($this->mimeTypes[$key], $button) : $this->mimeTypes[$button];
            $type = is_array($button) ? $key : $button;
            $url = parse_url(Yii::app()->request->requestUri);
            //$content[] = CHtml::link($item['caption'], '?'.$url['query'].'exportType='.$type.'&'.$this->grid_mode_var.'=export');
            if (key_exists('query', $url))
                $content[] = CHtml::link($item['caption'], '?' . $url['query'] . '&exportType=' . $type . '&' . $this->grid_mode_var . '=export');
            else
                $content[] = CHtml::link($item['caption'], '?exportType=' . $type . '&' . $this->grid_mode_var . '=export');
        }
        if ($content)
            echo CHtml::tag('div', array('class' => $this->exportButtonsCSS), $this->exportText . implode(', ', $content));
    }

    /**
     * Performs cleaning on mutliple levels.
     * 
     * From le_top @ yiiframework.com
     * 
     */
    private static function cleanOutput() {
        for ($level = ob_get_level(); $level > 0; --$level) {
            @ob_end_clean();
        }
    }

}
