<?php
namespace GetMVC\Framwork;

class Router {
    
    private static $routes = array();
    
    /**
     * Register a new route
     * 
     * @param string $name The name of this route. Can be anything.
     * @param string $method HTTP method used for this route (GET, POST, PUT, DELETE, etc.)
     * @param string $path Ex: <br> /store <br> /store/categories <br> /store/category/$id <br> /seller/$id/profile
     * @param string $function Function to call
     */
    public static function add_route($name, $method, $path, $function) {
    
        self::$routes[$name] = array('method' => strtoupper($method), 'path' => $path, 'function' => $function);
        
    }
    
    /**
     * Try to find a registed route that match the current URI
     * and call the specified function.
     */
    public static function parse() {
        
        $path = filter_var($_SERVER['REQUEST_URI'],FILTER_SANITIZE_URL);
        
        
        if (strpos($path, '?')){
            $path = strtok($path, '?');
        }
        
        $path_parts = explode('/', $path);
        $path_parts = array_filter($path_parts, 'strlen'); //delete empty values
        $path_parts = array_values($path_parts);
        
        //Test each route
        foreach (self::$routes as $route) {
            
            //If method doesn't match, stop evaluate this route
            if ($_SERVER['REQUEST_METHOD'] != $route['method']) { continue; }
            
            $route_parts = explode('/', $route['path']);
            $route_parts = array_filter($route_parts, 'strlen');
            $route_parts = array_values($route_parts);
            
            //If number of parts doesn't fit, stop evaluate this route
            if (count($route_parts) != count($path_parts)){ continue; }
            
            //Test each parts of the route
            $match = true;
            $args = array();
            for($i=0 ; $i < count($route_parts) ; $i++){
                
                //If this route part begin with $, it's an argument. Add it to the $args array and pass to the next part
                if ( preg_match("/^[$]/", $route_parts[$i]) ){
                    array_push($args,$path_parts[$i]);
                    continue;
                }
                
                //If the path part doesn't fit the route part, stop evaluate this route
                if ( $route_parts[$i] != $path_parts[$i] ){ 
                    $match = false;
                    break;
                }
         
            }
            
            //If the route match the path, call the specified function and stop parsing
            if ($match){
                call_user_func_array($route['function'], $args);
                return;
            }
        }
        
        
        //Route dosen't existe. 404
        if (!empty(self::$routes['404'])){
            self::call('404');
        } else {
            header("HTTP/1.0 404 Not Found");
        }
        exit();
        
    }
    
    /**
     * Redirect to specified route. <br>
     * Only GET route can be use with this function. <br>
     * Only route without argument ($) can be used with this function. <br>
     * 
     * @param string $route_name Name of the route
     * @throws Exception 
     */
    public static function redirect($route_name) {
        $path = self::$routes[$route_name]['path'];
        if (empty($path)){
            throw new Exception('Redirection failed. There is no route called "'.$route_name.'"');
        }
        if (self::$routes[$route_name]['method'] != 'GET'){
            throw new Exception('Redirection failed. The route "'.$route_name.'" does not use GET method.');
        }
        if (strpos(self::$routes[$route_name]['path'],'$')){
            throw new Exception('Redirection failed. The route "'.$route_name.'" needs arguments.');
        }
        header('location:'.$path);
    }
    
    /**
     * Call the specified route Controller function without redirect. <br>
     * Only GET route can be use with this function. <br>
     * Only route without argument ($) can be used with this function <br>
     * 
     * @param string $route_name Name of the route
     * @throws Exception 
     */
    public static function call($route_name) {
        $path = self::$routes[$route_name]['path'];
        if (empty($path)){
            throw new Exception('Redirection failed. There is no route called "'.$route_name.'"');
        }
        if (self::$routes[$route_name]['method'] != 'GET'){
            throw new Exception('Redirection failed. The route "'.$route_name.'" does not use GET method.');
        }
        if (strpos(self::$routes[$route_name]['path'],'$')){
            throw new Exception('Redirection failed. The route "'.$route_name.'" needs arguments.');
        }
        call_user_func(self::$routes[$route_name]['function']);
    }

}
