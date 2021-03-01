<?php

    header('Content-type: application/vnd.msexcel; charset=iso-8859-15');
    header('Content-Disposition: attachment; filename='.$nombreArchivo.'.xls');

    echo $tablaHTML;

?>