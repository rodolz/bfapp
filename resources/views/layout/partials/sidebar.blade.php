<!-- SIDEBAR - START -->
            <div class="page-sidebar ">

                <!-- MAIN MENU - START -->
                <div class="page-sidebar-wrapper" id="main-menu-wrapper">

                    <!-- USER INFO - START -->
                    <div class="profile-info row">

                        <div class="profile-image col-md-4 col-sm-4 col-xs-4">
                            <a href="/perfil">
                                <img src="/uploads/avatars/{{ Auth::user()->avatar }}" class="img-responsive img-circle">
                            </a>
                        </div>

                        <div class="profile-details col-md-8 col-sm-8 col-xs-8">

                            <h3>
                                <a href="/perfil">{{ Auth::user()->nombre }}</a>

                                <!-- Available statuses: online, idle, busy, away and offline -->
                                <span class="profile-status online"></span>
                            </h3>
                            <p class="profile-title">
                            </p>
                        </div>

                    </div>
                    <!-- USER INFO - END -->



                    <ul class='wraplist'>
                        <li class="{{ Request::is('/') ? 'open' : '' }}">
                            <a href="{{ URL::to('/') }}">
                                <i class="fa fa-tachometer"></i>
                                <span class="title">Resumen</span>
                            </a>
                        </li>
                        <li class="{{ Request::is('productos') || Request::is('productos/create') || Request::is('productos/inventario') || Request::is('categorias') || Request::is('categorias/create') ? 'open' : '' }}">
                            <a href="javascript:;">
                                <i class="fa fa-cubes"></i>
                                <span class="title">Productos</span>
                                <span class="{{ Request::is('productos') || Request::is('productos/create') || Request::is('productos/inventario') ? 'arrow open' : 'arrow' }}"></span>
                            </a>
                            <ul class="sub-menu" style='display:none;'>
                                <li>
                                    <a class="{{ Request::is('productos') || Request::is('productos/create') ? 'active' : '' }}" href="{{ URL::to('productos') }}" >Lista de Productos</a>
                                </li>
                                <li>
                                    <a class="{{ Request::is('categorias') || Request::is('categorias/create')? 'active' : '' }}" href="{{ URL::to('categorias') }}" >
                                        <!-- <span class="label label-orange">NUEVO</span> -->
                                        <span class="title">Categorias</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="{{ Request::is('productos/inventario') ? 'active' : '' }}" href="{{ URL::to('productos/inventario') }}" >
                                        <!-- <span class="label label-orange">NUEVO</span> -->
                                        <span class="title">Inventario</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="{{ Request::is('clientes') ? 'open' : '' }}">
                            <a href="{{ URL::to('/clientes') }}">
                                <i class="fa fa-users"></i>
                                <span class="title">Clientes</span>
                            </a>
                        </li>
                        <!-- Proveedores -->
                        <li class="{{ Request::is('proveedores') || Request::is('proveedores/create') || Request::is('proveedores/inventario') || Request::is('categorias') || Request::is('categorias/create') ? 'open' : '' }}">
                            <a href="javascript:;">
                                <i class="fa fa-industry"></i>
                                <span class="title">Proveedores</span>
                                <span class="{{ Request::is('proveedores') || Request::is('proveedores/create') || Request::is('proveedores/inventario') ? 'arrow open' : 'arrow' }}"></span>
                            </a>
                            <ul class="sub-menu" style='display:none;'>
                                <li>
                                    <a class="{{ Request::is('proveedores') || Request::is('proveedores/create') ? 'active' : '' }}" href="{{ URL::to('proveedores') }}" >Lista de Proveedores</a>
                                </li>
                                <li>
                                    <a class="{{ Request::is('proveedores_producto/create') ? 'active' : '' }}" href="{{ URL::to('proveedores_producto/create') }}" >
                                        <!-- <span class="label label-orange">NUEVO</span> -->
                                        <span class="title">Agregar Productos</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="{{ Request::is('proveedores/inventario') ? 'active' : '' }}" href="{{ URL::to('proveedores/inventario') }}" >
                                        <!-- <span class="label label-orange">NUEVO</span> -->
                                        <span class="title">Inventario</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="{{ Request::is('ordenes') || Request::is('ordenes/create') ? 'open' : '' }}">
                            <a href="javascript:;">
                                <i class="fa fa-file-pdf-o"></i>
                                <span class="title">Notas de Entrega</span>
                                <span class="{{ Request::is('ordenes') || Request::is('ordenes/create') ? 'arrow open' : 'arrow' }}"></span>
                            </a>
                            <ul class="sub-menu" style='display:none;'>
                                <li>
                                    <a class="{{ Request::is('ordenes') ? 'active' : '' }}" href="{{ URL::to('/ordenes') }}" >Ver Notas de entrega</a>
                                </li>
                                <li>
                                    <a class="{{ Request::is('ordenes/create') ? 'active' : '' }}" href="{{ URL::to('/ordenes/create') }}" > Nueva Nota de entrega</a>
                                </li>
                            </ul>
                        </li>
                        <li class="{{ Request::is('comisiones') || Request::is('comisiones/create') ? 'open' : '' }}">
                            <a href="javascript:;">
                                <i class="fa fa-handshake-o" aria-hidden="true"></i>
                                <span class="title">Comisiones</span>
                                <span class="{{ Request::is('comisiones') || Request::is('comisiones/create') ? 'arrow open' : 'arrow' }}"></span>
                            </a>
                            <ul class="sub-menu" style='display:none;'>
                                <li>
                                    <a class="{{ Request::is('comisiones') ? 'active' : '' }}" href="{{ URL::to('/comisiones') }}" >Ver Comisiones</a>
                                </li>
                                <li>
                                    <a class="{{ Request::is('comisiones/create') ? 'active' : '' }}" href="{{ URL::to('/comisiones/create') }}" > Nueva Comision</a>
                                </li>
                            </ul>
                        </li>
                       <li class="{{ Request::is('facturas') || Request::is('facturas/create') ? 'open' : '' }}">
                            <a href="{{ URL::to('facturas') }}">
                                <i class="fa fa-clipboard fa-lg"></i>
                                <span class="title">Control</span>
                            </a>
                        </li> 
                         <li class="{{ Request::is('pagos/cuentas_por_cobrar') || Request::is('pagos/nuevo_pago_index') || Request::is('pagos/nuevo_pago') || Request::is('pagos/cuentas_por_cobrar/resumen') || Request::is('pagos') ? 'open' : '' }}">
                            <a href="javascript:;">
                                <i class="fa fa-money"></i>
                                <span class="title">Pagos</span>
                                <span class="{{ Request::is('pagos/cuentas_por_cobrar') || Request::is('pagos/nuevo_pago_index') || Request::is('pagos/nuevo_pago') ? 'arrow open' : 'arrow' }}"></span>
                            </a>
                            <ul class="sub-menu" style='display:none;'>
                                <li>
                                    <a class="{{ Request::is('pagos') ? 'active' : '' }}" href="{{ URL::to('pagos') }}" >

                                    <span class="title">Cobranza</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="{{ Request::is('pagos/cuentas_por_cobrar') || Request::is('pagos/cuentas_por_cobrar/resumen') ? 'active' : '' }}" href="{{ URL::to('pagos/cuentas_por_cobrar') }}" >Cuentas por cobrar</a>
                                </li>
                                <li>
                                    <a class="{{ Request::is('pagos/nuevo_pago_index') || Request::is('pagos/nuevo_pago') || Request::is('pagos/nuevo_pago/{$id}') ? 'active' : '' }}" href="{{ URL::to('pagos/nuevo_pago_index') }}" > Nuevo Pago</a>
                                </li>
                            </ul>
                        </li>
                        <li class="{{ Request::is('ventas/cotizaciones') || Request::is('ventas/lista_precios') || Request::is('ventas/cotizaciones/create') ? 'open' : '' }}">
                            <a href="javascript:;">
                                <i class="fa fa-dollar" aria-hidden="true"></i>
                                <span class="title">Ventas</span>
                                <span class="{{ Request::is('ventas/cotizaciones') || Request::is('ventas/lista_precios') || Request::is('ventas/cotizaciones/create') ? 'arrow open' : 'arrow' }}"></span>
                            </a>
                            <ul class="sub-menu" style='display:none;'>
                                <li class={{ Request::is('ventas/cotizaciones') || Request::is('ventas/cotizaciones/create') ? 'open' : '' }}>
                                    <a href="javascript:;" class="{{ Request::is('ventas/cotizaciones') || Request::is('ventas/cotizaciones/create') ? 'active' : '' }}">
                                        <span class="title">Cotizaciones</span>
                                        <span class="{{ Request::is('ventas/cotizaciones') || Request::is('ventas/cotizaciones/create') ? 'arrow open' : 'arrow' }}"></span>
                                    </a>
                                    <ul class="sub-menu"">
                                        <li>
                                            <a class="{{ Request::is('ventas/cotizaciones/create') ? 'active' : '' }}" href="{{ URL::to('ventas/cotizaciones/create') }}">
                                                <span class="title">Crear Cotizacion</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="{{ Request::is('ventas/cotizaciones') ? 'active' : '' }}" href="{{ URL::to('ventas/cotizaciones') }}">
                                                <span class="title">Lista de Cotizaciones</span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a class="{{ Request::is('ventas/lista_precios') ? 'active' : '' }}" href="{{ URL::to('ventas/lista_precios') }}" >Lista de Precios</a>
                                </li>
                            </ul>
                        </li>
                        <li class="{{ Request::is('stats') ? 'open' : '' || Request::is('stats/resumen') ? 'open' : ''}}">
                            <a href="{{ URL::to('/stats') }}">
                                <i class="fa fa-line-chart"></i>
                                <span class="label label-orange">NUEVO</span>
                                <span class="title">Métricas</span>
                            </a>
                        </li>
                        <li class="{{ Request::is('register') ? 'open' : '' }}">
                            <a href="{{ URL::to('/register') }}">
                                <i class="fa fa-check-square-o"></i>
                                <span class="title">Registrar Usuario</span>
                            </a>
                        </li>
                    </ul>

                </div>
                <!-- MAIN MENU - END -->
            </div>
            <!--  SIDEBAR - END -->