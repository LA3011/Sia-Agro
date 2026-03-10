<?php
class cconexion {
    public static function ConexionBD() {
        try {
            // Cambia estos parámetros según tu configuración
            $host = 'localhost';
            $port = '5432'; 
            $dbname = 'siaagrodev';
            $user = 'postgres';
            $password = 'root';

            $dsn = "pgsql:host=$host;port=$port;dbname=$dbname"; 
            $conn = new PDO($dsn, $user, $password);
            
           
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            return $conn;
        } catch (PDOException $e) {
            echo "Error en la conexión: " . $e->getMessage();
            return null;
        }
    }
}
?>
