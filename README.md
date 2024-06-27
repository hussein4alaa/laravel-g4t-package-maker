# Laravel G4T Package Maker

<img src="https://cdn.dribbble.com/users/1260892/screenshots/14512031/media/ae5fdac3b3f6840c1efcc225d53ee03c.gif" alt="G4T Package Maker" width="400" height="300">


Laravel G4T Package Maker is a package that assists developers in easily creating and deleting their own packages with a single command.

## Features

- **Easy Package Creation:** Quickly scaffold a new package with \`php artisan make:package\`.
- **Simple Package Removal:** Remove a package effortlessly with \`php artisan remove:package\`.
- **Automatic Composer Updates:** The \`composer.json\` file is automatically updated after adding or removing a package.
- **Verification Link:** Receive a link to verify that your newly created package is working correctly.

## Installation

To install the Laravel G4T Package Maker, run the following command:

```bash
composer require g4t/package-maker
```

## Usage

### Creating a Package

To create a new package, use the following command:

```bash
php artisan make:package
```

Follow the on-screen instructions. Your new package will be located in the `root path\` of your project, within the `g4t/packages/` directory.

### Removing a Package

To remove an existing package, use the following command:

```bash
php artisan remove:package
```

This command will also automatically update your `composer.json` file to reflect the removal.

## Verification

After creating a package, you will receive a link to verify that your package is working correctly. 
Visit this link to ensure everything is set up properly.

## Contribution

We welcome contributions to enhance the Laravel G4T Package Maker.
If you find any issues or have suggestions for improvement, please open an issue or submit a pull request on our [GitHub repository](https://github.com/hussein4alaa/laravel-g4t-package-maker).

G4T Package Maker is developed and maintained by [HusseinAlaa](https://www.linkedin.com/in/hussein4alaa/).
## License

This project is open-source and available under the [MIT License](https://opensource.org/licenses/MIT).

