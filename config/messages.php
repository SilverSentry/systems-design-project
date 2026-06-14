<?php
//Archivo para el manejo de mensajes

namespace App\Config;

class Messages
{

    //Mensajes generales
    public const ERR_EMPTY_FIELDS = 'Todos los campos son obligatorios';
    public const ERR_EMAIL_INVALID = 'El formato del email no es válido';
    public const UNEXPECTED_ERR = 'Ocurrió un error inesperado';
    public const ERR_NAME_INVALID = 'El nombre debe contener solo letras';
    public const ERR_SURNAME_INVALID = 'El apellido debe contener solo letras';

    //Mensajes para el registro
    public const ERR_PASS_INVALID = 'La contraseña no cumple con los requisitos';
    public const ERR_PASS_DOES_NOT_MATCH = 'Las contraseñas no coinciden';
    public const ERR_ALREADY_EXISTS = 'Ya existe un usuario con el correo ingresado';
    public const SUCCESS_REGISTER = 'Ya puedes iniciar sesión';

    //Mensajes para la autenticación
    public const ERR_AUTH_SESSION = 'Debes iniciar sesión para acceder a esta sección';

    //Mensajes para el login
    public const ERR_INCORRECT_CREDENTIALS = 'Crendeciales incorrectas';

    //Mensajes para el registro de clientes
    public const ERR_PHONE_INVALID = 'El número de teléfono no es válido';
    public const ERR_DNI_INVALID = 'La cédula de identidad no es válida';
    public const ERR_BIRTHDATE_INVALID = 'La fecha de nacimiento no es válida';
    public const ERR_SELECTION_EMPTY = 'No se han seleccionado antecedentes clínicos';
    public const ERR_CLIENT_ALREADY_EXISTS = 'Ya existe un cliente con la cédula de identidad ingresada';
    public const ERR_CLIENT_CREATION_FAILED = 'No se pudo crear el cliente, intente nuevamente';
    public const SUCCESS_CLIENT_CREATED = '¡Cliente registrado exitosamente!';
    
}
