@extends('layouts.app')

@section('content')

<div class="container mt-4">
    <p class="fw-bold fs-5">Modificar ajustes de la aplicación</p>
    <form method="POST" action="{{ route('env-editor.update_env') }}">
        @csrf
        @method('POST')

        <div class="row">
            <div class="d-flex align-items-start">
                <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    @php
                    $printed=[];
                    @endphp
                    @foreach ($keys as $key => $item)
                    @php
                    $name=explode('_',$key)[0];
                    @endphp
                    @if (!in_array($name,$printed) && !in_array($key,config('env-editor.exclude')))
                    <button class="nav-link {{$loop->first?'active':''}} text-end" id="v-pills-{{$name}}-tab"
                        data-bs-toggle="pill" data-bs-target="#v-pills-{{$name}}" type="button" role="tab"
                        aria-controls="v-pills-{{$name}}" aria-selected="true">{{$name}}</button>



                    @php
                    $printed[]=$name;
                    @endphp
                    @endif
                    @endforeach


                </div>
                <div class="tab-content w-100" id="v-pills-tabContent">
                    @foreach ($printed as $tab)
                    <div class="tab-pane fade {{$loop->first?'show active':''}}" id="v-pills-{{$tab}}" role="tabpanel"
                        aria-labelledby="v-pills-{{$tab}}-tab">
                        @foreach ($keys as $key => $item)
                        @php
                        $name=explode('_',$key)[0];
                        @endphp
                        @if ($name==$tab )
                        @if (!in_array($key,config('env-editor.exclude')))
                        <div class="mb-3">
                            <label for="{{$key}}" class="form-label">{{$key}}</label>
                            <input type="text" class="form-control" id="{{$key}}" name="{{$key}}"
                                value="{{$item['value']}}">
                        </div>

                        @else
                        <input type="hidden" class="form-control" name="{{$key}}" value="{{$item['value']}}">
                        @endif
                        @endif
                        @endforeach
                    </div>
                    @endforeach


                </div>
            </div>

        </div>
        <div class="mb-0 row">
            <div class="col-12 text-end">
                <button type="submit" class="btn btn-primary ">
                    {{ __('Guardar') }}
                </button>
            </div>
        </div>
    </form>
    @if (config('env-editor.enable_backups'))
    <div class="row">
        <form method="POST" action="{{ route('env-editor.restore_backup') }}">
            @csrf
            @method('POST')
            <div class="col-12">

                <p class="fw-bold fs-5 text-danger">Backups</p>
                <p>
                    Seleccione un backup para restaurar
                </p>
                @foreach ($backups as $backup)

                <div class="form-check">
                    <input type="radio" class="form-check-input" id="{{$backup['filename']}}" name="backup"
                        value="{{$backup['filepath']}}" required>
                    <label class="form-check-label" for="{{$backup['filename']}}">
                        {{$backup['filename']}}
                    </label>
                </div>

                @endforeach

            </div>
            <div class="col-12 text-end">
                <button type="submit" class="btn btn-primary" onclick="return confirm('¿Está seguro que desea realizar la restauración?')">
                    {{ __('Restaurar') }}
                </button>
            </div>
        </form>

    </div>
    @endif
   





</div>
@endsection