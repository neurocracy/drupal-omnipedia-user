<?php

declare(strict_types=1);

namespace Drupal\omnipedia_user\Event\Omnipedia;

use Drupal\omnipedia_user\Event\Omnipedia\AbstractUserElevatedRolesChangedEvent;

/**
 * A user has had all elevated roles removed event.
 *
 * @see \Drupal\omnipedia_user\Event\Omnipedia\UserElevatedRolesEventInterface
 */
class UserElevatedRolesDeniedEvent extends AbstractUserElevatedRolesChangedEvent {}
