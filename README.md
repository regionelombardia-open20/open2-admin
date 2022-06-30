# Amos Admin 

User Profile management

### Installation

Add admin requirement in your composer.json:
```
"open20/amos-admin": "dev-master",
```

Enable the Admin modules in modules-amos.php, add :
```
 'admin' => [
	'class' => 'open20\amos\admin\AmosAdmin',
 ],

```
add admin migrations to console modules (console/config/migrations-amos.php):
```
'@vendor/open20/amos-admin/src/migrations'
```

If tags are needed enable tag roots for user in tag plugin.
To do so: 
- Activate tag plugin (check it is in modules configuration list)
- Open tag manager (admin privilege is required) url: <yourPlatformurl>/tag/manager
- Click on tag tree roots to enable
- In the last select of the page (user interest), enable for needed user roles. 

### Configurable fields 

Here the list of configurable fields, properties of module AmosAdmin.
If some property default is not suitable for your project, you can configure it in module, eg: 

```php
 'admin' => [
	'class' => 'open20\amos\admin\AmosAdmin',
	'enableRegister' => true, //changed property (default was false)
 ],
 
```
configuration example: 

```php
$modules['admin'] =  [
    'class' => 'open20\amos\admin\AmosAdmin',
	'enableRegister' => true,
	'organizationModuleName' => 'organizations',
	'modelMap' => [
		'User' => [
			'class' => 'open20\amos\porting\console\models\PortingUser',
		]
	],
     'fieldsConfigurations' => [
            'boxes' => [
                'box_account_data' => ['form' => true, 'view' => true],
                'box_dati_accesso' => ['form' => true, 'view' => true],
                'box_dati_contatto' => ['form' => true, 'view' => true],
                'box_dati_fiscali_amministrativi' => ['form' => false, 'view' => false],
                'box_dati_nascita' => ['form' => false, 'view' => false],
                'box_email_frequency' => ['form' => true, 'view' => true],
                'box_facilitatori' => ['form' => true, 'view' => true],
                'box_foto' => ['form' => true, 'view' => true],
                'box_informazioni_base' => ['form' => true, 'view' => true],
                'box_presentazione_personale' => ['form' => true, 'view' => true],
                'box_prevalent_partnership' => ['form' => true, 'view' => true],
                'box_privacy' => ['form' => true, 'view' => true],
                'box_questio' => ['form' => false, 'view' => false],
                'box_role_and_area' => ['form' => true, 'view' => true],
                'box_social_account' => ['form' => true, 'view' => true],
            ],
            'fields' => [
                'attivo' => ['form' => true, 'view' => true, 'referToBox' => 'box_account_data'],
                'codice_fiscale' => ['form' => false, 'view' => false, 'referToBox' => 'box_dati_fiscali_amministrativi'],
                'cognome' => ['form' => true, 'view' => true, 'referToBox' => 'box_informazioni_base'],
                'default_facilitatore' => ['form' => true, 'view' => true],
                'email' => ['form' => true, 'view' => false, 'referToBox' => 'box_dati_contatto'],
                'email_pec' => ['form' => false, 'view' => false, 'referToBox' => 'box_dati_contatto'],
                'facebook' => ['form' => true, 'view' => true, 'referToBox' => 'box_social_account'],
                'facilitatore_id' => ['form' => true, 'view' => true, 'referToBox' => 'box_facilitatori'],
                'googleplus' => ['form' => true, 'view' => true, 'referToBox' => 'box_social_account'],
                'linkedin' => ['form' => true, 'view' => true, 'referToBox' => 'box_social_account'],
                'nascita_comuni_id' => ['form' => false, 'view' => false, 'referToBox' => 'box_dati_nascita'],
                'nascita_data' => ['form' => false, 'view' => false, 'referToBox' => 'box_dati_nascita'],
                'nascita_nazioni_id' => ['form' => false, 'view' => false, 'referToBox' => 'box_dati_nascita'],
                'nascita_province_id' => ['form' => false, 'view' => false, 'referToBox' => 'box_dati_nascita'],
                'nome' => ['form' => true, 'view' => true, 'referToBox' => 'box_informazioni_base'],
                'note' => ['form' => true, 'view' => false, 'referToBox' => 'box_informazioni_base'],
                'presentazione_breve' => ['form' => true, 'view' => true, 'referToBox' => 'box_informazioni_base'],
                'presentazione_personale' => ['form' => true, 'view' => true, 'referToBox' => 'box_presentazione_personale'],
                'prevalent_partnership_id' => ['form' => true, 'view' => true, 'referToBox' => 'box_prevalent_partnership'],
                'privacy' => ['form' => true, 'view' => true, 'referToBox' => 'box_privacy'],
                'sesso' => ['form' => true, 'view' => false, 'referToBox' => 'box_informazioni_base'],
                'telefono' => ['form' => true, 'view' => false, 'referToBox' => 'box_dati_contatto'],
                'twitter' => ['form' => true, 'view' => true, 'referToBox' => 'box_social_account'],
                'ultimo_accesso' => ['form' => true, 'view' => true, 'referToBox' => 'box_account_data'],
                'ultimo_logout' => ['form' => true, 'view' => true, 'referToBox' => 'box_account_data'],
                'username' => ['form' => true, 'view' => false, 'referToBox' => 'box_dati_accesso'],
                'user_profile_age_group_id' => ['form' => true, 'view' => true, 'referToBox' => 'box_informazioni_base'],
                'user_profile_area_id' => ['form' => true, 'view' => false, 'referToBox' => 'box_role_and_area'],
                'userProfileImage' => ['form' => true, 'view' => true, 'referToBox' => 'box_foto'],
                'user_profile_role_id' => ['form' => true, 'view' => false, 'referToBox' => 'box_role_and_area'],
            ]
        ]
    ];
    .
    .
    . 
    return $modules;
```



