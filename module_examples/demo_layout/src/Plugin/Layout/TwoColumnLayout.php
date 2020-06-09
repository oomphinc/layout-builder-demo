<?php

declare(strict_types = 1);

namespace Drupal\demo_layout\Plugin\Layout;

use Drupal\demo_layout\DemoLayout;

/**
 * Provides a plugin class for two column layouts.
 */
final class TwoColumnLayout extends LayoutBase {

  /**
   * {@inheritdoc}
   */
  protected function getColumnWidths(): array {
    return [
      DemoLayout::ROW_WIDTH_25_75 => $this->t('25% / 75%'),
      DemoLayout::ROW_WIDTH_50_50 => $this->t('50% / 50%'),
      DemoLayout::ROW_WIDTH_75_25 => $this->t('75% / 25%'),
    ];
  }

  /**
   * {@inheritdoc}
   */
  protected function getDefaultColumnWidth(): string {
    return DemoLayout::ROW_WIDTH_50_50;
  }

}
