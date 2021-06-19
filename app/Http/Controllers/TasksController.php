<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Task;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
/**
        // メッセージ一覧を取得
        $tasks = task::all();

        // メッセージ一覧ビューでそれを表示
        return view('tasks.index', [
            'tasks' => $tasks,
        ]);
    }
 */     
      
       
       $data = [];
       
       /**
        * (\Auth::check()は、ユーザがログインしているか
        * どうかを調べるための関数
        */
        if (\Auth::check()) { // 認証済みの場合
        
        
        // \Auth::user();ログイン中のユーザを取得
        $user = \Auth::user();
       
       /**
        * orderBy(created_at,'desc')
        * created_atのカラムを基準にdesc降順で表示
        * 
       */
        $tasks = $user->tasks()->orderBy('created_at', 'desc')->paginate(10);
       
        //dd($tasks);
        
       
        $data = [
              'user' => $user,
              'tasks' => $tasks,
             ];
         
        //  resources\views\tasks\index
         
        return view('tasks.index', $data,);
    
      }else {
         //ログインしていない場合、ログイン画面にリダイレクト
          return redirect('login');
      }
    } 


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $task = new task;
        
        return view ('tasks.create',[
            'task'=> $task,
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       // バリデーション
        $request->validate([
            
            
            'content' => 'required',
            'status' => 'required|max:10'
        ]);
        
        
         // 認証済みユーザ（閲覧者）の投稿として作成（リクエストされた値をもとに作成）
        $request->user()->tasks()->create([
          'content' => $request->content,
          'status' => $request->status,
        ]);

        
     

        // トップページへリダイレクトさせる
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
        $task =\App\Task::findOrFail($id);
        
         if (\Auth::id() === $task->user_id) {
        
        return view('tasks.show',[
            'task' => $task,
            ]);
         } else {
             return redirect('/');
         }     
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
             // idの値でメッセージを検索して取得
        $task =\App\Task::findOrFail($id);
        
        if (\Auth::id() === $task->user_id) {

        // メッセージ編集ビューでそれを表示
        return view('tasks.edit', [
            'task' => $task,
        ]);
        } else {
          return redirect('/');
        }
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
        
        // バリデーション
        $request->validate([
            
             'status' => 'required|max:10',
             'content' => 'required|',
        ]);
        
        // idの値でメッセージを検索して取得
        $task = Task::findOrFail($id);
        // メッセージを更新
        $task->status = $request->status;    // 追加
        $task->content = $request->content;
        $task->save();
        
             // トップページへリダイレクトさせる
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
        /**
         * findOrFailはfindと同じく、指定されたレコード
         * を取得する。
         * しかし、findOrFail はレコードが存在しない時
         * に404エラーをだす。
         */
          // idの値でメッセージを検索して取得
        $task = \App\Task::findOrFail($id);
        
        // 認証済みユーザ（閲覧者）がその投稿の所有者である場合は、投稿を削除
        if (\Auth::id() === $task->user_id) {
           $task->delete();
       } 
        // トップページへリダイレクトさせる
    
       return redirect('/');
    
    }
}
