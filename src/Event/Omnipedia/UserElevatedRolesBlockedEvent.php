<?php

namespace Drupal\omnipedia_user\Event\Omnipedia;

use Drupal\omnipedia_user\Event\Omnipedia\AbstractUserElevatedRolesEvent;

/**
 * A user account with one or more elevated roles is blocked event.
 *
 * @see \Drupal\omnipedia_user\Event\Omnipedia\UserElevatedRolesEventInterface
 */
class UserElevatedRolesBlockedEvent extends AbstractUserElevatedRolesEvent {}
