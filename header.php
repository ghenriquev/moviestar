<?php
require_once("globals.php");
require_once("db.php");
require_once("models/Message.php");
require_once("dao/UserDAO.php");

$message = new Message($BASE_URL);

$flassMessage = $message->getMessage();
if (!empty($flassMessage["msg"])) {
  // Limpar mensagem
  $message->clearMessage();
}

$userDAO = new UserDAO($conn, $BASE_URL);
$userData = $userDAO->verifyToken(false);


?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Moviestar</title>
  <link
    rel="shortcut icon"
    href="<?php echo $BASE_URL ?>/img/moviestar.ico"
    type="image/x-icon" />
  <!-- CSS -->
  <link
    rel="stylesheet"
    href="<?php echo $BASE_URL ?>/css/styles.css" />
  <!-- BOOTSTRAP -->
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.css"
    integrity="sha512-VcyUgkobcyhqQl74HS1TcTMnLEfdfX6BbjhH8ZBjFU9YTwHwtoRtWSGzhpDVEJqtMlvLM2z3JIixUOu63PNCYQ=="
    crossorigin="anonymous"
    referrerpolicy="no-referrer" />
  <!-- FONT AWESOME -->
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
    integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
    crossorigin="anonymous"
    referrerpolicy="no-referrer" />
</head>

<body>

  <header>

    <nav id="main-navbar" class="navbar navbar-expand-lg">

      <!-- LOGO NAVBAR -->
      <a href="<?php echo $BASE_URL ?>" class="navbar-brand">
        <img
          src="<?php echo $BASE_URL ?>/img/logo.svg"
          alt="Moviestar Logo"
          id="logo">
        <span id="moviestar-title">MovieStar</span>
      </a>

      <!-- MENU HAMBURGUER MOBILE -->
      <button
        class="navbar-toggler"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#navbar"
        aria-controls="navbar"
        aria-expanded="false"
        aria-label="Toggle navigation">
        <i class="fas fa-bars"></i>
      </button>

      <!-- BARRA DE PESQUISA -->
      <form
        action="<?php echo $BASE_URL ?>/search.php"
        method="GET"
        id="search-form"
        class="form-inline my-2 my-lg-0">

        <input
          type="text"
          name="search_query"
          id="search"
          class="form-control mr-sm-2"
          type="search"
          placeholder="Buscar filmes"
          aria-label="Search">

        <button type="submit" class="btn my-2 my-sm-0">
          <i class="fas fa-search"></i>
        </button>

      </form>

      <!-- BOTÃO ENTRAR/CADASTRAR -->
      <div class="collapse navbar-collapse" id="navbar">
        <ul class="navbar-nav">
          <!-- NAVBAR SE USUÁRIO LOGADO -->
          <?php if ($userData) : ?>
            <li class="nav-item">
              <a href="<?php echo $BASE_URL ?>/new_movie.php" class="nav-link">
                <i class="far fa-plus-square"></i>
                Incluir filme
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo $BASE_URL ?>/dashboard.php" class="nav-link">
                Meus Filmes
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo $BASE_URL ?>/editprofile.php" class="nav-link bold">
                <?php echo $userData->name ?>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo $BASE_URL ?>/logout.php" class="nav-link">
                Sair
              </a>
            </li>
          <?php else: ?>
            <!-- BOTÃO ENTRAR/CADASTRAR SE NÃO HÁ USUÁRIO LOGADO -->
            <li class="nav-item">
              <a href="<?php echo $BASE_URL ?>/login_page.php" class="nav-link">
                Entrar / Cadastrar
              </a>
            </li>
          <?php endif; ?>
        </ul>
      </div>
    </nav>
  </header>

  <!-- MENSAGEM DE ERRO OU SUCESSO -->

  <?php if (!empty($flassMessage["msg"])) : ?>
    <div class="msg-container">
      <p class="msg <?php echo $flassMessage["type"] ?>">
        <?php echo $flassMessage["msg"] ?>
      </p>
    </div>
  <?php endif; ?>