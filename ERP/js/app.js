/// *********************************************************************************************/////
//// FILTROS
/// *******************************************

/// FECHA TIME STAMP
Vue.filter('FechaTimestamp', function (fecha) { 
    fecha = fecha.split('T');

    //var fecha_dia = fecha[0].replace(/^(\d{4})-(\d{2})-(\d{2})$/g, '$3/$2/$1');
    var fecha_dia = fecha[0].replace(/^(\d{4})-(\d{2})-(\d{2})$/g, '$3/$2/$1');

    var fecha_hora = fecha[1].split(':');
    fecha_hora = fecha_hora[0] + ':' + fecha_hora[1];

    //return fecha_dia + ' ' + fecha_hora + 'hs '
    return fecha_dia
})

/// FECHA TIME STAMP de MYSQL
Vue.filter('FechaTimeBD', function (fecha) {
    if (fecha != null) {
        fecha = fecha.split(' ');

        //var fecha_dia = fecha[0].replace(/^(\d{4})-(\d{2})-(\d{2})$/g, '$3/$2/$1');
        var fecha_dia = fecha[0].replace(/^(\d{4})-(\d{2})-(\d{2})$/g, '$3/$2/$1');

        var fecha_hora = fecha[1].split(':');
        fecha_hora = fecha_hora[0] + ':' + fecha_hora[1];

        return fecha_dia + ' ' + fecha_hora + 'hs '
        //return fecha_dia
    }
    else {
        return 'No definida'
    }

})

/// FECHA 
Vue.filter('Fecha', function (fecha) {

    if (fecha != null) {
        return fecha.replace(/^(\d{4})-(\d{2})-(\d{2})$/g, '$3/$2/$1');
    }
    else {
        return "Sin definir";
    }

})

/// FORMATO DINERO
Vue.filter('Moneda', function (numero) {
    /// PARA QUE FUNCIONE DEBE TOMAR EL NUMERO COMO UN STRING
    //si no lo es, lo convierte

    if (numero > 0 || numero != null) {
        //if (numero % 1 == 0) {
        numero = numero.toString();
        //}

        // Variable que contendra el resultado final
        var resultado = "";
        var nuevoNumero;

        // Si el numero empieza por el valor "-" (numero negativo)
        if (numero[0] == "-") {
            // Cogemos el numero eliminando los posibles puntos que tenga, y sin
            // el signo negativo
            nuevoNumero = numero.replace(/\./g, '').substring(1);
        } else {
            // Cogemos el numero eliminando los posibles puntos que tenga
            nuevoNumero = numero.replace(/\./g, '');
        }

        // Si tiene decimales, se los quitamos al numero
        if (numero.indexOf(",") >= 0)
            nuevoNumero = nuevoNumero.substring(0, nuevoNumero.indexOf(","));

        // Ponemos un punto cada 3 caracteres
        for (var j, i = nuevoNumero.length - 1, j = 0; i >= 0; i--, j++)
            resultado = nuevoNumero.charAt(i) + ((j > 0) && (j % 3 == 0) ? "." : "") + resultado;

        // Si tiene decimales, se lo añadimos al numero una vez forateado con 
        // los separadores de miles
        if (numero.indexOf(",") >= 0)
            resultado += numero.substring(numero.indexOf(","));

        if (numero[0] == "-") {
            // Devolvemos el valor añadiendo al inicio el signo negativo
            return "-" + resultado;
        } else {
            return resultado;
        }
    }
    else {
        return 0
    }
})

/// RECORTAR DECIMALES 
Vue.filter('Decimales', function (numero) {

    if (isNaN(numero)) {
        return 'NaN';
    }
    else {
        if (numero % 1 == 0) {
            return numero;
        }
        else {
            return numero.toFixed(3);

            /* numero.split(".");

            let entero = arr[0];
            let decimal = arr[1]; */
        }
    }
});

//// DIAS TRANSCURRIDOS A HOY
Vue.filter('DiasTranscurridos', function (fecha_inicial) {

    var fecha_hoy = hoy_php;
    if (fecha_inicial == null) { fecha_inicial = hoy_php }

    fecha_inicial = new Date(fecha_inicial).getTime();
    fecha_hoy = new Date(fecha_hoy).getTime();
    var diff = fecha_hoy - fecha_inicial;

    /// debe devolver mensajes completos
    diff = diff / (1000 * 60 * 60 * 24)

    return diff + ' días';
})

/// OBSERVACIONES RECORTAR LARGO
Vue.filter('Recortar', function(texto){
    if(texto != null){
        return texto.substr(0,30) + '...';
    }
    else{
        return 'Sin observaciones'
    }
})




