<?php
// CABECERA
include "head.php";
// TOP
include "top.php";
include "menusidebar.php";
?>
<!-- PAGE CONTAINER-->
<div class="page-container" id="fabricacion">
    <!-- HEADER DESKTOP-->
    <?PHP include "header_desktop.php"; ?>
    <!-- HEADER DESKTOP-->

    <!-- MAIN CONTENT-->
    <div class="main-content">
        <div class="section__content section__content--p30">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-2  d-print-none">
                        <div class="card">
                            <div class="card-header">
                                <h4>Info</h4>
                            </div>
                            <div class="card-body">
                                <div class="user-photo m-b-30">
                                    <img v-if="productoDatos.Imagen != null" v-bind:src="'<?php echo base_url(); ?>uploads/imagenes/'+productoDatos.Imagen" alt="">
                                    <img v-else src="<?php echo base_url(); ?>uploads/imagenes/addimagen.jpg" alt="">
                                </div>
                                <h5 class="text-sm-center mt-2 mb-1">{{productoDatos.Nombre_producto}}</h5>
                                <div class="location text-sm-center">
                                    {{productoDatos.Nombre_categoria}}
                                </div>
                            </div>
                        </div>
                    </div>




                    <!-- SECCION FICHA cliente -->
                    <div class="col-lg-10">
                        <ul class="nav nav-tabs  d-print-none">
                            <li class="nav-item">
                                <a class="nav-link" v-bind:class="{ active: mostrar == 1 }" href="#" v-on:click="mostrar = 1">Ficha</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" v-bind:class="{ active: mostrar == 3 }" href="#" v-on:click="mostrar = 3">Ventas de este producto</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" v-bind:class="{ active: mostrar == 4 }" href="#" v-on:click="mostrar = 4">Despiece</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" v-bind:class="{ active: mostrar == 2 }" href="#" v-on:click="mostrar = 2">Planillas</a>
                            </li>
                        </ul>

                        <!-- SECCION DATOS EDITABLES DEL cliente -->
                        <div class="row" v-show="mostrar == '1'">
                            <div class="col-lg-4">
                                <div class="card">
                                    <div class="card-header">
                                        <strong>Ficha: {{productoDatos.Nombre_producto}}</strong>
                                    </div>
                                    <div class="card-body">
                                        <div class="horizontal-form">
                                            <form class="form-horizontal" action="post" v-on:submit.prevent="actualizarProducto()">
                                                <div class="form-group">
                                                    <label class=" form-control-label">Nombre</label>
                                                    <input type="text" class="form-control" placeholder="" v-model="productoDatos.Nombre_producto" required>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">Empresa</label>
                                                    <select class="form-control" v-model="productoDatos.Empresa_id" required>
                                                        <option value="0">...</option>
                                                        <option v-for="empresas in listaEmpresas" v-bind:value="empresas.Id">{{empresas.Nombre_empresa}}</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">Categoría</label>
                                                    <select class="form-control" v-model="productoDatos.Categoria_fabricacion_id" required>
                                                        <option value="0">...</option>
                                                        <option v-for="categoria in listaCategorias" v-bind:value="categoria.Id">{{categoria.Nombre_categoria}}</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label class=" form-control-label">Código interno</label>
                                                    <input type="text" class="form-control" placeholder="" v-model="productoDatos.Codigo_interno">
                                                </div>
                                                <div class="form-group">
                                                    <label class=" form-control-label">Precio de venta</label>
                                                    <input type="number" class="form-control" placeholder="" v-model="productoDatos.Precio_venta">
                                                </div>
                                                <div class="form-group">
                                                    <label class=" form-control-label">Descripción Pública Corta</label>
                                                    <textarea class="form-control" rows="5" placeholder="" v-model="productoDatos.Descripcion_publica_corta"></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label class=" form-control-label">Descripción Pública Larga (HTML)</label>
                                                    <textarea class="form-control" rows="5" placeholder="" v-model="productoDatos.Descripcion_publica_larga"></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">Descripción privada</label>
                                                    <textarea class="form-control" rows="5" placeholder="" v-model="productoDatos.Descripcion_tecnica_privada"></textarea>
                                                </div>
                                                <hr>
                                                <button type="submit" class="btn btn-success   d-print-none" :disabled="boton_habilitado == 0">Actualizar datos</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="card">
                                    <div class="card-header">
                                        <strong>Archivos e historial sobre {{productoDatos.Nombre_producto}}</strong>
                                    </div>
                                    <div class="card-body">
                                        <p align="right">
                                            <a href="#modalArchivos" data-toggle="modal" title="Nuevo item" class="btn btn-success   d-print-none" v-on:click="limpiarFormularioArchivo()">
                                                <i class="ti-plus"></i> Añadir Reporte/Archivo
                                            </a>
                                        </p>
                                    </div>
                                    <div class="card-body">
                                        <div class="bootstrap-data-table-panel col-lg-12">
                                            <div class="table-responsive">
                                                <table id="table2excel" class="table table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>Archivo</th>
                                                            <th>Nombre</th>
                                                            <th>Fecha</th>
                                                            <th>Descripcion</th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="archivo in listaArchivos">
                                                            <td><a v-if="archivo.Url_archivo != null" target="_blank" v-bind:href="'<?php echo base_url(); ?>uploads/imagenes/'+archivo.Url_archivo">Descargar</a></td>
                                                            <td>{{archivo.Nombre_archivo}}</td>
                                                            <td>{{formatoFecha_hora(archivo.Fecha_hora)}}</td>
                                                            <td>{{archivo.Descripcion}}</td>
                                                            <td>
                                                                <a href="#modalArchivos" data-toggle="modal" v-on:click="editarFormularioarchivo(archivo)">
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

                        <!-- SECCION PLANILLAS DE ESTE PRODUCTO -->
                        <div class="row" v-show="mostrar == '2'">
                            <div class="col-lg-12">
                                <div class="card">
                                    <!-- Zona no imprimible -->
                                    <div class="card-header d-print-none">
                                        <strong>Planillas de {{productoDatos.Nombre_producto}}</strong>
                                    </div>
                                    <!-- Zona imprimible pero oculta-->
                                    <div class="d-none d-print-inline">

                                        <table BORDER="1"  width="100%">
                                            <tr>
                                                <td style="padding: 20px;" ROWSPAN="3" width="300px">
                                                    <img src="<?php echo base_url(); ?>uploads/imagenes/Logo_imu.jpg" width="300px">
                                                </td>
                                                <td style="padding: 20px;">
                                                    <h3>Planilla de {{nombrePlanillaElegida}}</h3>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 20px;">
                                                    <h4>{{productoDatos.Nombre_producto}}</h4>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 20px;">
                                                    <p>{{nombrePlanificacion}}</p>
                                                    <p>Operario: {{nombreOperario}}</p>
                                                    <p>Fecha: {{fechaPlanilla | Fecha}}</p>
                                                </td>
                                            </tr>
                                        </table>

                                    </div>
                                    <div class="card-body">
                                        <div class="table-data__tool">
                                            <div class="table-data__tool-left d-print-none">
                                                <div class="rs-select2--light">
                                                    <select class="form-control form-control-xl" v-model="filtro_planilla" v-on:change="getListadoItemsPlanillas(filtro_planilla)" style="font-size:25px; text-align:center; height: 60px">
                                                        <option selected="selected" value="0">Seleccionar Planilla</option>
                                                        <option v-for="planilla in listaPlanillas" v-bind:value="planilla.Id">Planilla de {{planilla.Nombre_planilla}}</option>
                                                    </select>
                                                    <div class="dropDownSelect2"></div>
                                                </div>
                                                <div class="rs-select2--light">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" placeholder="" v-model="multiplicadorProducto" style="font-size:30px; text-align:center; max-width:100px">
                                                    </div>
                                                    <div class="dropDownSelect2"></div>
                                                </div>
                                                <div class="rs-select2--light">
                                                    <select class="form-control form-control-xl" v-model="nombreOperario">
                                                        <option selected="selected" value="No se selecciono ningún operario">Seleccionar Operario</option>
                                                        <option v-for="usuario in listaUsuarios" v-bind:value="usuario.Nombre">{{usuario.Nombre}}</option>
                                                    </select>
                                                    <div class="dropDownSelect2"></div>
                                                </div>
                                                <div class="rs-select2--light">
                                                    <select class="form-control form-control-xl" v-model="nombrePlanificacion">
                                                        <option selected="selected" value="No se selecciono ninguna planificación">Seleccionar Planificación</option>
                                                        <option v-for="planificacion in listaPlanificaciones" v-bind:value="planificacion.Nombre_planificacion">{{planificacion.Nombre_planificacion}}</option>
                                                    </select>
                                                    <div class="dropDownSelect2"></div>
                                                </div>
                                                <div class="rs-select2--light">
                                                    <div class="form-group">
                                                        <input type="date" class="form-control" placeholder="" v-model="fechaPlanilla" style="font-size:20px; text-align:center;">
                                                    </div>
                                                    <div class="dropDownSelect2"></div>
                                                </div>

                                            </div>

                                            <div class="table-data__tool-right  d-print-none">
                                                <button class="au-btn au-btn-icon au-btn--green au-btn--small" data-toggle="modal" data-target="#vinculoModal" v-on:click="limpiarFormularioPlanillaVinculo()" v-show="filtro_planilla > 0">
                                                    <i class="zmdi zmdi-plus"></i>Asignar insumo
                                                </button>

                                                <button class="au-btn au-btn-icon au-btn--blue au-btn--small" data-toggle="modal" data-target="#planillaModal" v-on:click="limpiarFormularioPlanilla()">
                                                    <!--<i class="zmdi zmdi-plus"></i>-->Gestionar Planillas
                                                </button>

                                                <!--<div class="rs-select2--dark rs-select2--sm rs-select2--dark2">
                                            <select class="js-select2" name="type">
                                                <option selected="selected">Más</option>
                                                <option value="">Option 1</option>
                                                <option value="">Option 2</option>
                                            </select>
                                            <div class="dropDownSelect2"></div>
                                        </div>-->
                                            </div>
                                        </div>
                                        <div class="bootstrap-data-table-panel col-lg-12">
                                            <div class="table-responsive">
                                                <table class="table table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>Producto</th>
                                                            <th>Subproducto</th>
                                                            <th>Cantidad</th>
                                                            <th>Método</th>

                                                            <th style="border:lightgray solid thin">Hora Inicio</th>
                                                            <th style="border:lightgray solid thin">Hora Fin</th>
                                                            <th class=" d-print-none">

                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr class="tr-shadow" v-for="item in listaItemsPlanillas">

                                                            <td style="vertical-align: middle;">
                                                                <p align="center">

                                                                    <img v-if="item.Ubicacion_pieza_url != null" width="300px" v-bind:src="'<?php echo base_url(); ?>uploads/imagenes/'+item.Ubicacion_pieza_url" alt="">
                                                                    <br>{{item.Posicion}}
                                                                </p>
                                                            </td>
                                                            <td style="vertical-align: middle;">
                                                                <p align="center">
                                                                    <img v-if="item.Subproducto_url != null" width="300px" v-bind:src="'<?php echo base_url(); ?>uploads/imagenes/'+item.Subproducto_url" alt="">
                                                                    <br><b>{{item.Nombre_item}}</b>
                                                                </p>
                                                            </td>
                                                            <td style="vertical-align: middle;">
                                                                <h1 align="center">{{item.Cantidad * multiplicadorProducto}}
                                                            </td>
                                                            <td style="vertical-align: middle;">{{item.Metodo}}</h1>
                                                            </td>
                                                            <td width="140px" style="border:lightgray solid thin"></td>
                                                            <td width="140px" style="border:lightgray solid thin"></td>
                                                            <td style="vertical-align: middle;" class=" d-print-none">
                                                                <a href="#vinculoModal" data-toggle="modal" v-on:click="editarFormularioPlanillaVinculo(item)">
                                                                    <i class="fa fa-edit"></i>
                                                                </a>
                                                                <button v-on:click="desactivarAlgo(item.Id, 'tbl_fabricacion_planillas_vinculo')" class="item" title="Eliminar">
                                                                    <i class="zmdi zmdi-delete"></i>
                                                                </button>
                                                            </td>
                                                        <tr class="spacer"></tr>
                                                        </tr>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </DIV>
                            </DIV>
                        </div><!-- -->

                        <!-- SECCION VENTAS DE ESTE PRODUCTO -->
                        <div class="row" v-show="mostrar == '3'">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <strong>Ventas de {{productoDatos.Nombre_producto}}</strong>
                                    </div>
                                    <div class="card-body">
                                        <div class="bootstrap-data-table-panel col-lg-12">
                                            <div class="table-responsive">
                                                <table class="table table-data2">
                                                    <thead>
                                                        <tr>
                                                            <th>Cantidad</th>
                                                            <th>Id. Venta</th>
                                                            <th>Cliente</th>
                                                            <th>Vendedor</th>
                                                            <th>Fecha Iniciado</th>
                                                            <th>Fecha Terminado</th>
                                                            <th>Tiempo de fabricación</th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr class="tr-shadow" v-for="venta in listaVentas">

                                                            <td>{{venta.Cantidad_vendida_producto}}</td>
                                                            <td>{{venta.Datos_venta.Identificador_venta}}</td>
                                                            <td>{{venta.Datos_venta.Nombre_cliente}}</td>
                                                            <td>{{venta.Datos_venta.Nombre_vendedor}}</td>
                                                            <td>{{formatoFecha(venta.Datos_venta.Fecha_venta)}}</td>
                                                            <td>{{formatoFecha(venta.Datos_venta.Fecha_finalizada)}}</td>
                                                            <td>{{diferenciasEntre_fechas(venta.Datos_venta.Fecha_venta, venta.Datos_venta.Fecha_finalizada)}}</td>
                                                            <td>
                                                                <div class="table-data-feature">
                                                                    <a class="item" v-bind:href="'../../ventas/datos/?Id='+venta.Datos_venta.Id" title="Ver todos los datos">
                                                                        <i class="zmdi zmdi-mail-send"></i>
                                                                    </a>
                                                                </div>
                                                            </td>
                                                        <tr class="spacer"></tr>
                                                        </tr>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </DIV>
                            </DIV>
                        </div><!-- -->

                        <!-- SECCION INSUMOS REQUERIDOS PARA ESTE PRODUCTO -->
                        <div class="row" v-show="mostrar == '4'">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <strong>Despiece</strong>
                                    </div>
                                    <div class="card-body">
                                        <div class="bootstrap-data-table-panel col-lg-12">
                                            <div class="table-responsive">
                                                <table id="table2excel" class="table table-striped d-print-table" style="overflow-x: scroll;">
                                                    <thead>
                                                        <tr>
                                                            <th colspan="3">Pieza</th>
                                                            <th colspan="2">Materia Prima</th>
                                                            <th colspan="4">Medidas / Cantidades de piezas por producto</th>
                                                            <th colspan="4" style="background-color:skyblue;">Medidas / Cantidades de piezas para <b>{{multiplicadorProducto}}</b> productos</th>
                                                            <th style="background-color:skyblue;">
                                                                <a href="#modalInsumos" data-toggle="modal" title="Nuevo item" class="btn btn-success   d-print-none" v-on:click="limpiarFormularioInsumo()">
                                                                    <i class="ti-plus"></i> Añadir insumo
                                                                </a>

                                                            </th>
                                                        </tr>
                                                        <tr>
                                                            <th>Pos.</th>
                                                            <th>Subconjunto</th>
                                                            <th>Pieza/Proceso/Observación</th>

                                                            <th>Material</th>
                                                            <th>Comercial</th>

                                                            <th>Cant. de Piezas</th>
                                                            <th>Dimensión</th>
                                                            <th>Total</th>
                                                            <th>Un. Medida</th>

                                                            <th style="background-color:skyblue;">Cant. de Piezas</th>
                                                            <th style="background-color:skyblue;">Dimensión</th>
                                                            <th style="background-color:skyblue;">Total</th>
                                                            <th style="background-color:skyblue;">Un. Medida</th>

                                                            <th style="background-color:skyblue;">
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control" placeholder="" v-model="multiplicadorProducto" style="font-size:30px; text-align:center">
                                                                </div>
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="insumo in listaInsumos">
                                                            <td>
                                                                <a href="#modalproductosFoto" data-toggle="modal" v-on:click="editarFormularioProductoFoto(insumo, 'Posicion')">
                                                                    <i class="fa fa-picture-o"></i>
                                                                </a>
                                                                {{insumo.Posicion}}
                                                            </td>
                                                            <td>
                                                                <a href="#modalproductosFoto" data-toggle="modal" v-on:click="editarFormularioProductoFoto(insumo, 'Subconjunto')">
                                                                    <i class="fa fa-picture-o"></i>
                                                                </a>
                                                                {{insumo.Subconjunto}}
                                                            </td>
                                                            <td>{{insumo.Observaciones}}</td>

                                                            <td> <b>{{insumo.Nombre_item}}</b> </td>
                                                            <td>{{insumo.Cant_comercial}} {{insumo.Unidad_medida}}</td>

                                                            <td align="center">
                                                                <h3>{{insumo.Cantidad}}</h3>
                                                            </td>
                                                            <td align="center">
                                                                <h3>{{insumo.Dimension}}</h3>
                                                            </td>
                                                            <td align="center">
                                                                <h2>{{ insumo.Dimension * insumo.Cantidad | Decimales}}</h2>
                                                            </td>
                                                            <td>
                                                                <h4>{{insumo.Unidad_medida}}</h4>
                                                            </td>

                                                            <td align="center" style="background-color:skyblue;">
                                                                <h3>{{(insumo.Cantidad * multiplicadorProducto) | Decimales}}</h3>
                                                            </td>
                                                            <td align="center" style="background-color:skyblue;">
                                                                <h3>{{(insumo.Dimension * multiplicadorProducto) | Decimales}} </h3>
                                                            </td>
                                                            <td align="center" style="background-color:skyblue;">
                                                                <h2>{{ (insumo.Dimension * insumo.Cantidad * multiplicadorProducto) | Decimales}}</h2>
                                                            </td>
                                                            <td style="background-color:skyblue;">
                                                                <h4>{{insumo.Unidad_medida}}</h4>
                                                            </td>

                                                            <td style="background-color:skyblue;">
                                                                <a href="#modalInsumos" data-toggle="modal" v-on:click="editarFormularioInsumo(insumo)">
                                                                    <i class="fa fa-edit"></i>
                                                                </a>
                                                                <button v-on:click="desactivarAlgo(insumo.Id, 'tbl_fabricacion_insumos_producto')" class="item" title="Eliminar">
                                                                    <i class="zmdi zmdi-delete"></i>
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
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <strong>Listado de materia prima a comprar</strong>
                                    </div>
                                    <div class="card-body">
                                        <div class="bootstrap-data-table-panel col-lg-12">
                                            <div class="table-responsive">
                                                <table id="table2excel" class="table table-striped  d-print-table" style="overflow-x: scroll;">
                                                    <thead>

                                                        <tr>

                                                            <th>Material</th>
                                                            <th>Med. Comercial</th>

                                                            <th>Total para 1 producto</th>
                                                            <th>Total para <b>{{multiplicadorProducto}}</b> productos </th>
                                                            <th style="background-color:skyblue;">Cant. a comprar</th>

                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="insumo in listaMateriaTotal">

                                                            <td> <b>{{insumo.Nombre_item}}</b> </td>
                                                            <td>{{insumo.Cant_comercial}} {{insumo.Unidad_medida}}</td>

                                                            <td align="center">
                                                                <h3>{{insumo.Total_cantidad | Decimales}} {{insumo.Unidad_medida}}</h3>
                                                            </td>
                                                            <td align="center">
                                                                <h3>{{(insumo.Total_cantidad * multiplicadorProducto) | Decimales}} {{insumo.Unidad_medida}}</h3>
                                                            </td>
                                                            <td align="center" style="background-color:skyblue;">
                                                                <h1>{{(insumo.Total_cantidad * multiplicadorProducto / insumo.Cant_comercial) | Decimales}} </h1>
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
    <div class="modal fade" id="modalArchivos" tabindex="-1" role="dialog" aria-labelledby="modal" aria-hidden="true">
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
                        <form class="form-horizontal" enctype="multipart/form-data" action="post" v-on:submit.prevent="cargarArchivo()">
                            <!--   -->
                            <div class="form-group">
                                <label class=" form-control-label">Nombre del archivo</label>
                                <input type="text" class="form-control" placeholder="" v-model="archivoData.Nombre_archivo">
                            </div>
                            <div class="form-group">
                                <label class="control-label">Descripción</label>
                                <textarea class="form-control" rows="5" placeholder="" v-model="archivoData.Descripcion"></textarea>
                            </div>
                            <div class="form-group" v-show="archivoData.Url_archivo == null">
                                <div class="col-sm-12">
                                    <input @change="archivoSeleccionado" type="file" class="form-control" name="Imagen">
                                </div>
                                <div class="col-sm-12" v-if="archivoData.Url_archivo != null">
                                    Archivo previamente cargado
                                    <a target="_blank" v-bind:href="'<?php echo base_url(); ?>uploads/imagenes/'+archivoData.Url_archivo"> Ver archivo</a>
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

    <!-- Modal INSUMOS-->
    <div class="modal fade" id="modalInsumos" tabindex="-1" role="dialog" aria-labelledby="modal" aria-hidden="true">
        <div class="modal-dialog  modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{texto_boton}} información sobre insumo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="horizontal-form">
                        <form class="form-horizontal" action="post" v-on:submit.prevent="cargarInsumo()">
                            <!--   -->
                            <h3 v-if="insumoDatos.Id != null"> {{insumoDatos.Nombre_item}} </h3><br>
                            <div class="form-group" v-if="insumoDatos.Id == null">
                                <label class="control-label">Materia Prima</label>
                                <input list="productos" class="form-control" v-model="insumoDatos.Stock_id" required>
                                <datalist id="productos">
                                    <option v-for="stock in listaMateriaPrima" v-bind:value="stock.Id">{{stock.Nombre_item}} ({{stock.Unidad_medida}})</option>
                                </datalist>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class=" form-control-label">Dimensión x pieza</label>
                                        <input type="number" step="0.001" class="form-control" placeholder="" v-model="insumoDatos.Dimension">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class=" form-control-label">Cantidades de esta pieza que lleva el producto</label>
                                        <input type="number" class="form-control" placeholder="" v-model="insumoDatos.Cantidad">
                                    </div>
                                </div>
                            </div>


                            <div class="form-group">
                                <label class=" form-control-label">Posición</label>
                                <input type="text" class="form-control" placeholder="" v-model="insumoDatos.Posicion">
                            </div>
                            <div class="form-group">
                                <label class=" form-control-label">Subconjunto</label>
                                <input type="text" class="form-control" placeholder="" v-model="insumoDatos.Subconjunto">
                            </div>
                            <div class="form-group">
                                <label class="control-label">Pieza / Proceso / Observaciones </label>
                                <textarea class="form-control" rows="5" placeholder="" v-model="insumoDatos.Observaciones"></textarea>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-success" :disabled="boton_habilitado == 0">{{texto_boton}}</button>
                            </div>
                        </form>
                        <!-- <pre>{{insumoDatos}}</pre> -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /.modal -->
    <!-- Modal Producto Fotos-->
    <div class="modal fade" id="modalproductosFoto" tabindex="-1" role="dialog" aria-labelledby="modalCategoriasCartaTitle" aria-hidden="true">
        <div class="modal-dialog  modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modalItemsFoto">
                        <span v-if="productoFoto.Campo === 'Subconjunto'">
                            Imagen del subconjunto de <b>{{productoFoto.Nombre_item}}</b>
                        </span>
                        <span v-if="productoFoto.Campo === 'Posicion'">
                            Imagen de la posición de <b>{{productoFoto.Nombre_item}}</b>
                        </span>
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p align="center" v-if="productoFoto.Campo === 'Posicion'">
                        <img v-if="productoFoto.Ubicacion_pieza_url != null" class="avatar_grande" v-bind:src="'<?php echo base_url(); ?>uploads/imagenes/'+productoFoto.Ubicacion_pieza_url" alt="">
                        <img v-else class="avatar_grande" src="<?php echo base_url(); ?>uploads/imagenes/addimagen.jpg" alt="">
                    </p>
                    <p align="center" v-if="productoFoto.Campo === 'Subconjunto'">
                        <img v-if="productoFoto.Subproducto_url != null" class="avatar_grande" v-bind:src="'<?php echo base_url(); ?>uploads/imagenes/'+productoFoto.Subproducto_url" alt="">
                        <img v-else class="avatar_grande" src="<?php echo base_url(); ?>uploads/imagenes/addimagen.jpg" alt="">
                    </p>
                    <hr>
                    <div class="horizontal-form">
                        <!-- <form class="form-horizontal" action="post" enctype="multipart/form-data" v-on:submit.prevent="crearproveedors()">  -->
                        <form class="form-horizontal" action="post" enctype="multipart/form-data" v-on:submit.prevent="uploadFotoProducto(productoFoto.Id, productoFoto.Campo)">
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <input @change="archivoSeleccionado" type="file" class="form-control" name="Imagen">
                                </div>
                            </div>
                            <p v-show="preloader == 1">
                                <img src="http://grupopignatta.com.ar/images/preloader.gif" alt="">
                            </p>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-12">
                                    <button type="submit" class="btn btn-success">{{texto_boton}} imagen</button>
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
    <!-- modal PLANILLA -->
    <div class="modal fade" id="planillaModal" tabindex="-1" role="dialog" aria-labelledby="scrollmodalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="scrollmodalLabel">Listado de planillas</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <table class="table table-borderless table-striped table-earning">
                        <thead>
                            <tr>
                                <th>Nombre planilla</th>
                                <th>Descripción</th>
                                <th> </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="planilla in listaPlanillas">
                                <td>{{planilla.Nombre_planilla}}</td>
                                <td>{{planilla.Descripcion}}</td>
                                <td>
                                    <button class="item" v-on:click="editarFormularioPlanilla(planilla)" title="Editar">
                                        <i class="zmdi zmdi-edit"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <form class="form-horizontal" action="post" v-on:submit.prevent="crearPlanilla()">
                    <div class="modal-body">
                        <div class="horizontal-form">
                            <div class="form-group">
                                <label class=" form-control-label">Nombre de la planilla</label>
                                <input type="text" class="form-control" v-model="planillaDato.Nombre_planilla">
                            </div>
                            <div class="form-group">
                                <label class=" form-control-label">Descripción</label>
                                <textarea class="form-control" rows="5" v-model="planillaDato.Descripcion"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary" :disabled="boton_habilitado == 0">{{texto_boton}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- end modal PLANILLA -->
    <!-- modal ITEM VINCULO PLANILLA -->
    <div class="modal fade" id="vinculoModal" tabindex="-1" role="dialog" aria-labelledby="scrollmodalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="scrollmodalLabel">Añadir insumo a planilla</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="form-horizontal" action="post" v-on:submit.prevent="vincularItemPlanilla()">
                    <div class="modal-body">
                        <div class="horizontal-form">
                            <div class="form-group">
                                <label class=" form-control-label">Insumos</label>
                                <select class="form-control" v-model="planillaItemDato.Insumo_fabricacion_id" :disabled="planillaItemDato.Id > 0">
                                    <option selected="selected" value="0">Seleccionar</option>
                                    <option v-for="insumo in listaInsumos" v-bind:value="insumo.Id">{{insumo.Nombre_item}} | {{insumo.Posicion}} | {{insumo.Subconjunto}}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class=" form-control-label">Método / Finalidad / Procedimiento</label>
                                <textarea class="form-control" rows="5" v-model="planillaItemDato.Metodo"></textarea>
                            </div>
                            <div class="form-group">
                                <label class=" form-control-label">Observacion adicional</label>
                                <textarea class="form-control" rows="5" v-model="planillaItemDato.Observaciones"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary" :disabled="boton_habilitado == 0">{{texto_boton}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- end modal PLANILLA -->
    <!-- end modal movimientos -->
    <!-- END MAIN CONTENT-->
    <!-- END PAGE CONTAINER-->

    <?php
    // FOOTER
    include "footer.php";
    ?>


    </body>

    </html>
    <!-- end document-->