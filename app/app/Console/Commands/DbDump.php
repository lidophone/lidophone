<?php

namespace App\Console\Commands;

use App;
use Log;
use w3lifer\google\Drive;

class DbDump extends BaseCommand
{
    protected $signature = 'app:db:dump';

    private const GOOGLE_DRIVE_FOLDER_ID = '1whLgVa4MfV5xC6oRlW-PaBWzCofh1CqR';

    private const IGNORE_TABLES = [
        'yandex_realty',
    ];

    public function handle(): void
    {
        $dbHost = env('DB_HOST');
        $dbPort = env('DB_PORT');

        // https://dba.stackexchange.com/a/274460/98626
        if (App::isProduction()) {
            $dbUser = env('DB_USERNAME');
            $dbUserPassword = env('DB_PASSWORD');
        } else {
            $dbUser = 'root';
            $dbUserPassword = 'root_password';
        }

        $dbName = env('DB_DATABASE');

        $ignoreTables = '';
        foreach (self::IGNORE_TABLES as $value) {
            $ignoreTables .= " --ignore-table=$dbName.$value";
        }

        $pathToDump = storage_path('app/dumps/' . $dbName . '_' . date('Y-m-d_H-i-s') . '.sql');

        exec(
            "mysqldump " .
            "--single-transaction " .
            // `--single-transaction` is required due to the following error:
            // mysqldump: Got error: 1044: Access denied for user '...' to database '...' when using LOCK TABLES
            "--column-statistics=0 " .
            // `--column-statistics` is required due to the following warning:
            // -- Warning: column statistics not supported by the server.
            "-h $dbHost -P $dbPort -u $dbUser  -p'$dbUserPassword' $dbName $ignoreTables > $pathToDump",
            $output,
            $resultCode
        );

        if ($resultCode !== 0) {
            unlink($pathToDump);
            Log::error('Backups / Some error has occurred.');
            die;
        }

        $googleDrive = new Drive([
            'pathToCredentials' => storage_path('app/google-drive/credentials.json'),
            'pathToToken' => storage_path('app/google-drive/token.json'),
        ]);

        $fileId = $googleDrive->upload($pathToDump, [self::GOOGLE_DRIVE_FOLDER_ID]);

        if (!$fileId) {
            Log::error('Backups / Received an empty file ID from Google Drive.');
        }

        unlink($pathToDump);

        $this->printCommandCompletionMessage();
    }
}
