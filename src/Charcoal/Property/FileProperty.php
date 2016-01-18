<?php

namespace Charcoal\Property;

// Dependencies from `PHP`
use \Exception;
use \InvalidArgumentException;

// Dependencies from `PHP` extensions
use \finfo;
use \PDO;

// Module `charcoal-core` dependencies
use \Charcoal\Charcoal;
use \Charcoal\Property\AbstractProperty;

/**
 * File Property
 */
class FileProperty extends AbstractProperty
{

    /**
     * The upload path is a {{patern}}.
     * @var string $UploadPath
     */
    private $uploadPath = 'uploads/';

    /**
     * @var boolean $overwrite
     */
    private $overwrite = false;

    /**
     * @var string[] $acceptedMimetypes
     */
    private $acceptedMimetypes = [];

    /**
     * Maximum allowed file size, in bytes.
     * If null or 0, then no limit.
     * Default to 128M
     * @var integer $maxFilesize
     */
    private $maxFilesize = 134220000;

    /**
     * Current file mimetype
     *
     * @var string $mimetype
     */
    private $mimetype;

    /**
     * Current file size, in bytes.
     *
     * @var integer $Filesize
     */
    private $filesize;

    /**
     * @return string
     */
    public function type()
    {
        return 'file';
    }

    /**
     * @param string $uploadPath The upload path, relative to project's root.
     * @throws InvalidArgumentException If the upload path is not a string.
     * @return FileProperty Chainable
     */
    public function setUploadPath($uploadPath)
    {
        if (!is_string($uploadPath)) {
            throw new InvalidArgumentException(
                'Upload path must be a string'
            );
        }
        // Sanitize upload path (force trailing slash)
        $this->uploadPath = rtrim($uploadPath, '/').'/';
        return $this;
    }

    /**
     * @return string
     */
    public function uploadPath()
    {
        return $this->uploadPath;
    }

    /**
     * @param boolean $overwrite The overwrite flag.
     * @return FileProperty Chainable
     */
    public function setOverwrite($overwrite)
    {
        $this->overwrite = !!$overwrite;
        return $this;
    }

    /**
     * @return boolean
     */
    public function overwrite()
    {
        return !!$this->overwrite;
    }

    /**
     * @param string[] $mimetypes The accepted mimetypes.
     * @return FileProperty Chainable
     */
    public function setAcceptedMimetypes(array $mimetypes)
    {
        $this->acceptedMimetypes = $mimetypes;
        return $this;
    }

    /**
     * @return array
     */
    public function acceptedMimetypes()
    {
        return $this->acceptedMimetypes;
    }

    /**
     * @param integer $size The maximum file size allowed, in bytes.
     * @throws InvalidArgumentException If the size argument is not an integer.
     * @return FileProperty Chainable
     */
    public function setMaxFilesize($size)
    {
        if (!is_int($size)) {
            throw new InvalidArgumentException(
                'Max filesize must be an integer, in bytes.'
            );
        }
        $this->maxFilesize = $size;
        return $this;
    }

    /**
     * @return integer
     */
    public function maxFilesize()
    {
        return $this->maxFilesize;
    }

    /**
     * @param string $mimetype The file mimetype.
     * @throws InvalidArgumentException If the mimetype argument is not a string.
     * @return FileProperty Chainable
     */
    public function setMimetype($mimetype)
    {
        if (!is_string($mimetype)) {
            throw new InvalidArgumentException(
                'Mimetype must be a string'
            );
        }
        $this->mimetype = $mimetype;
        return $this;
    }

    /**
     * @return string
     */
    public function mimetype()
    {
        if (!$this->mimetype) {
            // Get mimetype from file
            $val = $this->val();
            if (!$val) {
                return '';
            }
            $info = new finfo(FILEINFO_MIME_TYPE);
            $this->mimetype = $info->file($val);
        }
        return $this->mimetype;
    }

    /**
     * @param integer $size The file size, in bytes.
     * @throws InvalidArgumentException If the size argument is not an integer.
     * @return FileProperty Chainable
     */
    public function setFilesize($size)
    {
        if (!is_int($size)) {
            throw new InvalidArgumentException(
                'Filesize must be an integer, in bytes.'
            );
        }
        $this->filesize = $size;
        return $this;
    }

    /**
     * @return integer
     */
    public function filesize()
    {
        if (!$this->filesize) {
            $val = $this->val();
            if (!$val || !file_exists($val) || !is_readable($val)) {
                return 0;
            } else {
                $this->filesize = filesize($val);
            }
        }
        return $this->filesize;
    }

    /**
     * @return array
     */
    public function validationMethods()
    {
        $parentMethods = parent::validationMethods();
        return array_merge($parentMethods, ['accepted_mimetypes', 'max_filesize']);
    }

    /**
     * @return boolean
     */
    public function validateAcceptedMimetypes()
    {
        $acceptedMimetypes = $this->acceptedMimetypes();
        if (empty($acceptedMimetypes)) {
            // No validation rules = always true
            return true;
        }

        if ($this->mimetype) {
            $mimetype = $this->mimetype;
        } else {
            $val = $this->val();
            if (!$val) {
                return true;
            }
            $info = new finfo(FILEINFO_MIME_TYPE);
            $mimetype = $info->file($val);
        }
        $valid = false;
        foreach ($acceptedMimetypes as $m) {
            if ($m == $mimetype) {
                $valid = true;
                break;
            }
        }
        if (!$valid) {
            $this->validator()->error('Accepted mimetypes error', 'acceptedMimetypes');
        }

        return $valid;
    }

