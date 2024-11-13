<?php
require_once("header.php");
require_once("dao/UserDAO.php");
require_once("models/User.php");

// Check de autenticação
$userDAO = new UserDAO($conn, $BASE_URL);
$userData = $userDAO->verifyToken(true);

?>

<div id="main-container" class="container-fluid">
  <div class="offset-md-4 col-md-4 new-movie-container">
    <h1 class="page-title">Adicionar Filme</h1>
    <p class="page-description">Adicione sua crítica e compartilhe com o mundo!</p>

    <form
      action="<?php $BASE_URL ?>movie_process.php"
      id="add-movie-form"
      method="POST"
      enctype="multipart/form-data">

      <input type="hidden" name="type" value="add-movie">

      <div class="form-group">
        <label for="title">Título</label>
        <input
          class="form-control"
          id="title"
          type="text"
          name="title"
          placeholder="Insira o título do filme">
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
          placeholder="Insira a duração do filme">
      </div>

      <div class="form-group">
        <label for="category">Categoria</label>
        <select
          name="category"
          id="category"
          class="form-control">
          <option value="">Selecione</option>
          <option value="Ação">Ação</option>
          <option value="Aventura">Aventura</option>
          <option value="Animação">Animação</option>
          <option value="Comédia">Comédia</option>
          <option value="Drama">Drama</option>
          <option value="Documentário">Documentário</option>
          <option value="Ficção Científica">Ficção Científica</option>
          <option value="Fantasia">Fantasia</option>
          <option value="Romance">Romance</option>
          <option value="Terror">Terror</option>
          <option value="Suspense">Suspense</option>
          <option value="Musical">Musical</option>
          <option value="Biografia">Biografia</option>
          <option value="Guerra">Guerra</option>
          <option value="Policial">Policial</option>
          <option value="Esporte">Esporte</option>
          <option value="Histórico">Histórico</option>
          <option value="Faroeste">Faroeste</option>
          <option value="Mistério">Mistério</option>
          <option value="Infantil">Infantil</option>
          <option value="Noir">Noir</option>
        </select>
      </div>

      <div class="form-group">
        <label for="trailer">Trailer do filme</label>
        <input
          class="form-control"
          id="trailer"
          type="url"
          name="trailer"
          placeholder="Insira o link do trailer">
      </div>

      <div class="form-group">
        <label for="description">Descrição do filme</label>
        <textarea
          class="form-control"
          name="description"
          id="description"
          rows="5"
          placeholder="Descreva o filme..."></textarea>
      </div>

      <input type="submit" class="btn card-btn" value="Adicionar filme">

    </form>
  </div>
</div>

<?php
require_once("footer.php");
?>