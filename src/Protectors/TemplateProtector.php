<?php

namespace VSPW\Protectors;

use VSPW\Protectors\Interfaces\ProtectorInterface;

class TemplateProtector implements ProtectorInterface
{
	protected $output;

    /**
     * Disables website template loading for unauthenticated users.
     */
    public function protect()
    {
        if (apply_filters('vspw_protect_template', true)) {
        	// Store the template in a variable
	        ob_start();
	        require_once(VSPW_PATH . '/views/protected.php');
	        $output = ob_get_clean();

	        // Then allow third-party to filter it
        	$this->output = apply_filters('vspw_template', $output);

            add_filter('template_include', function() {
                echo $this->output;
            });
        }
    }
}