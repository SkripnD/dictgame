<?php

Yii::import('system.cli.commands.MigrateCommand');

class Migrate extends MigrateCommand
{
    public function run($args)
    {
        parent::run($args);
 
        $this->refreshTableSchemas();
    }
 
    public function refreshTableSchemas()
    {
        $db = $this->getDbConnection();
 
        if ($db->schemaCachingDuration > 0) {
            $db->schema->getTables();
            $db->schema->refresh();
            $db->schema->getTables();
        }
    }
}
