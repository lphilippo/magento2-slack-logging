<?php

namespace Lphilippo\SlackLogging\Plugin;

use Lphilippo\SlackLogging\Model\Logger\Handler\Slack;
use Magento\Framework\Logger\Monolog;

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
     * @return Monolog
     */
    public function afterSetHandlers(
        Monolog $monolog
    ): Monolog {
        $monolog->pushHandler(
            $this->slackHandler
        );

        return $monolog;
    }
}
