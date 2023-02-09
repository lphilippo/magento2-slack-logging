<?php

namespace Lphilippo\SlackLogging\Helper;

use Lphilippo\SlackLogging\Model\Config;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;

class EligibilityHelper extends AbstractHelper
{
    protected $ignorePatterns = [
        'cache_purging' => '/No cache server\(s\) could be purged/i',
        'empty_less_compilation' => '/Compilation from source: LESS file is empty/i',
        'gather_file_stats' => "/FileSystemException: Cannot gather stats! Warning!stat\(\): stat failed for /i",
        'source_file_resolving' => '/Unable to resolve the source file for \'(.*)\'/i',
    ];

    /**
     * @var Config
     */
    private $config;

    /**
     * @param Config $config
     * @param Context $context
     */
    public function __construct(
        Config $config,
        Context $context
    ) {
        $this->config = $config;

        parent::__construct($context);
    }

    /**
     * Return the patterns that will be used to determine eligibility.
     *
     * @return array
     */
    public function getIgnoreExceptionPatterns(): array
    {
        $patterns = [];

        foreach ($this->ignorePatterns as $key => $pattern) {
            $methodName = 'ignore' . str_replace('_', '', ucwords($key, '_'));

            if (!$this->config->$methodName()) {
                continue;
            }

            $patterns[$key] = $pattern;
        }

        return $patterns;
    }

    /**
     * @param array $record
     *
     * @return bool
     */
    public function canRecordBeIgnored(array $record): bool
    {
        $message = $record['message'];
        $patterns = $this->getIgnoreExceptionPatterns();

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $message)) {
                return true;
            }
        }

        return false;
    }
}
