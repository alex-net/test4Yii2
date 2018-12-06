<?php 

namespace app\models;

/**
 * Class Status 
 *
 * модель статусов .. 
 */

class Status extends \yii\db\ActiveRecord
{
	public static function tableName()
	{
		return 'statuses';
	}

	public function attributeLabels()
	{
		return [
			'name'=>'Название',
		];
	}

	public function rules()
	{
		return [
			['name','string','max'=>255],
			['name','required'],
		];
	}

	/**
	 * сохранение/ удаление статуса ..
	 * @param array $post POST данные
	 * @param boolean $issave  true если происходит сохранение .. false = удаение
	 */
	
	public function saveData($post,$issave=true)
	{
		if($issave && (!$this->load($post) || !$this->validate()))
			return false;
		
		if (!$issave)
			$this->delete();
		else
			$this->save();		
	
		return true;
	}

	/**
	 * установить новые веса .. для статусов
	 * @param integer[] $weights упорядоченнй массив ключей 
	 */
	public static function setStatusesWeight($weights)
	{
		foreach($weights as $ind=>$sid){
			\Yii::$app->db->createCommand()->update('statuses',['weight'=>intval($ind)],['id'=>$sid])->execute();
		}
	}

	/**
	 * Вернуть статусы 
	 */
	public static function getStatuses()
	{
		$ar=static::find()->orderBy(['weight'=>SORT_ASC])->asArray()->all();
		$ar2=[];
		foreach($ar as $y)
			$ar2[$y['id']]=$y['name'];
		return $ar2;
	}

	/**
	 * Вернуть тексовое представление статуса по его номеруу  
	 * @param  integer $id Номер статуса 
	 * @return string    Текстовое обозначение статуса ...
	 */
	public static function nameById($id)
	{
		$s=static::findOne($id);
		return $s?$s->name:'';
	}
}