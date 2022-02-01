<?php

namespace Drupal\omnipedia_user\Plugin\RulesAction;

use Drupal\omnipedia_user\Event\Omnipedia\UserElevatedRolesCreatedEvent;
use Drupal\omnipedia_user\Event\Omnipedia\UserElevatedRolesEventInterface;
use Drupal\omnipedia_user\Plugin\RulesAction\AbstractUserElevatedRolesDispatch;
use Drupal\user\UserInterface;

/**
 * Provides a 'Dispatch event when a user is created with elevated role(s)' action.
 *
 * @RulesAction(
 *   id       = "omnipedia_user_elevated_roles_created_dispatch",
 *   label    = @Translation("Dispatch event when a user is created with elevated role(s)"),
 *   category = @Translation("User"),
 *   context_definitions = {
 *     "user" = @ContextDefinition("entity:user",
 *       label                  = @Translation("User"),
 *       description            = @Translation("The user that was created."),
 *       assignment_restriction = "selector",
 *       default_value          = "user",
 *     ),
 *   }
 * )
 */
class UserElevatedRolesCreatedDispatch extends AbstractUserElevatedRolesDispatch {

  /**
   * Dispatch an event when this action is executed.
   *
   * @param \Drupal\user\UserInterface $user
   *   The user account.
   */
  protected function doExecute(UserInterface $user) {

    /** @var \Drupal\omnipedia_user\Event\Omnipedia\UserElevatedRolesCreatedEvent */
    $event = new UserElevatedRolesCreatedEvent($user);

    $this->eventDispatcher->dispatch(
      UserElevatedRolesEventInterface::CREATED, $event
    );

  }

}
