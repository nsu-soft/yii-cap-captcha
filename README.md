# yii-cap-captcha

[На русском](docs/ru-RU/README.md)

[![License](https://img.shields.io/badge/license-BSD--3--Clause-blue.svg)](LICENSE)
[![PHP Version](https://img.shields.io/badge/php-%3E%3D%208.3-8892BF.svg?logo=php)](https://php.net)
[![Yii Version](https://img.shields.io/badge/yii-~2.0.50-E47B44.svg?logo=yii)](https://www.yiiframework.com)
[![Status](https://img.shields.io/badge/status-develop-yellow.svg)](../../tree/develop)

> ⚠️ **Notice**: This package is under active development (`develop` branch). The API and functionality are subject to change without prior notice.

---

## 📑 Table of Contents

- [Overview](#-overview)
- [Features](#-features)
- [Requirements](#-requirements)
- [Installation](#-installation)
- [Configuration](#-configuration)
- [Quick Start with Docker](#-quick-start-with-docker)
- [Usage](#-usage)
- [Testing](#-testing)
- [Project Structure](#-project-structure)
- [Error Handling](#-error-handling)
- [Security](#-security)
- [Contributing](#-contributing)
- [License](#-license)

---

## 📋 Overview

**yii-cap-captcha** is a PHP library for the **Yii2** framework that provides a robust and convenient interface for interacting with the standalone **[Cap Captcha](https://github.com/tiago2/cap)** server. The package enables seamless integration of bot protection into your Yii2 applications by leveraging an external service for captcha generation, challenge delivery, and response validation.

This component follows Yii2 design patterns, supports dependency injection, and is fully compatible with PSR-7 (HTTP message) and PSR-18 (HTTP client) standards.

---

## ✨ Features

- 🔐 **Full Cap Captcha API Integration**: Access all endpoints including challenge, redeem, siteverify, and administrative operations.
- 🧩 **Yii2 Component Architecture**: Drop-in component with support for Yii's DI container and application configuration.
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
| **HTTP Discovery** | `^1.20` | For automatic HTTP client/stream factory discovery |

---

## 📦 Installation

Install the package via Composer:

```bash
composer require nsu-soft/yii-cap-captcha:@dev --prefer-source
```

> 📌 **Note**: Since this package is in active development, the `@dev` stability flag is required to install it. For production use, wait for a stable release or pin to a specific commit hash.

---

## ⚙️ Configuration

### Step 1: Create Configuration File

Copy the distribution configuration template:

```bash
cp config/captcha.dist.php config/captcha.php
```

### Step 2: Fill Connection Parameters

Edit `config/captcha.php` with your Cap Captcha server details:

```php
<?php
return [
    // Cap Captcha server connection
    'server' => 'http://localhost',  // Base URL of the Cap server
    'port' => 3000,                  // Server port (default: 3000)
    
    // Site credentials (provided during site registration)
    'siteKey' => 'your-site-key',    // Public site identifier
    'secretKey' => 'your-secret-key',// Private key for response validation
    
    // Optional: API key for administrative operations
    'apiKey' => '',                  // Leave empty if not managing keys programmatically
    
    // Optional: HTTP client configuration
    'client' => [
        'class' => \yii\httpclient\Client::class,
        // Additional client options...
    ],
];
```

### Step 3: Register Component

Add the component to your Yii2 application configuration (`config/web.php` or `config/main.php`):

```php
'components' => [
    'captcha' => [
        'class' => \NsuSoft\Captcha\Cap::class,
        'server' => 'http://localhost',
        'port' => 3000,
        'siteKey' => 'your-site-key',
        'secretKey' => 'your-secret-key',
        'apiKey' => '',
        'client' => [
            'class' => \yii\httpclient\Client::class,
        ],
    ],
],
```

---

## 🚀 Quick Start with Docker

The project includes a `docker-compose.yml` file to spin up a local Cap Captcha server for development and testing.

### docker-compose.yml (example)

```yaml
version: '3.8'

services:
  cap:
    image: tiago2/cap:2.2.1
    container_name: cap-captcha
    environment:
      ADMIN_KEY: j9c8TClJJror0Wki
    ports:
      - "3000:3000"
    volumes:
      - cap_data:/usr/src/app/.data
    restart: unless-stopped

volumes:
  cap_data
```

### Start the Services

```bash
docker-compose up -d
```

### Access Points

| Service | URL | Description |
|---------|-----|-------------|
| 🌐 Cap Server | `http://localhost:3000` | Main API endpoint |
| 📚 Swagger UI | `http://localhost:3000/swagger` | Interactive API documentation |
| 🔑 Admin Key | `j9c8TClJJror0Wki` | Default admin key (change in production) |

---

## 💻 Usage

### Getting Component Instance

Access the configured component from anywhere in your Yii2 application:

```php
$cap = Yii::$app->captcha;
```

Or via dependency injection in a controller/service:

```php
public function __construct(
    private \NsuSoft\Captcha\Cap $captcha,
) {}
```

### Core Methods Reference

| Method | Description | Parameters | Returns |
|--------|-------------|------------|---------|
| `challenge(string $siteKey)` | Request a new captcha challenge for a site | `string $siteKey` | `stdClass` with `challenge`, `sessionId`, `image`, etc. |
| `redeem(string $siteKey, array $data)` | Validate user's captcha response | `string $siteKey`, `array $data` | `stdClass` with `success`, `sessionId`, `errorCodes` |
| `siteVerify(string $siteKey, array $data)` | Alternative validation endpoint (reCAPTCHA-style flow) | `string $siteKey`, `array $data` | `stdClass` with validation result |
| `getAbout()` | Retrieve server metadata and version info | — | `stdClass` with `version`, `description`, etc. |

### Example: Validating User Response

```php
use yii\base\Exception;
use Yii;

try {
    $result = $cap->redeem($siteKey, [
        'token' => $userSubmittedToken,
        'action' => 'login',
        'ip' => Yii::$app->request->userIP,
        'userAgent' => Yii::$app->request->userAgent,
    ]);
    
    if ($result->success) {
        // ✅ Captcha validation successful
        Yii::info("Captcha passed for session: {$result->sessionId}");
    } else {
        // ❌ Validation failed
        $errors = implode(', ', $result->errorCodes ?? ['unknown_error']);
        throw new Exception("Captcha validation failed: {$errors}");
    }
} catch (\yii\httpclient\Exception $e) {
    // Network or server communication error
    Yii::error("Captcha service unavailable: " . $e->getMessage());
} catch (\Exception $e) {
    // Unexpected error
    Yii::error("Unexpected captcha error: " . $e->getMessage());
}
```

### Key Management (requires `apiKey`)

> ⚠️ These methods require a valid `apiKey` configured in the component.

```php
// List all registered site keys
$keys = $cap->getKeys();

// Create a new site key
$newKey = $cap->createKey('My Production Website');

// View configuration of a specific key
$keyInfo = $cap->viewKey('your-site-key');

// Update key configuration
$cap->configKey('your-site-key', [
    'enabled' => true,
    'maxRequestsPerMinute' => 60,
]);

// Rotate the secret key (invalidates old secret)
$cap->rotateSecret('your-site-key');

// Delete a site key (irreversible)
$cap->deleteKey('your-site-key');
```

### API Key Management

```php
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

```php
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

# Run a specific test file
vendor/bin/codecept run Unit CapTest

# Run tests with coverage report
vendor/bin/codecept run --coverage --coverage-html
```

### Run Tests in Docker

```bash
docker-compose run --rm php vendor/bin/codecept run
```

### Configuration Files

| File | Purpose |
|------|---------|
| `codeception.yml` | Main Codeception configuration |
| `config/test.php` | Application configuration for test environment |
| `tests/_support/` | Helper classes, fixtures, and step objects |

---

## 🗂 Project Structure

```
yii-cap-captcha/
├── src/
│   ├── Cap.php                 # Main component class
│   ├── Adapters/               # HTTP client adapters (PSR-18)
│   ├── Factories/              # Object factories
│   └── Integrations/           # Cap API endpoint integrations
├── config/
│   ├── captcha.dist.php        # Configuration template
│   └── test.php                # Test environment config
├── tests/
│   ├── Unit/                   # Unit tests
│   ├── Functional/             # Integration tests
│   └── _support/               # Test helpers and fixtures
├── bin/
│   └── docker/                 # Docker entrypoint and helper scripts
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
| `\yii\httpclient\Exception` | Network timeout, DNS failure, server unreachable | Retry with backoff, fallback to alternative validation |
| `\NsuSoft\Captcha\Exception\CaptchaException` | Invalid response format, API error (4xx/5xx) | Log error, show user-friendly message |
| `\InvalidArgumentException` | Missing required parameters, invalid key format | Fix configuration or input validation |

### Common Error Codes (from `errorCodes` array)

| Code | Meaning | Action |
|------|---------|--------|
| `invalid-token` | Submitted token is malformed or expired | Request new challenge |
| `invalid-secret` | Site secret key is incorrect | Verify configuration |
| `site-disabled` | Site key has been disabled | Contact administrator |
| `rate-limit-exceeded` | Too many requests from this IP/site | Implement client-side throttling |
| `challenge-expired` | Challenge TTL exceeded | Refresh captcha UI |

---

## 🔐 Security

> ⚠️ **Critical Security Guidelines**

1. **Never commit secrets to version control**  
   Exclude `config/captcha.php` from Git or use environment variables:
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

6. **Validate and sanitize all user input**  
   Never trust client-supplied data; validate `token`, `action`, and `ip` before passing to `redeem()`.

7. **Monitor and log suspicious activity**  
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
