import helpers from '../../util/helpers';

const dismiss = (bulletinwpBulletins) => {
  jQuery(document).ready(function ($) {
    if (bulletinwpBulletins.length) {
      const closeButtons = bulletinwpBulletins.find(`.${window.BULLETINWP['pluginSlug']}-bulletin-close-button`);
      if (closeButtons.length) {
        closeButtons.each(function() {
          const thisCloseButton = $(this);
          const bulletins = thisCloseButton.closest(`.${window.BULLETINWP['pluginSlug']}-bulletins`);
          const bulletinItem = thisCloseButton.closest(`.${window.BULLETINWP['pluginSlug']}-bulletin-item`);
          const bulletinItemID = bulletinItem.data('id');
          const cookieExpiryName = `${window.BULLETINWP['pluginSlug']}-dismiss-expiry[${bulletinItemID}]`;
          const fixedHeaderSelector = bulletins.data('fixed-header-selector') ? bulletins.data('fixed-header-selector') : 'header, .header, .nav';
          const headerBannerStyle = bulletins.data('header-banner-style');

          helpers.docLocalStorage.checkExpiryItem(cookieExpiryName);

          if (!helpers.docLocalStorage.getItem(cookieExpiryName)) {
            thisCloseButton.on('click', function(e) {
              e.preventDefault();

              const _this = $(this);
              let cookieExpiry = '';

              if ( _this.hasClass(`${window.BULLETINWP['pluginSlug']}-bulletin-dismiss-button`) ) {
                cookieExpiry = _this.data('button-cookie-expiry') === -1 ? Infinity : _this.data('button-cookie-expiry');
              } else {
                cookieExpiry = _this.data('cookie-expiry') === -1 ? Infinity : _this.data('cookie-expiry');
              }

              // Move back last top the header
              if (fixedHeaderSelector) {
                // Set top position if below or above selector header
                if (headerBannerStyle === 'above-header') {
                  const selectedElement = $(fixedHeaderSelector).first();
                  const selectedElementDOM = selectedElement.get(0);
                  if (selectedElementDOM.length) {
                    const selectedElementDOMStylePosition = window.getComputedStyle(selectedElementDOM).position;

                    const computedTop = window.getComputedStyle(selectedElementDOM).top.replace('px', '');
                    // Edge cases: auto, percentages(%) -- very unlikely for fixed/sticky
                    if (selectedElementDOMStylePosition === 'fixed' || selectedElementDOMStylePosition === 'sticky' ) {
                      selectedElementDOM.style.top = ( computedTop - bulletins.outerHeight() ) + 'px';
                    }
                  }
                }
              }

              bulletins.remove();

              if (cookieExpiry && cookieExpiry !== 0) {
                // helpers.docCookies.setItem(cookieExpiryName, 1, cookieExpiry * 3600, '/');
                helpers.docLocalStorage.setItem(cookieExpiryName, cookieExpiry * 3600000);
              }
            });
          } else {
            bulletinItem.remove();
          }
        });
      }
    }
  });
};

export default dismiss;
