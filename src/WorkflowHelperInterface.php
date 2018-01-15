<?php

namespace Drupal\recyclebin;

use Drupal\state_machine\Plugin\Workflow\WorkflowInterface;
use Drupal\Core\Entity\FieldableEntityInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Interface for services that provide workflow related helper methods.
 */
interface WorkflowHelperInterface {

    /**
     * Returns the available transition states of the entity for the current user.
     *
     * @param \Drupal\Core\Entity\FieldableEntityInterface $entity
     *   The entity.
     * @param \Drupal\Core\Session\AccountInterface|null $user
     *   The account interface object, can be empty.
     *
     * @return array
     *   An array of transition state titles.
     */
    public function getAvailableStates(FieldableEntityInterface $entity, AccountInterface $user = NULL);

    /**
     * Returns the available transitions of the entity for the current user.
     *
     * @param \Drupal\Core\Entity\FieldableEntityInterface $entity
     *   The entity.
     * @param \Drupal\Core\Session\AccountInterface|null $user
     *   The account interface object, can be empty.
     *
     * @return array
     *   An array of transition titles.
     */
    public function getAvailableTransitions(FieldableEntityInterface $entity, AccountInterface $user);

    /**
     * Returns the state field definitions of an entity.
     *
     * @param \Drupal\Core\Entity\FieldableEntityInterface $entity
     *   The entity.
     *
     * @return \Drupal\Core\Field\FieldDefinitionInterface[]
     *   The array of state field definitions.
     */
    public static function getEntityStateFieldDefinitions(FieldableEntityInterface $entity);

    /**
     * Returns the StateItem field of the entity.
     *
     * Every entity with a state should have only one state field
     * so the method returns the available field definitions.
     *
     * @param \Drupal\Core\Entity\FieldableEntityInterface $entity
     *   The entity.
     *
     * @return \Drupal\state_machine\Plugin\Field\FieldType\StateItemInterface
     *   The state field.
     *
     * @throws \Exception
     *   Thrown when the entity does not have a state field.
     */
    public function getEntityStateField(FieldableEntityInterface $entity);

    /**
     * Returns TRUE if entity has a state field and supports workflow.
     *
     * @param \Drupal\Core\Entity\FieldableEntityInterface $entity
     *   The entity.
     *
     * @return bool
     *   TRUE if the entity has a state field, FALSE otherwise.
     */
    public function hasEntityStateField(FieldableEntityInterface $entity);

    /**
     * Checks if a state is set to published in the workflow.
     *
     * @param string $state_id
     *   state ID.
     * @param \Drupal\state_machine\Plugin\Workflow\WorkflowInterface $workflow
     *   Workflow of the state.
     *
     * @return bool
     *   TRUE if the state is set to published, FALSE otherwise.
     *
     * @throwns \InvalidArgumentException
     *   Thrown when the workflow is not plugin based, because this is required to
     *   retrieve the publication state from the workflow states.
     */
    public function isWorkflowStatePublished($state_id, WorkflowInterface $workflow);
    /**
     * Checks if a state is set as recycled in the workflow.
     *
     * @param string $state_id
     *   state ID.
     * @param \Drupal\state_machine\Plugin\Workflow\WorkflowInterface $workflow
     *   Workflow of the state.
     *
     * @return bool
     *   TRUE if the state is set to recycled, FALSE otherwise.
     *
     * @throwns \InvalidArgumentException
     *   Thrown when the workflow is not plugin based, because this is required to
     *   retrieve the recycled state from the workflow states.
     */
    public function isWorkflowStateRecycled($state_id, WorkflowInterface $workflow);
}