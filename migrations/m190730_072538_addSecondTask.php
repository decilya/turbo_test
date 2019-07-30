<?php

use yii\db\Migration;

/**
 * Class m190730_072538_addSecondTask
 */
class m190730_072538_addSecondTask extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("CREATE TABLE `users` (
                                `id` int(11) NOT NULL AUTO_INCREMENT,
                                `name` varchar(32) NOT NULL,
                                `gender` tinyint(2) NOT NULL,
                                `email` varchar(1024) NOT NULL,
                            PRIMARY KEY (`id`)
                            ) ENGINE=InnoDB;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
       $this->dropTable('users');
    }


}
