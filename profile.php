<?php

require_once("header.php");
require_once("dao/UserDAO.php");
require_once("dao/MovieDAO.php");
require_once("models/User.php");

$user = new User();
$movieDAO = new MovieDAO($conn, $BASE_URL);

// Check de autenticação
$userDAO = new UserDAO($conn, $BASE_URL);

// Receber ID do usuário da url
$id = filter_input(INPUT_GET, "id");

if (empty($id)) {
  if (!empty($userData)) {
    $id = $userData->id;
  } else {
    $message->setMessage("Usuário não encontrado!", "error", "/index.php");
  }
} else {

  $userData = $userDAO->findById($id);

  if (!$userData) {
    $message->setMessage("Usuário não encontrado!", "error", "/index.php");
  }
}

$fullName = $user->getFullName($userData);

if (empty($userData->image)) {
  $userData->image = "user.png";
}

// Filmes que o usuário adicionou
$userMovies = $movieDAO->getMoviesByUserId($id);

?>

<div id="main-container" class="container-fluid">
  <div class="col-md-8 offset-md-2">
    <div class="row profile-container">
      <div class="col-md-12 about-container">
        <h1 class="page-title"><?php echo $fullName ?></h1>
        <div
          id="profile-image-container"
          class="profile-image"
          style="background-image: url('<?php echo $BASE_URL ?>/img/users/<?php echo $userData->image ?>');">

        </div>
        <h3 class="about-title">Sobre:</h3>
        <!-- Se o usuário tiver adicionado bio -->
        <?php if (!empty($userData->bio)) : ?>
          <p class="profile-description">
            <?php echo $userData->bio ?>
          </p>
          <!-- Se o usuário não tiver adicionado bio -->
        <?php else : ?>
          <p class="profile-description">
            O usuário ainda não escreveu nada aqui.
          </p>
        <?php endif ?>
      </div>
      <div class="col-md-12 added-movies-container">
        <h3>Filmes que enviou:</h3>
        <div class="movies-container">
          <?php foreach ($userMovies as $movie) : ?>
            <?php require('templates/movie_card.php'); ?>
          <?php endforeach; ?>
          <?php if (count($userMovies) === 0) : ?>
            <p class="empty-list">
              O usuário ainda não enviou filmes.
            </p>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<?php
require_once("footer.php");
?>