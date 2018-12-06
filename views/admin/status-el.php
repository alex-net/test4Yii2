<?=\yii\helpers\Html::a($model->name,['admin/status-edit','id'=>$model->id]);?>
<input type="hidden" name='weight[<?=$model->id;?>]' value="<?=$model->weight;?>">