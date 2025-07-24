<?php
include("Conexion.php");
require 'vendor/autoload.php'; // Asegúrate de tener Dompdf instalado con Composer
use Dompdf\Dompdf;

// Obtener productos desde la base de datos
$query = "SELECT * FROM productos";
$resultado = mysqli_query($conexion, $query);

// Generar código REC automáticamente basado en el último ID
function generarSiguienteREC($conexion) {
    $sql = "SELECT rec FROM facturas ORDER BY id DESC LIMIT 1";
    $resultado = mysqli_query($conexion, $sql);

    if ($fila = mysqli_fetch_assoc($resultado)) {
        $ultimoCodigo = $fila['rec'];
        $numero = intval(substr($ultimoCodigo, 3)) + 1;
        return 'REC' . str_pad($numero, 4, '0', STR_PAD_LEFT);
    } else {
        return 'REC0001'; // Si no hay facturas, comienza desde REC0001
    }
}

$codigoGenerado = generarSiguienteREC($conexion);

// Fecha actual
$fecha = date("Y-m-d");

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cliente = $_POST['cliente'] ?? 'Sin nombre';
    $productosSeleccionados = $_POST['productos'] ?? [];

    // Obtener detalles de productos y calcular total
    $productosDetalles = [];
    $total = 0;
    foreach ($productosSeleccionados as $idProd) {
        $queryProd = "SELECT * FROM productos WHERE id = " . intval($idProd);
        $resProd = mysqli_query($conexion, $queryProd);
        if ($filaProd = mysqli_fetch_assoc($resProd)) {
            $productosDetalles[] = $filaProd;
            $total += $filaProd['precio'];
        }
    }

    // Insertar factura en base de datos
    $insertFactura = "INSERT INTO facturas (rec, fecha, cliente, total) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conexion, $insertFactura);
    mysqli_stmt_bind_param($stmt, "sssd", $codigoRec, $fecha, $cliente, $total);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // Generar contenido del PDF
    $html = "<h1>Factura $codigoRec</h1>";
    $html .= "<p><strong>Fecha:</strong> $fecha</p>";
    $html .= "<p><strong>Cliente:</strong> $cliente</p>";
    $html .= "<h3>Productos</h3>";
    $html .= "<table border='1' cellpadding='5' cellspacing='0' width='100%'>";
    $html .= "<tr><th>Producto</th><th>Precio</th></tr>";
    foreach ($productosDetalles as $prod) {
        $html .= "<tr><td>{$prod['nombre']}</td><td>$" . number_format($prod['precio'], 2) . "</td></tr>";
    }
    $html .= "<tr><td><strong>Total</strong></td><td><strong>$" . number_format($total, 2) . "</strong></td></tr>";
    $html .= "</table>";

    // Generar y descargar PDF
    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $dompdf->stream("Factura_$codigoRec.pdf", ["Attachment" => true]);
    exit;
}
?>

<!-- HTML -->
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Generar Factura</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script>
    let productos = [];

    function agregarProducto() {
      const select = document.getElementById("productoSelect");
      const tabla = document.getElementById("tablaProductos");
      const productoId = select.value;
      const productoTexto = select.options[select.selectedIndex].text;

      if (!productoId || productos.includes(productoId)) {
        alert("Este producto ya fue agregado o no se seleccionó ninguno.");
        return;
      }

      productos.push(productoId);

      const fila = tabla.insertRow();
      fila.innerHTML = `
        <td>${productoTexto}</td>
        <td><button type="button" class="btn btn-sm btn-danger" onclick="eliminarProducto(this, '${productoId}')">Eliminar</button></td>
        <input type="hidden" name="productos[]" value="${productoId}">
      `;
    }

    function eliminarProducto(boton, id) {
      const fila = boton.parentNode.parentNode;
      fila.remove();
      productos = productos.filter(pid => pid !== id);
    }
  </script>
</head>
<body class="bg-light">

<div class="container mt-5">
  <div class="card shadow">
    <div class="card-header bg-primary text-white">
      <h4>Generar Factura</h4>
    </div>
    <div class="card-body">
      <form method="POST">
        <div class="mb-3">
          <label class="form-label">Número de Factura (REC)</label>
          <input type="text" class="form-control" id="codigo" name="codigo" value="<?php echo $codigoGenerado; ?>" readonly>
        </div>

        <div class="mb-3">
          <label class="form-label">Fecha</label>
          <input type="text" class="form-control" name="fecha" value="<?php echo $fecha; ?>" readonly>
        </div>

        <div class="mb-3">
          <label class="form-label">Nombre del Cliente</label>
          <input type="text" class="form-control" name="cliente" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Seleccionar Producto</label>
          <div class="input-group">
            <select class="form-select" id="productoSelect">
              <option value="" selected disabled>-- Seleccione un producto --</option>
              <?php while ($producto = mysqli_fetch_assoc($resultado)): ?>
                <option value="<?php echo $producto['id']; ?>">
                  <?php echo $producto['nombre'] . " - $" . number_format($producto['precio'], 2); ?>
                </option>
              <?php endwhile; ?>
            </select>
            <button type="button" class="btn btn-outline-success" onclick="agregarProducto()">Agregar</button>
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label">Productos Seleccionados</label>
          <table class="table table-bordered" id="tablaProductos">
            <thead>
              <tr>
                <th>Producto</th>
                <th>Acción</th>
              </tr>
            </thead>
            <tbody>
              <!-- Productos agregados dinámicamente -->
            </tbody>
          </table>
        </div>

        <button type="submit" class="btn btn-success">Generar Factura</button>
      </form>
    </div>
  </div>
</div>

</body>
</html>