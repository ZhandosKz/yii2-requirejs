<?php

namespace app\extensions\requirejs;

use Yii;
use yii\helpers\Html;

class View extends \yii\web\View
{
    /**
     * @inheritdoc
     */
    protected function renderHeadHtml()
    {
        $lines = [];
        if (!empty($this->metaTags)) {
            $lines[] = implode("\n", $this->metaTags);
        }

        if (!empty($this->linkTags)) {
            $lines[] = implode("\n", $this->linkTags);
        }
        if (!empty($this->cssFiles)) {
            $lines[] = implode("\n", $this->cssFiles);
        }
        if (!empty($this->css)) {
            $lines[] = implode("\n", $this->css);
        }
        if (!empty($this->jsFiles[self::POS_HEAD])) {
            $lines[] = implode("\n", $this->jsFiles[self::POS_HEAD]);
        }
        if (!empty($this->js[self::POS_HEAD])) {
            $lines[] = Html::script($this->wrapInlineJsCode(implode("\n", $this->js[self::POS_HEAD])), ['type' => 'text/javascript']);
        }

        return empty($lines) ? '' : implode("\n", $lines);
    }

    /**
     * @inheritdoc
     */
    protected function renderBodyBeginHtml()
    {
        $lines = [];
        if (!empty($this->jsFiles[self::POS_BEGIN])) {
            $lines[] = implode("\n", $this->jsFiles[self::POS_BEGIN]);
        }
        if (!empty($this->js[self::POS_BEGIN])) {
            $lines[] = Html::script($this->wrapInlineJsCode(implode("\n", $this->js[self::POS_BEGIN])), ['type' => 'text/javascript']);
        }

        return empty($lines) ? '' : implode("\n", $lines);
    }

    /**
     * @inheritdoc
     */
    protected function renderBodyEndHtml($ajaxMode)
    {
        $lines = [];

        if (!empty($this->jsFiles[self::POS_END])) {
            $lines[] = implode("\n", $this->jsFiles[self::POS_END]);
        }

        if ($ajaxMode) {
            $scripts = [];
            if (!empty($this->js[self::POS_END])) {
                $scripts[] = implode("\n", $this->js[self::POS_END]);
            }
            if (!empty($this->js[self::POS_READY])) {
                $scripts[] = implode("\n", $this->js[self::POS_READY]);
            }
            if (!empty($this->js[self::POS_LOAD])) {
                $scripts[] = implode("\n", $this->js[self::POS_LOAD]);
            }
            if (!empty($scripts)) {
                $lines[] = Html::script($this->wrapInlineJsCode(implode("\n", $scripts)), ['type' => 'text/javascript']);
            }
        } else {
            if (!empty($this->js[self::POS_END])) {
                $lines[] = Html::script($this->wrapInlineJsCode(implode("\n", $this->js[self::POS_END])), ['type' => 'text/javascript']);
            }
            if (!empty($this->js[self::POS_READY])) {
                $js = "jQuery(document).ready(function () {\n" . implode("\n", $this->js[self::POS_READY]) . "\n});";
                $lines[] = Html::script($this->wrapInlineJsCode($js), ['type' => 'text/javascript']);
            }
            if (!empty($this->js[self::POS_LOAD])) {
                $js = "jQuery(window).on('load', function () {\n" . implode("\n", $this->js[self::POS_LOAD]) . "\n});";
                $lines[] = Html::script($this->wrapInlineJsCode($js), ['type' => 'text/javascript']);
            }
        }

        return empty($lines) ? '' : implode("\n", $lines);
    }

    private function wrapInlineJsCode($code)
    {
        /**
         * @var RequireJs $component
         */
        $component = Yii::$app->get('requireJs');

        return "{$component->jsLoadWatcherObjectName}.on(function() { $code } );";
    }
}