// MAIN
import main from './main';

// COMPONENTS
import dateTimePickers from './components/pro/dateTimePickers';
import googleFontsSelects from './components/pro/googleFontsSelects';
import repeaters from './components/pro/repeaters';
import selects from './components/pro/selects';
import uploadImageButtons from './components/pro/uploadImageButtons';

// STYLES
import '../styles/pro.scss';

// PUBLIC PATH
__webpack_public_path__ = window.BULLETINWP['buildPath'];

jQuery(document).ready(function ($) {
  const bulletinwpAdmin = $(`#${window.BULLETINWP['pluginSlug']}-admin`);
  if (bulletinwpAdmin.length) {
    // MAIN
    main();

    // COMPONENTS
    // Date Time Pickers
    dateTimePickers(bulletinwpAdmin);
    // Google Fonts Selects
    googleFontsSelects(bulletinwpAdmin);
    // Repeaters
    repeaters(bulletinwpAdmin);
    // Selects
    selects(bulletinwpAdmin);
    // Upload Image Buttons
    uploadImageButtons(bulletinwpAdmin);
  }
});
