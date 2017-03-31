<?php

$app->post('/api/Freshdesk/createSolutionArticle', function ($request, $response) {
    /** @var \Slim\Http\Response $response */
    /** @var \Slim\Http\Request $request */
    /** @var \Models\checkRequest $checkRequest */

    $settings = $this->settings;
    $checkRequest = $this->validation;
    $validateRes = $checkRequest->validate($request, ['apiKey', 'domain', 'folderId', 'title', 'description', 'status', 'type']);
    if (!empty($validateRes) && isset($validateRes['callback']) && $validateRes['callback'] == 'error') {
        return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($validateRes);
    } else {
        $postData = $validateRes;
    }

    $url = "https://" . $postData['args']['domain'] . "." . $settings['apiUrl'] . "/solutions/folders/" . (int) $postData['args']['folderId'] . "/articles";

    $headers['Authorization'] = "Basic " . base64_encode($postData['args']['apiKey']);
    $headers['Content-Type'] = 'application/json';

    $json['title'] = $postData['args']['title'];
    $json['description'] = $postData['args']['description'];
    $json['status'] = (int) $postData['args']['status'];
    $json['type'] = (int) $postData['args']['type'];
    if (!empty($postData['args']['tags'])) {
        if (is_array($postData['args']['tags'])) {
            $json['tags'] = $postData['args']['tags'];
        }
        else {
            $json['tags'] = explode(',', $postData['args']['tags']);
        }
    }
    if (!empty($postData['args']['metaTitle'])) {
        $json['seo_data']['meta_title'] = $postData['args']['metaTitle'];
    }
    if (!empty($postData['args']['metaDescription'])) {
        $json['seo_data']['meta_description'] = $postData['args']['metaDescription'];
    }
    if (!empty($postData['args']['metaKeywords'])) {
        if (is_array($postData['args']['metaKeywords'])) {
            $json['seo_data']['meta_keywords'] = $postData['args']['metaKeywords'];
        }
        else {
            $json['seo_data']['meta_keywords'] = explode(',', $postData['args']['metaKeywords']);
        }
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
        }
        else {
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
