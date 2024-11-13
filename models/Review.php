<?php

class Review {

  public $id;
  public $rating;
  public $review;
  public $users_id;
  public $movies_id;
}

interface ReviewDAOInterface {

  public function buildReviewObject($data);
  public function createReview(Review $review);
  public function getMoviesReview($id);
  public function hasAlreadyReviewed($id, $userId);
  public function getRatings($id);
}
