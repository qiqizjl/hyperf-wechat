<?php

declare(strict_types=1);
/**
 *
 *
 * @author    耐小心 <i@naixiaoixn.com>
 * @time      2020/1/18 12:49 上午
 *
 * @copyright 2019 耐小心
 */

namespace Naixiaoxin\HyperfWechat;

use Hyperf\HttpMessage\Stream\SwooleStream;
use Hyperf\Utils\Context;
use Symfony\Component\HttpFoundation\Response;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

class Helper
{
    public static function Response(Response $response)
    {
        $psrResponse = Context::get(PsrResponseInterface::class);
        $psrResponse = $psrResponse->withBody(new SwooleStream($response->getContent()))->withStatus($response->getStatusCode());
        foreach ($response->headers->all() as $key => $item) {
            $psrResponse = $psrResponse->withHeader($key, $item);
        }
        return $psrResponse;
    }
}