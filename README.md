# zellien-mjml

PHP library for rendering MJML templates

![Static Badge](https://img.shields.io/badge/source-zellien%2Fzellien--mjml-blue?style=flat-square)
![Static Badge](https://img.shields.io/badge/php-%5E8.1-brightgreen?style=flat-square&color=%234d588e)

## Installation

The preferred method of installation is via Composer. Run the following
command to install the package and add it as a requirement to your project's

```shell
composer require zellien/zellien-mjml
```

## Usage

Create an instance of the MjmlRenderer class by passing the configuration array to the constructor.
The configuration array should contain the application_id and secret_key as non-empty strings.

Call the render method on the $renderer object, passing the MJML template as a non-empty string.
It will return the rendered HTML as a non-empty string.

```php
$mjmlTemplate = '<mjml>Your MJML template goes here</mjml>';
$renderedHtml = $renderer->render($mjmlTemplate);
```

Handle the potential exceptions that can be thrown by the render method:

+ `BadRequestException` if the request is invalid.
+ `UnauthorizedException` if authentication fails.
+ `ForbiddenException` if the request is unauthorized.
+ `UnexpectedErrorException` if an unexpected error occurs.

```php
try {
    $renderedHtml = $renderer->render($mjmlTemplate);
} catch (BadRequestException $e) {
    // Handle bad request error
} catch (UnauthorizedException $e) {
    // Handle unauthorized error
} catch (ForbiddenException $e) {
    // Handle forbidden error
} catch (UnexpectedErrorException $e) {
    // Handle unexpected error
}
```

## Additional Notes
> Make sure you have the necessary dependencies installed, such as the cURL extension.
> 
> Ensure that the API URL (https://api.mjml.io/v1/render) is accessible from your environment.

That's it! You can now utilize the MjmlRenderer library in your project to render MJML templates using the API.