<?php
/**
 * @file
 * Template for a 2 column panel layout.
 *
 */
?>
<div <?php if (!empty($css_id)) { print "id=\"$css_id\""; } ?>>
    <div class="top-wrapper">
        <div class="top-left grid-8">
          <?php print $content['top_left']; ?>
        </div>
        <div class="top-right sidebar grid-4">
          <?php print $content['top_right']; ?>
        </div>        
    </div>
    <div class="center-wrapper">
        <div class="center-left grid-8">
          <?php print $content['center_left']; ?>
        </div>
        <div class="center-right sidebar grid-4">
          <?php print $content['center_right']; ?>
        </div>
    </div>
    <div class="bottom-wrapper">
        <div class="bottom-left grid-8">
          <?php print $content['bottom_left']; ?>
        </div>
        <div class="bottom-right sidebar grid-4">
          <?php print $content['bottom_right']; ?>
        </div>      
    </div>
</div>
