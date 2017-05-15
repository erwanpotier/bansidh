<?php 
namespace Drupal\event_promotion\Form; 

use Drupal\Core\Form\FormBase; 
use Drupal\Core\Form\FormStateInterface; 
use Drupal\Core\Ajax\AjaxResponse; 
use Drupal\Core\Ajax\OpenModalDialogCommand; 
use Drupal\Core\Ajax\ReplaceCommand;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Render\Element\Ajax;
use Drupal\Core\Render\Element;

/** 
* EventPromotion class. 
*/

class ModalEventPromotion extends FormBase { 

 /** 
 * {@inheritdoc} 
 */
  public function buildForm(array $form, FormStateInterface $form_state) { //, AccountInterface $user = NULL
  // We will have many fields with the same name, so we need to be able to
  // access the form hierarchically.
  $form['#tree'] = TRUE;
        // Attach the library for pop-up dialogs/modals. 
    $form['#attached']['library'][] = 'core/drupal.ajax'; 


    $form['#prefix'] = '<div id="event_promotion" >'; //class="form-control"
    $form['#suffix'] = '</div>'; 

    $nids = \Drupal::entityQuery('node')
            ->condition('type', 'event')
            ->condition('uid', \Drupal::currentUser()->id(), '=') 
            ->execute();

    $user_event = \Drupal::entityTypeManager()->getStorage('node')->loadMultiple($nids);
    $list_user_event = array();
    foreach ($user_event as $delta => $item){
        $list_user_event[$delta] = $item->title->value;
    }
    //kint( $user_event);
    $form['user_event'] = array(
      '#type' => 'select',
      '#default_value' => NULL,
      '#options' => $list_user_event,
      '#empty_option' => "- Évènements -",
      '#attributes' => [ 'class' => [ 'form-control form-control-lg', ], ],
      '#title' => $this->t("Selectionner l'évènement à promouvoir"),
    );
//$user_event = 
    $term_region = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->load(58);
    $term_categorie = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->load($user_event->field_categorie->value);
    $region_term_registration = array();
    foreach ($term_region as $delta => $item){
        $region_term_registration[$delta] = $item->rng_registration_type->value;
    }


    # the options to display in our checkboxes
/*    $promotion = array(
      'promote_home' => t("accueil"),//Promotion sur la page d'
      'promote_region' => t('Promotion régionale'), 
      'promote_categorie' => t('Promotion dans la catégorie'),
      'haut_region' => t('Haut de liste régionale'), 
      'haut_categorie' => t('Haut de liste catégorie')
    );
    # the drupal checkboxes form field definition
    $form['user_event_promotion'] = array(
      '#title' => t('Promotions disponibles:'),
      '#type' => 'checkboxes',
      '#description' => t('Selectionner le type de promotion pour votre évènnement.'),
      '#options' => $promotion,
    );
*/

    $form['promote_home'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t("Promotion sur la page d'accueil"),
    );
    $form['rng_promote_home'] = array(
      '#title' => t("Selectionner la période pour laquelle vous voulez promouvoir votre évènement"),
      '#type' => 'date',
      '#date_format' => 'd/m/Y',
      '#attributes' => array('type'=> 'date', 'min'=> '0 days', 'max' => '+4 months'),
      '#states' => array(
        'visible' => array(
          ':input[name="promote_home"]' => array('checked' => TRUE),
        ),
      ),
    );
/* ************************************************************************** */
    $form['promote_region'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t("Promotion régionale"),
    );
    //$event_types = \Drupal::service('rng.event_manager')->eventTypeWithEntityType('taxonomy_term');
    //CourierContext::load('rng_registration_' . $entity_type);
    //$display_date = entity_get_form_display('taxonomy_term', 'region', 'rng_date');//rng_event
    //$display_register = entity_get_form_display('taxonomy_term', 'region', 'rng_register');//rng_event

/*
$node = \Drupal::entityTypeManager()
  ->getStorage('node')
  ->create($values);
*/
// $term_region->getFormObject()->getOperation();
  $title = $term_region->name->value;
  $form_tax = \Drupal::entityTypeManager()
      ->getFormObject('taxonomy_term', 'default')
      ->setEntity($term_region);

  //$form_display = \Drupal\Core\Entity\Entity\EntityFormDisplay::collectRenderDisplay($form_tax, 'rng_register');

//$form_obj = new Drupal\rng\Form\RegistrationForm;
  $renderable_form = \Drupal::formBuilder()->getForm($form_tax);//\Drupal::formBuilder()->getForm($form_tax);
  //$renderable_form =\Drupal::service('entity.form_builder')->getForm($term_region, 'rng_event');//taxonomy_term.rng_event
  //$renderable_form = \Drupal::formBuilder()->getForm('Drupal\rng\Form\RegistrationForm');
  // Remove embedded form specific data.
  unset($renderable_form['actions']);
  unset($renderable_form['form_build_id']);
  unset($renderable_form['form_token']);
  unset($renderable_form['form_id']);

  // Also remove all other properties that start with a '#'.
  foreach ($renderable_form as $key => $value) {
    if (strpos($key, '#') === 0) {
      unset ($renderable_form[$key]);
    }
  }

  // Create a container for the entity's fields.
  $form['rng_register_region'] = array(
    '#type' => 'details',
    '#title' => t('White label settings'),
    '#open' => TRUE,
    '#tree' => TRUE,
    '#states' => array(
      'visible' => array(
        ':input[name="promote_region"]' => array('checked' => TRUE),
      ),
    ),
  );
  $form['rng_register_region'] += $renderable_form;

  $form['actions']['submit']['#submit'][] = 'rng_register_region_form_submit';

    //$form['rng_register_region'] = \Drupal::formBuilder()->getForm($form_tax);
    //$form['rng_register_region'] = \Drupal::service('entity.form_builder')->getForm('taxonomy_term', 'rng_register');
    $form['rng_promote_region'] = array(
      '#title' => t("Selectionner la période pour laquelle vous voulez promouvoir votre évènement"),
      '#type' => 'date',
      '#states' => array(
        'visible' => array(
          ':input[name="promote_region"]' => array('checked' => TRUE),
        ),
      ),
    );
/* ************************************************************************** */
    $form['promote_categorie'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t("Promotion dans la catégorie"),
    );
    $form['rng_promote_categorie'] = array(
      '#title' => t("Selectionner la période pour laquelle vous voulez promouvoir votre évènement"),
      '#type' => 'date',
      '#states' => array(
        'visible' => array(
          ':input[name="promote_categorie"]' => array('checked' => TRUE),
        ),
      ),
    );
/* ************************************************************************** */
    $form['haut_region'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t("Haut de liste régionale"),
    );
    $form['rng_haut_region'] = array(
      '#title' => t("Selectionner la période pour laquelle vous voulez promouvoir votre évènement"),
      '#type' => 'date',
      '#states' => array(
        'visible' => array(
          ':input[name="haut_region"]' => array('checked' => TRUE),
        ),
      ),
    );
    /* ************************************************************************** */

    $form['haut_categorie'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t("Haut de liste catégorie"),
    );
    $form['rng_haut_categorie'] = array(
      '#title' => t("Selectionner la période pour laquelle vous voulez promouvoir votre évènement"),
      '#type' => 'date',
      '#states' => array(
        'visible' => array(
          ':input[name="haut_categorie"]' => array('checked' => TRUE),
        ),
      ),
    );

   /* 
    $user1 = \Drupal::currentUser()->id();
    $registration
      ->addIdentity($user1)
      ->save();
*/
    //$rng_event = Event::getEventBundle();
/*    $registrants = Registrant::loadMultiple();
 
    $list_registrants = array();
    foreach ($registrants as $delta => $item){
        $list_registrants[$delta] = $item->name;
    }
    $form['user_name'] = array(
      '#type' => 'select',
      '#default_value' => NULL,
      '#options' => $list_registrants,
      '#empty_option' => "- Utilisateur -",
      '#attributes' => [ 'class' => [ 'form-control form-control-lg', ], ],
      '#title' => $this->t("liste utilisateur"),
    );
*/

    //echo $user_event;
//$user_event->field_promoted_to_region,
    //$term_region = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->load($user_event->field_region->value);
    //$term_categorie = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->load($user_event->field_categorie->value);
/*    $vocabulary_region = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree('region', $parent = 0, $max_depth = NULL, $load_entities = FALSE);
    $list = array();
    $select_region
    foreach ($vocabulary_region as $taxonomy){
           $list[$taxonomy->tid] = $taxonomy->name;
           if ($user_event->field_region == $taxonomy->name){
              $select_region = $taxonomy;
           }
    }
    $form['user_event_promoted_region'] = array(
      '#type' => 'select',
      '#default_value' => NULL,//valeur de l'event selectionner
      '#options' => $list,
      '#empty_option' => "- Votre région -",
      '#title' => $this->t("Région de l'évènement à promouvoir"),
    );

    $vocabulary_categorie = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree('categorie', $parent = 0, $max_depth = NULL, $load_entities = FALSE);
    $list = array();
    foreach ($vocabulary_categorie as $taxonomy){
           $list[$taxonomy->tid] = $taxonomy->name;
    }
    $form['user_event_promoted_categorie'] = array(
      '#type' => 'select',
      '#default_value' => NULL,//valeur de l'event selectionner
      '#options' => $list,
      '#empty_option' => "- Votre catégorie -",
      '#title' => $this->t("Catégorie de l'évènement à promouvoir"),
    );
*/

//$select_region->rng_registration_type;

   /* 
    $form['user_event_promoted_region'] = array(
      '#type' => 'checkbox',
      '#options' => $term_region->name,
      '#attributes' => [ 'class' => [ 'form-control', ], ],
      '#title' => $this->t("Promouvoir dans votre region:"),
    );*/


  /*  $form['promote_type'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Type de promotion:'),
      '#options' => $list_user_event,
    );*/

    $form['actions'] = array('#type' => 'actions'); 
    $form['actions']['send'] = [ 
      '#type' => 'submit', 
      '#value' => $this->t('Valider'), 
      '#attributes' => [ 'class' => [ 'use-ajax', ], ], 
      '#ajax' => [ 'callback' => [$this, 'submitEventPromotionAjax'], 
      'event' => 'click', 
        ],
      ]; 
     $form['#attached']['library'][] = 'core/drupal.dialog.ajax'; 

     return $form;
 }

