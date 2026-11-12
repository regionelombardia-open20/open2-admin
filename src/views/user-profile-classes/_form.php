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
use open20\amos\core\helpers\Html;
use open20\amos\core\forms\ActiveForm;
use kartik\datecontrol\DateControl;
use open20\amos\core\forms\Tabs;
use open20\amos\core\forms\CloseSaveButtonWidget;
use open20\amos\core\forms\RequiredFieldsTipWidget;
use yii\helpers\Url;
use open20\amos\core\forms\editors\Select;
use yii\helpers\ArrayHelper;
use open20\amos\core\icons\AmosIcons;
use yii\bootstrap\Modal;
use open20\amos\core\forms\TextEditorWidget;
use yii\helpers\Inflector;
use open20\amos\admin\AmosAdmin;
use softark\duallistbox\DualListbox;

/**
 * @var yii\web\View $this
 * @var open20\amos\admin\models\UserProfileClasses $model
 * @var yii\widgets\ActiveForm $form
 */
?>
<div class="user-profile-classes-form col-xs-12 nop">

    <?php
    $form    = ActiveForm::begin([
            'options' => [
                'id' => 'user-profile-classes_'.((isset($fid)) ? $fid : 0),
                'data-fid' => (isset($fid)) ? $fid : 0,
                'data-field' => ((isset($dataField)) ? $dataField : ''),
                'data-entity' => ((isset($dataEntity)) ? $dataEntity : ''),
                'class' => ((isset($class)) ? $class : '')
            ]
    ]);
    ?>
    <?php // $form->errorSummary($model, ['class' => 'alert-danger alert fade in']); ?>

    <div class="row">
        <div class="col-xs-12">          
            <div class="col-md-6 col xs-12">
                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?><!-- description text -->

            </div>
            <div class="col-md-6 col xs-12">
                <?=
                $form->field($model, 'enabled')->dropDownList([1 => AmosAdmin::t('amosadmin', 'Si'),
                    0 => AmosAdmin::t('amosadmin', 'No')])
                ?>

            </div>
            <div class="col-md-12 col xs-12">

                <?=
                $form->field($model, 'description')->widget(TextEditorWidget::className(),
                    [
                    'id' => 'description'.$fid,
                    'clientOptions' => [
                        'lang' => substr(\Yii::$app->language, 0, 2)
                    ]
                ]);
                ?>
            </div>
            <div class="col-md-12 col xs-12">


                <?php
                $options = [
                    'multiple' => true,
                ];
                $items   = $model->getItems();
                
                ?>

                <?=
                $form->field($model, 'configuration')->widget(DualListbox::className(),
                    [
                    'items' => $items,
                    'options' => $options,
                    'clientOptions' => [
                        'moveSelectedLabel' => AmosAdmin::t('amosadmin', 'Assegna'),
                        'removeSelectedLabel' => AmosAdmin::t('amosadmin', 'Rimuovi'),
                        'removeAllLabel' => AmosAdmin::t('amosadmin', 'Rimuovi tutti'),
                        'moveOnSelect' => false,
                        'infoTextEmpty' => AmosAdmin::t('amosadmin', 'Lista vuota'),
                        'moveAllLabel' => AmosAdmin::t('amosadmin', 'Assegna tutti'),
                        'selectedListLabel' => AmosAdmin::t('amosadmin', 'Permessi selezionati'),
                        'nonSelectedListLabel' => AmosAdmin::t('amosadmin', 'Permessi disponibili'),
                    ],
                ]);
                ?>
            </div>
            <div class="col-md-12 col xs-12">
                <?= RequiredFieldsTipWidget::widget(); ?>
                <?=
                CloseSaveButtonWidget::widget([
                    'model' => $model]);
                ?>
            </div>

        </div>
        <div class="clearfix"> </div>
        <?php ActiveForm::end(); ?>

    </div>
</div>
