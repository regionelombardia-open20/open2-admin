<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos-admin
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
use open20\amos\admin\models\UserProfile;
use yii\base\BootstrapInterface;
use yii\base\Event;
use Yii;

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\bootstrap
 * @category   CategoryName
 */


/**
 * Class UserLastOnlineBootstrap
 * @package open20\amos\admin\bootstrap
 */
class UserLastOnlineBootstrap implements BootstrapInterface
{
    /**
     * AmosAdmin module
     * @var AmosAdmin $moduleAdmin
     */
    private $moduleAdmin;

    /**
     * UserLastOnlineBootstrap constructor.
     */
    public function __construct()
    {
        $this->moduleAdmin = AmosAdmin::instance();
    }

    /**
     * @param $app
     * @return void
     */
    public function bootstrap($app)
    {
        if (Yii::$app instanceof \yii\web\Application && $this->moduleAdmin->enableOnlineUsers && !Yii::$app->user->isGuest) {
            Event::on(\yii\web\Controller::className(), \yii\base\Controller::EVENT_AFTER_ACTION, [$this, 'saveLastOnline']);
        }
    }

    /**
     * Save last online datetime
     * @param $event
     * @return true|void
     */
    public function saveLastOnline($event)
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            return true;
        }

        $userProfile = UserProfile::findOne(['user_id' => Yii::$app->user->id]);
        if (!empty($userProfile)) {
            $userProfile->last_online = date('Y-m-d H:i:s');
            $userProfile->save(false);
        }
    }
}