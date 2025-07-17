<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Sarov Services SAS</title>
    <style>
        /* Configuración de página */
        @page {
            margin: 1cm;
            size: A4 portrait;
        }
        
        /* Reset básico */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        html, body {
            margin: 0;
            padding: 0;
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            font-size: 14px;
            line-height: 1.4;
            color: #555;
        }
        
        .top_rw { 
            background-color: #f4f4f4; 
            padding: 15px 10px;
        }
        
        .invoice-box {
            max-width: 100%;
            margin: 0;
            padding: 30px;
            border: 1px solid #eee;
            font-size: 14px;
            line-height: 1.4;
            color: #555;
        }
        
        .invoice-box table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        
        .invoice-box table td {
            padding: 8px;
            vertical-align: top;
        }
        
        /* Header con logo */
        .header-section {
            background-color: #f4f4f4;
            padding: 15px;
            margin-bottom: 20px;
            position: relative;
        }
        
        .logo-img {
            max-width: 120px;
            height: auto;
            float: left;
            margin-right: 20px;
        }
        
        .header-content {
            text-align: center;
            margin-top: 20px;
        }
        
        .header-content h2 {
            font-size: 20px;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }
        
        .order-id {
            position: absolute;
            top: 15px;
            right: 15px;
            font-weight: bold;
            color: #666;
        }
        
        .date-info {
            text-align: left;
            margin-top: 10px;
            font-weight: bold;
        }
        
        /* Información de la empresa */
        .company-info {
            background-color: #f9f9f9;
            padding: 15px;
            margin-bottom: 20px;
            border-left: 4px solid #ffc000;
        }
        
        .company-info p {
            margin: 3px 0;
            line-height: 1.5;
        }
        
        /* Contenido principal en 2 columnas */
        .main-content {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        
        .column-left {
            display: table-cell;
            width: 50%;
            padding-right: 15px;
            vertical-align: top;
        }
        
        .column-right {
            display: table-cell;
            width: 50%;
            padding-left: 15px;
            vertical-align: top;
        }
        
        .section-title {
            font-weight: bold;
            font-size: 14px;
            color: #333;
            margin-bottom: 10px;
            text-transform: uppercase;
        }
        
        .info-item {
            margin-bottom: 8px;
            line-height: 1.4;
        }
        
        .info-label {
            font-weight: normal;
            color: #666;
        }
        
        .info-value {
            font-weight: bold;
            color: #333;
        }
        
        /* Sección de observaciones */
        .observations-section {
            background: #ffc000;
            text-align: center;
            padding: 8px;
            font-weight: bold;
            margin-bottom: 15px;
        }
        
        .observations-content {
            border: 1px solid #ddd;
            padding: 15px;
            min-height: 60px;
            background: #f9f9f9;
            margin-bottom: 20px;
        }
        
        /* Tabla de firmas */
        .signatures-section {
            display: table;
            width: 100%;
            margin-top: 30px;
        }
        
        .signatures-left {
            display: table-cell;
            width: 50%;
            padding-right: 15px;
            vertical-align: top;
        }
        
        .signatures-right {
            display: table-cell;
            width: 50%;
            padding-left: 15px;
            vertical-align: top;
        }
        
        .signature-block {
            margin-bottom: 20px;
        }
        
        .signature-line {
            border-bottom: 1px dotted #333;
            height: 20px;
            margin: 5px 0;
        }
        
        .terms-text {
            text-align: justify;
            font-size: 12px;
            line-height: 1.5;
        }
    </style>
</head>
<body>
@if(isset($data) && $data)
@php
    // Si $data es una colección, tomar el primer elemento
    // Si es un objeto, usarlo directamente
    $reportdata = is_array($data) || $data instanceof \Illuminate\Support\Collection ? $data[0] : $data;
@endphp

<div class="invoice-box">
    <!-- Header con logo y título -->
    <div class="header-section">
        <div class="order-id">
            Orden Id: <strong>{{ $reportdata->os_sarov ?? '00001' }}</strong>
        </div>
        
        @if(file_exists(public_path('vendor/crudbooster/assets/logo_sarov.png')))
            <img src="{{ public_path('vendor/crudbooster/assets/logo_sarov.png') }}" alt="Logo Sarov" class="logo-img">
        @else
            <div style="width: 120px; height: 60px; background: #ffc000; float: left; margin-right: 20px; text-align: center; line-height: 60px; border-radius: 50%; color: white; font-weight: bold;">SAROV</div>
        @endif
        
        <div class="header-content">
            <h2>RECEPCION DE EQUIPOS</h2>
        </div>
        
        <div class="date-info">
            Fecha: <strong>{{ \Carbon\Carbon::now()->format('d-m-Y') }}</strong>
        </div>
    </div>
    
    <!-- Información de la empresa -->
    <div class="company-info">
        <p><strong>SAROV SERVICES S.A.S.</strong></p>
        <p>NIT: 901.313.451-0</p>
        <p>Calle 144 # 13-42 oficina 905</p>
        <p>Tel: (+57) 3163366612 - (+57) 3176486050</p>
        <p>Bogotá D.C.</p>
    </div>
    
    <!-- Contenido principal en 2 columnas -->
    <div class="main-content">
        <!-- Columna izquierda - Información del vehículo -->
        <div class="column-left">
            <div class="section-title">Información del Vehículo y/o Equipo</div>
            
            <div class="info-item">
                <span class="info-label">Orden de servicio:</span> 
                <span class="info-value">{{ $reportdata->os_sarov ?? '00001' }}</span>
            </div>
            
            <div class="info-item">
                <span class="info-label">Placa:</span> 
                <span class="info-value">{{ $reportdata->placa ?? 'N/A' }}</span>
            </div>
            
            <div class="info-item">
                <span class="info-label">Tipo de Equipo/Vehiculo:</span> 
                <span class="info-value">{{ $reportdata->tipo_equipo ?? 'N/A' }}</span>
            </div>
            
            <div class="info-item">
                <span class="info-label">Descripcion:</span> 
                <span class="info-value">{{ $reportdata->descripcion ?? 'No contiene' }}</span>
            </div>
            
            <div class="info-item">
                <span class="info-label">Referencia y/o medida:</span> 
                <span class="info-value">{{ $reportdata->referencia ?? 'No contiene' }}</span>
            </div>
            
            <div class="info-item">
                <span class="info-label">Marca:</span> 
                <span class="info-value">{{ $reportdata->marca ?? 'N/A' }}</span>
            </div>
            
            <div class="info-item">
                <span class="info-label">Linea:</span> 
                <span class="info-value">{{ $reportdata->linea ?? 'N/A' }}</span>
            </div>
            
            <div class="info-item">
                <span class="info-label">Modelo:</span> 
                <span class="info-value">{{ $reportdata->modelo ?? 'N/A' }}</span>
            </div>
            
            <div class="info-item">
                <span class="info-label">Potencia:</span> 
                <span class="info-value">{{ $reportdata->potencia ?? 'N/A' }}</span>
            </div>
            
            <div class="info-item">
                <span class="info-label">Combustible:</span> 
                <span class="info-value">{{ $reportdata->combustible ?? 'N/A' }}</span>
            </div>
            
            <div class="info-item">
                <span class="info-label">#Interno:</span> 
                <span class="info-value">{{ $reportdata->interno_equipo ?? 'N/A' }}</span>
            </div>
            
            <div class="info-item">
                <span class="info-label">#Serie y/o VIN:</span> 
                <span class="info-value">{{ $reportdata->numero_serie ?? 'N/A' }}</span>
            </div>
            
            <div class="info-item">
                <span class="info-label">Km y/o Hrs: km al ingreso:</span> 
                <span class="info-value">{{ $reportdata->kms_hrs ?? 'N/A' }}</span>
            </div>
        </div>
        
        <!-- Columna derecha - Información del cliente -->
        <div class="column-right">
            <div class="section-title">Información del Cliente</div>
            
            <div class="info-item">
                <span class="info-label">Nombre del cliente:</span> 
                <span class="info-value">{{ $reportdata->customer ?? 'N/A' }}</span>
            </div>
            
            <div class="info-item">
                <span class="info-label">NIT/CC:</span> 
                <span class="info-value">{{ $reportdata->cedula_customer ?? 'N/A' }}</span>
            </div>
            
            <div class="info-item">
                <span class="info-label">Direccion:</span> 
                <span class="info-value">{{ $reportdata->direccion_customer ?? 'N/A' }}</span>
            </div>
            
            <div class="info-item">
                <span class="info-label">Telefono:</span> 
                <span class="info-value">{{ $reportdata->telefono_customer ?? 'N/A' }}</span>
            </div>
            
            <div class="info-item">
                <span class="info-label">Email:</span> 
                <span class="info-value">{{ $reportdata->correo_customer ?? 'N/A' }}</span>
            </div>
            
            <div class="info-item">
                <span class="info-label">Persona responsable del cliente:</span> 
                <span class="info-value">{{ $reportdata->responsable_cliente ?? 'N/A' }}</span>
            </div>
            
            <div class="info-item">
                <span class="info-label">Persona responsable de SAROV:</span> 
                <span class="info-value">{{ $reportdata->responsable_sarov ?? 'N/A' }}</span>
            </div>
            
            <div class="info-item">
                <span class="info-label">OS-SAROV:</span> 
                <span class="info-value">{{ $reportdata->os_sarov ?? 'N/A' }}</span>
            </div>
            
            <div class="info-item">
                <span class="info-label">WO-OQ-OP-OC-OT-OS-C-otro:</span> 
                <span class="info-value">{{ $reportdata->orden_cliente ?? 'N/A' }}</span>
            </div>
            
            <div class="info-item">
                <span class="info-label">Numero:</span> 
                <span class="info-value">{{ $reportdata->numero_orden_cliente ?? 'N/A' }}</span>
            </div>
        </div>
    </div>
    
    <!-- Sección de observaciones -->
    <div class="observations-section">
        OBSERVACIONES
    </div>
    
    <div class="observations-content">
        <strong>Observaciones:</strong> {{ $reportdata->observaciones ?? 'Sin observaciones registradas.' }}
    </div>
    
    <!-- Sección de firmas -->
    <div class="signatures-section">
        <!-- Firmas izquierda -->
        <div class="signatures-left">
            <div class="signature-block">
                <strong>Responsable de recepcion</strong><br>
                <strong>Nombre</strong><br>
                {{ $reportdata->nombre_resp_recep ?? 'N/A' }}<br>
                <div class="signature-line"></div>
                <strong>ID</strong><br>
                {{ $reportdata->id_resp_recep ?? 'N/A' }}<br>
                <div class="signature-line"></div>
                <strong>Firma</strong><br><br><br>
                <div class="signature-line"></div>
            </div>
            
            <div class="signature-block">
                <strong>Responsable del cliente</strong><br>
                <strong>Nombre</strong><br>
                {{ $reportdata->nombre_resp_cliente ?? 'N/A' }}<br>
                <div class="signature-line"></div>
                <strong>ID</strong><br>
                {{ $reportdata->id_resp_cliente ?? 'N/A' }}<br>
                <div class="signature-line"></div>
                <strong>Firma</strong><br><br><br>
                <div class="signature-line"></div>
            </div>
        </div>
        
        <!-- Términos y condiciones derecha -->
        <div class="signatures-right">
            <div class="terms-text">
                <strong>SAROV SERVICES S.A.S.</strong> NO se hace responsable por elementos y/o accesorios dejados en el vehículo y/o equipo que no sean reportados e inventariados en el presente formato, ni por daños en el vehículo y/o equipo causados por fuerza mayor, caso fortuito o culpa leve o levísima. Con la firma del presente documento el cliente manifiesta haber conocido todas implicaciones y riesgos que tiene el servicio y acepta los términos y condiciones de las cotizaciones.
                <br><br>
                <strong>NOTA:</strong> Todo diagnostico tiene costo; se indicara al cliente el valor al momento del retiro del vehiculo.
            </div>
        </div>
    </div>
</div>

@else
<div style="text-align: center; padding: 50px;">
    <h2>Error: No se encontraron datos para mostrar</h2>
    <p>La orden solicitada no existe o no tiene datos asociados.</p>
</div>
@endif
</body>
</html>