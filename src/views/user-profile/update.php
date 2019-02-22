<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\admin\views\user-profile
 * @category   CategoryName
 */

use lispa\amos\admin\AmosAdmin;

/**
 * @var yii\web\View $this
 * @var lispa\amos\admin\models\UserProfile $model
 * @var lispa\amos\core\user\User $user
 * @var string $tipologiautente
 * @var string $permissionSave
 */

$this->title = $model;
$this->params['breadcrumbs'][] = ['label' => AmosAdmin::t('amosadmin', 'Utenti'), 'url' => ['/admin']];
$this->params['breadcrumbs'][] = ['label' => AmosAdmin::t('amosadmin', 'Elenco'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = AmosAdmin::t('amosadmin', 'Aggiorna');

?>

<div class="user-profile-update">
    <?= $this->render('_form', [
        'user' => $user,
        'model' => $model,
        'tipologiautente' => $tipologiautente,
        'permissionSave' => $permissionSave,
        'tabActive' => $tabActive
    ]) ?>
</div>
