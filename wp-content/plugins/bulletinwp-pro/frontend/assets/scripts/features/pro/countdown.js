const countdown = (bulletinwpBulletins) => {
  jQuery(document).ready(function ($) {
    const bulletinItem = bulletinwpBulletins.find(`.${window.BULLETINWP['pluginSlug']}-bulletin-item`);
    if (bulletinItem.length) {
      const countdownTimer = $(`.${window.BULLETINWP['pluginSlug']}-countdown-timer`);

      let countdownMinWidth = 0;

      if (countdownTimer.length) {
        countdownTimer.each(function() {
          const _this = $(this);
          const countdownExpiry = _this.data('countdown-expiry');
          const showCountdown = _this.data('show-countdown');
          const daysCountdown = _this.find(`.${window.BULLETINWP['pluginSlug']}-days-countdown`);
          const hoursCountdown = _this.find(`.${window.BULLETINWP['pluginSlug']}-hours-countdown`);
          const minsCountdown = _this.find(`.${window.BULLETINWP['pluginSlug']}-mins-countdown`);
          const secsCountdown = _this.find(`.${window.BULLETINWP['pluginSlug']}-secs-countdown`);
          const countdownDate  = new Date(countdownExpiry).getTime();

          // Update the count down every 1 second
          const handleCountdown = function() {
            // Get today's date and time
            const now = new Date().getTime();

            // Find the distance between now and the count down date
            const distance = countdownDate - now;

            if ( showCountdown ) {
              // Time calculations for days, hours, minutes and seconds
              let days = Math.floor(distance / (1000 * 60 * 60 * 24));
              let hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
              let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
              let seconds = Math.floor((distance % (1000 * 60)) / 1000);

              days = (days < 10) ? '0' + days : days;
              hours = (hours < 10) ? '0' + hours : hours;
              minutes = (minutes < 10) ? '0' + minutes : minutes;
              seconds = (seconds < 10) ? '0' + seconds : seconds;

              // Display the result in the element
              daysCountdown.html(days);
              hoursCountdown.html(hours);
              minsCountdown.html(minutes);
              secsCountdown.html(seconds);

              // Old display version
              // _this.html(days + 'd ' + hours + 'h ' + minutes + 'm ' + seconds + 's ');

              if (countdownMinWidth < _this.width()) {
                countdownMinWidth = _this.width() + 10; //offset of 10px

                _this.css({ minWidth: countdownMinWidth + 'px' });
              }
            }

            // If the count down is finished
            if (distance < 0) {
              clearInterval(countdownInterval);
              _this.remove();
            }
          };

          // Run initially without delay
          handleCountdown();

          const countdownInterval = setInterval(handleCountdown, 1000);
        });
      }
    }
  });
};

export default countdown;
