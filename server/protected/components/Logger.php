<?php 

class Logger extends CDbLogRoute
{
    public $logs = [];
    
    /**
     * Creates the DB table for storing log messages.
     * @param CDbConnection $db the database connection
     * @param string $tableName the name of the table to be created
     */
    protected function createLogTable($db, $tableName)
    {
        $db->createCommand()->createTable($tableName, [
            'id'       => 'pk',
            'level'    => 'varchar(128) NOT NULL',
            'category' => 'varchar(128) NOT NULL',
            'logtime'  => 'integer NOT NULL',
            'message'  => 'text NOT NULL',
            // add new field
            'title'    => 'varchar(255) NOT NULL',
            'url'      => 'varchar(255) NOT NULL',
            'ip'       => 'bigint(20) NOT NULL',
            'userId'   => 'int(11) NOT NULL',
            'INDEX category (category)',
            'INDEX idx_category_title (category, title)',
        ]);
    }
    
    /**
     * Stores log messages into database.
     * @param array $logs list of log messages
     */
    protected function processLogs($logs)
    {
        $command = $this->getDbConnection()->createCommand();
        $isConsole = Yii::app() instanceof CConsoleApplication;
        
        foreach ($logs as $log) {
            $message = is_array($log[0]) ? reset($log[0]) : $log[0];
            $title   = is_array($log[0]) ? key($log[0]) : '';
            
            $command->insert($this->logTableName, [
                'level'    => $log[1],
                'category' => $log[2],
                'logtime'  => (int)$log[3],
                'message'  => $message,
                'title'    => $title,
                'url'      => $isConsole ? '' : Yii::app()->request->url,
                'ip'       => $isConsole ? 0  : ip2long(Yii::app()->request->userHostAddress),
                'userId'   => $isConsole || !Yii::app()->user->id ? 0  : Yii::app()->user->id
            ]);
        }
    }
}
