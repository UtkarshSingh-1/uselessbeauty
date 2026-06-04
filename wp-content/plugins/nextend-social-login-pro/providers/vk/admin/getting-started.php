<?php
defined('ABSPATH') || die();
/** @var $this NextendSocialProviderAdmin */

$lastUpdated = '2025-08-08';

$provider = $this->getProvider();
?>
<div class="nsl-admin-sub-content">
    <div class="nsl-admin-getting-started">
        <h2 class="title"><?php _e('Getting Started', 'nextend-facebook-connect'); ?></h2>

        <p><?php printf(__('To allow your visitors to log in with their %1$s account, first you must create a %1$s App. The following guide will help you through the %1$s App creation process. After you have created your %1$s App, head over to "Settings" and configure the given "%2$s" and "%3$s" according to your %1$s App.', 'nextend-facebook-connect'), "VKontakte", "Application ID", "Secure key"); ?></p>

        <p><?php do_action('nsl_getting_started_warnings', $provider, $lastUpdated); ?></p>

        <h2 class="title"><?php printf(_x('Create %s', 'App creation', 'nextend-facebook-connect'), 'VKontakte App'); ?></h2>

        <ol>
            <li><?php printf(__('Navigate to %s', 'nextend-facebook-connect'), '<a href="https://vk.com" target="_blank">https://vk.com</a>'); ?></li>
            <li><?php printf(__('Log in with your %s credentials if you are not logged in.', 'nextend-facebook-connect'), 'VK'); ?></li>
            <li><?php printf(__('Open the %s page', 'nextend-facebook-connect'), '<a href="https://dev.vk.com/en/admin/apps-list" target="_blank">VK for developers</a>'); ?></li>
            <li><?php printf(__('In the sidebar on the left side, click on %1$s then click on the %2$s button.', 'nextend-facebook-connect'), '"<b>Connect VK ID</b>"', '"<b>Go to service</b>"'); ?></li>
            <ul>
                <li><?php printf(__('%1$s If you don\'t have a developer account connected already, then you will need to continue with your VK account, authorize VK, select an account type and complete some forms in order to create a developer account.', 'nextend-facebook-connect'), '<b>' . __('Note:', 'nextend-facebook-connect') . '</b>'); ?></li>
            </ul>
            <li><?php printf(__('Click the %s button.', 'nextend-facebook-connect'), '"<b>Add apps</b>"'); ?></li>
            <li><?php printf(__('Enter a name for your app, and at the platforms, enable the %s option', 'nextend-facebook-connect'), '"<b>Web</b>"'); ?></li>
            <li><?php printf(__('Select an image, then click on the%s button!', 'nextend-facebook-connect'), '"<b>Next</b>"'); ?></li>
            <li><?php printf(__('Fill the %1$s field with your domain name, probably: %2$s', 'nextend-facebook-connect'), '"<b>Base domain</b>"', '<b>' . parse_url(site_url(), PHP_URL_HOST) . '</b>'); ?></li>
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
            <li><?php printf(__('Press the %s button.', 'nextend-facebook-connect'), '"<b>Create app</b>"'); ?></li>
            <li><?php printf(__('Click on the %s link ( as you don\'t need those widgets, we have our own buttons ).', 'nextend-facebook-connect'), '"<b>Set up later</b>"'); ?></li>
            <li><?php printf(__('You will end up on the %1$s page, scroll down to the %2$s section where you can find the %3$s and %4$s. You will need these for the fields with the same name on our %5$s tab.', 'nextend-facebook-connect'), '"<b>App</b>"', '"<b>App information</b>"', '"<b>App ID</b>"', '"<b>Secure key</b>"', '"<b>' . __('Settings', 'nextend-facebook-connect') . '</b>"'); ?></li>
        </ol>

        <a href="<?php echo $this->getUrl('settings'); ?>"
           class="button button-primary"><?php printf(__('I am done setting up my %s', 'nextend-facebook-connect'), 'VKontakte App'); ?></a>
    </div>
</div>