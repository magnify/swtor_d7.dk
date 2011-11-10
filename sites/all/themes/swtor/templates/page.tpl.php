<?php
// $Id: page.tpl.php,v 1.9 2010/11/07 21:48:56 dries Exp $

/**
 * @file
 * swtor.dk's theme implementation to display a single Drupal page.
 *
 * The doctype, html, head and body tags are not in this template. Instead they
 * can be found in the html.tpl.php template normally located in the
 * modules/system folder.
 *
 * Available variables:
 *
 * General utility variables:
 * - $base_path: The base URL path of the Drupal installation. At the very
 *   least, this will always default to /.
 * - $directory: The directory the template is located in, e.g. modules/system
 * - $is_front: TRUE if the current page is the front page.
 * - $logged_in: TRUE if the user is registered and signed in.
 * - $is_admin: TRUE if the user has permission to access administration pages.
 *
 * Site identity:
 * - $front_page: The URL of the front page. Use this instead of $base_path,
 *   when linking to the front page. This includes the language domain or
 *   prefix.
 * - $logo: The path to the logo image, as defined in theme configuration.
 * - $site_name: The name of the site, empty when display has been disabled
 *   in theme settings.
 * - $site_slogan: The slogan of the site, empty when display has been disabled
 *   in theme settings.
 * - $hide_site_name: TRUE if the site name has been toggled off on the theme
 *   settings page. If hidden, the "element-invisible" class is added to make
 *   the site name visually hidden, but still accessible.
 * - $hide_site_slogan: TRUE if the site slogan has been toggled off on the
 *   theme settings page. If hidden, the "element-invisible" class is added to
 *   make the site slogan visually hidden, but still accessible.
 *
 * Navigation:
 * - $main_menu (array): An array containing the Main menu links for the
 *   site, if they have been configured.
 * - $secondary_menu (array): An array containing the Secondary menu links for
 *   the site, if they have been configured.
 * - $breadcrumb: The breadcrumb trail for the current page.
 *
 * Page content (in order of occurrence in the default page.tpl.php):
 * - $title_prefix (array): An array containing additional output populated by
 *   modules, intended to be displayed in front of the main title tag that
 *   appears in the template.
 * - $title: The page title, for use in the actual HTML content.
 * - $title_suffix (array): An array containing additional output populated by
 *   modules, intended to be displayed after the main title tag that appears in
 *   the template.
 * - $messages: HTML for status and error messages. Should be displayed
 *   prominently.
 * - $tabs (array): Tabs linking to any sub-pages beneath the current page
 *   (e.g., the view and edit tabs when displaying a node).
 * - $action_links (array): Actions local to the page, such as 'Add menu' on the
 *   menu administration interface.
 * - $feed_icons: A string of all feed icons for the current page.
 * - $node: The node object, if there is an automatically-loaded node
 *   associated with the page, and the node ID is the second argument
 *   in the page's path (e.g. node/12345 and node/12345/revisions, but not
 *   comment/reply/12345).
 *
 * Regions:
 * - $page['header']: Items for the header region.
 * - $page['featured']: Items for the featured region.
 * - $page['highlighted']: Items for the highlighted content region.
 * - $page['help']: Dynamic help text, mostly for admin pages.
 * - $page['content']: The main content of the current page.
 * - $page['sidebar_first']: Items for the first sidebar.
 * - $page['footer']: Items for the footer region.
 *
 * @see template_preprocess()
 * @see template_preprocess_page()
 * @see template_process()
 * @see bartik_process_page()
 */
?>

<div id="header" class="container-12 <?php print $secondary_menu ? 'with-secondary-menu': 'without-secondary-menu'; ?>"><div class="section clearfix">

  <?php if ($logo): ?>
    <a href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>" rel="home" id="logo">
      <img src="<?php print $logo; ?>" alt="<?php print t('Home'); ?>" />
    </a>
  <?php endif; ?>

  <?php print render($page['header']); ?>
 
</div></div> <!-- /.section, /#header -->

<?php if ($page['pagetop']): ?>
  <div class="region container-12 clearfix"><?php print render($page['pagetop']); ?></div>
<?php endif ?>

