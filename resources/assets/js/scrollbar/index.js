import * as $ from 'jquery';
// export for others scripts to use
import PerfectScrollbar from 'perfect-scrollbar';

export default (function () {
  const scrollables = $('.scrollable');
  if (scrollables.length > 0) {

    scrollables.each((index, el) => {
      new PerfectScrollbar(el);
    });
  }
}());
