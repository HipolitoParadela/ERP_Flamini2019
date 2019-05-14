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
                                        <p class="text-sm-center mt-2 mb-1"><b>Empresa:</b> {{ventaDatos.Nombre_empresa}}</p>
                                        <p class="text-sm-center mt-2 mb-1"><b>Cliente:</b> {{ventaDatos.Nombre_cliente}} </p>
                                        <p class="text-sm-center mt-2 mb-1"><b>Vendedor:</b> {{ventaDatos.Nombre_vendedor}} </p>
                                        <p class="text-sm-center mt-2 mb-1"><b>Resp. Plan. Inicial:</b> {{ventaDatos.Nombre_resp_1}}</p>
                                        <p class="text-sm-center mt-2 mb-1"><b>Resp. Plan. Final:</b> {{ventaDatos.Nombre_resp_2}}</p>
                                        <p class="text-sm-center mt-2 mb-1"><b>Resp. Logistica:</b> {{ventaDatos.Nombre_logistica}}</p>
                                        <p class="text-sm-center mt-2 mb-1"><b>Resp. Instalación:</b> {{ventaDatos.Nombre_instalacion}}</p>
                                        <p class="text-sm-center mt-2 mb-1"><b>Resp. Cobranza:</b> {{ventaDatos.Nombre_cobranza}}</p>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header">
                                        <h4>Tiempos</h4>
                                    </div>
                                    <div class="card-body">
                                        <div v-if="ventaDatos.Estado < 10">
                                            <p class="text-sm-center mt-2 mb-1"><b>{{diferenciasEntre_fechas(ventaDatos.Fecha_venta, null)}}</b> de comenzada su producción</p>
                                            <p class="text-sm-center mt-2 mb-1"><b>  Finalización:</b>  {{diferenciasEntre_fechas(null, ventaDatos.Fecha_estimada_entrega)}} según fecha estimada</p>
                                        </div>
                                        <div v-if="ventaDatos.Estado == 10">
                                            <p class="text-sm-center mt-2 mb-1"><b>Finalizado el día </b>{{formatoFecha(ventaDatos.Fecha_finalizada)}}. </p>
                                            <p class="text-sm-center mt-2 mb-1">Su producción demandó <b>{{diferenciasEntre_fechas(ventaDatos.Fecha_venta, ventaDatos.Fecha_finalizada)}}</b>.</p>
                                            <p class="text-sm-center mt-2 mb-1">
                                                Diferencia entre finalizado y su estimación: <b>{{diferenciasEntre_fechas(ventaDatos.Fecha_finalizada, ventaDatos.Fecha_estimada_entrega)}}</b>
                                            </p>
                                        </div>
                                         
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header">
                                        <h4>Precio de venta del lote</h4>
                                    </div>
                                    <div class="card-body">
                                        <h2 align="center">{{precioVentaTotal}} USD</h2>
                                    </div>
                                </div> 
                            </div>

                            

                            
                            <!-- SECCION FICHA cliente -->
                            <div class="col-lg-10" > 
                                <ul class="nav nav-tabs">
								    <li class="nav-item">
										<a class="nav-link" v-bind:class="{ active: mostrar == 1 }" href="#" v-on:click="mostrar = 1">Ficha</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" v-bind:class="{ active: mostrar == 2 }" href="#" v-on:click="getListadoSeguimiento(0, 2)">Historial</a>
									</li>
                                    <li class="nav-item">
										<a class="nav-link" v-bind:class="{ active: mostrar == 3 }" href="#" v-on:click="mostrar = 3">Insumos usados</a>
									</li>
                                    <li class="nav-item">
                                        <a class="nav-link" v-bind:class="{ active: mostrar == 4 }" href="#" v-on:click="mostrar = 4">Producción</a>
                                    </li>
                                     <li class="nav-item">
										<a class="nav-link" v-bind:class="{ active: mostrar == 5 }" href="#" v-on:click="getListadoSeguimiento(3, 5)">Logística</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" v-bind:class="{ active: mostrar == 6 }" href="#" v-on:click="getListadoSeguimiento(4, 6)">Instalación</a>
									</li>
                                    <li class="nav-item">
										<a class="nav-link" v-bind:class="{ active: mostrar == 7 }" href="#" v-on:click="getListadoSeguimiento(5,7)">Cobranza</a>
                                    </li>
                                </ul>
                                
                                           
                                <!-- SECCION DATOS EDITABLES DE LA VENTA -->
                                <div class="row" v-show="mostrar == '1'">
                                    <div class="col-lg-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <strong>Ficha: {{ventaDatos.Nombre_producto }}</strong>
                                                Última actualización: {{formatoFecha_hora(ventaDatos.Fecha_ultima_edicion) }}
                                            </div>
                                            <div class="card-body">
                                                <div class="horizontal-form">
                                                    <form class="form-horizontal" action="post" v-on:submit.prevent="crearVenta()"> 
                                                        <div class="row">
                                                            <div class="col-sm-6">
                                                                <div class="form-group">
                                                                    <label  class=" form-control-label">Identificador de la vente</label> 
                                                                    <input type="text" class="form-control" v-model="ventaDatos.Identificador_venta" required>
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
                                                                    <label  class=" form-control-label">Fecha de venta</label> 
                                                                    <input type="date" class="form-control" v-model="ventaDatos.Fecha_venta" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label  class=" form-control-label">Fecha estimada para finalizar fabricación</label> 
                                                                    <input type="date" class="form-control" v-model="ventaDatos.Fecha_estimada_entrega" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class=" form-control-label">Observaciones sobre la venta</label>
                                                                    <textarea class="form-control" rows="5" v-model="ventaDatos.Observaciones_venta"></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                
                                                                
                                                                
                                                                
                                                                <div class="form-group">
                                                                    <label class="control-label">Responsable de logística</label>
                                                                    <select class="form-control" v-model="ventaDatos.Responsable_id_logistica" required>
                                                                        <option value="0">Seleccionar persona</option>
                                                                        <option v-for="persona in listaUsuarios" v-bind:value="persona.Id">{{persona.Nombre}}</option>
                                                                    </select>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class=" form-control-label">Información sobre logística</label>
                                                                    <textarea class="form-control" rows="5" v-model="ventaDatos.Info_logistica"></textarea>
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
                                                                    <td>{{formatoFecha_hora(seguimiento.Fecha)}}</td>

                                                                        <td v-if="seguimiento.Categoria_seguimiento == 0">Sin categoría</td>    
                                                                        <td v-if="seguimiento.Categoria_seguimiento == 1">Compras</td>
                                                                        <td v-if="seguimiento.Categoria_seguimiento == 2">Producción</td>
                                                                        <td v-if="seguimiento.Categoria_seguimiento == 3">Logística</td>
                                                                        <td v-if="seguimiento.Categoria_seguimiento == 4">Instalación</td>
                                                                        <td v-if="seguimiento.Categoria_seguimiento == 5">Cobranza</td>

                                                                    <td>{{seguimiento.Descripcion}}</td>
                                                                    <td><a  v-if="seguimiento.Url_archivo != null" target="_blank" v-bind:href="'<?php echo base_url(); ?>uploads/imagenes/'+seguimiento.Url_archivo"> Ver archivo</a></td>
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

                                <!-- SECCION INSUMOS USADOS -->
                                <div class="row" v-show="mostrar == '3'">              
                                    <div class="col-lg-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <strong>Producto/insumos utilizados en la fabricación</strong>
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
                                                                    <th>Fecha</th>
                                                                    <th>Descripción</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr v-for="producto in listaproductosUsados">
                                                                    <td>{{producto.Nombre_item}}</td>
                                                                    <td>{{producto.Cantidad}}</td>
                                                                    <td>{{formatoFecha_hora(producto.Fecha_hora)}}</td>
                                                                    <td>{{producto.Descripcion}}</td>
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
                                                        <h1 v-if="ventaDatos.Estado > 6">Producción Finalizada</h1>
                                                        <hr>
                                                        <p> 
                                                            <a v-if="ventaDatos.Estado < 7" href="#" class="btn btn-warning " v-on:click="cambiar_estado_venta()">
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
                                                        <strong>Listado de productos vendidos en este lote</strong>
                                                    </div>
                                                    <div class="card-body" v-if="ventaDatos.Estado < 2">
                                                        <a href="#modalProductos" data-toggle="modal" class="btn btn-success btn-flat btn-addon" v-on:click="limpiarFormularioProductos()">
                                                            <i class="ti-plus"></i> Añadir producto
                                                        </a>
                                                    </div>
                                                    <div class="card-body" >
                                                        <div class="bootstrap-data-table-panel col-lg-12">
                                                            <div class="table-responsive">
                                                                <div class="table-responsive">
                                                                    <table id="table2excel" class="table table-striped">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>Producto</th>
                                                                                <td></td>
                                                                                <th>Stock</th>
                                                                                <th>Proceso materiales</th>
                                                                                <th>Soldadura</th>
                                                                                <th>Pintura</th>
                                                                                <th>Rotulación</th>
                                                                                <th>Empaque</th>
                                                                                <th></th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <tr v-for="productoFabricado in listaProductosVendidos">
                                                                                <td>
                                                                                    <h4> {{productoFabricado.Nombre_producto}}</h4>
                                                                                </td>
                                                                                <td>
                                                                                    <span v-if="productoFabricado.Tipo_produccion == 1" class="text-secondary"><i class="fa fa-circle"></i></span>
                                                                                    <span v-if="productoFabricado.Tipo_produccion == 2" class="text-warning"><i class="fa fa-circle"></i></span>
                                                                                    <span v-if="productoFabricado.Tipo_produccion == 3" class="text-info"><i class="fa fa-circle"></i></span>
                                                                                </td>
                                                                                <td>
                                                                                    <a  v-if="productoFabricado.Estado == 1" href="#modalPasoapaso" data-toggle="modal" class="btn btn-info btn-flat btn-addon m-b-10 m-l-5 btn-sm" v-on:click="editarPasoProducto(productoFabricado.Id, 2)">
                                                                                        <i class="ti-plus"></i> Stock OK >>
                                                                                    </a>
                                                                                    <span v-if="productoFabricado.Estado > 1"> {{formatoFecha(productoFabricado.S_1_Fecha_finalizado)}}</span>
                                                                                    <span v-if="productoFabricado.Estado < 1">En etapa previa</span>
                                                                                    <button class="item" v-on:click="infoEtapa(productoFabricado.S_1_Requerimientos, productoFabricado.S_1_Observaciones)" data-toggle="modal" data-target="#modalDatosEtapa" data-placement="top" title="Info de esta etapa">
                                                                                        <i class="fa fa-exclamation-circle"></i>
                                                                                    </button>
                                                                                </td>

                                                                                <td>
                                                                                    <a v-if="productoFabricado.Estado == 2" href="#modalPasoapaso" data-toggle="modal" class="btn btn-info btn-flat btn-addon m-b-10 m-l-5 btn-sm" v-on:click="editarPasoProducto(productoFabricado.Id, 3)">
                                                                                        <i class="ti-plus"></i> Procesamiento completado >>
                                                                                    </a>
                                                                                    <span v-if="productoFabricado.Estado > 2">{{formatoFecha(productoFabricado.S_2_Fecha_finalizado)}}</span>
                                                                                    <span v-if="productoFabricado.Estado < 2">En etapa previa</span>
                                                                                    <button class="item" v-on:click="infoEtapa(productoFabricado.S_2_Requerimientos, productoFabricado.S_2_Observaciones)" data-toggle="modal" data-target="#modalDatosEtapa" data-placement="top" title="Info de esta etapa">
                                                                                        <i class="fa fa-exclamation-circle"></i>
                                                                                    </button>
                                                                                </td>

                                                                                <td>
                                                                                    <a v-if="productoFabricado.Estado == 3" href="#modalPasoapaso" data-toggle="modal" class="btn btn-info btn-flat btn-addon m-b-10 m-l-5 btn-sm" v-on:click="editarPasoProducto(productoFabricado.Id, 4)">
                                                                                        <i class="ti-plus"></i> Soldadura completada >>
                                                                                    </a>
                                                                                    <span v-if="productoFabricado.Estado > 3">{{formatoFecha(productoFabricado.S_3_Fecha_finalizado)}}</span>
                                                                                    <span v-if="productoFabricado.Estado < 3">En etapa previa</span>
                                                                                    <button class="item" v-on:click="infoEtapa(productoFabricado.S_3_Requerimientos, productoFabricado.S_3_Observaciones)" data-toggle="modal" data-target="#modalDatosEtapa" data-placement="top" title="Info de esta etapa">
                                                                                        <i class="fa fa-exclamation-circle"></i>
                                                                                    </button>
                                                                                </td>

                                                                                <td>
                                                                                    <a v-if="productoFabricado.Estado == 4" href="#modalPasoapaso" data-toggle="modal" class="btn btn-info btn-flat btn-addon m-b-10 m-l-5 btn-sm" v-on:click="editarPasoProducto(productoFabricado.Id, 5)">
                                                                                        <i class="ti-plus"></i> Pintura completada >>
                                                                                    </a>
                                                                                    <span v-if="productoFabricado.Estado > 4">{{formatoFecha(productoFabricado.S_4_Fecha_finalizado)}}</span>
                                                                                    <span v-if="productoFabricado.Estado < 4"> En etapa previa</span> 
                                                                                    <button class="item" v-on:click="infoEtapa(productoFabricado.S_4_Requerimientos, productoFabricado.S_4_Observaciones)" data-toggle="modal" data-target="#modalDatosEtapa" data-placement="top" title="Info de esta etapa">
                                                                                        <i class="fa fa-exclamation-circle"></i>
                                                                                    </button>
                                                                                </td>

                                                                                <td>
                                                                                    <a v-if="productoFabricado.Estado == 5" href="#modalPasoapaso" data-toggle="modal" class="btn btn-info btn-flat btn-addon m-b-10 m-l-5 btn-sm" v-on:click="editarPasoProducto(productoFabricado.Id, 6)">
                                                                                        <i class="ti-plus"></i> Rotulación completada >>
                                                                                    </a>
                                                                                    <span v-if="productoFabricado.Estado > 5">{{formatoFecha(productoFabricado.S_5_Fecha_finalizado)}}</span>
                                                                                    <span v-if="productoFabricado.Estado < 5">En etapa previa</span> 
                                                                                    <button class="item" v-on:click="infoEtapa(productoFabricado.S_5_Requerimientos, productoFabricado.S_5_Observaciones)" data-toggle="modal" data-target="#modalDatosEtapa" data-placement="top" title="Info de esta etapa">
                                                                                        <i class="fa fa-exclamation-circle"></i>
                                                                                    </button>
                                                                                </td>

                                                                                <td>
                                                                                    <a v-if="productoFabricado.Estado == 6" href="#modalPasoapaso" data-toggle="modal" class="btn btn-info btn-flat btn-addon m-b-10 m-l-5 btn-sm" v-on:click="editarPasoProducto(productoFabricado.Id, 7)">
                                                                                        <i class="ti-plus"></i> Producto empacado >>
                                                                                    </a>
                                                                                    <span v-if="productoFabricado.Estado < 6">En etapa previa</span>
                                                                                    <span v-if="productoFabricado.Estado > 6">{{formatoFecha(productoFabricado.S_6_Fecha_finalizado)}}</span>
                                                                                    <button class="item" v-on:click="infoEtapa(productoFabricado.S_6_Requerimientos, productoFabricado.S_6_Observaciones)" data-toggle="modal" data-target="#modalDatosEtapa" data-placement="top" title="Info de esta etapa">
                                                                                        <i class="fa fa-exclamation-circle"></i>
                                                                                    </button>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="table-data-feature">
                                                                                        <button class="item" v-on:click="editarFormularioProductos(productoFabricado)" data-toggle="modal" data-target="#modalProductos" data-placement="top" title="Editar">
                                                                                            <i class="zmdi zmdi-edit"></i>
                                                                                        </button>
                                                                                        <button class="item" v-on:click="editarAnularProducto(productoFabricado)" data-toggle="modal" data-target="#modalAnularProducto" data-placement="top" title="Editar">
                                                                                            <i class="fa fa-ban"></i>
                                                                                        </button>
                                                                                        <?php 
                                                                                            if($this->session->userdata('Rol_acceso') > 4) 
                                                                                            {
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
                                            </div>
                                        </div> 
                                    </div>
                                </div>

                                 <!-- SECCION LOGISTICA -->
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
                                                <a href="#" class="btn btn-warning " v-on:click="cambiar_estado_venta()">
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
                                                <form class="form-horizontal" action="post" v-on:submit.prevent="crearSeguimiento(3,3)"> <!--   -->
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
                                                        <button type="submit" class="btn btn-success":disabled="preloader == 1">{{texto_boton}}</button>
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
                                                                    <td>{{formatoFecha_hora(seguimiento.Fecha)}}</td>

                                                                        <td v-if="seguimiento.Categoria_seguimiento == 0">Sin categoría</td>    
                                                                        <td v-if="seguimiento.Categoria_seguimiento == 1">Compras</td>
                                                                        <td v-if="seguimiento.Categoria_seguimiento == 2">Producción</td>
                                                                        <td v-if="seguimiento.Categoria_seguimiento == 3">Logística</td>
                                                                        <td v-if="seguimiento.Categoria_seguimiento == 4">Instalación</td>
                                                                        <td v-if="seguimiento.Categoria_seguimiento == 5">Cobranza</td>

                                                                    <td>{{seguimiento.Descripcion}}</td>
                                                                    <td><a  v-if="seguimiento.Url_archivo != null" target="_blank" v-bind:href="'<?php echo base_url(); ?>uploads/imagenes/'+seguimiento.Url_archivo"> Ver archivo</a></td>
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

                                <!-- SECCION INSTALACIÒN -->
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
                                                <a href="#" class="btn btn-warning " v-on:click="cambiar_estado_venta()">
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
                                                <form class="form-horizontal" action="post" v-on:submit.prevent="crearSeguimiento(4,4)"> <!--   -->
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
                                                        <button type="submit" class="btn btn-success":disabled="preloader == 1">{{texto_boton}}</button>
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
                                                                    <td>{{formatoFecha_hora(seguimiento.Fecha)}}</td>

                                                                        <td v-if="seguimiento.Categoria_seguimiento == 0">Sin categoría</td>    
                                                                        <td v-if="seguimiento.Categoria_seguimiento == 1">Compras</td>
                                                                        <td v-if="seguimiento.Categoria_seguimiento == 2">Producción</td>
                                                                        <td v-if="seguimiento.Categoria_seguimiento == 3">Logística</td>
                                                                        <td v-if="seguimiento.Categoria_seguimiento == 4">Instalación</td>
                                                                        <td v-if="seguimiento.Categoria_seguimiento == 5">Cobranza</td>

                                                                    <td>{{seguimiento.Descripcion}}</td>
                                                                    <td><a  v-if="seguimiento.Url_archivo != null" target="_blank" v-bind:href="'<?php echo base_url(); ?>uploads/imagenes/'+seguimiento.Url_archivo"> Ver archivo</a></td>
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

                                <!-- SECCION COBRANZA -->
                                <div class="row" v-show="mostrar == '7'">
                                    <div class="col-lg-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <strong>Información para Cobranza</strong>
                                            </div>
                                            <div class="card-body">
                                                <h3>{{ventaDatos.Info_cobranza}}</h3>
                                            </div>
                                            <div v-if="ventaDatos.Estado == 9" class="card-body">
                                                <a href="#" class="btn btn-warning " v-on:click="cambiar_estado_venta()">
                                                    COBRANZA FINALIZADA
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
                                                <form class="form-horizontal" action="post" v-on:submit.prevent="crearSeguimiento(5,5)"> <!--   -->
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
                                                        <button type="submit" class="btn btn-success":disabled="preloader == 1">{{texto_boton}}</button>
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
                                                                    <td>{{formatoFecha_hora(seguimiento.Fecha)}}</td>

                                                                        <td v-if="seguimiento.Categoria_seguimiento == 0">Sin categoría</td>    
                                                                        <td v-if="seguimiento.Categoria_seguimiento == 1">Compras</td>
                                                                        <td v-if="seguimiento.Categoria_seguimiento == 2">Producción</td>
                                                                        <td v-if="seguimiento.Categoria_seguimiento == 3">Logística</td>
                                                                        <td v-if="seguimiento.Categoria_seguimiento == 4">Instalación</td>
                                                                        <td v-if="seguimiento.Categoria_seguimiento == 5">Cobranza</td>

                                                                    <td>{{seguimiento.Descripcion}}</td>
                                                                    <td><a  v-if="seguimiento.Url_archivo != null" target="_blank" v-bind:href="'<?php echo base_url(); ?>uploads/imagenes/'+seguimiento.Url_archivo"> Ver archivo</a></td>
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
                                <form class="form-horizontal" action="post" v-on:submit.prevent="crearSeguimiento(seguimientoData.Categoria_seguimiento, 0)"> <!--   -->
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
                                         <button type="submit" class="btn btn-success":disabled="preloader == 1">{{texto_boton}}</button>
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
                            <h5 class="modal-title">Añadir producto a esta venta</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>  
                        <div class="modal-body">
                                                            
                            <div class="horizontal-form">
                                <form class="form-horizontal" action="post" v-on:submit.prevent="agregarProducto()"> <!--   -->
                                    
                                    <div class="form-group">
                                        <label class="control-label">Producto a fabricar</label>
                                        <select class="form-control" v-model="productoData.Producto_id" required :disabled="productoData.Id > 0">
                                                <option value="0">Seleccionar producto</option>
                                                <option v-for="producto in listaProductos" v-bind:value="producto.Id">{{producto.Nombre_producto}}</option>
                                        </select>
                                    </div>
                                    <div class="form-group" v-if="productoData.Id == null">
                                        <label  class=" form-control-label">Cantidad de unidades de este producto</label> 
                                        <input type="number" class="form-control" v-model="productoData.Cantidad" required>
                                    </div>
                                    <div class="form-group" v-if="productoData.Id > 0">
                                        <label  class=" form-control-label">Tipo de producción</label> 
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
                                        <textarea class="form-control" rows="3" v-model="productoData.S_1_Requerimientos"></textarea>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class=" form-control-label">Proceso de materiales</label>
                                        <textarea class="form-control" rows="3" v-model="productoData.S_2_Requerimientos"></textarea>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class=" form-control-label">Soldadura</label>
                                        <textarea class="form-control" rows="3" v-model="productoData.S_3_Requerimientos"></textarea>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class=" form-control-label">Pintura</label>
                                        <textarea class="form-control" rows="3" v-model="productoData.S_4_Requerimientos"></textarea>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class=" form-control-label">Rotulación</label>
                                        <textarea class="form-control" rows="3" v-model="productoData.S_5_Requerimientos"></textarea>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class=" form-control-label">Empaque y Loteo</label>
                                        <textarea class="form-control" rows="3" v-model="productoData.S_6_Requerimientos"></textarea>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class=" form-control-label">Logística</label>
                                        <textarea class="form-control" rows="3" v-model="productoData.S_7_Requerimientos"></textarea>
                                    </div>
                                    <hr>
                                    <div class="form-group">
                                        <label class="control-label">Comentarios adicionales</label>
                                        <textarea class="form-control" rows="3" placeholder="" v-model="productoData.Observaciones"></textarea>
                                    </div>
                                            
                                    <div class="form-group">
                                         <button type="submit" class="btn btn-success":disabled="preloader == 1">{{texto_boton}}</button>
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
                                <form class="form-horizontal" action="post" v-on:submit.prevent="anularProducto()"> <!--   -->
                                    <!-- <pre>{{productoAnulado}}</pre> -->
                                    <div class="form-group">
                                        <label  class=" form-control-label">Fecha de la operación</label> 
                                        <input type="date" class="form-control" v-model="productoAnulado.Fecha" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Comentarios</label>
                                        <textarea class="form-control" rows="5" placeholder="" v-model="productoAnulado.Comentarios_anulacion"></textarea>
                                    </div>
        
                                    <div class="form-group">
                                         <button  type="submit" class="btn btn-danger">Confirmar anulación</button>
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
                                <form class="form-horizontal" action="post" v-on:submit.prevent="cambiarEstadoProducto()"> <!--   -->
                                    <!-- <pre>{{productoPasoData}}</pre> -->
                                    <div class="form-group">
                                        <label  class=" form-control-label">Fecha del movimiento</label> 
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
            <!-- Modal ANULAR PRODUCTO-->
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
    <!-- END MAIN CONTENT-->
    <!-- END PAGE CONTAINER-->
<?php
// CABECERA
include "footer.php";
?>
</body>
</html>
<!-- end document-->