/// ELEMENTOS COMUNES PARA LA WEB
new Vue({
    el: '#app',

    created: function () {
        /// CARGO FUNCIONES SEGUN EL SECCTOR QUE ESTE VISUALIZANDO - uso un if, pero tal vez un swich sea mejor en este caso


        if (pathname == carpeta + 'usuarios') {
            this.getListadoUsuarios(1);
            this.getListadoRoles();
            this.getListadoPuesto();
            this.getListadoEmpresas();
        }

        if (pathname == carpeta + 'usuarios/resumenreportes') {
            this.resumenReportes();
            this.getSuperiores();
            this.getListadoEmpresas();
            this.getListadoPuesto();
        }

        if (pathname == carpeta + 'curriculum/todos') {
            this.getListadoCurriculums();
            //this.getListadoRoles();
            this.getListadoPuesto();
            //this.getListadoEmpresas();
        }

        if (pathname == carpeta + 'curriculum') {
            this.getListadoCurriculumsTesteados();
            //this.getListadoRoles();
            this.getListadoPuesto();
            //this.getListadoEmpresas();
        }

        if (pathname == carpeta + 'stock') {
            this.getListadoCategorias();
            this.getListadoStock(2, 0);
            this.getListadoVentas(0, 0, 0, 1, 0);

        }

        if (pathname == carpeta + 'stock/panol') {
            this.getListadoCategorias();
            this.getListadoStock(1, 0);
            this.getListadoVentas(0, 0, 0, 1, 0);

        }

        if (pathname == carpeta + 'stock/pruductosdereventa') {
            this.getListadoStock(3, 0);
            this.getListadoVentas(0, 0, 0, 1, 0);
            this.getListadoCategorias();
        }

        if (pathname == carpeta + 'clientes') {
            this.getListadoClientes();
        }

        if (pathname == carpeta + 'proveedores') {
            this.getListadoProveedores();
            this.getListadoRubro();
        }

        if (pathname == carpeta + 'fabricacion') {
            this.valorDolar();
            this.consultaDolarBCRA();
            this.getListadoCategoriasProductos();
            this.getListadoProductos(0, 0);
            this.getListadoEmpresas();

        }

        if (pathname == carpeta + 'ventas') {
            this.getListadoVentas(0, 0, 0, 1, 0);
            this.getListadoUsuarios(1);
            this.getListadoProductos(0, 0);
            this.getListadoClientes();
            this.getListadoEmpresas();
            this.getListadoPlanificaciones();
        }

        if (pathname == carpeta + 'cobranzas') {
            this.obtener_listado_ventas_cobranzas(0, 0, 0, 1, 0);
            this.getListadoUsuarios(1);
            this.getListadoProductos(0, 0);
            this.getListadoClientes();
            this.getListadoEmpresas();
            this.getListadoPlanificaciones();
        }

        if (pathname == carpeta + 'cobranzas/cobros') {

            this.obtener_listado_cobros();
        }

        if (pathname == carpeta + 'compras') {
            this.getListadoCompras(null, null, null, null);
            this.getListadoRubro();
            this.getListadoProveedores_select();
        }
    },

    data:
    {
        Rol_usuario: '',

        buscar: '',

        texto_boton: "Cargar",
        filtro_puesto: "0",
        filtro_empresa: "0",
        filtro_sexo: '0',
        filtro_edad: '0',
        
        Filtro_fecha_inicial: null,
        Filtro_fecha_final: null,

        boton_habilitado : 1,

        //Usuarios
        listaUsuarios: [],
        usuario: {
            'Id': '',
            'Nombre': '',
            'DNI': '',
            'Pass': '',
            'Rol_id': '',
            'Empresa_id': '',
            'Puesto_Id': '',
            'Imagen': '',
            'Telefono': '',
            'Fecha_nacimiento': '',
            'Domicilio': '',
            'Nacionalidad': '',
            'Genero': '',
            'Email': '',
            'Obra_social': '',
            'Numero_obra_social': '',
            'Hijos': '',
            'Estado_civil': '',
            'Datos_persona_contacto': '',
            'Datos_bancarios': '',
            'Periodo_liquidacion_sueldo': '',
            'Horario_laboral': '',
            'Superior_inmediato': '',
            'Telefono': '',
            'Observaciones': '',
            'Presencia': '1',
            'Fecha_alta': '',
            'Fecha_baja': '',
            'Activo': 1
        },
        listaReportes: [],

        usuarioFoto: { 'Id': '', 'Nombre': '', 'Imagen': '' },
        Archivo: '',
        preloader: '0',
        Filtro_periodo: 0,
        pag: 0,
        NUM_RESULTS: 20,
        infoModal: '',

        listaRoles: [],

        listaEmpresas: [],
        empresaDatos: { 'Id': '', 'Nombre_empresa': '', 'Descripcion': '' },

        listaPuestos: [],
        puesto: { 'Id': '', 'Nombre_puesto': '', 'Descripcion': '' },

        listaCurriculums: [],

        //Stock
        listaCategorias: [],
        categoriaDatos: { 'Id': '', 'Nombre_categoria': '', 'Descripcion': '' },
        filtro_categoria: '0',

        listaStock: [],
        stockDato: { 'Id': '', 'Nombre_item': '', 'Categoria_id': '', 'Descripcion': '', 'Cant_actual': '0', 'Cant_ideal': '', 'Ult_modificacion_id': '' },
        stock_tipo: 0,

        stockFoto: { 'Id': '', 'Nombre': '', 'Imagen': '' },

        cantMovimientoStock: [],
        descripcionMovimiento: [],

        egresoDato: {},

        // Clientes
        listaClientes: [],
        clienteDatos: { 'Id': '', 'Nombre_cliente': '', 'Producto_servicio': '', 'Direccion': '', 'Localidad': '', 'Provincia': '', 'Pais': '', 'Telefono': '', 'Email': '', 'Web': '', 'Nombre_persona_contacto': '', 'Datos_persona_contacto': '', 'Mas_datos_cliente': '' },
        clienteFoto: { 'Id': '', 'Nombre_cliente': '', 'Imagen': '' },

        // Proveedores
        listaProveedores: [],
        proveedorDatos: { 'Id': '', 'Nombre_proveedor': '', 'Producto_servicio': '', 'Direccion': '', 'Localidad': '', 'Provincia': '', 'Pais': '', 'Telefono': '', 'Email': '', 'Web': '', 'Nombre_persona_contacto': '', 'Datos_persona_contacto': '', 'Mas_datos_proveedor': '' },
        proveedorFoto: { 'Id': '', 'Nombre_proveedor': '', 'Imagen': '' },
        listaRubros: [],
        rubroDato: {},
        filtro_rubro: 0,
        listaProveedores_select: [],

        // Productos
        listaProductos: [],
        productoDatos: {},

        productoFoto: { 'Id': '', 'Nombre': '', 'Imagen': '' },
        valorDolarHoy: '',
        valorDolarBCRA: '',

        // Ventas
        listaVentas: [],
        listaPlanificaciones: [],
        filtro_vendedor: '0',
        filtro_cliente: '0',
        filtro_estado: '1',
        filtro_planificacion: '0',
        ventaDatos: {},
        planificacionDatos: {},


        // Cobranzas
        listaCobranzas: [],
        cobroDatos: {},
        listaCobros: [],
        filtroFechaInicial: null,
        filtroFechaFinal: null,

        // Compras
        listaCompras: [],
        compraDatos: { 'Id': '', 'Proveedor_id': '', 'Fecha_compra': '', 'Factura_identificador': '', 'Valor': '', 'Descripcion': '' },
        compraFoto: { 'Id': '', 'Factura_identificador': '', 'Imagen': '' },


    },

    methods:
    {

        //// DOLAR COTIZACIÓN
        consultaDolarBCRA: function () {
            /* var url = 'http://api.estadisticasbcra.com/usd'; // url donde voy a mandar los datos

            axios.get(url,  { 
                headers: {
                    Authorization: 'BEARER eyJhbGciOiJIUzUxMiIsInR5cCI6IkpXVCJ9.eyJleHAiOjE1ODUxMTIwNjEsInR5cGUiOiJleHRlcm5hbCIsInVzZXIiOiJocGFyYWRlbGFAbGl2ZS5jb20uYXIifQ.oTHobNAOb3FUN5XekYbaqNK6JC4lI2jULGVdkSpf5wYo2whcWVDv8z98s_EZTQfVnsKxEJym94v7VOscgItoBQ'
                }
                }).then(response => {
                this.valorDolarBCRA = response.data;
                console.log("valor dolar: ")
                console.log(response.data)

            }).catch(error => {
                console.log(error.response); //alert("mal");
                console.log(error.response)

            }); */
        },

        //// DOLAR COTIZACIÓN BCRA
        valorDolar: function () {
            var url = 'http://ws.geeklab.com.ar/dolar/get-dolar-json.php'; // url donde voy a mandar los datos

            axios.post(url).then(response => {
                this.valorDolarHoy = response.data;

            }).catch(error => {
                console.log(error.response); //alert("mal");
                console.log(error.response.data)

            });
        },

        //// DOLAR CAMBIO DOLAR A PESO
        cambioDolar_peso: function (monto) {

            if (monto == null) { return null }
            else {
                var calculo = parseInt(monto) * this.valorDolarHoy.libre
                return calculo.toFixed(2)
            }
        },


        //// MOSTRAR LISTADO DE Usuarios  
        getListadoUsuarios: function (estado) {
            var url = base_url + 'usuarios/obtener_Usuarios/?estado=' + estado + '&empresa=' + this.filtro_empresa + '&puesto=' + this.filtro_puesto; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaUsuarios = response.data
            }).catch(error => {
                console.log(error.response.data); //alert("mal");
                this.boton_habilitado = 1;
            });
        },

        //// LIMPIAR EL FORMULARIO DE CREAR Usuarios
        limpiarFormularioUsuarios() {
            this.usuario = {}
            this.texto_boton = "Cargar";
        },

        //// Carga el formulario Usuarios para editar
        editarFormulariousuario(usuario) {
            //this.usuario = {};
            this.usuario = usuario;
            this.texto_boton = "Actualizar";
        },

        //// CREAR O EDITAR Usuarios  
        crearUsuarios: function () {
            var url = base_url + 'usuarios/cargar_Usuarios'; // url donde voy a mandar los datos
            this.boton_habilitado = 0;
            axios.post(url, {
                token: token,
                usuarioData: this.usuario
            }).then(response => {

                toastr.success('Proceso realizado correctamente', 'Usuarios')

                this.usuario.Id = response.data.Id;
                this.texto_boton = "Actualizar"

                this.getListadoUsuarios(1);
                this.boton_habilitado = 1;

            }).catch(error => {
                console.log(error.response); //alert("mal");
                this.boton_habilitado = 1;
                toastr.success('Proceso realizado correctamente', 'Usuarios')
            });
        },

        //// Dasactivar usuario  
        desactivarUsuario: function (Id) {
            var url = base_url + 'usuarios/desactivar_usuario'; // url donde voy a mandar los datos
            this.boton_habilitado = 0;
            var opcion = confirm("¿Esta seguro de eliminar a este usuario?");
            if (opcion == true) {
                axios.post(url, {
                    token: token,
                    Id: Id
                }).then(response => {

                    toastr.success('Proceso realizado correctamente', 'Usuarios')

                    this.getListadoUsuarios(1);
                    this.boton_habilitado = 1;
                }).catch(error => {
                    console.log(error.response); //alert("mal");
                    this.boton_habilitado = 1;

                });
            }
        },

        //// ACTIVAR/DESACTIVAR USUARIOS    
        activarUsuario: function (usuario) {
            var url = base_url + 'usuarios/cargar_Usuarios'; // url donde voy a mandar los datos
            this.boton_habilitado = 0;
            if (usuario.Activo == 1) {
                usuario.Activo = 0;
            }
            else {
                usuario.Activo = 1;
            }

            axios.post(url, {
                token: token,
                usuarioData: usuario
            }).then(response => {

                toastr.success('Proceso realizado con éxito', 'Items Carta')

                //this.usuario.Id = response.data.Id;
                //this.texto_boton = "Actualizar"
                this.boton_habilitado = 1;
                this.getListadoUsuarios(1);

            }).catch(error => {
                //toastr.error('Error al cargar el item'); // hacer funcionar esto, esta bueno

                console.log(error.response); //alert("mal");
                this.boton_habilitado = 1;
            });
        },

        upload(Id) {
            //this.texto_boton = "Actualizar"
            var url = base_url + 'usuarios/subirFotoUsuario/?Id=' + Id; // url donde voy a mandar los datos
            this.preloader = 1;
            //const formData = event.target.files[0];
            const formData = new FormData();
            formData.append("Archivo", this.Archivo);

            formData.append('_method', 'PUT');

            //Enviamos la petición
            axios.post(url, formData)
                .then(response => {

                    ////DEBO HACER FUNCIONAR BIEN ESTO PARA QUE SE ACTUALICE LA FOTO QUE CARGO EN EL MOMENTO, SI NO PARECE Q NO SE CARGARA NADA
                    this.usuarioFoto.Imagen = response.data.Imagen;
                    this.getListadoUsuarios(1);
                    toastr.success('Proceso realizado correctamente', 'Usuarios')

                    this.preloader = 0;
                }).catch(error => {
                    console.log(error.response); //alert("mal");
                    console.log(error)
                    this.preloader = 0;
                });
        },

        //// REPORTES RESUMEN | MOSTRAR LISTADO
        resumenReportes: function () {
            var url = base_url + 'usuarios/obtener_resumenReportes/?estado=1&empresa=' + this.filtro_empresa + '&puesto=' + this.filtro_puesto; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {

                this.listaReportes = response.data

                //console.log(this.listaReportes)

            }).catch(error => {

                console.log(error.response.data)
            });
        },

        //// USUARIOS | OBTENER USUARIOS LIDERES
        getSuperiores: function () {
            var url = base_url + 'usuarios/obtener_lideres';  //// averiguar como tomar el Id que viene por URL aca

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaSuperiores = response.data

            });
        },

        //// REPORTES RESUMEN | COLOREAR ITEMS BAJOS DE STOCK
        classColorReporte: function (numero) {
            var numero = Math.round(numero);

            if (numero == 5) { return 'text-success' }
            else if (numero == 4) { return 'text-success' }
            else if (numero == 3) { return 'text-secondary' }
            else if (numero == 2) { return 'text-warning' }
            else if (numero == 1) { return 'text-danger' }
            else { return '' }

        },

        //// Carga el formulario Items para editar FOTO
        editarFormulariousuarioFoto(item) {
            this.usuarioFoto = item;
            this.texto_boton = "Actualizar";
        },

        //// MOSTRAR LISTADO DE ROLES  
        getListadoRoles: function () {
            var url = base_url + 'usuarios/obtener_roles'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaRoles = response.data
            });
        },

        //// FORMATO FECHA
        formatoFecha: function (fecha) {
            if (fecha == null) {
                return 'No definida'
            }
            else {
                return fecha.replace(/^(\d{4})-(\d{2})-(\d{2})$/g, '$3/$2/$1');
            }

        },

        //// FORMATO HORA
        formatoHora: function (hora) {
            separador = ":",
                arrayHora = hora.split(separador);

            return arrayHora[0] + ':' + arrayHora[1] + 'hs'

        },
        //// CALCULANDO DIFERENCIA ENTRE DOS HORAS
        diferenciaTiempo: function (horaEnviada)  //// no funciona 
        {
            var f = new Date();
            var hora_actual_hora = f.getHours();
            var hora_actual_minuto = f.getMinutes();

            var separador = ":",
                horaEnviada = horaEnviada.split(separador);

            var horaEnviada_en_minutos = (parseInt(horaEnviada[0]) * 60) + parseInt(horaEnviada[1]);

            var hora_actual_en_minutos = (hora_actual_hora * 60) + hora_actual_minuto;

            var diferencia = hora_actual_en_minutos - horaEnviada_en_minutos;

            diferencia = diferencia / 60;
            diferencia = diferencia.toString();

            var arr = diferencia.split(".");

            var horas = arr[0];

            var minutos = parseInt(arr[1]) * 100 / 60;

            minutos = minutos.toString();
            minutos = minutos.substr(0, 2)

            return horas + 'h ' + minutos + 'm'


        },

        //// SUMAR CUENTA   
        sumarCuenta: function (items) {

            /// SUMAR LOS ENTREGADOS
            var Total = 0;

            for (var i = 0; i < items.length; i++) {
                var item = 0;

                if (isFinite(items[i].Valor_cuenta)) {
                    item = parseInt(items[i].Valor_cuenta);
                }

                Total = Total + item;
            }
            return Total
        },

        //// SCRITPS PARA SUBIR FOTOS USUARIOS
        archivoSeleccionado(event) {
            this.Archivo = event.target.files[0]
            //this.texto_boton = "Actualizar"
        },

        //// FORMATOS   | FORMATO Fecha Hora
        formatoFecha_hora: function (fecha) {
            //separador = ":",
            fecha = fecha.split(' ');

            //var fecha_dia = fecha[0].replace(/^(\d{4})-(\d{2})-(\d{2})$/g, '$3/$2/$1');
            var fecha_dia = fecha[0].replace(/^(\d{4})-(\d{2})-(\d{2})$/g, '$3/$2/$1');

            var fecha_hora = fecha[1].split(':');
            fecha_hora = fecha_hora[0] + ':' + fecha_hora[1];

            return fecha_dia + ' ' + fecha_hora + 'hs '

        },

        //// ELIMINAR USUARIO    
        eliminar: function (Id, tbl) {
            var url = base_url + 'Funcionescomunes/eliminar'; // url donde voy a mandar los datos

            //SOLICITANDO CONFIRMACIÓN PARA ELIMINAR
            var opcion = confirm("¿Esta seguro de eliminar a este usuario?");
            if (opcion == true) {

                axios.post(url, {
                    token: token,
                    Id: Id, tabla: tbl
                }).then(response => {

                    this.getListadoUsuarios(1);
                    toastr.success('Item eliminado correctamente', 'Comandas')

                }).catch(error => {
                    console.log(error.response); //alert("mal");
                    console.log(error)
                });
            }
        },

        //// MOSTRAR LISTADO DE EMPRESAS  
        getListadoEmpresas: function () {
            var url = base_url + 'usuarios/obtener_empresas'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaEmpresas = response.data
            });
        },

        //// LIMPIAR EL FORMULARIO DE CREAR EMPRESAS
        limpiarFormularioEmpresa() {
            this.empresaDatos = {}
            this.texto_boton = "Cargar";
        },

        //// Carga el formulario EMPRESAS para editar
        editarFormularioEmpresa(Empresa) {
            //this.usuario = {};
            this.empresaDatos = Empresa;
            this.texto_boton = "Actualizar";
        },

        //// CREAR O EDITAR EMPRESAS
        crearEmpresa: function () {
            var url = base_url + 'usuarios/cargar_empresa'; // url donde voy a mandar los datos
            this.boton_habilitado = 0;

            axios.post(url, {
                token: token,
                Data: this.empresaDatos
            }).then(response => {

                toastr.success('Proceso realizado correctamente', 'Usuarios')

                this.empresaDatos.Id = response.data.Id;
                this.texto_boton = "Actualizar"

                this.getListadoEmpresas();
                this.boton_habilitado = 1;

            }).catch(error => {
                console.log(error.response); //alert("mal");
                this.boton_habilitado = 1;

            });
        },

        //// MOSTRAR LISTADO DE puestos  
        getListadoPuesto: function () {
            var url = base_url + 'usuarios/obtener_puestos'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaPuestos = response.data
            });
        },

        //// LIMPIAR EL FORMULARIO DE CREAR puestos
        limpiarFormularioPuesto() {
            this.puesto = {}
            this.texto_boton = "Cargar";
        },

        //// Carga el formulario puesto para editar
        editarFormularioPuesto(Puesto) {
            //this.usuario = {};
            this.puesto = Puesto;
            this.texto_boton = "Actualizar";
        },

        //// CREAR O EDITAR puesto
        crearPuesto: function () {
            var url = base_url + 'usuarios/cargar_puesto'; // url donde voy a mandar los datos
            this.boton_habilitado = 0;

            axios.post(url, {
                token: token,
                Data: this.puesto
            }).then(response => {

                toastr.success('Proceso realizado correctamente', 'Usuarios')

                this.puesto.Id = response.data.Id;
                this.texto_boton = "Actualizar"

                this.boton_habilitado = 1;
                this.getListadoPuesto();

            }).catch(error => {
                console.log(error.response); //alert("mal");
                this.boton_habilitado = 1;

            });
        },

        //// CURRICULUM | MOSTRAR LISTADO
        getListadoCurriculums: function () {
            var url = base_url + 'curriculum/obtener_curriculums/?edad=' + this.filtro_edad + '&sexo=' + this.filtro_sexo; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaCurriculums = response.data
            });
        },

        //// CURRICULUM | MOSTRAR TESTEADOS
        getListadoCurriculumsTesteados: function () {
            var url = base_url + 'curriculum/getListadoCurriculumsTesteados/?edad=' + this.filtro_edad + '&sexo=' + this.filtro_sexo + '&puesto=' + this.filtro_puesto; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaCurriculums = response.data

                console.log(this.listaCurriculums)
            });
        },

        //// CURRICULUM | Dasactivar testeados
        desactivarTesteado: function (Id) {
            var url = base_url + 'curriculum/desactivar'; // url donde voy a mandar los datos

            var opcion = confirm("¿Esta seguro de eliminar a este curriculum?");
            if (opcion == true) {
                axios.post(url, {
                    token: token,
                    Id: Id
                }).then(response => {

                    this.getListadoCurriculumsTesteados(); // las funciones son iguales solo cambia esto
                    toastr.success('Proceso realizado correctamente', 'Usuarios')

                }).catch(error => {
                    console.log(error.response); //alert("mal");
                    console.log(error)
                });
            }
        },

        //// CURRICULUM | Dasactivar en la lista de todos
        desactivar: function (Id) {
            var url = base_url + 'curriculum/desactivar'; // url donde voy a mandar los datos

            var opcion = confirm("¿Esta seguro de eliminar a este curriculum?");
            if (opcion == true) {
                axios.post(url, {
                    token: token,
                    Id: Id
                }).then(response => {

                    this.getListadoCurriculums(); // las funciones son iguales solo cambia esto
                    toastr.success('Proceso realizado correctamente', 'Usuarios')

                }).catch(error => {
                    console.log(error.response); //alert("mal");
                    console.log(error)

                });
            }
        },


        //// STOCK |  MOSTRAR LISTADO DE CATEGORIAS
        getListadoCategorias: function () {

            var categoria_tipo = 0;
            if (pathname == carpeta + 'stock/pruductosdereventa') {
                categoria_tipo = 3;
            }

            var url = base_url + 'stock/obtener_categorias?categoria_tipo=' + categoria_tipo; // url donde voy a mandar los datos



            axios.post(url, {
                token: token
            }).then(response => {
                this.listaCategorias = response.data
            });
        },

        //// STOCK |  LIMPIAR EL FORMULARIO DE CREAR CATEGORIAS
        limpiarFormularioCategoria() {
            this.categoriaDatos = {}
            this.texto_boton = "Cargar";
        },

        //// STOCK |  Carga el formulario CATEGORIAS para editar
        editarFormularioCategoria(categoria) {
            //this.usuario = {};
            this.categoriaDatos = categoria;
            this.texto_boton = "Actualizar";
        },

        //// STOCK | CREAR O EDITAR CATEGORIA
        crearCategoria: function () {
            var url = base_url + 'stock/cargar_categoria'; // url donde voy a mandar los datos
            this.boton_habilitado = 0;

            if (pathname == carpeta + 'stock/pruductosdereventa') {
                this.categoriaDatos.Tipo = 3;
            }

            axios.post(url, {
                token: token,
                Data: this.categoriaDatos
            }).then(response => {

                toastr.success('Proceso realizado correctamente', 'stock')

                this.categoriaDatos.Id = response.data.Id;
                //this.texto_boton = "Actualizar"
                this.categoriaDatos = {}

                this.getListadoCategorias();
                this.boton_habilitado = 1;

            }).catch(error => {
                console.log(error.response); //alert("mal");
                this.boton_habilitado = 1;

            });
        },

        //// STOCK |  MOSTRAR LISTADO DE CATEGORIAS
        getListadoStock: function (tipo, categoria) {

            this.stock_tipo = tipo;
            console.log(tipo);

            var url = base_url + 'stock/obtener_listado_de_stock?tipo=' + tipo + '&categoria=' + categoria; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaStock = response.data

                console.log(this.listaStock);
            });
        },

        //// STOCK | CREAR O EDITAR ITEM
        crearStock: function () {
            var url = base_url + 'stock/cargar_stock_item'; // url donde voy a mandar los datos
            this.boton_habilitado = 0;

            if (!this.stockDato.Categoria_id) { this.stockDato.Categoria_id = 0; }
            if (this.stockDato.Tipo) { this.stock_tipo = this.stockDato.Tipo; }

            axios.post(url, {
                token: token,
                Data: this.stockDato,
                Tipo: this.stock_tipo
            }).then(response => {

                toastr.success('Proceso realizado correctamente', 'stock')

                this.stockDato.Id = response.data.Id;
                //this.texto_boton = "Actualizar"
                this.getListadoStock(this.stock_tipo, 0);
                this.stockDato = {}
                this.boton_habilitado = 1;


            }).catch(error => {
                console.log(error.response); //alert("mal");
                this.boton_habilitado = 1;

            });
        },

        //// STOCK |  LIMPIAR EL FORMULARIO DE CREAR
        limpiarFormularioStock() {
            this.stockDato = {}
            this.texto_boton = "Cargar";
        },

        //// STOCK |  Carga el formulario para editar
        editarFormulariostock(item) {
            //this.usuario = {};
            this.stockDato = item;
            this.texto_boton = "Actualizar";
        },

        ///// STOCK |   CARGAR IMAGEN DE STOCK
        upload_foto_stock(Id) {
            //this.texto_boton = "Actualizar"
            var url = base_url + 'stock/subirFotoStock/?Id=' + Id; // url donde voy a mandar los datos
            this.preloader = 1;
            //const formData = event.target.files[0];
            const formData = new FormData();
            formData.append("Archivo", this.Archivo);

            formData.append('_method', 'PUT');

            //Enviamos la petición
            axios.post(url, formData)
                .then(response => {

                    ////DEBO HACER FUNCIONAR BIEN ESTO PARA QUE SE ACTUALICE LA FOTO QUE CARGO EN EL MOMENTO, SI NO PARECE Q NO SE CARGARA NADA
                    this.stockFoto.Imagen = response.data.Imagen;
                    this.getListadoStock(0);
                    toastr.success('Proceso realizado correctamente', 'Usuarios')

                    this.preloader = 0;
                }).catch(error => {
                    console.log(error.response); //alert("mal");
                    console.log(error)
                    this.preloader = 0;
                });
        },

        //// STOCK |     Carga el formulario Items para editar FOTO
        editarFormularioItemFoto(item) {
            this.stockFoto = item;
            this.texto_boton = "Actualizar";
        },

        //// SUMAR CUENTA   
        sumarStock: function (pedido, teorico) {

            var Total = parseInt(pedido) + parseInt(teorico);

            return Total;
        },

        /* //// STOCK | CREAR O EDITAR -- solo servirá para actualizar egresos, los ingresos se harán desde compras nada más
        movimientoStock: function (id, cantidad, descripcion) {
            var url = base_url + 'stock/cargar_movimiento_stock'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Id: id,
                Modulo: 'Stock',
                Cantidad: cantidad,
                Descripcion: descripcion,
                Proceso_id: null,
                Tipo_movimiento: null,
                
            }).then(response => {

                toastr.success('Proceso realizado correctamente', 'stock')

                this.getListadoStock(0);
                this.cantMovimientoStock = [];
                this.descripcionMovimiento = []

            }).catch(error => {
                console.log(error.response); //alert("mal");
                console.log(error.response.data)

            });
        }, */

        editarStockProducto: function (item) {
            this.egresoDato = item;
        },

        //// STOCK | CREAR O EDITAR -- solo servirá para actualizar egresos, los ingresos se harán desde compras nada más
        movimientoStock_v2: function () {
            var url = base_url + 'stock/cargar_movimiento_stock'; // url donde voy a mandar los datos
            
            this.boton_habilitado = 0;
            axios.post(url, {

                token: token,
                Id: this.egresoDato.Id,
                Modulo: 'Stock',
                Cantidad: this.egresoDato.Cantidad,
                Descripcion: this.egresoDato.Descripcion_egreso,
                Proceso_id: this.egresoDato.Venta_id,
                Precio_venta_producto: 0,
                Tipo_movimiento: 2,

            }).then(response => {

                toastr.success('Proceso realizado correctamente', 'Stock')

                this.getListadoStock(0, this.filtro_categoria);
                this.boton_habilitado = 1;

            }).catch(error => {
                this.boton_habilitado = 1;
                console.log(error.response.data)

            });
        },

        /// STOCK  | COLOREAR ITEMS BAJOS DE STOCK
        classAlertaStock: function (actual, ideal) {
            var Valor_actual = parseInt(actual);
            var Valor_ideal = parseInt(ideal);

            if (Valor_actual < Valor_ideal) {
                return 'text-danger'
            }
            else {
                return ''
            }
        },

        //// STOCK  | Dasactivar un item
        desactivarStock: function (Id) {
            var url = base_url + 'stock/desactivar_producto'; // url donde voy a mandar los datos

            var opcion = confirm("¿Esta seguro de eliminar a este producto?");
            if (opcion == true) {
                axios.post(url, {
                    token: token,
                    Id: Id
                }).then(response => {

                    toastr.success('Proceso realizado correctamente', 'Proveedores')

                    this.getListadoStock(this.stock_tipo, 0);

                }).catch(error => {
                    console.log(error.response); //alert("mal");
                    console.log(error)

                });
            }
        },


        //// CLIENTES  | MOSTRAR LISTADO
        getListadoClientes: function (estado) {
            var url = base_url + 'clientes/obtener_clientes'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaClientes = response.data
            });
        },

        //// CLIENTES  | LIMPIAR EL FORMULARIO DE CREAR
        limpiarFormularioClientes() {
            this.clienteDatos = {}
            this.texto_boton = "Cargar";
        },

        //// CLIENTES  | Carga el formulario  para editar
        editarFormularioCliente(clienteDatos) {
            //this.usuario = {};
            this.clienteDatos = clienteDatos;
            this.texto_boton = "Actualizar";
        },

        //// CLIENTES  | CREAR O EDITAR 
        crearCliente: function () {
            var url = base_url + 'clientes/cargar_cliente'; // url donde voy a mandar los datos
            this.boton_habilitado = 0;

            axios.post(url, {
                token: token,
                Datos: this.clienteDatos
            }).then(response => {

                toastr.success('Proceso realizado correctamente', 'Usuarios')

                this.clienteDatos.Id = response.data.Id;
                this.texto_boton = "Actualizar"

                this.getListadoClientes();
                this.boton_habilitado = 1;

            }).catch(error => {
                console.log(error.response); //alert("mal");
                this.boton_habilitado = 1;
                toastr.success('Proceso realizado correctamente', 'Usuarios')
            });
        },

        //// CLIENTES  | Dasactivar
        desactivarCliente: function (Id) {
            var url = base_url + 'clientes/desactivar_cliente'; // url donde voy a mandar los datos

            var opcion = confirm("¿Esta seguro de eliminar a este cliente?");
            if (opcion == true) {
                axios.post(url, {
                    token: token,
                    Id: Id
                }).then(response => {

                    toastr.success('Proceso realizado correctamente', 'Clientes')

                    this.getListadoClientes();

                }).catch(error => {
                    console.log(error.response); //alert("mal");
                    console.log(error)

                });
            }
        },

        //// CLIENTES  | Subir imagen
        uploadCliente(Id) {
            //this.texto_boton = "Actualizar"
            var url = base_url + 'clientes/subirFoto/?Id=' + Id; // url donde voy a mandar los datos
            this.preloader = 1;
            //const formData = event.target.files[0];
            const formData = new FormData();
            formData.append("Archivo", this.Archivo);

            formData.append('_method', 'PUT');

            //Enviamos la petición
            axios.post(url, formData)
                .then(response => {

                    ////DEBO HACER FUNCIONAR BIEN ESTO PARA QUE SE ACTUALICE LA FOTO QUE CARGO EN EL MOMENTO, SI NO PARECE Q NO SE CARGARA NADA
                    this.clienteFoto.Imagen = response.data.Imagen;
                    this.getListadoClientes();
                    toastr.success('Proceso realizado correctamente', 'Usuarios')

                    this.preloader = 0;
                }).catch(error => {
                    console.log(error.response); //alert("mal");
                    console.log(error)
                    this.preloader = 0;
                });
        },

        //// CLIENTES  |  Carga el formulario Items para editar FOTO
        editarFormularioClienteFoto(item) {
            this.clienteFoto = item;
            this.texto_boton = "Actualizar";
        },


        //// PROVEEDORES  | MOSTRAR LISTADO
        getListadoProveedores: function (Rubro_id) {
            var url = base_url + 'proveedores/obtener_proveedores?Rubro_id=' + Rubro_id; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaProveedores = response.data

                //console.log(this.listaProveedores)
            }).catch(error => {
                console.log(error.response.data); //alert("mal");
                
            });
        },
        //// PROVEEDORES  | LIMPIAR EL FORMULARIO DE CREAR
        limpiarFormularioProveedor() {
            this.proveedorDatos = {}
            this.texto_boton = "Cargar";
        },

        //// PROVEEDORES  | Carga el formulario  para editar
        editarFormularioProveedor(proveedorDatos) {
            //this.usuario = {};
            this.proveedorDatos = proveedorDatos;
            this.texto_boton = "Actualizar";
        },

        //// PROVEEDORES  | CREAR O EDITAR 
        crearProveedor: function () {
            var url = base_url + 'proveedores/cargar_proveedor'; // url donde voy a mandar los datos

            this.boton_habilitado = 0;

            axios.post(url, {
                token: token,
                Datos: this.proveedorDatos
            }).then(response => {

                toastr.success('Proceso realizado correctamente', 'Proveedores')

                this.proveedorDatos.Id = response.data.Id;
                this.texto_boton = "Actualizar"

                this.getListadoProveedores();
                this.boton_habilitado = 1;

            }).catch(error => {
                console.log(error.response); //alert("mal");
                this.boton_habilitado = 1;
                toastr.success('Proceso realizado correctamente', 'Proveedores')
            });
        },

        //// PROVEEDORES  | Dasactivar
        desactivarProveedor: function (Id) {
            var url = base_url + 'proveedores/desactivar_proveedor'; // url donde voy a mandar los datos

            var opcion = confirm("¿Esta seguro de eliminar a este proveedor?");
            if (opcion == true) {
                axios.post(url, {
                    token: token,
                    Id: Id
                }).then(response => {

                    toastr.success('Proceso realizado correctamente', 'Proveedores')

                    this.getListadoProveedores();

                }).catch(error => {
                    console.log(error.response); //alert("mal");
                    console.log(error)

                });
            }
        },

        //// PROVEEDORES  | Subir imagen
        uploadProveedor(Id) {
            //this.texto_boton = "Actualizar"
            var url = base_url + 'proveedores/subirFoto/?Id=' + Id; // url donde voy a mandar los datos
            this.preloader = 1;
            //const formData = event.target.files[0];
            const formData = new FormData();
            formData.append("Archivo", this.Archivo);

            formData.append('_method', 'PUT');

            //Enviamos la petición
            axios.post(url, formData)
                .then(response => {

                    ////DEBO HACER FUNCIONAR BIEN ESTO PARA QUE SE ACTUALICE LA FOTO QUE CARGO EN EL MOMENTO, SI NO PARECE Q NO SE CARGARA NADA
                    this.proveedorFoto.Imagen = response.data.Imagen;
                    this.getListadoProveedores();
                    toastr.success('Proceso realizado correctamente', 'Proveedores')

                    this.preloader = 0;
                }).catch(error => {
                    console.log(error.response); //alert("mal");
                    console.log(error)
                    this.preloader = 0;
                });
        },

        //// PROVEEDORES  | MOSTRAR LISTADO
        getListadoProveedores_select: function (Rubro_id) {
            var url = base_url + 'proveedores/obtener_proveedores_select'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaProveedores_select = response.data
            });
        },

        //// PROVEEDORES  | Carga el formulario Items para editar FOTO
        editarFormularioProveedorFoto(item) {
            this.proveedorFoto = item;
            this.texto_boton = "Actualizar";
        },

        //// PROVEEDORES RUBROS  | MOSTRAR LISTADO
        getListadoRubro: function (estado) {
            var url = base_url + 'proveedores/obtener_rubros'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaRubros = response.data
            });
        },

        //// PROVEEDORES RUBROS  | LIMPIAR EL FORMULARIO DE CREAR
        limpiarFormularioRubros() {
            this.rubroDato = {}
            this.texto_boton = "Cargar";
        },

        //// PROVEEDORES RUBROS  | Carga el formulario  para editar
        editarFormularioRubros(rubroDato) {
            //this.usuario = {};
            this.rubroDato = rubroDato;
            this.texto_boton = "Actualizar";
        },

        //// PROVEEDORES RUBROS  | CREAR O EDITAR 
        crearRubro: function () {
            var url = base_url + 'proveedores/cargar_rubro'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Datos: this.rubroDato
            }).then(response => {

                toastr.success('Proceso realizado correctamente', 'Proveedores')

                this.rubroDato.Id = response.data.Id;
                this.texto_boton = "Actualizar"

                this.getListadoRubro();

            }).catch(error => {
                alert("mal");
                console.log(error)
                toastr.success('Proceso realizado correctamente', 'Proveedores')
            });
        },


        //// FABRICACION |  MOSTRAR LISTADO DE CATEGORIAS
        getListadoCategoriasProductos: function () {
            var url = base_url + 'fabricacion/obtener_categorias'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaCategorias = response.data
            });
        },

        //// FABRICACION |  LIMPIAR EL FORMULARIO DE CREAR CATEGORIAS
        limpiarFormularioCategoriaProductos() {
            this.categoriaDatos = {}
            this.texto_boton = "Cargar";
        },

        //// FABRICACION |  Carga el formulario CATEGORIAS para editar
        editarFormularioCategoriaProductos(categoria) {
            //this.usuario = {};
            this.categoriaDatos = categoria;
            this.texto_boton = "Actualizar";
        },

        //// FABRICACION | CREAR O EDITAR CATEGORIA
        crearCategoriaProductos: function () {
            var url = base_url + 'fabricacion/cargar_categoria'; // url donde voy a mandar los datos
            this.boton_habilitado = 0;

            axios.post(url, {
                token: token,
                Data: this.categoriaDatos
            }).then(response => {

                toastr.success('Proceso realizado correctamente', 'stock')

                this.categoriaDatos.Id = response.data.Id;
                //this.texto_boton = "Actualizar"
                this.categoriaDatos = {}

                this.getListadoCategoriasProductos();
                this.boton_habilitado = 1;

            }).catch(error => {
                console.log(error.response); //alert("mal");
                this.boton_habilitado = 1;

            });
        },

        //// FABRICACION |  MOSTRAR LISTADO DE CATEGORIAS
        getListadoProductos: function (categoria, empresa) {
            var url = base_url + 'fabricacion/obtener_listado_de_productos?categoria=' + categoria + '&empresa=' + empresa; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaProductos = response.data
            });
        },

        //// FABRICACION | CREAR O EDITAR ITEM
        crearProducto: function () {
            var url = base_url + 'fabricacion/cargar_producto'; // url donde voy a mandar los datos

            this.boton_habilitado = 0;

            axios.post(url, {
                token: token,
                Data: this.productoDatos
            }).then(response => {

                toastr.success('Proceso realizado correctamente', 'Fabricación')

                this.productoDatos.Id = response.data.Id;
                this.texto_boton = "Actualizar"

                this.getListadoProductos(this.filtro_categoria, this.filtro_empresa);
                this.boton_habilitado = 1;

            }).catch(error => {
                console.log(error.response); //alert("mal");
                this.boton_habilitado = 1;

            });
        },

        //// FABRICACION |  LIMPIAR EL FORMULARIO DE CREAR
        limpiarFormularioProducto() {
            this.productoDatos = {}
            this.texto_boton = "Cargar";
        },

        //// FABRICACION |  Carga el formulario para editar
        editarFormularioProducto(item) {
            //this.usuario = {};
            this.productoDatos = item;
            this.texto_boton = "Actualizar";
        },

        ///// FABRICACION |   CARGAR IMAGEN DE PRODUCTO DE FABRICACION
        uploadFotoProducto(Id) {
            //this.texto_boton = "Actualizar"
            var url = base_url + 'fabricacion/subirFotoProducto/?Id=' + Id; // url donde voy a mandar los datos
            this.preloader = 1;
            //const formData = event.target.files[0];
            const formData = new FormData();
            formData.append("Archivo", this.Archivo);

            formData.append('_method', 'PUT');

            //Enviamos la petición
            axios.post(url, formData)
                .then(response => {

                    ////DEBO HACER FUNCIONAR BIEN ESTO PARA QUE SE ACTUALICE LA FOTO QUE CARGO EN EL MOMENTO, SI NO PARECE Q NO SE CARGARA NADA
                    this.productoFoto.Imagen = response.data.Imagen;
                    this.getListadoProductos(0, 0);
                    toastr.success('Proceso realizado correctamente', 'Fabricación')

                    this.preloader = 0;
                }).catch(error => {
                    console.log(error.response); //alert("mal");
                    console.log(error)
                    this.preloader = 0;
                });
        },

        //// FABRICACION |     Carga el formulario Items para editar FOTO
        editarFormularioProductoFoto(item) {
            this.productoFoto = item;
            this.texto_boton = "Actualizar";
        },

        //// FABRICACION  | Dasactivar un item
        desactivarProducto: function (Id) {
            var url = base_url + 'fabricacion/desactivar_producto'; // url donde voy a mandar los datos

            var opcion = confirm("¿Esta seguro de eliminar a este producto?");
            if (opcion == true) {
                axios.post(url, {
                    token: token,
                    Id: Id
                }).then(response => {

                    toastr.success('Proceso realizado correctamente', 'Productos')

                    this.getListadoProductos(0, 0);

                }).catch(error => {
                    console.log(error.response); //alert("mal");
                    console.log(error)

                });
            }
        },


        //// VENTAS |  MOSTRAR LISTADO
        getListadoVentas: function (Usuario_id, Empresa_id, Cliente_id, Estado, Planificacion_id) {
            var url = base_url + 'ventas/obtener_listado_ventas?Empresa_id=' + Empresa_id + '&Vendedor_id=' + Usuario_id + '&Cliente_id=' + Cliente_id + '&Estado=' + Estado + '&Planificacion_id=' + Planificacion_id; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                
                this.listaVentas = response.data
            
            }).catch(error => {
                
                //console.log(error.response); //alert("mal");
                console.log(error.response.data)

            });
        },

        //// VENTAS | CREAR O EDITAR ITEM
        crearVenta: function () {
            var url = base_url + 'ventas/cargar_venta'; // url donde voy a mandar los datos

            this.boton_habilitado = 0;

            axios.post(url, {
                token: token,
                Data: this.ventaDatos
            }).then(response => {

                toastr.success('Proceso realizado correctamente', 'Fabricación')

                this.ventaDatos.Id = response.data.Id;
                this.texto_boton = "Actualizar"

                this.getListadoVentas(0, 0, 0, 1, 0);
                this.boton_habilitado = 1;

            }).catch(error => {
                this.boton_habilitado = 1;
                console.log(error.response.data)
            });
        },

        //// VENTAS |  LIMPIAR EL FORMULARIO DE CREAR
        limpiarFormularioVenta() {
            this.ventaDatos = {
                Descuento: 0,
                Recargo: 0,
                Valor_instalacion: 0,
                Valor_logistica: 0,
            }
            this.texto_boton = "Cargar";
        },

        //// VENTAS |  Carga el formulario para editar
        editarFormularioVenta(item) {
            this.ventaDatos = item;
            this.texto_boton = "Actualizar";
        },

        //// VENTAS  | Dasactivar un item
        desactivarVenta: function (Id) {
            var url = base_url + 'ventas/desactivarVenta'; // url donde voy a mandar los datos

            var opcion = confirm("¿Esta seguro de anular esta venta? Asegurarse de haber anulado todos los productos vinculados a ella.");
            if (opcion == true) {
                axios.post(url, {
                    token: token,
                    Id: Id
                }).then(response => {

                    toastr.success('Proceso realizado correctamente', 'Ventas')

                    this.getListadoVentas(this.filtro_vendedor, this.filtro_empresa, this.filtro_cliente, this.filtro_estado, this.filtro_planificacion);

                }).catch(error => {
                    console.log(error.response); //alert("mal");
                    console.log(error)
                });
            }
        },

        //// VENTAS  | CANTIDAD DE DIAS ENTRE DOS FECHAS
        diferenciasEntre_fechas: function (fechaInicio, fechaFin) {

            if (fechaFin == null) { fechaFin = hoy_php }
            if (fechaInicio == null) { fechaInicio = hoy_php }

            var fechaInicio = new Date(fechaInicio).getTime();
            var fechaFin = new Date(fechaFin).getTime();
            var diff = fechaFin - fechaInicio;

            /// debe devolver mensajes completos
            diff = diff / (1000 * 60 * 60 * 24)

            if (diff < 0) {
                diff = diff * parseInt(-1)
                return diff + ' días de atrazo'
            }
            else {
                return diff + ' días'
            }
        },



        //// COBRANZAS |  MOSTRAR LISTADO 
        obtener_listado_ventas_cobranzas: function (Usuario_id, Empresa_id, Cliente_id, Estado, Planificacion_id) {
            var url = base_url + 'ventas/obtener_listado_ventas_cobranzas?Empresa_id=' + Empresa_id + '&Vendedor_id=' + Usuario_id + '&Cliente_id=' + Cliente_id + '&Estado=' + Estado + '&Planificacion_id=' + Planificacion_id; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {

                this.listaCobranzas = response.data;
                //console.log(response.data);

            }).catch(error => {
                console.log(error.response); //alert("mal");
                console.log(error.response.data)

            });
        },

        //// COBRANZAS | LIMPIAR EL FORMULARIO 
        limpiarFormularioCobro(venta) {
            this.cobroDatos = venta;
            this.cobroDatos.Venta_id = venta.Id;
            delete this.cobroDatos.Id; /// al generar un cobro nuevo, debo eliminar el Id que trae el arreglo
            this.texto_boton = "Cargar";
        },

        //// COBRANZAS | CREAR O EDITAR EN EFECTIVO 
        crear_movimiento: function () {
            var url = base_url + 'finanzas/cargar_movimiento'; // url donde voy a mandar los datos
            this.boton_habilitado = 0;

            axios.post(url, {
                token: token,
                Datos: this.cobroDatos,
                Origen_movimiento: 'Ventas',
                Op: 1,
                Planificacion_id: this.cobroDatos.Planificacion_id,
                Fila_movimiento: this.cobroDatos.Venta_id,

            }).then(response => {

                toastr.success('Proceso realizado correctamente', 'Fondo')

                this.texto_boton = "Actualizar"
                this.obtener_listado_ventas_cobranzas(this.filtro_vendedor, 0, this.filtro_cliente, this.filtro_estado, this.filtro_planificacion)
                this.boton_habilitado = 1;


            }).catch(error => {
                this.boton_habilitado = 1;
                console.log(error.response.data)
            });
        },

        //// COBRANZAS |  MOSTRAR LISTADO 
        obtener_listado_cobros: function () {
            var url = base_url + 'ventas/obtener_listado_cobros'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Fecha_inicio: this.filtroFechaInicial,
                Fecha_fin: this.filtroFechaFinal,
            }).then(response => {

                this.listaCobros = response.data
                //console.log(this.listaCobros);

            }).catch(error => {
                console.log(error.response); //alert("mal");
                console.log(error.response.data)

            });
        },


        //// PLANIFICACIONES |  MOSTRAR LISTADO DE ORDENES
        getListadoPlanificaciones: function () {
            var url = base_url + 'ventas/obtener_listado_planificaciones'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaPlanificaciones = response.data
            }).catch(error => {
                console.log(error.response); //alert("mal");
                console.log(error.response.data)

            });
        },

        //// PLANIFICACIONES | CREAR O EDITAR ITEM
        crearPlanificacion: function () {
            var url = base_url + 'ventas/cargar_planificacion'; // url donde voy a mandar los datos
            this.boton_habilitado = 0;

            axios.post(url, {
                token: token,
                Data: this.planificacionDatos
            }).then(response => {

                toastr.success('Proceso realizado correctamente', 'Ventas')

                this.planificacionDatos.Id = response.data.Id;
                this.texto_boton = "Actualizar"

                this.getListadoPlanificaciones();
                this.boton_habilitado = 1;

            }).catch(error => {
                this.boton_habilitado = 1;
                console.log(error.response.data)
            });
        },

        //// PLANIFICACIONES |  LIMPIAR EL FORMULARIO DE CREAR
        limpiarFormularioPlanificacion() {
            this.planificacionDatos = {}
            this.texto_boton = "Cargar";
        },

        //// PLANIFICACIONES |  Carga el formulario para editar
        editarFormularioPlanificacion(item) {
            this.planificacionDatos = item;
            this.texto_boton = "Actualizar";
        },



        //// COMPRAS  | MOSTRAR LISTADO
        getListadoCompras: function (rubro_id, inicio, final, planificacion_id) {
            var url = base_url + 'compras/obtener_compras'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Fecha_inicio: inicio,
                Fecha_fin: final,
                Planifacion_id: planificacion_id,
                Rubro_id: rubro_id
            }).then(response => {
                this.listaCompras = response.data
                
                //console.log(this.listaCompras)
            }).catch(error => {
               
                console.log(error.response.data)
            });
        },

        //// COMPRAS  | LIMPIAR EL FORMULARIO DE CREAR
        limpiarFormularioCompras() {
            this.compraDatos = {}
            this.texto_boton = "Cargar";
        },

        //// COMPRAS  | Carga el formulario  para editar
        editarFormularioCompra(compraDatos) {
            //this.usuario = {};
            this.compraDatos = compraDatos;
            this.texto_boton = "Actualizar";
        },

        //// COMPRAS  | CREAR O EDITAR 
        crearCompra: function () {
            var url = base_url + 'compras/cargar_compra'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Datos: this.compraDatos
            }).then(response => {

                toastr.success('Proceso realizado correctamente', 'Compras')

                this.compraDatos.Id = response.data.Id;
                this.texto_boton = "Actualizar"

                this.getListadoCompras(null, null, null, 0);

            }).catch(error => {
                alert("mal");
                console.log(error)
                toastr.success('Proceso realizado correctamente', 'Compras')
            });
        },

        //// COMPRAS  | Dasactivar
        desactivarCompra: function (Id) {
            var url = base_url + 'compras/desactivar_compra'; // url donde voy a mandar los datos

            var opcion = confirm("Recuerde que al eliminar una compra, no se eliminarán los registros de pagos vinculados a la misma. ¿Esta seguro de eliminar a este compra?");
            if (opcion == true) {
                axios.post(url, {
                    token: token,
                    Id: Id
                }).then(response => {

                    toastr.success('Proceso realizado correctamente', 'Compras')

                    this.getListadoCompras(null, null, null, 0);

                }).catch(error => {
                    alert("mal");
                    console.log(error)

                });
            }
        },

        //// COMPRAS  | Subir imagen
        subirFotoCompra(Id) {
            //this.texto_boton = "Actualizar"
            var url = base_url + 'compras/subirFoto/?Id=' + Id; // url donde voy a mandar los datos
            this.preloader = 1;
            //const formData = event.target.files[0];
            const formData = new FormData();
            formData.append("Archivo", this.Archivo);

            formData.append('_method', 'PUT');

            //Enviamos la petición
            axios.post(url, formData)
                .then(response => {

                    ////DEBO HACER FUNCIONAR BIEN ESTO PARA QUE SE ACTUALICE LA FOTO QUE CARGO EN EL MOMENTO, SI NO PARECE Q NO SE CARGARA NADA
                    this.compraFoto.Imagen = response.data.Imagen;
                    this.getListadoCompras(null, null, null, 0);
                    toastr.success('Proceso realizado correctamente', 'Compras')

                    this.preloader = 0;
                }).catch(error => {
                    alert("mal");
                    console.log(error)
                    this.preloader = 0;
                });
        },

        //// COMPRAS  |  Carga el formulario Items para editar FOTO
        editarFormulariocompraFoto(item) {
            this.compraFoto = item;
            this.texto_boton = "Actualizar";
        },

        //// COMPRAS | SUMA PARA DAR TOTAL DE UNA COMPRA
        sumarComrpra: function (n1, n2, n3) {

            var N1 = parseInt(n1);
            var N2 = parseInt(n2);
            var N3 = parseInt(n3);

            return N1 + N2 + N3;
        },

        //// CALCULAR TOTAL DE LAS COMPRAS PAGADAS
        totalCompras: function(datos){

            var Total = 0;
            
            if(datos != null & datos.length > 0)
            {
                for (let index = 0; index < datos.length; index++) 
                {
                    Total = Total + parseInt(datos[index].Neto) + parseInt(datos[index].IVA) + parseInt(datos[index].No_gravado) ;
                }
            }
            return Total;
        },

        //// 
    },

    ////// ACCIONES COMPUTADAS     
    computed:
    {
        buscarUsuarios: function () {
            return this.listaUsuarios.filter( ( item ) => item.Nombre.toLowerCase().includes( this.buscar.toLowerCase() ) );
            //return this.listaUsuarios;
        },

        /* buscarCurriculums: function () {
            return this.listaCurriculums.filter(( item ) => item.nombre.toLowerCase().includes( this.buscar.toLowerCase() ) );
        }, */

        buscarStock: function () {
            return this.listaStock.filter(( item ) => item.Nombre_item.toLowerCase().includes( this.buscar.toLowerCase() ) );
        },

        buscarCliente: function () {
            return this.listaClientes.filter(( item ) => item.Nombre_cliente.toLowerCase().includes( this.buscar.toLowerCase() ) );
        },

        buscarProveedor: function () {
            return this.listaProveedores.filter(( item ) => item.Datos_proveedor.Nombre_proveedor.toLowerCase().includes( this.buscar.toLowerCase() ) );
        },

        buscarProducto: function () {
            return this.listaProductos.filter(( item ) => item.Nombre_producto.toLowerCase().includes( this.buscar.toLowerCase() ) );
        },

    }
});
///--------------

