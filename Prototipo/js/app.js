// Manejo del login
document.addEventListener('DOMContentLoaded', function() {
    // Si estamos en la página de login
    if (document.getElementById('loginForm')) {
        const loginForm = document.getElementById('loginForm');
        const errorMessage = document.getElementById('error-message');
        
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;
            
            // Validación simple (en un prototipo real, esto sería con backend)
            if (username && password) {
                // Guardar usuario "logueado" en sessionStorage
                sessionStorage.setItem('loggedIn', 'true');
                sessionStorage.setItem('username', username);
                
                // Redirigir a la página principal
                window.location.href = 'home.html';
            } else {
                errorMessage.textContent = 'Por favor ingrese usuario y contraseña';
            }
        });
    }
    
    // Si estamos en la página principal o registrar
    if (document.getElementById('logout')) {
        document.getElementById('logout').addEventListener('click', function(e) {
            e.preventDefault();
            sessionStorage.removeItem('loggedIn');
            sessionStorage.removeItem('username');
            window.location.href = 'index.html';
        });
    }
    
    // Verificar autenticación en páginas protegidas
    const protectedPages = ['home.html', 'registrar.html'];
    if (protectedPages.some(page => window.location.pathname.endsWith(page))) {
        if (!sessionStorage.getItem('loggedIn')) {
            window.location.href = 'index.html';
        }
    }
    
    // Manejo del formulario de accidentes
    if (document.getElementById('accidentForm')) {
        const accidentForm = document.getElementById('accidentForm');
        const formMessage = document.getElementById('form-message');
        
        accidentForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Obtener valores del formulario
            const accidentType = document.getElementById('accidentType').value;
            const accidentDate = document.getElementById('accidentDate').value;
            const location = document.getElementById('location').value;
            const description = document.getElementById('description').value;
            const severity = document.getElementById('severity').value;
            
            // En un prototipo real, aquí enviaríamos los datos al servidor
            // Para este prototipo, solo mostramos un mensaje de éxito
            
            formMessage.textContent = 'Accidente registrado exitosamente. Redirigiendo al mapa...';
            
            // Simular guardado y redirección
            setTimeout(() => {
                window.location.href = 'home.html';
            }, 2000);
        });
    }
});