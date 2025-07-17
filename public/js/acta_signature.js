// Variables globales para el modal del acta
window.actaSignaturePad = null;
window.currentActaId = null;
window.isActaSigningActive = false;
window.isActaMouseDown = false;

// NUEVAS VARIABLES para manejo de dos firmas
window.currentSignatureStep = 1; // 1 = quien entrega, 2 = quien recibe
window.entregaSignature = null; // Almacenar primera firma
window.recibeSignature = null;  // Almacenar segunda firma

// Función principal para abrir el modal del acta
async function openActaPDFModal(actaId) {
    // Validar acta ID
    if (!actaId || actaId === "[id]") {
        alert("Error: ID de acta no válido");
        return;
    }

    window.currentActaId = actaId;

    // Usar valores por defecto sin hacer peticiones al backend
    window.actaSignerName = "Usuario del Sistema";
    window.actaSignerPosition = "Responsable de Entrega";
    window.actaSignerCompany = "SAROV";

    // Verificar dependencias
    if (!checkActaDependencies()) {
        return;
    }

    // Remover modal anterior si existe
    cleanupActaModal();

    try {
        // Crear y mostrar modal
        var modalHtml = createActaModalHTML(actaId);
        $("body").append(modalHtml);

        $("#actaPdfSignModal").modal({
            backdrop: "static",
            keyboard: false,
            show: true,
        });

        // Configurar eventos del modal
        setupActaModalEvents();
    } catch (error) {
        alert("Error al abrir el modal del acta: " + error.message);
    }
}

// Verificar dependencias
function checkActaDependencies() {
    var missing = [];

    if (typeof SignaturePad === "undefined") {
        missing.push("SignaturePad");
    }

    if (typeof PDFLib === "undefined") {
        missing.push("PDFLib");
    }

    if (typeof jQuery === "undefined" && typeof $ === "undefined") {
        missing.push("jQuery");
    }

    if (missing.length > 0) {
        alert(
            "Error: Las siguientes librerías no están disponibles: " +
                missing.join(", ") + 
                "\nVerifique que estén cargadas correctamente."
        );
        return false;
    }

    return true;
}

// Limpiar modal anterior
function cleanupActaModal() {
    if ($("#actaPdfSignModal").length) {
        $("#actaPdfSignModal").modal("hide").remove();
    }
    $(".modal-backdrop").remove();
    $("body").removeClass("modal-open");
    window.actaSignaturePad = null;
    window.isActaSigningActive = false;
    window.isActaMouseDown = false;
    
    // RESETEAR variables de firma
    window.currentSignatureStep = 1;
    window.entregaSignature = null;
    window.recibeSignature = null;
}

// Configurar eventos del modal
function setupActaModalEvents() {
    // Cuando el modal se muestra completamente
    $("#actaPdfSignModal").on("shown.bs.modal", function () {
        $("#acta-debug-info").show();
        $("#acta-signature-status").text("Inicializando...");

        setTimeout(function () {
            initActaSignaturePad();
        }, 500);
    });

    // Cuando el modal se oculta
    $("#actaPdfSignModal").on("hidden.bs.modal", function () {
        cleanupActaModal();
    });

    // Manejar errores del iframe
    $("#acta-pdf-preview").on("error", function () {
        console.warn("Error cargando PDF preview del acta");
        $(this)
            .parent()
            .html(
                '<div class="alert alert-warning text-center"><i class="fa fa-exclamation-triangle"></i><br>No se pudo cargar la vista previa del PDF del acta</div>'
            );
    });
}

