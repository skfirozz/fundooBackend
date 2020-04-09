<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\model\Notes;
use App\model\Label;
use App\model\Labelnotes;

class NoteController extends Controller
{
    public function createNotes(Request $request)//to create a new note
    {
        $inputValues = $request->all();
    
        if ($inputValues['title'] == null  && $inputValues['description'] == null) {
            return response()->json(['message' => 'title and notes should not be empty']);
        } else {
            $inputValues['userid']= $inputValues['token'];
            $data = Notes::create($inputValues);
            return response()->json(['message' => $data]);
        }
    }

    public function createLabel(Request $request)//to create new Labek Name
    {
        $labelname =$request->all();
        $find=Labelnotes::where(['userid' => $labelname['token'],'noteid'=>$labelname['noteid'],'labelname'=> $labelname['labelname']])->get(['id']);
        if(count($find) == 0)
        {
            $labelname['userid']=$labelname['token'];
            $data=Labelnotes::create($labelname);
            return response()->json(['message' => $data]);
        }
        else{
            return response()->json(['message' => 'label not created']);
        }   
    }

    public function getUniqueLabels(Request $request)//to get label names without repitation
    {
        $find = Labelnotes::where(['userid' => $request['token']])->get(['id']);
        if ($find) {
           $labels=Labelnotes::where(['userid' => $request['token']])->get(['id','labelname']);
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
        $find = Labelnotes::where(['userid' => $request['token']])->get(['id']);
        if ($find) {
           $labels=Labelnotes::where(['userid' => $request['token']])->get(['id','labelname','noteid']);
           return response()->json(['data' => $labels]);
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

    public function getAllNotes(Request $request)//to get all notes main ---- this is enough to maintain display notes
    {
        $find = Notes::find($request['token'])->get('id');
        if ($find) {
            $notes = Notes::where(['userid' => $request['token']])->get(['id','labelname','title','description','color','ispinned','isarchived','istrash','reminder']);
            return response()->json(['data' => $notes],200);
        }
        else 
        {
            return response()->json(['data' => 'unauthorized error']);
        }
    }


    public function getPinNotes(Request $request)//to get only pinned notes
    {
        $find = Notes::find($request['token']);
        if ($find) {
            $notes = Notes::where(['userid' => $request['token'] ,'ispinned'=>true])->get(['id','title','description','color','ispinned','isarchived','istrash','reminder']);
        return response()->json(['data' => $notes],200);
        }
        else 
        {
            return response()->json(['message' => 'unauthorized error']);
        }
    }

    public function getUnPinNotes(Request $request)//to get unpinned notes
    {
        $find = Notes::find($request['token']);
        if ($find) {
            $notes = Notes::where(['userid' => $request['token'] ,'ispinned'=>false])->get(['id','title','description','color','ispinned','isarchived','istrash','reminder']);
        return response()->json(['data' => $notes],200);
        }
        else 
        {
            return response()->json(['message' => 'unauthorized error']);
        }
    }

    public function getTrashNotes(Request $request)//to get only trash notes
    {
            $find = Notes::find($request['token']);
            if ($find) {
                $notes = Notes::where(['userid' => $request['token'] ,'istrash'=>true])->get(['id','title','description','color','istrash','reminder']);
                return response()->json(['data' => $notes],200);
            }
            else 
            {
                return response()->json(['message' => 'unauthorized error']);
            }
    }


    public function getArchiveNotes(Request $request)//to get only archived notes
    {
        $find = Notes::find($request['token']);
        if ($find) {
            $notes = Notes::where(['userid' => $request['token'] ,'isarchived'=> true,'istrash'=>false])->get(['id','title','description','color','ispinned','isarchived','istrash','reminder']);
        return response()->json(['data' => $notes],200);
        }
        else 
        {
            return response()->json(['message' => 'unauthorized error']);
        }
    }

    public function getallLabels(Request $request)
    {
        $find = Notes::find($request['token']);
        if ($find) {
            $notes = Notes::where(['userid' => $request['token']])->get(['id','label','title','description','color','ispinned','isarchived','istrash','reminder']);
        return response()->json(['data' => $notes],200);
        }
        else 
        {
            return response()->json(['message' => 'unauthorized error']);
        }
    }
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


    
}
