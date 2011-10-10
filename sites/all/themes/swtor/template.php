<?php

// Auto-rebuild the theme registry during theme development.
if (theme_get_setting('swtor_rebuild_registry')) {
  system_rebuild_theme_data();
}

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

/**
 * Helper function that creates views preprocess functions foreach view.
 */
function swtor_preprocess_views_view(&$vars) {
  if (isset($vars['view']->name)) {
    $function = 'swtor_preprocess_views_view__'.$vars['view']->name; 
    if (function_exists($function)) {
     $function($vars);
    }
  }
}

/**
 * Implementation of hook_preprocess_panels_pane().
 *
 * This function create templets suggestions for panels panes. e.g
 * panels-pane--featured-content.tpl.php or at panel level
 * panels-pane--top-right.tpl.php.
 */
function swtor_preprocess_panels_pane(&$vars) {
  // Suggestions base on sub-type.
  $vars['theme_hook_suggestions'][] = 'panels_pane__' . str_replace('-', '__', $vars['pane']->subtype);

  // Suggestions on panel level
  $vars['theme_hook_suggestions'][] = 'panels_pane__' . $vars['pane']->panel;
}

/**
 * Implements hook_preprocess_views_view_field().
 * We use this hook to implement facebook like buttons in views lists on the
 * front page of the site.
 */
function swtor_preprocess_views_view_field(&$vars) {
  $view = $vars['view'];
  $field = $vars['field'];
  // In the media_detail view, replace nid field with a fb like button
  if (module_exists('fb_social') && ($view->name == 'frontpage') && $field->field == 'nid') {
    // Create a "pseudo-node" object to send to fb_social_like_link
    $row = $vars['row'];
    $pnode = new stdClass();
    $pnode->status = 1;
    $pnode->type = $row->node_type;
    $pnode->nid = $row->nid;
    fb_social_fb_plugin_load('like');
    $preset = fb_social_preset_load('swtor_like');
    $link = fb_social_like_link($preset, 'node', $pnode);
    $vars['output'] = $link['fb_social_like_swtor_like']['title'];
  }
}