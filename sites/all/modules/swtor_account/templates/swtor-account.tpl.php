<?php
/**
 * @file swtor-account.tpl.php
 * Default theme implementation for swtor-account block
 *
 * Available variables:
 * - $links: The linkst listed in under account infomation.
 * - $links_raw: List of links in array, before theme('links', $links).
 * - $messages: The number of unread message.
 * - $links_message: Message links in array, before theme('links', $messages).
 *
 */
?>
<div id="swtor-account">
  <h2><?php print t('Welcome') . ' ' . $username ?></h2>
  <div class="content">
    <?php print $links ?>
    <?php print $messages ?>
    <a href="/logout">Logout</a>
  </div>
</div>