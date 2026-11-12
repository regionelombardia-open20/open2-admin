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

use open20\amos\admin\AmosAdmin;
use open20\amos\core\helpers\Html;
use open20\amos\admin\assets\ModuleAdminAsset;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use open20\amos\core\icons\AmosIcons;
use yii\helpers\Url;

ModuleAdminAsset::register(Yii::$app->view);

/** @var $socialAuthModule \open20\amos\socialauth\Module */
$socialAuthModule = \open20\amos\socialauth\Module::getInstance();
?>
<?= Html::tag('h5', AmosAdmin::t('amosadmin', '#fullsize_spid'), ['class' => 'title-login']) ?>
<div class="m-t-20 m-b-30">
    <?=
    Html::a(
        //AmosIcons::show('account-circle') . AmosAdmin::t('amosadmin', $socialAuthModule->shibbolethConfig['buttonLabel']),
        AmosAdmin::t('amosadmin', $socialAuthModule->shibbolethConfig['buttonLabel']),
        Url::to("/{$socialAuthModule->id}/shibboleth/endpoint", 'https'),
        [
            'class' => 'btn btn-spid btn-block btn-lg',
            'title' => AmosAdmin::t('amosadmin', $socialAuthModule->shibbolethConfig['buttonLabel']),
            //'target' => '_blank'
        ]
    )
    ?>
</div>
<!--<div class="col-xs-12 nop">
    <p class="spid-text">< ?= AmosAdmin::t('amosadmin', $socialAuthModule->shibbolethConfig['buttonDescription']) ?></p>
</div>-->
