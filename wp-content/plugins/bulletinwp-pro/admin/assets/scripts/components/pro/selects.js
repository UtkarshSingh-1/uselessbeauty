const selects = (bulletinwpAdmin) => {
  jQuery(document).ready(function ($) {
    const select2 = bulletinwpAdmin.find('select.select2');
    if (select2.length) {
      select2.each(function () {
        const _this = $(this);

        if (_this.hasClass('infinity')) {
          _this.select2({
            minimumResultsForSearch: Infinity,
          });
        } else if (_this.hasClass('select-resolve-width')) {
          _this.select2({
            width: 'resolve',
            tags: true,
            templateSelection : function (tag, container){
              // here we are finding option element of tag and
              // if it has property 'locked' we will add class 'locked-tag'
              // to be able to style element in select
              let $option = $('.select2 option[value="'+tag.id+'"]');
              if ($option.attr('locked')){
                $(container).addClass('locked-tag');
                tag.locked = true;
              }
              return tag.text;
            },
            templateResult: function (tag, container){
              let $option = $('.select2 option[value="'+tag.id+'"]');
              if ($option.attr('locked')){
                $(container).addClass('locked-tag');
                tag.locked = true;
              }
              return tag.text;
            },
          }).on('select2:unselecting', function(e){
            // before removing tag we check option element of tag and
            // if it has property 'locked' we will create error to prevent all select2 functionality
            if ($(e.params.args.data.element).attr('locked')) {
              e.select2.pleaseStop();
            }
          });
        } else {
          _this.select2();
        }
      });
    }
  });
};

export default selects;
