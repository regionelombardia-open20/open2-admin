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

use lispa\amos\admin\base\ConfigurationManager;
use lispa\amos\admin\exceptions\AdminException;
use lispa\amos\admin\models\UserProfile;
use lispa\amos\admin\utility\UserProfileUtility;
use lispa\amos\admin\widgets\graphics\WidgetGraphicMyProfile;
use lispa\amos\admin\widgets\graphics\WidgetGraphicsUsers;
use lispa\amos\admin\widgets\icons\WidgetIconMyProfile;
use lispa\amos\admin\widgets\icons\WidgetIconUserProfile;
use lispa\amos\core\interfaces\SearchModuleInterface;
use lispa\amos\core\module\AmosModule;
use lispa\amos\core\user\User;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class AmosAdmin
 * @package lispa\amos\admin
 */
class AmosAdmin extends AmosModule implements SearchModuleInterface
{
    const site_key_param = 'google_recaptcha_site_key';

    //google contacts session keys
    const GOOGLE_CONTACTS = 'contacts';
    const GOOGLE_CONTACTS_PLATFORM = 'contacts_platform';
    const GOOGLE_CONTACTS_NOT_PLATFORM = 'contacts_not_platform';

    public $controllerNamespace = 'lispa\amos\admin\controllers';
    public $whiteListRoles = [];
    public $name = 'Utenti';
    public $searchListFields = [];

    public $hardDelete = false;

    /**
     * @var bool $enableRegister - set to true to enable user register to the application and create his own userprofile
     */
    public $enableRegister = false;
    
    /**
     * @var bool $showLogInRegisterButton - set to true to enable user register button on login form
     */
    
    public $showLogInRegisterButton = true;

    /**
     * @var bool $hideStandardLoginPageSection If true hide the login page section where the user can insert username and password.
     */
    public $hideStandardLoginPageSection = false;

    /**
     *  @var string $textWarningForRegisterDisabled - set the text that will to show if the register is disabled
     */
    public $textWarningForRegisterDisabled;

    /**
     * @var bool $enableUserContacts
     * enable connection to users, send private messages, and see 'contacts' in section 'NETWORK' of the user profile
     */
    public $enableUserContacts = true;

    /**
     * @var bool $enableSendMessage If this is true and $enableUserContacts is false all users see the "send message" button on view icon.
     */
    public $enableSendMessage = false;

    /**
     * @var bool
     */
    public $hideContactsInView = false;

    /**
     * @var bool
     */
    public $accordionNetworkOpenOnDefault = false;

    /**
     * @var bool $cached - enable or not admin query caching
     */
    public $cached = false;

    /**
     * @var int $cacheDuration
     * seconds of query caching duration if $cache = true - default is 1 day
     */
    public $cacheDuration = 84600;

    /**
     * @var bool $bypassWorkflow If true the plugin bypass the user profile workflow and show nothing of it.
     */
    public $bypassWorkflow = false;

    /**
     * This is the html used to render the subject of the e-mail. In the view is available the variable $profile
     * that is an instance of 'lispa\amos\admin\models\UserProfile'
     * @var string
     */
    public $htmlMailSubject = '@vendor/lispa/amos-admin/src/mail/user/credenziali-subject';

    /**
     * This is the html used to render the message of the e-mail. In the view is available the variable $profile
     * that is an instance of 'lispa\amos\admin\models\UserProfile'
     * @var string
     */
    public $htmlMailContent = '@vendor/lispa/amos-admin/src/mail/user/credenziali-html';

    /**
     * This is the text used to render the message of the e-mail. In the view is available the variable $profile
     * that is an instance of 'lispa\amos\admin\models\UserProfile'
     * @var string
     */
    public $textMailContent = '@vendor/lispa/amos-admin/src/mail/user/credenziali-text';

    /**
     * @var array $fieldsConfigurations This array contains all configurations for boxes and fields.
     */
    public $fieldsConfigurations = [
        'boxes' => [
            'box_informazioni_base' => ['form' => true, 'view' => true]
        ],
        'fields' => [
            'nome' => ['form' => true, 'view' => true, 'referToBox' => 'box_informazioni_base'],
            'cognome' => ['form' => true, 'view' => true, 'referToBox' => 'box_informazioni_base'],
            'userProfileImage' => ['form' => true, 'view' => true, 'referToBox' => 'box_foto']
        ]
    ];

