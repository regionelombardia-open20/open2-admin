<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos\admin\commands
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

namespace open20\amos\admin\commands;

use open20\amos\admin\AmosAdmin;
use open20\amos\core\user\User;
use yii\console\Controller;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Console;

class UserUtilityController  extends Controller
{

    public $data = [];

    /**
     * @var string[] $passedOptions
     */
    public $passedOptions = [
        'data'
    ];

    /**
     * @param $actionID
     * @return array|string[]
     */
    public function options($actionID)
    {
        return ArrayHelper::merge(parent::options($actionID), $this->passedOptions);
    }

    /**
     *
     */
    public function actionBasicUserAssign(){
        /** @var AmosAdmin $admin */
        $admin = AmosAdmin::instance();
        $userClass = $admin->model('User');
        $users = $userClass::find()->all();
        /** @var User $user */
        foreach ($users as $user){
            $roles = Yii::$app->authManager->getAssignments((int) $user->id);
            if(empty($roles)) {
                Yii::$app->getAuthManager()->assign(Yii::$app->getAuthManager()->getRole('BASIC_USER'), $user->id);
                $this->log ('Add BASIC_USER to : id'. $user->id . "  username: " . $user->username);
            }
        }
    }

    /**
     * @param $message
     */
    private function log($message){
        echo ($message ."\n");
    }

    /** Modifica i log dell'utente rimosso (procedura gemella di UserDropController)
     * @return void
     * @throws \yii\db\Exception
     */
    public function actionDropLogs() {
        Console::stdout('INIZIO cancellazione logs per l\'utente cancellato' . PHP_EOL);
        \Yii::$app->db->createCommand()
            ->delete('audit_mail', ['to' => $this->data['userEmail']])
            ->execute();

        \Yii::$app->db->createCommand()
            ->update('email_spool', ['to_address' => '#####'], ['to_address' => $this->data['userEmail']])
            ->execute();

        \Yii::$app->db->createCommand()
            ->update('email_spool', ['message' => '#####'], ['like', 'message', $this->data['userEmail']])
            ->execute();

        Yii::$app->db->createCommand()
            ->delete('session', ['like', 'data', $this->data['userEmail']])
            ->execute();
        Console::stdout('FINE cancellazione logs per l\'utente cancellato' . PHP_EOL);
    }
}