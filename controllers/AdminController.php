<?php 

namespace app\controllers;

use Yii;
use app\models\LoginForm;
use app\models\User;
use yii\data\ActiveDataProvider;

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
	    			Yii::$app->session->addFlash('info','Данные пользователя обновлены');
	    		else
	    			Yii::$app->session->addFlash('info','Пользователь удалён');
	    		return $this->redirect(['users']);
	    	}
	    		
	    }

    	return $this->render('index',['m'=>$u]);

    }

}