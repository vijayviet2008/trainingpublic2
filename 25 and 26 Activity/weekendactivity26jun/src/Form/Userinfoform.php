<?php

namespace Drupal\weekendactivity26jun\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;

/**
 * Class Userinfoform.
 */
class Userinfoform extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'userinfoform';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $vid = 'interests';
    $term_data = [];
    $terms =\Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree($vid);
    foreach ($terms as $term) {
      $term_data[$term->tid] = $term->name;
    }
    //kint($term_data); exit;
    $form['message'] = [
      '#type' => 'markup',
      '#markup' => '<div class="result_message"></div>'
    ];
    $form['first_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('First Name'),
      '#maxlength' => 64,
      '#size' => 64,
      '#weight' => '0',
    ];
    $form['last_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Last name'),
      '#maxlength' => 64,
      '#size' => 64,
      '#weight' => '0',
    ];
    $form['bio'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Bio'),
      '#weight' => '0',
    ];
    
    $form['gender'] = [
      '#type' => 'radios',
      '#title' => $this->t('Gender'),
      '#options' => ['Male' => $this->t('Male'), 'Female' => $this->t('Female'), 'Other' => $this->t('Other')],
      '#weight' => '0',
    ];
    $form['interests'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Interests'),
      '#options' => $term_data,
      '#weight' => '0',
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
      '#ajax' => [
        'callback' => '::submitUserInfoForm',
      ],
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    
  }
  
   /**
   * Submiting form.
   */
  public function submitUserInfoForm(array $form, FormStateInterface $form_state) {
    $response = new AjaxResponse();
    $first_name = $form_state->getValue('first_name');
    $last_name = $form_state->getValue('last_name');
    $bio = $form_state->getValue('bio');
    $gender = $form_state->getValue('gender');
    $interests = serialize($form_state->getValue('interests'));
    $error_mess = '';
    if (empty($first_name)) {
      $error_mess .= "Please enter first name.";
    }
    if (empty($bio)) {
      $error_mess .= "Please enter  bio.";
    }
    if (empty($gender)) {
    
      $error_mess .= "Please select gender.";
    }
    //echo "<pre>";
    //print_r($interests);
    if(empty($error_mess)){
      $entry = [
        'first_name' => $first_name,
        'last_name' => $last_name,
        'bio' => $bio,
        'gender' => $gender,
        'interests' => $interests,
      ];

      try {
        $return_value = \Drupal::database()->insert('userinfo')
          ->fields($entry)
          ->execute();
          $mess = "Saved sucessfully.";

      }
      catch (\Exception $e) {
        $mess = "Insert failed.";
      }
      $response->addCommand(
        new HtmlCommand(
          '.result_message',
          '<div class="my_top_message">' .  $mess . '</div>'
        )
      );
    }
    else{
      $response->addCommand(
        new HtmlCommand(
          '.result_message',
          '<div class="my_top_message">' .  $error_mess . '</div>'
        )
      );

    }
    
    return $response;
  }
  /**
   * Submitting the form.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
  }
}
