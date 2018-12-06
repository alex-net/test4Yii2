<?php 
use yii\helpers\Html;
use yii\grid\GridView;
?>
<h1>Задачи</h1>

<?=Html::a('Новая задача',['admin/task-add']);?>


<?=GridView::widget([
	'dataProvider'=>$dp,
	'columns'=>[
		'id',['attribute'=>'name','content'=>function($m,$k,$i,$c){

			return Html::a($m->name,['admin/task-edit','id'=>$m->id]);

		}],'created','updated','termin','status:statustask'
	],
	]);?>