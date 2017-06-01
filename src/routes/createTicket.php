<?php

$app->post('/api/Freshdesk/createTicket', function ($request, $response) {
    /** @var \Slim\Http\Response $response */
    /** @var \Slim\Http\Request $request */
    /** @var \Models\checkRequest $checkRequest */

    $settings = $this->settings;
    $checkRequest = $this->validation;
    $validateRes = $checkRequest->validate($request, ['apiKey', 'domain', 'description', 'status', 'subject', 'priority']);
    if (!empty($validateRes) && isset($validateRes['callback']) && $validateRes['callback'] == 'error') {
        return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($validateRes);
    } else {
        $postData = $validateRes;
    }

    $url = "https://" . $postData['args']['domain'] . "." . $settings['apiUrl'] . "/tickets";

    $headers['Authorization'] = "Basic " . base64_encode($postData['args']['apiKey']);

    $formData = [
        [
            "name" => "description",
            "contents" => $postData['args']['description']
        ],
        [
            "name" => "status",
            "contents" => $postData['args']['status']
        ],
        [
            "name" => "subject",
            "contents" => $postData['args']['subject']
        ],
        [
            "name" => "priority",
            "contents" => $postData['args']['priority']
        ]
    ];


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

    if (isset($postData['args']['name']) && strlen($postData['args']['name']) > 0) {
        $formData[] = [
            "name" => "name",
            "contents" => $postData['args']['name']
        ];
    }
    if (!empty($postData['args']['requesterId'])) {
        $formData[] = [
            "name" => "requester_id",
            "contents" => $postData['args']['requesterId']
        ];
    }
    if (isset($postData['args']['email']) && strlen($postData['args']['email']) > 0) {
        $formData[] = [
            "name" => "email",
            "contents" => $postData['args']['email']
        ];
    }
    if (isset($postData['args']['facebookId']) && strlen($postData['args']['facebookId']) > 0) {
        $formData[] = [
            "name" => "facebook_id",
            "contents" => $postData['args']['facebookId']
        ];
    }
    if (isset($postData['args']['phone']) && strlen($postData['args']['phone']) > 0) {
        $formData[] = [
            "name" => "phone",
            "contents" => $postData['args']['phone']
        ];
    }
    if (isset($postData['args']['twitterId']) && strlen($postData['args']['twitterId']) > 0) {
        $formData[] = [
            "name" => "twitter_id",
            "contents" => $postData['args']['twitterId']
        ];
    }
    if (isset($postData['args']['type']) && strlen($postData['args']['type']) > 0) {
        $formData[] = [
            "name" => "type",
            "contents" => $postData['args']['type']
        ];
    }
    if (!empty($postData['args']['responderId'])) {
        $formData[] = [
            "name" => "responder_id",
            "contents" => $postData['args']['responderId']
        ];
    }
    if (!empty($postData['args']['ccEmails'])) {
        $formData[] = [
            "name" => "cc_emails",
            "contents" => $postData['args']['ccEmails']
        ];
    }
    if (!empty($postData['args']['customFields'])) {
        $formData[] = [
            "name" => "custom_fields",
            "contents" => $postData['args']['customFields']
        ];
    }
    if (isset($postData['args']['dueBy']) && strlen($postData['args']['dueBy']) > 0) {
        $date = new DateTime($postData['args']['dueBy']);
        if ($date) {
            $timestamp = $date->getTimestamp();
        }
        else {
            $timestamp = $postData['args']['dueBy'];
        }
        $formData[] = [
            "name" => "due_by",
            "contents" => $timestamp
        ];
    }
    if (!empty($postData['args']['emailConfigId'])) {
        $formData[] = [
            "name" => "email_config_id",
            "contents" => $postData['args']['emailConfigId']
        ];
    }
    if (isset($postData['args']['frDueBy']) && strlen($postData['args']['frDueBy']) > 0) {
        $date = new DateTime($postData['args']['frDueBy']);
        if ($date) {
            $timestamp = $date->getTimestamp();
        }
        else {
            $timestamp = $postData['args']['frDueBy'];
        }
        $formData[] = [
            "name" => "fr_due_by",
            "contents" => $timestamp
        ];
    }
    if (!empty($postData['args']['groupId'])) {
        $formData[] = [
            "name" => "group_id",
            "contents" => $postData['args']['groupId']
        ];
    }
    if (!empty($postData['args']['productId'])) {
        $formData[] = [
            "name" => "product_id",
            "contents" => $postData['args']['productId']
        ];
    }
    if (!empty($postData['args']['source'])) {
        $formData[] = [
            "name" => "source",
            "contents" => $postData['args']['source']
        ];
    }
    if (isset($postData['args']['tags']) && strlen($postData['args']['tags']) > 0) {
        $formData[] = [
            "name" => "tags",
            "contents" => $postData['args']['tags']
        ];
    }
    if (!empty($postData['args']['companyId'])) {
        $formData[] = [
            "name" => "company_id",
            "contents" => $postData['args']['companyId']
        ];
    }
    if (isset($postData['args']['email']) && strlen($postData['args']['email']) > 0) {
        $formData[] = [
            "name" => "email",
            "contents" => $postData['args']['email']
        ];
    }
    if (isset($postData['args']['email']) && strlen($postData['args']['email']) > 0) {
        $formData[] = [
            "name" => "email",
            "contents" => $postData['args']['email']
        ];
    }

    try {
        /** @var GuzzleHttp\Client $client */
        $client = $this->httpClient;
        $vendorResponse = $client->post($url, [
            'headers' => $headers,
            'multipart' => $formData
        ]);
        $vendorResponseBody = $vendorResponse->getBody()->getContents();
        if ($vendorResponse->getStatusCode() == 201) {
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
        $result['contextWrites']['to']['status_msg'] = json_decode($exception->getResponse()->getBody(), true);
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
