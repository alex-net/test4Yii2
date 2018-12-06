<?php 

namespace app\controllers;

use Yii;
use app\models\LoginForm;
use app\models\User;
use yii\data\ActiveDataProvider;
use app\models\Status;
use app\models\Task;

/**
 * AdminController контроллер админки
 */

class AdminController extends \yii\web\Controller
{
	public function behaviors()
	{
		return [
			[
				'class'=>\yii\filters\AccessControl::className(),
				'rules'=>[
					['allow'=>true,'roles'=>['@'],]
				],
			]
		];
	}

	/**
     * Login action.
     *
     * @return Response|string
     */
    public function actionEnter()
    {
        if (!Yii::$app->user->isGuest) 
            return $this->goHome();

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) 
            return $this->redirect(['index']);

        $model->password = '';
        return $this->render('enter', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }

    /**
     * кабинет .. 
     * показываем форму редактирования текущего пользователя 
     */
    public function actionIndex()
    {
    	$m=Yii::$app->user->identity;
    	$m->scenario=User::SELF_EDIT;
    	$m->password='';
    	if (Yii::$app->request->isPost && $m->saveData(Yii::$app->request->post())){
    		Yii::$app->session->addFlash('info','Данные обновлены');
    		return $this->refresh();
    	}
    	return $this->render('index',['m'=>$m]);
    }

    /**
     * список пользователей 
     */
    public function actionUsers()
    {
    	$dp=new ActiveDataProvider([
    		'query'=>User::userList(),
    	]);
    	return $this->render('user-list',['dp'=>$dp]);
    }

    /**
     * Новый пользователь 
     */
    public function actionUserAdd()
    {
    	return $this->actionUserEdit(0);
    }

    /**
     * редактирование пользоватля ... 
     */
    public function actionUserEdit($id)
    {
    	$u=User::findOne($id);
    	if ($u)
    		$u->scenario=User::OTHER_EDIT;
    	else{
    		$u=new User();
    		$u->scenario=User::NEW_EDIT;
    	}
    	$u->password='';
    	if (Yii::$app->request->isPost){
	    	$post=Yii::$app->request->post();
	    	$issave=isset($post['save']);
	    	if ($u->saveData($post,$issave)){
	    		if ($issave)
	    			if ($id)
	    				Yii::$app->session->addFlash('info','Пользователь обновлён');
	    			else
	    				Yii::$app->session->addFlash('info','Пользователь создан');
	    		else
	    			Yii::$app->session->addFlash('info','Пользователь удалён');
	    		return $this->redirect(['users']);
	    	}
	    		
	    }

    	return $this->render('index',['m'=>$u]);

    }

    /**
     * Список статусов 
     */
    
    public function actionStatuses()
    {
    	$dp=new ActiveDataProvider([
    		'query'=>Status::find(),
    		'pagination'=>false,
    	]);
    	return $this->render('statuses-list',['dp'=>$dp]);
    }

	/**
	 * Добавить новый статус
	 */
	public function actionStatusAdd()
	{
		return  $this->actionStatusEdit(0);
	}
	/**
	 * Измнение статуса
	 */
	public function actionStatusEdit($id)
	{
		$s=Status::findOne($id);
		if (!$s)
			$s=new Status();

		if (Yii::$app->request->isPost){
			$post=Yii::$app->request->post();
			$issave=isset($post['save']);
			if ($s->saveData($post,$issave)){
				if ($issave)
					if($id)
						Yii::$app->session->addFlash('info','Статус обновлён');
					else
						Yii::$app->session->addFlash('info','Статус создан');

				else
					Yii::$app->session->addFlash('info','Статус удалён');
				return $this->redirect(['statuses']);
			}
		}

		return $this->render('status-edit',['m'=>$s]);
	}

	/**
	 * сохранение вестов статусов . 
	 */
	public function actionStatusesSetWeight()
	{
		Yii::$app->response->format=yii\web\Response::FORMAT_JSON;
		if (Yii::$app->request->isPost){
			$w=Yii::$app->request->post('weights');
			if ($w){
				Status::setStatusesWeight($w);
				return ['status'=>'ok'];
			}	
		}
		return ['status'=>'fail'];
	}


	/**
	 * Спискок задач 
	 */
	public function actionTasks()
	{
		$dp=new ActiveDataProvider([
			'query'=>Task::findUserTasks(Yii::$app->user->id),
		]);
		return $this->render('tasks',['dp'=>$dp]);
	}

	/**
	 * Новая задача
	 */
	public function actionTaskAdd()
	{
		return $this->actionTaskEdit(0);
	}

	/**
	 * редактирование задачи
	 */
	public function actionTaskEdit($id)
	{
		$t=Task::findOne($id);
		if (!$t)
			$t=new Task();
		if (Yii::$app->request->isPost){
			$post=Yii::$app->request->post();
			if ($t->saveData($post,isset($post['save']))){
				if (isset($post['save']))
					if ($id)
						Yii::$app->session->addFlash('info','Задача обновлена');
					else
						Yii::$app->session->addFlash('info','Задача создана');
				else
					Yii::$app->session->addFlash('info','Задача удалена');
				return $this->redirect(['tasks']);
			}
		}
		return $this->render('task-edit',['m'=>$t]);
	}
}