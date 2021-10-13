<?php

/**
 * @file
 *
 * Page to show list of roles, with their tags.
 */

namespace Drupal\dbo_roles_tags\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Utility\TableSort;

/**
 * Methods to deal with content locks.
 */
class DboRolesTagsController extends Controllerbase {

  public function tagsPage() {
    \Drupal::service('page_cache_kill_switch')->trigger();
    $config = \Drupal::service('config.factory')->getEditable('dbo_roles_tags.settings');
    $config_tags = $config->get('tags') ?? [];

    $rows = [];
    $header = [
      ['data' => $this->t('Role'), 'field' => 'role'],
      ['data' => $this->t('Tag(s)'), 'field' => 'tags'],
    ];

    $roles = \Drupal::entityTypeManager()->getStorage('user_role')->loadMultiple();

    foreach ($roles as $role) {
      $rows[] = [
        'role' => $role->label(),
        'tags' => !empty($config_tags[$role->id()]) ? $config_tags[$role->id()] : '',
      ];
    }

    $order = TableSort::getOrder($header, \Drupal::request());
    $sort = TableSort::getSort($header, \Drupal::request());
    $column = $order['sql'];
    foreach ($rows as $row) {
      $temp_array[$row['role']] = $row['tags'];
    }
    if ($column == 'role') {
      ksort($temp_array);
      if ($sort == 'desc') {
        krsort($temp_array);
      }
    }
    if ($column == 'tags') {
      asort($temp_array);
      if ($sort == 'desc') {
        arsort($temp_array);
      }
    }

    $sorted_rows = [];
    foreach ($temp_array as $key => $value) {
      $sorted_rows[] = [
        'role' => $key,
        'tags' => $value,
      ];
    }

    return [
      '#theme' => 'table',
      '#header' => $header,
      '#rows' => $sorted_rows,
    ];

  }

  /**
   * Sorts table contents.
   */
//  protected function _sortTableHeaders($rows, $header) {
//    $order = TableSort::getOrder($header, \Drupal::request());
//    $sort = TableSort::getSort($header, \Drupal::request());
//    $column = $order['sql'];
//    foreach ($rows as $row) {
//      $temp_array[] = $row[$column];
//    }
//    if ($sort == 'asc') {
//      ksort($temp_array);
//    } else {
//      krsort($temp_array);
//    }
//
//    foreach ($temp_array as $index => $data) {
//      $new_rows[] = $rows[$index];
//    }
//
//    return $new_rows;
//  }

}
