document.addEventListener('DOMContentLoaded', function () {
    var ajax = myajax.ajaxurl;
    var postsWrap = document.querySelector('.archive .post__list');
    var paginas = document.querySelectorAll('.archive  .pagination .page-numbers');
    paginas.forEach((pagina) => {
        pagina.addEventListener('click', (e) => {
            e.preventDefault();

            if (pagina.classList.contains('current')) {
                return;
            }

            var page = +pagina.innerHTML;

            var data = new FormData();
            data.append('action', 'archive_pagination');
            data.append('page', page);

            (async () => {
                var response = await fetch(ajax, {
                    method: 'POST',
                    body  : data
                });

                var html = await response.json();

                if (!html) return false;

                paginas.forEach((item) => {
                    item.classList.remove('current');
                });

                pagina.classList.add('current');

                postsWrap.innerHTML = html.result;
            })();
        });
    });

    var termsInput = document.querySelectorAll('.news-filter__item > input');
    var postsWrapper = document.querySelector('.archive.filter .post__list');
    var arrayInputs = [];

    termsInput.forEach((input) => {
        input.addEventListener('change', async (event) => {
            termsInput.forEach((inputCheck) => {
                var term = inputCheck.value;
                if (!inputCheck.checked || arrayInputs.includes(term)) {
                    return;
                }
                arrayInputs.push(term);
            });

            if (!event.target.checked && arrayInputs.includes(event.target.value)) {
                var index = arrayInputs.indexOf(event.target.value);
                arrayInputs.splice(index);
            }

            var data = new FormData();
            data.append('action', 'archive_filter');
            data.append('filter_data', arrayInputs);

            var response = await fetch(ajax, {
                method: 'POST',
                body  : data
            });

            var html = await response.json();

            if (!html) return false;

            postsWrapper.innerHTML = html.result;
        });
    });
});


(function ($) {
    $(document).ready(function () {
        if ($(window).width() < 767) {
            $(".terms__list.owl-carousel").owlCarousel({
                loop              : true,
                items             : 1,
                touchDrag         : true,
                margin            : 20,
                nav               : false,
                dots              : false,
                autoplay          : true,
                autoplayHoverPause: true,
            });
        }
    });
})(jQuery);