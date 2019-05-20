<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\admin\widgets\graphics\views
 * @category   CategoryName
 */

use lispa\amos\admin\AmosAdmin;
use lispa\amos\admin\widgets\graphics\WidgetGraphicMyProfile;
use lispa\amos\core\helpers\Html;

/**
 * @var \yii\web\View $this
 * @var WidgetGraphicMyProfile $widget
 * @var \lispa\amos\admin\models\UserProfile $userProfile
 */

?>

<div class="grid-item">
    <div class="box-widget myprofile">
        <div class="box-widget-toolbar dark-toolbar row nom">
            <h2 class="box-widget-title col-xs-10 nop"><?= AmosAdmin::t('amosadmin', 'Il mio profilo') ?></h2>
        </div>
        <section><h2 class="sr-only"><?= AmosAdmin::t('amosadmin', 'Il mio profilo') ?></h2>
            <div role="listbox">
                <div class="widget-listbox-option row list-items" role="option">
                    <article class="col-xs-12 nop">
                        <div class="icon-admin-wgt">
                            <span class="pull-left">
                                <?= Html::a(
                                    $widget->getUserProfileRoundImage(),
                                    $userProfile->getFullViewUrl(),
                                    ['title' => AmosAdmin::t('amosadmin', 'va al mio profilo'), 'class' => 'container-square-img-sm']
                                ) ?>
                            </span>
                        </div>
                        <div class="text-admin-wgt">
                            <h3 class="box-widget-subtitle"><?= $userProfile->nomeCognome ?></h3>
                            <p class="box-widget-text"><?= $widget->getBoxWidgetText() ?></p>
                        </div>
                        <div class="clearfix"></div>
                    </article>
                </div>
            </div>
        </section>
    </div>
</div>
