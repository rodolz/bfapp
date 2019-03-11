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
			Route::get('users','UserController@index')->name('users.index');
			Route::resource('users','UserController', ['except' => [
				'index',
			]]);
			Route::post('users/perfil/update_avatar','UserController@update_avatar');

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
			Route::get('purchase_orders/po_pdf/{id}', 'PurchaseOrderController@po_pdf');
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

			// RUTAS SHIPTO
			Route::get('shipto','ShiptoController@index')->name('shipto.index');

			Route::resource('shipto','ShiptoController', ['except' => [
					'index',
			]]);			

			// RUTAS DE ORDENES
			Route::get('ordenes','OrdenesController@index')->name('ordenes.index');
			Route::get('ordenes/orden_pdf/{id}', 'OrdenesController@orden_pdf');
			Route::resource('ordenes','OrdenesController', ['except' => [
				'index', 'update'
			]]);
			Route::post('nueva_orden', 'OrdenesController@nueva_orden');
			Route::post('ordenes/update_orden', 'OrdenesController@update');
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

			// RUTAS DE NOTA DE CREDITOS
			Route::get('nota_creditos/nota_credito_pdf/{id}', 'NotaCreditosController@nota_credito_pdf');
			Route::get('nota_creditos','NotaCreditosController@index')->name('nota_creditos.index');
			Route::resource('nota_creditos','NotaCreditosController', ['except' => [
					'index',
			]]);
			Route::post('nota_creditos/update/num_fiscal','NotaCreditosController@actualizar_num_fiscal');

			// RUTAS DE FACTURAS
			Route::get('facturas/factura_pdf/{id}', 'FacturasController@factura_pdf');
			Route::get('facturas','FacturasController@index')->name('facturas.index');
			Route::resource('facturas','FacturasController', ['except' => [
					'index',
			]]);

			Route::post('nueva_factura', 'FacturasController@nueva_factura');
			Route::get('facturas/create-by-id/{idFactura}', 'FacturasController@create_by_id');
			Route::post('facturas/update/num_fiscal','FacturasController@actualizar_num_fiscal');


			// RUTAS DE VENTAS
			Route::get('cotizaciones/cotizacion_pdf/{id}', 'CotizacionController@cotizacion_pdf');
			Route::get('ventas/lista_precios','VentasController@lista_precios')->name('ventas.index');
			Route::post('ventas/lista_precios_pdf', 'VentasController@lista_precios_pdf')->name('ventas.lista_precios_pdf');
			Route::get('ventas/cotizaciones','CotizacionController@index')->name('ventas.cotizaciones.index');
			// Route::get('ventas/cotizaciones/{id}','CotizacionController@edit')->name('ventas.cotizaciones.edit');
			Route::post('ventas/cotizaciones/update', 'CotizacionController@update_cotizacion');
			Route::resource('ventas/cotizaciones','CotizacionController', ['except' => [
					'index'
			]]);
			Route::post('ventas/nueva_cotizacion', 'CotizacionController@nueva_cotizacion');

			//RUTAS DE pagos
			// INDEX DE PAGOS
			Route::get('pagos','PagosController@index')->name('pagos.index');
			Route::get('pagos/getdata','PagosController@getdata');
			Route::post('check_monto', 'PagosController@check_monto');
			Route::get('pagos/estado_cuenta_pdf/{id}', 'PagosController@estado_cuenta_pdf');
			Route::get('pagos/estado_cuenta_pdf_multiple/{id}', 'PagosController@estado_cuenta_pdf_multiple');
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