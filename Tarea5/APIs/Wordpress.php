<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WordPress News Explorer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .news-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            padding: 30px;
            margin-top: 30px;
        }
        .site-logo {
            max-height: 80px;
            margin-bottom: 20px;
        }
        .news-card {
            border-left: 4px solid #0073aa;
            transition: all 0.3s ease;
            margin-bottom: 20px;
            padding: 20px;
            background: white;
            border-radius: 8px;
        }
        .news-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .news-title {
            color: #1a1a1a;
            font-weight: 600;
            margin-bottom: 15px;
        }
        .news-excerpt {
            color: #666;
            margin-bottom: 15px;
        }
        .read-more {
            color: #0073aa;
            font-weight: 500;
            text-decoration: none;
        }
        .read-more:hover {
            text-decoration: underline;
        }
        .site-selector {
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
        .featured-image {
            max-height: 200px;
            width: auto;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        .no-results {
            text-align: center;
            padding: 30px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="text-center mb-5">
                    <h1 class="display-5 mb-3">WordPress News Explorer</h1>
                    <p class="lead text-muted">Obtén las últimas noticias de cualquier sitio WordPress</p>
                </div>

                <!-- Selector de sitio -->
                <div class="site-selector">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <select class="form-select form-select-lg" id="siteSelect">
                                <option value="https://techcrunch.com/wp-json/wp/v2">TechCrunch</option>
                                <option value="https://wordpress.org/news/wp-json/wp/v2">WordPress Blog</option>
                                <option value="https://es.wordpress.org/wp-json/wp/v2">WordPress Español</option>
                                <option value="custom">Personalizado (ingresa URL)</option>
                            </select>
                        </div>
                        <div class="col-md-4 mt-3 mt-md-0">
                            <button id="fetchNewsBtn" class="btn btn-primary btn-lg w-100">
                                <i class="fas fa-newspaper me-2"></i>Obtener Noticias
                            </button>
                        </div>
                    </div>
                    <div id="customUrlContainer" class="mt-3" style="display: none;">
                        <input type="text" id="customSiteUrl" class="form-control" placeholder="https://tusitio.com/wp-json/wp/v2">
                        <small class="text-muted mt-1 d-block">Ejemplo: https://ejemplo.com/wp-json/wp/v2</small>
                    </div>
                </div>

                <!-- Resultados -->
                <div id="loadingSpinner" class="text-center loading-spinner">
                    <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;"></div>
                    <p class="mt-3">Cargando noticias...</p>
                </div>

                <div id="errorContainer" class="alert alert-danger" style="display: none;"></div>

                <div id="newsResults" class="news-container" style="display: none;">
                    <div class="text-center mb-4">
                        <img id="siteLogo" src="" alt="Logo del sitio" class="site-logo" style="display: none;">
                        <h2 id="siteName" class="mb-0"></h2>
                    </div>
                    
                    <div id="newsList"></div>
                    <div class="card-footer text-muted text-center">
                        <h7>Volver al <a href="../index.php">Inicio</a></h7>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Elementos del DOM
        const siteSelect = document.getElementById('siteSelect');
        const fetchNewsBtn = document.getElementById('fetchNewsBtn');
        const customUrlContainer = document.getElementById('customUrlContainer');
        const customSiteUrl = document.getElementById('customSiteUrl');
        const loadingSpinner = document.getElementById('loadingSpinner');
        const errorContainer = document.getElementById('errorContainer');
        const newsResults = document.getElementById('newsResults');
        const siteLogo = document.getElementById('siteLogo');
        const siteName = document.getElementById('siteName');
        const newsList = document.getElementById('newsList');

        // Sitios preconfigurados con sus logos y nombres
        const predefinedSites = {
            'https://techcrunch.com/wp-json/wp/v2': {
                name: 'TechCrunch',
                logo: 'https://techcrunch.com/wp-content/uploads/2015/02/tc-logo-2015.png'
            },
            'https://wordpress.org/news/wp-json/wp/v2': {
                name: 'WordPress News',
                logo: 'https://s.w.org/style/images/about/WordPress-logotype-wmark.png'
            },
            'https://es.wordpress.org/wp-json/wp/v2': {
                name: 'WordPress Español',
                logo: 'https://es.wordpress.org/wp-content/themes/pub/wporg-main/images/wordpress-logo-blue.png'
            }
        };

        // Cambiar visibilidad del campo personalizado
        siteSelect.addEventListener('change', function() {
            customUrlContainer.style.display = this.value === 'custom' ? 'block' : 'none';
        });

        // Obtener noticias
        fetchNewsBtn.addEventListener('click', fetchNews);

        async function fetchNews() {
            let apiUrl;
            
            if (siteSelect.value === 'custom') {
                apiUrl = customSiteUrl.value.trim();
                if (!apiUrl) {
                    showError('Por favor ingresa una URL válida');
                    return;
                }
                
                // Validar formato de la URL
                if (!apiUrl.startsWith('http') || !apiUrl.includes('wp-json/wp/v2')) {
                    showError('La URL debe incluir "wp-json/wp/v2"');
                    return;
                }
            } else {
                apiUrl = siteSelect.value;
            }

            showLoading();
            
            try {
                // Obtener las noticias
                const posts = await fetchLatestPosts(apiUrl);
                
                // Obtener información del sitio (si es un sitio preconfigurado)
                const siteInfo = predefinedSites[apiUrl] || {};
                
                displayNews(apiUrl, siteInfo, posts);
            } catch (error) {
                showError(`No se pudieron obtener las noticias. Verifica que la URL sea correcta y que el sitio tenga habilitada la REST API.`);
                console.error('Error:', error);
            } finally {
                hideLoading();
            }
        }

        async function fetchLatestPosts(apiUrl) {
            const response = await fetch(`${apiUrl}/posts?per_page=3&_embed`);
            
            if (!response.ok) {
                // Si falla, intentar sin el parámetro _embed
                const fallbackResponse = await fetch(`${apiUrl}/posts?per_page=3`);
                if (!fallbackResponse.ok) {
                    throw new Error('El sitio no respondió correctamente');
                }
                return await fallbackResponse.json();
            }
            
            return await response.json();
        }

        function displayNews(apiUrl, siteInfo, posts) {
            // Configurar logo y nombre del sitio
            if (siteInfo.logo) {
                siteLogo.src = siteInfo.logo;
                siteLogo.style.display = 'block';
            } else {
                siteLogo.style.display = 'none';
            }
            
            siteName.textContent = siteInfo.name || 'Noticias Recientes';
            
            // Mostrar noticias
            newsList.innerHTML = '';
            
            if (!posts || posts.length === 0) {
                newsList.innerHTML = `
                    <div class="no-results">
                        <i class="fas fa-newspaper fa-3x mb-3"></i>
                        <p>No se encontraron noticias recientes</p>
                    </div>
                `;
            } else {
                posts.forEach(post => {
                    // Limpiar el extracto y limitar su longitud
                    let excerpt = '';
                    if (post.excerpt && post.excerpt.rendered) {
                        excerpt = post.excerpt.rendered.replace(/<[^>]+>/g, '').substring(0, 200);
                        if (post.excerpt.rendered.length > 200) excerpt += '...';
                    } else if (post.content && post.content.rendered) {
                        excerpt = post.content.rendered.replace(/<[^>]+>/g, '').substring(0, 200) + '...';
                    }
                    
                    // Obtener imagen destacada si está disponible
                    let featuredImage = '';
                    if (post._embedded && post._embedded['wp:featuredmedia']) {
                        featuredImage = post._embedded['wp:featuredmedia'][0].source_url;
                    }
                    
                    const newsItem = document.createElement('div');
                    newsItem.className = 'news-card';
                    newsItem.innerHTML = `
                        ${featuredImage ? `<img src="${featuredImage}" class="featured-image img-fluid">` : ''}
                        <h3 class="news-title">${post.title.rendered}</h3>
                        ${excerpt ? `<div class="news-excerpt">${excerpt}</div>` : ''}
                        <a href="${post.link}" target="_blank" class="read-more">
                            Leer más <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    `;
                    newsList.appendChild(newsItem);
                });
            }
            
            newsResults.style.display = 'block';
            errorContainer.style.display = 'none';
        }

        function showLoading() {
            loadingSpinner.style.display = 'block';
            newsResults.style.display = 'none';
            errorContainer.style.display = 'none';
        }

        function hideLoading() {
            loadingSpinner.style.display = 'none';
        }

        function showError(message) {
            errorContainer.innerHTML = `
                <i class="fas fa-exclamation-circle me-2"></i>
                ${message}
            `;
            errorContainer.style.display = 'block';
            newsResults.style.display = 'none';
            hideLoading();
        }

        // Cargar noticias de TechCrunch al inicio
        window.addEventListener('load', () => {
            fetchNews();
        });
    </script>
</body>
</html>