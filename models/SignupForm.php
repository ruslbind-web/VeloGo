<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * SignupForm represents the user registration form.
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $phone;
    public $full_name;
    public $password;
    public $password_repeat;

    public function rules()
    {
        return [
            [['username', 'email', 'phone', 'full_name', 'password'], 'required', 'message' => 'Заполните это поле.'],
            ['username', 'trim'],
            ['username', 'string', 'min' => 3, 'max' => 64],
            ['username', 'unique', 'targetClass' => '\app\models\User', 'message' => 'Такой логин уже занят.'],
            ['email', 'trim'],
            ['email', 'email', 'message' => 'Введите корректный email.'],
            ['email', 'unique', 'targetClass' => '\app\models\User', 'message' => 'Пользователь с таким email уже зарегистрирован.'],
            ['phone', 'trim'],
            ['phone', 'string', 'max' => 32],
            ['full_name', 'trim'],
            ['full_name', 'string', 'min' => 2, 'max' => 128],
            ['password', 'string', 'min' => 4, 'message' => 'Пароль должен содержать минимум 4 символа.'],
            ['password_repeat', 'compare', 'compareAttribute' => 'password', 'message' => 'Пароли не совпадают.'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => 'Логин',
            'email' => 'Электронная почта',
            'phone' => 'Номер телефона',
            'full_name' => 'Имя и фамилия',
            'password' => 'Пароль',
            'password_repeat' => 'Повторите пароль',
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }

        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->phone = $this->phone;
        $user->full_name = $this->full_name;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->role = 'user';
        $user->status = User::STATUS_ACTIVE;
        $user->bonus_points = 100;
        $user->is_verified = 1;

        if ($user->save()) {
            if (Yii::$app->has('user')) {
                Yii::$app->user->login($user, 3600 * 24 * 30);
            }
            return $user;
        }

        return null;
    }
}
