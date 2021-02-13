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
                    <div class="table-data__tool">
                        <div class="col-md-12">
                            <!-- DATA TABLE -->
                            <h3 class="title-5 m-b-35">Listado de cobros</h3>

                            <div class="table-data__tool-left">
                                <div class="rs-select2--light">
                                    <a href="<?php echo base_url(); ?>cobranzas" class="btn btn-info">
                                        COBRANZAS
                                    </a>
                                </div>
                                <div class="rs-select2--light">

                                    <button class="btn btn-secondary btn-block" v-on:click="obtener_listado_cobros(null, null)"> Desde siempre</button>

                                    <div class="dropDownSelect2"></div>
                                </div>
                                <div class="rs-select2--light">

                                    <input type="date" class="form-control" v-model="filtroFechaInicial">

                                    </select>
                                    <div class="dropDownSelect2"></div>
                                </div>
                                <div class="rs-select2--light">

                                    <input type="date" class="form-control" v-model="filtroFechaFinal" :disabled="filtroFechaInicial == null" v-on:change="obtener_listado_cobros(filtroFechaInicial, filtroFechaFinal)">

                                    <div class="dropDownSelect2"></div>
                                </div>
                                <div class="rs-select2--light" v-if="filtroFechaFinal == null">

                                    Ultimos 30 días

                                    <div class="dropDownSelect2"></div>
                                </div>

                            </div>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="table-responsive table--no-card m-b-40">
                                <table class="table table-borderless table-striped table-earning">
                                    <thead>
                                        <tr>
                                            <th>Identificador</th>
                                            <th>Cliente</th>

                                            <th>Cob. IMU</th>
                                            <th>Cob. S.Junior</th>
                                            <th>Monto Venta</th>

                                            <th>Fecha Cobro</th>
                                            <th>Modalidad</th>
                                            <th>Observaciones</th>
                                            <th>Vendedor</th>
                                        </tr>
                                        <tr>
                                            <th></th>
                                            <th></th>

                                            <th>
                                                <h3 style="color:white" align="right">$ {{ listaCobros.Cobros_IMU | Moneda }}</h3>
                                            </th>
                                            <th>
                                                <h3 style="color:white" align="right">$ {{ listaCobros.Cobros_sJunior | Moneda }}</h3>
                                            </th>
                                            <th>
                                                <h3 style="color:white" align="right">$ {{ ( listaCobros.Total ) | Moneda }}</h3>
                                            </th>

                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <tr v-for="cobro in listaCobros.Datos">
                                            <td>
                                                <h4>
                                                    <a class="btn btn-dark btn-outline m-b-10 m-l-5" v-bind:href="'../../ventas/datos/?Id='+cobro.Venta_id" title="Ver todos los datos">
                                                        {{cobro.Identificador_venta}}
                                                    </a>
                                                </h4>
                                            </td>
                                            <td> {{cobro.Nombre_cliente}}</td>
                                            <td>
                                                <h4 align="right">$ {{cobro.Cobro_IMU | Moneda}}</h4>
                                            </td>
                                            <td>
                                                <h4 align="right">$ {{cobro.Cobro_sJunior | Moneda}}</h4>
                                            </td>
                                            <td>
                                                <h4 align="right">$ {{ cobro.Total | Moneda}}</h4>
                                            </td>

                                            <td>{{cobro.Fecha_ejecutado | Fecha }} ( {{ cobro.Fecha_ejecutado | DiasTranscurridos}} )</td>

                                            <td>{{cobro.Modalidad_pago}}</td>
                                            <td>{{cobro.Observaciones}}</td>
                                            <td>{{cobro.Nombre_vendedor}}</td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th></th>
                                            <th></th>

                                            <th>
                                                <h3 align="right">$ {{ listaCobros.Cobros_IMU | Moneda }}</h3>
                                            </th>
                                            <th>
                                                <h3 align="right">$ {{ listaCobros.Cobros_sJunior | Moneda }}</h3>
                                            </th>
                                            <th>
                                                <h3 align="right">$ {{ listaCobros.Total | Moneda }}</h3>
                                            </th>

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


<!-- END MAIN CONTENT-->
<!-- END PAGE CONTAINER-->


<?php
// CABECERA
include "footer.php";
?>

</body>

</html>
<!-- end document-->