<?php

function swtor_form_user_login_block_alter(&$form) {

  // Unset elements
  unset($form['name']);
  unset($form['pass']);
  unset($form['links']);
  unset($form['actions']['submit']);
  unset($form['openid_links']);


  // Set a weight for form actions so other elements can be placed
  // beneath them.
  $form['actions']['#weight'] = 5;

  // Render username
  $form['name'] = array(
    '#type' => 'textfield',
    '#title' => t('Username'),
    '#maxlength' => 60,
    '#size' => 15,
    '#required' => 1,
  );

  // Render password
  $form['pass'] = array(
    '#type' => 'password',
    '#title' => t('Password') . ' (<a href="/user/password" title="' . t('Forgot it?') .'" tabindex="3">' . t('Forgot it?') . '</a>)',
    '#maxlength' => 60,
    '#size' => 15,
    '#required' => 1,
    '#required' => 1,
  );

  // Render submit
  $form['actions']['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Log in'),
  );

  // New user
  if (variable_get('user_register', USER_REGISTER_VISITORS_ADMINISTRATIVE_APPROVAL)) {
    $form['signup'] = array(
      '#markup' => '<div class="new-user"><span>' . t('New user?') . '</span>' . l(t('Create user!'), 'user/register', array('attributes' => array('id' => 'create-new-account', 'title' => t('Create a new user account.')))). '</div>',
      '#weight' => 10,
    );
  }

}