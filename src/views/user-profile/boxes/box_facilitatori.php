<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\admin\views\user-profile\boxes
 * @category   CategoryName
 */

use lispa\amos\admin\AmosAdmin;
use lispa\amos\admin\models\UserProfile;
use lispa\amos\core\helpers\Html;
use lispa\amos\core\icons\AmosIcons;
use kartik\alert\Alert;

/**
 * @var yii\web\View $this
 * @var lispa\amos\core\forms\ActiveForm $form
 * @var lispa\amos\admin\models\UserProfile $model
 * @var lispa\amos\core\user\User $user
 */

/** @var \lispa\amos\admin\models\UserProfile $facilitatorUserProfile */
$facilitatorUserProfile = UserProfile::findOne(['user_id' => $model->facilitatore_id]);

?>

<section>
    <div class="col-xs-12 facilitator-content">
        <div class="col-xs-12 facilitator-textarea">
            <h4>
                <strong><?= AmosAdmin::t('amosadmin', 'The facilitator') ?></strong>
                <?= Html::beginTag('span', [
                    'title' => AmosAdmin::t('amosadmin', '#tooltip_facilitator'),
                    'data-toggle' => 'tooltip',
                    'data-html' => 'true',
                    'class' => 'amos-tooltip',
                ]) . AmosIcons::show('info'); ?>
            </h4>
            <p><?= AmosAdmin::t('amosadmin', 'The facilitator is a user with an in-depth knowledge of the platform\'s objectives and methodology and is responsible for providing assistance to users.') ?></p>
            <p><?= AmosAdmin::t('amosadmin', 'You can contact the facilitator at any time for informations on compiling your profile data and using the platform.') ?></p>
        </div>

        <div class="col-xs-12 ">
            <div class="col-xs-12 facilitator-id m-t-15">
                <?php if ($model->isNewRecord): ?>
                    <div class="m-t-20">
                        <?= Alert::widget([
                            'type' => Alert::TYPE_WARNING,
                            'body' => AmosAdmin::t('amosadmin', '#facilitator_box_new_record_message'),
                            'closeButton' => false
                        ]); ?>
                    </div>
                <?php else: ?>
                    <?php if (!is_null($facilitatorUserProfile)): ?>
                        <!--                        <div class="col-sm-1 col-xs-4 m-t-10 m-b-10">-->
                        <?php
                        Yii::$app->imageUtility->methodGetImageUrl = "getAvatarUrl";
                        echo Html::tag('div', Html::img($facilitatorUserProfile->getAvatarUrl(), [
                            'class' => Yii::$app->imageUtility->getRoundImage($facilitatorUserProfile)['class'],
                            'style' => "margin-left: " . Yii::$app->imageUtility->getRoundImage($facilitatorUserProfile)['margin-left'] . "%; margin-top: " . Yii::$app->imageUtility->getRoundImage($facilitatorUserProfile)['margin-top'] . "%;",
                            'alt' => $facilitatorUserProfile->getNomeCognome()
                        ]),
                            ['class' => 'container-round-img-sm']);
                        ?>
                        <!--                        </div>-->
                        <!--                        <div class="col-sm-11 col-xs-8">-->
                        <p><?= Html::tag('span', AmosAdmin::t('amosadmin', 'Nome')) . Html::tag('span', $facilitatorUserProfile->nome); ?></p>
                        <p><?= Html::tag('span', AmosAdmin::t('amosadmin', 'Cognome')) . Html::tag('span', $facilitatorUserProfile->cognome); ?></p>
                        <p><?= Html::tag('span', AmosAdmin::t('amosadmin', 'Prevalent partnership')) . (!is_null($facilitatorUserProfile->prevalentPartnership) ? Html::tag('span', $facilitatorUserProfile->prevalentPartnership->name) : '-') ?></p>
                        <p><?= Html::a(AmosAdmin::t('amosadmin', 'Change facilitator'), ['/admin/user-profile/associate-facilitator', 'id' => $model->id, 'viewM2MWidgetGenericSearch' => true]) ?></p>
                        <!--                        </div>-->
                    <?php else: ?>
                        <p><?= AmosAdmin::tHtml('amosadmin', 'Facilitator not selected') ?></p>
                        <p><?= Html::a(AmosAdmin::t('amosadmin', 'Select facilitator'), ['/admin/user-profile/associate-facilitator', 'id' => $model->id, 'viewM2MWidgetGenericSearch' => true]) ?></p>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
