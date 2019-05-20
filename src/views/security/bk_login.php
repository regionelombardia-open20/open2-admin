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
use lispa\amos\admin\assets\ModuleAdminAsset;
use lispa\amos\core\forms\ActiveForm;
use lispa\amos\core\helpers\Html;
use lispa\amos\core\icons\AmosIcons;

ModuleAdminAsset::register(Yii::$app->view);

/**
 * @var yii\web\View $this
 * @var yii\bootstrap\ActiveForm $form
 * @var \lispa\amos\admin\models\LoginForm $model
 */
$this->title = AmosAdmin::t('amosadmin', 'Login');
$this->params['breadcrumbs'][] = $this->title;

/** @var $socialAuthModule \lispa\amos\socialauth\Module */
$socialAuthModule = Yii::$app->getModule('socialauth');

/** @var AmosAdmin $adminModule */
$adminModule = Yii::$app->getModule(AmosAdmin::getModuleName());

$socialMatch = Yii::$app->session->get('social-match');
$socialProfile = Yii::$app->session->get('social-profile');

// for redirect to community after login or registration
$communityId = null;
$previousUrl = Yii::$app->getUser()->getReturnUrl();
$found = strpos($previousUrl, 'community/join?id=');
if($found) {
    $parsedUrl = parse_url($previousUrl);
    parse_str($parsedUrl['query'], $query_params);
    if($query_params) {
        $communityId = $query_params['id'];
    }
}
?>

