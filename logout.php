<?php

require_once("header.php");

if ($userDAO) {
  $userDAO->destroyToken();
}
