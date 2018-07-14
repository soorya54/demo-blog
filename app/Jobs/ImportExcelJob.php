<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Http\Requests;
use Illuminate\Http\Request;
use Input;
use App\User;
use DB;
use Session;
use Excel;
use App\Mail\WelcomeAgain;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Http\FormRequest;

class ImportExcelJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Excel::load(storage_path().'/files/excel/upload.xlsx', function ($reader) {
            $users = User::All();
            foreach ($reader->toArray() as $key => $row) {
                $flag = 0;
                foreach($users as $user){
                    if($user->email == $row['email']){
                        if($user->location == $row['location']){
                            $flag = 1;
                        }
                    }
                    else{
                        \Mail::to($row['email'])->send(new WelcomeAgain($row['email']));
                    }
                }
                if($flag == 1)
                    continue;
                $data['name'] = $row['name'];
                $data['email'] = $row['email'];
                $data['location'] = $row['location'];
                    if(!empty($data)) {
                        DB::table('users')->insert($data);
                    }
                    else{
                        session()->flash('message','Check the File Format');
                        return back();
                    }
            }
        });
        session()->flash('message','File has been Uploaded.');
        return back();
    }
}
