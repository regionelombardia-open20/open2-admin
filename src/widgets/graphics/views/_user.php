<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\admin\widgets\graphics\views
 * @category   CategoryName
 */

use lispa\amos\core\helpers\Html;
use lispa\amos\admin\utility\UserProfileUtility;
use lispa\amos\admin\widgets\graphics\WidgetGraphicsUsers;
use lispa\amos\organizzazioni\models\ProfiloUserMm;

/**
 * @var yii\web\View $this
 * @var \lispa\amos\admin\models\UserProfile $model
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