<?php
namespace lispa\amos\admin\events;


use lispa\amos\admin\AmosAdmin;
use lispa\amos\admin\models\UserContact;
use lispa\amos\admin\models\UserProfile;
use Yii;
use yii\base\Event;
use yii\base\Exception;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

/**
 * Class CorsiRichiesteRettificaWorkflowEvent
 * @package backend\modules\corsi\events\worflow
 */
class AssociateTutorToUserEvent
{

    /**
     *  Associate user TUTOR to the created users
     * @param Event $event
     */
    public function afterCreateUser(Event $event)
    {
        $adminModule = AmosAdmin::instance();
        if($adminModule && !empty($adminModule->associateTutor)) {
            /** @var $userProfile UserProfile */
            $userProfile = $event->data;
            $userContact = new UserContact([
                'user_id' => $userProfile->user_id,
                'contact_id' => $adminModule->associateTutor, // 197 user TUTOR
                'status' => 'ACCEPTED',
                'accepted_at' => $userProfile->created_at,
            ]);

            $userContact->save();
        }
        return true;
    }





}