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
  unset($form['openid_identifier']);
  unset($form['openid.return_to']);
  
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
  
  $form['actions']['submit'] = array(
    '#markup' => '<input name="submit" value="' . t('Log in') . '" type="submit" class="button-yellow" />', 
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
 * Helper function that builds the HTML for twitter buttons in views lists.
 */
function swtor_build_tweet_button($nid, $title, $name, $type) {
  drupal_add_js('http://platform.twitter.com/widgets.js');

  $token_replacements = array(
    '!title'        => $title,
    '!author_name'  => $name,
    '!node_type'    => $type,
    '!node_url'     => url('node/'.$nid, array('absolute' => TRUE)),
  );

  return theme('tweetbutton_display', array('tokens' => $token_replacements));
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

    // Add tweet button.
    $vars['output'] .= swtor_build_tweet_button($row->nid, $row->node_title, 'swtordk', $row->node_type);
  }
}

/**
 * Implements hook_breadcrumb().
 *
 * Remove the current page title from the breadcrumb.
 */
function swtor_breadcrumb($variables) {
  $output = '';
  $breadcrumb = $variables['breadcrumb'];

    // Build breadcrumb.
  if (!empty($breadcrumb)) {
    $path = drupal_get_path_alias();

    // Add breadcrumb for news subjects.
    if (preg_match('/^artikler\/+/', $path)) {
      $breadcrumb[] = l(t('Articles'), 'artikler');
    }

    // Add current page title to the end of the breadcrumb.
    if ($title = drupal_get_title()) {
      $breadcrumb[] = '<span>' . $title . '</span>';
    }

    // Provide a navigational heading to give context for breadcrumb links to
    // screen-reader users. Make the heading invisible with .element-invisible.
    $output = '<h2 class="element-invisible">' . t('You are here') . '</h2>';
    $output .= '<nav class="breadcrumb">' . implode(' / ', $breadcrumb) .'</nav>';
  }

  return $output;
}
