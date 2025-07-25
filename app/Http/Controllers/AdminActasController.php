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

			# OLD START FORM
			//$this->form = [];
			//$this->form[] = ['label'=>'Order Id','name'=>'orderid','type'=>'text','validation'=>'required','datatable'=>'orders,id','readonly'=>true,'width'=>'col-sm-8'];
			//$this->form[] = ['label'=>'Fecha Salida','name'=>'fecha_salida','type'=>'date','validation'=>'required','width'=>'col-sm-8'];
			//$this->form[] = ['label'=>'Firma Entrega','name'=>'firma_e','type'=>'text','width'=>'col-sm-8'];
			//$this->form[] = ['label'=>'Nombre Entrega','name'=>'nombre_e','type'=>'text','validation'=>'required','width'=>'col-sm-8'];
			//$this->form[] = ['label'=>'Cedula Entrega','name'=>'cedula_e','type'=>'text','validation'=>'required','width'=>'col-sm-8'];
			//$this->form[] = ['label'=>'Firma Recibe','name'=>'firma_r','type'=>'text','width'=>'col-sm-8'];
			//$this->form[] = ['label'=>'Nombre Recibe','name'=>'nombre_r','type'=>'text','width'=>'col-sm-8'];
			//$this->form[] = ['label'=>'Cedula Recibe','name'=>'cedula_r','type'=>'text','width'=>'col-sm-8'];
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
	        $this->addaction[] = ['label'=>'ACTA DE ENTREGA PDF','url'=>('actas').'/'.'[id]','icon'=>'fa fa-file-pdf-o','color'=>'info'];


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
	        
	        
	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | Add css style at body 
	        | ---------------------------------------------------------------------- 
	        | css code in the variable 
	        | $this->style_css = ".style{....}";
	        |
	        */
	        $this->style_css = NULL;
	        
	        
	        
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