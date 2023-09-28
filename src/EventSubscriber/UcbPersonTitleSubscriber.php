<?php

namespace Drupal\ucb_person_title\EventSubscriber;

use Drupal\core_event_dispatcher\Event\Form\FormAlterEvent;
use Drupal\core_event_dispatcher\FormHookEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class UcbPersonTitleSubscriber.
 *
 * 
 * "event_subscriber" needs to be in the *.services.yml:
 * like so...
 * services:
 *   ucb_person_title.event_subscriber:
 *     class: Drupal\ucb_person_title\EventSubscriber\UcbPersonTitleSubscriber
 *     tags:
 *       - { name: event_subscriber }
 */
class UcbPersonTitleSubscriber implements EventSubscriberInterface {

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents(): array {
    return [
      FormHookEvents::FORM_ALTER => 'alterUcbPersonForm',
    ];
  }

  /**
   * Alter ucb_person form.
   *
   * @param \Drupal\core_event_dispatcher\Event\Form\FormAlterEvent $event
   *   The form alter event.
   */
  public function alterUcbPersonForm(FormAlterEvent $event): void {
    $form = &$event->getForm();
    $form_id = $event->getFormId();

    if ($form_id === 'node_ucb_person_form' || $form_id === 'node_ucb_person_edit_form') {
      $form['title']['#access'] = FALSE;
      $form['#entity_builders'][] = 'Drupal\ucb_person_title\EventSubscriber\UcbPersonTitleSubscriber::ucbPersonTitleBuilder';
    }
  }

  /**
   * Custom entity builder for ucb_person.
   *
   * @param string $entity_type
   *   The entity type.
   * @param \Drupal\node\Entity\Node $node
   *   The node entity.
   * @param array $form
   *   The form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The form state.
   */
  public static function ucbPersonTitleBuilder(string $entity_type, $node, array &$form, FormStateInterface $form_state): void {
    $last = $node->get('field_ucb_person_last_name')->value;
    $first = $node->get('field_ucb_person_first_name')->value;
    $node->setTitle($first . ' ' . $last);
  }
}
