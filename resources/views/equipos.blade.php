<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sarov Services SAS</title>
</head>
<body>
 @foreach($data as $reportdata)    
    <div class="invoice-box">
        <table cellpadding="0" cellspacing="0">
		<tr class="top_rw">
            <td colspan="2">
                <img src="{{ base_path('/public/vendor/crudbooster/assets/logo_sarov.png')}}" alt="Logo de la empresa" style="display: block; margin-bottom: 10px; margin-top: 10px; margin-left: 10px;">
                <h2 style="text-align: center;"> RECEPCION DE EQUIPOS</h2>
                <span style="display: block; text-align: left;">Fecha: <b>{{ \Carbon\Carbon::now()->format('d-m-Y') }}</b></span>
            </td>
		    <td  style="width:30%; margin-right: 10px;">
		    Orden Id:<b>{{$reportdata->os_sarov}}</b>
		   </td>
		</tr>
            <tr class="top">
                <td colspan="3">
                    <table>
                        <tr>
                            <td>
							<b>SAROV SERVICES S.A.S.</b> <br>
                            NIT: 901.313.451-0 <br>
                            Calle 144 # 13-42 oficina 905 <br>
                            Tel: (+57) 3163366612 - (+57) 3176486050 <br>
                            Bogotá D.C. <br>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr class="information">
                  <td colspan="3">
                    <table>
                        <tr>
                            <td colspan="2">
							<b> INFORMACION DEL VEHICULO Y/O EQUIPO </b> <br>
                                Orden de servicio: <b>{{$reportdata->os_sarov}}</b> <br>
                                Placa: <b>{{$reportdata->placa}}</b> <br>
                                Tipo de Equipo/Vehiculo: <b>{{$reportdata->tipo_equipo}}</b> <br>
                                Descripcion: <b>{{$reportdata->descripcion}}</b> <br>
                                Referencia y/o medida: <b>{{$reportdata->referencia}}</b> <br>
								Marca: {{$reportdata->marca}}</b> <br>
                                Linea: <b>{{$reportdata->linea}}</b> <br>
                                Modelo: <b>{{$reportdata->modelo}}</b> <br>
                                Potencia: <b>{{$reportdata->potencia}}</b> <br>
                                Combustible: <b>{{$reportdata->combustible}}</b> <br>
                                #Interno: <b>{{$reportdata->interno_equipo}}</b> <br>
                                #Serie y/o VIN: <b>{{$reportdata->numero_serie}}</b> <br>
                                Km y/o Hrs: km al ingreso: <b>{{$reportdata->kms_hrs}}</b> <br>
                            </td>
                            <td> <b> INFORMACION DEL CLIENTE </b><br>
                                Nombre del cliente:  <b>{{$reportdata->customer}}</b><br>
                                NIT/CC: <b>{{$reportdata->cedula_customer}}</b><br>
                                Direccion:<b>{{$reportdata->direccion_customer}}</b><br>
                                Telefono: <b>{{$reportdata->telefono_customer}}</b><br>
                                Email: <b>{{$reportdata->correo_customer}}</b><br>
                                Persona responsable del cliente: <b>{{$reportdata->responsable_cliente}}</b><br>
                                Persona responsable de SAROV: <b>{{$reportdata->responsable_sarov}}</b><br>
                                OS-SAROV: <b>{{$reportdata->os_sarov}}</b><br>
                                WO-OQ-OP-OC-OT-OS-C-otro: <b>{{$reportdata->orden_cliente}}</b><br>
                                Numero: <b>{{$reportdata->numero_orden_cliente}}</b><br>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
         <tr class="heading">
                    <td colspan="3" align="center"><b>OBSERVACIONES</b></td>
                              </tr>
               <tr>
        <td colspan="3">
			 <table>
                  <tr>
               <td>
            <b> Observaciones: </b> 
          {{$reportdata->observaciones}}
               </td> 
                  </tr>       
                   </table>
			     </td>
            </tr>     
		</table>
            <table>
                    <tr>
						<td width="50%"> 
                            <br> 
						 	<b>Responsable de recepcion</b>
							<br>
                            <b>Nombre</b>
							<br>
							{{$reportdata->nombre_resp_recep}}<br>
							..........................................................
							<br>
                            <b>ID</b>
							<br>
							{{$reportdata->id_resp_recep}}<br>
							..........................................................
							<br>
							<b>Firma</b>
							<br>
                            <b></b>
							<br>
							
							..........................................................
						   <br> 
						 	<b>Responsable del cliente</b>
							<br>
                            <b>Nombre</b>
							<br>
							{{$reportdata->nombre_resp_cliente}}<br>
							..........................................................
							<br>
                            <b>ID</b>
							<br>
							{{$reportdata->id_resp_cliente}}<br>
							..........................................................
							<br>
							<b>Firma</b>
							<br>
                            <b></b>
							<br>
							
							..........................................................
						</td>
                       <td width="50%" style="text-align: justify;">
                        <b>SAROV SERVICES S.A.S.</b> 
                       NO&nbsp;se&nbsp;hace&nbsp;responsable por elementos y/o &nbsp; accesorios dejados en el vehículo y/o equipo que &nbsp;no sean 
                       reportados e inventariados en el presente &nbsp; formato, ni por daños en el vehículo y/o &nbsp;equipo &nbsp;causados 
                       por fuerza mayor, caso fortuito o culpa &nbsp;leve o levísima. Con la firma del presente &nbsp;documento el cliente
                       manifiesta haber conocido &nbsp;todas implicaciones y riesgos que tiene el servicio &nbsp;y acepta los términos y condiciones de las &nbsp;cotizaciones.
                         <b> NOTA: </b> 
                       Todo diagnostico tiene costo; se indicara al cliente el valor al momento del retiro del vehiculo.
                         </td>
			          </tr> 
                 </table>
			   </td>
			</tr>
        </table>
    </div>
   @endforeach  
 <style>
     .top_rw{ background-color:#f4f4f4; }
	.td_w{ }
	button{ padding:5px 10px; font-size:14px;}
    .invoice-box {
        max-width: 890px;
        margin: auto;
        padding:10px;
        border: 1px solid #eee;
        box-shadow: 0 0 10px rgba(0, 0, 0, .15);
        font-size: 14px;
        line-height: 24px;
        font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        color: #555;
    }
    .invoice-box table {
        width: 100%;
        line-height: inherit;
        text-align: left;
		border-bottom: solid 1px #ccc;
    }
    .invoice-box table td {
        padding: 5px;
        vertical-align:middle;
    }
    .invoice-box table tr td:nth-child(2) {
        text-align: right;
    }
    .invoice-box table tr.top table td {
        padding-bottom: 20px;
    }
    .invoice-box table tr.top table td.title {
        font-size: 45px;
        line-height: 45px;
        color: #333;
    }
    .invoice-box table tr.information table td {
        padding-bottom: 40px;
    }
    .invoice-box table tr.heading td {
        background: #ffc000;
        border-bottom: 1px solid #ddd;
        font-weight: bold;
		font-size:12px;
    }
    .invoice-box table tr.details td {
        padding-bottom: 20px;
    }
    .invoice-box table tr.item td{
        border-bottom: 1px solid #eee;
    }
    .invoice-box table tr.item.last td {
        border-bottom: none;
    }
    .invoice-box table tr.total td:nth-child(2) {
        border-top: 2px solid #eee;
        font-weight: bold;
    }
    @media only screen and (max-width: 600px) {
        .invoice-box table tr.top table td {
            width: 100%;
            display: block;
            text-align: center;
        }
        .invoice-box table tr.information table td {
            width: 100%;
            display: block;
            text-align: center;
        }
    }
    
 /** RTL **/
    .rtl {
        direction: rtl;
        font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
    }
    .rtl table {
        text-align: right;
    }
    .rtl table tr td:nth-child(2) {
        text-align: left;
    } 
    .grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            grid-gap: 10px;
            width: 100%;
        }

        .grid img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .placeholder {
            width: 100%;
            height: 200px;
            background-color: #e1e1e1;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 20px;
            color: #666;
        } 
    </style>   
</body>
</html>