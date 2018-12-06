<?php 
use \yii\widgets\ActiveForm;
use \yii\helpers\Html;
?>

<h1>
	<?=$m->id?'Редактирование статуса':'Новый статус';?></h1>

<?php $f=ActiveForm::begin();?>
<?=$f->field($m,'name');?>
<?=Html::submitButton('Сохранить',['name'=>'save']);?>
<?php if ($m->id):?>
	<?=Html::submitButton('Удалить',['name'=>'kill']);?>
<?php endif;?>
<?php ActiveForm::end();?>
