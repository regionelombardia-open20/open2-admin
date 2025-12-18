<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\views\user-profile
 * @category   CategoryName
 */

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\base\ConfigurationManager;
use open20\amos\admin\widgets\ConnectToUserWidget;
use open20\amos\admin\widgets\MiniStatusIconWidget;
use open20\amos\admin\widgets\SendMessageToUserWidget;
use open20\amos\core\forms\ContextMenuWidget;
use open20\amos\core\helpers\Html;

/**
 * @var yii\web\View $this
 * @var \open20\amos\admin\models\UserProfile $model
 */
$userId        = $model->user_id;
/** @var \open20\amos\admin\controllers\UserProfileController $appController */
$appController = Yii::$app->controller;
if (method_exists($appController, 'setCwhScopeNetworkInfo')) {
    $appController->setCwhScopeNetworkInfo($userId);
}
/** @var AmosAdmin $adminModule */
$adminModule = AmosAdmin::instance();

$nomeCognome = '';
if ($adminModule->confManager->isVisibleBox('box_informazioni_base', ConfigurationManager::VIEW_TYPE_VIEW)) {
    if ($adminModule->confManager->isVisibleField('nome', ConfigurationManager::VIEW_TYPE_VIEW)) {
        $nomeCognome .= $model->nome;
    }
    if ($adminModule->confManager->isVisibleField('cognome', ConfigurationManager::VIEW_TYPE_VIEW)) {
        $nomeCognome .= ' ' . $model->cognome;
    }
}
$arr         = explode(' ', trim($nomeCognome));
$nome        = $arr[0];
$cognome     = $arr[1];
$initials    = strtoupper(substr($nome, 0, 1) . substr($cognome, 0, 1));

$viewUrl = "/" . AmosAdmin::getModuleName() . "/user-profile/view?id=" . $model->id;


$prevalentPartnershipTruncated = '';
$prevalentPartnershipName      = '';
$userProfileRole = '';

if (
$adminModule->confManager->isVisibleBox('box_role_and_area', ConfigurationManager::VIEW_TYPE_VIEW)
) {
    if($model->user_profile_role_id == 1) { // ruolo "altro"
        $userProfileRole = AmosAdmin::t('amosadmin', $model->user_profile_role_other);
    } else {
        $userProfileRole = $model->userProfileRole;
        if(!is_null($userProfileRole)) {
            $userProfileRole = AmosAdmin::t('amosadmin', $userProfileRole->name);
        }
    }
}
if (
($adminModule->confManager->isVisibleBox('box_prevalent_partnership', ConfigurationManager::VIEW_TYPE_VIEW)) &&
($adminModule->confManager->isVisibleField('prevalent_partnership_id', ConfigurationManager::VIEW_TYPE_VIEW))
) {
    if (!is_null($model->prevalentPartnership)) {
        $prevalentPartnershipTruncated = $model->prevalentPartnership;
        $prevalentPartnershipName = $prevalentPartnershipTruncated->name;
    }
}
$wrapperMyProfileClass = (Yii::$app->user->id == $model->user_id) ? ' my-profile-card' : '';

if ($adminModule->confManager->isVisibleBox('box_informazioni_base', ConfigurationManager::VIEW_TYPE_VIEW)) {
    if ($adminModule->confManager->isVisibleField('nome', ConfigurationManager::VIEW_TYPE_VIEW)) {
        $nomeCognome .= $model->nome;
    }
    if ($adminModule->confManager->isVisibleField('cognome', ConfigurationManager::VIEW_TYPE_VIEW)) {
        $nomeCognome .= ' ' . $model->cognome;
    }
}

// Definizione classi pulsanti azione card
$defaultButtonClass = 'profile-action-button';

$prefixPrevalentPartnershipName = '';
if(!empty($userProfileRole)) {
    $prefixPrevalentPartnershipName = '<span>@ </span>';
}
?>

