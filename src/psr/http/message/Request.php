<?php

namespace nsusoft\captcha\psr\http\message;

use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

class Request implements RequestInterface
{
    private $protocolVersion = '1.1';

    private $headers = [];

    /**
     * @inheritDoc
     */
    public function getProtocolVersion(): string
    {
        return $this->protocolVersion;
    }

    /**
     * @inheritDoc
     */
    public function withProtocolVersion(string $version): MessageInterface
    {
        $clone = clone $this;
        $clone->protocolVersion = $version;

        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @inheritDoc
     */
    public function hasHeader(string $name): bool
    {
        foreach (array_keys($this->headers) as $key) {
            if (0 === strcasecmp($key, $name)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function getHeader(string $name): array
    {
        foreach ($this->headers as $key => $value) {
            if (0 === strcasecmp($key, $name)) {
                return $value;
            }
        }

        return [];
    }

    /**
     * @inheritDoc
     */
    public function getHeaderLine(string $name): string
    {
        foreach ($this->headers as $key => $value) {
            if (0 === strcasecmp($key, $name)) {
                return implode(',', $value);
            }
        }

        return '';
    }

    /**
     * @inheritDoc
     */
    public function withHeader(string $name, $value): MessageInterface
    {
        $headerName = $this->formatHeaderName($name);
        $headerValues = is_array($value) ? $value : [$value];
        
        $clone = clone $this;
        $clone->headers[$headerName] = $headerValues;

        return $clone;
    }

    /**
     * Makes header name like this 'Content-Type'.
     * @param string $name
     * @return string
     */
    private function formatHeaderName($name): string
    {
        return implode('-', array_map(
            fn ($item) => ucfirst(strtolower($item)),
            explode('-', $name)
        ));
    }

    /**
     * @inheritDoc
     */
    public function withAddedHeader(string $name, $value): MessageInterface
    {
        
    }

    /**
     * @inheritDoc
     */
    public function withoutHeader(string $name): MessageInterface
    {
        
    }

    /**
     * @inheritDoc
     */
    public function getBody(): StreamInterface
    {
        
    }

    /**
     * @inheritDoc
     */
    public function withBody(StreamInterface $body): MessageInterface
    {
        
    }

    /**
     * @inheritDoc
     */
    public function getRequestTarget(): string
    {
        
    }

    /**
     * @inheritDoc
     */
    public function withRequestTarget(string $requestTarget): RequestInterface
    {
        
    }

    /**
     * @inheritDoc
     */
    public function getMethod(): string
    {
        
    }

    /**
     * @inheritDoc
     */
    public function withMethod(string $method): RequestInterface
    {
        
    }

    /**
     * @inheritDoc
     */
    public function getUri(): UriInterface
    {
        
    }

    /**
     * @inheritDoc
     */
    public function withUri(UriInterface $uri, bool $preserveHost = false): RequestInterface
    {
        
    }
}