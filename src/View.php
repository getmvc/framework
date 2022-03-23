<?php
namespace GetMVC\Framework;


class View {
    
    public static $rootPath;

    protected $data;
    
    public function render($view) {
        //add configs to data
        $this->data('configs', Configs::GetAll());
        
        //$data is accessible in the view
        $data = $this->data;
        
        ob_start();
        require Configs::$rootPath . "/views/$view.php";
        $str = ob_get_contents();
        ob_end_clean();
        return $str;
    }

    function data($key, $val) {
        $this->data[$key] = $val;
    }
    
}