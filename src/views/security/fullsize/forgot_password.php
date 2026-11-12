<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos\admin\views\security
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
use open20\amos\core\helpers\Html;
use open20\amos\admin\assets\ModuleAdminAsset;
use open20\amos\core\forms\ActiveForm;
use open20\amos\core\icons\AmosIcons;

$this->title = AmosAdmin::t('amosadmin', 'Password dimenticata');
$this->params['breadcrumbs'][] = $this->title;

/*
pr('test');
die();
*/

$referrer = \Yii::$app->request->referrer;

if( (strpos($referrer, 'javascript') !== false) || (strpos($referrer ,\Yii::$app->params['backendUrl']) == false ) ){
    $referrer = null;
}

/*
pr($referrer);
die();
*/

ModuleAdminAsset::register(Yii::$app->view);
?>

<div id="bk-formDefaultLogin" class="loginContainerFullsize">
    <div class="login-block forgotpwd-block col-xs-12 nop">
        <div class="login-body">
            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
            <div class="row">
                <div class="col-xs-12">
                    <?= $form->field($model, 'email')->textInput(['placeholder' => AmosAdmin::t('amosadmin', '#fullsize_field_email_forgotpwd')])->label(AmosIcons::show('mail', '', AmosIcons::IC) . AmosAdmin::t('amosadmin', '#fullsize_forgotpwd_subtitle')) ?>
                </div>
                <div class="col-xs-12 action">
                    <?= Html::submitButton(AmosAdmin::t('amosadmin', '#forgot_pwd_send'), ['class' => 'btn btn-navigation-primary', 'name' => 'login-button', 'title' => AmosAdmin::t('amosadmin', '#forgot_pwd_send_title')]) ?>
                    <?= Html::a(AmosAdmin::t('amosadmin', '#go_to_login'), (strip_tags($referrer) ?: ['/'.AmosAdmin::getModuleName().'/security/login']), ['class' => 'btn btn-navigation-primary', 'title' => AmosAdmin::t('amosadmin', '#go_to_login_title')]) ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>