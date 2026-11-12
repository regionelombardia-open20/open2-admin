<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos\admin\views\user-profile
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

/**
 * @var yii\web\View $this
 * @var open20\amos\admin\models\UserProfile $model
 * @var open20\amos\core\user\User $user
 * @var string $tipologiautente
 * @var string $permissionSave
 */

$this->title = $model;
$this->params['titleSection'] = AmosAdmin::t('amosadmin', 'Il mio profilo');
$this->params['breadcrumbs'][] = ['label' => AmosAdmin::t('amosadmin', 'Partecipanti'), 'url' => ['/'.AmosAdmin::getModuleName().'/user-profile/validated-users']];
$this->params['breadcrumbs'][] = ['label' => AmosAdmin::t('amosadmin', 'Il mio profilo'), 'url' => ['/'.AmosAdmin::getModuleName().'/user-profile/view','id' => $model->id]];
$this->params['breadcrumbs'][] = AmosAdmin::t('amosadmin', 'Aggiorna');

//$this->params['breadcrumbs'][] = ['label' => $model, 'url' => ['view', 'id' => $model->id]];

?>

<div class="user-profile-update">
    <?= $this->render('_form', [
        'user' => $user,
        'model' => $model,
        'tipologiautente' => $tipologiautente,
        'permissionSave' => $permissionSave,
        'profiles' => $profiles,
        'tabActive' => $tabActive
    ]) ?>
</div>
