<?php 
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use kartik\datetime\DateTimePicker;
?>
<h1><?=$m->id?'Редактирование задачи':'Новая задача';?></h1>
<?php $f=ActiveForm::begin();?>
<?=$f->field($m,'name');?>
<?=$f->field($m,'descr')->textarea();?>
<?=$f->field($m,'termin')->widget(DateTimePicker::className(),[
	'pluginOptions' => [
       	'format' => 'yyyy-mm-dd HH:ii:ss',
        'startDate' => date('c'),
    ],
]);?>
<?=$f->field($m,'status')->dropDownList(\app\models\Status::getStatuses());?>

<?=Html::submitButton('Сохранить',['name'=>'save']);?>
<?php if ($m->id):?>
	<?=Html::submitButton('Удалить',['name'=>'kill']);?>
<?php endif;?>
<?php ActiveForm::end();?>