<?php

namespace Charcoal\Property;

use \PDO as PDO;
use \Exception as Exception;
use \InvalidArgumentException as InvalidArgumentException;

// In charcoal-core
use \Charcoal\Charcoal as Charcoal;
use \Charcoal\Property\AbstractProperty as AbstractProperty;

class FileProperty extends AbstractProperty
{

    /**
    * The upload path is a {{patern}}.
    * @var string $_upload_path
    */
    private $_upload_path = 'uploads/';

    /**
    * @var boolean $_overwrite
    */
    private $_overwrite = false;

    /**
    * @var array $_accepted_mimetypes
    */
    private $_accepted_mimetypes = [];

    /**
    * Maximum allowed file size, in bytes.
    * If null or 0, then no limit.
    * @var integer $_max_filesize
    */
    private $_max_filesize;

    /**
    * @return string
    */
    public function type()
    {
        return 'file';
    }

    /**
    * @param array $data
    * @throws InvalidArgumentException
    * @return StringProperty Chainable
    */
    public function set_data($data)
    {

        if (!is_array($data)) {
            throw new InvalidArgumentException('Data must be an array');
        }

        parent::set_data($data);

        if (isset($data['upload_path']) && $data['upload_path'] !== null) {
            $this->set_upload_path($data['upload_path']);
        }
        if (isset($data['overwrite']) && $data['overwrite'] !== null) {
            $this->set_overwrite($data['overwrite']);
        }
        if (isset($data['accepted_mimetypes']) && $data['accepted_mimetypes'] !== null) {
            $this->set_accepted_mimetypes($data['accepted_mimetypes']);
        }
        if (isset($data['max_filesize']) && $data['max_filesize'] !== null) {
            $this->set_max_filesize($data['max_filesize']);
        }
        return $this;
    }

    /**
    * @param string $upload_path
    * @throws InvalidArgumentException
    * @return FileProperty Chainable
    */
    public function set_upload_path($upload_path)
    {
        if (!is_string($upload_path)) {
            throw new InvalidArgumentException('Upload path must be a string');
        }
        $this->_upload_path = $upload_path;
        return $this;
    }

    /**
    * @return string
    */
    public function upload_path()
    {
        return rtrim($this->_upload_path, '/').'/';
    }

    /**
    * @param boolean $overwrite
    * @throws InvalidArgumentException
    * @return FileProperty Chainable
    */
    public function set_overwrite($overwrite)
    {
        if (!is_bool($overwrite)) {
            throw new InvalidArgumentException('Overwrite must be a boolean');
        }
        $this->_overwrite = $overwrite;
        return $this;
    }

    /**
    * @return boolean
    */
    public function overwrite()
    {
        return !!$this->_overwrite;
    }

    /**
    * @param array $mimetypes
    * @throws InvalidArgumentException
    * @return FileProperty Chainable
    */
    public function set_accepted_mimetypes($mimetypes)
    {
        if (!is_array($mimetypes)) {
            throw new InvalidArgumentException('Accepted mimetypes must be an array');
        }
        $this->_accepted_mimetypes = $mimetypes;
        return $this;
    }

    /**
    * @return array
    */
    public function accepted_mimetypes()
    {
        return $this->_accepted_mimetypes;
    }

    /**
    * @param integer $size
    * @throws InvalidArgumentException
    * @return FileProperty Chainable
    */
    public function set_max_filesize($size)
    {
        if (!is_int($size)) {
            throw new InvalidArgumentException('Max filesize must be an integer, in bytes.');
        }
        $this->_max_filesize = $size;
        return $this;
    }

    /**
    * @return integer
    */
    public function max_filesize()
    {
        return $this->_max_filesize;
    }

    /**
    * @return string
    */
    public function sql_extra()
    {
        return '';
    }

    /**
    * Get the SQL type (Storage format)
    *
    * Stored as `VARCHAR` for max_length under 255 and `TEXT` for other, longer strings
    *
    * @return string The SQL type
    */
    public function sql_type()
    {
        // Multiple strings are always stored as TEXT because they can hold multiple values
        if ($this->multiple()) {
            return 'TEXT';
        } else {
            return 'VARCHAR(255)';
        }
    }

    /**
    * @return integer
    */
    public function sql_pdo_type()
    {
        return PDO::PARAM_STR;
    }

    public function save()
    {
        if (preg_match('/^data:/', $this->val())) {
            $f = $this->data_upload($this->val());
            $this->set_val($f);
            return $f;
        }
        return $this->val();
    }

    public function data_upload($data)
    {
        $data = explode(',', $data);
        if (!isset($data[1])) {
            throw new InvalidArgumentException('Data was not a properly data-encoded, base64-encoded file.');
        }
        $file = $this->upload_target();
        $ret = file_put_contents($file, base64_decode($data[1]));
        if ($ret === false) {
            return '';
        } else {
            return $file;
        }
    }

    /**
    * @param string $filename
    * @throws Exception
    * @return string
    */
    public function upload_target($filename=null)
    {
        $base_path = rtrim(Charcoal::config()->ROOT, '/').'/';
        $upload_path = $this->upload_path();
        $dir = $base_path.$upload_path;
        $filename = ($filename) ? $filename : $this->generate_filename();

        if (!file_exists($dir)) {
            // @todo: Feedback
            mkdir($dir, 0777, true);
        }
        if (!is_writable($dir)) {
            throw new Exception('Error: upload directory is not writeable');
        }

        $target = $dir.$filename;

        if (file_exists($target)) {
            if ($this->overwrite() === true) {
                return $target;
            } else {
                // Can not overwrite. Must rename file. (@todo)
                return $target;
            }
        }
        
        return $target;
    }

    public function generate_filename()
    {
        return $this->label().' '.date('Y-m-d H-i-s');
    }
}
