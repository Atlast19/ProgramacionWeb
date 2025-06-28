<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscador de Países</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .search-container {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .results-container {
            background-color: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .country-flag {
            max-width: 100%;
            height: auto;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .loading-spinner {
            display: none;
            margin: 30px auto;
        }
        .error-message {
            display: none;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h1 class="text-center mb-4">Datos de Países 🌍</h1>
                <p class="text-center text-muted mb-4">Encuentra información sobre cualquier país</p>
                
                <div class="search-container">
                    <div class="input-group mb-3">
                        <input type="text" id="countryInput" class="form-control form-control-lg" placeholder="Ej: República Dominicana" value="República Dominicana">
                        <button id="searchBtn" class="btn btn-primary btn-lg">
                            <i class="fas fa-search me-1"></i> Buscar
                        </button>
                    </div>
                </div>

                <div id="loadingSpinner" class="text-center loading-spinner">
                    <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;"></div>
                    <p class="mt-2">Buscando información...</p>
                </div>

                <div id="errorContainer" class="alert alert-danger error-message">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <span id="errorText">No se encontró el país. Verifica el nombre e intenta nuevamente.</span>
                </div>

                <div id="resultsContainer" class="results-container" style="display: none;">
                    <div class="row">
                        <div class="col-md-4 text-center mb-3 mb-md-0">
                            <img id="countryFlag" src="" alt="Bandera del país" class="country-flag">
                        </div>
                        <div class="col-md-8">
                            <h2 id="countryName" class="mb-3"></h2>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Nombre oficial:</strong> <span id="officialName"></span></p>
                                    <p><strong>Capital:</strong> <span id="capital"></span></p>
                                    <p><strong>Continente:</strong> <span id="region"></span></p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Población:</strong> <span id="population"></span></p>
                                    <p><strong>Moneda:</strong> <span id="currency"></span></p>
                                    <p><strong>Idioma(s):</strong> <span id="languages"></span></p>
                                </div>
                            </div>
                                                <div class="card-footer text-muted text-center">
                        <h7>Volver al <a href="../index.php">Inicio</a></h7>
                    </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const API_URL = 'https://restcountries.com/v3.1/name/';
            const elements = {
                input: document.getElementById('countryInput'),
                btn: document.getElementById('searchBtn'),
                spinner: document.getElementById('loadingSpinner'),
                error: document.getElementById('errorContainer'),
                errorText: document.getElementById('errorText'),
                results: document.getElementById('resultsContainer'),
                flag: document.getElementById('countryFlag'),
                name: document.getElementById('countryName'),
                officialName: document.getElementById('officialName'),
                capital: document.getElementById('capital'),
                region: document.getElementById('region'),
                population: document.getElementById('population'),
                currency: document.getElementById('currency'),
                languages: document.getElementById('languages')
            };

            // Event listeners
            elements.btn.addEventListener('click', searchCountry);
            elements.input.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') searchCountry();
            });

            async function searchCountry() {
                const countryName = elements.input.value.trim();
                
                if (!countryName) {
                    showError('Por favor ingresa el nombre de un país');
                    return;
                }

                showLoading();
                hideError();
                hideResults();

                try {
                    const response = await fetch(`${API_URL}${encodeURIComponent(countryName)}`);
                    
                    if (!response.ok) {
                        throw new Error('El país no fue encontrado');
                    }

                    const data = await response.json();
                    
                    if (!data || data.length === 0) {
                        throw new Error('No se encontraron resultados');
                    }

                    displayCountryData(data[0]);
                } catch (error) {
                    console.error('Error:', error);
                    showError(error.message || 'Error al buscar el país. Intenta nuevamente.');
                } finally {
                    hideLoading();
                }
            }

            function displayCountryData(country) {
                // Bandera
                elements.flag.src = country.flags?.png || '';
                elements.flag.alt = `Bandera de ${country.name?.common || 'país'}`;
                
                // Nombres
                elements.name.textContent = country.name?.common || 'Nombre no disponible';
                elements.officialName.textContent = country.name?.official || 'No disponible';
                
                // Datos básicos
                elements.capital.textContent = country.capital?.[0] || 'No disponible';
                elements.region.textContent = country.region || 'No disponible';
                
                // Población
                elements.population.textContent = country.population 
                    ? country.population.toLocaleString('es') 
                    : 'No disponible';
                
                // Moneda
                const currencies = country.currencies ? Object.values(country.currencies) : [];
                elements.currency.textContent = currencies.length > 0
                    ? `${currencies[0].name} (${currencies[0].symbol || 'sin símbolo'})`
                    : 'No disponible';
                
                // Idiomas
                const langs = country.languages ? Object.values(country.languages) : [];
                elements.languages.textContent = langs.length > 0 
                    ? langs.join(', ') 
                    : 'No disponible';
                
                showResults();
            }

            function showLoading() {
                elements.spinner.style.display = 'block';
            }

            function hideLoading() {
                elements.spinner.style.display = 'none';
            }

            function showError(message) {
                elements.errorText.textContent = message;
                elements.error.style.display = 'block';
            }

            function hideError() {
                elements.error.style.display = 'none';
            }

            function showResults() {
                elements.results.style.display = 'block';
            }

            function hideResults() {
                elements.results.style.display = 'none';
            }

            // Búsqueda inicial
            searchCountry();
        });
    </script>
</body>
</html>