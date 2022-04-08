document.addEventListener('DOMContentLoaded', function () {
    //Pagination on archive page
    var ajax = myajax.ajaxurl;
    var postsWrap = document.querySelector('.archive .post__list');

    function clickOnPagination() {
        var paginas = document.getElementsByClassName('page-numbers');
        Array.from(paginas).forEach((pagina) => {
            pagina.onclick = (e) => {
                e.preventDefault();

                if (pagina.classList.contains('current')) {
                    return;
                }

                var page = +pagina.innerHTML;
                var post = pagina.parentNode.dataset.post || null;
                var terms = pagina.parentNode.dataset.terms || 0;

                var data = new FormData();
                data.append('action', 'archive_pagination');
                data.append('page', page);
                if (post) {
                    data.append('post', post);
                } else if (terms) {
                    data.append('terms', terms);
                }

                (async () => {
                    var response = await fetch(ajax, {
                        method: 'POST',
                        body: data
                    });

                    var html = await response.json();
                    if (!html) return false;

                    Array.from(paginas).forEach((item) => {
                        item.classList.remove('current');
                    });

                    pagina.classList.add('current');
                    postsWrap.innerHTML = html.result;

                    scrollTop();
                })();
            }
        });
    }

    clickOnPagination();

    //Filter taxes by terms
    var termsInput = document.querySelectorAll('.news-filter__item > input');
    var postsWrapper = document.querySelector('.archive.filter .post__list');
    var filterWrap = document.querySelector('.news-filter');
    var body = document.querySelector('body');
    var pagination = document.querySelector('.pagination');
    var arrayInputs = [];

    termsInput.forEach((input) => {
        input.onchange = async (event) => {
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
                body: data
            });

            var html = await response.json();
            if (!html) return false;

            postsWrapper.innerHTML = html.result;

            updatePagination(html.pages)
            updateStyles();
        }
    });

    function updateStyles() {
        filterWrap.classList.remove('open');
        body.classList.remove('no-scroll');
        scrollTop();
    }

    function updatePagination(pages) {
        pagination.dataset.terms = arrayInputs;
        pagination.innerHTML = '';

        if (pages <= 1) {
            return;
        }

        for (var i = 1; i <= pages; i++) {
            var numb = document.createElement('span');
            var className = i === 1 ? 'page-numbers current' : 'page-numbers';

            numb.setAttribute('class', className);
            numb.innerHTML = i;

            pagination.append(numb);
            clickOnPagination();
        }
    }

    function scrollTop() {
        window.scrollTo({
            'behavior': 'smooth',
            'left': 0,
            'top': 0
        });
    }

    //Mobile filter open on click button
    var filterBtn = document.querySelector('.filter__btn');
    var btnClose = document.querySelector('.btn-close');
    if (filterBtn) {
        filterBtn.onclick = () => {
            filterWrap.classList.add('open');
            body.classList.add('no-scroll');
        }
        btnClose.onclick = () => {
            filterWrap.classList.remove('open');
            body.classList.remove('no-scroll');
        }
    }

    //Mobile burger menu toggle
    var burgerBtn = document.querySelector('.nav__burger');
    var navMenu = document.querySelector('#navigation .nav');
    burgerBtn.onclick = () => {
        navMenu.classList.toggle('open');
        body.classList.toggle('no-scroll');
    }

    //Text to Speech
    var btnPlay = document.querySelector('.article .speech');
    var audioWrap = document.querySelector('.article .speech-result');
    if (btnPlay) {


        btnPlay.onclick = async () => {

            var id = btnPlay.dataset.id;

            var data = new FormData();
            data.append('action', 'speech');
            data.append('id', id);

            var response = await fetch(ajax, {
                method: 'POST',
                body: data
            });

            var html = await response.json();
            if (!html) return false;

            audioWrap.innerHTML = html.result;

        }
    }
});


(function ($) {
    $(document).ready(function () {
        if ($(window).width() < 767) {
            $(".terms__list.owl-carousel").owlCarousel({
                loop: true,
                items: 1,
                touchDrag: true,
                margin: 20,
                nav: false,
                dots: false,
                autoplay: true,
                autoplayHoverPause: true,
                autoplayTimeout: 2000,
            });
        }
    });
})(jQuery);