<?php

namespace Drupal\reservation\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class ReservationController extends ControllerBase {
  public function form() {
    $form = \Drupal::formBuilder()->getForm('Drupal\reservation\Form\ReservationForm');

    // Assemble the markup.
    $build = array(
      '#markup' => $this->t('<p>The Page example module provides two pages, "simple" and "arguments".</p><p>The @simple_link just returns a renderable array for display.</p><p>The @arguments_link takes two arguments and displays them, as in @arguments_url</p>',
        array(
          '@simple_link' => 'anc',
          '@arguments_link' => 'bca',
          '@arguments_url' => 'whatever',
        )
      ),
    );

    $build = array(
      '#markup' => render($form),
      '#type' => 'markup',
    );

    

    return $build;
  }
}
