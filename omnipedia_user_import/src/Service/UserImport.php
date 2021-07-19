<?php

namespace Drupal\omnipedia_user_import\Service;

use Drupal\bulk_user_registration\BulkUserRegistrationInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\user\UserInterface;

/**
 * The Omnipedia user import service.
 */
class UserImport {

  /**
   * The CSV field name that defines action machine names.
   *
   * Note that multiple actions can be applied by concatenating them with a '+'
   * symbol, and will be applied in the order they're found in.
   *
   * For example: 'action1+action2' will execute 'action1' and then 'action2'
   * for the given user account.
   */
  protected const ACTIONS_FIELD_NAME = 'actions';

  /**
   * The CSV actions field separator character for multiple actions.
   */
  protected const ACTIONS_FIELD_SEPARATOR = '+';

  /**
   * The Drupal action entity storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $actionStorage;

  /**
   * The Drupal user entity storage.
   *
   * @var \Drupal\user\UserStorageInterface
   */
  protected $userStorage;

  /**
   * Service constructor; saves dependencies.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *   The Drupal entity type manager.
   */
  public function __construct(EntityTypeManagerInterface $entityTypeManager) {
    $this->actionStorage  = $entityTypeManager->getStorage('action');
    $this->userStorage    = $entityTypeManager->getStorage('user');
  }

  /**
   * Get the actions CSV field name.
   *
   * @return string
   */
  public function getActionsFieldName(): string {
    return self::ACTIONS_FIELD_NAME;
  }

  /**
   * Attempt to load a user with the provided user name.
   *
   * @param string $name
   *   The user name to search for.
   *
   * @return \Drupal\user\UserInterface|null
   *   An existing user object with the provided user name or null if one
   *   doesn't exist.
   *
   * @see \user_load_by_name()
   *   Same as this core function but with dependency injection.
   */
  protected function loadUserByName(string $name): ?UserInterface {

    /** @var \Drupal\user\UserInterface[] */
    $users = $this->userStorage->loadByProperties(['name' => $name]);

    if (count($users) === 0) {
      return null;
    }

    return reset($users);

  }

  /**
   * Ensure a given user name does not already exist.
   *
   * This ensures that we don't try to save a user with a user name that already
   * exists, as this would cause an SQL error as that column requires unique
   * values. A counter is used to try appending numbers from 0 to 999 to the new
   * user's name, until we find one that doesn't already exist.
   *
   * @param string $name
   *   The proposed user name.
   *
   * @return string
   *   A unique user name.
   */
  protected function ensureUniqueUserName(string $name): string {

    // Try to load a user with the intended name to check if it already exists.
    /** @var \Drupal\user\UserInterface|null */
    $existingUser = $this->loadUserByName($name);

    // If the user name is not in use, return here and allow the user to be saved.
    if (!\is_object($existingUser)) {
      return $name;
    }

    for ($i = 0; $i < 1000; $i++) {

      /** @var \Drupal\user\UserInterface|null */
      $existingUser = $this->loadUserByName($name . $i);

      // Keep skipping to the next iteration if a user exists with the proposed
      // name, until one does or we run out of $i.
      if (\is_object($existingUser)) {
        continue;
      }

      return $name . $i;

    }

    return $name;

  }

  /**
   * Apply any provided actions to a user entity after it has been saved.
   *
   * @param \Drupal\user\UserInterface $user
   *   The user object.
   *
   * @param array $data
   *   The raw CSV data for the user.
   */
  protected function applyUserActions(UserInterface $user, array $data): void {

    // Bail if no actions were provided.
    if (empty($data[$this->getActionsFieldName()])) {
      return;
    }

    /** @var \Drupal\system\ActionConfigEntityInterface[] */
    $actions = [];

    // \explode() will always return an array containing at least one string,
    // given a non-empty separator and no limit, so we can safely do this
    // without additional checks.
    foreach (\explode(
      self::ACTIONS_FIELD_SEPARATOR, $data[$this->getActionsFieldName()]
    ) as $actionName) {

      // Skip empty strings; this can happen if the first or last character was
      // the separator character.
      if (empty($actionName)) {
        continue;
      }

      /** @var \Drupal\system\ActionConfigEntityInterface|null */
      $actionEntity = $this->actionStorage->load($actionName);

      // Skip if we can't find an action entity with the given machine name.
      if (!\is_object($actionEntity)) {
        continue;
      }

      $actions[$actionName] = $actionEntity;

    }

    // Finally, execute all the actions that could be loaded.
    foreach ($actions as $action) {
      $action->execute([$user]);
    }

  }

  /**
   * \hook_bulk_user_registration_user_presave() callback.
   *
   * @param \Drupal\user\UserInterface $user
   *   The user object.
   *
   * @param array $data
   *   The raw CSV data for the user.
   */
  public function bulkUserRegistrationPreSave(
    UserInterface $user, array $data
  ): void {

    $user->setUsername($this->ensureUniqueUserName(
      $data[BulkUserRegistrationInterface::FIELD_USER_NAME]
    ));

  }

  /**
   * \hook_bulk_user_registration_user_postsave() callback.
   *
   * @param \Drupal\user\UserInterface $user
   *   The user object.
   *
   * @param array $data
   *   The raw CSV data for the user.
   */
  public function bulkUserRegistrationPostSave(
    UserInterface $user, array $data
  ): void {

    $this->applyUserActions($user, $data);

  }

}
