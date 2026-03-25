<?php

    spl_autoload_register(function ($class){

        //Se definen las carpetas donde buscar las clases
        $directories = [
        '../app/controllers/',
        '../app/models/',
        '../config/'
    ];

        //Se recorre en los directorios para ver si el archivo existe mediante un foreach
        foreach ($directories as $directory) {

            $file = __DIR__ . '/' . $directory . $class . '.php';

            if (file_exists($file)) {

                require_once $file;
                return;

            }
        }

    });

?>