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
use open20\amos\comuni\widgets\helpers\AmosComuniWidget;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;

/**
 * @var yii\web\View $this
 * @var open20\amos\core\forms\ActiveForm $form
 * @var open20\amos\admin\models\UserProfile $model
 * @var open20\amos\core\user\User $user
 */

/** @var AmosAdmin $adminModule */
$adminModule = AmosAdmin::instance();

$comuniWidgetConf = [
    'form' => $form,
    'model' => $model,
];

$nazioneResidenzaActive = $adminModule->confManager->isVisibleFieldInForm('residenza_nazione_id');

if ($nazioneResidenzaActive) {
    $comuniWidgetConf['nazioneConfig'] = [
        'attribute' => 'residenza_nazione_id',
        'class' => 'col-lg-4 col-sm-4'
    ];
}

if ($adminModule->confManager->isVisibleFieldInForm('provincia_residenza_id')) {
    $comuniWidgetConf['provinciaConfig'] = [
        'attribute' => 'provincia_residenza_id',
        'class' => 'col-lg-4 col-sm-4'
    ];
}

if ($adminModule->confManager->isVisibleFieldInForm('comune_residenza_id')) {
    $comuniWidgetConf['comuneConfig'] = [
        'attribute' => 'comune_residenza_id',
        'class' => 'col-lg-4 col-sm-4'
    ];
}

?>
<section>
    <h2 class="subtitle-form">
        <?= AmosIcons::show('home') ?>
        <?= AmosAdmin::tHtml('amosadmin', 'Dati di Residenza'); ?>
    </h2>

    <div class="row">
        <?php if (!$nazioneResidenzaActive): ?>
            <?= Html::hiddenInput('residenza_nazione_id', 1, ['id' => 'residenza_nazione_id']) ?> <!-- 1 = ID dell'Italia -->
        <?php endif; ?>
        <?= AmosComuniWidget::widget($comuniWidgetConf); ?>
    </div>
    <div class="row">
        <?php if ($adminModule->confManager->isVisibleField('indirizzo_residenza', ConfigurationManager::VIEW_TYPE_FORM)): ?>
            <div class="col-lg-7 col-sm-7">
                <?= $form->field($model, 'indirizzo_residenza')->textInput(['maxlength' => 255, 'title' => AmosAdmin::t('amosadmin', 'In questo campo inserisci l\'indirizzo, il numero civico va inserito nel campo successivo, sulla destra'), 'readonly' => false]) ?>
            </div>
        <?php endif; ?>
        <?php if (
            ($adminModule->confManager->isVisibleField('numero_civico_residenza', ConfigurationManager::VIEW_TYPE_FORM)) &&
            ($adminModule->confManager->isVisibleField('cap_residenza', ConfigurationManager::VIEW_TYPE_FORM))
        ): ?>
            <div class="col-lg-5 col-sm-5 nop">
                <?php if ($adminModule->confManager->isVisibleField('numero_civico_residenza', ConfigurationManager::VIEW_TYPE_FORM)): ?>
                    <div class="col-lg-5 col-sm-5">
                        <?= $form->field($model, 'numero_civico_residenza')->textInput(['maxlength' => 10, 'title' => AmosAdmin::t('amosadmin', 'Inserisci il civico'), 'readonly' => false]) ?>
                    </div>
                <?php endif; ?>
                <?php if ($adminModule->confManager->isVisibleField('cap_residenza', ConfigurationManager::VIEW_TYPE_FORM)): ?>
                    <div class="col-lg-7 col-sm-7">
                        <?= $form->field($model, 'cap_residenza')->textInput(['maxlength' => true, 'title' => AmosAdmin::tHtml('amosadmin', 'Inserisci il CAP'), 'readonly' => false]) ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
