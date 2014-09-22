Laravel Repo
====

Simple implementation of the Repository Interface for Eloquent with the most basic CRUD methods.

If you find yourself implementing the same default methods over an over again for every new repository class you create, this package could save you some time.

## Quick overview
Repo consists of two basic files:

* RepoInterface.php (Interface)
* Repo.php
* CacheableRepo.php

### Repo.php
The *Repo.php* file is just an interface with the basic CRUD methods (Create, Read, Update, Destroy). It will enforce you implement all methods should you choose to use some other kind of Repository in the future.

### EloquentRepo.php
The *EloquentRepo.php* file is an Eloquent implementation of the Repo interface. Keep reading to find out how I intend to use it.

## Install
In your application's root directory, open up the *composer.json* file and add the package to the `require` section so it looks like this:

```php
"require": {
    "koalabs/repo": "dev-master"
},
```

Open the command line, and in the root ot our application, run the Composer update like this:

```
php composer.phar update
```

Now let's add the Repo Service Provider. Open the *app/config/app.php* file and in the `providers` array, add the following line:

```php
'Koalabs\Repo\RepoServiceProvider'
```

## Usage
You have at your disposal a simple repository class that handles eloquent entities (models). Every new repository you create will simply be an extension of the *EloquenRepository*. Here is an example:

```php
use Koalabs\Repo\Repo;

class UserRepository extends Repo {

    protected $relations = ['role'];

    public function __construct(User $entity)
    {
        parent::__construct($entity);
    }
}
```

The only thing you have to do for the child repository class is tell the repository which Eloquent model/entity (in this case, User) it should manage. You'll then be able to use default methods for finding, creating, storing, and deleting the specified model. With that you should be good to go, but dive into the package's code for a better grasp at what's happening under the hoods.

### Available methods
You'll have these basic CRUD methods at your disposal:
- `findById($id)`
- `findByField($field, $value)`
- `all($orderBy)`
- `create(array $fields)`
- `update($id, array $fields)`
- `destroy($id)`

## Cacheable Repos
There's the added option to use a basic *filesystem* cache with your repos. Simply extend the `CacheableRepo` class instead of the `Repo` and you'll be good to go.
