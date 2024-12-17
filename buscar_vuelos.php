<?php
// Incluir el archivo de conexión
include 'conexion.php';

// Verificar si se enviaron los parámetros del formulario
$destino = isset($_GET['destino']) ? $_GET['destino'] : '';
$fecha = isset($_GET['fecha']) ? $_GET['fecha'] : '';

// Consulta para buscar vuelos
$sql = "SELECT * FROM vuelos WHERE destino LIKE :destino";
if ($fecha) {
    $sql .= " AND fecha_salida = :fecha";
}

// Preparar y ejecutar la consulta
$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':destino' => "%$destino%", // Buscar por destino, de manera flexible
    ':fecha' => $fecha
]);

// Obtener los resultados
$vuelos = $stmt->fetchAll();

// Mostrar el formulario de búsqueda
echo '<form method="GET" action="buscar_vuelos.php">
        <input type="text" name="destino" placeholder="Destino" value="' . htmlspecialchars($destino) . '">
        <input type="date" name="fecha" value="' . htmlspecialchars($fecha) . '">
        <button type="submit">Buscar</button>
      </form>';

// Mostrar los resultados de la búsqueda
if ($vuelos) {
    echo "<h2>Vuelos disponibles</h2>";
    echo "<table border='1'>
            <tr>
                <th>ID</th>
                <th>Aerolínea</th>
                <th>Destino</th>
                <th>Precio</th>
            </tr>";

    foreach ($vuelos as $vuelo) {
        echo "<tr>
                <td>" . htmlspecialchars($vuelo['id']) . "</td>
                <td>" . htmlspecialchars($vuelo['aerolinea']) . "</td>
                <td>" . htmlspecialchars($vuelo['destino']) . "</td>
                <td>$" . htmlspecialchars($vuelo['precio']) . "</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "<p>No se encontraron vuelos para la búsqueda.</p>";
}
?>
