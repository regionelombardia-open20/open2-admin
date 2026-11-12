<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos\admin\widgets\graphics\views
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

/**
 * @var View $this
 * @var ActiveDataProvider $usersList
 * @var WidgetGraphicsUltimeNews $widget
 * @var string $toRefreshSectionId
 * @var \open20\amos\admin\models\UserProfile $model
 * @var yii\data\ActiveDataProvider $dataProvider
 */

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\models\search\UserProfileSearch;
use open20\amos\admin\widgets\graphics\WidgetGraphicsUsers;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\Pjax;
use yii\widgets\LinkPager;
use open20\amos\admin\assets\ModuleAdminAsset;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\views\DataProviderView;
use open20\amos\core\forms\WidgetGraphicsActions;

ModuleAdminAsset::register($this);
$moduleAdmin = \Yii::$app->getModule(AmosAdmin::getModuleName());
?>

<div class="box-widget-header">
	<?php
	if (isset($moduleAdmin) && !$moduleAdmin->hideWidgetGraphicsActions) {
		echo WidgetGraphicsActions::widget([
			'widget' => $widget,
			'tClassName' => AmosAdmin::className(),
			'actionRoute' => '/'.AmosAdmin::getModuleName().'/admin/create',
			'toRefreshSectionId' => $toRefreshSectionId
		]);
	} ?>

	<div class="box-widget-wrapper">
		<h2 class="box-widget-title">
			<?= AmosIcons::show('user', ['class' => 'am-2'], AmosIcons::IC); ?>
			<?= AmosAdmin::tHtml('amosadmin', 'Utenti'); ?>
		</h2>
	</div>

	<div class="read-all">
		<?php 
			$textReadAll = AmosAdmin::t('amosadmin', '#showAll') . AmosIcons::show('chevron-right'); 
			$linkReadAll = ['/admin']; 
			echo Html::a($textReadAll, $linkReadAll, ['class' => '']); 
		?>
	</div>
</div>

<div class="box-widget latest-users">
	<section>
	<?php   
		$usersList = $dataProviderViewWidgetConf['dataProvider'];
		if (count($usersList->getModels()) == 0): ?>
			<div class="list-items list-empty">
				<h3>
					<?= AmosAdmin::t('amosadmin', 'Nessun utente') ?>
				</h3>
			</div>
		<?php
		else:  
			if($searchButtons!= null) : ?>
				<div class="search-buttons">
			<?php	foreach($searchButtons as $button) {
						echo $button;
					} 
			?>	</div> 
			<?php	
			endif;
			Pjax::begin(['id' => $toRefreshSectionId, 'timeout' => 10000]);
				$pagination = $usersList->getPagination();
				echo DataProviderView::widget($dataProviderViewWidgetConf);
				echo LinkPager::widget([
					'pagination' => $pagination,
					'maxButtonCount' => $maxButtonCount,
					'options' => [
						'class' => 'pagination shortPager',
					]
				]);
			Pjax::end();
		endif; ?>
	</section>
</div>