<?php

namespace Drupal\search_form\Controller;

use Drupal\Core\Controller\ControllerBase;

class SearchFormController extends ControllerBase {

  /**
   * Display the markup.
   *
   * @return array
   */
  public function content() {
    return array(
      '#type' => 'markup',
      '#markup' => $this->t('SEARCH FORM'),
      '#theme' => 'search_form',
    );
  }

}
?>