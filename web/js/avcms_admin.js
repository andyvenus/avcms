var finder_already_loading = 0;var finder_page = 1;
History.Adapter.bind(window, 'statechange', pageChange);

var admin_url = 'http://localhost:8888/avcms/front.php/admin/';

$(document).ready(function() {
    var body = $('body');

    body.on('click', 'a', goToPage);

    body.on('change', '.ajax-editor form', editorFormChanged);
    body.on('change', 'form[name="filter_form"] select', changeFinderFilters);
    body.on('keyup', 'form[name="filter_form"] input', changeFinderFilters);

    //body.on("mouseenter mouseleave", '.browser-finder-item', toggleFinderOptions);

    body.on('submit', 'form', submitForm);

    body.on('click', '.reset-button', resetForm);

    Eventr.addEvent('page-modified', setBrowserFocus);

    Eventr.addEvent('submit-form-complete', browserFormSubmitted)

    Eventr.addEvent('submit-form-complete', function(form, response_data) {
        if (response_data.has_errors == false) {
            var title_id;
            if (form.find('input[name=title]').length >= 1) {
                title_id = 'title';
            }
            else if (form.find('input[name=name]').length >= 1) {
                title_id = 'name';
            }

            if (title_id !== undefined) {
                //$('data-item-id[]')
            }
        }
    })

    Eventr.addEvent('submit-form-success', function(form, response_data) {
        var item_id = form.data('item-id');

        if (item_id === 0) {
            form[0].reset();
            form.find('.form-errors').html('');
        }
    });

    onPageModified();
    setDataAjaxUrl($('.ajax-editor'), 'editor');
});

function onPageModified() {
    $("textarea[name=body]").markdown({autofocus:false,savable:false});
    $("select").select2();

    $(".nano").nanoScroller({ iOSNativeScrolling: true });

    $(document).trigger('pageModified');
    $('.nano-content').on('scroll', finderLoadMore);

    // Finder
    finder_already_loading = 0;
    finder_page = 1;

    Eventr.fireEvent('page-modified');
}

function goToPage(url, set_name) {
    if (url === undefined || typeof url != 'string') {
        url = $(this).attr('href');
    }

    if (url.indexOf('admin') <= 0) {
        return true;
    }

    if (url != '#') {
        History.pushState({state:1}, document.title, url);
        return false;
    }
    else if (set_url !== undefined && typeof set_url == 'string') {
        if (url != '#') {
            History.pushState({state:1}, set_name, set_url);
            return false;
        }
    }

    return true;
}

function pageChange() {
    var full_url = getCurrentUrl();

    var previous_url = getPreviousUrl();

    var prev_attr = previous_url.split('admin/').pop().split('/');
    var current_attr = full_url.split('admin/').pop().split('/');

    var prev_attr_clean = removeAllAfter('?', prev_attr[0]);

    var func_name;

    var ajax_depth;
    if ($.trim(prev_attr_clean) == $.trim(current_attr[0]) && $('.ajax-editor').length > 0) {
        ajax_depth = 'editor';
        func_name = 'append';
    }
    else {
        ajax_depth = 'main';
        func_name = 'html';
    }

    var content_container = $('.ajax-'+ajax_depth);

    var ajax_required = true;

    if (ajax_depth == 'editor') {
        var existing_editor = $('div[data-ajax-url="'+full_url+'"]');

        if (existing_editor.length > 0) {
            $('.ajax-editor-inner').hide();
            existing_editor.show();
            setPageTitle(full_url);
            setBrowserFocus();
            ajax_required = false;
        }
    }

    if (full_url.indexOf('?') < 0) {
        full_url = full_url + '?';
    }

    if (ajax_required === true) {
        $.get(full_url+'&ajax_depth='+ajax_depth, function(data) {
            $('.ajax-'+ajax_depth+'-inner').hide();
            content_container[func_name](data);

            setDataAjaxUrl(content_container, ajax_depth);

            setPageTitle(full_url);

            onPageModified();
        });
    }
}

function setDataAjaxUrl(content_container, ajax_depth) {
    var ajax_inner = content_container.find('.ajax-'+ajax_depth+'-inner').last()
    if (ajax_inner.data('ajax-url') == undefined) {
        ajax_inner.attr('data-ajax-url', getCurrentUrl());
    }
}

function setPageTitle(url) {
    var page_title = $('[data-ajax-url="'+url+'"]').find('.ajax_page_title');

    if (page_title.length) {
        if (page_title.html() != '') {
            document.title = page_title.html();
        }
        else {
            document.title = 'Unnamed Page';
        }
    }
}

function removeAllAfter(match, str) {
    if (str.indexOf(match) > 0) {
        return str.substring(0, str.indexOf(match));
    }
    else {
        return str;
    }
}

function getCurrentUrl(dont_remove_suid) {
    var State = History.getState(),
        states = History.savedStates,
        prevUrlIndex = states.length - 1;

    var hash = states[prevUrlIndex].hash;

    if (!dont_remove_suid) {
        hash = removeParameter(hash, '_suid');
    }

    return hash;
}

function getPreviousUrl() {
    var State = History.getState(),
        states = History.savedStates,
        prevUrlIndex = states.length - 2;

    return states[prevUrlIndex].hash;
}

function editorFormChanged() {
    var id = $(this).attr('data-item-id');

    if (id != 0) {
        $('.header-label-container:visible').html('<span class="label label-warning animated flipInY">Edited</span>');
        $('.browser-finder-item[data-id="'+id+'"]').addClass('finder-item-edited');
    }
}

