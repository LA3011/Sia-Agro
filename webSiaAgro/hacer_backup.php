<?php
session_start();
if(!isset($_SESSION['Aceso'])){
  header("location: index.html");
}

include_once("conexion/conexion.php");
$conn = cconexion::ConexionBD();

// Generar backup usando PDO
$sql = "-- Backup created on " . date('Y-m-d H:i:s') . "\n\n";

try {
    // Obtener todas las tablas
    $tables = $conn->query("SELECT tablename FROM pg_tables WHERE schemaname = 'public' ORDER BY tablename")->fetchAll(PDO::FETCH_COLUMN);

    foreach ($tables as $table) {
        // Obtener definición de tabla
        $columns = $conn->query("SELECT column_name, data_type, is_nullable, column_default FROM information_schema.columns WHERE table_schema = 'public' AND table_name = '" . $table . "' ORDER BY ordinal_position")->fetchAll(PDO::FETCH_ASSOC);
        $create = "CREATE TABLE \"$table\" (\n";
        $col_defs = [];
        foreach ($columns as $col) {
            $def = '"' . $col['column_name'] . '" ' . $col['data_type'];
            if ($col['is_nullable'] == 'NO') {
                $def .= ' NOT NULL';
            }
            if ($col['column_default'] !== null) {
                $def .= ' DEFAULT ' . $col['column_default'];
            }
            $col_defs[] = $def;
        }
        // Agregar primary key si existe
        $pk = $conn->query("SELECT k.column_name FROM information_schema.table_constraints t JOIN information_schema.key_column_usage k USING (constraint_name, table_schema, table_name) WHERE t.constraint_type = 'PRIMARY KEY' AND t.table_schema = 'public' AND t.table_name = '" . $table . "' ORDER BY k.ordinal_position")->fetchAll(PDO::FETCH_COLUMN);
        if ($pk) {
            $col_defs[] = "PRIMARY KEY (" . implode(', ', array_map(function($c){return '"' . $c . '"';}, $pk)) . ")";
        }
        $create .= implode(",\n", $col_defs) . "\n);\n\n";
        $sql .= $create;

        // Obtener datos
        $data = $conn->query("SELECT * FROM \"$table\"")->fetchAll(PDO::FETCH_ASSOC);
        if ($data) {
            $columns = array_keys($data[0]);
            $sql .= "INSERT INTO \"$table\" (" . implode('", "', $columns) . "\") VALUES\n";
            $values = [];
            foreach ($data as $row) {
                $vals = [];
                foreach ($row as $val) {
                    $vals[] = $conn->quote($val);
                }
                $values[] = "(" . implode(', ', $vals) . ")";
            }
            $sql .= implode(",\n", $values) . ";\n\n";
        }
    }

    file_put_contents($backupFile, $sql);

    // Forzar descarga
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . basename($backupFile) . '"');
    header('Content-Length: ' . filesize($backupFile));
    readfile($backupFile);
    exit;

} catch (Exception $e) {
    $_SESSION['mensaje_backup'] = "Error al realizar el backup: " . $e->getMessage();
    header("Location: backup.php");
}
?>