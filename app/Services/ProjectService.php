<?php

namespace App\Services;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Project;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class ProjectService
{


    public function AllProjects()
    {
        try {
            $user = JWTAuth::user();
            if ($user->is_admin == 'true')
                $projects = Project::with(['users', 'tasks'])->get();
            else
                $projects = $user->projects()->get();

            return $projects;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function CreateProject(array $data)
    {
        try {
            $user = JWTAuth::user();
            if ($user->is_admin == 'true') {
                $project = new Project();
                $project->name = $data['name'];
                $project->description = $data['description'];
                $project->save();
                return $project;
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    public function AddUserToProject($project,$assignuser,$role)
    {
        try {
            $user = JWTAuth::user();
            $user_id = $user->id;
         $assignuser = User::findOrFail($assignuser);
         $project = Project::findOrFail($project);
         $manager = $project->users()->wherePivot('user_id', $user_id)
         ->wherePivot('role', 'manager')->exists();
         if (($user->is_admin == 'true') || $manager)
         {       $project->users()->attach($assignuser->id, ['role' => $role,
                'last_activity' => Carbon::now(),]);
                if ($manager)
                {   $currentHours = $project->users()->find($user->id)->pivot->contribution_hours;
                  $project->users()-> updateExistingPivot($user->id,
              ['contribution_hours' => $currentHours+2,
              'last_activity'=>now()]);}
              return 'assigned';
              }
        } catch (Exception $e) {
            return  $e->getMessage();
        }
    }

    public function ShowProject($project)
    {
        try {
           $user = JWTAuth::user();
           if ($user->is_admin == 'true')
               $project = Project::findOrFail($project);
            else
                $project = $user->projects()
            ->wherePivot('project_id' , $project)->get();

            return $project;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function UpdateProject(array $data, $project)
    {
        try {
            $user = JWTAuth::user();
            if ($user->is_admin == 'true')
              {
            $project = Project::findOrFail($project);
            $project->update($data);
            return $project;
        }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function DeleteProject($request, $project)
    {
        try {
            $user = JWTAuth::user();
            if ($user->is_admin == 'true')
            {    $project = Project::withTrashed()->find($project);
            if ($request->has('Dlete_Permanently'))
                $project->forceDelete();
            else
                $project->delete();

            return "deleted";}
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function RestoreDeletedProject($project)
    {
        try {
           $user = JWTAuth::user();
            if ($user->is_admin == 'true')
            {
            $project =  Project::withTrashed()->find($project);
            if ($project && $project->trashed())
                // Restore the record
                $project->restore();
            return  $project;
        }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    public function GetLatestTask($project)
    {
        try {
            $user = JWTAuth::user();
            if ($user->is_admin == 'true')
                $project = Project::find($project);
           else
                $project = $user->projects()->where('project_id',$project)->first();

           if ($project)
               $latestTask = $project->latestTask;
            return $latestTask;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    public function GetOldestTask($project)
    {
        try {
            $user = JWTAuth::user();
            if ($user->is_admin == 'true')
                $project = Project::find($project);
            else
            $project = $user->projects()->where('project_id',$project)->first();

            if ($project)
            $oldestTask = $project->oldestTask;
            return $oldestTask;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    public function HighestPriorityTask($project,$title)
    {
        try {
            $user = JWTAuth::user();
            if ($user->is_admin == 'true')

                $project = Project::find($project);

            else

                $project = $user->projects()->where('project_id' , $project)
                    ->wherePivot( 'role' , 'manager')
                    ->first();

                    if ($project)
           {
            $task = $project->HighestPriorityTask($title);
            return $task;}
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
