<?php

namespace app\models;

use Yii;
use \yii\base\Security;

/**
 * класс пользователя .. 
 */
class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    /**
     * @constant(SELF_EDIT)   сценарий редактирования своей учёти .. 
     */
    const SELF_EDIT='selfedit';
    const OTHER_EDIT='oheredit';
    const NEW_EDIT='newedit';


    public static function tableName()
    {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
        //return isset(self::$users[$id]) ? new static(self::$users[$id]) : null;
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username'=>$username]);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password,$this->password);
    }

    /**
     * перед сохранением ... 
     */
    public function beforeSave($ins)
    {
        if (!parent::beforeSave($ins))
            return false;
        if ($this->isNewRecord)
            $this->auth_key=Yii::$app->security->generateRandomString();
        return true;
    }

    public function rules()
    {
        return [
            ['id','integer','min'=>1,'on'=>[static::SELF_EDIT,static::OTHER_EDIT]],
            ['id','integer','min'=>0,'max'=>0,'on'=>static::NEW_EDIT],
            ['username','required'],
            ['username','uniqueusername'],
            ['password','required','on'=>static::NEW_EDIT],
            ['password','safe','on'=>[static::SELF_EDIT,static::OTHER_EDIT]],
            ['status','boolean'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username'=>'Имя пользователя',
            'password'=>'Пароль',
            'status'=>'Активен',
        ];
    }

    public function attributeHints()
    {
        $h=[];
        if (in_array($this->scenario,[static::SELF_EDIT,static::OTHER_EDIT]))
            return ['password'=>'Оставить пустым если пароль не надо изменить'];
    }

    /**
     * Валиация уникального имени юзера ..
     * @param string $attr имя валидируемого параметра
     * @param array $param параметры валидации
     */
    public function uniqueusername($attr,$param)
    {
        Yii::info([$attr,$this->id],'valid');
        if (static::find()->where(['and',['=',$attr,$this->$attr],['!=','id',intval($this->id)]])->exists())
            $this->addError($attr,'Имя пользователя уже занято.');
    }

    /**
     * сохранение давнных из формы ..
     * @param array $post POST данные 
     * @param boolean $issave true в случае сохранения .. в противном случае = удаление ..
     */
    public function saveData($post,$issave=true)
    {
        if (!$this->load($post))
            return false;
        
        if ($issave && !$this->validate() || !$issave && !$this->validate('id'))
            return false;
        if ($issave){
            $this->password=$this->password?Yii::$app->security->generatePasswordHash($this->password):$this->oldAttributes['password'];
            $this->save();    
        }
        else
            $this->delete();
        
        return true;
    }

    /**
     * Вернуть список пользователей для отображения 
     * @return \yii\db\ActiveQuery Запрос выборки юзера 
     */
    public static function userList()
    {
        return static::find()->where(['!=','id',1]);
    }
}
