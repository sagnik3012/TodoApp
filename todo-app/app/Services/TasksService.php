<?php


namespace App\Services;
use Illuminate\Http\Response;
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
        $id =  DB::table('tasks')->insertGetId($data);
        return DB::table('tasks')->find($id);

    }
    public function getTaskById( $id ){


        $task = DB::table('tasks')->where('id',$id)->get();
        if (count($task) == 0){
            return false;
        }
        else
        return $task;

    }
    public function deleteTaskById($id){
        $resp = false;
        $task = DB::table('tasks')->where('id',$id)->get();
        if (count($task) == 0){
            return $resp;
        }
        DB::table('tasks')->delete($id);
        return $resp=1;

    }
    public function getAllTasks(){

        $tasks = DB::table('tasks')->get();
        if (count ($tasks) == 0){
            return false;
        }
        return $tasks;
    }
    public function editTaskStatusById(Request $request , $id){

        $resp = null;
        $newStatus = $request->input('status');
        echo $newStatus;
        if( !strcmp($newStatus ,"Pending") and !strcmp($newStatus ,"In Progress") and !strcmp($newStatus ,"Done") ){
            $resp = response()->json([
                "message" => "please check task status field and retry!"
            ],Response::HTTP_BAD_REQUEST);
            return $resp;
        }
        $task = DB::table('tasks')->find($id);
        if( $task == null) {
            $resp = response()->json([
                "message"=>"task with id = $id doesn't exist!"
            ],Response::HTTP_NOT_FOUND);
            return $resp;
        }
        DB::table('tasks')->where('id',$id)->update(['status'=>$newStatus]);
        $updatedTask = DB::table('tasks')->find($id);
        $resp = response()->json([
            "updated task" => $updatedTask
        ],Response::HTTP_OK);
        return $resp;
    }

}
