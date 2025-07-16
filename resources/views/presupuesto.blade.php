<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script>
 $('#printInvoice').click(function(){
            Popup($('.invoice')[0].outerHTML);
            function Popup(data) 
            {
                window.print();
                return true;
            }
        });   
</script>
<style>
 #invoice{
    padding: 30px;
}

.invoice {
    position: relative;
    background-color: #FFF;
    min-height: 680px;
    padding: 15px
}

.invoice header {
    padding: 10px 0;
    margin-bottom: 20px;
    border-bottom: 1px solid #3989c6
}

.invoice .company-details {
    text-align: right
}

.invoice .company-details .name {
    margin-top: 0;
    margin-bottom: 0
}

.invoice .contacts {
    margin-bottom: 20px
}

.invoice .invoice-to {
    text-align: left
}

.invoice .invoice-to .to {
    margin-top: 0;
    margin-bottom: 0
}

.invoice .invoice-details {
    text-align: right
}

.invoice .invoice-details .invoice-id {
    margin-top: 0;
    color: #3989c6
}

.invoice main {
    padding-bottom: 50px
}

.invoice main .thanks {
    margin-top: -100px;
    font-size: 2em;
    margin-bottom: 50px
}

.invoice main .notices {
    padding-left: 6px;
    border-left: 6px solid #3989c6
}

.invoice main .notices .notice {
    font-size: 1.2em
}

.invoice table {
    width: 100%;
    border-collapse: collapse;
    border-spacing: 0;
    margin-bottom: 20px
}

.invoice table td,.invoice table th {
    padding: 15px;
    background: #eee;
    border-bottom: 1px solid #fff
}

.invoice table th {
    white-space: nowrap;
    font-weight: 400;
    font-size: 16px
}

.invoice table td h3 {
    margin: 0;
    font-weight: 400;
    color: #3989c6;
    font-size: 1.2em
}

.invoice table .qty,.invoice table .total,.invoice table .unit {
    text-align: right;
    font-size: 1.2em
}

.invoice table .no {
    color: #fff;
    font-size: 1.6em;
    background: #3989c6
}

.invoice table .unit {
    background: #ddd
}

.invoice table .total {
    background: #3989c6;
    color: #fff
}

.invoice table tbody tr:last-child td {
    border: none
}

.invoice table tfoot td {
    background: 0 0;
    border-bottom: none;
    white-space: nowrap;
    text-align: right;
    padding: 10px 20px;
    font-size: 1.2em;
    border-top: 1px solid #aaa
}

.invoice table tfoot tr:first-child td {
    border-top: none
}

.invoice table tfoot tr:last-child td {
    color: #3989c6;
    font-size: 1.4em;
    border-top: 1px solid #3989c6
}

.invoice table tfoot tr td:first-child {
    border: none
}

.invoice footer {
    width: 100%;
    text-align: center;
    color: #777;
    border-top: 1px solid #aaa;
    padding: 8px 0
}

@media print {
    .invoice {
        font-size: 11px!important;
        overflow: hidden!important
    }

    .invoice footer {
        position: absolute;
        bottom: 10px;
        page-break-after: always
    }

    .invoice>div:last-child {
        page-break-before: always
    }
}   
</style>
<!------ Include the above in your HEAD tag ---------->

