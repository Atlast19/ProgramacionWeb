<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PokéExplorer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --pokemon-red: #FF0000;
            --pokemon-blue: #3B4CCA;
            --pokemon-yellow: #FFDE00;
        }
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }
        .pokemon-card {
            background: white;
            border-radius: 15px;
            border: 5px solid var(--pokemon-red);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            overflow: hidden;
            margin-top: 20px;
        }
        .pokemon-header {
            background-color: var(--pokemon-red);
            color: white;
            padding: 15px;
            text-align: center;
            position: relative;
        }
        .pokemon-image {
            background-color: #f8f8f8;
            padding: 20px;
            text-align: center;
            border-bottom: 3px solid var(--pokemon-blue);
        }
        .pokemon-image img {
            width: 200px;
            height: 200px;
            object-fit: contain;
        }
        .pokemon-details {
            padding: 20px;
        }
        .pokemon-ability {
            background-color: var(--pokemon-yellow);
            color: black;
            padding: 5px 10px;
            border-radius: 20px;
            margin-right: 5px;
            margin-bottom: 5px;
            display: inline-block;
            font-size: 0.9rem;
        }
        .pokemon-stats {
            background-color: var(--pokemon-blue);
            color: white;
            padding: 15px;
            border-radius: 0 0 10px 10px;
        }
        .search-container {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            margin-bottom: 20px;
            border: 3px solid var(--pokemon-red);
        }
        .btn-pokemon {
            background-color: var(--pokemon-red);
            color: white;
            border: none;
            font-weight: bold;
        }
        .btn-pokemon:hover {
            background-color: #cc0000;
        }
        .type-badge {
            padding: 5px 15px;
            border-radius: 20px;
            color: white;
            font-weight: bold;
            text-transform: capitalize;
            margin-right: 5px;
            font-size: 0.9rem;
        }
        .title {
            color: var(--pokemon-red);
            margin-bottom: 10px;
        }
        #pokemonSound {
            display: none;
        }
        .sound-btn {
            cursor: pointer;
            color: var(--pokemon-blue);
            font-size: 1.5rem;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="text-center mb-4">
                    <h1 class="title">PokéExplorer</h1>
                    <p class="text-muted">¡Descubre información sobre tus Pokémon favoritos!</p>
                </div>

                <!-- Formulario de búsqueda -->
                <div class="search-container">
                    <div class="input-group">
                        <input type="text" id="pokemonInput" class="form-control" placeholder="Escribe el nombre de un Pokémon" value="pikachu">
                        <button id="searchBtn" class="btn btn-pokemon" type="button">
                            <i class="fas fa-search me-1"></i> Buscar
                        </button>
                    </div>
                    <small class="text-muted mt-2 d-block">Ejemplos: pikachu, charizard, bulbasaur</small>
                </div>

                <!-- Resultados -->
                <div id="loadingSpinner" class="text-center my-4" style="display: none;">
                    <div class="spinner-border text-danger"></div>
                    <p class="mt-2">Buscando Pokémon...</p>
                </div>

                <div id="errorContainer" class="alert alert-danger" style="display: none;"></div>

                <!-- Tarjeta de Pokémon -->
                <div id="pokemonCard" class="pokemon-card" style="display: none;">
                    <div class="pokemon-header">
                        <h2 id="pokemonName" class="mb-0"></h2>
                        <span id="pokemonId" class="badge bg-light text-dark position-absolute top-0 start-0 m-2"></span>
                        <div id="pokemonTypes" class="mt-2"></div>
                    </div>
                    
                    <div class="pokemon-image">
                        <img id="pokemonSprite" src="" alt="Pokémon" class="img-fluid">
                        <div class="sound-btn" id="soundBtn">
                            <i class="fas fa-volume-up"></i> Escuchar
                        </div>
                        <audio id="pokemonSound"></audio>
                    </div>
                    
                    <div class="pokemon-details">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <h5>Experiencia base</h5>
                                <div id="baseExperience" class="fs-5"></div>
                            </div>
                            <div class="col-md-6">
                                <h5>Altura / Peso</h5>
                                <div id="heightWeight" class="fs-5"></div>
                            </div>
                        </div>
                        
                        <h5>Habilidades</h5>
                        <div id="abilities"></div>
                    </div>
                    
                    <div class="pokemon-stats">
                        <div class="row text-center">
                            <div class="col">
                                <h6>HP</h6>
                                <div id="hpStat"></div>
                            </div>
                            <div class="col">
                                <h6>Ataque</h6>
                                <div id="attackStat"></div>
                            </div>
                            <div class="col">
                                <h6>Defensa</h6>
                                <div id="defenseStat"></div>
                            </div>
                            <div class="col">
                                <h6>Velocidad</h6>
                                <div id="speedStat"></div>
                            </div>
                            
                        </div>
                    </div>
                </div>
                <div class="card-footer text-muted text-center">
                    <h7>Volver al <a href="../index.php">Inicio</a></h7>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Colores para tipos de Pokémon
        const typeColors = {
            normal: '#A8A878',
            fire: '#F08030',
            water: '#6890F0',
            electric: '#F8D030',
            grass: '#78C850',
            ice: '#98D8D8',
            fighting: '#C03028',
            poison: '#A040A0',
            ground: '#E0C068',
            flying: '#A890F0',
            psychic: '#F85888',
            bug: '#A8B820',
            rock: '#B8A038',
            ghost: '#705898',
            dragon: '#7038F8',
            dark: '#705848',
            steel: '#B8B8D0',
            fairy: '#EE99AC'
        };

        // Elementos del DOM
        const pokemonInput = document.getElementById('pokemonInput');
        const searchBtn = document.getElementById('searchBtn');
        const pokemonCard = document.getElementById('pokemonCard');
        const pokemonName = document.getElementById('pokemonName');
        const pokemonId = document.getElementById('pokemonId');
        const pokemonSprite = document.getElementById('pokemonSprite');
        const baseExperience = document.getElementById('baseExperience');
        const abilities = document.getElementById('abilities');
        const heightWeight = document.getElementById('heightWeight');
        const loadingSpinner = document.getElementById('loadingSpinner');
        const errorContainer = document.getElementById('errorContainer');
        const pokemonTypes = document.getElementById('pokemonTypes');
        const soundBtn = document.getElementById('soundBtn');
        const pokemonSound = document.getElementById('pokemonSound');
        const hpStat = document.getElementById('hpStat');
        const attackStat = document.getElementById('attackStat');
        const defenseStat = document.getElementById('defenseStat');
        const speedStat = document.getElementById('speedStat');

        // Buscar Pokémon
        searchBtn.addEventListener('click', searchPokemon);
        pokemonInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') searchPokemon();
        });

        // Reproducir sonido
        soundBtn.addEventListener('click', function() {
            pokemonSound.play();
        });

        function searchPokemon() {
            const name = pokemonInput.value.trim().toLowerCase();
            if (!name) {
                showError('Por favor ingresa un nombre de Pokémon');
                return;
            }
            showLoading();
            getPokemonData(name);
        }

        async function getPokemonData(name) {
            try {
                // Obtener datos básicos del Pokémon
                const response = await fetch(`https://pokeapi.co/api/v2/pokemon/${name}`);
                if (!response.ok) throw new Error('Pokémon no encontrado');
                const pokemonData = await response.json();
                
                // Obtener datos de especie para el sonido
                const speciesResponse = await fetch(pokemonData.species.url);
                const speciesData = await speciesResponse.json();
                
                displayPokemon(pokemonData, speciesData);
            } catch (error) {
                showError(`Error: ${error.message}. Intenta con otro nombre.`);
                console.error('Error:', error);
            } finally {
                hideLoading();
            }
        }

        function displayPokemon(pokemon, species) {
            // Información básica
            pokemonName.textContent = capitalize(pokemon.name);
            pokemonId.textContent = `#${pokemon.id.toString().padStart(3, '0')}`;
            pokemonSprite.src = pokemon.sprites.other['official-artwork'].front_default || pokemon.sprites.front_default;
            
            // Tipos
            pokemonTypes.innerHTML = '';
            pokemon.types.forEach(type => {
                const typeName = type.type.name;
                const badge = document.createElement('span');
                badge.className = 'type-badge';
                badge.style.backgroundColor = typeColors[typeName];
                badge.textContent = typeName;
                pokemonTypes.appendChild(badge);
            });
            
            // Estadísticas
            baseExperience.textContent = pokemon.base_experience || 'N/A';
            heightWeight.textContent = `${(pokemon.height / 10)}m / ${(pokemon.weight / 10)}kg`;
            
            // Habilidades
            abilities.innerHTML = '';
            pokemon.abilities.forEach(ability => {
                const abilityName = ability.ability.name.replace('-', ' ');
                const abilitySpan = document.createElement('span');
                abilitySpan.className = 'pokemon-ability';
                abilitySpan.textContent = capitalize(abilityName);
                abilities.appendChild(abilitySpan);
            });
            
            // Stats
            pokemon.stats.forEach(stat => {
                const statName = stat.stat.name;
                const baseStat = stat.base_stat;
                
                if (statName === 'hp') hpStat.textContent = baseStat;
                else if (statName === 'attack') attackStat.textContent = baseStat;
                else if (statName === 'defense') defenseStat.textContent = baseStat;
                else if (statName === 'speed') speedStat.textContent = baseStat;
            });
            
            // Sonido (si está disponible)
            if (species.cries && species.cries.latest) {
                pokemonSound.src = species.cries.latest;
                soundBtn.style.display = 'block';
            } else {
                soundBtn.style.display = 'none';
            }
            
            pokemonCard.style.display = 'block';
            errorContainer.style.display = 'none';
        }

        function capitalize(str) {
            return str.charAt(0).toUpperCase() + str.slice(1);
        }

        function showLoading() {
            loadingSpinner.style.display = 'block';
            pokemonCard.style.display = 'none';
            errorContainer.style.display = 'none';
        }

        function hideLoading() {
            loadingSpinner.style.display = 'none';
        }

        function showError(message) {
            errorContainer.textContent = message;
            errorContainer.style.display = 'block';
            pokemonCard.style.display = 'none';
            hideLoading();
        }

        // Cargar Pikachu al inicio
        window.addEventListener('load', () => {
            getPokemonData('pikachu');
        });
    </script>
</body>
</html>