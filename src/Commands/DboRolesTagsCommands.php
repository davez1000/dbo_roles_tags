<?php

namespace Drupal\dbo_roles_tags\Commands;

use Consolidation\OutputFormatters\StructuredData\RowsOfFields;
use Drush\Commands\DrushCommands;

/**
 * Drush commands.
 */
class DboRolesTagsCommands extends DrushCommands {

  /**
   * Gets a list of all roles and tags, or a single role for a specified tag.
   *
   * @command dbo:rolestags
   * @aliases dbo-rolestags
   * @usage dbo:rolestags --tagname=hello12
   *
   * @table-style default
   * @field-labels
   *   label: Name
   *   key: Machine name
   *   tags: Tag
   * @default-fields label,key,tags
   * @return \Consolidation\OutputFormatters\StructuredData\RowsOfFields
   */
  public function rolestags($options = ['tagname' => NULL]): RowsOfFields {
    $rows = [];
    $state = \Drupal::state()->get('dbo_roles_tags') ?? [];
    $roles = \Drupal::entityTypeManager()->getStorage('user_role')->loadMultiple();
    foreach ($roles as $key => $role) {
      if (!empty($options['tagname'])) {
        if ($options['tagname'] != $state[$key]) {
          continue;
        }
      }
      $rows[] = [
        'label' => $role->label(),
        'key' => $key,
        'tags' => isset($state[$key]) ? $state[$key] : '',
      ];
    }
    return new RowsOfFields($rows);
  }

}
