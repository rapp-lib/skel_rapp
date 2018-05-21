<?php
namespace R\App\Command;
use R\Lib\Console\Command;

class SampleCommand extends Command
{
    protected $name = 'app:sample';
    protected $description = 'Sample';
    public function fire()
    {
        // php artisan app:sample
        print "OK sample\n";
    }
}
