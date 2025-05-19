<?php include('partes/head.php');?>
<style>
    .inputx{
        margin: 10px 0;
    }

    .inputx label{
        display: inline-block;
        width: 60px;
    }

    .inputx input, .inputx select{
        padding: 5px;
        width: 200px;
    }

    .inputx button{
        padding: 5px 10px;
        background-color: green;
        color: white;
        border: none;
        cursor: pointer;
    }

    .inputx button:hover{
        background-color: blue;
    }
</style>
<h2>Calculadora</h2>

<form method="GET" action="Calculadora_result.php">    

    <div class="inputx">
        <label>Valor 1:</label> <input required type="number" name="valor1">
    </div>

    <div class="inputx">
        <label>Valor 2:</label> <input required type="number" name="valor2">
    </div>

    <div class="inputx">
        <select name="operacion" required>
            <option value="">Seleccionar operacion</option>
            <option value="suma">suma</option>
            <option value="resta">resta</option>
            <option value="multiplicacion">multiplicacion</option>
            <option value="divicion">divicion</option>
        </select>
    </div>

    <div class="inputx">
        <button type="submit">Calcualar</button>
    </div>
</form>
<?php include('partes/footer.php');?>