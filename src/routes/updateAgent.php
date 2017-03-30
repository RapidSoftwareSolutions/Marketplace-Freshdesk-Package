<?php

$app->post('/api/Freshdesk/updateAgent', function ($request, $response) {
    /** @var \Slim\Http\Response $response */
    /** @var \Slim\Http\Request $request */
    /** @var \Models\checkRequest $checkRequest */

    $settings = $this->settings;
    $checkRequest = $this->validation;
    $validateRes = $checkRequest->validate($request, ['apiKey', 'domain', 'agentId']);
    if (!empty($validateRes) && isset($validateRes['callback']) && $validateRes['callback'] == 'error') {
        return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($validateRes);
    } else {
        $postData = $validateRes;
    }

    $url = "https://" . $postData['args']['domain'] . "." . $settings['apiUrl'] . "/agents/" . $postData['args']['agentId'];

    $headers['Authorization'] = "Basic " . base64_encode($postData['args']['apiKey']);
    $headers['Content-Type'] = 'application/json';

    $json = [];
    if (isset($postData['args']['occasional']) && strlen($postData['args']['occasional']) > 0) {
        $json['occasional'] = filter_var($postData['args']['occasional'], FILTER_VALIDATE_BOOLEAN);
    }
    if (isset($postData['args']['signature']) && strlen($postData['args']['signature']) > 0) {
        $json['signature'] = $postData['args']['signature'];
    }
    if (isset($postData['args']['ticketScope']) && strlen($postData['args']['ticketScope']) > 0) {
        $json['ticket_scope'] = (int) $postData['args']['ticketScope'];
    }
    if (isset($postData['args']['groupIds']) && !empty($postData['args']['groupIds'])) {
        if (is_array($postData['args']['groupIds'])) {
            $groupIdList = $postData['args']['groupIds'];
        } else {
            $groupIdList = explode(',', $postData['args']['groupIds']);
        }
        $json['group_ids'] = $groupIdList;
    }
    if (isset($postData['args']['roleIds']) && !empty($postData['args']['roleIds'])) {
        if (is_array($postData['args']['roleIds'])) {
            $groupIdList = $postData['args']['roleIds'];
        } else {
            $groupIdList = explode(',', $postData['args']['roleIds']);
        }
        $json['role_ids'] = $groupIdList;
    }
    if (isset($postData['args']['name']) && strlen($postData['args']['name']) > 0) {
        $json['name'] = $postData['args']['name'];
    }
    if (isset($postData['args']['email']) && strlen($postData['args']['email']) > 0) {
        $json['email'] = $postData['args']['email'];
    }
    if (isset($postData['args']['phone']) && strlen($postData['args']['phone']) > 0) {
        $json['phone'] = $postData['args']['phone'];
    }
    if (isset($postData['args']['mobile']) && strlen($postData['args']['mobile']) > 0) {
        $json['mobile'] = $postData['args']['mobile'];
    }
    if (isset($postData['args']['jobTitle']) && strlen($postData['args']['jobTitle']) > 0) {
        $json['job_title'] = $postData['args']['jobTitle'];
    }
    if (isset($postData['args']['language']) && strlen($postData['args']['language']) > 0) {
        $json['language'] = $postData['args']['language'];
    }
    if (isset($postData['args']['timeZone']) && strlen($postData['args']['timeZone']) > 0) {
        $json['time_zone'] = $postData['args']['timeZone'];
    }

    if (!empty($json)) {
        try {
            /** @var GuzzleHttp\Client $client */
            $client = $this->httpClient;
            $vendorResponse = $client->put($url, [
                'headers' => $headers,
                'json' => $json
            ]);
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
            $result['contextWrites']['to']['status_msg']['result'] = json_decode($exception->getResponse()->getBody()->getContents(), true);
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
    } else {
        $result['callback'] = 'error';
        $result['contextWrites']['to']['status_code'] = 'API_ERROR';
        $result['contextWrites']['to']['status_msg']['result'] = [
            "code" => "missing_params",
            "message" => "Your update call does not have any parameter. Atleast one parameter is required."
        ];
    }

    return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($result);
});
