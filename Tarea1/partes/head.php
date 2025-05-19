<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tarea1</title>
    <style>
        body{
            font-family: Arial;
            padding: 0;
            margin: 0;
        }

        #principal{
            padding: 10px;
            margin: 100px;
            border: 20px;   
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 1.0);
        }

        

        #menu ul{
            padding: 10px;
            text-align: center;
            background-color: black;

        }

        #menu li{
            list-style: none;
            display: inline;
            margin: 20px;
        }

        #menu a{
            text-decoration: none;
            color: white;
        }

        #menu a :hover{
            color: rgb(124, 125, 134);
        }

        #contenido{
            height: 400px;
        }
    </style>
</head>
<body>
    <div id='principal'>
        <div>
            <h1>Tarea 1</h1>
            <p>la tarea uno es esta</p>
        </div>
        <div id='menu'>
            <ul>
                <li><a href="index.php">Inicio</a></li>
                <li><a href="Tarjeta.php">Tarjeta</a></li>
                <li><a href="Calculadora.php">Calculadora</a></li>
                <li><a href="Adivina.php">Adivina</a></li>
                <li><a href="Acerca_de.php">Acerca de</a></li>
            </ul>
        </div>
        <div id='contenido'>