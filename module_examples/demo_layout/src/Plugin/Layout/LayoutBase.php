<?php

declare(strict_types = 1);

namespace Drupal\demo_layout\Plugin\Layout;

use Drupal\demo_layout\DemoLayout;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Layout\LayoutDefault;

/**
 * Provides a layout base for custom layouts.
 */
abstract class LayoutBase extends LayoutDefault {

  /**
   * {@inheritdoc}
   */
  public function build(array $regions): array {
    $build = parent::build($regions);

    $columnWidth = $this->configuration['column_width'];
    if ($columnWidth) {
      $build['#attributes']['class'][] = 'demo-layout__row-width--' . $columnWidth;
    }

    $columnPaddingTop = $this->configuration['column_padding_top'];
    if ($columnPaddingTop !== 0) {
      $build['#attributes']['class'][] = 'demo-layout__row-padding-top--' . $columnPaddingTop;
    }

    $columnPaddingBottom = $this->configuration['column_padding_bottom'];
    if ($columnPaddingBottom !== 0) {
      $build['#attributes']['class'][] = 'demo-layout__row-padding-bottom--' . $columnPaddingBottom;
    }

    $class = $this->configuration['class'];
    if ($class) {
      $build['#attributes']['class'] = array_merge(
        explode(' ', $this->configuration['class']),
        $build['#attributes']['class']
      );
    }

    return $build;
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration(): array {
    return [
      'background_color' => DemoLayout::ROW_BACKGROUND_COLOR_NONE,
      'class' => NULL,
      'column_width' => $this->getDefaultColumnWidth(),
      'column_padding_top' => DemoLayout::ROW_TOP_PADDING_NONE,
      'column_padding_bottom' => DemoLayout::ROW_BOTTOM_PADDING_NONE,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state): array {

    $backgroundColorOptions = $this->getBackgroundColorOptions();
    $columnWidths = $this->getColumnWidths();
    $paddingTopOptions = $this->getPaddingTopOptions();
    $paddingBottomOptions = $this->getPaddingBottomOptions();

    $form['background'] = [
      '#type' => 'details',
      '#title' => $this->t('Background'),
      '#open' => $this->hasBackgroundSettings(),
      '#weight' => 20,
    ];

    $form['background']['background_color'] = [
      '#type' => 'radios',
      '#title' => $this->t('Background Color'),
      '#options' => $backgroundColorOptions,
      '#default_value' => $this->configuration['background_color'],
    ];

    if (!empty($columnWidths)) {
      $form['layout'] = [
        '#type' => 'details',
        '#title' => $this->t('Layout'),
        '#open' => TRUE,
        '#weight' => 30,
      ];

      $form['layout']['column_width'] = [
        '#type' => 'radios',
        '#title' => $this->t('Column Width'),
        '#options' => $columnWidths,
        '#default_value' => $this->configuration['column_width'],
        '#required' => TRUE,
      ];

      $form['layout']['column_padding_top'] = [
        '#type' => 'radios',
        '#title' => $this->t('Column Padding Top'),
        '#options' => $paddingTopOptions,
        '#default_value' => $this->configuration['column_padding_top'],
        '#required' => TRUE,
      ];

      $form['layout']['column_padding_bottom'] = [
        '#type' => 'radios',
        '#title' => $this->t('Column Padding Bottom'),
        '#options' => $paddingBottomOptions,
        '#default_value' => $this->configuration['column_padding_bottom'],
        '#required' => TRUE,
      ];
    }

    $form['extra'] = [
      '#type' => 'details',
      '#title' => $this->t('Extra'),
      '#open' => $this->hasExtraSettings(),
      '#weight' => 40,
    ];

    $form['extra']['class'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Custom Class'),
      '#description' => $this->t('Enter custom css classes for this row. Separate multiple classes by a space and do not include a period.'),
      '#default_value' => $this->configuration['class'],
      '#attributes' => [
        'placeholder' => 'class-one class-two',
      ],
    ];

    $form['#attached']['library'][] = 'demo_layout/layout_builder';

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateConfigurationForm(array &$form, FormStateInterface $form_state) {
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();

    $this->configuration['background_color'] = $values['background']['background_color'];
    $this->configuration['class'] = $values['extra']['class'];
    $this->configuration['column_width'] = $values['layout']['column_width'];
    $this->configuration['column_padding_top'] = $values['layout']['column_padding_top'];
    $this->configuration['column_padding_bottom'] = $values['layout']['column_padding_bottom'];
  }

  /**
   * Get the top padding options.
   *
   * @return array
   *   The top padding options.
   */
  protected function getPaddingTopOptions(): array {
    return [
      DemoLayout::ROW_TOP_PADDING_NONE => $this->t('None'),
      DemoLayout::ROW_TOP_PADDING_40 => $this->t('40px'),
      DemoLayout::ROW_TOP_PADDING_80 => $this->t('80px'),
    ];
  }

  /**
   * Get the bottom padding options.
   *
   * @return array
   *   The bottom padding options.
   */
  protected function getPaddingBottomOptions(): array {
    return [
      DemoLayout::ROW_BOTTOM_PADDING_NONE => $this->t('None'),
      DemoLayout::ROW_BOTTOM_PADDING_40 => $this->t('40px'),
      DemoLayout::ROW_BOTTOM_PADDING_80 => $this->t('80px'),
    ];
  }

  /**
   * Get the background color options.
   *
   * @return array
   *   The background color options.
   */
  protected function getBackgroundColorOptions(): array {
    return [
      DemoLayout::ROW_BACKGROUND_COLOR_NONE => $this->t('None'),
      DemoLayout::ROW_BACKGROUND_COLOR_RED => $this->t('Red'),
    ];
  }

  /**
   * Get the column widths.
   *
   * @return array
   *   The column widths.
   */
  abstract protected function getColumnWidths(): array;

  /**
   * Get the default column width.
   *
   * @return string
   *   The default column width.
   */
  abstract protected function getDefaultColumnWidth(): string;

  /**
   * Determine if this layout has background settings.
   *
   * @return bool
   *   If this layout has background settings.
   */
  protected function hasBackgroundSettings(): bool {
    if (!empty($this->configuration['background_color'])) {
      return TRUE;
    }
    else {
      return FALSE;
    }
  }

  /**
   * Determine if this layout has extra settings.
   *
   * @return bool
   *   If this layout has extra settings.
   */
  protected function hasExtraSettings(): bool {
    return $this->configuration['class'] || $this->configuration['hero'];
  }

}
