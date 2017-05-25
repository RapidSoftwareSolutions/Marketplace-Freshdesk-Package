<?php

$app->post('/api/Freshdesk/updateSolutionArticle', function ($request, $response) {
    /** @var \Slim\Http\Response $response */
    /** @var \Slim\Http\Request $request */
    /** @var \Models\checkRequest $checkRequest */

    $settings = $this->settings;
    $checkRequest = $this->validation;
    $validateRes = $checkRequest->validate($request, ['apiKey', 'domain', 'articleId']);
    if (!empty($validateRes) && isset($validateRes['callback']) && $validateRes['callback'] == 'error') {
        return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($validateRes);
    } else {
        $postData = $validateRes;
    }

    $url = "https://" . $postData['args']['domain'] . "." . $settings['apiUrl'] . "/solutions/articles/" . $postData['args']['articleId'];
    if (!empty($postData['args']['language'])) {
        $url .= "/" . $postData['args']['language'];
    }

    $headers['Authorization'] = "Basic " . base64_encode($postData['args']['apiKey']);
    $headers['Content-Type'] = 'application/json';

    if (!empty($postData['args']['agentId'])) {
        $json['agent_id'] = $postData['args']['agentId'];
    }
    if (!empty($postData['args']['description'])) {
        $json['description'] = $postData['args']['description'];
    }
    if (!empty($postData['args']['status'])) {
        $json['status'] = (int) $postData['args']['status'];
    }
    if (!empty($postData['args']['title'])) {
        $json['title'] = $postData['args']['title'];
    }
    if (!empty($postData['args']['type'])) {
        $json['type'] = (int) $postData['args']['type'];
    }
    if (!empty($postData['args']['tags'])) {
        $json['tags'] = $postData['args']['tags'];
    }
    if (!empty($postData['args']['metaKeywords'])) {
        $json['seo_data']['meta_keywords'] = $postData['args']['metaKeywords'];
    }
    if (!empty($postData['args']['metaTitle'])) {
        $json['seo_data']['meta_title'] = $postData['args']['metaTile'];
    }
    if (!empty($postData['args']['metaDescription'])) {
        $json['seo_data']['meta_description'] = $postData['args']['metaDescription'];
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
