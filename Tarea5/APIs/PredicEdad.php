<?php
// Inicializar variable para mantener el nombre ingresado
$name = $_POST['name'] ?? '';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Predicción de Edad por Nombre</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .age-result {
            margin-top: 20px;
            padding: 20px;
            border-radius: 10px;
            background-color: #f8f9fa;
            transition: all 0.3s ease;
        }
        .age-result:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .age-icon {
            font-size: 3rem;
            margin-bottom: 10px;
        }
        .young {
            border-left: 5px solid #28a745;
            background-color: rgba(40, 167, 69, 0.1);
        }
        .adult {
            border-left: 5px solid #007bff;
            background-color: rgba(0, 123, 255, 0.1);
        }
        .elderly {
            border-left: 5px solid #6c757d;
            background-color: rgba(108, 117, 125, 0.1);
        }
        #loadingSpinner {
            display: none;
            margin: 20px auto;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h2 class="text-center mb-0">Predicción de Edad por Nombre</h2>
                    </div>
                    <div class="card-body">
                        <form id="ageForm">
                            <div class="mb-3">
                                <label for="name" class="form-label">Ingresa un nombre:</label>
                                <input type="text" class="form-control form-control-lg" id="name" name="name" 
                                       value="<?php echo htmlspecialchars($name); ?>" required
                                       placeholder="Ejemplo: Juan, María, Carlos">
                                <div class="form-text">No incluyas números ni caracteres especiales</div>
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                Predecir Edad
                            </button>
                        </form>

                        <div id="errorContainer" class="alert alert-danger mt-4" style="display: none;"></div>

                        <div id="resultContainer" style="display: none;">
                            <div class="age-result" id="ageResult">
                                <div class="text-center">
                                    <div class="age-icon" id="ageIcon"></div>
                                    <h3 id="resultTitle"></h3>
                                    <p class="lead" id="ageText"></p>
                                    <p id="ageGroupText"></p>
                                </div>
                            </div>
                        </div>

                        <div class="text-center">
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
        document.getElementById('ageForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const name = document.getElementById('name').value.trim();
            
            // Validación básica
            if (!name) {
                showError('Por favor, ingresa un nombre.');
                return;
            }
            
            if (/\d/.test(name)) {
                showError('El nombre no debe contener números.');
                return;
            }
            
            // Limpiar resultados anteriores
            hideError();
            document.getElementById('resultContainer').style.display = 'none';
            
            // Mostrar spinner de carga
            document.getElementById('loadingSpinner').style.display = 'block';
            
            // Consumir la API con Fetch
            fetch(`https://api.agify.io/?name=${encodeURIComponent(name)}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error en la respuesta de la API');
                    }
                    return response.json();
                })
                .then(data => {
                    // Ocultar spinner
                    document.getElementById('loadingSpinner').style.display = 'none';
                    
                    if (data.age !== null) {
                        showResult(data, name);
                    } else {
                        showError(`No se pudo determinar la edad para el nombre "${name}".`);
                    }
                })
                .catch(error => {
                    document.getElementById('loadingSpinner').style.display = 'none';
                    showError('Error al conectar con el servicio de predicción: ' + error.message);
                });
        });
        
        function showResult(data, name) {
            const age = data.age;
            let ageGroup, icon, colorClass;
            
            // Determinar grupo de edad
            if (age < 18) {
                ageGroup = 'joven';
                icon = '👶';
                colorClass = 'young';
            } else if (age < 60) {
                ageGroup = 'adulto';
                icon = '🧑';
                colorClass = 'adult';
            } else {
                ageGroup = 'anciano';
                icon = '👴';
                colorClass = 'elderly';
            }
            
            // Actualizar la UI
            const ageResult = document.getElementById('ageResult');
            ageResult.className = `age-result ${colorClass}`;
            
            document.getElementById('ageIcon').textContent = icon;
            document.getElementById('resultTitle').textContent = `Resultado para: ${name}`;
            document.getElementById('ageText').innerHTML = `Edad estimada: <strong>${age} años</strong>`;
            document.getElementById('ageGroupText').innerHTML = `Grupo de edad: <strong>${ageGroup.charAt(0).toUpperCase() + ageGroup.slice(1)}</strong>`;
            
            // Mostrar resultados
            document.getElementById('resultContainer').style.display = 'block';
        }
        
        function showError(message) {
            const errorContainer = document.getElementById('errorContainer');
            errorContainer.textContent = message;
            errorContainer.style.display = 'block';
        }
        
        function hideError() {
            document.getElementById('errorContainer').style.display = 'none';
        }
    </script>
</body>
</html>