<?php

declare(strict_types=1);

namespace Drupal\omnipedia_user\Event\Omnipedia;

use Drupal\user\UserInterface;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * User with elevated role(s) abstract event.
 */
abstract class AbstractUserElevatedRolesEvent extends Event {

  /**
   * Constructs this event object.
   *
   * @param \Drupal\user\UserInterface $user
   *   The user account for which this event was triggered.
   */
  public function __construct(protected readonly UserInterface $user) {}

  /**
   * Get the user account for which this event was triggered.
   *
   * @return \Drupal\user\UserInterface
   */
  public function getUser(): UserInterface {
    return $this->user;
  }

}
