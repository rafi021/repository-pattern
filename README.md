# A laravel pacakge for appling repository pattern in application

[![Latest Version on Packagist](https://img.shields.io/packagist/v/rafi021/repository-pattern.svg?style=flat-square)](https://packagist.org/packages/rafi021/repository-pattern)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/rafi021/repository-pattern/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/rafi021/repository-pattern/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/rafi021/repository-pattern/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/rafi021/repository-pattern/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/rafi021/repository-pattern.svg?style=flat-square)](https://packagist.org/packages/rafi021/repository-pattern)

This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require rafi021/repository-pattern
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="repository-pattern-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="repository-pattern-config"
```

This is the contents of the published config file:

```php
return [
];
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="repository-pattern-views"
```

## Quick usage

This package overrides the default laravel `php artisan make:model User` command, and adds a few flags that can help you set up repository and service quickly.

```php
// will genearate controller, factory, service, seeder, repository, resource and migration
php artisan make:model User --all

// use the service and repository flag to generate the class
php artisan make:model User --service --repository

// use the short form to generate model with service and repository
php artisan make:model User -sr -rt
```

You can also create only the repository, or service, or both:

```php
php artisan make:repository User
// or
php artisan make:repository UserRepository

// or create together with a service
php artisan make:repository User --service
// or
php artisan make:repository UserRepository --service

// or create a service separately
php artisan make:service User
// or
php artisan make:service UserService

```

The `php artisan make:repository User` will generate two files. One for the interface, and one for the repository class. The interface is bound to it's counter part class automatically depending on the current implementation being used. If the implementation for an interface is not provided, you can provide one manually or otherwise, attempting to use the service will bring up an error.

Eloquent is the default implementation. Other implementations will be added in the future. This is because the package was mainly to simplify usage of the repository pattern in laravel. The classes created are:

```php
// app/Repositories/Interfaces/UserRepository.php

<?php

namespace App\Repositories\Interfaces;

use Mwakalingajohn\LaravelEasyRepository\Repository;

class UserRepositoryInterface extends Repository{

    // Write something awesome :)
}

```

and,

```php
// app/Repositories/Eloquent/UserRepository.php

<?php

namespace App\Repositories\Eloquent;

use Mwakalingajohn\LaravelEasyRepository\Repository;
use Mwakalingajohn\LaravelEasyRepository\Implementations\Eloquent;
use App\Repositories\Interfaces\UserRepositoryInterface;

class UserRepository extends Eloquent implements UserRepositoryInterface{

    /**
    * Model class to be used in this repository for the common methods inside Eloquent
    * @property Model|mixed $model;
    */
    protected $model = Model::class;

    // Write something awesome :)
}

```

also if you included the services flag, or created one by running a command, the service file generated is:

```php
// app/Services/UserService

<?php

namespace App\Services;

use Mwakalingajohn\LaravelEasyRepository\Service;

class UserService extends Service{

    /**
    * The repository interface to use in this service. Will allow to use within
    * methods $this->repository. It will be resolved from the container
    * @property string $repositoryInterface;
    */
    protected string $repositoryInterface = "App\Repositories\Interfaces\UserRepositoryInterface";

    // Define your custom methods :)
}

```

The repository interface property, tells the service which repository to fetch from the container. And the repository once fetched will be available using
`$this->repository` variable.

## Usage

In your controller you can use the service, or the repository.

```php
<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $userService = new UserService;
        return $userService->all();
    }
}

// or using the repository, the service container will automatically resolve the repository for you

<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public $repository;

    public function __construct(UserRepositoryInterface $userRepositoryInterface)
    {
        $this->repository = $userRepositoryInterface;
    }

    public function index()
    {
        return $this->repository->all();
    }
}

```

The repository and service also comes in built with 5 common CRUD methods

```php

interface Repository
{
    /**
     * Fin an item by id
     * @param int $id
     * @return Model|null
     */
    public function find(int $id);

    /**
     * Return all items
     * @return Collection|null
     */
    public function all();

    /**
     * Return query builder instance to perform more manouvers
     * @return Builder|null
     */
    public function query();

    /**
     * Create an item
     * @param array|mixed $data
     * @return Model|null
     */
    public function create($data);

    /**
     * Update a model
     * @param int|mixed $id
     * @param array|mixed $data
     * @return bool|mixed
     */
    public function update($id, array $data);

    /**
     * Delete a model
     * @param int|Model $id
     */
    public function delete($id);
}

```

You can publish the config file with:

```bash
php artisan vendor:publish --provider="Mwakalingajohn\LaravelEasyRepository\LaravelEasyRepositoryServiceProvider" --tag="easy-repository-config"
```

The configurations in the config file are standard, and can be extended with/depending on further requirements. No need to change any of the contents, unless you are very aware of what you are doing :)
This is the contents of the published config file:

```php

return [
    /**
     * The directory for all the repositories
     */
    "repository_directory" => "app/Repositories",

    /**
     * Default repository namespace
     */
    "repository_namespace" => "App\Repositories",

    /**
     * The directory for all the services
     */
    "service_directory" => "app/Services",

    /**
     * Default service namespace
     */
    "service_namespace" => "App\Services",

    /**
     * Default repository implementation
     */
    "default_repository_implementation" => "Eloquent",

    /**
     * Current repository implementation
     */
    "current_repository_implementation" => "Eloquent",
];

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [mahmud ibrahim](https://github.com/ibrahim-shuttlebd)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
