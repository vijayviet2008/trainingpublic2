<?php

namespace Drupal\training_migrate\Plugin\migrate\source;

use Drupal\migrate\Plugin\migrate\source\SqlBase;

/**
 * Source plugin for the movies types.
 *
 * @MigrateSource(
 *   id = "movies_types"
 * )
 */
class MoviesType extends SqlBase {

  /**
   * {@inheritdoc}
   */
  public function query() {
    $query = $this->select('movies_type', 'm')
      ->fields('m', ['id', 'movies_id', 'name']);
    return $query;
  }

  /**
   * {@inheritdoc}
   */
  public function fields() {
    $fields = [
      'id' => $this->t('Movies Type ID'),
      'movies_id' => $this->t('Movie ID'),
      'name' => $this->t('Movies Type'),
    ];

    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public function getIds() {
    return [
      'id' => [
        'type' => 'integer',
        'alias' => 'm',
      ],
    ];
  }
}