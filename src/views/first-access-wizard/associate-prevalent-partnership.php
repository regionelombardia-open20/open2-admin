<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\views\first-access-wizard
 * @category   CategoryName
 */

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\interfaces\OrganizationsModuleInterface;
use open20\amos\admin\models\UserProfile;
use open20\amos\core\forms\editors\m2mWidget\M2MWidget;

/**
 * @var \yii\web\View $this
 * @var \open20\amos\admin\models\UserProfile $model
 */

$this->registerJs($js);

$this->title = AmosAdmin::t('amosadmin', 'Select prevalent partnership');

$admin =  AmosAdmin::getInstance();
/** @var  $organizationsModule OrganizationsModuleInterface*/
$organizationsModule = \Yii::$app->getModule($admin->getOrganizationModuleName());
?>

<?php if (is_null($organizationsModule)): ?>
    <?= AmosAdmin::t('amosadmin', 'Module organizations not installed') ?>
<?php else: ?>
    <?php
    $facilitatorUserIds = Yii::$app->authManager->getUserIdsByRole('FACILITATOR');
    $organizationModel = $organizationsModule->getOrganizationModelClass();
    /** @var \yii\db\ActiveQuery $query */
    $query = $organizationsModule->getOrganizationsListQuery();
    $post = Yii::$app->request->post();
    if (isset($post['genericSearch'])) {
        $query->andFilterWhere(['or',
            ['like', $organizationModel::tableName() . '.name', $post['genericSearch']],
        ]);
    }
    ?>
    <?php if (!\Yii::$app->request->isAjax): ?>
        <h4><?= AmosAdmin::t('amosadmin', '#faw_ass_prev_part_text') ?></h4>
    <?php endif; ?>
    <?= M2MWidget::widget([
        'model' => $model,
        'modelId' => $model->id,
        'modelData' => UserProfile::find()->andWhere(['id' => $model->prevalent_partnership_id]),
        'modelDataArrFromTo' => [
            'from' => 'id',
            'to' => 'id'
        ],
        'modelTargetSearch' => [
            'class' => $organizationModel::className(),
            'query' => $query,
        ],
        'viewSearch' => (isset($viewM2MWidgetGenericSearch) ? $viewM2MWidgetGenericSearch : false),
        'multipleSelection' => false,
        'relationAttributesArray' => ['status', 'role'],
        'moduleClassName' => AmosAdmin::className(),
        'postName' => 'UserProfile',
        'postKey' => 'user',
        'targetUrlController' => 'first-access-wizard',
        'targetUrlParams' => [
            'viewM2MWidgetGenericSearch' => true
        ],
        'showSpinner' => true,
        'targetColumnsToView' => [
            'logo_id' => [
                'headerOptions' => [
                    'id' => AmosAdmin::t('amosadmin', 'Logo'),
                ],
                'contentOptions' => [
                    'headers' => AmosAdmin::t('amosadmin', 'Logo'),
                ],
                'label' => AmosAdmin::t('amosadmin', 'Logo'),
                'format' => 'raw',
                'value' => function ($model) {
                    $admin =  AmosAdmin::getInstance();
                    /** @var  $organizationsModule OrganizationsModuleInterface*/
                    $organizationsModule = \Yii::$app->getModule($admin->getOrganizationModuleName());
                    $widgetClass = $organizationsModule->getOrganizationCardWidgetClass();
                    return $widgetClass::widget(['model' => $model]);
                }
            ],
            'name' =>[
                'label' => AmosAdmin::t('amosadmin', '#name'),
                'format' => 'raw',
                'value' => function ($model) {
                    return $model->getDescription(false);
                }
            ]
        ]
    ]); ?>
<?php endif; ?>
