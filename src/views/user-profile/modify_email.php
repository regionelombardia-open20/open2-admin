<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos-admin
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
/**@var $user \open20\amos\admin\models\UserProfile
 * @var $model \open20\amos\admin\models\UserOtpCode
 */

$this->title = \open20\amos\admin\AmosAdmin::t('amosadmin','Modifica ed autentica email');
$this->params['breadcrumbs'][] = ['label' => 'modifica profilo', 'url' => ['update', 'id' => $user->userProfile->id]];
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>

<div class="form-auth-email">
    <?php $form = \open20\amos\core\forms\ActiveForm::begin() ?>
    <div class="row">
        <div class="col-xs-12 col-sm-6">
            <?= $form->field($user, 'email')->textInput(['readonly' => $inserisciCodice ]);?>
        </div>
        <div class="col-xs-12 col-sm-6 m-t-30">
            <?php $label = AmosAdmin::t('amosadmin', 'Confirm email');
            if($inserisciCodice){
                $label = AmosAdmin::t('amosadmin', 'Invia di nuovo il codice');
            }?>
            <?php echo \open20\amos\core\helpers\Html::submitButton($label, [
                    'class' => 'btn btn-primary',
                'id' => 'save-code',
                'name' => 'save-code',
                'value' => 1
            ]); ?>
        </div>
    </div>
    <?php if($inserisciCodice) {?>
    <div class="row m-t-25">
        <div class="col-xs-12 col-sm-6 nop">
            <div class="col-xs-12">
                <h4 class="bold"><?= AmosAdmin::t('amosadmin', 'Inserire il codice che ti è stato inviato via email nel campo qui sotto.')?></h4>
            </div>
            <div class="col-sm-8 col-xs-12">
                <?= $form->field($model, 'auth_code')->label(AmosAdmin::t('amosadmin','OTP Code'));?>
            </div>
            <div class="col-sm-4 col-xs-12 m-t-25 text-right">
                <?php echo \open20\amos\core\helpers\Html::submitButton(AmosAdmin::t('amosadmin', 'Confirm OTP code'),['class' => 'btn btn-primary']); ?>
            </div>
        </div>
    </div>
    <?php } else { ?>
        <div class="clearfix"></div>
        <div class="pull-right m-t-25">
        </div>
    <?php }?>
    <?php \open20\amos\core\forms\ActiveForm::end();?>
</div>
