<?php /* Template Name: Array Empresa 2 */ 

    //CONECTAR LA BASE DE DATOS --- y repetir este loop... habria q ver como... luego también hacerlo en una página web de todos los productos
    $con=mysqli_connect("http://imuargentina.com.ar","c1481017_erp","foVOwa06se","c1481017_erp");
	
	// Check connection
    if (mysqli_connect_errno())
    {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }   

	if (!$con->set_charset("utf8")) 
	{//asignamos la codificación comprobando que no falle
        die("Error cargando el conjunto de caracteres utf8");
	}

    $consulta = "select * from tbl_fabricacion where Empresa_id = 2";
    $resultado = mysqli_query($con, $consulta);
    $num_resultados = mysqli_num_rows($resultado);

            
	while($row = mysqli_fetch_array($resultado)) 
	{ 
		$Id					= $row['Id'];
		$Nombre_producto	= $row['Nombre_producto'];
		$Imagen				= $row['Imagen'];
		$Descripcion_publica_corta	= $row['Descripcion_publica_corta'];
		$email	=$row['email'];
		

		$array_json[] = array('Id'=> $Id, 'Nombre_producto'=> $Nombre_producto, 'Imagen'=> $Imagen, 'Descripcion_publica_corta'=> $Descripcion_publica_corta);

	}
		
	//desconectamos la base de datos
	/*$close = mysqli_close($conexion) 
	or die("Ha sucedido un error inexperado en la desconexion de la base de datos");*/
	

	//Creamos el JSON
	$json_string = json_encode($array_json);
	echo $json_string;

?>