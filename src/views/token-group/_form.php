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
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use open20\amos\core\forms\Tabs;
use open20\amos\core\forms\CloseSaveButtonWidget;
use yii\helpers\Url;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/**
 * @var yii\web\View $this
 * @var open20\amos\admin\models\TokenGroup $model
 * @var yii\widgets\ActiveForm $form
 */


?>

<div class="token-group-form col-xs-12 nop">

    <?php $form = ActiveForm::begin(); ?>




    <?php $this->beginBlock('general'); ?>

    <div class="col-lg-6 col-sm-6">
        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-lg-6 col-sm-6">
        <?= $form->field($model, 'string_code')->textInput(['maxlength' => true]) ?>
    </div>

    <div class="col-lg-12 col-sm-12">
        <?= $form->field($model, 'Description')->textarea(['rows' => 2, 'maxlength' => true]) ?>
    </div>

    <div class="col-lg- col-sm-6">
        <?= $form->field($model, 'url_redirect')->textInput(['maxlength' => true]) ?>
    </div>

    <div class="col-lg-4 col-sm-4">
        <?= $form->field($model, 'expire_date')->widget(DateControl::className(),[
            'type' => DateControl::FORMAT_DATETIME
        ]) ?>
    </div>

    <div class="col-lg-2 col-sm-2 m-t-20">
        <?= $form->field($model, 'consumable')->checkbox() ?>
    </div>

    <div class="col-lg-8 col-sm-8">
        <?= $form->field($model, 'target_class')->textInput(['maxlength' => true]) ?>
    </div>

    <div class="col-lg-4 col-sm-4">
        <?= $form->field($model, 'target_id')->textInput() ?>
    </div>

    <?php if(!$model->isNewRecord){ ?>
        <div class="col-lg-12 col-sm-12">
            <?= \open20\amos\core\views\AmosGridView::widget([
                    'dataProvider' => $dataProvider,
                'columns' => [
                        'user.userProfile.nomeCognome',
                        'token',
                        [
                            'attribute' => 'created_at',
                            'format' => 'date'
                        ]
                ]
            ])?>
        </div>
    <?php } ?>




    <div class="clearfix"></div>
    <?php $this->endBlock('general'); ?>

    <?php $itemsTab[] = [
        'label' => Yii::t('cruds', 'general'),
        'content' => $this->blocks['general'],
    ];
    ?>

    <?= Tabs::widget(
        [
            'encodeLabels' => false,
            'items' => $itemsTab
        ]
    );
    ?>
    <?= CloseSaveButtonWidget::widget(['model' => $model]); ?>
    <?php ActiveForm::end(); ?>
</div>
