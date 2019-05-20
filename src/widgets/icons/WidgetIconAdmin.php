<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\admin\widgets\icons
 * @category   CategoryName
 */

namespace lispa\amos\admin\widgets\icons;

use lispa\amos\admin\AmosAdmin;
use lispa\amos\core\widget\WidgetIcon;
use lispa\amos\core\widget\WidgetAbstract;
use lispa\amos\core\icons\AmosIcons;

use yii\helpers\ArrayHelper;

/**
 * Class WidgetIconAdmin
 * @package lispa\amos\admin\widgets\icons
 */
class WidgetIconAdmin extends WidgetIcon {

    /**
     * @inheritdoc
     */
    public function init() {
        parent::init();

        $paramsClassSpan = [
            'bk-backgroundIcon',
            'color-darkGrey'
        ];

        $this->setLabel(AmosAdmin::tHtml('amosadmin', 'Users'));
        $this->setDescription(AmosAdmin::t('amosadmin', 'Users'));

        if (!empty(\Yii::$app->params['dashboardEngine']) && \Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS) {
            $this->setIconFramework(AmosIcons::IC);
            $this->setIcon('user');
            $paramsClassSpan = [];
        } else {
            $this->setIcon('users');
        }

        $this->setUrl(['/admin']);
        $this->setCode('ADMIN_MODULE');
        $this->setModuleName(AmosAdmin::getModuleName());
        $this->setNamespace(__CLASS__);

        $this->setClassSpan(
            ArrayHelper::merge(
                $this->getClassSpan(),
                $paramsClassSpan
            )
        );
    }

    /**
     * @inheritdoc
     */
    public function getOptions() {
        return ArrayHelper::merge(
            parent::getOptions(),
            ['children' => $this->getWidgetsIcon()]
        );
    }

    /**
     * 
     * @return type
     */
    public function getWidgetsIcon() {
        $widgets = [];

        $MyProfile = new WidgetIconMyProfile();
        if ($MyProfile->isVisible()) {
            $widgets[] = $MyProfile->getOptions();
        }

        $UserProfile = new WidgetIconUserProfile();
        if ($UserProfile->isVisible()) {
            $widgets[] = $UserProfile->getOptions();
        }

        return $widgets;
    }

}