// Función para crear el HTML del modal del acta
function createActaModalHTML(actaId) {
    var baseUrl = getBaseUrl();
    var pdfUrl = baseUrl + "/admin/actas/" + actaId + "/view-pdf"; // Ruta para vista previa del acta

    var currentDate = new Date().toLocaleString("es-CO", {
        year: "numeric",
        month: "2-digit",
        day: "2-digit",
        hour: "2-digit",
        minute: "2-digit",
    });

    return (
        '<div class="modal fade" id="actaPdfSignModal" tabindex="-1" role="dialog">' +
        '<div class="modal-dialog modal-lg" style="width: 95%; max-width: 1200px;">' +
        '<div class="modal-content">' +
        '<div class="modal-header" style="background: linear-gradient(45deg, #fba601, #FCC402); color: white;">' +
        '<h4 class="modal-title">' +
        '<i class="fa fa-file-signature"></i> ' +
        "Firmar Acta de Entrega - ID #" +
        actaId +
        "</h4>" +
        '<button type="button" class="close" data-dismiss="modal" style="color: white; opacity: 1;">' +
        "<span>&times;</span>" +
        "</button>" +
        "</div>" +
        '<div class="modal-body" style="padding: 20px; max-height: 85vh; overflow-y: auto;">' +
        "<!-- Indicador de carga -->" +
        '<div id="acta-loading-indicator" class="text-center" style="padding: 20px;">' +
        '<i class="fa fa-spinner fa-spin fa-2x text-primary"></i>' +
        '<p class="mt-2">Cargando acta de entrega...</p>' +
        "</div>" +
        "<!-- Contenido principal -->" +
        '<div id="acta-main-content" style="display: none;">' +
        '<div class="row">' +
        "<!-- Vista previa PDF -->" +
        '<div class="col-md-8">' +
        '<div class="card">' +
        '<div class="card-header">' +
        '<h6 class="mb-0"><i class="fa fa-file-pdf text-danger"></i> Vista Previa del Acta de Entrega</h6>' +
        "</div>" +
        '<div class="card-body" style="padding: 10px;">' +
        '<div style="height: 500px; border: 2px solid #dee2e6; border-radius: 8px; background: #f8f9fa; position: relative; overflow: hidden;">' +
        '<iframe id="acta-pdf-preview" ' +
        'src="' +
        pdfUrl +
        '" ' +
        'style="width: 100%; height: 100%; border: none;" ' +
        'onload="hideActaLoading()">' +
        "</iframe>" +
        "<!-- Vista previa de firma -->" +
        '<div id="acta-signature-preview" style="position: absolute; bottom: 20px; left: 80px; ' +
        "background: rgba(255,255,255,0.98); border: 2px solid #dc3545; border-radius: 8px; " +
        'padding: 10px; display: none; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">' +
        '<div style="text-align: center;">' +
        '<img id="acta-preview-signature" style="max-width: 120px; max-height: 40px; display: block; margin: 0 auto;">' +
        '<div style="font-size: 10px; margin-top: 5px; color: #dc3545; font-weight: 500;">' +
        "Firmado: " +
        new Date().toLocaleDateString("es-CO") +
        "</div>" +
        "</div>" +
        "</div>" +
        "</div>" +
        "</div>" +
        "</div>" +
        "</div>" +
        "<!-- Panel de firma -->" +
        '<div class="col-md-4">' +
        '<div class="card">' +
        '<div class="card-header" style="background: #f8f9fa;">' +
        '<h6 class="mb-0" style="color: #dc3545;"><i class="fa fa-signature"></i> Firma Digital del Acta</h6>' +
        "</div>" +
        '<div class="card-body">' +
        '<div class="alert alert-info" style="font-size: 12px; padding: 10px; margin-bottom: 15px;">' +
        '<i class="fa fa-info-circle"></i> Haga clic en "ACTIVAR FIRMA" para comenzar' +
        "</div>" +
        "<!-- Fecha automática -->" +
        '<div class="form-group">' +
        '<label><strong><i class="fa fa-calendar"></i> Fecha y Hora:</strong></label>' +
        '<input type="text" class="form-control" id="acta-sign-date" readonly value="' +
        currentDate +
        '" style="background: #f8f9fa;">' +
        "</div>" +
        "<!-- Área de firma -->" +
        '<div class="form-group">' +
        '<label><strong><i class="fa fa-signature"></i> <span id="current-signature-label">Firma de quien entrega:</span></strong> <span class="text-danger">*</span></label>' +
        '<div id="acta-signature-container" style="border: 2px dashed #dc3545; background: #f8f9fa; border-radius: 8px; padding: 15px; text-align: center; position: relative;">' +
        '<canvas id="acta-signature-pad" ' +
        'width="300" height="150" ' +
        'style="border: 2px solid #ccc; background: #f0f0f0; border-radius: 4px; cursor: not-allowed; display: block; margin: 0 auto; touch-action: none;" ' +
        "disabled>" +
        "</canvas>" +
        '<div id="acta-signature-overlay" style="position: absolute; top: 15px; left: 15px; right: 15px; bottom: 30px; background: rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center; border-radius: 4px; z-index: 10;">' +
        '<span style="color: white; font-weight: bold; text-shadow: 1px 1px 2px rgba(0,0,0,0.7); text-align: center;">🔒 Hacer clic en<br>"ACTIVAR FIRMA"<br>para comenzar</span>' +
        "</div>" +
        '<small class="text-muted d-block mt-2" id="acta-signature-instructions">' +
        '<i class="fa fa-info-circle"></i> <strong>Instrucciones:</strong> 1) Active la firma 2) Dibuje sin salir del área 3) Revise el resultado' +
        "</small>" +
        "<!-- Debug info -->" +
        '<div id="acta-debug-info" style="display: none;">' +
        'Estado: <span id="acta-signature-status">Desactivado</span>' +
        "</div>" +
        "</div>" +
        "</div>" +
        "<!-- Botones de control -->" +
        '<div class="row mb-3">' +
        '<div class="col-12">' +
        '<button type="button" id="acta-activate-signature-btn" class="btn btn-danger btn-lg btn-block" onclick="activateActaSignature()" style="font-weight: bold;">' +
        '<i class="fa fa-unlock"></i> ACTIVAR FIRMA - Haga clic aquí para comenzar' +
        "</button>" +
        "</div>" +
        "</div>" +
        '<div class="row" id="acta-signature-controls" style="display: none;">' +
        '<div class="col-4">' +
        '<button type="button" class="btn btn-warning btn-sm btn-block" onclick="clearActaSignature()" title="Limpiar firma">' +
        '<i class="fa fa-eraser"></i> Limpiar' +
        "</button>" +
        "</div>" +
        '<div class="col-4">' +
        '<button type="button" class="btn btn-info btn-sm btn-block" onclick="previewActaSignature()" title="Ver vista previa">' +
        '<i class="fa fa-eye"></i> Vista Previa' +
        "</button>" +
        "</div>" +
        '<div class="col-4">' +
        '<button type="button" class="btn btn-success btn-sm btn-block" onclick="finishActaSignature()" title="Terminar de firmar">' +
        '<i class="fa fa-check"></i> Terminar' +
        "</button>" +
        "</div>" +
        "</div>" +
        "<!-- Información automática -->" +
        '<div class="mt-3">' +
        '<small class="text-muted" id="signature-step-info">' +
        '<i class="fa fa-info-circle"></i> <strong>Paso 1 de 2:</strong><br>' +
        "• Firmando como: Quien entrega<br>" +
        "• Empresa: SAROV<br>" +
        "• Después continuará con la firma de quien recibe" +
        "</small>" +
        "</div>" +
        "</div>" +
        "</div>" +
        "</div>" +
        "</div>" +
        "</div>" +
        "</div>" +
        '<div class="modal-footer" style="padding: 15px 20px;">' +
        '<button type="button" class="btn btn-secondary" data-dismiss="modal">' +
        '<i class="fa fa-times"></i> Cancelar' +
        "</button>" +
        '<button type="button" class="btn btn-danger btn-lg" onclick="downloadSignedActaPDF(' +
        actaId +
        ')" style="margin-left: 10px;">' +
        '<i class="fa fa-download"></i> Guardar Acta' +
        "</button>" +
        "</div>" +
        "</div>" +
        "</div>" +
        "</div>"
    );
}

