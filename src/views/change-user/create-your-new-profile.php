<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos\admin\views\change-user
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
use open20\amos\core\forms\ActiveForm;
use open20\amos\core\helpers\Html;

/**
 * @var yii\web\View $this
 * @var open20\amos\admin\models\ChangeUserCreateForm $model
 */

$this->title = AmosAdmin::t('amosadmin', '#change_user_create_new_user_profile');
$this->params['breadcrumbs'][] = $this->title;

$adminModuleName = AmosAdmin::getModuleName();
$emailElementId = Html::getInputId($model, 'email');

$js = <<<JS
function checkEmail(email) {
$.get("/$adminModuleName/change-user/check-email-ajax", {email: email})
    .done(function(data) {
        data = $.parseJSON(data);
        var checkMailElement = $('#check-email');
        if (data.success !== undefined) {
            checkMailElement.html(data.message);
            if (data.message == '') {
                checkMailElement.hide();
                $('#submit_create_new_profile').prop('disabled', '');
            } else {
                checkMailElement.show();
                $('#submit_create_new_profile').prop('disabled', 'disabled');
            }
        } else {
            checkMailElement.html('');
            checkMailElement.show();
        }
    });
}

$('#$emailElementId').change(function(e) {
    e.preventDefault();
    checkEmail($(this).val());
    return true;
});

JS;
$this->registerJs($js);

?>

<?php
$form = ActiveForm::begin([
    'id' => 'change-user-create-profile-form',
]);
?>
<div class="change-user-create-profile-form">
    <?= $this->render('_introduction', ['form' => $form, 'model' => $model]); ?>

    <div class="row">
        <?= $this->render('_fields_begin', ['form' => $form, 'model' => $model]); ?>
        <div class="col-xs-12 col-md-6">
            <?= $form->field($model, 'email')->textInput(['maxlength' => 255, 'readonly' => false]) ?>
        </div>
        <?= $this->render('_fields_end', ['form' => $form, 'model' => $model]); ?>
    </div>

    <div class="row">
        <div class="col-xs-12 alert alert-warning" style="display: none;" id="check-email"></div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <?= Html::tag('div',
                Html::submitButton(AmosAdmin::t('amosadmin', '#change_user_create_new_user_profile_submit_btn_label'), ['class' => 'btn btn-primary btn-administration-primary pull-right', 'title' => AmosAdmin::t('amosadmin', '#change_user_create_new_user_profile_submit_btn_label'), 'id' => 'submit_create_new_profile']) .
                Html::a(AmosAdmin::t('amosadmin', '#change_user_create_new_user_profile_goback_btn_label'), ['/' . AmosAdmin::getModuleName() . '/change-user/my-users-list'], ['class' => 'btn btn-secondary pull-left', 'title' => AmosAdmin::t('amosadmin', '#change_user_create_new_user_profile_goback_btn_label')])
            ); ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
