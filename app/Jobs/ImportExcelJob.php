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
use App\Mail\UploadInvalid;
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
        $this->checkdatastruct();
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
                if(!$row['name'] || !$row['email'] || !$row['location'])
                    continue;
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
    public function checkdatastruct(){
        $n = 0;
        $e = 0;
        $flag = 0;
        $rows = [];
        $users = User::All();
        $data = Excel::load(storage_path().'/files/excel/upload.xlsx', function ($reader){})->get();
        foreach ($data->toArray() as $key =>  $d) {
            $error = '';
            foreach ($users as $user) {
                if($user->email == $d['email']){
                    if($user->location == $d['location']){
                        if(!$d['name']){
                            $rows[] = array_merge($d, array('error'=> 'User Already exists. Invalid Name input'));
                            $flag = 1;
                        }
                        else
                            $rows[] = array_merge($d, array('error'=> 'User Already exists.'));
                    }
                }
            }
            // $this->check_name($flag,$error,$n,$d);
            // $this->check_email($n,$error,$d,$e);
            // $this->check_location($n,$e,$error,$d);
            if (!$d['name'] && !$flag) {
                $error = 'Invalid Name input';
                $n = 1;
            }
            if (!$d['email'] && !$n) {
                $error = 'Invalid Email input';
                $e = 1;
            }
            elseif(!$d['email'] && $n)
                $error .= ', Invalid Email input';
            if (!$d['location'] && !$n && !$e) {
                $error = 'Invalid Location input';
            }
            elseif(!$d['location'] && ($n || $e))
                $error .= ', Invalid Location input';
            if($error)
                $rows[] = array_merge($d, array('error' => $error));
        }
        if($rows){
            $excelData = Excel::create('Invalid', function($excel) use(&$rows){
                $excel->sheet('Invalid', function($sheet) use(&$rows) 
                {
                    $sheet->fromArray($rows);
                });
            })->save('xlsx', storage_path('files/excel/'), true);
            \Mail::to('sooryakumar.v@heptagon.in')->send(new UploadInvalid());
        }
    }
    public function check_name($flag,$error,$n,$d){
        if (!$d['name'] && !$flag) {
            $error = 'Invalid name input';
            $n = 1;
        }
    }
    public function check_email($n,$error,$d,$e){
        if (!$d['email'] && !$n) {
            $error = 'Invalid email input';
            $e = 1;
        }
        elseif(!$d['email'] && $n)
            $error = explode(',', 'Invalid email input');
    }
    public function check_location($n,$e,$error,$d){
        if (($d['location'] != 'coimbatore' || $d['location'] != 'bangalore') && !$n && !$e) {
            $error = 'Invalid email input';
        }
        elseif(($d['location'] != 'coimbatore' || $d['location'] != 'bangalore') && ($n || $e))
            $error = explode(',', 'Invalid email input');
    }
}
