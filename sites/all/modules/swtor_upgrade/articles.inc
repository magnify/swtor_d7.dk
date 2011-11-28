<?php

class SwtorArticlesMigration extends SwtorMigration {
  public function __construct() {    
    parent::__construct();

    // Give the task a description.
    $this->description = t('Migrate article pages');

    // We might need to migrate terms before articles.
    $this->dependencies = array('SwtorUsers');

    $source_fields = array(
      'nid' => t('The node ID of the article'),
      'title_image' => t('Article images from field image'),
      'image_gallery' => t('Image gallery found on some articles'),
      'field_video' => t('Video field'),
    );

    // Select all article nodes.
    $query = db_select($this->source_db .'.node', 'n')
             ->fields('n', array('nid', 'vid', 'type', 'language', 'title', 'uid', 'status', 'created', 'changed', 'comment', 'promote', 'moderate', 'sticky', 'tnid', 'translate'))
             ->condition('n.type', 'article_with_image');
    $query->join($this->source_db .'.node_revisions', 'nr', 'n.vid = nr.vid');
    $query->addField('nr', 'body');
    $query->addField('nr', 'teaser');
    $query->join($this->source_db .'.users', 'u', 'n.uid = u.uid');
    $query->addField('u', 'name');
    $query->orderBy('n.changed');

    // Add information about migrating updates of content.
    $this->highwaterField = array(
      'name' => 'changed',
      'alias' => 'n',
    );

    $this->source = new MigrateSourceSQL($query, $source_fields);
    $this->destination = new MigrateDestinationNode('article');

    // Define the SQL mapping.
    $this->map = new MigrateSQLMap($this->machineName,
      array(
        'nid' => array(
          'type' => 'int',
          'unsigned' => TRUE,
          'not null' => TRUE,
          'description' => 'D6 Unique Node ID',
          'alias' => 'n',
        )
      ),
      MigrateDestinationNode::getKeySchema()
    );

        // Make the mappings
    $this->addFieldMapping('title', 'title');
    $this->addFieldMapping('is_new')
          ->defaultValue(TRUE);
    $this->addFieldMapping('uid', 'uid');
    $this->addFieldMapping('revision')
         ->defaultValue(TRUE);
    $this->addFieldMapping('revision_uid', 'uid');
    $this->addFieldMapping('created', 'created');
    $this->addFieldMapping('changed', 'changed');
    $this->addFieldMapping('status', 'status');
    $this->addFieldMapping('promote', 'promote');
    $this->addFieldMapping('sticky', 'sticky');
    $this->addFieldMapping('comment', 'comment');
    $this->addFieldMapping('language')
         ->defaultValue('und');

    $this->addFieldMapping(NULL, 'name');
    $this->addFieldMapping(NULL, 'vid');
    $this->addFieldMapping(NULL, 'type');
    $this->addFieldMapping(NULL, 'language');
    $this->addFieldMapping(NULL, 'moderate');
    $this->addFieldMapping(NULL, 'tnid');
    $this->addFieldMapping(NULL, 'translate');

    // Unmapped fields
    $this->addFieldMapping('path')
         ->issueGroup(t('DNM'));
    $this->addFieldMapping('pathauto')
         ->issueGroup(t('DNM'));

    $this->addFieldMapping('field_teaser', 'teaser');

    $body_arguments = MigrateTextFieldHandler::arguments(NULL, 'editor_html', NULL);
    $this->addFieldMapping('body', 'body')
         ->arguments($body_arguments);

    $associated_file_arguments = MigrateFileFieldHandler::arguments(NULL, 'file_copy', FILE_EXISTS_RENAME);
    $this->addFieldMapping('field_image', 'title_image')
         ->arguments($associated_file_arguments);
  }

  public function prepareRow($current_row) {
    // Get more information about the title images
    $title_image_query = db_select($this->source_db . '.content_type_article_with_image', 'cfi')
                         ->fields('cfi')
                         ->condition('cfi.nid', $current_row->nid);
    $title_image_query->join($this->source_db . '.files', 'f', 'cfi.field_image_fid = f.fid');
    $title_image_query->addField('f', 'filepath');
    $results = $title_image_query->execute();

    $images = array();
    foreach ($results as $row) {
      $image_data = unserialize($row->field_image_data);
      $current_image_data = array(
        'path' => $this->source_path .'/'. $row->filepath,
        'alt' => $image_data['alt'],
        'title' => $image_data['title'],
      );
      $images[] = drupal_json_encode($current_image_data);
    }
    $current_row->title_image = $images;
    return TRUE;
  }
}