// Obtener URL base
function getBaseUrl() {
    return window.location.origin;
}

// Ocultar indicador de carga
function hideActaLoading() {
    setTimeout(function () {
        $("#acta-loading-indicator").fadeOut(300, function () {
            $("#acta-main-content").fadeIn(300);
        });
    }, 500);
}

// Inicializar SignaturePad para el acta
function initActaSignaturePad() {
    var canvas = document.getElementById("acta-signature-pad");
    if (!canvas) {
        alert("Error: No se encontró el área de firma del acta");
        return;
    }

    try {
        // Configurar dimensiones
        var rect = canvas.getBoundingClientRect();
        var computedStyle = getComputedStyle(canvas);

        var displayWidth = parseInt(computedStyle.width) || 300;
        var displayHeight = parseInt(computedStyle.height) || 150;

        var ratio = Math.max(window.devicePixelRatio || 1, 1);

        canvas.width = displayWidth * ratio;
        canvas.height = displayHeight * ratio;

        var ctx = canvas.getContext("2d");
        ctx.scale(ratio, ratio);

        canvas.style.width = displayWidth + "px";
        canvas.style.height = displayHeight + "px";

        // Crear SignaturePad
        window.actaSignaturePad = new SignaturePad(canvas, {
            backgroundColor: "rgba(255, 255, 255, 1)",
            penColor: "rgb(0, 0, 0)",
            velocityFilterWeight: 0.7,
            minWidth: 0.8,
            maxWidth: 2.5,
            throttle: 16,
            minDistance: 3,
            dotSize: function () {
                return (this.minWidth + this.maxWidth) / 2;
            },
        });

        // Desactivar inicialmente
        if (window.actaSignaturePad.off) {
            window.actaSignaturePad.off();
        }
        
        $("#acta-signature-status").text("Desactivado - Haga clic en 'Activar Firma'");
        window.isActaSigningActive = false;

    } catch (error) {
        alert("Error al inicializar la firma del acta: " + error.message);
    }
}

