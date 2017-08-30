<?php

namespace FsaTechTest\Middlewares;

use \League\Flysystem\Filesystem as Filesystem;
use \League\Flysystem\Adapter\Local as Local;

use function \GuzzleHttp\Psr7\str as str;
use function \GuzzleHttp\Psr7\parse_response as parse_response;

/**
 * a simple example of a caching middleware. constains functionality to
 * store and retrieve responses
 */
class CacheLayer
{
    /**
     * for dealing with the cache files
     * @var \League\Flysystem\Filesystem
     */
    private $filesystem;

    /**
     * the name to be used for this request instance caching
     * @var string
     */
    private $filename;

    /**
     * the age a cache needs to be, in seconds, before it is ignored and
     * a new one is generated anyway
     * @var int
     */
    private $expiryTime;

    public function __construct($expiryTime, $cacheDirectory = '../cache')
    {
        $adapter = new Local($cacheDirectory);
        $this->expiryTime = $expiryTime;
        $this->filesystem = new Filesystem($adapter);
    }

    public function __invoke($request, $response, $next)
    {
        $this->filename = $this->getHashForRequest($request) . '.response';
        if ($this->hasCachedResponseBody()) {
            return $this->getCachedResponseBody();
        }

        $response = $next($request, $response);
        $this->cacheResponseBody($response);
        return $response;
    }

    /**
     * generate a deterministic hash for a URL to be matched against when
     * checking if we already have a cached response for this given request
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request
     * @return string
     */
    private function getHashForRequest($request)
    {
        $hash = '';
        $hash .= $request->getMethod();
        $hash .= $request->getUri()->getScheme() . '://';
        $hash .= $request->getUri()->getHost();
        $hash .= $request->getUri()->getPath();
        $hash = md5($hash);
        return $hash;
    }

    /**
     * determine if we have a valid cached response for the given request
     *
     * @return boolean
     */
    public function hasCachedResponseBody()
    {
        if ($this->filesystem->has($this->filename)) {
            if (abs(time() - $this->filesystem->getTimestamp($this->filename)) > $this->expiryTime) {
                return false;
            }
            return true;
        }
        return false;
    }

    /**
     * build a valid PSR7 response from a cached response file contents
     *
     * @return \Psr\Http\Message\ServerResponseInterface
     */
    public function getCachedResponseBody()
    {
        return parse_response($this->filesystem->read($this->filename));
    }

    /**
     * given a PSR7 response, create a cachable piece of data, and save it to
     * the cache folder
     * @return void
     */
    public function cacheResponseBody($response)
    {
        $serializedResponse = str($response);
        $this->filesystem->put($this->filename, $serializedResponse);
    }
}
