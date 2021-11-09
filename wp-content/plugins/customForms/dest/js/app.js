document.addEventListener('DOMContentLoaded', function () {

    (() => {
        var form = document.querySelector('#flexi_form');
        if (!form) {
            return
        }
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            var formData = new FormData(e.target);
            formData.append('action', "formSubmit");

            (async () => {
                var response = await fetch(ajax_filter.url, {
                    method: 'POST',
                    body  : formData
                });
                var result = await response.json();
                if (result !== null && result.success) {
                    console.log(result.data.message)
                    var succesMsg = document.createElement('p');
                    succesMsg.innerHTML = result.data.message;
                    form.innerHTML = '';
                    form.appendChild(succesMsg)
                } else {
                    form.querySelectorAll('label').forEach(el => {
                        var errorField = el.querySelector('p');
                        if (errorField) {
                            errorField.remove();
                        }
                    })
                    for (const [key, value] of Object.entries(result.data)) {
                        var errorMsg = document.createElement('p');
                        if (key === 'message') {
                            var inputWithError = form.querySelector(`textarea[name=${key}]`);
                        } else {
                            var inputWithError = form.querySelector(`input[name=${key}]`);
                        }
                        errorMsg.innerHTML = value;
                        inputWithError.parentNode.appendChild(errorMsg);
                        inputWithError.classList.add('error');
                    }
                }
            })()
        });
    })();


    (() => {
        jQuery(document).on('click', '.form_entries__item', function () {
            var entry = jQuery(this);
            var data = new FormData();
            if (entry[0].classList.contains('active')) {
                var message = entry[0].querySelector('.message');
                entry[0].classList.add('message-out');
                setTimeout(() => {
                    entry[0].removeChild(message)
                    entry[0].classList.remove('active', 'message-out')
                }, 500)

                return;
            } else {
                entry[0].classList.add('active');
            }

            data.append('action', "getEntryMessage");
            data.append('id', entry[0].dataset.id);

            (async () => {
                var response = await fetch(ajax_filter.url, {
                    method: 'POST',
                    body  : data
                });

                var result = await response.json();

                if (result !== null && result.success) {
                    var messageContainer = document.createElement('li');
                    var message = document.createElement('p');
                    messageContainer.classList.add('form_entries__item__text', 'message');
                    message.innerHTML = result.data.message
                    messageContainer.appendChild(message)
                    entry[0].appendChild(messageContainer);
                } else {
                    console.log(1)
                }
            })()
        });
    })();
    (() => {
        var btnLoadMoreEntries = document.querySelector('.form_entries button');
        if (!btnLoadMoreEntries) {
            return;
        }
        btnLoadMoreEntries.addEventListener('click', function () {
            var data = new FormData();
            data.append('action', "loadMoreEntries");
            data.append('page', this.dataset.page);
            var container = document.querySelector('.form_entries .items_wrapper');

            (async () => {
                var response = await fetch(ajax_filter.url, {
                    method: 'POST',
                    body  : data
                });
                var result = await response.json();

                if (result !== null && result.success) {
                    this.dataset.page = parseInt(this.dataset.page) + 1;
                    var parser = new DOMParser();
                    var doc = parser.parseFromString(result.data.items, 'text/html');
                    var mainResult = doc.querySelectorAll('.form_entries__item');
                    mainResult.forEach(el => {
                        container.appendChild(el);
                    });
                    if (result.data.last) {
                        this.remove()
                    }
                } else {
                    console.log(1)
                }
            })()
        })
    })()
});
