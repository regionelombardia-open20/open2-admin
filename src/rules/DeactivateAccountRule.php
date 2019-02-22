<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\admin\rules
 * @category   CategoryName
 */

namespace lispa\amos\admin\rules;

use lispa\amos\admin\models\UserProfile;
use lispa\amos\core\rules\BasicContentRule;

/**
 * Class DeactivateAccountRule
 * @package lispa\amos\admin\rules
 */
class DeactivateAccountRule extends BasicContentRule
{
    public $name = 'deactivateAccount';

    /**
     * @inheritdoc
     */
    public function ruleLogic($user, $item, $params, $model)
    {
        if (!\Yii::$app->user->can('ADMIN') && !\Yii::$app->user->can('AMMINISTRATORE_UTENTI')) {
            return true;
        }
        if (is_null($model) || (!($model instanceof UserProfile))) {
            return false;
        }
        return ($user != $model->user_id);
    }
}
