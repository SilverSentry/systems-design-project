<?php

//Clase para el manejo de mensajes
class Messages {

    //Mensajes generales
    public const ERR_EMPTY_FIELDS = 'Rellene todos los campos';
    public const ERR_EMAIL_INVALID = 'El formato del email no es válido';
    public const UNEXPECTED_ERR = 'Ocurrió un error inesperado';

    //Mensajes para el registro
    public const ERR_NAME_INVALID = 'El nombre debe contener solo letras';
    public const ERR_SURNAME_INVALID = 'El apellido debe contener solo letras';
    public const ERR_PASS_INVALID = 'La contraseña no cumple con los requisitos';
    public const ERR_PASS_DOES_NOT_MATCH = 'Las contraseñas no coinciden';
    public const ERR_ALREADY_EXISTS = 'Ya existe un usuario con el correo ingresado';

    //Mensajes para el login
    public const ERR_INCORRECT_CREDENTIALS = 'Crendeciales incorrectas';

}

?>