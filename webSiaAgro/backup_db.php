<?php
// Script para backup de la base de datos PostgreSQL 'siaagro'

include_once("conexion/conexion.php");
$conn = cconexion::ConexionBD();

include_once("Sidebar.php");
// Directorio de backups
$backupDir = __DIR__ . '/backups/';
if (!is_dir($backupDir)) {
    mkdir($backupDir, 0755, true);
}

// Nombre del archivo de backup
$fecha = date('Y-m-d_H-i-s');
$backupFile = $backupDir . 'backup_' . $fecha . '.sql';

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
                    if (is_null($val)) {
                        $vals[] = 'NULL';
                    } elseif (is_bool($val)) {
                        $vals[] = $val ? 'TRUE' : 'FALSE';
                    } elseif (is_numeric($val)) {
                        $vals[] = $val;
                    } elseif (is_resource($val)) {
                        $vals[] = "NULL"; // No exportar recursos
                    } else {
                        $vals[] = $conn->quote((string)$val);
                    }
                }
                $values[] = "(" . implode(', ', $vals) . ")";
            }
            $sql .= implode(",\n", $values) . ";\n\n";
        }
    }

    file_put_contents($backupFile, $sql);
    echo "Backup realizado exitosamente. Archivo: $backupFile\n";

} catch (Exception $e) {
    echo "Error al realizar el backup: " . $e->getMessage() . "\n";
}
?>