<?php
// Incluir Bootstrap para mejorar UI/UX
echo '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">';

echo "<div class='container mt-4'>";

echo "<h2 class='text-primary'>Matriz con Arreglos Asociativos</h2>";
echo "<p>Esta matriz usa arreglos asociativos donde las claves representan los niveles de conocimiento.</p>";
function cargarAsociativos() {
    return [
        'Básico' => ['Inglés' => 25, 'Francés' => 10, 'Mandarín' => 8, 'Ruso' => 12, 'Portugués' => 30, 'Japonés' => 90],
        'Intermedio' => ['Inglés' => 15, 'Francés' => 5, 'Mandarín' => 4, 'Ruso' => 8, 'Portugués' => 15, 'Japonés' => 25],
        'Avanzado' => ['Inglés' => 10, 'Francés' => 2, 'Mandarín' => 1, 'Ruso' => 4, 'Portugués' => 10, 'Japonés' => 67]
    ];
}
$matriz_asociativa = cargarAsociativos();
mostrarMatriz($matriz_asociativa); //No envia datos numericos, por lo tanto es false

echo "<h2 class='text-primary'>Matriz con Arrays Anidados</h2>";
echo "<p>Esta matriz usa arrays anidados donde los niveles se representan por su índice numérico.</p>";
function cargarAnidado() {
    return [
        [25, 10, 8, 12, 30, 90],
        [15, 5, 4, 8, 15, 25],
        [10, 2, 1, 4, 10, 67]
    ];
}
$matriz_anidada = cargarAnidado();
mostrarMatriz($matriz_anidada, true);//Envia datos numericos, por lo tanto es true

echo "<h2 class='text-primary'>Matriz Combinada</h2>";
echo "<p>Esta matriz combina la estructura asociativa con arrays anidados.</p>";
function cargarCombinada() {
    $niveles = ['Básico', 'Intermedio', 'Avanzado'];
    $idiomas = ['Inglés', 'Francés', 'Mandarín', 'Ruso', 'Portugués', 'Japonés'];
    $valores = [
        [25, 10, 8, 12, 30, 90],
        [15, 5, 4, 8, 15, 25],
        [10, 2, 1, 4, 10, 67]
    ];
    $matriz = [];//arreglo matriz
    foreach ($niveles as $i => $nivel) {
        foreach ($idiomas as $j => $idioma) {
            $matriz[$nivel][$idioma] = $valores[$i][$j];
        //Asigna el valor correspondiente del array $valores a la nueva matriz.
        }
    }
    return $matriz;
}
$matriz_combinada = cargarCombinada();
mostrarMatriz($matriz_combinada);//No envia datos numericos, por lo tanto es false

echo "</div>";

function mostrarMatriz($matriz, $numerico = false) {
    echo "<table class='table table-bordered table-striped'><thead class='table-dark'><tr><th>Nivel</th><th>Inglés</th><th>Francés</th><th>Mandarín</th><th>Ruso</th><th>Portugués</th><th>Japonés</th></tr></thead><tbody>";
    //Crea el encabezado de una tabla HTML

    //Solo se usa si numerico es true
    $i = 0; //Se inicializa un contador $i para los índices numéricos
    foreach ($matriz as $nivel => $idiomas)//es el arreglo con los valores de idiomas para ese nivel
     {
        $nivel_texto = $numerico ? $i : $nivel;
        /*Si $numerico === true, entonces se muestra $i (el número de la fila).
        Si $numerico === false, se muestra $nivel (ej: 'Intermedio').*/
        echo "<tr><td>$nivel_texto</td>";//Imprime la celda con el nombre del nivel
        foreach ($idiomas as $cantidad) {
            echo "<td>$cantidad</td>"; //imprime la cantidad numerica de las personas que estudian X idioma
        }
        echo "</tr>";
        $i++;
    }
    echo "</tbody></table>";
}
?>
