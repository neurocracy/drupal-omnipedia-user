<?php

declare(strict_types=1);

namespace Drupal\omnipedia_user\Service;

use Drupal\user\UserInterface;

/**
 * The Omnipedia user permission hashes service interface.
 */
interface PermissionHashesInterface {

  /**
   * Get the permission hash for a provided user or the current user.
   *
   * @param \Drupal\user\UserInterface|null $user
   *   Either a loaded user entity, or null to indicate the current user.
   *
   * @return string
   *   The permission hash for the user.
   *
   * @see \Drupal\Core\Session\PermissionsHashGeneratorInterface::generate()
   */
  public function getPermissionHash(?UserInterface $user = null): string;

  /**
   * Get all unique permission hashes for all users.
   *
   * @return string[]
   *   An array of unique permission hash strings for all users, i.e. with all
   *   duplicate hashes reduced to a single entry. The keys are a comma-
   *   separated list of roles that the hashes correspond to.
   */
  public function getPermissionHashes(): array;

}
