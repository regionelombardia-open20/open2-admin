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

use open20\amos\admin\AmosAdmin;
use open20\amos\core\icons\AmosIcons;

/**
 * @var \open20\amos\admin\models\UserProfile $model
 * @var \open20\amos\socialauth\models\SocialAuthUsers[] $socialAuthUsers
 * @var array $enableServices
 */

$socialAuthUser = null;
if (count($socialAuthUsers) && array_key_exists('google', $socialAuthUsers)) {
    $socialAuthUser = $socialAuthUsers['google'];
}
$serviceCalendarActive = in_array('calendar', $enableServices);
$serviceContactActive = in_array('contacts', $enableServices);

if ($serviceCalendarActive || $serviceContactActive) {

    /** @var \open20\amos\socialauth\models\SocialAuthUsers $socialAuthUser */
    if ($socialAuthUser) {
        $isEnabledCalendar = $serviceCalendarActive && $socialAuthUser->getServices()->andWhere(['service' => 'calendar'])->count();
        $isEnabledContacts = $serviceContactActive && $socialAuthUser->getServices()->andWhere(['service' => 'contacts'])->count();
    } else {
        $isEnabledCalendar = false;
        $isEnabledContacts = false;
    }
    $js = <<<JS

function isEnabledCalendar(){
    isEnabledService($model->id, 'google', 'calendar');       
}
function isEnabledContacts(){
    isEnabledService($model->id, 'google', 'contacts');       
}
isEnabledCalendar();
isEnabledContacts();
      
JS;

    $this->registerJs($js);
    ?>

    <div id='google-services' class="<?= $socialAuthUser ? "google-services" : "hidden" ?>">
        <div class="col-xs-12 nop">
            <p><?= AmosAdmin::t('amosadmin', '#google_calendar_description') ?></p>
            <p class="label-social"><strong><?= AmosAdmin::t('amosadmin', '#google_calendar_label'); ?></strong></p>
            <div class="wrap-btn-social">
                <?php
                if ($serviceCalendarActive){
                ?>
                <span id="manage-calendar">
                <?= \open20\amos\core\helpers\Html::a(
                    AmosIcons::show('calendar', [], 'dash'),
                    '/'.AmosAdmin::getModuleName().'/user-profile/enable-google-service?id=' . $model->id . '&serviceName=calendar',
                    [
                        'id' => 'enable-calendar-btn',
                        'class' => 'btn btn-google-services' . ($isEnabledCalendar ? ' hidden' : ''),
                        'title' => AmosAdmin::t('amosadmin', 'Enable') . ' ' . AmosAdmin::t('amosadmin', '#calendar'),
                        'onclick' => "window.open(this.href, 'enableCalendar', 'left=20,top=20,width=500,height=500,toolbar=1,resizable=0'); return false;"
                    ]) ?>
                <?= \open20\amos\core\helpers\Html::a(
                    AmosIcons::show('calendar', [], 'dash') . '&nbsp;' . AmosAdmin::t('amosadmin', 'Disconnect'),
                    '/'.AmosAdmin::getModuleName().'/user-profile/disable-google-service?id=' . $model->id . '&serviceName=calendar',
                    [
                        'id' => 'disable-calendar-btn',
                        'class' => 'btn btn-google-services' . ($isEnabledCalendar ? ' btn-google-services-disconnect' : ' hidden'),
                        'title' => AmosAdmin::t('amosadmin', 'Disconnect') . ' ' . AmosAdmin::t('amosadmin', '#calendar'),
                    ]) ?>
            </span>
            </div>
        </div>
        <div class="col-xs-12 nop">
            <p><?= AmosAdmin::t('amosadmin', '#google_contact_description') ?></p>
            <p class="label-social"><strong><?= AmosAdmin::t('amosadmin', '#google_contact_label'); ?></strong></p>
            <div class="wrap-btn-social">
                <?php
                }
                if ($serviceContactActive) {
                    ?>
                    <span id="manage-contacts">
            <?= \open20\amos\core\helpers\Html::a(
                AmosIcons::show('account'),
                '/'.AmosAdmin::getModuleName().'/user-profile/enable-google-service?id=' . $model->id . '&serviceName=contacts',
                [
                    'id' => 'enable-contacts-btn',
                    'class' => 'btn btn-google-services' . ($isEnabledContacts ? ' hidden' : ''),
                    'title' => AmosAdmin::t('amosadmin', 'Enable') . ' ' . AmosAdmin::t('amosadmin', '#contacts'),
                    'onclick' => "window.open(this.href, 'enableContacts', 'left=20,top=20,width=500,height=500,toolbar=1,resizable=0'); return false;"
                ]) ?>
            <?= \open20\amos\core\helpers\Html::a(
                AmosIcons::show('account') . '&nbsp;' . AmosAdmin::t('amosadmin', 'Disconnect'),
                '/'.AmosAdmin::getModuleName().'/user-profile/disable-google-service?id=' . $model->id . '&serviceName=contacts',
                [
                    'id' => 'disable-contacts-btn',
                    'class' => 'btn btn-google-services' . ($isEnabledContacts ? ' btn-google-services-disconnect' : ' hidden'),
                    'title' => AmosAdmin::t('amosadmin', 'Disconnect') . ' ' . AmosAdmin::t('amosadmin', '#contacts')
                ]) ?>
        </span>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
<?php } ?>
