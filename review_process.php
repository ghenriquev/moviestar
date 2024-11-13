<?php

require_once("models/Movie.php");
require_once("models/Message.php");
require_once("models/Review.php");
require_once("dao/UserDAO.php");
require_once("dao/MovieDAO.php");
require_once("dao/ReviewDAO.php");
require_once("globals.php");
require_once("db.php");

$message = new Message($BASE_URL);
$userDAO = new UserDAO($conn, $BASE_URL);
$movieDAO = new MovieDAO($conn, $BASE_URL);
$reviewDAO = new ReviewDAO($conn, $BASE_URL);

// Resgata o tipo de formulário
$type = filter_input(INPUT_POST, "type");

// Resgata o id do filme
$moviesId = filter_input(INPUT_POST, "movies_id");

// Resgatando dados do usuário pelo token
$userData = $userDAO->verifyToken(true);

if ($type === "create-review") {

  // Recebendo dados do post
  $rating = filter_input(INPUT_POST, "rating");
  $review = filter_input(INPUT_POST, "review");
  $movies_id = filter_input(INPUT_POST, "movies_id");
  $users_id = $userData->id;

  // Verificando se o filme existe
  $movieData = $movieDAO->findById($movies_id);

  if ($movieData) {

    // Verificação de dados mínimos
    if (!empty($rating) && !empty($review) && !empty($movies_id)) {

      // Cria-se o objeto de Review
      $reviewObject = new Review();

      // Passando os dados para o objeto de Review
      $reviewObject->rating = $rating;
      $reviewObject->review = $review;
      $reviewObject->movies_id = $movies_id;
      $reviewObject->users_id = $users_id;

      $reviewDAO->createReview($reviewObject);
    } else {
      $message->setMessage("Você precisa inserir nota e comentário!", "error", "back");
    }
  } else {
    $message->setMessage("O filme não existe!", "error", "/index.php");
  }
} else {

  $message->setMessage("Informações inválidas!", "error", "/index.php");
}
