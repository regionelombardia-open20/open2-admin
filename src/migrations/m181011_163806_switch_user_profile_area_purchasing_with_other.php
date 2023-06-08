<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\migrations
 * @category   CategoryName
 */

use open20\amos\admin\models\UserProfileArea;
use open20\amos\core\migration\libs\common\MigrationCommon;
use yii\db\Migration;

/**
 * Class m181011_163806_switch_user_profile_area_purchasing_with_other
 */
class m181011_163806_switch_user_profile_area_purchasing_with_other extends Migration
{
    private $tableName;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->tableName = UserProfileArea::tableName();
    }

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $firstElement = UserProfileArea::findOne(1);
        $otherElement = UserProfileArea::findOne(12);
        $ok = $this->switchTableElements($firstElement, $otherElement);
        return $ok;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $firstElement = UserProfileArea::findOne(12);
        $otherElement = UserProfileArea::findOne(1);
        $ok = $this->switchTableElements($firstElement, $otherElement);
        return $ok;
    }

    /**
     * @param UserProfileArea $firstElement
     * @param UserProfileArea $otherElement
     * @return bool
     */
    private function switchTableElements($firstElement, $otherElement)
    {
        if ($this->db->driverName === 'mysql') {
            $this->execute("SET FOREIGN_KEY_CHECKS = 0;");
        }

        try {
            $this->delete($this->tableName, ['id' => $firstElement->id]);
        } catch (\Exception $exception) {
            MigrationCommon::printConsoleMessage('Errore cancellazione primo elemento con id ' . $firstElement->id);
            MigrationCommon::printConsoleMessage($exception->getMessage());
            return false;
        }
        MigrationCommon::printConsoleMessage('Cancellato primo elemento');

        try {
            $this->delete($this->tableName, ['id' => $otherElement->id]);
        } catch (\Exception $exception) {
            MigrationCommon::printConsoleMessage('Errore cancellazione altro elemento con id ' . $otherElement->id);
            MigrationCommon::printConsoleMessage($exception->getMessage());
            return false;
        }
        MigrationCommon::printConsoleMessage('Cancellato altro elemento');

        try {
            $otherElementToFirstElementValues = $otherElement->attributes;
            $otherElementToFirstElementValues['id'] = $firstElement->id;
            $this->insert($this->tableName, $otherElementToFirstElementValues);
        } catch (\Exception $exception) {
            MigrationCommon::printConsoleMessage('Errore inserimento altro elemento al posto del primo');
            MigrationCommon::printConsoleMessage($exception->getMessage());
            return false;
        }
        MigrationCommon::printConsoleMessage('Inserito altro elemento al posto del primo');

        try {
            $firstElementToOtherElementValues = $firstElement->attributes;
            $firstElementToOtherElementValues['id'] = $otherElement->id;
            $this->insert($this->tableName, $firstElementToOtherElementValues);
        } catch (\Exception $exception) {
            MigrationCommon::printConsoleMessage('Errore inserimento primo elemento al posto di altro');
            MigrationCommon::printConsoleMessage($exception->getMessage());
            return false;
        }
        MigrationCommon::printConsoleMessage('Inserito primo elemento al posto di altro');

        if ($this->db->driverName === 'mysql') {
            $this->execute("SET FOREIGN_KEY_CHECKS = 1;");
        }

        return true;
    }
}
