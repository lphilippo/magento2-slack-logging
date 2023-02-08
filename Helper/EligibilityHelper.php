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
     * @param array $record
     *
     * @return bool
     */
    public function canRecordBeIgnored(array $record): bool
    {
        $message = $record['message'];

        if (
            $this->config->ignoreSourceFileResolving() &&
            $this->messageMatchesFor($message, 'source_file_resolving')) {
            return true;
        }

        if (
            $this->config->ignoreEmptyLessCompilation() &&
            $this->messageMatchesFor($message, 'empty_less_compilation')) {
            return true;
        }

        if (
            $this->config->ignoreCachePurging() &&
            $this->messageMatchesFor($message, 'cache_purging')) {
            return true;
        }

        if (
            $this->config->ignoreGatherFileStats() &&
            $this->messageMatchesFor($message, 'gather_file_stats')) {
            return true;
        }

        return false;
    }

    /**
     * @param string $message
     * @param string $type
     *
     * @return bool
     */
    protected function messageMatchesFor(string $message, string $type): bool
    {
        if (!array_key_exists($type, $this->ignorePatterns)) {
            return false;
        }

        $pattern = $this->ignorePatterns[$type];

        if (preg_match($pattern, $message)) {
            return true;
        }

        return false;
    }
}
