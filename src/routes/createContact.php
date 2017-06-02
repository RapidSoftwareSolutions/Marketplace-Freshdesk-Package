<?php

$app->post('/api/Freshdesk/createContact', function ($request, $response) {
    /** @var \Slim\Http\Response $response */
    /** @var \Slim\Http\Request $request */
    /** @var \Models\checkRequest $checkRequest */

    $settings = $this->settings;
    $checkRequest = $this->validation;
    $validateRes = $checkRequest->validate($request, ['apiKey', 'domain', 'name']);
    if (!empty($validateRes) && isset($validateRes['callback']) && $validateRes['callback'] == 'error') {
        return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($validateRes);
    } else {
        $postData = $validateRes;
    }

    $url = "https://" . $postData['args']['domain'] . "." . $settings['apiUrl'] . "/contacts";

    $headers['Authorization'] = "Basic " . base64_encode($postData['args']['apiKey']);

    $json['name'] = $postData['args']['name'];
    if (isset($postData['args']['email']) && strlen($postData['args']['email']) > 0) {
        $json['email'] = $postData['args']['email'];
    }
    if (isset($postData['args']['phone']) && strlen($postData['args']['phone']) > 0) {
        $json['phone'] = $postData['args']['phone;'];
    }
    if (isset($postData['args']['mobile']) && strlen($postData['args']['mobile']) > 0) {
        $json['mobile'] = $postData['args']['mobile'];
    }
    if (isset($postData['args']['twitterId']) && strlen($postData['args']['twitterId']) > 0) {
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
    if (isset($postData['args']['otherCompanies']) && strlen($postData['args']['otherCompanies']) > 0) {
        $json['other_companies'] = $postData['args']['otherCompanies'];
    }
    if (isset($postData['args']['address']) && strlen($postData['args']['address']) > 0) {
        $json['address'] = $postData['args']['address'];
    }
    if (isset($postData['args']['customFields']) && strlen($postData['args']['customFields']) > 0) {
        foreach ($postData['args']['customFields'] as $array) {
            $json['custom_fields'][$array['key']] = $array['value'];
        }
    }
    if (isset($postData['args']['description']) && strlen($postData['args']['description']) > 0) {
        $json['description'] = $postData['args']['description'];
    }
    if (isset($postData['args']['jobTitle']) && strlen($postData['args']['jobTitle']) > 0) {
        $json['job_title'] = $postData['args']['jobTitle'];
    }
    if (isset($postData['args']['language']) && strlen($postData['args']['language']) > 0) {
        $json['language'] = $postData['args']['language'];
    }
    if (isset($postData['args']['tags']) && !empty($postData['args']['tags'])) {
        $json['tags'] = $postData['args']['tags'];
    }
    if (isset($postData['args']['timeZone']) && !empty($postData['args']['timeZone'])) {
        $json['time_zone'] = $postData['args']['timeZone'];
    }

    try {
        /** @var GuzzleHttp\Client $client */
        $client = $this->httpClient;
        $vendorResponse = $client->post($url, [
            'headers' => $headers,
            'json' => $json
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
