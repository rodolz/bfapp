{{-- START TOPBAR --}}
        <div class='page-topbar '>
            <div class='logo-area'>
                
            </div>
            <div class='quick-area'>
                <div class='pull-right'>
                    <ul class="info-menu right-links list-inline list-unstyled">
                        <li class="profile">
                            <a href="#" data-toggle="dropdown" class="toggle">
                                <img src="/uploads/avatars/{{ Auth::user()->avatar }}" alt="user-image" class="img-circle img-inline">
                                <span>{{ Auth::user()->nombre }} <i class="fa fa-angle-down"></i></span>
                            </a>
                            <ul class="dropdown-menu profile animated fadeIn">
                               {{--  <li>
                                    <a href="#settings">
                                        <i class="fa fa-wrench"></i>
                                        Settings
                                    </a>
                                </li> --}}
                                <li>
                                    <a href="/perfil">
                                        <i class="fa fa-user"></i>
                                        Perfil
                                    </a>
                                </li>
                                {{-- <li>
                                    <a href="#help">
                                        <i class="fa fa-info"></i>
                                        Help
                                    </a>
                                </li> --}}
                                <li class="last">
                                 <a href="{{ url('/logout') }}"

                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            <i class="fa fa-lock"></i>
                                            Desconectarse
                                        </a>

                                        <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                </li>
                            </ul>
                        </li>
                    </ul>           
                </div>      
            </div>

        </div>
        {{-- END TOPBAR --}}