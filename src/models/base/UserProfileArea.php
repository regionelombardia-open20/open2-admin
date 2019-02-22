<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\admin\models\base
 * @category   CategoryName
 */

namespace lispa\amos\admin\models\base;

use lispa\amos\admin\AmosAdmin;
use lispa\amos\core\record\Record;

/**
 * Class UserProfileArea
 * This is the base-model class for table "user_profile_area".
 *
 * @property integer $id
 * @property string $name
 * @property integer $enabled
 * @property integer $order
 * @property integer $type_cat
 *
 * @property \lispa\amos\admin\models\UserProfile[] $userProfiles
 *
 * @package lispa\amos\admin\models\base
 */
class UserProfileArea extends Record
{
    const OTHER = 1;
    const TYPE_CAT_GENERIC = 0;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_profile_area';
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'enabled', 'order'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['enabled', 'order', 'type_cat'], 'integer']
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => AmosAdmin::t('amosadmin', 'ID'),
            'name' => AmosAdmin::t('amosadmin', 'Name'),
            'enabled' => AmosAdmin::t('amosadmin', 'Enabled'),
            'order' => AmosAdmin::t('amosadmin', 'Order'),
            'type_cat' => AmosAdmin::t('amosadmin', 'Type Cat')
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserProfiles()
    {
        $modelClass = \lispa\amos\admin\AmosAdmin::instance()->createModel('UserProfile');
        return $this->hasMany($modelClass::className(), ['user_profile_area_id' => 'id']);
    }
}
