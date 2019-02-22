<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\admin\widgets
 * @category   CategoryName
 */

namespace lispa\amos\admin\widgets;

use lispa\amos\admin\AmosAdmin;
use lispa\amos\admin\models\UserProfile;
use lispa\amos\core\icons\AmosIcons;
use yii\base\Widget;

/**
 * Class GoogleContactWidget
 * @package lispa\amos\admin\widgets
 */
class GoogleContactWidget extends Widget
{
    /** @var  UserProfile $model */
    public $model;

    /**
     * widget initialization
     */
    public function init()
    {
        parent::init();

        if (is_null($this->model)) {
            throw new \Exception(AmosAdmin::t('amosadmin', 'Missing model'));
        }
    }

    /**
     * @inheritdoc
     */
    public function run()
    {

        if($this->model->isGoogleContact()) {
            $googleContactIcon = AmosIcons::show('google', [
                'class' => 'am-2',
                'title' => AmosAdmin::t('amosadmin', 'Google Contact')
            ]);
            return $googleContactIcon;
        }else{
            return '';
        }
    }


}