<?php

namespace Lphilippo\SlackLogging\Model\Logger\Handler;

use Lphilippo\SlackLogging\Helper\EligibilityHelper;
use Lphilippo\SlackLogging\Model\Config;
use Monolog\Formatter\FormatterInterface;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\SlackWebhookHandler as SlackWebhookHandler;
use Magento\Framework\App\RequestInterface;

class Slack extends SlackWebhookHandler
{
    /**
     * @param Config $config
     * @param EligibilityHelper $eligibilityHelper
     * @param RequestInterface $request
     */
    public function __construct(
        protected Config $config,
        protected EligibilityHelper $eligibilityHelper,
        protected RequestInterface $request
    ) {
        parent::__construct(
            $this->config->getWebhookUrl(),
            null,
            sprintf(
                'Magento (%s)',
                $this->config->getServiceName()
            ),
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
}