///         --------------------------------------------------------------------   ////
//// Elemento para del Dashboard
new Vue({
    el: '#dashboard',

    created: function () {

        this.obtener_cantidad_de_items_stoqueados();
        this.obtenerCantidadUsuarios();
        this.getListadoMovimientos();
        this.getListadoCompras(0);
        this.getListadoVentas();
        this.obtener_cantVentas();
        this.obtener_cantCompras();
        this.obtener_cantProductosPropios();
        this.valorDolar();
        //this.noticiasGoogle();
        this.seguimientoPersonal();


        //setInterval(() => { this.getTimeline(0, null); }, 60000); //// funcion para actualizar automaticamente cada 1minuto
    },

    data: {

        cantItemsStock: '0',
        cantidadUsuarios: '0',
        cantVentasAnio: '0',
        cantCompras: '0',
        listaMovimientos: [],
        listaCompras: [],
        listaVentas: [],
        cantProductosPropios: '0',
        valorDolarHoy: '',
        listaNoticias: {},
        listaSeguimientoPersonal: []
    },

    methods:
    {

        ////CANTIDAD DE CURRICULUM DE ESTE MES    
        obtener_cantidad_de_items_stoqueados: function () {
            var url = base_url + 'dashboard/obtener_cantidad_de_items_stoqueados'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.cantItemsStock = response.data
            });
        },

        ////CANTIDAD DE CURRICULUM DE ESTE MES    
        obtener_cantVentas: function () {
            var url = base_url + 'ventas/obtener_cantVentas'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.cantVentasAnio = response.data
            });
        },

        ////CANTIDAD DE CURRICULUM DE ESTE MES    
        obtener_cantCompras: function () {
            var url = base_url + 'dashboard/obtener_cantCompras'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.cantCompras = response.data
            });
        },

        ////CANTIDAD DE CURRICULUM DE ESTE MES    
        obtener_cantProductosPropios: function () {
            var url = base_url + 'dashboard/obtener_productosPropios'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.cantProductosPropios = response.data
            });
        },

        ////CANTIDAD DE USUARIOS TOTALES 
        obtenerCantidadUsuarios: function () {
            var url = base_url + 'dashboard/obtener_cantidad_usuarios'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.cantidadUsuarios = response.data
            });
        },

        //// FORMATO FECHA HORA
        formatoFecha_hora: function (fecha) {
            //separador = ":",
            fecha = fecha.split(' ');

            //var fecha_dia = fecha[0].replace(/^(\d{4})-(\d{2})-(\d{2})$/g, '$3/$2/$1');
            var fecha_dia = fecha[0].replace(/^(\d{4})-(\d{2})-(\d{2})$/g, '$3/$2/$1');

            var fecha_hora = fecha[1].split(':');
            fecha_hora = fecha_hora[0] + ':' + fecha_hora[1];

            return fecha_dia + ' ' + fecha_hora + 'hs '

        },

        //// MOSTRAR LISTADO DE ROLES  
        getListadoMovimientos: function () {
            var url = base_url + 'stock/obtener_ultimos_movimientos'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaMovimientos = response.data
            });
        },

        //// MENSAJE DE LOS INGRESOS A CAJA
        mensaje: function () {
            toastr.warning('Este valor refleja los ingresos en bruto a caja durante el período indicado. Surge del total de las ventas y solo se le han restado los descuentos realizados en la cuenta de cada comanda y de cada delivery.', 'IMPORTANTE')
        },

        //// MOSTRAR LISTADO DE COMPRAS 
        getListadoCompras: function () {
            var url = base_url + 'compras/obtener_compras_dashboarad'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaCompras = response.data
            });
        },

        //// FORMATO FECHA
        formatoFecha: function (fecha) {
            if (fecha == null) {
                return 'No definida'
            }
            else {
                return fecha.replace(/^(\d{4})-(\d{2})-(\d{2})$/g, '$3/$2/$1');
            }
        },

        //// VENTAS  | CANTIDAD DE DIAS ENTRE DOS FECHAS
        diferenciasEntre_fechas: function (fechaInicio, fechaFin) {

            if (fechaFin == null) { fechaFin = hoy_php }
            if (fechaInicio == null) { fechaInicio = hoy_php }

            var fechaInicio = new Date(fechaInicio).getTime();
            var fechaFin = new Date(fechaFin).getTime();
            var diff = fechaFin - fechaInicio;

            /// debe devolver mensajes completos
            diff = diff / (1000 * 60 * 60 * 24)

            if (diff < 0) {
                diff = diff * parseInt(-1)
                return diff + ' días de atrazo'
            }
            else {
                return diff + ' días'
            }
        },

        //// VENTAS |  MOSTRAR LISTADO DE VENTAS
        getListadoVentas: function () {
            var url = base_url + 'ventas/obtener_listado_ventas_dashboard'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaVentas = response.data
            }).catch(error => {
                console.log(error.response); //alert("mal");
                console.log(error.response.data)

            });
        },

        //// VENTAS |  MOSTRAR LISTADO DE VENTAS
        valorDolar: function () {
            var url = 'http://ws.geeklab.com.ar/dolar/get-dolar-json.php'; // url donde voy a mandar los datos

            axios.post(url).then(response => {
                this.valorDolarHoy = response.data
            }).catch(error => {
                console.log(error.response); //alert("mal");
                console.log(error.response.data)

            });
        },

        //// NOTICIAS DE INTERES
        noticiasGoogle: function () {
            var url = 'https://newsapi.org/v2/top-headlines?' + 'country=ar&' + 'apiKey=469fb6edbe7243b2ac2544b5058e30f8';

            axios.get(url).then(response => {
                this.listaNoticias = response.data
                //console.log(response.data)
            }).catch(error => {
                //console.log(error.response); //alert("mal");
                //console.log(error.response.data)

            });
        },

        //// MOSTRAR LISTADO DE SEGUIMIENTO PERSONAL
        seguimientoPersonal: function () {
            var url = base_url + 'dashboard/obtener_seguimiento_personal'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaSeguimientoPersonal = response.data
            });
        },






        //////
    }
});


