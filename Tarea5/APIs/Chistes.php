<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generador de Chistes en Español</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .joke-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            padding: 30px;
            max-width: 600px;
            margin: 0 auto;
        }
        .joke-setup {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 20px;
            color: #343a40;
        }
        .joke-punchline {
            font-size: 1.3rem;
            color: #495057;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px dashed #dee2e6;
            display: none;
        }
        .reveal-btn {
            margin-top: 20px;
        }
        .loading-spinner {
            margin: 30px auto;
        }
        .error-message {
            display: none;
            margin-top: 20px;
        }
        .refresh-btn {
            margin-top: 30px;
        }
        .joke-type {
            font-size: 0.9rem;
            color: #6c757d;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="joke-container text-center">
                    <h1 class="mb-4">Generador de Chistes en Español 🤣</h1>
                    
                    <div id="loadingSpinner" class="text-center loading-spinner">
                        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;"></div>
                        <p class="mt-2">Buscando un buen chiste...</p>
                    </div>
                    
                    <div id="errorContainer" class="alert alert-danger error-message">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <span id="errorText">Error al cargar el chiste. Intenta recargar la página.</span>
                    </div>
                    
                    <div id="jokeContent" style="display: none;">
                        <div id="jokeType" class="joke-type"></div>
                        <div id="jokeSetup" class="joke-setup"></div>
                        <div id="jokePunchline" class="joke-punchline"></div>
                        <button id="revealBtn" class="btn btn-primary reveal-btn">
                            <i class="fas fa-laugh-wink me-2"></i>¡Dime el remate!
                        </button>
                    </div>
                    
                    <button id="refreshBtn" class="btn btn-outline-primary refresh-btn">
                        <i class="fas fa-sync-alt me-2"></i>Quiero otro chiste
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // API de chistes en español (simulada con datos locales)
            const SPANISH_JOKES = [
                {
                    type: "Chiste de programadores",
                    setup: "¿Qué le dice un bit al otro?",
                    punchline: "Nos vemos en el bus."
                },
                {
                    type: "Chiste de animales",
                    setup: "¿Qué le dice una iguana a su hermana gemela?",
                    punchline: "Somos iguanitas."
                },
                {
                    type: "Chiste de comida",
                    setup: "¿Qué hace una uva verde cuando la pisan?",
                    punchline: "Soporta."
                },
                {
                    type: "Chiste de la vida cotidiana",
                    setup: "¿Por qué el libro de matemáticas estaba triste?",
                    punchline: "Porque tenía demasiados problemas."
                },
                {
                    type: "Chiste de la escuela",
                    setup: "¿Qué le dice un semáforo a otro?",
                    punchline: "No me mires, me estoy cambiando."
                }
            ];

            const elements = {
                spinner: document.getElementById('loadingSpinner'),
                error: document.getElementById('errorContainer'),
                errorText: document.getElementById('errorText'),
                jokeContent: document.getElementById('jokeContent'),
                jokeType: document.getElementById('jokeType'),
                setup: document.getElementById('jokeSetup'),
                punchline: document.getElementById('jokePunchline'),
                revealBtn: document.getElementById('revealBtn'),
                refreshBtn: document.getElementById('refreshBtn')
            };

            // Cargar un chiste al inicio
            fetchJoke();

            // Event listeners
            elements.revealBtn.addEventListener('click', revealPunchline);
            elements.refreshBtn.addEventListener('click', fetchJoke);

            function fetchJoke() {
                showLoading();
                hideError();
                hideJoke();
                hidePunchline();

                // Simulamos un pequeño retraso como si fuera una API real
                setTimeout(() => {
                    try {
                        // Seleccionamos un chiste aleatorio
                        const randomIndex = Math.floor(Math.random() * SPANISH_JOKES.length);
                        const joke = SPANISH_JOKES[randomIndex];
                        
                        if (!joke.setup || !joke.punchline) {
                            throw new Error('El chiste no tiene formato válido');
                        }

                        displayJoke(joke);
                    } catch (error) {
                        console.error('Error:', error);
                        showError(error.message || 'Error al cargar el chiste');
                    } finally {
                        hideLoading();
                    }
                }, 800); // Retraso simulado de 800ms
            }

            function displayJoke(joke) {
                elements.jokeType.textContent = joke.type;
                elements.setup.textContent = joke.setup;
                elements.punchline.textContent = joke.punchline;
                showJoke();
            }

            function revealPunchline() {
                elements.punchline.style.display = 'block';
                elements.revealBtn.style.display = 'none';
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

            function showJoke() {
                elements.jokeContent.style.display = 'block';
                elements.revealBtn.style.display = 'block';
            }

            function hideJoke() {
                elements.jokeContent.style.display = 'none';
            }

            function hidePunchline() {
                elements.punchline.style.display = 'none';
            }
        });
    </script>
</body>
</html>