<?php

require_once("globals.php");
require_once("db.php");
require_once("dao/UserDAO.php");

$userDAO = new UserDAO($conn, $BASE_URL);
$userData = $userDAO->verifyToken(false);

?>

<footer id="footer">
  <div id="social-container">
    <ul>
      <li>
        <a href="#"><i class="fab fa-facebook-square"></i></a>
      </li>
      <li>
        <a href="#"><i class="fab fa-instagram"></i></a>
      </li>
      <li>
        <a href="#"><i class="fab fa-youtube"></i></a>
      </li>
    </ul>
  </div>

  <div id="footer-links-container">
    <ul>
      <li><a href="#">Adicionar filme</a></li>
      <li><a href="#">Adicionar crítica</a></li>
      <li>
        <?php if ($userData) : ?>
          <a href="<?php echo $BASE_URL ?>/editprofile.php" class="nav-link bold">
            Meu Perfil
          </a>
        <?php else : ?>
          <a href="<?php echo $BASE_URL ?>/login_page.php">Entrar / Cadastrar</a>
        <?php endif; ?>
      </li>
    </ul>
  </div>

  <p>&copy; 2024 | Gabriel Henrique</p>
</footer>

<!-- BOOTSTRAP JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.js" integrity="sha512-lsA4IzLaXH0A+uH6JQTuz6DbhqxmVygrWv1CpC/s5vGyMqlnP0y+RYt65vKxbaVq+H6OzbbRtxzf+Zbj20alGw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</body>

</html>