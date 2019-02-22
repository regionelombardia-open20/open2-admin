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
use lispa\amos\core\icons\AmosIcons;
use lispa\amos\notificationmanager\widgets\NotifyFrequencyWidget;

/**
 * @var yii\web\View $this
 * @var lispa\amos\core\forms\ActiveForm $form
 * @var lispa\amos\admin\models\UserProfile $model
 * @var lispa\amos\core\user\User $user
 */

/** @var AmosAdmin $adminModule */
$adminModule = Yii::$app->controller->module;

\lispa\amos\core\utilities\ModalUtility::createAlertModal([
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
        <?= \lispa\amos\core\helpers\Html::activeCheckbox($model, 'notify_from_editorial_staff', [
            'name' => 'notify_from_editorial_staff',
            'id' => 'notify_from_editorial_staff-1',
            'onchange' => "if(!$(this).is(':checked')){ $('#notify-uncheck').modal('show'); }"
        ]) ?>
    <div>
</section>
