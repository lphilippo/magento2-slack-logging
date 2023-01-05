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
            false,
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

        parent::write($record);
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
