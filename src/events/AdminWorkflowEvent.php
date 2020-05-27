<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\events
 * @category   CategoryName
 */

namespace open20\amos\admin\events;

use open20\amos\admin\models\UserProfile;
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
}
