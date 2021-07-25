document.addEventListener('DOMContentLoaded', function () {
    var ajax = myajax.ajaxurl;
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
                var response = await fetch(ajax, {
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

    var termsInput = document.querySelectorAll('.news-filter__item > input');
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

            // if (arrayInputs.length) {
            //     arrayInputs.join();
            // }

            var data = new FormData();
            data.append('action', 'archive_filter');
            data.append('filter_data', arrayInputs);

            var response = await fetch(ajax,{
                method: 'POST',
                body  : data
            });

            var html = await response.json();

            document.querySelector('.archive.filter .post__list').innerHTML = html.result;

            // var parser = new DOMParser();
            // var doc = parser.parseFromString(html.result, 'text/html');
            // var posts = doc.querySelectorAll('.post__item');
            // postsWrapper.innerHTML = '';

            //posts.forEach((post)=> {
            //postsWrapper.appendChild(post);
            //});
        });
    });
});