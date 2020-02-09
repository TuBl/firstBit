<?php

namespace app\components;

use Yii;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\helpers\FileHelper;


class Component extends \yii\base\Component
{
    // Backups directory path
    public $backupsFolder = "../backups";
    // Backed up file name format
    public $backupFilename = 'Y_m_d-H_i_s';
    // Time in seconds before backed up files are deleted (1 month)
    public $expireTime = 2592000;
    // Directories to be backed up
    public $directories = [
        'images' => '../Images'
    ];

    public $db = 'db';
    // List of databases connections config
    public $databases = [
        'firstbit_db' => [
            'db' => 'firstbit_db',
            'host' => 'localhost',
            'username' => 'root',
            'password' => ''
        ]
    ];

    //command to create the database backup
    public $mysqldump = 'mysqldump --add-drop-table --allow-keywords -q -c -u "{username}" -h "{host}" -p\'{password}\' {db} | gzip -9';

    public $startTime = "";
    public $finishTime = "";
    public function getBuildPercentage() {
        $currentTime = strtotime(date('Y-m-d H:i:s')); //50
        $boughtTime = strtotime($this->startTime); 
        $finishTime = strtotime($this->finishTime); //100
    
        $first = $currentTime - $boughtTime;
        $second = $finishTime - $boughtTime;
    
        $percentage =   [$currentTime, $boughtTime, $finishTime];
        //round(($first / $second) * 100);    
    
        // if the percentage is higher than 100 -> item is finished
        // if($percentage >= 100)
        //     $this->setToFinished($this->id); 
    
        return $percentage; 
    }

    public function init()
    {
        // Yii::$app->session->setFlash('warning', 'Loading...');

        // Check backup folder
        if (!is_dir($this->backupsFolder)) {
            throw new InvalidConfigException('Directory for backups "' . $this->backupsFolder . '" does not exists');
        } elseif (!is_writable($this->backupsFolder)) {
            throw new InvalidConfigException('Directory for backups "' . $this->backupsFolder . '" is not writable');
        }

        // Add site database to primary databases list
        if (!empty($this->db) && Yii::$app->has($this->db)) {
            /** @var \yii\db\Connection $dbComponent */
            $dbComponent = Yii::$app->get($this->db);

            // Get default database name
            $dbName = $dbComponent->createCommand('select database()')->queryScalar();
            $this->databases[$dbName] = [
                'db' => $dbName,
                'host' => 'localhost',
                'username' => $dbComponent->username,
                'password' => addcslashes($dbComponent->password, '\''),
            ];
            $this->startTime = date('Y-m-d H:i:s');
        }

        // Set db name if not exists in databases config array
        foreach ($this->databases as $name => $params) {
            if (!isset($params['db'])) {
                $this->databases[$name]['db'] = $name;
            }
        }
    }

    //Create dump of all directories and all databases and save result to bakup folder with timestamp  named zip-archive

    public function create()
    {

        $folder = $this->getBackupFolder();
        $files = $this->backupFiles($folder);
        // $db = $this->backupDatabase($folder);
        $resultFilename = $this->getBackupFilename();
        $archiveFile = dirname($folder) . DIRECTORY_SEPARATOR . $resultFilename . '.zip';

        // Create new archive
        $archive = new \PharData($archiveFile);

        // add folder
        $archive->buildFromDirectory($folder);

        // Remove temp directory
        FileHelper::removeDirectory($folder);

        return $archiveFile;
    }

    //back up files in directories list
    public function backupFiles($saveTo)
    {
        foreach ($this->directories as $name => $value) {
            if (is_array($value)) {
                // if exists config, use it
                $folder = Yii::getAlias($value['path']);
                $regex = isset($value['regex']) ? $value['regex'] : null;
            } else {
                $regex = null;
                $folder = Yii::getAlias($value);
            }

            $archiveFile = $saveTo . DIRECTORY_SEPARATOR . $name . '.zip';

            // Create new archive
            $archive = new \PharData($archiveFile);

            // add folder
            $archive->buildFromDirectory($folder, $regex);
        }
        $this->finishTime = date('Y-m-d H:i:s');
        // Yii::$app->session->setFlash('success', 'File backed up succesfuly!');

        return true;
    }


 
    //generate file name
    public function getBackupFilename()
    {
        if (is_callable($this->backupFilename)) {
            return call_user_func($this->backupFilename, $this);
        } else {
            return date($this->backupFilename);
        }
    }


    public function getBackupFolder()
    {
        // Base backups folder
        $base = Yii::getAlias($this->backupsFolder);

        // Temp directory for current backup
        $current = $this->getBackupFilename();

        $fullpath = $base . DIRECTORY_SEPARATOR . $current;

        // Try to create new directory
        if (!is_dir($fullpath) && !mkdir($fullpath)) {
            throw new Exception('Can not create folder for backup: "' . $fullpath . '"');
        }

        return $fullpath;
    }
}


