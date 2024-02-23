<?php

namespace Lphilippo\SlackLogging\Plugin;

use Lphilippo\SlackLogging\Model\Config;
use Lphilippo\SlackLogging\Model\Logger\Handler\Slack;
use Monolog\Logger;

final class AddSlackHandler
{
    /**
     * @param Config $config
     * @param Slack $slackHandler
     */
    public function __construct(
        protected Config $config,
        protected Slack $slackHandler
    ) {
    }

    /**
     * @param Monolog $monolog
     *
     * @return Logger
     */
    public function afterSetHandlers(
        Logger $monolog
    ): Logger {
        if (!$this->config->isDisabled()) {
            $monolog->pushHandler(
                $this->slackHandler
            );
        }

        return $monolog;
    }
}
