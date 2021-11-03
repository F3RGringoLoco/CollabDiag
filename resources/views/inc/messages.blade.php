@if(count($errors) > 0)
    @foreach($errors->all() as $error)
        <div class="alert alert-danger mx-auto" style="width: 70%">
            {{$error}}
        </div>
    @endforeach
@endif

@if(session('success'))
        <div class="alert alert-success mx-auto" style="width: 70%">
            {{session('success')}}
        </div>
    
@endif

@if(session('error'))
    <div class="alert alert-danger mx-auto" style="width: 70%">
        {{session('error')}}
    </div>
@endif