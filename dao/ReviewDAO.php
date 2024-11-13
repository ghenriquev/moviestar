<?php

require_once("models/Review.php");
require_once("models/Message.php");
require_once("dao/UserDAO.php");

class ReviewDAO implements ReviewDAOInterface {

  private $conn;
  private $message;
  private $url;

  public function __construct(PDO $conn, $url) {
    $this->conn = $conn;
    $this->url = $url;
    $this->message = new Message($url);
  }

  public function buildReviewObject($data) {

    $reviewObject = new Review();

    $reviewObject->id = $data["id"];
    $reviewObject->rating = $data["rating"];
    $reviewObject->review = $data["review"];
    $reviewObject->users_id = $data["users_id"];
    $reviewObject->movies_id = $data["movies_id"];

    return $reviewObject;
  }
  public function createReview(Review $review) {
    $stmt = $this->conn->prepare("
      INSERT INTO reviews
          (rating, review, movies_id, users_id)
        VALUES
          (:rating, :review, :movies_id, :users_id)
      ");

    $stmt->bindParam(":rating", $review->rating);
    $stmt->bindParam(":review", $review->review);
    $stmt->bindParam(":movies_id", $review->movies_id);
    $stmt->bindParam(":users_id", $review->users_id);

    $stmt->execute();

    // Mensagem de sucesso
    $this->message->setMessage("Crítica inserida com sucesso!", "success", "/index.php");
  }
  public function getMoviesReview($id) {

    $stmt = $this->conn->prepare("
      SELECT *
        FROM reviews
        WHERE movies_id = :movies_id
    ");

    $stmt->bindParam(":movies_id", $id);

    $stmt->execute();

    $userDAO = new UserDAO($this->conn, $this->url);

    if ($stmt->rowCount() > 0) {

      $reviewsArr = $stmt->fetchAll(PDO::FETCH_ASSOC);

      foreach ($reviewsArr as $review) {

        $reviewObject = $this->buildReviewObject($review);

        // Chamar dados do usuário
        $user = $userDAO->findById($reviewObject->users_id);

        // Adicionando o objeto do usuário dentro do objeto de review
        $reviewObject->user = $user;

        $reviews[] = $reviewObject;
      }

      return $reviews;
    } else {
      return [];
    }
  }
  public function hasAlreadyReviewed($movieId, $userId) {
    $stmt = $this->conn->prepare("
      SELECT *
        FROM reviews
        WHERE
          movies_id = :movies_id
        AND
          users_id = :users_id
    ");

    $stmt->bindParam(":movies_id", $movieId);
    $stmt->bindParam(":users_id", $userId);

    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      return true;
    } else {
      return false;
    }
  }
  public function getRatings($movieId) {

    $stmt = $this->conn->prepare("
      SELECT *
        FROM reviews
        WHERE movies_id = :movies_id
    ");

    $stmt->bindParam(":movies_id", $movieId);

    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      $rating = 0;

      $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

      foreach ($reviews as $review) {
        $rating += $review["rating"];
      }

      $rating = $rating / count($reviews);

      if (is_float($rating)) {
        return number_format($rating, 1, ".", ",");
      } else {
        return $rating;
      }
    } else {
      $rating = "Não avaliado";

      return $rating;
    }
  }
}
