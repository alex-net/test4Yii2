<?php 

namespace app\assets;

class StatusAsset extends \yii\web\AssetBundle
{
	public $baePath='@webroot/statuslist';
	public $baseUrl='@web/statuslist';

	public $js=[
		'script.js',
	];

	public $depends=[
		'app\assets\JqUiAsset'
	];

	
}