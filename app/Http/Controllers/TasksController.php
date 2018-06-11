<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Task;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     
    /**public function index()
    {
        $data = [];
        if (\Auth::check()) {
            $user = \Auth::user();
            $tasklists= Tasks::all();
            $data = [
                'user' => $user,
                'tasklists' => $tasklists,
            ];
            return view('tasks.index', $data);
        }else {
            return view('welcome');
        }
    }
    */
    
    public function index()
    {
        $data = [];
        if (\Auth::check()) {
            $user = \Auth::user();
            $tasks = $user->tasks()->orderBy('created_at', 'desc')->paginate(10);

            return view('tasks.index',[
                'tasks' => $tasks
            ]);
        }else {
            return view('welcome');
        }
    }
    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //$task = new Task;

        //return view('tasks.create', [
        //    'task' => $task,
        //]);
        
         $data = [];
        if (\Auth::check()) {
            $user = \Auth::user();
            $task = new Task;

            return view('tasks.create', [
            'task' => $task,
            ]);
        }else {
            return view('welcome');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'status' => 'required|max:10', 
            'content' => 'required|max:191',
        ]);
        //$task = new Task;
        //$task->status = $request->status;  
        //$task->content = $request->content;
        //$task->user_id = $Auth::user();
       // $task->save();
       
       $request ->user()->tasks()->create([
           'content' => $request ->content, 
           'status' => $request->status, 
           ]); 

        return redirect('/');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $task = \App\Task::find($id);
        if (\Auth::user()->id === $task->user_id) {
            $task->get();
        }
        
        else {
            return redirect('/');
        }
        
        $tasks = Task::find($id);

        return view('tasks.show', [
            'task' => $task,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $task = \App\Task::find($id);
        if (\Auth::user()->id === $task->user_id) {
            $tasklist->get();
        }
        
        else {
            return redirect('/');
        }
        
        $task = Tasks::find($id);

        return view('tasks.edit', [
            'task' => $task,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $task = \App\Task::find($id);
        if (\Auth::user()->id === $task->user_id) {
            $task->put();
        }
        else {
            return redirect('/');
        }
        
        $this->validate($request, [
            'status' => 'required|max:10',
            'content' => 'required|max:191',
        ]);

        $task = Task::find($id);
        $task->status = $request->status; 
        $task->content = $request->content;
        $task->save();

        return redirect('/');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        $task = \App\Task::find($id);
        
        if (\Auth::user()->id === $task->user_id) {
            $task->delete();
        }
        
        else {
            return redirect('/');
        }
        
        $task = Task::find($id);
        $task->delete();

        return redirect('/');
    }
}
