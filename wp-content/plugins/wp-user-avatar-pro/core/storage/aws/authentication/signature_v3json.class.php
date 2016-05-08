<?php
/*
 * Copyright 2010-2013 Amazon.com, Inc. or its affiliates. All Rights Reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License").
 * You may not use this file except in compliance with the License.
 * A copy of the License is located at
 *
 *  http://aws.amazon.com/apache2.0
 *
 * or in the "license" file accompanying this file. This file is distributed
 * on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either
 * express or implied. See the License for the specific language governing
 * permissions and limitations under the License.
 */


/*%******************************************************************************************%*/
// CLASS

/**
 * Implements support for Signature v3 (JSON).
 *
 * @version 4.0.0
 * @license See the included NOTICE.md file for more information.
 * @copyright See the included NOTICE.md file for more information.
 * @link http://aws.amazon.com/php/ PHP Developer Center
 */
class AuthV3JSON extends Signer implements Signable
{
	/**
	 * Constructs a new instance of the <AuthV3JSON> class.
	 *
	 * @param string $endpoint (Required) The endpoint to direct the request to.
	 * @param string $operation (Required) The operation to execute as a result of this request.
	 * @param array $payload (Required) The options to use as part of the payload in the request.
	 * @param CFCredential $credentials (Required) The credentials to use for signing and making requests.
	 * @return void
	 */
	public function __construct($endpoint, $operation, $payload, CFCredential $credentials)
	{
		parent::__construct($endpoint, $operation, $payload, $credentials);
	}

	/**
	 * Generates a cURL handle with all of the required authentication bits set.
	 *
	 * @return resource A cURL handle ready for executing.
	 */
	public function authenticate()
	{
		// Determine signing values
		$current_time = time();
		$date = gmdate(CFUtilities::DATE_FORMAT_RFC2616, $current_time);
		$timestamp = gmdate(CFUtilities::DATE_FORMAT_ISO8601, $current_time);
		$nonce = $this->util->generate_guid();
		$signed_headers = array();
		$x_amz_target = null;
		$query = array();

		// Do we have an authentication token?
		if ($this->auth_token)
		{
			$headers['X-Amz-Security-Token'] = $this->auth_token;
			$query['SecurityToken'] = $this->auth_token;
		}

		// Manage the key-value pairs that are used in the query.
		if (stripos($this->operation, 'x-amz-target') !== false)
		{
			$x_amz_target = trim(str_ireplace('x-amz-target:', '', $this->operation));
		}
		else
		{
			$query['Action'] = $this->operation;
		}

		// Only add it if it exists.
		if ($this->api_version)
		{
			$query['Version'] = $this->api_version;
		}

		// Do a case-sensitive, natural order sort on the array keys.
		uksort($query, 'strcmp');

		// Normalize JSON input
		$query['body'] = json_encode($this->payload);
		if (isset($query['body']) && $query['body'] === '[]')
		{
			$query['body'] = '{}';
		}

		// Create the string that needs to be hashed.
		$canonical_query_string = $this->util->encode_signature2($query['body']);

		// Remove the default scheme from the domain.
		$domain = str_replace(array('http://', 'https://'), '', $this->endpoint);

		// Parse our request.
		$parsed_url = parse_url('http://' . $domain);

		// Set the proper host header.
		if (isset($parsed_url['port']) && (integer) $parsed_url['port'] !== 80 && (integer) $parsed_url['port'] !== 443)
		{
			$host_header = strtolower($parsed_url['host']) . ':' . $parsed_url['port'];
		}
		else
		{
			$host_header = strtolower($parsed_url['host']);
		}

		// Set the proper request URI.
		$request_uri = isset($parsed_url['path']) ? $parsed_url['path'] : '/';

		// Generate the querystring from $query
		$this->querystring = $this->util->to_query_string($query);

		// Gather information to pass along to other classes.
		$helpers = array(
			'utilities' => $this->utilities_class,
			'request' => $this->request_class,
			'response' => $this->response_class,
		);

		// Compose the request.
		$request_url = ($this->use_ssl ? 'https://' : 'http://') . $domain;
		$request_url .= !isset($parsed_url['path']) ? '/' : '';

		// Instantiate the request class
		$request = new $this->request_class($request_url, $this->proxy, $helpers, $this->credentials);
		$request->set_method('POST');
		//$request->set_body($this->querystring);
		//$headers['Content-Type'] = 'application/x-www-form-urlencoded; charset=utf-8';

		// Signing using X-Amz-Target is handled differently.
		$headers['X-Amz-Target'] = $x_amz_target;
		$headers['Content-Type'] = 'application/x-amz-json-1.0';
		$request->set_body($query['body']);
		$this->querystring = $query['body'];

		// Pass along registered stream callbacks
		if ($this->registered_streaming_read_callback)
		{
			$request->register_streaming_read_callback($this->registered_streaming_read_callback);
		}

		if ($this->registered_streaming_write_callback)
		{
			$request->register_streaming_write_callback($this->registered_streaming_write_callback);
		}

		// Add authentication headers
		// $headers['X-Amz-Nonce'] = $nonce;
		$headers['Date'] = $date;
		$headers['Content-Length'] = strlen($this->querystring);
		$headers['Content-MD5'] = $this->util->hex_to_base64(md5($this->querystring));
		$headers['Host'] = $host_header;

		// Sort headers
		uksort($headers, 'strnatcasecmp');

		// Prepare the string to sign (HTTP)
		$this->string_to_sign = "POST\n$request_uri\n\n";

		// Add headers to request and compute the string to sign
		foreach ($headers as $header_key => $header_value)
		{
			// Strip linebreaks from header values as they're illegal and can allow for security issues
			$header_value = str_replace(array("\r", "\n"), '', $header_value);

			// Add the header if it has a value
			if ($header_value !== '')
			{
				$request->add_header($header_key, $header_value);
			}

			// Generate the string to sign
			if (
				substr(strtolower($header_key), 0, 8) === 'content-' ||
				strtolower($header_key) === 'date' ||
				strtolower($header_key) === 'expires' ||
				strtolower($header_key) === 'host' ||
				substr(strtolower($header_key), 0, 6) === 'x-amz-'
			)
			{
				$this->string_to_sign .= strtolower($header_key) . ':' . $header_value . "\n";
				$signed_headers[] = $header_key;
			}
		}

		$this->string_to_sign .= "\n";

		if (isset($query['body']) && $query['body'] !== '')
		{
			$this->string_to_sign .= $query['body'];
		}

		// Convert from string-to-sign to bytes-to-sign
		$bytes_to_sign = hash('sha256', $this->string_to_sign, true);

		// Hash the AWS secret key and generate a signature for the request.
		$signature = base64_encode(hash_hmac('sha256', $bytes_to_sign, $this->secret_key, true));

		$headers['X-Amzn-Authorization'] = 'AWS3'
			. ' AWSAccessKeyId=' . $this->key
			. ',Algorithm=HmacSHA256'
			. ',SignedHeaders=' . implode(';', $signed_headers)
			. ',Signature=' . $signature;

		$request->add_header('X-Amzn-Authorization', $headers['X-Amzn-Authorization']);
		$request->request_headers = $headers;

		return $request;
	}
}
