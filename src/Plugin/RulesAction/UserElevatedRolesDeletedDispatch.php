<?php

namespace Drupal\omnipedia_user\Plugin\RulesAction;

use Drupal\omnipedia_user\Event\Omnipedia\UserElevatedRolesDeletedEvent;
use Drupal\omnipedia_user\Event\Omnipedia\UserElevatedRolesEventInterface;
use Drupal\omnipedia_user\Plugin\RulesAction\AbstractUserElevatedRolesDispatch;
use Drupal\user\UserInterface;

/**
 * Provides a 'Dispatch event when a user with elevated role(s) is deleted' action.
 *
 * @RulesAction(
 *   id       = "omnipedia_user_elevated_roles_deleted_dispatch",
 *   label    = @Translation("Dispatch event when a user with elevated role(s) is deleted"),
 *   category = @Translation("User"),
 *   context_definitions = {
 *     "user" = @ContextDefinition("entity:user",
 *       label                  = @Translation("User"),
 *       description            = @Translation("The user that was deleted."),
 *       assignment_restriction = "selector",
 *       default_value          = "user",
 *     ),
 *   }
 * )
 */
class UserElevatedRolesDeletedDispatch extends AbstractUserElevatedRolesDispatch {

  /**
   * Dispatch an event when this action is executed.
   *
   * @param \Drupal\user\UserInterface $user
   *   The user account.
   */
  protected function doExecute(UserInterface $user) {

    /** @var \Drupal\omnipedia_user\Event\Omnipedia\UserElevatedRolesDeletedEvent */
    $event = new UserElevatedRolesDeletedEvent($user);

    $this->eventDispatcher->dispatch(
      UserElevatedRolesEventInterface::DELETED, $event
    );

  }

}
