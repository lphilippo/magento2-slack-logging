<?php

namespace Lphilippo\SlackLogging\Model\Logger\Handler;

use Lphilippo\SlackLogging\Helper\EligibilityHelper;
use Lphilippo\SlackLogging\Model\Config;
use Magento\Framework\App\State as AppState;
use Monolog\Handler\SlackWebhookHandler as SlackWebhookHandler;

class Slack extends SlackWebhookHandler
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * @var EligibilityHelper
     */
    protected $eligibilityHelper;

    /**
     * @param AppState $appState
     * @param Config $config
     * @param EligibilityHelper $eligibilityHelper
     */
    public function __construct(
        AppState $appState,
        Config $config,
        EligibilityHelper $eligibilityHelper
    ) {
        $this->appState = $appState;
        $this->config = $config;
        $this->eligibilityHelper = $eligibilityHelper;

        parent::__construct(
            $this->config->getWebhookUrl(),
            null,
            $this->getUserName($appState),
            true,
            null,
            true,
            true,
            $this->config->getLogLevel()
        );
    }

     /**
      * {@inheritdoc}
      */
    protected function write(array $record): void
    {
        if ($this->config->isDisabled()) {
            return;
        }

        if ($this->eligibilityHelper->canRecordBeIgnored($record)) {
            return;
        }

        if ($this->config->isContextIncluded()) {
            $record['extra'] = [];
            $record['context'] = $this->getContext();
        }

        parent::write($record);
    }

    /**
     * @return array
     */
    protected function getContext(): array
    {
        return array_filter([
            'command' => array_key_exists('argv', $_SERVER) ? implode(' ', $_SERVER['argv']) : null,
            'host' => array_key_exists('HTTP_HOST', $_SERVER) ? $_SERVER['HTTP_HOST'] : null,
            'method' => array_key_exists('REQUEST_METHOD', $_SERVER) ? $_SERVER['REQUEST_METHOD'] : null,
            'uri' => array_key_exists('REQUEST_URI', $_SERVER) ? $_SERVER['REQUEST_URI'] : null,
        ]);
    }

    /**
     * @param AppState $appState
     *
     * @return string
     */
    protected function getUserName(AppState $appState): string
    {
        if ($appState->getMode() === AppState::MODE_DEVELOPER) {
            return 'Magento (Developer)';
        }

        if ($appState->getMode() === AppState::MODE_DEFAULT) {
            return 'Magento (Default)';
        }

        return 'Magento (Production)';
    }
}
