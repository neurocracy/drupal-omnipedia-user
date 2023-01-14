<?php

declare(strict_types=1);

namespace Drupal\omnipedia_user\Plugin\RulesAction;

use Drupal\omnipedia_user\Event\Omnipedia\UserElevatedRolesDeniedEvent;
use Drupal\omnipedia_user\Event\Omnipedia\UserElevatedRolesEventInterface;
use Drupal\omnipedia_user\Plugin\RulesAction\AbstractUserElevatedRolesDispatch;
use Drupal\user\UserInterface;

/**
 * Provides a 'Dispatch event when a user has all elevated role(s) removed' action.
 *
 * @RulesAction(
 *   id       = "omnipedia_user_elevated_roles_denied_dispatch",
 *   label    = @Translation("Dispatch event when a user has all elevated role(s) removed"),
 *   category = @Translation("User"),
 *   context_definitions = {
 *     "user" = @ContextDefinition("entity:user",
 *       label                  = @Translation("User"),
 *       description            = @Translation("The user that has had all elevated role(s) removed."),
 *       assignment_restriction = "selector",
 *       default_value          = "user",
 *     ),
 *     "user_unchanged" = @ContextDefinition("entity:user",
 *       label                  = @Translation("Unchanged user"),
 *       description            = @Translation("The user before elevated role(s) were removed."),
 *       assignment_restriction = "selector",
 *       default_value          = "user_unchanged",
 *     ),
 *   }
 * )
 */
class UserElevatedRolesDeniedDispatch extends AbstractUserElevatedRolesDispatch {

  /**
   * Dispatch an event when this action is executed.
   *
   * @param \Drupal\user\UserInterface $user
   *   The user account.
   *
   * @param \Drupal\user\UserInterface $user_unchanged
   *   The user account before having all elevated role(s) removed.
   */
  protected function doExecute(
    UserInterface $user,
    UserInterface $user_unchanged
  ) {

    /** @var \Drupal\omnipedia_user\Event\Omnipedia\UserElevatedRolesDeniedEvent */
    $event = new UserElevatedRolesDeniedEvent($user, $user_unchanged);

    $this->eventDispatcher->dispatch(
      UserElevatedRolesEventInterface::DENIED, $event
    );

  }

}
