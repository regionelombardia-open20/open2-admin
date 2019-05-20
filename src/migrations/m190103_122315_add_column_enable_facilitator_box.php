<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\admin\migrations
 * @category   CategoryName
 */

use lispa\amos\admin\models\UserProfileArea;
use yii\db\Migration;

/**
 * Class m190103_122315_add_column_enable_facilitator_box
 */
class m190103_122315_add_column_enable_facilitator_box extends Migration
{



    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('user_profile','enable_facilitator_box', $this->integer(1)->defaultValue(0)->after('dont_show_facilitator')->comment('Display facilitator box in user profile for admin'));

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('user_profile','enable_facilitator_box');
        return true;
    }
}