// Activar el modo de firma para el acta
function activateActaSignature() {
    if (!window.actaSignaturePad) {
        alert("Error: Sistema de firma del acta no inicializado");
        return;
    }
    
    window.isActaSigningActive = true;
    
    var canvas = document.getElementById("acta-signature-pad");
    if (!canvas) {
        alert("Error: Canvas del acta no encontrado");
        return;
    }
    
    // Configurar canvas activo
    canvas.style.cursor = "crosshair";
    canvas.style.background = "white";
    canvas.style.border = "2px solid #dc3545";
    canvas.style.pointerEvents = "auto";
    canvas.removeAttribute("disabled");
    
    // Cambiar interfaz
    $("#acta-signature-overlay").hide();
    $("#acta-activate-signature-btn").hide();
    $("#acta-signature-controls").show();
    
    $("#acta-signature-instructions").html(
        '<i class="fa fa-pencil text-success"></i> <strong>FIRMANDO:</strong> Mantenga presionado y dibuje. No salga del área hasta terminar.'
    );
    
    $("#acta-signature-status").text("✅ Activado - Listo para firmar");
    
    // Habilitar SignaturePad
    if (window.actaSignaturePad.on) {
        window.actaSignaturePad.on();
    }
    
    setupActaSignatureCapture(canvas);
    
    setTimeout(function() {
        $("#acta-signature-instructions").append('<br><small style="color: #28a745;"><i class="fa fa-check"></i> Sistema activado - Puede comenzar a firmar</small>');
    }, 1000);
}

// Configurar captura del mouse para el acta
function setupActaSignatureCapture(canvas) {
    canvas.removeEventListener('mousedown', handleActaMouseDown);
    document.removeEventListener('mousemove', handleActaMouseMove);
    document.removeEventListener('mouseup', handleActaMouseUp);
    
    canvas.addEventListener('mousedown', handleActaMouseDown);
    document.addEventListener('mousemove', handleActaMouseMove);
    document.addEventListener('mouseup', handleActaMouseUp);
    
    canvas.addEventListener('touchstart', handleActaTouchStart);
    document.addEventListener('touchend', handleActaTouchEnd);
    document.addEventListener('touchmove', handleActaTouchMove);
}

function handleActaMouseDown(e) {
    if (!window.isActaSigningActive) return;
    
    window.isActaMouseDown = true;
    document.body.style.cursor = 'crosshair';
    document.body.style.userSelect = 'none';
    e.preventDefault();
}

