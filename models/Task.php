<?php 

namespace app\models;


class Task extends \yii\db\ActiveRecord
{
	public static function tableName()
	{
		return 'tasks';
	}

	public function attributeLabels()
	{
		return [
			'name'=>'Название',
			'descr'=>'Описание',
			'termin'=>'Срок выполнения',
			'status'=>'Статус',
			'created'=>'Создана',
			'updated'=>'Обновлена',
		];
	}

	public function rules()
	{
		return [
			['name','required'],
			['name','string','max'=>255],
			['descr','safe'],
			['termin','datetime','type'=>\yii\validators\DateValidator::TYPE_DATETIME],
			['status','in','range'=>array_keys(Status::getStatuses()),'skipOnEmpty'=>false],
		];
	}

	public function attributeHints()
	{
		return ['termin'=>'Фромат Y-m-d H:i'];
	}
	/**
	 * [beforeSave description]
	 * @param  boolean $ins признак вставки .. если false = замена элмента 
	 * @return [type]      
	 */
	public function beforeSave($ins)
	{
		if (!parent::beforeSave($ins))
			return false;

		$this->updated=date('c');
		if ($this->isNewRecord)
			$this->created=date('c');
		
		return true;
	}

	/**
	 * Сохранение данных .. из формы
	 * @param array $post POST запрос от формы 
	 * @param boolean $issave признак сохранения = true ..  false = удалить
	 * @return boolean результат завершения операции 
	 */
	public function saveData($post,$issave=true)
	{
		if (!$this->load($post))
			return false;

		if ($issave && !$this->validate() || !$issave && !$this->validate('id'))
			return false;

		if ($issave){
			$this->uid=\Yii::$app->user->id;
			\Yii::info($this->attributes,'ssd');
			$this->save();
		}
		else
			$this->delete();
		return true;
	}


	/**
	 * Венуть задачи пользователя
	 */
	public static function findUserTasks($uid)
	{
		
		return static::find()->where(['uid'=>$uid]);
	}
}