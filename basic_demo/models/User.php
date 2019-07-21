<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_users".
 *
 * @property int $id
 * @property string $username
 * @property string $email
 * @property string $password
 */
class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'email', 'password'], 'required'],
            [['email', 'password'], 'string', 'max' => 255],
            [['password'], 'string', 'min' => 6],
           // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'username' => 'UserName',
            'email' => 'Email',
            'password' => 'Password',
        ];
    }

     /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).*$/', $this->password)) {
            $this->addError($attribute, 'Password must be contain upper and number');
        }
        
    }

    public function verifyPassword($password) {
        // echo $password. " skjafffffffffff " . $this->password; 
        return password_verify($password, $this->password);
    }

    public static function findIdentity($id) {
        return self::findOne($id);
    }

    public static function findByEmail($email) {
        return self::findOne(['email'=>$email]);
    }

    public static function findByUsername($username) {
        return self::findOne(['username'=>$username]);
    }

    public function getId() {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        foreach (self::$users as $user) {
            if ($user['accessToken'] === $token) {
                return new static($user);
            }
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }
}
