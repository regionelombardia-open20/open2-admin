<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos\basic\template
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



use yii\helpers\Html;
use open20\amos\core\forms\ActiveForm;
use open20\amos\admin\AmosAdmin;
//use kartik\password\PasswordInput;
use open20\amos\core\forms\PasswordInput;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = 'Reset password';
$this->params['breadcrumbs'][] = $this->title;
?>

<div id="bk-formDefaultLogin" class="bk-loginContainer loginContainer">
    <div class="body col-xs-12">
        <h2 class="title-login"><?= AmosAdmin::t('amosadmin', '#reset_pwd_title'); ?></h2>
        <h3 class="subtitle-login"><?= AmosAdmin::t('amosadmin', '#reset_pwd_subtitle'); ?></h3>
        <?php
        $form = ActiveForm::begin([
            'id' => 'login-form',
            'options' => ['autocomplete' => 'off'],
        ])
        ?>
        <div class="row">
            <div class="col-xs-12 nop">
                <?= Html::beginTag('div', ['class' => 'form-group field-firstaccessform-password']) ?>
                <?= Html::tag('span', $model->getAttributeLabel('username')) ?>
                <?= Html::tag('strong', Html::encode($model->username)) ?>
                <?= Html::endTag('div') ?>
                <div class="level-security">
                    <span><?= Yii::t('amosadmin', 'Livello sicurezza') ?></span>
                </div>
                <?=
                $form->field($model, 'password')->widget(PasswordInput::classname(), [
                    'language' => 'it',
                    'options' => [
                        'autocomplete' => 'off'
                    ],
                    'pluginOptions' => [
                        'showMeter' => true,
                        'toggleMask' => true,
                        'language' => 'it'
                    ]]);
                ?>
                <?= $form->field($model, 'ripetiPassword')->passwordInput([
                            'autocomplete' => 'off'
                        ]) ?>
                <?php if(!empty($isFirstAccess) && $isFirstAccess){ ?>
                    <div class="text-bottom col-xs-12 nop">
                        <?= Html::a(AmosAdmin::t('amosadmin', '#cookie_policy_message'), '/site/privacy', ['title' => AmosAdmin::t('amosadmin', '#cookie_policy_title'), 'target' => '_blank']) ?>
                        <?= Html::tag('p', AmosAdmin::t('amosadmin', '#cookie_policy_content')) ?>
                        <div class="">
                            <?= $form->field($model, 'privacy')->radioList([
                                1 => AmosAdmin::t('amosadmin', '#cookie_policy_ok'),
                                0 => AmosAdmin::t('amosadmin', '#cookie_policy_not_ok')
                            ]); ?>
                        </div>
                    </div>
                <?php } ?>
                <?= $form->field($model, 'token')->hiddenInput()->label(false) ?>
            </div>
            <div class="col-xs-12 footer-link nop">
                <?= Html::submitButton(AmosAdmin::t('amosadmin', '#text_button_login'), ['class' => 'btn btn-primary btn-administration-primary pull-right', 'name' => 'first-access-button', 'title' => AmosAdmin::t('amosadmin', '#text_button_login')]) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
