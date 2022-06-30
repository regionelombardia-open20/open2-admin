<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\bootstrap
 * @category   CategoryName
 */

namespace open20\amos\admin\bootstrap;

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\components\ReDirectAfterLoginComponent;
use open20\amos\admin\models\UserProfile;
use Yii;
use yii\base\BootstrapInterface;
use yii\base\Event;
use yii\rest\Controller;
use yii\web\User;

class RedirectAfterLogin implements BootstrapInterface
{

    /**
     * @param $app
     */
    public function bootstrap($app)
    {
        Event::on(User::className(), User::EVENT_AFTER_LOGIN, [$this, 'startUpRedirect']);
    }

    public function startUpRedirect($event)
    {
        if (!(Yii::$app->controller instanceof Controller)) {
            $adminModule = Yii::$app->getModule(AmosAdmin::getModuleName());
            if (!is_null($adminModule)) {
                $actionId    = Yii::$app->controller->action->id;
                // is set the redirect url you skip the  profile wizard,  and go to the url, at the secondo login you kskip the wizard and go in dashboard
                $userProfile = UserProfile::find()->andWhere(['user_id' => Yii::$app->user->id])->one();
                if (!empty($userProfile) && $actionId != 'send-event-mail') {
                    if (!empty($userProfile->first_access_redirect_url)) {
                        $component = new ReDirectAfterLoginComponent();
                        $component->redirectToUrl($userProfile->first_access_redirect_url);
                        Yii::$app->response->send();
                    }
                }
            }
        }
    }
}