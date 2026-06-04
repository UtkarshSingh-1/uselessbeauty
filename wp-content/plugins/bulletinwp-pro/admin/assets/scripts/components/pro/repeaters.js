const repeaters = (bulletinwpAdmin) => {
  jQuery(document).ready(function ($) {
    const repeaterWrapper = bulletinwpAdmin.find('.repeater-container');
    if (repeaterWrapper.length) {
      repeaterWrapper.each(function() {
        const thisRepeaterWrapper = $(this);

        // Remove a repeater item
        $(document).on('click', `#${window.BULLETINWP['pluginSlug']}-admin .repeater-container .controls .control-button.delete-button`, function(e) {
          e.preventDefault();

          const _this = $(this);
          const thisRepeaterItemWrapperParent = _this.closest('.repeater-item');

          thisRepeaterItemWrapperParent.remove();
        });

        // Add a repeater item
        $(document).on('click', `#${window.BULLETINWP['pluginSlug']}-admin .repeater-container .controls .control-button.add-button`, function(e) {
          e.preventDefault();

          const _this = $(this);
          const thisRepeaterItemWrapperParent = _this.closest('.repeater-item');
          const repeaterItemWrapperClone = thisRepeaterWrapper.find('.repeater-item.cloner');

          thisRepeaterItemWrapperParent.after(repeaterItemWrapperClone.clone(true).removeClass('cloner'));
        });
      });
    }
  });
};

export default repeaters;
