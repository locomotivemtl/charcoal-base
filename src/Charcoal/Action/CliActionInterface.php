<?php

namespace Charcoal\Action;

interface CliActionInterface
{

    /**
    * @param array $data
    * @return CliActionInterface Chainable
    */
    public function set_cli_data($data);

    /**
    * @param string $ident
    * @return CliActionInterface Chainable
    */
    public function set_ident($ident);
    
    /**
    * @return string
    */
    public function ident();

    /**
    * @param string $description
    * @return CliActionInterface Chainable
    */
    public function set_description($description);

    /**
    * @return string
    */
    public function description();
    
    /**
    * @param array $arguments
    * @return CliActionInterface Chainable
    */
    public function set_arguments($arguments);
    /**
    * @param string $argument_ident
    * @param array  $argument
    * @return CliActionInterface Chainable
    */
    public function add_argument($argument_ident, $argument);

    /**
    * @return array $arguments
    */
    public function arguments();

    /**
    * @param string $argument_ident
    * @return array
    */
    public function argument($argument_ident);

    /**
    * @param string $arg_name
    * @return array
    */
    public function arg_or_input($arg_name);

    /**
    * @return string
    */
    public function help();
}