function handleActaMouseMove(e) {
    if (!window.isActaMouseDown || !window.isActaSigningActive) return;
    
    var canvas = document.getElementById("acta-signature-pad");
    var rect = canvas.getBoundingClientRect();
    var x = e.clientX;
    var y = e.clientY;
    
    if (x < rect.left || x > rect.right || y < rect.top || y > rect.bottom) {
        var constrainedX = Math.max(rect.left + 1, Math.min(rect.right - 1, x));
        var constrainedY = Math.max(rect.top + 1, Math.min(rect.bottom - 1, y));
        
        var syntheticEvent = new MouseEvent('mousemove', {
            clientX: constrainedX,
            clientY: constrainedY,
            bubbles: true
        });
        canvas.dispatchEvent(syntheticEvent);
    }
}

function handleActaMouseUp(e) {
    if (!window.isActaMouseDown) return;
    
    window.isActaMouseDown = false;
    document.body.style.cursor = 'default';
    document.body.style.userSelect = 'auto';
    
    if (window.actaSignaturePad && !window.actaSignaturePad.isEmpty()) {
        $("#acta-signature-instructions").html(
            '<i class="fa fa-check-circle text-success"></i> <strong>Trazo completado:</strong> Puede continuar firmando o presionar "Terminar" para revisar.'
        );
    }
}

function handleActaTouchStart(e) {
    if (!window.isActaSigningActive) return;
    window.isActaMouseDown = true;
    e.preventDefault();
}

function handleActaTouchEnd(e) {
    window.isActaMouseDown = false;
    e.preventDefault();
}

function handleActaTouchMove(e) {
    if (!window.isActaMouseDown || !window.isActaSigningActive) return;
    e.preventDefault();
}

// Terminar la firma del acta
function finishActaSignature() {
    if (!window.actaSignaturePad || window.actaSignaturePad.isEmpty()) {
        alert("No hay firma para revisar. Por favor dibuje su firma primero.");
        return;
    }
    
    window.isActaSigningActive = false;
    window.isActaMouseDown = false;
    
    var canvas = document.getElementById("acta-signature-pad");
    canvas.style.cursor = "not-allowed";
    canvas.style.border = "2px solid #6c757d";
    canvas.setAttribute("disabled", true);
    
    $("#acta-signature-status").text("Firma completada - En revisión");
    
    $("#acta-signature-instructions").html(
        '<i class="fa fa-eye text-info"></i> <strong>REVISIÓN:</strong> Examine su firma. Use "Vista Previa" para verla mejor o "Limpiar" para empezar de nuevo.'
    );
    
    $("#acta-signature-controls").hide();
    
    if ($("#acta-review-controls").length === 0) {
        var reviewButtons = 
            '<div class="row mt-3" id="acta-review-controls">' +
            '<div class="col-4">' +
            '<button type="button" class="btn btn-warning btn-sm btn-block" onclick="restartActaSignature()">' +
            '<i class="fa fa-refresh"></i> Firmar de Nuevo' +
            '</button>' +
            '</div>' +
            '<div class="col-4">' +
            '<button type="button" class="btn btn-info btn-sm btn-block" onclick="previewActaSignature()">' +
            '<i class="fa fa-search-plus"></i> Vista Previa' +
            '</button>' +
            '</div>' +
            '<div class="col-4">' +
            '<button type="button" class="btn btn-success btn-sm btn-block" onclick="approveActaSignature()">' +
            '<i class="fa fa-thumbs-up"></i> Aprobar' +
            '</button>' +
            '</div>' +
            '</div>';
        
        $("#acta-signature-controls").after(reviewButtons);
    }
    
    $("#acta-review-controls").show();
    
    setTimeout(function() {
        previewActaSignature();
    }, 500);
}

// Reiniciar firma del acta
function restartActaSignature() {
    clearActaSignature();
    
    $("#acta-activate-signature-btn").show();
    $("#acta-signature-controls").hide();
    $("#acta-review-controls").hide();
    
    var canvas = document.getElementById("acta-signature-pad");
    canvas.style.cursor = "not-allowed";
    canvas.style.background = "#f0f0f0";
    canvas.style.border = "2px solid #ccc";
    canvas.setAttribute("disabled", true);
    
    $("#acta-signature-overlay").show();
    
    $("#acta-signature-instructions").html(
        '<i class="fa fa-info-circle"></i> <strong>Instrucciones:</strong> 1) Active la firma 2) Dibuje sin salir del área 3) Revise el resultado'
    );
    
    $("#acta-signature-status").text("Desactivado");
    window.isActaSigningActive = false;
    window.isActaMouseDown = false;
}

