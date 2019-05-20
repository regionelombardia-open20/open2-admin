<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\admin\migrations
 * @category   CategoryName
 */

use lispa\amos\core\migration\AmosMigrationWidgets;
use lispa\amos\dashboard\models\AmosWidgets;

/**
 * Class m190314_115600_add_new_admin_widgets_users
 */
class m190314_115600_add_new_admin_widgets_users extends AmosMigrationWidgets
{
    const MODULE_NAME = 'admin';
    
    /**
     * @inheritdoc
     */
    protected function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' => \lispa\amos\admin\widgets\graphics\WidgetGraphicsUsers::className(),
                'type' => AmosWidgets::TYPE_GRAPHIC,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_DISABLED,
                'default_order' => 1,
                'dashboard_visible' => 1
            ]
        ];
    }
}
