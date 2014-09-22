<?php namespace Koalabs\Repo;

interface RepoInterface {

  public function findById($id);

  public function findByField($field, $value);

  public function all($orderBy);

  public function create(array $fields);

  public function update($id, array $fields);

  public function destroy($id);

}