// Aprobar firma del acta
function approveActaSignature() {
    if (!window.actaSignaturePad || window.actaSignaturePad.isEmpty()) {
        alert("No hay firma para aprobar.");
        return;
    }
    
    if (window.currentSignatureStep === 1) {
        // Guardar primera firma y pasar al siguiente paso
        window.entregaSignature = window.actaSignaturePad.toDataURL("image/png");
        
        $("#acta-signature-status").text("Primera firma completada - Preparando segunda firma");
        $("#acta-signature-instructions").html(
            '<i class="fa fa-check-circle text-success"></i> <strong>PRIMERA FIRMA COMPLETADA:</strong> Ahora procederá con la firma de quien recibe.'
        );
        
        setTimeout(function() {
            startSecondSignature();
        }, 2000);
        
    } else if (window.currentSignatureStep === 2) {
        // Guardar segunda firma y finalizar
        window.recibeSignature = window.actaSignaturePad.toDataURL("image/png");
        
        $("#acta-signature-status").text("Ambas firmas completadas - Listas para usar");
        $("#acta-signature-instructions").html(
            '<i class="fa fa-check-circle text-success"></i> <strong>PROCESO COMPLETADO:</strong> Ambas firmas han sido capturadas. Puede proceder a descargar el acta firmada.'
        );
        
        $("#acta-review-controls").hide();
        $('button[onclick*="downloadSignedActaPDF"]').removeClass('disabled').prop('disabled', false);
    }
}

// Nueva función para iniciar la segunda firma
function startSecondSignature() {
    window.currentSignatureStep = 2;
    
    // Limpiar el canvas para la segunda firma
    if (window.actaSignaturePad) {
        window.actaSignaturePad.clear();
    }
    
    // Actualizar la interfaz para la segunda firma
    $("#current-signature-label").text("Firma de quien recibe:");
    $("#signature-step-info").html(
        '<i class="fa fa-info-circle"></i> <strong>Paso 2 de 2:</strong><br>' +
        "• Firmando como: Quien recibe<br>" +
        "• Cliente/Responsable<br>" +
        "• Última firma requerida"
    );
    
    // Reiniciar el proceso de firma
    $("#acta-activate-signature-btn").show();
    $("#acta-signature-controls").hide();
    $("#acta-review-controls").hide();
    
    var canvas = document.getElementById("acta-signature-pad");
    canvas.style.cursor = "not-allowed";
    canvas.style.background = "#f0f0f0";
    canvas.style.border = "2px solid #ccc";
    canvas.setAttribute("disabled", true);
    
    $("#acta-signature-overlay").show();
    
    $("#acta-signature-instructions").html(
        '<i class="fa fa-info-circle"></i> <strong>Segunda firma:</strong> Active la firma para que quien recibe pueda firmar el documento.'
    );
    
    $("#acta-signature-status").text("Esperando segunda firma");
    window.isActaSigningActive = false;
    window.isActaMouseDown = false;
    
    // Cambiar el texto del botón de activación
    $("#acta-activate-signature-btn").html('<i class="fa fa-unlock"></i> ACTIVAR FIRMA DE QUIEN RECIBE');
}

// Limpiar firma del acta
function clearActaSignature() {
    if (window.actaSignaturePad) {
        window.actaSignaturePad.clear();
        $("#acta-signature-preview").hide();
        
        if (window.isActaSigningActive) {
            $("#acta-signature-status").text("Activado - Listo para firmar");
            $("#acta-signature-instructions").html(
                '<i class="fa fa-pencil"></i> <strong>FIRMANDO:</strong> Mantenga presionado y dibuje. No salga del área hasta terminar.'
            );
        } else {
            $("#acta-signature-status").text("Firma limpiada");
        }
    } else {
        alert("Error: Sistema de firma del acta no inicializado");
    }
}

// Vista previa de firma del acta
function previewActaSignature() {
    if (!window.actaSignaturePad || window.actaSignaturePad.isEmpty()) {
        alert("Por favor dibuje su firma primero");
        return;
    }

    try {
        var signatureData = window.actaSignaturePad.toDataURL("image/png");
        $("#acta-preview-signature").attr("src", signatureData);
        $("#acta-signature-preview").fadeIn(300);
    } catch (error) {
        alert("Error mostrando vista previa: " + error.message);
    }
}

