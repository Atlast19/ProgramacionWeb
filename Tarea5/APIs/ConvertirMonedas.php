<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conversor de Monedas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .converter-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            padding: 30px;
            margin-top: 30px;
        }
        .currency-card {
            border-left: 4px solid #28a745;
            transition: all 0.3s ease;
            margin-bottom: 20px;
            padding: 20px;
            background: white;
            border-radius: 8px;
        }
        .currency-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .currency-title {
            color: #1a1a1a;
            font-weight: 600;
            margin-bottom: 15px;
        }
        .currency-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: #28a745;
        }
        .input-container {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            margin-bottom: 30px;
        }
        .loading-spinner {
            display: none;
            margin: 30px auto;
        }
        .no-results {
            text-align: center;
            padding: 30px;
            color: #666;
        }
        .currency-flag {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="text-center mb-5">
                    <h1 class="display-5 mb-3">Conversor de Monedas 💰</h1>
                    <p class="lead text-muted">Convierte dólares (USD) a diferentes monedas</p>
                </div>

                <!-- Entrada de datos -->
                <div class="input-container">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <div class="input-group input-group-lg">
                                <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                                <input type="number" id="amountInput" class="form-control" placeholder="Ingresa cantidad en USD" step="0.01" min="0" value="20">
                            </div>
                        </div>
                        <div class="col-md-4 mt-3 mt-md-0">
                            <button id="convertBtn" class="btn btn-primary btn-lg w-100">
                                <i class="fas fa-exchange-alt me-2"></i>Convertir
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Spinner de carga -->
                <div id="loadingSpinner" class="text-center loading-spinner">
                    <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;"></div>
                    <p class="mt-3">Obteniendo tasas de cambio...</p>
                </div>

                <!-- Mensaje de error -->
                <div id="errorContainer" class="alert alert-danger" style="display: none;"></div>

                <!-- Resultados -->
                <div id="resultsContainer" class="converter-container" style="display: none;">
                    <h2 class="text-center mb-4">Resultados de Conversión</h2>
                    <div id="currencyList"></div>
                                        <div class="card-footer text-muted text-center">
                        <h7>Volver al <a href="../index.php">Inicio</a></h7>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Elementos del DOM
            const elements = {
                amountInput: document.getElementById('amountInput'),
                convertBtn: document.getElementById('convertBtn'),
                loadingSpinner: document.getElementById('loadingSpinner'),
                errorContainer: document.getElementById('errorContainer'),
                resultsContainer: document.getElementById('resultsContainer'),
                currencyList: document.getElementById('currencyList')
            };

            // Monedas a mostrar
            const currencies = {
                'DOP': ['Pesos Dominicanos', 'fas fa-peso-sign', 'https://flagcdn.com/w80/do.png'],
                'EUR': ['Euros', 'fas fa-euro-sign', 'https://flagcdn.com/w80/eu.png'],
                'GBP': ['Libras Esterlinas', 'fas fa-pound-sign', 'https://flagcdn.com/w80/gb.png']
            };

            // Configuración de APIs
            const apiEndpoints = [
                'https://api.exchangerate-api.com/v4/latest/USD',
                'https://open.er-api.com/v6/latest/USD',
                'https://api.frankfurter.app/latest?from=USD'
            ];

            // Event listeners
            elements.convertBtn.addEventListener('click', convertCurrency);
            elements.amountInput.addEventListener('keypress', (e) => e.key === 'Enter' && convertCurrency());

            // Función principal de conversión
            async function convertCurrency() {
                const amount = parseFloat(elements.amountInput.value);
                
                if (!validateInput(amount)) return;

                showLoading();
                
                try {
                    const rates = await getExchangeRates();
                    if (rates) {
                        displayResults(amount, rates);
                    } else {
                        throw new Error('No se pudo obtener tasas de cambio');
                    }
                } catch (error) {
                    showError('Error al obtener tasas. Intente nuevamente.');
                    console.error('Error:', error);
                } finally {
                    hideLoading();
                }
            }

            // Validación de entrada
            function validateInput(amount) {
                if (isNaN(amount) || amount <= 0) {
                    showError('Ingrese una cantidad válida mayor que cero');
                    return false;
                }
                return true;
            }

            // Obtener tasas de cambio
            async function getExchangeRates() {
                for (const endpoint of apiEndpoints) {
                    try {
                        const response = await fetch(endpoint);
                        if (!response.ok) continue;
                        
                        const data = await response.json();
                        return data.rates || data;
                    } catch (error) {
                        console.warn(`Error con API ${endpoint}:`, error);
                    }
                }
                return getFallbackRates();
            }

            // Datos de respaldo
            function getFallbackRates() {
                console.warn('Usando tasas de cambio de respaldo');
                return {
                    'DOP': 56.50,
                    'EUR': 0.85,
                    'GBP': 0.73,
                    'USD': 1.00
                };
            }

            // Mostrar resultados
            function displayResults(amount, rates) {
                elements.currencyList.innerHTML = '';
                
                for (const [code, info] of Object.entries(currencies)) {
                    const rate = rates[code];
                    if (!rate) continue;
                    
                    const convertedAmount = (amount * rate).toFixed(2);
                    
                    const currencyItem = document.createElement('div');
                    currencyItem.className = 'currency-card';
                    currencyItem.innerHTML = `
                        <div class="d-flex align-items-center mb-3">
                            <img src="${info[2]}" class="currency-flag" alt="${info[0]}" onerror="this.style.display='none'">
                            <h3 class="currency-title mb-0">${info[0]} (${code})</h3>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <i class="${info[1]} me-2"></i>
                                <span class="currency-value">${convertedAmount}</span>
                            </div>
                            <small class="text-muted">1 USD = ${rate.toFixed(4)} ${code}</small>
                        </div>
                    `;
                    elements.currencyList.appendChild(currencyItem);
                }
                
                elements.resultsContainer.style.display = 'block';
                elements.errorContainer.style.display = 'none';
            }

            function showLoading() {
                elements.loadingSpinner.style.display = 'block';
                elements.resultsContainer.style.display = 'none';
                elements.errorContainer.style.display = 'none';
            }

            function hideLoading() {
                elements.loadingSpinner.style.display = 'none';
            }

            function showError(message) {
                elements.errorContainer.innerHTML = `
                    <i class="fas fa-exclamation-circle me-2"></i>
                    ${message}
                `;
                elements.errorContainer.style.display = 'block';
                elements.resultsContainer.style.display = 'none';
            }

            // Convertir automáticamente al cargar
            if (elements.amountInput.value) elements.convertBtn.click();
        });
    </script>
</body>
</html>