<div class="avatar-wrapper avatar-extra-text test-mev1 mb-0<?= $wrapperMyProfileClass ?>">
    <div class="avatar-box-img">
        <?php
        if (($adminModule->confManager->isVisibleBox('box_foto', ConfigurationManager::VIEW_TYPE_VIEW)) &&
            ($adminModule->confManager->isVisibleField('userProfileImage', ConfigurationManager::VIEW_TYPE_VIEW))
        ) :
            ?>
            <?php
            $url                                       = $model->getAvatarUrl('card_users');
            Yii::$app->imageUtility->methodGetImageUrl = 'getAvatarUrl';
            $logoOptions                               = [
                'class' => Yii::$app->imageUtility->getRoundImage($model)['class'],
                //'style' => "margin-left: " . Yii::$app->imageUtility->getRoundImage($model)['margin-left'] . "%; margin-top: " . Yii::$app->imageUtility->getRoundImage($model)['margin-top'] . "%;",
                'alt' => "",
            ];
            $options                                   = [];
            if (strlen($nomeCognome) > 0) {
                $logoOptions['alt'] = AmosAdmin::t('amosadmin', '#icon_name_title_link') . ' ' . $model->getNomeCognome();
                $options['title']   = AmosAdmin::t('amosadmin', '#icon_name_title_link') . ' ' . $model->getNomeCognome();
                $options['class']   = 'avatar';
            }
            $logo = Html::img($url, $logoOptions);
            ?>
            <?= Html::a($logo, $viewUrl, $options); ?>
        <?php endif; ?>

        <?=
        \open20\amos\notificationmanager\forms\NewsWidget::widget([
            'model' => $model,
            'css_class' => 'badge badge-left'
        ]);
        ?>

        <?= MiniStatusIconWidget::widget([
            'model' => $model,
            'adminModule' => $adminModule
        ]); ?>

        <div class="info-box-avatar">
            <?php
            if ($model->isFacilitator()) {
                $facilitatorIcon = '<span class="mdi mdi-assistant"></span>';
                echo Html::tag(
                    'span',
                    $facilitatorIcon,
                    [
                        'class' => 'icon-info-avatar icon-facilitator ',
                        'data-toggle' => 'tooltip',
                        'data-placement' => 'bottom',
                        'data-html' => 'true',
                        'title' => AmosAdmin::t('amosadmin', 'Facilitator')
                    ]
                );
            }

            if (\open20\amos\admin\models\search\UserProfileSearch::isCommunityManagerOfAtLeastOne($model->user_id)) {
                $communityManagerIcon = '<span class="mdi mdi-account-supervisor"></span>';
                echo Html::tag(
                    'span',
                    $communityManagerIcon,
                    [
                        'class' => 'icon-info-avatar icon-community-manager ',
                        'data-toggle' => 'tooltip',
                        'data-placement' => 'bottom',
                        'data-html' => 'true',
                        'title' => AmosAdmin::t('amosadmin', 'Community Manager')
                    ]
                );
            }
            ?>
        </div>

    </div>

    <div class="ml-2 avatar-body">
        <div class="name-manage">
            <p class="avatar-name font-weight-bold mb-0"><?= Html::a(
                    $model->getNomeCognome(),
                    $viewUrl,
                    ['title' => AmosAdmin::t('amosadmin', '#icon_name_title_link') . ' ' . $model->getNomeCognome(), 'data-gui' => 'icon-view-profiles']
                );
                ?>

            </p>

            <?=
            ContextMenuWidget::widget([
                'model' => $model,
                'actionModify' => '/amosadmin/user-profile/update?id=' . $model->id,
                'disableDelete' => true
            ])
            ?>

        </div>
        <!-- "additionalInfo" -->
        <small class="avatar-info-role font-weight-normal mb-0">
            <?= (!empty($userProfileRole)) ? $userProfileRole : ''; ?>
        </small>
        <small class="avatar-info font-weight-normal mb-0">
            <?= (!empty($prevalentPartnershipName)) ? $prefixPrevalentPartnershipName . $prevalentPartnershipName : ''; ?>
        </small>

        <?php if (Yii::$app->user->id != $model->user_id) : ?>
            <?php if ($adminModule->enableUserContacts && !$adminModule->enableSendMessage) : ?>
                <?= ConnectToUserWidget::widget(['model' => $model, 'divClassBtnContainer' => $defaultButtonClass]) ?>
            <?php endif; ?>
            <?php if (!$adminModule->enableUserContacts && $adminModule->enableSendMessage) : ?>
                <?= SendMessageToUserWidget::widget(['model' => $model, 'divClassBtnContainer' => $defaultButtonClass]) ?>
            <?php endif; ?>
            <?php if ($adminModule->enableInviteUserToEvent) : ?>
                <?php
                /** @var \open20\amos\events\AmosEvents $eventsModule */
                $eventsModule = Yii::$app->getModule('events');
                ?>
                <?php if (!is_null($eventsModule) && $eventsModule->hasMethod('getInviteUserToEventWidget')) : ?>
                    <?= $eventsModule->getInviteUserToEventWidget($model) ?>
                <?php endif; ?>
            <?php endif; ?>
        <?php else : ?>
            <div class="<?= $defaultButtonClass ?>">
                <?= Html::a(
                    'Il mio profilo' . '<span class="mdi mdi-18px mdi-account-outline"></span>',
                        ['user-profile/view', 'id' => $model->id],
                        ['class' => ['btn btn-outline-tertiary']]
                    )
                ?>
            </div>
        <?php endif; ?>

    </div>

</div>