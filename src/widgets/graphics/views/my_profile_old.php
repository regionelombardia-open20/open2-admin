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

<div class="box-widget">
    <div class="box-widget-toolbar row nom">
        <h2 class="box-widget-title col-xs-10 nop"><?= AmosAdmin::t('amosadmin', 'Il mio profilo') ?></h2>
    </div>
    <section><h2 class="sr-only"><?= AmosAdmin::t('amosadmin', 'Il mio profilo') ?></h2>
        <div role="listbox">
            <div class="widget-listbox-option row list-items" role="option">
                <article class="col-xs-12 nop">
                    <div class="col-xs-4 nopl">
                        <span class="pull-left">
                            <?= Html::a(
                                $widget->getUserProfileRoundImage(),
                                $userProfile->getFullViewUrl(),
                                ['title' => AmosAdmin::t('amosadmin', 'va al mio profilo'), 'class' => 'container-round-img-md ']
                            ) ?>
                        </span>
                    </div>
                    <div class="col-xs-8 nopl">
                        <h3 class="box-widget-subtitle"><?= $userProfile->nomeCognome ?></h3>
                        <p class="box-widget-text"><?= $widget->getBoxWidgetText() ?></p>
                    </div>
                    <div class="clearfix"></div>
                </article>
            </div>
        </div>
    </section>
</div>
