<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos\admin\widgets\graphics
 * @author    Aria S.p.A. <protocollo@pec.ariaspa.it>
 * @license   GNU General Public License v3.0 <https://www.gnu.org/licenses/gpl-3.0.html>
 * @link      https://www.ariaspa.it/
 *
 * This file is part of OPEN 2.0.
 *
 * OPEN 2.0 is free software: you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * OPEN 2.0 is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with OPEN 2.0. If not, see <https://www.gnu.org/licenses/>.
 */

namespace open20\amos\admin\widgets\graphics;

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\assets\ModuleAdminAsset;
use open20\amos\admin\base\ConfigurationManager;
use open20\amos\admin\models\UserProfile;
use open20\amos\core\helpers\Html;
use open20\amos\core\widget\WidgetGraphic;
use Yii;

/**
 * Class WidgetGraphicMyProfile
 * @package open20\amos\admin\widgets\graphics
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

        $viewPath = '@vendor/open20/amos-admin/src/widgets/graphics/views/';
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
