<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\model\Notes;
use App\model\Label;
use App\model\Labelnotes;

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
            return response()->json(['message' => $data]);
        }
    }

    public function createLabel(Request $request)
    {
        $labelname =$request->all();
        if($request['labelname'] != null )
        {
            $labelname['userid']=1;
            $data=Labelnotes::create($labelname);
            return response()->json(['message' => $data]);
        }
        else{
            return response()->json(['message' => 'label not created']);
        }
    }

    public function createLabelName(Request $request)
    {
        $labelname =$request->all();
        if($request['labelname'] != null )
        {
            $labelname['userid']=1;
            $data=Labelnotes::create($labelname);
            return response()->json(['message' => $data]);
        }
        else{
            return response()->json(['message' => 'label not created']);
        }
    }

    public function getUniqueLabels()
    {
        $find = Labelnotes::find(1);
        if ($find) {
           $labels=Labelnotes::where(['userid' => 1])->get(['id','labelname']);
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


    public function getLabelNotes()
    {
        $find = Labelnotes::find(1);
        if ($find) {
           $labels=Labelnotes::where(['userid' => 1])->get(['id','labelname','noteid']);
           return response()->json(['data' => $labels]);
        } else {
            return response()->json(['message' => 'unauthorized user']);
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
            $find->istrash =$request['istrash'];
            $find->save();
            return response()->json(['message' => 'trashed successfully']);
        } else {
            return response()->json(['message' => 'unauthorized user']);
        }
    }

    public function archive(Request $request)
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

    public function deleteNotes(Request $request)
    {
        $find = Notes::find($request['id']);
        if ($find) {
            $find = Notes::find($request['id'])->delete();
            return response()->json(['message' => 'Note Deleted Successfully']);
        } else {
            return response()->json(['message' => 'Note Id Invalid']);
        }
    }

    public function deleteLabel(Request $request) 
    {
        $find = Labelnotes::find($request['id']);
        if ($find) {
            $find =Labelnotes::find($request['id'])->delete();
            return response()->json(['message' => 'label Deleted Successfully']);
        } else {
            return response()->json(['message' => 'Note Id Invalid']);
        }
    }

    public function getAllNotes()
    {
        $find = Notes::where('userid', 1)->first();
        if ($find) {
            $notes = Notes::where(['userid' => 1 ,'isarchived'=>false, 'istrash'=>false])->get(['id','labelname','title','description','color','ispinned','isarchived','istrash']);
        return response()->json(['data' => $notes],200);
        }
        else 
        {
            return response()->json(['data' => 'unauthorized error']);
        }
    }


    public function getPinNotes()
    {
        $find = Notes::where('userid', 1)->first();
        if ($find) {
            $notes = Notes::where(['userid' => 1 ,'ispinned'=>true])->get(['id','title','description','color','ispinned','isarchived','istrash']);
        return response()->json(['data' => $notes],200);
        }
        else 
        {
            return response()->json(['message' => 'unauthorized error']);
        }
    }

    public function getUnPinNotes()
    {
        $find = Notes::where('userid', 1)->first();
        if ($find) {
            $notes = Notes::where(['userid' => 1 ,'ispinned'=>false])->get(['id','title','description','color','ispinned','isarchived','istrash']);
        return response()->json(['data' => $notes],200);
        }
        else 
        {
            return response()->json(['message' => 'unauthorized error']);
        }
    }

    public function getTrash(Request $request)
    {
            $find = Notes::where('userid', 1)->first();
            if ($find) {
                $notes = Notes::where(['userid' => 1 ,'istrash'=>true])->get(['id','title','description','color','istrash']);
                return response()->json(['data' => $notes],200);
            }
            else 
            {
                return response()->json(['message' => 'unauthorized error']);
            }
    }


    public function getArchive()
    {
        $find = Notes::where('userid', 1)->first();
        if ($find) {
            $notes = Notes::where(['userid' => 1 ,'isarchived'=> true,'istrash'=>false])->get(['id','title','description','color','ispinned','isarchived','istrash']);
        return response()->json(['data' => $notes],200);
        }
        else 
        {
            return response()->json(['message' => 'unauthorized error']);
        }
    }

    public function getallLabels()
    {
        $find = Notes::where('userid', 1)->first();
        if ($find) {
            $notes = Notes::where(['userid' => 1])->get(['id','label','title','description','color','ispinned','isarchived','istrash']);
        return response()->json(['data' => $notes],200);
        }
        else 
        {
            return response()->json(['message' => 'unauthorized error']);
        }
    }
    //working
    public function setColor(Request $request)
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
    public function updatePin(Request $request)
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

    public function updateNotes(Request $request)
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

}
