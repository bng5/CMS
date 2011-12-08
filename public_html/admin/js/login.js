
function deshabilitarFormLogin(hab) {
    var campos = document.forms[0].elements;
    for(var i = 2; i < campos.length; i++) {
        campos[i].disabled = hab;
        campos[i].className = '';
        if(campos[i].nextSibling != null)
            campos[i].parentNode.removeChild(campos[i].nextSibling);
    }
}

function enfocarUsuario() {
    deshabilitarFormLogin(false);
    document.forms[0].elements[2].focus();
}
agregarEvento(window, 'load', enfocarUsuario);

var LOGIN_TEXTOS = {
    0: "Enviando solicitud...",
    1: "Su navegador debe aceptar cookies para este dominio.",
    2: "La respuesta del servidor no pudo ser interpretada correctamente.",
    202: "Accediendo.",
    400: "Existen errores en los campos.",
    401: "Los datos proporcionados no son correctos.",
    500: "Error interno del servidor",
    mensajes: {
        7: "Debe completar ambos campos para ingresar.",
        8: "Existen campos con formato incorrecto."
    },
    errores: {
        1: "Campo requerido",
        3: "No existe el usuario indicado.",
        4: "El campo debe contener al menos 4 caracteres.",
        5: "El campo excede la cantidad de caracteres permitida."
    },
    9: "El usuario no se encuentra habilitado."
};

function loginAcceso(formulario) {
    var loginaviso = document.getElementById('aviso');
    while(loginaviso.firstChild)
        loginaviso.removeChild(loginaviso.firstChild);
    deshabilitarFormLogin(true);
    var usuario = trim(formulario.elements[2].value);
    var clave = trim(formulario.elements[3].value);
    if(usuario == '' || clave == '') {
        loginaviso.appendChild(document.createTextNode(LOGIN_TEXTOS['mensajes'][7]));
        deshabilitarFormLogin(false);
        if(usuario == '')
            agregarErrorCampo('usuario', 1);
        if(clave == '')
            agregarErrorCampo('clave', 1);
        return false;
    }

    //var params = {};
    var datos = new Array();
    datos.push('usuario='+encodeURIComponent(usuario));
    datos.push('clave='+encodeURIComponent(clave));
    if(formulario.elements[4].type == 'checkbox' && formulario.elements[4].checked == true)
        datos.push('recordarme=1');

    loginaviso.className = 'cargando';
    loginaviso.appendChild(document.createTextNode(LOGIN_TEXTOS[0]));
    //enviarXHR('/api/login', loginRespuesta, datos, false, params);
    var envio = new XMLHttpRequest();
    envio.onreadystatechange = function() { // hand();
        if(envio.readyState == 4)
            loginRespuesta(envio);//, params eval(hand+"(pet, elAviso)");
    };
    // ruta
    envio.open("POST", 'api/v1/login', true);
    envio.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    envio.setRequestHeader('Accept', 'application/json, application/*; q=0.1');
    envio.send(datos.join("&"));
    return false;
}

function loginRespuesta(request) {
    var loginaviso = document.getElementById('aviso');
    while(loginaviso.firstChild)
        loginaviso.removeChild(loginaviso.firstChild);
    if(LOGIN_TEXTOS[request.status] != null)
        loginaviso.appendChild(document.createTextNode(LOGIN_TEXTOS[request.status]));
    if(request.status == 202) {
        if(document.cookie == '') {
            loginaviso.firstChild.data = LOGIN_TEXTOS[1];
            deshabilitarFormLogin(false);
        }
        else {
            // ruta
            document.location.href = 'login?ref='+document.forms[0].elements[1].value;//encodeURIComponent()
        }
        return;
    }
    deshabilitarFormLogin(false);
    var respuesta;
    try {
        respuesta = eval('('+request.responseText+')');
    }
    catch(e) {
        loginaviso.firstChild.data = LOGIN_TEXTOS[2];
        return;
    }

    if(respuesta['errores']) {
        var i = 0;
        for(var campo_k in respuesta['errores']) {
            agregarErrorCampo(campo_k, respuesta.errores[campo_k].cod);
            if(i == 0)
                document.forms[0][campo_k].focus();
            i++
        }
    }
}

function agregarErrorCampo(campo_k, cod) {
    var campo = document.forms[0][campo_k];
    campo.className = 'error';
    var div = document.createElement('div');
    div.className = 'campo_error';
    div.style.top = (campo.offsetTop - 30)+'px';
    var span = document.createElement('span');
    span.className = 'error';
    span.appendChild(document.createTextNode(LOGIN_TEXTOS['errores'][cod]));
    div.appendChild(span);
    campo.parentNode.appendChild(div);
}

/*
 *  agregarEvento(campo, 'change', quitarErrorCampo);
function quitarErrorCampo(event)
 {
  var campo = event.target;
  if(campo.className != 'error')
	return;
  campo.className = '';
  campo.parentNode.removeChild(campo.nextSibling)
 }
*/