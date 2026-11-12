<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos\admin\mail\user
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
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var \open20\amos\core\user\User $user
 * @var \open20\amos\admin\models\UserProfile $profile
 */

$appLink = Yii::$app->urlManager->createAbsoluteUrl(['/']);
$appLinkPrivacy = Yii::$app->urlManager->createAbsoluteUrl(['/'.AmosAdmin::getModuleName().'/user-profile/privacy']);
$appName = Yii::$app->name;

?>
<?= AmosAdmin::tHtml('amosadmin', 'Gentile {nome} {cognome},', [
    'nome' => Html::encode($profile->nome),
    'cognome' => Html::encode($profile->cognome)]);
?>
<?= "\n"; ?>
<?= AmosAdmin::tHtml('amosadmin', 'la presente per comunicarle, ai sensi dell’art. 6 dell’AVVISO PUBBLICO “Presentazione domande volte all’inserimento nel Registro dei formatori del Sistema della IeFP della Regione Emilia Romagna, per l’eventuale conferimento di incarichi non a carattere subordinato”, che la sua domanda di inserimento nel Registro dei Formatori risulta formalmente corretta e pertanto accoglibile.\n\nDi seguito troverà le credenziali per accedere alla Piattaforma collaborativa dei Formatori IeFP, dove le verrà richiesto, come primo accesso, il consenso a pubblicare il suo CV ed i suoi dati sulla pagina dedicata.\n\nGrazie della collaborazione.\n\n') ?>
<?= "\n"; ?>
<?= Html::beginTag('a', ['href' => $appLink . 'admin/security/insert-auth-data?token=' . $profile->user->password_reset_token]) ?>
<?= AmosAdmin::t('amosadmin', 'Link di accesso alla piattaforma'); ?>
<?= Html::endTag('a'); ?>
<?= "\n"; ?>
<?= AmosAdmin::t('amosadmin', 'Se il link non funziona copia e incolla il seguente in una finestra del tuo browser di navigazione') ?>
<?= $appLink . 'admin/security/insert-auth-data?token=' . $profile->user->password_reset_token ?>
<?= "\n"; ?>
<?= "\n"; ?>

<?= AmosAdmin::t('amosadmin', '#footer_credential_mail', [
    'appName' => $appName,
    'nome' => $profile->nome,
    'cognome' => $profile->cognome,
    'email' => $profile->user->email,
]) ?>
<?= "\n"; ?>
<?= "\n"; ?>
<?= $appLink; ?>
