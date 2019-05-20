<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\admin\widgets\graphics
 * @category   CategoryName
 */

namespace lispa\amos\admin\widgets\graphics;

use yii\helpers\Html;
use lispa\amos\core\widget\WidgetGraphic;
use lispa\amos\admin\AmosAdmin;
use lispa\amos\admin\assets\ModuleAdminAsset;
use lispa\amos\admin\models\Admin;
use lispa\amos\admin\models\UserProfile;
use lispa\amos\admin\models\search\UserProfileSearch;

/**
 * Class WidgetGraphicsUsers
 * @package lispa\amos\admin\widgets\graphics
 */
class WidgetGraphicsUsers extends WidgetGraphic {
    /**
     * @var string $widgetTitle
     */
    public $widgetTitle = '';

    /**
     * @var string|array $linkReadAll
     */
    public $linkReadAll = '';

    /**
     * @var int $pageSize
     */
    public $pageSize = 6;

    /**
     * @var int $maxButtonCount
     */
    public $maxButtonCount = 5;
	
    /**
     * @var array $dataProviderViewWidgetConf
     */
	public $dataProviderViewWidgetConf = [];

    /**
     * @inheritdoc
     */
    public function init() {
        $this->widgetTitle = AmosAdmin::tHtml('amosadmin', 'Utenti');
        $this->linkReadAll = ['/admin'];

        parent::init();

        $this->setCode('UTENTI_GRAPHIC');
        $this->setLabel(AmosAdmin::tHtml('amosadmin', 'Utenti'));
        $this->setDescription(AmosAdmin::t('amosadmin', 'Lista utenti'));
    }

    /**
     * @inheritdoc
     */
    public function getHtml() {
        ModuleAdminAsset::register($this->getView());
		$usersList = $this->getDataProvider();
        $viewToRender = '@vendor/lispa/amos-admin/src/widgets/graphics/views/users_list';

        return $this->render($viewToRender, [
			'widget' => $this,
			'searchButtons' => $this->getSearchButtons(),
			'maxButtonCount' => $this->maxButtonCount,
            'toRefreshSectionId' => 'widgetGraphicUsers',
			'dataProviderViewWidgetConf' => [
				'dataProvider' => $usersList,
				'currentView' => [
					'name' => 'icon'
				],
				'iconView' => [
					'itemView' => '_user',
					'options' => [
						'class' => 'list-items-wrapper'
					],
					'containerOptions' => [
						'class' => 'list-items'
					],
					'itemOptions' => [
						'class' => 'list-item'
					]
				]
			]
        ]);
    }

    /**
     * Returns the widget data provider.
     * @return \yii\data\ActiveDataProvider
     */
    protected function getDataProvider() {
		$userProfileSearch = new UserProfileSearch();
        return $userProfileSearch->searchOnceValidatedUsers([], $this->pageSize);
	}
	
	protected function getSearchButtons () {
		if(empty(\Yii::$app->params['serchCompaniesAndUsersBtns'])) {
			return null;
		}

		return [
			'users' =>	
				Html::a(AmosAdmin::t('amosadmin', 'Cerca utenti'), 
					[ '/admin/user-profile/index?enableSearch=1&UserProfileSearch='], [
						'class' => 'btn btn-navigation-primary btn-search-users',
						'title' => AmosAdmin::t('amosadmin', 'Ricerca utenti'),
						'role' 	=> 'button',
					]),
			'companies' => 
				Html::a(AmosAdmin::t('amosadmin', 'Cerca aziende'), 
					['/organizzazioni/profilo?enableSearch=1&ProfiloSearch='], [
						'class' => 'btn btn-navigation-primary btn-search-companies',
						'title' => AmosAdmin::t('amosadmin', 'Ricerca aziende'),
					])
		];
	}
}
