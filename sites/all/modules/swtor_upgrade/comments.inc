<?php

/**
 * Base class used by comment migration objects.
 */
abstract class SwtorCommentMigration extends SwtorMigration {
  public function __construct($distination, $source, $dependent) {
    parent::__construct();

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
                ->condition('type', $source);
    $query = db_select($this->source_db . '.comments', 'c')
             ->fields('c', array('cid', 'pid', 'nid', 'uid', 'subject', 'comment', 'hostname', 'timestamp', 'status', 'format', 'thread', 'name', 'mail', 'homepage'))
             ->condition('nid', $articles, 'in');

    $this->source = new MigrateSourceSQL($query);
    $this->destination = new MigrateDestinationComment($distination);

    $this->addSimpleMappings(array('pid', 'subject', 'hostname', 'thread',
                                   'name', 'mail', 'homepage'));

    // Connect the comment to a node based on previous mapping of articles.
    $this->addFieldMapping('nid', 'nid')
         ->sourceMigration($dependent);

    // Map uid base on user migration.
    $this->addFieldMapping('uid', 'uid')
         ->sourceMigration('SwtorUsers');

    $body_arguments = MigrateTextFieldHandler::arguments(NULL, 'filtered_html', NULL);
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

class SwtorArticleCommentsMigration extends SwtorCommentMigration {
  public function __construct() {
    parent::__construct('comment_node_article', 'article_with_image', 'SwtorArticles');

    // We need to migrate content and users before comments.
    $this->dependencies = array('SwtorUsers', 'SwtorArticles');

    $this->description = 'Comments about articles';
  }
}

class SwtorForumCommentsMigration extends SwtorCommentMigration {
  public function __construct() {
    parent::__construct('comment_node_forum', 'forum', 'SwtorForum');

    // We need to migrate content and users before comments.
    $this->dependencies = array('SwtorUsers', 'SwtorForum');

    $this->description = 'Comments in forum';
  }
}
