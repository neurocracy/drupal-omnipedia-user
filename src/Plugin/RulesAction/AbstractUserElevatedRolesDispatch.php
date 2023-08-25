<?php

declare(strict_types=1);

namespace Drupal\omnipedia_user\Plugin\RulesAction;

use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\rules\Core\RulesActionBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Abstract user with elevated role(s) dispatch action.
 */
abstract class AbstractUserElevatedRolesDispatch extends RulesActionBase implements ContainerFactoryPluginInterface {

  /**
   * {@inheritdoc}
   *
   * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcher
   *   The Symfony event dispatcher service.
   */
  public function __construct(
    array $configuration, $pluginId, $pluginDefinition,
    protected readonly EventDispatcherInterface $eventDispatcher,
  ) {

    parent::__construct($configuration, $pluginId, $pluginDefinition);

  }

  /**
   * {@inheritdoc}
   */
  public static function create(
    ContainerInterface $container,
    array $configuration, $pluginId, $pluginDefinition,
  ) {
    return new static(
      $configuration,
      $pluginId,
      $pluginDefinition,
      $container->get('event_dispatcher'),
    );
  }

}
