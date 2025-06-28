<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clima en República Dominicana</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .weather-container { max-width: 800px; margin: 0 auto; }
        .api-key-form { background: white; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .weather-card {
            background: linear-gradient(135deg, #4b6cb7 0%, #182848 100%);
            color: white;
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }
        .weather-icon { font-size: 5rem; margin: 20px 0; }
        .temperature { font-size: 3.5rem; font-weight: bold; }
        .weather-detail { font-size: 1.1rem; }
        #loadingSpinner { width: 4rem; height: 4rem; }
        .btn-primary { background-color: #3a7bd5; border: none; }
        .btn-primary:hover { background-color: #2c5fb3; }
    </style>
</head>
<body>
    <div class="container py-5 weather-container">
        <div class="text-center mb-5">
            <h1 class="display-4 text-primary">Clima en República Dominicana</h1>
            <p class="lead text-muted">Consulta el clima actual en tiempo real</p>
        </div>

        <!-- Formulario de API Key -->
        <div id="apiKeyForm" class="p-4 mb-4 api-key-form">
            <h2 class="text-center mb-4">Configuración Inicial</h2>
            <div class="mb-3">
                <label for="apiKeyInput" class="form-label fw-bold">1. Ingresa tu API Key de OpenWeatherMap</label>
                <input type="text" class="form-control form-control-lg" id="apiKeyInput" 
                       placeholder="Ej: 3c3cccad35d289676b44ec5ce22ba4a5">
                <div class="form-text">
                    La API Key debe tener exactamente 32 caracteres alfanuméricos
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label fw-bold">2. ¿Cómo obtener tu API Key?</label>
                <div class="card border-primary">
                    <div class="card-body">
                        <ol>
                            <li>Visita <a href="https://home.openweathermap.org/users/sign_up" target="_blank">OpenWeatherMap</a></li>
                            <li>Regístrate para una cuenta gratuita</li>
                            <li>Verifica tu email (revisa la carpeta de spam)</li>
                            <li>En tu panel de control, ve a "API Keys"</li>
                            <li>Copia tu clave API (puede tardar 1-2 horas en activarse)</li>
                        </ol>
                    </div>
                </div>
            </div>
            <div class="d-grid">
                <button id="saveApiKeyBtn" class="btn btn-primary btn-lg">
                    <i class="fas fa-save me-2"></i>Guardar API Key
                </button>
            </div>
        </div>

        <!-- Aplicación de Clima (inicialmente oculta) -->
        <div id="weatherApp" style="display: none;">
            <div class="card mb-4 border-0 shadow">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <select class="form-select form-select-lg" id="citySelect">
                                <option value="Santo Domingo">Santo Domingo</option>
                                <option value="Santiago">Santiago de los Caballeros</option>
                                <option value="La Romana">La Romana</option>
                                <option value="Puerto Plata">Puerto Plata</option>
                            </select>
                        </div>
                        <div class="col-md-4 mt-2 mt-md-0">
                            <button id="searchBtn" class="btn btn-primary btn-lg w-100">
                                <i class="fas fa-search me-2"></i>Buscar
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div id="weatherResult" class="card weather-card mb-4" style="display: none;">
                <div class="card-body text-center p-5">
                    <div class="weather-icon">
                        <i id="weatherIcon" class="fas fa-sun"></i>
                    </div>
                    <h2 id="cityName" class="mb-3"></h2>
                    <div class="temperature mb-4" id="temperature"></div>
                    
                    <div class="row text-center">
                        <div class="col-md-3 mb-3">
                            <div class="weather-detail">
                                <i class="fas fa-temperature-low fa-2x mb-2"></i>
                                <div id="tempMin"></div>
                                <small>Mínima</small>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="weather-detail">
                                <i class="fas fa-temperature-high fa-2x mb-2"></i>
                                <div id="tempMax"></div>
                                <small>Máxima</small>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="weather-detail">
                                <i class="fas fa-tint fa-2x mb-2"></i>
                                <div id="humidity"></div>
                                <small>Humedad</small>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="weather-detail">
                                <i class="fas fa-wind fa-2x mb-2"></i>
                                <div id="windSpeed"></div>
                                <small>Viento</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent text-center">
                    <small id="updateTime" class="text-white-50"></small>
                </div>
            </div>
        </div>

        <div id="loadingSpinner" class="text-center my-5" style="display: none;">
            <div class="spinner-border text-primary"></div>
            <p class="mt-3">Cargando datos meteorológicos...</p>
        </div>

        <div id="errorContainer" class="alert alert-danger" style="display: none;"></div>

        <div class="text-center text-muted mt-5">
            <small>Datos proporcionados por <a href="https://openweathermap.org/" target="_blank">OpenWeatherMap</a></small>
        </div>
    </div>

    <script>
        // Almacenamiento para la API Key
        const STORAGE_KEY = 'weatherAppApiKey';
        let currentApiKey = localStorage.getItem(STORAGE_KEY);

        // Elementos del DOM
        const apiKeyForm = document.getElementById('apiKeyForm');
        const weatherApp = document.getElementById('weatherApp');
        const apiKeyInput = document.getElementById('apiKeyInput');
        const saveApiKeyBtn = document.getElementById('saveApiKeyBtn');
        const citySelect = document.getElementById('citySelect');
        const searchBtn = document.getElementById('searchBtn');
        const weatherResult = document.getElementById('weatherResult');
        const loadingSpinner = document.getElementById('loadingSpinner');
        const errorContainer = document.getElementById('errorContainer');

        // Iconos del clima
        const weatherIcons = {
            '01d': 'fa-sun', '01n': 'fa-moon',
            '02d': 'fa-cloud-sun', '02n': 'fa-cloud-moon',
            '03': 'fa-cloud', '04': 'fa-cloud',
            '09': 'fa-cloud-rain', '10': 'fa-cloud-showers-heavy',
            '11': 'fa-bolt', '13': 'fa-snowflake',
            '50': 'fa-smog'
        };

        // Inicialización
        document.addEventListener('DOMContentLoaded', () => {
            if (currentApiKey && currentApiKey.length === 32) {
                initWeatherApp();
            }
        });

        // Guardar API Key
        saveApiKeyBtn.addEventListener('click', () => {
            const apiKey = apiKeyInput.value.trim();
            if (apiKey.length === 32) {
                localStorage.setItem(STORAGE_KEY, apiKey);
                currentApiKey = apiKey;
                initWeatherApp();
                testApiKey(apiKey);
            } else {
                showError('La API Key debe tener exactamente 32 caracteres');
            }
        });

        // Iniciar aplicación de clima
        function initWeatherApp() {
            apiKeyForm.style.display = 'none';
            weatherApp.style.display = 'block';
            getWeatherData(citySelect.value);
        }

        // Probar la API Key
        async function testApiKey(apiKey) {
            showLoading();
            try {
                const response = await fetch(
                    `https://api.openweathermap.org/data/2.5/weather?q=Santo Domingo,DO&appid=${apiKey}`
                );
                
                if (!response.ok) {
                    const errorData = await response.json();
                    throw new Error(errorData.message || 'API Key inválida');
                }
                
                // Si llega aquí, la API Key es válida
                const data = await response.json();
                console.log('API Key válida:', data);
                
            } catch (error) {
                localStorage.removeItem(STORAGE_KEY);
                apiKeyForm.style.display = 'block';
                weatherApp.style.display = 'none';
                showError(`Error: ${error.message}. Por favor verifica tu API Key.`);
            } finally {
                hideLoading();
            }
        }

        // Obtener datos del clima
        async function getWeatherData(city) {
            showLoading();
            
            try {
                const response = await fetch(
                    `https://api.openweathermap.org/data/2.5/weather?q=${encodeURIComponent(city)},DO&appid=${currentApiKey}&units=metric&lang=es`
                );

                if (!response.ok) {
                    const errorData = await response.json();
                    throw new Error(errorData.message || 'Error al obtener datos');
                }

                const data = await response.json();
                displayWeatherData(data, city);
                
            } catch (error) {
                showError(`Error: ${error.message}`);
                console.error('Error:', error);
            } finally {
                hideLoading();
            }
        }

        // Mostrar datos del clima
        function displayWeatherData(data, city) {
            const weatherIconCode = data.weather[0].icon;
            const weatherIconClass = weatherIcons[weatherIconCode] || 'fa-cloud';
            
            document.getElementById('weatherIcon').className = `fas ${weatherIconClass}`;
            document.getElementById('cityName').textContent = city;
            document.getElementById('temperature').textContent = `${Math.round(data.main.temp)}°C`;
            document.getElementById('tempMin').textContent = `${Math.round(data.main.temp_min)}°C`;
            document.getElementById('tempMax').textContent = `${Math.round(data.main.temp_max)}°C`;
            document.getElementById('humidity').textContent = `${data.main.humidity}%`;
            document.getElementById('windSpeed').textContent = `${Math.round(data.wind.speed * 3.6)} km/h`;
            
            const now = new Date();
            document.getElementById('updateTime').textContent = 
                `Actualizado: ${now.toLocaleDateString()} ${now.toLocaleTimeString()}`;
            
            weatherResult.style.display = 'block';
            errorContainer.style.display = 'none';
        }

        function showLoading() {
            loadingSpinner.style.display = 'block';
            weatherResult.style.display = 'none';
            errorContainer.style.display = 'none';
        }

        function hideLoading() {
            loadingSpinner.style.display = 'none';
        }

        function showError(message) {
            errorContainer.textContent = message;
            errorContainer.style.display = 'block';
            weatherResult.style.display = 'none';
        }
    </script>
</body>
</html>