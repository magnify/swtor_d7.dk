<?php

class SwtorCommentsMigration extends SwtorMigration {
  public function __construct() {
    parent::__construct();
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

    $this->destination = new MigrateDestinationComment('article');
  }
}