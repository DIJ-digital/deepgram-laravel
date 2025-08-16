# Contributing to Deepgram Laravel

Thank you for considering contributing to Deepgram Laravel! This document provides guidelines and information for contributors.

## Development Setup

### Prerequisites

- PHP 8.3 or PHP 8.4
- Laravel 12+
- Composer

### Installation

Clone the repository and install dependencies:

```bash
git clone https://github.com/dij-digital/deepgram-laravel.git
cd deepgram-laravel
composer install
```

### Package Setup

After installation, discover the package:

```bash
composer run prepare
```

## Development Commands

### Code Quality

🤙 **Modern codebase, refactoring and static analysis in one command:**
```bash
composer qa
```

This command runs:
- Code refactoring with Rector
- Code linting with Laravel Pint
- Type checking with PHPStan
- Full test suite with Pest

### Individual Commands

**Testing:**
```bash
# Run the entire test suite
composer test

# Run tests with coverage
composer test-coverage
```

**Code Style:**
```bash
# Fix code style issues
composer format
```

**Refactoring:**
```bash
# Apply modern PHP refactoring
composer rector
```

**Static Analysis:**
```bash
# Run PHPStan type checking
composer analyse
```

**Package Discovery:**
```bash
# Discover Laravel package services
composer prepare
```

## Code Standards

This project follows:
- **PSR-12** coding standard
- **Strict types** declaration in all PHP files (`declare(strict_types=1);`)
- **Modern PHP 8.3+** features and syntax
- **Laravel 12+** conventions and best practices
- **Comprehensive test coverage** requirement

## Laravel Package Conventions

This package follows Laravel package development best practices:
- Uses **Spatie Package Tools** for service provider setup
- Config files use package name: `config/deepgram-laravel.php`
- Facade pattern for easy Laravel integration
- Proper exception handling with custom exceptions

## Testing

All contributions must include tests. The project uses **Pest PHP** for testing with **describe/it** syntax.

### Test Structure
- `tests/Feature/Facades/` - Facade integration tests
- `tests/` - Architecture and package tests

### Writing Tests
- Use `describe()` blocks to organize tests by functionality
- Follow the **Arrange / Act / Assert** pattern
- Extend `Tests\TestCase` for all tests

## Configuration

### Environment Setup

For testing, create a `.env` file with:
```env
DEEPGRAM_API_KEY=test-key
DEEPGRAM_BASE_URL=https://api.deepgram.com/v1
DEEPGRAM_DEFAULT_MODEL=nova-2
DEEPGRAM_DEFAULT_LANGUAGE=nl
```

## Pull Request Process

1. **Fork** the repository
2. **Create a feature branch** from `main`
3. **Write tests** for your changes following our testing patterns
4. **Ensure all quality checks pass**: `composer qa`
5. **Run the full test suite**: `composer test`
6. **Check test coverage**: `composer test-coverage`
7. **Commit** with a clear, descriptive message
8. **Submit a pull request** with a detailed description

## Code Review

All contributions will be reviewed for:
- Code quality and adherence to Laravel standards
- Test coverage and quality using Pest PHP
- Proper use of Storage/Http fakes in tests
- Exception handling and error cases
- Documentation updates (if applicable)
- Backward compatibility

## Package Features

Current features:
- **File transcription** via `transcribeFile()` method
- **Configurable options** per call
- **HTTP client mocking** for testing
- **Facade pattern** for Laravel integration
- **Custom exceptions** for error handling

Future features (contributions welcome):
- URL transcription support
- Real-time streaming transcription
- Text-to-speech capabilities
- Batch processing

## Questions?

If you have questions about contributing, please:
- Open an issue for discussion
- Check existing issues and pull requests
- Review the package documentation in `README.md`
- Look at existing test examples in `tests/Feature/`

Thank you for contributing to Deepgram Laravel! 🚀
