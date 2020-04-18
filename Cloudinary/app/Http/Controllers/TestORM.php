<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\model\Test;

class TestORM extends Controller
{
    public function insertTest(Request $request)
    {
        $test=new Test;
        $test->token=$request['token'];
        $test->about=$request['about'];
        $test->save();
        return \response()->json(['message'=> 'Inserted']);
    }

    public function read()
    {
        $test=Test::all();
        return \response()->json(['read'=> $test]);
    }

    public function find(Request $request)
    {
        $test=Test::find($request['id']);
        return \response()->json(['find'=> $test]);
    }

    public function count(Request $request)
    {
        $test=Test::where($request['token'],1)->count();
        return \response()->json(['count'=> $test]);
    }

    public function readMax()
    {
        $test=Test::all()->max('token');
        return \response()->json(['readMax'=> $test]);
    }
    
    public function update()
    {
        $test=Test::find(2);
        $test->token=8;
        $test->save();
        return \response()->json(['update'=> $test]);
    }
    
    public function delete()
    {
        $test=Test::find(3);
        $test->delete();
        return \response()->json(['delete'=> $test]);
    }

}
