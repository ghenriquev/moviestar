<?php

require_once("models/User.php");
require_once("models/Message.php");
require_once("dao/UserDAO.php");
require_once("globals.php");
require_once("db.php");

$message = new Message($BASE_URL);
$userDAO = new UserDAO($conn, $BASE_URL);

// Resgate do tipo de form
$type = filter_input(INPUT_POST, "type");
// Aqui é "type" porque é o name do input hidden no login_page.php

// Verificação do tipo de form
if ($type === "register") {

  // Usar o filter_input seria o mesmo que usar a super global $_POST['name'], porém não haveria validação, por isso, usa-se o filter_input.
  // Aqui estamos resgatando os valores que um 
  $name = filter_input(INPUT_POST, "name");
  $lastname = filter_input(INPUT_POST, "lastname");
  $email = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL);
  $password = filter_input(INPUT_POST, "password");
  $confirmpassword = filter_input(INPUT_POST, "confirmpassword");

  // Verificando se as informações mínimas para criar um usuário estão sendo enviadas.
  if ($name && $lastname && $email && $password) {

    // Verificar se as senhas coincidem
    if ($password === $confirmpassword) {

      // Verificar se o e-mail já está cadastrado no sistema
      if ($userDAO->findByEmail($email) === false) {

        $user = new User();

        // Criação de token e senha para o banco

        $userToken = $user->generateToken();
        $finalPassword = $user->generatePassword($password);

        $user->name = $name;
        $user->lastname = $lastname;
        $user->email = $email;
        $user->password = $finalPassword;
        $user->token = $userToken;

        $authUser = true;

        // Criando usuário pelo DAO
        $userDAO->create($user, $authUser);
      } else {
        // Mensagem de erro de e-mail
        $message->setMessage("E-mail já cadastrado, tente outro e-mail.", "error", "back");
      }
    } else {
      // Mensagem de erro de confirmação de senha
      $message->setMessage("As senhas não coincidem, verifique e tente novamente.", "error", "back");
    }
  } else {
    // Enviar mensagem de erro de dados faltantes
    $message->setMessage("Por favor, preencha todos os campos.", "error", "back");
  }
} else if ($type === "login") {
  $email = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL);
  $password = filter_input(INPUT_POST, "password");

  // Tenta autenticar usuário
  if ($userDAO->authenticateUser($email, $password)) {

    // Aqui é setada a mensagem que vai aparecer após login
    $message->setMessage("Seja bem-vindo!", "success", "/index.php");
  } else {

    // Redireciona o usuário, caso não conseguir autenticar
    $message->setMessage("Usuário e/ou senha incorretos.", "error", "back");
  }
} else {
  $message->setMessage("Informações inválidas.", "error", "/index.php");
}
