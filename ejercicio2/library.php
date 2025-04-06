<?php

session_start();

if (!isset($_SESSION['books'])) {
  $_SESSION['books'] = [];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  function validarCampos($data)
  {
    $errores = [];

    // 1. AUTOR: [APELLIDOS, Nombre] o VARIOS AUTORES o AUTORES VARIOS
    if (!preg_match('/^([A-ZÁÉÍÓÚÑ]{2,},\s[A-ZÁÉÍÓÚÑ][a-záéíóúñ]+|VARIOS AUTORES|AUTORES VARIOS)$/u', $data['author'])) {
      $errores['author'] = 'Formato incorrecto. Use "APELLIDOS, Nombre" o "VARIOS AUTORES".';
    }

    // 2. TÍTULO DEL LIBRO: sin comillas
    if (!preg_match('/^[^"]{2,}$/u', $data['book_title'])) {
      $errores['book_title'] = 'El título no debe estar entre comillas.';
    }

    // 3. NÚMERO DE EDICIÓN: número entero positivo
    if (!preg_match('/^\d{1,3}$/', $data['edition'])) {
      $errores['edition'] = 'Ingrese un número válido de edición.';
    }

    // 4. AÑO DE LA EDICIÓN: debe estar entre paréntesis y ser un año de 4 cifras
    if (!preg_match('/^\(\d{4}\)$/', $data['publish_date'])) {
      $errores['publish_date'] = 'El año debe estar entre paréntesis y tener 4 dígitos, ejemplo: (2024).';
    }

    // 5. ISBN: formato como 978-92-95055-02-5
    if (!preg_match('/^\d{3}-\d{2}-\d{5}-\d{2}-\d{1}$/', $data['isbn'])) {
      $errores['isbn'] = 'Formato ISBN inválido. Use el formato 978-92-95055-02-5.';
    }

    if (empty($data['publisher'])) {
      $errores['publisher'] = 'La editorial no es válida.';
    }

    if (empty($data['publish_place'])) {
      $errores['publish_place'] = 'El lugar de publicación no es válido.';
    }

    return $errores;
  }


  $errores = validarCampos($_POST);

  if (empty($errores)) {
    $book = [
      'author' => $_POST['author'],
      'edition' => $_POST['edition'],
      'publisher' => $_POST['publisher'],
      'publish_date' => $_POST['publish_date'],
      'book_title' => $_POST['book_title'],
      'publish_place' => $_POST['publish_place'],
      'isbn' => $_POST['isbn'],
      'page_count' => $_POST['page_count'],
      'notes' => $_POST['notes']
    ];
    $_SESSION['books'][] = $book;
  }
}


function hasError($campo, $errores)
{
  return isset($errores[$campo]);
}

function getError($campo, $errores)
{
  return $errores[$campo] ?? '';
}

