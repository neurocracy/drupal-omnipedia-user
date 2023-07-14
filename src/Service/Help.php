<?php

declare(strict_types=1);

namespace Drupal\omnipedia_user\Service;

use Drupal\Component\Render\MarkupInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\omnipedia_core\Service\HelpInterface;

/**
 * The Omnipedia user help service.
 */
class Help implements HelpInterface {

  use StringTranslationTrait;

  /**
   * Service constructor; saves dependencies.
   *
   * @param \Drupal\Core\StringTranslation\TranslationInterface $stringTranslation
   *   The Drupal string translation service.
   */
  public function __construct(protected $stringTranslation) {}

  /**
   * {@inheritdoc}
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