// Descargar PDF del acta firmado
async function downloadSignedActaPDF(actaId) {
    // Validar que ambas firmas estén completas
    if (!window.entregaSignature || !window.recibeSignature) {
        alert("Debe completar ambas firmas antes de descargar el documento");
        return;
    }

    var downloadBtn = $('button[onclick*="downloadSignedActaPDF(' + actaId + ')"]');
    var originalText = downloadBtn.html();
    downloadBtn
        .html('<i class="fa fa-spinner fa-spin"></i> Procesando Acta...')
        .prop("disabled", true);

    try {
        var baseUrl = getBaseUrl();
        var pdfUrl = baseUrl + "/admin/actas/" + actaId + "/pdf";

        var response = await fetch(pdfUrl);
        if (!response.ok) {
            throw new Error("No se pudo cargar el PDF del acta (Error: " + response.status + ")");
        }

        var existingPdfBytes = await response.arrayBuffer();
        var pdfDoc = await PDFLib.PDFDocument.load(existingPdfBytes);
        var pages = pdfDoc.getPages();
        var firstPage = pages[1];
        var pageSize = firstPage.getSize();
        var width = pageSize.width;
        var height = pageSize.height;

        // Procesar PRIMERA firma (quien entrega)
        var entregaResponse = await fetch(window.entregaSignature);
        var entregaImageBytes = await entregaResponse.arrayBuffer();
        var entregaImage = await pdfDoc.embedPng(entregaImageBytes);

        // Procesar SEGUNDA firma (quien recibe)
        var recibeResponse = await fetch(window.recibeSignature);
        var recibeImageBytes = await recibeResponse.arrayBuffer();
        var recibeImage = await pdfDoc.embedPng(recibeImageBytes);

        // Posicionar firma de QUIEN ENTREGA (lado izquierdo)
        var signatureWidth = 100;
        var signatureHeight = 40;
        var entregaX = 85; // Lado izquierdo
        var entregaY = 729;

        firstPage.drawImage(entregaImage, {
            x: entregaX,
            y: entregaY,
            width: signatureWidth,
            height: signatureHeight,
        });

        // Posicionar firma de QUIEN RECIBE (lado derecho)
        var recibeX = width - signatureWidth - 90; // Lado derecho
        var recibeY = 729;

        firstPage.drawImage(recibeImage, {
            x: recibeX,
            y: recibeY,
            width: signatureWidth,
            height: signatureHeight,
        });

        // Agregar información de firmantes
        var fontSize = 7;
        
        // Texto para quien entrega
        firstPage.drawText("Entregado por SAROV", {
            x: entregaX,
            y: entregaY - 12,
            size: fontSize,
            color: PDFLib.rgb(0, 0, 0),
        });

        // Texto para quien recibe
        firstPage.drawText("Recibido por Cliente", {
            x: recibeX,
            y: recibeY - 12,
            size: fontSize,
            color: PDFLib.rgb(0, 0, 0),
        });

        // Descargar PDF
        var pdfBytes = await pdfDoc.save();
        var blob = new Blob([pdfBytes], { type: "application/pdf" });
        var url = URL.createObjectURL(blob);

        var a = document.createElement("a");
        a.href = url;
        a.download = "Acta_Entrega_" + actaId + "_Firmada_" + new Date().toISOString().split("T")[0] + ".pdf";
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);

        $("#actaPdfSignModal").modal("hide");

        setTimeout(function () {
            alert("Acta de entrega con ambas firmas descargada correctamente");
        }, 500);
    } catch (error) {
        alert("Error al procesar el acta: " + error.message);
    } finally {
        downloadBtn.html(originalText).prop("disabled", false);
    }
}

// Validar datos de firma del acta
function validateActaSignatureData() {
    if (!window.actaSignaturePad || window.actaSignaturePad.isEmpty()) {
        alert("Por favor dibuje su firma antes de continuar");
        return false;
    }
    return true;
}

// Inicialización
$(document).ready(function () {
    setTimeout(function () {
        checkActaDependencies();
    }, 1000);
});