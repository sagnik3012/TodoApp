<?php


namespace App\Services;
use App\Models\Tasks;
use Illuminate\Database\QueryException;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TasksService{

    public function createTask( $taskName , $taskDescription, $taskStatus){


        if( !($taskStatus == "Pending" || $taskStatus == "In Progress" || $taskStatus == "Done" )){
            return  response()->json(["message" => "please check task status field and retry!"],Response::HTTP_BAD_REQUEST);
        }

        $data = array('task_name'=>$taskName , 'description'=>$taskDescription , 'status'=>$taskStatus);
        try{
            $id =  Tasks::insertGetId($data);
        } catch(QueryException $e){
            return response()->json($e->errorInfo , Response::HTTP_BAD_REQUEST);
        }

        $task =  Tasks::find($id); // Tasks::
        return response()->json($task,Response::HTTP_CREATED);

    }
    public function getTaskById( $id ){

        try{
            $task = DB::table('tasks')->where('id',$id)->get();
        } catch (QueryException $e){
            return response()->json($e->errorInfo,Response::HTTP_BAD_REQUEST);
        }

        if (count($task) == 0){
            return response()->json(["message"=>"task with id = $id doesn't exist!"],Response::HTTP_NOT_FOUND);
        }
        return response()->json($task,Response::HTTP_OK);

    }
    public function deleteTaskById($id){

        try{
            $task = DB::table('tasks')->where('id',$id)->get();
        } catch (QueryException $e){
            return response()->json($e->errorInfo , Response::HTTP_BAD_REQUEST);
        }

        if (count($task) == 0){
            return response()->json(["message"=>"task with id = $id doesn't exist"],Response::HTTP_NOT_FOUND);
        }
        try{
            DB::table('tasks')->delete($id);
        }catch (QueryException $e){
            return response()->json([$e->errorInfo]);
        }

        return response()->json(["message"=>"task with id = $id successfully deleted!"],Response::HTTP_OK);

    }
    public function getAllTasks(){
        try{
            $tasks = DB::table('tasks')->get();
        }catch (QueryException $e){
            return response()->json($e->errorInfo , Response::HTTP_BAD_REQUEST);
        }
        if (count ($tasks) == 0){
            return response()->json(["message"=>"there is no task in the todo-list!"],Response::HTTP_BAD_REQUEST);
        }
        return response()->json(["tasks"=>$tasks],Response::HTTP_OK);
    }
    public function editTaskStatusById(Request $request , $id){

        $newStatus = $request->input('status');
        if( !strcmp($newStatus ,"Pending") and !strcmp($newStatus ,"In Progress") and !strcmp($newStatus ,"Done") ){
            return  response()->json(["message" => "please check task status field and retry!"],Response::HTTP_BAD_REQUEST);
        }
        try{
            DB::table('tasks')->where('id',$id)->update(['status'=>$newStatus]);
        } catch (QueryException $e){
            return response()->json($e->errorInfo, Response::HTTP_BAD_REQUEST);
        }
        $updatedTask = DB::table('tasks')->find($id);
        return  response()->json(["updated task" => $updatedTask],Response::HTTP_OK);
    }

}
