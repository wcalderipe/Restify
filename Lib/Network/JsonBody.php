<?php

class JsonBody {

	protected $success = null;

	protected $metadata = array();

	protected $results = array();

	protected $error = null;

	protected $queue = array();

	protected $bodyKeys = array(
		'success',
		'metadata',
		'results',
		'error',
	);

	public function __construct(
		$success = null, $metadata = array(), $results = array(), $error = array()
	) {
		$this->success  = $success;
		$this->metadata = $metadata;
		$this->results  = $results;
		$this->error    = $error;
	}

	public function enqueue($value) {
		$this->queue[] = $value;

		return $this;
	}

	// TODO: Improve the queue parse.. it still pretty dumb
	protected function parseQueue() {
		foreach ($this->queue as $key => $value) {
			if (is_array($value)) {
				$keys = array_keys($value);
				foreach ($keys as $keyName) {
					if (is_numeric($keyName)) {
						$this->results[] = $value[$keyName];
						continue;
					}

					// If an array key is recognized put the value in the right
					// place otherwise append it to results
					if (in_array($keyName, $this->bodyKeys)) {
						$this->$keyName = $value[$keyName];
					} else {
						if (is_array($value[$keyName])) {
							$this->results += $value[$keyName];
						} else {
							$this->results[] = $value[$keyName];
						}
					}
				}
				continue;
			}

			// If this is not an array means that we don't need to parse it so
			// let's just put it as a result
			$this->results[] = $value;
		}
	}

	public function setSuccess($status) {
		if (is_bool($status)) {
			$this->success = $status;
			return $this;
		}

		$status = (int) $status;
		if ($status >= 100 && $status < 400) {
			$this->success = true;
		} else {
			$this->success = false;
		}

		return $this;
	}

	public function setResults($results = array()) {
		$this->results = $results;

		return $this;
	}

	public function serialize($cleanSerialization = false) {
		$this->parseQueue();

		if ($cleanSerialization) {
			return $this->performCleanSerialization();
		}

		return json_encode($this->__toArray());
	}

	private function performCleanSerialization() {
		$body = $this->__toArray();
		foreach ($body as $key => $value) {
			if (null === $body[$key]) {
				unset($body[$key]);
				continue;
			}

			if (is_array($body[$key]) && empty($body[$key])) {
				unset($body[$key]);
				continue;	
			}
		}	

		return json_encode($body);	
	}

	public function __toArray() {
		return array(
			'success'  => $this->success,
			'metadata' => $this->metadata,
			'results'  => $this->results,
			'error'    => $this->error,
		);
	}

}