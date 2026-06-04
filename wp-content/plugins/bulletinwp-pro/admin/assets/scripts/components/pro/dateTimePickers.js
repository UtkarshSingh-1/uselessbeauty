const dateTimePickers = (bulletinwpAdmin) => {
  jQuery(document).ready(function ($) {
    const dateTimePickerInputs = bulletinwpAdmin.find('.date-time-picker-input');
    if (dateTimePickerInputs.length) {
      dateTimePickerInputs.each(function () {
        const thisInput = $(this);
        let currentDateAndTime = new Date();
        const timezoneString = window.BULLETINWP['timezoneString'];

        if (timezoneString) {
          currentDateAndTime = new Date(currentDateAndTime.toLocaleString('en-US', {timeZone: timezoneString})).getTime();
        }

        thisInput.datetimepicker({
          minDate: currentDateAndTime,
        });

        if (thisInput.hasClass('date-time-picker-range-input')) {
          thisInput.on('change', function() {
            const _this = $(this);
            const dataEndDateElement = _this.data('end-date-element');
            const endDateElement = bulletinwpAdmin.find(`input[name="${dataEndDateElement}"]`);

            if (endDateElement.length) {
              const startDate = new Date(_this.val()).getTime();
              const endDate = new Date(endDateElement.val()).getTime();

              if (startDate > endDate) {
                endDateElement.val('');
              }

              endDateElement.datetimepicker({
                minDate: startDate ? startDate : currentDateAndTime,
              });
            }
          });
        }
      });
    }
  });
};

export default dateTimePickers;
