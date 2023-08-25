<?php

declare(strict_types=1);

namespace Drupal\omnipedia_user\Service;

use Drupal\Core\Cache\Cache;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\Core\Session\PermissionsHashGeneratorInterface;
use Drupal\omnipedia_user\Service\PermissionHashesInterface;
use Drupal\user\UserInterface;

/**
 * The Omnipedia user permission hashes service.
 */
class PermissionHashes implements PermissionHashesInterface {

  /**
   * The cache bin name where user permission hashes are stored.
   *
   * @var string
   */
  protected const CACHE_BIN = 'omnipedia_user_permission_hashes';

  /**
   * Service constructor; saves dependencies.
   *
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache
   *   The cache bin where user permission hashes are stored.
   *
   * @param \Drupal\Core\Session\AccountProxyInterface $currentUser
   *   The current user proxy service.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *   The Drupal entity type manager.
   *
   * @param \Drupal\Core\Session\PermissionsHashGeneratorInterface $permissionsHashGenerator
   *   The Drupal user permissions hash generator.
   */
  public function __construct(
    protected readonly CacheBackendInterface              $cache,
    protected readonly AccountProxyInterface              $currentUser,
    protected readonly EntityTypeManagerInterface         $entityTypeManager,
    protected readonly PermissionsHashGeneratorInterface  $permissionsHashGenerator,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function getPermissionHash(?UserInterface $user = null): string {

    if (!\is_object($user)) {
      $user = $this->currentUser;
    }

    return $this->permissionsHashGenerator->generate($user);

  }

  /**
   * {@inheritdoc}
   *
   * @see \Drupal\Core\Cache\Context\AccountPermissionsCacheContext::getContext()
   *   We generate the permission hash in the exact same way as the
   *   'user.permissions' cache context.
   *
   * @todo Determine how well this scales, and if starts to have a noticeable
   *   performance impact, implement a system that only generates this when a
   *   user is added/edited/deleted, one user at a time.
   */
  public function getPermissionHashes(): array {

    /** @var object|null */
    $cached = $this->cache->get(self::CACHE_BIN);

    if (\is_object($cached)) {
      return $cached->data;
    }

    /** @var \Drupal\user\UserInterface[] */
    $allUsers = $this->entityTypeManager->getStorage('user')->loadMultiple();

    /** @var string[] */
    $permissionHashes = [];

    foreach ($allUsers as $user) {
      $permissionHashes[
        \implode(',', $user->getRoles())
      ] = $this->permissionsHashGenerator->generate($user);
    }

    // Remove all duplicate hash values.
    $permissionHashes = \array_unique($permissionHashes);

    $this->cache->set(
      self::CACHE_BIN, $permissionHashes,
      Cache::PERMANENT,
      Cache::mergeTags(
        // Invalidated whenever any role is added/updated/deleted.
        $this->entityTypeManager->getStorage('user_role')->getEntityType()
          ->getListCacheTags(),
        // Invalidated whenever any user is added/updated/deleted.
        $this->entityTypeManager->getStorage('user')->getEntityType()
          ->getListCacheTags()
      ),
    );

    return $permissionHashes;

  }

}
