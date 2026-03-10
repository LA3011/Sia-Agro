<?php
class Bitacora {
  private $conexion;

  public function __construct($conexion) {
    $this->conexion = $conexion;
  }
//-----------------------------------------------------------------------------------------------------
  public function insertbitacora($tabla, $usuario, $id_usuario, $numero_registro = null) {
    date_default_timezone_set('America/Caracas');
    $fecha = date("Y-m-d");
    $hora = date("H:i:s");
    

    // Acción fija para insertar en la columna "Accion"
    $accion = "Registro";

    $sql = "INSERT INTO bitacora (\"Tabla_Modificada\", \"Usuario\", \"Id_usuario\", \"Fecha\", \"Hora\", \"Accion\", \"Numero_Registro\") VALUES ('$tabla', '$usuario', '$id_usuario', '$fecha', '$hora', '$accion',";

    if ($numero_registro !== null) {
        $sql .= $numero_registro;
    } else {
        $sql .= "NULL";
    }

    $sql .= ")";

    // Ejecutar la consulta utilizando la variable de conexión
    if ($this->conexion->query($sql) === TRUE) {
       
    } else {
     
    }
}
//--------------------------------------------------------------------------------------------------
public function updatebitacora($tabla, $usuario, $id_usuario, $numero_registro = null) {
    date_default_timezone_set('America/Caracas');
    $fecha = date("Y-m-d");
    $hora = date("H:i:s");
    

    // Acción fija para insertar en la columna "Accion"
    $accion = "Modifico";

    $sql = "INSERT INTO bitacora (\"Tabla_Modificada\", \"Usuario\", \"Id_usuario\", \"Fecha\", \"Hora\", \"Accion\", \"Numero_Registro\") VALUES ('$tabla', '$usuario', '$id_usuario', '$fecha', '$hora', '$accion',";

    if ($numero_registro !== null) {
        $sql .= $numero_registro;
    } else {
        $sql .= "NULL";
    }

    $sql .= ")";

    // Ejecutar la consulta utilizando la variable de conexión
    if ($this->conexion->query($sql) === TRUE) {
       
    } else {
     
    }
}
//----------------------------------------------------------------------------------------------------------

public function deletebitacora($tabla, $usuario, $id_usuario, $numero_registro = null) {
    // Obtener la fecha y hora actual
    $fecha = date("Y-m-d");
    $hora = date("H:i:s");

    // Acción fija para insertar en la columna "Accion"
    $accion = "Elimino";

    // Construir la consulta SQL para insertar en la tabla de bitacora
    $sql = "INSERT INTO bitacora (\"Tabla_Modificada\", \"Usuario\", \"Id_usuario\", \"Fecha\", \"Hora\", \"Accion\", \"Numero_Registro\") VALUES ('$tabla', '$usuario', '$id_usuario', '$fecha', '$hora', '$accion',";

    if ($numero_registro !== null) {
        $sql .= $numero_registro;
    } else {
        $sql .= "NULL";
    }

    $sql .= ")";

    // Ejecutar la consulta utilizando la variable de conexión
    if ($this->conexion->query($sql) === TRUE) {
        echo "Registro insertado en la bitácora correctamente.";
    } else {
        echo "Error al insertar registro en la bitácora: " . $this->conexion->error;
    }
}
//---------------------------------------------------------------------------------------------
public function importbitacora($tabla, $usuario, $id_usuario, $numero_registro = null) {
    // Obtener la fecha y hora actual
    $fecha = date("Y-m-d");
    $hora = date("H:i:s");

    // Acción fija para insertar en la columna "Accion"
    $accion = "Imprimio";

    // Construir la consulta SQL para insertar en la tabla de bitacora
    $sql = "INSERT INTO bitacora (\"Tabla_Modificada\", \"Usuario\", \"Id_usuario\", \"Fecha\", \"Hora\", \"Accion\", \"Numero_Registro\") VALUES ('$tabla', '$usuario', '$id_usuario', '$fecha', '$hora', '$accion', ";

    if ($numero_registro !== null) {
        $sql .= $numero_registro;
    } else {
        $sql .= "NULL";
    }

    $sql .= ")";

    // Ejecutar la consulta utilizando la variable de conexión
    if ($this->conexion->query($sql) === TRUE) {
        //echo "Registro insertado en la bitácora correctamente.";
    } else {
        //echo "Error al insertar registro en la bitácora: " . $this->conexion->error;
    }
}
}