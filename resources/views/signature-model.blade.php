<div class="modal fade" id="signatureModal" tabindex="-1" role="dialog" aria-labelledby="signatureModalLabel">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            {{-- Header --}}
            <div class="modal-header" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white;">
                <h4 class="modal-title" id="signatureModalLabel">
                    <i class="fa fa-file-signature"></i>
                    <span id="modal-title-text">Firmar Documento</span>
                    <small id="modal-order-id" class="ml-2"></small>
                </h4>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            {{-- Body --}}
            <div class="modal-body" style="padding: 20px;">
                {{-- Loading --}}
                <div id="modal-loading" class="text-center py-4">
                    <i class="fa fa-spinner fa-spin fa-2x text-primary"></i>
                    <p class="mt-2">Cargando documento...</p>
                </div>

                {{-- Contenido Principal --}}
                <div id="modal-content" style="display: none;">
                    <div class="row">
                        {{-- Vista Previa PDF --}}
                        <div class="col-md-8" id="pdf-column">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">
                                        <i class="fa fa-file-pdf text-danger"></i>
                                        Vista Previa del Documento
                                    </h6>
                                </div>
                                <div class="card-body p-2">
                                    <div class="pdf-container" style="height: 500px; border: 2px solid #dee2e6; border-radius: 8px; background: #f8f9fa; position: relative; overflow: hidden;">
                                        <iframe
                                            id="pdf-preview"
                                            style="width: 100%; height: 100%; border: none;"
                                            onload="SignatureModal.hideLoading()">
                                        </iframe>

                                        {{-- Aquí se mostrarán las previsualizaciones de firmas --}}
                                        <div id="signature-previews-container"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Panel de Firmas --}}
                        <div class="col-md-4" id="signatures-column">
                            {{-- Información General --}}
                            <div class="card mb-3">
                                <div class="card-header bg-info text-white">
                                    <h6 class="mb-0">
                                        <i class="fa fa-info-circle"></i>
                                        Información del Documento
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label><i class="fa fa-calendar"></i> <strong>Fecha y Hora:</strong></label>
                                        <input type="text"
                                            class="form-control"
                                            id="sign-date"
                                            readonly
                                            style="background: #f8f9fa;">
                                    </div>
                                </div>
                            </div>

                            {{-- Contenedor de Firmas Dinámico --}}
                            <div id="signatures-container">
                                {{-- Las firmas se cargarán dinámicamente aquí --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Footer --}}
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fa fa-times"></i> Cancelar
                </button>
                <button type="button"
                    class="btn btn-success btn-lg"
                    id="download-signed-pdf"
                    disabled>
                    <i class="fa fa-download"></i>
                    Descargar PDF Firmado
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Template para una firma individual --}}
<script type="text/template" id="signature-template">
    <div class="card mb-3 signature-card" data-signature-id="__SIGNATURE_ID__">
        <div class="card-header" style="background-color: __COLOR__; color: white;">
            <h6 class="mb-0">
                <i class="fa fa-signature"></i>
                __LABEL__
                <span class="signature-required text-warning" style="display: __REQUIRED_DISPLAY__;">*</span>
            </h6>
        </div>
        <div class="card-body">
            {{-- Información del Firmante --}}
            <div class="signer-info" style="display: __SIGNER_DISPLAY__;">
                <div class="form-group" style="display: __NAME_DISPLAY__;">
                    <label><strong>Nombre:</strong></label>
                    <input type="text" 
                           class="form-control signer-name" 
                           name="signer_name___SIGNATURE_ID__"
                           value="__SIGNER_NAME__"
                           __NAME_READONLY__>
                </div>
                
                <div class="form-group" style="display: __POSITION_DISPLAY__;">
                    <label><strong>Cargo:</strong></label>
                    <input type="text" 
                           class="form-control signer-position" 
                           name="signer_position___SIGNATURE_ID__"
                           value="__SIGNER_POSITION__"
                           __POSITION_READONLY__>
                </div>

                <div class="form-group" style="display: __COMPANY_DISPLAY__;">
                    <label><strong>Empresa:</strong></label>
                    <input type="text" 
                           class="form-control signer-company" 
                           name="signer_company___SIGNATURE_ID__"
                           value="__SIGNER_COMPANY__"
                           __COMPANY_READONLY__>
                </div>
            </div>

            {{-- Área de Firma --}}
            <div class="signature-area">
                <label><strong>Firma:</strong> 
                    <span class="signature-required text-danger" style="display: __REQUIRED_DISPLAY__;">*</span>
                </label>
                
                <div class="signature-container" 
                     style="border: 2px dashed __COLOR__; 
                            background: #f8f9fa; 
                            border-radius: 8px; 
                            padding: 15px; 
                            text-align: center; 
                            position: relative;">
                    
                    <canvas id="signature-pad-__SIGNATURE_ID__" 
                            width="260" 
                            height="100" 
                            style="border: 2px solid #ccc; 
                                   background: #f0f0f0; 
                                   border-radius: 4px; 
                                   cursor: not-allowed; 
                                   display: block; 
                                   margin: 0 auto;"
                            disabled>
                    </canvas>
                    
                    <div id="signature-overlay-__SIGNATURE_ID__" 
                         class="signature-overlay" 
                         style="position: absolute; 
                                top: 15px; 
                                left: 15px; 
                                right: 15px; 
                                bottom: 15px; 
                                background: rgba(0,0,0,0.4); 
                                display: flex; 
                                align-items: center; 
                                justify-content: center; 
                                border-radius: 4px; 
                                z-index: 100;">
                        <span style="color: white; 
                                     font-weight: bold; 
                                     text-shadow: 1px 1px 2px rgba(0,0,0,0.8); 
                                     text-align: center; 
                                     font-size: 11px;">
                            🔒 Clic en "Activar"<br>para firmar
                        </span>
                    </div>
                </div>

                {{-- Controles de Firma --}}
                <div class="signature-controls mt-2">
                    <button type="button" 
                            class="btn btn-sm btn-block mb-2 activate-signature-btn" 
                            style="background-color: __COLOR__; 
                                   color: white; 
                                   border-color: __COLOR__;"
                            data-signature-id="__SIGNATURE_ID__">
                        <i class="fa fa-unlock"></i> Activar Firma
                    </button>
                    
                    <div class="row signature-action-controls" style="display: none;">
                        <div class="col-4">
                            <button type="button" 
                                    class="btn btn-warning btn-sm btn-block clear-signature-btn" 
                                    data-signature-id="__SIGNATURE_ID__">
                                <i class="fa fa-eraser"></i> Limpiar
                            </button>
                        </div>
                        <div class="col-4">
                            <button type="button" 
                                    class="btn btn-info btn-sm btn-block preview-signature-btn" 
                                    data-signature-id="__SIGNATURE_ID__">
                                <i class="fa fa-eye"></i> Vista
                            </button>
                        </div>
                        <div class="col-4">
                            <button type="button" 
                                    class="btn btn-success btn-sm btn-block finish-signature-btn" 
                                    data-signature-id="__SIGNATURE_ID__">
                                <i class="fa fa-check"></i> OK
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Estado de la Firma --}}
                <div class="mt-2">
                    <small class="signature-status text-muted" id="status-__SIGNATURE_ID__">
                        <i class="fa fa-circle text-secondary"></i> Sin firmar
                    </small>
                </div>
            </div>
        </div>
    </div>
