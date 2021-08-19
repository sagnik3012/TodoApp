<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    // create a single task
    public function createTask(Request $request){

        $taskName = $request->input('task_name');
        $taskDescription = $request->input('description');
        $taskStatus = $request->input('status');
        $data = array('task_name'=>$taskName , 'description'=>$taskDescription , 'status'=>$taskStatus);
        $id = DB::table('tasks')->insertGetId($data);
        return response()->json([
            'id'=>$id,
            'task_name' => $taskName,
            'description'=>$taskDescription,
            'message'=>"successfully created!"
        ]);
    }
    // create multiple tasks
    public function createTasks( Request $request){

        $responses = [];
        $allTasks = $request->input('tasks');
        foreach ( $allTasks as $task){
            $taskName = $task['task_name'];
            $taskDescription = $task['description'];
            $taskStatus = $task['status'];
            $Task = array('task_name'=>$taskName , 'description' =>$taskDescription , 'status'=>$taskStatus);
            $id = DB::table('tasks')->insertGetId($Task);
            $Task['id'] = $id;
            $Task['message'] = "successfully created task : '$taskName' ";
            array_push($responses, $Task);
        }

        return response()->json([

            "tasks" => $responses

        ]);

    }
    public function getTaskById( $id){
        $task = DB::table('tasks')->find($id);
        return response()->json([
            "task" => $task
        ]);

    }
    public function deleteTask( $id){
        DB::table('tasks')->delete($id);

        return response()->json([
            "message" => "task with id = $id is successfully deleted!"
        ]);

    }
    public function viewAllTasks( ){
        $tasks = DB::table('tasks')->get();
        return response()->json([
            "tasks" => $tasks
        ]);

    }
    public function editTaskStatus( Request $request , $id){

        $newStatus = $request->input('status');
        DB::table('tasks')->where('id',$id)->update(['status'=>$newStatus]);
        $updatedTask = DB::table('tasks')->find($id);

        return response()->json([
            "updated_task" => $updatedTask
        ]);
    }
}
