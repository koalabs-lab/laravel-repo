<?php namespace Koalabs\Repo;

use Illuminate\Database\Eloquent\Model as Entity;

abstract class Repo implements RepoInterface {

  /**
   * The entity being handled by the repository
   *
   * @var Illuminate\Database\Eloquent\Model
   */
  protected $entity;

  /**
   * List of relationships for the entity for
   * later lazy loading
   *
   * @var array
   */
  protected $relations = [];

  /**
   * Create the Repository injecting
   * an Eloquent Model, and its relations
   * 
   * @param  Illuminate\Database\Eloquent\Model $entity
   * @return void
   */
  public function __construct(Entity $entity)
  {
    $this->entity = $entity;
  }

  /**
   * Find a model by its ID
   *
   * @param  integer $id
   * @return Illuminate\Database\Eloquent\Model
   */
  public function findById($id)
  {
    $resource = $this->entity->find($id);

    $resource->load($this->relations);

    return $resource;
  }

  /**
   * Find the first model matching a field value
   *
   * @param string $field
   * @param string $value
   * @return Illuminate\Database\Eloquent\Model
   */
  public function findByField($field, $value)
  {
    $resource = $this->entity->where($field,'=',$value)->first();

    $resource->load($this->relations);

    return $resource;
  }

  /**
   * Get all elements for the entity
   * 
   * @param  string  $orderBy
   * @return Illuminate\Database\Eloquent\Collection
   */
  public function all($orderBy = 'id')
  {
    $collection = $this->entity->orderBy($orderBy)->get();

    $collection->load($this->relations);

    return $collection;
  }

  /**
   * Create a new record for this entity
   * 
   * @param  array  $fields
   * @return Illuminate\Database\Eloquent\Model
   */
  public function create(array $fields)
  {
    return $this->entity->create($fields);
  }

  /**
   * Update an existing record for this entity
   *
   * @param integer $id
   * @param array   $fields
   * @return Bool
   */
  public function update($id, array $fields)
  {
    $resource = $this->entity->find($id);

    foreach ($fields as $field => $value)
    {
      $resource->{$field} = $value;
    }

    return $resource->save();
  }

  /**
   * Delete a model by its ID
   *
   * @param  integer $id
   * @return Bool
   */
  public function destroy($id)
  {
    return $this->entity->destroy($id);
  }

}