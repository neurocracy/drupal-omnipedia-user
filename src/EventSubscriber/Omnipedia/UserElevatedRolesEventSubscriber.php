<?php

namespace Drupal\omnipedia_user\EventSubscriber\Omnipedia;

use Drupal\omnipedia_user\Event\Omnipedia\UserElevatedRolesBlockedEvent;
use Drupal\omnipedia_user\Event\Omnipedia\UserElevatedRolesChangedEvent;
use Drupal\omnipedia_user\Event\Omnipedia\UserElevatedRolesCreatedEvent;
use Drupal\omnipedia_user\Event\Omnipedia\UserElevatedRolesDeletedEvent;
use Drupal\omnipedia_user\Event\Omnipedia\UserElevatedRolesDeniedEvent;
use Drupal\omnipedia_user\Event\Omnipedia\UserElevatedRolesEventInterface;
use Drupal\omnipedia_user\Event\Omnipedia\UserElevatedRolesGrantedEvent;
use Drupal\omnipedia_user\Event\Omnipedia\UserElevatedRolesLoggedInEvent;
use Drupal\omnipedia_user\Event\Omnipedia\UserElevatedRolesUnblockedEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Event subscriber to perform actions relating to users with elevated role(s).
 */
class UserElevatedRolesEventSubscriber implements EventSubscriberInterface {

  /**
   * Our logger channel.
   *
   * @var \Psr\Log\LoggerInterface
   */
  protected $loggerChannel;

  /**
   * Event subscriber constructor; saves dependencies.
   *
   * @param \Psr\Log\LoggerInterface $loggerChannel
   *   Our logger channel.
   */
  public function __construct(
    LoggerInterface $loggerChannel
  ) {
    $this->loggerChannel = $loggerChannel;
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents(): array {
    return [
      UserElevatedRolesEventInterface::BLOCKED   => 'onBlocked',
      UserElevatedRolesEventInterface::CHANGED   => 'onChanged',
      UserElevatedRolesEventInterface::CREATED   => 'onCreated',
      UserElevatedRolesEventInterface::DELETED   => 'onDeleted',
      UserElevatedRolesEventInterface::DENIED    => 'onDenied',
      UserElevatedRolesEventInterface::GRANTED   => 'onGranted',
      UserElevatedRolesEventInterface::LOGGED_IN => 'onLoggedIn',
      UserElevatedRolesEventInterface::UNBLOCKED => 'onUnblocked',
    ];
  }

  /**
   * Perform actions when a user with elevated roles has been blocked.
   *
   * @param \Drupal\omnipedia_user\Event\Omnipedia\UserElevatedRolesBlockedEvent $event
   *   The event object.
   */
  public function onBlocked(UserElevatedRolesBlockedEvent $event): void {

    $this->loggerChannel->notice(
      'A user with elevated roles was blocked: %name',
      [
        'link'  => $event->getUser()->toLink('View user')->toString(),
        '%name' => $event->getUser()->getDisplayName(),
      ]
    );

  }

  /**
   * Perform actions when a user's elevated roles have changed.
   *
   * @param \Drupal\omnipedia_user\Event\Omnipedia\UserElevatedRolesChangedEvent $event
   *   The event object.
   */
  public function onChanged(UserElevatedRolesChangedEvent $event): void {

    /** @var string */
    $rolesAdded   = \implode(', ', $event->getAddedRoles());

    /** @var string */
    $rolesRemoved = \implode(', ', $event->getRemovedRoles());

    // Do nothing if roles are the same.
    if (empty($rolesAdded) && empty($rolesRemoved)) {
      return;
    }

    if (empty($rolesAdded)) {
      $rolesAdded = 'none';
    }

    if (empty($rolesRemoved)) {
      $rolesRemoved = 'none';
    }

    $this->loggerChannel->notice(
      'A user has had elevated roles changed: %name <br>Roles added: %rolesAdded <br>Roles removed: %rolesRemoved',
      [
        'link'          => $event->getUser()->toLink('View user')->toString(),
        '%name'         => $event->getUser()->getDisplayName(),
        '%rolesAdded'   => $rolesAdded,
        '%rolesRemoved' => $rolesRemoved,
      ]
    );

  }

  /**
   * Perform actions when a user with elevated roles has been created.
   *
   * @param \Drupal\omnipedia_user\Event\Omnipedia\UserElevatedRolesCreatedEvent $event
   *   The event object.
   */
  public function onCreated(UserElevatedRolesCreatedEvent $event): void {

    $this->loggerChannel->notice(
      'A user with elevated roles was created: %name',
      [
        'link'  => $event->getUser()->toLink('View user')->toString(),
        '%name' => $event->getUser()->getDisplayName(),
      ]
    );

  }

  /**
   * Perform actions when a user with elevated roles has been deleted.
   *
   * @param \Drupal\omnipedia_user\Event\Omnipedia\UserElevatedRolesDeletedEvent $event
   *   The event object.
   */
  public function onDeleted(UserElevatedRolesDeletedEvent $event): void {

    $this->loggerChannel->notice(
      'A user with elevated roles was deleted: %name',
      [
        '%name' => $event->getUser()->getDisplayName(),
      ]
    );

  }

  /**
   * Perform actions when a user has had all elevated roles removed.
   *
   * @param \Drupal\omnipedia_user\Event\Omnipedia\UserElevatedRolesDeniedEvent $event
   *   The event object.
   */
  public function onDenied(UserElevatedRolesDeniedEvent $event): void {

    $this->loggerChannel->notice(
      'A user has had all elevated roles removed: %name <br>Roles removed: %roles',
      [
        'link'    => $event->getUser()->toLink('View user')->toString(),
        '%name'   => $event->getUser()->getDisplayName(),
        '%roles'  => \implode(', ', $event->getRemovedRoles()),
      ]
    );

  }

  /**
   * Perform actions when a user without elevated roles has been granted them.
   *
   * @param \Drupal\omnipedia_user\Event\Omnipedia\UserElevatedRolesGrantedEvent $event
   *   The event object.
   */
  public function onGranted(UserElevatedRolesGrantedEvent $event): void {

    $this->loggerChannel->notice(
      'A user has had one or more elevated roles granted: %name <br>Roles added: %roles',
      [
        'link'    => $event->getUser()->toLink('View user')->toString(),
        '%name'   => $event->getUser()->getDisplayName(),
        '%roles'  => \implode(', ', $event->getAddedRoles()),
      ]
    );

  }

  /**
   * Perform actions when a user with one or more elevated roles has logged in.
   *
   * @param \Drupal\omnipedia_user\Event\Omnipedia\UserElevatedRolesLoggedInEvent $event
   *   The event object.
   */
  public function onLoggedIn(UserElevatedRolesLoggedInEvent $event): void {

    $this->loggerChannel->notice(
      'A user with elevated roles has logged in: %name',
      [
        'link'  => $event->getUser()->toLink('View user')->toString(),
        '%name' => $event->getUser()->getDisplayName(),
      ]
    );

  }

  /**
   * Perform actions when a user with elevated roles has been unblocked.
   *
   * @param \Drupal\omnipedia_user\Event\Omnipedia\UserElevatedRolesUnblockedEvent $event
   *   The event object.
   */
  public function onUnblocked(UserElevatedRolesUnblockedEvent $event): void {

    $this->loggerChannel->notice(
      'A user with elevated roles was unblocked: %name',
      [
        'link'  => $event->getUser()->toLink('View user')->toString(),
        '%name' => $event->getUser()->getDisplayName(),
      ]
    );

  }

}
