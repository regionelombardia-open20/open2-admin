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
use Yii;
use yii\rbac\Rule;

/**
 * Class ValidatedBasicUserRule
 * @package open20\amos\admin\rules
 */
class ValidatedBasicUserRule extends Rule
{
    /**
     * @inheritdoc
     */
    public $name = 'validatedBasicUser';

    /**
     * @inheritdoc
     */
    public function execute($loggedUserId, $item, $params)
    {
        /** @var UserProfile $loggedUser */
        $loggedUser = \Yii::$app->getUser()->identity->profile;
        $adminModule = \Yii::$app->getModule(AmosAdmin::getModuleName());
        $communityModule = \Yii::$app->getModule('communty');
        $cwhModule = \Yii::$app->getModule('cwh');
        $scope = (!is_null($cwhModule) ? $cwhModule->getCwhScope() : []);
        
        if (($adminModule->createContentInMyOwnCommunityOnly === true) && (isset($scope['community']) && !(empty($communityModule)))) {
            if (isset($scope['community']) && !(empty($communityModule))) {
                $myOwnCommunities = $communityModule->getCommunitiesByUserId(Yii::$app->getUser()->getId(), true);

                return (in_array($scope['community'], $myOwnCommunities));
            }

            return false;
        }

        return ($loggedUser->validato_almeno_una_volta == true);
    }
}
