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
use open20\amos\admin\widgets\UserCardWidget;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\views\DataProviderView;

/**
 * @var yii\web\View $this
 * @var \open20\amos\admin\models\UserProfile $model
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var string $currentView
 * @var string $fromAction
 */

$fromAction = (isset($fromAction) ? $fromAction : 'operators');

/** @var \open20\amos\admin\controllers\UserProfileController $appController */
$appController = Yii::$app->controller;
$adminModule = AmosAdmin::instance();

?>
<div class="user-profile-index">
    <?= $this->render(
        '_search',
        [
            'model' => $model,
            'originAction' => $appController->action->id
        ]);
    ?>
    <?php
    // $this->render('_order', ['model' => $model]);
    ?>

    <?php
    $columns = [];
    $columns['userProfileImage'] = [
        'label' => $model->getAttributeLabel('userProfileImage'),
        'format' => 'raw',
        'value' => function ($model) {
            /** @var \open20\amos\admin\models\UserProfile $model */
            return UserCardWidget::widget(['model' => $model, 'avatarDimension' => 'table_small']);
        }
    ];



    //    $columns[] = 'cognome';
    $columns['nominativo'] = [
        'label' => 'Nominativo',
        'attribute' => 'nominativo',
        'format' => 'raw',
        'value' => function ($model) {

            return  '<strong>' . $model->cognome . ' ' . $model->nome . '</strong>';
        }
    ];
    $columns['profiles'] = [
        'label' => 'Profilo',
        'format' => 'raw',
        'value' => function ($model) {
            $upc = \open20\amos\admin\models\UserProfileClassesUserMm::findOne(['user_id' => $model->user_id]);
            if (empty($upc)) return '';
            $profile = $upc->userProfileClasses;
            if (empty($profile)) return '';
            return $profile->name;
        }
    ];

    switch ($fromAction) {
        case 'inactive-users':
            $columns['user.email'] = [
                'attribute' => 'user.email',
                'label' => AmosAdmin::t('amosadmin', 'Email')
            ];

            if ($adminModule->confManager->isVisibleBox('box_prevalent_partnership', ConfigurationManager::VIEW_TYPE_FORM) &&
                $adminModule->confManager->isVisibleField('prevalent_partnership_id', ConfigurationManager::VIEW_TYPE_FORM)) {
                $columns['prevalentPartnership.name'] = [
                    'attribute' => 'prevalentPartnership.name',
                    'label' => $model->getAttributeLabel('prevalentPartnership')
                ];
            }

            $columns[] = [
                'attribute' => 'updated_at',
                'value' => function ($model) {
                    /** @var \open20\amos\admin\models\UserProfile $model */
                    return Yii::$app->formatter->asDatetime($model->updated_at);
                }
            ];
            break;
        default:
            if ($adminModule->confManager->isVisibleBox('box_prevalent_partnership', ConfigurationManager::VIEW_TYPE_FORM) &&
                $adminModule->confManager->isVisibleField('prevalent_partnership_id', ConfigurationManager::VIEW_TYPE_FORM)) {
                $columns['prevalentPartnership.name'] = [
                    'attribute' => 'prevalentPartnership.name',
                    'label' => $model->getAttributeLabel('prevalentPartnership')
                ];
            }

            if ($adminModule->confManager->isVisibleBox('box_facilitatori', ConfigurationManager::VIEW_TYPE_FORM)) {
                $columns['facilitatore.nomeCognome'] = [
                    'attribute' => 'facilitatore.nomeCognome',
                    'label' => $model->getAttributeLabel('facilitatore')
                ];
            }
            if ($adminModule->confManager->isVisibleBox('box_facilitatori', ConfigurationManager::VIEW_TYPE_FORM)) {
                $columns['facilitatore.nomeCognome'] = [
                    'attribute' => 'facilitatore.nomeCognome',
                    'label' => $model->getAttributeLabel('facilitatore')
                ];
            }
            if (!$adminModule->bypassWorkflow && !$adminModule->completeBypassWorkflow) {
                $columns[] = [
                    'attribute' => 'status',
                    'value' => function ($model) {
                        /** @var \open20\amos\admin\models\UserProfile $model */
                        return $model->hasWorkflowStatus() ? AmosAdmin::t('amosadmin', $model->getWorkflowStatus()->getLabel()) : '-';
                    }
                ];
            }
            break;
    }

    $columns[] = [
        'class' => 'open20\amos\core\views\grid\ActionColumn',
        'template' => '{update} {registra} {impersonate}',
        'buttons' => [
            'registra' => function ($url, $model) {
                /** @var \open20\amos\admin\models\UserProfile $model */
                $utente = Yii::$app->getUser();
                if ($utente->can('REGISTRAZIONE_ACCESSI')) {
                    return Html::a(AmosIcons::show(
                            'timer',
                            ['class' => 'btn btn-tool-secondary bk-btnPagine'])
                        . '<span class="sr-only">'
                        . AmosAdmin::t('amosadmin', 'Registra l\'accesso al servizio di facilitazione')
                        . '</span>', Yii::$app->urlManager->createUrl([
                        '/puntopei/pei-accessi-servizi-facilitazione/create',
                        'user_profile_id' => $model->id
                    ]),
                        [
                            'title' => AmosAdmin::t('amosadmin', 'Registra l\'accesso al servizio di facilitazione')
                        ]);
                } else {
                    return '';
                }
            },
            'impersonate' => function ($url, $model) {
                /** @var \open20\amos\admin\models\UserProfile $model */
                $utente = Yii::$app->getUser();
                if ($utente->can('ADMIN')) {
                    return Html::a('Impersona', Yii::$app->urlManager->createUrl([
                        '/amosadmin/security/impersonate',
                        'user_id' => $model->user_id
                    ]),
                        [
                            'title' => AmosAdmin::t('amosadmin', 'Impersona questo utente')
                        ]);
                } else {
                    return '';
                }
            }
        ]
    ];

    if(!Yii::$app->request->get('currentView') || (Yii::$app->request->get('currentView')=='icon')){
        $dataProvider->pagination= [
            'pageSize' => 21,
        ];
    }


    $dataProviderViewWidgetConf = [
        'dataProvider' => $dataProvider,
        //'filterModel' => $model,
        'currentView' => $currentView,
        'iconView' => [
            'itemView' => '_icon'
        ],
        'listView' => [
            'itemView' => '_item'
        ],
        'gridView' => [
            'columns' => $columns
        ],

        /* 'mapView' => [
            'itemView' => '_map',
            'markerConfig' => [
                'lat' => 'domicilio_lat',
                'lng' => 'domicilio_lon',
                'icon' => 'iconaMarker'
            ],
        ]*/
    ];

    // View export button only if the logged user have the ADMIN role.
    //    if (Yii::$app->user->can('ADMIN') || Yii::$app->user->can('AMMINISTRATORE_UTENTI')) {
    //        $dataProviderViewWidgetConf['exportConfig'] = [
    //            'exportEnabled' => true,
    //            'exportColumns' => $appController->getExportColumns($model)
    //        ];
    //    }
    ?>

    <?= DataProviderView::widget($dataProviderViewWidgetConf); ?>
</div>
