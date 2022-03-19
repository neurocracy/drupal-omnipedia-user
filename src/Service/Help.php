<?php declare(strict_types=1);

namespace Drupal\omnipedia_user\Service;

use Drupal\Component\Render\MarkupInterface;
use Drupal\Core\Link;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\StringTranslation\TranslationInterface;
use Drupal\omnipedia_commerce\Service\ContentAccessProductInterface;
use Drupal\omnipedia_core\Service\HelpInterface;

/**
 * The Omnipedia user help service.
 */
class Help implements HelpInterface {

  use StringTranslationTrait;

  /**
   * The current user proxy service.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $currentUser;

  /**
   * The Omnipedia content access product service.
   *
   * @var \Drupal\omnipedia_commerce\Service\ContentAccessProductInterface
   */
  protected $contentAccessProduct;

  /**
   * Service constructor; saves dependencies.
   *
   * @param \Drupal\Core\Session\AccountProxyInterface $currentUser
   *   The current user proxy service.
   *
   * @param \Drupal\omnipedia_commerce\Service\ContentAccessProductInterface $contentAccessProduct
   *   The Omnipedia content access product service.
   *
   * @param \Drupal\Core\StringTranslation\TranslationInterface $stringTranslation
   *   The Drupal string translation service.
   */
  public function __construct(
    AccountProxyInterface         $currentUser,
    ContentAccessProductInterface $contentAccessProduct,
    TranslationInterface          $stringTranslation
  ) {
    $this->currentUser          = $currentUser;
    $this->contentAccessProduct = $contentAccessProduct;
    $this->stringTranslation    = $stringTranslation;
  }

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

    /** @var \Drupal\commerce_product\Entity\ProductInterface|null */
    $product = $this->contentAccessProduct->getBaseProduct();

    // Don't render anything if the base product has not been configured.
    if (
      !\is_object($product) ||
      !$product->access('view', $this->currentUser)
    ) {
      return [];
    }

    /** @var \Drupal\Core\Link */
    $link = new Link($this->t('joining Omnipedia'), $product->toUrl());

    return [
      '#type'   => 'html_tag',
      '#tag'    => 'p',
      '#value'  => $this->t(
        'Don\'t have an account? You can acquire one by @joining.',
        [
          // Unfortunately, this needs to be rendered here or it'll cause a
          // fatal error when Drupal tries to pass it to \htmlspecialchars().
          '@joining' => $link->toString(),
        ]
      )
    ];

  }

}
