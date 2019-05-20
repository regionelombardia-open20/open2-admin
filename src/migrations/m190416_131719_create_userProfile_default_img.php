<?php

use yii\db\Migration;
use lispa\amos\admin\models\UserProfile;
use lispa\amos\attachments\components\FileImport;

/**
 * Class m190416_131719_create_userProfile_default_img
 */
class m190416_131719_create_userProfile_default_img extends Migration {

    const BASE_IMPORT_PATH = "@backend/web/img/defaultProfilo.png";

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        Yii::getLogger()->flushInterval = 1;
        Yii::$app->log->targets = [];
   
        $this->importDocuments();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        echo "m190416_131719_create_userProfile_default_img cannot be reverted.\n";

        return false;
    }

    public function importDocuments() {
        $connection = \Yii::$app->db;
        $rows = UserProfile::find()
                ->all();
        $i = 0;
        $transaction = $connection->beginTransaction();
        foreach ($rows as $row) {
            if (empty($row->userProfileImage)) {
                
                $ok = $this->migrateFile($row, 'userProfileImage',
                        \Yii::getAlias( self::BASE_IMPORT_PATH));
                $this->printConsoleMsg('Update Defalutf for --' . $row->user_id);
            }
            if (++$i > 1000) {
                $transaction->commit();
                $transaction = $connection->beginTransaction();
                $this->printConsoleMsg('Commit ok');
                $i = 0;
            }
        }
        $transaction->commit();
    }

    /**
     * This method print a console message
     * @param $msg
     */
    private function printConsoleMsg($msg) {
        print_r($msg);
        print_r("\n");
    }

    

    /**
     * This method migrate one file from old folder to new folder and then update database
     * @param News $news
     * @param string $attribute
     * @param string $filePath
     * @return array
     */
    private function migrateFile($document, $attribute, $filePath) {
        $fileImport = new FileImport();
        $ok = $fileImport->importFileForModel($document, $attribute,
                $filePath,false);
        return $ok;
    }

}
