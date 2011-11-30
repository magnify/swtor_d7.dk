<?php

class SwtorArticleCommentsMigration extends SwtorMigration {
  public function __construct() {
    parent::__construct();

    // We need to migrate content before comments.
    $this->dependencies = array('SwtorArticles');

    $this->description = 'Comments about articles';
    $this->dependencies = array('SwtorUsers', 'SwtorArticles');
    $this->map = new MigrateSQLMap($this->machineName,
      array('cid' => array(
            'type' => 'int',
            'not null' => TRUE,
           )
         ),
      MigrateDestinationComment::getKeySchema()
    );

    $articles = db_select($this->source_db . '.node', 'n')
                ->fields('n', array('nid'))
                ->condition('type', 'article_with_image');
    $query = db_select($this->source_db . '.comments', 'c')
             ->fields('c', array('cid', 'pid', 'nid', 'uid', 'subject', 'comment', 'hostname', 'timestamp', 'status', 'format', 'thread', 'name', 'mail', 'homepage'))
             ->condition('nid', $articles, 'in');

    $this->source = new MigrateSourceSQL($query);
    $this->destination = new MigrateDestinationComment('comment_node_article');

    $this->addSimpleMappings(array('pid', 'subject', 'hostname', 'thread',
                                   'name', 'mail', 'homepage'));

    // Connect the comment to a node based on previous mapping of articles.
    $this->addFieldMapping('nid', 'nid')
         ->sourceMigration('SwtorArticles');

    // Map uid base on user migration.
    $this->addFieldMapping('uid', 'uid')
         ->sourceMigration('SwtorUsers');

    $body_arguments = MigrateTextFieldHandler::arguments(NULL, filter_default_format(), NULL);
    $this->addFieldMapping('comment_body', 'comment')
         ->arguments($body_arguments);

    $this->addFieldMapping('created', 'timestamp');
    $this->addFieldMapping('status', 'status')->defaultValue(COMMENT_PUBLISHED);

     // Unmapped fields.
    $this->addFieldMapping(NULL, 'format')
         ->issueGroup(t('DNM'));

    // Unmapped destination fields
    $this->addUnmigratedDestinations(array('path','changed', 'language'));
  }
}