const uploadImageButtons = (bulletinwpAdmin) => {
  jQuery(document).ready(function ($) {
    const uploadImageButtonWrapper = bulletinwpAdmin.find('.upload-image-button-wrapper');
    if (uploadImageButtonWrapper.length) {
      uploadImageButtonWrapper.each(function() {
        const thisUploadImageButtonWrapper = $(this);
        const imageAttachmentIDInput = thisUploadImageButtonWrapper.find('input.image-attachment-id');
        const uploadImageButton = thisUploadImageButtonWrapper.find('.upload-image-button');
        const imagePreviewWrapper = thisUploadImageButtonWrapper.find('.image-preview-wrapper');
        const imagePreview = imagePreviewWrapper.find('img.image-preview');

        if (typeof wp !== 'undefined' && wp.media && wp.media.editor) {
          uploadImageButton.on('click', function(e) {
            e.preventDefault();

            wp.media.editor.send.attachment = function(props, attachment) {
              imageAttachmentIDInput.val(attachment.id);
              imagePreviewWrapper.show();
              imagePreview.attr('src', '');
              imagePreview.attr('src', attachment.url);
            };

            wp.media.editor.open();

            return false;
          });
        }
      });
    }
  });
};

export default uploadImageButtons;
