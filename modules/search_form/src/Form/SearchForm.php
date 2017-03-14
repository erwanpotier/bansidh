<?php

/**
 * @file
 * Contains \Drupal\src\Form\SearchForm.
 */

namespace Drupal\search_form\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\NodeType;
use Drupal\Core\Url;
use Drupal\Core\Routing;
use Drupal\Core\Routing\TrustedRedirectResponse;

class SearchForm extends FormBase {
  
  /**
   * {@inheritdoc}.
   */
  public function getFormId() {
    return 'search_form';
  }
  

  /**
   * {@inheritdoc}.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

      $form['#theme'] = 'search_form';
      $form['search_key_word'] = array(
        '#type' => 'search',
        '#title' => $this->t('Mots clés:'),
        '#default_value' => Null,
      );

    $vocabulary_region = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree('region', $parent = 0, $max_depth = NULL, $load_entities = FALSE);
    $list = array();
    foreach ($vocabulary_region as $taxonomy){
           $list[$taxonomy->tid] = $taxonomy->name;
    }
      $form['regions'] = array(
        '#type' => 'select',
        '#title' => t('Régions'),
        '#default_value' => NULL,
        '#options' => $list,
      );

    $vocabulary_categorie = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree('categorie', $parent = 0, $max_depth = NULL, $load_entities = FALSE);
    $list_categorie = array();
    foreach ($vocabulary_categorie as $taxonomy){
           $list_categorie[$taxonomy->tid] = $taxonomy->name;
    }
        $form['categories'] = array(
        '#type' => 'select',
        '#title' => t('Catégories'),
        '#default_value' => NULL,
        '#options' => $list_categorie,
      );
      $form['search_date'] = array(
        '#type' => 'date',
        '#title' => $this->t('A partir de:'),
        '#default_value' => date("Y-m-d"),
      );
      $form['submit'] = array(
      '#type' => 'submit',
      '#value' => 'Rechercher',
      '#button_type' => 'primary',
      );

      return $form;

  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {

  if ($form_state->hasValue('search_date')) {
     $check_date = $form_state->getValue('search_date');
     if ($check_date < date("Y-m-d")) {
        $form_state->setErrorByName('date', t('Veuillez entrer une date valide.'));
     }
  }

  }
  
  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state)  {

    $options = array('absolute' => TRUE);

    $search_key_word = $form_state->getValue('search_key_word');
    if ($search_key_word) {
      $options['query']['search_key_word'] = $search_key_word;
    }
    $regions = $form_state->getValue('regions');
    if ($regions) {
      $options['query']['regions'] = $regions;
    }
    $categories = $form_state->getValue('categories');
    if ($categories) {
      $options['query']['categories'] = $categories;
    }
    $search_date = $form_state->getValue('search_date');
    if ($search_date) {
      $options['query']['search_date'] = $search_date;
    }

    $form_state->setRedirect('entity.taxonomy_term.canonical',array('taxonomy_term' => $regions), $options);

  }
  
}

?>