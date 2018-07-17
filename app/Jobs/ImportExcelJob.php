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
use App\VerifyUser;

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
        $users = User::All();
        foreach ($users as $user) {
            if($user->verified == 0 && $user->password == 'NULL'){
                $verifyUser = VerifyUser::create([
                    'user_id' => $user->id,
                    'token' => str_random(40)
                ]);
                \Mail::to($user)->send(new WelcomeAgain($user));
            }
        }
        session()->flash('message','File has been Uploaded.');
        return back();
    }
}
