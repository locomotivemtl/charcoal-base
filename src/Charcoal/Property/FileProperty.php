<?php

namespace Charcoal\Property;

use \finfo as finfo;
use \PDO as PDO;
use \Exception as Exception;
use \InvalidArgumentException as InvalidArgumentException;

// In charcoal-core
use \Charcoal\Charcoal as Charcoal;
use \Charcoal\Property\AbstractProperty as AbstractProperty;

/**
*
*/
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
    private $_max_filesize = 134220000; //128M

    private $_mimetype;
    private $_filesize;

    /**
    * @return string
    */
    public function type()
    {
        return 'file';
    }

    /**
    * @param array $data
    * @return FileProperty Chainable
    */
    public function set_data(array $data)
    {
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
    * @param string $mimetype
    * @throws InvalidArgumentException
    * @return FileProperty Chainable
    */
    public function set_mimetype($mimetype)
    {
        if (!is_string($mimetype)) {
            throw new InvalidArgumentException('Mimetype must be a string');
        }
        $this->_mimetype = $mimetype;
        return $this;
    }

    /**
    * @return string
    */
    public function mimetype()
    {
        if (!$this->_mimetype) {
            // Get mimetype from file
            $val = $this->val();
            if (!$val) {
                return '';
            }
            $info = new finfo(FILEINFO_MIME_TYPE);
            $this->_mimetype = $info->file($val);
        }
        return $this->_mimetype;
    }

    /**
    * @param integer $size
    * @throws InvalidArgumentException
    * @return FileProperty Chainable
    */
    public function set_filesize($size)
    {
        if (!is_int($size)) {
            throw new InvalidArgumentException('Filesize must be an integer, in bytes');
        }
        $this->_filesize = $size;
        return $this;
    }

    /**
    * @return integer
    */
    public function filesize()
    {
        if (!$this->_filesize) {
            $val = $this->val();
            if (!$val) {
                return 0;
            }
            return 0;
            //            $this->_filesize = filesize($val);
        }
        return $this->_filesize;
    }

    /**
    * @return array
    */
    public function validation_methods()
    {
        $parent_methods = parent::validation_methods();
        return array_merge($parent_methods, ['accepted_mimetypes', 'max_filesize']);
    }

    /**
    * @return boolean
    */
    public function validate_accepted_mimetypes()
    {
        $accepted_mimetypes = $this->accepted_mimetypes();
        if (empty($accepted_mimetypes)) {
            // No validation rules = always true
            return true;
        }

        if ($this->_mimetype) {
            $mimetype = $this->_mimetype;
        } else {
            $val = $this->val();
            if (!$val) {
                return true;
            }
            $info = new finfo(FILEINFO_MIME_TYPE);
            $mimetype = $info->file($val);
        }
        //var_dump($mimetype);
        $valid = false;
        foreach ($accepted_mimetypes as $m) {
            if ($m == $mimetype) {
                $valid = true;
                break;
            }
        }
        if (!$valid) {
            $this->validator()->error('Accepted mimetypes error', 'accepted_mimetypes');
        }

        return $valid;
    }

    /**
    * @return boolean
    */
    public function validate_max_filesize()
    {
        $max_filesize = $this->max_filesize();
        if ($max_filesize == 0) {
            // No max size rule = always true
            return true;
        }

        $filesize = $this->filesize();
        $valid = ($filesize <= $max_filesize);
        if (!$valid) {
            $this->validator()->error('Max filesize error', 'max_filesize');
        }

        return $valid;
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

    /**
    * @return string
    */
    public function save()
    {
        $i = $this->ident();
        if (isset($_FILES[$i]) && (isset($_FILES[$i]['name']) && $_FILES[$i]['name']) && (isset($_FILES[$i]['tmp_name']) && $_FILES[$i]['tmp_name'])) {
            $f = $this->file_upload($_FILES[$i]);
            $this->set_val($f);
            return $f;
        } else if (preg_match('/^data:/', $this->val())) {
            $f = $this->data_upload($this->val());
            $this->set_val($f);
            return $f;
        }
        return $this->val();
    }

    /**
    * @param string $file_data
    * @throws Exception
    * @return string
    */
    public function data_upload($file_data)
    {
        $file_content = file_get_contents($file_data);
        if ($file_content === false) {
            throw new Exception('File content could not be decoded.');
        }
        
        $info = new finfo(FILEINFO_MIME_TYPE);
        $this->set_mimetype($info->buffer($file_content));
        $this->set_filesize(strlen($file_content));
        if (!$this->validate_accepted_mimetypes() || !$this->validate_max_filesize()) {
            return '';
        }

        $target = $this->upload_target();

        $ret = file_put_contents($target, $file_content);
        if ($ret === false) {
            return '';
        } else {
            return $target;
        }
    }

    /**
    * @param array $file_data
    * @throws InvalidArgumentException
    * @return string
    */
    public function file_upload(array $file_data)
    {
        if (!isset($file_data['name'])) {
            throw new InvalidArgumentException('File data is invalid');
        }

        $target = $this->upload_target($file_data['name']);
        
        $info = new finfo(FILEINFO_MIME_TYPE);
        $this->set_mimetype($info->file($file_data['tmp_name']));
        $this->set_filesize(filesize($file_data['tmp_name']));
        if (!$this->validate_accepted_mimetypes() || !$this->validate_max_filesize()) {
            return '';
        }


        $ret = move_uploaded_file($file_data['tmp_name'], $target);
        if ($ret === false) {
            return '';
        } else {
            return $target;
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
        $filename = ($filename) ? $this->sanitize_filename($filename) : $this->generate_filename();

        if (!file_exists($dir)) {
            // @todo: Feedback
            mkdir($dir, 0777, true);
        }
        if (!is_writable($dir)) {
            throw new Exception('Error: upload directory is not writeable');
        }

        $target = $dir.$filename;

        if ($this->file_exists($target)) {
            if ($this->overwrite() === true) {
                return $target;
            } else {
                // Can not overwrite. Must rename file. (@todo)
                $info = pathinfo($filename);
                //var_dump($info);

                $filename = $info['filename'].'-'.uniqid();
                if (isset($info['extension']) && $info['extension']) {
                    $filename .= '.'.$info['extension'];
                }
                $target = $dir.$filename;
            }
        }
        
        return $target;
    }

    /**
    * This function checks if a file exist, by default in a case-insensitive manner.
    *
    * PHP builtin's `file_exists` is only case-insensitive on case-insensitive filesystem (such as windows)
    * This method allows to have the same validation across different platforms / filesystem.
    *
    * @param string $file
    * @param boolean $case_insensitive
    * @return boolean
    */
    public function file_exists($file, $case_insensitive=true)
    {
        if (file_exists($file)) {
            return true;
        }
        if ($case_insensitive === false) {
            return false;
        }

        $files = glob(dirname($file).'/*', GLOB_NOSORT);
        foreach ($files as $f) {
            if (preg_match("#{$file}#i", $f)) {
                return true;
            }
        }

        return false;
    }

    /**
    * @return string
    */
    public function sanitize_filename($filename)
    {
        $filename = str_replace(['/', '\\', '\0', '*', ':', '?', '"', '<', '>', '|'], '_', $filename);
        $filename = ltrim($filename, '.');
        return $filename;
    }

    /**
    * @return string
    */
    public function generate_filename()
    {
        $filename = $this->label().' '.date('Y-m-d H-i-s');
        $extension = $this->generate_extension();
        
        if ($extension) {
            return $filename.'.'.$extension;
        } else {
            return $filename;
        }
    }

    /**
    * @return string
    */
    public function generate_extension()
    {
        $mimetype = $this->mimetype();
    }
}
