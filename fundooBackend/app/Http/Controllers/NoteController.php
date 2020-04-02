<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\model\Notes;

class NoteController extends Controller
{
    public function createNotes(Request $request)
    {
        $inputValues = $request->all();

        // $token=$request->header('Authorization');
        // $tokenArray=preg_split("/\./", $token);
        // $decodeToken=base64_decode($tokenArray[1]);
        // $decodeToken=json_decode($decodeToken,true);
        // $id=$decodeToken['sub'];
    
        if ($inputValues['title'] == null  && $inputValues['description'] == null) {
            return response()->json(['message' => 'title and notes should not be empty']);
        } else {
            $inputValues['userid']= 1;
            $data = Notes::create($inputValues);
            // echo $id;
            return response()->json(['message' => 'Notes created successfully']);
        }
    }

    public function editNotes(Request $request)
    {
        $inputValues = $request->all();
        $data = Notes::find($request['id']);

        if ($data) {
            if ($inputValues['title'] == null && $inputValues['description'] == null) {
                return response()->json(['message' => 'title and notes should not be empty']);
            } else {
                $data->title = $inputValues['title'];
                $data->description = $inputValues['description'];
                $data->save();
                return response()->json(['message' => 'title and notes updated']);
            }
        } else {
            return response()->json(['message' => 'user not found']);
        }
    }

    public function trash(Request $request)
    {
        $find = Notes::find($request['id']);
        if ($find) {
            $find->istrash = 1;
            $find->save();
            return response()->json(['message' => 'notes trashed']);
        } else {
            return response()->json(['message' => 'unauthorized error']);
        }
    }

    public function notTrash(Request $request)
    {
        $find = Notes::find($request['id']);
        if ($find) {
            $find->istrash = 0;
            $find->save();
            return response()->json(['message' => 'restores notes successfully']);
        } else {
            return response()->json(['message' => 'unauthorized error']);
        }
    }

    public function archive(Request $request)
    {
        $find = Notes::find($request['id']);
        if ($find) {
            $find->isarchive = 1;
            $find->save();
            return response()->json(['message' => 'archived successfully']);
        } else {
            return response()->json(['message' => 'unauthorized user']);
        }
    }

    public function unarchive(Request $request)
    {
        $find = Notes::find($request['id']);
        if ($find) {
            $find->isarchive = 0;
            $find->save();
            return response()->json(['message' => 'unarchived successfully']);
        } else {
            return response()->json(['message' => 'unauthorized user']);
        }
    }

    public function deleteNotes(Request $request)
    {
        $find = Notes::find($request['id']);
        if ($find) {
            $find = Notes::find($request['id'])->delete();
            return response()->json(['message' => 'Note Deleted Successfully'], 200);
        } else {
            return response()->json(['message' => 'Note Id Invalid'], 404);
        }
    }

    public function getNotes()
    {

        /*
        $token=$request->header('Authorization');
        $tokenArray=preg_split("/\./", $token);
        $decodeToken=base64_decode($tokenArray[1]);
        $decodeToken=json_decode($decodeToken,true);
        $id=$decodeToken['sub'];
        */
        $find = Notes::where('userid', 1)->first();
        if ($find) {
            $notes = Notes::where('userid',1)->get(['id']);
            $array=array();
            foreach($notes as $n)
            {
                $note=Notes::where(['id' => $n['id']])->get(['id','title','description','color',]);
        //         // if($note)
                // echo $n,"\n";
                $array[]=$note;
            }
            return $array;
        }
        else 
        {
            return response()->json(['message' => 'unauthorized error']);
        }
    }

    public function displayTrash(Request $request)
    {
         /*
        $token=$request->header('Authorization');
        $tokenArray=preg_split("/\./", $token);
        $decodeToken=base64_decode($tokenArray[1]);
        $decodeToken=json_decode($decodeToken,true);
        $id=$decodeToken['sub'];
        */
        $find = Notes::where('userid', $request->id)->first();
        if ($find) {
    
            $notes = Notes::where('userid',$request->id)->get(['id']);
            foreach($notes as $n)
            {
                $note=Notes::where(['id' => $n['id'],'istrash' => '1','isarchived'=> '0'])->get(['id','userid','title','description','color',]);
                if($note != '[]')
                echo $note,"\n";
            }
        }
        else 
        {
            return response()->json(['message' => 'unauthorized error']);
        }
    }

    public function displayArchive(Request $request)
    {
         /*
        $token=$request->header('Authorization');
        $tokenArray=preg_split("/\./", $token);
        $decodeToken=base64_decode($tokenArray[1]);
        $decodeToken=json_decode($decodeToken,true);
        $id=$decodeToken['sub'];
        */
        $find = Notes::where('userid', $request->id)->first();
        if ($find) {
    
            $notes = Notes::where('userid',$request->id)->get(['id']);
            foreach($notes as $n)
            {
                $note=Notes::where(['id' => $n['id'],'istrash' => '0','isarchived'=> '1'])->get(['id','userid','title','description','color',]);
                if($note != '[]')
                echo $note,"\n";
            }
        }
        else 
        {
            return response()->json(['message' => 'unauthorized error']);
        }
    }


    public function setColor(Request $request)
    {
        $find = Notes::find($request['id']);
        // echo $find,"\n";
        if($find)
        {
            $find->color = $request['color'];
            $find->save();
            return response()->json(['message' => 'color added successfully']);
        }
        else{
            return response()->json(['message' => 'unauthorized error']);
        }
    }
}
