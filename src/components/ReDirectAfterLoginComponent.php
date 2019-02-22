<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\admin\components
 * @category   CategoryName
 */

namespace lispa\amos\admin\components;

use lispa\amos\admin\AmosAdmin;
use lispa\amos\admin\models\UserProfile;
//use yii\base\BootstrapInterface;
use yii\base\Component;
//use yii\base\Event;

/**
 * Class FirstAccessWizardComponent
 * @package lispa\amos\admin\components
 */
class ReDirectAfterLoginComponent extends Component /*implements BootstrapInterface*/
{

    /**
     * @param $url
     * @return \yii\web\Response
     */
    public function redirectToUrl($url){
        return \Yii::$app->controller->redirect([$url]);

    }
}
