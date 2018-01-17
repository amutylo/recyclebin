<?php

namespace Drupal\recyclebin\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\RedirectResponse;

class RecyclebinController extends ControllerBase {

  /**
  *  Purge nodes in Recycle Bin
  */
  public function purge() {
    $query = \Drupal::entityQuery('node')
      ->condition('field_recycle', 'recycled');
    $nids = $query->execute();
    $storage_handler = \Drupal::entityTypeManager()->getStorage("node");
    $entities = $storage_handler->loadMultiple($nids);
    $storage_handler->delete($entities);
    drupal_set_message('Recycle Bin was purged.');
    return new RedirectResponse(Url::fromUserInput('/recycle-bin')->toString());
  }
}