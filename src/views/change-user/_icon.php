<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos\admin\views\change-user
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
use open20\amos\admin\base\ConfigurationManager;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\user\User;

/**
 * @var yii\web\View $this
 * @var \open20\amos\admin\models\UserProfile $model
 */

/** @var AmosAdmin $adminModule */
$adminModule = AmosAdmin::instance();

/** @var User $user */
$user = $model->user;
$nomeCognome = '';
if ($adminModule->confManager->isVisibleBox('box_informazioni_base', ConfigurationManager::VIEW_TYPE_VIEW)) {
    if ($adminModule->confManager->isVisibleField('nome', ConfigurationManager::VIEW_TYPE_VIEW)) {
        $nomeCognome .= $model->nome;
    }
    if ($adminModule->confManager->isVisibleField('cognome', ConfigurationManager::VIEW_TYPE_VIEW)) {
        $nomeCognome .= ' ' . $model->cognome;
    }
}

$viewUrl = "/" . AmosAdmin::getModuleName() . "/user-profile/view?id=" . $model->id;

$prevalentPartnership = $model->prevalentPartnership;
$prevalentPartnershipTruncated = '';
$prevalentPartnershipName = '';
if (!is_null($prevalentPartnership)) {
    $prevalentPartnershipTruncated = $prevalentPartnership;
    $prevalentPartnershipName = $prevalentPartnership->name;
}

?>

<div class="change-user-card-container">
    <div class="change-user-card-img">
        <?php if (($adminModule->confManager->isVisibleBox('box_foto', ConfigurationManager::VIEW_TYPE_VIEW)) &&
            ($adminModule->confManager->isVisibleField('userProfileImage', ConfigurationManager::VIEW_TYPE_VIEW))
        ): ?>
            <div>
                <div class="container-round-img">
                    <?php
                    $url = $model->getAvatarUrl('card_users');
                    Yii::$app->imageUtility->methodGetImageUrl = 'getAvatarUrl';
                    $logoOptions = [
                        'class' => Yii::$app->imageUtility->getRoundImage($model)['class'],
                        'alt' => $model->getNomeCognome(),
                    ];
                    $options = [];
                    if (strlen($nomeCognome) > 0) {
                        $logoOptions['alt'] = $nomeCognome;
                        $options['title'] = $nomeCognome;
                    }
                    $logo = Html::img($url, $logoOptions);
                    ?>
                    <?= Html::a($logo, $viewUrl, $options); ?>
                </div>
            </div>
        <?php endif; ?>
        <?php if (!is_null($prevalentPartnership)): ?>
            <div class="change-user-organization">
                <?= (!empty($prevalentPartnershipTruncated)) ? $prevalentPartnershipTruncated : $prevalentPartnershipName; ?>
            </div>
        <?php endif; ?>
    </div>
    <div class="change-user-card-body">
        <div class="change-user-card-info">
            <strong><?= $model->getAttributeLabel('nome'); ?>:</strong> <span><?= $nomeCognome; ?></span>
        </div>
        <div class="change-user-card-info">
            <strong><?= $user->getAttributeLabel('email'); ?>:</strong> <span><?= $user->email; ?></span>
        </div>
        <?php if ((Yii::$app->user->id == $model->user_id)): ?>
            <div class="change-user-cta">
                <?= AmosIcons::show('check-circle') . AmosAdmin::t('amosadmin', '#change_user_currently_logged'); ?>
            </div>
        <?php elseif (Yii::$app->user->can('CHANGE_USER_PROFILE')): ?>
            <?php $enterTitle = AmosAdmin::t('amosadmin', '#change_user_icon_btn_label'); ?>
            <?= Html::a(
                $enterTitle,
                ['/' . AmosAdmin::getModuleName() . '/change-user/login-with-my-user', 'user_id' => $model->user_id],
                ['title' => $enterTitle, 'class' => 'btn btn-primary change-user-cta']
            ); ?>
        <?php endif; ?>
    </div>
</div>
