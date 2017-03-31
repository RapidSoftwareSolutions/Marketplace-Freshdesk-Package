<?php

$app->post('/api/Freshdesk/addNoteToTicket', function ($request, $response) {
    /** @var \Slim\Http\Response $response */
    /** @var \Slim\Http\Request $request */
    /** @var \Models\checkRequest $checkRequest */

    $settings = $this->settings;
    $checkRequest = $this->validation;
    $validateRes = $checkRequest->validate($request, ['apiKey', 'domain', 'ticketId', 'body']);
    if (!empty($validateRes) && isset($validateRes['callback']) && $validateRes['callback'] == 'error') {
        return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($validateRes);
    } else {
        $postData = $validateRes;
    }

    $url = "https://" . $postData['args']['domain'] . "." . $settings['apiUrl'] . "/tickets/" . $postData['args']['ticketId'] . "/notes";

    $headers['Authorization'] = "Basic " . base64_encode($postData['args']['apiKey']);
//    $headers['Content-Type'] = 'application/json';

    $json['body'] = $postData['args']['body'];
    $formData[] = [
        "name" => "body",
        "contents" => $postData['args']['body']
    ];

    if (isset($postData['args']['incoming']) && strlen($postData['args']['incoming']) > 0) {
        $formData[] = [
            "name" => "incoming",
            "contents" => filter_var($postData['args']['incoming'], FILTER_VALIDATE_BOOLEAN)
        ];
    }
    if (isset($postData['args']['notifyEmails']) && !empty($postData['args']['notifyEmails'])) {
        if (is_array($postData['args']['notifyEmails'])) {
            $formData[] = [
                "name" => "notify_emails",
                "contents" => $postData['args']['notifyEmails']
            ];
        } else {
            $formData[] = [
                "name" => "notify_emails",
                "contents" => explode(',', $postData['args']['notifyEmails'])
            ];
        }
    }
    if (isset($postData['args']['private']) && strlen($postData['args']['private']) > 0) {
        $formData[] = [
            "name" => "private",
            "contents" => filter_var($postData['args']['private'] , FILTER_VALIDATE_BOOLEAN)
        ];
    }
    if (!empty($postData['args']['userId'])) {
        $formData[] = [
            "name" => "user_id",
            "contents" => (int) $postData['args']['userId']
        ];
    }
    if (isset($postData['args']['attachments']) && !empty($postData['args']['attachments'])) {
        if (is_array($postData['args']['attachments'])) {
            $attachments = $postData['args']['attachments'];
        }
        else {
            $attachments = explode(',', $postData['args']['attachments']);
        }
        if (!empty($attachments)) {
            foreach ($attachments as $link) {
                $content = fopen($link, "r");
                if ($content) {
                    $formData[] = [
                        "name" => "attachments[]",
                        "contents" => $content
                    ];
                }
            }
        }
    }

    try {
        /** @var GuzzleHttp\Client $client */
        $client = $this->httpClient;
        $vendorResponse = $client->post($url, [
            'headers' => $headers,
            'multipart' => $formData
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

    return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($result);
});
