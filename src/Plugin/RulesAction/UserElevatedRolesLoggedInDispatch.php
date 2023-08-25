<?php

declare(strict_types=1);

namespace Drupal\omnipedia_user\Plugin\RulesAction;

use Drupal\omnipedia_user\Event\Omnipedia\UserElevatedRolesEventInterface;
use Drupal\omnipedia_user\Event\Omnipedia\UserElevatedRolesLoggedInEvent;
use Drupal\omnipedia_user\Plugin\RulesAction\AbstractUserElevatedRolesDispatch;
use Drupal\user\UserInterface;

/**
 * Provides a 'Dispatch event when a user with elevated role(s) has logged in' action.
 *
 * @RulesAction(
 *   id       = "omnipedia_user_elevated_roles_logged_in_dispatch",
 *   label    = @Translation("Dispatch event when a user with elevated role(s) has logged in"),
 *   category = @Translation("User"),
 *   context_definitions = {
 *     "user" = @ContextDefinition("entity:user",
 *       label = @Translation("User"),
 *       description = @Translation("The user that logged in.")
 *     ),
 *   }
 * )
 */
class UserElevatedRolesLoggedInDispatch extends AbstractUserElevatedRolesDispatch {

  /**
   * Dispatch an event when this action is executed.
   *
   * @param \Drupal\user\UserInterface $user
   *   The user account.
   */
  protected function doExecute(UserInterface $user) {

    /** @var \Drupal\omnipedia_user\Event\Omnipedia\UserElevatedRolesLoggedInEvent */
    $event = new UserElevatedRolesLoggedInEvent($user);

    $this->eventDispatcher->dispatch(
      $event, UserElevatedRolesEventInterface::LOGGED_IN,
    );

  }

}
