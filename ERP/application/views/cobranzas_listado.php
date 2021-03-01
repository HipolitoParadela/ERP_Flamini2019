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
                                    <select class="form-control form-control" v-model="filtro_planificacion" v-on:change="obtener_listado_ventas_cobranzas(filtro_vendedor, filtro_empresa ,filtro_cliente, filtro_estado, filtro_planificacion)">
                                        <option selected="selected" v-bind:value="0">Todas las planificaciones</option>
                                        <option v-for="planificacion in listaPlanificaciones" v-bind:value="planificacion.Id">{{planificacion.Nombre_planificacion}}</option>
                                    </select>
                                    <div class="dropDownSelect2"></div>
                                </div>
                                <div class="rs-select2--light">
                                    <select class="form-control form-control" v-model="filtro_vendedor" v-on:change="obtener_listado_ventas_cobranzas(filtro_vendedor, filtro_empresa ,filtro_cliente, filtro_estado, filtro_planificacion)">
                                        <option selected="selected" v-bind:value="0">Todos los vendedores</option>
                                        <option v-for="usuario in listaUsuarios" v-bind:value="usuario.Id">{{usuario.Nombre}}</option>

                                        <!-- ACA DEBE TRAER SOLO USUARIOS VENDEDORES -->

                                    </select>
                                    <div class="dropDownSelect2"></div>
                                </div>
                                <div class="rs-select2--light">
                                    <select class="form-control form-control" v-model="filtro_empresa" v-on:change="obtener_listado_ventas_cobranzas(filtro_vendedor, filtro_empresa ,filtro_cliente, filtro_estado, filtro_planificacion)">
                                        <option selected="selected" v-bind:value="0">Todas las empresas</option>
                                        <option v-for="empresa in listaEmpresas" v-bind:value="empresa.Id">{{empresa.Nombre_empresa}}</option>
                                    </select>
                                    <div class="dropDownSelect2"></div>
                                </div>
                                <div class="rs-select2--light">
                                    <select class="form-control form-control" v-model="filtro_cliente" v-on:change="obtener_listado_ventas_cobranzas(filtro_vendedor, filtro_empresa ,filtro_cliente, filtro_estado, filtro_planificacion)">
                                        <option selected="selected" v-bind:value="0">Todos los clientes</option>
                                        <option v-for="cliente in listaClientes" v-bind:value="cliente.Id">{{cliente.Nombre_cliente}}</option>
                                    </select>
                                    <div class="dropDownSelect2"></div>
                                </div>
                                <div class="rs-select2--light">
                                    <select class="form-control form-control" v-model="filtro_estado" v-on:change="obtener_listado_ventas_cobranzas(filtro_vendedor, filtro_empresa ,filtro_cliente, filtro_estado, filtro_planificacion)">
                                        <option selected="selected" v-bind:value="1">Ventas en proceso</option>
                                        <option selected="selected" v-bind:value="10">Ventas cerradas</option>
                                    </select>
                                    <div class="dropDownSelect2"></div>
                                </div>
                                <div class="rs-select2--light rs-select2--sm">
                                    <input type="text" class="form-control form-control" placeholder="Buscar venta" v-model="buscar">
                                </div>
                                <!--<button class="au-btn-filter"><i class="zmdi zmdi-filter-list"></i>Filtros</button>-->
                            </div>
                            <div class="table-data__tool-right">
                                <button class="au-btn au-btn-icon au-btn--green au-btn--small" data-toggle="modal" data-target="#ventaModal" v-on:click="limpiarFormularioVenta()">
                                    <i class="zmdi zmdi-plus"></i>Nueva venta
                                </button>
                                <a href="<?php echo base_url(); ?>cobranzas/cobros" class="btn btn-info">
                                    COBROS DIARIOS
                                </a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="table-responsive">
                                    <table class="table table-borderless table-striped table-earning">
                                        <thead>
                                            <tr>
                                                <th>Identificador</th>
                                                <th>Cliente</th>
                                                <th>Estado</th>

                                                <th>Monto Venta</th>
                                                <th>Cob. IMU</th>
                                                <th>Cob. S.Junior</th>
                                                <th style="background-color:darkseagreen;">Saldo</th>

                                                <th>Ult. Cobro</th>
                                                <th>Iniciado</th>
                                                <th>Entregado</th>

                                                <th>Vendedor</th>
                                                <th></th>
                                            </tr>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th></th>

                                                <th><h3 style="color:white" align="right">$ {{ listaCobranzas.Total_monto_venta | Moneda }}</h3></th>
                                                <th><h3 style="color:white" align="right">$ {{ listaCobranzas.Total_cobros_IMU | Moneda }}</h3></th>
                                                <th><h3 style="color:white" align="right">$ {{ listaCobranzas.Total_cobrosSJunior | Moneda }}</h3></th>
                                                <th style="background-color:darkseagreen;"><h1 style="color:white" align="right">$ {{ listaCobranzas.Total_saldo | Moneda }}</h1></th>

                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
                                            <tr v-for="venta in listaCobranzas.Datos">
                                                <td>
                                                    <h4>
                                                        <button class="au-btn au-btn-icon au-btn--green" data-toggle="modal" data-target="#cobroModal" v-on:click="limpiarFormularioCobro(venta)">
                                                            +
                                                        </button>
                                                        <a class="btn btn-dark btn-outline m-b-10 m-l-5" v-bind:href="'ventas/datos/?Id='+venta.Id" title="Ver todos los datos">
                                                            {{venta.Identificador_venta}}
                                                        </a>
                                                    </h4>
                                                </td>
                                                <td>{{venta.Nombre_cliente}}</td>
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
                                                    <h4 align="right">$ {{venta.Monto_venta | Moneda}}</h4>
                                                </td>
                                                <td>
                                                    <h4 align="right">$ {{venta.Cobros_IMU | Moneda}}</h4>
                                                </td>
                                                <td>
                                                    <h4 align="right">$ {{venta.CobrosSJunior | Moneda}}</h4>
                                                </td>
                                                <td style="background-color:darkseagreen;">
                                                    <h3 align="right">$ {{venta.Saldo | Moneda}}</h3>
                                                </td>

                                                <td>{{venta.Fecha_ult_cobro | Fecha }} ( {{ venta.Fecha_ult_cobro | DiasTranscurridos}} )</td>
                                                <td>{{venta.Fecha_venta | Fecha }} ( {{ venta.Fecha_venta | DiasTranscurridos}} )</td>
                                                <td>{{venta.Fecha_entregado | Fecha }} ( {{ venta.Fecha_entregado | DiasTranscurridos}} )</td>

                                                <td>{{venta.Nombre_vendedor}}</td>
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
                                        <tfoot>
                                        <tr>
                                                <th></th>
                                                <th></th>
                                                <th></th>

                                                <th><h3 align="right">$ {{ listaCobranzas.Total_monto_venta | Moneda }}<h3></th>
                                                <th><h3 align="right">$ {{ listaCobranzas.Total_cobros_IMU | Moneda }}<h3></th>
                                                <th><h3 align="right">$ {{ listaCobranzas.Total_cobrosSJunior | Moneda }}<h3></th>
                                                <th><h1 align="right">$ {{ listaCobranzas.Total_saldo | Moneda }}<h1></th>

                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                            </tfoot>
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
    <!-- Modal efectivo-->
    <div class="modal fade" id="cobroModal" tabindex="-1" role="dialog" aria-labelledby="modalCategoriasCartaTitle" aria-hidden="true">
        <div class="modal-dialog  modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalItemsFoto">Registrar cobro a {{cobroDatos.Identificador_venta}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="horizontal-form">
                        <form class="form-horizontal" action="post" v-on:submit.prevent="crear_movimiento()">
                            <div class="form-group">
                                <label class=" form-control-label">Empresa que factura el pago</label>
                                <select class="form-control" v-model="cobroDatos.Empresa_id" required>
                                    <option v-for="empresa in listaEmpresas" v-bind:value="empresa.Id">{{empresa.Nombre_empresa}}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Identificador de la factura</label>
                                <input type="text" class="form-control" v-model="cobroDatos.Identificador_factura" required>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Monto</label>
                                <input type="number" class="form-control" v-model="cobroDatos.Monto" required>
                            </div>
                            <div class="form-group">
                                <label class=" form-control-label">Modalidad de pago</label>
                                <select class="form-control" v-model="cobroDatos.Modalidad_pago" required>
                                    <option value="Efectivo">Efectivo</option>
                                    <option value="Banco">Banco</option>
                                    <option value="Cheque">Cheque</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Fecha en que se realizó el pago</label>
                                <input type="date" class="form-control" v-model="cobroDatos.Fecha_ejecutado" required>
                            </div>
                            <div class="form-group">
                                <label class=" form-control-label">Observaciones</label>
                                <textarea class="form-control" rows="5" v-model="cobroDatos.Observaciones"></textarea>
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

    <!-- END MAIN CONTENT-->
    <!-- END PAGE CONTAINER-->




    <?php
    // CABECERA
    include "footer.php";
    ?>

    </body>

    </html>
    <!-- end document-->