</script>

{{-- Template para preview de firma en PDF --}}
<script type="text/template" id="signature-preview-template">
    <div id="signature-preview-__SIGNATURE_ID__" 
         class="signature-preview" 
         style="position: absolute; 
                bottom: __BOTTOM__px; 
                right: 20px; 
                background: rgba(255,255,255,0.98); 
                border: 2px solid __COLOR__; 
                border-radius: 8px; 
                padding: 8px; 
                display: none; 
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                min-width: 140px;
                z-index: 50;">
        <div class="text-center">
            <img id="preview-img-__SIGNATURE_ID__" 
                 style="max-width: 100px; max-height: 30px; display: block; margin: 0 auto;">
            <div class="mt-1" style="font-size: 9px; color: __COLOR__; font-weight: 500;">
                __LABEL__
            </div>
            <div style="font-size: 8px; color: #666;">
                __DATE__
            </div>
        </div>
    </div>
</script>

<style>
    /* Estilos específicos del modal */
    .signature-card {
        transition: all 0.3s ease;
    }

    .signature-card.active {
        transform: scale(1.02);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .signature-container canvas {
        transition: all 0.3s ease;
    }

    .signature-container canvas:not([disabled]) {
        box-shadow: 0 0 10px rgba(40, 167, 69, 0.3);
    }

    .signature-status {
        font-weight: 500;
    }

    .pdf-container iframe {
        transition: opacity 0.3s ease;
    }

    @media (max-width: 768px) {
        .modal-dialog.modal-xl {
            margin: 10px;
            max-width: calc(100% - 20px);
        }

        #pdf-column {
            margin-bottom: 20px;
        }

        .signature-container canvas {
            width: 100% !important;
            max-width: 250px;
        }
    }
</style>