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

ModuleAdminAsset::register(Yii::$app->view);
/**
 * @var yii\web\View $this
 * @var yii\bootstrap\ActiveForm $form
 * @var \open20\amos\admin\models\LoginForm $model
 */

$this->title = AmosAdmin::t('amosadmin', 'Reactivate Profile');
$this->params['breadcrumbs'][] = $this->title;

?>

<?php $form = ActiveForm::begin([
    'id' => 'reactivate-profile-form'
]); ?>
<div id="bk-formDefaultLogin" class="loginContainerFullsize">
    <div class="login-block reactivate-block col-xs-12 nop">
        <div class="login-body">
            <h2 class="title-login"><?= AmosAdmin::t('amosadmin', '#fullsize_reactivate_profile'); ?></h2>
            <?php if (\Yii::$app->request->get() && array_key_exists("userdisabled", \Yii::$app->request->get())) { ?>
                <h3 class="title-login"><?= AmosAdmin::t('amosadmin', '#userdisabled_profile_subtitle'); ?></h3>
            <?php } else { ?>
                <h3 class="title-login"><?= AmosAdmin::t('amosadmin', '#fullsize_reactivate_profile_subtitle'); ?></h3>
            <?php } ?>

            <div class="row">
                <div class="col-xs-12">
                    <?= $form->field($model, 'email')->textInput([
                            'maxlength' => true,
                            'placeholder' => AmosAdmin::t('amosadmin', '#fullsize_field_email_reactivate')
                    ])?>
                </div>
                <div class="col-xs-12">
                    <?= $form->field($model, 'message')->textarea([
                            'maxlength' => true,
                            'rows' => 6,
                            'placeholder' => AmosAdmin::t('amosadmin', '#fullsize_field_msg_reactivate')
                    ])?>
                </div>
                <div class="col-xs-12 action">
                    <?= Html::submitButton(AmosAdmin::t('amosadmin', '#reactivate_profile_send'), ['class' => 'btn btn-secondary', 'title' => AmosAdmin::t('amosadmin', '#reactivate_profile_send_title')]) ?>
                    <?php if (\Yii::$app->request->get() && array_key_exists("userdisabled", \Yii::$app->request->get())) { ?>
                        <?= Html::a(AmosAdmin::t('amosadmin', '#go_to_login'), ['/'.AmosAdmin::getModuleName().'/security/login'], ['class' => 'btn btn-navigation-primary', 'title' => AmosAdmin::t('amosadmin', '#go_to_login_title')]) ?>
                    <?php } else { ?>
                        <?= Html::a(AmosAdmin::t('amosadmin', '#go_to_register'), ['/'.AmosAdmin::getModuleName().'/security/register'], ['class' => 'btn btn-navigation-primary', 'title' => AmosAdmin::t('amosadmin', '#go_to_register_title')]) ?>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>