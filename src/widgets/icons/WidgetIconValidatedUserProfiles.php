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
 * Class WidgetIconValidatedUserProfiles
 * @package lispa\amos\admin\widgets\icons
 */
class WidgetIconValidatedUserProfiles extends WidgetIcon {

    /**
     * @inheritdoc
     */
    public function init() {
        parent::init();

        $paramsClassSpan = [
            'bk-backgroundIcon',
            'color-darkGrey'
        ];

        $this->setLabel(AmosAdmin::tHtml('amosadmin', 'Validated users'));
        $this->setDescription(AmosAdmin::t('amosadmin', 'List of validated platform users'));

        if (!empty(\Yii::$app->params['dashboardEngine']) && \Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS) {
            $this->setIconFramework(AmosIcons::IC);
            $this->setIcon('user');
            $paramsClassSpan = [];
        } else {
            $this->setIcon('users');
        }

        $this->setUrl(['/admin/user-profile/validated-users']);
        $this->setCode('VALIDATED_USERS');
        $this->setModuleName(AmosAdmin::getModuleName());
        $this->setNamespace(__CLASS__);

        $this->setClassSpan(
            ArrayHelper::merge(
                $this->getClassSpan(),
                $paramsClassSpan
            )
        );
    }

}
