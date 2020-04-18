<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\model\Test;

class TestORM extends Controller
{
    //to insert data in table
    public function insertTest(Request $request)
    {
        $test=new Test;
        $test->token=$request['token'];
        $test->about=$request['about'];
        $test->save();
        return \response()->json(['message'=> 'Inserted']);
    }

    //to read all values from table
    public function read()
    {
        $test=Test::all();
        return \response()->json(['read'=> $test]);
    }
 
    //to find the exact values by using id
    public function find(Request $request)
    {
        $test=Test::find($request['id']);
        return \response()->json(['find'=> $test]);
    }

    //to count how many rows present in table based on condition
    public function count(Request $request)
    {
        $test=Test::where($request['token'],1)->count();
        return \response()->json(['count'=> $test]);
    }

    //o read the maximum value in given column
    public function readMax()
    {
        $test=Test::all()->max('token');
        return \response()->json(['readMax'=> $test]);
    }
    
    //to update the data 
    public function update()
    {
        $test=Test::find(2);
        $test->token=8;
        $test->save();
        return \response()->json(['update'=> $test]);
    }

    //to delete the data
    public function delete()
    {
        $test=Test::find(3);
        $test->delete();
        return \response()->json(['delete'=> $test]);
    }

    //to delete set of data using array with destroy
    public function destroy()
    {
        $test=Test::destroy([2,4]);
        return \response()->json(['destroy'=>$test]);
    }

    //fetch data from table including trash
    public function withTrash()
    {
        $test=Test::withTrashed()
                    ->where('token',1)
                    ->get();
        return \response()->json(['withTrash'=>$test]);
    }
    
    //fettch the data from table only trashed data
    public function onlyTrash()
    {
        $test=Test::onlyTrashed()
                    ->where('token',1)
                    ->get();
        return \response()->json(['onlyTrash'=> $test]);
    }
}
