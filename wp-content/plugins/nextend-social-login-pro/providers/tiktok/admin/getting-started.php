<?php
defined('ABSPATH') || die();
/** @var $this NextendSocialProviderAdmin */

$lastUpdated = '2025-03-26';

$provider = $this->getProvider();
?>
<div class="nsl-admin-sub-content">

    <?php if (substr($provider->getLoginUrl(), 0, 8) !== 'https://'): ?>
        <div class="error">
            <p><?php printf(__('%1$s allows HTTPS OAuth Redirects only. You must move your site to HTTPS in order to allow login with %1$s.', 'nextend-facebook-connect'), 'TikTok'); ?></p>
            <p>
                <a href="https://social-login.nextendweb.com/documentation/providers/facebook/#how-to-add-ssl-to-wordpress"><?php _e("How to get SSL for my WordPress site?", 'nextend-facebook-connect'); ?></a>
            </p>
        </div>
    <?php else: ?>
        <div class="nsl-admin-getting-started">

            <h2 class="title"><?php _e('Getting Started', 'nextend-facebook-connect'); ?></h2>

            <p><?php printf(__('To allow your visitors to log in with their %1$s account, first you must create an %1$s App. The following guide will help you through the %1$s App creation process. After you have created your %1$s App, head over to "Settings" and configure the given "%2$s" and "%3$s" according to your %1$s App.', 'nextend-facebook-connect'), "TikTok", "Client Key", "Client Secret"); ?></p>

            <p><?php do_action('nsl_getting_started_warnings', $provider, $lastUpdated); ?></p>

            <h2 class="title"><?php printf(_x('Create %s', 'App creation', 'nextend-facebook-connect'), 'TikTok App'); ?></h2>

            <ol>
                <li><?php printf(__('Navigate to %s', 'nextend-facebook-connect'), '<a href="https://developers.tiktok.com/" target="_blank">https://developers.tiktok.com/</a>'); ?></li>
                <li><?php printf(__('Log in to your %s developer account or register one if you don\'t have any!', 'nextend-facebook-connect'), 'TikTok'); ?></li>
                <li><?php printf(__('On the top right corner click on %1$s then on %2$s.', 'nextend-facebook-connect'), '<strong>Developer Portal</strong>', '<strong>Manage apps</strong>'); ?></li>
                <li><?php printf(__('Locate the red %1$s button and click on it.', 'nextend-facebook-connect'), '<strong>Connect an app</strong>'); ?></li>
                <li><?php printf(__('You will see a popup window. Select the %1$s option, and enter an %2$s, then click %3$s.', 'nextend-facebook-connect'), '<strong>An individual developer (myself)</strong>', '<strong>App name</strong>', '<strong>Confirm</strong>'); ?></li>
                <li><?php printf(__('Create a %1$s, and configure Nextend Social Login with it, in order to record a demo video that shows the complete end-to-end flow of the integration. This is required to be able to verify your %2$s. Once done, switch back to %3$s.', 'nextend-facebook-connect'), '<a href="https://social-login.nextendweb.com/documentation/providers/tiktok/#sandbox" target="_blank">Sandbox App</a>', 'TikTok App', '<strong>Production</strong>'); ?></li>
                <li><?php printf(__('Find the %1$s section, and upload an image for the %2$s, update the %3$s if necessary, choose a %4$s, and add a %5$s', 'nextend-facebook-connect'), '<strong>Basic information</strong>', '<strong>App icon</strong>', '<strong>App name</strong>', '<strong>Category</strong>', '<strong>Description</strong>'); ?></li>
                <li><?php printf(__('Enter your %1$s and %2$s URLs. You will also need to %3$sverify the URL properties%4$s.', 'nextend-facebook-connect'), '<strong>Terms of Service</strong>', '<strong>Privacy Policy</strong>', '<a href="https://social-login.nextendweb.com/documentation/providers/tiktok/#verify-url" target="_blank">', '</a>'); ?></li>
                <li><?php printf(__('For %1$s you should enable the %2$s option, then enter the URL of your website into the %3$s field.<br>Probably: %4$s <br>You will also need to %5$sverify the URL properties%6$s.', 'nextend-facebook-connect'), '<strong>Platforms</strong>', '<strong>Web</strong>', '<strong>Web/Desktop URL</strong>', '<strong>' . site_url() . '</strong>', '<a href="https://social-login.nextendweb.com/documentation/providers/tiktok/#verify-url" target="_blank">', '</a>'); ?></li>
                <li><?php printf(__('Afterwards, click on %1$s section, then add the %2$s product, and press the %3$s button.', 'nextend-facebook-connect'), '<strong>+ Add products</strong>', '<strong>Login Kit</strong>', '<strong>Done</strong>'); ?></li>
                <li><?php
                    $loginUrls = $provider->getAllRedirectUrisForAppCreation();
                    printf(__('Next, for the %1$s field under the %2$s product, add the following URL: ', 'nextend-facebook-connect'), '<b>Web</b>', '<b>Login Kit</b>');
                    echo "<ul>";
                    foreach ($loginUrls as $loginUrl) {
                        echo "<li><strong>" . $loginUrl . "</strong></li>";
                    }
                    echo "</ul>";
                    ?>
                </li>
                <li><?php printf(__('At the %1$s section, describe what purpose you are using the social login, and user data for. The %2$s for the %3$s social login on your website, to make the registration/login more straightforward and convenient. The requested scope(s) are used to retrieve user data in order to create or update a WordPress account on your website.', 'nextend-facebook-connect'), '<strong>App review</strong>', 'Login Kit', 'TikTok'); ?></li>
                <li><?php printf(__('At the top of the page, click on the %s button.', 'nextend-facebook-connect'), '<strong>Save</strong>'); ?></li>
                <li><?php printf(__('Currently your App is in %1$s mode, meaning that you don\'t have access to the credentials which are necessary for the %2$s integration. In order to gain access, click on the %3$s in the top right corner.', 'nextend-facebook-connect'), '<strong>Draft</strong>', 'TikTok', '<strong>Submit for review</strong>'); ?></li>
                <li><?php printf(__('A modal will appear where you should enter a text, that describes what you are going to do with the App. In this particular case, you will use it to offer %1$s login option for your visitors.', 'nextend-facebook-connect'), 'TikTok'); ?></li>
                <li><?php printf(__('Press the %s button.', 'nextend-facebook-connect'), '<strong>Submit</strong>'); ?></li>
                <li><?php printf(__('Wait until your App gets approved. This can take a couple of days. On the left side you will see %1$s once your App has been approved. If you want to learn more about the App review process, you can find more information in the %2$sofficial documentation%3$s.', 'nextend-facebook-connect'), '<strong>Live</strong>', '<a href="https://developers.tiktok.com/doc/getting-started-faq" target="_blank">', '</a>'); ?></li>
                <li><?php printf(__('Once your App is %1$s, you will be able to reveal the %2$s and %3$s by clicking on the eye icon next to these fields. You will need these credentials for the provider configuration.<br>%4$s Make sure to use the credentials of your %5$s!', 'nextend-facebook-connect'), '<strong>Live</strong>', '<strong>Client Key</strong>', '<strong>Client Secret</strong>', '<strong>Important:</strong>', '<strong>Production App</strong>'); ?></li>
            </ol>
            <p><?php printf(__('<b>WARNING:</b> The %1$s API can not return any email address or phone number! %2$sLearn more%3$s.', 'nextend-facebook-connect'), 'TikTok', '<a href="https://social-login.nextendweb.com/documentation/providers/tiktok/#empty-email" target="_blank">', '</a>'); ?></p>

            <a href="<?php echo $this->getUrl('settings'); ?>"
               class="button button-primary"><?php printf(__('I am done setting up my %s', 'nextend-facebook-connect'), 'TikTok App'); ?></a>
        </div>
    <?php endif; ?>

</div>