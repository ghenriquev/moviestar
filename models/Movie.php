<?php

class Movie {

  public $id;
  public $title;
  public $description;
  public $image;
  public $trailer;
  public $category;
  public $length;
  public $users_id;

  public function imageGenerateName() {
    return bin2hex(random_bytes(60)) . ".jpg";
  }
}

interface MovieDAOInterface {

  public function buildMovieObject($data);
  public function findAll();
  public function getLatestMovies();
  public function getMoviesByCategory($category);
  public function getMoviesByUserId($userId);
  public function findById($movieId);
  public function findByTitle($title);
  public function createMovie($movie);
  public function updateMovie(Movie $movie);
  public function destroyMovie($id);
}
