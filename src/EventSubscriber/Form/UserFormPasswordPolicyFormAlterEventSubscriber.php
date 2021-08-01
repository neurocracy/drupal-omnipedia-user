<?php

namespace Drupal\omnipedia_user\EventSubscriber\Form;

use Drupal\core_event_dispatcher\Event\Form\FormIdAlterEvent;
use Drupal\Core\Form\FormStateInterface;
use Drupal\hook_event_dispatcher\HookEventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Event subscriber to alter Password Policy elements on the user edit form.
 *
 * This makes the following changes to the Password Policy form element on the
 * user edit form:
 *
 * - The table is removed if there are no policies applied to the user.
 *
 * @see \password_policy_form_user_form_alter()
 *   Password Policy table generated in this hook.
 */
class UserFormPasswordPolicyFormAlterEventSubscriber implements EventSubscriberInterface {

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents(): array {
    return [
      HookEventDispatcherInterface::PREFIX . 'form_user_form.alter' =>
        'onFormAlter',
    ];
  }

  /**
   * Alter the 'user_form' form.
   *
   * @param \Drupal\core_event_dispatcher\Event\Form\FormIdAlterEvent $event
   *   The event object.
   */
  public function onFormAlter(FormIdAlterEvent $event): void {

    /** @var array */
    $form = &$event->getForm();

    // Since the Password Policy module sets its weight as heavier than default,
    // its form alter hook runs later than most so the table would not have been
    // added by this point. To work around that, we add a #process callback,
    // which gets invoked after all the form alters have run.
    $form['#process'][] = [$this, 'onProcess'];

  }

  /**
   * Process callback for the form.
   *
   * @param array $form
   *   The complete form.
   *
   * @param \Drupal\Core\Form\FormStateInterface $formState
   *   The form state.
   *
   * @return array
   *   The $form parameter with our alterations.
   */
  public function onProcess(
    array $form, FormStateInterface $formState
  ): array {

    if (
      isset($form['account']['password_policy_status']['#rows']) &&
      empty($form['account']['password_policy_status']['#rows'])
    ) {
      $form['account']['password_policy_status']['#access'] = false;
    }

    return $form;

  }

}
