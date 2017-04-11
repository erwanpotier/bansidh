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
      '#default_value' => Null,
      '#placeholder' => "Mots clés?",
      '#size' => 30,
    );
    $vocabulary_region = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree('region', $parent = 0, $max_depth = NULL, $load_entities = FALSE);
    $list = array();
    foreach ($vocabulary_region as $taxonomy){
           $list[$taxonomy->tid] = $taxonomy->name;
    }
    $form['regions'] = array(
      '#type' => 'select',
      '#default_value' => NULL,
      '#options' => $list,
      '#empty_option' => "- Régions -",
    );

    $vocabulary_categorie = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree('categorie', $parent = 0, $max_depth = NULL, $load_entities = FALSE);
    $list_categorie = array();
    foreach ($vocabulary_categorie as $taxonomy){
      foreach ($taxonomy->parents as $term_parents){
        if ($taxonomy->depth == 0){
          $parent_name_t1 = $taxonomy->name;
       }
       else{
         if ($taxonomy->depth == 1){
          $parent_name_t2 = $taxonomy->name;
          if ($taxonomy->tid != 41) {/* Sports Collectifs*/
            $list_categorie[$parent_name_t1][$taxonomy->tid] = $taxonomy->name;
          }
         }
         else {
          $list_categorie[$parent_name_t1][$parent_name_t2][$taxonomy->tid] = $taxonomy->name;
         }
    }
      }
    }
    $form['categories'] = array(
      '#type' => 'select',
      '#default_value' => NULL,
      '#options' => $list_categorie,
      '#title' => $this->t('Categories'),
      '#empty_option' => "- Catégories -",
    );
    $form['search_date'] = array(
      '#type' => 'date',
      '#title' => $this->t('A partir du'),
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

  if (($form_state->isValueEmpty('regions'))  && ($form_state->isValueEmpty('categories'))) {
        $form_state->setErrorByName('regions', t('Vous devez remplir un champs parmi categories et regions.'));
      }

  }
  
  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state)  {

    $options = array('absolute' => TRUE);

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
    $search_key_word = $form_state->getValue('search_key_word');
    if ($search_key_word) {
      $options['query']['search_key_word'] = $search_key_word;
    }
  if ($form_state->hasValue('regions')){
    $form_state->setRedirect('entity.taxonomy_term.canonical',array('taxonomy_term' => $regions), $options);
  }
  else if($form_state->hasValue('categories')){
    kint($categories);
    $form_state->setRedirect('entity.taxonomy_term.canonical',array('taxonomy_term' => $categories), $options);
  }

  }
  
}

?>