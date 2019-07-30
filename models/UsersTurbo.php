<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property string $name
 * @property int $gender
 * @property string $email
 *
 * @property array $emails
 * @property array $domains
 */
class UsersTurbo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'gender', 'email'], 'required'],
            [['gender'], 'integer'],
            [['name'], 'string', 'max' => 32],
            [['email'], 'string', 'max' => 1024],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'gender' => 'Gender',
            'email' => 'Email',
        ];
    }

    public function getEmails()
    {
        $tmpArr = [];
        $tmpEmails = explode(",", $this->email);
        foreach ($tmpEmails as $email) {
            $tmpArr[] = preg_replace('/\s+/', '', $email);
        }

        return $tmpArr;
    }

    public function getDomains()
    {
        $domains = [];
        $tmpDomains = [];
        foreach ($this->emails as $email) {
            $tmp = explode(".", $email);
            $domain = isset($tmp[1]) ? $tmp[1] : null;

            if (isset($domain) && ($domain != null)) {
                if (!in_array($domain, $tmpDomains)) {
                    $tmpDomains[] = $domain;
                    $domains[$domain] = 1;
                } else {
                    $domains[$domain] = ++$domains[$domain];
                }
            }
        }

        return $domains;
    }
}