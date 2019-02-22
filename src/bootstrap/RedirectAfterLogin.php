<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\admin\bootstrap
 * @category   CategoryName
 */

namespace lispa\amos\admin\bootstrap;

use lispa\amos\admin\components\FirstAccessWizardComponent;
use lispa\amos\admin\components\ReDirectAfterLoginComponent;
use lispa\amos\admin\models\UserProfile;
use yii\base\BootstrapInterface;
use yii\base\Controller;
use yii\base\Event;
use yii\base\ViewEvent;
use yii\base\View;
use yii\base\WidgetEvent;
use yii\helpers\Url;
use yii\web\User;
use yii\widgets\Breadcrumbs;


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
        $adminModule = \Yii::$app->getModule('admin');
        if (!is_null($adminModule)) {
            $actionId = \Yii::$app->controller->action->id;
            // is set the redirect url you skip the  profile wizard,  and go to the url, at the secondo login you kskip the wizard and go in dashboard
            $userProfile = UserProfile::find()->andWhere(['user_id' => \Yii::$app->user->id])->one();
            if (!empty($userProfile) && $actionId != 'send-event-mail') {
                if (!empty($userProfile->first_access_redirect_url)) {
                    $component = new  ReDirectAfterLoginComponent();
                    $component->redirectToUrl($userProfile->first_access_redirect_url);
                    \Yii::$app->response->send();
                }
            }
        }
    }
}