<div id="page-wrapper"><div id="page" class="container-12">

  <?php if ($messages): ?>
    <div id="messages"><div class="section clearfix">
      <?php print $messages; ?>
    </div></div> <!-- /.section, /#messages -->
  <?php endif; ?>

  <div id="main-wrapper" class="clearfix"><div id="main" class="clearfix">

    <div id="content" class="grid-12 alpha omega"><div class="section">

      <?php if ($tabs): ?>
        <div class="tabs">
          <?php print render($tabs); ?>
        </div>
      <?php endif; ?>
      <?php print render($page['help']); ?>
      <?php if ($action_links): ?>
        <ul class="action-links">
          <?php print render($action_links); ?>
        </ul>
      <?php endif; ?>

      <?php if ($breadcrumb): ?>
        <div id="breadcrumb"><?php print $breadcrumb; ?></div>
      <?php endif; ?>

      <?php if ($page['sidebar_first']): ?>
        <div id="sidebar-first" class="grid-3"><div class="section">
          <?php print render($page['sidebar_first']); ?>
        </div></div> <!-- /.section, /#sidebar-first -->
      <?php endif; ?>

      <div class="<?php if ($page['sidebar_first']) print 'grid-9 ' ?><?php if($page['sidebar_second']) print 'grid-8 ' ?>alpha omega">
        <a id="main-content"></a>
        <?php if ($title): ?>
          <h1 class="title" id="page-title">
            <?php print $title; ?>
          </h1>
        <?php endif; ?>
        <?php print render($title_suffix); ?>
        <?php print render($page['content']); ?>
      </div>
      <?php if ($page['sidebar_second']): ?>
        <div id="sidebar-second" class="grid-4">
          <div class="section">
            <?php print render($page['sidebar_second']); ?>
          </div>
        </div> <!-- /.section, /#sidebar-second -->
      <?php endif; ?>
      <?php if ($page['column_three_one'] || $page['column_three_two'] || $page['column_three_three']): ?>
        <div id="columns-three" class="columns-three grid-12 alpha omega">
          <div id="columns-three-one" class="column columns-three-one grid-4 alpha"><?php print render($page['column_three_one']); ?></div>
          <div id="columns-three-two" class="column columns-three-two grid-4"><?php print render($page['column_three_two']); ?></div>
          <div id="columns-three-three" class="column columns-three-three grid-4 omega"><?php print render($page['column_three_three']); ?></div>
        </div>
      <?php endif; ?>
      <?php if ($page['column_four_one'] || $page['column_four_two'] || $page['column_four_three'] || $page['column_four_four']): ?>
        <div id="columns-four" class="region columns-four container-12">
          <div id="columns-three-one" class="column columns-four-one grid-3"><?php print render($page['column_four_one']); ?></div>
          <div id="columns-three-two" class="column columns-three-two grid-3"><?php print render($page['column_four_two']); ?></div>
          <div id="columns-three-three" class="column columns-four-three grid-3"><?php print render($page['column_four_three']); ?></div>
          <div id="columns-three-three" class="column columns-four-three grid-3"><?php print render($page['column_four_four']); ?></div>
        </div>
      <?php endif; ?>
    </div></div> <!-- /.section, /#content -->

  </div></div> <!-- /#main, /#main-wrapper -->

</div></div> <!-- /#page, /#page-wrapper -->

<div id="footer-wrapper"><div class="section">
  <?php if ($page['footer'] || $page['footer_first'] || $page['footer_second'] || $page['footer_third']): ?>
    <div id="footer" class="container-12 clearfix">
      <?php if ($page['footer_links']): ?>
        <div id="footer-links" class="footer-links grid-12">
          <?php print render($page['footer_links']); ?>
        </div>
      <?php endif; ?>
      <?php print render($page['footer']); ?>
      <?php if ($page['footer_first']): ?>
        <div id="footer-first" class="footer-column footer-first grid-3 alpha omega">
          <?php print render($page['footer_first']); ?>
        </div>
      <?php endif; ?>      
      <?php if ($page['footer_second']): ?>
        <div id="footer-second" class="footer-column footer-second grid-3 alpha omega">
          <?php print render($page['footer_second']); ?>
        </div>
      <?php endif; ?>      
      <?php if ($page['footer_third']): ?>
        <div id="footer-third" class="footer-column footer-third grid-3 alpha omega">
          <?php print render($page['footer_third']); ?>
        </div>
      <?php endif; ?>
      <?php if ($page['footer_copyright']): ?>
        <div id="footer-copyright" class="footer-copyright grid-12">
          <?php print render($page['footer_copyright']); ?>
        </div>
      <?php endif; ?>      
    </div>
  <?php endif; ?>    
</div></div> <!-- /.section, /#footer-wrapper -->
  