///         --------------------------------------------------------------------   ////
//// Elemento para el manejo de usuarios por Id
new Vue({
    el: '#usuarios',

    created: function () {
        this.getDatosUsuario();
        this.getListadoRoles();
        this.getFormaciones();
        this.getSuperiores();
        this.getListadoEmpresas();
        this.getListadoPuesto();
        this.getListadoSeguimiento();
        this.getListadoCategoriasSeguimiento();

    },

    data: {

        mostrar: "1",

        usuario: {},

        usuarioFoto: { 'Id': '', 'Nombre': '', 'Imagen': '' },
        Archivo: '',

        listaSuperiores: [],

        listaRoles: [],

        listaSeguimiento: [],
        texto_boton: "Cargar",
        seguimientoData: {},
        Archivo: null,
        preloader: '0',

        listaFormaciones: [],
        formacionData: { 'Id': '', 'Titulo': '', 'Establecimiento': '', 'Anio_inicio': '', 'Anio_finalizado': '', 'Descripcion_titulo': '' },

        listaEmpresas: [],
        listaPuestos: [],

        //CATEGORIA SEGUIMIENTO
        listaCatReportes: [],
        catReporte: {},

        listaReportes: [],

        filtro_puesto: "0",
        filtro_empresa: "0",
        filtro_sexo: '0',
        filtro_edad: '0',
    },

    methods:
    {

        //// MOSTRAR LISTADO DE ROLES  
        getListadoRoles: function () {
            var url = base_url + 'usuarios/obtener_roles'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaRoles = response.data
            });
        },

        //// OBTENER DATOS DE UN USUARIO 
        getDatosUsuario: function () {
            var url = base_url + 'usuarios/obtener_Usuario/?Id=' + Get_Id;  //// averiguar como tomar el Id que viene por URL aca

            axios.post(url, {
                token: token
            }).then(response => {
                this.usuario = response.data[0]
            });
        },

        //// OBTENER USUARIOS LIDERES
        getSuperiores: function () {
            var url = base_url + 'usuarios/obtener_lideres';  //// averiguar como tomar el Id que viene por URL aca

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaSuperiores = response.data


            });
        },

        //// CREAR O EDITA un Usuario 
        crearUsuarios: function () {
            var url = base_url + 'usuarios/cargar_Usuarios'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                usuarioData: this.usuario
            }).then(response => {

                toastr.success('Datos actualizados correctamente', 'Usuarios')

                this.usuario.Id = response.data.Id;
                this.texto_boton = "Actualizar"

            }).catch(error => {
                console.log(error.response); //alert("mal");
                console.log(error)
            });
        },

        //// CREAR O EDITAR una formación
        crearFormacion: function () {
            var url = base_url + 'usuarios/cargar_formacion'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                formacionData: this.formacionData, Usuario_id: Get_Id
            }).then(response => {

                toastr.success('Datos actualizados correctamente', 'Usuarios')

                this.formacionData.Id = response.data.Id;
                this.texto_boton = "Actualizar"
                this.getFormaciones();

            }).catch(error => {
                alert(response.data);
            });
        },

        //// MOSTRAR LISTADO DE FORMACIONES
        getFormaciones: function () {
            var url = base_url + 'usuarios/obtener_formaciones/?Id=' + Get_Id;  //// averiguar como tomar el Id que viene por URL aca

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaFormaciones = response.data

                //console.log(response.data)
            });
        },

        editarFormularioFormacion: function (formacion) {
            this.formacionData = formacion;
        },

        //// LIMPIAR FORMULARIO FORMACION
        limpiarFormularioFormacion: function () {
            this.formacionData = { 'Id': '', 'Titulo': '', 'Establecimiento': '', 'Anio_inicio': '', 'Anio_finalizado': '', 'Descripcion_titulo': '' }
        },

        //// SCRITPS PARA SUBIR FOTOS FORMACIONES
        archivoSeleccionado(event) {
            this.Archivo = event.target.files[0]
            //this.texto_boton = "Actualizar"
        },

        upload(Id) {
            //this.texto_boton = "Actualizar"
            var url = base_url + 'usuarios/subirFotoUsuario/?Id=' + Id; // url donde voy a mandar los datos

            //const formData = event.target.files[0];
            const formData = new FormData();
            formData.append("Archivo", this.Archivo);

            formData.append('_method', 'PUT');

            //Enviamos la petición
            axios.post(url, formData)
                .then(response => {

                    ////DEBO HACER FUNCIONAR BIEN ESTO PARA QUE SE ACTUALICE LA FOTO QUE CARGO EN EL MOMENTO, SI NO PARECE Q NO SE CARGARA NADA
                    this.usuarioFoto.Imagen = response.data.Imagen;
                    this.getListadoUsuarios(1);
                    toastr.success('Proceso realizado correctamente', 'Usuarios')

                }).catch(error => {
                    console.log(error.response); //alert("mal");
                    console.log(error)

                });
        },

        //// Carga el formulario Items para editar FOTO
        editarFormulariousuarioFoto(item) {
            this.usuarioFoto = item;
            this.texto_boton = "Actualizar";
        },

        //// MOSTRAR LISTADO DE puestos  
        getListadoPuesto: function () {
            var url = base_url + 'usuarios/obtener_puestos'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaPuestos = response.data
            });
        },

        //// MOSTRAR LISTADO DE EMPRESAS  
        getListadoEmpresas: function () {
            var url = base_url + 'usuarios/obtener_empresas'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaEmpresas = response.data
            });
        },

        //// MOSTRAR LISTADO
        getListadoSeguimiento: function () {
            var url = base_url + 'usuarios/obtener_seguimientos/?Id=' + Get_Id; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaSeguimiento = response.data
            });
        },

        //// DATOS SOBRE EL ARCHIVO SELECCIONADO EN CASO QUE QUIERA CARGAR ALGUNA ARCHIVO
        archivoSeleccionado(event) {
            this.Archivo = event.target.files[0]
            //this.texto_boton = "Actualizar"
        },

        //// CREAR O EDITAR una SEGUIMIENTO
        crearSeguimiento: function () {
            var url = base_url + 'usuarios/cargar_seguimiento'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Datos: this.seguimientoData,
                Usuario_id: Get_Id
            }).then(response => {

                this.seguimientoData.Id = response.data.Id;

                /// si eso se ralizó bien, debe comprobar si hay un archivo a cargar.
                if (this.Archivo != null) {
                    var url = base_url + 'usuarios/subirFotoSeguimiento/?Id=' + this.seguimientoData.Id;
                    this.preloader = 1;

                    //const formData = event.target.files[0];
                    const formData = new FormData();
                    formData.append("Archivo", this.Archivo);

                    formData.append('_method', 'PUT');

                    //Enviamos la petición
                    axios.post(url, formData)
                        .then(response => {

                            this.seguimientoData.Url_archivo = response.data.Url_archivo;

                            toastr.success('El archivo se cargo correctamente', 'Usuarios')
                            this.preloader = 0;
                            this.getListadoSeguimiento();

                        }).catch(error => {
                            alert("MAL LA CARGA EN FUNCIÓN DE CARGAR ARCHIVO");
                            this.preloader = 0;
                            //this.seguimientoData.Url_archivo = response.data.Url_archivo;
                        });
                }
                // si lo hay lo carga, si no lo hay no hace nada

                this.getListadoSeguimiento();
                this.Archivo = null
                this.texto_boton = "Actualizar"
                toastr.success('Datos actualizados correctamente', 'Usuarios')

            }).catch(error => {
                alert("MAL LA CARGA EN FUNCIÓN DE CARGAR DATOS");
            });
        },

        /// EDITAR UN SEGUIMIENTO
        editarFormularioSeguimiento: function (dato) {
            this.seguimientoData = dato;
        },

        //// LIMPIAR FORMULARIO SEGUIMIENTO
        limpiarFormularioSeguimiento: function () {
            this.seguimientoData = {}
        },

        //// CATEGORIAS SEGUIMIENTO | MOSTRAR LISTADO
        getListadoCategoriasSeguimiento: function () {
            var url = base_url + 'usuarios/obtener_categorias_seguimientos'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaCatReportes = response.data
            });
        },

        //// CATEGORIAS SEGUIMIENTO | CREAR O EDITAR
        crearCatReporte: function () {
            var url = base_url + 'usuarios/cargar_categorias_seguimiento'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Datos: this.catReporte,
            }).then(response => {

                this.catReporte.Id = response.data.Id;


                this.getListadoCategoriasSeguimiento();
                this.Archivo = null
                this.texto_boton = "Actualizar"
                toastr.success('Datos actualizados correctamente', 'Usuarios')

            }).catch(error => {
                alert("MAL LA CARGA EN FUNCIÓN DE CARGAR DATOS");
            });
        },

        //// CATEGORIAS SEGUIMIENTO | EDITAR
        editarFormularioCatReporte: function (dato) {
            this.catReporte = dato;
        },

        //// CATEGORIAS SEGUIMIENTO | LIMPIAR FORMULARIO
        limpiarFormularioCatSeguimiento: function () {
            this.catReporte = {}
        },

        //// PROMEDIO CALIFICACION SEGUIMIENTOS 
        promedioCalificaciones: function (datos) {
            if (datos != null) {
                var Promedio = 0;

                for (var i = 0; i < datos.length; i++) {
                    var item = 0;

                    if (isFinite(datos[i].Calificacion)) {
                        item = parseInt(datos[i].Calificacion);
                    }

                    Promedio = Promedio + item;
                }
                Promedio = Promedio / datos.length
                return Promedio.toFixed(2)
            }
            else {
                return "Sin datos";
            }
        },

        //// REPORTES RESUMEN | COLOREAR ITEMS BAJOS DE STOCK
        classColorReporte: function (numero) {
            var numero = Math.round(numero);

            if (numero == 5) { return 'text-success' }
            else if (numero == 4) { return 'text-success' }
            else if (numero == 3) { return 'text-secondary' }
            else if (numero == 2) { return 'text-warning' }
            else if (numero == 1) { return 'text-danger' }
            else { return '' }

        },


    },

    ////// ACCIONES COMPUTADAS     
    computed:
    {

    }
});


///         --------------------------------------------------------------------   ////
//// Elemento para el manejo de CURRICULUM por Id
new Vue({
    el: '#curriculum',

    created: function () {
        //this.getDatosCurriculum();
        //this.getListadoPuestosCurriculum();
        //this.getListadoPuestos();
    },

    data: {

        mostrar: "1",

        curriculum: {
            'Id': '',
            'IP': '',
            'fecha': '',
            'nombre': '',
            'sexo': '',
            'nacimiento': '',
            'foto': '',
            'ciudad': '',
            'domicilio': '',
            'telefono': '',
            'email': '',
            'cuil': '',
            'referencia': '',
            'url': '',
            'hijos': '',
            'estadocivil': '',
            'nivelestudios': '',
            'sobreestudios': '',
            'laboral': '',
            'intereses': '',
            'personal': '',
            'Puntaje': '',
            'Puestos': '',
            'Observaciones': ''
        },

        texto_boton: "Cargar",

        listaPuestos: [],

        listaPuestosInteres: [],

        puestoData: { 'Id': '', 'Puesto_id': '', 'Justificacion': '' },
    },

    methods:
    {

        ////  CURRICULUM | OBTENER DATOS DE UNO
        getDatosCurriculum: function () {
            var url = base_url + 'curriculum/obtener_curriculum/?Id=' + Get_Id;  //// averiguar como tomar el Id que viene por URL aca

            axios.post(url, {
                token: token
            }).then(response => {
                this.curriculum = response.data[0]
            });
        },


        ////  CURRICULUM | CREAR O EDITA
        actualizarCurriculum: function () {
            var url = base_url + 'curriculum/actualizar_curriculum'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Data: this.curriculum
            }).then(response => {

                toastr.success('Datos actualizados correctamente', 'Curriculum')

                this.curriculum.Id = response.data.Id;
                this.texto_boton = "Actualizar"

            }).catch(error => {
                console.log(error.response); //alert("mal");
                console.log(error)
            });
        },

        //// MOSTRAR LISTADO DE puestos  
        getListadoPuestos: function () {
            var url = base_url + 'usuarios/obtener_puestos'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaPuestos = response.data
            });
        },


        //// CURRICULUM | CREAR O EDITAR EMPRESAS
        agregarPuestoCurriculum: function () {
            var url = base_url + 'curriculum/cargar_puesto_curriculum'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Data: this.puestoData, Id: Get_Id
            }).then(response => {

                toastr.success('Proceso realizado correctamente', 'Curriculum')

                this.puestoData.Id = response.data.Id;
                this.texto_boton = "Actualizar"

                this.getListadoPuestosCurriculum();

            }).catch(error => {
                console.log(error.response); //alert("mal");
                console.log(error)

            });
        },


        ////  CURRICULUM | OBTENER PUESTOS ASIGNADOS
        getListadoPuestosCurriculum: function () {
            var url = base_url + 'curriculum/obtener_puestos_curriculum/?Id=' + Get_Id;  //// averiguar como tomar el Id que viene por URL aca

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaPuestosInteres = response.data
            });
        },


        //// ELIMINAR UN PUESTO
        eliminar: function (Id, tbl) {
            var url = base_url + 'curriculum/eliminar'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Id: Id, tabla: tbl
            }).then(response => {

                this.getListadoPuestosCurriculum();
                toastr.success('pUESTO eliminado correctamente', 'Curriculum')

            }).catch(error => {
                console.log(error.response); //alert("mal");
                console.log(error)
            });
        },
    },

    ////// ACCIONES COMPUTADAS     
    computed:
    {

    }
});


