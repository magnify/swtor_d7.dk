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
    );

    // Select all article nodes.
    $query = db_select($this->source_db .'.node', 'n')
             ->fields('n', array('nid', 'vid', 'type', 'language', 'title', 'uid', 'status', 'created', 'changed', 'comment', 'promote', 'moderate', 'sticky', 'tnid', 'translate'))
             ->condition('n.type', 'article');
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

  }
}