<?php 
use yii\helpers\Html;
use app\assets\StatusAsset;
StatusAsset::register($this);
?>

<h1>Статусы</h1>
<?=Html::a('Новый статус',['admin/status-add']);?>

<?=\yii\widgets\ListView::widget([
	'dataProvider'=>$dp,
	'itemView'=>'status-el',
	'options'=>['class'=>'status-list'],
	'itemOptions'=>['class'=>'status-el'],
	]);?>