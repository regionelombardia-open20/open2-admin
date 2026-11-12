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

/**
 * @var $model \open20\amos\admin\models\UserProfile
 * @var $form \open20\amos\core\forms\ActiveForm
 */

use open20\amos\admin\AmosAdmin;
use yii\helpers\Html;

$moduleCommunity = \Yii::$app->getModule('community');
if (\Yii::$app->user->id == $model->user_id && $moduleCommunity && $moduleCommunity->enableMicrosoftTeams) { ?>
    <div class="row m-t-35">
        <div class="col-sm-12 m-t-35">
        <h4 class="spid-title "><?= AmosAdmin::t('amosadmin', "Accedi con account Microsoft") ?></h4>
        </div>
    </div>
    <?php if ($model->hasMicrosoftAccountConnected()) { ?>
        <div class="row m-t-10">

            <div class="col-md-8">
                <div class="content-community-enable-ms-teams">
                    <div class="icon-svg-ms-teams">
                        <?= \open20\amos\community\utilities\CommunityUtil::showIconSvgTeams() ?>
                    </div>
                    <?php echo $form->field($model, 'enable_msgraph')->widget(\kartik\widgets\SwitchInput::className(), [
                        'options' => ['label' => AmosAdmin::t("amosadmin", "Abilita le funzionalità avanzate Microsoft con Open2.0")],
                        'pluginOptions' => [
                            'size' => 'mini',
                            //                    'onText' => AmosIcons::show('check-circle'),
                            //                    'offText' => AmosIcons::show('circle-o')
                            'onText' => 'Si',
                            'offText' => 'No'
                        ],
                    ])->label(false); ?>
                </div>
            </div>
            <div class="col-md-4">
                <?= Html::a(AmosAdmin::t("amosadmin", 'Disconnetti la tua identità digitale'), ['/community/ms-teams/disconnect-profile', 'urlRedirect' => \yii\helpers\Url::current()], [
                    'class' => 'btn btn-secondary',
                    'title' => AmosAdmin::t("amosadmin", 'Disconnetti la tua identità digitale')
                ]) ?>

            </div>
        </div>

    <?php } else { ?>
        <?= \open20\amos\community\widgets\MsTeamsConnectWidget::widget() ?>
    <?php } ?>
<?php } else {
    $isConnected = $model->hasMicrosoftAccountConnected() ?>
    <h4 class="spid-title"><?= AmosAdmin::t('amosadmin', "Accedi con account Microsoft") ?></h4>
    <h5><?= AmosAdmin::t('amosadmin', "Account Microsoft connesso") . ':' ?> <?= \Yii::$app->formatter->asBoolean($isConnected) ?></h5>
    <h5><?= AmosAdmin::t('amosadmin', "Funzionalità avanzate Microsoft abilitate") . ':' ?> <?= \Yii::$app->formatter->asBoolean($model->enable_msgraph) ?></h5>
<?php } ?>