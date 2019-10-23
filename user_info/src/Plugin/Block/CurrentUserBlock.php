<?php

namespace Drupal\user_info\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Form\FormBuilder;
use Drupal\Core\Render\Renderer;
use Drupal\Core\Cache\Cache;

/**
 * Current user block.
 *
 * @Block(
 *  id = "user_info_block",
 *  admin_label = @Translation("User Info Block"),
 *  category = @Translation("Custom")
 * )
 */
class CurrentUserBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Current User.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $currentUser;

  /**
   * FormBuilder object.
   *
   * @var \Drupal\Core\Form\FormBuilder
   */
  protected $formBuilder;

  /**
   * Renderer object.
   *
   * @var \Drupal\Core\Render\Renderer
   */
  protected $renderer;

  /**
   * Construct.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param string $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Session\AccountInterface $current_user
   *   Current logged in user.
   * @param \Drupal\Core\Form\FormBuilder $form_builder
   *   Formbuilder service.
   * @param \Drupal\Core\Render\Renderer $renderer
   *   Renderer service.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, AccountInterface $current_user, FormBuilder $form_builder, Renderer $renderer) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->currentUser = $current_user;
    $this->formBuilder = $form_builder;
    $this->renderer = $renderer;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('current_user'),
      $container->get('form_builder'),
      $container->get('renderer')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $output = '';
    $uid = $this->currentUser->id();
    if ($uid) {
      $output = $this->currentUser->getUserName();
    }
    else {
      $form = $this->formBuilder->getForm('Drupal\user_info\Form\LoginForm');
      $output = $this->renderer->render($form);
    }

    $build = [
      '#markup' => $output,
      '#cache' => [
        'tags' => $this->getCacheTags(),
      ],
    ];

    return $build;
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheTags() {
    $uid = $this->currentUser->id();

    return Cache::mergeTags(
      parent::getCacheTags(),
      ['user:' . $uid]
    );
  }

}
