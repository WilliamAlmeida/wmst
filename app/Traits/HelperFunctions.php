<?php

namespace App\Traits;

trait HelperFunctions
{
    public function linkWhatsapp($phone): string
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);
        $phone = (str_starts_with($phone, '55') ? '' : '55') . $phone;
        return $this->isMobile() ? 'https://api.whatsapp.com/send?phone=' . $phone : 'https://web.whatsapp.com/send?phone=' . $phone;
    }

    public function isMobile() {
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
    
        $mobileAgents = [
            'Mobile', 'Android', 'Silk/', 'Kindle', 'BlackBerry', 'Opera Mini', 'Opera Mobi'
        ];
    
        foreach ($mobileAgents as $agent) {
            if (strpos($userAgent, $agent) !== false) {
                return true;
            }
        }
    
        return false;
    }
    
    public function isDesktop() {
        return !$this->isMobile();
    }
}
