document.addEventListener('DOMContentLoaded', function () {
    var ajax = myajax.ajaxurl;
    var paginas = document.querySelectorAll('.archive  .pagination .page-numbers');
    paginas.forEach((pagina) => {
        pagina.addEventListener('click', (e) => {
            e.preventDefault();

            var element = '';

            if (pagina.classList.contains('current')) {
                return;
            }

            // var link = pagina.getAttribute('href');
            // var url = new URL(link);
            // var page = url.searchParams.get('page');

            var data = new FormData();
            data.append('action', 'archive_pagination');
            data.append('page', +pagina.innerHTML);

            (async () => {
                var response = await fetch(ajax, {
                    method: 'POST',
                    body  : data
                });

                var html = await response.json();

                if (!html) return false;

                paginas.forEach((item) => {
                    item.classList.remove('current');
                })

                pagina.classList.add('current');

                document.querySelector('.post__list').innerHTML = html.result;
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

            //if (arrayInputs.length) arrayInputs.join();

            var data = new FormData();
            data.append('action', 'archive_filter');
            data.append('filter_data', arrayInputs);

            var response = await fetch(ajax,{
                method: 'POST',
                body  : data
            });

            var html = await response.json();

            if (!html) return false;

            postsWrapper.innerHTML = html.result;

            // var parser = new DOMParser();
            // var doc = parser.parseFromString(html.result, 'text/html');
            // var posts = doc.querySelectorAll('.post__item');
            //
            // posts.forEach((post)=> {
            //     document.querySelector('.archive.filter .post__list').appendChild(post);
            // });
        });
    });
});