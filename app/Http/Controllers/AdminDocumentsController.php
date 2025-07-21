<?php

namespace App\Http\Controllers;

use Session;
use Request;
use DB;
use CRUDBooster;

class AdminDocumentsController extends \crocodicstudio\crudbooster\controllers\CBController
{

	public function cbInit()
	{

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
		$this->col[] = ["label" => "Orden Cliente (OQ/WO)", "name" => "req_cliente"];
		$this->col[] = ["label" => "Acta Firmada", "name" => "acta_firmada"];
		$this->col[] = ["label" => "Recepcion Firmada", "name" => "recepcion_firmada"];
		# END COLUMNS DO NOT REMOVE THIS LINE

		# START FORM DO NOT REMOVE THIS LINE
		$this->form = [];
		$this->form[] = ['label' => 'Orden Cliente (OQ/WO)', 'name' => 'req_cliente', 'type' => 'upload', 'width' => 'col-sm-8'];
		$this->form[] = ['label' => 'Recepción firmada', 'name' => 'recepcion_firmada', 'type' => 'text', 'width' => 'col-sm-8', 'readonly' => true];
		$this->form[] = ['label' => 'Cotizacion', 'name' => 'cotizacion', 'type' => 'upload', 'width' => 'col-sm-8'];
		$this->form[] = ['label' => 'Centro de Costos', 'name' => 'centro_costos', 'type' => 'upload', 'width' => 'col-sm-8'];
		$this->form[] = ['label' => 'Acta Firmada', 'name' => 'acta_firmada', 'type' => 'text', 'width' => 'col-sm-8', 'readonly' => true];
		$this->form[] = ['label' => 'OP', 'name' => 'op', 'type' => 'upload', 'width' => 'col-sm-8'];
		$this->form[] = ['label' => 'adicionales 1', 'name' => 'adicional1', 'type' => 'upload', 'width' => 'col-sm-8'];
		$this->form[] = ['label' => 'adicionales 2', 'name' => 'adicional2', 'type' => 'upload', 'width' => 'col-sm-8'];
		$this->form[] = ['label' => 'adicionales 3', 'name' => 'adicional3', 'type' => 'upload', 'width' => 'col-sm-8'];
		$this->form[] = ['label' => 'adicionales 4', 'name' => 'adicional4', 'type' => 'upload', 'width' => 'col-sm-8'];
		$this->form[] = ['label' => 'adicionales 5', 'name' => 'adicional5', 'type' => 'upload', 'width' => 'col-sm-8'];
		$this->form[] = ['label' => 'adicionales 6', 'name' => 'adicional6', 'type' => 'upload', 'width' => 'col-sm-8'];
		$this->form[] = ['label' => 'adicionales 7', 'name' => 'adicional7', 'type' => 'upload', 'width' => 'col-sm-8'];
		$this->form[] = ['label' => 'adicionales 8', 'name' => 'adicional8', 'type' => 'upload', 'width' => 'col-sm-8'];
		$this->form[] = ['label' => 'adicionales 9', 'name' => 'adicional9', 'type' => 'upload', 'width' => 'col-sm-8'];
		$this->form[] = ['label' => 'Order Id', 'name' => 'order_id', 'type' => 'text', 'validation' => 'required', 'datatable' => 'orders,id', 'readonly' => true, 'width' => 'col-sm-9'];
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
		$this->script_js = "
			$(document).ready(function(){
				var urlParams = new URLSearchParams(window.location.search);
				var parentId = urlParams.get('parent_id');
				
				if(parentId) {
					$.ajax({
						url: '" . url('admin/documents/get-order-documents/') . "/' + parentId,
						type: 'GET',
						success: function(response) {
							if(response.success && response.data) {
								var data = response.data;
								
								// Campo de recepción
								if(data.has_reception) {
									var receptionText = '📄 ' + (data.reception_filename || 'Documento de recepción firmado');
									if(data.reception_signed_at) {
										receptionText += ' (Firmado: ' + data.reception_signed_at + ')';
									}
									if(data.reception_size) {
										receptionText += ' [' + formatFileSize(data.reception_size) + ']';
									}
									$('#recepcion_firmada').val(receptionText);
									$('#recepcion_firmada').css({
										'cursor': 'pointer',
										'background-color': '#e3f2fd',
										'border': '2px solid #2196f3'
									});
									$('#recepcion_firmada').attr('data-pdf-url', data.reception_url);
									$('#recepcion_firmada').attr('data-doc-type', 'Recepción Firmada');
								} else {
									$('#recepcion_firmada').val('No hay documento de recepción firmado');
									$('#recepcion_firmada').css('color', '#666');
								}
								
								// Campo de acta
								if(data.has_delivery) {
									var deliveryText = '📄 ' + (data.delivery_filename || 'Acta de entrega firmada');
									if(data.delivery_signed_at) {
										deliveryText += ' (Firmado: ' + data.delivery_signed_at + ')';
									}
									if(data.delivery_size) {
										deliveryText += ' [' + formatFileSize(data.delivery_size) + ']';
									}
									$('#acta_firmada').val(deliveryText);
									$('#acta_firmada').css({
										'cursor': 'pointer',
										'background-color': '#e8f5e8',
										'border': '2px solid #4caf50'
									});
									$('#acta_firmada').attr('data-pdf-url', data.delivery_url);
									$('#acta_firmada').attr('data-doc-type', 'Acta de Entrega Firmada');
								} else {
									$('#acta_firmada').val('No hay acta de entrega firmada');
									$('#acta_firmada').css('color', '#666');
								}
								
								$('#order_id').val(parentId);
							}
						},
						error: function() {
							$('#recepcion_firmada').val('Error al cargar documentos');
							$('#acta_firmada').val('Error al cargar documentos');
						}
					});
				}
				
				$(document).on('click', '#recepcion_firmada, #acta_firmada', function() {
					var pdfUrl = $(this).attr('data-pdf-url');
					var docType = $(this).attr('data-doc-type');
					
					if (pdfUrl && pdfUrl !== 'undefined') {
						openPDFViewerModal(pdfUrl, docType);
					}
				});
			});

			function formatFileSize(bytes) {
				if (bytes === 0) return '0 Bytes';
				const k = 1024;
				const sizes = ['Bytes', 'KB', 'MB'];
				const i = Math.floor(Math.log(bytes) / Math.log(k));
				return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
			}

			function openPDFViewerModal(pdfUrl, docType) {
    var modalHtml = `
        <div class='modal fade' id='pdfViewerModal' tabindex='-1' role='dialog' style='z-index: 9999;'>
            <div class='modal-dialog modal-xl' role='document' style='max-width: 95%; width: 95%;'>
                <div class='modal-content' style='height: 90vh;'>
                    <div class='modal-header bg-primary text-white' style='text-align: center; justify-content: center; position: relative;'>
                        <div style='position: absolute; left: 15px; top: 50%; transform: translateY(-50%);'>
                            <!-- Espacio para balancear el botón de cerrar -->
                        </div>
                        <h4 class='modal-title' style='margin: 0; flex-grow: 1; text-align: center;'>
                            <i class='fa fa-file-pdf-o'></i> ` + docType + `
                        </h4>
                        <button type='button' class='close' data-dismiss='modal' style='position: absolute; right: 15px; top: 50%; transform: translateY(-50%); color: white; opacity: 1; font-size: 24px;'>
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class='modal-body p-0' style='height: calc(90vh - 60px);'>
                        <!-- Barra de botones centrada -->
                        <div style='background: #f8f9fa; padding: 10px 0; text-align: center; border-bottom: 1px solid #dee2e6;'>
                            <div class='btn-group' role='group'>
                                <a href='` + pdfUrl + `' target='_blank' class='btn btn-outline-primary btn-lg' style='margin: 0 5px; padding: 10px 20px; font-weight: 500;'>
                                    <i class='fa fa-external-link' style='margin-right: 8px;'></i>
                                    Abrir en Nueva Pestaña
                                </a>
                                <button type='button' class='btn btn-outline-secondary btn-lg' data-dismiss='modal' style='margin: 0 5px; padding: 10px 20px; font-weight: 500;'>
                                    <i class='fa fa-times' style='margin-right: 8px;'></i>
                                    Cerrar Visor
                                </button>
                            </div>
                        </div>
                        
                        <!-- Contenido del PDF -->
                        <div style='height: calc(90vh - 120px);'>
                            <div id='pdfLoadingMessage' class='text-center p-4'>
                                <i class='fa fa-spinner fa-spin fa-2x text-primary'></i>
                                <p class='mt-2' style='font-size: 16px;'>Cargando documento...</p>
                            </div>
                            <iframe id='pdfViewer' 
                                    src='` + pdfUrl + `#toolbar=1&navpanes=1&scrollbar=1' 
                                    style='width: 100%; height: 100%; border: none; display: none;'
                                    onload='$(\"#pdfLoadingMessage\").hide(); $(this).show();'>
                            </iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    $('#pdfViewerModal').remove();
    $('body').append(modalHtml);
    $('#pdfViewerModal').modal('show');
    $('#pdfViewerModal').on('hidden.bs.modal', function() {
        $(this).remove();
    });
}
		";

		$this->style_css = "
			#recepcion_firmada[data-pdf-url], #acta_firmada[data-pdf-url] {
				cursor: pointer !important;
				transition: all 0.3s ease;
			}

			#recepcion_firmada[data-pdf-url]:hover {
				background-color: #bbdefb !important;
				transform: translateY(-1px);
				box-shadow: 0 2px 4px rgba(0,0,0,0.1);
			}

			#acta_firmada[data-pdf-url]:hover {
				background-color: #c8e6c9 !important;
				transform: translateY(-1px);
				box-shadow: 0 2px 4px rgba(0,0,0,0.1);
			}

			.modal-xl {
				max-width: 95% !important;
			}

			#pdfViewerModal .modal-content {
				border-radius: 8px;
				box-shadow: 0 10px 25px rgba(0,0,0,0.3);
			}

			#pdfViewerModal .modal-header {
				border-radius: 8px 8px 0 0;
				display: flex;
				justify-content: space-between;
				align-items: center;
			}

			#pdfViewerModal .btn-group .btn {
				margin-left: 5px;
			}
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
	public function actionButtonSelected($id_selected, $button_name)
	{
		//Your code here

	}


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
				$query->join('cms_users', 'cms_users.id', '=', 'documents.id_usuario')
					->select('documents.*')
					->where('documents.id_usuario', $user_id);
			} else {
				if (CRUDBooster::myPrivilegeId() == '14') {
					$user_id = CRUDBooster::myId();
					$query->join('cms_users', 'cms_users.id', '=', 'documents.id_usuario')
						->select('documents.*')
						->where('documents.id_usuario', $user_id);
				} else {
					if (CRUDBooster::myPrivilegeId() == '15') {
						$user_id = CRUDBooster::myId();
						$query->join('cms_users', 'cms_users.id', '=', 'documents.id_usuario')
							->select('documents.*')
							->where('documents.id_usuario', $user_id);
					} else {
						if (CRUDBooster::myPrivilegeId() == '16') {
							$user_id = CRUDBooster::myId();
							$query->join('cms_users', 'cms_users.id', '=', 'documents.id_usuario')
								->select('documents.*')
								->where('documents.id_usuario', $user_id);
						} else {
							if (CRUDBooster::myPrivilegeId() == '17') {
								$user_id = CRUDBooster::myId();
								$query->join('cms_users', 'cms_users.id', '=', 'documents.id_usuario')
									->select('documents.*')
									->where('documents.id_usuario', $user_id);
							} else {
								if (CRUDBooster::myPrivilegeId() == '18') {
									$user_id = CRUDBooster::myId();
									$query->join('cms_users', 'cms_users.id', '=', 'documents.id_usuario')
										->select('documents.*')
										->where('documents.id_usuario', $user_id);
								} else {
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
	public function hook_row_index($column_index, &$column_value)
	{

		if ($column_index == 1) {
			$order_id = $this->getCurrentOrderId();
			if ($order_id) {
				$column_value = "Orden #" . $order_id . " - " . $column_value;
			}
		}

		if ($column_index == 2) {
			if ($column_value) {
				$order_id = $this->getCurrentOrderId();
				$url = url('admin/orders/download-document/' . $order_id . '/delivery');
				$column_value = "<a href='" . $url . "' target='_blank' class='btn btn-sm btn-success'><i class='fa fa-download'></i> Descargar Acta</a>";
			} else {
				$column_value = "<span class='text-muted'>No disponible</span>";
			}
		}

		if ($column_index == 3) {
			if ($column_value) {
				$order_id = $this->getCurrentOrderId();
				$url = url('admin/orders/download-document/' . $order_id . '/reception');
				$column_value = "<a href='" . $url . "' target='_blank' class='btn btn-sm btn-primary'><i class='fa fa-download'></i> Descargar Recepción</a>";
			} else {
				$column_value = "<span class='text-muted'>No disponible</span>";
			}
		}
	}


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

		$order_id = Request::get('parent_id') ?: $postdata['order_id'] ?? null;

		if ($order_id) {
			$serviceOrderDocs = DB::table('service_order_documents')
				->where('order_id', $order_id)
				->first();

			if ($serviceOrderDocs) {
				$postdata['recepcion_firmada'] = $serviceOrderDocs->reception_filename;
				$postdata['acta_firmada'] = $serviceOrderDocs->delivery_filename;
			}
			$postdata['order_id'] = $order_id;
		}
	}

	/* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after add public static function called 
	    | ---------------------------------------------------------------------- 
	    | @id = last insert id
	    | 
	    */
	public function hook_after_add($id) {}

	/* 
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate data input before update data is execute
	    | ---------------------------------------------------------------------- 
	    | @postdata = input post data 
	    | @id       = current id 
	    | 
	    */
	public function hook_before_edit(&$postdata, $id)
	{
		$order_id = Request::get('parent_id') ?: $postdata['order_id'] ?? null;

		if ($order_id) {
			$serviceOrderDocs = DB::table('service_order_documents')
				->where('order_id', $order_id)
				->first();

			if ($serviceOrderDocs) {
				$postdata['recepcion_firmada'] = $serviceOrderDocs->reception_filename;
				$postdata['acta_firmada'] = $serviceOrderDocs->delivery_filename;
			}
		}
	}

	/* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after edit public static function called
	    | ----------------------------------------------------------------------     
	    | @id       = current id 
	    | 
	    */
	public function hook_after_edit($id) {}

	/* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command before delete public static function called
	    | ----------------------------------------------------------------------     
	    | @id       = current id 
	    | 
	    */
	public function hook_before_delete($id) {}

	/* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after delete public static function called
	    | ----------------------------------------------------------------------     
	    | @id       = current id 
	    | 
	    */
	public function hook_after_delete($id) {}

	public function getOrderDocuments($order_id)
	{
		try {
			$documents = DB::table('service_order_documents')
				->where('order_id', $order_id)
				->select(
					'reception_signed',
					'delivery_signed',
					'reception_signed_at',
					'delivery_signed_at',
					'reception_filename',
					'delivery_filename',
					'reception_size',
					'delivery_size'
				)
				->first();

			$response = [
				'has_reception' => !empty($documents->reception_signed),
				'has_delivery' => !empty($documents->delivery_signed),
				'reception_url' => !empty($documents->reception_signed) ? url('admin/orders/download-document/' . $order_id . '/reception') : null,
				'delivery_url' => !empty($documents->delivery_signed) ? url('admin/orders/download-document/' . $order_id . '/delivery') : null,
				'reception_filename' => $documents->reception_filename ?? null,
				'delivery_filename' => $documents->delivery_filename ?? null,
				'reception_size' => $documents->reception_size ?? null,
				'delivery_size' => $documents->delivery_size ?? null,
				'reception_signed_at' => $documents->reception_signed_at ?? null,
				'delivery_signed_at' => $documents->delivery_signed_at ?? null
			];

			return response()->json([
				'success' => true,
				'data' => $response
			]);
		} catch (\Exception $e) {
			return response()->json([
				'success' => false,
				'message' => 'Error al obtener documentos: ' . $e->getMessage()
			]);
		}
	}

	private function getCurrentOrderId()
	{
		$currentId = Request::route('id');
		if ($currentId) {
			$document = DB::table('documents')->where('id', $currentId)->first();
			return $document ? $document->order_id : null;
		}
		return Request::get('parent_id');
	}
}
