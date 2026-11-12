<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos\admin\events
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

namespace open20\amos\admin\events;

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\models\UserProfile;
use open20\amos\admin\models\UserProfileValidationNotify;
use open20\amos\admin\utility\UserProfileMailUtility;
use Yii;
use yii\base\Event;

/**
 * Class AdminWorkflowEvent
 * @package open20\amos\admin\events
 */
class AdminWorkflowEvent implements AdminWorkflowEventInterface
{
    private $roles = [
        'CREATORE_NEWS',
        'CREATORE_DISCUSSIONI'
    ];

    /**
     * @inheritdoc
     */
    public function assignCreatorRoles(Event $event)
    {
        /** @var UserProfile $userProfile */
        $userProfile = $event->data;
        $userProfile->validato_almeno_una_volta = 1;
        $userProfile->update(false);
        $userId = $userProfile->user_id;
        $inUpdateUserRoles = Yii::$app->authManager->getRolesByUser($userId);

        foreach ($this->roles as $roleStr) {
            if (!isset($inUpdateUserRoles[$roleStr])) {
                $auth = Yii::$app->authManager;
                $roleObj = $auth->getRole($roleStr);
                $auth->assign($roleObj, $userId);
            }
        }
    }
    
    /**
     * @param Event $event
     */
    public function afterEnterStatusValidated(Event $event)
    {
        $userProfile = $event->data;
        /** @var UserProfileValidationNotify $newModel */
        $newModel = AmosAdmin::instance()->createModel('UserProfileValidationNotify');
        $newModel->createNotify($userProfile->user_id, UserProfileValidationNotify::STATUS_ACTIVE);
    }
    
    /**
     * @param Event $event
     */
    public function afterEnterStatusNotValidated(Event $event)
    {
        $userProfile = $event->data;
        /** @var UserProfileValidationNotify $newModel */
        $newModel = AmosAdmin::instance()->createModel('UserProfileValidationNotify');
        $newModel->createNotify($userProfile->user_id, UserProfileValidationNotify::STATUS_DISABLED);
        UserProfileMailUtility::sendEmailValidationRejected($userProfile);
    }
    
    /**
     * @param Event $event
     */
    public function afterEnterStatusToValidate(Event $event){
        $userProfile = $event->data;
        $nomeCognome = '';
        $facilitatore = $userProfile->facilitatore;
        if($facilitatore){
            $nomeCognome = $facilitatore->nomeCognome;
        }
        \Yii::$app->session->addFlash('success',AmosAdmin::t('amosadmin', "La tua richiesta è stata inviata al Facilitatore {nomeCognome}.<br> Riceverai un riscontro sulla validazione del tuo profilo.",[
            'nomeCognome' => $nomeCognome
        ]));
    }
}
