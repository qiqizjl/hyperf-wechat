<?php

declare(strict_types=1);
/**
 *
 *
 * @author    耐小心 <i@naixiaoixn.com>
 * @time      2019/10/27 2:06 上午
 *
 * @copyright 2019 耐小心
 */

namespace Naixiaoxin\HyperfWechat;

use Hyperf\Contract\ConfigInterface;
use Hyperf\Guzzle\CoroutineHandler;
use Hyperf\HttpServer\Contract\RequestInterface;
use Psr\Container\ContainerInterface;
use Psr\SimpleCache\CacheInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class Factory
 *
 * @package Naixiaoxin\HyperfWechat
 * @method \EasyWeChat\OfficialAccount\Application  officialAccount(string $name = "default", array $config = [])
 * @method \EasyWeChat\Work\Application  work(string $name = "default", array $config = [])
 * @method \EasyWeChat\MiniProgram\Application  miniProgram(string $name = "default", array $config = [])
 * @method \EasyWeChat\Payment\Application  payment(string $name = "default", array $config = [])
 * @method \EasyWeChat\OpenPayment\Application  openPlatform(string $name = "default", array $config = [])
 * @method \EasyWeChat\OpenWork\Application  openWork(string $name = "default", array $config = [])
 * @method \EasyWeChat\MicroMerchant\Application  microMerchant(string $name = "default", array $config = [])
 */
class Factory
{

    protected $configMap
        = [
            "officialAccount" => "official_account",
            "work"            => "work",
            "miniProgram"     => "mini_program",
            "payment"         => "payment",
            "openPlatform"    => "open_platform",
            "openWork"        => "open_work",
            "microMerchant"   => "micro_merchant",
        ];

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var ConfigInterface
     */
    protected $config;

    /**
     * @var CacheInterface
     */
    protected $cache;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->config    = $container->get(ConfigInterface::class);
        $this->cache     = $container->get(CacheInterface::class);

    }


    public function __call($functionName, $args)
    {
        $accountName   = $args[0] ?? "default";
        $accountConfig = $args[1] ?? [];
        if (!isset($this->configMap[$functionName])) {
            throw new \Exception("方法不存在");
        }
        $configName = $this->configMap[$functionName];
        $config     = $this->getConfig(sprintf("wechat.%s.%s", $configName, $accountName), $accountConfig);
        $app        = \EasyWeChat\Factory::$functionName($config);
        $app->rebind("cache", $this->cache);
        $app["guzzle_handler"] = CoroutineHandler::class;
        $app->rebind("request", $this->getRequest());
        return $app;
    }


    /**
     * 获得配置
     *
     * @param string $name
     * @param array  $config
     * @return array
     */
    private function getConfig(string $name, array $config = []): array
    {
        $defaultConfig = $this->config->get("wechat.defaults", []);
        $moduleConfig  = $this->config->get($name, []);
        return array_merge($moduleConfig, $defaultConfig, $config);
    }


    /**
     * 获取Request请求
     *
     * @return Request
     */
    private function getRequest(): Request
    {
        $request = $this->container->get(RequestInterface::class);
        //return $this->container->get(RequestInterface::class);
        return new Request(
            $request->getQueryParams(),
            $request->getParsedBody(),
            [],
            $request->getCookieParams(),
            $request->getUploadedFiles(),
            $_SERVER->toArray(),
            $request->getBody()->getContents());
    }


}