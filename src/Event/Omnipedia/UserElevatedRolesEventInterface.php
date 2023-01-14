<?php

declare(strict_types=1);

namespace Drupal\omnipedia_user\Event\Omnipedia;

/**
 * Interface defining events for users with one or more elevated roles.
 */
interface UserElevatedRolesEventInterface {

  /**
   * A user account with one or more elevated roles is blocked.
   *
   * @Event
   *
   * @var string
   */
  public const BLOCKED = 'omnipedia.user_elevated_role_blocked';

  /**
   * A user account has had a change in their elevated roles.
   *
   * This event is triggered when a user has one or more elevated roles both
   * before the change and after.
   *
   * @Event
   *
   * @var string
   */
  public const CHANGED = 'omnipedia.user_elevated_role_changed';

  /**
   * A user is created with one or more elevated roles.
   *
   * @Event
   *
   * @var string
   */
  public const CREATED = 'omnipedia.user_elevated_role_created';

  /**
   * A user account with one or more elevated roles is deleted.
   *
   * @Event
   *
   * @var string
   */
  public const DELETED = 'omnipedia.user_elevated_role_deleted';

  /**
   * A user has all elevated roles removed.
   *
   * @Event
   *
   * @var string
   */
  public const DENIED = 'omnipedia.user_elevated_role_denied';

  /**
   * A user without elevated roles is granted one or more elevated roles.
   *
   * @Event
   *
   * @var string
   */
  public const GRANTED = 'omnipedia.user_elevated_role_granted';

  /**
   * A user with one or more elevated roles has logged in.
   *
   * @Event
   *
   * @var string
   */
  public const LOGGED_IN = 'omnipedia.user_elevated_role_logged_in';

  /**
   * A user account with one or more elevated roles is unblocked.
   *
   * @Event
   *
   * @var string
   */
  public const UNBLOCKED = 'omnipedia.user_elevated_role_unblocked';

}
