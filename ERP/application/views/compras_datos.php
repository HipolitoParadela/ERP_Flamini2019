<?php
// CABECERA
include "head.php";
// TOP
include "top.php";
include "menusidebar.php";
?>
<!-- PAGE CONTAINER-->
<div class="page-container" id="compras">
    <!-- HEADER DESKTOP-->
    <?PHP include "header_desktop.php"; ?>
    <!-- HEADER DESKTOP-->

    <!-- MAIN CONTENT-->
    <div class="main-content">
        <div class="section__content section__content--p30">
            <div class="container-fluid">
                <div class="row">


                    <!-- SECCION FICHA compra -->
                    <div class="col-lg-12">

                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a class="nav-link" v-bind:class="{ active: mostrar == 1 }" href="#" v-on:click="mostrar = 1">Ficha</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" v-bind:class="{ active: mostrar == 2 }" href="#" v-on:click="getListaProductosProveedor()">Productos comprados</a>
                            </li>
                        </ul>

                        <!-- SECCION DATOS EDITABLES DEL compra -->
                        <div class="row" v-show="mostrar == '1'">
                            <div class="col-lg-2">
                                <div class="card">
                                    <div class="card-header">
                                        <h4>Info</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="user-photo m-b-30">
                                            <img v-if="compraDatos.Imagen != null" width="420px" v-bind:src="'<?php echo base_url(); ?>uploads/imagenes/'+compraDatos.Imagen" alt="">
                                            <img v-else src="<?php echo base_url(); ?>uploads/imagenes/addimagen.jpg" alt="">
                                        </div>
                                        <h5 class="text-sm-center mt-2 mb-1">{{compraDatos.Factura_identificador}}</h5>
                                        <div class="location text-sm-center">
                                            Proveedor: {{compraDatos.Nombre_proveedor}}
                                        </div>
                                        <div v-show="compraDatos.Imagen != null" class="location text-sm-center">
                                            <a target="_blank" v-bind:href="'<?php echo base_url(); ?>uploads/imagenes/'+compraDatos.Imagen" class="btn btn-secondary-sm btn-block">
                                                <i class="fa fa-share-square"></i> VER FACTURA
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <a target="_blank" v-bind:href="'https://api.whatsapp.com/send?phone='+compraDatos.Telefono" class="btn btn-success btn-block">
                                        <i class="fab fa-whatsapp"></i> Enviar whatsapp a {{compraDatos.Nombre_proveedor}}
                                    </a>
                                    <hr>
                                    <a target="_blank" v-bind:href="'mailto:'+compraDatos.Email" class="btn btn-info btn-block">
                                        <i class="fa fa-envelope"></i> Enviar email a {{compraDatos.Nombre_proveedor}}
                                    </a>
                                </div>
                                <hr>
                                <span v-show="compraDatos.Web != null">
                                    <a target="_blank" v-bind:href="'http://'+compraDatos.Web" class="btn btn-secondary btn-block">
                                        <i class="fa fa-share-square"></i> {{compraDatos.Web}}
                                    </a>
                                </span>
                            </div>
                            <div class="col-lg-10">
                                <div class="card">
                                    <div class="card-header">
                                        <strong>{{compraDatos.Factura_identificador}} de fecha {{compraDatos.Fecha_compra | Fecha}}</strong>
                                    </div>
                                    <div class="card-body">
                                        <div class="horizontal-form">
                                            <form class="form-horizontal" action="post" v-on:submit.prevent="crearcompra()">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <h4>Datos compra</h4>
                                                        <hr>
                                                        <div class="form-group">
                                                            <label class=" form-control-label">Número factura o Identificador de la compra</label>
                                                            <input type="text" class="form-control" placeholder="" v-model="compraDatos.Factura_identificador" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label">Planificación</label>
                                                            <select class="form-control" v-model="compraDatos.Planificacion_id" required>
                                                                <option value="0">Seleccionar planificación</option>
                                                                <option v-for="planificacion in listaPlanificaciones" v-bind:value="planificacion.Id">{{planificacion.Nombre_planificacion}}</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class=" form-control-label">Seleccionar proveedor</label>
                                                            <select class="form-control" v-model="compraDatos.Proveedor_id" required>
                                                                <option value="0">No asignar proveedor</option>
                                                                <option v-for="proveedor in listaProveedores" v-bind:value="proveedor.Id">{{proveedor.Nombre_proveedor}}</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class=" form-control-label">Fecha en que se realizó la compra</label>
                                                            <input type="date" class="form-control" placeholder="" v-model="compraDatos.Fecha_compra" required>
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="control-label">Monto de la compra en pesos</label>
                                                            <input type="number" class="form-control" placeholder="" v-model="compraDatos.Valor" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class=" form-control-label">Descripción de la compra</label>
                                                            <textarea class="form-control" rows="5" v-model="compraDatos.Descripcion"></textarea>
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

                        <!-- SECCION DATOS DE PRODUCTOS -->
                        <div class="row" v-show="mostrar == '2'">
                            <div class="col-lg-4">
                                <div class="card">
                                    <div class="card-header">
                                        <strong>Productos comprados</strong>
                                    </div>
                                    <div class="card-body">
                                        <div class="bootstrap-data-table-panel col-lg-12">

                                            <div class="table-responsive">
                                                <table id="table2excel" class="table table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>Producto</th>
                                                            <th>Cantidad</th>
                                                            <th>Descripción</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="productoComprado in listaComprados">
                                                            <td>{{productoComprado.Nombre_item}}</td>
                                                            <td>{{productoComprado.Cantidad}}</td>
                                                            <td>{{productoComprado.Descripcion}}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </DIV>
                            </DIV>
                            <div class="col-lg-8">
                                <div class="card">
                                    <div class="card-header">
                                        <strong>Productos de este proveedor</strong>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-data__tool-right">
                                            <div class="rs-select2--light ">
                                                <input type="text" class="form-control form-control" placeholder="Buscar producto" v-model="buscar">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="bootstrap-data-table-panel col-lg-12">
                                            <div class="table-responsive">
                                                <table id="table2excel" class="table table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>Producto</th>
                                                            <th>Cantidad</th>
                                                            <th>Descripción</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="(producto, index) in buscarProducto">
                                                            <td>{{producto.Nombre_item}}</td>
                                                            <td><input size="6" type="number" class="form-control" v-model="cantMovimientoStock[index]"></td>
                                                            <td>
                                                                <div class="input-group">
                                                                    <input type="text" placeholder="Descripción" class="form-control" v-model="descripcionMovimiento[index]" :disabled="cantMovimientoStock[index] == null">
                                                                    <div class="input-group-btn">
                                                                        <button class="btn btn-warning" v-on:click="movimientoStock(producto.Id, cantMovimientoStock[index], descripcionMovimiento[index])" :disabled="cantMovimientoStock[index] == null">
                                                                            <i class="fa fa-save"></i>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </DIV>
                            </DIV>
                        </div>

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