
@extends('layout.master')

	@section('page-title')
		Perfil
	@endsection
    
@section('content')
    <div class="col-lg-12">
        <section class="box nobox">
            <div class="content-body">    
                <div class="row">
                    <div class="col-md-3 col-sm-4 col-xs-12">
                        <div class="uprofile-image">
                            <img src="/uploads/avatars/{{ Auth::user()->avatar }}" class="img-responsive" alt="{{  Auth::user()->nombre }} avatar">
                        </div>
                        <div class="uprofile-name">
                            <h3>
                                <a href="#">{{ Auth::user()->nombre }}</a>
                                <!-- Available statuses: online, idle, busy, away and offline -->
                                <span class="uprofile-status online"></span>
                            </h3>
                            <div class="uprofile-title">
                                @foreach(Auth::user()->roles as $role)
                                    @if ($loop->last)
                                        {{ $role->display_name }}
                                    @else
                                    {{ $role->display_name }} /
                                    @endif
                                @endforeach
                            </div>
                            <div class="row top15">
                                <form enctype="multipart/form-data" action="/perfil" method="POST" >
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
                        </div>
                        <div class="uprofile-info top15">
                            <ul class="list-unstyled">
                                <li><i class="fa fa-envelope"></i>{{ Auth::user()->email }}</li>
                                <li><i class="fa fa-calendar"></i>{{ Auth::user()->created_at->format('F Y') }}</li>
                                {{-- <li><i class="fa fa-user"></i> 340 Contacts</li>
                                <li><i class="fa fa-suitcase"></i> Tech Lead, YIAM</li> --}}
                            </ul>
                        </div>
                        {{-- <div class=" uprofile-social">

                            <a href="#" class="btn btn-primary btn-md facebook"><i class="fa fa-facebook icon-xs"></i></a>
                            <a href="#" class="btn btn-primary btn-md twitter"><i class="fa fa-twitter icon-xs"></i></a>
                            <a href="#" class="btn btn-primary btn-md google-plus"><i class="fa fa-google-plus icon-xs"></i></a>
                            <a href="#" class="btn btn-primary btn-md dribbble"><i class="fa fa-dribbble icon-xs"></i></a>

                        </div>  --}}

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