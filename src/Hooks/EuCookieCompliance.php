<?php

declare(strict_types=1);

namespace Drupal\omnipedia_user\Hooks;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Routing\AdminContext;
use Drupal\hux\Attribute\Alter;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * EU Cookie Compliance hook implementations.
 *
 * The EU Cookie Compliance module introduced a change (very likely in 8.x-1.15)
 * that forces the banner markup to be attached even on admin pages when the
 * option to exclude admin pages is enabled. This hook removes the
 * eu_cookie_compliance libraries and drupalSettings key if the setting to
 * exclude admin pages is set to true. This is a hack and intended as a
 * temporary workaround.
 *
 * Note that the 'config:eu_cookie_compliance.settings' cache tag is not removed
 * as that should be present even if the pop-up isn't present, so that it may be
 * added when that config changes depending on settings.
 *
 * @see \hook_eu_cookie_compliance_show_popup_alter()
 *   Use of this hook was attempted but the module seems to override whatever we
 *   set after that's invoked.
 *
 * @see https://www.drupal.org/project/eu_cookie_compliance/issues/3236590
 *
 * @see https://www.drupal.org/project/eu_cookie_compliance/releases/8.x-1.15
 *
 * @todo Remove this once we switch to another cookie/privacy pop-up.
 */
class EuCookieCompliance implements ContainerInjectionInterface {

  /**
   * Service constructor; saves dependencies.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $configFactory
   *   The Drupal configuration factory service.
   *
   * @param \Drupal\Core\Routing\AdminContext $adminContext
   *   The Drupal admin context service.
   */
  public function __construct(
    protected readonly ConfigFactoryInterface $configFactory,
    protected readonly AdminContext           $adminContext,
  ) {}

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('router.admin_context'),
    );
  }

  #[Alter('page_attachments')]
  /**
   * Implements \hook_page_attachments_alter().
   *
   * @param array &$attachments
   *   Array of all attachments provided by \hook_page_attachments()
   *   implementations.
   */
  public function pageAttachmentsAlter(array &$variables): void {

    // Bail if not an admin route or the EU Cookie Compliance drupalSettings are
    // not present.
    if (
      !$this->adminContext->isAdminRoute() ||
      !isset($variables['#attached']['drupalSettings']['eu_cookie_compliance'])
    ) {
      return;
    }

    /** @var \Drupal\Core\Config\ImmutableConfig The EU Cookie Compliance module config. */
    $config = $this->configFactory->get('eu_cookie_compliance.settings');

    if ($config->get('exclude_admin_theme') === false) {
      return;
    }

    unset($variables['#attached']['drupalSettings']['eu_cookie_compliance']);

    foreach ($variables['#attached']['library'] as $key => $value) {
      if (\strpos($value, 'eu_cookie_compliance/') === 0) {
        unset($variables['#attached']['library'][$key]);
      }
    }

  }

}