///         --------------------------------------------------------------------   ////
//// Elemento para el manejo de STOCK por Id
new Vue({
    el: '#itemStock',

    created: function () {
        this.getDatosItem();
        this.getListadoMovimientos();
        this.obtener_listado_de_proveedores_asignados();
    },

    data: {

        itemDatos: {},
        listaMovimientos: [],
        lista_proveedores_vinculados: [],
        lista_proveedores_no_vinculados: [],

        movimientoDatos: ''

    },

    methods:
    {

        //// MOSTRAR LISTADO DE MOVIMIENTOS  
        getListadoMovimientos: function () {
            var url = base_url + 'stock/obtener_movimientos/?Id=' + Get_Id; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Tipo_movimiento: 0

            }).then(response => {
                this.listaMovimientos = response.data
            });
        },

        //// OBTENER DATOS DE UN PRODUCTO/ITEM 
        getDatosItem: function () {
            var url = base_url + 'stock/obtener_datos_item/?Id=' + Get_Id;  //// averiguar como tomar el Id que viene por URL aca

            axios.post(url, {
                token: token
            }).then(response => {
                this.itemDatos = response.data[0]
            });
        },


        classAlertaStock: function (actual, ideal) {
            var Valor_actual = parseInt(actual);
            var Valor_ideal = parseInt(ideal);

            if (Valor_actual < Valor_ideal) {
                return 'text-danger'
            }
            else {
                return ''
            }
        },

        //// FORMATO FECHA HORA
        formatoFecha_hora: function (fecha) {
            //separador = ":",
            fecha = fecha.split(' ');

            //var fecha_dia = fecha[0].replace(/^(\d{4})-(\d{2})-(\d{2})$/g, '$3/$2/$1');
            var fecha_dia = fecha[0].replace(/^(\d{4})-(\d{2})-(\d{2})$/g, '$3/$2/$1');

            var fecha_hora = fecha[1].split(':');
            fecha_hora = fecha_hora[0] + ':' + fecha_hora[1];

            return fecha_dia + ' ' + fecha_hora + 'hs '

        },

        //// VINCULO STOCK PROVEEDOR |  Vincular proveedor a producto del stock
        Vincular_producto_proveedor: function (Proveedor_id) {
            var url = base_url + 'stock/Vincular_producto_proveedor'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Proveedor_id: Proveedor_id, Stock_id: Get_Id
            }).then(response => {

                toastr.success('Datos actualizados correctamente', 'Clientes')

                this.Vincular_producto_proveedor(); // actualizar listado de proveedores no vinculados
                this.obtener_listado_de_proveedores_asignados(); // actualizar listado de proveedores vinculados

            }).catch(error => {
                alert(response.data);
            });
        },


        //// VINCULO STOCK PROVEEDOR |  Desvincular proveedor a producto del stock
        Vincular_producto_proveedor: function (Proveedor_id) {
            var url = base_url + 'stock/Vincular_producto_proveedor'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Proveedor_id: Proveedor_id, Stock_id: Get_Id
            }).then(response => {

                toastr.success('Datos actualizados correctamente', 'Clientes')

                this.obtener_listado_de_proveedores_asignados(); // actualizar listado de proveedores no vinculados
                this.obtener_listado_de_proveedores_no_asignados(); // actualizar listado de proveedores vinculados

            }).catch(error => {
                alert(response.data);
            });
        },


        //// VINCULO STOCK PROVEEDOR |  OBTENER LISTADO DE PROVEEDORES VINCULADOS
        obtener_listado_de_proveedores_asignados: function () {
            var url = base_url + 'stock/obtener_listado_de_proveedores_asignados/?Id=' + Get_Id;  //// averiguar como tomar el Id que viene por URL aca

            axios.post(url, {
                token: token
            }).then(response => {
                this.lista_proveedores_vinculados = response.data
            });
        },


        //// VINCULO STOCK PROVEEDOR |  OBTENER LISTADO DE PROVEEDORES NO VINCULADOS
        obtener_listado_de_proveedores_no_asignados: function () {
            var url = base_url + 'stock/obtener_listado_de_proveedores_no_asignados/?Id=' + Get_Id;  //// averiguar como tomar el Id que viene por URL aca

            axios.post(url, {
                token: token
            }).then(response => {
                this.lista_proveedores_no_vinculados = response.data
            });
        },

        //// REPORTES DE MOVIMIENTOS DE STOCK | FORMULARIO
        editarFormularioMovimiento: function (datos) {
            this.movimientoDatos = datos;
        },

        //// REPORTES DE MOVIMIENTOS DE STOCK | ACTUALIZAR
        actualizarMovimiento: function () {
            var url = base_url + 'stock/actualizarMovimiento'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Data: this.movimientoDatos
            }).then(response => {

                toastr.success('Proceso realizado correctamente', 'Stock')

                this.texto_boton = "Actualizar"

                this.getListadoMovimientos();

            }).catch(error => {
                console.log(error.response); //alert("mal");
                console.log(error)

            });
        },
    },

    ////// ACCIONES COMPUTADAS     
    computed:
    {

    }
});


///         --------------------------------------------------------------------   ////
//// Elemento para el manejo de CLIENTES por Id
new Vue({
    el: '#clientes',

    created: function () {
        this.getDatosCliente();
        this.getListadoSeguimiento();
        this.getListadoVentas(0, 0, Get_Id, 10, 0);

    },

    data: {
        mostrar: "1",
        clienteDatos: { 'Id': '', 'Nombre_cliente': '', 'Producto_servicio': '', 'Imagen': '', 'Direccion': '', 'Localidad': '', 'Provincia': '', 'Pais': '', 'Telefono': '', 'Email': '', 'Web': '', 'Nombre_persona_contacto': '', 'Datos_persona_contacto': '', 'Mas_datos_cliente': '' },
        listaSeguimiento: [],
        texto_boton: "Cargar",
        seguimientoData: {},

        listaVentas: [],
        filtro_estado: 10,
    },

    methods:
    {

        //// MOSTRAR LISTADO
        getListadoSeguimiento: function (Categoria_seguimiento, mostrar) {
            var url = base_url + 'clientes/obtener_seguimientos/?Id=' + Get_Id; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaSeguimiento = response.data;

            });
        },

        //// OBTENER DATOS DE UN CLIENTE
        getDatosCliente: function () {
            var url = base_url + 'clientes/obtener_datos_cliente/?Id=' + Get_Id;  //// averiguar como tomar el Id que viene por URL aca

            axios.post(url, {
                token: token
            }).then(response => {
                this.clienteDatos = response.data[0]
            });
        },

        //// CREAR O EDITAR una SEGUIMIENTO
        crearSeguimiento: function () {
            var url = base_url + 'clientes/cargar_seguimiento'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Datos: this.seguimientoData, Id_cliente: Get_Id
            }).then(response => {

                toastr.success('Datos actualizados correctamente', 'Clientes')

                this.seguimientoData.Id = response.data.Id;
                this.texto_boton = "Actualizar"
                this.getListadoSeguimiento();

            }).catch(error => {
                alert(response.data);
            });
        },

        /// EDITAR UN SEGUIMIENTO
        editarFormularioSeguimiento: function (dato) {
            this.seguimientoData = dato;
        },

        //// LIMPIAR FORMULARIO SEGUIMIENTO
        limpiarFormularioSeguimiento: function () {
            this.seguimientoData = {}
        },

        //// CLIENTES  | CREAR O EDITAR 
        crearCliente: function () {
            var url = base_url + 'clientes/cargar_cliente'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Datos: this.clienteDatos
            }).then(response => {

                toastr.success('Proceso realizado correctamente', 'Usuarios')

                this.clienteDatos.Id = response.data.Id;
                this.texto_boton = "Actualizar"

            }).catch(error => {
                console.log(error.response); //alert("mal");
                console.log(error)
                toastr.success('Proceso realizado correctamente', 'Usuarios')
            });
        },

        //// VENTAS |  MOSTRAR LISTADO DE ORDENES
        getListadoVentas: function (Vendedor_id, Empresa_id, Cliente_id, Estado, Planificacion_id) {
            var url = base_url + 'ventas/obtener_listado_ventas?Empresa_id=' + Empresa_id + '&Vendedor_id=' + Vendedor_id + '&Cliente_id=' + Cliente_id + '&Estado=' + Estado + '&Planificacion_id=' + Planificacion_id; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaVentas = response.data
            }).catch(error => {
                console.log(error.response); //alert("mal");
                console.log(error.response.data)
            });
        },

        //// FORMATOS   | FORMATO Fecha Hora
        formatoFecha_hora: function (fecha) {
            //separador = ":",
            fecha = fecha.split(' ');

            //var fecha_dia = fecha[0].replace(/^(\d{4})-(\d{2})-(\d{2})$/g, '$3/$2/$1');
            var fecha_dia = fecha[0].replace(/^(\d{4})-(\d{2})-(\d{2})$/g, '$3/$2/$1');

            var fecha_hora = fecha[1].split(':');
            fecha_hora = fecha_hora[0] + ':' + fecha_hora[1];

            return fecha_dia + ' ' + fecha_hora + 'hs '

        },

        //// FORMATO FECHA
        formatoFecha: function (fecha) {
            if (fecha == null) {
                return 'No definida'
            }
            else {
                return fecha.replace(/^(\d{4})-(\d{2})-(\d{2})$/g, '$3/$2/$1');
            }
        },

        //// VENTAS  | CANTIDAD DE DIAS ENTRE DOS FECHAS
        diferenciasEntre_fechas: function (fechaInicio, fechaFin) {

            if (fechaFin == null) { fechaFin = hoy_php }
            if (fechaInicio == null) { fechaInicio = hoy_php }

            var fechaInicio = new Date(fechaInicio).getTime();
            var fechaFin = new Date(fechaFin).getTime();
            var diff = fechaFin - fechaInicio;

            /// debe devolver mensajes completos
            diff = diff / (1000 * 60 * 60 * 24)

            if (diff < 0) {
                diff = diff * parseInt(-1)
                return diff + ' días de atrazo'
            }
            else {
                return diff + ' días'
            }
        },
    },

    ////// ACCIONES COMPUTADAS     
    computed:
    {

    }
});


///         --------------------------------------------------------------------   ////
//// Elemento para el manejo de PROVEEDORES por Id
new Vue({
    el: '#proveedores',

    created: function () {
        this.getDatosProveedores();
        this.getListadoSeguimiento();
        this.obtener_listado_de_categorias_asignadas();
        this.obtener_listado_de_compras();
    },

    data: {
        mostrar: "1",
        proveedoresDatos: { 'Id': '', 'Nombre_proveedor': '', 'Producto_servicio': '', 'Imagen': '', 'Direccion': '', 'Localidad': '', 'Provincia': '', 'Pais': '', 'Telefono': '', 'Email': '', 'Web': '', 'Nombre_persona_contacto': '', 'Datos_persona_contacto': '', 'Mas_datos_proveedor': '' },
        listaSeguimiento: [],
        texto_boton: "Cargar",
        seguimientoData: {},
        listaProductos: [],
        Archivo: null,
        preloader: '0',
        comentarioDatos: {},

        lista_categoria_productos_vinculados: [],
        lista_categoria_productos_no_vinculados: [],

        listaComprasProveedor: []
    },

    methods:
    {

        //// MOSTRAR LISTADO
        getListadoSeguimiento: function () {
            var url = base_url + 'proveedores/obtener_seguimientos/?Id=' + Get_Id; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaSeguimiento = response.data
            });
        },

        //// MOSTRAR LISTADO
        obtener_listado_de_compras: function () {
            var url = base_url + 'compras/obtener_compras_proveedor/?Id=' + Get_Id; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaComprasProveedor = response.data;
                //console.log(response.data);
            }).catch(error => {
                
                console.log(error.response.data);
            });
        },

        //// OBTENER DATOS DE UN CLIENTE
        getDatosProveedores: function () {
            var url = base_url + 'proveedores/obtener_datos_proveedor/?Id=' + Get_Id;  //// averiguar como tomar el Id que viene por URL aca

            axios.post(url, {
                token: token
            }).then(response => {
                this.proveedoresDatos = response.data[0]
            });
        },

        //// DATOS SOBRE EL ARCHIVO SELECCIONADO EN CASO QUE QUIERA CARGAR ALGUNA ARCHIVO
        archivoSeleccionado(event) {
            this.Archivo = event.target.files[0]
            //this.texto_boton = "Actualizar"
        },

        //// CREAR O EDITAR una SEGUIMIENTO
        crearSeguimiento: function () {
            var url = base_url + 'proveedores/cargar_seguimiento'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Datos: this.seguimientoData, Id_proveedor: Get_Id
            }).then(response => {

                this.seguimientoData.Id = response.data.Id;

                /// si eso se ralizó bien, debe comprobar si hay un archivo a cargar.
                if (this.Archivo != null) {
                    var url = base_url + 'proveedores/subirFotoSeguimiento/?Id=' + this.seguimientoData.Id;
                    this.preloader = 1;

                    //const formData = event.target.files[0];
                    const formData = new FormData();
                    formData.append("Archivo", this.Archivo);

                    formData.append('_method', 'PUT');

                    //Enviamos la petición
                    axios.post(url, formData)
                        .then(response => {

                            this.seguimientoData.Url_archivo = response.data.Url_archivo;

                            toastr.success('El archivo se cargo correctamente', 'Proveedores')
                            this.preloader = 0;
                            this.getListadoSeguimiento();

                        }).catch(error => {
                            alert("MAL LA CARGA EN FUNCIÓN DE CARGAR ARCHIVO");
                            this.preloader = 0;
                            //this.seguimientoData.Url_archivo = response.data.Url_archivo;
                        });
                }
                // si lo hay lo carga, si no lo hay no hace nada

                this.getListadoSeguimiento();
                this.Archivo = null
                this.texto_boton = "Actualizar"
                toastr.success('Datos actualizados correctamente', 'Proveedores')

            }).catch(error => {
                alert("MAL LA CARGA EN FUNCIÓN DE CARGAR DATOS");
            });
        },

        /// EDITAR UN SEGUIMIENTO
        editarFormularioSeguimiento: function (dato) {
            this.seguimientoData = dato;
        },

        //// LIMPIAR FORMULARIO SEGUIMIENTO
        limpiarFormularioSeguimiento: function () {
            this.seguimientoData = {}
        },

        //// PROVEEDOR  | CREAR O EDITAR 
        crearProveedor: function () {
            var url = base_url + 'proveedores/cargar_proveedor'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Datos: this.proveedoresDatos
            }).then(response => {

                toastr.success('Proceso realizado correctamente', 'Proveedores')

                this.proveedoresDatos.Id = response.data.Id;
                this.texto_boton = "Actualizar"

            }).catch(error => {
                console.log(error.response); //alert("mal");
                console.log(error)
                toastr.success('Proceso realizado correctamente', 'Proveedores')
            });
        },

        //// OBTENER PRODUCTOS QUE OFRECE ESTE PROVEEDOR
        getProductos: function () {
            var url = base_url + 'proveedores/obtener_productos/?Id=' + Get_Id;  //// averiguar como tomar el Id que viene por URL aca

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaProductos = response.data
            });
        },

        //// VINCULO CATEGORÍA STOCK PROVEEDOR |  Vincular proveedor a producto del stock
        Vincular_categoria_productos_proveedor: function (Categoria_id) {
            var url = base_url + 'proveedores/Vincular_categoria_productos_proveedor'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Categoria_id: Categoria_id, Proveedor_id: Get_Id
            }).then(response => {

                toastr.success('Datos actualizados correctamente', 'Proveedor')

                this.obtener_listado_de_categorias_no_asignados(); // actualizar listado no vinculados
                this.obtener_listado_de_categorias_asignadas(); // actualizar listado vinculados

            }).catch(error => {
                alert(response.data);
            });
        },

        //// VINCULO CATEGORÍA STOCK PROVEEDOR |  Desvincular proveedor a producto del stock
        Desvincular_categoria_productos_proveedor: function (Categoria_id) {
            var url = base_url + 'proveedores/Desvincular_categoria_productos_proveedor'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Categoria_id: Categoria_id, Proveedor_id: Get_Id
            }).then(response => {

                toastr.success('Datos actualizados correctamente', 'Clientes')

                this.obtener_listado_de_categorias_asignadas(); // actualizar listado  no vinculados
                this.obtener_listado_de_categorias_no_asignados(); // actualizar listado  vinculados

            }).catch(error => {
                alert(response.data);
            });
        },

        //// VINCULO CATEGORÍA STOCK PROVEEDOR |  OBTENER LISTADO  VINCULADOS
        obtener_listado_de_categorias_asignadas: function () {
            var url = base_url + 'proveedores/obtener_listado_de_categorias_asignadas/?Id=' + Get_Id;  //// averiguar como tomar el Id que viene por URL aca

            axios.post(url, {
                token: token
            }).then(response => {
                this.lista_categoria_productos_vinculados = response.data
            });
        },

        //// VINCULO CATEGORÍA STOCK PROVEEDOR |  OBTENER LISTADO  NO VINCULADOS
        obtener_listado_de_categorias_no_asignados: function () {
            var url = base_url + 'proveedores/obtener_listado_de_categorias_no_asignadas/?Id=' + Get_Id;  //// averiguar como tomar el Id que viene por URL aca

            axios.post(url, {
                token: token
            }).then(response => {
                this.lista_categoria_productos_no_vinculados = response.data
            });
        },

        /// VINCULO CATEGORÍA STOCK PROVEEDOR | EDITAR UN COMENTARIO DE UN PRODUCTO
        editarFormularioComentario: function (dato) {
            this.comentarioDatos = dato;
        },

        /// VINCULO CATEGORÍA STOCK PROVEEDOR |  ACTUALIZAR COMENTARIOS SOBRE EL PRODUCTO
        actualizarComentario: function () {
            var url = base_url + 'proveedores/actualizarComentario'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Data: this.comentarioDatos
            }).then(response => {

                toastr.success('Proceso realizado correctamente', 'Proveedores')

                this.texto_boton = "Actualizar"

                this.obtener_listado_de_categorias_asignadas();

            }).catch(error => {
                console.log(error.response); //alert("mal");
                console.log(error)

            });
        },

        //// FORMATO FECHA
        formatoFecha: function (fecha) {
            if (fecha == null) {
                return 'No definida'
            }
            else {
                return fecha.replace(/^(\d{4})-(\d{2})-(\d{2})$/g, '$3/$2/$1');
            }

        },
    },

    ////// ACCIONES COMPUTADAS     
    computed:
    {

    }
});


