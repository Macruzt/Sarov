<?php namespace App\Http\Controllers;

	use Session;
	use Request;
	use DB;
	use CRUDBooster;

	class AdminEquiposController extends \crocodicstudio\crudbooster\controllers\CBController {

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
			$this->table = "equipos";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
		    $this->col = [];
			$this->col[] = ["label"=>"Tipo de Equipo","name"=>"tipo_equipo"];
			$this->col[] = ["label"=>"Placa","name"=>"placa"];
			$this->col[] = ["label"=>"Numero de Serie","name"=>"numero_serie"];
			$this->col[] = ["label"=>"Descripcion","name"=>"descripcion"];
			$this->col[] = ["label"=>"Referencia y/o medida","name"=>"referencia"];
			$this->col[] = ["label"=>"Marca","name"=>"marca"];
			$this->col[] = ["label"=>"Linea","name"=>"linea"];
			$this->col[] = ["label"=>"Modelo","name"=>"modelo"];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
				$this->form = [];
			$this->form[] = ['label'=>'Placa','name'=>'placa','type'=>'text','validation'=>'required','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Tipo de Equipo','name'=>'tipo_equipo','type'=>'select','dataenum'=>'Vehiculo;Generador;Equipo;Otros','validation'=>'required','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Descripcion','name'=>'descripcion','type'=>'text','validation'=>'required','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Referencia y/o medida','name'=>'referencia','type'=>'text','validation'=>'required','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Marca','name'=>'marca','type'=>'text','validation'=>'required','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Linea','name'=>'linea','type'=>'text','validation'=>'required','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Modelo','name'=>'modelo','type'=>'text','validation'=>'required','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Potencia','name'=>'potencia','type'=>'text','validation'=>'required','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Combustible','name'=>'combustible','type'=>'text','validation'=>'required','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Interno Equipo','name'=>'interno_equipo','type'=>'text','validation'=>'required','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Numero de Serie','name'=>'numero_serie','type'=>'text','validation'=>'required','width'=>'col-sm-10'];
			# END FORM DO NOT REMOVE THIS LINE

			# OLD START FORM
			//$this->form = [];
			//$this->form[] = ['label'=>'OS Sarov','name'=>'order_id','type'=>'select2','validation'=>'required','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Fecha Ingreso','name'=>'fecha_ingreso','type'=>'date','validation'=>'required','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Plaza','name'=>'plaza','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Marca','name'=>'marca','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Linea','name'=>'linea','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Modelo','name'=>'modelo','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Potencia','name'=>'potencia','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Combustible','name'=>'combustible','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Interno Equipo','name'=>'interno_equipo','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Numero de Serie','name'=>'numero_serie','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Kms Hrs','name'=>'kms_hrs','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Nombre Resp Recep','name'=>'nombre_resp_recep','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Resp Recep','name'=>'id_resp_recep','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Firma Resp Recep','name'=>'firma_resp_recep','type'=>'upload','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Nombre Resp Cliente','name'=>'nombre_resp_cliente','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Resp Cliente','name'=>'id_resp_cliente','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Firma Resp Cliente','name'=>'firma_resp_cliente','type'=>'upload','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
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
	        $this->script_js = "$(document).ready(function() {
    $('#marca, #linea, #modelo, #potencia, #combustible, #interno_equipo, #numero_serie').closest('.form-group').show();
    $('#descripcion,#referencia').closest('.form-group').hide();

    // Muestra u oculta los campos de entrada según la opción seleccionada en el select
    $('#tipo_equipo').on('change', function() {
        if ($(this).val() === 'Otros') {
            $('#marca, #linea, #modelo, #potencia, #combustible, #interno_equipo, #numero_serie').closest('.form-group').hide();
            $('#descripcion,#referencia').closest('.form-group').show();
            $('#marca, #linea, #modelo, #potencia, #combustible, #interno_equipo, #numero_serie').val('No contiene').removeAttr('required');
            $('#descripcion,#referencia').attr('required', 'required').val('');
        } else {
            $('#marca, #linea, #modelo, #potencia, #combustible, #interno_equipo, #numero_serie').closest('.form-group').show();
            $('#descripcion,#referencia').closest('.form-group').hide();
            $('#marca, #linea, #modelo, #potencia, #combustible, #interno_equipo, #numero_serie').attr('required', 'required').val('');
            $('#descripcion,#referencia').val('No contiene').removeAttr('required');
        }
    });
});";


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
	    
	// Here Functions controller//


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
	      $query->join('cms_users as cms_users1', 'cms_users1.id', '=', 'equipos.id_usuario')
          ->select('equipos.*')
          ->where('equipos.id_usuario', $user_id);
			          }else{
			if (CRUDBooster::myPrivilegeId() == '14') {
		  $user_id = CRUDBooster::myId(); 
	      $query->join('cms_users as cms_users1', 'cms_users1.id', '=', 'equipos.id_usuario')
          ->select('equipos.*')
          ->where('equipos.id_usuario', $user_id);
			          }else{
			if (CRUDBooster::myPrivilegeId() == '15') {
		  $user_id = CRUDBooster::myId(); 
	      $query->join('cms_users as cms_users1', 'cms_users1.id', '=', 'equipos.id_usuario')
          ->select('equipos.*')
          ->where('equipos.id_usuario', $user_id);
			          }else{
			if (CRUDBooster::myPrivilegeId() == '16') {
		  $user_id = CRUDBooster::myId(); 
	      $query->join('cms_users as cms_users1', 'cms_users1.id', '=', 'equipos.id_usuario')
          ->select('equipos.*')
          ->where('equipos.id_usuario', $user_id);
			          }else{
			if (CRUDBooster::myPrivilegeId() == '17') {
		  $user_id = CRUDBooster::myId(); 
	      $query->join('cms_users as cms_users1', 'cms_users1.id', '=', 'equipos.id_usuario')
          ->select('equipos.*')
          ->where('equipos.id_usuario', $user_id);
			          }else{
			if (CRUDBooster::myPrivilegeId() == '18') {
		  $user_id = CRUDBooster::myId(); 
	      $query->join('cms_users as cms_users1', 'cms_users1.id', '=', 'equipos.id_usuario')
          ->select('equipos.*')
          ->where('equipos.id_usuario', $user_id);
			          }else{
			if (CRUDBooster::myPrivilegeId() == '19') {
		  $user_id = CRUDBooster::myId(); 
	      $query->join('cms_users as cms_users1', 'cms_users1.id', '=', 'equipos.id_usuario')
          ->select('equipos.*')
          ->where('equipos.id_usuario', $user_id);
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