    /**
     * @return boolean
     */
    public function validateMaxFilesize()
    {
        $maxFilesize = $this->maxFilesize();
        if ($maxFilesize == 0) {
            // No max size rule = always true
            return true;
        }

        $filesize = $this->filesize();
        $valid = ($filesize <= $maxFilesize);
        if (!$valid) {
            $this->validator()->error('Max filesize error', 'maxFilesize');
        }

        return $valid;
    }

    /**
     * @return string
     */
    public function sqlExtra()
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
    public function sqlType()
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
    public function sqlPdoType()
    {
        return PDO::PARAM_STR;
    }

    /**
     * @return string
     */
    public function save()
    {
        $i = $this->ident();

        if (isset($_FILES[$i])
            && (isset($_FILES[$i]['name']) && $_FILES[$i]['name'])
            && (isset($_FILES[$i]['tmp_name']) && $_FILES[$i]['tmp_name'])) {
            $f = $this->fileUpload($_FILES[$i]);
            $this->set_val($f);
            return $f;
        } elseif (preg_match('/^data:/', $this->val())) {
            $f = $this->dataUpload($this->val());
            $this->set_val($f);
            return $f;
        }
        return $this->val();
    }

    /**
     * Upload to filesystem, from data: content.
     *
     * @param string $fileData The file data, raw.
     * @throws Exception If data content decoding fails.
     * @return string
     */
    public function dataUpload($fileData)
    {
        $file_content = file_get_contents($fileData);
        if ($file_content === false) {
            throw new Exception(
                'File content could not be decoded.'
            );
        }

        $info = new finfo(FILEINFO_MIME_TYPE);
        $this->setMimetype($info->buffer($file_content));
        $this->setFilesize(strlen($file_content));
        if (!$this->validateAcceptedMimetypes() || !$this->validateMaxFilesize()) {
            return '';
        }

        $target = $this->uploadTarget();

        $ret = file_put_contents($target, $file_content);
        if ($ret === false) {
            return '';
        } else {
            if (class_exists('\Charcoal\App\App')) {
                $basePath = \Charcoal\App\App::instance()->config()->get('ROOT');
                $target = str_replace($basePath, '', $target);
            }

            return $target;
        }
    }

    /**
     * @param array $fileData The file data (from $_FILES, typically).
     * @throws InvalidArgumentException If the FILES data argument is missing `name` or `tmp_name`.
     * @return string
     */
    public function fileUpload(array $fileData)
    {
        if (!isset($fileData['name'])) {
            throw new InvalidArgumentException(
                'File data is invalid'
            );
        }

        if (!file_exists($fileData['tmp_name'])) {
            throw new InvalidArgumentException(
                'The uploaded file could not be read (does not exist)'
            );
        }

        $info = new finfo(FILEINFO_MIME_TYPE);
        $this->setMimetype($info->file($fileData['tmp_name']));
        $this->setFilesize(filesize($fileData['tmp_name']));
        if (!$this->validateAcceptedMimetypes() || !$this->validateMaxFilesize()) {
            return '';
        }

        $target = $this->uploadTarget($fileData['name']);

        $ret = moveUploadedFile($fileData['tmp_name'], $target);
        if ($ret === false) {
            return '';
        } else {
            if (class_exists('\Charcoal\App\App')) {
                $basePath = \Charcoal\App\App::instance()->config()->get('ROOT');
                $target = str_replace($basePath, '', $target);
            }

            return $target;
        }
    }

    /**
     * @param string $filename Optional. The filename to save. If unset, a default filename will be generated.
     * @throws Exception If the target path is not writeable.
     * @return string
     */
    public function uploadTarget($filename = null)
    {
        if (class_exists('\Charcoal\App\App')) {
            $basePath = \Charcoal\App\App::instance()->config()->get('ROOT');
        } else {
            $basePath = '';
        }

        $dir = $basePath.$this->uploadPath();
        $filename = ($filename) ? $this->sanitizeFilename($filename) : $this->generateFilename();

        if (!file_exists($dir)) {
            // @todo: Feedback
            $this->logger()->debug(
                'Path does not exist. Attempting to create path.',
                [get_called_class().'::'.__FUNCTION__]
            );
            mkdir($dir, 0777, true);
        }
        if (!is_writable($dir)) {
            throw new Exception(
                'Error: upload directory is not writeable'
            );
        }

        $target = $dir.$filename;

        if ($this->fileExists($target)) {
            if ($this->overwrite() === true) {
                return $target;
            } else {
                // Can not overwrite. Must rename file. (@todo)
                $info = pathinfo($filename);

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
     * @param string  $file             The full file to check.
     * @param boolean $case_insensitive Optional. Case insensitive flag.
     * @return boolean
     */
    public function fileExists($file, $case_insensitive = true)
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
     * Sanitize a filename by removing characters from a blacklist and escaping dot.
     *
     * @param string $filename The filename to sanitize.
     * @return string The sanitized filename.
     */
    public function sanitizeFilename($filename)
    {
        // Remove blacklisted caharacters
        $blacklist = ['/', '\\', '\0', '*', ':', '?', '"', '<', '>', '|', '#', '&', '!', '`'];
        $filename = str_replace($blacklist, '_', $filename);

        // Avoid hidden file
        $filename = ltrim($filename, '.');

        return $filename;
    }

    /**
     * @return string
     */
    public function generateFilename()
    {
        $filename = $this->label().' '.date('Y-m-d H-i-s');
        $extension = $this->generateExtension();

        if ($extension) {
            return $filename.'.'.$extension;
        } else {
            return $filename;
        }
    }

    /**
     * @return string
     */
    public function generateExtension()
    {
        $mimetype = $this->mimetype();
        return '';
    }
}
