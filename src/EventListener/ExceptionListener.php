<?php
/**
 * Created by PhpStorm.
 * User: kasalirazaq
 * Date: 5/25/18
 * Time: 8:34 PM
 */

namespace App\EventListener;

use App\Constants\ResponseCodes;
use App\Constants\ResponseMessages;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ExceptionListener
{
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        // You get the exception object from the received event
        $exception = $event->getException();
        $message = sprintf(
            'My Error says: %s with code: %s',
            $exception->getMessage(),
            $exception->getCode()
        );

        // Customize your response object to display the exception details
        $response = new Response();
        $response->setContent($message);

        $data = [
            'status' => 'error',
            'code' => ResponseCodes::INTERNAL_SERVER_ERROR,
            'message' => ResponseMessages::INTERNAL_SERVER_ERROR,
            'ex' => $exception->getMessage(),
            'type' => get_class($exception)
        ];

        $statusCode = 500;

        // HttpExceptionInterface is a special type of exception that
        // holds status code and header details
        if ($exception instanceof HttpExceptionInterface) {
            $response->setStatusCode($exception->getStatusCode());
            $response->headers->replace($exception->getHeaders());
            if ($exception instanceof MethodNotAllowedHttpException) {
                $statusCode = 400;
                $data['code'] = ResponseCodes::NOT_ALLOWED;
                $data['message'] = ResponseMessages::NOT_ALLOWED;
            } elseif ($exception instanceof NotFoundHttpException) {
                $statusCode = 404;
                $data['code'] = ResponseCodes::NOT_FOUND;
                $data['message'] = sprintf(ResponseMessages::NOT_FOUND, 'resource');
            }
        } else {
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        if (strpos($event->getRequest()->getRequestUri(), '/api') !== false) {
            $event->setResponse(new JsonResponse($data, $statusCode));
            return;
        }

        $event->setResponse(new Response($exception->getMessage(), $statusCode));
        // sends the modified response object to the event
    }
}