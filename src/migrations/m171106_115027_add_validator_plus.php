<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\admin\migrations
 * @category   CategoryName
 */

use lispa\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m171106_115027_add_validator_plus
 */
class m171106_115027_add_validator_plus extends AmosMigrationPermissions
{
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'VALIDATOR_PLUS',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Validator PLUS role for all platform users'
            ]
        ];
    }
}
