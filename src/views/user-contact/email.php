<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos\admin\views\user-contact
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
 * @var \open20\amos\admin\models\UserProfile $contactProfile
 * @var string $message
 * @var string $url
 * @var string $messageLink
 */

?>

<div>
    <div style="box-sizing:border-box;">
        <div class="corpo"
             style="border:1px solid #cccccc;padding:10px;margin-bottom:10px;background-color:#ffffff;margin-top:20px">
            <div style="width: 50px; height: 50px; overflow: hidden;-webkit-border-radius: 50%; -moz-border-radius: 50%; border-radius: 50%;float: left;">
                <?= \open20\amos\admin\widgets\UserCardWidget::widget([
                    'model' => $contactProfile,
                    'onlyAvatar' => true,
                    'absoluteUrl' => true
                ]) ?>
            </div>
            <div style="margin: 0 0 0 20px;">
                <p style="font-weight: 900"><?= $contactProfile->getNomeCognome() ?></p>
                <p><?= $message ?></p>
            </div>
        </div>
        <div style="width:100%;margin-top:30px">
            <p><?=
                Html::a(AmosAdmin::t('amosadmin', 'Sign into the platflorm'), $url, ['style' => 'color: #297a38;']). ' '. $messageLink
                ?>
            </p>
        </div>
    </div>
</div>