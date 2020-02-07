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

    public function init()
    {
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
        }

        // Set db name if not exists in databases config array
        foreach ($this->databases as $name => $params) {
            if (!isset($params['db'])) {
                $this->databases[$name]['db'] = $name;
            }
        }
    }

    /**
     * Create dump of all directories and all databases and save result to bakup folder with timestamp named zip-archive
     *
     * @return string Full path to created backup file
     * @throws Exception
     */
    public function create()
    {
        $folder = $this->getBackupFolder();

        $files = $this->backupFiles($folder);
        $db = $this->backupDatabase($folder);

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

    /**
     * Create backups for $directories and save it to "<backups folder>"
     *
     * @param string $saveTo
     *
     * @return bool
     */
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

        return true;
    }

    public function backupDatabase($saveTo)
    {
        $saveTo .= DIRECTORY_SEPARATOR . 'sql';
        mkdir($saveTo);

        foreach ($this->databases as $name => $params) {
            // Get mysqldump command
            $command = isset($params['command']) && !empty($params['command']) ? $params['command'] : $this->mysqldump;

            if ((string)$params['password'] === '') {
                // Remove password option
                $command = str_replace('-p\'{password}\'', '', $command);
                unset($params['password']);
            }

            foreach ($params as $k => $v) {
                $command = str_replace('{' . $k . '}', $v, $command);
            }

            $file = $saveTo . DIRECTORY_SEPARATOR . $name . '.sql.gz';

            system($command . ' > ' . $file);
        }

        return true;
    }


    // delete expired backups
    public function deleteExpired()
    {
        if (empty($this->expireTime)) {
            // Prevent deleting if expireTime is disabled
            return true;
        }

        $backupsFolder = Yii::getAlias($this->backupsFolder);
        // Calculate expire date
        $expireDate = time() - $this->expireTime;

        $filter = function ($path) use ($expireDate) {
            // Check extension
            if (substr($path, -4) !== '.zip') {
                return false;
            }

            if (is_file($path) && filemtime($path) <= $expireDate) {
                // if the time has come - delete file
                return true;
            }

            return false;
        };

        // Find expired backups files
        $files = FileHelper::findFiles($backupsFolder, ['recursive' => false, 'filter' => $filter]);

        foreach ($files as $file) {
            if (@unlink($file)) {
                Yii::info('Backup file was deleted: ' . $file, 'app\components\Component::deleteExpired()');
            } else {
                Yii::error('Cannot delete backup file: ' . $file, 'app\components\Component::deleteExpired()');
            }
        }

        return true;
    }

    /**
     * Generate backup filename
     *
     * @return string
     */
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