 function getReturnTripFields($form, $form_state){
  return array(
      '#title' => t('Promotions disponibles:'),
      '#type' => 'checkboxes',
      '#description' => t('Selectionner le type de promotion pour votre évènnement.'),
      '#options' =>  array(t('SAT'), t('ACT')), 
    ); 

}

  function returnAjax($form, FormStateInterface $form_state) {
    $response = new AjaxResponse();
    if ($form_state->getValue('switch') == 2) {
      $content = [ '#markup' => ' YES ', ];
    }
    else {
      $content = [ '#markup' => ' ', ];
    }
    $response->addCommand(new HtmlCommand('#edit-fields-test', $content));
    $form_state->setRebuild(TRUE);
    return $response;
  }


 /**
  * AJAX callback handler that displays any errors or a success message. 
  */ 
  public function submitModalEventPromotionAjax(array $form, FormStateInterface $form_state) { 
    $response = new AjaxResponse(); 
    // If there are any form errors, re-display the form. 
    if ($form_state->hasAnyErrors()) { 
      $response->addCommand(new ReplaceCommand('#event_promotion', $form)); 
    } 
    else { 
      $response->addCommand(new OpenDialogCommand("Success!", 'The modal form has been submitted.', ['width' => 800])); 
    } 
    return $response; 
  }
/** 
* {@inheritdoc} 
*/ 
  public function getFormId() { 
    return 'event_promotion_modal_form'; 
  }

/** 
* {@inheritdoc} 
*/ 
public function validateForm(array &$form, FormStateInterface $form_state) {} 
/**
* {@inheritdoc} 
*/ 
public function submitForm(array &$form, FormStateInterface $form_state) {} 
/** 
* Gets the configuration names that will be editable. 
* 
* @return array 
* An array of configuration object names that are editable if called in 
* conjunction with the trait's config() method. 
*/ 
  protected function getEditableConfigNames() { 
    return ['config.event_promotion_modal_form']; 
  }


}

?>