### Module configuration params 

* **enableRegister** - boolean, default = false  
set to true to enable user register to the application and create his own userprofile  

* **showLogInRegisterButton** - boolean, default = true  
set to true to enable user register button on login form  

* **hideStandardLoginPageSection** - boolean, default = false  
If true hide the login page section where the user can insert username and password.  

* **textWarningForRegisterDisabled** - string
set the text that will to show if the register is disabled

* **enableUserContacts** - boolean, default = true  
enable connection to users, send private messages, and see 'contacts' in section 'NETWORK' of the user profile

* **cached** - boolean, default = false  
enable or not admin query caching

* **cacheDuration** - int, default = 84600  (24 hours)  
seconds of query caching duration if $cache = true - default is 1 day 

* **bypassWorkflow** - boolean, default = false  
If true the plugin bypass the user profile workflow and show nothing of it

* **htmlMailContent** - string, default = '@vendor/open20/amos-admin/src/mail/user/credenziali-html'  
This is the html used to render the message of the e-mail. In the view is available the variable $profile that is an instance of 'open20\amos\admin\models\UserProfile'.

* **textMailContent** - string, default = '@vendor/open20/amos-admin/src/mail/user/credenziali-text'  
This is the text used to render the message of the e-mail. In the view is available the variable $profile that is an instance of 'open20\amos\admin\models\UserProfile'

* **fieldsConfigurations** - array, default:
```php
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
```
This array contains all configurations for boxes and fields to enable in form/wizard and view for model UserProfile.
Find in /src/views/user-profile/boxes all the possible subviews to enable/disable for user profile. 

* **profileRequiredFields** - array, default = ['nome', 'cognome', 'status', 'presentazione_breve']  
Mandatory fields in user profile form: by default name, surname, profile status and short presentation (I present myself in 140 characters) are mandatory.  
If in your platform, for example, you don't want short presentation to be a mandatory field, overwrite profileRequiredFields property as below:
```php
'admin' => [
    'class' => 'open20\amos\admin\AmosAdmin',
    'profileRequiredFields' => ['nome', 'cognome', 'status'] 
],
```
* **confManager** - ConfigurationManager, default = null  
//TODO explain

  
* **defaultUserRole** - string, default = 'BASIC_USER'  
At user creation, it is possible to customize the Rbac role to assign to a new user, default is BASIC_USER role.

* **organizationModuleName** - string, default = 'organizzazioni'  
This is the module name (you used as array key in modules configuration of your platform) referring to a module extending open20\amos\core\interfaces\OrganizationsModuleInterface
It is used to give the possibility to customize the entity type used to set user profile prevalent partnership, for example.
//TODO explain better

* **allowLoginWithEmailOrUsername** - boolean, default = true  
This feature allow user to login both with username or email. It's enabled by default.

* **userCanSelectUsername** - boolean, default = false  
If this is set to false, when a new user is created the platform automatically set the username with the part before '@' of the email. If the username is not available the system generate an unique one. by default the user cannot select the username.

* **disableUpdateChangeStatus** - boolean, default = false  
If this is set to false the popup on my profile modify is disabled.

* **enableWorkflowChangeStatusMails** - boolean, default = true  
If this is set to false the mails on change profile workflow status are disabled.

* **whiteListProfileImageExts** - string, default = jpeg, jpg, png, gif  
Used to set the allowed extensions for profile images.

* **associateTutor** - int, default = null 
At the creation of the user, set the user (Tutor) as contact of the created use.

* **defaultPrivateMessage** - bool, default = null
At the creation of the user, send a private message from the tutor

* **roleAndAreaOnOrganizations** - boolean, default = false  
If true, hide roles and areas standard and enable it on the single network organization row.

* **roleAndAreaFromOrganizationsWithTypeCat** - boolean, default = false  
If true, uses "type_cat" field in the roles and areas queries.

* **sendUserAssignmentsReportOnDelete** - boolean, default = false
To enable sending a report via email to all admin users when a user deletes himself or is deleted by another user.
The email contains a recap of all assignments of the deleted user inside the app. 

* **enableSendMessage** - boolean, default = false  
If this is true and $enableUserContacts is false all users see the "send message" button on view icon.

* **helpLinkAction** - string, default = null  
The action to run from backend to go in the technical area section via the email and the private message sent enabling the param above.

* **showFacilitatorForModuleSelect** - boolean, default = false  
Enable modify facilitator

* **dontCheckOneTagPresent** - boolean, default = false  
If true the model validation doesn't check if there's at least one tag present for non ADMIN users.

* **enableMultiUsersSameCF** - boolean, default = false  
If true the model validation doesn't check the unique of che fiscal code.

* **enableInviteUserToEvent** - boolean, default = false  
If true enable a link on single user useful to invite a user to a published event with an event community.


* **createContentInMyOwnCommunityOnly** - boolean, default = false
If true the validate basic user can create contents only in his/her own Communities

* **actionBlacklistManageInvite** - array, default = [] 
Array used for checking that action controller can use invitation button.

### How to use Token groups 
First create the token group and the you can use the following functions. 
* You can login using the token, using the link /admin/security/login?token=[__TOKEN__]
* After the login you will be redirected to the url set on the TokenGroup

#####  Get the created group of token (for a model, or model/model_id)
```php
TokenGroup::getTokenGroup($classname, $id = null)
```
#####  Generate the user tokens for the group using  the user_id (array)
```php
$tokenGroup->generateTokenUsersByIds($ids)
```
#####  Generate a single token using the user_id, @return TokenUsers
```php
$tokenUser = $tokenGroup->generateSingleTokenUser($id)
```