<?php

namespace Drupal\omnipedia_user\Plugin\RulesAction;

use Drupal\omnipedia_user\Event\Omnipedia\UserElevatedRolesChangedEvent;
use Drupal\omnipedia_user\Event\Omnipedia\UserElevatedRolesEventInterface;
use Drupal\omnipedia_user\Plugin\RulesAction\AbstractUserElevatedRolesDispatch;
use Drupal\user\UserInterface;

/**
 * Provides a 'Dispatch event when a user's elevated role(s) change' action.
 *
 * @RulesAction(
 *   id       = "omnipedia_user_elevated_roles_changed_dispatch",
 *   label    = @Translation("Dispatch event when a user's elevated role(s) change"),
 *   category = @Translation("User"),
 *   context_definitions = {
 *     "user" = @ContextDefinition("entity:user",
 *       label                  = @Translation("User"),
 *       description            = @Translation("The user that had elevated role(s) changed."),
 *       assignment_restriction = "selector",
 *       default_value          = "user",
 *     ),
 *     "user_unchanged" = @ContextDefinition("entity:user",
 *       label                  = @Translation("Unchanged user"),
 *       description            = @Translation("The user before elevated role(s) were changed."),
 *       assignment_restriction = "selector",
 *       default_value          = "user_unchanged",
 *     ),
 *   }
 * )
 */
class UserElevatedRolesChangedDispatch extends AbstractUserElevatedRolesDispatch {

  /**
   * Dispatch an event when this action is executed.
   *
   * @param \Drupal\user\UserInterface $user
   *   The user account.
   *
   * @param \Drupal\user\UserInterface $user_unchanged
   *   The user account before being changed.
   */
  protected function doExecute(
    UserInterface $user,
    UserInterface $user_unchanged
  ) {

    /** @var \Drupal\omnipedia_user\Event\Omnipedia\UserElevatedRolesChangedEvent */
    $event = new UserElevatedRolesChangedEvent($user, $user_unchanged);

    $this->eventDispatcher->dispatch(
      UserElevatedRolesEventInterface::CHANGED, $event
    );

  }

}
