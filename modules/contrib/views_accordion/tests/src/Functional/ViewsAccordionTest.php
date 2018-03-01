<?php

namespace Drupal\Tests\views_accordion\Functional;

use Drupal\Tests\BrowserTestBase;

/**
 * Functional tests for the Views Accordion module.
 *
 * @group views_accordion
 */
class ViewsAccordionTest extends BrowserTestBase {

  /**
   * {@inheritdoc}
   */
  public static $modules = [
    'views_accordion_test',
    'views_ui',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    $user = $this->drupalCreateUser([
      'access administration pages',
      'administer views',
    ]);
    $this->drupalLogin($user);
  }

  /**
   * Tests Views Accordion functionality.
   */
  public function testViewsAccordion() {
    $this->drupalGet('admin/structure/views/view/views_accordion_test/edit');
    $this->assertResponse(200);
    $this->assertText('jQuery UI accordion');

    // Verify the style options show with the right values in the form.
    $this->drupalGet('admin/structure/views/nojs/display/views_accordion_test/page_1/style_options');
    $this->assertResponse(200);
    $this->assertNoFieldChecked('style_options[grouping][0][use-grouping-header]');
    $this->assertNoFieldChecked('style_options[disableifone]');
    $this->assertNoFieldChecked('style_options[collapsible]');
    $this->assertSession()->fieldValueEquals('style_options[animated]', 'none');
    $this->assertSession()->fieldValueEquals('style_options[animation_time]', '300');
    $this->assertSession()->fieldValueEquals('style_options[heightStyle]', 'auto');
    $this->assertSession()->fieldValueEquals('style_options[event]', 'click');
    $this->assertFieldChecked('style_options[use_header_icons]');
    $this->assertSession()->fieldValueEquals('style_options[icon_header]', 'ui-icon-triangle-1-e');
    $this->assertSession()->fieldValueEquals('style_options[icon_active_header]', 'ui-icon-triangle-1-s');
  }

}
