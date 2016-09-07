<?php   namespace App\Console\Commands;


use Illuminate\Console\Command;

class PrintDateTime extends Command{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pr:datetime';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Display a DateTime';

    public function __construct(){
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //$this->comment(PHP_EOL.Inspiring::quote().PHP_EOL);
        //$this->info();
        file_put_contents("E:\\cron.txt", date('Y-m-d H:i:s') .', '. round(microtime(true) * 1000), FILE_APPEND);
    }
}