<?php

namespace Drupal\omnipedia_user\Plugin\Condition;

use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\omnipedia_user\Service\UserRolesInterface;
use Drupal\rules\Core\RulesConditionBase;
use Drupal\rules\Exception\InvalidArgumentException;
use Drupal\user\UserInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'User has elevated roles(s)' condition.
 *
 * @Condition(
 *   id       = "omnipedia_user_has_elevated_role",
 *   label    = @Translation("User has elevated role(s)"),
 *   category = @Translation("User"),
 *   context_definitions = {
 *     "user" = @ContextDefinition("entity:user",
 *       label        = @Translation("User"),
 *       description  = @Translation("Specifies the user account to check."),
 *     ),
 *   }
 * )
 */
class UserHasElevatedRole extends RulesConditionBase implements ContainerFactoryPluginInterface {

  /**
   * The Omnipedia user roles service interface.
   *
   * @var \Drupal\omnipedia_user\Service\UserRolesInterface
   */
  protected $userRoles;

  /**
   * {@inheritdoc}
   *
   * @param \Drupal\omnipedia_user\Service\UserRolesInterface $userRoles
   *   The Omnipedia user roles service interface.
   */
  public function __construct(
    array $configuration, $pluginId, $pluginDefinition,
    UserRolesInterface $userRoles
  ) {

    parent::__construct($configuration, $pluginId, $pluginDefinition);

    $this->userRoles = $userRoles;

  }

  /**
   * {@inheritdoc}
   */
  public static function create(
    ContainerInterface $container,
    array $configuration, $pluginId, $pluginDefinition
  ) {
    return new static(
      $configuration,
      $pluginId,
      $pluginDefinition,
      $container->get('omnipedia.user_roles')
    );
  }

  /**
   * Evaluate if user has one or more elevated role(s).
   *
   * @param \Drupal\user\UserInterface $user
   *   The user account to check.
   *
   * @return bool
   *   True if the user has one or more elevated role(s).
   */
  protected function doEvaluate(UserInterface $user) {

    return $this->userRoles->userHasElevatedRole($user);

  }

}
