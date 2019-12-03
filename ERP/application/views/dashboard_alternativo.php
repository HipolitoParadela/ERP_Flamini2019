<?php
// CABECERA
include "head.php";
// TOP
include "top.php";
include "menusidebar.php";
?>
<!-- PAGE CONTAINER-->
<div class="page-container">
    <!-- HEADER DESKTOP-->
    <?PHP include "header_desktop.php"; ?>
    <!-- HEADER DESKTOP-->

    <!-- MAIN CONTENT-->
    <div class="main-content" id="dashboard">
        <div class="section__content section__content--p30">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="overview-wrap">
                            <h2 class="title-1">Dasboard</h2>
                            <!--<button class="au-btn au-btn-icon au-btn--blue">
                                        <i class="zmdi zmdi-plus"></i>add item</button>-->
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-5" v-if="Usuario_id == 3">
                        <div class="au-card au-card--no-shadow au-card--no-pad m-b-40">
                            <div class="au-card-title" style="background-image:url('https://static.iris.net.co/dinero/upload/images/2019/4/15/269729_1.jpg');">
                                <div class="bg-overlay "></div><!-- bg-overlay--blue -->
                                <h3>
                                    <i class="zmdi zmdi-comment-text"></i>Reportes de recursos humanos</h3>
                                <!-- <button class="au-btn-plus">
                                            <i class="zmdi zmdi-plus"></i>
                                        </button> -->
                            </div>
                            <div class="au-inbox-wrap js-inbox-wrap">
                                <div class="au-message js-list-load">
                                    <div class="au-message-list">
                                        
                                        <div v-for="reporte in listaSeguimientoPersonal" class="au-message__item">
                                            <div class="au-message__item-inner">
                                                <div class="au-message__item-text">
                                                    <div class="avatar-wrap">
                                                        <div class="avatar">
                                                            <img v-bind:src="reporte.Imagen">
                                                        </div>
                                                    </div>
                                                    <div class="text">
                                                        <h5 class="name">{{reporte.Nombre}}</h5>
                                                        <p>{{reporte.Descripcion}}</p>
                                                        
                                                    </div>
                                                </div>
                                                <div class="au-message__item-time">
                                                    <span class="text-primary">{{reporte.Fecha | Fecha}}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <div class="au-message__footer">
                                                <button class="au-btn au-btn-load js-load-btn">load more</button>
                                            </div> -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <div class="au-card au-card--no-shadow au-card--no-pad m-b-40">
                            <div class="au-card-title" style="background-image:url('http://imuargentina.com.ar/wp-content/uploads/2019/03/Sin-título-1.jpg');">
                                <div class="bg-overlay "></div><!-- bg-overlay--blue -->
                                <h3>
                                    <i class="zmdi zmdi-comment-text"></i>Noticias del día</h3>
                                <!-- <button class="au-btn-plus">
                                            <i class="zmdi zmdi-plus"></i>
                                        </button> -->
                            </div>
                            <div class="au-inbox-wrap js-inbox-wrap">
                                <div class="au-message js-list-load">
                                    <div class="au-message__noti">
                                        <p>
                                            Selección de Google Noticias
                                        </p>
                                    </div>
                                    <div class="au-message-list">
                                        <div v-for="noticia in listaNoticias.articles" class="au-message__item">
                                            <div class="au-message__item-inner">
                                                <div class="au-message__item-text">
                                                    <div class="avatar-wrap">
                                                        <div class="avatar">
                                                            <img v-bind:src="noticia.urlToImage">
                                                        </div>
                                                    </div>
                                                    <div class="text">
                                                        <h5 class="name">{{noticia.title}}</h5>
                                                        <p>{{noticia.content}}</p>
                                                        <p><a target="_blank" v-bind:href="noticia.url">Leer más </a></p>
                                                    </div>
                                                </div>
                                                <div class="au-message__item-time">
                                                    <span>{{noticia.name}}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <div class="au-message__footer">
                                                <button class="au-btn au-btn-load js-load-btn">load more</button>
                                            </div> -->
                                </div>
                                <!-- <div class="au-chat">
                                            <div class="au-chat__title">
                                                <div class="au-chat-info">
                                                    <div class="avatar-wrap online">
                                                        <div class="avatar avatar--small">
                                                            <img src="images/icon/avatar-02.jpg" alt="John Smith">
                                                        </div>
                                                    </div>
                                                    <span class="nick">
                                                        <a href="#">John Smith</a>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="au-chat__content">
                                                <div class="recei-mess-wrap">
                                                    <span class="mess-time">12 Min ago</span>
                                                    <div class="recei-mess__inner">
                                                        <div class="avatar avatar--tiny">
                                                            <img src="images/icon/avatar-02.jpg" alt="John Smith">
                                                        </div>
                                                        <div class="recei-mess-list">
                                                            <div class="recei-mess">Lorem ipsum dolor sit amet, consectetur adipiscing elit non iaculis</div>
                                                            <div class="recei-mess">Donec tempor, sapien ac viverra</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="send-mess-wrap">
                                                    <span class="mess-time">30 Sec ago</span>
                                                    <div class="send-mess__inner">
                                                        <div class="send-mess-list">
                                                            <div class="send-mess">Lorem ipsum dolor sit amet, consectetur adipiscing elit non iaculis</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="au-chat-textfield">
                                                <form class="au-form-icon">
                                                    <input class="au-input au-input--full au-input--h65" type="text" placeholder="Type a message">
                                                    <button class="au-input-icon">
                                                        <i class="zmdi zmdi-camera"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div> -->
                            </div>
                        </div>
                    </div>
                </div>
            
            </div>
        </div>
    </div>


            <?php
            // footer
            include "footer.php";
            ?>

            </body>

            </html>
            <!-- end document-->