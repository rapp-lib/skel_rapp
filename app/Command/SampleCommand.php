<?php
namespace R\App\Command;
use R\Lib\Console\Command;

class SampleCommand extends Command
{
    protected function init ()
    {
    }
    public function act_test ()
    {
        // bin/rexe sample test
        print "OK sample.test\n";
    }
}
