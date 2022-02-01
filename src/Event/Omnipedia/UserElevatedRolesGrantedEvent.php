<?php

namespace Drupal\omnipedia_user\Event\Omnipedia;

use Drupal\omnipedia_user\Event\Omnipedia\AbstractUserElevatedRolesChangedEvent;

/**
 * A user without elevated roles is granted one or more elevated roles event.
 *
 * @see \Drupal\omnipedia_user\Event\Omnipedia\UserElevatedRolesEventInterface
 */
class UserElevatedRolesGrantedEvent extends AbstractUserElevatedRolesChangedEvent {}
