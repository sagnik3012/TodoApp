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
            $task =  Tasks::create($data);
        } catch(QueryException $e){
            return response()->json($e->errorInfo , Response::HTTP_BAD_REQUEST);
        }


        return response()->json($task,Response::HTTP_CREATED);

    }
    public function getTaskById( $id ){

        try{
            $task = Tasks::where('id',$id)->get();
        } catch (QueryException $e){
            return response()->json($e->errorInfo,Response::HTTP_BAD_REQUEST);
        }

        if (count($task) == 0){
            return response()->json(["message"=>"task with id = $id doesn't exist!"],Response::HTTP_NOT_FOUND);
        }
        return response()->json($task[0],Response::HTTP_OK);

    }
    public function deleteTaskById($id){

        try{
            $task = Tasks::where('id',$id)->get();
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
            $tasks = Tasks::get();
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
            Tasks::where('id',$id)->update(['status'=>$newStatus]);
        } catch (QueryException $e){
            return response()->json($e->errorInfo, Response::HTTP_BAD_REQUEST);
        }
        $updatedTask = Tasks::find($id);
        return  response()->json(["updated task" => $updatedTask],Response::HTTP_OK);
    }

}
