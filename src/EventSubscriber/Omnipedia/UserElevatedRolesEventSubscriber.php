<?php

declare(strict_types=1);

namespace Drupal\omnipedia_user\EventSubscriber\Omnipedia;

use Drupal\Core\Logger\RfcLogLevel;
use Drupal\omnipedia_user\Event\Omnipedia\UserElevatedRolesBlockedEvent;
use Drupal\omnipedia_user\Event\Omnipedia\UserElevatedRolesChangedEvent;
use Drupal\omnipedia_user\Event\Omnipedia\UserElevatedRolesCreatedEvent;
use Drupal\omnipedia_user\Event\Omnipedia\UserElevatedRolesDeletedEvent;
use Drupal\omnipedia_user\Event\Omnipedia\UserElevatedRolesDeniedEvent;
use Drupal\omnipedia_user\Event\Omnipedia\UserElevatedRolesEventInterface;
use Drupal\omnipedia_user\Event\Omnipedia\UserElevatedRolesGrantedEvent;
use Drupal\omnipedia_user\Event\Omnipedia\UserElevatedRolesLoggedInEvent;
use Drupal\omnipedia_user\Event\Omnipedia\UserElevatedRolesUnblockedEvent;
use Drupal\user\UserInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Event subscriber to perform actions relating to users with elevated role(s).
 */
class UserElevatedRolesEventSubscriber implements EventSubscriberInterface {

  /**
   * Event subscriber constructor; saves dependencies.
   *
   * @param \Psr\Log\LoggerInterface $loggerChannel
   *   Our logger channel.
   */
  public function __construct(
    protected readonly LoggerInterface $loggerChannel,
  ) {}

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
   * Log a message to the logger.
   *
   * @param string $message
   *   The untranslated message to log.
   *
   * @param array $context
   *   Context variables to pass to the logger, i.e. to replace placeholders in
   *   $message.
   *
   * @param \Drupal\user\UserInterface $user
   *   The user entity to log a message about.
   *
   * @param string|int $severity
   *   The severity level to log. Defaults to
   *   \Drupal\Core\Logger\RfcLogLevel::NOTICE
   */
  protected function log(
    string $message, array $context,
    UserInterface $user,
    string|int $severity = RfcLogLevel::NOTICE,
  ): void {

    // Add a link to the user if not set.
    if (!isset($context['link'])) {
      $context['link'] = $this->getUserLink($user);
    }

    // Provide %name if not set.
    if (!isset($context['%name'])) {
      $context['%name'] = $user->getDisplayName();
    }

    $this->loggerChannel->log($severity, $message, $context);

  }

  /**
   * Get a rendered link for the provided user account.
   *
   * @param \Drupal\user\UserInterface $user
   *   The user entity to return a link for.
   *
   * @return string
   *   The rendered link, suitable for the 'link' context for a log message.
   */
  protected function getUserLink(UserInterface $user): string {

    // \Drupal\Core\Link::toString() bafflingly does not return a string but an
    // instance of \Drupal\Core\GeneratedLink which needs to be cast to a
    // string.
    return (string) $user->toLink(
      'View user', 'canonical', ['absolute' => true],
    )->toString();

  }

  /**
   * Perform actions when a user with elevated roles has been blocked.
   *
   * @param \Drupal\omnipedia_user\Event\Omnipedia\UserElevatedRolesBlockedEvent $event
   *   The event object.
   */
  public function onBlocked(UserElevatedRolesBlockedEvent $event): void {

    $this->log(
      'A user with elevated roles was blocked: %name', [], $event->getUser(),
    );

  }

  /**
   * Perform actions when a user's elevated roles have changed.
   *
   * @param \Drupal\omnipedia_user\Event\Omnipedia\UserElevatedRolesChangedEvent $event
   *   The event object.
   */
  public function onChanged(UserElevatedRolesChangedEvent $event): void {

    /** @var \Drupal\user\UserInterface */
    $targetUser = $event->getUser();

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

    $this->log(
      'A user has had elevated roles changed: %name <br>Roles added: %rolesAdded <br>Roles removed: %rolesRemoved',
      [
        '%rolesAdded'   => $rolesAdded,
        '%rolesRemoved' => $rolesRemoved,
      ],
      $targetUser,
    );

  }

  /**
   * Perform actions when a user with elevated roles has been created.
   *
   * @param \Drupal\omnipedia_user\Event\Omnipedia\UserElevatedRolesCreatedEvent $event
   *   The event object.
   */
  public function onCreated(UserElevatedRolesCreatedEvent $event): void {

    $this->log(
      'A user with elevated roles was created: %name', [], $event->getUser(),
    );

  }

  /**
   * Perform actions when a user with elevated roles has been deleted.
   *
   * @param \Drupal\omnipedia_user\Event\Omnipedia\UserElevatedRolesDeletedEvent $event
   *   The event object.
   */
  public function onDeleted(UserElevatedRolesDeletedEvent $event): void {

    $this->log(
      'A user with elevated roles was deleted: %name', [], $event->getUser(),
    );

  }

  /**
   * Perform actions when a user has had all elevated roles removed.
   *
   * @param \Drupal\omnipedia_user\Event\Omnipedia\UserElevatedRolesDeniedEvent $event
   *   The event object.
   */
  public function onDenied(UserElevatedRolesDeniedEvent $event): void {

    $this->log(
      'A user has had all elevated roles removed: %name <br>Roles removed: %roles',
      [
        '%roles'  => \implode(', ', $event->getRemovedRoles()),
      ],
      $event->getUser(),
    );

  }

  /**
   * Perform actions when a user without elevated roles has been granted them.
   *
   * @param \Drupal\omnipedia_user\Event\Omnipedia\UserElevatedRolesGrantedEvent $event
   *   The event object.
   */
  public function onGranted(UserElevatedRolesGrantedEvent $event): void {

    $this->log(
      'A user has had one or more elevated roles granted: %name <br>Roles added: %roles',
      [
        '%roles'  => \implode(', ', $event->getAddedRoles()),
      ],
      $event->getUser(),
    );

  }

  /**
   * Perform actions when a user with one or more elevated roles has logged in.
   *
   * @param \Drupal\omnipedia_user\Event\Omnipedia\UserElevatedRolesLoggedInEvent $event
   *   The event object.
   */
  public function onLoggedIn(UserElevatedRolesLoggedInEvent $event): void {

    $this->log(
      'A user with elevated roles has logged in: %name', [], $event->getUser(),
    );

  }

  /**
   * Perform actions when a user with elevated roles has been unblocked.
   *
   * @param \Drupal\omnipedia_user\Event\Omnipedia\UserElevatedRolesUnblockedEvent $event
   *   The event object.
   */
  public function onUnblocked(UserElevatedRolesUnblockedEvent $event): void {

    $this->log(
      'A user with elevated roles was unblocked: %name', [], $event->getUser(),
    );

  }

}
