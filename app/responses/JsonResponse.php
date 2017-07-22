<?php

namespace TestPhalconApi\Responses;

class JsonResponse extends BaseResponse
{
    /**
     * JsonResponse constructor.
     */
    public function __construct()
    {
        // Construct parent
        parent::__construct();
    }

    /**
     * Method to send success response.
     *
     * @param $data
     * @param int $responseCode
     * @return JsonResponse
     */
    public function sendSuccess($data, $responseCode = 200)
    {
        return $this->sendResponse($data, false, $responseCode);
    }

    /**
     * Method to send error response.
     *
     * @param $message
     * @param int $responseCode
     * @return JsonResponse
     */
    public function sendError($message, $responseCode = 500)
    {
        return $this->sendResponse($message, true, $responseCode);
    }

    /**
     * Common method to send success/error responses.
     *
     * @param $data
     * @param $isError
     * @param $responseCode
     * @return $this
     */
    private function sendResponse($data, $isError, $responseCode)
    {
        // Load response
        $response = $this->di->get('response');

        // Set response headers
        $response->setContentType('application/json');
        $response->setHeader('X-Status', $isError ? 'ERROR' : 'SUCCESS');
        $response->setHeader('E-Tag', md5(serialize($data)));

        // Set the response & send
        $response->setJsonContent($isError ? ['error' => $data] : $data);
        $response->setStatusCode($responseCode);
        $response->send();

        // Finished
        return $this;
    }
}
