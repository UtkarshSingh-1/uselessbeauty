<?php
defined('ABSPATH') || die();
/** @var $this NextendSocialProviderAdmin */

$provider = $this->getProvider();
?>
<ol>
    <li><?php printf(__('Navigate to %s', 'nextend-facebook-connect'), '<a href="https://id.vk.com/about/business/go/" target="_blank">https://id.vk.com/about/business/go/</a>'); ?></li>
    <li><?php printf(__('Log in with your %s credentials if you are not logged in', 'nextend-facebook-connect'), 'VKontakte'); ?></li>
    <li><?php _e('Click on the card associated with your App.', 'nextend-facebook-connect'); ?></li>
    <li><?php printf(__('Select the %s option from the side bar on the left side, if it is not selected already.', 'nextend-facebook-connect'), '"<b>App</b>"'); ?></li>
    <li><?php printf(__('Scroll down to the %s section.', 'nextend-facebook-connect'), '"<b>Connect authorization</b>"'); ?></li>
    <li><?php
        $loginUrls = $provider->getAllRedirectUrisForAppCreation();
        printf(__('Add the following URL to the %s field: ', 'nextend-facebook-connect'), '"<b>Trusted redirect URL</b>"');
        echo "<ul>";
        foreach ($loginUrls as $loginUrl) {
            echo "<li><strong>" . $loginUrl . "</strong></li>";
        }
        echo "</ul>";
        ?>
    </li>
    <li><?php printf(__('Click on %s button to save the changes.', 'nextend-facebook-connect'), '"<b>Save</b>"'); ?></li>

</ol>