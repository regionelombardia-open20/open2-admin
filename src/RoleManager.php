<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\admin
 * @category   CategoryName
 */

namespace lispa\amos\admin;

use mdm\admin\Module;
use Yii;
use yii\base\Exception;
use yii\web\ForbiddenHttpException;

/**
 * Class AmosAdmin
 * @package lispa\amos\admin
 */
class RoleManager extends Module
{
    public $controllerNamespace = "mdm\admin\controllers";
    public function init()
    {
        //Views on the original plugin
        $this->viewPath = '@vendor/mdmsoft/yii2-admin/views';

        if(!Yii::$app->user->can('ADMIN')) {
            throw new ForbiddenHttpException(Yii::t('amosadmin', 'Access Denied'));
        }

        return parent::init();
    }
}
