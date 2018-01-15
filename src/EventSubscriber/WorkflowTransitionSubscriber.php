<?php

namespace Drupal\recyclebin\WorkflowTransitionSubscriber;

use Drupal\recyclebin\WorkflowHelperInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
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
//        return [
//            'state_machine.pre_transition' => 'handleAction',
//        ];
    }
    
}