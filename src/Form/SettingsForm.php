<?php

namespace Drupal\commerce_confirm_leave\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class SettingsForm.
 */
class SettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'commerce_confirm_leave.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('commerce_confirm_leave.settings');
    $form['confirmation_message'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Confirmation message'),
      '#description' => $this->t('The message displayed while leaving the order process.'),
      '#maxlength' => 255,
      '#size' => 64,
      '#default_value' => $config->get('confirmation_message'),
    ];

    $form['routes'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Routes'),
      '#description' => $this->t('Routes that will show up a confirmation message.'),
      '#options' => ['cart_page' => $this->t('Cart page'), 'checkout_form' => $this->t('Checkout form')],
      '#default_value' => $config->get('visibility.routes'),
    ];

    $visibility_user_role_roles = $config->get('visibility.user_role_roles');
    $form['role_visibility'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Roles'),
    ];
    $form['role_visibility']['user_role_mode'] = [
      '#type' => 'radios',
      '#title' => $this->t('Show for specific roles'),
      '#options' => [
        $this->t('Show to the selected roles only'),
        $this->t('Show to every role except the selected ones'),
      ],
      '#default_value' => $config->get('visibility.user_role_mode'),
    ];
    $form['role_visibility']['user_role_roles'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Roles'),
      '#default_value' => !empty($visibility_user_role_roles) ? $visibility_user_role_roles : [],
      '#options' => array_map('\Drupal\Component\Utility\Html::escape', user_role_names()),
      '#description' => $this->t('If none of the roles are selected, all users will see the confirmation. If a user has any of the roles checked, that user will see the confirmation (or excluded, depending on the setting above).'),
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $this->config('commerce_confirm_leave.settings')
      ->set('confirmation_message', $form_state->getValue('confirmation_message'))
      ->set('visibility.routes', $form_state->getValue('routes'))
      ->set('visibility.user_role_mode', $form_state->getValue('user_role_mode'))
      ->set('visibility.user_role_roles', $form_state->getValue('user_role_roles'))
      ->save();
  }

}
