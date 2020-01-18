<?php

declare(strict_types=1);
/**
 *
 *
 * @author    耐小心 <i@naixiaoixn.com>
 * @time      2020/1/19 3:19 上午
 *
 * @copyright 2019 耐小心
 */

namespace Naixiaoxin\HyperfWechat;

use Hyperf\Utils\ApplicationContext;

/**
 * Class EasyWechat
 *
 * @package Naixiaoxin\HyperfWechat
 * @method static \EasyWeChat\OfficialAccount\Application  officialAccount(string $name = "default", array $config = [])
 * @method static \EasyWeChat\Work\Application  work(string $name = "default", array $config = [])
 * @method static \EasyWeChat\MiniProgram\Application  miniProgram(string $name = "default", array $config = [])
 * @method static \EasyWeChat\Payment\Application  payment(string $name = "default", array $config = [])
 * @method static \EasyWeChat\OpenPayment\Application  openPlatform(string $name = "default", array $config = [])
 * @method static \EasyWeChat\OpenWork\Application  openWork(string $name = "default", array $config = [])
 * @method static \EasyWeChat\MicroMerchant\Application  microMerchant(string $name = "default", array $config = [])
 */
class EasyWechat
{

    public static function __callStatic($functionName, $args)
    {
        return ApplicationContext::getContainer()->get(Factory::class)->$functionName(...$args);
    }


}
