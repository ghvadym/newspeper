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

    // var filterButtons = document.querySelectorAll('.news-filter__item > input');
    // var array = [];
    // filterButtons.forEach((button) => {
    //     button.addEventListener('click', async () => {
    //         filterButtons.forEach((check) => {
    //             var term = check.value;
    //
    //             if (!check.checked || array.includes(term)) {
    //                 return;
    //             }
    //              array.push(term);
    //
    //         });
    //         var formData = new FormData();
    //         formData.append('action', 'archive_filter');
    //         formData.append('terms', array);
    //         var selectPost = await fetch(myajax.ajaxurl, {
    //             method: 'POST',
    //             body: formData
    //         });
    //
    //     });
    // });

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

            var data = new FormData();
            data.append('action', 'archive_filter');
            data.append('filter_data', arrayInputs);

            var response = await fetch(ajax,{
                method: 'POST',
                body  : data
            });

            var html = await response.json();

            if (html == null) {
                return;
            }

            document.querySelector('.archive.filter .post__list').innerHTML = html.result;
        });
    });
});