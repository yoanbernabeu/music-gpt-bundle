# Contributing to Music GPT Bundle

Thank you for considering contributing to the Music GPT Bundle! This document outlines the process for contributing to this project.

## Code of Conduct

This project adheres to a [Code of Conduct](CODE_OF_CONDUCT.md). By participating, you are expected to uphold this code.

## How Can I Contribute?

### Reporting Bugs

Before creating bug reports, please check the existing issues to avoid duplicates. When creating a bug report, include as many details as possible:

- **Use a clear and descriptive title**
- **Describe the exact steps to reproduce the problem**
- **Provide specific examples**
- **Describe the behavior you observed and what you expected**
- **Include PHP and Symfony versions**

### Suggesting Enhancements

Enhancement suggestions are tracked as GitHub issues. When creating an enhancement suggestion:

- **Use a clear and descriptive title**
- **Provide a detailed description of the suggested enhancement**
- **Explain why this enhancement would be useful**
- **List any alternatives you've considered**

### Pull Requests

1. **Fork the repository** and create your branch from `main`
2. **Follow the coding standards** (see below)
3. **Write tests** for your changes
4. **Update documentation** as needed
5. **Ensure all tests pass**
6. **Submit the pull request**

## Development Setup

### Prerequisites

- PHP 8.4 or higher
- Composer

### Installation

```bash
# Clone your fork
git clone https://github.com/YOUR_USERNAME/music-gpt-bundle.git
cd music-gpt-bundle

# Install dependencies
composer install
```

### Running Tests

```bash
# Run all tests
composer test

# Run tests with coverage
composer test -- --coverage-html coverage/
```

### Code Style

This project follows PSR-12 coding standards with some additional rules defined in `.php-cs-fixer.dist.php`.

```bash
# Check code style
composer cs-check

# Fix code style automatically
composer cs-fix
```

### Static Analysis

```bash
# Run PHPStan
composer phpstan
```

## Coding Guidelines

### PHP Standards

- Follow PSR-12 coding standards
- Use strict types (`declare(strict_types=1);`)
- Use type hints for all parameters and return types
- Document all public methods with PHPDoc

### Naming Conventions

- Use descriptive variable and method names
- Use camelCase for methods and properties
- Use PascalCase for class names
- Use UPPER_CASE for constants

### Testing

- Write unit tests for all new functionality
- Maintain or improve code coverage
- Follow the existing test structure

### Git Commit Messages

- Use the present tense ("Add feature" not "Added feature")
- Use the imperative mood ("Move cursor to..." not "Moves cursor to...")
- Limit the first line to 72 characters or less
- Reference issues and pull requests when relevant

Example:

```
Add cache clearing functionality

- Implement clearEndpointCache method
- Implement clearAllCache method
- Add tests for cache clearing

Fixes #123
```

## Project Structure

```
music-gpt-bundle/
├── src/
│   ├── Contract/           # Interfaces
│   ├── Service/            # Service implementations
│   └── MusicGptBundle.php  # Bundle configuration
├── tests/
│   └── Unit/               # Unit tests
├── .php-cs-fixer.dist.php  # PHP CS Fixer configuration
├── composer.json           # Dependencies
└── phpunit.xml.dist        # PHPUnit configuration
```

## Review Process

1. All pull requests require at least one review
2. Automated checks must pass (tests, code style, static analysis)
3. Changes should be well documented
4. Breaking changes require a major version bump

## Questions?

Feel free to open an issue if you have questions about contributing!

## License

By contributing to Music GPT Bundle, you agree that your contributions will be licensed under the MIT License.

