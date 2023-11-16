<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{
    public function allTask(){
        try {
                $tasks = Task::orderBy('id', 'desc')->get();
                return response()->json($tasks);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function createTask(){
        try {

        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function storeTask($request){
        try {
            $data = new Task();
            $data->name = $request['name'];
            $data->description = $request['description'];

            if($data->save()){
                return response()->json(['status'=>true, 'message'=>'Successfully, Task created..!!']);
            }else{
                return response()->json(['status'=>false, 'error'=>'Sorry, Unable to create task..!!']);
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function updateTask(Request $request){
        try {
            if($request['type'] === 'checkbox'){
                $is_marked =Task::where('id', $request['taskId'])->update(['is_marked'=>$request['isMarked']]) ;
            
                if($is_marked){
                    $tasks = Task::orderBy('id', 'desc')->get();
                    return response()->json(['status'=>true,'message'=>'Successfully, tasks completed..!!' , 'data' =>$tasks]);
                }
            }else if($request['type'] === 'delete'){
                $delete =Task::where('id', $request['taskId'])->delete();

                if($delete){
                    return response()->json(['status'=>true,'message'=>'Successfully, tasks delete..!!']);
                }
            }
            return response()->json(['status'=>false,'error'=>'Sorry, Unable to do tasks..!!']);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

}
