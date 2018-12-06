<?php 

namespace app\components;

/**
 * Форматтер приожения 
 */
class AppFormatter extends \yii\i18n\Formatter
{
	public function asStatusFormat($val)
	{
		\Yii::info($val,'$val');
		return $val?'':'не '.'активный';
	}
}