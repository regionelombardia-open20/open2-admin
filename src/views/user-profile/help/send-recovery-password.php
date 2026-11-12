<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos\admin\views\user-profile\help
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
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\utilities\ModalUtility;

/**
 * @var yii\web\View $this
 * @var open20\amos\core\forms\ActiveForm $form
 * @var open20\amos\admin\models\UserProfile $model
 * @var open20\amos\core\user\User $user
 * @var bool $spediscicredenzialienable
 */

$modalId = 'send-recovery-password-modal-id';

$spedisciCredenzialiLink = [
    '/'.AmosAdmin::getModuleName().'/security/spedisci-credenziali',
    'id' => $model->id
];

$baseModalContent = Html::tag('div',
    AmosAdmin::t('amosadmin', 'Sei sicuro di voler inviare le credenziali? Sarà inviata una mail contenente un link per modificare le credenziali. Vuoi continuare?'),
    ['class' => 'send-recovery-password pull-right m-15-0']
);

$footerText = Html::tag('div',
    Html::a(
        Html::tag('span', null,
            ['class' => 'glyphicon glyphicon-ban-circle']
        ) .
        AmosAdmin::t('amosadmin', 'Annulla'),
        null,
        [
            'id' => 'undo',
            'class' => 'btn btn-secondary',
            'data-dismiss' => 'modal'
        ]) .
    Html::a(
        Html::tag('span', null,
            ['class' => 'glyphicon glyphicon-ok']
        ) .
        AmosAdmin::t('amosadmin', 'Ok'),
        $spedisciCredenzialiLink,
        [
            'id' => 'confirm',
            'class' => 'btn btn-navigation-primary'
        ]
    )
);

ModalUtility::amosModal([
    'id' => $modalId,
    'headerText' => AmosAdmin::t('amosadmin', "Conferma"),
    'modalBodyContent' => $baseModalContent,
    'footerText' => $footerText,
    'containerOptions' => ['class' => 'modal-utility bootstrap-dialog type-warning fade']
]);

?>

<?= Html::a(

    AmosIcons::show('email') . '<span>' . AmosAdmin::t('amosadmin', 'Spedisci credenziali') . '</span>',

    $spedisciCredenzialiLink,
    [
        'class' => 'btn btn-default btn-spedisci-credenziali btn-block',
        'data-toggle' => 'modal',
        'data-target' => '#' . $modalId
    ]
); ?>
