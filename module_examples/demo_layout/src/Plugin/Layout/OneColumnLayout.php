<?php

declare(strict_types = 1);

namespace Drupal\demo_layout\Plugin\Layout;

use Drupal\demo_layout\DemoLayout;

/**
 * Provides a plugin class for a one column layout.
 */
final class OneColumnLayout extends LayoutBase {

  /**
   * {@inheritdoc}
   */
  protected function getColumnWidths(): array {
    return [
      DemoLayout::ROW_WIDTH_25 => $this->t('25%'),
      DemoLayout::ROW_WIDTH_50 => $this->t('50%'),
      DemoLayout::ROW_WIDTH_75 => $this->t('75%'),
      DemoLayout::ROW_WIDTH_100 => $this->t('100%'),
    ];
  }

  /**
   * {@inheritdoc}
   */
  protected function getDefaultColumnWidth(): string {
    return DemoLayout::ROW_WIDTH_100;
  }

}
