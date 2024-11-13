<?php

require_once("models/Movie.php");
require_once("models/Message.php");
require_once("dao/UserDAO.php");
require_once("dao/MovieDAO.php");
require_once("globals.php");
require_once("db.php");

$message = new Message($BASE_URL);
$userDAO = new UserDAO($conn, $BASE_URL);
$movieDAO = new MovieDAO($conn, $BASE_URL);

// Resgata o tipo de formulário
$type = filter_input(INPUT_POST, "type");

// Resgatando dados do usuário pelo token
$userData = $userDAO->verifyToken(true);

if ($type === "add-movie") {

  // Receber os dados dos inputs
  $title = filter_input(INPUT_POST, "title");
  $description = filter_input(INPUT_POST, "description");
  $trailer = filter_input(INPUT_POST, "trailer");
  $category = filter_input(INPUT_POST, "category");
  $length = filter_input(INPUT_POST, "length");

  $movie = new Movie();

  // Validação mínima de dados
  if (!empty($title) && !empty($description) && !empty($category)) {

    $movie->title = $title;
    $movie->description = $description;
    $movie->trailer = $trailer;
    $movie->category = $category;
    $movie->length = $length;
    $movie->users_id = $userData->id;

    // Upload da imagem do filme
    if (isset($_FILES["image"]) && !empty($_FILES["image"]["tmp_name"])) {

      $image = $_FILES["image"];
      $imageTypesAllowed = ["image/jpeg", "image/jpg", "image/png"];

      // Checagem de tipo de imagem
      if (in_array($image["type"], $imageTypesAllowed)) {

        // Checar se é jpeg/jpg
        if (in_array($image["type"], ["image/jpeg", "image/jpg"])) {

          $imageFile = imagecreatefromjpeg($image["tmp_name"]);
        } else {

          // Imagem é png
          $imageFile = imagecreatefrompng($image["tmp_name"]);
        }

        // Cria o hash da imagem
        $imageName = $movie->imageGenerateName();

        imagejpeg($imageFile, "./img/movies/" . $imageName, 100);

        // Salva no banco
        $movie->image = $imageName;
      } else {

        $message->setMessage("Tipo inválido de imagem, insira png ou jpg.", "error", "back");
      }
    }

    $movieDAO->createMovie($movie);
  } else {
    $message->setMessage("Você precisa adicionar pelo menos: título, descrição e categoria.", "error", "back");
  }
} else if ($type === "delete-movie") {
  // Recebe os dados do formulário
  $id = filter_input(INPUT_POST, "id");
  $movie = $movieDAO->findById($id);

  if ($movie) {
    // Verificar se o filme é do usuário
    if ($movie->users_id === $userData->id) {
      $movieDAO->destroyMovie($movie->id);
    } else {
      $message->setMessage("Informações inválidas!", "error", "/index.php");
    }
  } else {
    $message->setMessage("Informações inválidas!", "error", "/index.php");
  }
} else if ($type === "edit-movie") {
  // Receber os dados dos inputs
  $title = filter_input(INPUT_POST, "title");
  $description = filter_input(INPUT_POST, "description");
  $trailer = filter_input(INPUT_POST, "trailer");
  $category = filter_input(INPUT_POST, "category");
  $length = filter_input(INPUT_POST, "length");
  $id = filter_input(INPUT_POST, "id");

  $movie = $movieDAO->findById($id);

  // Verifica se encontrou filme
  if ($movie) {
    // Verificar se o filme é do usuário
    if ($movie->users_id === $userData->id) {

      // Validação mínima de dados
      if (!empty($title) && !empty($description) && !empty($category)) {
        // Edição do filme
        $movie->title = $title;
        $movie->description = $description;
        $movie->trailer = $trailer;
        $movie->category = $category;
        $movie->length = $length;

        // Upload da imagem do filme
        if (isset($_FILES["image"]) && !empty($_FILES["image"]["tmp_name"])) {

          $image = $_FILES["image"];
          $imageTypesAllowed = ["image/jpeg", "image/jpg", "image/png"];

          // Checagem de tipo de imagem
          if (in_array($image["type"], $imageTypesAllowed)) {

            // Checar se é jpeg/jpg
            if (in_array($image["type"], ["image/jpeg", "image/jpg"])) {

              $imageFile = imagecreatefromjpeg($image["tmp_name"]);
            } else {

              // Imagem é png
              $imageFile = imagecreatefrompng($image["tmp_name"]);
            }

            // Cria o hash da imagem
            $imageName = $movie->imageGenerateName();

            imagejpeg($imageFile, "./img/movies/" . $imageName, 100);

            // Salva no banco
            $movie->image = $imageName;
          } else {

            $message->setMessage("Tipo inválido de imagem, insira png ou jpg.", "error", "back");
          }
        }

        $movieDAO->updateMovie($movie);
      } else {
        $message->setMessage("Você precisa adicionar pelo menos: título, descrição e categoria.", "error", "back");
      }
    } else {
      $message->setMessage("Informações inválidas!", "error", "/index.php");
    }
  } else {
    $message->setMessage("Informações inválidas!", "error", "/index.php");
  }
} else {
  $message->setMessage("Informações inválidas!", "error", "/index.php");
}
