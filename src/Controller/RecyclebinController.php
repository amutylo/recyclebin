<?php

namespace Drupal\recyclebin\Controller;

use Drupal\Core\Controller\ControllerBase;

class RecyclebinController extends ControllerBase {

  /**
  *  Purge nodes in Recycle Bin
  */
  public function purge() {
    $query = \Drupal::entityQuery('node')
      ->condition('field_recycle', 'normal');
    $nids = $query->execute();

  }
}