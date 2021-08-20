<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Services\TasksService;
use Illuminate\Validation\Rule;
class TaskController extends Controller
{
    // create a single task
    protected $tasksService;


    public function __construct(TasksService $tasksService)
    {
        $this->tasksService = $tasksService;
    }

    // create a task
    public function CreateTask(Request $request){

        $validator = Validator::make($request->all(), [
            'task_name' => 'required|unique:tasks',
            'description' => 'required',
            'status' => ['required',Rule:: in(['Pending','In Progress','Done'])]
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(),Response::HTTP_BAD_REQUEST);
        }

        return $this->tasksService->createTask($request->input('task_name'),$request->input('description'),$request->input('status'));
    }
    // get a task by its id
    public function GetTaskById( $id){

        return $this->tasksService->getTaskById($id);

    }
    // delete a task by its id
    public function DeleteTaskById( $id){

        return $this->tasksService->deleteTaskById($id);

    }
    // get list of all tasks
    public function ViewAllTasks( ){

        return  $this->tasksService->getAllTasks();


    }
    // edit task status for a particular task
    public function EditTaskStatusById( Request $request , $id){
        $validator = Validator::make($request->all(), [
            'status' => ['required',Rule:: in(['Pending','In Progress','Done'])]
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(),Response::HTTP_BAD_REQUEST);
        }
        return $this->tasksService->editTaskStatusById($request , $id);
    }


    // extra to create multiple tasks
/*    public function createTasks( Request $request){

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

    }*/
}