///         --------------------------------------------------------------------   ////
//// Elemento para el manejo de VENTAS por Id
new Vue({
    el: '#ventas',

    created: function () {
        if (pathname == carpeta + 'ventas/produccion') {
            this.Set_valores_usuario();
            this.getListadoProductosEstado_1();
            this.getListadoProductosEstado_2();
            this.getListadoProductosEstado_3();
            this.getListadoProductosEstado_4();
            this.getListadoProductosEstado_5();
            this.getListadoProductosEstado_6();
            this.getListadoProductosEstado_7();
            this.getListadoProductosEstado_8();
            this.getListadoPlanificaciones();
            this.setFichaPrimera();
        }
        else {
            this.getDatosventas();
            this.getListadoUsuarios(1);
            this.getListadoClientes();
            this.getListadoSeguimiento(0, 11); // de aca seteo cual solapa se va a ver primero
            this.getListaUsados();
            this.getListadoEmpresas();
            this.getListadoProductos(0, 0);
            this.getListadoProductosVendidos();
            this.getListadoVentas(0, 0, 0, 1, 0);
            this.getListadoPlanificaciones();
            this.Set_valores_usuario();
            this.getListadoResumenProductos();
            this.getMovimientos();
            this.getListadoProductosReventa(); // 6 para marcar la categoria a la que pertenecen los productos de reventa
            this.getListadoProductosReventaLote(); // 6 para marcar la categoria a la que pertenecen los productos de reventa
        }
    },

    data: {
        mostrar: "11",
        ventaDatos: {},
        buscar: '',

        Rol_acceso: '',
        Usuario_id: '',

        listaSeguimiento: [],
        texto_boton: "Cargar",
        seguimientoData: {},

        listaProductos: [],
        listaUsuarios: [],
        listaClientes: [],
        listaEmpresas: [],
        hoy: '',
        fecha_boton: null,
        listaproductosUsados: [],
        listaPlanificaciones: [],

        Archivo: null,
        preloader: '0',

        cookies: [],

        filtro_empresa: '0',
        filtro_planificacion: '0',

        listaProductos: [],
        productoData: {
            'Cantidad': 1,
            'Precio_venta_producto': 0,
            'Fecha_ultima_mod': null
        },

        listaProductosVendidos: [],
        productoPasoData: { 'Estado': '', 'Producto_id': '', 'Comentarios': 'Sin comentarios' },

        productoAnulado: {},
        infoModal: { 'Requerimentos': '', 'Observaciones': '' },
        listaVentas: [],
        productoAsignado: { 'Id': '', 'Venta_id': '' },
        precioVentaTotal: 0,

        listaEtapa_1: [],
        listaEtapa_2: [],
        listaEtapa_3: [],
        listaEtapa_4: [],
        listaEtapa_5: [],
        listaEtapa_6: [],
        listaEtapa_7: [],
        listaEtapa_8: [],

        // COBRANZA
        listaResumenProductos: [],
        listaMovimientos: [],
        movimientoDatos: {},

        // PRODUCTOS DE REVENTA
        egresoDato: {
            "Fecha_ultima_mod": null,
            "Cantidad": 1,
            "Precio_venta_producto": 0
        },
        listaProductosReventa: [],
        listaProductosReventaLote: [],
        tipoMovimiento: 0,

        boton_habilitado : 1,

    },

    methods:
    {
        //// DATOS SOBRE EL ARCHIVO SELECCIONADO EN CASO QUE QUIERA CARGAR ALGUNA ARCHIVO
        Set_valores_usuario: function () {

            this.Rol_acceso = Rol_acceso;
            this.Usuario_id = Usuario_id;

        },

        //// MOSTRAR LISTADO DE Usuarios  
        getListadoUsuarios: function (estado) {
            var url = base_url + 'usuarios/obtener_Usuarios/?estado=' + estado + '&empresa=' + this.filtro_empresa + '&puesto=' + this.filtro_puesto; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaUsuarios = response.data
            });
        },

        //// MOSTRAR LISTADO DE EMPRESAS  
        getListadoEmpresas: function () {
            var url = base_url + 'usuarios/obtener_empresas'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaEmpresas = response.data
            });
        },

        //// CLIENTES  | MOSTRAR LISTADO
        getListadoClientes: function (estado) {
            var url = base_url + 'clientes/obtener_clientes'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaClientes = response.data
            });
        },

        //// OBTENER DATOS DE UN CLIENTE
        getDatosventas: function () {
            var url = base_url + 'ventas/obtener_datos_venta/?Id=' + Get_Id;  //// averiguar como tomar el Id que viene por URL aca

            /* console.log(document.cookie) */

            axios.post(url, {
                token: token
            }).then(response => {
                this.ventaDatos = response.data[0];
                //console.log(this.ventaDatos);
            });
        },

        //// VENTAS | CREAR O EDITAR ITEM
        crearVenta: function () {
            var url = base_url + 'ventas/cargar_venta'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Data: this.ventaDatos
            }).then(response => {

                toastr.success('Proceso realizado correctamente', 'Ventas')

                this.ventaDatos.Id = response.data.Id;
                this.texto_boton = "Actualizar"

                this.getDatosventas();

            }).catch(error => {
                console.log(error.response); //alert("mal");
                console.log(error.response.data)
            });
        },

        //// MOSTRAR LISTADO
        getListadoSeguimiento: function (Categoria_seguimiento, mostrar) {
            var url = base_url + 'ventas/obtener_seguimientos/?Id=' + Get_Id + '&Categoria_seguimiento=' + Categoria_seguimiento; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaSeguimiento = response.data
                this.mostrar = mostrar
            });
        },

        //// DATOS SOBRE EL ARCHIVO SELECCIONADO EN CASO QUE QUIERA CARGAR ALGUNA ARCHIVO
        archivoSeleccionado(event) {
            this.Archivo = event.target.files[0]
            //this.texto_boton = "Actualizar"
        },

        //// CREAR O EDITAR una SEGUIMIENTO
        crearSeguimiento: function (categoria, categoria_a_listar) {
            var url = base_url + 'ventas/cargar_seguimiento'; // url donde voy a mandar los datos
            this.boton_habilitado = 0;

            axios.post(url, {
                token: token,
                Datos: this.seguimientoData,
                Venta_id: Get_Id,
                Categoria_seguimiento: categoria,
            }).then(response => {

                this.seguimientoData.Id = response.data.Id;


                /// si eso se ralizó bien, debe comprobar si hay un archivo a cargar.
                if (this.Archivo != null) {
                    var url = base_url + 'ventas/subirFotoSeguimiento/?Id=' + this.seguimientoData.Id;
                    this.preloader = 1;

                    //const formData = event.target.files[0];
                    const formData = new FormData();
                    formData.append("Archivo", this.Archivo);

                    formData.append('_method', 'PUT');

                    //Enviamos la petición
                    axios.post(url, formData)
                        .then(response => {

                            this.seguimientoData.Url_archivo = response.data.Url_archivo;

                            toastr.success('El archivo se cargo correctamente', 'Proveedores')
                            this.preloader = 0;
                            this.getListadoSeguimiento(categoria_a_listar, this.mostrar);

                        }).catch(error => {
                            alert("MAL LA CARGA EN FUNCIÓN DE CARGAR ARCHIVO");
                            this.preloader = 0;
                            //this.seguimientoData.Url_archivo = response.data.Url_archivo;
                        });
                }
                // si lo hay lo carga, si no lo hay no hace nada


                this.getListadoSeguimiento(categoria_a_listar, this.mostrar);

                this.texto_boton = "Actualizar"
                toastr.success('Datos actualizados correctamente', 'Ventas')

                this.seguimientoData = {}; // RESETEO LA VARIABLE
                this.boton_habilitado = 1;

            }).catch(error => {
                alert("MAL LA CARGA EN FUNCIÓN DE CARGAR DATOS");
                console.log(error.response.data);
                this.boton_habilitado = 1;
            });
        },

        /// EDITAR UN SEGUIMIENTO
        editarFormularioSeguimiento: function (dato) {
            this.seguimientoData = dato;
        },

        //// LIMPIAR FORMULARIO SEGUIMIENTO
        limpiarFormularioSeguimiento: function () {
            this.seguimientoData = {}
        },

        //// MOSTRAR LISTADO DE PRODUCTOS COMPRADOS
        getListaUsados: function () {
            var url = base_url + 'stock/obtener_movimientos_v2/?Id=' + Get_Id; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Tipo_movimiento: 2
            }).then(response => {
                this.listaproductosUsados = response.data
            });
        },

        //// FABRICACION |  MOSTRAR LISTADO DE CATEGORIAS
        getListadoProductos: function (categoria, empresa) {
            var url = base_url + 'fabricacion/obtener_listado_de_productos_ventas'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaProductos = response.data;
                //console.log(this.listaProductos);
            });
        },


        /// CANTIDAD DE DIAS ENTRE DOS FECHAS       
        diferenciasEntre_fechas: function (fechaInicio, fechaFin) {

            if (fechaFin == null) { fechaFin = hoy_php }
            if (fechaInicio == null) { fechaInicio = hoy_php }

            var fechaInicio = new Date(fechaInicio).getTime();
            var fechaFin = new Date(fechaFin).getTime();
            var diff = fechaFin - fechaInicio;

            /// debe devolver mensajes completos
            diff = diff / (1000 * 60 * 60 * 24)

            /* if(diff < 0) 
            {
                diff = diff * parseInt(-1)
                return diff + ' días de atrazo'
            }
            else 
            {
                return diff + ' días'
            } */

            return diff
        },

        //// VENTAS | CARGAR PRODUCTO A LA VENTA
        agregarProducto: function () {
            var url = base_url + 'ventas/agregarProducto/?Id=' + Get_Id; // url donde voy a mandar los datos
            this.boton_habilitado = 0;

            if (!this.productoData.Precio_venta_producto) {
                this.productoData.Precio_venta_producto = 0;
            }
            // Seteo este dato para evitar la falla en la base datos que pide este dato obligatorio

            axios.post(url, {
                token: token,
                Datos: this.productoData
            }).then(response => {

                toastr.success('Proceso realizado correctamente', 'Ventas')

                this.productoData.Id = response.data.Id;
                this.texto_boton = "Actualizar"

                this.getListadoProductosVendidos();
                this.getListadoResumenProductos();

                this.boton_habilitado = 1;

            }).catch(error => {
                
                this.boton_habilitado = 1;
                console.log(error.response.data)
            });
        },

        //// VENTAS |  LISTADO DE PRODUCTOS VENDIDOS
        getListadoProductosVendidos: function () {
            var url = base_url + 'ventas/obtener_listado_de_productos_vendidos/?Id=' + Get_Id; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaProductosVendidos = response.data;
                //console.log(this.listaProductosVendidos)

                /// Realizar suma del costo de todos los productos vendidos
                this.precioVentaTotal = 0;
                for (let index = 0; index < this.listaProductosVendidos.length; index++) {
                    /* console.log(this.listaProductosVendidos[index].Precio_USD) */

                    var precioVenta = parseInt(this.listaProductosVendidos[index].Precio_venta);
                    var cantidad = parseInt(this.listaProductosVendidos[index].Cantidad);

                    if (precioVenta == null) { precioVenta = 0 }

                    this.precioVentaTotal = this.precioVentaTotal + (precioVenta * cantidad);
                }
            });
        },

        /// VENTA | Mostrando el precio guardado del producto, de la lista de propios
        actualizarPrecioProducto: function (Producto_id) {

            var precio;
            var actualizado;

            this.listaProductos.map(
                function (producto) {
                    if (producto.Id === Producto_id) {

                        if (producto.Precio_venta != null) {

                            precio = producto.Precio_venta;
                            actualizado = producto.Fecha_ultima_mod;

                        }
                    }
                }
            )
            this.productoData.Precio_venta_producto = precio;
            this.productoData.Fecha_ultima_mod = actualizado;

        },

        /// PASO A PASO PRODUCTO | Abrir modal
        editarPasoProducto: function (Producto_id, Estado) {
            this.productoPasoData.Estado = Estado;
            this.productoPasoData.Comentarios = 'Sin comentarios';
            this.productoPasoData.Producto_id = Producto_id;
            this.productoPasoData.Fecha = hoy_php;
        },

        /// PRODUCCION | Setear la primer ficha
        setFichaPrimera: function () {
            this.mostrar = 1;
        },

        //// VENTAS | CAMBIAR ESTADO DE UN PRODUCTO
        cambiarEstadoProducto: function () {
            var url = base_url + 'ventas/cambiarEstadoProducto'; // url donde voy a mandar los datos
            this.boton_habilitado = 0;

            axios.post(url, {
                token: token,
                Datos: this.productoPasoData
            }).then(response => {

                toastr.success('Proceso realizado correctamente', 'Ventas')
                this.texto_boton = "Actualizar"


                if (pathname == carpeta + 'ventas/produccion') {
                    this.getListadoProductosEstado_1();
                    this.getListadoProductosEstado_2();
                    this.getListadoProductosEstado_3();
                    this.getListadoProductosEstado_4();
                    this.getListadoProductosEstado_5();
                    this.getListadoProductosEstado_6();
                    this.getListadoProductosEstado_7();
                    this.getListadoProductosEstado_8();
                    
                }
                else {
                    this.getListadoProductosVendidos();
                    this.getListadoResumenProductos();
                }

                this.boton_habilitado = 1;

            }).catch(error => {
                console.log(error.response.data)
                this.boton_habilitado = 1;
            });
        },

        //// VENTAS | CAMBIAR ESTADO DE LA VENTA
        cambiar_estado_venta: function (monto_cobrado) {
            var url = base_url + 'ventas/cambiar_estado_venta'; // url donde voy a mandar los datos
            this.boton_habilitado = 0;

            var opcion = confirm("¿Esta seguro que quiere avanzar el lote a la siguiente estación?");
            if (opcion == true) {
                axios.post(url, {
                    token: token,
                    Venta_id: Get_Id,
                    Estado: this.ventaDatos.Estado,
                    Monto_cobrado: monto_cobrado
                }).then(response => {

                    toastr.success('Proceso realizado correctamente', 'Ventas')
                    //this.texto_boton = "Actualizar"

                    this.getDatosventas();
                    this.boton_habilitado = 1;

                }).catch(error => {
                    console.log(error.response.data)
                    this.boton_habilitado = 1;
                });
            }
        },

        /// EDITAR UN PRODUCTO AGREGADO
        editarFormularioProductos: function (dato) {
            this.productoData = dato;
        },

        /// EDITAR UN PRODUCTO AGREGADO
        editarAnularProducto: function (producto) {
            this.productoAnulado = producto;
            this.productoAnulado.Fecha = hoy_php;
            this.productoAnulado.Cantidad_anulada = producto.Cantidad;
            //console.log(this.productoAnulado);
        },

        //// VENTAS | CAMBIAR ESTADO DE LA VENTA
        anularProducto: function () {
            var url = base_url + 'ventas/anular_producto'; // url donde voy a mandar los datos
            this.boton_habilitado = 0;

            var opcion = confirm("¿Esta seguro que quiere anular este producto?");
            if (opcion == true) {
                axios.post(url, {
                    token: token,
                    Datos: this.productoAnulado
                }).then(response => {

                    toastr.success('Proceso realizado correctamente', 'Ventas')
                    this.texto_boton = "Actualizar"

                    this.getListadoProductosVendidos();
                    this.boton_habilitado = 1;

                }).catch(error => {
                    console.log(error.response.data)
                    this.boton_habilitado = 1;
                });
            }
        },

        //// VENTAS  | Eliminar
        desactivarProductoVenta: function (Id) {
            var url = base_url + 'ventas/eliminar_producto'; // url donde voy a mandar los datos

            var opcion = confirm("¿Esta seguro de eliminar a este producto?");
            if (opcion == true) {
                axios.post(url, {
                    token: token,
                    Id: Id
                }).then(response => {

                    toastr.success('Proceso realizado correctamente', 'Proveedores')

                    this.getListadoProductosVendidos(0);

                }).catch(error => {
                    console.log(error.response); //alert("mal");
                    console.log(error)

                });
            }
        },

        /// INFO PARA MODAL 
        infoEtapa: function (requerimentos, observaciones) {
            this.infoModal.Requerimentos = requerimentos;
            this.infoModal.Observaciones = observaciones;
        },

        //// VENTAS |  MOSTRAR LISTADO DE VENTAS
        getListadoVentas: function (Usuario_id, Empresa_id, Cliente_id, Estado, Planificacion_id) {
            var url = base_url + 'ventas/obtener_listado_ventas?Empresa_id=' + Empresa_id + '&Vendedor_id=' + Usuario_id + '&Cliente_id=' + Cliente_id + '&Estado=' + Estado + '&Planificacion_id=' + Planificacion_id; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaVentas = response.data;
                //console.log(response.data);

            }).catch(error => {
                console.log(error.response); //alert("mal");
                console.log(error.response.data)

            });
        },

        /// EDITAR MODAL ASIGNAR PRODUCTO VENTA
        editAsignarProductoVenta: function (productoFabricado) {
            this.productoAsignado = productoFabricado;
            this.productoAsignado.Cantidad_nueva = productoFabricado.Cantidad;
            //this.productoAsignado.Venta_id = productoFabricado.Id;

            //console.log(this.productoAsignado);
        },

        //// VENTAS | ASIGNAR UNPRODUCTO A UNA VENTA
        reasignar_producto: function () {
            var url = base_url + 'fabricacion/reasignar_producto'; // url donde voy a mandar los datos

            this.boton_habilitado = 0;
            var opcion = confirm("¿Esta seguro que quiere reasignar este producto?");
            if (opcion == true) {
                axios.post(url, {
                    token: token,
                    Datos: this.productoAsignado
                }).then(response => {

                    toastr.success('Proceso realizado correctamente', 'Ventas')
                    this.texto_boton = "Actualizar"

                    this.getListadoProductosVendidos();
                    this.boton_habilitado = 1;

                }).catch(error => {
                    console.log(error.response.data)
                    this.boton_habilitado = 1;
                });
            }
        },

        ///  PRODUCCIÓN | Mostrar todos los listados
        mostrarListadosProduccion: function () {
            this.getListadoProductosEstado_1();
            this.getListadoProductosEstado_2();
            this.getListadoProductosEstado_3();
            this.getListadoProductosEstado_4();
            this.getListadoProductosEstado_5();
            this.getListadoProductosEstado_6();
            this.getListadoProductosEstado_7();
            this.getListadoProductosEstado_8();
        },

        //// PRODUCCION  | MOSTRAR LISTADO Estado 1
        getListadoProductosEstado_1: function () {
            var url = base_url + 'ventas/obtener_productos_a_fabricar?Planificacion_id=' + this.filtro_planificacion; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Estado: 2
            }).then(response => {
                this.listaEtapa_1 = response.data;
                //this.mostrar = 1;
            });
        },

        //// PRODUCCION  | MOSTRAR LISTADO Estado 1
        getListadoProductosEstado_2: function () {
            var url = base_url + 'ventas/obtener_productos_a_fabricar?Planificacion_id=' + this.filtro_planificacion; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Estado: 3
            }).then(response => {
                this.listaEtapa_2 = response.data
            });
        },

        //// PRODUCCION  | MOSTRAR LISTADO Estado 1
        getListadoProductosEstado_3: function () {
            var url = base_url + 'ventas/obtener_productos_a_fabricar?Planificacion_id=' + this.filtro_planificacion; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Estado: 4
            }).then(response => {
                this.listaEtapa_3 = response.data
            });
        },

        //// PRODUCCION  | MOSTRAR LISTADO Estado 1
        getListadoProductosEstado_4: function () {
            var url = base_url + 'ventas/obtener_productos_a_fabricar?Planificacion_id=' + this.filtro_planificacion; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Estado: 5
            }).then(response => {
                this.listaEtapa_4 = response.data
            });
        },

        //// PRODUCCION  | MOSTRAR LISTADO Estado 1
        getListadoProductosEstado_5: function () {
            var url = base_url + 'ventas/obtener_productos_a_fabricar?Planificacion_id=' + this.filtro_planificacion; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Estado: 6
            }).then(response => {
                this.listaEtapa_5 = response.data
            });
        },

        //// PRODUCCION  | MOSTRAR LISTADO Estado 1
        getListadoProductosEstado_6: function () {
            var url = base_url + 'ventas/obtener_productos_a_fabricar?Planificacion_id=' + this.filtro_planificacion; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Estado: 7
            }).then(response => {
                this.listaEtapa_6 = response.data
            });
        },

        //// PRODUCCION  | MOSTRAR LISTADO Estado 1
        getListadoProductosEstado_7: function () {
            var url = base_url + 'ventas/obtener_productos_a_fabricar?Planificacion_id=' + this.filtro_planificacion; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Estado: 8
            }).then(response => {
                this.listaEtapa_7 = response.data
            });
        },


        //// PRODUCCION  | MOSTRAR LISTADO Estado 1
        getListadoProductosEstado_8: function () {
            var url = base_url + 'ventas/obtener_productos_a_fabricar?Planificacion_id=' + this.filtro_planificacion; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Estado: 9
            }).then(response => {
                this.listaEtapa_8 = response.data;

                console.log(response.data);
            });
        },

        //// SUMAR PRODUCTOS   
        sumarProductos: function (items) {

            var Total = 0;

            if (items.length > 0) {
                for (var i = 0; i < items.length; i++) {
                    var item = 0;
                    var cantidad = 0;

                    if (isFinite(items[i].Precio_venta_producto)) {
                        item = parseInt(items[i].Precio_venta_producto);
                        cantidad = parseInt(items[i].Cantidad);

                    }

                    Total = Total + (item * cantidad);
                }
                return Total
            }
            else {
                return 0
            }

        },

        //// PLANIFICACIONES |  MOSTRAR LISTADO DE ORDENES
        getListadoPlanificaciones: function () {
            var url = base_url + 'ventas/obtener_listado_planificaciones'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaPlanificaciones = response.data
            }).catch(error => {
                console.log(error.response); //alert("mal");
                console.log(error.response.data)

            });
        },

        //// COBRANZAS |  MOSTRAR LISTADO DE ORDENES
        getListadoResumenProductos: function () {
            var url = base_url + 'ventas/obtener_listado_resumen_productos?Id=' + Get_Id; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaResumenProductos = response.data
            }).catch(error => {
                console.log(error.response); //alert("mal");
                console.log(error.response.data)

            });
        },

        //// FONDO  | MOSTRAR LISTADO MOVIMIENTOS
        getMovimientos: function () {
            var url = base_url + 'finanzas/obtener_movimientos'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Origen_movimiento: 'Ventas',
                Fila_movimiento: Get_Id
            }).then(response => {
                this.listaMovimientos = response.data
            });
        },


        //// PAGOS | CREAR O EDITAR EN EFECTIVO
        crear_movimiento: function () {
            var url = base_url + 'finanzas/cargar_movimiento'; // url donde voy a mandar los datos
            this.boton_habilitado = 0;

            axios.post(url, {
                token: token,
                Datos: this.movimientoDatos,
                Origen_movimiento: 'Ventas',
                Op: 1,
                Planificacion_id: this.ventaDatos.Planificacion_id,
                Fila_movimiento: Get_Id,

            }).then(response => {

                toastr.success('Proceso realizado correctamente', 'Fondo')

                this.texto_boton = "Actualizar"
                this.getMovimientos()
                this.boton_habilitado = 1;

            }).catch(error => {
                this.boton_habilitado = 1;
                console.log(error.response.data)
            });
        },

        //// VENTAS  | Dasactivar
        desactivarAlgo: function (Id, tabla) {
            var url = base_url + 'funcionescomunes/desvisualizar'; // url donde voy a mandar los datos

            var opcion = confirm("¿Esta seguro de eliminar a este dato?");
            if (opcion == true) {
                axios.post(url, {
                    token: token,
                    Id: Id,
                    tabla: tabla
                }).then(response => {

                    toastr.success('Proceso realizado correctamente', 'Ventas')

                    this.getMovimientos();
                    this.getListadoProductosReventaLote();

                }).catch(error => {
                    console.log(error.response); //alert("mal");
                    console.log(error)

                });
            }
        },


        //// VENTAS  | Eliminar Producto de reventa
        eliminarProductoReventa: function (Stock_id) {
            var url = base_url + 'stock/eliminarProductoReventa/?Venta_id=' + Get_Id; // url donde voy a mandar los datos

            var opcion = confirm("¿Esta seguro de eliminar a este producto?");
            if (opcion == true) {
                axios.post(url, {
                    token: token,
                    Stock_id: Stock_id

                }).then(response => {

                    toastr.success('Proceso realizado correctamente', 'Ventas')

                    this.getListadoProductosReventaLote();
                    console.log(response.data);

                }).catch(error => {
                    console.log(error.response); //alert("mal");
                    console.log(error)

                });
            }
        },

        //// SUMAR PRODUCTOS   
        calcularMontosVentas: function (PP, Logis, Inst, PR, Pagos, Rec, Desc) {


            var Total = parseInt(PP) + parseInt(Logis) + parseInt(Inst) + parseInt(PR) - parseInt(Pagos) + parseInt(Rec) - parseInt(Desc);

            return Total;
        },


        //// PRODUCTOS DE REVENTA |   MOSTRAR LISTADO
        getListadoProductosReventaLote: function () {
            var url = base_url + 'stock/obtener_movimientos_ventas_prod_reventa/?Id=' + Get_Id; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,

            }).then(response => {

                this.listaProductosReventaLote = response.data

                //console.log(this.listaProductosReventaLote);

            }).catch(error => {
                console.log(error.response); //alert("mal");
                console.log(error.response.data)
            });
        },

        //// PRODUCTOS DE REVENTA |  MOSTRAR LISTADO DE CATEGORIAS
        getListadoProductosReventa: function (categoria) {
            var url = base_url + 'stock/obtener_listado_de_stock?tipo=3&categoria=' + categoria; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaProductosReventa = response.data;
                //console.log(this.listaProductosReventa);
            });
        },


        //// PRODUCTOS DE REVENTA | Integrar a la venta y restar del stock
        movimientoStock_v2: function () {
            var url = base_url + 'stock/cargar_movimiento_stock'; // url donde voy a mandar los datos
            this.boton_habilitado = 0;

            axios.post(url, {
                token: token,
                Id: this.egresoDato.Producto_id,
                Modulo: 'Ventas',
                Cantidad: this.egresoDato.Cantidad,
                Descripcion: this.egresoDato.Descripcion_egreso,
                Proceso_id: Get_Id,
                Precio_venta_producto: this.egresoDato.Precio_venta_producto,
                Tipo_movimiento: this.tipoMovimiento
            }).then(response => {

                toastr.success('Proceso realizado correctamente', 'Stock')

                this.getListadoProductosReventaLote();
                this.boton_habilitado = 1;
            }).catch(error => {
                this.boton_habilitado = 1;
                console.log(error.response.data)

            });
        },

        //// PRODUCTOS DE REVENTA | EDITAR UN PRODUCTO AGREGADO
        editarFormularioProductosReventa: function (dato) {
            this.egresoDato = dato;
            this.egresoDato.Producto_id = dato.Stock_id;
            this.tipoMovimiento = 0;
            //this.egresoDato.Cantidad = 0;

            console.log(this.egresoDato);
        },


        //// LIMPIAR FORMULARIO SEGUIMIENTO
        limpiarFormularioProductos: function () {

            this.productoData = { 'Cantidad': 1, 'Precio_venta_producto': 0, 'Fecha_ultima_mod': null };
            this.egresoDato = { 'Cantidad': 1, 'Precio_venta_producto': 0, 'Fecha_ultima_mod': null };
            this.texto_boton = "Cargar";
            this.tipoMovimiento = 2;

            /* console.log('Egreso dato');
            console.log(this.egresoDato);
            console.log('Producto dato');
            console.log(this.productoData); */
        },


        //// LIMPIAR FORMULARIO MOVIMIENTOS
        limpiarFormularioMovimiento: function () {
            this.movimientoDatos = {};
        },


        /// VENTA | Mostrando el precio guardado del producto, de la lista de reventa
        actualizarPrecioProductoReventa: function (Producto_id) {

            var precio;
            var actualizado;

            this.listaProductosReventa.map(
                function (producto) {
                    if (producto.Id === Producto_id) {

                        //console.log(producto.Id);
                        //console.log(producto.Precio_venta);

                        if (producto.Precio_venta != null) {

                            precio = producto.Precio_venta;
                            actualizado = producto.Fecha_ultima_mod;

                        }
                    }
                    /* else{
                        console.log("no encontro coincidencia");
                    } */
                }
            )
            this.egresoDato.Precio_venta_producto = precio;
            this.egresoDato.Fecha_ultima_mod = actualizado;
            //console.log("precio");
            //console.log(precio);
        },

        //// PRODUCTOS DE REVENTA | Marcar como entregado
        prodReventaEntregado: function (Stock_id) {
            var url = base_url + 'stock/prodReventaEntregado'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Stock_id: Stock_id,
                Proceso_id: Get_Id
            }).then(response => {

                toastr.success('Proceso realizado correctamente', 'Stock')

                console.log(response.data);

                this.getListadoProductosReventaLote();

            }).catch(error => {
                console.log(error.response); //alert("mal");
                console.log(error)

            });
        },

        /// VENTAS  | ALERTA EN PRODUCCIONES ATRAZADAS
        classAlerta: function (valor) {

            if (valor < 0) {
                return 'text-danger'
            }
            else {
                return ''
            }
        },

        // FUNCIONES JAVASCRIPT COMUNES
        exportarExcel: function (tableID, filename = 'PX ERP') {

            var tableSelect = document.getElementById(tableID);
            var tablaHTML = tableSelect.outerHTML;

            //tablaHTML = 'Header(Content-type: application/vnd.msexcel; charset=iso-8859-15); Header(Content-Disposition: attachment; filename='+filename+'.xls);' + tablaHTML;

            var ua = window.navigator.userAgent;
            var msie = ua.indexOf("MSIE ");

            //other browser not tested on IE 11
            // If Internet Explorer
            if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)) {
                jQuery('body').append(" <iframe id=\"iframeExport\" style=\"display:none\"></iframe>");
                iframeExport.document.open("txt/html", "replace");
                iframeExport.document.write(tablaHTML);
                iframeExport.document.close();
                iframeExport.focus();
                sa = iframeExport.document.execCommand("SaveAs", true, "List.xls");
            }
            else {
                var link = document.createElement('a');

                document.body.appendChild(link); // Firefox requires the link to be in the body
                link.download = "List.xls";
                link.href = 'data:application/vnd.ms-excel,' + escape(tablaHTML);
                link.click();
                document.body.removeChild(link);
            }

            //window.location.reload();




            // Enviar estos datos a un php para que genere un excel
            /* 
            
            

                //let data = XLSX.utils.json_to_sheet(this.dataToExport)

                let data = tablaHTML;
                
                const workbook = XLSX.utils.book_new()
                //const filename = 'devschile-admins'
                XLSX.utils.book_append_sheet(workbook, data, filename)
                XLSX.writeFile(workbook, `${filename}.xlsx`) */


            /*   EL PROBLEMA ESTA QUE POR GET NO PUEDO MANDAR UNA TABLA - VER COMO MANDARLA DE OTRA MANERA   */

            /* var url = base_url + 'funcionescomunes/generarExcel?tablaHTML='+tablaHTML+'&nombreArchivo='+filename;
            
            window.location = url; */

            /*var url = base_url + 'funcionescomunes/generarExcel'; // url donde voy a mandar los datos

             axios.post(url, {
                token: token,
                tablaHTML: tablaHTML,
                nombreArchivo: filename

            }).then(response => {

                toastr.success('Proceso realizado correctamente', 'Descarga')

            }).catch(error => {
                console.log(error.response); //alert("mal");
                console.log(error.response.data)
            }); */

        }

    },

    ////// ACCIONES COMPUTADAS     
    computed:
    {
        
        buscarProducto: function () {
            return this.listaProductosVendidos.filter((item) => item.Nombre_producto.toLowerCase().includes(this.buscar.toLowerCase()));
        },
    }
});


