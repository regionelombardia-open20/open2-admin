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
use open20\amos\admin\models\TokenGroup;
use Yii;
use yii\base\BootstrapInterface;
use yii\base\Event;
use yii\httpclient\Client;
use yii\rest\Controller;
use yii\web\User;

class FrontEndAutoLogin implements BootstrapInterface
{

    /**
     * @param $app
     */
    public function bootstrap($app)
    {
        $adminModule = Yii::$app->getModule(AmosAdmin::getModuleName());
        if (!is_null($adminModule)) {
            if ($adminModule->frontend_auto_login) {
                Event::on(User::className(), User::EVENT_AFTER_LOGIN,
                    [$this, 'goAutoLogin']);
            }
        }
    }

    /**
     *
     * @param type $event
     */
    public function goAutoLogin($event)
    {
        if (!(Yii::$app->controller instanceof Controller)) {
            $adminModule = Yii::$app->getModule(AmosAdmin::getModuleName());
            if (!is_null($adminModule)) {

                $this->callUrl($this->getLinkWithToken(Yii::$app->user->id,
                        $adminModule->frontend_autologin_token_group));
            }
        }
    }

    /**
     *
     * @param type $user_id
     * @param type $event_string
     * @return type
     */
    public function getLinkWithToken($user_id, $event_string)
    {
        $link       = null;
        $tokengroup = TokenGroup::getTokenGroup($event_string);

        if ($tokengroup) {

            $tokenUser = $tokengroup->generateSingleTokenUser($user_id);
            if (!empty($tokenUser)) {
                $link = $tokenUser->getFrontendTokenLink();
            }
        }
        return $link;
    }

    /**
     *
     * @param type $url
     * @return type
     */
    public function callUrl($url)
    {
        $client   = new Client();
        $response = $client->createRequest()
            ->setUrl($url)
            ->send();
        return $response->getContent();
    }
}