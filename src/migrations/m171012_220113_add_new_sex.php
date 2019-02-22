<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\admin\migrations
 * @category   CategoryName
 */

use yii\db\Migration;
use lispa\amos\admin\models\UserProfile;

class m171012_220113_add_new_sex extends Migration
{

    private $table = null;


    public function __construct()
    {
        $this->table = UserProfile::tableName();
        parent::__construct();
    }


    public function safeUp()
    {
        $this->alterColumn($this->table,'sesso', "ENUM('None','Maschio','Femmina','') DEFAULT NULL COMMENT 'Sesso'");
        return true;
    }

    public function safeDown()
    {

        $this->alterColumn($this->table,'sesso', "ENUM('Maschio','Femmina','') DEFAULT NULL COMMENT 'Sesso'");
        return true;
    }


}