<!--Author      : @arboshiki-->
<body>
@foreach ($data2 as $presupuestodata) 
<div id="invoice">
    <div class="invoice overflow-auto">
        <div style="min-width: 600px">
            <header>
                <div class="row">
                    <div class="col">
                        <a target="_blank" href="https://lobianijs.com">
                            <img src="{{ base_path('/public/vendor/crudbooster/assets/iclogo.png') }}" class="img-responsive" height="90px" width="250px">
                            </a>
                    </div>
                    <div class="col company-details">
                        <h2 class="invoice-id", style="text-align:center;">Ficha de Solicitud para Cruzada Nacional</h2>
                        <h3 class="invoice-id", style="text-align:center;">International Commission</h3>
                        <div><strong>Fecha de Inicio:</strong> {{$presupuestodata->inicio_proyecto}}</div>
                        <div><strong>Fecha de Termino:</strong>  {{$presupuestodata->termino_proyecto}}</div>
                        <div><strong>Pais:</strong>  {{$presupuestodata->pais}}</div>
                        <div><strong>Tipo de Cruzada:</strong>  {{$presupuestodata->tipo_proyecto}}</div>
                         <div><strong>Fecha que se llenó esta ficha:</strong>  {{$presupuestodata->created_at}}</div>
                    </div>
                </div>
            </header>
            <main>
                <div class="row contacts">
                    <div class="col invoice-to">
                        <div class="text-gray-light">DATOS PRINCIPALES DE LA CRUZADA:</div>
                         <br>
                         <div><strong>Area de la Cruzada:</strong> {{$presupuestodata->region}}</div>
                         <div><strong>Lema de la Cruzada:</strong> {{$presupuestodata->lema_proyecto}}</div>
                         <div><strong>Numero de Iglesias participantes:</strong> {{$presupuestodata->num_iglesias}}</div>
                         <div><strong>Numero probable de Desiciones:</strong> {{$presupuestodata->num_desiciones}}</div>
                         <div><strong>Numero esperado de personas bautizadas:</strong> {{$presupuestodata->num_per_bautizadas}}</div>
                         <div><strong>Numero de personas que esperan escuchar la palabra:</strong> {{$presupuestodata->num_pers_escucharan}}</div>
                        <div><strong>Numero de personas que esperan Discipular:</strong> {{$presupuestodata->num_per_disciples}}</div>
                        <br>
                        <div class="text-gray-light">PRINCIPALES LIDERES DE LA CRUZADA:</div>
                        <br>
                        <div><strong>Nombre Lider:</strong> {{$presupuestodata->nombre_lider}}</div>
                        <div><strong>Email Lider:</strong> {{$presupuestodata->email_lider}}</div>
                        <div><strong>Telefono:</strong> {{$presupuestodata->contacto_lider}}</div>
                        <br>
                         <div class="text-gray-light">INFORMACION DE RELEVANCIA SOBRE LA CRUZADA:</div>
                        <br>
                        <div><strong>Es la primera vez que esta area tiene un proyecto?:</strong> {{$presupuestodata->primer_proyecto}}</div>
                        <div><strong>Cuales son las necesidades espirituales de esta region?:</strong> {{$presupuestodata->necesidades}}</div>
                        <div><strong>Escriba algo importante de esta region?:</strong> {{$presupuestodata->important}}</div>
                        <div><strong>Cuales metas tienes al realizar este proyecto?:</strong> {{$presupuestodata->metas_proyecto}}</div>
                    </div>
                </div>
                <br>
                <br>
                <br>
                <h1 class="invoice-id">PRESUPUESTO</h1>
                <table border="0" cellspacing="0" cellpadding="0">
                    <thead>
                        <tr>
                            <th></th>
                            <th class="text-left">DESCRIPTION</th>
                            <th class="text-left">MONEDA</th>
                            <th class="text-left">QTY</th>
                            <th class="text-right">TOTAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="no">01</td>
                            <td class="text-left">Costo Impresion OA</td>
                            <td class="unit">USD</td>
                            <td class="qty">01</td>
                            <td class="total">${{$presupuestodata->costo_impresion}}</td>
                        </tr>
                        <tr>
                            <td class="no">02</td>
                            <td class="text-left">Costo de lanzamiento OA y Retiro</td>
                            <td class="unit">USD</td>
                            <td class="qty">01</td>
                            <td class="total">${{$presupuestodata->costo_lanzamiento + $presupuestodata->costo_retiro}}</td>
                        </tr>
                        <tr>
                            <td class="no">03</td>
                            <td class="text-left">Costo de etapa de Visitación </td>
                            <td class="unit">USD</td>
                            <td class="qty">01</td>
                            <td class="total">${{$presupuestodata->costo_etapa_visita}}</td>
                        </tr>
                        <tr>
                            <td class="no">04</td>
                            <td class="text-left">Costo etapa de seguimiento Discipular</td>
                            <td class="unit">USD</td>
                            <td class="qty">01</td>
                            <td class="total">${{$presupuestodata->costo_etapa_seguimiento}}</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2"></td>
                            <td colspan="2">TOTAL</td>
                            <td>${{$presupuestodata->costo_impresion + $presupuestodata->costo_lanzamiento + $presupuestodata->costo_retiro + $presupuestodata->costo_etapa_visita + $presupuestodata->costo_etapa_seguimiento}}</td>
                        </tr>
                    </tfoot>
                </table>
                <div class="notices">
                    <div>NUESTRA META:</div>
                    <div class="notice">“Comisión Internacional proveerá los materiales y el entrenamiento. Los participantes pagarán sus gastos de viajes. Las iglesias cubrirán los gastos de su cruzada.Todos juntos tendremos más recursos y más personas podrán escuchar Las Buenas Nuevas.”</div>
                </div>
            </main>
            <footer>
                <div style="padding: 10px; float: left; max-width: 45%; text-align: justify; font-size: 14px;">Coordinador de la Cruzada: {{$presupuestodata->nombre_lider}}</div>
                <div style="padding: 10px; float: right; max-width: 45%; text-align: justify; font-size: 14px;">Representante Internacional de C.I: Juan Lavado</div>

            </footer>
        </div>
        <!--DO NOT DELETE THIS div. IT is responsible for showing footer always at the bottom-->
        <div></div>
    </div>
</div>
 @endforeach 
</body>
	