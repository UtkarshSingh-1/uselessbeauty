// MAIN
import main from './main';

// FEATURES
import dismiss from './features/pro/dismiss';
import multipleMessages from './features/pro/multipleMessages';
import countdown from './features/pro/countdown';

// STYLES
import '../styles/pro.scss';

jQuery(document).ready(function ($) {
  // MAIN
  main();

  const bulletinwpBulletins = $(`.${window.BULLETINWP['pluginSlug']}-bulletins`);
  if (bulletinwpBulletins.length) {
    // FEATURES
    // Countdown
    countdown(bulletinwpBulletins);
    // Dismiss
    dismiss(bulletinwpBulletins);
    // Multiple messages
    multipleMessages(bulletinwpBulletins);
  }
});
