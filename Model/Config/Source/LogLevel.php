<?php

namespace Lphilippo\SlackLogging\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Monolog\Logger;

final class LogLevel implements OptionSourceInterface
{
    /**
     * @return array
     */
    public function toOptionArray(): array
    {
        return [
            [
                'label' => __('Info'),
                'value' => Logger::INFO,
            ],
            [
                'label' => __('Notice'),
                'value' => Logger::NOTICE,
            ],
            [
                'label' => __('Warning'),
                'value' => Logger::WARNING,
            ],
            [
                'label' => __('Error'),
                'value' => Logger::ERROR,
            ],
            [
                'label' => __('Critical'),
                'value' => Logger::CRITICAL,
            ],
            [
                'label' => __('Alert'),
                'value' => Logger::ALERT,
            ],
            [
                'label' => __('Emergency'),
                'value' => Logger::EMERGENCY,
            ],
        ];
    }
}
