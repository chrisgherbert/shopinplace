# Timber Boilerplate Theme

## Requirements

- PHP 7.0
- Composer (For OSX: `brew install composer`)
- NPM

## Inital Installation

- `npm ci` to install NPM dependencies
- `composer install` to install PHP dependencies

## Required Plugins

The site requires Advanced Custom Fields. You can install that manually or use WP-CLI, while making sure to replace the placeholder string with your actual ACF Pro key.

`wp plugin install "http://connect.advancedcustomfields.com/index.php?p=pro&a=download&k=<YOUR_KEY>"`

## Compiling Assets

- During development, run `npm run watch` to compile assets automatically watch for changes to JS and SASS files

## Deploying

- To build the theme for production deployment, run `npm run production` before sending it to a remote server




