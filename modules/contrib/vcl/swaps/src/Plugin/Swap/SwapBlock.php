<?php

/**
 * @file
 * Contains \Drupal\vcl\Plugin\Swap\.
 */

namespace Drupal\swaps\Plugin\Swap;

use Drupal\swaps\SwapBase;
use Drupal\block\Entity\Block as EntityBlock;

/**
 * Provides a 'Block' swap.
 *
 * @Swap(
 *   id = "swap_block",
 *   name = "Block",
 *   description = @Translation("Insert a block."),
 *   container = false
 * )
 */
class SwapBlock extends SwapBase {

  /**
   * Get all attributes of the swap and validate it.
   */
  public function processCallback($attrs, $text) {
    $attrs = $this->setAttrs(array(
      'blockid' => '',
      'blocktype' => '',
    ),
      $attrs
    );

    $attrs['class'] = $this->addClass($this->validateKey($attrs,'class'), 'vcl_block');
    $attrs['class'] = $this->addClass($this->validateKey($attrs,'class'), $this->validateKey($attrs,'extraclass'));
    $attrs['style'] = $this->getStyle($attrs);
    $this->getBlockType($attrs);

    return $this->theme($attrs, $text);
  }

  /**
   * Validate if the block is view, custom or normal block.
   */
  public function getBlockType(&$attrs) {

    $block_attr = explode(":", $attrs['blockid']);

    if (count($block_attr) > 1) {
      $attrs['blocktype'] = $block_attr[0];
      $attrs['blockid'] = $block_attr[1];
    }
    else {
      $attrs['blocktype'] = 'block';
    }

  }

  /**
   * Create the string of the swap.
   */
  public function theme($attrs, $text) {

    switch ($attrs['blocktype']) {
      case 'block':
        $block = EntityBlock::load($attrs['blockid']);
        $block_content = \Drupal::entityManager()
          ->getViewBuilder($attrs['blocktype'])->view($block);
        break;

      case 'block_content':
        $block = \Drupal::entityManager()
          ->loadEntityByUuid($attrs['blocktype'], $attrs['blockid']);
        $block_content = \Drupal::entityManager()
          ->getViewBuilder($attrs['blocktype'])->view($block);
        break;

      case 'views_block':
        $view_attr = explode("-", $attrs['blockid']);
        $view = \Drupal\views\Views::getView($view_attr[0]);

        // Validate if user can access to the view.
        if ($view->access($view_attr[1], \Drupal::currentUser())) {
          $block_content = $view->render($view_attr[1]);
          break;
        }
        else {
          return "<h2>You dont have permission to see this view</h2>";
        }
    }

    // Validate exists id.
    $id = ($this->validateKey($attrs,'id')	 != '') ? ' id="' . $attrs['id'] . '"' : "";

    $output = '<div' . $id . ' class="' . $attrs['class'] . '" '
      . $attrs['style'] . '>' . render($block_content) . '</div>';

    return $output;

  }

}
