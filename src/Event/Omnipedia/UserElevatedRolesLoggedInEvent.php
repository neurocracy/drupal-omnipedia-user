<?php

namespace Drupal\omnipedia_user\Event\Omnipedia;

use Drupal\omnipedia_user\Event\Omnipedia\AbstractUserElevatedRolesEvent;

/**
 * A user with one or more elevated roles has logged in event.
 *
 * @see \Drupal\omnipedia_user\Event\Omnipedia\UserElevatedRolesEventInterface
 */
class UserElevatedRolesLoggedInEvent extends AbstractUserElevatedRolesEvent {}
