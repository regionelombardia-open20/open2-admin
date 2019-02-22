<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\admin\controllers
 * @category   CategoryName
 */

namespace lispa\amos\admin\controllers;

use lispa\amos\admin\AmosAdmin;
use lispa\amos\admin\assets\ModuleAdminAsset;
use lispa\amos\admin\exceptions\AdminException;
use lispa\amos\admin\interfaces\OrganizationsModuleInterface;
use lispa\amos\admin\models\CambiaPasswordForm;
use lispa\amos\admin\models\DropAccountForm;
use lispa\amos\admin\models\search\UserProfileAreaSearch;
use lispa\amos\admin\models\search\UserProfileRoleSearch;
use lispa\amos\admin\models\UserProfile;
use lispa\amos\admin\models\UserProfileReactivationRequest;
use lispa\amos\admin\utility\UserProfileUtility;
use lispa\amos\core\forms\editors\m2mWidget\controllers\M2MWidgetControllerTrait;
use lispa\amos\core\forms\editors\m2mWidget\M2MEventsEnum;
use lispa\amos\core\module\BaseAmosModule;
use lispa\amos\core\utilities\ArrayUtility;
use lispa\amos\socialauth\models\SocialAuthServices;
use raoul2000\workflow\base\WorkflowException;
use Yii;
use yii\base\Exception;
use yii\db\ActiveQuery;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

/**
 * Class UserProfileController
 * @package lispa\amos\admin\controllers
 */
class UserProfileController extends \lispa\amos\admin\controllers\base\UserProfileController
{
    /**
     * @var string $layout
     */
    public $layout = 'list';

    /**
     * M2MWidgetControllerTrait
     */
    use M2MWidgetControllerTrait;

