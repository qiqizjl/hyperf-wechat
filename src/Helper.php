<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace Naixiaoxin\HyperfWechat;

use Hyperf\HttpMessage\Stream\SwooleStream;
use Hyperf\Utils\Context;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;
use Symfony\Component\HttpFoundation\Response;

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
