<?php

declare(strict_types=1);

namespace Drupal\omnipedia_user\Service;

use Drupal\Core\Session\AccountInterface;

/**
 * The Omnipedia user roles service interface.
 */
interface UserRolesInterface {

  /**
   * Determine if the provided user has a role with elevated permissions.
   *
   * @param \Drupal\Core\Session\AccountInterface $user
   *   The user account to check.
   *
   * @return boolean
   *   True if the user has an elevated role, false if they don't.
   */
  public function userHasElevatedRole(AccountInterface $user): bool;

}
