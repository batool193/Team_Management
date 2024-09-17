<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use App\Services\ProjectService;
use App\Http\Requests\ProjectFormRequest;

class ProjectController extends Controller
{
    protected $projectservice;

    public function __construct(ProjectService $projectService)
    {
      $this->projectservice = $projectService;
    }

    public function index()
    {
      $result = $this->projectservice->AllProjects();
      return response()->json($result);
    }


    public function store(ProjectFormRequest $request)
    {
      $result = $this->projectservice->CreateProject($request->validated());
      return response()->json($result);
    }

    public function AddUserToProject($project, $assignuser,$role)
{
    $result = $this->projectservice->AddUserToProject($project, $assignuser,$role);
    return response()->json($result);
}

    public function show($project)
    {
      $result = $this->projectservice->ShowProject($project);
      return response()->json($result);
    }


    public function update(ProjectFormRequest $request, $project)
    {
      $result = $this->projectservice->UpdateProject($request->validated(), $project);
      return response()->json($result);
    }


    public function destroy(Request $request, $project)
    {
      $result = $this->projectservice->DeleteProject($request, $project);
      return response()->json($result);
    }


    public function RestoreDeletedProject($project)
    {
      $result = $this->projectservice->RestoreDeletedProject($project);
          return response()->json($result);
}
public function GetLatestTask($project)
{
    $result = $this->projectservice->GetLatestTask($project);
    return response()->json($result);
}
public function GetOldestTask($project)
{
    $result = $this->projectservice->GetOldestTask($project);
    return response()->json($result);
}
public function HighestPriorityTask($project,$title)
{
    $result = $this->projectservice->HighestPriorityTask($project,$title);
    return response()->json($result);
}
}
