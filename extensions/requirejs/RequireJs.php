<?php

namespace app\extensions\requirejs;

use yii\base;
use yii\helpers\Html;

class RequireJs extends base\Component
{
    public $jsLoadCheckDomElement;
    public $jsLoadWatcherObjectName = 'window.requireJsCoreModulesLoad';
    public $resourcesUrl;

    public $defaultCss;
    public $defaultEntry;

    private $entry;
    private $css = [];

    public function registerEntry($entry)
    {
        $this->entry = $entry;

        return $this;
    }

    public function registerCss($css, $reset = false)
    {
        if ($reset) {
            $this->css = [];
        }
        $this->css[] = $css;

        return $this;
    }

    public function head()
    {
        $css = empty($this->css) && $this->defaultCss ? [$this->defaultCss] : $this->css;

        foreach ($css as $c) {
            echo Html::cssFile("{$this->resourcesUrl}/$c") . "\n";
        }

        $configurationCode = "\nwindow.require = {baseUrl: '{$this->resourcesUrl}'};";
        if ($this->jsLoadWatcherObjectName) {
            $configurationCode .= "\n{$this->coreModulesLoadingWatcher()}";
        }
        echo Html::script($configurationCode, [
            'type' => 'text/javascript',
        ]);
    }

    public function endBody()
    {
        $entry = $this->entry ? : $this->defaultEntry;
        if (!$entry) {
            throw new base\InvalidConfigException("RequireJs entry script is undefined");
        }

        echo Html::script('', [
            'data' => [
                'main' => $entry,
            ],
            'type' => 'text/javascript',
            'src' => "{$this->resourcesUrl}/require.js",
        ]) . "\n";
    }

    private function coreModulesLoadingWatcher()
    {
        if (!$this->jsLoadCheckDomElement) {
            throw new base\InvalidConfigException("DOM element for raise event is undefined");
        }
        return <<<EOD
{$this->jsLoadWatcherObjectName} = {
    eventName: "requirejs-loaded",
    eventRaised: false,
    bodyElemName: "{$this->jsLoadCheckDomElement}",
    loaded: function() {
        if (this.eventRaised) {
            return;
        }
        var elem = document.getElementsByClassName(this.bodyElemName)[0]
        var event;
        if (document.createEvent) {
            event = document.createEvent("HTMLEvents");
            event.initEvent(this.eventName, true, true);
        } else {
            event = document.createEventObject();
            event.eventType = this.eventName;
        }
        event.eventName = this.eventName;
        if (document.createEvent) {
            elem.dispatchEvent(event);
        } else {
            elem.fireEvent("on" + event.eventType, event);
        }
        this.eventRaised = true;
    },
    on: function(callback) {
        var elem = document.getElementsByClassName(this.bodyElemName)[0]
        elem.addEventListener(this.eventName, callback);
    }
};
EOD;
    }
}