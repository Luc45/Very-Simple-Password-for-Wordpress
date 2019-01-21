<?php

namespace VSPW\Protectors;

use VSPW\Protectors\Interfaces\ProtectorInterface;

class FeedProtector implements ProtectorInterface
{
    /**
     * Protects Feed from unauthenticated users.
     */
    public function protect()
    {
        if (apply_filters('vspw_protect_feed', true)) {
            add_action('do_feed',               [$this, 'disableFeed'], 1);
            add_action('do_feed_rdf',           [$this, 'disableFeed'], 1);
            add_action('do_feed_rss',           [$this, 'disableFeed'], 1);
            add_action('do_feed_rss2',          [$this, 'disableFeed'], 1);
            add_action('do_feed_atom',          [$this, 'disableFeed'], 1);
            add_action('do_feed_rss2_comments', [$this, 'disableFeed'], 1);
            add_action('do_feed_atom_comments', [$this, 'disableFeed'], 1);
        }
    }

    public function disableFeed()
    {
        wp_die(__('Unauthenticated Feed Requests are blocked by Very Simple Password for WordPress plugin.', 'very-simple-password'));
    }
}