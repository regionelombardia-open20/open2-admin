<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos\admin\views\first-access-wizard
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
use open20\amos\core\forms\WizardPrevAndContinueButtonWidget;
use open20\amos\core\helpers\Html;

/**
 * @var \yii\web\View $this
 * @var \open20\amos\admin\models\UserProfile $model
 */

$partnershipUrl = ['/'.AmosAdmin::getModuleName().'/first-access-wizard/associate-prevalent-partnership', 'id' => $model->id, 'viewM2MWidgetGenericSearch' => true];

/* @var \open20\amos\cwh\AmosCwh $moduleCwh */
$moduleCwh = \Yii::$app->getModule('cwh');

$moduleTag = \Yii::$app->getModule('tag');

?>

<div class="first-access-wizard-partnership">
    <?php $form = ActiveForm::begin([
        'options' => [
            'id' => 'first-access-wizard-form',
            'class' => 'form',
            'enctype' => 'multipart/form-data', //to load images
            'enableClientValidation' => true,
            'errorSummaryCssClass' => 'error-summary alert alert-error'
        ]
    ]); ?>
    
    <?= $form->errorSummary($model, ['class' => 'alert-danger alert fade in', 'role' => 'alert']); ?>
    <?= $this->render('parts/header', ['model' => $model]) ?>

    <section>
        <div>
            <p class="lead"><?= AmosAdmin::t('amosadmin', '#faw_partnership_text') ?></p>
        </div>
    </section>
    <section>
        <div >
            <div class="row">
                <?php if (!is_null($model->prevalentPartnership)): ?>
                    <div class="col-xs-3 col-md-2 img-wizard-partnership">
                        <?php
                        $admin =  AmosAdmin::getInstance();
                        /** @var  $organizationsModule OrganizationsModuleInterface*/
                        $organizationsModule = \Yii::$app->getModule($admin->getOrganizationModuleName());
                        $widgetClass = $organizationsModule->getOrganizationCardWidgetClass();
                        echo $widgetClass::widget(['model' => $model->prevalentPartnership]);
                        ?>
                    </div>
                    <div class="col-xs-4">
                        <div><h4><?= $model->prevalentPartnership->getTitle() ?></h4></div>
                        <div><?= Html::a(AmosAdmin::t('amosadmin', 'Change prevalent partnership'), $partnershipUrl, ['class' => 'btn btn-primary']) ?></div>
                    </div>
                <?php else: ?>
                    <div class="col-xs-12 m-t-35">
                        <p><?= AmosAdmin::tHtml('amosadmin', 'Prevalent partnership not selected') ?></p>
                        <div><?= Html::a(AmosAdmin::t('amosadmin', 'Select prevalent partnership'), $partnershipUrl, ['class' => 'btn btn-primary']) ?></div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <?= $form->field($model, 'prevalent_partnership_id')->hiddenInput()->label(false) ?>
    
    <?= WizardPrevAndContinueButtonWidget::widget([
        'model' => $model,
        'previousUrl' => (isset($moduleCwh) && isset($moduleTag)) ? Yii::$app->getUrlManager()->createUrl(['/'.AmosAdmin::getModuleName().'/first-access-wizard/interests']) : Yii::$app->getUrlManager()->createUrl(['/'.AmosAdmin::getModuleName().'/first-access-wizard/role-and-area']),
    ]) ?>
    <?php ActiveForm::end(); ?>
</div>
