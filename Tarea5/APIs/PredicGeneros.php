<?php
// Inicializar variables
$name = $_POST['name'] ?? '';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Predicción de Género por Nombre</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome para íconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .gender-icon {
            font-size: 2rem;
            margin-bottom: 1rem;
        }
        .card-result {
            transition: all 0.3s ease;
        }
        .card-result:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        body {
            background-color: #f8f9fa;
        }
        .card-header {
            background-color: #6c757d;
            color: white;
        }
        #loadingSpinner {
            display: none;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow-sm">
                    <div class="card-header text-center">
                        <h1 class="h3 mb-0">Predicción de Género por Nombre</h1>
                    </div>
                    <div class="card-body">
                        <form id="genderForm">
                            <div class="mb-3">
                                <label for="name" class="form-label">Ingresa un nombre:</label>
                                <input type="text" class="form-control form-control-lg" id="name" name="name" 
                                       value="<?php echo htmlspecialchars($name); ?>" required
                                       placeholder="Ejemplo: María, Carlos, Irma">
                                <div class="form-text">No incluyas números ni caracteres especiales</div>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-venus-mars me-2"></i>Predecir Género
                                </button>
                            </div>
                        </form>
                        
                        <div id="errorContainer" class="alert alert-danger mt-4" style="display: none;"></div>
                        
                        <div id="resultContainer" style="display: none;">
                            <div class="card card-result mt-4" id="genderCard">
                                <div class="card-body text-center" id="genderCardBody">
                                    <div class="gender-icon" id="genderIcon"></div>
                                    <h3 class="card-title">Resultado</h3>
                                    <p class="card-text" id="genderMessage"></p>
                                    <div class="progress mt-3">
                                        <div class="progress-bar" id="genderProgress" 
                                             role="progressbar" 
                                             aria-valuemin="0" 
                                             aria-valuemax="100">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <div id="loadingSpinner" class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Cargando...</span>
                            </div>
                        </div>
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
    
    <script>
        document.getElementById('genderForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const nameInput = document.getElementById('name');
            const name = nameInput.value.trim();
            
            
            if (!name) {
                showError('Por favor, ingresa un nombre.');
                return;
            }
            
            if (/\d/.test(name)) {
                showError('El nombre no debe contener números.');
                return;
            }
            
            
            hideError();
            document.getElementById('resultContainer').style.display = 'none';
            
            
            document.getElementById('loadingSpinner').style.display = 'block';
            
            
            fetch(`https://api.genderize.io/?name=${encodeURIComponent(name)}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error en la respuesta de la API');
                    }
                    return response.json();
                })
                .then(data => {
                    // Ocultar spinner
                    document.getElementById('loadingSpinner').style.display = 'none';
                    
                    if (data.gender) {
                        showResult(data);
                    } else {
                        showError(`No se pudo determinar el género para el nombre "${name}".`);
                    }
                })
                .catch(error => {
                    document.getElementById('loadingSpinner').style.display = 'none';
                    showError('Error al conectar con el servicio de predicción: ' + error.message);
                });
        });
        
        function showResult(data) {
            const name = document.getElementById('name').value.trim();
            const probability = data.probability * 100;
            let color, icon, message;
            
            if (data.gender === 'male') {
                color = 'primary';
                icon = '♂';
                message = `El nombre <strong>${name}</strong> es probablemente masculino con un <strong>${probability.toFixed(1)}%</strong> de certeza.`;
            } else {
                color = 'danger';
                icon = '♀';
                message = `El nombre <strong>${name}</strong> es probablemente femenino con un <strong>${probability.toFixed(1)}%</strong> de certeza.`;
            }
            
            
            const card = document.getElementById('genderCard');
            const cardBody = document.getElementById('genderCardBody');
            const progress = document.getElementById('genderProgress');
            
            card.classList.add(`border-${color}`);
            cardBody.classList.add(`text-${color}`);
            document.getElementById('genderIcon').textContent = icon;
            document.getElementById('genderMessage').innerHTML = message;
            
            progress.classList.add(`bg-${color}`);
            progress.style.width = `${probability}%`;
            progress.textContent = `${probability.toFixed(1)}%`;
            progress.setAttribute('aria-valuenow', probability);
            
            // Mostrar resultados
            document.getElementById('resultContainer').style.display = 'block';
        }
        
        function showError(message) {
            const errorContainer = document.getElementById('errorContainer');
            errorContainer.innerHTML = message;
            errorContainer.style.display = 'block';
        }
        
        function hideError() {
            document.getElementById('errorContainer').style.display = 'none';
        }
    </script>
</body>
</html>