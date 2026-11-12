<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos-admin
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

namespace open20\amos\admin\commands;

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\models\UserProfile;
use open20\amos\news\AmosNews;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Yii;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Console;
use open20\amos\news\models\News;
use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Style_Alignment;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\IOFactory;
use open20\amos\taskmanager\models\TaskManagerJob;


/**
 * Export controller
 */
class ExportController extends \yii\console\Controller
{
    /**
     * @var string $filename
     */
    public $filename = 'user_profile_export.xlsx';

    /**
     * @var array $data
     */
    public $data = [];

    /**
     * @var string[] $passedOptions
     */
    public $passedOptions = [
        'filename',
        'data'
    ];

    /**
     * @param $actionID
     * @return array|string[]
     */
    public function options($actionID)
    {
        return ArrayHelper::merge(parent::options($actionID), $this->passedOptions);
    }

    /**
     * @return void
     */
    public function init()
    {
        $this->module =  \Yii::$app->getModule(AmosAdmin::getModuleName());
        parent::init();
    }

    /**
     * @return void
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     * @throws \PHPExcel_Writer_Exception
     * @throws \yii\base\InvalidConfigException
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function actionExportUsers()
    {
        $filename = $this->filename;
        Console::stdout('start export' . PHP_EOL);
        // Base news query
        $rawQuery = htmlspecialchars_decode($this->data['query']);
        $rawQuery .= ' ORDER BY user_profile.created_at DESC';
        $userProfileQuery = \Yii::$app->db->createCommand($rawQuery);

        $profileModels = $userProfileQuery->queryAll();

        $emptModelUserProfile = new UserProfile();

        // Excel header columns
        $xlsData = [];
        $xlsData[] = [
            'ID',
            'Nome',
            'Cognome',
            'Stato',
            'Email',
            $emptModelUserProfile->getAttributeLabel('prevalentPartnership'),
            $emptModelUserProfile->getAttributeLabel('facilitatore'),
            $emptModelUserProfile->getAttributeLabel('notify_from_editorial_staff'),
            'Creato il',
            'Aggiornato il',
            'Ultimo accesso'
        ];

        // Populate rows
        foreach ($profileModels as $userProfile) {
            $model = UserProfile::findOne($userProfile['id']);
            if ($model->hasWorkflowStatus()) {
                $statusLabel = $model->getWorkflowStatus()->getLabel();
            } else {
                $statusLabel = '-';
            }

            $dataCreazione = '-';
            if (!is_null($model->created_at)) {
                    $dataCreazione = Yii::$app->formatter->asDatetime($model->created_at, 'php:d/m/Y H:i');
            }
            $dataAggiornamentp = '-';
            if (!is_null($model->updated_at)) {
                $dataAggiornamentp = Yii::$app->formatter->asDatetime($model->updated_at, 'php:d/m/Y H:i');
            }
            $ultimoAccesso = '-';
            if ($model->ultimo_accesso) {
                $ultimoAccesso = Yii::$app->formatter->asDatetime($model->ultimo_accesso, 'php:d/m/Y H:i');
            }

            $facilitatore = '-';
            if ($model->facilitatore) {
                $facilitatore = $model->facilitatore->nomeCognome;
            }
            $prevalentPartership = '-';
            if ($model->prevalentPartnership) {
                $prevalentPartership = $model->prevalentPartnership->name;
            }

            $xlsData[] = [
                $model->id,
                $model->nome,
                $model->cognome,
                $statusLabel,
                $model->user->email,
                $prevalentPartership,
                $facilitatore,
                // \Yii::$app->formatter->asDatetime($model->updated_at, 'php:d/m/Y H:i'),
                \Yii::$app->formatter->asBoolean($model->notify_from_editorial_staff),
                $dataCreazione,
                $dataAggiornamentp,
                $ultimoAccesso
            ];
        }

        // Initialize PhpSpreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Cells styles
        foreach (range('A', 'K') as $column) {
            $column .= '1';
            $sheet->getStyle($column)->getFont()->setBold(true);
            $sheet->getStyle($column)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_MEDIUM);
        }

        // Add data to the sheet
        $sheet->fromArray($xlsData, NULL, 'A1');

        // Save file
        $writer = new Xlsx($spreadsheet);
        $writer->save($filename);

        Console::stdout("Users exported successfully.\n");
    }

}