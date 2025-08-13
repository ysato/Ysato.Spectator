# Ysato.Spectator

OpenAPI specification coverage visualization library for Laravel Feature tests

[![PHP Version](https://img.shields.io/badge/php-%5E8.2-blue.svg)](https://php.net/)
[![Laravel](https://img.shields.io/badge/laravel-%5E11.43%7C%5E12.0-red.svg)](https://laravel.com/)
[![License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)

## Overview

Ysato.Spectator is a library for visualizing OpenAPI specification coverage in Laravel Feature tests. By integrating it into your existing Feature tests, you can clearly see which endpoints defined in your OpenAPI specification are tested and which ones are missing from your test suite.

### Key Features

- **Test Coverage Visualization**: Display test coverage for OpenAPI specifications in a table format
- **Automatic Tracking**: Automatically monitor Feature test execution and record endpoint usage
- **Laravel Integration**: Easy integration with Laravel Feature tests

## Installation

Install via Composer:

```bash
composer require --dev ysato/spectator
```

## Usage

### 1. Prepare OpenAPI Specification File

Place an OpenAPI specification file (YAML format) in your project:

```yaml
# openapi.yaml
openapi: 3.0.0
info:
  title: My API
  version: 1.0.0
paths:
  /api/users:
    get:
      responses:
        '200':
          description: Success
        '404':
          description: Not Found
    post:
      responses:
        '201':
          description: Created
        '422':
          description: Validation Error
  /api/users/{id}:
    get:
      responses:
        '200':
          description: Success
        '404':
          description: Not Found
```

### 2. Use Spectator in Feature Tests

Use the `Spectatable` trait in `tests/Feature/TestCase.php`:

```php
<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Ysato\Spectator\Spectatable;

abstract class TestCase extends BaseTestCase
{
    use Spectatable;
}
```

### 3. Create Feature Tests

Create regular Feature tests. The `Spectatable` trait will automatically monitor API calls:

```php
<?php

namespace Tests\Feature;

class UserTest extends TestCase
{
    public function test_can_list_users()
    {
        // Regular Feature test - automatically monitored
        $response = $this->get('/api/users');
        
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => ['id', 'name', 'email']
            ]
        ]);
    }

    public function test_can_create_user()
    {
        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123'
        ];

        $response = $this->post('/api/users', $userData);
        
        $response->assertStatus(201);
    }

    public function test_returns_404_for_nonexistent_user()
    {
        $response = $this->get('/api/users/9999');
        
        $response->assertStatus(404);
    }
}
```

### 4. Display Coverage Report

Set environment variable and run tests to display coverage report:

```bash
ENABLE_SPECTATION_REPORT=true ./vendor/bin/phpunit --no-progress --no-results
```

Example output:
```
┌─────────────┬────────┬─────────────────┬─────────────┐
│ IMPLEMENTED │ METHOD │ ENDPOINT        │ STATUS CODE │
├─────────────┼────────┼─────────────────┼─────────────┤
│ ✅          │ GET    │ /api/users      │ 200         │
│ ❌          │ GET    │ /api/users      │ 404         │
│ ✅          │ POST   │ /api/users      │ 201         │
│ ❌          │ POST   │ /api/users      │ 422         │
│ ❌          │ GET    │ /api/users/{id} │ 200         │
│ ✅          │ GET    │ /api/users/{id} │ 404         │
└─────────────┴────────┴─────────────────┴─────────────┘
```

- ✅ = Tested
- ❌ = Not tested

## Configuration Options

### Environment Variable Configuration

Set the OpenAPI specification file path with the `OPENAPI_SPEC_PATH` environment variable:

```bash
# .env
OPENAPI_SPEC_PATH=/path/to/your/openapi.yaml
```

## Requirements

- PHP 8.2 or higher
- Laravel 11.43 or higher, or 12.0 or higher

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

## Support

If you encounter any issues or have questions, please report them on [GitHub Issues](https://github.com/ysato/Ysato.Spectator/issues).
