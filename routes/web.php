<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

		Auth::routes();

		Route::group(['middleware' => 'auth'], function(){
			//Perfil de usuario
			Route::get('/perfil','UserController@perfil');
			Route::post('/perfil','UserController@update_avatar');

			//Registro
			Route::get('register','Auth\RegisterController@index');

			Route::get('/',[
				'as' => 'index',
				'uses' =>'DashboardController@dashboard'
				]
			);

			// Rutas de productos
			Route::get('productos','ProductoCRUDController@index')->name('productos.index');
			Route::get('productos/getdata','ProductoCRUDController@getdata');
			Route::post('check_inventario', 'ProductoCRUDController@check_inventario');
			Route::post('check_precio', 'ProductoCRUDController@check_precio');
			Route::get('productos/inventario_pdf', 'ProductoCRUDController@inventario_pdf');
			Route::get('productos/inventario',[
				'as' => 'productos.inventario',
				'uses' => 'ProductoCRUDController@inventario'
			]);

			Route::resource('productos','ProductoCRUDController', ['except' => [
					'index',
			]]);
			Route::post('productos/update','ProductoCRUDController@actualizar_producto');

			// RUTAS DE CLIENTES
			Route::get('clientes','ClienteCRUDController@index')->name('clientes.index');
			Route::resource('clientes', 'ClienteCRUDController', ['except' => [
					'index',
			]]);

			// RUTAS DE PROVEEDORES
			Route::get('proveedores','ProveedorController@index')->name('proveedores.index');
			Route::resource('proveedores', 'ProveedorController', ['except' => [
					'index',
			]]);

			// RUTAS DE PRODUCTO PROVEEDORES
			Route::post('producto_proveedor/check_precio', 'ProductoProveedorController@check_precio');
			Route::resource('proveedores_producto', 'ProductoProveedorController', ['except' => [
					'index','store'
			]]);
			Route::post('productos_proveedores_store', 'ProductoProveedorController@store');
			
			// RUTAS DE PURCHASE ORDERS
			Route::get('purchase_orders/load_into_stock/{id}','PurchaseOrderController@load')->name('purchase_orders.load');
			Route::get('purchase_orders','PurchaseOrderController@index')->name('purchase_orders.index');
			Route::get('po_pdf/{idPO}', 'PurchaseOrderController@po_pdf');
			Route::get('purchase_orders/select_proveedor','PurchaseOrderController@select_proveedor')->name('purchase_orders.select_proveedor');
			Route::post('purchase_orders/proveedor_productos','PurchaseOrderController@proveedor_productos')->name('purchase_orders.proveedor_productos');
			Route::resource('purchase_orders', 'PurchaseOrderController', ['except' => [
				'index',
			]]);

			// RUTAS CATEGORIAS
			Route::get('categorias','CategoriaController@index')->name('categorias.index');

			Route::resource('categorias','CategoriaController', ['except' => [
					'index',
			]]);
			

			// RUTAS DE ORDENES
			Route::get('ordenes','OrdenesController@index')->name('ordenes.index');
			Route::get('orden-pdf/{idOrden}', 'OrdenesController@pdf');
			Route::resource('ordenes','OrdenesController', ['except' => [
				'index',
			]]);
			Route::post('nueva_orden', 'OrdenesController@nueva_orden');
			Route::post('update_orden', 'OrdenesController@update_orden');
			Route::post('nueva_ordenC', 'OrdenesController@nueva_ordenC');
			Route::get('/ordenes/edit_from_cotizacion/{idCotizacion}', [
				'as' => 'ordenes.create_from_cotizacion',
				'uses' => 'OrdenesController@nueva_orden_cotizacion'
			]);

			// RUTAS DE COMISIONES
			Route::get('comisiones','ComisionesController@index')->name('comisiones.index');
			Route::get('comision-pdf/{idComision}', 'ComisionesController@pdf');
			Route::resource('comisiones','ComisionesController', ['except' => [
				'index',
			]]);
			Route::post('nueva_comision', 'ComisionesController@nueva_comision');
			Route::post('comisiones_repartidor', 'ComisionesController@comisiones_repartidor');


			// RUTAS DE FACTURAS
			Route::get('facturas','FacturasController@index')->name('facturas.index');
			Route::resource('facturas','FacturasController', ['except' => [
					'index',
			]]);
			Route::post('nueva_factura', 'FacturasController@nueva_factura');
			Route::get('factura-pdf/{idFactura}/{idOrden}', 'FacturasController@pdf');
			Route::get('factura-pdf/{idFactura}', 'FacturasController@pdf');
			Route::get('facturas/create-by-id/{idFactura}', 'FacturasController@create_by_id');
			Route::post('facturas/update/num_fiscal','FacturasController@actualizar_num_fiscal');


			// RUTAS DE VENTAS
			Route::get('cotizacion-pdf/{idCotizacion}', 'CotizacionController@nueva_cotizacion_pdf');
			Route::post('ventas/lista_precios_pdf', [
				'as' => 'ventas.lista_precios_pdf',
				'uses' => 'VentasController@lista_precios_pdf'
			]);
			Route::get('ventas/lista_precios_dompdf', 'VentasController@lista_precios_dompdf');
			Route::get('ventas/lista_precios','VentasController@lista_precios')->name('ventas.index');
			Route::get('ventas/cotizaciones','CotizacionController@index')->name('ventas.cotizaciones.index');
			// Route::get('ventas/cotizaciones/{id}','CotizacionController@edit')->name('ventas.cotizaciones.edit');
			Route::post('ventas/cotizaciones/update', 'CotizacionController@update_cotizacion');
			Route::resource('ventas/cotizaciones','CotizacionController', ['except' => [
					'index'
			]]);
			Route::post('ventas/nueva_cotizacion', 'CotizacionController@nueva_cotizacion');
			Route::resource('ventas/lista_precios', 'VentasController@lista_precios', ['except' => [
					'index',
			]]);


			//RUTAS DE pagos
			// INDEX DE PAGOS
			Route::get('pagos','PagosController@index')->name('pagos.index');
			Route::get('pagos/getdata','PagosController@getdata');
			Route::post('check_monto', 'PagosController@check_monto');
			Route::get('pagos/estado_cuenta_pdf/{id}', 'PagosController@estado_cuenta_pdf');
			Route::get('pagos/cuentas_por_cobrar', 'PagosController@cuentas_por_cobrar_index');
			Route::post('pagos/cuentas_por_cobrar/resumen','PagosController@cuentas_por_cobrar');
			Route::get('pagos/nuevo_pago_index',[
				'as' => 'pagos.nuevo_pago_index',
				'uses' => 'PagosController@nuevo_pago_index'
				
			]);
			Route::post('pagos/nuevo_pago','PagosController@nuevo_pago');
			Route::post('pagos/nuevo_pago/{id}', [
				'as' => 'pagos.nuevo_pago',
				'uses' => 'PagosController@nuevo_pago_resumen'
			]);
			Route::post('pagos/guardar_pago','PagosController@guardar_pago');
			Route::resource('pagos', 'PagosController', ['except' => [
					'index',
			]]);


			//Estadisticas
			Route::get('stats/','StatsController@index');
			Route::post('stats/resumen','StatsController@estadisticas');


			// RUTAS DE PROVEEDORES
			Route::get('proveedores','ProveedorController@index')->name('proveedores.index');
			Route::resource('proveedores', 'ProveedorController', ['except' => [
					'index',
			]]);
		});