# Attla disposable email checker

<p align="center">
<a href="LICENSE"><img src="https://img.shields.io/badge/license-MIT-lightgrey.svg" alt="License"></a>
<a href="https://packagist.org/packages/attla/disposable"><img src="https://img.shields.io/packagist/v/attla/disposable" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/attla/disposable"><img src="https://img.shields.io/packagist/dt/attla/disposable" alt="Total Downloads"></a>
</p>

A Laravel Wrapper for the [Validator.pizza](https://www.validator.pizza) disposable email API.

## Installation

```bash
composer require attla/disposable
```

## Usage

#### Controller Validation

```php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function handleEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|disposable',
        ]);

        // ...
    }
}
```

#### Standalone

```php
$checker = new \Attla\Disposable\Checker;

// Validate Email
$validEmail = $checker->allowedEmail('lucas@octha.com');

// Validate Domain
$validDomain = $checker->allowedDomain('octha.com');
```

### Testing

```bash
composer test
```

## License

This package is licensed under the [MIT license](LICENSE) © [Octha](https://octha.com).
