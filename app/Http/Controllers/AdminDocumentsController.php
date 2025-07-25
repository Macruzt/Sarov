<?php namespace App\Http\Controllers;

	use Session;
	use Request;
	use DB;
	use CRUDBooster;

	class AdminDocumentsController extends \crocodicstudio\crudbooster\controllers\CBController {

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
			$this->table = "documents";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"Orden Cliente (OQ/WO)","name"=>"req_cliente"];
			$this->col[] = ["label"=>"Acta Firmada","name"=>"acta_firmada"];
			$this->col[] = ["label"=>"Recepcion Firmada","name"=>"recepcion_firmada"];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			$this->form[] = ['label'=>'Orden Cliente (OQ/WO)','name'=>'req_cliente','type'=>'upload','width'=>'col-sm-8'];
			$this->form[] = ['label'=>'Recepción firmada','name'=>'recepcion_firmada','type'=>'upload','width'=>'col-sm-8'];
			$this->form[] = ['label'=>'Cotizacion','name'=>'cotizacion','type'=>'upload','width'=>'col-sm-8'];
			$this->form[] = ['label'=>'Centro de Costos','name'=>'centro_costos','type'=>'upload','width'=>'col-sm-8'];
			$this->form[] = ['label'=>'Acta Firmada','name'=>'acta_firmada','type'=>'upload','width'=>'col-sm-8'];
			$this->form[] = ['label'=>'OP','name'=>'op','type'=>'upload','width'=>'col-sm-8'];
			$this->form[] = ['label'=>'adicionales 1','name'=>'adicional1','type'=>'upload','width'=>'col-sm-8'];
			$this->form[] = ['label'=>'adicionales 2','name'=>'adicional2','type'=>'upload','width'=>'col-sm-8'];
			$this->form[] = ['label'=>'adicionales 3','name'=>'adicional3','type'=>'upload','width'=>'col-sm-8'];
			$this->form[] = ['label'=>'adicionales 4','name'=>'adicional4','type'=>'upload','width'=>'col-sm-8'];
			$this->form[] = ['label'=>'adicionales 5','name'=>'adicional5','type'=>'upload','width'=>'col-sm-8'];
			$this->form[] = ['label'=>'adicionales 6','name'=>'adicional6','type'=>'upload','width'=>'col-sm-8'];
			$this->form[] = ['label'=>'adicionales 7','name'=>'adicional7','type'=>'upload','width'=>'col-sm-8'];
			$this->form[] = ['label'=>'adicionales 8','name'=>'adicional8','type'=>'upload','width'=>'col-sm-8'];
			$this->form[] = ['label'=>'adicionales 9','name'=>'adicional9','type'=>'upload','width'=>'col-sm-8'];
			$this->form[] = ['label'=>'Order Id','name'=>'order_id','type'=>'text','validation'=>'required','datatable'=>'orders,id','readonly'=>true,'width'=>'col-sm-9'];
			# END FORM DO NOT REMOVE THIS LINE

			# OLD START FORM
			//$this->form = [];
			//$this->form[] = ['label'=>'Usuario','name'=>'id_usuario','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-8','datatable'=>'usuario,id'];
			//$this->form[] = ['label'=>'Req Cliente','name'=>'req_cliente','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-8'];
			//$this->form[] = ['label'=>'Centro Costos','name'=>'centro_costos','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-8'];
			//$this->form[] = ['label'=>'Cotizacion','name'=>'cotizacion','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-8'];
			//$this->form[] = ['label'=>'Order Id','name'=>'order_id','type'=>'select2','validation'=>'required','width'=>'col-sm-9'];
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
	      $query->join('cms_users', 'cms_users.id', '=', 'documents.id_usuario')
          ->select('documents.*')
          ->where('documents.id_usuario', $user_id);
			}else{
			if (CRUDBooster::myPrivilegeId() == '14') {
		  $user_id = CRUDBooster::myId(); 
	      $query->join('cms_users', 'cms_users.id', '=', 'documents.id_usuario')
          ->select('documents.*')
          ->where('documents.id_usuario', $user_id);
			          }else{
			if (CRUDBooster::myPrivilegeId() == '15') {
		  $user_id = CRUDBooster::myId(); 
	      $query->join('cms_users', 'cms_users.id', '=', 'documents.id_usuario')
          ->select('documents.*')
          ->where('documents.id_usuario', $user_id);
			          }else{
			if (CRUDBooster::myPrivilegeId() == '16') {
		  $user_id = CRUDBooster::myId(); 
	      $query->join('cms_users', 'cms_users.id', '=', 'documents.id_usuario')
          ->select('documents.*')
          ->where('documents.id_usuario', $user_id);
			          }else{
			if (CRUDBooster::myPrivilegeId() == '17') {
		  $user_id = CRUDBooster::myId(); 
	      $query->join('cms_users', 'cms_users.id', '=', 'documents.id_usuario')
          ->select('documents.*')
          ->where('documents.id_usuario', $user_id);
			          }else{
			if (CRUDBooster::myPrivilegeId() == '18') {
		  $user_id = CRUDBooster::myId(); 
	      $query->join('cms_users', 'cms_users.id', '=', 'documents.id_usuario')
          ->select('documents.*')
          ->where('documents.id_usuario', $user_id);
			          }else{
			if (CRUDBooster::myPrivilegeId() == '19') {
		  $user_id = CRUDBooster::myId(); 
	      $query->join('cms_users', 'cms_users.id', '=', 'documents.id_usuario')
          ->select('documents.*')
          ->where('documents.id_usuario', $user_id);
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
	        //Your code here
         $postdata['id_usuario'] = CRUDBooster::myId(); 
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