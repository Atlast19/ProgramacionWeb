<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generador de Imágenes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .generator-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            padding: 30px;
            margin-top: 30px;
        }
        .image-card {
            transition: all 0.3s ease;
            margin-bottom: 20px;
            overflow: hidden;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .image-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.15);
        }
        .generated-image {
            width: 100%;
            height: 300px;
            object-fit: cover;
            border-radius: 8px 8px 0 0;
        }
        .image-info {
            padding: 15px;
            background: white;
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
        .category {
            margin-bottom: 15px;
        }
        .category-title {
            font-weight: bold;
            margin-bottom: 8px;
            color: #495057;
        }
        .keyword-btn {
            margin: 3px;
            padding: 5px 12px;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="text-center mb-5">
                    <h1 class="display-5 mb-3">Generador de Imágenes 🖼️</h1>
                    <p class="lead text-muted">Encuentra imágenes por categorías</p>
                </div>

                <!-- Categorías -->
                <div class="input-container">
                    <div class="category">
                        <div class="category-title">Naturaleza</div>
                        <button class="keyword-btn btn btn-outline-primary" data-keyword="playa">Playa</button>
                        <button class="keyword-btn btn btn-outline-primary" data-keyword="montaña">Montaña</button>
                        <button class="keyword-btn btn btn-outline-primary" data-keyword="bosque">Bosque</button>
                    </div>
                    
                    <div class="category">
                        <div class="category-title">Animales</div>
                        <button class="keyword-btn btn btn-outline-primary" data-keyword="perros">Perros</button>
                        <button class="keyword-btn btn btn-outline-primary" data-keyword="gatos">Gatos</button>
                    </div>
                    
                    <div class="category">
                        <div class="category-title">Otros</div>
                        <button class="keyword-btn btn btn-outline-primary" data-keyword="comida">Comida</button>
                        <button class="keyword-btn btn btn-outline-primary" data-keyword="tecnología">Tecnología</button>
                    </div>
                </div>

                <!-- Spinner de carga -->
                <div id="loadingSpinner" class="text-center loading-spinner">
                    <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;"></div>
                    <p class="mt-3">Buscando imágenes...</p>
                </div>

                <!-- Mensaje de error -->
                <div id="errorContainer" class="alert alert-danger" style="display: none;"></div>

                <!-- Resultados -->
                <div id="resultsContainer" class="generator-container" style="display: none;">
                    <h2 class="text-center mb-4">Resultados para: <span id="searchKeyword"></span></h2>
                    <div class="row" id="imageGallery"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Configuración de la API de Pixabay
        const PIXABAY_API_KEY = '38042663-5f9b3e3e5b1e4e8d9a4d5f9b3'; // Key pública
        const PIXABAY_API_URL = 'https://pixabay.com/api/';
        
        document.addEventListener('DOMContentLoaded', function() {
            // Elementos del DOM
            const elements = {
                loadingSpinner: document.getElementById('loadingSpinner'),
                errorContainer: document.getElementById('errorContainer'),
                resultsContainer: document.getElementById('resultsContainer'),
                searchKeyword: document.getElementById('searchKeyword'),
                imageGallery: document.getElementById('imageGallery'),
                keywordBtns: document.querySelectorAll('.keyword-btn')
            };

            // Event listeners para botones de palabras clave
            elements.keywordBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const keyword = this.getAttribute('data-keyword');
                    searchImages(keyword);
                });
            });

            // Función para buscar imágenes
            async function searchImages(keyword) {
                showLoading();
                elements.searchKeyword.textContent = keyword;
                
                try {
                    const images = await fetchImages(keyword);
                    displayImages(images);
                } catch (error) {
                    showError('Error al buscar imágenes. Intenta con otra palabra clave.');
                    console.error('Error:', error);
                } finally {
                    hideLoading();
                }
            }

            // Función para hacer fetch a la API
            async function fetchImages(keyword) {
                try {
                    const response = await fetch(
                        `${PIXABAY_API_URL}?key=${PIXABAY_API_KEY}&q=${encodeURIComponent(keyword)}&per_page=12&lang=es`
                    );
                    
                    if (!response.ok) {
                        throw new Error('Error en la respuesta de la API');
                    }
                    
                    const data = await response.json();
                    return data.hits;
                } catch (error) {
                    console.error('Error al obtener imágenes:', error);
                    throw error;
                }
            }

            // Mostrar imágenes
            function displayImages(images) {
                elements.imageGallery.innerHTML = '';
                
                if (!images || images.length === 0) {
                    elements.imageGallery.innerHTML = `
                        <div class="no-results">
                            <i class="fas fa-image fa-3x mb-3"></i>
                            <p>No se encontraron imágenes</p>
                            <p>Intenta con otra palabra clave</p>
                        </div>
                    `;
                    return;
                }
                
                images.forEach(image => {
                    const imageCol = document.createElement('div');
                    imageCol.className = 'col-md-4 col-sm-6 mb-4';
                    
                    const imageCard = document.createElement('div');
                    imageCard.className = 'image-card';
                    
                    const imgElement = document.createElement('img');
                    imgElement.src = image.webformatURL;
                    imgElement.alt = elements.searchKeyword.textContent;
                    imgElement.className = 'generated-image';
                    imgElement.loading = 'lazy';
                    
                    const imageInfo = document.createElement('div');
                    imageInfo.className = 'image-info';
                    imageInfo.innerHTML = `
                        <p class="mb-1"><small>Por: ${image.user}</small></p>
                        <a href="https://pixabay.com/users/${image.user_id}" target="_blank" class="btn btn-sm btn-outline-primary">Ver más</a>
                    `;
                    
                    imageCard.appendChild(imgElement);
                    imageCard.appendChild(imageInfo);
                    imageCol.appendChild(imageCard);
                    elements.imageGallery.appendChild(imageCol);
                });
                
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

            // Cargar imágenes de "playa" por defecto al inicio
            document.querySelector('[data-keyword="playa"]').click();
        });
    </script>
</body>
</html>