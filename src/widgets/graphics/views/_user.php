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

use open20\amos\core\helpers\Html;
use open20\amos\admin\utility\UserProfileUtility;
use open20\amos\admin\widgets\graphics\WidgetGraphicsUsers;
use open20\amos\organizzazioni\models\ProfiloUserMm;

/**
 * @var yii\web\View $this
 * @var \open20\amos\admin\models\UserProfile $model
 */
?>

<article class="user-box">       
    <div class="profile-icon container-round-img">
        <?= Html::img($model->getAvatarUrl('square_small'), [
            'class' => Yii::$app->imageUtility->getRoundRelativeImage($model)['class'],
            'alt' => $model->id
        ]) ?>
    </div>
    <div class="profile-info">
        <span class="name surname"><?= $model->nomeCognome ?></span>
        <?php if(!is_null($model->userOrganization)): ?>
            <span class="company"><?= $model->userOrganization->getNameField() ?></span>
        <?php endif; ?>
        <?php if($model->user_profile_role_other): ?>
            <span class="role"><?= $model->user_profile_role_other ?></span>
        <?php endif; ?>
    </div>
</article>