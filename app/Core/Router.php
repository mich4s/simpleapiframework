<?php
namespace App\Core;

use App\Middleware\AbstractMiddleware;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Router
{

    protected static $instance;
    /** @var Request */
    protected $request;
    protected $middlewareList;
    protected $getRoutes    = [];
    protected $postRoutes   = [];
    protected $putRoutes    = [];
    protected $patchRoutes  = [];
    protected $deleteRoutes = [];
    protected $headRoutes   = [];

    public function __construct(Request $request)
    {
        self::$instance = $this;
        $this->request = $request;
        $this->middlewareList = require __ROOTPATH__.'/app/Middlewares.php';
        require_once __ROOTPATH__.'/app/Routes.php';
    }

    public function dispatch(): Response
    {
        $routeInfo = $this->dispatcher()->dispatch($this->request->getMethod(), $this->request->getRequestUri());
        switch($routeInfo[0]){
            case Dispatcher::NOT_FOUND:
                return JsonResponse::create(
                    ['test' => 'yolo'],
                    JsonResponse::HTTP_NOT_FOUND
                );
                break;
            default:
                return $this->executeResponse($routeInfo[1], $routeInfo[2]);
                break;
        }
    }

    public static function GET(string $route, string $executor, array $middleware = [])
    {
        if(self::$instance)
            self::$instance->addRoute('GET', $route, $executor, $middleware);
    }

    public static function POST(string $route, string $executor, array $middleware = [])
    {
        if(self::$instance)
            self::$instance->addRoute('POST', $route, $executor, $middleware);
    }

    public static function PUT(string $route, string $executor, array $middleware = [])
    {
        if(self::$instance)
            self::$instance->addRoute('PUT', $route, $executor, $middleware);
    }

    public static function PATCH(string $route, string $executor, array $middleware = [])
    {
        if(self::$instance)
            self::$instance->addRoute('PATCH', $route, $executor, $middleware);
    }

    public static function DELETE(string $route, string $executor, array $middleware = [])
    {
        if(self::$instance)
            self::$instance->addRoute('DELETE', $route, $executor, $middleware);
    }

    public static function HEAD(string $route, string $executor, array $middleware = [])
    {
        if(self::$instance)
            self::$instance->addRoute('HEAD', $route, $executor, $middleware);
    }

    protected function addRoute(string $method = "GET", string $route, string $executor, array $middleware = [])
    {
        $this->{strtolower($method).'Routes'}[$route] = [$executor, $middleware];
    }

    protected function dispatcher(array $options = [])
    {
        $options += [
            'routeParser' => 'FastRoute\\RouteParser\\Std',
            'dataGenerator' => 'FastRoute\\DataGenerator\\GroupCountBased',
            'dispatcher' => 'FastRoute\\Dispatcher\\GroupCountBased',
            'routeCollector' => 'FastRoute\\RouteCollector',
        ];
        /** @var RouteCollector $routeCollector */
        $routeCollector = new $options['routeCollector'](
            new $options['routeParser'], new $options['dataGenerator']
        );
        $this->collectorCallback($routeCollector);
        return new $options['dispatcher']($routeCollector->getData());
    }

    protected function collectorCallback(RouteCollector $r)
    {
        foreach($this->getRoutes as $route => $handler)
            $r->get($route, $handler);
    }

    protected function executeResponse(array $middlewareList, array $routeParams): Response
    {
        //TODO controllers and middleware unified response, so can be used in one interface and loop
        foreach ($middlewareList[1] as $middleware)
        {
            $className = "App\\Middleware\\".$middleware."Middleware";
            /** @var AbstractMiddleware $class */
            $class = new $className($this->request);
            $middlewareResponse = $class->resolve();
            if($middlewareResponse instanceof Response)
                return $middlewareResponse;
        }
        list($className, $action) = explode('@', $middlewareList[0]);
        $className = "App\\Controllers\\".$className."Controller";
        $class = new $className($this->request);
        return JsonResponse::create(
            call_user_func_array(array($class, $action), $routeParams),
            Response::HTTP_OK
        );
    }
}