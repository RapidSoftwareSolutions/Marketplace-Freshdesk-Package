<?php

$app->post('/api/Freshdesk/getAllTickets', function ($request, $response) {
    /** @var \Slim\Http\Response $response */
    /** @var \Slim\Http\Request $request */
    /** @var \Models\checkRequest $checkRequest */

    $settings = $this->settings;
    $checkRequest = $this->validation;
    $validateRes = $checkRequest->validate($request, ['apiKey', 'domain']);
    if (!empty($validateRes) && isset($validateRes['callback']) && $validateRes['callback'] == 'error') {
        return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($validateRes);
    } else {
        $postData = $validateRes;
    }

    $url = "https://" . $postData['args']['domain'] . "." . $settings['apiUrl'] . "/tickets";

    $headers['Authorization'] = "Basic " . base64_encode($postData['args']['apiKey']);

    $params = [];

    if (isset($postData['args']['filter']) && strlen($postData['args']['filter']) > 0) {
        $params['filter'] = $postData['args']['filter'];
    }
    if (isset($postData['args']['userId']) && strlen($postData['args']['userId']) > 0) {
        $params['requester_id'] = $postData['args']['userId'];
    }
    if (isset($postData['args']['email']) && strlen($postData['args']['email']) > 0) {
        $params['email'] = $postData['args']['email'];
    }
    if (isset($postData['args']['companyId']) && strlen($postData['args']['companyId']) > 0) {
        $params['company_id'] = $postData['args']['companyId'];
    }
    if (isset($postData['args']['updatedSince']) && strlen($postData['args']['updatedSince']) > 0) {
        $params['updated_since'] = $postData['args']['updatedSince'];
    }
    if (isset($postData['args']['orderBy']) && strlen($postData['args']['orderBy']) > 0) {
        $params['order_by'] = $postData['args']['orderBy'];
    }
    if (isset($postData['args']['orderType']) && strlen($postData['args']['orderType']) > 0) {
        $params['orderType'] = $postData['args']['orderType'];
    }


    try {
        /** @var GuzzleHttp\Client $client */
        $client = $this->httpClient;
        $vendorResponse = $client->get($url, [
            'headers' => $headers
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
        $result['contextWrites']['to']['status_msg']['result'] = json_decode($exception->getResponse()->getBody());
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