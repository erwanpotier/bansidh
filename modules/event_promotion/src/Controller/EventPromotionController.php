<?php

namespace Drupal\event_promotion\Controller;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\OpenModalDialogCommand;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Form\FormBuilder;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Ajax\OpenDialogCommand; 
use Drupal\rng\Form\EventTypeForm;

class EventPromotionController extends ControllerBase {

protected $formBuilder;

public function __construct(FormBuilder $formBuilder) {
  $this->formBuilder = $formBuilder; 
} 

 public static function create(ContainerInterface $container) { 
  return new static( $container->get('form_builder') ); 
}


 public function openModalForm(AccountInterface  $user, $js = 'nojs') { 
  if ($js == 'ajax') {
    $options = array(
      'width' => '80%',
      'height' => '700',
     // 'dialogClass' => 'modal fade',
    );
  $response = new AjaxResponse(); 
  $user = \Drupal::currentUser()->id();
  // Get the modal form using the form builder.
  $modal_form = $this->formBuilder->getForm('Drupal\event_promotion\Form\ModalEventPromotion', $user); 
  // Add an AJAX command to open a modal dialog with the form as the content. 
  $response->addCommand(new OpenModalDialogCommand(t('Promouvoir votre évènnement.'), $modal_form, $options)); 
  return $response; 
  }
  else {
    return t('This is the page without Javascript.');      
  }
  }

}

?>