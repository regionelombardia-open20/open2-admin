<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos\admin\bootstrap
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

namespace open20\amos\admin\bootstrap;

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\components\RedirectBeforeActionComponent;
use open20\amos\admin\utility\UserProfileUtility;
use Yii;
use yii\base\BootstrapInterface;
use yii\base\Event;
use yii\rest\Controller;

/**
 * Class RedirectBeforeAction
 * @package open20\amos\admin\bootstrap
 */
class RedirectBeforeAction implements BootstrapInterface
{
    /**
     * @param $app
     */
    public function bootstrap($app)
    {
        Event::on(\yii\base\Controller::className(), \yii\base\Controller::EVENT_BEFORE_ACTION, [$this, 'startUpRedirect']);
    }
    
    /**
     * @param $event
     */
    public function startUpRedirect($event)
    {
        if (!(Yii::$app->controller instanceof Controller)) {
            $adminModule = Yii::$app->getModule(AmosAdmin::getModuleName());
            if (!is_null($adminModule)) {
                //redirect for dl semplificazione
                if (UserProfileUtility::mandatoryReconciliationPage()) {
                    if(!\Yii::$app->user->isGuest) {
                        $userProfileWizard = new RedirectBeforeActionComponent();
                        if (Yii::$app->controller->action->id != 'reconciliation'
                            && Yii::$app->controller->action->id != 'connect-spid'
                            && Yii::$app->controller->id != 'security'
                            && Yii::$app->controller->module->id != 'socialauth'
                            && (\Yii::$app->controller->action->id != 'logout')
                            && (\Yii::$app->controller->action->id != 'login')
                            && (\Yii::$app->controller->action->id != 'privacy')
                            && (\Yii::$app->controller->action->id != 'error')
                        ) {
                            $userProfileWizard->redirectToUrl("/" . AmosAdmin::getModuleName() . "/security/reconciliation");
                            Yii::$app->response->send();
                        }
                    }
                }
            }
        }
    }
}
