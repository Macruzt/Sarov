<?php namespace App\Http\Controllers;

	use Session;
	use Request;
	use DB;
	use CRUDBooster;
	use App\Models\informes;
	use PDF;

	class AdminInformesController extends \crocodicstudio\crudbooster\controllers\CBController {

	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "id";
			$this->limit = "20";
			$this->orderby = "id,desc";
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
			$this->table = "informes";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"Informe Tecnico","name"=>"informe_tecnico"];
			$this->col[] = ["label"=>"Recomendaciones","name"=>"observaciones"];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			$this->form[] = ['label'=>'Order Id','name'=>'order_id','type'=>'text','validation'=>'required','datatable'=>'orders,id','readonly'=>true,'width'=>'col-sm-9'];
		    	//$observaciones = DB::table('orders')->select('observaciones')->get();
            $this->form[] = ['label'=>'Descripcion Servicio','type'=>'textarea','name'=>'descripcion_servicio','width'=>'col-sm-8'];
			$this->form[] = ['label'=>'Informe Tecnico','name'=>'informe_tecnico','type'=>'textarea','validation'=>'required','width'=>'col-sm-8'];
			$this->form[] = ['label'=>'Evidencia Antes Foto 1','name'=>'evdfa1','type'=>'upload','validation'=>'required','width'=>'col-sm-8'];
			$this->form[] = ['label'=>'Evidencia Antes Foto 2','name'=>'evdfa2','type'=>'upload','validation'=>'required','width'=>'col-sm-8'];
			$this->form[] = ['label'=>'Evidencia Antes Foto 3','name'=>'evdfa3','type'=>'upload','validation'=>'required','width'=>'col-sm-8'];
			$this->form[] = ['label'=>'Evidencia Antes Foto 4','name'=>'evdfa4','type'=>'upload','width'=>'col-sm-8'];
			$this->form[] = ['label'=>'Evidencia Antes Foto 5','name'=>'evdfa5','type'=>'upload','width'=>'col-sm-8'];
			$this->form[] = ['label'=>'Evidencia Antes Foto 6','name'=>'evdfa6','type'=>'upload','width'=>'col-sm-8'];
			$this->form[] = ['label'=>'Evidencia Despues Foto 1','name'=>'evdfd1','type'=>'upload','validation'=>'required','width'=>'col-sm-8'];
			$this->form[] = ['label'=>'Evidencia Despues Foto 2','name'=>'evdfd2','type'=>'upload','validation'=>'required','width'=>'col-sm-8'];
			$this->form[] = ['label'=>'Evidencia Despues Foto 3','name'=>'evdfd3','type'=>'upload','validation'=>'required','width'=>'col-sm-8'];
			$this->form[] = ['label'=>'Evidencia Despues Foto 4','name'=>'evdfd4','type'=>'upload','width'=>'col-sm-8'];
			$this->form[] = ['label'=>'Evidencia Despues Foto 5','name'=>'evdfd5','type'=>'upload','width'=>'col-sm-8'];
			$this->form[] = ['label'=>'Evidencia Despues Foto 6','name'=>'evdfd6','type'=>'upload','width'=>'col-sm-8'];
			$this->form[] = ['label'=>'Anexo 1','name'=>'anexo1','type'=>'upload','width'=>'col-sm-8'];
			$this->form[] = ['label'=>'Anexo 2','name'=>'anexo2','type'=>'upload','width'=>'col-sm-8'];
			$this->form[] = ['label'=>'Anexo 3','name'=>'anexo3','type'=>'upload','width'=>'col-sm-8'];
			$this->form[] = ['label'=>'Observaciones y Recomendaciones','name'=>'observaciones','type'=>'textarea','validation'=>'required','width'=>'col-sm-8'];
			# END FORM DO NOT REMOVE THIS LINE

			# OLD START FORM
			//$this->form = [];
			//$this->form[] = ['label'=>'Order Id','name'=>'order_id','type'=>'select2','validation'=>'required','width'=>'col-sm-8'];
			//$this->form[] = ['label'=>'Descripcion Servicio','name'=>'descripcion_servicio','type'=>'textarea','validation'=>'required','width'=>'col-sm-8'];
			//$this->form[] = ['label'=>'Informe Tecnico','name'=>'informe_tecnico','type'=>'textarea','validation'=>'required','width'=>'col-sm-8'];
			//$this->form[] = ['label'=>'Evidencia Antes Foto 1','name'=>'evdfa1','type'=>'upload','validation'=>'required','width'=>'col-sm-8'];
			//$this->form[] = ['label'=>'Evidencia Antes Foto 2','name'=>'evdfa2','type'=>'upload','validation'=>'required','width'=>'col-sm-8'];
			//$this->form[] = ['label'=>'Evidencia Antes Foto 3','name'=>'evdfa3','type'=>'upload','validation'=>'required','width'=>'col-sm-8'];
			//$this->form[] = ['label'=>'Evidencia Antes Foto 4','name'=>'evdfa4','type'=>'upload','validation'=>'required','width'=>'col-sm-8'];
			//$this->form[] = ['label'=>'Evidencia Antes Foto 5','name'=>'evdfa5','type'=>'upload','validation'=>'required','width'=>'col-sm-8'];
			//$this->form[] = ['label'=>'Evidencia Antes Foto 6','name'=>'evdfa6','type'=>'upload','validation'=>'required','width'=>'col-sm-8'];
			//$this->form[] = ['label'=>'Evidencia Despues Foto 1','name'=>'evdfd1','type'=>'upload','validation'=>'required','width'=>'col-sm-8'];
			//$this->form[] = ['label'=>'Evidencia Despues Foto 2','name'=>'evdfd2','type'=>'upload','validation'=>'required','width'=>'col-sm-8'];
			//$this->form[] = ['label'=>'Evidencia Despues Foto 3','name'=>'evdfd3','type'=>'upload','validation'=>'required','width'=>'col-sm-8'];
			//$this->form[] = ['label'=>'Evidencia Despues Foto 4','name'=>'evdfd4','type'=>'upload','validation'=>'required','width'=>'col-sm-8'];
			//$this->form[] = ['label'=>'Evidencia Despues Foto 5','name'=>'evdfd5','type'=>'upload','validation'=>'required','width'=>'col-sm-8'];
			//$this->form[] = ['label'=>'Evdfd6','name'=>'evdfd6','type'=>'upload','validation'=>'required','width'=>'col-sm-8'];
			//$this->form[] = ['label'=>'Anexo1','name'=>'anexo1','type'=>'upload','validation'=>'required','width'=>'col-sm-8'];
			//$this->form[] = ['label'=>'Anexo2','name'=>'anexo2','type'=>'upload','validation'=>'required','width'=>'col-sm-8'];
			//$this->form[] = ['label'=>'Anexo3','name'=>'anexo3','type'=>'upload','validation'=>'required','width'=>'col-sm-8'];
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
	        $this->addaction[] = ['label'=>'INFORME PDF','url'=>('informes/') . '[id]','icon'=>'fa fa-file-pdf-o','color'=>'info'];	


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
	    
	     public function informeservicioPDF($id) {
			// retreive all records from db
			$data2 = DB::table('informes')
    ->join('orders', 'orders.id', '=', 'informes.order_id')
    ->join('customers', 'customers.id', '=', 'orders.id_customer')
    ->join('equipos', 'equipos.id', '=', 'orders.id_equipo')
    ->select(
        'orders.*', 'equipos.placa','equipos.tipo_equipo','equipos.descripcion','equipos.referencia','equipos.marca', 'equipos.modelo',
        'equipos.linea', 'equipos.combustible', 'equipos.potencia', 'equipos.interno_equipo',
        'equipos.numero_serie', 'orders.kms_hrs', 'informes.descripcion_servicio',
        'informes.informe_tecnico', 'informes.observaciones AS recomendaciones',
        'informes.evdfa1', 'informes.evdfa2', 'informes.evdfa3', 'informes.evdfa4',
        'informes.evdfa5', 'informes.evdfa6', 'informes.evdfd1', 'informes.evdfd2',
        'informes.evdfd3', 'informes.evdfd4', 'informes.evdfd5', 'informes.evdfd6',
        'orders.observaciones', 'informes.anexo1', 'informes.anexo2', 'informes.anexo3',
        'customers.name AS customer'
    )
    ->where('informes.id', $id)->get();
		    // share data to view
			$pdf = PDF::loadView('informeservicio', array('data2' =>  $data2));
			// download PDF file with download method
			return $pdf->download('informe de Servicio.pdf');
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
	      $query->join('cms_users', 'cms_users.id', '=', 'informes.id_usuario')
          ->select('informes.*')
          ->where('informes.id_usuario', $user_id);
			          }else{
			if (CRUDBooster::myPrivilegeId() == '14') {
		  $user_id = CRUDBooster::myId(); 
	      $query->join('cms_users', 'cms_users.id', '=', 'informes.id_usuario')
          ->select('informes.*')
          ->where('informes.id_usuario', $user_id);
			          }else{
			if (CRUDBooster::myPrivilegeId() == '15') {
		  $user_id = CRUDBooster::myId(); 
	      $query->join('cms_users', 'cms_users.id', '=', 'informes.id_usuario')
          ->select('informes.*')
          ->where('informes.id_usuario', $user_id);
			          }else{
			if (CRUDBooster::myPrivilegeId() == '17') {
		  $user_id = CRUDBooster::myId(); 
	      $query->join('cms_users', 'cms_users.id', '=', 'informes.id_usuario')
          ->select('informes.*')
          ->where('informes.id_usuario', $user_id);
			          }else{
			if (CRUDBooster::myPrivilegeId() == '18') {
		  $user_id = CRUDBooster::myId(); 
	      $query->join('cms_users', 'cms_users.id', '=', 'informes.id_usuario')
          ->select('informes.*')
          ->where('informes.id_usuario', $user_id);
			          }else{
			if (CRUDBooster::myPrivilegeId() == '19') {
		  $user_id = CRUDBooster::myId(); 
	      $query->join('cms_users', 'cms_users.id', '=', 'informes.id_usuario')
          ->select('informes.*')
          ->where('informes.id_usuario', $user_id);
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