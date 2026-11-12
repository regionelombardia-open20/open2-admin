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


use open20\gamification\models\Gamification;
use open20\gamification\models\PunteggiTotUtenti;
use open20\amos\admin\AmosAdmin;
use open20\amos\core\helpers\Html;

$oggi = new DateTime();
$dataOggi = $oggi->format('Y-m-d H:i:s');
$currentGamification = Gamification::find()
    ->andWhere(['<=', 'starting_date',  $dataOggi])
    ->andWhere(['>=', 'finish_date', $dataOggi])
    ->andWhere(['status' => 'GamificationWorkflow/OPENING'])
    ->one();
	
if (!is_null($currentGamification)) {
	
    $currentGamificationAdd30 = Gamification::find()
        ->andWhere(['<=', 'starting_date',  $dataOggi])
    ->andWhere(['>=', 'finish_date', $dataOggi])
        ->andWhere([
            'OR',
            ['status' => Gamification::GAMIFICATION_WORKFLOW_STATUS_CONCLUDED],
            ['status' =>Gamification::GAMIFICATION_WORKFLOW_STATUS_OPENING],
        ])
        ->orderBy(['ABS(TIMESTAMPDIFF(SECOND, NOW(), finish_date))' => SORT_ASC])
        ->one();

    $finishDatePlus30GG = new DateTime($currentGamificationAdd30->finish_date);
    $finishDatePlus30GG = $finishDatePlus30GG->add(new DateInterval('P30D'));
    $finishDatePlus30GG = $finishDatePlus30GG->format('Y-m-d H:i:s');

    if ($finishDatePlus30GG >= $dataOggi){
        $currentGamification = $currentGamificationAdd30;
    }
	

$PunteggioTotUtente = PunteggiTotUtenti::find()
    ->andWhere(['gamification_id' => $currentGamification->id])
    ->andWhere(['user_id' => $user->id])
    ->one();
}
?>
<?php if (!is_null($currentGamification) && !is_null($PunteggioTotUtente)) {  ?>

        <!-- BOX GAMIFICATION -->
        <div class="gamification-userprofile-box">
            <div class="row">
                <div class="col-xs-12">
                    <h2 class="subtitle-form">
                        <?=AmosAdmin::t('amosadmin', 'Gamification')?>: <?= $currentGamification->titolo; ?>
                    </h2>
                    <p class="lead">
                        <?=AmosAdmin::t('amosadmin', 'E\' in corso una campagna di Gamification. Partecipa alle attività della Piattaforma Generazione Lombardia per conquistare punti, scalare la classifica e vincere il premio finale. Consulta il regolamento per tutte le informazioni.')?>
                    </p>

                    <h4>
                        <?= $currentGamification->titolo; ?>
                    </h4>
                    <p>
                        <?= $currentGamification->descrizione; ?>
                    </p>
                    <p>
                        <label><?=AmosAdmin::t('amosadmin', 'Attiva dal')?>:</label> <?= Yii::$app->formatter->asTime($currentGamification->starting_date, 'dd/MM/yyyy HH:mm'); ?> <label>al</label> <?= Yii::$app->formatter->asTime($currentGamification->finish_date, 'dd/MM/yyyy HH:mm'); ?>
                    </p>
                    <p>
                        <label><?=AmosAdmin::t('amosadmin', 'Punteggio personale')?>:</label> <?= $PunteggioTotUtente->totale ?>
                    </p>

                    <?= Html::a('Ricalcola', ["/gamification/gamification/ricalcola", 'gamif_id' => $currentGamification->id, 'user_id' => $user->getId()], ['id' => 'ricalcola-button', 'class' => 'btn btn-primary btn-xs']) ?>
                </div>
            </div>
        </div>
    <?php  } ?>