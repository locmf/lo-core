<?php

namespace lo\core\widgets\bootstrap;

use yii\base\InvalidConfigException;
use yii\bootstrap\Collapse as YiiCollapse;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * Class Panel
 * @package lo\core\bootstrap\widgets
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class Collapse extends YiiCollapse
{
    /**
     * Renders a single collapsible item group
     * @param string $header a label of the item group [[items]]
     * @param array $item a single item from [[items]]
     * @param integer $index the item index as each item group content must have an id
     * @return string the rendering result
     * @throws InvalidConfigException
     */
    public function renderItem($header, $item, $index)
    {
        if (array_key_exists('content', $item)) {
            $id = $this->options['id'] . '-collapse' . $index;
            $options = ArrayHelper::getValue($item, 'contentOptions', []);
            $options['id'] = $id;
            Html::addCssClass($options, ['widget' => 'panel-collapse', 'collapse' => 'collapse']);

            $encodeLabel = isset($item['encode']) ? $item['encode'] : $this->encodeLabels;
            if ($encodeLabel) {
                $header = Html::encode($header);
            }

            $leftTools = ArrayHelper::getValue($item, 'leftTools');
            $rightTools = ArrayHelper::getValue($item, 'rightTools');

            if ($leftTools) {
                $leftTools = Html::tag('div', $leftTools, [
                    'class' => 'pull-left',
                    'style' => 'margin-right:5px;'
                ]);
            }

            if ($rightTools) {
                $rightTools = Html::tag('div', $rightTools, [
                    'class' => 'pull-right',
                    'style' => 'margin-left:5px;'
                ]);
            }

            $headerToggle = Html::a($header, '#' . $id, [
                    'class' => 'collapse-toggle',
                    'data-toggle' => 'collapse',
                    'data-parent' => '#' . $this->options['id']
                ]) . "\n";

            $header = $leftTools . Html::tag('h4', $headerToggle, ['class' => 'panel-title']) . $rightTools;

            if (is_string($item['content']) || is_numeric($item['content']) || is_object($item['content'])) {
                $content = Html::tag('div', $item['content'], ['class' => 'panel-body']) . "\n";
            } elseif (is_array($item['content'])) {
                $content = Html::ul($item['content'], [
                        'class' => 'list-group',
                        'itemOptions' => [
                            'class' => 'list-group-item'
                        ],
                        'encode' => false,
                    ]) . "\n";
                if (isset($item['footer'])) {
                    $content .= Html::tag('div', $item['footer'], ['class' => 'panel-footer']) . "\n";
                }
            } else {
                throw new InvalidConfigException('The "content" option should be a string, array or object.');
            }
        } else {
            throw new InvalidConfigException('The "content" option is required.');
        }
        $group = [];

        $group[] = Html::tag('div', $header, ['class' => 'panel-heading']);
        $group[] = Html::tag('div', $content, $options);

        return implode("\n", $group);
    }
}