<div id="bk-formDefaultLogin" class="bk-loginContainer loginContainer">

    <div class="header col-xs-12">
        <?php if (!isset(Yii::$app->params['logo']) || !Yii::$app->params['logo']) : ?>
            <p class="welcome-message"><?= AmosAdmin::t('amosadmin', '#login_welcome_message') ?></p>
        <?php endif; ?>

        <?php
        if ($socialProfile) :
            echo Html::tag('h2',
                AmosAdmin::t('amosadmin', 'You are right to link you {provider} account to your profile',
                    [
                        'provider' => $socialMatch
                    ]), ['class' => 'title-login col-xs-12 nop']);
        endif;
        ?>

        <?php if ($socialAuthModule && $socialAuthModule->enableSpid) : ?>
            <?=
            Html::a(
                AmosIcons::show('account-circle') . AmosAdmin::t('amosadmin', '#login_spid_text') . ' ' . Html::tag('span',AmosAdmin::t('amosadmin', '#login_spid_text2')),
                '/socialauth/shibboleth/endpoint',
                [
                    'class' => 'btn btn-spid',
                    'title' => AmosAdmin::t('amosadmin', '#login_spid_title'),
                    'target' => '_blank'
                ]
            )
            ?>
        <?php endif; ?>
    </div>

    <?php if ($socialAuthModule && $socialAuthModule->enableSpid) : ?>
        <?= Html::tag('div',Html::tag('span', AmosAdmin::t('amosadmin', '#or')),['class' => 'or-login col-xs-12 nop'])?>
    <?php endif; ?>

    <div class="header col-xs-12">
        <?php if ($socialAuthModule && $socialAuthModule->enableLogin && !$socialMatch) : ?>
            <?=
            $this->render('parts/header', [
                'type' => 'login',
                'communityId' => $communityId
            ]);
            ?>
        <?php endif; ?>
    </div>

    <?php if ($socialAuthModule && $socialAuthModule->enableLogin && !$socialMatch) : ?>
        <?= Html::tag('div',Html::tag('span', AmosAdmin::t('amosadmin', '#or')),['class' => 'or-login col-xs-12 nop'])?>
    <?php endif; ?>

    <div class="body col-xs-12 nop">
        <?= Html::tag('h2', AmosAdmin::t('amosadmin', '#title_login'),
            ['class' => 'title-login col-xs-12 nop'])
        ?>
        <?= Html::tag('h3', AmosAdmin::t('amosadmin', '#subtitle_login'),
            ['class' => 'subtitle-login col-xs-12 nop'])
        ?>
        <div class="row">
            <div class="col-lg-12 col-sm-12 nop">
                <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
                <?php if (isset(\Yii::$app->params['template-amos']) && \Yii::$app->params['template-amos']): ?>
                    <?=
                    $form->field($model, 'ruolo')->dropDownList([
                        'ADMIN' => AmosAdmin::t('amosadmin', 'Admin'),
                        'VALIDATED_BASIC_USER' => AmosAdmin::t('amosadmin', 'Validated Basic User')
                    ])
                    ?>
                <?php endif; ?>

                <?php if ($adminModule->allowLoginWithEmailOrUsername): ?>
                    <?php if (isset(\Yii::$app->params['isDemoLogin']) && \Yii::$app->params['isDemoLogin']): ?>
                        <div class="col-xs-12 col-sm-6">
                            <?= $form->field($model, 'usernameOrEmail',
                                ['inputOptions' => ['value' => 'demo']])->textInput([
                                'readonly' => true])
                            ?>
                        </div>
                    <?php else: ?>
                        <div class="col-xs-12 col-sm-6">
                            <?= $form->field($model, 'usernameOrEmail')->textInput() ?>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <?php if (isset(\Yii::$app->params['isDemoLogin']) && \Yii::$app->params['isDemoLogin']): ?>
                        <div class="col-xs-12 col-sm-6">
                            <?= $form->field($model, 'username',
                                ['inputOptions' => ['value' => 'demo']])->textInput([
                                'readonly' => true])
                            ?>
                        </div>
                    <?php else: ?>
                        <div class="col-xs-12 col-sm-6">
                            <?= $form->field($model, 'username')->textInput() ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
                <?php if (isset(\Yii::$app->params['isDemoLogin']) && \Yii::$app->params['isDemoLogin']): ?>
                    <div class="col-xs-12 col-sm-6">
                        <?= $form->field($model, 'password', ['inputOptions' => ['value' => 'Demo1234']])->passwordInput([
                            'readonly' => true]) ?>
                        <div class="forgot-password"><?=
                            Html::a(AmosAdmin::t('amosadmin', '#forgot_password'), ['/admin/security/forgot-password'],
                                ['title' => AmosAdmin::t('amosadmin', '#forgot_password_title_link'), 'target' => '_self'])
                            ?></div>
                    </div>
                <?php else: ?>
                    <div class="col-xs-12 col-sm-6">
                        <?= $form->field($model, 'password')->passwordInput() ?>
                        <div class="forgot-password"><?=
                            Html::a(AmosAdmin::t('amosadmin', '#forgot_password'), ['/admin/security/forgot-password'],
                                ['title' => AmosAdmin::t('amosadmin', '#forgot_password_title_link'), 'target' => '_self'])
                            ?></div>
                    </div>
                <?php endif; ?>

            </div>

            <div class="col-xs-12">
                <?=
                $form->field($model, 'rememberMe')->checkbox()->label(AmosAdmin::t('amosadmin', '#remember_access'),
                    ['class' => 'remember-me', 'title' => AmosAdmin::t('amosadmin', '#remember_access')])
                ?>
            </div>

            <div class="col-xs-12">
                <?=
                Html::submitButton(AmosAdmin::t('amosadmin', '#text_button_login'),
                    ['class' => 'btn btn-primary btn-administration-primary pull-right', 'name' => 'login-button', 'title' => AmosAdmin::t('amosadmin',
                        '#text_button_login_title')])
                ?>
                <?php ActiveForm::end(); ?>
            </div>

            <?php if (Yii::$app->getModule('admin')->enableRegister || (!Yii::$app->getModule('admin')->enableRegister && !empty(Yii::$app->getModule('admin')->textWarningForRegisterDisabled))): ?>
                <div class="col-xs-12 footer-link">
    <?php
    if($communityId){
        $urlRegister = ['/admin/security/register', 'community' => $communityId];
    } else {
        $urlRegister = ['/admin/security/register'];
    }
    if(Yii::$app->getModule('admin')->showLogInRegisterButton){
        echo Html::tag('h3',
            AmosAdmin::t('amosadmin', '#new_user').
            ' '.
            Html::a(AmosAdmin::t('amosadmin', '#register_now'), $urlRegister,
                ['class' => '', 'title' => AmosAdmin::t('amosadmin', '#register_now_title_link',
                    ['appName' => Yii::$app->name]), 'target' => '_self']), ['class' => 'subtitle-login nom']);
    }
    ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
