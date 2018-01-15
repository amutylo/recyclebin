<?php

namespace Drupal\recyclebin;

use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Session\AccountProxyInterface;

/**
 * Class RecycleUserProvider
 *
 * @package Drupal\recyclebin
 */
class RecycleUserProvider {

  /**
   * The user object.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  private $account;

  /**
   * RecycleUserProvider constructor.
   *
   * @param \Drupal\Core\Session\AccountProxyInterface $currentUser
   * The current user.
   */
  public function __construct(AccountProxyInterface $currentUser) {
    $this->account = $currentUser;
  }

  /**
   *
   * Return user.
   *
   * @return \Drupal\Core\Session\AccountInterface|\Drupal\Core\Session\AccountProxyInterface
   */
  public function getUser() {
    return $this->account;
  }

  /**
   * Set user.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   */
  public function setUser(AccountInterface $account) {
    $this->account = $account;
  }
}