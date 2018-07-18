<?php

namespace App\Http\Controllers;

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
use Illuminate\Notifications\Notifiable;
use App\Jobs\ImportExcelJob;
use App\Mail\UploadInvalid;

class ImportController extends Controller
{
     public function import()
    {
        return view('import');
    }
    public function importExcel	(Request $request)
    {
    	if($request->hasFile('import_file')){
    		$this->checkexcelstruct = $request->all();
    		if($this->checkexcelstruct($request)){
    			$file = $request->file('import_file');
    			$file->move(storage_path().'/files/excel', 'upload.xlsx');
                // $this->checkdatastruct();
    			dispatch(new ImportExcelJob());
                session()->flash('message','File has been Uploaded to queue.');
    		}
            else{
                session()->flash('message','Check the File Format');
                return back();
            }
            return back();
	    }
     	else{
        	session()->flash('message','No File has been Uploaded.');
        	return back();
        }
    }
    public function checkexcelstruct(Request $request){
    	$header =  (((Excel::load($request->file('import_file')->getRealPath(), function ($reader) {})->all())->first())->keys())->toArray();
    	if($header[0] == 'name' && $header[1] == 'email' && $header[2] == 'location')
    		return true;
    	else
    		return false;
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