///         --------------------------------------------------------------------   ////
//// Elemento para el manejo de FABRICACION por Id
new Vue({
    el: '#fabricacion',

    created: function () {

        this.getDatosId();
        this.getListadoArchivos();
        this.getListadoCategoriasProductos();
        this.getListadoVentas();
        this.getListadoStock();
        this.getListadoInsumos();
        this.getListadoEmpresas();
        this.getListadoTotalMateriaPrimaComprar();
        this.getListadoPlanillas();
        this.getListadoUsuarios(1);
        this.getListadoPlanificaciones();

    },

    data: {
        mostrar: "1",
        productoDatos: {
            'Id': '',
            'Codigo_interno': '',
            'Codigo_interno': '',
        },
        'Categoria_fabricacion_id': '',
        'Nombre_producto': '',
        'Imagen': '',
        'Descripcion_publica_corta': '',
        'Descripcion_publica_larga': '',
        'Descripcion_tecnica_privada': '',

        listaArchivos: [],
        texto_boton: "Cargar",
        archivoData: {},

        listaProductos: [],
        Archivo: null,
        preloader: '0',
        comentarioDatos: {},

        listaCategorias: [],
        listaVentas: [],
        filtro_vendedor: '0',
        filtro_empresa: '0',
        filtro_cliente: '0',
        filtro_estado: '0',

        insumoDatos: { 'Stock_id': 0, 'Cantidad': 0, 'Observaciones': '', },
        listaInsumos: [],
        listaMateriaPrima: [],
        listaEmpresas: [],

        multiplicadorProducto: 1,
        listaMateriaTotal: [],

        boton_habilitado : 1,

        productoFoto: '',
        filtro_planilla: 0,
        listaPlanillas: [],
        planillaDato: {},

        listaItemsPlanillas: [],
        planillaItemDato: {},
        nombrePlanillaElegida: "No hay ninguna planilla seleccionada",

        nombreOperario: "No se selecciono ningún operario",
        listaUsuarios: [],
        nombrePlanificacion: "No se selecciono ninguna planificación",
        listaPlanificaciones: [],
        fechaPlanilla: '',


    },

    methods:
    {
        //// MOSTRAR LISTADO DE EMPRESAS  
        getListadoEmpresas: function () {
            var url = base_url + 'usuarios/obtener_empresas'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaEmpresas = response.data
            });
        },

        //// MOSTRAR LISTADO
        getListadoArchivos: function () {
            var url = base_url + 'fabricacion/obtener_listado_archivos/?Id=' + Get_Id; // url donde voy a mandar los datos
            axios.post(url, {
                token: token,
            }).then(response => {
                this.listaArchivos = response.data
            });
        },

        //// OBTENER DATOS DEL ID
        getDatosId: function () {
            var url = base_url + 'fabricacion/obtener_datos_id/?Id=' + Get_Id;  //// averiguar como tomar el Id que viene por URL aca

            axios.post(url, {
                token: token
            }).then(response => {
                this.productoDatos = response.data[0]
            });
        },

        //// PRODUCTO  | CREAR O EDITAR 
        actualizarProducto: function () {
            var url = base_url + 'fabricacion/cargar_producto'; // url donde voy a mandar los datos
            this.boton_habilitado = 0;

            axios.post(url, {
                token: token,
                Data: this.productoDatos
            }).then(response => {

                toastr.success('Proceso realizado correctamente', 'Fabricación')

                this.productoDatos.Id = response.data.Id;
                this.texto_boton = "Actualizar"
                this.boton_habilitado = 1;

            }).catch(error => {
                console.log(error.response); //alert("mal");
                this.boton_habilitado = 1;
                //toastr.success('Proceso realizado correctamente', 'Proveedores')
            });
        },

        //// FABRICACION |     Carga el formulario Items para editar FOTO
        editarFormularioProductoFoto(item, campo) {
            this.productoFoto = {};
            this.productoFoto = item;
            this.productoFoto.Campo = campo;
            this.texto_boton = "Actualizar";
            console.log(this.productoFoto);
        },

        ///// FABRICACION |   CARGAR IMAGEN DE PRODUCTO DE FABRICACION
        uploadFotoProducto(Id, Campo) {
            //this.texto_boton = "Actualizar"
            var url = base_url + 'fabricacion/subirFotoProducto/?Id=' + Id + '&Campo=' + Campo; // url donde voy a mandar los datos
            this.preloader = 1; 
            //const formData = event.target.files[0];
            const formData = new FormData();
            formData.append("Archivo", this.Archivo);

            formData.append('_method', 'PUT');

            //Enviamos la petición
            axios.post(url, formData)
                .then(response => {

                    ////DEBO HACER FUNCIONAR BIEN ESTO PARA QUE SE ACTUALICE LA FOTO QUE CARGO EN EL MOMENTO, SI NO PARECE Q NO SE CARGARA NADA
                    this.getListadoInsumos();

                    this.productoFoto.Imagen = response.data.Imagen;
                    this.productoFoto.Ubicacion_pieza_url = response.data.Ubicacion_pieza_url;
                    this.productoFoto.Subproducto_url = response.data.Subproducto_url;
                    
                    toastr.success('Proceso realizado correctamente', 'Fabricación')

                    this.preloader = 0;
                }).catch(error => {
                    console.log(error.response); //alert("mal");
                    console.log(error)
                    this.preloader = 0;
                });
        },

        


        //// FABRICACION |  MOSTRAR LISTADO DE VENTAS DONDE SE VENDIO TAL PRODUCTO
        getListadoVentas: function () {

            ///
            var url = base_url + 'fabricacion/obtener_ventas_producto?Producto_id=' + Get_Id; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaVentas = response.data

                /* console.log(response.data) */

            }).catch(error => {
                console.log(error.response); //alert("mal")
                /* console.log(error.response.data) */

            });

            // La consulta aca debe cambiar bastante
        },

        //// FABRICACION |  MOSTRAR LISTADO DE CATEGORIAS
        getListadoCategoriasProductos: function () {
            var url = base_url + 'fabricacion/obtener_categorias'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaCategorias = response.data
            });
        },

        //// FORMATOS   | FORMATO Fecha Hora
        formatoFecha_hora: function (fecha) {
            //separador = ":",
            fecha = fecha.split(' ');

            //var fecha_dia = fecha[0].replace(/^(\d{4})-(\d{2})-(\d{2})$/g, '$3/$2/$1');
            var fecha_dia = fecha[0].replace(/^(\d{4})-(\d{2})-(\d{2})$/g, '$3/$2/$1');

            var fecha_hora = fecha[1].split(':');
            fecha_hora = fecha_hora[0] + ':' + fecha_hora[1];

            return fecha_dia + ' ' + fecha_hora + 'hs '

        },

        //// FORMATO FECHA
        formatoFecha: function (fecha) {
            if (fecha == null) {
                return 'No definida'
            }
            else {
                return fecha.replace(/^(\d{4})-(\d{2})-(\d{2})$/g, '$3/$2/$1');
            }
        },

        //// VENTAS  | CANTIDAD DE DIAS ENTRE DOS FECHAS
        diferenciasEntre_fechas: function (fechaInicio, fechaFin) {

            if (fechaFin == null) { fechaFin = hoy_php }
            if (fechaInicio == null) { fechaInicio = hoy_php }

            var fechaInicio = new Date(fechaInicio).getTime();
            var fechaFin = new Date(fechaFin).getTime();
            var diff = fechaFin - fechaInicio;

            /// debe devolver mensajes completos
            diff = diff / (1000 * 60 * 60 * 24)

            if (diff < 0) {
                diff = diff * parseInt(-1)
                return diff + ' días de atrazo'
            }
            else {
                return diff + ' días'
            }
        },

        //// DATOS SOBRE EL ARCHIVO SELECCIONADO EN CASO QUE QUIERA CARGAR ALGUNA ARCHIVO
        archivoSeleccionado(event) {
            //console.log(event);
            this.Archivo = event.target.files[0]
            //this.texto_boton = "Actualizar"
        },

        //// FABRICACIÓN | CREAR O EDITAR una SEGUIMIENTO
        cargarArchivo: function () {
            var url = base_url + 'fabricacion/cargar_archivo'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Datos: this.archivoData, Producto_id: Get_Id
            }).then(response => {

                this.archivoData.Id = response.data.Id;

                /// si eso se ralizó bien, debe comprobar si hay un archivo a cargar.
                if (this.Archivo != null) {
                    var url = base_url + 'fabricacion/subirArchivo/?Id=' + this.archivoData.Id;
                    this.preloader = 1;

                    //const formData = event.target.files[0];
                    const formData = new FormData();
                    formData.append("Archivo", this.Archivo);

                    formData.append('_method', 'PUT');

                    //Enviamos la petición
                    axios.post(url, formData)
                        .then(response => {

                            this.archivoData.Url_archivo = response.data.Url_archivo;

                            toastr.success('El archivo se cargo correctamente', 'fabricacion')
                            this.preloader = 0;
                            this.getListadoArchivos();

                        }).catch(error => {
                            alert("MAL LA CARGA EN FUNCIÓN DE CARGAR ARCHIVO");
                            this.preloader = 0;

                        });
                }
                // si lo hay lo carga, si no lo hay no hace nada

                this.getListadoArchivos();
                this.Archivo = null
                this.texto_boton = "Actualizar"
                toastr.success('Datos actualizados correctamente', 'fabricacion')

            }).catch(error => {
                alert("MAL LA CARGA EN FUNCIÓN DE CARGAR DATOS");
            });
        },

        /// EDITAR UN SEGUIMIENTO
        editarFormularioarchivo: function (dato) {
            this.archivoData = dato;
            console.log(dato);
        },

        //// LIMPIAR FORMULARIO SEGUIMIENTO
        limpiarFormularioArchivo: function () {
            this.archivoData = {}
        },


        //// INSUMOS FABRICACIÓN  | CREAR O EDITAR 
        cargarInsumo: function () {
            var url = base_url + 'fabricacion/cargar_insumo_producto?Fabricacion_id=' + Get_Id; // url donde voy a mandar los datos
            this.boton_habilitado = 0;

            axios.post(url, {
                token: token,
                Datos: this.insumoDatos
            }).then(response => {

                toastr.success('Proceso realizado correctamente', 'Compras')

                this.insumoDatos.Id = response.data.Id;
                this.texto_boton = "Actualizar"
                this.getListadoInsumos()
                this.boton_habilitado = 1;

            }).catch(error => {
                this.boton_habilitado = 1;
                console.log(error.response.data)
            });
        },

        //// INSUMOS FABRICACIÓN  | LISTADO DE INSUMOS 
        getListadoInsumos: function () {
            var url = base_url + 'fabricacion/obtener_listado_insumos/?Fabricacion_id=' + Get_Id; // url donde voy a mandar los datos
            axios.post(url, {
                token: token,
            }).then(response => {
                this.listaInsumos = response.data;
                //console.log(this.listaInsumos)
            });
        },

        //// INSUMOS FABRICACIÓN  | LISTADO COMPLETO DE STOCK
        getListadoStock: function () {
            var url = base_url + 'stock/obtener_listado_de_stock?categoria=0&tipo=2'; // url donde voy a mandar los datos
            axios.post(url, {
                token: token,
            }).then(response => {
                this.listaMateriaPrima = response.data;
                //console.log(this.listaStock);
            });
        },

        ///  INSUMOS FABRICACIÓN  | EDITAR
        editarFormularioInsumo: function (dato) {
            this.insumoDatos = dato;
        },

        ////  INSUMOS FABRICACIÓN  | LIMPIAR FORMULARIO 
        limpiarFormularioInsumo: function () {
            this.insumoDatos = {}
        },

        //// INSUMOS FABRICACIÓN  | LISTADO TOTAL DE INSUMOS PARA COMPRAR
        getListadoTotalMateriaPrimaComprar: function () {
            var url = base_url + 'fabricacion/obtener_lista_total_productos_comprar/?Fabricacion_id=' + Get_Id; // url donde voy a mandar los datos
            axios.post(url, {
                token: token,
            }).then(response => {
                this.listaMateriaTotal = response.data;
                //console.log(this.listaMateriaTotal)
            });
        },

        //// GENERAL  | Dasactivar
        desactivarAlgo: function (Id, tabla) {
            var url = base_url + 'funcionescomunes/desvisualizar'; // url donde voy a mandar los datos

            var opcion = confirm("¿Esta seguro de eliminar a este dato?");
            if (opcion == true) {
                axios.post(url, {
                    token: token,
                    Id: Id,
                    tabla: tabla
                }).then(response => {

                    toastr.success('Proceso realizado correctamente', 'Fabricación')

                    this.getListadoInsumos();
                    this.getListadoItemsPlanillas(this.filtro_planilla);

                }).catch(error => {
                    console.log(error.response); //alert("mal");
                    console.log(error)

                });
            }
        },

        //// PLANILLAS |  MOSTRAR LISTADO
        getListadoPlanillas: function () {

            var url = base_url + 'fabricacion/obtener_planillas'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaPlanillas = response.data
            });
        },

        //// PLANILLAS |  LIMPIAR EL FORMULARIO DE CREARS
        limpiarFormularioPlanilla() {
            this.planillaDato = {}
            this.texto_boton = "Cargar";
        },

        //// PLANILLAS |  Carga el formulario para editar
        editarFormularioPlanilla(planilla) {
            //this.usuario = {};
            this.planillaDato = planilla;
            this.texto_boton = "Actualizar";
        },

        //// PLANILLAS | CREAR O EDITAR
        crearPlanilla: function () {
            var url = base_url + 'fabricacion/cargar_planilla'; // url donde voy a mandar los datos
            this.boton_habilitado = 0;

            axios.post(url, {
                token: token,
                Data: this.planillaDato
            }).then(response => {

                toastr.success('Proceso realizado correctamente', 'stock')

                this.planillaDato.Id = response.data.Id;
                //this.texto_boton = "Actualizar"
                this.planillaDato = {}

                this.getListadoPlanillas();
                this.boton_habilitado = 1;
                this.planillaDato = {}
                this.texto_boton = "Cargar";

            }).catch(error => {
                console.log(error.response); //alert("mal");
                this.boton_habilitado = 1;

            });
        },


        //// PLANILLAS | VINCULO |  MOSTRAR LISTADO
        getListadoItemsPlanillas: function (planillaELegida) {

            var url = base_url + 'fabricacion/obtener_items_planillas?Planilla_id='+planillaELegida+'&Producto_id='+Get_Id; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaItemsPlanillas = response.data;
                this.nombrePlanillaElegida = response.data[0].Nombre_planilla;
                
            }).catch(error => {
                console.log(error.response); 
            });
        },

        //// PLANILLAS | VINCULO |  LIMPIAR EL FORMULARIO DE CREARS
        limpiarFormularioPlanillaVinculo() {
            this.planillaItemDato = {}
            this.texto_boton = "Cargar";
        },

        //// PLANILLAS | VINCULO |  Carga el formulario para editar
        editarFormularioPlanillaVinculo(planilla) {
            //this.usuario = {};
            this.planillaItemDato = planilla;
            this.texto_boton = "Actualizar";
            console.log(this.planillaItemDato);
        },

        //// PLANILLAS | VINCULO | CREAR O EDITAR
        vincularItemPlanilla: function () {
            var url = base_url + 'fabricacion/cargar_vinculo_planilla'; // url donde voy a mandar los datos
            this.boton_habilitado = 0;

            axios.post(url, {
                token: token,
                Data: this.planillaItemDato,
                Producto_id: Get_Id,
                Planilla_id: this.filtro_planilla

            }).then(response => {

                toastr.success('Proceso realizado correctamente', 'stock')

                this.planillaItemDato.Id = response.data.Id;
                //this.texto_boton = "Actualizar"
                this.planillaItemDato = {}

                this.getListadoItemsPlanillas(this.filtro_planilla);
                this.boton_habilitado = 1;
                this.planillaItemDato = {}
                this.texto_boton = "Cargar";

            }).catch(error => {
                console.log(error.response); //alert("mal");
                this.boton_habilitado = 1;

            });
        },

        //// MOSTRAR LISTADO DE Usuarios  
        getListadoUsuarios: function (estado) {
            var url = base_url + 'usuarios/obtener_Usuarios/?estado=' + estado + '&empresa=0&puesto=0'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaUsuarios = response.data
            });
        },

        //// PLANIFICACIONES |  MOSTRAR LISTADO DE ORDENES
        getListadoPlanificaciones: function () {
            var url = base_url + 'ventas/obtener_listado_planificaciones'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaPlanificaciones = response.data;

            }).catch(error => {
                
                console.log(error.response.data)

            });
        },


        ////////////////////////////-----------------
    },

    ////// ACCIONES COMPUTADAS     
    computed:
    {

    }
});


