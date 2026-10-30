<?php
namespace SkepticSpriggan;

use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;

class SimpleRadio extends Widget
{
    /** @var string Field name that is sent in the POST body */
    public $name = 'value';

    /** @var string|null The currently selected value */
    public $value = null;

    /**
     * @var array Radio items. Either:
     *  - ['value' => ['label' => 'IP', 'url' => ['user/login-ip'], ...], ...]
     *  - ['value' => 'IP', ...]   (requires $changeUrl)
     */
    public $items = [];

    /**
     * @var string|array|null Fallback URL when items do not define their own.
     */
    public $changeUrl = null;

    /** @var string 'text' or 'html' */
    public $format = 'text';

    /** @var string Default icon for the selected item */
    public $checkedIcon = 'fa fa-check-circle';

    /** @var string Default icon for unselected items */
    public $uncheckedIcon = 'fa fa-circle-o';

    /** @var string Font Awesome size class */
    public $iconSize = 'fa-2x';

    /** @var string Default color class for the selected item */
    public $checkedColor = 'text-success';

    /** @var string Default color class for unselected items */
    public $uncheckedColor = 'text-secondary';

    /** @var array HTML options for the container */
    public $options = [];

    public function init()
    {
        parent::init();

        if (empty($this->items)) {
            throw new InvalidConfigException('The "items" property must be set.');
        }

        RadioAsset::register($this->view);

        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->getId();
        }

        Html::addCssClass($this->options, 'yii2-radio');
    }

    public function run()
    {
        $this->options['data-radio-name']  = $this->name;
        $this->options['data-radio-value'] = (string) $this->value;
        $this->options['role'] = 'radiogroup';

        $itemsHtml = [];
        foreach ($this->items as $value => $item) {
            $itemsHtml[] = $this->renderItem($value, $item);
        }

        return Html::tag('div', implode("\n", $itemsHtml), $this->options);
    }

    protected function renderItem($value, $item): string
    {
        if (is_string($item)) {
            $item = ['label' => $item];
        }

        $label = $item['label'] ?? '';
        $url = $item['url'] ?? $this->changeUrl;
        $checkedIcon = $item['checkedIcon'] ?? $this->checkedIcon;
        $uncheckedIcon = $item['uncheckedIcon'] ?? $this->uncheckedIcon;
        $iconSize = $item['iconSize'] ?? $this->iconSize;
        $checkedColor = $item['checkedColor'] ?? $this->checkedColor;
        $uncheckedColor = $item['uncheckedColor'] ?? $this->uncheckedColor;

        if (empty($url)) {
            throw new InvalidConfigException(
                "No URL set for radio item '{$value}'. Provide 'url' per item or set 'changeUrl'."
            );
        }

        $isChecked = (string) $value === (string) $this->value;
        $icon = $isChecked ? $checkedIcon : $uncheckedIcon;
        $color = $isChecked ? $checkedColor : $uncheckedColor;

        $toggle = Html::a(
            Html::tag('i', '', [
                'class' => "{$icon} {$iconSize} {$color} align-middle",
            ]),
            Url::to($url),
            [
                'class' => 'simple-radio' . ($isChecked ? ' radio-checked' : ''),
                'data-checked' => $isChecked ? 'true' : 'false',
                'data-value' => $value,
                'data-url' => Url::to($url),
                'data-checked-icon' => $checkedIcon,
                'data-unchecked-icon' => $uncheckedIcon,
                'data-checked-color' => $checkedColor,
                'data-unchecked-color' => $uncheckedColor,
                'data-icon-size' => $iconSize,
                'role' => 'radio',
                'aria-checked' => $isChecked ? 'true' : 'false',
            ]
        );

        $labelHtml = '';
        if ($label !== '') {
            $text = $this->format === 'html' ? $label : Html::encode($label);
            $labelHtml = Html::tag('span', $text, ['class' => 'radio-label']);
        }

        return Html::tag('div', "{$toggle} {$labelHtml}", ['class' => 'yii2-radio-item']);
    }
}
