<?php

namespace Lphilippo\SlackLogging\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Monolog\Level;
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
                'value' => Level::Info,
            ],
            [
                'label' => __('Notice'),
                'value' => Level::Notice,
            ],
            [
                'label' => __('Warning'),
                'value' => Level::Warning,
            ],
            [
                'label' => __('Error'),
                'value' => Level::Error,
            ],
            [
                'label' => __('Critical'),
                'value' => Level::Critical,
            ],
            [
                'label' => __('Alert'),
                'value' => Level::Alert,
            ],
            [
                'label' => __('Emergency'),
                'value' => Level::Emergency,
            ],
        ];
    }
}
