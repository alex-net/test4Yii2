<?php 

namespace app\assets;

class JqUiAsset extends \yii\web\AssetBundle
{
	public $sourcePath='@npm/jqueryui';

	public $js=[
		'jquery-ui.min.js'
	];

	public $css=[
		'jquery-ui.min.css',
		'jquery-ui.structure.min.css',
		'jquery-ui.theme.min.css',
	];

	public $depends=[
		'yii\web\JqueryAsset',
	];
}