    /**
     * @var array $profileRequiredFields - mandatory fields in user profile form
     */
    public $profileRequiredFields = [
        'nome',
        'cognome',
        'status',
        'presentazione_breve'
    ];

    /**
     * @var ConfigurationManager $confManager
     */
    public $confManager = null;

    /**
     * At user creation, it is possible to customize the Rbac role to assign to a new user, default is BASIC_USER role.
     *
     * @var string $defaultUserRole
     */
    public $defaultUserRole = 'BASIC_USER';

    /**
     * This is the module name (you used as array key in modules configuration of your platform) referring to a module
     * extending lispa\amos\core\interfaces\OrganizationsModuleInterface
     * It is used to give the possibility to customize the entity type used to set user profile prevalent partnership, for example.
     *
     * @var string $organizationModuleName
     */
    private $organizationModuleName = "organizzazioni";

    /**
     * @var bool $allowLoginWithEmailOrUsername
     */
    public $allowLoginWithEmailOrUsername = true;

    /**
     * @var bool $userCanSelectUsername
     */
    public $userCanSelectUsername = false;

    /**
     * @var bool $disableUpdateChangeStatus
     */
    public $disableUpdateChangeStatus = false;

    /**
     * @var bool $enableWorkflowChangeStatusMails
     */
    public $enableWorkflowChangeStatusMails = true;

    /**
     * @var string $whiteListProfileImageExts List of the allowed extensions for the upload of profile image.
     */
    public $whiteListProfileImageExts = 'jpeg, jpg, png, gif';

    /**
     * @var string $moduleName
     */
    private static $moduleName = 'admin';

    /**
     * @var array $defaultListViews This set the default order for the views in lists
     */
    public $defaultListViews = ['icon', 'grid', 'list'];

    /**
     * @var bool $forceDefaultViewType
     */
    public $forceDefaultViewType = false;

    /**
     *  At the creation of the user, set the user (Tutor) as contact of the created user
     * @var null|int $user_id
     */
    public $associateTutor = null;

    /**
     *  At the creation of the user, send a private message from the tutor
     * @var null|boolean
     */
    public $defaultPrivateMessage = false;

    /**
     * Set the backend action (url) to go to if the private message above is sent
     * @var null|string
     */
    public $helpLinkAction = null;

    /**
     * @var bool $roleAndAreaOnOrganizations If true, hide roles and areas standard and enable it on the single network organization row
     */
    public $roleAndAreaOnOrganizations = false;

    /**
     * @var bool $roleAndAreaFromOrganizationsWithTypeCat If true, uses "type_cat" field in the roles and areas queries
     */
    public $roleAndAreaFromOrganizationsWithTypeCat = false;

    /**
     * @var bool $sendUserAssignmentsReportOnDelete Send a report of user assignments via email if true
     */
    public $sendUserAssignmentsReportOnDelete = false;

    /**
     * @var bool $showFacilitatorForModuleSelect Display a select multiple field for assigning specific module facilitator permissions in the user profile form
     */
    public $showFacilitatorForModuleSelect = false;

    /**
     * @var bool $dontCheckOneTagPresent If true the model validation doesn't check if there's at least one tag present for non ADMIN users.
     */
    public $dontCheckOneTagPresent = false;

    /**
     * @var bool $enableMultiUsersSameCF If true the model validation doesn't check the unique of che fiscal code.
     */
    public $enableMultiUsersSameCF = false;

    /**
     * @inheritdoc
     */
    public $db_fields_translation = [
        [
            'namespace' => 'lispa\amos\admin\models\UserProfileArea',
            'attributes' => ['name'],
            'category' => 'amosadmin'
        ],
        [
            'namespace' => 'lispa\amos\admin\models\UserProfileRole',
            'attributes' => ['name'],
            'category' => 'amosadmin'
        ],
    ];

    /**
     * @var bool
     */
    public $precompileUsernameOnFirstAccess = false;

    /**
     * @var bool $enableUserContactsWidget
     */
    public $enableUserContactsWidget = true;
    
