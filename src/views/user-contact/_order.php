<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\views\user-profile
 * @category   CategoryName
 */

use open20\amos\admin\AmosAdmin;
use yii\helpers\Html;
use kartik\select2\Select2;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var \open20\amos\admin\models\search\UserProfileSearch $model
 * @var AmosAdmin $adminModule
 * @var yii\widgets\ActiveForm $form
 */


// Remove last online from order fields if module parameter 'enableOnlineUsers' is false
if (isset(Yii::$app->controller->module->params['orderParams']['user-profile'])) {
    $orderParams = Yii::$app->controller->module->params['orderParams']['user-profile'];
    if (!$adminModule->enableOnlineUsers && !empty($orderParams) && $orderParams['enable'] && in_array('user_profile.last_online', $orderParams['fields'])) {
        unset($orderParams['fields'][array_search('user_profile.last_online', $orderParams['fields'])]);
        $model->setOrderAttributes($orderParams['fields']);
    }
}

?>

<div class="user-profile-order element-to-toggle" data-toggle-element="form-order">
    <div class="col-xs-12">
        <p class="h3"><?= AmosAdmin::t('amosadmin', 'Order by') ?>:</p>
    </div>
       
    <?= Html::hiddenInput("currentView", Yii::$app->request->getQueryParam('currentView')); ?>

    <div class="col-sm-6 col-lg-4">  
        <?= Html::label($model->getAttributeLabel('orderAttribute'), 'orderAttribute') ?>
        <?= Select2::widget([
                'name' => 'orderAttribute',
                'value' => Yii::$app->request->post('orderAttribute',null),
                'data' => $model->getOrderAttributesLabels(),               
                'options' => [
                    'multiple' => false, 
                    'placeholder' => AmosAdmin::t('amosadmin', 'Select/Choose') . '...',
                ],
                'pluginOptions' => [
                    'allowClear' => true
                ],         
            ])
        ?>
        
    </div>
    <div class="col-sm-6 col-lg-4"> 
        <?= Html::label($model->getAttributeLabel('orderType'), 'orderType') ?>
        <?= Select2::widget([
                'name' => 'orderType',
                'value' => Yii::$app->request->post('orderType',null),
                'data' => [
                    SORT_ASC => AmosAdmin::t('amosadmin', 'Ascending'),
                    SORT_DESC => AmosAdmin::t('amosadmin', 'Descending')
                ],
                'language' => 'it',
                'options' => [
                    'multiple' => false,                
                ],
                'pluginOptions' => [
                    'allowClear' => false
                ],         
            ])
        ?>        
    </div>

    <div class="col-xs-12">
        <div class="pull-right">
            <?= Html::a(AmosAdmin::t('amosadmin', 'Cancel'), Url::current(), ['class' => 'btn btn-secondary']) ?>
            <?= Html::submitButton(AmosAdmin::t('amosadmin', 'Order'), ['class' => 'btn btn-outline-tertiary']) ?>
        </div>
    </div>

    <div class="clearfix"></div>
    
</div>
