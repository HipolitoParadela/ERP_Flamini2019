<?php
// CABECERA
include "head.php";
// TOP
include "top.php";
include "menusidebar.php";
?>
<!-- PAGE CONTAINER-->
<div class="page-container" id="app">
    <!-- HEADER DESKTOP-->
    <?PHP include "header_desktop.php"; ?>
    <!-- HEADER DESKTOP-->

    <!-- MAIN CONTENT-->
    <div class="main-content">
        <div class="section__content section__content--p30">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <!-- DATA TABLE -->
                        <h3 class="title-5 m-b-35">Cobranzas</h3>

                        <div class="table-data__tool">
                            <div class="table-data__tool-left">
                                <div class="rs-select2--light">
                                    <select class="form-control-sm form-control" v-model="filtro_categoria" v-on:change="getListadoStockPanol(stock_tipo, filtro_categoria)">
                                        <option selected="selected" value="0">Pendientes</option>
                                        <option selected="selected" value="1">Finalizadas</option>
                                    </select>
                                    <div class="dropDownSelect2"></div>
                                </div>
                                <div class="rs-select2--light rs-select2--sm">
                                    <input type="text" class="form-control-sm form-control" placeholder="Buscar item" v-model="buscar">
                                </div>
                                <!--<button class="au-btn-filter"><i class="zmdi zmdi-filter-list"></i>Filtros</button>-->
                            </div>
                            <div class="table-data__tool-right">
                                <button class="au-btn au-btn-icon au-btn--green au-btn--small" data-toggle="modal" data-target="#cobroModal" v-on:click="limpiarFormularioCobro()">
                                    <i class="zmdi zmdi-plus"></i>Registar cobro
                                </button>
                            </div>
                        </div>
                        <div class="row">
                    <div class="col-lg-12">
                        <h2 class="title-1 m-b-25">Ventas - En proceso</h2>
                        <div class="table-responsive table--no-card m-b-40">
                            <table class="table table-borderless table-striped table-earning">
                                <thead>
                                    <tr>
                                        <th>Identificador</th>
                                        <th>Estado Actual</th>
                                        <th>Empresa</th>
                                        <th>Vendedor</th>
                                        <th>Cliente</th>
                                        <th>Iniciado</th>
                                        <th>Finalización estimada</th>
                                        <th>Días restantes estimados</th>


                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="venta in listaVentas">
                                        <td>
                                            <h4>{{venta.Identificador_venta}}</h4>
                                        </td>
                                        <td v-if="venta.Estado == 1"><b>Compras</b></td>
                                        <td v-if="venta.Estado == 2"><b>Proceso de materiales</b></td>
                                        <td v-if="venta.Estado == 3"><b>Soldadura</b></td>
                                        <td v-if="venta.Estado == 4"><b>Pintura</b></td>
                                        <td v-if="venta.Estado == 5"><b>Rotulación</b></td>
                                        <td v-if="venta.Estado == 6"><b>Empaque</b></td>
                                        <td v-if="venta.Estado == 7"><b>Logística</b></td>
                                        <td v-if="venta.Estado == 8"><b>Instalación</b></td>
                                        <td v-if="venta.Estado == 9"><b>Cobranza</b></td>
                                        <td>
                                            {{venta.Nombre_empresa}}
                                        </td>
                                        <td>{{venta.Nombre_vendedor}}</td>
                                        <td>
                                            {{venta.Nombre_cliente}}
                                        </td>

                                        <td>{{formatoFecha(venta.Fecha_venta)}}</td>

                                        <td>{{formatoFecha(venta.Fecha_estimada_entrega)}}</td>
                                        <td>{{diferenciasEntre_fechas(null, venta.Fecha_estimada_entrega)}}</td>

                                        <td>
                                            <div class="table-data-feature">

                                                <a class="item" v-bind:href="'ventas/datos/?Id='+venta.Id" title="Ver todos los datos">
                                                    <i class="zmdi zmdi-mail-send"></i>
                                                </a>
                                                </button>

                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                        <!-- END DATA TABLE -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- modal stock -->
    <div class="modal fade" id="cobroModal" tabindex="-1" role="dialog" aria-labelledby="scrollmodalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="scrollmodalLabel">Formulario de stock</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="form-horizontal" action="post" v-on:submit.prevent="crearStock()">
                    <div class="modal-body">
                        <div class="horizontal-form">
                            <div class="form-group">
                                <label class=" form-control-label">Nombre del item</label> <input type="text" class="form-control" placeholder="" v-model="stockDato.Nombre_item">
                            </div>
                            <div class="form-group" v-if="stockDato.Id">
                                <label class="control-label">Tipo de producto</label>
                                <select class="form-control" v-model="stockDato.Tipo">
                                    <option value="2">Materia Prima</option>
                                    <option value="1">Pañol</option>
                                    <option value="3">Producto de reventa</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Categoría</label>
                                <select class="form-control" v-model="stockDato.Categoria_id">
                                    <option value="0">Sin categoría</option>
                                    <option v-for="categoria in listaCategorias" v-bind:value="categoria.Id">{{categoria.Nombre_categoria}}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Unidad de medida</label>
                                <select class="form-control" v-model="stockDato.Unidad_medida">
                                    <option value="Un.">Unidad</option>
                                    <option value="Packs">Pack</option>
                                    <option value="Cajas">Cajas</option>
                                    <option value="Mtrs">Metros</option>
                                    <option value="Cms">Centimetros</option>
                                    <option value="Litro">Litro</option>
                                    <option value="Grs">Gramos</option>
                                    <option value="Kgs">Kilogramos</option>

                                </select>
                            </div>
                            <div class="form-group">
                                <label class=" form-control-label">Cantidad inicial</em></label>
                                <input type="number" class="form-control" placeholder="" v-model="stockDato.Cant_actual"> <!-- :disabled="stockDato.Id" -->
                            </div>
                            <div class="form-group">
                                <label class=" form-control-label">Cantidad ideal</label>
                                <input type="number" class="form-control" placeholder="" v-model="stockDato.Cant_ideal">
                            </div>
                            <div class="form-group">
                                <label class=" form-control-label">Descripción</label>
                                <textarea class="form-control" placeholder="" v-model="stockDato.Descripcion"></textarea>
                            </div>
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
    <!-- end modal stock -->

    <!-- END MAIN CONTENT-->
    <!-- END PAGE CONTAINER-->




    <?php
    // CABECERA
    include "footer.php";
    ?>

    </body>

    </html>
    <!-- end document-->