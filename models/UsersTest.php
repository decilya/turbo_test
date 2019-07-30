<?php

namespace app\models;

use app\models\traits\SetCreatedAtTrait;
use Yii;

/**
 * This is the model class for table "users_test".
 *
 * @property int $id
 * @property string $username
 * @property string $name
 * @property string $surname
 * @property string $patronymic
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $phone
 * @property int $status_id
 * @property int $type
 * @property string $description
 * @property int $created_at
 * @property int $updated_at
 * @property int $last_updated_user_id
 *
 * @property Likes[] $likes
 */
class UsersTest extends \yii\db\ActiveRecord
{
    // Трейты для работы с полем created_at
    use SetCreatedAtTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users_test';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'name', 'surname', 'auth_key', 'password_hash', 'email', 'type'], 'required'],
            [['status_id', 'type', 'created_at', 'updated_at', 'last_updated_user_id'], 'integer'],
            [['username', 'description'], 'string', 'max' => 250],
            [['name', 'surname', 'patronymic'], 'string', 'max' => 125],
            [['auth_key'], 'string', 'max' => 32],
            [['password_hash', 'password_reset_token', 'email'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 20],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['password_reset_token'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'name' => 'Name',
            'surname' => 'Surname',
            'patronymic' => 'Patronymic',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'email' => 'Email',
            'phone' => 'Phone',
            'status_id' => 'Status ID',
            'type' => 'Type',
            'description' => 'Description',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'last_updated_user_id' => 'Last Updated User ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLikes()
    {
        return $this->hasMany(Likes::className(), ['user_id' => 'id']);
    }
}