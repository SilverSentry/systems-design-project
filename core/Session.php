<?php
//Manejo seguro de sesiones con métodos utilitarios
 
class Session {

    //Iniciar sesión de forma segura
    public static function start(): void {

        if(session_status() === PHP_SESSION_NONE) {

            //Configuraciones de seguridad para la sesión
            ini_set('session.cookie_httponly', 1);
            ini_set('session.cookie_secure', isset($_SERVER['HTTPS']));
            ini_set('session.use_strict_mode', 1);
            ini_set('session.cookie_samesite', 'Lax');
            
            session_start();
        }
    }

    //Establecer un valor en sesión
    public static function set(string $key, $value): void {
        self::start();
        $_SESSION[$key] = $value;
    }

    //Obtener un valor de sesión
    public static function get(string $key, $default = null) {
        self::start();
        return $_SESSION[$key] ?? $default;
    }

    //Verificar si existe una clave en sesión
    public static function has(string $key): bool {
        self::start();
        return isset($_SESSION[$key]);
    }

    //Eliminar una clave de sesión
    public static function remove(string $key): void {
        self::start();
        unset($_SESSION[$key]);
    }

    //Cerrar sesión del usuario
    public static function logout(): void {
        self::remove('user');
    }

    //Destruir toda la sesión
    public static function destroy(): void {
        self::start();
        $_SESSION = [];
        
        //Eliminar la cookie de sesión
        if(ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }
        
        session_destroy();
    }

    //Regenerar ID de sesión (previene hijacking)
    public static function regenerate(): void {
        self::start();
        session_regenerate_id(true);
    }

    //Obtener el usuario logueado actual
    public static function getUser(): ?array {
        return self::get('user');
    }

    //Verificar si hay usuario logueado
    public static function isLogged(): bool {
        return self::has('user') && is_array(self::get('user'));
    }

    //Establecer usuario en sesión
    public static function setUser(array $user): void {
        self::set('user', $user);
    }
}