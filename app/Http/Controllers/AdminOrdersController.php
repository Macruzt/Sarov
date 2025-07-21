<?php

namespace App\Http\Controllers;

use Session;
use Request;
use DB;
use PDF;
use App\Models\Customers;
use CRUDBooster;

class AdminOrdersController extends \crocodicstudio\crudbooster\controllers\CBController
{

	public function cbInit()
	{

		# START CONFIGURATION DO NOT REMOVE THIS LINE
		$this->title_field = "tipo_persona_customer";
		$this->limit = "20";
		$this->orderby = "id,asc";
		$this->global_privilege = false;
		$this->button_table_action = true;
		$this->button_bulk_action = true;
		$this->button_action_style = "button_icon";
		$this->button_add = true;
		$this->button_edit = true;
		$this->button_delete = true;
		$this->button_detail = true;
		$this->button_show = true;
		$this->button_filter = true;
		$this->button_import = false;
		$this->button_export = false;
		$this->table = "orders";
		# END CONFIGURATION DO NOT REMOVE THIS LINE

		# START COLUMNS DO NOT REMOVE THIS LINE
		$this->col = [];
		$this->col[] = ["label" => "OS Sarov", "name" => "os_sarov"];
		$this->col[] = ["label" => "Responsable", "name" => "id_usuario", "join" => "cms_users,name"];
		$this->col[] = ["label" => "Nombre del Cliente", "name" => "id_customer", "join" => "customers,name"];
		$this->col[] = ["label" => "NIT/CC", "name" => "cedula_customer"];
		$this->col[] = ["label" => "Tipo de Equipo", "name" => "id_equipo", "join" => "equipos,tipo_equipo"];
		$this->col[] = ["label" => "Placa", "name" => "id_equipo", "join" => "equipos,placa"];
		# END COLUMNS DO NOT REMOVE THIS LINE

		# START FORM DO NOT REMOVE THIS LINE
		$this->form = [];
		$this->form[] = ['label' => 'Nombre/Cliente', 'name' => 'id_customer', 'type' => 'select', 'validation' => 'required', 'datatable' => 'customers,name', 'width' => 'col-sm-8'];
		$this->form[] = ['label' => 'CC/NIT', 'name' => 'cedula_customer', 'type' => 'text', 'validation' => 'required|min:1|max:13', 'width' => 'col-sm-8'];
		$this->form[] = ['label' => 'Direccion', 'name' => 'direccion_customer', 'type' => 'textarea', 'validation' => 'required', 'width' => 'col-sm-8'];
		$this->form[] = ['label' => 'Telefono', 'name' => 'telefono_customer', 'type' => 'text', 'validation' => 'required|min:1|max:10', 'width' => 'col-sm-8'];
		$this->form[] = ['label' => 'Correo', 'name' => 'correo_customer', 'type' => 'text', 'validation' => 'required', 'width' => 'col-sm-8'];
		$this->form[] = ['label' => 'Tipo Persona', 'name' => 'tipo_persona_customer', 'type' => 'select', 'validation' => 'required', 'width' => 'col-sm-8', 'dataenum' => 'Persona Natural; Persona Juridica'];
		$this->form[] = ['label' => 'Responsable Cliente', 'name' => 'responsable_cliente', 'type' => 'text', 'validation' => 'required', 'width' => 'col-sm-8'];
		$this->form[] = ['label' => 'Responsable Sarov', 'name' => 'responsable_sarov', 'type' => 'text', 'validation' => 'required', 'width' => 'col-sm-8'];
		$this->form[] = ['label' => 'Orden Cliente', 'name' => 'orden_cliente', 'type' => 'select2', 'validation' => 'required', 'width' => 'col-sm-8', 'dataenum' => 'OP;WO;OQ;OC;OT;OS;CO;OTROS'];
		$this->form[] = ['label' => '# Orden de Cliente', 'name' => 'numero_orden_cliente', 'type' => 'text', 'validation' => 'required', 'width' => 'col-sm-8', 'placeholder' => '0000-00'];
		$this->form[] = ['label' => 'Linea', 'name' => 'linea', 'type' => 'select', 'validation' => 'required', 'width' => 'col-sm-8', 'dataenum' => 'Wireline;Slickline;Pps;Fractura;Trs;MPD;Drilling;Flush By;Mchine shop;Als;R&M;Mantenimiento; Facilidades;Operaciones;Vías;Ingenieria;Mecánica; Mixer;Autobombas;Planta;Camión;Automóvil;Otros'];
		$this->form[] = ['label' => 'Recepcion de Vehiculos/Equipo', 'name' => 'id_equipo', 'type' => 'select', 'validation' => 'required', 'datatable' => 'equipos,placa', 'datatable_format' => "tipo_equipo,' - ',placa", 'width' => 'col-sm-8'];
		$this->form[] = ['label' => 'Kms Hrs', 'name' => 'kms_hrs', 'type' => 'text', 'width' => 'col-sm-10'];
		$this->form[] = ['label' => 'Nombre', 'name' => 'nombre_resp_recep', 'type' => 'text', 'width' => 'col-sm-10'];
		$this->form[] = ['label' => 'ID/CC', 'name' => 'id_resp_recep', 'type' => 'text', 'width' => 'col-sm-10'];
		$this->form[] = ['label' => 'Nombre Cliente', 'name' => 'nombre_resp_cliente', 'type' => 'text', 'width' => 'col-sm-10'];
		$this->form[] = ['label' => 'ID/CC Cliente', 'name' => 'id_resp_cliente', 'type' => 'text', 'width' => 'col-sm-10'];
		$this->form[] = ['label' => 'Observaciones', 'name' => 'observaciones', 'type' => 'textarea', 'validation' => 'required', 'width' => 'col-sm-8'];
		# END FORM DO NOT REMOVE THIS LINE

		# OLD START FORM
		//$this->form = [];
		//$this->form[] = ['label'=>'Nombre/Cliente','name'=>'nombre_customer','type'=>'text','validation'=>'required','width'=>'col-sm-8'];
		//$this->form[] = ['label'=>'CC/NIT','name'=>'cedula_customer','type'=>'text','validation'=>'required|min:1|max:13','width'=>'col-sm-8'];
		//$this->form[] = ['label'=>'Direccion','name'=>'direccion_customer','type'=>'textarea','validation'=>'required','width'=>'col-sm-8'];
		//$this->form[] = ['label'=>'Telefono','name'=>'telefono_customer','type'=>'text','validation'=>'required|min:1|max:10','width'=>'col-sm-8'];
		//$this->form[] = ['label'=>'Correo','name'=>'correo_customer','type'=>'text','validation'=>'required','width'=>'col-sm-8'];
		//$this->form[] = ['label'=>'Tipo Persona','name'=>'tipo_persona_customer','type'=>'select','validation'=>'required','width'=>'col-sm-8','dataenum'=>'Persona Natural; Persona Juridica'];
		//$this->form[] = ['label'=>'Responsable Cliente','name'=>'responsable_cliente','type'=>'text','validation'=>'required','width'=>'col-sm-8'];
		//$this->form[] = ['label'=>'Responsable Sarov','name'=>'responsable_sarov','type'=>'text','validation'=>'required','width'=>'col-sm-8'];
		//$this->form[] = ['label'=>'Orden Cliente','name'=>'orden_cliente','type'=>'select2','validation'=>'required','width'=>'col-sm-8','dataenum'=>'OP;WO;OQ; OC;OT; OS;CO;OTROS'];
		//$this->form[] = ['label'=>'Linea','name'=>'linea','type'=>'select','validation'=>'required','width'=>'col-sm-8','dataenum'=>'Wirelina; fkust by; ALS; testing; mixer; autobomba; planta'];
		//$this->form[] = ['label'=>'Observaciones','name'=>'observaciones','type'=>'textarea','validation'=>'required','width'=>'col-sm-8'];
		# OLD END FORM

		/* 
	        | ---------------------------------------------------------------------- 
	        | Sub Module
	        | ----------------------------------------------------------------------     
			| @label          = Label of action 
			| @path           = Path of sub module
			| @foreign_key 	  = foreign key of sub table/module
			| @button_color   = Bootstrap Class (primary,success,warning,danger)
			| @button_icon    = Font Awesome Class  
			| @parent_columns = Sparate with comma, e.g : name,created_at
	        | 
	        */
		$this->sub_module = array();
		$this->sub_module[] = ['label' => 'Doc', 'path' => 'documents', 'foreign_key' => 'order_id', 'button_color' => 'warning', 'button_icon' => 'fa fa-file-o'];
		$this->sub_module[] = ['label' => 'Informe', 'path' => 'informes', 'foreign_key' => 'order_id', 'button_color' => 'warning', 'button_icon' => 'fa fa-file-text-o'];
		$this->sub_module[] = ['label' => 'Acta', 'path' => 'actas', 'foreign_key' => 'orderid', 'button_color' => 'warning', 'button_icon' => 'fa fa-file-text-o'];

		/* 
	        | ---------------------------------------------------------------------- 
	        | Add More Action Button / Menu
	        | ----------------------------------------------------------------------     
	        | @label       = Label of action 
	        | @url         = Target URL, you can use field alias. e.g : [id], [name], [title], etc
	        | @icon        = Font awesome class icon. e.g : fa fa-bars
	        | @color 	   = Default is primary. (primary, warning, succecss, info)     
	        | @showIf 	   = If condition when action show. Use field alias. e.g : [id] == 1
	        | 
	        */
		$this->addaction = array();
		$this->addaction[] = [
			'label' => 'Firmar recepcion',
			'url' => 'javascript:openPDFModal([id])',
			'icon' => 'fa fa-pencil',
			'color' => 'info'
		];


		/* 
	        | ---------------------------------------------------------------------- 
	        | Add More Button Selected
	        | ----------------------------------------------------------------------     
	        | @label       = Label of action 
	        | @icon 	   = Icon from fontawesome
	        | @name 	   = Name of button 
	        | Then about the action, you should code at actionButtonSelected method 
	        | 
	        */
		$this->button_selected = array();


		/* 
	        | ---------------------------------------------------------------------- 
	        | Add alert message to this module at overheader
	        | ----------------------------------------------------------------------     
	        | @message = Text of message 
	        | @type    = warning,success,danger,info        
	        | 
	        */
		$this->alert        = array();



		/* 
	        | ---------------------------------------------------------------------- 
	        | Add more button to header button 
	        | ----------------------------------------------------------------------     
	        | @label = Name of button 
	        | @url   = URL Target
	        | @icon  = Icon from Awesome.
	        | 
	        */
		$this->index_button = array();



		/* 
	        | ---------------------------------------------------------------------- 
	        | Customize Table Row Color
	        | ----------------------------------------------------------------------     
	        | @condition = If condition. You may use field alias. E.g : [id] == 1
	        | @color = Default is none. You can use bootstrap success,info,warning,danger,primary.        
	        | 
	        */
		$this->table_row_color = array();


		/*
	        | ---------------------------------------------------------------------- 
	        | You may use this bellow array to add statistic at dashboard 
	        | ---------------------------------------------------------------------- 
	        | @label, @count, @icon, @color 
	        |
	        */
		$this->index_statistic = array();



		/*
	        | ---------------------------------------------------------------------- 
	        | Add javascript at body 
	        | ---------------------------------------------------------------------- 
	        | javascript code in the variable 
	        | $this->script_js = "function() { ... }";
	        |
	        */
		$this->script_js = "
$(document).ready(function(){
    $('#id_customer').on('change',function(){
        var customerId = $(this).val();
        if(customerId) {
            $.ajax({
                url: '" . url('admin/orders/get-info/') . "/' + customerId,
                type: 'get',
                success: function(msg) {
                    if(msg && msg[0]) {
                        $('#cedula_customer').val(msg[0].nit || '');
                        $('#direccion_customer').val(msg[0].address || '');
                        $('#telefono_customer').val(msg[0].phone || '');
                        $('#correo_customer').val(msg[0].email || '');
                        console.log('Datos del cliente cargados');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        }
    });
    
    setTimeout(function() {
    }, 3000);
});
";



		/*
	        | ---------------------------------------------------------------------- 
	        | Include HTML Code before index table 
	        | ---------------------------------------------------------------------- 
	        | html code to display it before index table
	        | $this->pre_index_html = "<p>test</p>";
	        |
	        */
		$this->pre_index_html = null;



		/*
	        | ---------------------------------------------------------------------- 
	        | Include HTML Code after index table 
	        | ---------------------------------------------------------------------- 
	        | html code to display it after index table
	        | $this->post_index_html = "<p>test</p>";
	        |
	        */
		$this->post_index_html = null;



		/*
	        | ---------------------------------------------------------------------- 
	        | Include Javascript File 
	        | ---------------------------------------------------------------------- 
	        | URL of your javascript each array 
	        | $this->load_js[] = asset("myfile.js");
	        |
	        */
		$this->load_js = array();
		$this->load_js[] = "https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js";
		$this->load_js[] = "https://unpkg.com/pdf-lib@1.17.1/dist/pdf-lib.min.js";
		$this->load_js[] = asset("js/signature_modal.js");



		/*
	        | ---------------------------------------------------------------------- 
	        | Add css style at body 
	        | ---------------------------------------------------------------------- 
	        | css code in the variable 
	        | $this->style_css = ".style{....}";
	        |
	        */
		$this->style_css = "
#pdfSignModal .modal-lg { max-width: 90% !important; }
#pdfSignModal .modal-content { max-height: 95vh; }
#signature-pad { touch-action: none; background: white; }
.modal-backdrop.in { opacity: 0.5 !important; }
";



		/*
	        | ---------------------------------------------------------------------- 
	        | Include css File 
	        | ---------------------------------------------------------------------- 
	        | URL of your css each array 
	        | $this->load_css[] = asset("myfile.css");
	        |
	        */
		$this->load_css = array();
	}

	public function equiposPDF($id)
	{
		$data = DB::table('equipos')
			->join('orders', 'orders.id_equipo', '=', 'equipos.id')
			->join('customers', 'customers.id', '=', 'orders.id_customer')
			->join('cms_users', 'cms_users.id', '=', 'orders.id_usuario')
			->select('orders.*', 'equipos.placa', 'equipos.tipo_equipo', 'equipos.descripcion', 'equipos.referencia', 'equipos.marca', 'equipos.modelo', 'equipos.linea', 'equipos.combustible', 'equipos.potencia', 'equipos.interno_equipo', 'equipos.numero_serie', 'orders.kms_hrs', 'orders.nombre_resp_recep', 'orders.id_resp_recep', 'orders.nombre_resp_cliente', 'orders.id_resp_cliente', 'customers.name as customer')->where('orders.id', $id)->get();
		$pdf = PDF::loadView('equipos', compact('data'));
		return $pdf->download('recepcion de equipos.pdf');
	}
   
	public function getInfo($id)
	{
		$fill = \DB::table('customers')
			->select('customers.*')
			->where('customers.id', $id)->get();
		return \Response::json($fill);
	}

	//           

	/*
	    | ---------------------------------------------------------------------- 
	    | Hook for button selected
	    | ---------------------------------------------------------------------- 
	    | @id_selected = the id selected
	    | @button_name = the name of button
	    |
	    */
	public function actionButtonSelected($id_selected, $button_name)
	{}


	/*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate query of index result 
	    | ---------------------------------------------------------------------- 
	    | @query = current sql query 
	    |
	    */
	public function hook_query_index(&$query)
	{
		if (CRUDBooster::isSuperadmin()) {
		} else {
			if (CRUDBooster::myPrivilegeId() == '13') {
				$user_id = CRUDBooster::myId();
				$query->join('cms_users as cms_users1', 'cms_users1.id', '=', 'orders.id_usuario')
					->select('orders.*')
					->where('orders.id_usuario', $user_id);
			} else {
				if (CRUDBooster::myPrivilegeId() == '14') {
					$user_id = CRUDBooster::myId();
					$query->join('cms_users as cms_users1', 'cms_users1.id', '=', 'orders.id_usuario')
						->select('orders.*')
						->where('orders.id_usuario', $user_id);
				} else {
					if (CRUDBooster::myPrivilegeId() == '15') {
						$user_id = CRUDBooster::myId();
						$query->join('cms_users as cms_users1', 'cms_users1.id', '=', 'orders.id_usuario')
							->select('orders.*')
							->where('orders.id_usuario', $user_id);
					} else {
						if (CRUDBooster::myPrivilegeId() == '16') {
							$user_id = CRUDBooster::myId();
							$query->join('cms_users as cms_users1', 'cms_users1.id', '=', 'orders.id_usuario')
								->select('orders.*')
								->where('orders.id_usuario', $user_id);
						} else {
							if (CRUDBooster::myPrivilegeId() == '17') {
								$user_id = CRUDBooster::myId();
								$query->join('cms_users as cms_users1', 'cms_users1.id', '=', 'orders.id_usuario')
									->select('orders.*')
									->where('orders.id_usuario', $user_id);
							} else {
								if (CRUDBooster::myPrivilegeId() == '18') {
									$user_id = CRUDBooster::myId();
									$query->join('cms_users as cms_users1', 'cms_users1.id', '=', 'orders.id_usuario')
										->select('orders.*')
										->where('orders.id_usuario', $user_id);
								} else {
									if (CRUDBooster::myPrivilegeId() == '19') {
										$user_id = CRUDBooster::myId();
										$query->join('cms_users as cms_users1', 'cms_users1.id', '=', 'orders.id_usuario')
											->select('orders.*')
											->where('orders.id_usuario', $user_id);
									}
								}
							}
						}
					}
				}
			}
		}
	}



	/*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate row of index table html 
	    | ---------------------------------------------------------------------- 
	    |
	    */
	public function hook_row_index($column_index, &$column_value)
	{}

	/*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate data input before add data is execute
	    | ---------------------------------------------------------------------- 
	    | @arr
	    |
	    */
	public function hook_before_add(&$postdata)
	{
		$postdata['id_usuario'] = CRUDBooster::myId();
	}

	/* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after add public static function called 
	    | ---------------------------------------------------------------------- 
	    | @id = last insert id
	    | 
	    */
	public function hook_after_add($id)
	{}

	/* 
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate data input before update data is execute
	    | ---------------------------------------------------------------------- 
	    | @postdata = input post data 
	    | @id       = current id 
	    | 
	    */
	public function hook_before_edit(&$postdata, $id)
	{}

	/* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after edit public static function called
	    | ----------------------------------------------------------------------     
	    | @id       = current id 
	    | 
	    */
	public function hook_after_edit($id)
	{}

	/* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command before delete public static function called
	    | ----------------------------------------------------------------------     
	    | @id       = current id 
	    | 
	    */
	public function hook_before_delete($id)
	{
		$order = DB::table('orders')->where('id', $id)->first();
		DB::table('informes')->where('order_id', $id)->delete();
		DB::table('actas')->where('order_id', $id)->delete();
		parent::hook_before_delete($id);
	}

	/* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after delete public static function called
	    | ----------------------------------------------------------------------     
	    | @id       = current id 
	    | 
	    */
	public function hook_after_delete($id)
	{}
	// public function viewPDF($id)
	// {
	// 	try {
	// 		$data = DB::table('equipos')
	// 			->join('orders', 'orders.id_equipo', '=', 'equipos.id')
	// 			->join('customers', 'customers.id', '=', 'orders.id_customer')
	// 			->join('cms_users', 'cms_users.id', '=', 'orders.id_usuario')
	// 			->select('orders.*', 'equipos.placa', 'equipos.tipo_equipo', 'equipos.descripcion', 'equipos.referencia', 'equipos.marca', 'equipos.modelo', 'equipos.linea', 'equipos.combustible', 'equipos.potencia', 'equipos.interno_equipo', 'equipos.numero_serie', 'orders.kms_hrs', 'orders.nombre_resp_recep', 'orders.id_resp_recep', 'orders.nombre_resp_cliente', 'orders.id_resp_cliente', 'customers.name as customer', 'cms_users.name as responsable_sarov_name')
	// 			->where('orders.id', $id)
	// 			->first();

	// 		if (!$data) {
	// 			return abort(404, 'Orden no encontrada');
	// 		}
	// 		$hasContent = !empty($data->os_sarov) || !empty($data->customer) || !empty($data->placa) || !empty($data->tipo_equipo);

	// 		if (!$hasContent) {
	// 			\Log::warning("Orden {$id} no tiene datos suficientes para PDF - omitiendo generación");
	// 			return response()->json([
	// 				'error' => 'Esta orden no tiene suficiente información para generar el PDF',
	// 				'orden_id' => $id
	// 			], 400);
	// 		}

	// 		$pdf = PDF::loadView('equipos', compact('data'));
	// 		return $pdf->stream('recepcion-equipos-' . $id . '.pdf');
	// 	} catch (\Exception $e) {
	// 		\Log::error('Error al mostrar PDF: ' . $e->getMessage());
	// 		return abort(500, 'Error al cargar el documento');
	// 	}
	// }

	public function viewPDF($id)
	{
		try {
			// Configuración para evitar problemas
			ini_set('memory_limit', '256M');
			ini_set('max_execution_time', 60);

			$data = DB::table('equipos')
				->join('orders', 'orders.id_equipo', '=', 'equipos.id')
				->join('customers', 'customers.id', '=', 'orders.id_customer')
				->join('cms_users', 'cms_users.id', '=', 'orders.id_usuario')
				->select('orders.*', 'equipos.placa', 'equipos.tipo_equipo', 'equipos.descripcion', 'equipos.referencia', 'equipos.marca', 'equipos.modelo', 'equipos.linea', 'equipos.combustible', 'equipos.potencia', 'equipos.interno_equipo', 'equipos.numero_serie', 'orders.kms_hrs', 'orders.nombre_resp_recep', 'orders.id_resp_recep', 'orders.nombre_resp_cliente', 'orders.id_resp_cliente', 'customers.name as customer', 'cms_users.name as responsable_sarov_name')
				->where('orders.id', $id)
				->first();

			if (!$data) {
				return abort(404, 'Orden no encontrada');
			}
			$hasContent = !empty($data->os_sarov) || !empty($data->customer) || !empty($data->placa) || !empty($data->tipo_equipo);

			if (!$hasContent) {
				\Log::warning("Orden {$id} sin datos suficientes - omitiendo PDF en blanco");
				return response()->view('pdf-error', [
					'message' => 'Esta orden no tiene información suficiente para generar el PDF',
					'orden_id' => $id
				], 400);
			}
			$pdf = PDF::setOptions([
				'isRemoteEnabled' => true,
				'chroot' => public_path(),
				'isHtml5ParserEnabled' => true,
				'isPhpEnabled' => false,
				'defaultFont' => 'DejaVu Sans',
				'dpi' => 96,
				'debugPng' => false,
				'debugKeepTemp' => false,
				'debugCss' => false,
				'debugLayout' => false,
				'isFontSubsettingEnabled' => false,
			]);

			$pdf->loadView('equipos', compact('data'));
			$pdf->setPaper('A4', 'portrait');
			$output = $pdf->output();

			if (strlen($output) < 3000) {
				\Log::error("PDF muy pequeño generado para orden: {$id} - tamaño: " . strlen($output));
				return response()->view('pdf-error', [
					'message' => 'Error al generar PDF - documento muy pequeño',
					'orden_id' => $id
				], 500);
			}

			return $pdf->stream('recepcion-equipos-' . $id . '.pdf');
		} catch (\Exception $e) {
			\Log::error('Error al mostrar PDF: ' . $e->getMessage());
			return response()->view('pdf-error', [
				'message' => 'Error al generar PDF: ' . $e->getMessage(),
				'orden_id' => $id
			], 500);
		}
	}

	public function saveSignature($id)
	{
		try {
			$input = Request::all();

			$validator = \Validator::make($input, [
				'signature' => 'required|string',
				'signer_name' => 'required|string|min:3|max:100',
				'signer_position' => 'nullable|string|max:100',
				'signer_company' => 'nullable|string|max:100'
			]);

			if ($validator->fails()) {
				return response()->json([
					'success' => false,
					'message' => 'Datos inválidos: ' . $validator->errors()->first()
				], 400);
			}

			$order = DB::table('orders')->where('id', $id)->first();
			if (!$order) {
				return response()->json([
					'success' => false,
					'message' => 'Orden no encontrada'
				], 404);
			}

			$updateData = [
				'signature_data' => $input['signature'],
				'signer_name' => trim($input['signer_name']),
				'signer_position' => trim($input['signer_position'] ?? ''),
				'signer_company' => trim($input['signer_company'] ?? ''),
				'signed_at' => now(),
				'signature_ip' => Request::ip(),
				'updated_at' => now()
			];

			$updated = DB::table('orders')->where('id', $id)->update($updateData);

			if (!$updated) {
				throw new \Exception('No se pudo guardar la firma');
			}

			return response()->json([
				'success' => true,
				'message' => 'Firma guardada correctamente',
				'data' => [
					'order_id' => $id,
					'signer_name' => $updateData['signer_name'],
					'signed_at' => $updateData['signed_at']->format('Y-m-d H:i:s')
				]
			]);
		} catch (\Exception $e) {
			\Log::error('Error al guardar firma: ' . $e->getMessage());

			return response()->json([
				'success' => false,
				'message' => 'Error al guardar la firma: ' . $e->getMessage()
			], 500);
		}
	}

	public function getSignerInfo($id)
	{
		try {
			$order = DB::table('orders')
				->join('cms_users', 'orders.id_usuario', '=', 'cms_users.id')
				->where('orders.id', $id)
				->select(
					'cms_users.name as signer_name',
					DB::raw("'Responsable' as signer_position"),
					DB::raw("'SAROV' as signer_company")
				)
				->first();

			if (!$order) {
				return response()->json([
					'success' => false,
					'message' => 'Orden no encontrada'
				]);
			}

			return response()->json([
				'success' => true,
				'data' => $order
			]);
		} catch (\Exception $e) {
			return response()->json([
				'success' => false,
				'message' => 'Error: ' . $e->getMessage()
			]);
		}
	}
	public function getOrderPDF($id)
	{
		try {
			$data = DB::table('equipos')
				->join('orders', 'orders.id_equipo', '=', 'equipos.id')
				->join('customers', 'customers.id', '=', 'orders.id_customer')
				->join('cms_users', 'cms_users.id', '=', 'orders.id_usuario')
				->select('orders.*', 'equipos.placa', 'equipos.tipo_equipo', 'equipos.descripcion', 'equipos.referencia', 'equipos.marca', 'equipos.modelo', 'equipos.linea', 'equipos.combustible', 'equipos.potencia', 'equipos.interno_equipo', 'equipos.numero_serie', 'orders.kms_hrs', 'orders.nombre_resp_recep', 'orders.id_resp_recep', 'orders.nombre_resp_cliente', 'orders.id_resp_cliente', 'customers.name as customer', 'cms_users.name as responsable_sarov_name')
				->where('orders.id', $id)
				->first();

			if (!$data) {
				return response()->json(['error' => 'Orden no encontrada'], 404);
			}

			$pdf = PDF::loadView('equipos', compact('data'));
			$pdf->setPaper('A4', 'portrait');

			return response($pdf->output(), 200, [
				'Content-Type' => 'application/pdf',
				'Content-Disposition' => 'inline; filename="order-' . $id . '.pdf"'
			]);
		} catch (\Exception $e) {
			\Log::error('Error al obtener PDF de orden: ' . $e->getMessage());
			return response()->json(['error' => 'Error al cargar el PDF'], 500);
		}
	}

	public function saveSignedOrderPDF($id)
	{
		try {
			$input = Request::all();

			if (!isset($input['pdf_data']) || empty($input['pdf_data'])) {
				return response()->json([
					'success' => false,
					'message' => 'No se recibió el contenido del PDF firmado'
				], 400);
			}

			$order = DB::table('orders')->where('id', $id)->first();
			if (!$order) {
				return response()->json([
					'success' => false,
					'message' => 'Orden no encontrada'
				], 404);
			}

			$pdfBase64 = $input['pdf_data'];

			$pdfBinaryData = base64_decode($pdfBase64, true);
			if (!$pdfBinaryData) {
				return response()->json([
					'success' => false,
					'message' => 'Datos del PDF inválidos - base64 corrupto'
				], 400);
			}

			if (substr($pdfBinaryData, 0, 4) !== '%PDF') {
				return response()->json([
					'success' => false,
					'message' => 'El archivo no es un PDF válido'
				], 400);
			}

			$fileName = 'reception_signed_' . $id . '_' . date('Y-m-d_H-i-s') . '.pdf';
			$fileSize = strlen($pdfBinaryData);

			$existingRecord = DB::table('service_order_documents')->where('order_id', $id)->first();

			if ($existingRecord) {
				$updated = DB::table('service_order_documents')
					->where('order_id', $id)
					->update([
						'reception_signed' => $pdfBase64,
						'reception_filename' => $fileName,
						'reception_size' => $fileSize,
						'reception_signed_at' => now(),
						'updated_at' => now()
					]);
			} else {
				// Crear nuevo registro
				$updated = DB::table('service_order_documents')->insert([
					'order_id' => $id,
					'reception_signed' => $pdfBase64,
					'reception_filename' => $fileName,
					'reception_size' => $fileSize,
					'reception_signed_at' => now(),
					'delivery_signed' => null, 
					'delivery_filename' => null,
					'delivery_size' => null,
					'delivery_signed_at' => null,
					'created_at' => now(),
					'updated_at' => now()
				]);
			}

			if (!$updated) {
				throw new \Exception('No se pudo guardar el documento en la base de datos');
			}

			\Log::info("Documento de recepción firmado guardado como base64 para orden {$id}", [
				'filename' => $fileName,
				'size_bytes' => $fileSize,
				'base64_length' => strlen($pdfBase64),
				'timestamp' => now()->format('Y-m-d H:i:s')
			]);

			return response()->json([
				'success' => true,
				'message' => 'Documento de recepción firmado guardado correctamente en la base de datos',
				'data' => [
					'order_id' => $id,
					'filename' => $fileName,
					'size' => $fileSize,
					'document_type' => 'reception',
					'storage_type' => 'base64_database',
					'saved_at' => now()->format('Y-m-d H:i:s'),
					'next_step' => 'El acta de entrega se firmará más tarde'
				]
			]);
		} catch (\Exception $e) {
			\Log::error('Error al guardar PDF de recepción firmado como base64: ' . $e->getMessage());

			return response()->json([
				'success' => false,
				'message' => 'Error al guardar documento: ' . $e->getMessage()
			], 500);
		}
	}

	public function downloadDocumentPDF($order_id, $type)
	{
		try {
			if (!in_array($type, ['reception', 'delivery'])) {
				return response()->json(['error' => 'Tipo de documento inválido'], 400);
			}
			$document = DB::table('service_order_documents')
				->where('order_id', $order_id)
				->first();

			if (!$document) {
				return response()->json(['error' => 'Documento no encontrado'], 404);
			}
			if ($type === 'reception') {
				$pdfBase64 = $document->reception_signed;
				$filename = $document->reception_filename ?: 'reception_signed_' . $order_id . '.pdf';
			} else {
				$pdfBase64 = $document->delivery_signed;
				$filename = $document->delivery_filename ?: 'delivery_signed_' . $order_id . '.pdf';
			}

			if (!$pdfBase64) {
				return response()->json(['error' => 'Documento no disponible'], 404);
			}

			$pdfData = base64_decode($pdfBase64);
			if (!$pdfData) {
				\Log::error("Error al decodificar base64 para documento {$type} de orden {$order_id}");
				return response()->json(['error' => 'Error al decodificar documento'], 500);
			}

			if (substr($pdfData, 0, 4) !== '%PDF') {
				\Log::error("Datos inválidos para documento {$type} de orden {$order_id} - no es PDF");
				return response()->json(['error' => 'Documento corrupto'], 500);
			}

			return response($pdfData, 200, [
				'Content-Type' => 'application/pdf',
				'Content-Disposition' => 'inline; filename="' . $filename . '"',
				'Content-Length' => strlen($pdfData),
				'Cache-Control' => 'no-cache, no-store, must-revalidate',
				'Pragma' => 'no-cache',
				'Expires' => '0'
			]);
		} catch (\Exception $e) {
			\Log::error('Error al servir PDF desde base64: ' . $e->getMessage());
			return response()->json(['error' => 'Error interno del servidor'], 500);
		}
	}
}
