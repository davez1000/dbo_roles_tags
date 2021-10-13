<?php

/**
 * @file
 * Creates a admin configuration form for solr synonyms.
 */

namespace Drupal\dbo_roles_tags\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Builds the synonyms config form.
 */
class RolesTagsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'dbo_roles_tags.roles_tags_form',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'dbo_roles_tags_roles_tags_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $config = \Drupal::service('config.factory')->getEditable('dbo_roles_tags.settings');
    $config_tags = $config->get('tags') ?? [];
//    kint($config->); exit;


//    $state = \Drupal::state()->get('dbo_roles_tags') ?? [];
    $roles = \Drupal::entityTypeManager()->getStorage('user_role')->loadMultiple();

    foreach ($roles as $key => $role) {
      $form['roles'][$key] = [
        '#title' => 'Tags for "' . $role->label() . '" [' . $key . ']:',
        '#description' => '',
        '#type' => 'textfield',
        '#default_value' => !empty($config_tags[$key]) ? $config_tags[$key] : '',
        '#size' => 60,
        '#maxlength' => 1000,
        '#required' => FALSE,
      ];
    }

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);
//    $state = \Drupal::state()->get('dbo_roles_tags') ?? [];
    $config = \Drupal::service('config.factory')->getEditable('dbo_roles_tags.settings');
    $config_tags = $config->get('tags') ?? [];

    $values = $form_state->getValues();
    foreach ($values as $key => $value) {
      $config_tags[$key] = $value;
    }
    $config->set('tags', $config_tags)->save();
//    \Drupal::state()->set('dbo_roles_tags', $state);
  }
}
