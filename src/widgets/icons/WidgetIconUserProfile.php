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
use lispa\amos\admin\models\search\UserProfileSearch;
use lispa\amos\admin\models\UserProfile;
use lispa\amos\core\widget\WidgetIcon;
use lispa\amos\core\widget\WidgetAbstract;
use lispa\amos\core\icons\AmosIcons;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class WidgetIconUserProfile
 * @package lispa\amos\admin\widgets\icons
 */
class WidgetIconUserProfile extends WidgetIcon {

    /**
     * @inheritdoc
     */
    public function init() {
        parent::init();

        $paramsClassSpan = [
            'bk-backgroundIcon',
            'color-darkGrey'
        ];

        $this->setLabel(AmosAdmin::tHtml('amosadmin', 'All users'));
        $this->setDescription(AmosAdmin::t('amosadmin', 'List of all platform users'));
        
        if (!empty(\Yii::$app->params['dashboardEngine']) && \Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS) {
            $this->setIconFramework(AmosIcons::IC);
            $this->setIcon('user');
            $paramsClassSpan = [];
        } else {
            $this->setIcon('users');
        }

        $this->setUrl(['/admin/user-profile/index']);
        $this->setCode('ALL_USERS');
        $this->setModuleName(AmosAdmin::getModuleName());
        $this->setNamespace(__CLASS__);

        $this->setClassSpan(
            ArrayHelper::merge(
                $this->getClassSpan(),
                $paramsClassSpan
            )
        );

        $this->setBulletCount(
            $this->makeBulletCounter(null)
        );
    }

    /**
     * 
     * @param type $user_id
     * @return type
     */
    public function makeBulletCounter($user_id = null) {
        return 0;
        
        $query = new UserProfileSearch();
        
        return $query->getNewProfilesCount();

    }

}
