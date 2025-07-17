<?php namespace App\Http\Controllers;

	use Session;
	use Request;
	use DB;
	use PDF;
	use CRUDBooster;

	class AdminActasController extends \crocodicstudio\crudbooster\controllers\CBController {

	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "id";
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
			$this->table = "actas";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"Nombre Entrega","name"=>"nombre_e"];
			$this->col[] = ["label"=>"Cedula Entrega","name"=>"cedula_e"];
			$this->col[] = ["label"=>"Nombre Recibe","name"=>"nombre_r"];
			$this->col[] = ["label"=>"Cedula Recibe","name"=>"cedula_r"];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			$this->form[] = ['label'=>'Order Id','name'=>'orderid','type'=>'text','validation'=>'required','datatable'=>'orders,id','readonly'=>true,'width'=>'col-sm-8'];
			$this->form[] = ['label'=>'Fecha Salida','name'=>'fecha_salida','type'=>'date','validation'=>'required','width'=>'col-sm-8'];
			$this->form[] = ['label'=>'Firma Entrega','name'=>'firma_e','type'=>'text','width'=>'col-sm-8'];
			$this->form[] = ['label'=>'Nombre Entrega','name'=>'nombre_e','type'=>'text','validation'=>'required','width'=>'col-sm-8'];
			$this->form[] = ['label'=>'Cedula Entrega','name'=>'cedula_e','type'=>'text','validation'=>'required','width'=>'col-sm-8'];
			$this->form[] = ['label'=>'Firma Recibe','name'=>'firma_r','type'=>'text','width'=>'col-sm-8'];
			$this->form[] = ['label'=>'Nombre Recibe','name'=>'nombre_r','type'=>'text','width'=>'col-sm-8'];
			$this->form[] = ['label'=>'Cedula Recibe','name'=>'cedula_r','type'=>'text','width'=>'col-sm-8'];
			$this->form[] = ['label'=>'Observaciones','name'=>'observaciones','type'=>'textarea','validation'=>'required','width'=>'col-sm-8'];
			# END FORM DO NOT REMOVE THIS LINE

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
	        
	        // Botón para firmar acta de entrega (reemplaza el original)
	        $this->addaction[] = [
				'label'=>'Firmar acta de entrega',
				'url'=>'javascript:openActaPDFModal([id])',
				'icon'=>'fa fa-signature',
				'color'=>'info'
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
	        $this->script_js = NULL;

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
	        // NUEVO: Agregar librerías necesarias para la firma digital
	        $this->load_js[] = "https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js";
	        $this->load_js[] = "https://unpkg.com/pdf-lib@1.17.1/dist/pdf-lib.min.js";
	        $this->load_js[] = asset("js/acta_signature.js"); // Archivo JS para firma de actas

	        /*
	        | ---------------------------------------------------------------------- 
	        | Add css style at body 
	        | ---------------------------------------------------------------------- 
	        | css code in the variable 
	        | $this->style_css = ".style{....}";
	        |
	        */
	        $this->style_css = "
			#actaPdfSignModal .modal-lg { max-width: 90% !important; }
			#actaPdfSignModal .modal-content { max-height: 95vh; }
			#acta-signature-pad { touch-action: none; background: white; }
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
	    
	    // MÉTODO ORIGINAL - Descarga directa del PDF
	    public function actasPDF($id) {
			// retreive all records from db
			$data = DB::table('actas')
            ->join('orders', 'orders.id', '=', 'actas.orderid')
            ->join('customers', 'customers.id', '=', 'orders.id_customer')
            ->join('cms_users', 'cms_users.id', '=', 'orders.id_usuario')
            ->join('equipos', 'equipos.id', '=', 'orders.id_equipo')
            ->select('orders.*', 'equipos.placa','equipos.tipo_equipo','equipos.descripcion','equipos.referencia','equipos.marca','equipos.modelo','equipos.linea','equipos.combustible','equipos.potencia','equipos.interno_equipo','equipos.numero_serie','orders.kms_hrs','customers.name as customer','actas.nombre_e','actas.cedula_e','actas.nombre_r','actas.cedula_r','actas.observaciones as observacion','actas.fecha_salida' )->where('actas.id',$id)->get();
		    // share data to view
			$pdf = PDF::loadView('actas', compact('data'));
			// download PDF file with download method
			return $pdf->download('acta de entrega.pdf');
		}
		
		// ====================================================================
		// NUEVOS MÉTODOS PARA FIRMA DIGITAL DEL ACTA
		// ====================================================================
		
		// Mostrar PDF del acta en línea para vista previa
		public function viewActaPDF($id)
		{
		    try {
		        $data = DB::table('actas')
		            ->join('orders', 'orders.id', '=', 'actas.orderid')
		            ->join('customers', 'customers.id', '=', 'orders.id_customer')
		            ->join('cms_users', 'cms_users.id', '=', 'orders.id_usuario')
		            ->join('equipos', 'equipos.id', '=', 'orders.id_equipo')
		            ->select('orders.*', 'equipos.placa','equipos.tipo_equipo','equipos.descripcion','equipos.referencia','equipos.marca','equipos.modelo','equipos.linea','equipos.combustible','equipos.potencia','equipos.interno_equipo','equipos.numero_serie','orders.kms_hrs','customers.name as customer','actas.nombre_e','actas.cedula_e','actas.nombre_r','actas.cedula_r','actas.observaciones as observacion','actas.fecha_salida' )
		            ->where('actas.id', $id)
		            ->get();

		        if ($data->isEmpty()) {
		            return abort(404, 'Acta no encontrada');
		        }

		        $pdf = PDF::loadView('actas', compact('data'));
		        $pdf->setPaper('A4', 'portrait');

		        return $pdf->stream('acta-entrega-' . $id . '.pdf');
		        
		    } catch (\Exception $e) {
		        \Log::error('Error al mostrar PDF del acta: ' . $e->getMessage());
		        return abort(500, 'Error al generar el PDF del acta');
		    }
		}
		
		// Obtener PDF del acta para descarga/firma
		public function getActaPDF($id)
		{
		    try {
		        $data = DB::table('actas')
		            ->join('orders', 'orders.id', '=', 'actas.orderid')
		            ->join('customers', 'customers.id', '=', 'orders.id_customer')
		            ->join('cms_users', 'cms_users.id', '=', 'orders.id_usuario')
		            ->join('equipos', 'equipos.id', '=', 'orders.id_equipo')
		            ->select('orders.*', 'equipos.placa','equipos.tipo_equipo','equipos.descripcion','equipos.referencia','equipos.marca','equipos.modelo','equipos.linea','equipos.combustible','equipos.potencia','equipos.interno_equipo','equipos.numero_serie','orders.kms_hrs','customers.name as customer','actas.nombre_e','actas.cedula_e','actas.nombre_r','actas.cedula_r','actas.observaciones as observacion','actas.fecha_salida' )
		            ->where('actas.id', $id)
		            ->get();

		        if ($data->isEmpty()) {
		            return response()->json(['error' => 'Acta no encontrada'], 404);
		        }

		        $pdf = PDF::loadView('actas', compact('data'));
		        $pdf->setPaper('A4', 'portrait');

		        return $pdf->download('acta-entrega-' . $id . '.pdf');
		        
		    } catch (\Exception $e) {
		        \Log::error('Error al obtener PDF del acta: ' . $e->getMessage());
		        return response()->json(['error' => 'Error al cargar el PDF'], 500);
		    }
		}
		
		// Guardar firma del acta
		public function saveActaSignature($id)
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

		        $acta = DB::table('actas')->where('id', $id)->first();
		        if (!$acta) {
		            return response()->json([
		                'success' => false,
		                'message' => 'Acta no encontrada'
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

		        $updated = DB::table('actas')->where('id', $id)->update($updateData);

		        if (!$updated) {
		            throw new \Exception('No se pudo guardar la firma');
		        }

		        return response()->json([
		            'success' => true,
		            'message' => 'Firma guardada correctamente',
		            'data' => [
		                'acta_id' => $id,
		                'signer_name' => $updateData['signer_name'],
		                'signed_at' => $updateData['signed_at']->format('Y-m-d H:i:s')
		            ]
		        ]);
		        
		    } catch (\Exception $e) {
		        \Log::error('Error al guardar firma del acta: ' . $e->getMessage());

		        return response()->json([
		            'success' => false,
		            'message' => 'Error al guardar la firma: ' . $e->getMessage()
		        ], 500);
		    }
		}
		
		// Obtener información del firmante del acta
		public function getActaSignerInfo($id)
		{
		    try {
		        $acta = DB::table('actas')
		            ->join('orders', 'actas.orderid', '=', 'orders.id')
		            ->join('cms_users', 'orders.id_usuario', '=', 'cms_users.id')
		            ->where('actas.id', $id)
		            ->select(
		                'cms_users.name as signer_name',
		                DB::raw("'Responsable de Entrega' as signer_position"),
		                DB::raw("'SAROV' as signer_company")
		            )
		            ->first();

		        if (!$acta) {
		            return response()->json([
		                'success' => false,
		                'message' => 'Acta no encontrada'
		            ]);
		        }

		        return response()->json([
		            'success' => true,
		            'data' => $acta
		        ]);
		        
		    } catch (\Exception $e) {
		        return response()->json([
		            'success' => false,
		            'message' => 'Error: ' . $e->getMessage()
		        ]);
		    }
		}

	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for button selected
	    | ---------------------------------------------------------------------- 
	    | @id_selected = the id selected
	    | @button_name = the name of button
	    |
	    */
	    public function actionButtonSelected($id_selected,$button_name) {
	        //Your code here
	    }

	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate query of index result 
	    | ---------------------------------------------------------------------- 
	    | @query = current sql query 
	    |
	    */
	    public function hook_query_index(&$query) {
	      	      if(CRUDBooster::isSuperadmin()){ 
			}else{
			if (CRUDBooster::myPrivilegeId() == '13') {
		  $user_id = CRUDBooster::myId(); 
	      $query->join('cms_users', 'cms_users.id', '=', 'actas.id_usuario')
          ->select('actas.*')
          ->where('actas.id_usuario', $user_id);
			          }else{
			if (CRUDBooster::myPrivilegeId() == '14') {
		  $user_id = CRUDBooster::myId(); 
	      $query->join('cms_users', 'cms_users.id', '=', 'actas.id_usuario')
          ->select('actas.*')
          ->where('actas.id_usuario', $user_id);
			          }else{
			if (CRUDBooster::myPrivilegeId() == '15') {
		  $user_id = CRUDBooster::myId(); 
	      $query->join('cms_users', 'cms_users.id', '=', 'actas.id_usuario')
          ->select('actas.*')
          ->where('actas.id_usuario', $user_id);
			          }else{
			if (CRUDBooster::myPrivilegeId() == '16') {
		  $user_id = CRUDBooster::myId(); 
	      $query->join('cms_users', 'cms_users.id', '=', 'actas.id_usuario')
          ->select('actas.*')
          ->where('actas.id_usuario', $user_id);
			          }else{
			if (CRUDBooster::myPrivilegeId() == '17') {
		  $user_id = CRUDBooster::myId(); 
	      $query->join('cms_users', 'cms_users.id', '=', 'actas.id_usuario')
          ->select('actas.*')
          ->where('actas.id_usuario', $user_id);
			          }else{
			if (CRUDBooster::myPrivilegeId() == '18') {
		  $user_id = CRUDBooster::myId(); 
	      $query->join('cms_users', 'cms_users.id', '=', 'actas.id_usuario')
          ->select('actas.*')
          ->where('actas.id_usuario', $user_id);
			          }else{
			if (CRUDBooster::myPrivilegeId() == '19') {
		  $user_id = CRUDBooster::myId(); 
	      $query->join('cms_users', 'cms_users.id', '=', 'actas.id_usuario')
          ->select('actas.*')
          ->where('actas.id_usuario', $user_id);
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
	    public function hook_row_index($column_index,&$column_value) {	        
	    	//Your code here
	    }

	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate data input before add data is execute
	    | ---------------------------------------------------------------------- 
	    | @arr
	    |
	    */
	    public function hook_before_add(&$postdata) { 
	     $postdata['id_usuario'] = CRUDBooster::myId();     
	        //Your code here
	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after add public static function called 
	    | ---------------------------------------------------------------------- 
	    | @id = last insert id
	    | 
	    */
	    public function hook_after_add($id) {        
	        //Your code here
	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate data input before update data is execute
	    | ---------------------------------------------------------------------- 
	    | @postdata = input post data 
	    | @id       = current id 
	    | 
	    */
	    public function hook_before_edit(&$postdata,$id) {        
	        //Your code here
	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after edit public static function called
	    | ----------------------------------------------------------------------     
	    | @id       = current id 
	    | 
	    */
	    public function hook_after_edit($id) {
	        //Your code here 
	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command before delete public static function called
	    | ----------------------------------------------------------------------     
	    | @id       = current id 
	    | 
	    */
	    public function hook_before_delete($id) {
	        //Your code here
	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after delete public static function called
	    | ----------------------------------------------------------------------     
	    | @id       = current id 
	    | 
	    */
	    public function hook_after_delete($id) {
	        //Your code here
	    }

	    //By the way, you can still create your own method in here... :) 
	}