    public $defaultProfileImagePath = "@webroot/img/defaultProfilo.png";

    /**
     * @return string
     */
    public function getOrganizationModuleName()
    {
        return $this->organizationModuleName;
    }

    /**
     * @param string $organizationModuleName
     */
    public function setOrganizationModuleName($organizationModuleName)
    {
        $this->organizationModuleName = $organizationModuleName;
    }

    /**
     * Module name
     * @return string
     */
    public static function getModuleName()
    {
        return static::$moduleName;
    }

    /**
     * @param string $moduleName
     */
    public static function setModuleName($moduleName)
    {
        static::$moduleName = $moduleName;
    }

    public static function getModelSearchClassName()
    {
        return __NAMESPACE__ . '\models\search\UserProfileSearch';
    }

    public static function getModuleIconName()
    {
        return 'users';
    }

    /**
     * Module Initialization
     * @throws AdminException
     */
    public function init()
    {
        parent::init();

        \Yii::setAlias('@lispa/amos/' . static::getModuleName() . '/controllers', __DIR__ . '/controllers/');
        // initialize the module with the configuration loaded from config.php
        \Yii::configure($this, require(__DIR__ . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php'));

        $this->confManager = new ConfigurationManager([
            'fieldsConfigurations' => $this->fieldsConfigurations
        ]);
        $this->confManager->checkFieldsConfigurationsStructure();

        //dependency injection of reCaptcha
        if (isset($this->reCaptcha)) {
            if (isset(\Yii::$app->params[self::site_key_param])) {
                $this->reCaptcha->siteKey = \Yii::$app->params[self::site_key_param];
            }
            \Yii::$app->set('reCaptcha', $this->reCaptcha);
        }

    }

    /**
     * Array of widget-namespaces that belong to the module
     * @return array
     */
    public function getWidgetGraphics()
    {
        return [
            WidgetGraphicMyProfile::className(),
            WidgetGraphicsUsers::className()
        ];
    }

    /**
     * Array of widget-namespaces that belong to the module
     * @return array
     */
    public function getWidgetIcons()
    {
        return [
            WidgetIconMyProfile::className(),
            WidgetIconUserProfile::className()
        ];
    }

    /**
     * Get roles white-list
     * @return array
     * @deprecated
     */
    public function getWhiteListRules() // TODO change to getWhiteListRoles()
    {
        trigger_error('Deprecated: this function is repleca by getWhiteListRoles', E_NOTICE);
        return $this->whiteListRoles;
    }

    /**
     * Return list of white Roles
     * @return []
     */
    public function getWhiteListRoles()
    {
        return $this->whiteListRoles;
    }

    /**
     * Get default models
     * @return array
     */
    protected function getDefaultModels()
    {
        return [
            'UserProfile' => __NAMESPACE__ . '\\' . 'models\UserProfile',
            'UserContact' => __NAMESPACE__ . '\\' . 'models\UserContact',
            'UserProfileStatiCivili' => __NAMESPACE__ . '\\' . 'models\UserProfileStatiCivili',
            'UserProfileTitoliStudio' => __NAMESPACE__ . '\\' . 'models\UserProfileTitoliStudio',
            'User' => 'lispa\amos\core\user\User',
            'IstatComuni' => 'lispa\amos\comuni\models\IstatComuni',
            'IstatProvince' => 'lispa\amos\comuni\models\IstatProvince',
            'IstatRegioni' => 'lispa\amos\comuni\models\IstatRegioni',
            'IstatNazioni' => 'lispa\amos\comuni\models\IstatNazioni',
            'Ruoli' => 'common\models\Ruoli',
            'UserProfileSearch' => __NAMESPACE__ . '\\' . 'models\search\UserProfileSearch',
            'UserContactSearch' => __NAMESPACE__ . '\\' . 'models\search\UserContactSearch',
            'UserProfileTitoliStudioSearch' => __NAMESPACE__ . '\\' . 'models\search\UserProfileTitoliStudioSearch',
        ];
    }

    /**
     * The method create a new account. It creates a new User and new UserProfile only with
     * name, surname and email. The email must be unique in the database! This method returns
     * the user id if all goes well. It returns boolean false in case of errors.
     * @param string $name
     * @param string $surname
     * @param string $email
     * @param \lispa\amos\community\models\Community $community
     * @param bool|false $sendCredentials if credential mail must be sent to the newly created user
     * @return array
     */
    public function createNewAccount($name, $surname, $email, $privacy, $sendCredentials = false, $community = null)
    {
        return UserProfileUtility::createNewAccount($name, $surname, $email, $privacy, $sendCredentials, $community);
    }

    /**
     * @param \Google_Service_PeopleService $serviceGoogle
     * @return string $message
     */
    public static function synchronizeGoogleContacts($serviceGoogle)
    {
        $connections = $serviceGoogle->people_connections->listPeopleConnections(
            'people/me', array('personFields' => 'photos,names,emailAddresses'));
        $items = $connections->getTotalItems();
        $message = AmosAdmin::t('amosadmin', 'Google contacts: {count}', ['count' => $items]);
        $session = Yii::$app->session;
        if ($items) {
            $contacts = [];
            $inPlatform = [];
            $notInplatform = [];
            $i = 0;
            /**  @var \Google_Service_PeopleService_Person $connection */
            foreach ($connections as $connection) {
                $contact = [];
                $names = [];
                $photos = [];
                $names['displayName'] = ArrayHelper::getColumn($connection->getNames(), 'displayName');
                $names['name'] = ArrayHelper::getColumn($connection->getNames(), 'givenName');
                $names['surname'] = ArrayHelper::getColumn($connection->getNames(), 'familyName');
                $photos['url'] = ArrayHelper::getColumn($connection->getPhotos(), 'url');
                $emails = ArrayHelper::getColumn($connection->getEmailAddresses(), 'value');
                $emails = array_unique($emails);
                foreach ($emails as $email) {
                    $contact['email'] = $email;
                    $contact['names'] = $names;
                    $contact['photos'] = $photos;
                    $user = User::findByEmail($email);
                    if (!is_null($user)) {
                        $contact['user_id'] = $user->id;
                        $inPlatform[] = $user->id;
                    } else {
                        $notInplatform[] = $i;
                    }
                    $contacts[$i] = $contact;
                    $i++;

                    $session->set(self::GOOGLE_CONTACTS, $contacts);
                    $session->set(self::GOOGLE_CONTACTS_PLATFORM, $inPlatform);
                    $session->set(self::GOOGLE_CONTACTS_NOT_PLATFORM, $notInplatform);
                }
            }
            $message .= '<br/>' . AmosAdmin::t('amosadmin', 'Registered in \'{appName}\': {count}', ['appName' => \Yii::$app->name, 'count' => count($inPlatform)]);
        }
        return $message;
    }

    public static function removeGoogleContacts()
    {
        $session = Yii::$app->session;
        if ($session->has(self::GOOGLE_CONTACTS)) {
            $session->remove(self::GOOGLE_CONTACTS);
        }
        if ($session->has(self::GOOGLE_CONTACTS_PLATFORM)) {
            $session->remove(self::GOOGLE_CONTACTS_PLATFORM);
        }
        if ($session->has(self::GOOGLE_CONTACTS_NOT_PLATFORM)) {
            $session->remove(self::GOOGLE_CONTACTS_NOT_PLATFORM);
        }
    }

    /**
     * @param null|int $id
     * @return null|string
     */
    public static function fetchGoogleContacts($id = null)
    {
        $socialAuth = Yii::$app->getModule('socialauth');
        if (!is_null($socialAuth)) {
            if (is_null($id)) {
                $userId = Yii::$app->user->id;
                $profile = UserProfile::findOne(['user_id' => $userId]);
            } else {
                $profile = UserProfile::findOne($id);
            }
            if (!is_null($profile)) {
                $socialAuthUser = $profile->getSocialAuthUsers()->andWhere(['provider' => 'google'])->one();
                if ($socialAuthUser) {
                    $service = $socialAuthUser->getServices()->andWhere(['service' => 'contacts'])->one();
                    if ($service) {
                        return $socialAuth->synchronizeGoogleService($service);
                    }
                }
            }
        }
        return null;
    }

    public static function getModelClassName() {
        return __NAMESPACE__ . '\models\UserProfile';
    }

}
