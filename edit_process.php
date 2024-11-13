<?php

require_once("models/User.php");
require_once("models/Message.php");
require_once("dao/UserDAO.php");
require_once("globals.php");
require_once("db.php");

$message = new Message($BASE_URL);
$userDAO = new UserDAO($conn, $BASE_URL);

$user = new User();

// Resgata o tipo de formulário
$type = filter_input(INPUT_POST, "type");

// Atualizar dados do usuário
if ($type === "update-data") {

  // Resgatando dados do usuário pelo token
  $userData = $userDAO->verifyToken(true);

  // Receber dados do post
  $name = filter_input(INPUT_POST, "name");
  $lastname = filter_input(INPUT_POST, "lastname");
  $email = filter_input(INPUT_POST, "email");
  $bio = filter_input(INPUT_POST, "bio");

  // Preencher os dados do usuário
  $userData->name = $name;
  $userData->lastname = $lastname;
  $userData->email = $email;
  $userData->bio = $bio;

  // Upload da imagem
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
      $imageName = $user->imageGenerateName();


      imagejpeg($imageFile, "./img/users/" . $imageName, 100);

      // Salva no banco
      $userData->image = $imageName;
    } else {
      $message->setMessage("Tipo inválido de imagem, insira png ou jpg.", "error", "back");
    }
  }

  $userDAO->update($userData);

  // Atualizar senha do usuário
} else if ($type === "update-password") {

  // Receber dados do post
  $password = filter_input(INPUT_POST, "password");
  $confirmpassword = filter_input(INPUT_POST, "confirmpassword");

  // Resgata dados do usuário atual
  $userData = $userDAO->verifyToken(true);
  $id = $userData->id;

  // Resgata o hash da senha atual
  $currentPassword = $userDAO->getCurrentPassword($id);

  // Checa se os campos estão vazios para não enviar uma senha vazia para o banco.
  if ($password === "" && $confirmpassword === "") {

    $message->setMessage("Por favor, insira uma senha caso deseje alterar sua senha.", "error", "back");
  } else if ($password === $confirmpassword) {

    if (password_verify($password, $currentPassword)) {
      $message->setMessage("A nova senha deve ser diferente da atual.", "error", "back");
    } else {
      $finalPassword = $user->generatePassword($password);

      $user->password = $finalPassword;
      $user->id = $id;

      $userDAO->changePassword($user);
    }
  } else {

    $message->setMessage("As senhas não são iguais, tente novamente.", "error", "back");
  }
} else {
  $message->setMessage("Informações inválidas!", "error",);
}
