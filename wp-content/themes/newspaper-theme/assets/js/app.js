document.addEventListener('DOMContentLoaded', function () {
    var paginas = document.querySelectorAll('.archive  .pagination .page-numbers');

    paginas.forEach((pagina) => {
        pagina.addEventListener('click', (e) => {
            e.preventDefault();

            if (pagina.classList.contains('current') || pagina.classList.contains('next') || pagina.classList.contains('prev')) {
                return;
            }

            var data = new FormData();
            data.append('action', 'archive_pagination');
            data.append('page', +pagina.innerHTML);

            (async () => {
                var response = await fetch(myajax.ajaxurl, {
                    method: 'POST',
                    body  : data
                });

                var html = await response.json();

                if (html == null) {
                    return false;
                }

                // var parser = new DOMParser();
                // var doc = parser.parseFromString(html.result, 'text/html');
                // var container = document.querySelector('.post__list')
                // container.innerHTML = '';
                // var resultItems = doc.querySelectorAll('.post__item');
                // resultItems.forEach(function (el) {
                //     container.appendChild(el);
                // });

                // parser.parseFromString(html.result, 'text/html');
                document.querySelector('.post__list').innerHTML = html.result;

            })();
        });
    });
});