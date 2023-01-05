# lphilippo-slack-logging

Simple module, just to add a configurable Slack log handler to Magento.

It's explicitely added on top of the existing log handlers, to prevent interference.

The configuration settings are limited to:

```xml
    <enabled>0</enabled>
    <log_level>500</log_level>
    <webhook_url></webhook_url>
```

It includes the possibility to prevent specific exceptions to not be forwarded to Slack, to prevent unwanted messaging in those cases.

It is expected that separate environments (`production`, `staging`) have different configurations for the webhook, in which case it's not critical to keep that distinction in the message itself. However, the `MAGE_MODE` is included in the username, just in case.