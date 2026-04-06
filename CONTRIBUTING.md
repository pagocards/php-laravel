# Contributing to Pagocards PHP SDK

Thank you for considering contributing to the Pagocards PHP SDK! We welcome all contributions and appreciate your effort to improve this project.

## Getting Started

1. Fork the repository
2. Clone your fork locally
3. Create a new branch for your feature or bug fix
4. Make your changes
5. Write tests if applicable
6. Push to your fork
7. Submit a pull request

## Development Setup

```bash
# Clone your forked repository
git clone https://github.com/YOUR_USERNAME/pagocards-php-sdk.git
cd pagocards-php-sdk

# Install dependencies
composer install

# Run tests to ensure everything is working
composer test
```

## Code Style

Please follow PSR-12 coding standards. You can use PHP_CodeSniffer to check your code:

```bash
composer cs-check
composer cs-fix
```

## Writing Tests

All new features must have accompanying tests. Tests should be placed in the `tests/` directory.

```bash
composer test
```

## Documentation

If you're adding new features, please update the README.md with examples and documentation.

## Commit Messages

Use clear and descriptive commit messages:

```
Add feature: Description of feature
Fix: Description of bug fix
Update: Description of update
```

## Pull Requests

When submitting a pull request:

1. Provide a clear description of the changes
2. Reference any related issues
3. Ensure all tests pass
4. Update documentation if needed
5. Keep commits clean and logical

## Reporting Issues

When reporting bugs, please include:

- PHP version
- Laravel version
- SDK version
- Detailed description of the issue
- Steps to reproduce
- Expected vs actual behavior

## Code of Conduct

Please be respectful and professional in all interactions. We are committed to providing a welcoming environment for all contributors.

## Questions?

Feel free to reach out to us at [support@pagocards.com](mailto:support@pagocards.com) or open an issue on GitHub.

Thank you for contributing!