/***********
 BROWSER
***********/

function browserFormSubmitted(form, response_data) {
    if (response_data.form.has_errors === false) {
        var id = form.attr('data-item-id')
        if (id !== undefined) {
            $(form).find('*').filter(':input:not(button)').each(function(i, form_field){
                form_field = $(form_field);
                var field_name = form_field.attr('name');

                if (form_field.prop('tagName') != 'SELECT') {
                    var field_value = form_field.val();
                }
                else {
                    var field_value = form_field.find(":selected").text();
                }

                $('[data-id="'+id+'"][data-field='+field_name+'], [data-id="'+id+'"] > [data-field='+field_name+']').html(field_value);
            });

            if (id != 0) {
                $('.header-label-container:visible').html('<span class="label label-success animated flipInY">Saved</span>');
            }

            $('.browser-finder-item[data-id="'+id+'"]').addClass('finder-item-saved').removeClass('finder-item-edited');
        }
    }
}

function finderLoadMore() {
    if(($(this).scrollTop() + ($(this).innerHeight() + 150) >= $(this)[0].scrollHeight) && (window.finder_already_loading != 1)) {
        finder_already_loading = 1;
        var new_page = finder_page + 1;
        var form_serial = $('form[name="filter_form"]').serialize() + '&page=' + new_page;

        var finder = $(this).find('[data-url]');

        $.get(finder.data('url') + '?' + form_serial, function(data) {
            if (data) {
                finder.append(data);
                finder_already_loading = 0;
                finder_page = new_page;
            }
        })
    }
}

function changeFinderFilters() {
    var form_serial = $('form[name="filter_form"]').serialize();

    var finder = $('.finder-ajax').find('[data-url]');
    finder_already_loading = 1;

    $.get(finder.data('url') + '?' + form_serial, function(data) {
        if (data) {
            finder.html(data);
            finder_already_loading = 0;
            finder_page = 1;
        }
    })
}

function setBrowserFocus() {
    if ($('.ajax-editor-inner').children().filter(':visible').length < 1) {
        $('.browser-finder').addClass('finder-has-focus');
        $('.browser-editor').removeClass('editor-has-focus');
    }
    else {
        $('.browser-finder').removeClass('finder-has-focus');
        $('.browser-editor').addClass('editor-has-focus');
    }
}

/***********
 FORMS
***********/

function submitForm() {
    var data = $(this).serialize();
    var form = $(this);

    $.ajax({
        type: "POST",
        url: getCurrentUrl(), //todo: change to use form url or getCurrentUrl() depending on whether form url is set
        dataType: 'json',
        data: data,
        success: function(data) {
            form.find('.has-error').removeClass('has-error');

            var messages = $(form).find('.form-errors');
            messages.html('');

            if (data.form.has_errors === true) {
                for (var id in data.form.errors) {
                    form.find('[name='+data.form['errors'][id]['param']+']').closest('.form-group').addClass('has-error');

                    messages.append('<div class="alert alert-danger animated bounce">'+data.form['errors'][id]['message']+'</div>');
                }
            }
            else {
                if (data.redirect) {
                    goToPage(data.redirect);
                }

                messages.append('<div class="alert alert-success animated bounce">Saved!</div>');

                Eventr.fireEvent('submit-form-success', [form, data]);
            }

            Eventr.fireEvent('submit-form-complete', [form, data]);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            var messages = $(form).find('.form-errors');
            messages.html('<div class="alert alert-danger animated bounce">Save Error: '+errorThrown+'</div>');
        }
    });

    return false;
}

function resetForm()
{
    $(this).parent('form')[0].reset();

    var id = $(this).parent('form').attr('data-item-id');
    $('.browser-finder-item[data-id="'+id+'"]').removeClass('finder-item-edited');

    $('.header-label-container:visible').html('&nbsp;');
}

/***********
 Events
 ************/

var Eventr = new function () {
    this.events = [];

    this.fireEvent = function (event, args) {
        //console.log('Event ' + event + ' fired')
        if (!args) {
            args = [];
        }

        if (this.events[event] === undefined) {
            this.events[event] = [];
        }

        var arrayLength =  this.events[event].length;
        for (var i = 0; i < arrayLength; i++) {
            this.events[event][i].apply(this, args);
        }
    };

    this.addEvent = function (event, fn) {
        if (this.events[event] === undefined) {
            this.events[event] = [];
        }

        this.events[event].push(fn);
    }
};

/***********
 Styling & Layout
 ************/

function toggleFinderOptions()
{
    $(this).find('.finder-item-options').toggle();
    $(this).find('.finder-item-subheading').toggle();
}

/* GENERAL HELPER FUNCTIONS */
function removeParameter(url, parameter)
{
    var urlparts= url.split('?');

    if (urlparts.length>=2)
    {
        var urlBase=urlparts.shift(); //get first part, and remove from array
        var queryString=urlparts.join("?"); //join it back up

        var prefix = encodeURIComponent(parameter)+'=';
        var pars = queryString.split(/[&;]/g);
        for (var i= pars.length; i-->0;)               //reverse iteration as may be destructive
            if (pars[i].lastIndexOf(prefix, 0)!==-1)   //idiom for string.startsWith
                pars.splice(i, 1);
        url = urlBase+'?'+pars.join('&');
    }
    return url;
}