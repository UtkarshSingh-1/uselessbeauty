const multipleMessages = (bulletinwpBulletins) => {
  jQuery(document).ready(function ($) {
    const bulletinItem = bulletinwpBulletins.find(`.${window.BULLETINWP['pluginSlug']}-bulletin-item`);
    if (bulletinItem.length) {
      // Cycle option
      const swiperContainers = bulletinItem.find('.swiper-container');
      if (swiperContainers.length) {
        swiperContainers.each(function() {
          const thisSwiperContainer = $(this);
          const cycleSpeed = thisSwiperContainer.data('cycle-speed');
          let speed = 5000;

          if ( cycleSpeed ) {
            speed = cycleSpeed;
          }

          new Swiper(this, {
            slidesPerView: 1,
            direction: 'vertical',
            loop: true,
            autoplay: {
              delay: speed,
              disableOnInteraction: false,
            },
          });

          // Get highest height
          let highestHeight = 0;
          const slideItem = thisSwiperContainer.find(`.${window.BULLETINWP['pluginSlug']}-slide-item`);

          slideItem.each(function() {
            const _this = $(this);

            if (highestHeight < _this.height()) {
              highestHeight = _this.height();
            }
          });

          // Set highest height (required for vertical swipers it seems)
          thisSwiperContainer.height(highestHeight);

          slideItem.each(function() {
            const _this = $(this);

            _this.css('height', highestHeight);
          });
        });
      }

      // Marquee option
      const marqueeTextWrappers = bulletinItem.find(`.${window.BULLETINWP['pluginSlug']}-marquee-text-wrapper`);
      if (marqueeTextWrappers.length) {
        marqueeTextWrappers.each(function() {
          const _this = $(this);

          // time = distance / speed
          let speed = 50;
          const marqueePart = _this.find(`.${window.BULLETINWP['pluginSlug']}-marquee-part`);
          const marqueeSpeed = _this.data('marquee-speed');

          if ( marqueeSpeed ) {
            speed = marqueeSpeed / 1000;
          }

          const marqueePartLength = marqueePart.width();
          const time = marqueePartLength / speed;

          const marqueePartOne = _this.find(`.${window.BULLETINWP['pluginSlug']}-marquee-part-1`);
          const marqueePartTwo = _this.find(`.${window.BULLETINWP['pluginSlug']}-marquee-part-2`);

          marqueePartOne.css('animation-duration', `${time}s`);
          marqueePartOne.css('animation-delay', `-${time}s`);
          marqueePartTwo.css('animation-duration', `${time}s`);
          marqueePartTwo.css('animation-delay', `-${time / 2}s`);
        });
      }
    }
  });
};

export default multipleMessages;
