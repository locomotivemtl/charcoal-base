<?php

namespace Charcoal\Email;

use \Charcoal\Config\AbstractConfig as AbstractConfig;

class EmailConfig extends AbstractConfig
{
    public function default_data()
    {
        return [
            'smtp'=>false,
            'smtp_options'=>[],
            'default_from'=>null,
            
        ];
    }
}
