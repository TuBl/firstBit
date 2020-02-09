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
        'tables' => '../tables'
    ];

    public function init()
    {

        // Check backup folder
        // if directory doesn't exist, try creating it
        // else thorw error
        if (!is_dir($this->backupsFolder)) {
            try {
                mkdir("../backups");
            } catch (\Throwable $th) {
                throw new InvalidConfigException('Directory for backups "' . $this->backupsFolder . '" does not exists');
            }
        } elseif (!is_writable($this->backupsFolder)) {
            throw new InvalidConfigException('Directory for backups "' . $this->backupsFolder . '" is not writable');
        }
    }

    //Create dump of all directories and all databases and save result to bakup folder with timestamp  named zip-archive

    public function create()
    {

        $folder = $this->getBackupFolder();
        $files = $this->backupFiles($folder);
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


