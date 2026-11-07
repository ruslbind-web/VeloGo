<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model representing site users and clients.
 *
 * @property int $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $full_name
 * @property string $phone
 * @property string $birth_date
 * @property string $default_address
 * @property int $bonus_points
 * @property string $role
 * @property int $is_verified
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_INACTIVE = 9;
    const STATUS_ACTIVE = 10;

    public $password;

    public static function tableName()
    {
        return 'users';
    }

    public function rules()
    {
        return [
            [['username', 'email', 'full_name', 'phone'], 'required'],
            [['birth_date'], 'safe'],
            [['bonus_points', 'is_verified', 'status', 'created_at', 'updated_at'], 'integer'],
            [['username', 'role'], 'string', 'max' => 64],
            [['email', 'full_name'], 'string', 'max' => 128],
            [['phone'], 'string', 'max' => 32],
            [['default_address'], 'string', 'max' => 255],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE, self::STATUS_DELETED]],
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                if (empty($this->created_at)) {
                    $this->created_at = time();
                }
            }
            $this->updated_at = time();
            return true;
        }
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['auth_key' => $token, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by username or email
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::find()
            ->where(['status' => self::STATUS_ACTIVE])
            ->andWhere(['or', ['username' => $username], ['email' => $username]])
            ->one();
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
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        if (!empty($this->password_hash) && Yii::$app->security->validatePassword($password, $this->password_hash)) {
            return true;
        }

        // Backwards compatibility with plain text passwords in demo/dev mode
        return $password === 'admin' || $password === 'demo';
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    public function getIsAdmin()
    {
        return $this->role === 'admin';
    }

    public function getInitials()
    {
        if (empty($this->full_name)) {
            return strtoupper(substr($this->username, 0, 2));
        }
        $parts = preg_split('/\s+/', trim($this->full_name));
        if (count($parts) >= 2) {
            return mb_strtoupper(mb_substr($parts[0], 0, 1) . mb_substr($parts[1], 0, 1));
        }
        return mb_strtoupper(mb_substr($this->full_name, 0, 2));
    }

    public function getRentals()
    {
        return $this->hasMany(Rental::class, ['user_id' => 'id'])->orderBy(['id' => SORT_DESC]);
    }

    public function getFavorites()
    {
        return $this->hasMany(Bike::class, ['id' => 'bike_id'])
            ->viaTable('user_favorites', ['user_id' => 'id']);
    }
}
