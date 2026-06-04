const googleFontsSelects = (bulletinwpAdmin) => {
  jQuery(document).ready(function ($) {
    const googleFontsSelectWrapper = bulletinwpAdmin.find('.google-fonts-select-wrapper');
    if (googleFontsSelectWrapper.length) {
      googleFontsSelectWrapper.each(function() {
        const thisGoogleFontsSelectWrapper = $(this);

        const fontTarget = thisGoogleFontsSelectWrapper.data('font-target');
        const fontTargetElement = thisGoogleFontsSelectWrapper.find(fontTarget);
        const select = thisGoogleFontsSelectWrapper.find('select.google-fonts-select');
        const arabicFont = thisGoogleFontsSelectWrapper.find('.arabic-font');
        const hebrewFont = thisGoogleFontsSelectWrapper.find('.hebrew-font');
        const regularFont = thisGoogleFontsSelectWrapper.find('.regular-font');
        let selectValue = select.find('option:selected').val();

        if (selectValue !== '') {
          if (selectValue.includes(' - Arabic')) {
            selectValue = selectValue.replace(' - Arabic', '');
          } else if (selectValue.includes(' - Hebrew')) {
            selectValue = selectValue.replace(' - Hebrew', '');
          }

          WebFont.load({
            google: {
              families: [selectValue],
            },
          });

          fontTargetElement.css('font-family', '"' + selectValue + '"');
        }

        select.on('change', function() {
          const _this = $(this);
          const selectedValue = _this.val();
          let checkFontValue = '';

          if (selectedValue.includes(' - Arabic')) {
            checkFontValue = selectedValue.replace(' - Arabic', '');
          } else if (selectedValue.includes(' - Hebrew')) {
            checkFontValue = selectedValue.replace(' - Hebrew', '');
          } else {
            checkFontValue = selectedValue;
          }

          WebFont.load({
            google: {
              families: [checkFontValue],
            },
          });

          if (selectedValue.includes(' - Arabic')) {
            arabicFont.show();
            hebrewFont.hide();
            regularFont.hide();
          } else if (selectedValue.includes(' - Hebrew')) {
            arabicFont.hide();
            hebrewFont.show();
            regularFont.hide();
          } else {
            arabicFont.hide();
            hebrewFont.hide();
            regularFont.show();
          }

          // fontTargetElement.show();
          fontTargetElement.css('font-family', '"' + checkFontValue + '"');
        });
      });
    }
  });
};

export default googleFontsSelects;
