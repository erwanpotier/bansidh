<?php

namespace Drupal\event_past_delete\Plugin\QueueWorker;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Queue\QueueWorkerBase;
use Drupal\node\NodeInterface;

/**
 * Executes interface translation queue tasks.
 *
 * @QueueWorker(
 *   id = "event_past_delete_FileCronQueue",
 *   title = @Translation("Delete files previously attached to older event"),
 *   cron = {"time" = 180}
 * )
 */
class event_past_delete_FileCronQueue extends QueueWorkerBase {

  /**
   * {@inheritdoc}
   */
  public function processItem($data) {

    //db_query to find all files not attached to a node:
    $result = db_query("SELECT fm.*
    FROM {file_managed} AS fm
    LEFT OUTER JOIN {file_usage} AS fu ON ( fm.fid = fu.fid )
    LEFT OUTER JOIN {node} AS n ON ( fu.id = n.nid )
    WHERE (fu.type = 'node' OR fu.type IS NULL) AND n.nid IS NULL
    ORDER BY `fm`.`fid`  DESC");

    //Delete file & database entry
    foreach ($result as $delta => $record) {
         file_delete($record->fid);
    }


  }

}