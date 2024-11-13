<?php
require_once("header.php");
require_once("dao/MovieDAO.php");

// DAO dos filmes
$movieDAO = new MovieDAO($conn, $BASE_URL);

// Resgate da busca do usuário
$q = filter_input(INPUT_GET, "search_query");

$movies = $movieDAO->findByTitle($q);

?>

<div id="main-container" class="container-fluid">
  <h2 class="section-title" id="search-title">Você está buscando por: <span id="search-result"><?php echo $q ?></span></h2>
  <p class="section-description">
    Resultados de busca retornados com base na sua pesquisa.
  </p>
  <div class="movies-container">
    <?php if (count($movies) === 0) : ?>
      <p class="empty-list">Não há filmes correspondentes à sua pesquisa, <a href="<?php echo $BASE_URL ?>/index.php" class="back-link">voltar à página inicial</a>.</p>
    <?php else : ?>
      <?php foreach ($movies as $movie) : ?>
        <?php require("templates/movie_card.php"); ?>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</div>

<?php
require_once("footer.php");
?>