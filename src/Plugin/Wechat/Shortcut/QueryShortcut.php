<?php

declare(strict_types=1);

namespace Yansongda\Pay\Plugin\Wechat\Shortcut;

use Yansongda\Pay\Contract\ShortcutInterface;
use Yansongda\Pay\Exception\Exception;
use Yansongda\Pay\Exception\InvalidParamsException;
use Yansongda\Pay\Plugin\Wechat\Pay\Common\FindRefundPlugin;
use Yansongda\Pay\Plugin\Wechat\Pay\Common\QueryPlugin;

class QueryShortcut implements ShortcutInterface
{
    /**
     * @throws \Yansongda\Pay\Exception\InvalidParamsException
     */
    public function getPlugins(array $params): array
    {
        $typeMethod = ($params['_type'] ?? 'default').'Plugins';

        if (isset($params['combine_out_trade_no'])) {
            return $this->combinePlugins();
        }

        if (method_exists($this, $typeMethod)) {
            return $this->{$typeMethod}();
        }

        throw new InvalidParamsException(Exception::SHORTCUT_QUERY_TYPE_ERROR, "Query type [$typeMethod] not supported");
    }

    protected function defaultPlugins(): array
    {
        return [
            QueryPlugin::class,
        ];
    }

    protected function refundPlugins(): array
    {
        return [
            FindRefundPlugin::class,
        ];
    }

    protected function combinePlugins(): array
    {
        return [
            \Yansongda\Pay\Plugin\Wechat\Pay\Combine\QueryPlugin::class,
        ];
    }
}
