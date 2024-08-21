<?php

namespace App\Console\Commands;

use DB;
use Schema;

abstract class BaseParseCommand extends BaseCommand
{
    protected const CLASS_NAME = '';

    private string $className = '';
    private string $tableName = '';
    private string $tmpTableName = '';

    public function __construct()
    {
        parent::__construct();
        $this->className = static::CLASS_NAME;
        /** @var $class \Illuminate\Database\Eloquent\Model */
        $class = new $this->className;
        $this->tableName = $class->getTable();
        $this->tmpTableName = $this->tableName . '_tmp';
    }

    protected function createTmpTable(): void
    {
        Schema::dropIfExists($this->tmpTableName);
        DB::statement('CREATE TABLE ' . $this->tmpTableName . ' LIKE ' . $this->tableName);
    }

    protected function renameTmpTable(): void
    {
        Schema::drop($this->tableName);
        Schema::rename($this->tmpTableName, $this->tableName);
    }
}
