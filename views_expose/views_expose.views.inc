<?php

/**
 * Implements hook_views_data().
 *
 * Views data definition for table trainings_attendancetracker.
 */
function views_expose_views_data() {
  $data = array();
  _views_expose_get_views_data($data);
  return $data;
}

/**
 * Callback to expose 'trainings_attendancetracker' data table for views.
 */
function _views_expose_get_views_data(&$data) {
  $data['example']['table']['group'] = t('Example');
  $data['example']['table']['base'] = array(
    'title' => t('example table'),
    'help' => t('example table.'),
  );
  $data['example']['name'] = array(
    'title' => t('Name Field'),
    'help' => t('Name Field'),
    'field' => array(
      'handler' => 'views_handler_field',
    ),
    'filter' => array(
      'handler' => 'views_handler_filter_string',
    ),
    'sort' => array(
      'handler' => 'views_handler_sort',
    ),
  );

// name field
  $data['example']['city'] = array(
    'title' => t('City Name'),
    'help' => t('City Name'),
    'field' => array(
      'handler' => 'views_handler_field',
    ),
    'filter' => array(
      'handler' => 'views_handler_filter_string',
    ),
    'sort' => array(
      'handler' => 'views_handler_sort',
    ),
  );
}
