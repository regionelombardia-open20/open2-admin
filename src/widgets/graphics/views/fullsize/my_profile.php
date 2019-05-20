<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\admin\widgets\graphics\views\fullsize
 * @category   CategoryName
 */

use lispa\amos\admin\AmosAdmin;
use lispa\amos\admin\widgets\graphics\WidgetGraphicMyProfile;
use lispa\amos\core\helpers\Html;
use lispa\amos\core\icons\AmosIcons;


/**
 * @var \yii\web\View $this
 * @var WidgetGraphicMyProfile $widget
 * @var \lispa\amos\admin\models\UserProfile $userProfile
 */

?>

<div class="box-widget-header">
    <div class="box-widget-wrapper">
        <h2 class="box-widget-title col-xs-10 nop">
            <?= AmosIcons::show('user', ['class' => 'am-2'], AmosIcons::IC); ?>
            <?= AmosAdmin::tHtml('amosadmin', 'Il mio profilo') ?>
        </h2>
    </div>
    <div class="read-all"><?= Html::a(AmosAdmin::t('amosadmin', '#view-all-profile'), $userProfile->getFullViewUrl(), ['class' => '']); ?></div>
</div>

<div class="box-widget myprofile">
    <section>
        <div class="list-items">
        <h2 class="sr-only"><?= AmosAdmin::t('amosadmin', 'Il mio profilo') ?></h2>
            <div class="widget-listbox-option" role="option">
                <article class="wrap-item-box">
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
                </article>
            </div>
        </div>
    </section>
</div>
