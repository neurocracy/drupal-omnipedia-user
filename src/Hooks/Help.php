<?php

declare(strict_types=1);

namespace Drupal\omnipedia_user\Hooks;

use Drupal\Component\Render\MarkupInterface;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\hux\Attribute\Hook;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Help hook implementations.
 */
class Help implements ContainerInjectionInterface {

  use StringTranslationTrait;

  /**
   * Hook constructor; saves dependencies.
   *
   * @param \Drupal\Core\StringTranslation\TranslationInterface $stringTranslation
   *   The Drupal string translation service.
   */
  public function __construct(protected $stringTranslation) {}

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('string_translation'),
    );
  }

  #[Hook('help')]
  /**
   * Implements \hook_help().
   *
   * @param string $routeName
   *   The current route name.
   *
   * @param \Drupal\Core\Routing\RouteMatchInterface $routeMatch
   *   The current route match.
   *
   * @return \Drupal\Component\Render\MarkupInterface|array|string
   */
  public function help(
    string $routeName, RouteMatchInterface $routeMatch
  ): MarkupInterface|array|string {

    switch ($routeName) {
      case 'user.login':
      case 'user.pass':

        return $this->getLogInHelp();

    }

    return [];

  }

  /**
   * Get help content for user log in routes.
   *
   * @return array
   *   A render array.
   */
  protected function getLogInHelp(): array {

    return [
      '#type'   => 'html_tag',
      '#tag'    => 'p',
      '#value'  => $this->t(
        'If you\'re having trouble resetting your password, please try copying and pasting the link from the email into your browser\'s address bar as some email services can mangle the clickable link.'
      )
    ];

  }

}
