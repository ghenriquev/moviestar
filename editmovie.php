<?php

require_once("header.php");
require_once("dao/UserDAO.php");
require_once("dao/MovieDAO.php");
require_once("models/User.php");

// Check de autenticação
$userDAO = new UserDAO($conn, $BASE_URL);
$userData = $userDAO->verifyToken(true);

// Pegar o id do filme
$movieId = filter_input(INPUT_GET, "id");
$movieDAO = new MovieDAO($conn, $BASE_URL);

if (empty($movieId)) {
  $message->setMessage("O filme não foi encontrado!", "error", "/index.php");
} else {
  $movie = $movieDAO->findById($movieId);
  // Verifica se o filme existe
  if (!$movie) {
    $message->setMessage("O filme não foi encontrado!", "error", "/index.php");
  }
}

// Checar se o filme tem imagem
if (empty($movie->image)) {
  $movie->image = "movie_cover.jpg";
}

?>

<div id="main-container" class="container-fluid">
  <div class="col-md-12">
    <div class="row">
      <div class="col-md-6 offset-md-1">
        <h1><?php echo $movie->title ?></h1>
        <p class="page-description">Altere os dados do filme no formulário abaixo:</p>
        <form
          action="<?php echo $BASE_URL ?>/movie_process.php"
          method="post"
          enctype="multipart/form-data"
          id="edit-movie-form">
          <input type="hidden" name="type" value="edit-movie">
          <input type="hidden" name="id" value="<?php echo $movie->id ?>">

          <div class="form-group">
            <label for="title">Título</label>
            <input
              class="form-control"
              id="title"
              type="text"
              name="title"
              placeholder="Insira o título do filme"
              value="<?php echo $movie->title ?>">
          </div>

          <div class="form-group">
            <label for="image">Imagem</label>
            <input
              class="form-control"
              type="file"
              name="image"
              id="image">
          </div>

          <div class="form-group">
            <label for="length">Duração</label>
            <input
              class="form-control"
              id="length"
              type="text"
              name="length"
              placeholder="Insira a duração do filme"
              value="<?php echo $movie->length ?>">
          </div>

          <div class="form-group">
            <label for="category">Categoria</label>
            <select
              name="category"
              id="category"
              class="form-control">
              <option value="">Selecione</option>
              <option value="Ação" <?php echo $movie->category === "Ação" ? "selected" : "" ?>>Ação</option>
              <option value="Aventura" <?php echo $movie->category === "Aventura" ? "selected" : "" ?>>Aventura</option>
              <option value="Animação" <?php echo $movie->category === "Animação" ? "selected" : "" ?>>Animação</option>
              <option value="Comédia" <?php echo $movie->category === "Comédia" ? "selected" : "" ?>>Comédia</option>
              <option value="Drama" <?php echo $movie->category === "Drama" ? "selected" : "" ?>>Drama</option>
              <option value="Documentário" <?php echo $movie->category === "Documentário" ? "selected" : "" ?>>Documentário</option>
              <option value="Ficção Científica" <?php echo $movie->category === "Ficção Científica" ? "selected" : "" ?>>Ficção Científica</option>
              <option value="Fantasia" <?php echo $movie->category === "Fantasia" ? "selected" : "" ?>>Fantasia</option>
              <option value="Romance" <?php echo $movie->category === "Romance" ? "selected" : "" ?>>Romance</option>
              <option value="Terror" <?php echo $movie->category === "Terror" ? "selected" : "" ?>>Terror</option>
              <option value="Suspense" <?php echo $movie->category === "Suspense" ? "selected" : "" ?>>Suspense</option>
              <option value="Musical" <?php echo $movie->category === "Musical" ? "selected" : "" ?>>Musical</option>
              <option value="Biografia" <?php echo $movie->category === "Biografia" ? "selected" : "" ?>>Biografia</option>
              <option value="Guerra" <?php echo $movie->category === "Guerra" ? "selected" : "" ?>>Guerra</option>
              <option value="Policial" <?php echo $movie->category === "Policial" ? "selected" : "" ?>>Policial</option>
              <option value="Esporte" <?php echo $movie->category === "Esporte" ? "selected" : "" ?>>Esporte</option>
              <option value="Histórico" <?php echo $movie->category === "Histórico" ? "selected" : "" ?>>Histórico</option>
              <option value="Faroeste" <?php echo $movie->category === "Faroeste" ? "selected" : "" ?>>Faroeste</option>
              <option value="Mistério" <?php echo $movie->category === "Mistério" ? "selected" : "" ?>>Mistério</option>
              <option value="Infantil" <?php echo $movie->category === "Infantil" ? "selected" : "" ?>>Infantil</option>
              <option value="Noir" <?php echo $movie->category === "Noir" ? "selected" : "" ?>>Noir</option>
            </select>
          </div>

          <div class="form-group">
            <label for="trailer">Trailer do filme</label>
            <input
              class="form-control"
              id="trailer"
              type="url"
              name="trailer"
              placeholder="Insira o link do trailer"
              value="<?php echo $movie->trailer ?>">
          </div>

          <div class="form-group">
            <label for="description">Descrição do filme</label>
            <textarea
              class="form-control"
              name="description"
              id="description"
              rows="5"
              placeholder="Descreva o filme..."><?php echo $movie->description ?>
            </textarea>
          </div>
          <input type="submit" class="btn card-btn" value="Editar filme">
        </form>
      </div>
      <div class="col-md-3">
        <div class="movie-image-container" style="background-image: url('<?php echo $BASE_URL ?>/img/movies/<?php echo $movie->image ?>');"></div>
      </div>
    </div>
  </div>
</div>

<?php
require_once("footer.php");
?>