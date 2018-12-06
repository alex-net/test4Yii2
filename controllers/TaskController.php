<?php 

namespace app\controllers;

use app\models\Task;
use Yii;
use yii\web\ServerErrorHttpException;
use yii\web\NotFoundHttpException;
use app\models\Status;


/**
 * контроллер работы с задачами по restAPI
 */

class TaskController extends \yii\rest\ActiveController
{
	public $modelClass='app\models\Task';

	/**
	 * обновление статуса задачи 
	 * @param  integer $id ID сузности задачи
	 * @return \yii\db\ActiveRecord Обновлённая сущность
	 * @throws ServerErrorHttpException ошибка запроса 
	 * @throws NotFoundHttpException страница не найдена 
	 */
	public function actionStatusChange($id)
	{
		$t=Task::findOne($id);
		if(!$t)
			throw new NotFoundHttpException('Не найдено');
			
		$params=Yii::$app->request->bodyParams;
		if (!isset($params['status']))
			throw new  ServerErrorHttpException('Отсутствует поле status');
		$s=Status::findOne($params['status']);
		if (!$s)
			throw new  ServerErrorHttpException('Указан неверный статус');

		$t->status=$s->id;
		$t->save();
		return $t;
	}
}