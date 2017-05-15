<?php

namespace Drupal\event_promotion\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\BlockPluginInterface;
use Drupal\Core\Form\FormStateInterface;


/**
 * Provides a 'Event Promotion' Block.
 *
 * @Block(
 *   id = "event_promotion",
 *   admin_label = @Translation("Event_Prmotion"),
 * )
 */
class EventPromotionBlock extends BlockBase  implements BlockPluginInterface {

  /**
   * {@inheritdoc}
   */  
  public function build() {
    $config = $this->getConfiguration();

    $form = \Drupal::formBuilder()->getForm('Drupal\event_promotion\Form\EventPromotion');
    return $form;

  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    $default_config = \Drupal::config('event_promotion.settings');
    return array(
      'name' => $default_config->get('event_promotion.name'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form = parent::blockForm($form, $form_state);

    $config = $this->getConfiguration();

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['name'] = $form_state->getValue('event_promotion');
  }

}

?>