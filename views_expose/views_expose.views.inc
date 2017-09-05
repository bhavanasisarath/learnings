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
  
  // Export data from training_data table.
  // Making a relation with node table nid field.
  $data['training_data']['table']['group'] = t('Training Data Table');
  $data['training_data']['table']['base'] = array(
    'title' => t('Training Data Table'),
    'help' => t('Training Data Table with location.'),
  );
  $data['training_data']['table']['join'] = array(
    'node' => array(
        'left_field' => 'nid',
        'field' => 'node_id',
    ),
  );
  $data['training_data']['node_id'] = array(
    'title' => t('Nid'),
    'help' => t('Nid of training data table.'),
    'field' => array(
      'handler' => 'views_handler_field_node',
    ),
    'filter' => array(
      'handler' => 'views_handler_filter_numeric',
    ),
    'sort' => array(
      'handler' => 'views_handler_sort',
    ),
    'relationship' => array(
        'base' => 'node',
        'field' => 'node_id',
        'handler' => 'views_handler_relationship',
        'label' => t('node'),
    ),
    'argument' => array(
        'handler' => 'views_handler_argument_node_nid',
        'numeric' => TRUE,
        'validate type' => 'nid',
    ),
  );

// name field
  $data['training_data']['location'] = array(
    'title' => t('Training Location'),
    'help' => t('Location from training data table.'),
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