    public function init()
    {
        parent::init();
        ModuleAdminAsset::register(Yii::$app->view);

        $this->setStartObjClassName(UserProfile::className());
        $this->setTargetObjClassName(UserProfile::className());
        $this->setRedirectAction('update');
        $this->on(M2MEventsEnum::EVENT_BEFORE_ASSOCIATE_ONE2MANY, [$this, 'beforeAssociateOneToMany']);
        $this->on(M2MEventsEnum::EVENT_BEFORE_RENDER_ASSOCIATE_ONE2MANY, [$this, 'beforeRenderOneToMany']);
        $this->on(M2MEventsEnum::EVENT_AFTER_ASSOCIATE_ONE2MANY, [$this, 'afterAssociateOneToMany']);
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $result = ArrayHelper::merge(parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::className(),
                    'ruleConfig' => [
                        'class' => AccessRule::className(),
                    ],
                    'rules' => [
                        [
                            'allow' => true,
                            'actions' => [
                                'password-expired',
                                'cambia-password',
                                'enable-google-service',
                                'disable-google-service',
                                'get-social-service-status',
                                'get-social-user'
                            ],
                            'roles' => ['BASIC_USER']
                        ],
                        [
                            'allow' => true,
                            'actions' => [
                                'associate-facilitator',
                                'associate-prevalent-partnership',
                                'update-profile',
                                'remove-prevalent-partnership',
                                'drop-account',
                            ],
                            'roles' => ['UpdateOwnUserProfile']
                        ],
                        [
                            'allow' => true,
                            'actions' => [
                                'drop-account',
                                'update-profile',
                                'inactive-users',
                                'validated-users',
                                'facilitator-users',
                                'community-manager-users',
                                'associate-facilitator',
                                'associate-prevalent-partnership',
                                'remove-prevalent-partnership',
                                'annulla-m2m',
                                'deactivate-account',
                                'reactivate-account',
                                'reject-reactivation-request',
                                'def-facilitator-present',
                                'validate-user-profile',
                                'reject-user-profile',
                                'contacts',
                                'password-expired',
                                'cambia-password',
                            ],
                            'roles' => ['ADMIN', 'AMMINISTRATORE_UTENTI']
                        ],
                        [
                            'allow' => true,
                            'actions' => [
                                'validate-user-profile',
                                'reject-user-profile',
                            ],
                            'roles' => ['FACILITATOR', 'VALIDATOR']
                        ],
                        [
                            'allow' => true,
                            'actions' => [
                                'validated-users',
                                'facilitator-users',
                                'community-manager-users',
                                'annulla-m2m',
                                'deactivate-account',
                                'def-facilitator-present',
                                'contacts'
                            ],
                            'roles' => ['USERPROFILE_READ']
                        ],
                        [
                            'actions' => [
                                'spedisci-credenziali'
                            ],
                            'allow' => true,
                            'roles' => ['GESTIONE_UTENTI'],
                        ],
                    ],
                ],
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'remove-prevalent-partnership' => ['post'],
                        'delete' => ['post', 'get']
                    ]
                ]
            ]);

        return $result;
    }

    /**
     * @param \yii\base\Event $event
     */
    public function beforeAssociateOneToMany($event)
    {
        $this->setUpLayout('main');
    }

    /**
     * @param \yii\base\Event $event
     */
    public function beforeRenderOneToMany($event)
    {
        Yii::$app->view->params['model'] = $this->model;
    }

    /**
     * @param $event
     */
    public function afterAssociateOneToMany($event)
    {

        try {

            $userprofile_class = AmosAdmin::getInstance()->model('UserProfile');

            if (!empty($event->sender) && is_object($event->sender) && $event->sender instanceof $userprofile_class) {
                if (!empty($event->sender->prevalent_partnership_id)) {
                    $admin = AmosAdmin::getInstance();
                    /** @var  $organizationsModule OrganizationsModuleInterface */
                    $organizationsModule = \Yii::$app->getModule($admin->getOrganizationModuleName());
                    $organizationsModule->saveOrganizationUserMm(Yii::$app->user->id, $event->sender->prevalent_partnership_id);
                }
            }
        } catch (Exception $ex) {
            Yii::getLogger()->log($ex->getMessage(), \yii\log\Logger::LEVEL_ERROR);
        }
    }

    /**
     * This method return all enabled professional roles translated.
     * @return array
     */
    public function getRoles()
    {
        return ArrayUtility::translateArrayValues(
            ArrayHelper::map(UserProfileRoleSearch::searchAll(), 'id', 'name'), 'amosadmin', AmosAdmin::className()
        );
    }

    /**
     * This method return all enabled professional areas translated.
     * @return array
     */
    public function getAreas()
    {
        return ArrayUtility::translateArrayValues(
            ArrayHelper::map(UserProfileAreaSearch::searchAll(), 'id', 'name'), 'amosadmin', AmosAdmin::className()
        );
    }

    /**
     * @param int $id The user id
     * @return \yii\web\Response
     */
    public function actionValidateUserProfile($id)
    {
        $userProfile = UserProfile::findOne($id);
        try {
            $userProfile->sendToStatus(UserProfile::USERPROFILE_WORKFLOW_STATUS_VALIDATED);
            $ok = $userProfile->save(false);
            if ($ok) {
                Yii::$app->session->addFlash('success', AmosAdmin::t('amosadmin', '#USER_VALIDATED'));
            } else {
                Yii::$app->session->addFlash('danger', AmosAdmin::t('amosadmin', '#ERROR_WHILE_VALIDATING'));
            }
        } catch (WorkflowException $e) {
            Yii::$app->session->addFlash('danger', $e->getMessage());
            return $this->redirect(Url::previous());
        }
        return $this->redirect(Url::previous());
    }

    /**
     * @param int $id The user id
     * @return \yii\web\Response
     */
    public function actionRejectUserProfile($id)
    {
        $userProfile = UserProfile::findOne($id);
        try {
            $userProfile->sendToStatus(UserProfile::USERPROFILE_WORKFLOW_STATUS_NOTVALIDATED);
            $ok = $userProfile->save(false);
            if ($ok) {
                Yii::$app->session->addFlash('success', AmosAdmin::t('amosadmin', '#USER_REJECTED'));
            } else {
                Yii::$app->session->addFlash('danger', AmosAdmin::t('amosadmin', '#ERROR_WHILE_REJECTING'));
            }
        } catch (WorkflowException $e) {
            Yii::$app->session->addFlash('danger', $e->getMessage());
            return $this->redirect(Url::previous());
        }
        return $this->redirect(Url::previous());
    }

    /**
     * @param $id
     * @return string
     */
    public function actionUpdateProfile($id)
    {
        $model = $this->actionUpdate($id, false);

        return $this->render('update_profile',
            [
                'user' => $model->user,
                'model' => $model,
                'tipologiautente' => $model->tipo_utente,
                'permissionSave' => 'USERPROFILE_UPDATE',
            ]);
    }

    /**
     * @param int $id
     */
    public function actionSpedisciCredenziali($id)
    {
        $this->actionSendCredentials($id);
    }

    /**
     * @param int $id
     * @return \yii\web\Response
     */
    public function actionSendCredentials($id)
    {
        /** @var \lispa\amos\admin\models\UserProfile $model */
        $model = $this->findModel($id);
        if ($model && $model->user && $model->user->email) {
            $sent = $this->sendPasswordResetMail($model);

            if ($sent) {
                Yii::$app->session->addFlash('success',
                    AmosAdmin::t('amosadmin', 'Credenziali spedite correttamente alla email {email}',
                        ['email' => $model->user->email]));
            } else {
                Yii::$app->session->addFlash('danger',
                    AmosAdmin::t('amosadmin', 'Si è verificato un errore durante la spedizione delle credenziali'));
            }
        } else {
            Yii::$app->session->addFlash('danger',
                AmosAdmin::t('amosadmin',
                    'L\'utente non esiste o è sprovvisto di email, impossibile spedire le credenziali'));
        }
        return $this->redirect(Url::previous());
    }

    /**
     * @param UserProfile $model
     * @return bool
     */
    public function sendCredentialsMail($model)
    {
        return UserProfileUtility::sendCredentialsMail($model);
    }

    /**
     * @param UserProfile $model
     * @return bool
     */
    public function sendPasswordResetMail($model)
    {
        return UserProfileUtility::sendPasswordResetMail($model);
    }

    /**
     * @param int $id
     * @return string|\yii\web\Response
     */
    public function actionCambiaPassword($id)
    {
        $this->setUpLayout('form');
        $dbuser = $this->findModel($id);
        $model = new CambiaPasswordForm();

        if (Yii::$app->request->isPost) {

            if ($model->load(Yii::$app->request->post()) && $model->validate() && $dbuser->user) {
                $password = $model->nuovaPassword;
                $dbuser->user->setPassword($password);
                if ($dbuser->user->validate() && $dbuser->user->save()) {
                    Yii::$app->getSession()->addFlash('success',
                        AmosAdmin::t('amosadmin', 'Password cambiata correttamente'));
                    return $this->redirect(Url::previous());
                } else {
                    Yii::$app->getSession()->addFlash('warning',
                        AmosAdmin::t('amosadmin', 'Cambio password non riuscito, controllare i dati e riprovare.'));
                    return $this->render('password', ['model' => $model, 'id' => $id]);
                }
            } else {
                Yii::$app->getSession()->addFlash('warning',
                    AmosAdmin::t('amosadmin', 'Cambio password non riuscito, controllare i dati e riprovare.'));
                return $this->render('password', ['model' => $model, 'id' => $id]);
            }
        } else {
            return $this->render('password', ['model' => $model, 'id' => $id]);
        }
    }

    /**
     * @return array|null
     */
    public function getWhiteListRoles()
    {
        $arrayRuoli = null;
        $moduleWhite = $this->module->getWhiteListRoles();

        foreach ($moduleWhite as $rule) {
            $arrayRuoli[] = Yii::$app->authManager->getRole($rule);
        }
        return $arrayRuoli;
    }

    /**
     * In Icon view if we are in a network dashboard eg. community, projects, ..
     * view additional information related to current scope
     * @param int $userId
     */
    public function setCwhScopeNewtworkInfo($userId)
    {
        $this->setCwhScopeNetworkInfo($userId);
    }

    /**
     * In Icon view if we are in a network dashboard eg. community, projects, ..
     * view additional information related to current scope
     * @param int $userId
     */
    public function setCwhScopeNetworkInfo($userId)
    {
        /** @var \lispa\amos\cwh\AmosCwh $cwh */
        $cwh = Yii::$app->getModule("cwh");
        // if we are navigating users inside a sprecific entity (eg. a community)
        // see users filtered by entity-user association table
        if (isset($cwh)) {
            $cwh->setCwhScopeFromSession();
            if (!empty($cwh->userEntityRelationTable)) {
                \Yii::$app->view->params['cwhScope'] = true;
                $mmTable = $cwh->userEntityRelationTable['mm_name'];
                $entityField = $cwh->userEntityRelationTable['entity_id_field'];
                $entityId = $cwh->userEntityRelationTable['entity_id'];
                $entity = key($cwh->scope);
                $network = \lispa\amos\cwh\models\CwhConfig::findOne(['tablename' => $entity]);
                if (!empty($network)) {
                    $networkObj = Yii::createObject($network->classname);
                    if ($networkObj->hasMethod('getMmClassName')) {
                        $userField = $networkObj->getMmUserIdFieldName();
                        $className = ($networkObj->getMmClassName());
                        $userEntityMm = $className::findOne([$entityField => $entityId, $userField => $userId]);
                        $networkModel = $networkObj->findOne($entityId);
                        if (!empty($networkModel) && !is_null($userEntityMm)) {
                            if ($userEntityMm->hasProperty('role')) {
                                $role = BaseAmosModule::t('amos' . $entity, $userEntityMm->role);
                                \Yii::$app->view->params['role'] = $role;
                            }
                            if ($userEntityMm->hasProperty('status')) {
                                $status = BaseAmosModule::t('amos' . $entity, $userEntityMm->status);
                                \Yii::$app->view->params['status'] = $status;
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * @param UserProfile $model
     * @return array
     */
    public function getWorkflowStatuses($model)
    {
        /** @var \cornernote\workflow\manager\components\WorkflowDbSource $workflowSource */
        $workflowSource = $model->getWorkflowSource();
        $allStatuses = $workflowSource->getAllStatuses(UserProfile::USERPROFILE_WORKFLOW);
        $userProfileWorkflowStatuses = [];
        foreach ($allStatuses as $status) {
            $userProfileWorkflowStatuses[$status->getId()] = AmosAdmin::t('amosadmin', $status->getLabel());
        }
        return $userProfileWorkflowStatuses;
    }

    /**
     * @return []
     */
    public function getAllOrganizations()
    {
        $admin = AmosAdmin::getInstance();
        /** @var  OrganizationsModuleInterface $organizationsModule */
        $organizationsModule = \Yii::$app->getModule($admin->getOrganizationModuleName());
        $organizationsModels = [];
        if (!is_null($organizationsModule)) {
            /** @var \yii\db\ActiveQuery $organizationsQuery */
            $organizationsQuery = $organizationsModule->getOrganizationsListQuery();
            $organizationsModels = $organizationsQuery->all();
        }
        return $organizationsModels;
    }

    /**
     * @return array
     */
    public function getAllOrganizationsForSelect()
    {
        $organizations = ArrayHelper::merge(['-1' => AmosAdmin::t('amosadmin', 'No prevalent partnership')],
            ArrayHelper::map($this->getAllOrganizations(), 'id', 'title'));
        return $organizations;
    }

    /**
     * @return UserProfile[]
     */
    public function getFacilitators()
    {
        /** @var ActiveQuery $query */
        $query = AmosAdmin::instance()->createModel('UserProfile')->find()->joinWith(['user']);
        $query->andWhere([UserProfile::tableName() . '.attivo' => 1]);
        $facilitatorUserIds = \Yii::$app->getAuthManager()->getUserIdsByRole('FACILITATOR');
        $query->andWhere(['in', 'user_id', $facilitatorUserIds]);
        $query->andWhere(['!=', 'dont_show_facilitator' ,  1]);

        $query->orderBy(['cognome' => SORT_ASC, 'nome' => SORT_ASC]);
        return $query->all();
    }

    /**
     * @return array
     */
    public function getFacilitatorsForSelect()
    {
        return ArrayHelper::merge([
            '-1' => AmosAdmin::t('amosadmin', 'Not selected')
        ], ArrayHelper::map($this->getFacilitators(), 'id', 'surnameName'));
    }

    /**
     * This method make the export columns array to set in the configurations of the DataProviderView widget.
     * @param UserProfile $model
     * @return array
     */
    public function getExportColumns($model)
    {
        return [
            'id',
            'nome',
            'cognome',
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    /** @var \lispa\amos\admin\models\UserProfile $model */
                    return $model->hasWorkflowStatus() ? AmosAdmin::t('amosadmin',
                        $model->getWorkflowStatus()->getLabel()) : '-';
                }
            ],
            'user.email' => [
                'attribute' => 'user.email',
                'label' => AmosAdmin::t('amosadmin', 'Email')
            ],
            'prevalentPartnership.name' => [
                'attribute' => 'prevalentPartnership.title',
                'label' => $model->getAttributeLabel('prevalentPartnership')
            ],
            'facilitatore.nomeCognome' => [
                'attribute' => 'facilitatore.nomeCognome',
                'label' => $model->getAttributeLabel('facilitatore')
            ],
            'notify_from_editorial_staff' => [
                'attribute' => 'notify_from_editorial_staff',
                'format' => 'boolean',
                'label' => $model->getAttributeLabel('notify_from_editorial_staff')
            ],
            [
                'attribute' => 'created_at',
                'value' => function ($model) {
                    /** @var \lispa\amos\admin\models\UserProfile $model */
                    if ($model->created_at) {
                        return Yii::$app->formatter->asDatetime($model->created_at);
                    } else {
                        return '';
                    }
                }
            ],
            [
                'attribute' => 'updated_at',
                'value' => function ($model) {
                    /** @var \lispa\amos\admin\models\UserProfile $model */
                    if ($model->updated_at) {
                        return Yii::$app->formatter->asDatetime($model->updated_at);
                    } else {
                        return '';
                    }
                }
            ]
        ];
    }

    public function actionAssociateFacilitator($id)
    {
        $this->setUpLayout('main');
        $this->setMmTargetKey('facilitatore_id');
        $this->setTargetUrl('associate-facilitator');
        $this->setTargetObjClassName(UserProfile::className());
        return $this->actionAssociateOneToMany($id);
    }

    /**
     * @param $id
     * @return string
     */
    public function actionAssociatePrevalentPartnership($id)
    {
        $this->setUpLayout('main');
        $this->setMmTargetKey('prevalent_partnership_id');
        $this->setTargetUrl('associate-prevalent-partnership');
        $admin = AmosAdmin::getInstance();
        /** @var  $organizationsModule OrganizationsModuleInterface */
        $organizationsModule = \Yii::$app->getModule($admin->getOrganizationModuleName());
        $this->setTargetObjClassName($organizationsModule->getOrganizationModelClass());
        return $this->actionAssociateOneToMany($id);
    }

    /**
     * Remove association with prevalent partnership profile
     * @param int|$id
     * @return mixed|null
     */
    public function actionRemovePrevalentPartnership($id)
    {
        if (Yii::$app->request->isPost && Yii::$app->request->isAjax) {
            $this->model = $this->findModel($id);
            $this->model->prevalent_partnership_id = null;
            $ok = $this->model->save(false);
            if (!$ok) {
                Yii::$app->getSession()->addFlash('danger',
                    AmosAdmin::t('amosadmin', 'Si &egrave; verificato un errore durante il salvataggio'));
            }
            return $this->redirect(['annulla-m2m', 'id' => $id]);
        }
        Yii::$app->session->addFlash('danger', BaseAmosModule::t('amoscore', '#unauthorized_flash_message'));
        return null;
    }

    /**
     * Lists all active users that was validated at least once.
     * @return string
     */
    public function actionValidatedUsers()
    {
        $this->setUpLayout('list');

        Url::remember();

        $availableViews = [];
        if (!empty(Yii::$app->session['cwh-scope'])) {
            $defaultViews = [
                'icon' => $this->iconView
            ];
            $viewType = reset($this->adminModule->defaultListViews);
            if (isset($defaultViews[$viewType])) {
                $availableViews[$viewType] = $defaultViews[$viewType];
            }
        } else {
            $defaultViews = [
                'icon' => $this->iconView,
                'grid' => $this->gridView,
            ];
            foreach ($this->adminModule->defaultListViews as $view) {
                if (isset($defaultViews[$view])) {
                    $availableViews[$view] = $defaultViews[$view];
                }
            }
        }
        $this->setAvailableViews($availableViews);

        $this->setDataProvider($this->getModelSearch()->searchOnceValidatedUsers(Yii::$app->request->getQueryParams()));
        $this->setCreateNewBtnParams();
        $this->setListsViewParams();
        $this->setTitleAndBreadcrumbs(AmosAdmin::t('amosadmin', 'Validated users'));
        $this->view->params['currentDashboard'] = $this->getCurrentDashboard();

        return $this->render('index', [
            'dataProvider' => $this->getDataProvider(),
            'model' => $this->getModelSearch(),
            'currentView' => $this->getCurrentView(),
            'availableViews' => $this->getAvailableViews(),
            'url' => ($this->url) ? $this->url : null,
            'fromAction' => 'validated-users'
        ]);
    }

    /**
     * Lists all active users that are a community manager for at least one community.
     * @return string
     */
    public function actionCommunityManagerUsers()
    {
        $this->setUpLayout('list');

        Url::remember();

        $this->setAvailableViews([
            'list' => $this->listView
        ]);
        $this->setCurrentView($this->getAvailableView('list'));
        $this->setDataProvider($this->getModelSearch()->searchCommunityManagerUsers(Yii::$app->request->getQueryParams()));
        $this->setCreateNewBtnParams();
        $this->setListsViewParams();
        $this->setTitleAndBreadcrumbs(AmosAdmin::t('amosadmin', 'Community Managers'));
        $this->view->params['currentDashboard'] = $this->getCurrentDashboard();

        return $this->render('index',
            [
                'dataProvider' => $this->getDataProvider(),
                'model' => $this->getModelSearch(),
                'currentView' => $this->getCurrentView(),
                'availableViews' => $this->getAvailableViews(),
                'url' => ($this->url) ? $this->url : null,
                'fromAction' => 'community-manager-users'
            ]);
    }

    /**
     * Lists all active users with "FACILITATOR" role.
     * @return string
     */
    public function actionFacilitatorUsers()
    {
        $this->setUpLayout('list');

        Url::remember();

        $this->setAvailableViews([
            'list' => $this->listView
        ]);
        $this->setCurrentView($this->getAvailableView('list'));
        $this->setDataProvider($this->getModelSearch()->searchFacilitatorUsers(Yii::$app->request->getQueryParams()));
        $this->setCreateNewBtnParams();
        $this->setListsViewParams();
        $this->setTitleAndBreadcrumbs(AmosAdmin::t('amosadmin', 'Facilitators'));
        $this->view->params['currentDashboard'] = $this->getCurrentDashboard();

        return $this->render('index',
            [
                'dataProvider' => $this->getDataProvider(),
                'model' => $this->getModelSearch(),
                'currentView' => $this->getCurrentView(),
                'availableViews' => $this->getAvailableViews(),
                'url' => ($this->url) ? $this->url : null,
                'fromAction' => 'facilitator-users'
            ]);
    }

    /**
     * Lists all inactive users.
     * @return string
     */
    public function actionInactiveUsers()
    {
        $this->setUpLayout('list');

        Url::remember();

        $this->setAvailableViews([
            'grid' => $this->gridView
        ]);
        $this->setCurrentView($this->getAvailableView('grid'));
        $this->setDataProvider($this->getModelSearch()->searchInactiveUsers(Yii::$app->request->getQueryParams()));
        $this->setCreateNewBtnParams();
        $this->setListsViewParams();
        $this->setTitleAndBreadcrumbs(AmosAdmin::t('amosadmin', 'Inactive users'));
        $this->view->params['currentDashboard'] = $this->getCurrentDashboard();

        return $this->render('index',
            [
                'dataProvider' => $this->getDataProvider(),
                'model' => $this->getModelSearch(),
                'currentView' => $this->getCurrentView(),
                'availableViews' => $this->getAvailableViews(),
                'url' => ($this->url) ? $this->url : null,
                'fromAction' => 'inactive-users'
            ]);
    }

    /**
     * Override default delete because it is not allowed to delete a user profile. It can only be deactivated.
     * @param integer $id
     * @return \yii\web\Response
     */
    public function actionDelete($id)
    {
        Yii::$app->getSession()->addFlash('danger',
            AmosAdmin::t('amosadmin', 'A user profile can only be deactivated. It cannot be deleted.'));
        return $this->redirect(Url::previous());
    }

    /**
     * This action deactivate an user profile.
     * @param int $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionDeactivateAccount($id)
    {
        $this->model = $this->findModel($id);
        $this->model->setScenario(UserProfile::SCENARIO_REACTIVATE_DEACTIVATE_USER);

        if ($this->model->isDeactivated()) {
            Yii::$app->getSession()->addFlash('danger', AmosAdmin::t('amosadmin', 'This user profile is already deactivated.'));
            return $this->redirect(Url::previous());
        }

        $isLoggedUser = (Yii::$app->getUser()->getId() == $this->model->user_id);

        if (!Yii::$app->user->can('DeactivateAccount', ['model' => $this->model])) {
            if ($isLoggedUser && (Yii::$app->user->can('ADMIN') || Yii::$app->user->can('AMMINISTRATORE_UTENTI'))) {
                Yii::$app->getSession()->addFlash('danger', AmosAdmin::t('amosadmin', 'You cannot deactivate your user profile.'));
            } else {
                Yii::$app->getSession()->addFlash('danger', AmosAdmin::t('amosadmin', 'You have not the permission to deactivate an user profile.'));
            }
            return $this->redirect(Url::previous());
        }

        if ($isLoggedUser && (Yii::$app->user->can('ADMIN') || Yii::$app->user->can('AMMINISTRATORE_UTENTI'))) {
            Yii::$app->getSession()->addFlash('danger', AmosAdmin::t('amosadmin', 'You cannot deactivate your user profile.'));
            return $this->redirect(Url::previous());
        }

        $ok = $this->model->deactivateUserProfile();
        $subjectView = '@vendor/lispa/amos-admin/src/mail/user/deactivateaccount-subject';
        $contentView = '@vendor/lispa/amos-admin/src/mail/user/deactivateaccount-html';
        UserProfileUtility::sendMail($this->model, $subjectView, $contentView);

        if ($ok) {
            Yii::$app->getSession()->addFlash('success', AmosAdmin::t('amosadmin', 'User profile deactivated successfully.'));
            if ($isLoggedUser) {
                return $this->redirect(['/admin/security/logout']);
            }
        } else {
            Yii::$app->getSession()->addFlash('danger', AmosAdmin::t('amosadmin', 'Error while deactivating user profile.'));
        }

        return $this->redirect(Url::previous());
    }

    /**
     * This action reactivate an user profile.
     * @param int $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionReactivateAccount($id)
    {
        $this->model = $this->findModel($id);
        $this->model->setScenario(UserProfile::SCENARIO_REACTIVATE_DEACTIVATE_USER);

        if ($this->model->isActive()) {
            Yii::$app->getSession()->addFlash('danger',
                AmosAdmin::t('amosadmin', 'This user profile is already active.'));
            return $this->redirect(Url::previous());
        }

        if (!Yii::$app->user->can('ADMIN') && !Yii::$app->user->can('AMMINISTRATORE_UTENTI')) {
            Yii::$app->getSession()->addFlash('danger',
                AmosAdmin::t('amosadmin', 'You have not the permission to reactivate an user profile.'));
            return $this->redirect(Url::previous());
        }

        $ok = $this->model->activateUserProfile();
        $reactRequest = $this->model->userProfileReactivationRequest;
        if ($reactRequest) {
            $reactRequest->delete();
        }

        if ($ok) {
            Yii::$app->getSession()->addFlash('success',
                AmosAdmin::t('amosadmin', 'User profile reactivated successfully.'));
        } else {
            Yii::$app->getSession()->addFlash('danger',
                AmosAdmin::t('amosadmin', 'Error during reactivation of the user profile.'));
        }

        return $this->redirect(Url::previous());
    }

    /**
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * @throws \yii\db\StaleObjectException
     */
    public function actionRejectReactivationRequest($id)
    {
        $reactRequ = UserProfileReactivationRequest::findOne(['user_profile_id' => $id]);
        if ($reactRequ) {
            $reactRequ->delete();
        } else throw new NotFoundHttpException();
        return $this->redirect(Url::previous());
    }

    /**
     * Action that check if there is already a default facilitator in the system.
     * @param int $id The user profile id (N.B. NOT THE USER ID!!!)
     * @return string
     * @throws AdminException
     * @throws NotFoundHttpException
     */
    public function actionDefFacilitatorPresent($id)
    {
        if (!Yii::$app->request->getIsAjax()) {
            throw new AdminException(AmosAdmin::t('amosadmin', 'The request is not via AJAX'));
        }
        if (!Yii::$app->request->getIsPost()) {
            throw new AdminException(AmosAdmin::t('amosadmin', 'The request is not via POST method'));
        }
        $this->model = $this->findModel($id);
        $facilitatorUserProfile = $this->model->getDefaultFacilitator();
        return json_encode([
            'defaultFaciliatorPresent' => (!is_null($facilitatorUserProfile) ? 1 : 0),
            'facilitatorNameSurname' => (!is_null($facilitatorUserProfile) ? $facilitatorUserProfile->getNomeCognome() : '')
        ]);
    }

    /**
     * Section Contacts (in edit or view mode) on user profile tab network
     * @param int $id
     * @param bool $isUpdate
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionContacts($id, $isUpdate = false)
    {
        if (\Yii::$app->request->isAjax) {
            $this->setUpLayout(false);
            $this->model = $this->findModel($id);

            return $this->render('contacts', [
                'model' => $this->model,
                'isUpdate' => $isUpdate
            ]);
        }

        Yii::$app->getSession()->addFlash('danger', AmosAdmin::t('amosadmin', 'This action must be called via AJAX.'));
        return $this->redirect(Url::previous());
    }

    /**
     * @param int $id
     * @return string
     */
    public function actionPasswordExpired($id)
    {
        $this->setUpLayout('main');

        $messaggio = \Yii::t('amosadmin', '#new_password_is_expired');

        return $this->render('utenza_scaduta',
            [
                'message' => $messaggio,
                'user_id' => $id
            ]);
    }

    /**
     * This is a GDPR required function to allow user drop theri own account and all his data
     * @param int $id UserProfile
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionDropAccount($id)
    {
        //Avoid admin self-dropping
        if (\Yii::$app->user->can('ADMIN') && $id == \Yii::$app->user->id) {
            throw new \Exception('Hey! Can\'t Drop ADMIN User');
        }

        $this->setUpLayout('form');
        $user = $this->findModel($id);
        $model = new DropAccountForm();

        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                //New drop instance
                $dropController = new UserDropController('user_drop', $this->module);

                //Irreversible action of user drop
                $moduleAdmin = \Yii::$app->getModule('admin');
                if (!empty($moduleAdmin) && $moduleAdmin->hardDelete) {
                    $dropController->dropEverything($user->user_id);
                } else {
                    $dropController->softDropEverything($user->user_id);
                }

                Yii::$app->getSession()->addFlash('success', AmosAdmin::t('amosadmin', 'Account Deleted.'));

                //Back to home because youre not logged anymore
                $redirectUrl = Url::home();
                if (\Yii::$app->user->can('ADMIN')) {
                    $redirectUrl = '/dashboard';
                }
                return $this->redirect($redirectUrl);
            } else {
                return $this->render('drop-account', ['model' => $model, 'id' => $id]);
            }
        } else {
            return $this->render('drop-account', ['model' => $model, 'id' => $id]);
        }
    }

    /**
     * Request authorization to user connect to $serviceName
     * Stores authorization data in SocialAuthServices table
     * Synchronize the requested service calling synchronizeGoogleService method of socialAuth module
     *
     * @param int|null $id
     * @param string $serviceName
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionEnableGoogleService($id = null, $serviceName = '')
    {
        $this->setUpLayout('empty');
        $socialAuth = Yii::$app->getModule('socialauth');
        $authUrl = null;
        $message = '';
        if (!is_null($socialAuth)) {
            /** @var \Google_Client $client */
            $client = $socialAuth->getClient('google');
            if (!is_null($client)) {
                if (is_null($id)) {
                    $userId = Yii::$app->user->id;
                    $this->model = UserProfile::findOne(['user_id' => $userId]);
                } else {
                    $this->model = $this->findModel($id);
                }
                if (!is_null($this->model)) {
                    $socialAuthUser = $this->model->getSocialAuthUsers()->andWhere(['provider' => 'google'])->one();
                    if ($socialAuthUser) {
                        if (!empty($serviceName)) {
                            Yii::$app->session->set('serviceName', $serviceName);
                        } else {
                            if (Yii::$app->session->has('serviceName')) {
                                $serviceName = Yii::$app->session->get('serviceName');
                            }
                        }
                        $service = $socialAuthUser->getServices()->andWhere(['service' => $serviceName])->one();
                        if (!$service) {
                            // Request authorization from the user.
                            $client->setRedirectUri(Yii::$app->urlManager->createAbsoluteUrl('admin/user-profile/enable-google-service'));
                            if ($serviceName == 'contacts') {
                                $client->addScope("https://www.googleapis.com/auth/" . $serviceName . '.readonly');
                            } else {
                                $client->setScopes("https://www.googleapis.com/auth/" . $serviceName);
                            }

                            $client->setAccessType('offline');

                            if (!empty($_GET['code'])) {
                                $get = filter_input(INPUT_GET, 'code');
                                $client->fetchAccessTokenWithAuthCode($get);
                                $accessToken = $client->getAccessToken();
                                if (!empty($accessToken) && !empty($accessToken['access_token'])) {
                                    $message .= 'accesstoken ' . $accessToken['access_token'];
                                } else {
                                    $message .= 'no access_token';
                                }

                                if (!$client->getRefreshToken()) {
                                    //No refresh token generated, the access token is old/deleted/invalid
                                    //revoke token and ask for a new authorization to store in social auth service
                                    $client->revokeToken($accessToken['access_token']);
                                    $authUrl = $client->createAuthUrl();
                                    return $this->redirect($authUrl);
                                }
                            } else {
                                $authUrl = $client->createAuthUrl();
                                return $this->redirect($authUrl);
                            }

                            if (isset($accessToken) && !empty($accessToken['refresh_token'])) {
                                $serviceName = Yii::$app->session->get('serviceName');
                                $service = new SocialAuthServices();
                                $service->service = $serviceName;
                                $service->social_users_id = $socialAuthUser->id;
                                $service->access_token = $accessToken['access_token'];
                                $service->token_type = $accessToken['token_type'];
                                $service->expires_in = $accessToken['expires_in'];
                                $service->refresh_token = $accessToken['refresh_token'];
                                $service->token_created = $accessToken['created'];
                                if ($service->save()) {
                                    $message = AmosAdmin::t('amosadmin', 'Impostazione salvata con successo');
                                }
                                Yii::$app->session->offsetUnset('serviceName');
                            }
                        }
                        if ($service) {
                            try {
                                $message = $socialAuth->synchronizeGoogleService($service);
                            } catch (Exception $e) {
                                $message = $e->getMessage();
                            }
                        }
                    }
                }
            }
        }
        return $this->render('enable-google-service', ['message' => $message]);
    }

    /**
     * Revoke user authorization to connect to google $serviceName
     * Delete all records created by service synchronization on user google account
     * Delete data of user service previously stored in SocialAuth Services table
     *
     * @param int $id
     * @param string $serviceName
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionDisableGoogleService($id, $serviceName = 'calendar')
    {
        $this->setUpLayout('empty');
        $this->model = $this->findModel($id);
        $socialAuth = Yii::$app->getModule('socialauth');
        $authUrl = null;
        if (!is_null($socialAuth)) {
            $client = $socialAuth->getClient('google');
            if (!is_null($client)) {
                $socialAuthUser = $this->model->getSocialAuthUsers()->andWhere(['provider' => 'google'])->one();
                if ($socialAuthUser) {
                    $service = $socialAuthUser->getServices()->andWhere(['service' => $serviceName])->one();
                    if ($service) {
                        $socialAuth->removeGoogleService($service);
                        $client->revokeToken($service->access_token);
                        $service->delete();
                    }
                }
            }
        }
        if (Yii::$app->request->isPost) {
            return json_encode(true);
        }
        return $this->render('enable-google-service');
    }

    /**
     * Given userProfile id, check if the specified $serviceName provided by $provider is active for the user.
     * Returns json, with enabled
     *
     * @param int $id - the user profile Id
     * @param string $provider
     * @param string $serviceName
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionGetSocialServiceStatus($id, $provider = 'google', $serviceName = '')
    {
        $service['enabled'] = -1;
        $socialAuth = Yii::$app->getModule('socialauth');
        if (!is_null($socialAuth)) {
            $this->model = $this->findModel($id);
            $socialAuthUser = $this->model->getSocialAuthUsers()->andWhere(['provider' => $provider])->one();
            if ($socialAuthUser) {
                $service['enabled'] = $socialAuthUser->getServices()->andWhere(['service' => $serviceName])->count();
                if ($service['enabled']) {
                    $action = 'disable';
                } else {
                    $action = 'enable';
                }
                $service['url'] = '/admin/user-profile/' . $action . '-' . $provider . '-service?id=' . $this->model->id . '&serviceName=' . $serviceName;
            }
        }
        return json_encode($service);
    }

    /**
     * Given userProfile id, check if the specified $serviceName provided by $provider is active for the user.
     * Returns json, with enabled
     *
     * @param $id - the user profile Id
     * @param null|string $provider
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionGetSocialUser($id, $provider = '')
    {
        $socialAuthUser = 0;
        $socialAuth = Yii::$app->getModule('socialauth');
        if (!is_null($socialAuth)) {
            $this->model = $this->findModel($id);
            $socialAuthUserQuery = $this->model->getSocialAuthUsers();
            if (!empty($provider)) {
                $socialAuthUserQuery->andWhere(['provider' => $provider]);
            }
            $socialAuthUser = $socialAuthUserQuery->count();
        }
        return json_encode($socialAuthUser);
    }
}
