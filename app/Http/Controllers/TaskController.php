<?php

namespace App\Http\Controllers;

use App\Task;
use Illuminate\Http\Request;
use App\Events\TaskEvent;
use Pusher\Pusher;

class TaskController extends Controller
{
    public function showAllTasks(Request $request)
    {
        $sortingparam1 = $request->sort;
        $sortingparam2 = $request->filterStatus;
        if ($sortingparam1 != NULL) {
            $tasks = Task::where('task_deleted', '=', '0')->orderBy($sortingparam1, 'asc');
        }
        if ($sortingparam2 === 'pending') {
            $tasks2 = Task::where('status', 'pending')->where('task_deleted', '0');
        } else if ($sortingparam2 === 'inprogress') {
            $tasks2 = Task::where('status', 'inprogress')->where('task_deleted', '0');
        } else if ($sortingparam2 === 'overdue') {
            $tasks2 = Task::where('status', 'overdue')->where('task_deleted', '0');
        } else if ($sortingparam2 === 'finished') {
            $tasks2 = Task::where('status', 'finished')->where('task_deleted', '0');
        } else {
            $tasks2 = Task::where('task_deleted', '=', '0');
        }

        if ($sortingparam1 != NULL) {
            return response()->json($tasks->intersect($tasks2)->paginate(10));
        } else {
            return response()->json($tasks2->paginate(10));
        }
    }
    public function showUserTasks(Request $request)
    {
        $id = auth()->user()->id;
        $sortingparam1 = $request->sort;
        $sortingparam2 = $request->filterStatus;
        if ($sortingparam1 != NULL) {
            $tasks = Task::where(function ($query) use ($id) {
                $query->where([['assignee', $id], ['task_deleted', '=', '0']])
                    ->orWhere([['creator', $id], ['task_deleted', '=', '0']]);
            })
                ->orderBy($sortingparam1, 'asc')
                ->get();
        }
        if ($sortingparam2 === 'pending') {
            $tasks2 = Task::where([['task_deleted', '=', '0'], ['assignee', $id], ['status', 'pending']])->orWhere([['task_deleted', '=', '0'], ['creator', $id], ['status', 'pending']])->get();
        } else if ($sortingparam2 === 'inprogress') {
            $tasks2 = Task::where([['task_deleted', '=', '0'], ['assignee', $id], ['status', 'inprogress']])->orWhere([['task_deleted', '=', '0'], ['creator', $id], ['status', 'inprogress']])->get();
        } else if ($sortingparam2 === 'overdue') {
            $tasks2 = Task::where([['task_deleted', '=', '0'], ['assignee', $id], ['status', 'overdue']])->orWhere([['task_deleted', '=', '0'], ['creator', $id], ['status', 'overdue']])->get();
        } else if ($sortingparam2 === 'finished') {
            $tasks2 = Task::where([['task_deleted', '=', '0'], ['assignee', $id], ['status', 'finished']])->orWhere([['task_deleted', '=', '0'], ['creator', $id], ['status', 'finished']])->get();
        } else {
            $tasks2 = Task::where([['task_deleted', '=', '0'], ['assignee', $id]])->orWhere([['creator', $id], ['task_deleted', '=', '0']])->get();
        }
        if ($sortingparam1 != NULL) {
            return response()->json($tasks->intersect($tasks2));
        } else {
            return response()->json($tasks2);
        }
    }

    public function createTask(Request $request)
    {
        $requestData = $request->all();
        $this->validate($request, [
            'title' => 'bail|required|min:3|max:50',
            'description' => 'required|min:5|max:500',
            'assignee' => 'bail|required',
            'creator' => 'bail|required'
        ]);
        $task = Task::create($requestData);
        event(new TaskEvent($task));


        return response()->json($task, 201);
    }
    public function updateTask($id, Request $request)
    {
        $requestData = $request->all();
        $task = Task::find($id);
        if (!Task::find($id)) {
            return response()->json("Task doesn't exist", 400);
        }
        // dd($request->status);
        // $this->validate($request, [
        //     'status' => 'required'
        // ]);
        $task->update($requestData);
        // $task->save();
        return response()->json($task, 200);
    }
    public function deleteTask($id)
    {
        $task = Task::find($id);
        if ($task == null) {
            return response()->json("Task doesn't exist", 400);
        }
        $task->task_deleted = 1;
        // $task->status = 'deleted';
        $task->save();
        return response('Task deleted Successfully', 200);
    }
}
