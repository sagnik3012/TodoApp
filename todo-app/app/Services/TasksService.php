<?php


namespace App\Services;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TasksService{

    public function createTask( Request $request){

        $request->validate([
            'task_name' => 'required|max:255',
            'description' => 'required',
            'status' => 'required',
        ]);

        $taskName = $request->input('task_name');
        $taskDescription = $request->input('description');
        $taskStatus = $request->input('status');
        $data = array('task_name'=>$taskName , 'description'=>$taskDescription , 'status'=>$taskStatus);
        return  DB::table('tasks')->insertGetId($data);


    }




}
