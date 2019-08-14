<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Pinisi School | e - Learning',
	'theme'=>'pinisiv2',

	// preloading 'log' component
	'preload'=>array(
		'log',
		'bootstrap',
		),

	'aliases' => array(
    	// 'bootstrap' => realpath(__DIR__ . '/../extensions/yiibooster-4.0.1'), // yiibooster
		'xupload' => 'ext.xupload',
		),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'application.extensions.*',
		'application.extensions.PasswordHash',
		'application.extensions.crontab.*',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'zxcv',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),
		
	),

	// application components
	'components'=>array(
		//yiibooster
		// 'bootstrap' => array(
	 //        'class' => 'bootstrap.components.Booster',
	 //        'fontAwesomeCss' => true, //font awesome yii booster
	 //    ),

	    'widgetFactory' => array(
        	'widgets' => array(
                'CLinkPager' => array(
                    //'header' => '<div>',
                    //'footer' => '</div>',
                    'header'=>'',
                    'maxButtonCount'=>5,
                    'firstPageLabel'=>'<<',
                    'lastPageLabel'=>'>>',
                    /*'previousPageCssClass'=>'hidden',
                    'nextPageCssClass'=>'hidden',*/
                    'prevPageLabel'=>'<',
                    'nextPageLabel'=>'>',
                    'selectedPageCssClass' => 'active',
                    'hiddenPageCssClass' => 'disabled',
                    'htmlOptions' => array(
                        'class' => 'pagination pagination-sm',
		            )
        	    )
        	)
        ),

		'user'=>array(
			// enable cookie-based authentication
			'class'=>'application.components.EWebUser',
			'allowAutoLogin'=>true,
		),
		// uncomment the following to enable URLs in path-format
		
		'dompdf'=>array(
			//'class'=>'ext.yiidompdf.yiidompdf'
			'class'=>'ext.dompdf.yiidompdf'
		),
		
		'image'=>array(
			'class'=>'application.extensions.image.CImageComponent',
			'driver'=>'GD',
			
		),

		'ECSVExport'=>array(
			'class'=>'ext.CsvExport'
		),

		/*'excel'=>array(
                  //'class'=>'ext.PHPExcel.PHPExcel',
			 	'class'=>'ext.PHPExcel.Classes.PHPExcel',
                ),*/
		'yexcel' => array(
		    'class' => 'ext.yexcel.Yexcel'
		),
		
		'urlManager'=>array(
			'urlFormat'=>'path',
			'showScriptName'=>false,
			'rules'=>array(
				'reset-password' => 'site/forget',


				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),
		
		
		// uncomment the following to use a MySQL database
		
		/*'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=mimha',
			'emulatePrepare' => true,
			'username' => 'mimha',
			'password' => 'mimha',
			'charset' => 'utf8',
		),*/

		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=schema',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => '',
			'charset' => 'utf8',
		),

		/*'session' => array(
		    'class' => 'system.web.CDbHttpSession',
		    'connectionID' => 'db',
		    'sessionTableName' => 'session',
		    'timeout' => 300,
		),*/

		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	// freezMthode = tutup (tidak ada freez), code (freez pakai code), pengawas(mode pengawas)
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'webmaster@example.com',
		'freezMethod'=>'tutup'
	),
);