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
use open20\amos\admin\base\ConfigurationManager;
use open20\amos\admin\utility\UserProfileUtility;
use open20\amos\core\helpers\Html;
use open20\amos\core\utilities\FormUtility;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var open20\amos\admin\models\search\UserProfileSearch $model
 * @var yii\widgets\ActiveForm $form
 */
/** @var \open20\amos\admin\controllers\UserProfileController $appController */
$appController = new \open20\amos\admin\controllers\UserProfileController('user-profile', Yii::$app);

/** @var AmosAdmin $adminModule */
$adminModule = AmosAdmin::instance();

$enableAutoOpenSearchPanel = Yii::$app->request->post('enableSearch',0);
?>

<div class="element-to-toggle <?= $enableAutoOpenSearchPanel ? 'toggleIn' : '' ?>" data-toggle-element="form-search">
    <div class="col-xs-12"><p class="h3"><?= AmosAdmin::t('amosadmin', 'Search by') ?>:</p></div>

    <?= Html::hiddenInput("enableSearch", $enableAutoOpenSearchPanel); ?>
    <?= Html::hiddenInput("currentView", Yii::$app->request->getQueryParam('currentView')); ?>
   

    
    <div class="col-sm-6 col-lg-3 hide">
        <?php Html::input('text', Html::getInputName($model, 'nome'), $model->nome, [
                'placeholder' => AmosAdmin::t('amosadmin', 'Search by name'),
                'class' => 'form-control' // Aggiungi le classi CSS se necessarie
            ]); 
        ?>
    </div>

    <div class="col-sm-6 col-lg-3 hide">
        <?php Html::input('text', Html::getInputName($model, 'cognome'), $model->cognome, [
                'placeholder' => AmosAdmin::t('amosadmin', 'Search by surname'),
                'class' => 'form-control' // Aggiungi le classi CSS se necessarie
            ]); 
        ?>       
    </div>

    <div class="col-sm-6 col-lg-4">
        <?= Html::activeLabel($model, 'username') ?>
        <?= Html::input('text', Html::getInputName($model, 'username'), Yii::$app->request->post('UserProfileSearch') ? Yii::$app->request->post('UserProfileSearch')['username'] ?? null : null, [
                'placeholder' => AmosAdmin::t('amosadmin', 'Search by username'),
                'class' => 'form-control' // Aggiungi le classi CSS se necessarie
            ]); 
        ?>       
    </div>

    <div class="col-sm-6 col-lg-4">
        <?= Html::activeLabel($model, 'email') ?>
        <?= Html::input('text', Html::getInputName($model, 'email'), Yii::$app->request->post('UserProfileSearch') ? Yii::$app->request->post('UserProfileSearch')['email'] ?? null : null, [
                'placeholder' => AmosAdmin::t('amosadmin', 'Search by email'),
                'class' => 'form-control' // Aggiungi le classi CSS se necessarie
            ]); 
        ?>       
    </div>
    
    <div class="col-sm-6 col-lg-4">
        <?= Html::label($model->getAttributeLabel('prevalentPartnership'), 'prevalent_partnership_id') ?>
        <?= Html::dropDownList(Html::getInputName($model, 'prevalent_partnership_id'), Yii::$app->request->post('UserProfileSearch') ? Yii::$app->request->post('UserProfileSearch')['prevalent_partnership_id'] ?? null : null, $appController->getAllOrganizationsForSelect(), ['prompt' => AmosAdmin::t('amosadmin', 'Select/Choose') . '...','class' => 'form-control']); ?>         
    </div>
    
 
    <div class="col-xs-12 m-t-35">
        <div class="pull-right">
            <?= Html::a(
                AmosAdmin::t('amosadmin', 'Reset'),
                Url::current(),
                ['class' => 'btn btn-secondary'])
            ?>
            <?= Html::submitButton(AmosAdmin::t('amosadmin', 'Filtra'), ['class' => 'btn btn-outline-tertiary', 'onclick'=>'
                    $("input[name=\"genericSearch\"]").val("");
                    $(".element-to-toggle[data-toggle-element=\"form-search\"]").find("input[name=\"enableSearch\"]").val(1);              
             ']) ?>
        </div>
    </div>

    <div class="clearfix"></div>

</div>