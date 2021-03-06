<?php

$app->post('/api/Freshdesk/updateContact', function ($request, $response) {
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

    $url = "https://" . $postData['args']['domain'] . "." . $settings['apiUrl'] . "/contacts/" . $postData['args']['contactId'];

    $headers['Authorization'] = "Basic " . base64_encode($postData['args']['apiKey']);

    if (!empty($postData['args']['name'])) {
        $json['name'] = $postData['args']['name'];
    }
    if (!empty($postData['args']['email'])) {
        $json['email'] = $postData['args']['email'];
    }
    if (!empty($postData['args']['phone'])) {
        $json['phone'] = $postData['args']['phone'];
    }
    if (!empty($postData['args']['mobile'])) {
        $json['mobile'] = $postData['args']['mobile'];
    }
    if (!empty($postData['args']['twitterId'])) {
        $json['twitter_id'] = $postData['args']['twitterId'];
    }
    if (!empty($postData['args']['otherEmails'])) {
        $json['other_emails'] = $postData['args']['otherEmails'];
    }
    if (!empty($postData['args']['companyId'])) {
        $json['company_id'] = $postData['args']['companyId'];
    }
    if (is_bool($postData['args']['viewAllTickets'])) {
        $json['view_all_tickets'] = filter_var($postData['args']['viewAllTickets'], FILTER_VALIDATE_BOOLEAN);
    }
    if (!empty($postData['args']['otherCompanies'])) {
        $json['other_companies'] = $postData['args']['otherCompanies'];
    }
    if (!empty($postData['args']['address'])) {
        $json['address'] =$postData['args']['address'];
    }
    if (!empty($postData['args']['customFields'])) {
        foreach ($postData['args']['customFields'] as $array) {
            $json['custom_fields'][$array['key']] = $array['value'];
        }
    }
    if (!empty($postData['args']['description'])) {
        $json['description'] = $postData['args']['description'];
    }
    if (!empty($postData['args']['jobTitle'])) {
        $json['job_title'] = $postData['args']['jobTitle'];
    }
    if (!empty($postData['args']['language'])) {
        $json['language'] = $postData['args']['language'];
    }
    if (!empty($postData['args']['tags'])) {
        $json['tags'] = $postData['args']['tags'];
    }
    if (!empty($postData['args']['timeZone'])) {
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
