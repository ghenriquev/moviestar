<?php

require_once("models/Movie.php");
require_once("models/Message.php");
require_once("dao/ReviewDAO.php");

class MovieDAO implements MovieDAOInterface {

  private $conn;
  private $message;
  private $url;

  public function __construct(PDO $conn, $url) {
    $this->conn = $conn;
    $this->url = $url;
    $this->message = new Message($url);
  }

  public function buildMovieObject($data) {
    $movie = new Movie();

    $movie->id = $data["id"];
    $movie->title = $data["title"];
    $movie->description = $data["description"];
    $movie->image = $data["image"];
    $movie->trailer = $data["trailer"];
    $movie->category = $data["category"];
    $movie->length = $data["length"];
    $movie->users_id = $data["users_id"];

    // Recebe as ratings do filme
    $reviewDAO = new ReviewDAO($this->conn, $this->url);
    $rating = $reviewDAO->getRatings($movie->id);

    $movie->rating = $rating;

    return $movie;
  }

  public function findAll() {
  }

  public function getLatestMovies() {
    $movies = [];
    $stmt = $this->conn->query("
      SELECT *
        FROM movies
        ORDER BY id DESC
    ");

    $stmt->execute();

    if ($stmt->rowCount() > 0) {

      $moviesArr = $stmt->fetchAll(PDO::FETCH_ASSOC);

      foreach ($moviesArr as $movie) {
        $movies[] = $this->buildMovieObject($movie);
      }
    }

    return $movies;
  }

  public function getMoviesByCategory($category) {
    $movies = [];
    $stmt = $this->conn->prepare("
      SELECT *
        FROM movies
        WHERE category = :category
        ORDER BY id DESC
    ");

    $stmt->bindParam(":category", $category);

    $stmt->execute();

    if ($stmt->rowCount() > 0) {

      $moviesArr = $stmt->fetchAll(PDO::FETCH_ASSOC);

      foreach ($moviesArr as $movie) {
        $movies[] = $this->buildMovieObject($movie);
      }
    }

    return $movies;
  }

  public function getMoviesByUserId($userId) {
    $movies = [];
    $stmt = $this->conn->prepare("
      SELECT *
        FROM movies
        WHERE users_id = :users_id
    ");

    $stmt->bindParam(":users_id", $userId);

    $stmt->execute();

    if ($stmt->rowCount() > 0) {

      $moviesArr = $stmt->fetchAll(PDO::FETCH_ASSOC);

      foreach ($moviesArr as $movie) {
        $movies[] = $this->buildMovieObject($movie);
      }
    }

    return $movies;
  }

  public function findById($movieId) {

    $stmt = $this->conn->prepare("
      SELECT *
        FROM movies
        WHERE id = :id
    ");

    $stmt->bindParam(":id", $movieId);

    $stmt->execute();

    if ($stmt->rowCount() > 0) {

      $movieData = $stmt->fetch();
      $movie = $this->buildMovieObject($movieData);
      return $movie;
    } else {

      return false;
    }
  }

  public function findByTitle($title) {

    $movies = [];

    $stmt = $this->conn->prepare("
      SELECT *
        FROM movies
        WHERE title LIKE :title
    ");

    $stmt->bindValue(":title", '%' . $title . "%");

    $stmt->execute();

    if ($stmt->rowCount() > 0) {

      $moviesArr = $stmt->fetchAll(PDO::FETCH_ASSOC);

      foreach ($moviesArr as $movie) {
        $movies[] = $this->buildMovieObject($movie);
      }
    }

    return $movies;
  }

  public function createMovie($movie) {
    $stmt = $this->conn->prepare("
      INSERT INTO movies
          (title, description, image, trailer, category, length, users_id)
        VALUES
          (:title, :description, :image, :trailer, :category, :length, :users_id)
      ");

    $stmt->bindParam(":title", $movie->title);
    $stmt->bindParam(":description", $movie->description);
    $stmt->bindParam(":image", $movie->image);
    $stmt->bindParam(":trailer", $movie->trailer);
    $stmt->bindParam(":category", $movie->category);
    $stmt->bindParam(":length", $movie->length);
    $stmt->bindParam(":users_id", $movie->users_id);

    $stmt->execute();

    // Mensagem de sucesso
    $this->message->setMessage("Filme inserido com sucesso!", "success", "/index.php");
  }

  public function updateMovie(Movie $movie) {

    $stmt = $this->conn->prepare("
      UPDATE movies
        SET
          title = :title,
          description = :description,
          image = :image,
          category = :category,
          trailer = :trailer,
          length = :length
        WHERE id = :id
    ");

    $stmt->bindParam(":title", $movie->title);
    $stmt->bindParam(":description", $movie->description);
    $stmt->bindParam(":image", $movie->image);
    $stmt->bindParam(":category", $movie->category);
    $stmt->bindParam(":trailer", $movie->trailer);
    $stmt->bindParam(":length", $movie->length);
    $stmt->bindParam(":id", $movie->id);

    $stmt->execute();

    $this->message->setMessage("Filme atualizado com sucesso!", "success", "/dashboard.php");
  }

  public function destroyMovie($movieId) {
    $stmt = $this->conn->prepare("
      DELETE FROM movies
        WHERE id = :id
    ");

    $stmt->bindParam(":id", $movieId);

    $stmt->execute();

    // Mensagem de sucesso por remover filme
    $this->message->setMessage("Filme removido com sucesso!", "success", "/dashboard.php");
  }
}
