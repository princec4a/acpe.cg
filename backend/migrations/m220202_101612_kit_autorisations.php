<?php

use yii\db\Schema;
use yii\db\Migration;

class m220202_101612_kit_autorisations extends Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }

    public function safeUp()
    {
        $tableOptions = 'ENGINE=InnoDB';

        $this->createTable(
            'kit_autorisation',
            [
                'id'=> $this->pk()->comment('ID'),
            ],$tableOptions
        );
    }

    public function safeDown()
    {
        $this->dropTable('kit_autorisation');
    }
}
