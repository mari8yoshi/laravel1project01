<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Task;
use Auth;

class TasksController extends Controller
{
public function __construct() 
{
$this->middleware('auth'); 
}
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    // return view('tasks');
    //下記のように編集
    // {$tasks = Task::orderBy('deadline', 'asc')->get();
       // ddd(Auth user());
      {$tasks = Task::where('user_id',Auth::user()->id) 
      ->orderBy('deadline', 'asc')
->get();  
        //ddd($tasks);
        return view('tasks',[
            'tasks' => $tasks]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    { //ddd($request);
$validator = Validator::make($request->all(), [ 
    'task' => 'required|max:255',
'deadline' => 'required',
]);
// :
if ($validator->fails()) { 
    return redirect()
->route('tasks.index') 
->withInput() 
->withErrors($validator);
}
// Eloquent
$task = new Task;
$task->user_id = Auth::user()->id;
$task->task = $request->task; 
$task->deadline = $request->deadline; 
$task->comment = $request->comment;;
$task->save();
// tasks.index
return redirect()->route('tasks.index');

        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {$task = Task::find($id);
  return view('taskedit', [
      'task' => $task]);
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
        //バリデーション
        $validator = Validator::make($request->all(), [ 
        'task' => 'required|max:255',
'deadline' => 'required',
]);
if ($validator->fails()) { 
    return redirect()
->route('tasks.edit', $id) 
->withInput() 
->withErrors($validator);
    }
    //データ更新処理
    $task = Task::find($id); 
    $task->task = $request->task;
    $task->deadline = $request->deadline; 
    $task->comment = $request->comment; 
    $task->save();
return redirect()->route('tasks.index');
}

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {$task = Task::find($id); 
        //ddd($tasks);
    $task->delete();
return redirect()->route('tasks.index');
    }
}
