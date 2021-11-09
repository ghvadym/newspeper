document.addEventListener('DOMContentLoaded', function () {
    var btnTranslate = document.querySelector('#translate');
    var insertLanguage = document.querySelector('#select-lang');
    if (btnTranslate) {
        btnTranslate.addEventListener('click', function (e) {
            e.preventDefault();
            var postId = this.getAttribute('data-post-id');
            var language = insertLanguage.options[insertLanguage.selectedIndex].value;
            var url = this.getAttribute('data-domain') + '/wp-admin/options-general.php?page=flexi-translate&id=' + postId + '&lang=' + language;
            window.location = url;
        })
    }

    (function () {
        var goTranslateBtn = document.querySelector('.button__main-button');
        if (!goTranslateBtn) {
            return;
        }
        goTranslateBtn.addEventListener('click', function () {
            var data = new FormData();
            data.append('action', "postTranslate");
            data.append('lang', this.getAttribute('data-lang'));
            data.append('id', this.getAttribute('data-id'));
            var loader = document.querySelector('.container-loader');
            loader.classList.add('content-loading');


            (async () => {
                try {
                    var response = await fetch(ajax_filter.url, {
                        method: 'POST',
                        body  : data
                    });

                    var result = await response.json();

                    if (result !== null) {
                        loader.classList.remove('content-loading');
                        tinyMCE.get('input-result').setContent(result.res);
                        var acfBlocks = result.acf_blocks;
                        var title = result.postTitle;
                        var titleField = document.querySelector('.title__result');
                        titleField.value = title;
                        var textAreas = document.querySelectorAll('.textarea__result');
                        var text = document.querySelectorAll('.text__result');
                        var editorsWrapper = document.querySelectorAll('.editor__wrapper');
                        var resultBtn = document.querySelector('.result-btn');
                        var seoTitle = document.querySelector('.text__result.text__seoTitle');
                        var seoSocialTitle = document.querySelector('.text__result.text__seoSocialTitle');
                        if (seoTitle) {
                            seoTitle.value = result.yostSeoTitle;
                        }
                        if (seoSocialTitle) {
                            seoSocialTitle.value = result.yostSocialTitle;
                        }

                        resultBtn.classList.add('show');

                        for (const [key, value] of Object.entries(acfBlocks)) {
                            switch (key) {
                                case 'textarea':
                                    value.forEach(function (el, i) {
                                        textAreas.forEach(function (area, j) {
                                            if (i === j) {
                                                area.innerHTML = el;
                                            }
                                        });
                                    });
                                    break;
                                case 'text':
                                    value.forEach(function (el, i) {
                                        text.forEach(function (area, j) {
                                            if (i === j) {
                                                area.value = el;
                                            }
                                        });
                                    });
                                    break;
                                case 'wysiwyg':
                                    value.forEach(function (el, i) {
                                        editorsWrapper.forEach(function (area, j) {
                                            var spanId = area.querySelector('.editor-id');
                                            if (i === j) {
                                                tinyMCE.get(spanId.innerHTML).setContent(el);
                                            }
                                        });

                                    });
                                    break;
                            }
                        }
                    } else {
                    }
                } catch (e) {
                    loader.classList.remove('content-loading');
                    console.log(e);
                    alert('Слишком длинный текст')
                }

            })();
        })

    }());
    (function () {
        var btnCreatePost = document.querySelector('#createPost');
        if (!btnCreatePost) {
            return;
        }
        btnCreatePost.addEventListener('click', function (e) {
            e.preventDefault()
            var itemsToTranslateACF = document.querySelectorAll('[data-totranslate]');
            var editorsToTranslateACF = document.querySelectorAll('[data-totranslate_editor]');
            var translatedFields = {};
            var loader = document.querySelector('.container-loader');
            loader.classList.add('content-loading');


            editorsToTranslateACF.forEach(el => {
                translatedFields[el.getAttribute('data-totranslate_editor')] = tinyMCE.get(el.innerHTML).getContent();
            });

            translatedFields.title = document.querySelector('.title__result').value;
            translatedFields.content = tinyMCE.get('input-result').getContent();
            itemsToTranslateACF.forEach(function (el) {
                var key = el.getAttribute('data-totranslate');
                var value = el.value;
                translatedFields[key] = value;
            });

            var data = new FormData();
            data.append('action', "createTranslatedPost");
            data.append('lang', this.getAttribute('data-lang'));
            data.append('id', this.getAttribute('data-id'));
            data.append('fieldArray', JSON.stringify(translatedFields));



            (async () => {
                var response = await fetch(ajax_filter.url, {
                    method: 'POST',
                    body  : data
                });

                var result = await response.json();
                if (result !== null) {
                    loader.classList.remove('content-loading');
                    window.location = result.url + '/wp-admin/post.php?post=' + result.res + '&action=edit';
                } else {
                    console.log('error');
                }
            })();
        })

    })();
    (function () {
        var btnCreatePost = document.querySelector('#wp-admin-bar-addtranslate a');
        if (!btnCreatePost) {
            return;
        }
        btnCreatePost.addEventListener('click', function (e) {
            e.preventDefault()
            this.parentElement.classList.add('translateActive');
            var data = new FormData();
            data.append('action', "popUpCreate");

            (async () => {
                var response = await fetch(ajax_filter.url, {
                    method: 'POST',
                    body  : data
                });
                var result = await response.json();
                if (result !== null) {
                    var parser = new DOMParser();
                    var doc = parser.parseFromString(result.res, 'text/html');
                    this.parentElement.appendChild(doc.querySelector('.translateActive__popup'));
                    var btnClose = document.querySelector('.btn-close');
                    btnClose.addEventListener('click', function () {
                        document.querySelector('.translateActive__popup').remove();
                        document.querySelector('#wp-admin-bar-addtranslate').classList.remove('translateActive');
                    })

                    var btnTranslate = document.querySelector('.translateActive__popup .button-click-me');
                    btnTranslate.addEventListener('click', function (e) {
                        e.preventDefault();
                        var data = new FormData();
                        data.append('action', "popUpTranslate");
                        data.append('langCurrent', document.querySelector('.translateActive__popup #selectGoal').value);
                        data.append('langResult', document.querySelector('.translateActive__popup #selectResult').value);
                        data.append('textToTranslate', document.querySelector('.translateActive__popup #textareaGoal').value);
                        (async () => {
                            var response = await fetch(ajax_filter.url, {
                                method: 'POST',
                                body  : data
                            });

                            var result = await response.json();
                            if (result !== null) {
                                document.querySelector('.translateActive__popup #textareaResult').value = result.res
                            } else {
                                console.log('error');
                            }
                        })();
                    })
                } else {
                    console.log('error');
                }
            })()
        })

    })();
});

jQuery(function ($) {
    $(document).ready(function () {
        var buttons = $('.button-click-me a');
        var ripplesEffect = function (e) {
            var x = e.clientX - $(this).offset().left + $(window).scrollLeft();
            var y = e.clientY - $(this).offset().top + $(window).scrollTop();
            var ripples = $(`<span class="ripple" style="left: ${x}px; top: ${y}px"></span>`)
            $(this).append(ripples)
            setTimeout(() => {
                ripples.remove()
            }, 1000)
        }
        buttons.each(function () {
            $(this).on('mouseenter', ripplesEffect)
            $(this).on('click', ripplesEffect)
        })
    });
})
