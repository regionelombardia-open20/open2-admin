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
use open20\amos\admin\models\UserProfileClasses;

/**
 * @var yii\web\View $this
 * @var open20\amos\core\forms\ActiveForm $form
 * @var open20\amos\admin\models\UserProfile $model
 * @var open20\amos\core\user\User $user
 */
/** @var AmosAdmin $adminModule */
$adminModule = Yii::$app->controller->module;
?>

<section class="section-data">

    <div>
        <?php if ($manage_profile) { ?>
            <div>
                <a href="/<?= AmosAdmin::getModuleName() ?>/user-profile-classes/index" class="btn btn-action-primary pull-right"><?=
                                                                                                                                    AmosAdmin::t('amosadmin', 'Gestisci i profili')
                                                                                                                                    ?></a>
            </div>
        <?php
        }
        ?>
        <div>
            <?php
            if ($permission) {
            ?>
                <?php // $form->field($model, 'profiles')->checkboxList($profiles)  
                ?>
                <h2><?= $model->getAttributeLabel('profiles') ?></h2>
                <div class="permission-checkbox">
                    <fieldset>
                        <legend class="sr-only">User Profile[profiles][]</legend>
                        <input type="hidden" name="UserProfile[profiles]" value="">
                        <div id="userprofile-profiles" class="userprofile-profiles-checkbox row">
                            <?php
                            if (empty($profiles)) {
                            ?>
                                <div class="checkbox">
                                    <?=
                                    AmosAdmin::t('amosadmin', 'Nessun profilo disponibile.');
                                    ?>
                                </div>
                                <?php
                            } else {
                                foreach ($profiles as $k => $v) {

                                    $item = UserProfileClasses::findOne($k);
                                    if (!empty($item)) {
                                ?>

                                        <div class="checkbox">
                                            <input type="checkbox" id="UserProfile[profiles][]<?= $k ?>" name="UserProfile[profiles][]" value="<?= $k ?>" <?=
                                                                                                                                                            ((!empty($model->profiles) && array_key_exists($k, $model->profiles)) ? 'checked' : '')
                                                                                                                                                            ?>>
                                            <label class="no-asterisk" for="UserProfile[profiles][]<?= $k ?>">

                                                <div class="avatar-wrapper avatar-extra-text mb-0">
                                                    <div class="avatar-box-img">
                                                        <?=
                                                        AmosIcons::show(
                                                            'account-circle',
                                                            ['class' => 'avatar size-lg'],
                                                            AmosIcons::AM
                                                        )
                                                        ?>
                                                    </div>

                                                    <div class="ml-2 avatar-body">
                                                        <div class="name-manage">
                                                            <p class="avatar-name font-weight-bold mb-0">
                                                                <?= $item->name ?>
                                                            </p>
                                                        </div>
                                                        <small class="avatar-info font-weight-normal mb-0">
                                                            <?= strip_tags($item->description) ?>
                                                        </small>
                                                    </div>
                                                </div>

                                            </label>
                                        </div>
                            <?php
                                    }
                                }
                            }
                            ?>
                        </div>
                    </fieldset>
                </div>
                <?php
            } else {
                $profileClasses = $model->profileClasses;
                if (!empty($profileClasses)) {
                ?>
                    <h2><?= $model->getAttributeLabel('profiles') ?></h2>
                    <div class="permission-container">
                        <?php foreach ($profileClasses as $item) { ?>

                            <div class="avatar-wrapper avatar-extra-text mb-0">
                                <div class="avatar-box-img">
                                    <?=
                                    AmosIcons::show(
                                        'account-circle',
                                        ['class' => 'avatar size-lg'],
                                        AmosIcons::AM
                                    )
                                    ?>
                                </div>

                                <div class="ml-2 avatar-body">
                                    <div class="name-manage">
                                        <p class="avatar-name font-weight-bold mb-0">
                                            <?= $item->name ?>
                                        </p>
                                    </div>
                                    <small class="avatar-info font-weight-normal mb-0">
                                        <?= strip_tags($item->description) ?>
                                    </small>
                                </div>
                            </div>

                        <?php } ?>
                    </div>
                <?php } else { ?>
                    <?= AmosAdmin::t('amosadmin', 'Nessun profilo associato'); ?>
            <?php
                }
            }
            ?>
        </div>
    </div>

</section>