<?php

namespace Drupal\search_form\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\BlockPluginInterface;
use Drupal\Core\Form\FormStateInterface;


/**
 * Provides a 'Search Form' Block.
 *
 * @Block(
 *   id = "search_form",
 *   admin_label = @Translation("Search_Form"),
 * )
 */
class SearchFormBlock extends BlockBase  implements BlockPluginInterface {

  /**
   * {@inheritdoc}
   */  
  public function build() {
    $config = $this->getConfiguration();

    $form = \Drupal::formBuilder()->getForm('Drupal\search_form\Form\SearchForm');
    return $form;

  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    $default_config = \Drupal::config('search_form.settings');
    return array(
      'name' => $default_config->get('search_form.name'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form = parent::blockForm($form, $form_state);

    $config = $this->getConfiguration();

    $form['search_form'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Who'),
      '#description' => $this->t('Who do you want to say hello to?'),
      '#default_value' => isset($config['name']) ? $config['name'] : '',
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['name'] = $form_state->getValue('search_form');
  }

}

?>