<?php 

namespace Drupal\reservation\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\ChangedCommand;
use Drupal\Core\Ajax\CssCommand;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Ajax\InvokeCommand;

class ReservationForm extends FormBase {
  
  public function getFormId() {
    return 'reservation_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['phone_number'] = array(
      '#type' => 'tel',
      '#title' => $this->t('Your phone number'),
    );
    /* NUMBER 1 */
    /* Working example. This field works on change. Wrapper to change is #dupa. Method called is validateValue */
    $form['color'] = array(
      '#type' => 'select',
      '#title' => $this->t('Select desired color'),
      '#options' => array($this->t('Red'), $this->t('Green'), $this->t('Blue')),
      '#ajax' => array(
        'callback' => '\Drupal\reservation\Form\ReservationForm::validateValue',
        'event' => 'change',
        'wrapper' => 'dupa',
        'progress' => array(
          'type' => 'throbber',
          'message' => t('Verifying email...'),
        ),
      ),
    );

    /* NUMBER 1 */
    /* This is wrapper that change the content. */
    $form['dupa'] = array(
      '#title' => $this->t('Dupa'),
      '#type' => 'container',
      '#prefix' => '<div id="dupa">',
      '#suffix' => '</div>',
    );


    /* NUMBER 2 */
    $form['month'] = array(
      '#type' => 'select',
      '#title' => $this->t('Select month'),
      '#options' => array($this->t('January'), $this->t('February'), $this->t('March')),
      '#ajax' => array(
        'callback' => array($this, 'extraField'),
        'event' => 'change',
        'wrapper' => 'week-day',
      ),
    );

    // Disable caching on this form.
    $form_state->setCached(FALSE);

    $form['week_day'] = [
      '#type' => 'container',
      '#attributes' => ['id' => 'week-day'],
    ];
    ksm($form_state);
    /* END OF NUMBER 2 */

    $form['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    );

    return $form;
  }

  public function validateForm(array &$form, FormStateInterface $form_state) {
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    drupal_set_message(t('whatever'), 'error');
  }

  /* NUMBER 1 */
  /* Working example response*/
  public function validateValue(array &$form, FormStateInterface $form_state) {
    $response = new AjaxResponse();
    $message = 'Your phone number is ' . $form_state->getValue(['color']);
    $response->addCommand(new HtmlCommand('#dupa', $message));
    return $response;
  }

  /* NUMBER 2 */
  /* Adding extra fields to form. It's different from d7, because you create a field in callback method. */
  /* Creating ajax property on ajax generated form item doesn't work. I tried adding an ajax to $form['week_day']['hour']
    element, but it didn't work. */
  public function extraField(array &$form, FormStateInterface $form_state) {
    $month = $form_state->getValue('month');

    $form['week_day']['day'] = array(
      '#type' => 'select',
      '#title' => $this->t('Day'),
      '#options' => array('M', 'T', 'W', 'T', 'F', 'S', 'S'),
    );

    $hour_options = array();

    if ($month == '0') {
      $hour_options[0] = $this->t('08:00');
      $hour_options[1] = $this->t('09:00');
      $hour_options[2] = $this->t('10:00');
    }

    if ($month == '1') {
      $hour_options[0] = $this->t('12:00');
      $hour_options[1] = $this->t('13:00');
      $hour_options[2] = $this->t('14:00');
    }

    if ($month == '2') {
      $hour_options[0] = $this->t('18:00');
      $hour_options[1] = $this->t('19:00');
      $hour_options[2] = $this->t('20:00');
    }

    $form['week_day']['hour'] = array(
      '#type' => 'select',
      '#title' => $this->t('Choose hours @month', array('@month' => $month)),
      '#options' => $hour_options,
    );

    return $form['week_day'];
  }
}