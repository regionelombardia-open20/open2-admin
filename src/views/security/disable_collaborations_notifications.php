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

$this->title = AmosAdmin::t('amosadmin', '#disable_collaborations_notifications_title');
$this->params['breadcrumbs'][] = $this->title;

ModuleAdminAsset::register(Yii::$app->view);
?>

<div id="bk-formDefaultLogin" class="bk-loginContainer loginContainer">
    <div class="body col-xs-12 nop">
        <h4 class="subtitle-login"><?= AmosAdmin::t('amosadmin', '#disable_collaborations_notifications_text'); ?></h4>
        <?php $form = ActiveForm::begin(['id' => 'disable-collaborations-notifications']); ?>
        <div class="row">
            <div class="col-xs-12">
                <?= Html::submitButton(
                        AmosAdmin::t('amosadmin', '#disable_collaborations_notifications_submit_button'),
                        [
                            'class' => 'btn btn-primary btn-administration-primary m-t-20',
                            'name' => 'disable-collaborations-notifications',
                            'value' => 1,
                            'title' => AmosAdmin::t('amosadmin', '#disable_collaborations_notifications_submit_button_title')]) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>


</div>
