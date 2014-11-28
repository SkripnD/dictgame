<?php

/**
 * Refresh all tables schemas cache
 */
class RefreshSchemasCommand extends CConsoleCommand
{
    /**
     * Provides the command description.
     * @return string the command description.
     */
    public function getHelp()
    {
        return <<<EOD
DESCRIPTION
Refresh all tables schemas

EOD;
    }

    public function run($args)
    {
        Yii::app()->db->schema->getTables();
        Yii::app()->db->schema->refresh();
        Yii::app()->db->schema->getTables();
    }
}
