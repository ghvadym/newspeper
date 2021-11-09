document.addEventListener('DOMContentLoaded', function () {
    var monthSelected = document.querySelector('.months__selected');
    var monthsWrap = document.querySelector('.calendar__months');
    var monthsList = document.querySelectorAll('.calendar__months .month__item');
    var mothSelected = document.querySelector('.months__selected');
    var calendarWrap = document.getElementById('calendar');

    calendarWrap.classList.add('loaded');

    getDaysOfMonth(mothSelected.dataset.month);

    monthSelected.onclick = () => {
        monthsWrap.classList.toggle('active');
    }

    window.onclick = (e) => {
        if (!monthsWrap.contains(e.target) && monthsWrap.classList.contains('active')) {
            monthsWrap.classList.remove('active');
        }
    }

    monthsList.forEach((item) => {
        item.onclick = () => {
            getDaysOfMonth(item.dataset.month);
            mothSelected.innerHTML = item.innerHTML;
            monthsWrap.classList.remove('active');
        }
    })
});

async function getDaysOfMonth(month)
{
    var data = new FormData();
    data.append('action', 'month_days');
    data.append('month', month);

    var response = await fetch(fx_theme_ajax.ajaxurl, {
        method: 'POST',
        body  : data
    });

    var html = await response.json();
    var loopDaysWrap = document.querySelector('.calendar__loop')
    if (html !== null) {
        loopDaysWrap.innerHTML = html.result;
    }
}
