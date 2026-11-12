<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos\admin\views\user-profile\boxes
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
use open20\amos\admin\base\ConfigurationManager;
use open20\amos\core\icons\AmosIcons;

/**
 * @var yii\web\View $this
 * @var open20\amos\core\forms\ActiveForm $form
 * @var open20\amos\admin\models\UserProfile $model
 * @var open20\amos\core\user\User $user
 */

/** @var AmosAdmin $adminModule */
$adminModule = Yii::$app->controller->module;

$linkPrivacy = '@app/views/site/privacy';

if (isset(\Yii::$app->params['linkConfigurations']['privacyPolicyLinkCommon'])) {
    $linkPrivacy = \Yii::$app->params['linkConfigurations']['privacyPolicyLinkCommon'];
}

?>

<section class="section-data">
    <?php if ($adminModule->confManager->isVisibleField('privacy', ConfigurationManager::VIEW_TYPE_FORM)) : ?>
        <div>
            <?php if (!isset(\Yii::$app->params['linkConfigurations']['privacyPolicyLinkCommon'])) : ?>
                <div>
                    <?= $form->field($model, 'privacy')->label('<a data-toggle="modal" href="#" data-target="#modalPrivacy">Visualizza e accetta il documento della privacy</a>')->checkbox() ?>
                </div>
            <?php else : ?>
                <div>
                    <a href="<?= $linkPrivacy ?>" target='_blank' title="<?= AmosAdmin::t('amosadmin', 'Visualizza il documento della privacy in nuova finestra') ?>">
                        <?= AmosAdmin::t('amosadmin', 'Visualizza il documento della privacy') . ' ' . AmosIcons::show('square-right') ?>
                    </a>
                    <?= $form->field($model, 'privacy')->hiddenInput(['value' => 1]); ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</section>

<?php if (!isset(\Yii::$app->params['linkConfigurations']['privacyPolicyLinkCommon'])) : ?>
<!-- Modal -->
<div class="modal fade" id="modalPrivacy" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-lg">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?= AmosAdmin::t('amosadmin', '#privacy_label') ?></h4>
            </div>
            <div class="modal-body">
                <?= $this->render($linkPrivacy); ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?= AmosAdmin::t('amosadmin', '#close') ?></button>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
