<?php

$app->post('/api/Freshdesk/updateTicket', function ($request, $response) {
    /** @var \Slim\Http\Response $response */
    /** @var \Slim\Http\Request $request */
    /** @var \Models\checkRequest $checkRequest */

    $settings = $this->settings;
    $checkRequest = $this->validation;
    $validateRes = $checkRequest->validate($request, ['apiKey', 'domain', 'ticketId']);
    if (!empty($validateRes) && isset($validateRes['callback']) && $validateRes['callback'] == 'error') {
        return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($validateRes);
    } else {
        $postData = $validateRes;
    }

    $url = "https://" . $postData['args']['domain'] . "." . $settings['apiUrl'] . "/tickets/" . $postData['args']['ticketId'];

    $headers['Authorization'] = "Basic " . base64_encode($postData['args']['apiKey']);

    if (isset($postData['args']['description']) && strlen($postData['args']['description']) > 0) {
        $formData[] = [
            "name" => "description",
            "contents" => $postData['args']['description']
        ];
    }
    if (isset($postData['args']['status']) && strlen($postData['args']['status']) > 0) {
        $formData[] = [
            "name" => "status",
            "contents" => $postData['args']['status']
        ];
    }
    if (isset($postData['args']['subject']) && strlen($postData['args']['subject']) > 0) {
        $formData[] = [
            "name" => "subject",
            "contents" => $postData['args']['subject']
        ];
    }
    if (isset($postData['args']['priority']) && strlen($postData['args']['priority']) > 0) {
        $formData[] = [
            "name" => "priority",
            "contents" => $postData['args']['priority']
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

    if (isset($postData['args']['name']) && strlen($postData['args']['name']) > 0) {
        $formData[] = [
            "name" => "name",
            "contents" => $postData['args']['name']
        ];
    }
    if (isset($postData['args']['requesterId']) && strlen($postData['args']['requesterId']) > 0) {
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
    if (isset($postData['args']['responderId']) && strlen($postData['args']['responderId']) > 0) {
        $formData[] = [
            "name" => "responder_id",
            "contents" => $postData['args']['responderId']
        ];
    }
    if (isset($postData['args']['ccEmails']) && strlen($postData['args']['ccEmails']) > 0) {
        $formData[] = [
            "name" => "cc_emails",
            "contents" => $postData['args']['ccEmails']
        ];
    }
    if (isset($postData['args']['customFields']) && strlen($postData['args']['customFields']) > 0) {
        $formData[] = [
            "name" => "custom_fields",
            "contents" => $postData['args']['customFields']
        ];
    }
    if (isset($postData['args']['dueBy']) && strlen($postData['args']['dueBy']) > 0) {
        $formData[] = [
            "name" => "due_by",
            "contents" => $postData['args']['dueBy']
        ];
    }
    if (isset($postData['args']['emailConfigId']) && strlen($postData['args']['emailConfigId']) > 0) {
        $formData[] = [
            "name" => "email_config_id",
            "contents" => $postData['args']['emailConfigId']
        ];
    }
    if (isset($postData['args']['frDueBy']) && strlen($postData['args']['frDueBy']) > 0) {
        $formData[] = [
            "name" => "fr_due_by",
            "contents" => $postData['args']['frDueBy']
        ];
    }
    if (isset($postData['args']['groupId']) && strlen($postData['args']['groupId']) > 0) {
        $formData[] = [
            "name" => "group_id",
            "contents" => $postData['args']['groupId']
        ];
    }
    if (isset($postData['args']['productId']) && strlen($postData['args']['productId']) > 0) {
        $formData[] = [
            "name" => "product_id",
            "contents" => $postData['args']['productId']
        ];
    }
    if (isset($postData['args']['source']) && strlen($postData['args']['source']) > 0) {
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
    if (isset($postData['args']['companyId']) && strlen($postData['args']['companyId']) > 0) {
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

    if (!empty($formData)) {
        try {
            /** @var GuzzleHttp\Client $client */
            $client = $this->httpClient;
            $vendorResponse = $client->put($url, [
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
