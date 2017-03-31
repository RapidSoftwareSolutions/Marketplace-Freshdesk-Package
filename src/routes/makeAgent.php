<?php

$app->post('/api/Freshdesk/makeAgent', function ($request, $response) {
    /** @var \Slim\Http\Response $response */
    /** @var \Slim\Http\Request $request */
    /** @var \Models\checkRequest $checkRequest */

    $settings = $this->settings;
    $checkRequest = $this->validation;
    $validateRes = $checkRequest->validate($request, ['apiKey', 'domain', 'contactId']);
    if (!empty($validateRes) && isset($validateRes['callback']) && $validateRes['callback'] == 'error') {
        return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($validateRes);
    } else {
        $postData = $validateRes;
    }

    $url = "https://" . $postData['args']['domain'] . "." . $settings['apiUrl'] . "/contacts/" . $postData['args']['contactId'] . "/make_agent";

    $args['headers']['Authorization'] = "Basic " . base64_encode($postData['args']['apiKey']);
    $args['headers']['Content-Type'] = 'application/json';

    if (isset($postData['args']['occasional']) && strlen($postData['args']['occasional']) > 0) {
        $args['json']['occasional'] = filter_var($postData['args']['occasional'], FILTER_VALIDATE_BOOLEAN);
    }
    if (isset($postData['args']['signature']) && strlen($postData['args']['signature']) > 0) {
        $args['json']['signature'] = $postData['args']['signature'];
    }
    if (!empty($postData['args']['ticketScope'])) {
        $args['json']['ticket_scope'] = (int) $postData['args']['ticketScope'];
    }
    if (isset($postData['args']['groupIds']) && !empty($postData['args']['groupIds'])) {
        if (is_array($postData['args']['groupIds'])) {
            $args['json']['group_ids'] = $postData['args']['groupIds'];
        } else {
            $args['json']['group_ids'] = explode(',', $postData['args']['groupIds']);
        }
    }
    if (isset($postData['args']['roleIds']) && !empty($postData['args']['roleIds'])) {
        if (is_array($postData['args']['roleIds'])) {
            $args['json']['role_ids'] = $postData['args']['roleIds'];
        } else {
            $args['json']['role_ids'] = explode(',', $postData['args']['roleIds']);
        }
    }

    try {
        /** @var GuzzleHttp\Client $client */
        $client = $this->httpClient;
        $vendorResponse = $client->put($url, $args);
        $vendorResponseBody = $vendorResponse->getBody()->getContents();
        if ($vendorResponse->getStatusCode() == 200) {
            $result['callback'] = 'success';
            $result['contextWrites']['to'] = [
                "result" => json_decode($vendorResponse->getBody()),
                "info" => [
                    "X-Freshdesk-API-Version" => $vendorResponse->getHeader("X-Freshdesk-API-Version")[0],
                    "X-RateLimit-Remaining" => $vendorResponse->getHeader("X-RateLimit-Remaining")[0],
                    "X-RateLimit-Total" => $vendorResponse->getHeader("X-RateLimit-Total")[0],
                    "X-RateLimit-Used-CurrentRequest" => $vendorResponse->getHeader("X-RateLimit-Used-CurrentRequest")[0]
                ]
            ];
        } else {
            $result['callback'] = 'error';
            $result['contextWrites']['to']['status_code'] = 'API_ERROR';
            $result['contextWrites']['to']['status_msg'] = is_array($vendorResponseBody) ? $vendorResponseBody : json_decode($vendorResponseBody);
        }
    } catch (\GuzzleHttp\Exception\ServerException $exception) {
        $result['callback'] = 'error';
        $result['contextWrites']['to']['status_code'] = 'API_ERROR';
        $result['contextWrites']['to']['status_msg'] = json_decode($exception->getResponse()->getBody());

    } catch (\GuzzleHttp\Exception\ClientException $exception) {
        $result['callback'] = 'error';
        $result['contextWrites']['to']['status_code'] = 'API_ERROR';
        if ($exception->getCode() == 404) {
            $result['contextWrites']['to']['status_msg']['result'] = "Contact not found, or already Agent";
        } else {
            $result['contextWrites']['to']['status_msg']['result'] = json_decode($exception->getResponse()->getBody()->getContents(), true);
        }
        $result['contextWrites']['to']['status_msg']['info'] = [
            "X-Freshdesk-API-Version" => $exception->getResponse()->getHeader("X-Freshdesk-API-Version")[0],
            "X-RateLimit-Remaining" => $exception->getResponse()->getHeader("X-RateLimit-Remaining")[0],
            "X-RateLimit-Total" => $exception->getResponse()->getHeader("X-RateLimit-Total")[0],
            "X-RateLimit-Used-CurrentRequest" => $exception->getResponse()->getHeader("X-RateLimit-Used-CurrentRequest")[0]
        ];
        if ($exception->getCode() == 429) {
            $result['contextWrites']['to']['status_msg']['info']['Retry-After'] = $exception->getResponse()->getHeader("Retry-After");
        }
    }

    return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($result);
});
