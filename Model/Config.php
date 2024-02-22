<?php

namespace Lphilippo\SlackLogging\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;

class Config
{
    public const PATH_PREFIX = 'lphilippo_slack_logging';

    public const PATH_INCLUDE_CONTEXT = 'settings/include_context';
    public const PATH_IS_ENABLED = 'settings/enabled';
    public const PATH_LOG_LEVEL = 'settings/log_level';
    public const PATH_WEBHOOK_URL = 'settings/webhook_url';

    public const PATH_IGNORE_CACHE_PURGING = 'ignore/cache_purging';
    public const PATH_IGNORE_EMPTY_LESS_COMPILATION = 'ignore/empty_less_compilation';
    public const PATH_IGNORE_GATHER_FILE_STATS = 'ignore/gather_file_stats';
    public const PATH_IGNORE_SOURCE_FILE_RESOLVING = 'ignore/source_file_resolving';

    /**
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        private ScopeConfigInterface $scopeConfig
    ) {
    }

     /**
      * @param string $path
      *
      * @return mixed
      */
    protected function getValue(string $path)
    {
        return $this->scopeConfig->getValue(self::PATH_PREFIX . '/' . $path);
    }

    /**
     * @return bool
     */
    public function ignoreCachePurging(): bool
    {
        return (bool) $this->getValue(self::PATH_IGNORE_CACHE_PURGING);
    }

    /**
     * @return bool
     */
    public function ignoreEmptyLessCompilation(): bool
    {
        return (bool) $this->getValue(self::PATH_IGNORE_EMPTY_LESS_COMPILATION);
    }

    /**
     * @return bool
     */
    public function ignoreGatherFileStats(): bool
    {
        return (bool) $this->getValue(self::PATH_IGNORE_GATHER_FILE_STATS);
    }

     /**
      * @return bool
      */
    public function ignoreSourceFileResolving(): bool
    {
        return (bool) $this->getValue(self::PATH_IGNORE_SOURCE_FILE_RESOLVING);
    }

    /**
     * @return bool
     */
    public function isContextIncluded(): bool
    {
        return (bool) $this->getValue(self::PATH_INCLUDE_CONTEXT);
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return (bool) $this->getValue(self::PATH_IS_ENABLED);
    }

    /**
     * @return bool
     */
    public function isDisabled(): bool
    {
        return !$this->isEnabled();
    }

    /**
     * @return int
     */
    public function getLogLevel(): int
    {
        return (int) $this->getValue(self::PATH_LOG_LEVEL);
    }

    /**
     * @return string
     */
    public function getWebhookUrl(): string
    {
        return (string) $this->getValue(self::PATH_WEBHOOK_URL);
    }
}
