<?php

/**
 * Implementation of hook_feeds_importer_default().
 */
function cenix_migration_feeds_importer_default() {
  $export = array();
  $feeds_importer = new stdClass;
  $feeds_importer->disabled = FALSE; /* Edit this to true to make a default feeds_importer disabled initially */
  $feeds_importer->api_version = 1;
  $feeds_importer->id = 'swtor_user_import';
  $feeds_importer->config = array(
    'name' => 'SWTOR user import',
    'description' => '',
    'fetcher' => array(
      'plugin_key' => 'FeedsHTTPFetcher',
      'config' => array(
        'auto_detect_feeds' => FALSE,
        'use_pubsubhubbub' => FALSE,
        'designated_hub' => '',
      ),
    ),
    'parser' => array(
      'plugin_key' => 'FeedsCSVParser',
      'config' => array(
        'delimiter' => ';',
        'no_headers' => 0,
      ),
    ),
    'processor' => array(
      'plugin_key' => 'FeedsUserProcessor',
      'config' => array(
        'roles' => array(
          3 => 0,
        ),
        'status' => '1',
        'defuse_mail' => 0,
        'mappings' => array(
          0 => array(
            'source' => '_guid_',
            'target' => 'guid',
            'unique' => 1,
          ),
          1 => array(
            'source' => '_username_',
            'target' => 'name',
            'unique' => 1,
          ),
          2 => array(
            'source' => '_email_',
            'target' => 'mail',
            'unique' => 1,
          ),
          3 => array(
            'source' => '_created_',
            'target' => 'created',
            'unique' => FALSE,
          ),
          4 => array(
            'source' => '_fullname_',
            'target' => 'field_fullname',
            'unique' => FALSE,
          ),
          5 => array(
            'source' => '_age_',
            'target' => 'field_age',
            'unique' => FALSE,
          ),
          6 => array(
            'source' => '_wlm_',
            'target' => 'field_wlm',
            'unique' => FALSE,
          ),
          7 => array(
            'source' => '_skype_',
            'target' => 'field_skype',
            'unique' => FALSE,
          ),
        ),
        'update_existing' => 1,
        'input_format' => 'plain_text',
      ),
    ),
    'content_type' => '',
    'update' => 0,
    'import_period' => '-1',
    'expire_period' => 3600,
    'import_on_create' => 1,
    'process_in_background' => 0,
  );
  
  $export['swtor_user_import'] = $feeds_importer;
  
  
  $feeds_importer = new stdClass;
  $feeds_importer->disabled = FALSE; /* Edit this to true to make a default feeds_importer disabled initially */
  $feeds_importer->api_version = 1;
  $feeds_importer->id = 'swtor_article_import';
  $feeds_importer->config = array(
    'name' => 'SWTOR article import',
    'description' => '',
    'fetcher' => array(
      'plugin_key' => 'FeedsHTTPFetcher',
      'config' => array(
        'auto_detect_feeds' => FALSE,
        'use_pubsubhubbub' => FALSE,
        'designated_hub' => '',
      ),
    ),
    'parser' => array(
      'plugin_key' => 'FeedsCSVParser',
      'config' => array(
        'delimiter' => ';',
        'no_headers' => 0,
      ),
    ),
    'processor' => array(
      'plugin_key' => 'FeedsNodeProcessor',
      'config' => array(
        'content_type' => 'article',
        'expire' => '-1',
        'author' => 0,
        'mappings' => array(
          0 => array(
            'source' => '_guid_',
            'target' => 'nid',
            'unique' => 1,
          ),
          1 => array(
            'source' => '_title_',
            'target' => 'title',
            'unique' => FALSE,
          ),
          2 => array(
            'source' => '_body_',
            'target' => 'body',
            'unique' => FALSE,
          ),
          3 => array(
            'source' => '_author_',
            'target' => 'uid',
            'unique' => FALSE,
          ),
          4 => array(
            'source' => '_published_',
            'target' => 'status',
            'unique' => FALSE,
          ),
          5 => array(
            'source' => '_publish_date_',
            'target' => 'created',
            'unique' => FALSE,
          ),
          6 => array(
            'source' => '_image_',
            'target' => 'field_image',
            'unique' => FALSE,
          ),
        ),
        'update_existing' => '1',
        'input_format' => 'full_html',
      ),
    ),
    'content_type' => '',
    'update' => 0,
    'import_period' => '-1',
    'expire_period' => 3600,
    'import_on_create' => 1,
    'process_in_background' => 0,
  );
    
  $export['swtor_article_import'] = $feeds_importer;
  return $export;
}
