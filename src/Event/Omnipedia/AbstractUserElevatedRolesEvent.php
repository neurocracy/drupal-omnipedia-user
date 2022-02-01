<?php

namespace Drupal\omnipedia_user\Event\Omnipedia;

use Drupal\user\UserInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * User with elevated role(s) abstract event.
 */
abstract class AbstractUserElevatedRolesEvent extends Event {

  /**
   * The user account for which this event was triggered.
   *
   * @var \Drupal\user\UserInterface
   */
  protected $user;

  /**
   * Constructs this event object.
   *
   * @param \Drupal\user\UserInterface $user
   *   The user account for which this event was triggered.
   */
  public function __construct(UserInterface $user) {
    $this->user = $user;
  }

  /**
   * Get the user account for which this event was triggered.
   *
   * @return \Drupal\user\UserInterface
   */
  public function getUser(): UserInterface {
    return $this->user;
  }

}
