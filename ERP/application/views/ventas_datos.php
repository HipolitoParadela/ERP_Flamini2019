<?php
// CABECERA
include "head.php";
// TOP
include "top.php";
include "menusidebar.php";
?>
<!-- PAGE CONTAINER-->
<div class="page-container" id="ventas">
    <!-- HEADER DESKTOP-->
    <?PHP include "header_desktop.php"; ?>
    <!-- HEADER DESKTOP-->

    <!-- MAIN CONTENT-->
    <div class="main-content">
        <div class="section__content section__content--p30">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-2">
                        <div class="card">
                            <div class="card-header">
                                <h4>Info</h4>
                                <!-- <?php echo $Datos_venta["Id"]; ?> -->
                            </div>

                            <div class="card-body">
                                <h3 class="text-sm-center mt-2 mb-1"> {{ventaDatos.Identificador_venta}}</h3>
                                <hr>
                                <h4 class="text-sm-center mt-2 mb-1" v-if="ventaDatos.Estado == 1">Control de materiales recibidos</h4>
                                <h4 class="text-sm-center mt-2 mb-1" v-if="ventaDatos.Estado == 2">Proceso de materiales</h4>
                                <h4 class="text-sm-center mt-2 mb-1" v-if="ventaDatos.Estado == 3">Soldadura</h4>
                                <h4 class="text-sm-center mt-2 mb-1" v-if="ventaDatos.Estado == 4">Pintura</h4>
                                <h4 class="text-sm-center mt-2 mb-1" v-if="ventaDatos.Estado == 5">Rotulación</h4>
                                <h4 class="text-sm-center mt-2 mb-1" v-if="ventaDatos.Estado == 6">Empaque</h4>
                                <h4 class="text-sm-center mt-2 mb-1" v-if="ventaDatos.Estado == 7">Logística</h4>
                                <h4 class="text-sm-center mt-2 mb-1" v-if="ventaDatos.Estado == 8">Instalación</h4>
                                <h4 class="text-sm-center mt-2 mb-1" v-if="ventaDatos.Estado > 8">Producción Finalizada</h4>
                                <hr>

                                <p class="text-sm-center mt-2 mb-1"><b>Planificación:</b> {{ventaDatos.Nombre_planificacion}}</p>
                                <p class="text-sm-center mt-2 mb-1"><b>Empresa:</b> {{ventaDatos.Nombre_empresa}}</p>
                                <p class="text-sm-center mt-2 mb-1"><b>Cliente:</b> {{ventaDatos.Nombre_cliente}} </p>
                                <p class="text-sm-center mt-2 mb-1"><b>Vendedor:</b> {{ventaDatos.Nombre_vendedor}} </p>
                                <p class="text-sm-center mt-2 mb-1"><b>Plan. Inicial:</b> {{ventaDatos.Nombre_resp_1}}</p>
                                <p class="text-sm-center mt-2 mb-1"><b>Plan. Final:</b> {{ventaDatos.Nombre_resp_2}}</p>
                                <p class="text-sm-center mt-2 mb-1"><b>Logistica:</b> {{ventaDatos.Nombre_logistica}}</p>
                                <p class="text-sm-center mt-2 mb-1"><b>Instalación:</b> {{ventaDatos.Nombre_instalacion}}</p>
                                <p class="text-sm-center mt-2 mb-1"><b>Cobranza:</b> {{ventaDatos.Nombre_cobranza}}</p>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h4>Tiempos</h4>
                            </div>
                            <div class="card-body">
                                <div v-if="ventaDatos.Estado < 10">
                                    <p class="text-sm-center mt-2 mb-1"><b>{{diferenciasEntre_fechas(ventaDatos.Fecha_venta, null)}}</b> de comenzada su producción</p>
                                    <p class="text-sm-center mt-2 mb-1" v-bind:class=" classAlerta(diferenciasEntre_fechas(null, ventaDatos.Fecha_estimada_entrega)) ">
                                        <b> Finalización:</b>
                                        {{diferenciasEntre_fechas(null, ventaDatos.Fecha_estimada_entrega)}} días
                                    </p>
                                    <p class="text-sm-center mt-2 mb-1">del {{ventaDatos.Fecha_venta | Fecha}} al {{ventaDatos.Fecha_estimada_entrega | Fecha}}</p>
                                </div>
                                <div v-if="ventaDatos.Estado == 10">
                                    <p class="text-sm-center mt-2 mb-1"><b>Finalizado el día </b>{{ventaDatos.Fecha_finalizada | Fecha}}. </p>
                                    <p class="text-sm-center mt-2 mb-1">Su producción demandó <b>{{diferenciasEntre_fechas(ventaDatos.Fecha_venta, ventaDatos.Fecha_finalizada)}}</b>.</p>
                                    <p class="text-sm-center mt-2 mb-1">
                                        Diferencia entre finalizado y su estimación: <b>{{diferenciasEntre_fechas(ventaDatos.Fecha_finalizada, ventaDatos.Fecha_estimada_entrega)}}</b>
                                    </p>

                                </div>

                            </div>
                        </div>
                        <!-- {{Usuario_id}} -->

                        <div class="card" v-if="Usuario_id == '5' || Usuario_id == '6' || Usuario_id == 10 || Usuario_id == 4">
                            <div class="card-header">
                                <h4>Datos contacto cliente</h4>
                            </div>
                            <div class="card-body text-center">
                                <p>{{ventaDatos.Nombre_cliente}}</p>
                                <p>{{ventaDatos.Direccion}}. {{ventaDatos.Localidad}}. {{ventaDatos.Provincia}}</p>
                                <p>{{ventaDatos.Telefono}}</p>
                                <p>{{ventaDatos.Telefono_fijo}}</p>
                                <p>{{ventaDatos.Email}}</p>
                                <p>{{ventaDatos.Nombre_persona_contacto}}</p>
                                <p>{{ventaDatos.CUIT_CUIL}}</p>
                            </div>
                        </div>
                    </div>


                    <!-- NAVEGADOR PESTAÑAS -->
                    <div class="col-lg-10 ">
                        <ul class="nav nav-tabs d-print-none">
                            <li class="nav-item" v-if="Usuario_id == '5' || Usuario_id == '6' || Usuario_id == 10 || Usuario_id == 4">
                                <a class="nav-link" v-bind:class="{ active: mostrar == 11 }" href="#" v-on:click="mostrar = 11">
                                    Informe General
                                </a>
                            </li>
                            <li class="nav-item" v-if="Usuario_id == '5' || Usuario_id == '6' || Usuario_id == 10 || Usuario_id == 4">
                                <a class="nav-link" v-bind:class="{ active: mostrar == 1 }" href="#" v-on:click="mostrar = 1">Ficha</a>
                            </li>
                            <li class="nav-item" v-if="Usuario_id == '5' || Usuario_id == '6' || Usuario_id == 10 || Usuario_id == 4">
                                <a class="nav-link" v-bind:class="{ active: mostrar == 2 }" href="#" v-on:click="getListadoSeguimiento(0, 2)">Historial</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" v-bind:class="{ active: mostrar == 3 }" href="#" v-on:click="mostrar = 3">Stock</a>
                            </li>
                            <li class="nav-item" v-if="Usuario_id == '5' || Usuario_id == '6' || Usuario_id == 10 || Usuario_id == 4 || Usuario_id == 7">
                                <a class="nav-link" v-bind:class="{ active: mostrar == 4 }" href="#" v-on:click="mostrar = 4">Producción</a>
                            </li>
                            <li class="nav-item" v-if="Usuario_id == '5' || Usuario_id == '6' || Usuario_id == 10 || Usuario_id == 4">
                                <a class="nav-link" v-bind:class="{ active: mostrar == 5 }" href="#" v-on:click="getListadoSeguimiento(3, 5)">Logística</a>
                            </li>
                            <li class="nav-item" v-if="Usuario_id == '5' || Usuario_id == '6' || Usuario_id == 10 || Usuario_id == 4">
                                <a class="nav-link" v-bind:class="{ active: mostrar == 6 }" href="#" v-on:click="getListadoSeguimiento(4, 6)">Instalación</a>
                            </li>
                            <li class="nav-item" v-if="Usuario_id == '5' || Usuario_id == '6' || Usuario_id == 10 || Usuario_id == 4">
                                <a class="nav-link" v-bind:class="{ active: mostrar == 7 }" href="#" v-on:click="getListadoSeguimiento(5,7)">Cobranza</a>
                            </li>
                        </ul>



                        <!-- SECCIÓN INFORME GENERAL -->
                        <div class="row" v-show="mostrar == '11'" v-if="Usuario_id == '5' || Usuario_id == '6' || Usuario_id == 10 || Usuario_id == 4">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <strong>Observaciones generales</strong>
                                    </div>
                                    <div class="card-body">


                                        {{ventaDatos.Observaciones_venta}}
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header">
                                        <strong>Cobranza</strong>
                                    </div>
                                    <div class="card-body">
                                        <div class="col-lg-12">
                                            <h4>
                                                {{ventaDatos.Info_cobranza}}
                                            </h4><br>

                                            <table class="table table-striped">
                                                <tr>
                                                    <th>Total productos</th>
                                                    <th>Logística</th>
                                                    <th>Instalación</th>
                                                    <th>Recargo</th>
                                                    <th>Descuento</th>
                                                    <th>Total a cobrar</th>
                                                    <th>Total cobrado</th>
                                                    <th>Saldo</th>
                                                </tr>
                                                <tr>
                                                    <td>${{ sumarProductos(listaProductosVendidos) + sumarProductos(listaProductosReventaLote) | Moneda}}</td>
                                                    <td>${{ventaDatos.Valor_logistica | Moneda}}</td>
                                                    <td>${{ventaDatos.Valor_instalacion | Moneda}}</td>
                                                    <td>${{ventaDatos.Recargo | Moneda}}</td>
                                                    <td>${{ventaDatos.Descuento | Moneda}}</td>
                                                    <td>${{
                                                        calcularMontosVentas(
                                                            sumarProductos(listaProductosVendidos), 
                                                            ventaDatos.Valor_logistica, 
                                                            ventaDatos.Valor_instalacion, 
                                                            sumarProductos(listaProductosReventaLote), 
                                                            0, 
                                                            ventaDatos.Recargo, 
                                                            ventaDatos.Descuento
                                                        )   | Moneda}}</td>
                                                    <td> ${{listaMovimientos.Total | Moneda}}</td>
                                                    <td>${{
                                                        calcularMontosVentas(
                                                            sumarProductos(listaProductosVendidos), 
                                                            ventaDatos.Valor_logistica, 
                                                            ventaDatos.Valor_instalacion, 
                                                            sumarProductos(listaProductosReventaLote), 
                                                            listaMovimientos.Total,
                                                            ventaDatos.Recargo, 
                                                            ventaDatos.Descuento
                                                        ) | Moneda}}</td>
                                                </tr>
                                            </table><br>
                                            <hr>
                                            <p style="margin-bottom: 5px;"><b>Pagos registrados</b></p>
                                            <div class="table-responsive">
                                                <table id="table2excel" class="table table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>Monto</th>
                                                            <th>Fecha</th>
                                                            <th>Modalidad</th>
                                                            <th>Observaciones</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="movimiento in listaMovimientos.Datos">
                                                            <td>${{movimiento.Monto | Moneda}}</td>
                                                            <td>{{movimiento.Fecha_ejecutado | Fecha}}</td>
                                                            <td>{{movimiento.Modalidad_pago}}</td>
                                                            <td>{{movimiento.Observaciones}}</td>
                                                        </tr>
                                                    </tbody>
                                                    <tfoot>
                                                        <th>
                                                            <h3>${{listaMovimientos.Total | Moneda}}</h3>
                                                        </th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header">
                                        <strong>Producción</strong>
                                    </div>
                                    <div class="card-body">

                                        <p style="margin-bottom: 5px;"><strong>PRODUCTOS PROPIOS</strong></p>
                                        <table id="table2excel" class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Código</th>
                                                    <th>Producto</th>

                                                    <th>Cantidad</th>
                                                    <th>Precio</th>
                                                    <th>Subtotal</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="productos in listaResumenProductos">
                                                    <td>{{productos.Codigo_interno}}</td>
                                                    <td>{{productos.Nombre_producto}}</td>

                                                    <td align="center">{{productos.Cantidad}}</td>
                                                    <td align="right">${{productos.Precio_venta | Moneda}}</td>
                                                    <td align="right">${{productos.Precio_venta * productos.Cantidad | Moneda}}</td>
                                                </tr>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th>
                                                        <h4 align="right">${{sumarProductos(listaProductosVendidos) | Moneda}}</h4>
                                                    </th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                        <br>
                                        <p style="margin-bottom: 5px;"> <strong>PRODUCTOS DE REVENTA</strong></p>
                                        <table id="table2excel" class="table table-striped">
                                            <thead>
                                                <tr>

                                                    <th>Producto</th>
                                                    <th>Cantidad</th>
                                                    <th>Precio</th>
                                                    <th>Subtotal</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="productosRev in listaProductosReventaLote">
                                                    <td>{{productosRev.Nombre_item}}</td>

                                                    <td align="right">{{productosRev.Cantidad}}</td>
                                                    <td align="right">${{productosRev.Precio_venta_producto | Moneda}}</td>
                                                    <td align="right">${{productosRev.Precio_venta_producto * productosRev.Cantidad | Moneda}}</td>
                                                    <!-- <td>{{productosRev.Subtotal}}</td> -->
                                                </tr>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th>
                                                        <h4 align="right">${{sumarProductos(listaProductosReventaLote) | Moneda}}</h4>
                                                    </th>
                                                </tr>
                                            </tfoot>
                                        </table>

                                        <hr>
                                        <br>
                                        <p style="margin-bottom: 5px;"> <strong>REQUERIMIENTOS PARA LOGÍSTICA</strong></p>
                                        <P>{{ventaDatos.Info_logistica}}</P>
                                        <hr>
                                        <br>
                                        <p style="margin-bottom: 5px;"> <strong>REQUERIMIENTOS PARA INSTALACIÓN</strong></p>
                                        <P>{{ventaDatos.Instalacion}}</P>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header">
                                        <strong>Historial de Reportes</strong>
                                    </div>
                                    <div class="card-body">


                                        <div class="bootstrap-data-table-panel col-lg-12">
                                            <div class="table-responsive">
                                                <table id="table2excel" class="table table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>Fecha</th>
                                                            <th>Categoría</th>
                                                            <th>Descripcion</th>
                                                            <th>Archivo</th>
                                                            <th>Autor</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="seguimiento in listaSeguimiento">
                                                            <td>{{ seguimiento.Fecha | FechaTimeBD}}</td>

                                                            <td v-if="seguimiento.Categoria_seguimiento == 0">Sin categoría</td>
                                                            <td v-if="seguimiento.Categoria_seguimiento == 1">Compras</td>
                                                            <td v-if="seguimiento.Categoria_seguimiento == 2">Producción</td>
                                                            <td v-if="seguimiento.Categoria_seguimiento == 3">Logística</td>
                                                            <td v-if="seguimiento.Categoria_seguimiento == 4">Instalación</td>
                                                            <td v-if="seguimiento.Categoria_seguimiento == 5">Cobranza</td>

                                                            <td>{{seguimiento.Descripcion}}</td>
                                                            <td><a v-if="seguimiento.Url_archivo != null" target="_blank" v-bind:href="'<?php echo base_url(); ?>uploads/imagenes/'+seguimiento.Url_archivo"> Ver archivo</a></td>
                                                            <td>{{seguimiento.Nombre}}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>


                                    </div>
                                </div>

                            </div>
                        </div>





                        <!-- SECCION DATOS EDITABLES DE LA VENTA -->
                        <div class="row" v-show="mostrar == '1'">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <strong>Ficha: {{ventaDatos.Nombre_producto }}</strong>
                                        Última actualización: {{ventaDatos.Fecha_ultima_edicion | FechaTimeBD}}
                                    </div>
                                    <div class="card-body">
                                        <div class="horizontal-form">
                                            <form class="form-horizontal" action="post" v-on:submit.prevent="crearVenta()">
                                                <div class="row">
                                                    <div class="col-sm-5">
                                                        <div class="form-group">
                                                            <label class=" form-control-label">Identificador de la vente</label>
                                                            <input type="text" class="form-control" v-model="ventaDatos.Identificador_venta" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label">Planificación</label>
                                                            <select class="form-control" v-model="ventaDatos.Planificacion_id" required>
                                                                <option value="0">Seleccionar persona</option>
                                                                <option v-for="planificacion in listaPlanificaciones" v-bind:value="planificacion.Id">{{planificacion.Nombre_planificacion}}</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label">Empresa</label>
                                                            <select class="form-control" v-model="ventaDatos.Empresa_id" required>
                                                                <option value="0">Seleccionar empresa</option>
                                                                <option v-for="empresa in listaEmpresas" v-bind:value="empresa.Id">{{empresa.Nombre_empresa}}</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label">Vendedor</label>
                                                            <select class="form-control" v-model="ventaDatos.Vendedor_id" required>
                                                                <option value="0">Seleccionar persona</option>
                                                                <option v-for="persona in listaUsuarios" v-bind:value="persona.Id">{{persona.Nombre}}</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label">Cliente</label>
                                                            <select class="form-control" v-model="ventaDatos.Cliente_id" required>
                                                                <option value="0">Seleccionar persona</option>
                                                                <option v-for="cliente in listaClientes" v-bind:value="cliente.Id">{{cliente.Nombre_cliente}}</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label">Responsable panificacion inicial</label>
                                                            <select class="form-control" v-model="ventaDatos.Responsable_id_planif_inicial" required>
                                                                <option value="0">Seleccionar persona</option>
                                                                <option v-for="persona in listaUsuarios" v-bind:value="persona.Id">{{persona.Nombre}}</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label">Responsable panificacion final</label>
                                                            <select class="form-control" v-model="ventaDatos.Responsable_id_planif_final" required>
                                                                <option value="0">Seleccionar persona</option>
                                                                <option v-for="persona in listaUsuarios" v-bind:value="persona.Id">{{persona.Nombre}}</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label">Prioridad de producción</label>
                                                            <select class="form-control" v-model="ventaDatos.Prioritario" required>
                                                                <option value="0">Sin prioridad</option>
                                                                <option value="1">Dar prioridad</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class=" form-control-label">Fecha de venta</label>
                                                            <input type="date" class="form-control" v-model="ventaDatos.Fecha_venta" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class=" form-control-label">Fecha estimada para finalizar fabricación</label>
                                                            <input type="date" class="form-control" v-model="ventaDatos.Fecha_estimada_entrega" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class=" form-control-label">Observaciones sobre la venta</label>
                                                            <textarea class="form-control" rows="5" v-model="ventaDatos.Observaciones_venta"></textarea>
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-5">
                                                        <div class="form-group">
                                                            <label class="control-label">Responsable de logística</label>
                                                            <select class="form-control" v-model="ventaDatos.Responsable_id_logistica" required>
                                                                <option value="0">Seleccionar persona</option>
                                                                <option v-for="persona in listaUsuarios" v-bind:value="persona.Id">{{persona.Nombre}}</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class=" form-control-label">Costo por logística</label>
                                                            <input type="int" class="form-control" v-model="ventaDatos.Valor_logistica" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class=" form-control-label">Información sobre logística</label>
                                                            <textarea class="form-control" rows="5" v-model="ventaDatos.Info_logistica"></textarea>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class=" form-control-label">Costo por instalación</label>
                                                            <input type="int" class="form-control" v-model="ventaDatos.Valor_instalacion" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label">Responsable de instalación/colocaciones</label>
                                                            <select class="form-control" v-model="ventaDatos.Responsable_id_instalacion" required>
                                                                <option value="0">Seleccionar persona</option>
                                                                <option v-for="persona in listaUsuarios" v-bind:value="persona.Id">{{persona.Nombre}}</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class=" form-control-label">Información sobre Instalaciones</label>
                                                            <textarea class="form-control" rows="5" v-model="ventaDatos.Info_instalaciones"></textarea>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label">Responsable de cobranza</label>
                                                            <select class="form-control" v-model="ventaDatos.Responsable_id_cobranza" required>
                                                                <option value="0">Seleccionar persona</option>
                                                                <option v-for="persona in listaUsuarios" v-bind:value="persona.Id">{{persona.Nombre}}</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class=" form-control-label">Información sobre cobranza</label>
                                                            <textarea class="form-control" rows="5" v-model="ventaDatos.Info_cobranza"></textarea>
                                                        </div>
                                                    </div>


                                                    <div class="col-sm-2">
                                                        <div class="form-group">
                                                            <label class=" form-control-label">Recargo</label>
                                                            <input type="int" class="form-control" v-model="ventaDatos.Recargo">
                                                        </div>
                                                        <div class="form-group">
                                                            <label class=" form-control-label">Descuento</label>
                                                            <input type="int" class="form-control" v-model="ventaDatos.Descuento">
                                                        </div>
                                                        <p class="text-info">Al editar estos campos, reportar en la ficha de Historial las razones del descuento/recargo aplicado. </p>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <hr>
                                                        <button type="submit" class="btn btn-success">Actualizar datos</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- SECCION DATOS DE SEGUIMIENTO -->
                        <div class="row" v-show="mostrar == '2'">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <strong>Seguimiento</strong>
                                    </div>
                                    <div class="card-body">
                                        <p align="right">
                                            <a href="#modalSeguimiento" data-toggle="modal" class="btn btn-success btn-flat btn-addon m-b-10 m-l-5 btn-sm" v-on:click="limpiarFormularioSeguimiento()">
                                                <i class="ti-plus"></i> Añadir reporte
                                            </a>
                                        </p>
                                        <div class="bootstrap-data-table-panel col-lg-12">
                                            <div class="table-responsive">
                                                <table id="table2excel" class="table table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>Fecha</th>
                                                            <th>Categoría</th>
                                                            <th>Descripcion</th>
                                                            <th>Archivo</th>
                                                            <th>Autor</th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="seguimiento in listaSeguimiento">
                                                            <td>{{ seguimiento.Fecha | FechaTimeBD}}</td>

                                                            <td v-if="seguimiento.Categoria_seguimiento == 0">Sin categoría</td>
                                                            <td v-if="seguimiento.Categoria_seguimiento == 1">Compras</td>
                                                            <td v-if="seguimiento.Categoria_seguimiento == 2">Producción</td>
                                                            <td v-if="seguimiento.Categoria_seguimiento == 3">Logística</td>
                                                            <td v-if="seguimiento.Categoria_seguimiento == 4">Instalación</td>
                                                            <td v-if="seguimiento.Categoria_seguimiento == 5">Cobranza</td>

                                                            <td>{{seguimiento.Descripcion}}</td>
                                                            <td><a v-if="seguimiento.Url_archivo != null" target="_blank" v-bind:href="'<?php echo base_url(); ?>uploads/imagenes/'+seguimiento.Url_archivo"> Ver archivo</a></td>
                                                            <td>{{seguimiento.Nombre}}</td>
                                                            <td>
                                                                <a href="#modalSeguimiento" data-toggle="modal" v-on:click="editarFormularioSeguimiento(seguimiento)">
                                                                    <i class="fa fa-edit"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- -->
                            </div>
                        </div>

                        <!-- SECCION INSUMOS USADOS -->
                        <div class="row" v-show="mostrar == '3'">

                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="card">
                                            <div class="card-header">
                                                <strong>Estación actual del lote</strong>
                                            </div>
                                            <div class="card-body">
                                                <h1 v-if="ventaDatos.Estado == 1">Control de materiales recibidos</h1>
                                                <h1 v-if="ventaDatos.Estado == 2">Proceso de materiales</h1>
                                                <h1 v-if="ventaDatos.Estado == 3">Soldadura</h1>
                                                <h1 v-if="ventaDatos.Estado == 4">Pintura</h1>
                                                <h1 v-if="ventaDatos.Estado == 5">Rotulación</h1>
                                                <h1 v-if="ventaDatos.Estado == 6">Empaque</h1>
                                                <h1 v-if="ventaDatos.Estado == 7">Logística</h1>
                                                <h1 v-if="ventaDatos.Estado == 8">Instalación</h1>
                                                <h1 v-if="ventaDatos.Estado > 8">Producción Finalizada</h1>
                                                <hr>
                                                <p>
                                                    <a v-if="ventaDatos.Estado < 9" href="#" class="btn btn-warning " v-on:click="cambiar_estado_venta(0)">
                                                        Avanzar lote a siguiente etapa >>
                                                    </a>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-8">
                                        <div class="card">
                                            <div class="card-header">
                                                <strong>Responsables</strong>
                                            </div>
                                            <div class="card-body">
                                                <div class="bootstrap-data-table-panel col-lg-12">
                                                    <div class="table-responsive">
                                                        <div class="table-responsive">
                                                            <table id="table2excel" class="table table-striped">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Planificación Inicial</th>
                                                                        <th>Planificación Final</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td>{{ventaDatos.Nombre_resp_1}}</td>
                                                                        <td>{{ventaDatos.Nombre_resp_2}}</td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <strong>Seguimiento de la producción</strong>
                                            </div>

                                            <div class="card-body">
                                                <div class="bootstrap-data-table-panel col-lg-12">
                                                    <div class="table-responsive">
                                                        <div class="table-responsive">
                                                            <table id="table2excel" class="table table-striped" style="overflow-x: visible;">
                                                                <thead>
                                                                    <tr>
                                                                        <td colspan="2">
                                                                            <!-- <h2>
                                                                                <a v-if="ventaDatos.Estado < 7" href="#modalProductos" data-toggle="modal" class="btn btn-success btn-flat btn-addon" v-on:click="limpiarFormularioProductos()">
                                                                                    <i class="ti-plus"></i> +
                                                                                </a>
                                                                            </h2> -->
                                                                        </td>

                                                                        <th>Producto</th>
                                                                        <th>Código</th>
                                                                        <th>Cantidad</th>
                                                                        <th>Stock</th>
                                                                        <th>Proceso materiales</th>
                                                                        <th>Soldadura</th>
                                                                        <th>Pintura</th>
                                                                        <th>Rotulación</th>
                                                                        <th>Empaque</th>
                                                                        <th>Logística</th>
                                                                        <th>Instalación</th>

                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr v-for="productoFabricado in listaProductosVendidos">
                                                                        <td>
                                                                            <img v-if="productoFabricado.Imagen != null" v-bind:src="'<?php echo base_url(); ?>uploads/imagenes/'+productoFabricado.Imagen" width="60px">
                                                                        </td>
                                                                        <td>
                                                                            <span v-if="productoFabricado.Tipo_produccion == 1" class="text-secondary"><i class="fa fa-circle"></i></span>
                                                                            <span v-if="productoFabricado.Tipo_produccion == 2" class="text-warning"><i class="fa fa-circle"></i></span>
                                                                            <span v-if="productoFabricado.Tipo_produccion == 3" class="text-info"><i class="fa fa-circle"></i></span>
                                                                        </td>
                                                                        <td>
                                                                            <h4> {{productoFabricado.Nombre_producto}}</h4>
                                                                        </td>
                                                                        <td>
                                                                            {{productoFabricado.Codigo_interno}}
                                                                        </td>
                                                                        <td align="center">
                                                                            <h4>{{productoFabricado.Cantidad}}</h4>
                                                                        </td>
                                                                        <td>
                                                                            <a v-if="productoFabricado.Estado == 1" href="#modalPasoapaso" data-toggle="modal" class="btn btn-info btn-flat btn-addon m-b-10 m-l-5 btn-sm" v-on:click="editarPasoProducto(productoFabricado.Id, 2)">
                                                                                <i class="ti-plus"></i> Stock OK >>
                                                                            </a>
                                                                            <span v-if="productoFabricado.Estado > 1"> {{productoFabricado.S_1_Fecha_finalizado | Fecha}}</span>
                                                                            <span v-if="productoFabricado.Estado < 1">En espera</span>
                                                                            <button class="item" v-on:click="infoEtapa(productoFabricado.S_1_Requerimientos, productoFabricado.S_1_Observaciones)" data-toggle="modal" data-target="#modalDatosEtapa" data-placement="top" title="Info de esta etapa">
                                                                                <i class="fa fa-exclamation-circle"></i>
                                                                            </button>
                                                                        </td>

                                                                        <td>
                                                                            <a v-if="productoFabricado.Estado == 2" href="#modalPasoapaso" data-toggle="modal" class="btn btn-info btn-flat btn-addon m-b-10 m-l-5 btn-sm" v-on:click="editarPasoProducto(productoFabricado.Id, 3)">
                                                                                <i class="ti-plus"></i> Procesado >>
                                                                            </a>
                                                                            <span v-if="productoFabricado.Estado > 2">{{productoFabricado.S_2_Fecha_finalizado | Fecha}}</span>
                                                                            <span v-if="productoFabricado.Estado < 2">En espera</span>
                                                                            <button class="item" v-on:click="infoEtapa(productoFabricado.S_2_Requerimientos, productoFabricado.S_2_Observaciones)" data-toggle="modal" data-target="#modalDatosEtapa" data-placement="top" title="Info de esta etapa">
                                                                                <i class="fa fa-exclamation-circle"></i>
                                                                            </button>
                                                                        </td>

                                                                        <td>
                                                                            <a v-if="productoFabricado.Estado == 3" href="#modalPasoapaso" data-toggle="modal" class="btn btn-info btn-flat btn-addon m-b-10 m-l-5 btn-sm" v-on:click="editarPasoProducto(productoFabricado.Id, 4)">
                                                                                <i class="ti-plus"></i> Soldado >>
                                                                            </a>
                                                                            <span v-if="productoFabricado.Estado > 3">{{productoFabricado.S_3_Fecha_finalizado | Fecha}}</span>
                                                                            <span v-if="productoFabricado.Estado < 3">En espera</span>
                                                                            <button class="item" v-on:click="infoEtapa(productoFabricado.S_3_Requerimientos, productoFabricado.S_3_Observaciones)" data-toggle="modal" data-target="#modalDatosEtapa" data-placement="top" title="Info de esta etapa">
                                                                                <i class="fa fa-exclamation-circle"></i>
                                                                            </button>
                                                                        </td>

                                                                        <td>
                                                                            <a v-if="productoFabricado.Estado == 4" href="#modalPasoapaso" data-toggle="modal" class="btn btn-info btn-flat btn-addon m-b-10 m-l-5 btn-sm" v-on:click="editarPasoProducto(productoFabricado.Id, 5)">
                                                                                <i class="ti-plus"></i> Pintado >>
                                                                            </a>
                                                                            <span v-if="productoFabricado.Estado > 4">{{productoFabricado.S_4_Fecha_finalizado | Fecha}}</span>
                                                                            <span v-if="productoFabricado.Estado < 4"> En espera</span>
                                                                            <button class="item" v-on:click="infoEtapa(productoFabricado.S_4_Requerimientos, productoFabricado.S_4_Observaciones)" data-toggle="modal" data-target="#modalDatosEtapa" data-placement="top" title="Info de esta etapa">
                                                                                <i class="fa fa-exclamation-circle"></i>
                                                                            </button>
                                                                        </td>

                                                                        <td>
                                                                            <a v-if="productoFabricado.Estado == 5" href="#modalPasoapaso" data-toggle="modal" class="btn btn-info btn-flat btn-addon m-b-10 m-l-5 btn-sm" v-on:click="editarPasoProducto(productoFabricado.Id, 6)">
                                                                                <i class="ti-plus"></i> Rotulado >>
                                                                            </a>
                                                                            <span v-if="productoFabricado.Estado > 5">{{productoFabricado.S_5_Fecha_finalizado | Fecha}}</span>
                                                                            <span v-if="productoFabricado.Estado < 5">En espera</span>
                                                                            <button class="item" v-on:click="infoEtapa(productoFabricado.S_5_Requerimientos, productoFabricado.S_5_Observaciones)" data-toggle="modal" data-target="#modalDatosEtapa" data-placement="top" title="Info de esta etapa">
                                                                                <i class="fa fa-exclamation-circle"></i>
                                                                            </button>
                                                                        </td>

                                                                        <td>
                                                                            <a v-if="productoFabricado.Estado == 6" href="#modalPasoapaso" data-toggle="modal" class="btn btn-info btn-flat btn-addon m-b-10 m-l-5 btn-sm" v-on:click="editarPasoProducto(productoFabricado.Id, 7)">
                                                                                <i class="ti-plus"></i> Empacado >>
                                                                            </a>
                                                                            <span v-if="productoFabricado.Estado < 6">En espera</span>
                                                                            <span v-if="productoFabricado.Estado > 6">{{productoFabricado.S_6_Fecha_finalizado | Fecha}}</span>
                                                                            <button class="item" v-on:click="infoEtapa(productoFabricado.S_6_Requerimientos, productoFabricado.S_6_Observaciones)" data-toggle="modal" data-target="#modalDatosEtapa" data-placement="top" title="Info de esta etapa">
                                                                                <i class="fa fa-exclamation-circle"></i>
                                                                            </button>
                                                                        </td>

                                                                        <td>
                                                                            <a v-if="productoFabricado.Estado == 7" href="#modalPasoapaso" data-toggle="modal" class="btn btn-info btn-flat btn-addon m-b-10 m-l-5 btn-sm" v-on:click="editarPasoProducto(productoFabricado.Id, 8)">
                                                                                <i class="ti-plus"></i> Entregado >>
                                                                            </a>
                                                                            <span v-if="productoFabricado.Estado < 7">En espera</span>
                                                                            <span v-if="productoFabricado.Estado > 7">{{productoFabricado.S_7_Fecha_finalizado | Fecha}}</span>
                                                                            <button class="item" v-on:click="infoEtapa(productoFabricado.S_7_Requerimientos, productoFabricado.S_7_Observaciones)" data-toggle="modal" data-target="#modalDatosEtapa" data-placement="top" title="Info de esta etapa">
                                                                                <i class="fa fa-exclamation-circle"></i>
                                                                            </button>
                                                                        </td>

                                                                        <td>
                                                                            <a v-if="productoFabricado.Estado == 8" href="#modalPasoapaso" data-toggle="modal" class="btn btn-info btn-flat btn-addon m-b-10 m-l-5 btn-sm" v-on:click="editarPasoProducto(productoFabricado.Id, 9)">
                                                                                <i class="ti-plus"></i> Instalado >>
                                                                            </a>
                                                                            <span v-if="productoFabricado.Estado < 8">En espera</span>
                                                                            <span v-if="productoFabricado.Estado > 8">{{productoFabricado.S_8_Fecha_finalizado | Fecha}}</span>
                                                                            <button class="item" v-on:click="infoEtapa(productoFabricado.S_8_Requerimientos, productoFabricado.S_8_Observaciones)" data-toggle="modal" data-target="#modalDatosEtapa" data-placement="top" title="Info de esta etapa">
                                                                                <i class="fa fa-exclamation-circle"></i>
                                                                            </button>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <p align="center">
                                            <span class="text-secondary"><i class="fa fa-circle"></i> Normal</span>
                                            <span class="text-warning"><i class="fa fa-circle"></i> Reclamo</span>
                                            <span class="text-info"><i class="fa fa-circle"></i> Muestra</span>
                                        </p><br>
                                        <div class="card">
                                            <div class="card-header">
                                                <strong>Productos de reventa</strong>
                                            </div>

                                            <div class="card-body">
                                                <div class="bootstrap-data-table-panel col-lg-12">
                                                    <div class="table-responsive">
                                                        <div class="table-responsive">
                                                            <table id="table2excel" class="table table-striped">
                                                                <thead>
                                                                    <tr>
                                                                        <th>
                                                                            <!-- <h2>
                                                                                <a v-if="ventaDatos.Estado < 7" href="#productosreventaModal" data-toggle="modal" class="btn btn-success btn-flat btn-addon" v-on:click="limpiarFormularioProductos()">
                                                                                    <i class="ti-plus"></i> +
                                                                                </a>
                                                                            </h2> -->
                                                                        </th>
                                                                        <th>Producto</th>
                                                                        <th>Cantidad</th>
                                                                        <th>Descripción</th>
                                                                        <th>Entrega</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr v-for="productoReventa in listaProductosReventaLote">
                                                                        <td>
                                                                            <div class="round-img">
                                                                                <img v-if="productoReventa.Imagen != null" v-bind:src="'<?php echo base_url(); ?>uploads/imagenes/'+productoReventa.Imagen" width="60px">
                                                                                <!-- <img v-else src="https://freeiconshop.com/wp-content/uploads/edd/person-flat.png" width="50px" alt=""> -->
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <h4> {{productoReventa.Nombre_item}}</h4>
                                                                        </td>
                                                                        <td align="center">
                                                                            <h4>{{productoReventa.Cantidad}}</h4>
                                                                        </td>
                                                                        <td> {{ productoReventa.Descripcion }} </td>


                                                                        <td>
                                                                            <div class="table-data-feature" v-if="ventaDatos.Estado < 7">
                                                                                <button v-if="productoReventa.Entregado == 0" class="btn btn-success" v-on:click="prodReventaEntregado(productoReventa.Stock_id)">
                                                                                    <i class="ti-plus"></i> Marcar como entregado
                                                                                </button>
                                                                                <span v-if="productoReventa.Entregado == 1">{{productoReventa.Fecha_entregado | Fecha}}</span>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Stock necesario para la producción -->
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header text-warning">
                                        <strong>Productos para constrol de stock</strong>
                                    </div>
                                    <div class="card-body">
                                        <p>Aca se va a mostrar el despiece de los productos de fabricación.</p>
                                        <!-- <div class="bootstrap-data-table-panel col-lg-12">
                                            <div class="table-responsive">
                                                <div class="table-responsive">
                                                    <table id="table2excel" class="table table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>Producto</th>
                                                                <th>Cantidad</th>
                                                                <th>Fecha</th>
                                                                <th>Descripción</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr v-for="producto in listaproductosUsados">
                                                                <td>{{producto.Nombre_item}}</td>
                                                                <td>{{producto.Cantidad}}</td>
                                                                <td>{{producto.Fecha_hora | FechaTimeBD}}</td>
                                                                <td>{{producto.Descripcion}}</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div> -->
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- SECCION PRODUCCIÒN -->
                        <div class="row" v-show="mostrar == '4'">
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="card">
                                            <div class="card-header">
                                                <strong>Estación actual del lote</strong>
                                            </div>
                                            <div class="card-body">
                                                <h1 v-if="ventaDatos.Estado == 1">Control de materiales recibidos</h1>
                                                <h1 v-if="ventaDatos.Estado == 2">Proceso de materiales</h1>
                                                <h1 v-if="ventaDatos.Estado == 3">Soldadura</h1>
                                                <h1 v-if="ventaDatos.Estado == 4">Pintura</h1>
                                                <h1 v-if="ventaDatos.Estado == 5">Rotulación</h1>
                                                <h1 v-if="ventaDatos.Estado == 6">Empaque</h1>
                                                <h1 v-if="ventaDatos.Estado == 7">Logística</h1>
                                                <h1 v-if="ventaDatos.Estado == 8">Instalación</h1>
                                                <h1 v-if="ventaDatos.Estado > 8">Producción Finalizada</h1>
                                                <hr>
                                                <p>
                                                    <a v-if="ventaDatos.Estado < 9" href="#" class="btn btn-warning " v-on:click="cambiar_estado_venta(0)">
                                                        Avanzar lote a siguiente etapa >>
                                                    </a>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-8">
                                        <div class="card">
                                            <div class="card-header">
                                                <strong>Responsables</strong>
                                            </div>
                                            <div class="card-body">
                                                <div class="bootstrap-data-table-panel col-lg-12">
                                                    <div class="table-responsive">
                                                        <div class="table-responsive">
                                                            <table id="table2excel" class="table table-striped">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Planificación Inicial</th>
                                                                        <th>Planificación Final</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td>{{ventaDatos.Nombre_resp_1}}</td>
                                                                        <td>{{ventaDatos.Nombre_resp_2}}</td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <!-- <div class="card">
                                            <div class="card-header">
                                                <strong>Productos del lote</strong>
                                            </div>
                                            <div class="card-body">
                                                <div class="bootstrap-data-table-panel col-lg-12">
                                                    <div class="table-responsive">
                                                        <div class="table-responsive">
                                                            <table id="table2excel" class="table table-striped">
                                                                <thead>
                                                                    <tr>
                                                                        <th>
                                                                            
                                                                        </th>
                                                                        <th>Código</th>
                                                                        <th>Producto</th>
                                                                        <th>Cantidad</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr v-for="productos in listaResumenProductos">
                                                                        <td></td>
                                                                        <td>{{productos.Codigo_interno}}</td>
                                                                        <td>{{productos.Nombre_producto}}</td>
                                                                        <td>{{productos.Cantidad}}</td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> -->
                                        <div class="card">
                                            <div class="card-header">
                                                <strong>Seguimiento de la producción</strong>
                                            </div>

                                            <div class="card-body">
                                                <div class="bootstrap-data-table-panel col-lg-12">
                                                    <div class="table-responsive">
                                                        <div class="table-responsive">
                                                            <table id="table2excel" class="table table-striped" style="overflow-x: visible;">
                                                                <thead>
                                                                    <tr>
                                                                        <td colspan="2">
                                                                            <h2>
                                                                                <a v-if="ventaDatos.Estado < 7" href="#modalProductos" data-toggle="modal" class="btn btn-success btn-flat btn-addon" v-on:click="limpiarFormularioProductos()">
                                                                                    <i class="ti-plus"></i> +
                                                                                </a>
                                                                            </h2>
                                                                        </td>

                                                                        <th>Producto</th>
                                                                        <th>Código</th>
                                                                        <th>Cantidad</th>
                                                                        <th>Precio</th>
                                                                        <th>Sub Total</th>
                                                                        <th>Stock</th>
                                                                        <th>Proceso materiales</th>
                                                                        <th>Soldadura</th>
                                                                        <th>Pintura</th>
                                                                        <th>Rotulación</th>
                                                                        <th>Empaque</th>
                                                                        <th>Logística</th>
                                                                        <th>Instalación</th>

                                                                        <th></th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr v-for="productoFabricado in listaProductosVendidos">
                                                                        <td>
                                                                            <img v-if="productoFabricado.Imagen != null" v-bind:src="'<?php echo base_url(); ?>uploads/imagenes/'+productoFabricado.Imagen" width="60px">
                                                                        </td>
                                                                        <td>
                                                                            <span v-if="productoFabricado.Tipo_produccion == 1" class="text-secondary"><i class="fa fa-circle"></i></span>
                                                                            <span v-if="productoFabricado.Tipo_produccion == 2" class="text-warning"><i class="fa fa-circle"></i></span>
                                                                            <span v-if="productoFabricado.Tipo_produccion == 3" class="text-info"><i class="fa fa-circle"></i></span>
                                                                        </td>
                                                                        <td>
                                                                            <h4> {{productoFabricado.Nombre_producto}}</h4>
                                                                        </td>
                                                                        <td>
                                                                            {{productoFabricado.Codigo_interno}}
                                                                        </td>
                                                                        <td align="center">
                                                                            <h4>{{productoFabricado.Cantidad}}</h4>
                                                                        </td>
                                                                        <td>
                                                                            <h4> ${{productoFabricado.Precio_venta_producto | Moneda}}</h4>
                                                                        </td>
                                                                        <td>
                                                                            <h4> ${{productoFabricado.Precio_venta_producto * productoFabricado.Cantidad | Moneda}}</h4>
                                                                        </td>
                                                                        <td>
                                                                            <a v-if="productoFabricado.Estado == 1" href="#modalPasoapaso" data-toggle="modal" class="btn btn-info btn-flat btn-addon m-b-10 m-l-5 btn-sm" v-on:click="editarPasoProducto(productoFabricado.Id, 2)">
                                                                                <i class="ti-plus"></i> Stock OK >>
                                                                            </a>
                                                                            <span v-if="productoFabricado.Estado > 1"> {{productoFabricado.S_1_Fecha_finalizado | Fecha}}</span>
                                                                            <span v-if="productoFabricado.Estado < 1">En espera</span>
                                                                            <button class="item" v-on:click="infoEtapa(productoFabricado.S_1_Requerimientos, productoFabricado.S_1_Observaciones)" data-toggle="modal" data-target="#modalDatosEtapa" data-placement="top" title="Info de esta etapa">
                                                                                <i class="fa fa-exclamation-circle"></i>
                                                                            </button>
                                                                        </td>

                                                                        <td>
                                                                            <a v-if="productoFabricado.Estado == 2" href="#modalPasoapaso" data-toggle="modal" class="btn btn-info btn-flat btn-addon m-b-10 m-l-5 btn-sm" v-on:click="editarPasoProducto(productoFabricado.Id, 3)">
                                                                                <i class="ti-plus"></i> Procesado >>
                                                                            </a>
                                                                            <span v-if="productoFabricado.Estado > 2">{{productoFabricado.S_2_Fecha_finalizado | Fecha}}</span>
                                                                            <span v-if="productoFabricado.Estado < 2">En espera</span>
                                                                            <button class="item" v-on:click="infoEtapa(productoFabricado.S_2_Requerimientos, productoFabricado.S_2_Observaciones)" data-toggle="modal" data-target="#modalDatosEtapa" data-placement="top" title="Info de esta etapa">
                                                                                <i class="fa fa-exclamation-circle"></i>
                                                                            </button>
                                                                        </td>

                                                                        <td>
                                                                            <a v-if="productoFabricado.Estado == 3" href="#modalPasoapaso" data-toggle="modal" class="btn btn-info btn-flat btn-addon m-b-10 m-l-5 btn-sm" v-on:click="editarPasoProducto(productoFabricado.Id, 4)">
                                                                                <i class="ti-plus"></i> Soldado >>
                                                                            </a>
                                                                            <span v-if="productoFabricado.Estado > 3">{{productoFabricado.S_3_Fecha_finalizado | Fecha}}</span>
                                                                            <span v-if="productoFabricado.Estado < 3">En espera</span>
                                                                            <button class="item" v-on:click="infoEtapa(productoFabricado.S_3_Requerimientos, productoFabricado.S_3_Observaciones)" data-toggle="modal" data-target="#modalDatosEtapa" data-placement="top" title="Info de esta etapa">
                                                                                <i class="fa fa-exclamation-circle"></i>
                                                                            </button>
                                                                        </td>

                                                                        <td>
                                                                            <a v-if="productoFabricado.Estado == 4" href="#modalPasoapaso" data-toggle="modal" class="btn btn-info btn-flat btn-addon m-b-10 m-l-5 btn-sm" v-on:click="editarPasoProducto(productoFabricado.Id, 5)">
                                                                                <i class="ti-plus"></i> Pintado >>
                                                                            </a>
                                                                            <span v-if="productoFabricado.Estado > 4">{{productoFabricado.S_4_Fecha_finalizado | Fecha}}</span>
                                                                            <span v-if="productoFabricado.Estado < 4"> En espera</span>
                                                                            <button class="item" v-on:click="infoEtapa(productoFabricado.S_4_Requerimientos, productoFabricado.S_4_Observaciones)" data-toggle="modal" data-target="#modalDatosEtapa" data-placement="top" title="Info de esta etapa">
                                                                                <i class="fa fa-exclamation-circle"></i>
                                                                            </button>
                                                                        </td>

                                                                        <td>
                                                                            <a v-if="productoFabricado.Estado == 5" href="#modalPasoapaso" data-toggle="modal" class="btn btn-info btn-flat btn-addon m-b-10 m-l-5 btn-sm" v-on:click="editarPasoProducto(productoFabricado.Id, 6)">
                                                                                <i class="ti-plus"></i> Rotulado >>
                                                                            </a>
                                                                            <span v-if="productoFabricado.Estado > 5">{{productoFabricado.S_5_Fecha_finalizado | Fecha}}</span>
                                                                            <span v-if="productoFabricado.Estado < 5">En espera</span>
                                                                            <button class="item" v-on:click="infoEtapa(productoFabricado.S_5_Requerimientos, productoFabricado.S_5_Observaciones)" data-toggle="modal" data-target="#modalDatosEtapa" data-placement="top" title="Info de esta etapa">
                                                                                <i class="fa fa-exclamation-circle"></i>
                                                                            </button>
                                                                        </td>

                                                                        <td>
                                                                            <a v-if="productoFabricado.Estado == 6" href="#modalPasoapaso" data-toggle="modal" class="btn btn-info btn-flat btn-addon m-b-10 m-l-5 btn-sm" v-on:click="editarPasoProducto(productoFabricado.Id, 7)">
                                                                                <i class="ti-plus"></i> Empacado >>
                                                                            </a>
                                                                            <span v-if="productoFabricado.Estado < 6">En espera</span>
                                                                            <span v-if="productoFabricado.Estado > 6">{{productoFabricado.S_6_Fecha_finalizado | Fecha}}</span>
                                                                            <button class="item" v-on:click="infoEtapa(productoFabricado.S_6_Requerimientos, productoFabricado.S_6_Observaciones)" data-toggle="modal" data-target="#modalDatosEtapa" data-placement="top" title="Info de esta etapa">
                                                                                <i class="fa fa-exclamation-circle"></i>
                                                                            </button>
                                                                        </td>

                                                                        <td>
                                                                            <a v-if="productoFabricado.Estado == 7" href="#modalPasoapaso" data-toggle="modal" class="btn btn-info btn-flat btn-addon m-b-10 m-l-5 btn-sm" v-on:click="editarPasoProducto(productoFabricado.Id, 8)">
                                                                                <i class="ti-plus"></i> Entregado >>
                                                                            </a>
                                                                            <span v-if="productoFabricado.Estado < 7">En espera</span>
                                                                            <span v-if="productoFabricado.Estado > 7">{{productoFabricado.S_7_Fecha_finalizado | Fecha}}</span>
                                                                            <button class="item" v-on:click="infoEtapa(productoFabricado.S_7_Requerimientos, productoFabricado.S_7_Observaciones)" data-toggle="modal" data-target="#modalDatosEtapa" data-placement="top" title="Info de esta etapa">
                                                                                <i class="fa fa-exclamation-circle"></i>
                                                                            </button>
                                                                        </td>

                                                                        <td>
                                                                            <a v-if="productoFabricado.Estado == 8" href="#modalPasoapaso" data-toggle="modal" class="btn btn-info btn-flat btn-addon m-b-10 m-l-5 btn-sm" v-on:click="editarPasoProducto(productoFabricado.Id, 9)">
                                                                                <i class="ti-plus"></i> Instalado >>
                                                                            </a>
                                                                            <span v-if="productoFabricado.Estado < 8">En espera</span>
                                                                            <span v-if="productoFabricado.Estado > 8">{{productoFabricado.S_8_Fecha_finalizado | Fecha}}</span>
                                                                            <button class="item" v-on:click="infoEtapa(productoFabricado.S_8_Requerimientos, productoFabricado.S_8_Observaciones)" data-toggle="modal" data-target="#modalDatosEtapa" data-placement="top" title="Info de esta etapa">
                                                                                <i class="fa fa-exclamation-circle"></i>
                                                                            </button>
                                                                        </td>

                                                                        <td>
                                                                            <div class="table-data-feature">
                                                                                <button class="item" v-on:click="editarFormularioProductos(productoFabricado)" data-toggle="modal" data-target="#modalProductos" data-placement="top" title="Editar">
                                                                                    <i class="zmdi zmdi-edit"></i>
                                                                                </button>
                                                                                <button class="item" v-on:click="editarAnularProducto(productoFabricado)" data-toggle="modal" data-target="#modalAnularProducto" data-placement="top" title="Anular">
                                                                                    <i class="fa fa-ban"></i>
                                                                                </button>
                                                                                <?php
                                                                                if ($this->session->userdata('Rol_acceso') > 4) {
                                                                                    echo '
                                                                                        <button v-on:click="desactivarProductoVenta(productoFabricado.Id)" class="item" data-toggle="tooltip" data-placement="top" title="Eliminar">
                                                                                            <i class="zmdi zmdi-delete"></i>
                                                                                        </button>';
                                                                                }
                                                                                ?>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <p align="center">
                                            <span class="text-secondary"><i class="fa fa-circle"></i> Normal</span>
                                            <span class="text-warning"><i class="fa fa-circle"></i> Reclamo</span>
                                            <span class="text-info"><i class="fa fa-circle"></i> Muestra</span>
                                        </p>
                                        <div class="card">
                                            <div class="card-header">
                                                <strong>Productos de reventa</strong>
                                            </div>

                                            <div class="card-body">
                                                <div class="bootstrap-data-table-panel col-lg-12">
                                                    <div class="table-responsive">
                                                        <div class="table-responsive">
                                                            <table id="table2excel" class="table table-striped">
                                                                <thead>
                                                                    <tr>
                                                                        <th>
                                                                            <h2>
                                                                                <a v-if="ventaDatos.Estado < 9" href="#productosreventaModal" data-toggle="modal" class="btn btn-success btn-flat btn-addon" v-on:click="limpiarFormularioProductos()">
                                                                                    <i class="ti-plus"></i> +
                                                                                </a>
                                                                            </h2>
                                                                        </th>
                                                                        <th>Producto</th>
                                                                        <th>Cantidad</th>
                                                                        <th>P. Venta</th>
                                                                        <th>Subtotal</th>
                                                                        <th>Descripción</th>
                                                                        <th>Entrega</th>
                                                                        <th></th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr v-for="productoReventa in listaProductosReventaLote">
                                                                        <td>
                                                                            <div class="round-img">
                                                                                <img v-if="productoReventa.Imagen != null" v-bind:src="'<?php echo base_url(); ?>uploads/imagenes/'+productoReventa.Imagen" width="60px">
                                                                                <!-- <img v-else src="https://freeiconshop.com/wp-content/uploads/edd/person-flat.png" width="50px" alt=""> -->
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <h4> {{productoReventa.Nombre_item}}</h4>
                                                                        </td>
                                                                        <td align="center">
                                                                            <h4>{{productoReventa.Cantidad}}</h4>
                                                                        </td>
                                                                        <td>
                                                                            <h4> ${{productoReventa.Precio_venta_producto | Moneda}}</h4>
                                                                        </td>
                                                                        <td>
                                                                            <h4> ${{productoReventa.Precio_venta_producto * productoReventa.Cantidad| Moneda}}</h4>
                                                                        </td>
                                                                        <td> {{ productoReventa.Descripcion }} </td>

                                                                        <td>
                                                                            <div class="table-data-feature" v-if="ventaDatos.Estado < 7">
                                                                                <button v-if="productoReventa.Entregado == 0" class="btn" v-on:click="prodReventaEntregado(productoReventa.Stock_id)">
                                                                                    <i class="ti-plus"></i> Marcar como entregado
                                                                                </button>
                                                                                <span v-if="productoReventa.Entregado == 1">{{productoReventa.Fecha_entregado | Fecha}}</span>
                                                                            </div>
                                                                        </td>

                                                                        <td>
                                                                            <div class="table-data-feature" v-if="ventaDatos.Estado < 9">
                                                                                <button class="item" v-if="productoReventa.Entregado == 0" v-on:click="editarFormularioProductosReventa(productoReventa)" data-toggle="modal" data-target="#productosreventaModal" data-placement="top" title="Editar">
                                                                                    <i class="zmdi zmdi-edit"></i>
                                                                                </button>
                                                                                <button v-show="productoReventa.Cantidad == 0" class="item" v-on:click="eliminarProductoReventa(productoReventa.Stock_id)">
                                                                                    <i class="fa fa-ban"></i>
                                                                                </button>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- SECCION LOGISTICA ____________________ -->
                        <div class="row" v-show="mostrar == '5'">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <strong>Información para Logística</strong>
                                    </div>
                                    <div class="card-body">
                                        <h3>{{ventaDatos.Info_logistica}}</h3>
                                    </div>

                                    <div v-if="ventaDatos.Estado == 7" class="card-body">
                                        <a href="#" class="btn btn-warning " v-on:click="cambiar_estado_venta(0)">
                                            LOGÍSTICA FINALIZADA
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <strong>Publicar reporte</strong>
                                    </div>
                                    <div class="card-body">
                                        <p align="right">
                                        <form class="form-horizontal" action="post" v-on:submit.prevent="crearSeguimiento(3,3)">
                                            <!--   -->
                                            <div class="form-group">
                                                <label class="control-label">Datos del seguimiento</label>
                                                <textarea class="form-control" rows="3" placeholder="" v-model="seguimientoData.Descripcion" required></textarea>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <input @change="archivoSeleccionado" type="file" class="form-control" name="Imagen">
                                                </div>
                                                <div class="col-sm-12" v-if="seguimientoData.Url_archivo != null">
                                                    Archivo previamente cargado
                                                    <a target="_blank" v-bind:href="'<?php echo base_url(); ?>uploads/imagenes/'+seguimientoData.Url_archivo"> Ver archivo</a>
                                                </div>
                                            </div>
                                            <div class="form-group" v-show="preloader == 1">
                                                <p align="center">
                                                    EL ARCHIVO SE ESTA CARGANDO. <br> No cerrar la ventana hasta finalizada la carga, dependiendo del peso del archivo puede demorar algunos minutos.
                                                </p>
                                                <p align="center">
                                                    <img src="http://grupopignatta.com.ar/images/preloader.gif" alt="">
                                                </p>
                                            </DIV>
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-success" :disabled="preloader == 1">{{texto_boton}}</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header">
                                        <strong>Seguimiento</strong>
                                    </div>
                                    <div class="card-body">
                                        <div class="bootstrap-data-table-panel col-lg-12">
                                            <div class="table-responsive">
                                                <table id="table2excel" class="table table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>Fecha</th>
                                                            <th>Categoría</th>
                                                            <th>Descripcion</th>
                                                            <th>Archivo</th>
                                                            <th>Autor</th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="seguimiento in listaSeguimiento">
                                                            <td>{{seguimiento.Fecha | FechaTimeBD}}</td>

                                                            <td v-if="seguimiento.Categoria_seguimiento == 0">Sin categoría</td>
                                                            <td v-if="seguimiento.Categoria_seguimiento == 1">Compras</td>
                                                            <td v-if="seguimiento.Categoria_seguimiento == 2">Producción</td>
                                                            <td v-if="seguimiento.Categoria_seguimiento == 3">Logística</td>
                                                            <td v-if="seguimiento.Categoria_seguimiento == 4">Instalación</td>
                                                            <td v-if="seguimiento.Categoria_seguimiento == 5">Cobranza</td>

                                                            <td>{{seguimiento.Descripcion}}</td>
                                                            <td><a v-if="seguimiento.Url_archivo != null" target="_blank" v-bind:href="'<?php echo base_url(); ?>uploads/imagenes/'+seguimiento.Url_archivo"> Ver archivo</a></td>
                                                            <td>{{seguimiento.Nombre}}</td>
                                                            <td>
                                                                <a href="#modalSeguimiento" data-toggle="modal" v-on:click="editarFormularioSeguimiento(seguimiento)">
                                                                    <i class="fa fa-edit"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- SECCION INSTALACIÒN _____________  -->
                        <div class="row" v-show="mostrar == '6'">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <strong>Información para Instalaciones</strong>
                                    </div>
                                    <div class="card-body">
                                        <h3>{{ventaDatos.Info_instalaciones}}</h3>
                                    </div>
                                    <div v-if="ventaDatos.Estado == 8" class="card-body">
                                        <a href="#" class="btn btn-warning" v-on:click="cambiar_estado_venta( 
                                                            calcularMontosVentas(
                                                                sumarProductos(listaProductosVendidos), 
                                                                ventaDatos.Valor_logistica, 
                                                                ventaDatos.Valor_instalacion, 
                                                                sumarProductos(listaProductosReventaLote), 
                                                                listaMovimientos.Total,
                                                                ventaDatos.Recargo, 
                                                                ventaDatos.Descuento
                                                            )
                                        )">
                                            INSTALACIÓN FINALIZADA
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <strong>Publicar reporte</strong>
                                    </div>
                                    <div class="card-body">
                                        <p align="right">
                                        <form class="form-horizontal" action="post" v-on:submit.prevent="crearSeguimiento(4,4)">
                                            <!--   -->
                                            <div class="form-group">
                                                <label class="control-label">Datos del seguimiento</label>
                                                <textarea class="form-control" rows="3" placeholder="" v-model="seguimientoData.Descripcion" required></textarea>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <input @change="archivoSeleccionado" type="file" class="form-control" name="Imagen">
                                                </div>
                                                <div class="col-sm-12" v-if="seguimientoData.Url_archivo != null">
                                                    Archivo previamente cargado
                                                    <a target="_blank" v-bind:href="'<?php echo base_url(); ?>uploads/imagenes/'+seguimientoData.Url_archivo"> Ver archivo</a>
                                                </div>
                                            </div>
                                            <div class="form-group" v-show="preloader == 1">
                                                <p align="center">
                                                    EL ARCHIVO SE ESTA CARGANDO. <br> No cerrar la ventana hasta finalizada la carga, dependiendo del peso del archivo puede demorar algunos minutos.
                                                </p>
                                                <p align="center">
                                                    <img src="http://grupopignatta.com.ar/images/preloader.gif" alt="">
                                                </p>
                                            </DIV>
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-success" :disabled="preloader == 1">{{texto_boton}}</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header">
                                        <strong>Seguimiento</strong>
                                    </div>
                                    <div class="card-body">
                                        <div class="bootstrap-data-table-panel col-lg-12">
                                            <div class="table-responsive">
                                                <table id="table2excel" class="table table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>Fecha</th>
                                                            <th>Categoría</th>
                                                            <th>Descripcion</th>
                                                            <th>Archivo</th>
                                                            <th>Autor</th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="seguimiento in listaSeguimiento">
                                                            <td>{{seguimiento.Fecha | FechaTimeBD}}</td>

                                                            <td v-if="seguimiento.Categoria_seguimiento == 0">Sin categoría</td>
                                                            <td v-if="seguimiento.Categoria_seguimiento == 1">Compras</td>
                                                            <td v-if="seguimiento.Categoria_seguimiento == 2">Producción</td>
                                                            <td v-if="seguimiento.Categoria_seguimiento == 3">Logística</td>
                                                            <td v-if="seguimiento.Categoria_seguimiento == 4">Instalación</td>
                                                            <td v-if="seguimiento.Categoria_seguimiento == 5">Cobranza</td>

                                                            <td>{{seguimiento.Descripcion}}</td>
                                                            <td><a v-if="seguimiento.Url_archivo != null" target="_blank" v-bind:href="'<?php echo base_url(); ?>uploads/imagenes/'+seguimiento.Url_archivo"> Ver archivo</a></td>
                                                            <td>{{seguimiento.Nombre}}</td>
                                                            <td>
                                                                <a href="#modalSeguimiento" data-toggle="modal" v-on:click="editarFormularioSeguimiento(seguimiento)">
                                                                    <i class="fa fa-edit"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- SECCION COBRANZA ___________________   -->
                        <div class="row" v-show="mostrar == '7'">
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <strong>Información para Cobranza</strong>
                                            </div>
                                            <div class="card-body">
                                                <h3 align="center">{{ventaDatos.Info_cobranza}}</h3>
                                            </div>
                                            <div v-if="ventaDatos.Estado == 9" class="card-body">
                                                <a href="#" class="btn btn-warning " v-on:click="cambiar_estado_venta(cambiar_estado_venta( 
                                                            calcularMontosVentas(
                                                                sumarProductos(listaProductosVendidos), 
                                                                ventaDatos.Valor_logistica, 
                                                                ventaDatos.Valor_instalacion, 
                                                                sumarProductos(listaProductosReventaLote), 
                                                                listaMovimientos.Total,
                                                                ventaDatos.Recargo, 
                                                                ventaDatos.Descuento
                                                            ))
                                        )">
                                                    COBRANZA FINALIZADA
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="card">
                                            <div class="card-header">
                                                <strong>Total productos</strong>
                                            </div>
                                            <div class="card-body">
                                                <h3 align="center"> ${{ sumarProductos(listaProductosVendidos) + sumarProductos(listaProductosReventaLote) | Moneda}}</h3><br>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
                                        <div class="card">
                                            <div class="card-header">
                                                <strong>Logística</strong>
                                            </div>
                                            <div class="card-body">
                                                <h3 align="center"> ${{ventaDatos.Valor_logistica | Moneda}}</h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
                                        <div class="card">
                                            <div class="card-header">
                                                <strong>Instalación</strong>
                                            </div>
                                            <div class="card-body">
                                                <h3 align="center"> ${{ventaDatos.Valor_instalacion | Moneda}}</h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
                                        <div class="card">
                                            <div class="card-header">
                                                <strong>Recargo</strong>
                                            </div>
                                            <div class="card-body">
                                                <h3 align="center"> ${{ventaDatos.Recargo | Moneda}}</h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
                                        <div class="card">
                                            <div class="card-header">
                                                <strong>Descuento</strong>
                                            </div>
                                            <div class="card-body">
                                                <h3 align="center"> ${{ventaDatos.Descuento | Moneda}}</h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="card">
                                            <div class="card-header">
                                                <strong>Total a cobrar</strong>
                                            </div>
                                            <div class="card-body">
                                                <h3 align="center">
                                                    ${{
                                                        calcularMontosVentas(
                                                            sumarProductos(listaProductosVendidos), 
                                                            ventaDatos.Valor_logistica, 
                                                            ventaDatos.Valor_instalacion, 
                                                            sumarProductos(listaProductosReventaLote), 
                                                            0, 
                                                            ventaDatos.Recargo, 
                                                            ventaDatos.Descuento
                                                        )   | Moneda}}
                                                </h3>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="card">
                                            <div class="card-header">
                                                <strong>Total cobrado</strong>
                                            </div>
                                            <div class="card-body">
                                                <h3 align="center"> ${{listaMovimientos.Total | Moneda}}</h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="card">
                                            <div class="card-header">
                                                <strong>Saldo</strong>
                                            </div>
                                            <div class="card-body">
                                                <h3 align="center">
                                                    ${{
                                                        calcularMontosVentas(
                                                            sumarProductos(listaProductosVendidos), 
                                                            ventaDatos.Valor_logistica, 
                                                            ventaDatos.Valor_instalacion, 
                                                            sumarProductos(listaProductosReventaLote), 
                                                            listaMovimientos.Total,
                                                            ventaDatos.Recargo, 
                                                            ventaDatos.Descuento
                                                        ) | Moneda}}
                                                </h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="card">
                                            <div class="card-header">
                                                <strong>Productos propios</strong>
                                            </div>
                                            <div class="card-body">

                                                <div class="bootstrap-data-table-panel col-lg-12">
                                                    <div class="table-responsive">
                                                        <div class="table-responsive">
                                                            <table id="table2excel" class="table table-striped">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Código</th>
                                                                        <th>Producto</th>

                                                                        <th>Cantidad</th>
                                                                        <th>Precio</th>
                                                                        <th>Subtotal</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr v-for="productos in listaResumenProductos">
                                                                        <td>{{productos.Codigo_interno}}</td>
                                                                        <td>{{productos.Nombre_producto}}</td>
                                                                        <td align="center">{{productos.Cantidad}}</td>
                                                                        <td align="right">${{productos.Precio_venta | Moneda}}</td>
                                                                        <td align="right">${{productos.Precio_venta * productos.Cantidad | Moneda}}</td>
                                                                    </tr>
                                                                </tbody>
                                                                <tfoot>
                                                                    <tr>
                                                                        <th></th>
                                                                        <th></th>
                                                                        <th></th>
                                                                        <th></th>
                                                                        <th>
                                                                            <h4 align="right">${{sumarProductos(listaProductosVendidos) | Moneda}}</h4>
                                                                        </th>
                                                                    </tr>
                                                                </tfoot>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card">
                                            <div class="card-header">
                                                <strong>Productos de reventa</strong>
                                            </div>
                                            <div class="card-body">

                                                <div class="bootstrap-data-table-panel col-lg-12">
                                                    <div class="table-responsive">
                                                        <div class="table-responsive">
                                                            <table id="table2excel" class="table table-striped">
                                                                <thead>
                                                                    <tr>

                                                                        <th>Producto</th>
                                                                        <th>Cantidad</th>
                                                                        <th>Precio</th>
                                                                        <th>Subtotal</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr v-for="productosRev in listaProductosReventaLote">
                                                                        <td>{{productosRev.Nombre_item}}</td>

                                                                        <td align="right">{{productosRev.Cantidad}}</td>
                                                                        <td align="right">${{productosRev.Precio_venta_producto | Moneda}}</td>
                                                                        <td align="right">${{productosRev.Precio_venta_producto * productosRev.Cantidad | Moneda}}</td>
                                                                        <!-- <td>{{productosRev.Subtotal}}</td> -->
                                                                    </tr>
                                                                </tbody>
                                                                <tfoot>
                                                                    <tr>
                                                                        <th></th>
                                                                        <th></th>
                                                                        <th></th>
                                                                        <th>
                                                                            <h4 align="right">${{sumarProductos(listaProductosReventaLote) | Moneda}}</h4>
                                                                        </th>
                                                                    </tr>
                                                                </tfoot>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- PAGOS -->
                                        <div class="card">
                                            <div class="card-header">
                                                <strong>Pagos</strong>
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table id="table2excel" class="table table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>
                                                                    <button data-toggle="modal" data-target="#modalEfectivo" v-on:click="limpiarFormularioMovimiento()">
                                                                        <i class="fa fa-plus-circle text-success"></i>
                                                                    </button>
                                                                </th>
                                                                <th>Monto</th>
                                                                <th>Fecha</th>
                                                                <th>Modalidad</th>
                                                                <th>Observaciones</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr v-for="movimiento in listaMovimientos.Datos">
                                                                <td>
                                                                    <button v-on:click="desactivarAlgo(movimiento.Id, 'tbl_cobros')" class="item" data-toggle="tooltip" data-placement="top" title="Eliminar">
                                                                        <i class="zmdi zmdi-delete"></i>
                                                                    </button>
                                                                </td>
                                                                <td>${{movimiento.Monto | Moneda}}</td>
                                                                <td>{{movimiento.Fecha_ejecutado | Fecha}}</td>
                                                                <td>{{movimiento.Modalidad_pago}}</td>
                                                                <td>{{movimiento.Observaciones}}</td>
                                                            </tr>
                                                        </tbody>
                                                        <tfoot>
                                                            <th></th>
                                                            <th>
                                                                <h3>${{listaMovimientos.Total | Moneda}}</h3>
                                                            </th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="card">
                                            <div class="card-header">
                                                <strong>Publicar reporte</strong>
                                            </div>
                                            <div class="card-body">
                                                <p align="right">
                                                <form class="form-horizontal" action="post" v-on:submit.prevent="crearSeguimiento(5,5)">
                                                    <!--   -->
                                                    <div class="row">
                                                        <div class="col-8">
                                                            <div class="form-group">
                                                                <label class="control-label">Datos del seguimiento</label>
                                                                <textarea class="form-control" rows="3" placeholder="" v-model="seguimientoData.Descripcion" required></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="col-4">
                                                            <div class="form-group">
                                                                <label class="control-label">Subir archivo</label>
                                                                <div class="col-sm-12">
                                                                    <input @change="archivoSeleccionado" type="file" class="form-control" name="Imagen">
                                                                </div>
                                                                <div class="col-sm-12" v-if="seguimientoData.Url_archivo != null">
                                                                    Archivo previamente cargado
                                                                    <a target="_blank" v-bind:href="'<?php echo base_url(); ?>uploads/imagenes/'+seguimientoData.Url_archivo"> Ver archivo</a>
                                                                </div>
                                                            </div>
                                                            <div class="form-group" v-show="preloader == 1">
                                                                <p align="center">
                                                                    EL ARCHIVO SE ESTA CARGANDO. <br> No cerrar la ventana hasta finalizada la carga, dependiendo del peso del archivo puede demorar algunos minutos.
                                                                </p>
                                                                <p align="center">
                                                                    <img src="http://grupopignatta.com.ar/images/preloader.gif" alt="">
                                                                </p>
                                                            </DIV>
                                                            <div class="form-group">
                                                                <button type="submit" class="btn btn-success" :disabled="preloader == 1">Publicar reporte</button>
                                                            </div>
                                                        </div>
                                                    </div>



                                                </form>
                                            </div>
                                            <div class="card-header">
                                                <strong>Seguimiento</strong>
                                            </div>
                                            <div class="card-body">
                                                <div class="bootstrap-data-table-panel col-lg-12">
                                                    <div class="table-responsive">
                                                        <table id="table2excel" class="table table-striped">
                                                            <thead>
                                                                <tr>
                                                                    <th>Fecha</th>
                                                                    <th>Categoría</th>
                                                                    <th>Descripcion</th>
                                                                    <th>Archivo</th>
                                                                    <th>Autor</th>
                                                                    <th></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr v-for="seguimiento in listaSeguimiento">
                                                                    <td>{{seguimiento.Fecha | FechaTimeBD}}</td>

                                                                    <td v-if="seguimiento.Categoria_seguimiento == 0">Sin categoría</td>
                                                                    <td v-if="seguimiento.Categoria_seguimiento == 1">Compras</td>
                                                                    <td v-if="seguimiento.Categoria_seguimiento == 2">Producción</td>
                                                                    <td v-if="seguimiento.Categoria_seguimiento == 3">Logística</td>
                                                                    <td v-if="seguimiento.Categoria_seguimiento == 4">Instalación</td>
                                                                    <td v-if="seguimiento.Categoria_seguimiento == 5">Cobranza</td>

                                                                    <td>{{seguimiento.Descripcion}}</td>
                                                                    <td><a v-if="seguimiento.Url_archivo != null" target="_blank" v-bind:href="'<?php echo base_url(); ?>uploads/imagenes/'+seguimiento.Url_archivo"> Ver archivo</a></td>
                                                                    <td>{{seguimiento.Nombre}}</td>
                                                                    <td>
                                                                        <a v-if="" href="#modalSeguimiento" data-toggle="modal" v-on:click="editarFormularioSeguimiento(seguimiento)">
                                                                            <i class="fa fa-edit"></i>
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>



                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal SEGUIMIENTO-->
    <div class="modal fade" id="modalSeguimiento" tabindex="-1" role="dialog" aria-labelledby="modal" aria-hidden="true">
        <div class="modal-dialog  modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{texto_boton}} reporte de seguimiento</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="horizontal-form">
                        <form class="form-horizontal" action="post" v-on:submit.prevent="crearSeguimiento(seguimientoData.Categoria_seguimiento, 0)">
                            <!--   -->
                            <div class="form-group">
                                <label class="control-label">Categoría del reporte</label>
                                <select class="form-control" v-model="seguimientoData.Categoria_seguimiento" required>
                                    <option value="0">Sin categoría</option>
                                    <option value="1">Compras</option>
                                    <option value="2">Producción</option>
                                    <option value="3">Logística</option>
                                    <option value="4">Instalación</option>
                                    <option value="5">Cobranza</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Datos del seguimiento</label>
                                <textarea class="form-control" rows="5" placeholder="" v-model="seguimientoData.Descripcion" required></textarea>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <input @change="archivoSeleccionado" type="file" class="form-control" name="Imagen">
                                </div>
                                <div class="col-sm-12" v-if="seguimientoData.Url_archivo != null">
                                    Archivo previamente cargado
                                    <a target="_blank" v-bind:href="'<?php echo base_url(); ?>uploads/imagenes/'+seguimientoData.Url_archivo"> Ver archivo</a>
                                </div>
                            </div>
                            <div class="form-group" v-show="preloader == 1">
                                <p align="center">
                                    EL ARCHIVO SE ESTA CARGANDO. <br> No cerrar la ventana hasta finalizada la carga, dependiendo del peso del archivo puede demorar algunos minutos.
                                </p>
                                <p align="center">
                                    <img src="http://grupopignatta.com.ar/images/preloader.gif" alt="">
                                </p>
                            </DIV>
                            <div class="form-group">
                                <button type="submit" class="btn btn-success" :disabled="preloader == 1">{{texto_boton}}</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /.modal -->
    <!-- Modal AÑADIR PRODUCTOS-->
    <div class="modal fade" id="modalProductos" tabindex="-1" role="dialog" aria-labelledby="modal" aria-hidden="true">
        <div class="modal-dialog  modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 v-if="productoData.Id == null" class="modal-title">Añadir producto a esta venta</h5>
                    <h3 v-if="productoData.Id > 0" class="modal-title"> {{productoData.Nombre_producto}}</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="horizontal-form">
                        <form class="form-horizontal" action="post" v-on:submit.prevent="agregarProducto()">
                            <!--   -->

                            <div class="form-group" v-if="productoData.Id == null">
                                <label class="control-label">Producto a fabricar</label>
                                <input list="productos" class="form-control" v-model="productoData.Producto_id" :disabled="productoData.Id > 0" v-on:change="actualizarPrecioProducto(productoData.Producto_id)" required>
                                <datalist id="productos">
                                    <option v-for="producto in listaProductos" v-bind:value="producto.Id">{{producto.Nombre_producto}}</option>
                                </datalist>
                            </div>
                            <div class="form-group">
                                <label class=" form-control-label">Cantidad de unidades de este producto</label>
                                <input type="number" class="form-control" v-model="productoData.Cantidad" required>
                            </div>
                            <div class="form-group">
                                <label class=" form-control-label">Precio por unidad</label>
                                <input type="number" class="form-control" v-model="productoData.Precio_venta_producto" required>
                                <p class="text-info"> Ult. actualización: {{ productoData.Fecha_ultima_mod | Fecha}}</p>
                            </div>

                            <div class="form-group" v-if="productoData.Id > 0">
                                <label class=" form-control-label">Tipo de producción</label>
                                <select class="form-control" v-model="productoData.Tipo_produccion">
                                    <option value="1">Normal</option>
                                    <option value="2">Reclamo</option>
                                    <option value="3">Muestra</option>
                                </select>
                            </div>
                            <hr>
                            <h4>Requerimentos especificos para este producto en sus respecticas áreas y estaciones. </h4>
                            <p>Estos campos no son obligatorios, añadir información en el área que sea necesario.</p><br>
                            <div class="form-group">
                                <label class=" form-control-label">Área de compras</label>
                                <textarea class="form-control" rows="3" v-model="productoData.S_1_Requerimientos" required></textarea>
                            </div>

                            <div class="form-group">
                                <label class=" form-control-label">Proceso de materiales</label>
                                <textarea class="form-control" rows="3" v-model="productoData.S_2_Requerimientos" required></textarea>
                            </div>

                            <div class="form-group">
                                <label class=" form-control-label">Soldadura</label>
                                <textarea class="form-control" rows="3" v-model="productoData.S_3_Requerimientos" required></textarea>
                            </div>

                            <div class="form-group">
                                <label class=" form-control-label">Pintura</label>
                                <textarea class="form-control" rows="3" v-model="productoData.S_4_Requerimientos" required></textarea>
                            </div>

                            <div class="form-group">
                                <label class=" form-control-label">Rotulación</label>
                                <textarea class="form-control" rows="3" v-model="productoData.S_5_Requerimientos" required></textarea>
                            </div>

                            <div class="form-group">
                                <label class=" form-control-label">Empaque y Loteo</label>
                                <textarea class="form-control" rows="3" v-model="productoData.S_6_Requerimientos" required></textarea>
                            </div>

                            <div class="form-group">
                                <label class=" form-control-label">Logística</label>
                                <textarea class="form-control" rows="3" v-model="productoData.S_7_Requerimientos" required></textarea>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label class="control-label">Comentarios adicionales</label>
                                <textarea class="form-control" rows="3" placeholder="" v-model="productoData.Observaciones"></textarea>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-success" :disabled="preloader == 1">{{texto_boton}}</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /.modal -->
    <!-- Modal ANULAR PRODUCTO-->
    <div class="modal fade" id="modalAnularProducto" tabindex="-1" role="dialog" aria-labelledby="modal" aria-hidden="true">
        <div class="modal-dialog  modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Anular {{productoAnulado.Nombre_producto}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="horizontal-form">
                        <form class="form-horizontal" action="post" v-on:submit.prevent="anularProducto()">

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class=" form-control-label">Fecha de la operación</label>
                                        <input type="date" class="form-control" v-model="productoAnulado.Fecha" required>
                                    </div>
                                    <div class="form-group">
                                        <label class=" form-control-label">Cantidad a anular</label>
                                        <input type="number" class="form-control" v-model="productoAnulado.Cantidad_anulada" :max='productoAnulado.Cantidad' min="1" required>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label class="control-label">Observaciones</label>
                                        <textarea class="form-control" rows="5" placeholder="" v-model="productoAnulado.Observaciones"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-danger" :disabled="boton_habilitado == 0">Realizar anulación</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /.modal -->
    <!-- Modal PASO A PASO PRODUCTO-->
    <div class="modal fade" id="modalPasoapaso" tabindex="-1" role="dialog" aria-labelledby="modal" aria-hidden="true">
        <div class="modal-dialog  modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Avanzar producto a siguiente etapa</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="horizontal-form">
                        <form class="form-horizontal" action="post" v-on:submit.prevent="cambiarEstadoProducto()">
                            <!--   -->
                            <!-- <pre>{{productoPasoData}}</pre> -->
                            <div class="form-group">
                                <label class=" form-control-label">Fecha del movimiento</label>
                                <input type="date" class="form-control" v-model="productoPasoData.Fecha" required>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Comentarios de esta etapa</label>
                                <textarea class="form-control" rows="5" placeholder="" v-model="productoPasoData.Comentarios"></textarea>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-success">Avanzar a siguiente etapa</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /.modal -->
    <!-- Modal observaciones-->
    <div class="modal fade" id="modalDatosEtapa" tabindex="-1" role="dialog" aria-labelledby="modal" aria-hidden="true">
        <div class="modal-dialog  modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Información etapa</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h4>Requerimentos</h4>
                    <p>{{infoModal.Requerimentos}}</p>
                    <hr>
                    <h4>Observaciones</h4>
                    <p>{{infoModal.Observaciones}}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /.modal -->
    <!-- Modal efectivo-->
    <div class="modal fade" id="modalEfectivo" tabindex="-1" role="dialog" aria-labelledby="modalCategoriasCartaTitle" aria-hidden="true">
        <div class="modal-dialog  modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalItemsFoto">Ingresar pago</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="horizontal-form">
                        <form class="form-horizontal" action="post" v-on:submit.prevent="crear_movimiento()">
                            <div class="form-group">
                                <label class=" form-control-label">Empresa que factura el pago</label>
                                <select class="form-control" v-model="movimientoDatos.Empresa_id" required>
                                    <option v-for="empresa in listaEmpresas" v-bind:value="empresa.Id">{{empresa.Nombre_empresa}}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Identificador de la factura</label>
                                <input type="text" class="form-control" v-model="movimientoDatos.Identificador_factura" required>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Monto</label>
                                <input type="number" class="form-control" v-model="movimientoDatos.Monto" required>
                            </div>
                            <div class="form-group">
                                <label class=" form-control-label">Modalidad de pago</label>
                                <select class="form-control" v-model="movimientoDatos.Modalidad_pago" required>
                                    <option value="Efectivo">Efectivo</option>
                                    <option value="Banco">Banco</option>
                                    <option value="Cheque">Cheque</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Fecha en que se realizó el pago</label>
                                <input type="date" class="form-control" v-model="movimientoDatos.Fecha_ejecutado" required>
                            </div>
                            <div class="form-group">
                                <label class=" form-control-label">Observaciones</label>
                                <textarea class="form-control" rows="5" v-model="movimientoDatos.Observaciones"></textarea>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-12">
                                    <button type="submit" class="btn btn-success">{{texto_boton}}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /.modal -->

    <!-- modal PRODUCTOS DE REVENTA -->
    <div class="modal fade" id="productosreventaModal" tabindex="-1" role="dialog" aria-labelledby="scrollmodalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="scrollmodalLabel">{{egresoDato.Nombre_item}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="form-horizontal" action="post" v-on:submit.prevent="movimientoStock_v2()">
                    <div class="modal-body">

                        <div class="form-group" v-if="egresoDato.Id == null">
                            <label class="control-label">Seleccionar producto</label>
                            <input list="productosReventa" class="form-control" v-model="egresoDato.Producto_id" v-on:change="actualizarPrecioProductoReventa(egresoDato.Producto_id)" :disabled="egresoDato.Id > 0" required>
                            <datalist id="productosReventa">
                                <option v-for="prod in listaProductosReventa" v-bind:value="prod.Id">{{prod.Nombre_item}} <b>({{prod.Cant_actual}})</b></option>
                            </datalist>
                        </div>

                        <div class="form-group" v-if="egresoDato.Id == null">
                            <label class=" form-control-label">Cantidad</label>
                            <input type="number" min="1" class="form-control" v-model="egresoDato.Cantidad" required>
                        </div>

                        <div class="row" v-if="egresoDato.Id > 0">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label">Seleccione el ajuste necesario</label>

                                    <select class="form-control" v-model="tipoMovimiento" id="productosReventa">
                                        <option value="2">Agregar</option>
                                        <option value="1">Quitar</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group" v-if="egresoDato.Id > 0">
                                    <label class=" form-control-label">
                                        Cantidad a
                                        <span v-show="tipoMovimiento == 2"><b> agregar</b> </span>
                                        <span v-show="tipoMovimiento == 1"><b> quitar</b> </span>
                                    </label>
                                    <input type="number" min="1" class="form-control" v-model="egresoDato.Cantidad" :disabled="tipoMovimiento == 0" required>
                                    <h6>Colocar la cantidad a sumar o restar. No confundir con la nueva cantidad total.</h6>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class=" form-control-label">Precio unitario del producto</label>
                            <input type="number" min="1" class="form-control" v-model="egresoDato.Precio_venta_producto" required>
                            <p class="text-info"> Ult. actualización: {{ egresoDato.Fecha_ultima_mod | Fecha}}</p>
                        </div>
                        <div class="form-group">
                            <label class=" form-control-label">Descripción</label>
                            <textarea class="form-control" rows="5" v-model="egresoDato.Descripcion_egreso"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">{{texto_boton}}</button>
                    </div>
                </form>
            </div>


        </div>
    </div>
</div>
<!-- END MAIN CONTENT-->
<!-- END PAGE CONTAINER-->
<?php
// CABECERA
include "footer.php";
?>
</body>

</html>
<!-- end document-->