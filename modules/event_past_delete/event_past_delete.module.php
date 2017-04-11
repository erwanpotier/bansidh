<?php

function event_past_delete_cron() {

  $query = \Drupal::entityQuery('event')
    ->condition('field_date_de_fin', strtotime('-1 hours'), '<='); // Can change -2 week to -2 year or -3 day
  $nids = $query->execute();
  foreach ($nids as $nid) {
    $event = node_load($nid);
    $event->delete();
  }
}

?>