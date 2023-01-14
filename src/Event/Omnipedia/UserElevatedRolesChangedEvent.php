<?php

declare(strict_types=1);

namespace Drupal\omnipedia_user\Event\Omnipedia;

use Drupal\omnipedia_user\Event\Omnipedia\AbstractUserElevatedRolesChangedEvent;

/**
 * A user account has had a change in their elevated roles event.
 *
 * @see \Drupal\omnipedia_user\Event\Omnipedia\UserElevatedRolesEventInterface
 */
class UserElevatedRolesChangedEvent extends AbstractUserElevatedRolesChangedEvent {}
