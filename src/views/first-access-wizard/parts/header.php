<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos\admin\views\first-access-wizard\parts
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

/**
 * @var \open20\amos\admin\models\UserProfile $model
 */

?>

<section class="m-b-35">
    
        <div class="avatar-wizard-profile">
        <div>
            <div class="img-profile">
                <?php
                $url = $model->getAvatarUrl('original', [
                    'class' => 'img-responsive'
                ]);
                Yii::$app->imageUtility->methodGetImageUrl = 'getAvatarUrl';
                try {
                    $getHorizontalImageClass = (!empty($model->userProfileImage) ? Yii::$app->imageUtility->getHorizontalImage($model->userProfileImage)['class'] : '');
                    $getHorizontalImageMarginLeft = (!empty($model->userProfileImage) ? 'margin-left:' . Yii::$app->imageUtility->getHorizontalImage($model->userProfileImage)["margin-left"] . 'px;margin-top:' . Yii::$app->imageUtility->getHorizontalImage($model->userProfileImage)["margin-top"] . 'px;' : '');
                } catch (\Exception $ex) {
                    $getHorizontalImageClass = '';
                    $getHorizontalImageMarginLeft = '';
                }
                ?>
                <?= Html::img($url, [
                    'class' => 'img-responsive ' . $getHorizontalImageClass,
                    'style' => $getHorizontalImageMarginLeft,
                    'alt' => AmosAdmin::t('amosadmin', 'Profile Image')
                ]);
                ?>
            </div>
            </div>
        <div>
            <div>
                <h3><strong><?= $model->getNomeCognome() ?></strong></h3>
            </div>
            <div class="text-muted small">
                <p><?= ($model->presentazione_breve ? $model->presentazione_breve : '-') ?></p>
            </div>
        </div>
        </div>
</section>

