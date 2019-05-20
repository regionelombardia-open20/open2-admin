<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\admin\views\user-profile\boxes
 * @category   CategoryName
 */

use lispa\amos\admin\AmosAdmin;
use lispa\amos\admin\base\ConfigurationManager;
use lispa\amos\attachments\components\CropInput;

/**
 * @var yii\web\View $this
 * @var lispa\amos\core\forms\ActiveForm $form
 * @var lispa\amos\admin\models\UserProfile $model
 * @var lispa\amos\core\user\User $user
 */

/** @var AmosAdmin $adminModule */
$adminModule = Yii::$app->controller->module;

?>

<?php if ($adminModule->confManager->isVisibleField('userProfileImage', ConfigurationManager::VIEW_TYPE_FORM)): ?>
    <?= $form->field($model,'userProfileImage')->widget(CropInput::classname(), [
        'enableUploadFromGallery' => false,
        'jcropOptions' => [ 'aspectRatio' => '1']
    ])->label(AmosAdmin::t('amosadmin', '#image_field'))->hint(AmosAdmin::t('amosadmin', '#image_field_hint')); ?>
<?php endif; ?>
