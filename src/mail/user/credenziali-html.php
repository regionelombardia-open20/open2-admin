<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\mail\user
 * @category   CategoryName
 */

use open20\amos\admin\AmosAdmin;
use open20\amos\core\utilities\CoreCommonUtility;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var \open20\amos\core\user\User $user
 * @var \open20\amos\admin\models\UserProfile $profile
 * @var bool $socialAccount
 */

$appLink = Yii::$app->params['platform'] ['frontendUrl'];//Yii::$app->urlManager->createAbsoluteUrl(['/']);
$appLink = substr($appLink, -1) == '/' ? $appLink : $appLink . '/';
$appLinkPrivacy = Yii::$app->params['platform'] ['frontendUrl'] .'/'.AmosAdmin::getModuleName().'/user-profile/privacy'; //Yii::$app->urlManager->createAbsoluteUrl(['/'.AmosAdmin::getModuleName().'/user-profile/privacy']);
$appName = Yii::$app->name;
$loginLink = CoreCommonUtility::getLoginLink();
$loginLink = substr($loginLink, 0) == '/' ? substr($loginLink, 1) : $loginLink;


$regWihSpid = \Yii::$app->request->post('reg_with_spid');
$this->title = AmosAdmin::t('amosadmin', 'Registrazione {appName}', ['appName' => $appName]);
$this->registerCssFile('http://fonts.googleapis.com/css?family=Roboto');

?>

<table style="line-height: 18px;" width=" 600" border="0" cellpadding="0" cellspacing="0" align="center">
    <tr>
        <td>
            <div class="corpo"
                 style="padding:10px;margin-bottom:10px;background-color:#ffffff;">

                <div class="sezione" style="overflow:hidden;color:#000000;">
                    <div class="testo">
                        <p style="margin-bottom: 20px;">
                            <span style="font-weight: bold;">
                                <?= AmosAdmin::t('amosadmin', '#welcome_email_dear', [
                                    'name' => Html::encode($profile->nome),
                                    'surname' => Html::encode($profile->cognome)
                                ]); ?>
                                </span>
                            <br/>
                            <?= AmosAdmin::t('amosadmin', "#welcome_email") . Yii::$app->name ?>.
                        </p>
                        <?php if ($adminModule->enableDlSemplification || !empty($regWihSpid)): ?>
                            <p style="margin-bottom: 20px;">
                                <?= Html::a(
                                    AmosAdmin::t('amosadmin', '#welcome_email_dl_semplification_click_here'),
                                    $appLink . $loginLink,
                                    ['title' => AmosAdmin::t('amosadmin', 'Enter')]
                                ) ?> <?= AmosAdmin::t('amosadmin', '#welcome_email_dl_semplification', [
                                    'appName' => $appName
                                ]); ?>
                            </p>
                        <?php elseif (isset($socialAccount) && $socialAccount): ?>
                            <p style="margin-bottom: 20px;">
                                <?= Html::a(
                                    AmosAdmin::t('amosadmin', '#welcome_email_social_registration_click_here'),
                                    $appLink . $loginLink,
                                    ['title' => AmosAdmin::t('amosadmin', 'Enter')]
                                ) ?> <?= AmosAdmin::t('amosadmin', '#welcome_email_social_registration', [
                                    'appName' => $appName
                                ]); ?>
                            </p>
                        <?php else: ?>
                            <p style="margin-bottom: 20px;">
                                <?php
                                $seconds = Yii::$app->params['user.passwordResetTokenExpire'];

                                if ($seconds >= 86400) {
                                    $passwordResetTokenExpire = floor($seconds / (3600 * 24));
                                    if ($passwordResetTokenExpire == 1) {
                                        $textDay = 'giorno';
                                    } else {
                                        $textDay = 'giorni';
                                    }
                                } else {
                                    if (floor($seconds / 60) >= 60) {
                                        $textDay = chr(8);
                                        $passwordResetTokenExpire = sprintf("%d ore", floor($seconds / (60 * 60)));
                                    } else {
                                        $textDay = 'minuti';
                                        $passwordResetTokenExpire = floor($seconds / 60);
                                    }

                                }
                                $passwordResetTokenExpire = $passwordResetTokenExpire . ' ' . $textDay;
                                ?>
                                <?= AmosAdmin::t('amosadmin', '#welcome_email_expire', [
                                    'passwordResetTokenExpire' => $passwordResetTokenExpire,
                                    'supportEmail' => Yii::$app->params['supportEmail']
                                ]); ?>

                                <?php
                                if(\Yii::$app->params['befe']){
                                    $link = $appLink . 'userauthfrontend' . '/default/insert-auth-data?token=' . $profile->user->password_reset_token;
                                }
                                else {
                                    $link = $appLink . AmosAdmin::getModuleName() . '/security/insert-auth-data?token=' . $profile->user->password_reset_token;
                                }
                                if (!empty($community)) {
                                    $link .= '&community_id=' . $community->id . '&subscribe=1';
                                }
                                ?>
                                <?= Html::beginTag('a', ['href' => $link]) ?>
                                <?= AmosAdmin::t('amosadmin', "#welcome_email_link") ?>
                                <?= Html::endTag('a'); ?>
                            </p>
                            <p style="margin-bottom: 20px;">
                                <?= AmosAdmin::t('amosadmin', '#welcome_email_error_link') ?>
                                <?= $link; ?>
                            </p>
                        <?php endif; ?>
                        <?php
                        /**
                         * @var \open20\amos\socialauth\Module $social
                         */
                        $social = \Yii::$app->getModule('socialauth');
                        if ($social && $social->enableRegister == true): ?>
                            <p style="margin-bottom: 20px;">
                                <?= AmosAdmin::t('amosadmin', '#welcome_email_social', [
                                    'platformName' => Yii::$app->name
                                ]) ?>
                            </p>
                        <?php endif; ?>
                        <p style="margin-bottom: 20px;">
                            <?= AmosAdmin::t('amosadmin', '#welcome_email_change_data') ?>
                        </p>
                        <p style="text-align: right;margin-bottom: 20px">
                            <?= AmosAdmin::t('amosadmin', '#welcome_email_thanks') ?>
                        </p>
                    </div>
                </div>
            </div>
        </td>
    </tr>
</table>
