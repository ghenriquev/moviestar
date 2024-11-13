<?php
require_once("header.php");
require_once("dao/UserDAO.php");
require_once("models/User.php");

$userDAO = new UserDAO($conn, $BASE_URL);

$userData ? $userDAO->verifyToken(false) : $userDAO->verifyToken(true);
// Se há dados de usuário na requisição, a view não é protegida, portanto, entrará na página de edição de usuário
// Senão, a view é protegida e retornará a mensagem de erro em verifyToken.

$user = new User();
$fullName = $user->getFullName($userData);

if (empty($userData->image)) {
  $userData->image = "user.png";
}

?>

<div id="main-container" class="container-fluid edit-profile-page">

  <div class="col-md-12">

    <form action="<?php echo $BASE_URL ?>/edit_process.php" method="POST" enctype="multipart/form-data">

      <!-- INPUT HIDDEN PARA ENVIAR O TIPO DO FORM -->
      <input type="hidden" name="type" value="update-data">

      <div class="row">
        <div class="col-md-4">
          <h1><?php echo $fullName ?></h1>

          <p class="page-description">Altere seus dados no formulário abaixo:</p>

          <div class="form-group">
            <label for="name">Nome:</label>
            <input
              type="text"
              class="form-control"
              id="name"
              name="name"
              placeholder="Digite o seu nome"
              value="<?php echo $userData->name ?>">
          </div>

          <div class="form-group">
            <label for="lastname">Sobrenome:</label>
            <input
              type="text"
              class="form-control"
              id="lastname"
              name="lastname"
              placeholder="Digite o seu sobrenome"
              value="<?php echo $userData->lastname ?>">
          </div>

          <div class="form-group">
            <label for="email">E-mail:</label>
            <input
              type="text"
              class="form-control disabled"
              id="email"
              name="email"
              placeholder="Digite o seu sobrenome"
              value="<?php echo $userData->email ?>"
              readonly>
          </div>

          <input type="submit" class="btn card-btn" value="Alterar">

        </div>

        <div class="col-md-4">
          <div
            id="profile-image-container"
            style="background-image: url('<?php echo $BASE_URL ?>/img/users/<?php echo $userData->image ?>');">
          </div>
          <div class="form-group">
            <label for="image">Foto:</label>
            <input type="file" class="form-control-file" name="image">
          </div>
          <div class="form-group">
            <label for="bio">Sobre você:</label>
            <textarea
              class="form-control"
              name="bio"
              id="bio"
              rows="5"
              placeholder="Conte quem você é, o que faz e onde trabalha..."><?php echo $userData->bio ?></textarea>
          </div>
        </div>
      </div>
    </form>

    <div class="row" id="change-password-container">
      <div class="col-md-4">
        <h2>Alterar Senha</h2>
        <p class="page-description">Digite a nova senha e confirme, para alterar</p>

        <form action="<?php echo $BASE_URL ?>/edit_process.php" method="post">

          <!-- INPUT HIDDEN PARA ENVIAR O TIPO DO FORM -->
          <input type="hidden" name="type" value="update-password">

          <div class="form-group">
            <label for="password">Senha:</label>
            <input
              type="password"
              class="form-control"
              id="password"
              name="password"
              placeholder="Digite a sua nova senha">
          </div>

          <div class="form-group">
            <label for="confirmpassword">Confirmação de senha:</label>
            <input
              type="password"
              class="form-control"
              id="confirmpassword"
              name="confirmpassword"
              placeholder="Confirme a sua nova senha">
          </div>

          <input
            type="submit"
            class="btn card-btn"
            value="Alterar Senha">
        </form>
      </div>
    </div>
  </div>
</div>

<?php
require_once("footer.php");
?>