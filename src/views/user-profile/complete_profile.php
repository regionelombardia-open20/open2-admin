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

$this->title = AmosAdmin::t('amosadmin',"Completa il tuo profilo utente");
$assetBundle = \open20\amos\admin\assets\ModuleUserProfileAsset::register($this);

$url = \yii\helpers\Url::to(['/'.AmosAdmin::getModuleName().'/user-profile/update', 'id' => $model->id]);
?>
<div class="col-xs-12 m-t-15">
    <h4><?= AmosAdmin::t('amosadmin',"Porta a termine la tua registrazione utente e richiedi la validazione del profilo, <br> così potrai partecipare attivamente alla creazione di contenuti in piattaforma, accedendo a <a href='{link}'>'Il mio profilo'</a>.",[
            'link' => $url
        ]) ?>
    </h4>
</div>
<div class="col-xs-12 m-t-15">
    <?= \yii\helpers\Html::img($assetBundle->baseUrl.'/img/example_complete_profile.jpg')?>
</div>
<div class="col-xs-12 m-t-15">
    <?= \yii\helpers\Html::a(AmosAdmin::t('amosadmin', 'Chiudi'), '/dashboard', [
        'class' => 'btn btn-navigation-secondary pull-left',
        'title' => AmosAdmin::t('amosadmin', 'Chiudi')
    ]);
    ?>
    <?= \yii\helpers\Html::a(AmosAdmin::t('amosadmin', 'Completa il profilo'), $url, [
        'class' => 'btn btn-navigation-primary pull-right',
        'title' => AmosAdmin::t('amosadmin', 'Completa il profilo')
    ]);
    ?>
</div>
