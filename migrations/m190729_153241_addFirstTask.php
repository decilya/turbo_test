<?php

use yii\db\Migration;

/**
 * Class m190729_153241_addFirstTask
 */
class m190729_153241_addFirstTask extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        /// ПОЛЬЗОВАТЕЛИ
        /////////////////////////////////
        $this->createTable('users_test', [
            'id' => $this->primaryKey(), // первичный ключ
            'username' => $this->string(250)->notNull()->unique(),
            'name' => $this->string(125)->notNull(),
            'surname' => $this->string(125)->notNull(),
            'patronymic' => $this->string(125)->null(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string()->unique(),
            'email' => $this->string()->notNull()->unique(),
            'phone' => $this->string(20)->null(),
            'status_id' => $this->smallInteger(1)->notNull()->defaultValue(1),
            'type' => $this->smallInteger(1)->notNull(),
            'description' => $this->string(250)->null(),
            'created_at' => $this->integer(11)->notNull(),
            'updated_at' => $this->integer(11)->null(),
            'last_updated_user_id' => $this->integer(11)->null(),
        ], $tableOptions);

        /** Добавим индекс для логина так-как по нему будут частые запосы на поиск  */
        $this->createIndex(
            'idx-user-username',
            'users_test',
            'username'
        );

        /** Добавим индекс для типа пользователкей для ускорения запросов на выборку пользователей опредленного типа */
        $this->createIndex(
            'idx-user-type',
            'users_test',
            'type'
        );

        /** Добавии индекс для ускорения запросов на выборку пользователей с опредленным статусом  */
        $this->createIndex(
            'idx-user-status_id',
            'users_test',
            'status_id'
        );

        /// НОВОСТИ
        /////////////////////////////////
        $this->createTable('news', [
            'id' => $this->primaryKey(), // первичный ключ
            'title' => $this->string(250)->notNull()->unique(), // не может быть постов с одним и тем же заголовком
            'user_id' => $this->integer(11)->notNull(),
            'content' => $this->string(242)->null(), // так-как по условию задачи у нас стоит ограничение в 243 байта,
            'created_at' => $this->integer(11)->notNull(),
            'updated_at' => $this->integer(11)->null(),
            // а string создает тип поля varchar, который занимает 1 байт при
            // пустом поле 2 байта при 1 символе, 5 байтов при 4х символах и т.д.
        ], $tableOptions);

        /** Добавии индекс для ускорения запросов на выборку новостей созданых опред. юзером  */
        $this->createIndex(
            'idx-news-user_id',
            'news',
            'user_id'
        );

        /// ЛАЙКИ
        /////////////////////////////////
        $this->createTable('likes', [
            'id' => $this->primaryKey(), // первичный ключ
            'user_id' => $this->integer(11)->notNull(),
            'news_id' => $this->integer(11)->notNull(),
            'created_at' => $this->integer(11)->notNull(),
        ], $tableOptions);

        /** Добавии индекс для ускорения запросов на выборку лайков по опред. юзеру  */
        $this->createIndex(
            'idx-likes-user_id',
            'likes',
            'user_id'
        );

        /** Добавии индекс для ускорения запросов на выборку лайков по опред. посту  */
        $this->createIndex(
            'idx-likes-news_id',
            'likes',
            'news_id'
        );

        /** А теперь добавим внешние ключи, чтобы при удалении пользователя или новости лайки так же удолялись автоматически */
        $this->addForeignKey(
            'fk-likes-user_id',
            'likes',
            'user_id',
            'users_test',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-likes-news_id',
            'likes',
            'news_id',
            'news',
            'id',
            'CASCADE'
        );

        /*
         * Напишите запросы для выборки и обновления контента
         *
         * см. SiteController actionFirstTask
         *
         *
         *
         */

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-likes-news_id',
            'likes'
        );

        $this->dropForeignKey(
            'fk-likes-user_id',
            'likes'
        );

        $this->dropIndex(
            'idx-likes-news_id',
            'likes'
        );

        $this->dropIndex(
            'idx-likes-user_id',
            'likes'
        );

        $this->dropIndex(
            'idx-news-user_id',
            'news'
        );


        $this->dropIndex(
            'idx-user-type',
            'users_test'
        );

        $this->dropIndex(
            'idx-user-username',
            'users_test'
        );

        $this->dropTable('likes');
        $this->dropTable('news');
        $this->dropTable('users_test');

    }


}
