<?php 
use \yii\helpers\Html;
?>
<h1>Список пользователей</h1>
<?=Html::a('Новый пользователь',['admin/user-add']);?>
<?=\yii\grid\GridView::widget([
	'dataProvider'=>$dp,
	'columns'=>[['attribute'=>'username','content'=>function($m,$k,$i,$c){return Html::a($m->username,['admin/user-edit','id'=>$k]);}],'status:statusformat'],
]);
?>