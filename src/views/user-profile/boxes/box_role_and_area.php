<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos\admin\views\user-profile\boxes
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
use open20\amos\admin\controllers\UserProfileController;
use open20\amos\admin\models\UserProfileArea;
use open20\amos\admin\models\UserProfileRole;
use open20\amos\core\forms\editors\Select;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use yii\web\View;

/**
 * @var yii\web\View $this
 * @var open20\amos\core\forms\ActiveForm $form
 * @var open20\amos\admin\models\UserProfile $model
 * @var open20\amos\core\user\User $user
 */

/** @var UserProfileController $appController */
$appController = Yii::$app->controller;

/** @var AmosAdmin $adminModule */
$adminModule = Yii::$app->controller->module;

$roleId = Html::getInputId($model, 'user_profile_role_id');
$roleName = Html::getInputName($model, 'user_profile_role_id');
$areaId = Html::getInputId($model, 'user_profile_area_id');
$areaName = Html::getInputName($model, 'user_profile_area_id');
$otherRoleId = Html::getInputId($model, 'user_profile_role_other');
$otherAreaId = Html::getInputId($model, 'user_profile_area_other');

$js = "
//$('input[name=\"" . $roleName . "\"]').on('change', function(event) {
$('#$roleId').on('change', function(event) {
    if ($(this).val() != " . $adminModule->roleFreeTextFieldId . ") {
        $('#" . $otherRoleId . "').attr('disabled', true).val('');
    } else {
        $('#" . $otherRoleId . "').attr('disabled', false);
    }
});
//$('input[name=\"" . $areaName . "\"]').on('change', function(event) {
$('#$areaId').on('change', function(event) {
    if ($(this).val() != " . UserProfileArea::OTHER . ") {
        $('#" . $otherAreaId . "').attr('disabled', true).val('');
    } else {
        $('#" . $otherAreaId . "').attr('disabled', false);
    }
});
";
$this->registerJs($js, View::POS_READY);

?>
<?php if (!$adminModule->roleAndAreaOnOrganizations): ?>
    <section>
        <div class="row">
            <?php if ($adminModule->confManager->isVisibleField('user_profile_role_id', ConfigurationManager::VIEW_TYPE_FORM)): ?>
                <div class="col-xs-12 col-md-6">
                    <?= $form->field($model, 'user_profile_role_id', [
                        'template' => "{label}\n{hint}\n{beginWrapper}\n{input}\n{error}\n{endWrapper}",
                    ])->widget(Select::classname(), [
                        'options' => ['placeholder' => AmosAdmin::t('amosadmin', 'Select/Choose') . '...', 'disabled' => false],
                        'data' => $appController->getRoles()
                    ])->label($model->getAttributeLabel('role') . ' ' . AmosIcons::show('lock', ['title' => AmosAdmin::t('amosadmin', '#confidential')])); ?>
                    <?= $form->field($model, 'user_profile_role_other')->textInput([
                        'maxlength' => true,
                        'readonly' => false,
                        'disabled' => ($model->user_profile_role_id != $adminModule->roleFreeTextFieldId),
                        'placeholder' => AmosAdmin::t('amosadmin', 'Other Role')
                    ])->label(false) ?>
                </div>
            <?php endif; ?>
            <?php if ($adminModule->confManager->isVisibleField('user_profile_area_id', ConfigurationManager::VIEW_TYPE_FORM)): ?>
                <div class="col-xs-12 col-md-6">
                    <?= $form->field($model, 'user_profile_area_id', [
                        'template' => "{label}\n{hint}\n{beginWrapper}\n{input}\n{error}\n{endWrapper}",
                    ])->widget(Select::classname(), [
                        'options' => ['placeholder' => AmosAdmin::t('amosadmin', 'Select/Choose') . '...', 'disabled' => false],
                        'data' => $appController->getAreas()
                    ])->label($model->getAttributeLabel('area') . ' ' . AmosIcons::show('lock', ['title' => AmosAdmin::t('amosadmin', '#confidential')])); ?>
                    <?= $form->field($model, 'user_profile_area_other')->textInput([
                        'maxlength' => true,
                        'readonly' => false,
                        'disabled' => ($model->user_profile_area_id != UserProfileArea::OTHER),
                        'placeholder' => AmosAdmin::t('amosadmin', 'Other Area')
                    ])->label(false) ?>
                </div>
            <?php endif; ?>
        </div>
    </section>
<?php endif; ?>
