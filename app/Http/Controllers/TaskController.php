<?php
namespace App\Http\Controllers;
use App\task;
use Illuminate\Http\Request;

class TaskController extends Controller{
    public function showAllTasks(Request $request)
    {
        $tasks=Task::where('task_deleted','=','0');
        return response()->json($tasks->get());      
    }
    public function showUserTasks($id, Request $request)
    {
        $sortingparam1 = $request->sort;
        $sortingparam2 = $request->filterStatus;
        if ($sortingparam1 != NULL) {
            $tasks = Task::where([['task_deleted','=','0'], ['assignee', $id]])->orderBy($sortingparam1,'asc')->get();
            return response()->json($tasks);
        }
        if ($sortingparam2 === 'pending') {
            $tasks = Task::where('status', 'pending')->where([['task_deleted','=','0'], ['assignee', $id]])->get();
            return response()->json($tasks);
        } else if ($sortingparam2 === 'inprogress') {
            $tasks = Task::where('status', 'inprogress')->where([['task_deleted','=','0'], ['assignee', $id]])->get();
            return response()->json($tasks);
        } else if ($sortingparam2 === 'overdue') {
            $tasks = Task::where('status', 'overdue')->where([['task_deleted','=','0'], ['assignee', $id]])->get();
            return response()->json($tasks);
        } else if ($sortingparam2 === 'finished') {
            $tasks = Task::where('status', 'finished')->where([['task_deleted','=','0'], ['assignee', $id]])->get();
            return response()->json($tasks);
        } else{
            $tasks = Task::where([['task_deleted','=','0'], ['assignee', $id]])->get();
            return response()->json($tasks);
        }
    }

    public function createTask(Request $request){
        $requestData = $request->all();
        $this->validate($request, [
            'title' => 'bail|required|min:3|max:50',
            'description'=>'required|min:5|max:500',
            'assignee'=>'bail|required',
            'creator'=>'bail|required'
        ]);
        $task = Task::create($requestData);
        // $task->update($requestData);
        return response()->json($task, 201);
    }
    public function updateTask($id, Request $request){    
        $requestData = $request->all();
        $task = Task::find($id);
        if(!Task::find($id)){
            return response()->json("Task doesn't exist", 400);
        }
        // dd($request->status);
        // $this->validate($request, [
        //     'status' => 'required'
        // ]);
        $task->update($requestData);
        // $task->save();
        return response()->json($task,200);
    }
    public function deleteTask($id){
        $task = Task::find($id);
        if ($task == null) {
            return response()->json('Task doesnt exist', 400);
        }
        $task->is_deleted = 1 ;
        $task->status = 'deleted';
        $task->save();
        return response('Task deleted Successfully', 200);
    }
}
