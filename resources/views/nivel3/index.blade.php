@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    Nivel 3 (Componentes)
                    <a class="btn btn-sm btn-primary float-right" href="{{route('nivel3.create')}}">Crear Nuevo</a>    
                </div>
                <div class="card-body">
                    <table class="table table-striped table-hover" id="table_id">
                        <thead>
                            <tr>
                                <th scope="col">Titulo</th>
                                <th scope="col">Autor</th>
                                <th scope="col">Creado</th>
                                <th scope="col">Actualizado</th>
                                <th scope="col" width="10px">&nbsp;</th>
                                <th scope="col" width="10px">&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($nivel3 as $L3)
                                <tr>
                                    <td>{{$L3->title}}</td>
                                    <td>{{$L3->author_name}}</td>
                                    <td>{{$L3->created_at}}</td>
                                    <td>{{$L3->updated_at}}</td>
                                    <td>
                                        <a href="{{route('nivel3.edit', $L3->title_slug)}}" class="btn btn-outline-success pull-right">Editar</a>
                                    </td>
                                    <td>
                                        {!! Form::open(['route' => ['nivel3.destroy', $L3->title_slug], 'method' => 'DELETE']) !!}
                                            <button class="btn btn-outline-danger">Eliminar</button>
                                        {!! Form::close() !!} 
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                   </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection