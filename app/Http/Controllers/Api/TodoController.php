<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\Todo;
class TodoController extends Controller
{
    public function index()
    {
        $todo = Todo::get();
        return $todo;
    }
    public function store(Request $request)
    {

        $id = Auth::id();
        $request->validate([
            'content' =>'required',
        ]);
        $todo = Todo::create([
            'user_id'   =>  $id,
            'content'   =>  $request->content,
            'completion_time' => now(),
        ]);
        if($todo){
            return response()->json([
                'message' => 'Todo created successfully!',
                'status_code' => 201
            ], 201);
        }else{
            return response()->json([
                'message' => 'Some error occurred, Please try again',
                'status_code' => 500
            ], 500);
        }
    }
    public function update(Request $request , $id)
    {
        $user_id = Auth::id();
        $todo = Todo::find($id);
        $todo->user_id = $user_id;
        $todo->content = $request->content;
        $todo->completion_time = now();
        if ($todo->update()) {
            return response()->json([
                'message' => 'Todo list updated successfully!',
                'status_code' => 200
            ], 200);
        }
    }
    public function owns()
    {
        $id = Auth::id();
        $todo = Todo::find($id);
        return $todo;
    }
    public function delete($id)
    {
        $delete = Todo::find($id);
        if ($delete->delete()) {
            return response()->json([
                'message' => 'Todo list deleted successfully!',
                'status_code' => 200
            ], 200);
        }

    }
    public function incomplete($id)
    {

        $todo = Todo::find($id);
        $todo->completion_time = "";
        if ($todo->update()) {
            return response()->json([
                'message' => 'Todo list incomplete successfully!',
                'status_code' => 200
            ], 200);
        }
    }

}
