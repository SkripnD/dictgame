<?php
use \Redis;

/**
 * Create a new administrator account
 */
class UpdateWordsCommand extends CConsoleCommand
{
    /**
     * Provides the command description.
     * @return string the command description.
     */
    public function getHelp()
    {
        return <<<EOD
EOD;
    }

    public function run($args)
    {
        $parser = new Parser();
        $parser->parseVerbs();
        $parser->parseAdjectives();
        $parser->parseAdverbs();
        $parser->parseNouns();
    }
}
