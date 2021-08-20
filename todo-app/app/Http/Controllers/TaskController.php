<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Services\TasksService;
class TaskController extends Controller
{
    // create a single task
    protected $tasksService;


    public function __construct(TasksService $tasksService)
    {
        $this->tasksService = $tasksService;
    }
    public function createTask(Request $request){

        $validator = Validator::make($request->all(), [
            'task_name' => 'required|unique:tasks',
            'description' => 'required',
            'status'=>'required'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(),Response::HTTP_BAD_REQUEST);
        }

        $task = $this->tasksService->createTask($request);
        return response()->json([
            "task" => $task
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

        ],Response::HTTP_CREATED);

    }
    public function getTaskById( $id){

        $resp = $this->tasksService->getTaskById($id);

        if ( $resp == false ){
            return response()->json([
                "message" => "task with id = $id doesn't exist!"
            ]);
        }
        return response()->json([
            "task" => $resp
        ]);

    }
    public function deleteTask( $id){


        $resp = $this->tasksService->deleteTaskById($id);
        if ( $resp == false){
            return response()->json([
                "message" => "task with id = $id doesn't exits!!"],Response::HTTP_NOT_FOUND
            );
        }
        return response()->json([
            "message" => "task with id = $id is successfully deleted!"
        ]);

    }
    public function viewAllTasks( ){

        $tasks = $this->tasksService->getAllTasks();
        if ( $tasks == false){
            return response()->json([
                "message"=>"no tasks found!"
            ],Response::HTTP_NOT_FOUND);
        }
        return response()->json([
            "tasks" => $tasks
        ],Response::HTTP_OK);

    }
    public function editTaskStatusById( Request $request , $id){
        $validator = Validator::make($request->all(), [
            'status'=>'required'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(),Response::HTTP_BAD_REQUEST);
        }
        return $this->tasksService->editTaskStatusById($request , $id);
    }
}
