<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos\admin\controllers
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

namespace open20\amos\admin\controllers;
use Yii;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use open20\amos\admin\AmosAdmin;
use open20\amos\core\helpers\Html;

/**
 * Class UserProfileClassesController 
 * This is the class for controller "UserProfileClassesController".
 * @package open20\amos\admin\controllers 
 */
class UserProfileClassesController extends \open20\amos\admin\controllers\base\UserProfileClassesController
{
    /**
     * @inheritdoc
     */
    
    public function behaviors()
    {
        $result = ArrayHelper::merge(
            parent::behaviors(), 
            [
                'access' => [
                    'class' => AccessControl::class,
                    'ruleConfig' => [
                        'class' => AccessRule::class,
                    ],
                    'rules' => [
                        [
                            'allow' => true,
                            'actions' => [
                                'clone',
                            ],
                            'roles' => ['ADMIN', 'AMMINISTRATORE_UTENTI']
                        ],
                    ],
                ],
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'remove-prevalent-partnership' => ['post'],
                        'delete' => ['post', 'get']
                    ]
                ]
            ]
        );

        return $result;
    }
    public function beforeAction($action)
    {
        if (\Yii::$app->user->isGuest) {
            $titleSection = AmosAdmin::t('amosadmin', 'Utenti');
            $urlLinkAll   = '';

            $labelSigninOrSignup = AmosAdmin::t('amosadmin', '#beforeActionCtaLoginRegister');
            $titleSigninOrSignup = AmosAdmin::t(
                    'amosadmin', '#beforeActionCtaLoginRegisterTitle', ['platformName' => \Yii::$app->name]
            );
            $labelSignin         = AmosAdmin::t('amosadmin', '#beforeActionCtaLogin');
            $titleSignin         = AmosAdmin::t(
                    'amosadmin', '#beforeActionCtaLoginTitle', ['platformName' => \Yii::$app->name]
            );

            $labelLink        = $labelSigninOrSignup;
            $titleLink        = $titleSigninOrSignup;
            $socialAuthModule = \Yii::$app->getModule('socialauth');
            if ($socialAuthModule && ($socialAuthModule->enableRegister == false)) {
                $labelLink = $labelSignin;
                $titleLink = $titleSignin;
            }

            $ctaLoginRegister = Html::a(
                    $labelLink,
                    isset(\Yii::$app->params['linkConfigurations']['loginLinkCommon']) ? \Yii::$app->params['linkConfigurations']['loginLinkCommon']
                        : \Yii::$app->params['platform']['backendUrl'].'/'.AmosAdmin::getModuleName().'/security/login',
                    [
                    'title' => $titleLink
                    ]
            );
            $subTitleSection  = Html::tag(
                    'p',
                    AmosAdmin::t(
                        'amosadmin', 'Unisciti a {platformName}!, {ctaLoginRegister}',
                        ['platformName' => \Yii::$app->name, 'ctaLoginRegister' => $ctaLoginRegister]
                    )
            );
        } else {
            $titleSection = AmosAdmin::t('amosadmin', 'Profili');
            $labelLinkAll = '';
            $urlLinkAll   = '';
            $titleLinkAll = '';

            $subTitleSection = Html::tag('p', AmosAdmin::t('amosadmin', ''));
        }

        $labelCreate        = AmosAdmin::t('amosadmin', 'Nuovo');
        $titleCreate        = AmosAdmin::t('amosadmin', 'Crea un nuovo profilo');
        $labelManage        = '';
        $titleManage        = '';
        $urlCreate          = '/'.AmosAdmin::getModuleName().'/user-profile-classes/create';
        $urlManage          = null;
        $this->view->params = [
            'isGuest' => \Yii::$app->user->isGuest,
            'modelLabel' => 'profili',
            'titleSection' => $titleSection,
            'subTitleSection' => $subTitleSection,
            'urlLinkAll' => $urlLinkAll,
            'labelLinkAll' => $labelLinkAll,
            'titleLinkAll' => $titleLinkAll,
            'labelCreate' => $labelCreate,
            'titleCreate' => $titleCreate,
            'labelManage' => $labelManage,
            'titleManage' => $titleManage,
            'urlCreate' => $urlCreate,
            'urlManage' => $urlManage,
        ];

        if (!parent::beforeAction($action)) {
            return false;
        }

        return true;
    }

}