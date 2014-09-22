<?php namespace Koalabs\Repo;

use Koalabs\Repo\Services\Cache;
use Illuminate\Database\Eloquent\Model as Entity;

abstract class CachableRepo implements RepoInterface {

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
   * @var Cache
   */
  protected $cache;

  /**
   * Create the Repository injecting
   * an Eloquent Model, and its relations
   * 
   * @param  Illuminate\Database\Eloquent\Model $entity
   * @param  Koalabs\Repo\Services\Cache $cache
   * @return void
   */
  public function __construct(Entity $entity, Cache $cache)
  {
    $this->entity = $entity;
    $this->cache  = $cache;
  }

  /**
   * Find a model by its ID
   *
   * @param  integer $id
   * @return Illuminate\Database\Eloquent\Model
   */
  public function findById($id)
  {
    $key = $this->generateKey($id);

    if ($this->cache->has($key))
    {
      return $this->cache->get($key);
    }

    $resource = $this->entity->find($id);

    $resource->load($this->relations);

    $this->cache->put($key, $resource);

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
    $key = $this->generateKey('field.'.$field.'='.$value);

    if ($this->cache->has($key))
    {
      return $this->cache->get($key);
    }

    $resource = $this->entity->where($field,'=',$value)->first();

    $resource->load($this->relations);

    $this->cache->put($key, $resource);

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
    $key = $this->generateKey('all.orderedBy'.$orderBy);

    if ($this->cache->has($key))
    {
      return $this->cache->get($key);
    }

    $collection = $this->entity->orderBy($orderBy)->get();

    $collection->load($this->relations);

    $this->cache->put($key, $collection);

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

  /**
   * Generate a unique cachekey for the query
   *
   * @var $string
   */
  protected function generateKey($string)
  {
    return md5(class_basename($this->entity).$string);
  }

}