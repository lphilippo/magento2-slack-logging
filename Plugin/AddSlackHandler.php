<?php

namespace Lphilippo\SlackLogging\Plugin;

use Lphilippo\SlackLogging\Model\Logger\Handler\Slack;
use Monolog\Logger;

class AddSlackHandler
{
    /**
     * @var Slack
     */
    protected $slackHandler;

    /**
     * @param Slack $slackHandler
     */
    public function __construct(
        Slack $slackHandler
    ) {
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
        $monolog->pushHandler(
            $this->slackHandler
        );

        return $monolog;
    }
}
