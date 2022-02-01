<?php

namespace Drupal\omnipedia_user\Plugin\RulesAction;

use Drupal\omnipedia_user\Event\Omnipedia\UserElevatedRolesEventInterface;
use Drupal\omnipedia_user\Event\Omnipedia\UserElevatedRolesGrantedEvent;
use Drupal\omnipedia_user\Plugin\RulesAction\AbstractUserElevatedRolesDispatch;
use Drupal\user\UserInterface;

/**
 * Provides a 'Dispatch event when a user is granted elevated role(s)' action.
 *
 * @RulesAction(
 *   id       = "omnipedia_user_elevated_roles_granted_dispatch",
 *   label    = @Translation("Dispatch event when a user is granted elevated role(s)"),
 *   category = @Translation("User"),
 *   context_definitions = {
 *     "user" = @ContextDefinition("entity:user",
 *       label                  = @Translation("User"),
 *       description            = @Translation("The user that was granted elevated role(s)."),
 *       assignment_restriction = "selector",
 *       default_value          = "user",
 *     ),
 *     "user_unchanged" = @ContextDefinition("entity:user",
 *       label                  = @Translation("Unchanged user"),
 *       description            = @Translation("The user before being granted elevated role(s)."),
 *       assignment_restriction = "selector",
 *       default_value          = "user_unchanged",
 *     ),
 *   }
 * )
 */
class UserElevatedRolesGrantedDispatch extends AbstractUserElevatedRolesDispatch {

  /**
   * Dispatch an event when this action is executed.
   *
   * @param \Drupal\user\UserInterface $user
   *   The user account.
   *
   * @param \Drupal\user\UserInterface $user_unchanged
   *   The user account before being granted elevated role(s).
   */
  protected function doExecute(
    UserInterface $user,
    UserInterface $user_unchanged
  ) {

    /** @var \Drupal\omnipedia_user\Event\Omnipedia\UserElevatedRolesGrantedEvent */
    $event = new UserElevatedRolesGrantedEvent($user, $user_unchanged);

    $this->eventDispatcher->dispatch(
      UserElevatedRolesEventInterface::GRANTED, $event
    );

  }

}
