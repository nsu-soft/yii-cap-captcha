# yii-cap-captcha

[На русском](docs/ru-RU/README.md)

[![License](https://img.shields.io/badge/license-BSD--3--Clause-blue.svg)](LICENSE)
[![PHP Version](https://img.shields.io/badge/php-%3E%3D%208.3-8892BF.svg?logo=php)](https://php.net)
[![Yii Version](https://img.shields.io/badge/yii-~2.0.50-E47B44.svg?logo=yii)](https://www.yiiframework.com)
[![Status](https://img.shields.io/badge/stable-1.0-blue.svg)](https://packagist.org/packages/nsu-soft/yii-cap-captcha)

---

## 📑 Table of Contents

- [Overview](#-overview)
- [Features](#-features)
- [Requirements](#-requirements)
- [Installation](#-installation)
- [Configuration](#-configuration)
- [Usage](#-usage)
- [Testing](#-testing)
- [Project Structure](#-project-structure)
- [Error Handling](#-error-handling)
- [Security](#-security)
- [Contributing](#-contributing)
- [License](#-license)

---

## 📋 Overview

**yii-cap-captcha** is a PHP library for the **Yii2** framework that provides a robust and convenient interface for interacting with the standalone **[Cap Captcha](https://github.com/tiagozip/cap)** server. The package enables seamless integration of bot protection into your Yii2 applications by leveraging an external service for captcha generation, challenge delivery, and response validation.

This component follows Yii2 design patterns and is fully compatible with PSR-7 (HTTP message) and PSR-18 (HTTP client) standards.

---

## ✨ Features

- 🔐 **Full Cap Captcha API Integration**: Access all endpoints including challenge, redeem, siteverify, and administrative operations.
- 🧩 **Yii2 Component Architecture**: Drop-in component with support for Yii's application configuration.
- 🔑 **Comprehensive Key Management**: Create, view, update, rotate, and delete site keys and API keys programmatically.
- 🐳 **Docker-Ready**: Pre-configured `docker-compose.yml` for instant local development and testing environments.
- 🧪 **Tested with Codeception**: Full test suite covering unit and functional scenarios.
- 📦 **PSR Compliant**: Built on PSR-7 HTTP messages and PSR-18 HTTP client interfaces for maximum interoperability.
- 🔄 **Flexible HTTP Client**: Works with any PSR-18 compatible HTTP client via discovery or explicit configuration.

---

## 🔧 Requirements

| Component | Version | Description |
|-----------|---------|-------------|
| **PHP** | `>= 8.3` | Required PHP version |
| **Yii Framework** | `~2.0.50` | Yii2 web application framework |
| **PSR HTTP Client** | `^1.0` | HTTP client interface (PSR-18) |
| **HTTP Message Factory** | `^1.0` | PSR-7 message factory interface |
| **Cap Captcha Server** | `^2.2` | Cap Captcha standalone server |

---

## 📦 Installation

If you don't have Composer, you may install it by [following instruction](https://getcomposer.org/doc/00-intro.md).

Than install the package via Composer:

```bash
composer require nsu-soft/yii-cap-captcha
```

You also need an HTTP client installed in your project. You may use standard [yiisoft/yii2-httpclient](https://packagist.org/packages/yiisoft/yii2-httpclient). Additionally you need to install PSR-17 implementation. For example:

```bash
composer require yiisoft/yii2-httpclient
composer require nyholm/psr7
```

Or you may use one of [PSR-18 implementations](https://packagist.org/providers/psr/http-client-implementation).

For example:

```bash
composer require guzzlehttp/guzzle
```

The package will use the HTTP client that it finds in the project. It uses [php-http/discovery](https://packagist.org/packages/php-http/discovery) package to find the HTTP client.

---

## ⚙️ Configuration

### Step 1: Generate keys in Cap Captcha server admin panel

Generate access keys to the Cap Captcha server as described on the [official website](https://capjs.js.org/guide/) in the "Setting up your server" section.

### Step 2: Add configuration to your Yii 2 application

Add your Cap Captcha server details to your application configuration:

```php
    'components' => [
        'captcha' => [
            'class' => NsuSoft\Captcha\Cap::class,

            'server' => 'http://localhost',   // Base URL of the Cap server
            'port' => 3000,                   // Server port (default: 3000)
            
            // Site credentials (provided during site registration)
            'siteKey' => 'your-site-key',     // Public site identifier
            'secretKey' => 'your-secret-key', // Private key for client request validation

            // Optional: API key for administrative operations
            'apiKey' => '',                   // Leave empty if not managing keys programmatically
        ],
    ],
```

---

## 💻 Usage

### Getting Component Instance

Access the configured component from anywhere in your Yii2 application:

```php
$cap = Yii::$app->captcha;
```

### Methods Reference

JSON schemas are located in `tests/Support/Data/Cap` directory.

| Method | Description | Parameters | Returns |
|--------|-------------|------------|---------|
| `siteVerify(string $response)` | Alternative validation endpoint (reCAPTCHA-style flow) | `$response'`: token from `/{siteKey}/redeem` endpoint | See JSON schema in `Main/siteverify.200.json` |
| `getAbout()` | Retrieves server metadata | — | See JSON schema in `Server/about.200.json` |
| `logout(string $session)` | Logout specified session | `$session` | `null` |
| `getKeys()` | Gets all sites keys | — | See JSON schema in `Server/Keys/index.200.json` |
| `createKey(string $name)` | Creates site key | `$name` | See JSON schema in `Server/Keys/post.200.json` |
| `viewKey(string $siteKey)` | Views site key | `$siteKey` | See JSON schema in `Server/Keys/get.200.json` |
| `deleteKey(string $siteKey)` | Deletes site key with it secret | `$siteKey` | See JSON schema in `Server/Keys/delete.200.json` |
| `configKey(string $siteKey, array $options = [])` | Configures site key | `$siteKey`, `$options = ['challengeCount' => int, 'difficulty' => int, 'name' => 'new-name', 'saltSize' => int]` | See JSON schema in `Server/Keys/config.200.json` |
| `rotateSecret(string $siteKey)` | Rotates secret key for specified site key | — | See JSON schema in `Server/Keys/rotate-secret.200.json` |
| `getApiKeys()` | Gets all API keys | — | See JSON schema in `Server/Settings/apikeys.index.200.json` |
| `createApiKey(string $name)` | Creates API key | `$name` | See JSON schema in `Server/Settings/apikeys.post.200.json` |
| `deleteApiKey(string $id)` | Deletes API key | `$id` | See JSON schema in `Server/Settings/apikeys.delete.200.json` |
| `deleteLastApiKey(string $name)` | Deletes a last added API key by it name | `$name` | See JSON schema in `Server/Settings/apikeys.delete.200.json` |
| `getSessions()` | Gets all sessions tokens | — | See JSON schema in `Server/Settings/sessions.200.json` |

### Example: Validating User Response

```php
use NsuSoft\Captcha\Exceptions\JsonDecodeException;
use Psr\Http\Client\ClientExceptionInterface;
use Yii;

$cap = Yii::$app->captcha;

try {
    $response = $cap->siteVerify($responseToken); // token from @cap.js/widget
    
    if ($response->success) {
        // ✅ Captcha validation successful
        Yii::info("Captcha passed");
    }
} catch (ClientExceptionInterface $e) {
    // Network or server communication error
    Yii::error("Captcha service unavailable: " . $e->getMessage());
} catch (JsonDecodeException $e) {
    // JSON decoding from server response was failed
    Yii::error("JSON decoding error: " . $e->getMessage());
}
```

### Key Management (requires `apiKey`)

> ⚠️ These methods require a valid `apiKey` configured in the component.

```php
use Yii;

$cap = Yii::$app->captcha;

// List all registered site keys
$keys = $cap->getKeys();

// Create a new site key
$newKey = $cap->createKey('My Production Website');

// View configuration of a specific key
$keyInfo = $cap->viewKey('your-site-key');

// Update key configuration
$cap->configKey('your-site-key', [
    'challengeCount' => 10,
    'difficulty' => 5,
    'name' => 'new-site-key-name',
    'saltSize' => 40,
]);

// Rotate the secret key (invalidates old secret)
$cap->rotateSecret('your-site-key');

// Delete a site key (irreversible)
$cap->deleteKey('your-site-key');
```

### API Key Management

> ⚠️ These methods require a valid `apiKey` configured in the component.

```php
use Yii;

$cap = Yii::$app->captcha;

// List all API keys
$apiKeys = $cap->getApiKeys();

// Create a new API key with a descriptive label
$cap->createApiKey('CI/CD Deployment Tool');

// Delete an API key by its ID
$cap->deleteApiKey('ak_1234567890');

// Delete the most recently created key with a given label
$cap->deleteLastApiKey('CI/CD Deployment Tool');
```

### Session Management

> ⚠️ These methods require a valid `apiKey` configured in the component.

```php
use Yii;

$cap = Yii::$app->captcha;

// Retrieve list of active sessions
$sessions = $cap->getSessions();

// Terminate a specific session
$cap->logout('session-id-here');
```

---

## 🧪 Testing

The project uses **Codeception** for comprehensive testing.

### Run Tests Locally

```bash
# Install test dependencies
composer install --dev

# Run all test suites
vendor/bin/codecept run

# Run with verbose output
vendor/bin/codecept run --verbose

# Run a specific test suite
vendor/bin/codecept run Unit
```

### Run Tests in Docker

```bash
cd ./bin
./start.sh
./codecept.sh run
```

### Configuration Files

| File | Purpose |
|------|---------|
| `codeception.yml` | Main Codeception configuration |
| `config/test.php` | Application configuration for test environment |
| `tests/Support/` | Helper classes, fixtures, and step objects |

---

## 🗂 Project Structure

```
yii-cap-captcha/
├── bin/                        # Docker wrapper scripts
├── config/
│   ├── captcha.dist.php        # Configuration template
│   └── test.php                # Test environment config
├── src/
│   ├── Cap.php                 # Main component class
│   ├── Adapters/               # HTTP client adapters (PSR-18)
│   ├── Exceptions/             # Exceptions
│   ├── Factories/              # Object factories
│   └── Integrations/Cap/       # Cap API endpoint integrations
├── tests/
│   ├── Unit/                   # Unit tests
│   ├── Functional/             # Integration tests
│   └── Support/                # Test helpers and fixtures
├── composer.json               # Dependencies, autoloading, scripts
├── docker-compose.yml          # Docker orchestration for dev/test
├── codeception.yml             # Codeception test runner config
├── LICENSE                     # BSD-3-Clause license text
└── README.md                   # This file
```

---

## ⚠️ Error Handling

The component throws exceptions for recoverable and unrecoverable errors. Always wrap calls in `try-catch` blocks.

### Exception Types

| Exception | When Thrown | Recommended Action |
|-----------|-------------|-------------------|
| `Psr\Http\Client\RequestExceptionInterface` | Exception for when a request failed. | Request from this package is not compatible with Cap Captcha server. Open an issue in this repository. |
| `Psr\Http\Client\NetworkExceptionInterface` |  Thrown when the request cannot be completed because of network issues. | Check the network connection |
| `NsuSoft\Captcha\Exceptions\ResponseExceptionInterface` | Cap Captcha response body is not a valid JSON | Incompatible version of Cap Captcha server with this package. Open an issue in this repository. |

---

## 🔐 Security

> ⚠️ **Critical Security Guidelines**

1. **Never commit secrets to version control**  
   Exclude site key, secret key and API key from Git:
   ```php
   'secretKey' => getenv('CAPTCHA_SECRET_KEY'),
   'apiKey' => getenv('CAPTCHA_API_KEY'),
   ```

2. **Use environment-specific configuration**  
   Maintain separate configs for `dev`, `staging`, and `prod` environments.

3. **Rotate keys regularly**  
   Use `rotateSecret()` periodically and update your configuration securely.

4. **Restrict administrative endpoints**  
   Ensure `apiKey`-protected routes are accessible only from trusted IPs or internal networks.

5. **Enforce HTTPS in production**  
   Always use `https://` for the `server` URL when deploying to production.

6. **Monitor and log suspicious activity**  
   Log failed validation attempts and unusual patterns for security auditing.

---

## 🤝 Contributing

We welcome contributions from the community! Please follow these guidelines to ensure smooth collaboration.

### 📋 Code Standards

- Follow **[PSR-12](https://www.php-fig.org/psr/psr-12/)** coding style.
- Document all public methods and classes using **PHPDoc** format.
- Write meaningful commit messages using [Conventional Commits](https://www.conventionalcommits.org/).
- Add tests for new features or bug fixes.

### 🔄 Pull Request Process

1. Fork the repository and clone your fork.
2. Create a feature branch:
   ```bash
   git checkout -b feature/your-feature-name
   ```
3. Make your changes and add/update tests.
4. Run the test suite and ensure all checks pass:
   ```bash
   vendor/bin/codecept run
   ```
5. Update documentation if functionality changes.
6. Push to your fork and submit a Pull Request to the `develop` branch.
7. Respond to code review feedback promptly.

### 🧭 Development Workflow

```
[Fork Repo] 
     ↓
[Create Feature Branch] 
     ↓
[Implement + Test] 
     ↓
[Run Codeception] 
     ↓
[Update Docs] 
     ↓
[Submit PR to develop] 
     ↓
[Code Review] 
     ↓
[Merge & Close]
```

---

## 📄 License

This project is open-source software licensed under the **BSD-3-Clause License**.  
See the [LICENSE](LICENSE) file for the full license text.