function mostrarLibros($libros)
{
  $html = '';
  foreach ($libros as $libro) {
    $html .= '<tr>';
    foreach ($libro as $campo => $valor) {
      $html .= '<td>' . ($valor) . '</td>';
    }
    $html .= '</tr>';
  }
  return $html;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.4/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-DQvkBjpPgn7RC31MCQoOeC9TI2kdqa4+BSgNMNj8v77fdC77Kj5zpWFTJaaAoMbC" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.4/dist/js/bootstrap.bundle.min.js" integrity="sha384-YUe2LzesAfftltw+PEaao2tjU/QATaW/rOitAq67e0CT0Zi2VVRL0oC4+gAaeBKu" crossorigin="anonymous"></script>
  <title>Biblioteca 📚</title>
</head>

<body>

  <div class="container">
    <div class="row">
      <div class="col-4">
        <form class="mt-5 mx-5" method="POST">
          <div class="row mb-4">
            <div class="col">
              <div class="form-outline">
                <input type="text" id="author" name="author"
                  class="form-control <?= hasError('author', $errores ?? []) ? 'is-invalid' : '' ?>"
                  value="<?= ($_POST['author'] ?? '') ?>" />
                <label class="form-label" for="author">Autor</label>
                <?php if (hasError('author', $errores ?? [])): ?>
                  <div class="invalid-feedback"><?= getError('author', $errores) ?></div>
                <?php endif; ?>
              </div>
            </div>
            <div class="col">
              <div class="form-outline">
                <input type="text" id="edition" name="edition"
                  class="form-control <?= hasError('edition', $errores ?? []) ? 'is-invalid' : '' ?>"
                  value="<?= ($_POST['edition'] ?? '') ?>" />
                <label class="form-label" for="edition">Edición</label>
                <?php if (hasError('edition', $errores ?? [])): ?>
                  <div class="invalid-feedback"><?= getError('edition', $errores) ?></div>
                <?php endif; ?>
              </div>
            </div>
          </div>

          <div class="row mb-4">
            <div class="col">
              <div class="form-outline">
                <input type="text" id="publisher" name="publisher"
                  class="form-control <?= hasError('publisher', $errores) ? 'is-invalid' : '' ?>"
                  value="<?= ($_POST['publisher'] ?? '') ?>" />
                <label class="form-label" for="publisher">Editorial</label>
                <?php if (hasError('publisher', $errores ?? [])): ?>
                  <div class="invalid-feedback"><?= getError('publisher', $errores) ?></div>
                <?php endif; ?>
              </div>
            </div>
            <div class="col">
              <div class="form-outline">
                <input type="text" id="publish_date" name="publish_date"
                  class="form-control <?= hasError('publish_date', $errores ?? []) ? 'is-invalid' : '' ?>"
                  value="<?= ($_POST['publish_date'] ?? '') ?>" />
                <label class="form-label" for="publish_date">Año de edición</label>
                <?php if (hasError('publish_date', $errores ?? [])): ?>
                  <div class="invalid-feedback"><?= getError('publish_date', $errores) ?></div>
                <?php endif; ?>
              </div>
            </div>
          </div>

          <div class="form-outline mb-4">
            <input type="text" id="book_title" name="book_title"
              class="form-control <?= hasError('book_title', $errores ?? []) ? 'is-invalid' : '' ?>"
              value="<?= ($_POST['book_title'] ?? '') ?>" />
            <label class="form-label" for="book_title">Título del libro</label>
            <?php if (hasError('book_title', $errores ?? [])): ?>
              <div class="invalid-feedback"><?= getError('book_title', $errores) ?></div>
            <?php endif; ?>
          </div>

          <div class="form-outline mb-4">
            <input type="text" id="publish_place" name="publish_place"
              class="form-control <?= hasError('publish_place', $errores) ? 'is-invalid' : '' ?>"
              value="<?= ($_POST['publish_place'] ?? '') ?>" />
            <label class="form-label" for="publish_place">Lugar de publicación</label>
            <?php if (hasError('publish_place', $errores ?? [])): ?>
              <div class="invalid-feedback"><?= getError('publish_place', $errores) ?></div>
            <?php endif; ?>
          </div>

          <div class="form-outline mb-4">
            <input type="text" id="isbn" name="isbn"
              class="form-control <?= hasError('isbn', $errores ?? []) ? 'is-invalid' : '' ?>"
              value="<?= ($_POST['isbn'] ?? '') ?>" />
            <label class="form-label" for="isbn">ISBN</label>
            <?php if (hasError('isbn', $errores ?? [])): ?>
              <div class="invalid-feedback"><?= getError('isbn', $errores) ?></div>
            <?php endif; ?>
          </div>

          <div class="form-outline mb-4">
            <input type="number" id="page_count" name="page_count"
              min="1" max="9999"
              class="form-control <?= hasError('page_count', $errores) ? 'is-invalid' : '' ?>"
              value="<?= ($_POST['page_count'] ?? '') ?>" />
            <label class="form-label" for="page_count">Número de páginas</label>
            <?php if (hasError('page_count', $errores ?? [])): ?>
              <div class="invalid-feedback"><?= getError('page_count', $errores) ?></div>
            <?php endif; ?>
          </div>

          <div class="form-outline mb-4">
            <textarea class="form-control" id="notes" name="notes" rows="4"><?= ($_POST['notes'] ?? '') ?></textarea>
            <label class="form-label" for="notes">Notas</label>
          </div>

          <button type="submit" class="btn d-flex justify-content-center btn-primary btn-block mb-4">Agregar libro</button>
        </form>

      </div>
      <div class="col-8">
        <div class="mt-5">
          <?php if (isset($_SESSION['books']) && count($_SESSION['books']) > 0): ?>
            <h4 class="mb-4">Libros agregados</h4>
            <div class="table-responsive">
              <table class="table table-striped table-bordered">
                <thead class="table-dark">
                  <tr>
                    <th>Título</th>
                    <th>Autor</th>
                    <th>Edición</th>
                    <th>Editorial</th>
                    <th>Año</th>
                    <th>Lugar</th>
                    <th>ISBN</th>
                    <th>Páginas</th>
                    <th>Notas</th>
                  </tr>
                </thead>
                <tbody>
                  <?= mostrarLibros($_SESSION['books']); ?>
                </tbody>
              </table>
            </div>
          <?php else: ?>
            <p class="text-muted">No se han agregado libros aún.</p>
          <?php endif; ?>
        </div>
      </div>
    </div>
</body>

</html>