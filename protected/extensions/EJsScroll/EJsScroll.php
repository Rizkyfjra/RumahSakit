<?php
/**
 * EJsScroll
 * =================
 * This extension beautify the vertical and horizontal bars (scrolling) by javascript
 * Links:
 * - EJsScroll demo: http://www.webkit.gr/index.php/Apps/mountainpopupScroll
 * - SliderPopImage site: http://www.yiiframework.com/extension/ejsscroll
 *
 * The javascript and css files are Licensed on http://jscrollpane.kelvinluck.com/MIT-LICENSE.txt
 * You can see all the demos of the javascript author on http://jscrollpane.kelvinluck.com
 * @version 1.0
 * @author Konstaninos Apazidis <konapaz@gmail.com>
 * @date 25 May 2013
 * */

class EJsScroll extends CWidget {

    public $selector = '#content';
    public $showArrowsBar = true;

    public function run() {

        $url = Yii::app()->assetManager->publish(Yii::getPathOfAlias('ext.EJsScroll.assets'), false, -1, YII_DEBUG);

        Yii::app()->clientScript->registerCssFile($url . '/css/jquery.jscrollpane.css');
        Yii::app()->clientScript->registerCssFile($url . '/css/scrollstyle.css');

        Yii::app()->clientScript->registerScriptFile($url . '/js/jquery.jscrollpane.js', CClientScript::POS_HEAD);
        Yii::app()->clientScript->registerScriptFile($url . '/js/jquery.mousewheel.js', CClientScript::POS_HEAD);


        $settingsScroll = '{
		showArrows: '. ($this->showArrowsBar ? 'true' : 'false') .',
		autoReinitialise: true
	}';

        Yii::app()->clientScript->registerScript('scrollbar', '$("' . $this->selector . '").jScrollPane(' . $settingsScroll . ');', CClientScript::POS_READY);
    }

}

?>