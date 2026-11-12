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

ModuleAdminAsset::register(Yii::$app->view);

/**
 * @var $socialAuthModule \open20\amos\socialauth\Module
 */
$socialAuthModule = Yii::$app->getModule('socialauth');

//change social url

$urlSocial = ($type == 'login') ? '/socialauth/social-auth/sign-in?provider=' : '/socialauth/social-auth/sign-up?provider=';

if(empty($communityId)) {
    $communityId = \Yii::$app->request->get('community');
}
$paramCommunity = '';
if($communityId){
    $paramCommunity = '&community='.$communityId;
}


?>
<?= Html::tag('h2', ($type == 'login') ? AmosAdmin::t('amosadmin', '#social_title_login') : AmosAdmin::t('amosadmin', '#social_title_register'), ['class' => 'title-login']) ?>
<?= Html::tag('h3', ($type == 'login') ? AmosAdmin::t('amosadmin', '#social_subtitle_login') : AmosAdmin::t('amosadmin', '#social_subtitle_register'), ['class' => 'subtitle-login']) ?>
<div class="social-buttons col-xs-12 nop">
    <?php
    foreach ($socialAuthModule->providers as $name => $config) :
        ?>
        <div class="col-xs-12 nop">
            <a
                    class="btn btn-<?= strtolower($name); ?> social-link"
                    title="<?= ($type == 'login') ? AmosAdmin::t('amosadmin', '#login_with_social') : AmosAdmin::t('amosadmin', '#register_with_social') ?> <?= $name; ?>"
                    target="_self"
                    href="<?= Yii::$app->urlManager->createAbsoluteUrl($urlSocial . strtolower($name).$paramCommunity); ?>"
            >
                <span class="am am-<?= strtolower($name); ?>"></span>
                <span class="text"><?= $name; ?></span>
            </a>
        </div>
    <?php endforeach; ?>
</div>
