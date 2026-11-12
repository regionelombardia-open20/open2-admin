<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos\admin\rules
 * @author    Aria S.p.A. <protocollo@pec.ariaspa.it>
 * @license   GNU General Public License v3.0 <https://www.gnu.org/licenses/gpl-3.0.html>
 * @link      https://www.ariaspa.it/
 *
 * This file is part of OPEN 2.0.
 *
 * OPEN 2.0 is free software: you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * OPEN 2.0 is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with OPEN 2.0. If not, see <https://www.gnu.org/licenses/>.
 */

namespace open20\amos\admin\rules;

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\models\UserProfile;
use open20\amos\core\rules\BasicContentRule;

/**
 * Class UpdateProfileFacilitatorRule
 * @package open20\amos\admin\rules
 */
class ValidateUserProfileWorkflowRule extends BasicContentRule
{
    public $name = 'ValidateUserProfileWorkflow';
    
    /**
     * Facilitator cannot validate his own profile
     * @inheritdoc
     */
    public function ruleLogic($user, $item, $params, $model)
    {
        $module = \Yii::$app->getModule(AmosAdmin::getModuleName());
        $currentProfile = UserProfile::find()->andWhere(['user_id' => $user])->one();
        // Return false if the model is null
        if (is_null($model)) {
            return false;
        }

        /** @var UserProfile $model */
        if(\Yii::$app->user->can('FACILITATOR')){
            if($model->user_id == $user) {
                return false;
            }
            // facilitator can validate only his own users
            if($module && $module->facilitatorCanValidateOnlyOwnUser && $currentProfile){
                if($model->facilitatore_id != $currentProfile->id){
                    return false;
                }
            }
            return true;
        }
        return false;
    }
}
