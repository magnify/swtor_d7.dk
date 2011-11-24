<?php

/**
 * @file
 * A basic migration example.
 */
abstract class SwtorMigration extends Migration {
  protected $source_db = 'swtor_d6';

  public function __construct() {
    parent::__construct();
  }
}

class SwtorUsersMigration extends SwtorMigration {
  public function __construct() {
    // The basic setup is similar to BeerTermMigraiton
    parent::__construct();

    $this->description = t('Swtor users');
    $this->map = new MigrateSQLMap($this->machineName,
        array('uid' => array(
                'type' => 'int',
                'unsigned' => TRUE,
                'not null' => TRUE,
                'description' => 'ID of sources user',
              ),
             ),
        MigrateDestinationUser::getKeySchema()
    );
    $query = db_select($this->source_db . '.users', 'swuser')
             ->fields('swuser', array('uid', 'name', 'pass', 'mail', 'mode',
                                      'sort', 'threshold', 'theme', 'signature',
                                      'signature_format', 'created', 'access',
                                      'login', 'status', 'timezone', 'language',
                                      'picture', 'init', 'data', 'timezone_name'))
             ->condition('uid', 0, '!=')
             ->condition('uid', 1, '!=');
    $this->source = new MigrateSourceSQL($query);

    $this->destination = new MigrateDestinationUser();

    // One good way to organize your mappings is in three groups - mapped fields,
    // unmapped source fields, and unmapped destination fields

    // Mapped fields

    // This is a shortcut you can use when the source and destination fields are
    // identical
    $this->addSimpleMappings(array('pass', 'mail', 'theme', 'signature',
                                   'signature_format', 'created', 'access',
                                   'login', 'status', 'timezone', 'language',
                                   'picture', 'init'));

    // Map 'data'


    // Dedupe assures that value is unique. Use it when source data is non-unique.
    // Pass the Drupal table and column for determining uniqueness.
    $this->addFieldMapping('name', 'name')
         ->dedupe('users', 'name');

    // Instead of mapping a source field to a destination field, you can
    // hardcode a default value. You can also use both together - if a default
    // value is provided in addition to a source field, the default value will
    // be applied to any rows where the source field is empty or NULL.
    $this->addFieldMapping('roles')
         ->defaultValue(2);

    // Unmapped source fields
    $this->addFieldMapping(NULL, 'mode')
         ->issueGroup(t('DNM'));
    $this->addFieldMapping(NULL, 'sort')
         ->issueGroup(t('DNM'));
    $this->addFieldMapping(NULL, 'threshold')
         ->issueGroup(t('DNM'));
    $this->addFieldMapping(NULL, 'timezone_name')
         ->issueGroup(t('DNM'));


    // Unmapped destination fields

    // This is a shortcut you can use to mark several destination fields as DNM
    // at once
    $this->addUnmigratedDestinations(array('is_new'));
  }
}

