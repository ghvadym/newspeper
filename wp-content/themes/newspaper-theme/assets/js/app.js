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

                paginas.forEach((item) => {
                    item.classList.remove('current');
                })
                pagina.classList.add('current');

                document.querySelector('.post__list').innerHTML = html.result;
            })();
        });
    });
});