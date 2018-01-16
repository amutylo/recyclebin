<?php

namespace Drupal\recyclebin\EventSubscriber;

use Drupal\recyclebin\WorkflowHelperInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\state_machine\Event\WorkflowTransitionEvent;
use Drupal\state_machine\Plugin\Workflow\WorkflowInterface;
use Drupal\state_machine\Plugin\Workflow\WorkflowState;
use Drupal\Core\Entity\EntityPublishedInterface;

/**
 * Event subscriber to handle revisions on workflow-enabled entities.
 */
class WorkflowTransitionSubscriber implements EventSubscriberInterface {

    /**
     * The helper.
     *
     * @var \Drupal\recyclebin\WorkflowHelperInterface
     */
    protected $workflowHelper;

    /**
     * Constructor WorkflowTransitionEventSubscriber.
     *
     * @param \Drupal\recyclebin\WorkflowHelperInterface $workflowHelper
     *   The helper.
     */
    public function __construct(WorkflowHelperInterface $workflowHelper) {
        $this->workflowHelper = $workflowHelper;
    }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    //use generic event name;
    return [
      'recycle.recycled.pre_transition' => 'handleAction',
    ];
  }

    /**
     * Handle action callback from workflow.
     * @param WorkflowTransitionEvent $event
     * @throws \Exception
     */
    public function handleAction(WorkflowTransitionEvent $event) {
        $entity = $event->getEntity();

        // Check the new state is the published one.
        $is_published_state = $this->isPublishedState($event->getToState(), $event->getWorkflow());

        $fields = $this->workflowHelper->getEntityStateField($entity);

        if ($entity instanceof EntityPublishedInterface) {
            if ($is_published_state) {
                $entity->setPublished();
            }
            else {
                $entity->setUnpublished();
            }

        }
    }

    /**
     * Checks if a state is set as published in a certain workflow.
     *
     * @param \Drupal\state_machine\Plugin\Workflow\WorkflowState $state
     *   The state to check.
     * @param \Drupal\state_machine\Plugin\Workflow\WorkflowInterface $workflow
     *   The workflow the state belongs to.
     *
     * @return bool
     *   TRUE if the state is set as published in the workflow, FALSE otherwise.
     */
    protected function isPublishedState(WorkflowState $state, WorkflowInterface $workflow) {
        return $this->workflowHelper->isWorkflowStatePublished($state->getId(), $workflow);
    }
}