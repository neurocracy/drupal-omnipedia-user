This contains the source files for the "*Omnipedia - User*" Drupal module, which
provides user-related functionality for [Omnipedia](https://omnipedia.app/).

⚠️ ***[Why open source? / Spoiler warning](https://omnipedia.app/open-source)***

----

# Requirements

* [Drupal 10](https://www.drupal.org/download)

* PHP 8

* [Composer](https://getcomposer.org/)

## Drupal dependencies

Before attempting to install this, you must add the Composer repositories as
described in the installation instructions for these dependencies:

* The [`omnipedia_core` module](https://github.com/neurocracy/drupal-omnipedia-core).

----

# Installation

## Composer

### Set up

Ensure that you have your Drupal installation set up with the correct Composer
installer types such as those provided by [the `drupal/recommended-project`
template](https://www.drupal.org/docs/develop/using-composer/starting-a-site-using-drupal-composer-project-templates#s-drupalrecommended-project).
If you're starting from scratch, simply requiring that template and following
[the Drupal.org Composer
documentation](https://www.drupal.org/docs/develop/using-composer/starting-a-site-using-drupal-composer-project-templates)
should get you up and running.

### Repository

In your root `composer.json`, add the following to the `"repositories"` section:

```json
"drupal/omnipedia_user": {
  "type": "vcs",
  "url": "https://github.com/neurocracy/drupal-omnipedia-user.git"
}
```

### Installing

Once you've completed all of the above, run `composer require
"drupal/omnipedia_user:6.x-dev@dev"` in the root of your project to have
Composer install this and its required dependencies for you.

----

# Major breaking changes

The following major version bumps indicate breaking changes:

* 4.x:

  * Requires Drupal 9.5; includes backward compatible [Drupal 10](https://www.drupal.org/project/drupal/releases/10.0.0) deprecation fixes but is still not fully compatible.

  * Increases minimum version of [Hook Event Dispatcher](https://www.drupal.org/project/hook_event_dispatcher) to 3.1 and adds support for 4.0 which supports Drupal 10.

  * Removes the `omnipedia_user_import` module; you can still find it in the 3.x branch.

* 5.x:

  * Requires [Drupal 10](https://www.drupal.org/project/drupal/releases/10.0.0) due to non-backwards compatible change to [`\Drupal\Component\EventDispatcher\ContainerAwareEventDispatcher::dispatch()`](https://git.drupalcode.org/project/drupal/-/commit/7b324dd8f18919fc4d728bdb0afbcf27c8c02cb2#6e9d627c11801448b7a793c204471d8f951ae2fb).

* 6.x:

  * Removed all use of the `omnipedia_commerce` module and removed it from dependencies.
