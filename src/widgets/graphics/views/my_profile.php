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

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\widgets\graphics\WidgetGraphicMyProfile;
use open20\amos\core\helpers\Html;

/**
 * @var \yii\web\View $this
 * @var WidgetGraphicMyProfile $widget
 * @var \open20\amos\admin\models\UserProfile $userProfile
 */

?>

<div class="grid-item">
    <div class="box-widget myprofile">
        <div class="box-widget-toolbar dark-toolbar row nom">
            <h2 class="box-widget-title col-xs-10 nop"><?= AmosAdmin::t('amosadmin', 'Il mio profilo') ?></h2>
        </div>
        <section><h2 class="sr-only"><?= AmosAdmin::t('amosadmin', 'Il mio profilo') ?></h2>
            <div role="listbox">
                <div class="widget-listbox-option row list-items" role="option">
                    <article class="col-xs-12 nop">
                        <div class="icon-admin-wgt">
                            <span class="pull-left">
                                <?= Html::a(
                                    $widget->getUserProfileRoundImage(),
                                    ['/'.AmosAdmin::getModuleName().'/user-profile/update', 'id' => $userProfile->id],
                                    ['title' => AmosAdmin::t('amosadmin', '#go_to_your_profile'), 'class' => 'container-square-img-sm']
                                ) ?>
                            </span>
                        </div>
                        <div class="text-admin-wgt">
                            <h3 class="box-widget-subtitle"><?= $userProfile->nomeCognome ?></h3>
                            <p class="box-widget-text"><?= $widget->getBoxWidgetText() ?></p>
                        </div>
                        <div class="clearfix"></div>
                    </article>
                </div>
            </div>
        </section>
    </div>
</div>
