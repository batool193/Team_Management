<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TaskService;
use App\Http\Requests\TaskFormRequest;
use App\Http\Requests\UpdateTaskStatusRequest;

class TaskController extends Controller
{
    protected $taskservice;

    public function __construct(TaskService $taskservice)
    {
        $this->taskservice = $taskservice;
    }

    public function index($request)
    {
        $result = $this->taskservice->AllTasks($request);
        return response()->json($result);
    }


    public function store(TaskFormRequest $request)
    {
        $result = $this->taskservice->CreateTask($request->validated());
        return response()->json($result);
    }


    public function show($task)
    {
        $result = $this->taskservice->ShowTask($task);
        return response()->json($result);
    }


    public function update(TaskFormRequest $request, $task)
    {
        $result = $this->taskservice->UpdateTask($request->validated(), $task);
        return response()->json($result);
    }
    public function updateStatus(UpdateTaskStatusRequest $request, $task)
    {
        $result = $this->taskservice->updateTaskStatus($request->validated(), $task);
        return response()->json($result);
    }

    public function destroy(Request $request, $task)
    {
        $result = $this->taskservice->DeleteTask($request, $task);
        return response()->json($result);
    }

    public function RestoreDeletedTask($task)
    {
        $result = $this->taskservice->RestoreDeletedTask($task);
        return response()->json($result);
    }

    public function AddNote(Request $request)
    {
        $result = $this->taskservice->AddNote($request);
        return response()->json($result);

    }

}
