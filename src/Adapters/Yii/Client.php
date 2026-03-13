<?php

namespace NsuSoft\Captcha\Adapters\Yii;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use yii\base\Component;
use yii\httpclient\Exception;

class Client extends Component implements ClientInterface
{
    /**
     * @var RequestAdapterInterface|null Request adapter to convert PSR-7 request format to Yii HTTP client.
     */
    private ?RequestAdapterInterface $requestAdapter = null;

    /**
     * @var ResponseAdapterInterface|null Response adapter to convert Yii HTTP client format to PSR-7.
     */
    private ?ResponseAdapterInterface $responseAdapter = null;

    /**
     * @inheritDoc
     */
    public function init(): void
    {
        $this->initRequestAdapter();
        $this->initResponseAdapter();
    }

    /**
     * Initialize request adapter object.
     * @return void
     */
    private function initRequestAdapter(): void
    {
        if (is_null($this->requestAdapter)) {
            $this->setRequestAdapter(new RequestAdapter());
        }
    }

    /**
     * Initialize response adapter object.
     * @return void
     */
    private function initResponseAdapter(): void
    {
        if (is_null($this->responseAdapter)) {
            $this->setResponseAdapter(new ResponseAdapter());
        }
    }

    /**
     * Sets request adapter.
     * @param RequestAdapterInterface $requestAdapter
     * @return void
     */
    public function setRequestAdapter(RequestAdapterInterface $requestAdapter): void
    {
        $this->requestAdapter = $requestAdapter;
    }

    /**
     * Sets response adapter.
     * @param ResponseAdapterInterface $responseAdapter
     * @return void
     */
    public function setResponseAdapter(ResponseAdapterInterface $responseAdapter): void
    {
        $this->responseAdapter = $responseAdapter;
    }

    /**
     * @inheritDoc
     */
    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        try {
            $response = $this->requestAdapter->toYii($request)->send();
            return $this->responseAdapter->toPsr($response);
        } catch (Exception $e) {
            throw new RequestException($e, $request);
        }
    }
}