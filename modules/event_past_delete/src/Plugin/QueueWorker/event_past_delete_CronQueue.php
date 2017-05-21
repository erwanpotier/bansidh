<?php

namespace Drupal\event_past_delete\Plugin\QueueWorker;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Queue\QueueWorkerBase;
use Drupal\node\NodeInterface;
use Drupal\Core\Datetime\DrupalDateTime;
/**
 * Executes interface translation queue tasks.
 *
 * @QueueWorker(
 *   id = "event_past_delete_CronQueue",
 *   title = @Translation("Delete older event"),
 *   cron = {"time" = 180}
 * )
 */
class event_past_delete_CronQueue extends QueueWorkerBase {

  /**
   * {@inheritdoc}
   */
  public function processItem($data) {
    $date = new DrupalDateTime();
    $dateString = $date->format('Y-m-d H:i:s');

    $query = \Drupal::entityQuery('node')->condition('type', 'event')->condition('field_date_de_fin', $dateString, '<='); // Can change -2 week to -2 year or -3 day
    $nids = array();
    $nids = $query->execute();

    foreach($nids as $item) {
      $event = node_load($item);
      $event->delete();
    }

  }

}