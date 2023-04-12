<?php

namespace App\Console\Commands;

use DB;
use Illuminate\Console\Command;

class ClearNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clean:notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'this will delete all notification that has been read from database';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        DB::table('notifications')->where('read_at', '!=', null)->delete();
        $this->info("Start cleaning notification");
    }
}
