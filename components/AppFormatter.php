<?php 

namespace app\components;

/**
 * Форматтер приожения 
 */
class AppFormatter extends \yii\i18n\Formatter
{
	/**
	 * формирование ячейки статуса пользователя
	 * @param  boolean $val Входные данные 
	 * @return string      Строка форматированная 
	 */
	public function asStatusFormat($val)
	{
		\Yii::info($val,'$val');
		return $val?'':'не '.'активный';
	}
	/**
	 * Форматирование статуса заявки 
	 * @param  integer $val цифровое значение статуса 
	 * @return string      Строка форматированная 
	 */
	public function asStatusTask($val)
	{
		return \app\models\Status::nameById($val);
	}
}