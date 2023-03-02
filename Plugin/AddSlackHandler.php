<?php

namespace Lphilippo\SlackLogging\Plugin;

use Lphilippo\SlackLogging\Model\Config;
use Lphilippo\SlackLogging\Model\Logger\Handler\Slack;
use Monolog\Logger;

class AddSlackHandler
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * @var Slack
     */
    protected $slackHandler;

    /**
     * @param Config $config
     * @param Slack $slackHandler
     */
    public function __construct(
        Config $config,
        Slack $slackHandler
    ) {
        $this->config = $config;
        $this->slackHandler = $slackHandler;
    }

    /**
     * @param Monolog $monolog
     *
     * @return Logger
     */
    public function afterSetHandlers(
        Logger $monolog
    ): Logger {
        if ($this->config->isDisabled()) {
            return;
        }

        $monolog->pushHandler(
            $this->slackHandler
        );

        return $monolog;
    }
}
