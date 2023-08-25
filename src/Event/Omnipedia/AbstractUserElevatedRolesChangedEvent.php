<?php

declare(strict_types=1);

namespace Drupal\omnipedia_user\Event\Omnipedia;

use Drupal\user\UserInterface;
use Drupal\omnipedia_user\Event\Omnipedia\AbstractUserElevatedRolesEvent;

/**
 * User with elevated role(s) changed abstract event.
 */
abstract class AbstractUserElevatedRolesChangedEvent extends AbstractUserElevatedRolesEvent {

  /**
   * {@inheritdoc}
   *
   * @param \Drupal\user\UserInterface $unchangedUser
   *   The user account for which this event was triggered, before changes.
   */
  public function __construct(
    UserInterface $user,
    protected readonly UserInterface $unchangedUser,
  ) {

    parent::__construct($user);

  }

  /**
   * Get the user account for which this event was triggered, before changes.
   *
   * @return \Drupal\user\UserInterface
   */
  public function getUnchangedUser(): UserInterface {
    return $this->unchangedUser;
  }

  /**
   * Get roles that were added to the user account.
   *
   * @return string[]
   *   An array of one or more role IDs that were added.
   */
  public function getAddedRoles(): array {
    return \array_diff(
      $this->user->getRoles(false), $this->unchangedUser->getRoles(false),
    );
  }


  /**
   * Get roles that were removed from the user account.
   *
   * @return string[]
   *   An array of one or more role IDs that were removed.
   */
  public function getRemovedRoles(): array {
    return \array_diff(
      $this->unchangedUser->getRoles(false), $this->user->getRoles(false),
    );
  }

}
