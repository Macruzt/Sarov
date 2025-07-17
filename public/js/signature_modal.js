// Variables globales
window.signaturePad = null;
window.currentOrderId = null;
window.isSigningActive = false;
window.isMouseDown = false;

// Función principal para abrir el modal
async function openPDFModal(orderId) {
    // Validar orden ID
    if (!orderId || orderId === "[id]") {
        alert("Error: ID de orden no válido");
        return;
    }

    window.currentOrderId = orderId;

    // Usar valores por defecto sin hacer peticiones al backend
    window.signerName = "Usuario del Sistema";
    window.signerPosition = "Responsable";
    window.signerCompany = "SAROV";

    // Verificar dependencias
    if (!checkDependencies()) {
        return;
    }

    // Remover modal anterior si existe
    cleanupModal();

    try {
        // Crear y mostrar modal
        var modalHtml = createModalHTML(orderId);
        $("body").append(modalHtml);

        $("#pdfSignModal").modal({
            backdrop: "static",
            keyboard: false,
            show: true,
        });

        // Configurar eventos del modal
        setupModalEvents();
    } catch (error) {
        alert("Error al abrir el modal: " + error.message);
    }
}

// Verificar dependencias
function checkDependencies() {
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
function cleanupModal() {
    if ($("#pdfSignModal").length) {
        $("#pdfSignModal").modal("hide").remove();
    }
    $(".modal-backdrop").remove();
    $("body").removeClass("modal-open");
    window.signaturePad = null;
    window.isSigningActive = false;
    window.isMouseDown = false;
}

// Configurar eventos del modal
function setupModalEvents() {
    // Cuando el modal se muestra completamente
    $("#pdfSignModal").on("shown.bs.modal", function () {
        $("#debug-info").show();
        $("#signature-status").text("Inicializando...");

        setTimeout(function () {
            initSignaturePad();
        }, 500);
    });

    // Cuando el modal se oculta
    $("#pdfSignModal").on("hidden.bs.modal", function () {
        cleanupModal();
    });

    // Manejar errores del iframe
    $("#pdf-preview").on("error", function () {
        console.warn("Error cargando PDF preview");
        $(this)
            .parent()
            .html(
                '<div class="alert alert-warning text-center"><i class="fa fa-exclamation-triangle"></i><br>No se pudo cargar la vista previa del PDF</div>'
            );
    });
}

// Función para crear el HTML del modal CORREGIDO
function createModalHTML(orderId) {
    // CORREGIDO: Usar la ruta que muestra el PDF en línea
    var baseUrl = getBaseUrl();
    var pdfUrl = baseUrl + "/admin/orders/" + orderId + "/view-pdf"; // Cambio aquí

    var currentDate = new Date().toLocaleString("es-CO", {
        year: "numeric",
        month: "2-digit",
        day: "2-digit",
        hour: "2-digit",
        minute: "2-digit",
    });

    return (
        '<div class="modal fade" id="pdfSignModal" tabindex="-1" role="dialog">' +
        '<div class="modal-dialog modal-lg" style="width: 95%; max-width: 1200px;">' +
        '<div class="modal-content">' +
        '<div class="modal-header" style="background: linear-gradient(45deg, #28a745, #20c997); color: white;">' +
        '<h4 class="modal-title">' +
        '<i class="fa fa-file-signature"></i> ' +
        "Firmar Documento - Orden #" +
        orderId +
        "</h4>" +
        '<button type="button" class="close" data-dismiss="modal" style="color: white; opacity: 1;">' +
        "<span>&times;</span>" +
        "</button>" +
        "</div>" +
        '<div class="modal-body" style="padding: 20px; max-height: 85vh; overflow-y: auto;">' +
        "<!-- Indicador de carga -->" +
        '<div id="loading-indicator" class="text-center" style="padding: 20px;">' +
        '<i class="fa fa-spinner fa-spin fa-2x text-primary"></i>' +
        '<p class="mt-2">Cargando documento...</p>' +
        "</div>" +
        "<!-- Contenido principal -->" +
        '<div id="main-content" style="display: none;">' +
        '<div class="row">' +
        "<!-- Vista previa PDF -->" +
        '<div class="col-md-8">' +
        '<div class="card">' +
        '<div class="card-header">' +
        '<h6 class="mb-0"><i class="fa fa-file-pdf text-danger"></i> Vista Previa del Documento</h6>' +
        "</div>" +
        '<div class="card-body" style="padding: 10px;">' +
        '<div style="height: 500px; border: 2px solid #dee2e6; border-radius: 8px; background: #f8f9fa; position: relative; overflow: hidden;">' +
        '<iframe id="pdf-preview" ' +
        'src="' +
        pdfUrl +
        '" ' +
        'style="width: 100%; height: 100%; border: none;" ' +
        'onload="hideLoading()">' +
        "</iframe>" +
        "<!-- Vista previa de firma -->" +
        '<div id="signature-preview" style="position: absolute; bottom: 20px; right: 20px; ' +
        "background: rgba(255,255,255,0.98); border: 2px solid #28a745; border-radius: 8px; " +
        'padding: 10px; display: none; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">' +
        '<div style="text-align: center;">' +
        '<img id="preview-signature" style="max-width: 120px; max-height: 40px; display: block; margin: 0 auto;">' +
        '<div style="font-size: 10px; margin-top: 5px; color: #28a745; font-weight: 500;">' +
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
        '<h6 class="mb-0" style="color: #28a745;"><i class="fa fa-signature"></i> Firma Digital</h6>' +
        "</div>" +
        '<div class="card-body">' +
        '<div class="alert alert-info" style="font-size: 12px; padding: 10px; margin-bottom: 15px;">' +
        '<i class="fa fa-info-circle"></i> Haga clic en "ACTIVAR FIRMA" para comenzar' +
        "</div>" +
        "<!-- Fecha automática -->" +
        '<div class="form-group">' +
        '<label><strong><i class="fa fa-calendar"></i> Fecha y Hora:</strong></label>' +
        '<input type="text" class="form-control" id="sign-date" readonly value="' +
        currentDate +
        '" style="background: #f8f9fa;">' +
        "</div>" +
        "<!-- Área de firma -->" +
        '<div class="form-group">' +
        '<label><strong><i class="fa fa-signature"></i> Su Firma:</strong> <span class="text-danger">*</span></label>' +
        '<div id="signature-container" style="border: 2px dashed #28a745; background: #f8f9fa; border-radius: 8px; padding: 15px; text-align: center; position: relative;">' +
        '<canvas id="signature-pad" ' +
        'width="300" height="150" ' +
        'style="border: 2px solid #ccc; background: #f0f0f0; border-radius: 4px; cursor: not-allowed; display: block; margin: 0 auto; touch-action: none;" ' +
        "disabled>" +
        "</canvas>" +
        '<div id="signature-overlay" style="position: absolute; top: 15px; left: 15px; right: 15px; bottom: 30px; background: rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center; border-radius: 4px; z-index: 10;">' +
        '<span style="color: white; font-weight: bold; text-shadow: 1px 1px 2px rgba(0,0,0,0.7); text-align: center;">🔒 Hacer clic en<br>"ACTIVAR FIRMA"<br>para comenzar</span>' +
        "</div>" +
        '<small class="text-muted d-block mt-2" id="signature-instructions">' +
        '<i class="fa fa-info-circle"></i> <strong>Instrucciones:</strong> 1) Active la firma 2) Dibuje sin salir del área 3) Revise el resultado' +
        "</small>" +
        "<!-- Debug info -->" +
        '<div id="debug-info" style="display: none;">' +
        'Estado: <span id="signature-status">Desactivado</span>' +
        "</div>" +
        "</div>" +
        "</div>" +
        "<!-- Botones de control -->" +
        '<div class="row mb-3">' +
        '<div class="col-12">' +
        '<button type="button" id="activate-signature-btn" class="btn btn-success btn-lg btn-block" onclick="activateSignature()" style="font-weight: bold;">' +
        '<i class="fa fa-unlock"></i> ACTIVAR FIRMA - Haga clic aquí para comenzar' +
        "</button>" +
        "</div>" +
        "</div>" +
        '<div class="row" id="signature-controls" style="display: none;">' +
        '<div class="col-4">' +
        '<button type="button" class="btn btn-warning btn-sm btn-block" onclick="clearSignature()" title="Limpiar firma">' +
        '<i class="fa fa-eraser"></i> Limpiar' +
        "</button>" +
        "</div>" +
        '<div class="col-4">' +
        '<button type="button" class="btn btn-info btn-sm btn-block" onclick="previewSignature()" title="Ver vista previa">' +
        '<i class="fa fa-eye"></i> Vista Previa' +
        "</button>" +
        "</div>" +
        '<div class="col-4">' +
        '<button type="button" class="btn btn-danger btn-sm btn-block" onclick="finishSignature()" title="Terminar de firmar">' +
        '<i class="fa fa-check"></i> Terminar' +
        "</button>" +
        "</div>" +
        "</div>" +
        "<!-- Información automática -->" +
        '<div class="mt-3">' +
        '<small class="text-muted">' +
        '<i class="fa fa-info-circle"></i> <strong>Información automática:</strong><br>' +
        "• Nombre: Usuario del Sistema<br>" +
        "• Empresa: SAROV<br>" +
        "• Responsable de la orden" +
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
        '<button type="button" class="btn btn-success btn-lg" onclick="downloadSignedPDF(' +
        orderId +
        ')" style="margin-left: 10px;">' +
        '<i class="fa fa-download"></i> Firmar y Descargar PDF' +
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
function hideLoading() {
    setTimeout(function () {
        $("#loading-indicator").fadeOut(300, function () {
            $("#main-content").fadeIn(300);
        });
    }, 500);
}

// Inicializar SignaturePad
function initSignaturePad() {
    var canvas = document.getElementById("signature-pad");
    if (!canvas) {
        alert("Error: No se encontró el área de firma");
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
        window.signaturePad = new SignaturePad(canvas, {
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
        if (window.signaturePad.off) {
            window.signaturePad.off();
        }
        
        $("#signature-status").text("Desactivado - Haga clic en 'Activar Firma'");
        window.isSigningActive = false;

    } catch (error) {
        alert("Error al inicializar la firma: " + error.message);
    }
}

// Activar el modo de firma
function activateSignature() {
    if (!window.signaturePad) {
        alert("Error: Sistema de firma no inicializado");
        return;
    }
    
    window.isSigningActive = true;
    
    var canvas = document.getElementById("signature-pad");
    if (!canvas) {
        alert("Error: Canvas no encontrado");
        return;
    }
    
    // Configurar canvas activo
    canvas.style.cursor = "crosshair";
    canvas.style.background = "white";
    canvas.style.border = "2px solid #28a745";
    canvas.style.pointerEvents = "auto";
    canvas.removeAttribute("disabled");
    
    // Cambiar interfaz
    $("#signature-overlay").hide();
    $("#activate-signature-btn").hide();
    $("#signature-controls").show();
    
    $("#signature-instructions").html(
        '<i class="fa fa-pencil text-success"></i> <strong>FIRMANDO:</strong> Mantenga presionado y dibuje. No salga del área hasta terminar.'
    );
    
    $("#signature-status").text("✅ Activado - Listo para firmar");
    
    // Habilitar SignaturePad
    if (window.signaturePad.on) {
        window.signaturePad.on();
    }
    
    setupSignatureCapture(canvas);
    
    setTimeout(function() {
        $("#signature-instructions").append('<br><small style="color: #28a745;"><i class="fa fa-check"></i> Sistema activado - Puede comenzar a firmar</small>');
    }, 1000);
}

// Configurar captura del mouse
function setupSignatureCapture(canvas) {
    canvas.removeEventListener('mousedown', handleMouseDown);
    document.removeEventListener('mousemove', handleMouseMove);
    document.removeEventListener('mouseup', handleMouseUp);
    
    canvas.addEventListener('mousedown', handleMouseDown);
    document.addEventListener('mousemove', handleMouseMove);
    document.addEventListener('mouseup', handleMouseUp);
    
    canvas.addEventListener('touchstart', handleTouchStart);
    document.addEventListener('touchend', handleTouchEnd);
    document.addEventListener('touchmove', handleTouchMove);
}

function handleMouseDown(e) {
    if (!window.isSigningActive) return;
    
    window.isMouseDown = true;
    document.body.style.cursor = 'crosshair';
    document.body.style.userSelect = 'none';
    e.preventDefault();
}

function handleMouseMove(e) {
    if (!window.isMouseDown || !window.isSigningActive) return;
    
    var canvas = document.getElementById("signature-pad");
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

function handleMouseUp(e) {
    if (!window.isMouseDown) return;
    
    window.isMouseDown = false;
    document.body.style.cursor = 'default';
    document.body.style.userSelect = 'auto';
    
    if (window.signaturePad && !window.signaturePad.isEmpty()) {
        $("#signature-instructions").html(
            '<i class="fa fa-check-circle text-success"></i> <strong>Trazo completado:</strong> Puede continuar firmando o presionar "Terminar" para revisar.'
        );
    }
}

function handleTouchStart(e) {
    if (!window.isSigningActive) return;
    window.isMouseDown = true;
    e.preventDefault();
}

function handleTouchEnd(e) {
    window.isMouseDown = false;
    e.preventDefault();
}

function handleTouchMove(e) {
    if (!window.isMouseDown || !window.isSigningActive) return;
    e.preventDefault();
}

// Terminar la firma
function finishSignature() {
    if (!window.signaturePad || window.signaturePad.isEmpty()) {
        alert("No hay firma para revisar. Por favor dibuje su firma primero.");
        return;
    }
    
    window.isSigningActive = false;
    window.isMouseDown = false;
    
    var canvas = document.getElementById("signature-pad");
    canvas.style.cursor = "not-allowed";
    canvas.style.border = "2px solid #6c757d";
    canvas.setAttribute("disabled", true);
    
    $("#signature-status").text("Firma completada - En revisión");
    
    $("#signature-instructions").html(
        '<i class="fa fa-eye text-info"></i> <strong>REVISIÓN:</strong> Examine su firma. Use "Vista Previa" para verla mejor o "Limpiar" para empezar de nuevo.'
    );
    
    $("#signature-controls").hide();
    
    if ($("#review-controls").length === 0) {
        var reviewButtons = 
            '<div class="row mt-3" id="review-controls">' +
            '<div class="col-4">' +
            '<button type="button" class="btn btn-warning btn-sm btn-block" onclick="restartSignature()">' +
            '<i class="fa fa-refresh"></i> Firmar de Nuevo' +
            '</button>' +
            '</div>' +
            '<div class="col-4">' +
            '<button type="button" class="btn btn-info btn-sm btn-block" onclick="previewSignature()">' +
            '<i class="fa fa-search-plus"></i> Vista Previa' +
            '</button>' +
            '</div>' +
            '<div class="col-4">' +
            '<button type="button" class="btn btn-success btn-sm btn-block" onclick="approveSignature()">' +
            '<i class="fa fa-thumbs-up"></i> Aprobar' +
            '</button>' +
            '</div>' +
            '</div>';
        
        $("#signature-controls").after(reviewButtons);
    }
    
    $("#review-controls").show();
    
    setTimeout(function() {
        previewSignature();
    }, 500);
}

// Reiniciar firma
function restartSignature() {
    clearSignature();
    
    $("#activate-signature-btn").show();
    $("#signature-controls").hide();
    $("#review-controls").hide();
    
    var canvas = document.getElementById("signature-pad");
    canvas.style.cursor = "not-allowed";
    canvas.style.background = "#f0f0f0";
    canvas.style.border = "2px solid #ccc";
    canvas.setAttribute("disabled", true);
    
    $("#signature-overlay").show();
    
    $("#signature-instructions").html(
        '<i class="fa fa-info-circle"></i> <strong>Instrucciones:</strong> 1) Active la firma 2) Dibuje sin salir del área 3) Revise el resultado'
    );
    
    $("#signature-status").text("Desactivado");
    window.isSigningActive = false;
    window.isMouseDown = false;
}

// Aprobar firma
function approveSignature() {
    if (!window.signaturePad || window.signaturePad.isEmpty()) {
        alert("No hay firma para aprobar.");
        return;
    }
    
    $("#signature-status").text("Firma aprobada - Lista para usar");
    $("#signature-instructions").html(
        '<i class="fa fa-check-circle text-success"></i> <strong>APROBADA:</strong> Su firma ha sido aceptada. Puede proceder a firmar el documento.'
    );
    
    $("#review-controls").hide();
    
    $('button[onclick*="downloadSignedPDF"]').removeClass('disabled').prop('disabled', false);
}

// Limpiar firma
function clearSignature() {
    if (window.signaturePad) {
        window.signaturePad.clear();
        $("#signature-preview").hide();
        
        if (window.isSigningActive) {
            $("#signature-status").text("Activado - Listo para firmar");
            $("#signature-instructions").html(
                '<i class="fa fa-pencil"></i> <strong>FIRMANDO:</strong> Mantenga presionado y dibuje. No salga del área hasta terminar.'
            );
        } else {
            $("#signature-status").text("Firma limpiada");
        }
    } else {
        alert("Error: Sistema de firma no inicializado");
    }
}

// Vista previa de firma
function previewSignature() {
    if (!window.signaturePad || window.signaturePad.isEmpty()) {
        alert("Por favor dibuje su firma primero");
        return;
    }

    try {
        var signatureData = window.signaturePad.toDataURL("image/png");
        $("#preview-signature").attr("src", signatureData);
        $("#signature-preview").fadeIn(300);
    } catch (error) {
        alert("Error mostrando vista previa: " + error.message);
    }
}

// ELIMINADO: saveSignature() ya que no necesitamos guardar en el servidor

// Descargar PDF firmado SIMPLIFICADO
async function downloadSignedPDF(orderId) {
    if (!validateSignatureData()) {
        return;
    }

    var downloadBtn = $('button[onclick*="downloadSignedPDF(' + orderId + ')"]');
    var originalText = downloadBtn.html();
    downloadBtn
        .html('<i class="fa fa-spinner fa-spin"></i> Procesando PDF...')
        .prop("disabled", true);

    try {
        // CORREGIDO: Usar la ruta para descargar PDF al final
        var baseUrl = getBaseUrl();
        var pdfUrl = baseUrl + "/admin/orders/" + orderId + "/pdf";

        var response = await fetch(pdfUrl);
        if (!response.ok) {
            throw new Error("No se pudo cargar el PDF original (Error: " + response.status + ")");
        }

        var existingPdfBytes = await response.arrayBuffer();
        var pdfDoc = await PDFLib.PDFDocument.load(existingPdfBytes);
        var pages = pdfDoc.getPages();
        var firstPage = pages[0];
        var pageSize = firstPage.getSize();
        var width = pageSize.width;
        var height = pageSize.height;

        // Procesar firma
        var signatureDataURL = window.signaturePad.toDataURL("image/png");
        var signatureResponse = await fetch(signatureDataURL);
        var signatureImageBytes = await signatureResponse.arrayBuffer();
        var signatureImage = await pdfDoc.embedPng(signatureImageBytes);

        // Posicionar firma
        var signatureWidth = 140;
        var signatureHeight = 60;
        var signatureX = width - signatureWidth - 40;
        var signatureY = 80;

        firstPage.drawImage(signatureImage, {
            x: signatureX,
            y: signatureY,
            width: signatureWidth,
            height: signatureHeight,
        });

        // Agregar información
        var fontSize = 9;
        var textY = signatureY - 15;

        firstPage.drawText(window.signerName || "Usuario del Sistema", {
            x: signatureX,
            y: textY - 12,
            size: fontSize,
            color: PDFLib.rgb(0, 0, 0),
        });

        firstPage.drawText("Empresa: " + (window.signerCompany || "SAROV"), {
            x: signatureX,
            y: textY - 24,
            size: fontSize - 1,
            color: PDFLib.rgb(0.2, 0.2, 0.2),
        });

        firstPage.drawText(window.signerPosition || "Responsable", {
            x: signatureX,
            y: textY - 18,
            size: fontSize - 1,
            color: PDFLib.rgb(0.2, 0.2, 0.2),
        });

        // Descargar PDF
        var pdfBytes = await pdfDoc.save();
        var blob = new Blob([pdfBytes], { type: "application/pdf" });
        var url = URL.createObjectURL(blob);

        var a = document.createElement("a");
        a.href = url;
        a.download = "Orden_" + orderId + "_Firmada_" + new Date().toISOString().split("T")[0] + ".pdf";
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);

        $("#pdfSignModal").modal("hide");

        setTimeout(function () {
            alert("PDF firmado descargado correctamente");
        }, 500);
    } catch (error) {
        alert("Error al procesar el PDF: " + error.message);
    } finally {
        downloadBtn.html(originalText).prop("disabled", false);
    }
}

// Validar datos de firma
function validateSignatureData() {
    if (!window.signaturePad || window.signaturePad.isEmpty()) {
        alert("Por favor dibuje su firma antes de continuar");
        return false;
    }
    return true;
}

// Obtener token CSRF
function getCSRFToken() {
    var token = $('meta[name="csrf-token"]').attr("content");
    if (!token) {
        token = $('input[name="_token"]').val();
    }
    return token || "";
}

// Inicialización
$(document).ready(function () {
    setTimeout(function () {
        checkDependencies();
    }, 1000);
});