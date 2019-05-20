<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\admin\views\security
 * @category   CategoryName
 */

use lispa\amos\admin\AmosAdmin;
use lispa\amos\core\helpers\Html;
use lispa\amos\admin\assets\ModuleAdminAsset;
use lispa\amos\core\forms\ActiveForm;
use lispa\amos\core\icons\AmosIcons;

$this->title = AmosAdmin::t('amosadmin', 'Password dimenticata');
$this->params['breadcrumbs'][] = $this->title;

ModuleAdminAsset::register(Yii::$app->view);
?>

<div id="bk-formDefaultLogin" class="loginContainerFullsize">
    <div class="login-block forgotpwd-block col-xs-12 nop">
        <div class="login-body">
            <h2 class="title-login"><?= AmosAdmin::t('amosadmin', '#fullsize_forgotpwd'); ?></h2>
            <h3 class="title-login"><?= AmosAdmin::t('amosadmin', '#fullsize_forgotpwd_subtitle'); ?></h3>
            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
            <div class="row">
                <div class="col-xs-12">
                    <?= $form->field($model, 'email')->textInput(['placeholder' => AmosAdmin::t('amosadmin', '#fullsize_field_email_forgotpwd')])->label('') ?>
                    <?= AmosIcons::show('mail', '', AmosIcons::IC) ?>
                </div>
                <div class="col-xs-12 action">
                    <?= Html::submitButton(AmosAdmin::t('amosadmin', '#forgot_pwd_send'), ['class' => 'btn btn-secondary', 'name' => 'login-button', 'title' => AmosAdmin::t('amosadmin', '#forgot_pwd_send_title')]) ?>
                    <?= Html::a(AmosAdmin::t('amosadmin', '#go_to_login'), (\Yii::$app->request->referrer ?: ['/admin/security/login']), ['class' => 'btn btn-navigation-primary', 'title' => AmosAdmin::t('amosadmin', '#go_to_login_title')]) ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>