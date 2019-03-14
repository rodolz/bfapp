
@extends('layout.master')

	@section('page-title')
    <div class="pull-left">
        <h1 class="title">Perfil</h1>
    </div>
	@endsection
    
@section('content')
    <div class="col-lg-12">
        <section class="box nobox">
            <div class="content-body">    
                <div class="row">
                    <div class="col-md-3 col-sm-4 col-xs-12">
                        <div class="uprofile-image">
                            <img src="/uploads/avatars/{{ $user->avatar }}" class="img-responsive" alt="{{$user->nombre}} avatar">
                        </div>
                        <div class="uprofile-name">
                            <h3>
                                <a href="#">{{ $user->nombre }}</a>
                                <!-- Available statuses: online, idle, busy, away and offline -->
                                <span class="uprofile-status online"></span>
                            </h3>
                            <div class="uprofile-title">
                                @foreach($user->roles as $role)
                                    {{ $role->name }} <br/>
                                @endforeach
                            </div>
                            @if(Auth::user()->id == $user->id)
                            <div class="row top15">
                                <form enctype="multipart/form-data" action="/users/perfil/update_avatar" method="POST" >
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                                    <div class="custom-file-upload">
                                        <input type="file" name="avatar" id="file" class="inputfile">
                                        <label id="file-label" for="file"><strong>Elige un nuevo avatar</strong></label>
                                    </div>

                                    <div class="form-group">
                                        <button type="submit" id="avatar-submit" class="btn btn-sm btn-corner btn-purple" disabled="true">Cambiar Avatar</button>
                                    </div>
                                </form>
                            </div>
                            @endif
                        </div>
                        <div class="uprofile-info top15">
                            <ul class="list-unstyled">
                                <li><i class="fa fa-envelope"></i>{{ $user->email }}</li>
                                <li><i class="fa fa-calendar"></i>{{ $user->created_at->format('F Y') }}</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-9 col-sm-8 col-xs-12">
                        <div class="uprofile-content">
                            <div class="row">
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </div>

@endsection

@section('add-plugins')
    <script type="text/javascript">
        $('#file').change(function(){
            var filename = $(this).val().replace(/C:\\fakepath\\/i, '')
            if(filename == ""){
                $('#file-label').html('Elige un nuevo avatar');
                $("#avatar-submit").prop('disabled', true);
            }
            else{
                $('#file-label').html(filename);
                $("#avatar-submit").prop('disabled', false);
            }
        });
    </script>
@endsection