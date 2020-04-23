<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\model\Notes;
use App\model\Labelnotes;

class NoteController extends Controller
{

    public function convertJwtToId($token){
        $value=app('App\Http\Controllers\AuthController')->me();
        $a1=$value->original->id;
        return $a1;
    }


    public function createNotes(Request $request)//to create a new note
    {
        $inputValues = $request->all();
        $token=$request['token'];
        if ($inputValues['title'] == null  && $inputValues['description'] == null) {
            return response()->json(['message' => 'title and notes should not be empty']);
        } else {
            $inputValues['userid']= NoteController::convertJwtToId($token);
            $data = Notes::create($inputValues);
            return response()->json(['message' => 'notes created successfully']);
        }
    }

    public function createLabel(Request $request)//to create new Label Name
    {
        $labelname =$request->all();
        $token=$request['token'];
        $userId=NoteController::convertJwtToId($token);
        $find=Labelnotes::where(['userid' => $userId,'noteid'=>$labelname['noteid'],'labelname'=> $labelname['labelname']])->get(['id']);
        if(count($find) == 0)
        {
            $labelname['userid']=$userId;
            $data=Labelnotes::create($labelname);
            return response()->json(['message' => 'label created']);
        }
        else{
            return response()->json(['message' => 'label not created']);
        }   
    }



    public function getUniqueLabels(Request $request)
    {
        $token=$request['token'];
        $userId=NoteController::convertJwtToId($token);
        
        $labels =Labelnotes::select(['id','labelname'])->distinct('labelname')->where('userid', $userId)->get();
        if ($labels) {
           $result=array();
           for($i=0;$i<count($labels);$i++)
            {
                $inc=0;
                $temp=$labels[$i]['labelname'];
                for($j=0;$j<count($result);$j++)
                {
                    if($temp==$result[$j]['labelname'])
                    $inc++;
                }
                if($inc==0)
                $result[]=$labels[$i];
            }
            return response()->json(['data' => $result]);
        } else {
            return response()->json(['message' => 'unauthorized user']);
        }
    }

    public function getLabelNotes(Request $request)//to get label names depend on note id
    {
        $token=$request['token'];
        $userId=NoteController::convertJwtToId($token);
        $find = Labelnotes::where(['userid'=> $userId])->get();
        if ($find) {
           return response()->json(['data' => $find]);
        } else {
            return response()->json(['message' => 'unauthorized user']);
        }
    }

    public function updateTrash(Request $request)//to update trash is deleted or removed from trash
    {
        $find = Notes::find($request['id']);
        if ($find) {
            $find->istrash =$request['istrash'];
            $find->save();
            return response()->json(['message' => 'trashed successfully']);
        } else {
            return response()->json(['message' => 'unauthorized user']);
        }
    }

    public function updateArchive(Request $request)//to update note is archived or not
    {
        $find = Notes::find($request['id']);
        if ($find) {
            $find->isarchived =$request['isarchived'];
            $find->save();
            return response()->json(['message' => 'archived successfully']);
        } else {
            return response()->json(['message' => 'unauthorized user']);
        }
    }

    public function deleteNotes(Request $request)//to delete notes permanently
    {
        $find = Notes::find($request['id']);
        if ($find) {
            $find = Notes::find($request['id'])->delete();
            return response()->json(['message' => 'Note Deleted Successfully']);
        } else {
            return response()->json(['message' => 'Note Id Invalid']);
        }
    }

    public function deleteLabel(Request $request) //to delete label names permanently
    {
        $find = Labelnotes::find($request['id']);
        if ($find) {
            $find =Labelnotes::find($request['id'])->delete();
            return response()->json(['message' => 'label Deleted Successfully']);
        } else {
            return response()->json(['message' => 'Note Id Invalid']);
        }
    }

    public function deleteCollaboration(Request $request)
    {
        $find = Notes::find($request['id']);
        if($find)
        {
            $find->collaborator = null;
            $find->save();
            return response()->json(['message' => 'Collaboration deleted successfully']);
        }
        else{
            return response()->json(['message' => 'Error while deleting']);
        }
    }

    public function getAllNotes(Request $request)//to get all notes main ---- this is enough to maintain display notes
    {
        $token=$request['token'];
        $userId=NoteController::convertJwtToId($token);
        if ($token) {
            $notes = Notes::where(['userid' => $userId])->get();
            return response()->json(['data' => $notes]);
        }
        else 
        {
            return response()->json(['data' => 'error']);
        }
    }

    // public function getallLabels(Request $request)
    // {

    //     $token=$request['token'];
    //     $userId=NoteController::convertJwtToId($token);
    //     if ($userId) {
    //         $notes = Notes::where(['userid' => $userId])->get(['id','label','title','description','color','ispinned','isarchived','istrash','reminder']);
    //     return response()->json(['data' => $notes],200);
    //     }
    //     else 
    //     {
    //         return response()->json(['message' => 'unauthorized error']);
    //     }
    // }
    //working
    public function setColor(Request $request)//to set color for notes
    {
        $find = Notes::find($request['id']);
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

    //working
    public function updatePin(Request $request)//to update pin
    {
        $find = Notes::find($request['id']);
        if($find)
        {
            $find->ispinned = $request['ispinned'];
            $find->save();
            return response()->json(['message' => 'pin changed successfully']);
        }
        else{
            return response()->json(['message' => 'unauthorized error']);
        }
    }

    public function updateNotes(Request $request)//to update notes data
    {
        $find=Notes::find($request['id']);
        if($find)
        {
            $find->title= $request['title'];
            $find->description=$request['description'];
            $find->save();
            return  response()->json(['message' => 'notes updates successfully']);
        }
        else{
            return response()->json(['message' => 'unauthorized error']);
        }
    }

    public function addReminder(Request $request)
    {
        $find=Notes::find($request['id']);
        if($find)
        {
            $find->reminder= $request['reminder'];
            $find->save();
            return  response()->json(['message' => 'successfully added reminder']);
        }
        else{
            return response()->json(['message' => 'unauthorized error']);
        }
    }

    public function deleteReminder(Request $request)
    {
        $find=Notes::find($request['id']);
        if($find)
        {
            $find->reminder= null;
            $find->save();
            return  response()->json(['message' => 'Reminder Removed successfully']);
        }
        else{
            return response()->json(['message' => 'unauthorized error']);
        }
    }

    public function searchData(Request $request)
    {
        $search = $request['search'];
        $token=$request['token'];
        $userId=NoteController::convertJwtToId($token);
        
        $user = Notes::where('userid', $userId)->where('title','LIKE',"%{$search}%")->orwhere('description','LIKE',"%{$search}%")->get();
        return response()->json(['data' =>  $user]);
    }
}
