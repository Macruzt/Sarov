<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>CRUD Facturas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .table-container { max-height: 600px; overflow-y: auto; }
        .btn-sm { padding: 0.25rem 0.5rem; font-size: 0.875rem; }
        .badge { font-size: 0.75rem; }
        .form-floating { margin-bottom: 1rem; }
        .modal-xl { max-width: 1200px; }
        .status-badge { white-space: nowrap; }
        .items-section { border: 1px solid #dee2e6; border-radius: 0.25rem; padding: 1rem; margin-top: 1rem; background: #f8f9fa; }
        .item-row { background: white; padding: 0.75rem; margin-bottom: 0.5rem; border-radius: 0.25rem; border: 1px solid #e9ecef; }
    </style>
</head>
<body>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-file-invoice me-2"></i>Gestión de Facturas</h2>
                    <div>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#facturaModal" onclick="openCreateModal()">
                            <i class="fas fa-plus"></i> Nueva Factura
                        </button>
                        <button class="btn btn-info" onclick="loadFacturas()">
                            <i class="fas fa-sync"></i> Actualizar
                        </button>
                    </div>
                </div>

                <!-- Filtros -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <input type="text" id="searchInput" class="form-control" placeholder="Buscar facturas...">
                            </div>
                            <div class="col-md-3">
                                <select id="estadoFilter" class="form-select">
                                    <option value="">Todos los estados</option>
                                    <option value="pendiente">Pendientes</option>
                                    <option value="pagada">Pagadas</option>
                                    <option value="vencida">Vencidas</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-outline-primary" onclick="applyFilters()">
                                    <i class="fas fa-search"></i> Buscar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Estadísticas -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <h5 id="totalFacturas">0</h5>
                                <small>Total Facturas</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body">
                                <h5 id="totalPendientes">0</h5>
                                <small>Pendientes</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <h5 id="totalPagadas">0</h5>
                                <small>Pagadas</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-danger text-white">
                            <div class="card-body">
                                <h5 id="totalVencidas">0</h5>
                                <small>Vencidas</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabla -->
                <div class="card">
                    <div class="card-body">
                        <div class="table-container">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark sticky-top">
                                    <tr>
                                        <th>Número</th>
                                        <th>Proveedor</th>
                                        <th>Emisión</th>
                                        <th>Vencimiento</th>
                                        <th>Total</th>
                                        <th>Estado</th>
                                        <th width="200">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="facturasTableBody">
                                    <tr>
                                        <td colspan="7" class="text-center">
                                            <div class="spinner-border" role="status">
                                                <span class="visually-hidden">Cargando...</span>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Paginación -->
                        <nav class="mt-3">
                            <ul class="pagination justify-content-center" id="pagination"></ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Formulario -->
    <div class="modal fade" id="facturaModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Nueva Factura</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="facturaForm">
                    <div class="modal-body">
                        <!-- Datos de la Factura -->
                        <h6 class="mb-3"><i class="fas fa-file-invoice me-2"></i>Datos de la Factura</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="numero_factura" name="numero_factura" required>
                                    <label>Número de Factura *</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <select class="form-select" id="supplier_id" name="supplier_id" required>
                                        <option value="">Seleccionar...</option>
                                    </select>
                                    <label>Proveedor *</label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="date" class="form-control" id="fecha_emision" name="fecha_emision" required>
                                    <label>Fecha Emisión *</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="date" class="form-control" id="fecha_vencimiento" name="fecha_vencimiento" required>
                                    <label>Fecha Vencimiento *</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-floating">
                            <textarea class="form-control" id="concepto_detalle" name="concepto_detalle" style="height: 80px" required></textarea>
                            <label>Concepto/Detalle *</label>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <select class="form-select" id="forma_pago" name="forma_pago" required>
                                        <option value="">Seleccionar...</option>
                                        <option value="efectivo">Efectivo</option>
                                        <option value="transferencia">Transferencia</option>
                                        <option value="cheque">Cheque</option>
                                        <option value="tarjeta_credito">Tarjeta de Crédito</option>
                                    </select>
                                    <label>Forma de Pago *</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <select class="form-select" id="estado" name="estado">
                                        <option value="pendiente">Pendiente</option>
                                        <option value="pagada">Pagada</option>
                                        <option value="vencida">Vencida</option>
                                    </select>
                                    <label>Estado</label>
                                </div>
                            </div>
                        </div>

                        <!-- Sección de Items -->
                        <div class="items-section mt-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0"><i class="fas fa-boxes me-2"></i>Items de la Factura</h6>
                                <button type="button" class="btn btn-sm btn-success" onclick="addItemRow()">
                                    <i class="fas fa-plus"></i> Agregar Item
                                </button>
                            </div>
                            
                            <div id="itemsContainer">
                                <!-- Los items se agregarán aquí dinámicamente -->
                            </div>
                        </div>

                        <!-- Totales -->
                        <div class="row mt-3">
                            <div class="col-md-4">
                                <div class="form-floating">
                                    <input type="number" class="form-control bg-light" id="subtotal" name="subtotal" step="0.01" readonly>
                                    <label>Subtotal</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-floating">
                                    <input type="number" class="form-control bg-light" id="total_iva" name="total_iva" step="0.01" readonly>
                                    <label>IVA Total</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-floating">
                                    <input type="number" class="form-control bg-light fw-bold" id="total" name="total" step="0.01" readonly>
                                    <label>Total *</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="fas fa-save"></i> Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        let currentFacturaId = null;
        let currentPage = 1;
        let itemCounter = 0;
        let products = [];

        // Cargar productos
function loadProducts() {
    $.ajax({
        url: '/api/products',
        method: 'GET',
        success: function(response) {
            if (response.success && response.data) {
                products = response.data;
                console.log('Productos cargados:', products.length);
            }
        },
        error: function(xhr) {
            console.error('Error al cargar productos:', xhr.responseText);
            products = [];
        }
    });
}
       $(document).ready(function() {

  
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    loadFacturas();
    loadStats();
    loadSuppliers();
    loadProducts(); // AGREGAR ESTA LÍNEA
    
    $('#searchInput').on('keypress', function(e) {
        if (e.which === 13) applyFilters();
    });
});
        // ==================== GESTIÓN DE ITEMS ====================
        
 function addItemRow(existingItem = null) {
    itemCounter++;
    const itemHtml = `
        <div class="item-row" id="item-${itemCounter}" ${existingItem && existingItem.product_id ? `data-product-id="${existingItem.product_id}"` : ''}>
            <div class="row g-2 mb-2">
                <div class="col-md-3">
                    <select class="form-select form-select-sm" id="product-select-${itemCounter}" 
                            onchange="fillProductData(${itemCounter})">
                        <option value="">-- Buscar producto existente --</option>
                        ${products.map(p => `<option value="${p.id}">${p.detalle}</option>`).join('')}
                    </select>
                </div>
                <div class="col-md-9">
                    <small class="text-muted">O ingresa manualmente:</small>
                </div>
            </div>
            <div class="row g-2">
                <div class="col-md-3">
                    <input type="text" class="form-control form-control-sm" 
                           placeholder="Detalle del producto *" 
                           id="detalle-${itemCounter}" required>
                </div>
                <div class="col-md-2">
                    <select class="form-select form-select-sm" id="unidad-${itemCounter}">
                        <option value="Unidad">Unidad</option>
                        <option value="Kilogramo">Kilogramo</option>
                        <option value="Litro">Litro</option>
                        <option value="Metro">Metro</option>
                        <option value="Caja">Caja</option>
                        <option value="Galon">Galón</option>
                        <option value="Libra">Libra</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <input type="number" class="form-control form-control-sm" 
                           placeholder="Cant" value="1" min="1"
                           id="cantidad-${itemCounter}" onchange="calculateItemTotal(${itemCounter})">
                </div>
                <div class="col-md-2">
                    <input type="number" class="form-control form-control-sm" 
                           placeholder="Valor Unit" step="0.01" min="0"
                           id="valor-${itemCounter}" onchange="calculateItemTotal(${itemCounter})">
                </div>
                <div class="col-md-1">
                    <input type="number" class="form-control form-control-sm" 
                           placeholder="IVA%" value="19" min="0" max="100"
                           id="iva-${itemCounter}" onchange="calculateItemTotal(${itemCounter})">
                </div>
                <div class="col-md-2">
                    <input type="text" class="form-control form-control-sm bg-light" 
                           id="total-item-${itemCounter}" readonly placeholder="Total">
                    <input type="hidden" id="product-id-${itemCounter}">
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-sm btn-danger w-100" 
                            onclick="removeItemRow(${itemCounter})">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    `;
    
    $('#itemsContainer').append(itemHtml);
    
    // Si viene un item existente, llenar los campos
    if (existingItem) {
        $(`#detalle-${itemCounter}`).val(existingItem.detalle);
        $(`#unidad-${itemCounter}`).val(existingItem.unidad_medida || existingItem.unidadMedida);
        $(`#cantidad-${itemCounter}`).val(existingItem.cantidad);
        $(`#valor-${itemCounter}`).val(existingItem.valor_unitario || existingItem.valorUnitario);
        $(`#iva-${itemCounter}`).val(existingItem.porcentaje_iva || existingItem.porcentajeIva);
        
        // CRÍTICO: Llenar product_id
        if (existingItem.product_id) {
            $(`#product-id-${itemCounter}`).val(existingItem.product_id);
            $(`#product-select-${itemCounter}`).val(existingItem.product_id);
        }
        
        calculateItemTotal(itemCounter);
    }
}

        function removeItemRow(id) {
            $(`#item-${id}`).remove();
            calculateFacturaTotals();
        }

        function calculateItemTotal(id) {
            const cantidad = parseFloat($(`#cantidad-${id}`).val()) || 0;
            const valorUnitario = parseFloat($(`#valor-${id}`).val()) || 0;
            const porcentajeIva = parseFloat($(`#iva-${id}`).val()) || 0;
            
            const valorIva = valorUnitario * (porcentajeIva / 100);
            const valorConIva = valorUnitario + valorIva;
            const totalItem = valorConIva * cantidad;
            
            $(`#total-item-${id}`).val('$' + totalItem.toFixed(2));
            calculateFacturaTotals();
        }

        function calculateFacturaTotals() {
            let subtotal = 0;
            let totalIva = 0;
            
            $('.item-row').each(function() {
                const id = $(this).attr('id').split('-')[1];
                const cantidad = parseFloat($(`#cantidad-${id}`).val()) || 0;
                const valorUnitario = parseFloat($(`#valor-${id}`).val()) || 0;
                const porcentajeIva = parseFloat($(`#iva-${id}`).val()) || 0;
                
                const subtotalItem = valorUnitario * cantidad;
                const ivaItem = (valorUnitario * (porcentajeIva / 100)) * cantidad;
                
                subtotal += subtotalItem;
                totalIva += ivaItem;
            });
            
            const total = subtotal + totalIva;
            
            $('#subtotal').val(subtotal.toFixed(2));
            $('#total_iva').val(totalIva.toFixed(2));
            $('#total').val(total.toFixed(2));
        }

        function collectFormItems() {
    const items = [];
    
    $('.item-row').each(function() {
        const rowId = $(this).attr('id').split('-')[1];
        const dbItemId = $(this).data('item-id'); // ID del item en BD
        
        const item = {
            detalle: $(`#detalle-${rowId}`).val(),
            unidad_medida: $(`#unidad-${rowId}`).val(),
            cantidad: parseInt($(`#cantidad-${rowId}`).val()) || 1,
            valor_unitario: parseFloat($(`#valor-${rowId}`).val()) || 0,
            porcentaje_iva: parseFloat($(`#iva-${rowId}`).val()) || 0
        };
        
        // Incluir ID del item si es una edición
        if (dbItemId) {
            item.id = dbItemId;
        }
        
        // Incluir product_id si existe
        const productId = $(`#product-id-${rowId}`).val();
        if (productId && productId !== '') {
            item.product_id = parseInt(productId);
        }
        
        if (item.detalle && item.valor_unitario > 0) {
            items.push(item);
        }
    });
    
    return items;
}
        // ==================== CRUD FACTURAS ====================

        function loadFacturas(page = 1) {
            currentPage = page;
            const search = $('#searchInput').val();
            const estado = $('#estadoFilter').val();
            
            showLoading();
            
            $.get('/api/facturas', {
                page: page,
                search: search,
                estado: estado,
                per_page: 10
            }).done(function(response) {
                if (response.success) {
                    renderFacturas(response.data);
                    renderPagination(response.pagination);
                }
            }).fail(function() {
                showAlert('Error de conexión', 'error');
            });
        }

        function loadStats() {
            $.get('/api/facturas/stats').done(function(response) {
                if (response.success) {
                    const data = response.data;
                    $('#totalFacturas').text(data.total_facturas || 0);
                    $('#totalPendientes').text(data.pendientes || 0);
                    $('#totalPagadas').text(data.pagadas || 0);
                    $('#totalVencidas').text(data.vencidas || 0);
                }
            });
        }

        function loadSuppliers() {
            $.ajax({
                url: '/api/suppliers',
                method: 'GET',
                success: function(response) {
                    if (response.success && response.data) {
                        const select = $('#supplier_id');
                        select.empty().append('<option value="">Seleccionar...</option>');
                        
                        response.data.forEach(function(supplier) {
                            const nombre = supplier.nombreRazonSocial || supplier.nombre_razon_social || 'Sin nombre';
                            select.append(`<option value="${supplier.id}">${nombre}</option>`);
                        });
                    }
                }
            });
        }

        function renderFacturas(facturas) {
            const tbody = $('#facturasTableBody');
            tbody.empty();

            if (facturas.length === 0) {
                tbody.append('<tr><td colspan="7" class="text-center text-muted">No hay facturas para mostrar</td></tr>');
                return;
            }

            facturas.forEach(function(factura) {
                const numeroFactura = factura.numero_factura || factura.numeroFactura;
                const supplierName = factura.supplier?.nombre_razon_social || factura.supplier?.nombreRazonSocial || 'N/A';
                const fechaEmision = factura.fecha_emision || factura.fechaEmision;
                const fechaVencimiento = factura.fecha_vencimiento || factura.fechaVencimiento;
                
                const row = `
                    <tr>
                        <td><strong>${numeroFactura}</strong></td>
                        <td>${supplierName}</td>
                        <td>${formatDate(fechaEmision)}</td>
                        <td>${formatDate(fechaVencimiento)}</td>
                        <td><strong>$${formatNumber(factura.total)}</strong></td>
                        <td>
                            <span class="badge ${getStatusBadge(factura.estado)} status-badge">
                                ${capitalizeFirst(factura.estado)}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-info" onclick="viewFactura(${factura.id})" title="Ver">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-outline-warning" onclick="editFactura(${factura.id})" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-outline-success" onclick="changeStatus(${factura.id}, 'pagada')" title="Marcar Pagada">
                                    <i class="fas fa-check"></i>
                                </button>
                                <button class="btn btn-outline-danger" onclick="deleteFactura(${factura.id})" title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
                tbody.append(row);
            });
        }

        function renderPagination(pagination) {
            const paginationEl = $('#pagination');
            paginationEl.empty();

            if (pagination.last_page <= 1) return;

            if (pagination.current_page > 1) {
                paginationEl.append(`
                    <li class="page-item">
                        <a class="page-link" href="javascript:void(0)" onclick="loadFacturas(${pagination.current_page - 1})">Anterior</a>
                    </li>
                `);
            }

            for (let i = 1; i <= pagination.last_page; i++) {
                const active = i === pagination.current_page ? 'active' : '';
                paginationEl.append(`
                    <li class="page-item ${active}">
                        <a class="page-link" href="javascript:void(0)" onclick="loadFacturas(${i})">${i}</a>
                    </li>
                `);
            }

            if (pagination.current_page < pagination.last_page) {
                paginationEl.append(`
                    <li class="page-item">
                        <a class="page-link" href="javascript:void(0)" onclick="loadFacturas(${pagination.current_page + 1})">Siguiente</a>
                    </li>
                `);
            }
        }

        function openCreateModal() {
            currentFacturaId = null;
            $('#modalTitle').text('Nueva Factura');
            $('#facturaForm')[0].reset();
            $('#fecha_emision').val(new Date().toISOString().split('T')[0]);
            $('#itemsContainer').empty();
            itemCounter = 0;
            addItemRow();
        }

        function viewFactura(id) {
            $.get(`/api/facturas/${id}`).done(function(response) {
                if (response.success) {
                    const factura = response.data;
                    
                    let itemsHtml = '';
                    if (factura.items && factura.items.length > 0) {
                        itemsHtml = `
                            <h6 class="mt-3"><i class="fas fa-boxes me-2"></i>Items de la Factura:</h6>
                            <table class="table table-sm table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Detalle</th>
                                        <th>Cantidad</th>
                                        <th>Valor Unit.</th>
                                        <th>IVA</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                        `;
                        
                        factura.items.forEach(item => {
                            const valorUnitario = item.valor_unitario || item.valorUnitario;
                            const porcentajeIva = item.porcentaje_iva || item.porcentajeIva;
                            const unidadMedida = item.unidad_medida || item.unidadMedida;
                            const valorConIva = valorUnitario * (1 + porcentajeIva / 100);
                            const totalItem = valorConIva * item.cantidad;
                            
                            itemsHtml += `
                                <tr>
                                    <td>${item.detalle}</td>
                                    <td>${item.cantidad} ${unidadMedida}</td>
                                    <td>$${formatNumber(valorUnitario)}</td>
                                    <td>${porcentajeIva}%</td>
                                    <td><strong>$${formatNumber(totalItem)}</strong></td>
                                </tr>
                            `;
                        });
                        
                        itemsHtml += '</tbody></table>';
                    }
                    
                    const info = `
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Número:</strong> ${factura.numero_factura || factura.numeroFactura}<br>
                                <strong>Proveedor:</strong> ${factura.supplier?.nombre_razon_social || factura.supplier?.nombreRazonSocial || 'N/A'}<br>
                                <strong>Emisión:</strong> ${formatDate(factura.fecha_emision || factura.fechaEmision)}<br>
                                <strong>Vencimiento:</strong> ${formatDate(factura.fecha_vencimiento || factura.fechaVencimiento)}<br>
                            </div>
                            <div class="col-md-6">
                                <strong>Forma Pago:</strong> ${factura.forma_pago || factura.formaPago}<br>
                                <strong>Estado:</strong> <span class="badge ${getStatusBadge(factura.estado)}">${capitalizeFirst(factura.estado)}</span><br>
                                <strong>Subtotal:</strong> $${formatNumber(factura.subtotal)}<br>
                                <strong>IVA:</strong> $${formatNumber(factura.total_iva || factura.totalIva)}<br>
                                <strong>Total:</strong> <h4 class="text-primary mt-2">$${formatNumber(factura.total)}</h4>
                            </div>
                        </div>
                        <hr>
                        <strong>Concepto:</strong><br>
                        <div class="bg-light p-3 rounded">${factura.concepto_detalle || factura.conceptoDetalle}</div>
                        ${itemsHtml}
                    `;
                    
                    showAlert(info, 'info', 'Detalle de Factura', true);
                }
            });
        }

        function editFactura(id) {
    $.get(`/api/facturas/${id}`).done(function(response) {
        if (response.success) {
            const factura = response.data;
            currentFacturaId = id;
            
            $('#modalTitle').text('Editar Factura');
            $('#numero_factura').val(factura.numero_factura || factura.numeroFactura);
            $('#supplier_id').val(factura.supplier_id || factura.supplierId);
            $('#fecha_emision').val(factura.fecha_emision || factura.fechaEmision);
            $('#fecha_vencimiento').val(factura.fecha_vencimiento || factura.fechaVencimiento);
            $('#concepto_detalle').val(factura.concepto_detalle || factura.conceptoDetalle);
            $('#forma_pago').val(factura.forma_pago || factura.formaPago);
            $('#estado').val(factura.estado);
            
            $('#itemsContainer').empty();
            itemCounter = 0;
            
            if (factura.items && factura.items.length > 0) {
                factura.items.forEach(item => {
                    addItemRow();
                    
                    $(`#detalle-${itemCounter}`).val(item.detalle);
                    $(`#unidad-${itemCounter}`).val(item.unidad_medida || item.unidadMedida);
                    $(`#cantidad-${itemCounter}`).val(item.cantidad);
                    $(`#valor-${itemCounter}`).val(item.valor_unitario || item.valorUnitario);
                    $(`#iva-${itemCounter}`).val(item.porcentaje_iva || item.porcentajeIva);
                    
                    // ⭐ CRÍTICO: Guardar el product_id
                    if (item.product_id) {
                        $(`#product-id-${itemCounter}`).val(item.product_id);
                        $(`#product-select-${itemCounter}`).val(item.product_id);
                    }
                    
                    calculateItemTotal(itemCounter);
                });
            } else {
                addItemRow();
            }
            
            $('#facturaModal').modal('show');
        }
    });
}

        function changeStatus(id, status) {
            if (confirm(`¿Confirma cambiar el estado a "${status}"?`)) {
                $.ajax({
                    url: `/api/facturas/${id}/estado`,
                    method: 'PATCH',
                    contentType: 'application/json',
                    data: JSON.stringify({ estado: status }),
                    success: function(response) {
                        if (response.success) {
                            showAlert('Estado actualizado correctamente', 'success');
                            loadFacturas(currentPage);
                            loadStats();
                        }
                    },
                    error: function() {
                        showAlert('Error al cambiar estado', 'error');
                    }
                });
            }
        }

 function deleteFactura(id) {
    if (confirm('¿Está seguro de eliminar esta factura?')) {
        $.ajax({
            url: `/api/facturas/${id}`,
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                showAlert('Factura eliminada correctamente', 'success');
                loadFacturas(currentPage);
                loadStats();
            },
            error: function(xhr) {
                if (xhr.status === 404) {
                    showAlert('Factura no encontrada', 'warning');
                } else if (xhr.status === 419) {
                    showAlert('Sesión expirada. Recarga la página.', 'error');
                } else {
                    showAlert('Error al eliminar factura', 'error');
                }
                loadFacturas(currentPage); // Recargar de todos modos
            }
        });
    }
}
        // ==================== FORM SUBMIT ====================

        $('#facturaForm').on('submit', function(e) {
            e.preventDefault();
            
            const items = collectFormItems();
            if (items.length === 0) {
                showAlert('Debe agregar al menos un item a la factura', 'warning');
                return;
            }
            
            const formData = new FormData(this);
            const facturaData = {
                numero_factura: formData.get('numero_factura'),
                supplier_id: parseInt(formData.get('supplier_id')),
                fecha_emision: formData.get('fecha_emision'),
                fecha_vencimiento: formData.get('fecha_vencimiento'),
                concepto_detalle: formData.get('concepto_detalle'),
                forma_pago: formData.get('forma_pago'),
                estado: formData.get('estado'),
                subtotal: parseFloat($('#subtotal').val()),
                total_iva: parseFloat($('#total_iva').val()),
                total: parseFloat($('#total').val()),
                user_id: 1
            };
            
            const requestData = {
                factura: facturaData,
                items: items
            };
            
            const url = currentFacturaId ? `/api/facturas/${currentFacturaId}` : '/api/facturas';
            const method = currentFacturaId ? 'PUT' : 'POST';
            
            $('#submitBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Guardando...');
            
            $.ajax({
                url: url,
                method: method,
                contentType: 'application/json',
                data: JSON.stringify(requestData),
                success: function(response) {
                    if (response.success) {
                        showAlert('Factura guardada correctamente', 'success');
                        $('#facturaModal').modal('hide');
                        loadFacturas(currentPage);
                        loadStats();
                    } else {
                        showAlert(response.message || 'Error al guardar', 'error');
                    }
                },
              error: function(xhr) {
    // DEBUG: Mostrar errores detallados
    console.error('Error completo:', xhr);
    console.error('Status:', xhr.status);
    console.error('Response:', xhr.responseJSON);
    
    if (xhr.status === 422) {
        const errors = xhr.responseJSON?.errors || {};
        console.error('Errores de validación:', errors);
        
        let errorMsg = 'Errores de validación:\n';
        Object.keys(errors).forEach(field => {
            errorMsg += `- ${field}: ${errors[field].join(', ')}\n`;
        });
        showAlert(errorMsg, 'error');
    } else {
        const errorDetail = xhr.responseJSON?.message || xhr.responseJSON?.error || 'Error desconocido';
        showAlert('Error al guardar factura: ' + errorDetail, 'error');
    }
},
                complete: function() {
                    $('#submitBtn').prop('disabled', false).html('<i class="fas fa-save"></i> Guardar');
                }
            });
        });

        // ==================== FUNCIONES AUXILIARES ====================

        function applyFilters() {
            currentPage = 1;
            loadFacturas(1);
        }

        function showLoading() {
            $('#facturasTableBody').html(`
                <tr>
                    <td colspan="7" class="text-center">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                    </td>
                </tr>
            `);
        }

        function getStatusBadge(estado) {
            const badges = {
                'pendiente': 'bg-warning',
                'pagada': 'bg-success',
                'vencida': 'bg-danger'
            };
            return badges[estado] || 'bg-secondary';
        }

        function formatDate(dateString) {
            if (!dateString) return 'N/A';
            const date = new Date(dateString);
            return date.toLocaleDateString('es-ES');
        }

        function formatNumber(number) {
            if (!number) return '0.00';
            return parseFloat(number).toLocaleString('es-ES', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        }

        function capitalizeFirst(str) {
            if (!str) return '';
            return str.charAt(0).toUpperCase() + str.slice(1);
        }

        function fillProductData(itemId) {
    const productId = $(`#product-select-${itemId}`).val();
    
    if (!productId) return;
    
    const product = products.find(p => p.id == productId);
    
    if (product) {
        $(`#detalle-${itemId}`).val(product.detalle);
        $(`#unidad-${itemId}`).val(product.unidadMedida || product.unidad_medida || 'Unidad');
        $(`#valor-${itemId}`).val(product.valorUnitario || product.valor_unitario || 0);
        $(`#iva-${itemId}`).val(product.porcentajeIva || product.porcentaje_iva || 19);
        $(`#product-id-${itemId}`).val(productId);
        calculateItemTotal(itemId);
    }
}

        function showAlert(message, type = 'info', title = null, isHtml = false) {
            const alertClass = type === 'error' ? 'danger' : type;
            const icon = {
                'success': 'check-circle',
                'error': 'exclamation-triangle',
                'warning': 'exclamation-triangle',
                'info': 'info-circle'
            };
            
            if (title && isHtml) {
                const modalHtml = `
                    <div class="modal fade" id="alertModal" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">${title}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    ${message}
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                
                $('#alertModal').remove();
                $('body').append(modalHtml);
                $('#alertModal').modal('show');
                
            } else {
                const alert = `
                    <div class="alert alert-${alertClass} alert-dismissible fade show position-fixed" 
                         style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
                        <i class="fas fa-${icon[type] || 'info-circle'} me-2"></i>
                        ${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `;
                
                $('body').append(alert);
                setTimeout(() => $('.alert').fadeOut(), 5000);
            }
        }
    </script>
</body>
</html>