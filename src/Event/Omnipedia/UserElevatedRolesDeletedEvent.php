<?php

declare(strict_types=1);

namespace Drupal\omnipedia_user\Event\Omnipedia;

use Drupal\omnipedia_user\Event\Omnipedia\AbstractUserElevatedRolesEvent;

/**
 * A user account with one or more elevated roles is deleted event.
 *
 * @see \Drupal\omnipedia_user\Event\Omnipedia\UserElevatedRolesEventInterface
 */
class UserElevatedRolesDeletedEvent extends AbstractUserElevatedRolesEvent {}
