<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos\admin\views\user-contact
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
use open20\amos\admin\models\UserProfile;
use open20\amos\core\forms\editors\m2mWidget\M2MWidget;
use open20\amos\core\helpers\Html;

/**
 * @var \open20\amos\admin\models\UserProfile $model
 */
$this->title = $this->params['titleSection'] ?? ''; 

$this->params['breadcrumbs'][] = AmosAdmin::t('amosadmin', 'Associa contatti');
$this->params['breadcrumbs'][] = $this->title;
$this->params['forceBreadcrumbs'][] = [
    'label' => AmosAdmin::t('amosadmin', "Users"),
    'url' => '/' .AmosAdmin::getModuleName() . '/user-profile/index',
    'route' => '/' .AmosAdmin::getModuleName() . '/user-profile/index',
];
$this->params['forceBreadcrumbs'][] = [
    'label' => $this->title,
];

$userProfileId = Yii::$app->request->get("id");
$model = UserProfile::findOne($userProfileId);

/**
 * @var \yii\db\ActiveQuery $query UserProfiles to invite or with pending invitation
 */
$query = $model->getUserNetworkAssociationQuery();
$query->leftJoin('user u','u.id = user_profile.user_id');

$post = Yii::$app->request->post();

if(!empty($post['orderAttribute']) && !empty($post['orderType'])){
    if($post['orderAttribute'] == 'surnameName'){
        $query->orderBy([
            'user_profile.cognome' => $post['orderType'] == 3 ? SORT_DESC : SORT_ASC,
            'user_profile.nome' => $post['orderType'] == 3 ? SORT_DESC : SORT_ASC,
        ]);
    }elseif($post['orderAttribute'] == 'prevalentPartnership'){
        $query->orderBy([
            'user_profile.prevalent_partnership_id' => $post['orderType'] == 3 ? SORT_DESC : SORT_ASC,        
        ]);
    }else{
        $query->orderBy([
            $post['orderAttribute'] => $post['orderType'] == 3 ? SORT_DESC : SORT_ASC,     
        ]);
    }
}else{
    $query->orderBy([
        'user_profile.cognome' => SORT_ASC,
        'user_profile.nome' => SORT_ASC,
    ]);
}

if (empty($post['enableSearch']) && !empty($post['genericSearch']) ) {
    $searchName = $post['genericSearch'];
    $query->andFilterWhere(['or',
        ['like', 'user_profile.nome',$searchName],
        ['like', 'user_profile.cognome', $searchName],
        ['like', "CONCAT( user_profile.nome , ' ', user_profile.cognome )", $searchName],
        ['like', "CONCAT( user_profile.cognome , ' ', user_profile.nome )", $searchName]
    ]);
}

if(!empty($post['enableSearch']) && !empty($post['UserProfileSearch'])){
    $query->andFilterWhere(['like', 'u.username',$post['UserProfileSearch']['username']]);
    $query->andFilterWhere(['like', 'u.email',$post['UserProfileSearch']['email']]);
    $query->andFilterWhere(['user_profile.prevalent_partnership_id'=>$post['UserProfileSearch']['prevalent_partnership_id']]);
}

?>

<?= M2MWidget::widget([
    'pjaxContainer' => true,
    'model' => $model,
    'modelId' => $model->id,
    'modelData' => $query,
    'modelDataArrFromTo' => [
        'from' => 'id',
        'to' => 'id'
    ],
    'modelTargetSearch' => [
        'class' => AmosAdmin::instance()->createModel('UserProfileSearch')->className(),
        'query' => $query,
    ],
    'targetFooterButtons' => Html::a(AmosAdmin::t('amosadmin', 'Close'), Yii::$app->urlManager->createUrl([
        '/'.AmosAdmin::getModuleName().'/user-contact/annulla-m2m',
        'id' => $userProfileId
    ]), ['class' => 'btn btn-secondary', 'AmosAdmin' => Yii::t('amosadmin', 'Close')]),
    'renderTargetCheckbox' => false,
    'viewSearch' => (isset($viewM2MWidgetGenericSearch) ? $viewM2MWidgetGenericSearch : false),
    'hideInlineSearch' => true,
    'targetUrlController' => 'user-contact',
    'targetActionColumnsTemplate' => '{googleContact}{connect}',
    'moduleClassName' => \open20\amos\admin\AmosAdmin::className(),
    'postName' => 'UserContact',
    'postKey' => 'user-contact',
    'iconView' => '@vendor/open20/amos-admin/src/views/user-profile/_icon',
    'listView' => '@vendor/open20/amos-admin/src/views/user-profile/_item',
    'currentView' => 'icon',
    'advancedSearch' => true,
    'viewFilter' => '@vendor/open20/amos-admin/src/views/user-contact/_search',
    'viewOrder' => '@vendor/open20/amos-admin/src/views/user-contact/_order',
    'targetColumnsToView' => [
        'photo' => [
            'headerOptions' => [
                'id' => AmosAdmin::t('amosadmin', 'Photo'),
            ],
            'contentOptions' => [
                'headers' => AmosAdmin::t('amosadmin', 'Photo'),
            ],
            'label' => AmosAdmin::t('amosadmin', 'Photo'),
            'format' => 'raw',
            'value' => function ($model) {
                /** @var UserProfile $model */
                return \open20\amos\admin\widgets\UserCardWidget::widget(['model' => $model, 'onlyAvatar'=> true]);
            }
        ],
        'name' => [
            'attribute' => 'surnameName',
            'headerOptions' => [
                'id' => AmosAdmin::t('amosadmin', 'Name'),
            ],
            'contentOptions' => [
                'headers' => AmosAdmin::t('amosadmin', 'Name'),
            ],
            'label' => AmosAdmin::t('amosadmin', 'Name'),
            'value' => function($model){
                /** @var UserProfile $model */
                return Html::a($model->surnameName, ['/'.AmosAdmin::getModuleName().'/user-profile/view', 'id' => $model->id ], [
                    'title' => AmosAdmin::t('amosnews', 'Apri il profilo di {nome_profilo}', ['nome_profilo' => $model->surnameName])
                ]);
            },
            'format' => 'html'
        ],
        [
            'class' => 'open20\amos\core\views\grid\ActionColumn',
            'template' => '{googleContact}{connect}',
            'buttons' => [
                'googleContact' => function($url, $model){
                    /** @var UserProfile $model */
                    return \open20\amos\admin\widgets\GoogleContactWidget::widget(['model' => $model]).'&nbsp;';
                },
                'connect' =>  function ($url, $model) {
                    /** @var UserProfile $model */
                    return \open20\amos\admin\widgets\ConnectToUserWidget::widget([ 'model' => $model, 'isGridView' => true ]);
                }
            ]
        ]
    ],
]);
?>
