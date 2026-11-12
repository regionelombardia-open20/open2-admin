<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos\admin\views\user-profile\boxes
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

use open20\amos\admin\AmosAdmin;
use open20\amos\core\icons\AmosIcons;
use open20\amos\notificationmanager\widgets\NotifyFrequencyWidget;

/**
 * @var yii\web\View $this
 * @var open20\amos\core\forms\ActiveForm $form
 * @var open20\amos\admin\models\UserProfile $model
 * @var open20\amos\core\user\User $user
 */

/** @var AmosAdmin $adminModule */
$adminModule = Yii::$app->controller->module;

\open20\amos\core\utilities\ModalUtility::createAlertModal([
    'id' => 'notify-uncheck',
    'modalDescriptionText' => AmosAdmin::t('amosadmin', '#notify_flag_uncheck_msg'),
]);

?>

<section class="email-freq-admin-section col-xs-12 m-t-15">
    <h3>
<!--        < ?= AmosIcons::show('email') ?>-->
        <?= AmosAdmin::tHtml('amosadmin', '#email_frequency_settings') ?>
    </h3>
    <p><?= AmosAdmin::t('amosadmin', 'If the frequency is not indicated, you will receive the notifications as automatically set by the system') . '.' ?></p>
    <div class="col-xs-12 nop m-t-15">
        <label><strong><?= AmosAdmin::tHtml('amosadmin', 'Email frequency')?></strong></label>
        <?= NotifyFrequencyWidget::widget([
            'model' => $model
        ]) ?>
    </div>
    <div class="col-xs-12 nop m-t-15">
        <?= \open20\amos\core\helpers\Html::activeCheckbox($model, 'notify_from_editorial_staff', [
            'name' => 'notify_from_editorial_staff',
            'id' => 'notify_from_editorial_staff-1',
            'onchange' => "if(!$(this).is(':checked')){ $('#notify-uncheck').modal('show'); }"
        ]) ?>
    <div>
</section>
