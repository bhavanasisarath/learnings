<?php

namespace Drupal\user_info\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\user\Entity\User;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\user\UserAuthInterface;

/**
 * Class UserRegistrationForm.
 *
 * @package Drupal\user_info\Form
 */
class LoginForm extends FormBase {

  /**
   * The user authentication object.
   *
   * @var \Drupal\user\UserAuthInterface
   */
  protected $userAuth;

  /**
   * Construct.
   *
   * @param \Drupal\user\UserAuthInterface $user_auth
   *   UserAuthInterface service.
   */
  public function __construct(UserAuthInterface $user_auth) {
    $this->userAuth = $user_auth;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('user.auth')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'user_login_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['username'] = [
      '#type' => 'textfield',
      '#title' => t('Email'),
      '#description' => t('Please enter email address'),
      '#required' => TRUE,
    ];

    $form['password'] = [
      '#type' => 'password',
      '#title' => t('Password'),
      '#description' => t('Please enter password'),
      '#required' => TRUE,
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => 'Login',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $username = $form_state->getValue('username');
    $password = $form_state->getValue('password');
    if (empty($username)) {
      $form_state->setErrorByName('username', t('Please enter email address.'));
    }
    elseif (!preg_match('/[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/', $username)) {
      $form_state->setErrorByName('username', t('Please enter valid email address.'));
    }

    if (empty($password)) {
      $form_state->setErrorByName('password', t('Please enter password.'));
    }

    if (!empty($username) && !empty($password)) {
      $account = user_load_by_mail($username);
      if ($account) {
        $name = $account->getAccountName();
        $uid = $this->userAuth->authenticate($name, $password);
        if (!$uid) {
          $form_state->setErrorByName('username', t('Please enter valid username and password to login.'));
        }
        else {
          if (!$account->isActive()) {
            $form_state->setErrorByName('username', t('User is not active. Please contact Administrator.'));
          }
          else {
            $form_state->setValue('name', $name);
          }
        }
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $username = $form_state->getValue('username');
    $password = $form_state->getValue('password');

    if (!empty($username) && !empty($password)) {
      $name = $form_state->getValue('name');
      $uid = $this->userAuth->authenticate($name, $password);
      if ($uid) {
        $user = User::load($uid);

        // Finalizes the login process and logs in a user.
        user_login_finalize($user);

        $form_state->setRedirect('<front>');
        return;
      }
    }
  }

}
