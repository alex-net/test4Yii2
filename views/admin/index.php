<?php 
use \yii\widgets\ActiveForm;
use \yii\helpers\Html;
?>

<?php if ($m->scenario==$m::SELF_EDIT):?>
	<h1>Кабинет</h1>
<?php else:?>
	<h1><?=$m->id?'Редактирование':'Содание';?> пользователя</h1>
<?php endif;?>

<?php $f=ActiveForm::begin();?>
<?=$f->field($m,'username');?>
<?=$f->field($m,'password');?>
<?=$f->field($m,'status')->checkbox();?>
<?=Html::submitButton('Сохранить',['name'=>'save']);?>
<?php if ($m->scenario==$m::OTHER_EDIT && $m->id):?>
	<?=Html::submitButton('Удалить',['name'=>'kill']);?>
<?php endif;?>
<?php ActiveForm::end(); ?>
