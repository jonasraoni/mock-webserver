<?php

namespace donatj\MockWebServer;

/**
 * DelayedResponse wraps a response, causing it when called to be delayed by a specified number of microseconds.
 */
class DelayedResponse implements InitializingResponseInterface, MultiResponseInterface {

	/**
	 * @var int Microseconds to delay the response by.
	 */
	protected $delay;
	/**
	 * @var \donatj\MockWebServer\ResponseInterface
	 */
	protected $response;

	/**
	 * @param \donatj\MockWebServer\ResponseInterface $response
	 * @param int                                     $delay Microseconds to delay the response
	 */
	public function __construct(
		ResponseInterface $response,
		int $delay
	) {
		$this->response = $response;
		$this->delay    = $delay;
	}

	/**
	 * @inheritDoc
	 */
	public function getRef() : string {
		return md5('delayed-' . $this->response->getRef());
	}

	/**
	 * @inheritDoc
	 */
	public function initialize( RequestInfo $request ) {
		usleep($this->delay);
	}

	/**
	 * @inheritDoc
	 */
	public function getBody( RequestInfo $request ) : string {
		return $this->response->getBody($request);
	}

	/**
	 * @inheritDoc
	 */
	public function getHeaders( RequestInfo $request ) : array {
		return $this->response->getHeaders($request);
	}

	/**
	 * @inheritDoc
	 */
	public function getStatus( RequestInfo $request ) : int {
		return $this->response->getStatus($request);
	}

	/**
	 * @inheritDoc
	 */
	public function next() : bool {
		if( $this->response instanceof MultiResponseInterface ) {
			return $this->response->next();
		}

		return false;
	}

}
