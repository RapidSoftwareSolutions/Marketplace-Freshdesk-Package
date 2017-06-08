<?php

$app->post('/api/Freshdesk/updateTimeEntry', function ($request, $response) {
    /** @var \Slim\Http\Response $response */
    /** @var \Slim\Http\Request $request */
    /** @var \Models\checkRequest $checkRequest */

    $settings = $this->settings;
    $checkRequest = $this->validation;
    $validateRes = $checkRequest->validate($request, ['apiKey', 'domain', 'timeEntryId']);
    if (!empty($validateRes) && isset($validateRes['callback']) && $validateRes['callback'] == 'error') {
        return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($validateRes);
    } else {
        $postData = $validateRes;
    }

    $url = "https://" . $postData['args']['domain'] . "." . $settings['apiUrl'] . "/time_entries/" . $postData['args']['timeEntryId'];

    $headers['Authorization'] = "Basic " . base64_encode($postData['args']['apiKey']);
    $headers['Content-Type'] = 'application/json';

    if (!empty($postData['args']['agentId'])) {
        $json['agent_id'] = $postData['args']['agentId'];
    }
    if (isset($postData['args']['billable']) && strlen($postData['args']['billable']) > 0) {
        $json['billable'] = filter_var($postData['args']['billable'], FILTER_VALIDATE_BOOLEAN);
    }
    if (!empty($postData['args']['executedAt'])) {
        $date = DateTime::createFromFormat('Y-m-d H:i:s', $postData['args']['executedAt']);
        if ($date instanceof DateTime) {
            $json['executed_at'] = $date->format('Y-m-d\TH:i:s\Z');
        }
        else {
            $json['executed_at'] = $postData['args']['executedAt'];
        }
    }
    if (isset($postData['args']['note']) && strlen($postData['args']['note'])) {
        $json['note'] = $postData['args']['note'];
    }
    if (!empty($postData['args']['startTime'])) {
        $date = DateTime::createFromFormat('Y-m-d H:i:s', $postData['args']['startTime']);
        if ($date instanceof DateTime) {
            $json['start_time'] = $date->format('Y-m-d\TH:i:s\Z');
        }
        else {
            $json['start_time'] = $postData['args']['startTime'];
        }
    }
    if (!empty($postData['args']['timeSpent'])) {
        $json['time_spent'] = $postData['args']['timeSpent'];
    }
    if (isset($postData['args']['timerRunning']) && strlen($postData['args']['timerRunning']) > 0) {
        $json['timer_running'] = filter_var($postData['args']['timerRunning'], FILTER_VALIDATE_BOOLEAN);
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
    }
    else {
        $result['callback'] = 'error';
        $result['contextWrites']['to']['status_code'] = 'API_ERROR';
        $result['contextWrites']['to']['status_msg']['result'] = [
            "code" => "missing_params",
            "message" => "Your update call does not have any parameter. Atleast one parameter is required."
        ];
    }

    return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($result);
});
