<?php

namespace Drupal\recyclebin;

use Drupal\Component\Plugin\PluginInspectionInterface;
use Drupal\Core\Entity\FieldableEntityInterface;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\state_machine\Plugin\Workflow\WorkflowInterface;
use Drupal\state_machine\Plugin\Workflow\WorkflowTransition;

/**
 * Contains methods to get workflow related data from the entity.
 */
class WorkflowHelper implements WorkflowHelperInterface {

    /**
     * The current user proxy.
     *
     * @var \Drupal\Core\Session\AccountProxyInterface
     */
    protected $currentUser;

    /**
     * Constructor of a WorkflowHelper.
     *
     * @param \Drupal\Core\Session\AccountProxyInterface $currentUser
     *   The service that holds the current user.
     */
    public function __construct(AccountProxyInterface $currentUser) {
        $this->currentUser = $currentUser;
    }

    /**
     * {@inheritdoc}
     */
    public function getAvailableStates(FieldableEntityInterface $entity, AccountInterface $user = NULL) {
        $field = $this->getEntityStateField($entity);
        $allowed_transitions = $field->getTransitions();
        $allowed_states = array_map(function (WorkflowTransition $transition) {
            return (string) $transition->getToState()->getLabel();
        }, $allowed_transitions);
        return $allowed_states;
    }

    /**
     * {@inheritdoc}
     */
    public function getAvailableTransitions(FieldableEntityInterface $entity, AccountInterface $user) {
        $field = $this->getEntityStateField($entity);
        return array_map(function (WorkflowTransition $transition) {
            return (string) $transition->getLabel();
        }, $field->getTransitions());
    }

    /**
     * {@inheritdoc}
     */
    public static function getEntityStateFieldDefinitions(FieldableEntityInterface $entity) {
        return array_filter($entity->getFieldDefinitions(), function (FieldDefinitionInterface $field_definition) {
            return $field_definition->getType() == 'state';
        });
    }

    /**
     * {@inheritdoc}
     */
    public static function getEntityStateFieldDefinition(FieldableEntityInterface $entity) {
        if ($field_definitions = static::getEntityStateFieldDefinitions($entity)) {
            return reset($field_definitions);
        }
        return NULL;
    }
    /**
     * {@inheritdoc}
     */
    public function getEntityStateField(FieldableEntityInterface $entity) {
        $field_definition = $this->getEntityStateFieldDefinition($entity);
        if ($field_definition == NULL) {
            throw new \Exception('Can\'t find state fields in the entity.');
        }
        return $entity->{$field_definition->getName()}->first();
    }
    /**
     * {@inheritdoc}
     */
    public function hasEntityStateField(FieldableEntityInterface $entity) {
        return (bool) static::getEntityStateFieldDefinitions($entity);
    }
    /**
     * {@inheritdoc}
     */
    public function isWorkflowStatePublished($state_id, WorkflowInterface $workflow) {
        if (!$workflow instanceof PluginInspectionInterface) {
            $label = $workflow->getLabel();
            throw new \InvalidArgumentException("The '" . $label . "' workflow is not plugin based.");
        }
        $raw_workflow_definition = $workflow->getPluginDefinition();
        return !empty($raw_workflow_definition['states'][$state_id]['published']);
    }
    /**
     * {@inheritdoc}
     */
    public function isWorkflowStateRecycled($state_id, WorkflowInterface $workflow) {
        if (!$workflow instanceof PluginInspectionInterface) {
            $label = $workflow->getLabel();
            throw new \InvalidArgumentException("The '" . $label ."' workflow is not plugin based.");
        }

        $raw_workflow_definition = $workflow->getPluginDefinition();
        return !empty($raw_workflow_definition['states'][$state_id]['recycled']);
    }
}