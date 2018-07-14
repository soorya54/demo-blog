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
    			dispatch(new ImportExcelJob());
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
}