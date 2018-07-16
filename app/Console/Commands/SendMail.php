<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Mail\DailyUpdate;
use App\User;
use DB;

class SendMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:mail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send users list Email';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $users = \DB::table('users')->get();
        \Mail::to('vadivel@heptagon.in')->send(new DailyUpdate($users));
    }
}
