<?php 
namespace Drupal\event_promotion\Form; 

use Drupal\Core\Form\FormBase; 
use Drupal\Core\Form\FormStateInterface; 
use Drupal\Core\Url; 
use Drupal\Core\Session\AccountInterface;

/** 
* EventPromotion class. 
*/ 
class EventPromotion extends FormBase { 

	/** 
	* {@inheritdoc} 
	*/ 
	public function buildForm(array $form, FormStateInterface $form_state, AccountInterface $user = NULL) { 

		$form['open_modal'] = [ 
			'#type' => 'link', 
			'#title' => $this->t('Promouvoir vos évènements'), 
			'#url' => Url::fromRoute('event_promotion.open_modal_form', array('user' => \Drupal::currentUser()->id(), 'js' => 'ajax')), 
			'#attributes' => [ 
				'class' => [ 
					'use-ajax', 
					'button',
					'btn', 
					'btn-lg',
					'btn-primary',
					'form-control', 
					'fa', 
					'fa-shopping-cart',
					'fa-2x', 
					'pull-left',
					], 
				],
		];
		// Attach the library for pop-up dialogs/modals. 
		$form['#attached']['library'][] = 'core/drupal.dialog.ajax'; 

		return $form;
	} 

	/** 
	* {@inheritdoc} 
	*/ 
	public function submitForm(array &$form, FormStateInterface $form_state) {} 

	/** 
	* {@inheritdoc} 
	*/ 
	public function getFormId() { 
		return 'event_promotion_form'; 
	} 
	/** 
	* Gets the configuration names that will be editable. * 
	* @return array * An array of configuration object names that are editable if called in 
	* conjunction with the trait's config() method. 
	*/ 
	protected function getEditableConfigNames() { 
		return ['config.event_promotion_form']; 
	} 

}

?>