<?php

namespace Charcoal\Action;

use \InvalidArgumentException as InvalidArgumentException;

use \League\CLImate\CLImate as CLImate;

trait CliActionTrait
{
    /**
    * @var string $_ident
    */
    protected $_ident;
    /**
    * @var string $_description
    */
    protected $_description;
    /**
    * @var array $_arguments
    */
    protected $_arguments;

    /**
    * @var CLImate $_climate
    */
    private $_climate;

    /**
    * @var boolean $_verbose
    */
    protected $_verbose;

    /**
    * @return CLImate
    */
    public function climate()
    {
        if ($this->_climate === null) {
            $this->_climate = new CLImate();
        }
        return $this->_climate;
    }

    /**
    * @return array
    */
    public function default_arguments()
    {
        return [
            'help' => [
                'longPrefix'   => 'help',
                'description'  => 'Prints a usage statement',
                'noValue'      => true
            ],
            'quiet' => [
                'prefix'       => 'q',
                'longPrefix'   => 'quiet',
                'description'  => 'Disable Output additional information on operations',
                'noValue'      => false
            ]
        ];
    }

    /**
    * @param array $data
    * @throws InvalidArgumentException
    * @return CliActionInterface Chainable
    */
    public function set_cli_data($data)
    {
        if (!is_array($data)) {
            throw new InvalidArgumentException('Data must be an array');
        }

        if (isset($data['ident']) && $data['ident'] !== null) {
            $this->set_ident($data['ident']);
        }
        if (isset($data['description']) && $data['description'] !== null) {
            $this->set_description($data['description']);
        }
        if (isset($data['arguments']) && $data['arguments'] !== null) {
            $this->set_arguments($data['arguments']);
        }

        return $this;
    }

    /**
    * @param string $ident
    * @throws InvalidArgumentException
    * @return CliActionInterface Chainable
    */
    public function set_ident($ident)
    {
        if (!is_string($ident)) {
            throw new InvalidArgumentException('Ident must be a string');
        }
        $this->_ident = $ident;
        return $this;
    }
    
    /**
    * @return string
    */
    public function ident()
    {
        return $this->_ident;
    }

    /**
    * @param string $description
    * @throws InvalidArgumentException
    * @return CliActionInterface Chainable
    */
    public function set_description($description)
    {
        if (!is_string($description)) {
            throw new InvalidArgumentException('Description must be a string');
        }
        $this->_description = $description;
        $this->climate()->description($description);
        return $this;
    }

    /**
    * @return string
    */
    public function description()
    {
        return $this->_description;
    }
    
    /**
    * @param array $arguments
    * @throws InvalidArgumentException
    * @return CliActionInterface Chainable
    */
    public function set_arguments($arguments)
    {
        if (!is_array($arguments)) {
            throw new InvalidArgumentException('Arguments must be an array');
        }
        $this->_arguments = [];
        foreach ($arguments as $argument_ident => $argument) {
            $this->add_argument($argument_ident, $argument);
        }

        return $this;
    }

    /**
    * @param string $argument_ident
    * @param array  $argument
    * @return CliActionInterface Chainable
    */
    public function add_argument($argument_ident, $argument)
    {
        $this->_arguments[$argument_ident] = $argument;
        $this->climate()->arguments->add([$argument_ident=>$argument]);
        return $this;
    }

    /**
    * @return array $arguments
    */
    public function arguments()
    {
        return $this->_arguments;
    }

    /**
    * @param string $argument_ident
    * @return array
    */
    public function argument($argument_ident)
    {
        if (!isset($this->_arguments[$argument_ident])) {
            return null;
        }
        return $this->_arguments[$argument_ident];
    }

    /**
    * Get an argument either from argument list (if set) or else from an input prompt.
    *
    * @param string $arg_name
    * @return string The argument value or prompt value
    */
    public function arg_or_input($arg_name)
    {
        $climate = $this->climate();
        $arg = $climate->arguments->get($arg_name);
        if ($arg) {
            return $arg;
        } else {
            $arguments = $this->arguments();
            if (isset($arguments[$arg_name])) {
                $a = $arguments[$arg_name];
                $arg_desc = $a['description'];
                $input_type = isset($a['inputType']) ? $a['inputType'] : 'text';
                $choices = isset($a['choices']) ? $a['choices'] : null;
            
            } else {
                $arg_desc = $arg_name;
                $input_type = 'text';
                $choices = null;
            }
            if ($input_type == 'checkbox') {
                $input = $climate->checkboxes(sprintf('Select %s', $arg_desc), $choices);
            } else {
                $input = $climate->input(sprintf('Enter %s:', $arg_desc));
                if ($choices) {
                    $input->accept(array_keys($choices), true);
                }
            }
            $arg = $input->prompt();
            return $arg;
        }
    }

    /**
    * @param boolean $verbose
    * @throws InvalidArgumentException
    * @return CliActionTrait Chainable
    */
    public function set_verbose($verbose)
    {
        if (!is_bool($verbose)) {
            throw new InvalidArgumentException('Verbose flag must be a boolean');
        }
        $this->_verbose = $verbose;
        return $this;
    }

    /**
    * @return boolean
    */
    public function verbose()
    {
        return $this->_verbose;
    }

    /**
    * @return string
    */
    public function help()
    {
        return $this->climate()->usage();
    }
}
