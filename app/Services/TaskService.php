<?php

namespace App\Services;

use Exception;
use Carbon\Carbon;
use App\Models\Task;
use App\Models\Project;
use App\Enums\TaskStatus;
use Tymon\JWTAuth\Facades\JWTAuth;

class TaskService
{


    public function AllTasks($request)
    {
        try {
            $user = JWTAuth::user();
        $tasks = Task::all();

            if ($request->has('status'))
                // Filter by status
                $tasks = $user->whereRelation('tasks', 'status', $request->status)->get();
            if ($request->has('priority'))
                // Filter by priority
                $tasks = $user->whereRelation('tasks', 'priority',$request->priority)->get();
            return $tasks;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }


    public function CreateTask(array $data)
    {
        try {
            $project = Project::find($data['project_id']);
            $user = JWTAuth::user();
           $manager= $project->users()->where('user_id',$user->id)
           ->wherePivot('role','manager')->exists();
            if ($project && ($user->is_admin == 'true' || $manager) ) {
                $task = new Task();
                $task->title = $data['title'];
                $task->description = $data['description'];
                $task->status = TaskStatus::New;
                $task->priority = $data['priority'];
                $task->project_id = $data['project_id'];
                $task->save();
                 if ($manager)
              {   $currentHours = $project->users()->find($user->id)->pivot->contribution_hours;
                $project->users()-> updateExistingPivot($user->id,
            ['contribution_hours' => $currentHours+2,
            'last_activity'=>now()]);}

                return $task;
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function ShowTask($task)
    {
        try {
            $user = JWTAuth::user();
            if ($user->is_admin == 'true')
            $task = Task::findOrFail($task);
        else
        $task = $user->tasks;
          return $task;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function UpdateTask(array $data, $task)
    {
        try {
            $user = JWTAuth::user();
            $task = Task::findOrFail($task);
            $manager = $task->project->users()->where('user_id', $user->id)
            ->wherePivot('role','manager')->exists();
            if ($task && ($user->is_admin == 'true' || $manager)  )
              {
                $task->update($data);
                if ($manager)
                {
                 $currentHours = $task->project()->users()->find($user->id)->pivot->contribution_hours;
                  $task->project()->users()-> updateExistingPivot($user->id,
              ['contribution_hours' => $currentHours+2,
              'last_activity'=>now()]);}
            return $task;

              }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    public function updateTaskStatus($data, $task)
    {
        try {
           $user = JWTAuth::user();
           $task = Task::findOrFail($task);
           $project = Project::findOrFail($task->project_id);
            $developer =  $project->users()->where('user_id', $user->id)
            ->wherePivot('role','developer')->exists();
      $status = $task->status->value;
           if ($task && $developer && ($status !== 'completed'))
           {
             $task->update(['status' => $data['status']]);

            if ($data['status'] == 'completed')
                 {   $task->due_date = Carbon::now();
                    $currentHours = $project->users()->find($user->id)->pivot->contribution_hours;
                    $project->users()-> updateExistingPivot($user->id,
                ['contribution_hours' => $currentHours+2,
                'last_activity'=>now()]);
            }
                return $task;
            }
           }
         catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function DeleteTask($request, $task)
    {
        try {
            $task = Task::withTrashed()->find($task);
            $user = JWTAuth::user();
            $manager = $task->project->users()
            ->wherePivot(['user_id'=>$user->id,'role'=> 'manager'])->exists();
            if ($task && ($user->is_admin == true || $manager)) {
                if ($request->has('Dlete_Permanently'))
                    $task->forceDelete();
                else
                    $task->delete();
                return "deleted";
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function RestoreDeletedTask($task)
    {
        try {
            $task = Task::withTrashed()->findOrFail($task);
            $user = JWTAuth::user();
            $manager = $task->project->users()
            ->wherePivot(['user_id'=>$user->id,'role'=> 'manager'])->exists();
            if ($task->trashed() && ($user->is_admin == true || $manager))
                // Restore the record
                $task->restore();
            return  $task;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function AddNote($request)
    {
        try {
            $task = Task::findOrFail($request->task);
            $user = JWTAuth::user();
            $project = Project::findOrFail($task->project_id);
            $tester = $project->users()->where('user_id',$user->id)
            ->wherePivot('role','tester')->exists();
            if ($tester)
          {
           $task->notes = $request->note;
           $task->save();
            $task->due_date = Carbon::now();
            $currentHours = $project->users()->find($user->id)->pivot->contribution_hours;
            $project->users()-> updateExistingPivot($user->id,
        ['contribution_hours' => $currentHours+2,
        'last_activity'=>now()]);
        return $task;
    }

        }
        catch (Exception $e) {
            return $e->getMessage();}
    }


}
