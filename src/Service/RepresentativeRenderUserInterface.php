<?php

declare(strict_types=1);

namespace Drupal\omnipedia_user\Service;

use Drupal\user\UserInterface;

/**
 * The Omnipedia representative render user service interface.
 */
interface RepresentativeRenderUserInterface {

  /**
   * Get a user to render something as.
   *
   * Since we need to generate multiple cache variations that vary per user
   * permissions, we need a representative user for each variation.
   * Unfortuntely, attempting to create temporary users (i.e. that have not been
   * saved to the database) has not been successful; that approach would have
   * been preferable to isolate/sandbox any potential security issues that could
   * arise with rendering as an existing user.
   *
   * Creating the temporary users via the user entity storage, assigning the
   * correct roles, and setting them as active is the easy part. However, many
   * places in Drupal core check User::isAuthenticated(), a method which returns
   * true only if the user ID (uid) is greater than 0; a user that has not yet
   * been saved to the database will always return false. Attempting to build a
   * solution that tricks Drupal into thinking a temporary user is authenticated
   * would likely be overengineering and could introduce unforeseen implications
   * for security.
   *
   * @param array $roles
   *   An array of role IDs (rids) to match to a user.
   *
   * @param callable $accessCallback
   *   A callable to perform access checking or other validation. The callable
   *   is passed a user entity (\Drupal\user\UserInterface) with exactly the
   *   roles provided in $roles and must return true if the user can be used,
   *   i.e. can access something being rendered, or false to try the next
   *   matching user. If no user can be found with the provided roles, the
   *   callable won't be called.
   *
   * @return \Drupal\user\UserInterface|null
   *   Either a loaded user entity, or null if one can't be found that has only
   *   the provided $roles and has access to view both $node and $previousNode.
   */
  public function getUserToRenderAs(
    array $roles, callable $accessCallback
  ): ?UserInterface;

}
