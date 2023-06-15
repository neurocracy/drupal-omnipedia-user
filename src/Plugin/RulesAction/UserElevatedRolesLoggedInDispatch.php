<?php

declare(strict_types=1);

namespace Drupal\omnipedia_user\Plugin\RulesAction;

use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\omnipedia_user\Event\Omnipedia\UserElevatedRolesEventInterface;
use Drupal\omnipedia_user\Event\Omnipedia\UserElevatedRolesLoggedInEvent;
use Drupal\rules\Core\RulesActionBase;
use Drupal\user\UserInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Provides a 'Dispatch event when a user with elevated role(s) has logged in' action.
 *
 * @RulesAction(
 *   id       = "omnipedia_user_elevated_roles_logged_in_dispatch",
 *   label    = @Translation("Dispatch event when a user with elevated role(s) has logged in"),
 *   category = @Translation("User"),
 *   context_definitions = {
 *     "user" = @ContextDefinition("entity:user",
 *       label = @Translation("User"),
 *       description = @Translation("The user that logged in.")
 *     ),
 *   }
 * )
 */
class UserElevatedRolesLoggedInDispatch extends RulesActionBase implements ContainerFactoryPluginInterface {

  /**
   * The Symfony event dispatcher service.
   *
   * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
   */
  protected EventDispatcherInterface $eventDispatcher;

  /**
   * {@inheritdoc}
   *
   * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcher
   *   The Symfony event dispatcher service.
   */
  public function __construct(
    array $configuration, $pluginId, $pluginDefinition,
    EventDispatcherInterface $eventDispatcher
  ) {

    parent::__construct($configuration, $pluginId, $pluginDefinition);

    $this->eventDispatcher = $eventDispatcher;

  }

  /**
   * {@inheritdoc}
   */
  public static function create(
    ContainerInterface $container,
    array $configuration, $pluginId, $pluginDefinition
  ) {
    return new static(
      $configuration,
      $pluginId,
      $pluginDefinition,
      $container->get('event_dispatcher')
    );
  }

  /**
   * Dispatch an event when this action is executed.
   *
   * @param \Drupal\user\UserInterface $user
   *   The user account.
   */
  protected function doExecute(UserInterface $user) {

    /** @var \Drupal\omnipedia_user\Event\Omnipedia\UserElevatedRolesLoggedInEvent */
    $event = new UserElevatedRolesLoggedInEvent($user);

    $this->eventDispatcher->dispatch(
      $event, UserElevatedRolesEventInterface::LOGGED_IN
    );

  }

}
