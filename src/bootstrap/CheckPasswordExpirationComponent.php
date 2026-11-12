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

use yii\base\Application;
use yii\base\BootstrapInterface;

/**
 * Description of CheckPasswordExpirationComponent
 *
 *
 */
class CheckPasswordExpirationComponent implements BootstrapInterface{
    
    /**
     * 
     * @param type $app
     */
    public function bootstrap($app)
    {
        \Yii::$app->on(Application::EVENT_BEFORE_ACTION, [$this, 'onApplicationAction']);
    }

    /**
     * 
     * @param type $event
     */
    public function onApplicationAction($event) {
        $actionId = $event->action->uniqueId;


        $User = \open20\amos\core\utilities\CurrentUser::getUser();
        if (!$User->isGuest) {
            $UserId = $User->getIdentity()->getId();
            $ruoli = \Yii::$app->authManager->getRolesByUser($UserId);
            $verifica = (!(isset($ruoli['ADMIN'])) && (isset(\Yii::$app->params['active-expiration-password']) && \Yii::$app->params['active-expiration-password'] == true)) ? true : false;

            if($verifica){
                $dataScadenza = date('Y-m-d', strtotime((isset(\Yii::$app->params['days-expiration-password'])) ? '+' . \Yii::$app->params['days-expiration-password'] . ' days' : '+90 days', strtotime(date('Y-m-d', strtotime($User->getIdentity()->updated_at)))));
                $dataOdierna = date('Y-m-d');
                $profileId = \Yii::$app->user->identity->profile->id;
                if ($dataScadenza <= $dataOdierna && $actionId != 'admin/user-profile/password-expired' && $actionId != 'site/accettazione-privacy' && $actionId != 'admin/user-profile/cambia-password' && $actionId != 'site/inserisci-dati-autenticazione') {
                    \Yii::$app->controller->redirect('admin/user-profile/password-expired?id=' . $profileId);
                }
            }
        }
    }
}
