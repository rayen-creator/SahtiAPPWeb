<?php

// src/Security/AccessDeniedHandler.php
namespace App\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;
use Symfony\Component\Routing\Route;
class AccessDeniedHandler implements AccessDeniedHandlerInterface
{
//    public function handle(Request $request, AccessDeniedException $accessDeniedException ): Response
//    {
//        // ...
//        $content = " ";
//
//        $response = new Response();
//        $response->setContent($content);
//        $response->setStatusCode(403);
//        return $response;
    function handle(Request $request, AccessDeniedException $accessDeniedException){

//        if ($request->isXmlHttpRequest()) {
//            $response = new Response(json_encode(array('status' => 'protected')));
//            return $response;
//        }
//        else {
            return new RedirectResponse('/accessdenied');

    }


}