///         --------------------------------------------------------------------   ////
//// Elemento para el manejo de COMPRAS por Id
new Vue({
    el: '#compras',

    created: function () {
        this.getDatoscompra();
        this.getListaComprados();
        this.getListadoProveedores_select();
        this.getMovimientos();/* 
        this.getDatosTransferencia();
        this.getDatosCheques();
        this.getListadoCheques(1); */
        this.getListadoEmpresas();
        this.getListadoPlanificaciones();

    },

    data: {
        mostrar: "1",
        compraDatos: {},
        texto_boton: "Cargar",
        buscar: '',
        infoModal: {'Observaciones':''},

        listaProveedores_select: [],
        listaComprados: [],
        listaProductos: [],

        cantMovimientoStock: [],
        descripcionMovimiento: [],
        filtro_rubro: 0,
        movimientoDatos: {'Monto_bruto': 0},
        listaMovimientos: [],
        listaMovimientosTransferencia: [],
        listaMovimientosCheques: [],
        listaCheques: [],

        Total_cheques: 0,
        Total_efectivo: 0,
        Total_transferencias: 0,
        listaPeriodos: [],
        
        listaEmpresas: [],
        listaPlanificaciones: [],
        boton_habilitado: 1,

        seguimientoData: {},
        listaSeguimiento: [],
        Archivo: null,
        preloader: '0',
    },

    methods:
    {

        //// OBTENER DATOS DE UN compra
        getDatoscompra: function () {
            var url = base_url + 'compras/obtener_datos_compra/?Id=' + Get_Id;  //// averiguar como tomar el Id que viene por URL aca

            axios.post(url, {
                token: token
            }).then(response => {
                this.compraDatos = response.data[0]
            }).catch(error => {
                alert("mal");
                console.log(error.response.data)
            });
        },

        //// STOCK | CREAR
        movimientoStock: function (id, cantidad, descripcion, precio) {
            var url = base_url + 'stock/cargar_movimiento'; // url donde voy a mandar los datos

            // 1 Equivale a compras, 2 a Ordenes de trabajo

            axios.post(url, {
                token: token,
                Id: id,
                Cantidad: cantidad,
                Descripcion: descripcion,
                Proceso_id: Get_Id, 
                Tipo_movimiento: 1,
                Precio: precio
            }).then(response => {

                toastr.success('Proceso realizado correctamente', 'Stock')

                this.getListaComprados();
                this.cantMovimientoStock = [];
                this.descripcionMovimiento = []

            }).catch(error => {
                //alert("mal");
                console.log(error.response.data)

            });
        },

        //// PERIODOS  | MOSTRAR LISTADO
        getListadoPeriodos: function () {
            var url = base_url + 'periodos/obtener_periodos'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaPeriodos = response.data.Datos
            });
        },

        //// MOSTRAR LISTADO DE PRODUCTOS COMPRADOS -- la consulta ya esta lista si no me equivoco
        getListaComprados: function () {
            var url = base_url + 'stock/obtener_movimientos_v2/?Id=' + Get_Id; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Tipo_movimiento: 1
            }).then(response => {
                this.listaComprados = response.data
            });
        },

        //// compraS  | CREAR O EDITAR 
        crearcompra: function () {
            var url = base_url + 'compras/cargar_compra'; // url donde voy a mandar los datos
            this.boton_habilitado = 0;

            axios.post(url, {
                token: token,
                Datos: this.compraDatos
            }).then(response => {

                toastr.success('Proceso realizado correctamente', 'Compras')

                this.compraDatos.Id = response.data.Id;
                this.texto_boton = "Actualizar"
                this.getDatoscompra()
                this.boton_habilitado = 1;

            }).catch(error => {
                this.boton_habilitado = 1;
                console.log(error.response.data)
            });
        },


        //// PROVEEDORES  | MOSTRAR LISTADO
        getListadoProveedores_select: function (Rubro_id) {
            var url = base_url + 'proveedores/obtener_proveedores_select'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaProveedores_select = response.data
            });
        },

        //// MOSTRAR LISTADO DE PRODUCTOS QUE OFRECE EL PROVEEDOR EN CUESTIÓN
        getListaProductosProveedor: function () {
            var url = base_url + 'proveedores/obtener_productos_proveedor'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Proveedor_id: this.compraDatos.Proveedor_id,


            }).then(response => {
                this.listaProductos = response.data
                this.mostrar = 2
            });
        },


        //// COMPRAS | SUMA PARA DAR TOTAL DE UNA COMPRA
        sumarComrpra: function (n1, n2, n3) {

            var N1 = parseInt(n1);
            var N2 = parseInt(n2);
            var N3 = parseInt(n3);

            return N1 + N2 + N3;
        },

        //// MOVIMIENTOS | LIMPIAR FORMULARIO SEGUIMIENTO
        limpiarFormularioMovimiento: function () {
            this.movimientoDatos = {}
        },

        //// MOVIMIENTOS |  MOVIMIENTOS EN EFECTIVO
        getMovimientos: function () {
            var url = base_url + 'finanzas/obtener_movimientos'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Origen_movimiento: 'Compras',
                Fila_movimiento: Get_Id,

            }).then(response => {
                this.listaMovimientos = response.data.Datos;
                this.Total_efectivo = response.data.Total;
            });
        },

        //// COBRANZAS | CREAR O EDITAR EN EFECTIVO 
        crear_movimiento: function () {
            var url = base_url + 'finanzas/cargar_movimiento'; // url donde voy a mandar los datos
            this.boton_habilitado = 0;

            axios.post(url, {
                token: token,
                Datos: this.movimientoDatos,
                Origen_movimiento: 'Compras',
                Op: 0,
                Planificacion_id: 0,
                Fila_movimiento: Get_Id,

            }).then(response => {

                toastr.success('Proceso realizado correctamente', 'Compras')

                this.texto_boton = "Actualizar"
                this.getMovimientos()
                this.boton_habilitado = 1;

            }).catch(error => {
                this.boton_habilitado = 1;
                console.log(error.response.data)
            });
        },


        //// COMPRAS  | Dasactivar
        desactivarAlgo: function (Id, tabla) {
            var url = base_url + 'funcionescomunes/desvisualizar'; // url donde voy a mandar los datos

            var opcion = confirm("¿Esta seguro de eliminar a este pago?");
            if (opcion == true) {
                axios.post(url, {
                    token: token,
                    Id: Id,
                    tabla: tabla
                }).then(response => {

                    

                    /// si se elimino correctamente debe volver a dar de alta el cheque
                    if(tabla == tbl_dinero_cheques)
                    {
                        var url = base_url + 'finanzas/rehabilitar_cheque'; // url donde voy a mandar los datos
                        axios.post(url, {
                            token: token,
                            Id: Id,
                            
                        }).then(response => {
                            console.log("Cheque habilitado nuevamente: " + response.data.Id)
                        }).catch(error => {
                            //alert("mal");
                            console.log(error)
        
                        });
                    }

                    this.getDatosEfectivo();
                    this.getDatosTransferencia();
                    this.getDatosCheques();

                    toastr.success('Proceso realizado correctamente', 'Compras')
                    
                }).catch(error => {
                    alert("mal");
                    console.log(error)

                });
            }
        },

        /// INFO PARA MODAL 
        infoEtapa: function (observaciones) {
            this.infoModal.Observaciones = observaciones;
        },

        //// MOSTRAR LISTADO DE EMPRESAS  
        getListadoEmpresas: function () {
            var url = base_url + 'usuarios/obtener_empresas'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaEmpresas = response.data
            });
        },

        //// PLANIFICACIONES |  MOSTRAR LISTADO DE ORDENES
        getListadoPlanificaciones: function () {
            var url = base_url + 'ventas/obtener_listado_planificaciones'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaPlanificaciones = response.data
            }).catch(error => {
                console.log(error.response); //alert("mal");
                console.log(error.response.data)

            });
        },

        //// SEGUIMIENTO | MOSTRAR LISTADO
        getListadoSeguimiento: function () {
            var url = base_url + 'compras/obtener_seguimientos/?Id=' + Get_Id; // url donde voy a mandar los datos

            axios.post(url, {
                token: token
            }).then(response => {
                this.listaSeguimiento = response.data
            });
        },

        //// SEGUIMIENTO | DATOS SOBRE EL ARCHIVO SELECCIONADO EN CASO QUE QUIERA CARGAR ALGUNA ARCHIVO
        archivoSeleccionado(event) {
            this.Archivo = event.target.files[0]
            //this.texto_boton = "Actualizar"
        },

        //// SEGUIMIENTO | CREAR O EDITAR una SEGUIMIENTO
        crearSeguimiento: function () {
            var url = base_url + 'compras/cargar_seguimiento'; // url donde voy a mandar los datos

            axios.post(url, {
                token: token,
                Datos: this.seguimientoData, Compra_id: Get_Id
            }).then(response => {

                this.seguimientoData.Id = response.data.Id;

                /// si eso se ralizó bien, debe comprobar si hay un archivo a cargar.
                if (this.Archivo != null) {
                    var url = base_url + 'compras/subirFotoSeguimiento/?Id=' + this.seguimientoData.Id;
                    this.preloader = 1;

                    //const formData = event.target.files[0];
                    const formData = new FormData();
                    formData.append("Archivo", this.Archivo);

                    formData.append('_method', 'PUT');

                    //Enviamos la petición
                    axios.post(url, formData)
                        .then(response => {

                            this.seguimientoData.Url_archivo = response.data.Url_archivo;

                            toastr.success('El archivo se cargo correctamente', 'Compras')
                            this.preloader = 0;
                            this.getListadoSeguimiento();

                        }).catch(error => {
                            alert("MAL LA CARGA EN FUNCIÓN DE CARGAR ARCHIVO");
                            this.preloader = 0;
                            //this.seguimientoData.Url_archivo = response.data.Url_archivo;
                        });
                }
                // si lo hay lo carga, si no lo hay no hace nada

                this.getListadoSeguimiento();
                this.Archivo = null
                this.texto_boton = "Actualizar"
                toastr.success('Datos actualizados correctamente', 'Proveedores')

            }).catch(error => {
                alert("MAL LA CARGA EN FUNCIÓN DE CARGAR DATOS");
            });
        },

        //// SEGUIMIENTO | EDITAR UN SEGUIMIENTO
        editarFormularioSeguimiento: function (dato) {
            this.seguimientoData = dato;
        },

        //// SEGUIMIENTO | LIMPIAR FORMULARIO SEGUIMIENTO
        limpiarFormularioSeguimiento: function () {
            this.seguimientoData = {}
        },
    },

    ////// ACCIONES COMPUTADAS     
    computed:
    {
        buscarProducto: function () {
            return this.listaProductos.filter((item) => item.Nombre_item.toLowerCase().includes(this.buscar));
        },
    }
});


///---------------------------
