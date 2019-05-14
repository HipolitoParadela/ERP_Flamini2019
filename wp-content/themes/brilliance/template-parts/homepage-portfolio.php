<?php// $query = new WP_Query('post_type=cpo_portfolio&order=ASC&orderby=menu_order&meta_key=portfolio_featured&meta_value=1&numberposts=-1&posts_per_page=-1'); ?>
<?php //if($query->posts): ?>
<!-- <div id="portfolio" class="portfolio secondary-color-bg">
	<?php// cpotheme_grid($query->posts, 'element', 'portfolio', 4, array('class' => 'column-fit')); ?>
</div> -->
<?php //endif; ?>



<div id="portfolio" class="portfolio">
    <div class="row">

            <?php 
                //CONECTAR LA BASE DE DATOS --- y repetir este loop... habria q ver como... luego también hacerlo en una página web de todos los productos
                $con=mysqli_connect("localhost","c1481017_erp","foVOwa06se","c1481017_erp");
                // Check connection
                if (mysqli_connect_errno())
                {
                    echo "Failed to connect to MySQL: " . mysqli_connect_error();
                }   

                if (!$con->set_charset("utf8")) {//asignamos la codificación comprobando que no falle
                    die("Error cargando el conjunto de caracteres utf8");
                }
                //$mysqli->set_charset("utf8");

                    $consulta = "select * from tbl_fabricacion where Empresa_id = '1' and Visible = '1'";
                    $resultado = mysqli_query($con, $consulta);
                    $num_resultados = mysqli_num_rows($resultado);

                    for ($i=0; $i < $num_resultados; $i++) 
                    { 
                        $row = mysqli_fetch_array($resultado);
                        
                        echo'
                        <div class="column column-fit col4">
                            <div class="portfolio-item">
                                <a class="portfolio-item-image" href="#">
                                    <div class="portfolio-item-overlay dark">
                                        <h3 class="portfolio-item-title">
                                            '.$row["Nombre_producto"].'
                                        </h3>
                                        <div class="portfolio-item-description">
                                            '.$row["Descripcion_publica_corta"].'
                                        </div>
                                    </div>
                                    <img width="400" height="400" src="ERP/uploads/imagenes/'.$row["Imagen"].'" class="attachment-portfolio size-portfolio wp-post-image" alt="" title="" srcset="ERP/uploads/imagenes/'.$row["Imagen"].' 400w, ERP/uploads/imagenes/'.$row["Imagen"].' 150w" sizes="(max-width: 400px) 100vw, 400px" />
                                </a>
                            </div>
                        </div>';
                    }

            ?>
    </div>
</div>