<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos\admin\widgets\graphics
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

namespace open20\amos\admin\widgets\graphics;

use yii\helpers\Html;
use open20\amos\core\widget\WidgetGraphic;
use open20\amos\admin\AmosAdmin;
use open20\amos\admin\assets\ModuleAdminAsset;
use open20\amos\admin\models\Admin;
use open20\amos\admin\models\UserProfile;
use open20\amos\admin\models\search\UserProfileSearch;

/**
 * Class WidgetGraphicsUsers
 * @package open20\amos\admin\widgets\graphics
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
        $viewToRender = '@vendor/open20/amos-admin/src/widgets/graphics/views/users_list';

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
					[ '/'.AmosAdmin::getModuleName().'/user-profile/index?enableSearch=1&UserProfileSearch%5B%5D='], [
						'class' => 'btn btn-navigation-primary btn-search-users',
						'title' => AmosAdmin::t('amosadmin', 'Ricerca utenti'),
						'role' 	=> 'button',
					]),
			'companies' => 
				Html::a(AmosAdmin::t('amosadmin', 'Cerca aziende'), 
					['/organizzazioni/profilo/index?enableSearch=1&ProfiloSearch%5B%5D='], [
						'class' => 'btn btn-navigation-primary btn-search-companies',
						'title' => AmosAdmin::t('amosadmin', 'Ricerca aziende'),
					])
		];
	}
}
