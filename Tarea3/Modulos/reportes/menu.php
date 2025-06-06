<?php
include("../../libreria/principal.php");

define("PAGINA_ACTUAL","estadisticas");
Plantilla::aplicar();

$personajes = DBx::list("personajes");
$profeciones = DBx::list("profeciones");

$edad_total = 0;
$excom = 0;

foreach($personajes as $personaje) {
    $edad_total += $personaje->edad();
    $excom += $personaje->nivel_experiencia; // Aquí también corregí usando += en lugar de =
}

$eprom = $edad_total / count($personajes);
$excom = $excom / count($personajes);

$data = [
    'personajes' => count($personajes),
    'profeciones' => count($profeciones),
    'edad_promedio' => $eprom,
    'nivel_experiencia_comun' => $excom,
];


$profecionasXprofecion = [];
foreach($profeciones as $profecion){
    if(!isset($profecionasXprofecion[$profecion->idx])){
        $profecionasXprofecion[$profecion->idx] = [
            'nombre' => $profecion->nombre,
            'cantidad' => 0
        ];  
    }
}

?>
<h4>Dashboard</h4>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

 <div class="container my-5">
    <h1 class="text-center text-pink mb-4">Panel de Control - Mundo Barbie</h1>

    <div class="row g-4">
      <div class="col-md-3">
        <div class="card text-white bg-primary shadow">
          <div class="card-body">
            <h5 class="card-title">Personajes Registrados</h5>
            <p class="card-text fs-4" id="totalPersonajes"><?= $data['personajes'];?></p>
          </div>
        </div>
      </div>

      <div class="col-md-3">
        <div class="card text-white bg-success shadow">
          <div class="card-body">
            <h5 class="card-title">Profesiones Registradas</h5>
            <p class="card-text fs-4" id="totalProfesiones"><?= $data['profeciones'];?></p>
          </div>
        </div>
      </div>

      <div class="col-md-3">
        <div class="card text-white bg-warning shadow">
          <div class="card-body">
            <h5 class="card-title">Edad Promedio</h5>
            <p class="card-text fs-4" id="edadPromedio"><?= number_format($data['edad_promedio'], 0);?> Años</p>
          </div>
        </div>
      </div>

      <div class="col-md-3">
        <div class="card text-white bg-danger shadow">
          <div class="card-body">
            <h5 class="card-title">Nivel de Experiencia Común</h5>
            <p class="card-text fs-4" id="experienciaComun"><?= number_format($data['nivel_experiencia_comun'],2);?></p>
          </div>
        </div>
      </div>
    </div>

    <div class="row mt-4 g-4">
      <div class="col-md-6">
        <div class="card shadow">
          <div class="card-body">
            <h5 class="card-title">Profesión Mejor y Peor Pagada</h5>
            <p>Mayor salario: <strong id="profesionMayorSalario">--</strong></p>
            <p>Menor salario: <strong id="profesionMenorSalario">--</strong></p>
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="card shadow">
          <div class="card-body">
            <h5 class="card-title">Salario Promedio & Personaje Mejor Pagado</h5>
            <p>Salario promedio: <strong id="salarioPromedio">--</strong></p>
            <p>Mejor salario: <strong id="personajeMejorPagado">--</strong></p>
          </div>
        </div>
      </div>
    </div>

    <div class="card mt-5 shadow">
      <div class="card-body">
        <h5 class="card-title">Distribución de Salarios por Categoría de Profesión</h5>
        <canvas id="graficoSalarios" height="100"></canvas>
      </div>
    </div>
  </div>

  <script>
    // Simulación de datos (puedes conectar con PHP/MySQL en producción)

    document.getElementById('profesionMayorSalario').textContent = 'Doctora ($120,000)';
    document.getElementById('profesionMenorSalario').textContent = 'Cantante ($20,000)';
    document.getElementById('salarioPromedio').textContent = '$58,000';
    document.getElementById('personajeMejorPagado').textContent = 'Barbie Empresaria ($150,000)';

    const ctx = document.getElementById('graficoSalarios').getContext('2d');
    const chart = new Chart(ctx, {
      type: 'bar', // o 'pie'
      data: {
        labels: ['Medicina', 'Arte', 'Ciencia', 'Negocios', 'Deporte'],
        datasets: [{
          label: 'Salario promedio por categoría',
          data: [90000, 40000, 80000, 100000, 50000],
          backgroundColor: [
            '#ff69b4',
            '#ffc107',
            '#28a745',
            '#17a2b8',
            '#6f42c1'
          ],
          borderColor: '#fff',
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            display: false
          },
          tooltip: {
            callbacks: {
              label: function(context) {
                return `$${context.parsed.y.toLocaleString()}`;
              }
            }
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            title: {
              display: true,
              text: 'Salario (USD)'
            }
          }
        }
      }
    });
  </script>

  <style>
    .text-pink {
      color: #e83e8c;
    }
  </style>