<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ExampleTest extends TestCase
{

 //   use RefreshDatabase;


    public function testCreateTask()
    {
        $response = $this->json('POST', '/task', ['task_name' => 'test7Task',
            'description' => '1. Shampoo , 2. Fruits , 3. Vegetables',
            'status' => "Pending"]);


        $response->assertStatus(201)->assertExactJson([
            'created_at'=>null,
            'description' => '1. Shampoo , 2. Fruits , 3. Vegetables',
            'id'=>7,
            'status' => 'Pending',
            'task_name' => 'test7Task',
            'updated_at'=>null
            ]);

    }
   public function testGetTaskById()
    {

        $response = $this->json('GET', '/task/3', [] );


        $response->assertStatus(200)->assertJsonStructure([
                ['id',
                    'task_name',
                    'description',
                    'status',
                    'created_at',
                    'updated_at'
                ]

            ]);
    }

    public function testDeleteTaskById()
    {
        $this->json('DELETE', '/task/123', [])
            ->assertExactJson([
                'message' => "task with id = 123 doesn't exist"
            ]);
    }

    public function testViewAllTasks(){
        $this->json('GET','/task')
            ->assertJsonStructure([
                '*' => [

                ]
            ]);

    }

    public function testEditTaskStatusById(){

        $this->json('PATCH','/task/{id}',['status'=>"progress"],[1])
            ->assertStatus(400)
            ->assertJsonStructure([]);

    }
}
