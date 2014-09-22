<?php namespace Koalabs\Repo\Services;

use Config;
use Illuminate\Cache\CacheManager;

class Cache {

  protected $cache;
  protected $cachekey;
  protected $minutes;

  public function __construct(CacheManager $cache, $cachekey='repo', $minutes=null)
  {
    $this->cache    = $cache;
    $this->cachekey = $cachekey;

    if (! $this->minutes) $this->minutes = Config::get('repo::minutes_in_cache');
  }

  public function get($key)
  {
    return $this->cache->get($this->cachekey.'.'.$key);
  }

  public function put($key, $value, $minutes=null)
  {
    if ( is_null($minutes))
    {
      $minutes = $this->minutes;
    }

    return $this->cache->put($this->cachekey.'.'.$key, $value, $minutes);
  }

  public function has($key)
  {
    return $this->cache->has($this->cachekey.'.'.$key);
  }

  public function setCachekey($cachekey)
  {
    $this->cachekey = $cachekey;
  }

  public function setMinutes($minutes)
  {
    $this->minutes = $minutes;
  }

}