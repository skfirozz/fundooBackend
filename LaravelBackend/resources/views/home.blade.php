<!-- @extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    You are logged in!
                </div>
            </div>
        </div>
    </div>
</div>
@endsection -->


<!DOCTYPE html>
<html>
<head>
   <meta name="viewport" content="width=device-width">
   <title>Cloudinary Image Upload</title>
   <meta name="description" content="Prego is a project management app built for learning purposes">
   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
   <link rel="stylesheet" href="{{asset('css/style.css')}}">
</head>
<body>

<div class="container" style="margin-top: 100px;">
   <div class="row">
       <h4 class="text-center">
           Upload Images
       </h4>

       <div class="row">
           <div id="formWrapper" class="col-md-4 col-md-offset-4">
               <form class="form-vertical" role="form" enctype="multipart/form-data" method="post" action="{{ route('uploadImage')  }}">
                   {{csrf_field()}}
                   @if(session()->has('status'))
                       <div class="alert alert-info" role="alert">
                           {{session()->get('status')}}
                       </div>
                   @endif
                   <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                       <input type="file" name="image_name" class="form-control" id="name" value="">
                       @if($errors->has('image_name'))
                           <span class="help-block">{{ $errors->first('image_name') }}</span>
                       @endif
                   </div>

                   <div class="form-group">
                       <button type="submit" class="btn btn-success">Upload Image </button>
                   </div>
               </form>

           </div>
       </div>
   </div>
</div>
</body>
</html>