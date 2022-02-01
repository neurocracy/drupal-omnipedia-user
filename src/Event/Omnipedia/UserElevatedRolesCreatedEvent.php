<?php

namespace Drupal\omnipedia_user\Event\Omnipedia;

use Drupal\omnipedia_user\Event\Omnipedia\AbstractUserElevatedRolesEvent;

/**
 * A user is created with one or more elevated roles event.
 *
 * @see \Drupal\omnipedia_user\Event\Omnipedia\UserElevatedRolesEventInterface
 */
class UserElevatedRolesCreatedEvent extends AbstractUserElevatedRolesEvent {}
