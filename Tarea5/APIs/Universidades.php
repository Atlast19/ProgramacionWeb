<?php
// Inicializar variables
$country = $_POST['country'] ?? '';
$universities = [];
$error = '';
$searched = false;

// Procesar el formulario cuando se envía
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $country = trim($_POST['country'] ?? '');
    
    // Validar el país
    if (empty($country)) {
        $error = 'Por favor, ingresa un país.';
    } else {
        $searched = true;
        // Llamar a la API
        $apiUrl = "http://universities.hipolabs.com/search?country=" . urlencode($country);
        $response = file_get_contents($apiUrl);
        
        if ($response === FALSE) {
            $error = 'Error al conectar con el servicio de universidades.';
        } else {
            $universities = json_decode($response, true);
            
            if (empty($universities)) {
                $error = "No se encontraron universidades para el país '$country'.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Universidades por País</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .university-card {
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }
        .university-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .university-link {
            text-decoration: none;
        }
        .university-link:hover {
            text-decoration: underline;
        }
        .no-results {
            text-align: center;
            padding: 40px;
            color: #6c757d;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h2 class="text-center mb-0">Buscador de Universidades por País</h2>
                    </div>
                    <div class="card-body">
                        <form method="post">
                            <div class="mb-3">
                                <label for="country" class="form-label">Ingresa un país (en inglés):</label>
                                <input type="text" class="form-control form-control-lg" id="country" name="country" 
                                       value="<?php echo htmlspecialchars($country); ?>" required
                                       placeholder="Ejemplo: Dominican Republic, Mexico, Spain">
                                <div class="form-text">Debes escribir el nombre del país en inglés</div>
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                Buscar Universidades
                            </button>
                        </form>

                        <?php if ($error): ?>
                            <div class="alert alert-danger mt-4"><?php echo $error; ?></div>
                        <?php endif; ?>

                        <?php if ($searched && empty($error)): ?>
                            <div class="mt-4">
                                <h3 class="mb-4">Universidades en <?php echo htmlspecialchars($country); ?></h3>
                                
                                <?php if (!empty($universities)): ?>
                                    <div class="row">
                                        <?php foreach ($universities as $university): ?>
                                            <div class="col-md-6">
                                                <div class="card university-card">
                                                    <div class="card-body">
                                                        <h5 class="card-title"><?php echo htmlspecialchars($university['name']); ?></h5>
                                                        <p class="card-text">
                                                            <strong>Dominio:</strong> 
                                                            <?php if (!empty($university['domains'])): ?>
                                                                <?php echo htmlspecialchars($university['domains'][0]); ?>
                                                            <?php else: ?>
                                                                No disponible
                                                            <?php endif; ?>
                                                        </p>
                                                        <?php if (!empty($university['web_pages'])): ?>
                                                            <a href="<?php echo htmlspecialchars($university['web_pages'][0]); ?>" 
                                                               class="university-link text-primary" 
                                                               target="_blank">
                                                                Visitar sitio web
                                                            </a>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <div class="no-results">
                                        <i class="fas fa-university fa-3x mb-3"></i>
                                        <p class="lead">No se encontraron universidades</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="card-footer text-muted text-center">
                        <h7>Volver al <a href="../index.php">Inicio</a></h7>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Font Awesome -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
</body>
</html>