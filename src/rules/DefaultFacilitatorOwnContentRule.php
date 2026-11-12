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

use open20\amos\admin\models\UserProfile;
use open20\amos\core\record\Record;
use yii\rbac\Rule;

/**
 * Class DefaultFacilitatorOwnContentRule
 * @package open20\amos\admin\rules
 */
class DefaultFacilitatorOwnContentRule extends Rule
{
    public $name = 'defaultFacilitatorOwnContent';

    /**
     * @inheritdoc
     */
    public function execute($loggedUserId, $item, $params)
    {
        // If the key "model" non exist return false
        if (!isset($params['model'])) {
            return false;
        }

        /** @var Record $model */
        $model = $params['model'];
        if (!$model->id) {
            $post = \Yii::$app->getRequest()->post();
            $get = \Yii::$app->getRequest()->get();
            if (isset($get['id'])) {
                $model = $this->instanceModel($model, $get['id']);
            } elseif (isset($post['id'])) {
                $model = $this->instanceModel($model, $post['id']);
            }
        }

        // Search content creator
        $contentOwnerObj = UserProfile::findOne(['user_id' => $model->created_by]);
        if (is_null($contentOwnerObj)) {
            return false;
        }

        // Search content creator facilitator
        $contentOwnerFacilitatorObj = UserProfile::find()->andWhere(['OR',
            ['id' => $contentOwnerObj->facilitatore_id],
        ])->one();
        $contentOwnerExternalFacilitatorObj = UserProfile::find()->andWhere(['OR',
            ['id' => $contentOwnerObj->external_facilitator_id],
        ])->one();
        if (is_null($contentOwnerFacilitatorObj) && is_null($contentOwnerExternalFacilitatorObj)) {
            return false;
        }

        // Check if content owner facilitator is the same of the logged user
        return ($contentOwnerFacilitatorObj->user_id == $loggedUserId || $contentOwnerExternalFacilitatorObj->user_id == $loggedUserId);
    }

    /**
     * @param Record $model
     * @param int $modelId
     * @return mixed
     */
    protected function instanceModel($model, $modelId)
    {
        $modelClass = $model->className();
        /** @var Record $modelClass */
        $instancedModel = $modelClass::findOne($modelId);
        if (!is_null($instancedModel)) {
            $model = $instancedModel;
        }
        return $model;
    }
}
