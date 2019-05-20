<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\admin\widgets\graphics
 * @category   CategoryName
 */

namespace lispa\amos\admin\widgets\graphics;

use lispa\amos\admin\AmosAdmin;
use lispa\amos\admin\assets\ModuleAdminAsset;
use lispa\amos\admin\base\ConfigurationManager;
use lispa\amos\admin\models\UserProfile;
use lispa\amos\core\helpers\Html;
use lispa\amos\core\widget\WidgetGraphic;
use Yii;

/**
 * Class WidgetGraphicMyProfile
 * @package lispa\amos\admin\widgets\graphics
 */
class WidgetGraphicMyProfile extends WidgetGraphic
{
    /**
     * @var AmosAdmin $adminModule
     */
    public $adminModule;

    /**
     * @var int $loggedUserId
     */
    public $loggedUserId;

    /**
     * @var UserProfile $userProfile
     */
    public $userProfile;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->setCode('USER_PROFILE_GRAPHIC');
        $this->setLabel(AmosAdmin::t('amosadmin', 'Il mio profilo (grafico)'));
        $this->setDescription(AmosAdmin::t('amosadmin', 'Riassume alcune informazioni sul profilo'));

        $this->adminModule = AmosAdmin::instance();
        $this->loggedUserId = Yii::$app->getUser()->id;

        /** @var UserProfile $userProfileModel */
        $userProfileModel = $this->adminModule->createModel('UserProfile');
        $this->userProfile = $userProfileModel::findOne(['user_id' => $this->loggedUserId]);
    }

    /**
     * @inheritdoc
     */
    public function getHtml()
    {
        ModuleAdminAsset::register($this->getView());

        $viewPath = '@vendor/lispa/amos-admin/src/widgets/graphics/views/';
        $viewToRender = $viewPath . 'my_profile';
        if (is_null(\Yii::$app->getModule('layout'))) {
            $viewToRender .= '_old';
        }

        return $this->render($viewToRender, [
            'widget' => $this,
            'userProfile' => $this->userProfile,
        ]);
    }

    /**
     * @return string
     */
    public function getBoxWidgetText()
    {
        $str = $this->userProfile->user->email;
        if (
            $this->adminModule->confManager->isVisibleBox('box_dati_fiscali_amministrativi', ConfigurationManager::VIEW_TYPE_FORM) &&
            $this->adminModule->confManager->isVisibleField('codice_fiscale', ConfigurationManager::VIEW_TYPE_FORM)
        ) {
            $str .= ' | ' . ($this->userProfile->codice_fiscale ? $this->userProfile->codice_fiscale : '-');
        }
        return $str;
    }

    /**
     * @return string
     */
    public function getUserProfileRoundImage()
    {
        Yii::$app->imageUtility->methodGetImageUrl = "getAvatarUrl";
        $roundImage = Yii::$app->imageUtility->getRoundImage($this->userProfile);
        $img = Html::img($this->userProfile->getAvatarUrl(), [
            'class' => $roundImage['class'],
            'style' => "margin-left: " . $roundImage['margin-left'] . "%; margin-top: " . $roundImage['margin-top'] . "%;",
            'alt' => $this->userProfile->getNomeCognome()
        ]);
        return $img;
    }
}
