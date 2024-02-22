<?php

namespace Lphilippo\SlackLogging\Model\Logger\Handler;

use Lphilippo\SlackLogging\Helper\EligibilityHelper;
use Lphilippo\SlackLogging\Model\Config;
use Magento\Framework\App\State as AppState;
use Monolog\Formatter\FormatterInterface;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\SlackWebhookHandler as SlackWebhookHandler;
use Magento\Framework\App\RequestInterface;

class Slack extends SlackWebhookHandler
{
    /**
     * @param AppState $appState
     * @param Config $config
     * @param EligibilityHelper $eligibilityHelper
     * @param RequestInterface $request
     */
    public function __construct(
        protected AppState $appState,
        protected Config $config,
        protected EligibilityHelper $eligibilityHelper,
        protected RequestInterface $request
    ) {
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

    public function setFormatter(FormatterInterface $formatter): HandlerInterface
    {
        return parent::setFormatter(
            $formatter->includeStacktraces(true)
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function write(array $record): void
    {
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
    public function getContext(): array
    {
        return array_filter([
            'client_ip' => $this->request->getClientIp(),
            'command' => array_key_exists('argv', $_SERVER) ? implode(' ', $_SERVER['argv']) : null,
            'host' => $this->request->getHttpHost(),
            'method' => array_key_exists('REQUEST_METHOD', $_SERVER) ? $_SERVER['REQUEST_METHOD'] : null,
            'uri' => $this->request->getRequestUri(),
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
