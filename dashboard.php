<?php

require_once("header.php");
require_once("dao/UserDAO.php");
require_once("dao/MovieDAO.php");
require_once("models/User.php");

// Check de autenticação
$userDAO = new UserDAO($conn, $BASE_URL);
$movieDAO = new MovieDAO($conn, $BASE_URL);
$userData = $userDAO->verifyToken(true);

$userMovies = $movieDAO->getMoviesByUserId($userData->id);

?>

<div id="main-container" class="container-fluid">
  <h2 class="section-title">Dashboard</h2>
  <p class="section-description">
    Adicione ou atualize as informações dos filmes que você adicionou
  </p>
  <div class="col-md-12" id="add-movie-container">
    <a href="<?php echo $BASE_URL ?>/new_movie.php" class="btn card-btn">
      <i class="fas fa-plus"></i> Adicionar filme
    </a>
  </div>
  <div class="col-md-12" id="movies-dashboard">
    <div class="container mt-5">
      <div class="row bg-dark text-white py-2 rounded-top">
        <div class="col-1">#</div>
        <div class="col-6">Título</div>
        <div class="col-2">Nota</div>
        <div class="col-3">Ações</div>
      </div>

      <?php foreach ($userMovies as $movie) : ?>
        <div class="row border-bottom py-3 align-items-center">
          <div class="col-1 fw-bold"><?php echo $movie->id ?></div>
          <div class="col-6">
            <a href="<?php echo $BASE_URL ?>/movie.php?id=<?php echo $movie->id ?>" class="table-movie-title text-decoration-none">
              <?php echo $movie->title ?>
            </a>
          </div>
          <div class="col-2">
            <i class="fas fa-star"></i> <?php echo $movie->rating ?>
          </div>
          <div class="col-3">
            <a href="<?php echo $BASE_URL ?>/editmovie.php?id=<?php echo $movie->id ?>" class="btn btn-sm edit-btn">
              <i class="far fa-edit"></i> Editar
            </a>
            <form action="<?php echo $BASE_URL ?>/movie_process.php" method="post" class="d-inline">
              <input type="hidden" name="type" value="delete-movie">
              <input type="hidden" name="id" value="<?php echo $movie->id ?>">
              <button type="submit" class="btn btn-sm delete-btn">
                <i class="fas fa-times"></i> Deletar
              </button>
            </form>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

  </div>
</div>

<?php
require_once("footer.php");
?>