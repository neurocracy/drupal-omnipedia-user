<?php

declare(strict_types=1);

namespace Drupal\omnipedia_user\Service;

use Drupal\omnipedia_user\Service\UserRolesInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * The Omnipedia user roles service interface.
 */
class UserRoles implements UserRolesInterface {

  /**
   * {@inheritdoc}
   *
   * @todo Find a more thorough heuristic for whether a user has what can be
   *   considered an elevated role or permissions. Alternatively, add a
   *   configuration form that allows selecting which roles are to be considered
   *   as having elevated roles.
   */
  public function userHasElevatedRole(AccountInterface $user): bool {

    // The assumption here is that if a user can access administration pages,
    // they very likely have some sort of elevated permission. Note that this is
    // a really crude heuristic that doesn't cover instances where an account
    // has some sort of elevated permission but cannot access admin pages.
    return $user->hasPermission('access administration pages');

  }

}
