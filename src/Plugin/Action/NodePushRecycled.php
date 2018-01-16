<?php


namespace Drupal\recyclebin\Plugin\Action;

use Drupal\Core\Action\ActionBase;
use Drupal\Core\Session\AccountInterface;

/**
 * Push node to Recycle Bin.
 *
 * @Action(
 *   id = "node_set_recycled",
 *   label = @Translation("Push node to Recycle Bin"),
 *   type = "node"
 * )
 */
class NodePushRecycled extends ActionBase {

    /**
     * The current user.
     *
     * @var \Drupal\Core\Session\AccountInterface
     */
    protected $currentUser;


    /**
     * Constructor.
     *
     * @param array $configuration
     *   A configuration array containing information about the plugin instance.
     * @param string $plugin_id
     *   The plugin ID for the plugin instance.
     * @param mixed $plugin_definition
     *   The plugin implementation definition.
     * @param \Drupal\Core\Session\AccountInterface $current_user
     *   Current user.
     */
    public function __construct(array $configuration, $plugin_id, $plugin_definition, AccountInterface $current_user) {
        $this->currentUser = $current_user;
        parent::__construct($configuration, $plugin_id, $plugin_definition);
    }

    /**
     * {@inheritdoc}
     */
    public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
        return new static(
            $configuration,
            $plugin_id,
            $plugin_definition,
            $container->get('current_user')
        );
    }

    /**
     * {@inheritdoc}
     */
    public function executeMultiple(array $entities) {
        /** @var \Drupal\node\NodeInterface $node */
        foreach ($entities as $node) {
            if ($node->hasField('field_recycle')) {
                $node->field_recycle->value = 'recycled';
                $node->save();
            }
        }

    }

    /**
     * {@inheritdoc}
     */
    public function execute($object = NULL) {
        $this->executeMultiple([$object]);
    }

    /**
     * {@inheritdoc}
     */
    public function access($object, AccountInterface $account = NULL, $return_as_object = FALSE) {
        /** @var \Drupal\node\NodeInterface $object */
        return $object->access('use recycled transition', $account, $return_as_object);
    }
}