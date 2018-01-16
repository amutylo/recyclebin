<?php

/**
 * @file
 * Contains \Drupal\recycledbin\Guard\RecycledGuard.
 */

namespace Drupal\recyclebin\Guard;

use Drupal\state_machine\Guard\GuardInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\state_machine\WorkflowManagerInterface;
use Drupal\state_machine\Plugin\Workflow\WorkflowTransition;
use Drupal\state_machine\Plugin\Workflow\WorkflowInterface;
use Drupal\Core\Entity\EntityInterface;


class RecycleGuard implements GuardInterface {

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $currentUser;

  /**
   * The workflow manager.
   *
   * @var \Drupal\state_machine\WorkflowManagerInterface
   */
  protected $workflowManager;

  /**
   * RecycleGuard constructor.
   *
   * @param \Drupal\Core\Session\AccountProxyInterface $current_user
   * The current user
   *
   * @param \Drupal\state_machine\WorkflowManagerInterface $workflow_manager
   * THe workflow manager
   *
   */
  public function __construct(AccountProxyInterface $current_user, WorkflowManagerInterface $workflow_manager) {
    $this->currentUser = $current_user;
    $this->workflowManager = $workflow_manager;
  }

  /**
   * {@inheritdoc}
   */
  public function allowed(WorkflowTransition $transition, WorkflowInterface $workflow, EntityInterface $entity) {
    // Users without permissions won't be allowed to act.
    $transition_id = $transition->getId();
    $workflow_id = $workflow->getId();
    if (!$this->currentUser->hasPermission('use ' . $transition_id . ' transition in ' . $workflow_id)) {
      return FALSE;
    }
  }

}