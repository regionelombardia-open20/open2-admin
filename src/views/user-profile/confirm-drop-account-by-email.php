<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos\admin\views\user-profile
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

use kartik\password\PasswordInput;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use open20\amos\admin\AmosAdmin;

//use open20\amos\core\forms\ActiveForm;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 */

$this->title = AmosAdmin::t('amosadmin', 'Drop your account');
$this->params['breadcrumbs'][] = ['label' => AmosAdmin::t('amosadmin', 'Utenti'), 'url' => ['/admin']];
$this->params['breadcrumbs'][] = ['label' => AmosAdmin::t('amosadmin', 'Elenco'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => AmosAdmin::t('amosadmin', 'Drop Account'), 'url' => ['update', 'id' => $id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-profile-index row nom">
    <div class="tab-content">
        <h1 class="sr-only"><?= $this->title ?></h1>
        <h3><?= AmosAdmin::t('amosadmin', "Attenzione, stai per completare l'operazione di cancellazione del tuo account. Se confermi, il tuo account e tutti i dati a te associati verranno eliminati. L'operazione è irreversibile, confermi?") ?></h3>
        <?php
        $form = ActiveForm::begin([
            'id' => 'drop-form',
            'options' => ['class' => 'form-horizontal', 'autocomplete' => 'off'],
        ]) ?>
        <div class="col-sm-5 col-xs-12" hidden>
            <?= Html::hiddenInput('user_id', $model->user_id) ?>
            <?= Html::hiddenInput('confirm',1) ?>
        </div>
        <div class="clearfix"></div>

        <div class="bk-btnFormContainer">
            <?= Html::a(AmosAdmin::t('amosadmin', 'Cancel'),['update' ,'id' => $model->id] ,['class' => 'btn btn-secondary pull-left', 'title' =>  AmosAdmin::t('amosadmin', 'Cancel')]) ?>

            <!--        <div class="col-lg-12 col-sm-12">-->
            <?= Html::submitButton(AmosAdmin::t('amosadmin', 'Confirm delete'), ['class' => 'btn btn-danger pull-right', 'title' =>  AmosAdmin::t('amosadmin', 'Drop your account')]) ?>
            <!--        </div>-->
        </div>
        <?php ActiveForm::end() ?>
    </div>
</div>
