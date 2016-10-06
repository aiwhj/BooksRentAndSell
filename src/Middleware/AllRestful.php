<?php
class AllRestful
{
    /**
     * Example middleware invokable class
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request  PSR7 request
     * @param  \Psr\Http\Message\ResponseInterface      $response PSR7 response
     * @param  callable                                 $next     Next middleware
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke($request, $response, $next)
    {
		header('Access-Control-Allow-Origin:*');
		header('Access-Control-Allow-Headers:X-Requested-With');
		header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE');
		$response = $next($request, $response);
		return $response;
    }
}