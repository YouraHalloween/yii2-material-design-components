import { ajax } from './ajax.js'; 

/**
 * 
 * @param {stirng} id - id data table
 */

function DataTableProcessing(id) {
    /**
     * Создание объекта, без использования new
     */
    if (!new.target) {
        return new DataTableProcessing(id);
    }

    let th = this;
    let idDataTable = id;
    let currentUrl = '';

    let _onSuccess;
    let _onComplete;

    let table = app.controls.item(id);

    let pagination = table.root.querySelectorAll('.mdc-data-table__pagination');
    if (pagination.length > 0) {
        var summury = pagination[0].querySelector('.mdc-data-table__pagination-total');
        var nav = pagination[0].querySelector('.mdc-data-table__pagination-navigation');
    }
    let tableBody = table.root.querySelector('tbody');

    let rowsPage = app.controls.item(id + '-rows');
    let rowsReload = true;

    if (rowsPage) {
        rowsPage.root.addEventListener('MDCSelect:change', (event) => {
            if (rowsReload) {
                reload(getNewUrl(document.location.href, 'per-page', event.detail.value), true);
            } else {
                rowsReload = true;
            }
        });
    }

    let sortColumn = table.root.querySelectorAll('.mdc-data-table__header-cell--with-sort');

    //Запустить data-processing
    initNavigation();
    initSortColumn();

    function initSortColumn() {
        if (sortColumn.length > 0) {
            sortColumn.forEach((elem) => {
                elem.addEventListener('click', () => {
                    let link = currentUrl.length == 0 ? elem.getAttribute('link') : currentUrl;
                    let field = elem.getAttribute('data-column-id');
                    let sort = elem.getAttribute('aria-sort');
                    if (sort === 'ascending') {
                        field = '-' + field;
                    }
                    link = getNewUrl(link, 'sort', field);
                    reload(link, true);
                });
            });
        }
    }

    function redrawSortColumn(link) {
        if (sortColumn.length > 0) {
            let url = new URL(link);
            let sort = url.searchParams.get('sort');
            if (sort) {
                let dataColumnId = sort.replace(/^-/, '');
                sortColumn.forEach((elem) => {
                    if (elem.getAttribute('data-column-id') === dataColumnId) {
                        elem.classList.add('mdc-data-table__header-cell--sorted');
                        if (sort[0] === '-') {
                            elem.setAttribute('aria-sort', 'descending');
                            elem.classList.add('mdc-data-table__header-cell--sorted-descending');
                        } else {
                            elem.setAttribute('aria-sort', 'ascending');
                            elem.classList.remove('mdc-data-table__header-cell--sorted-descending');
                        }
                    } else {
                        elem.classList.remove('mdc-data-table__header-cell--sorted');
                        elem.classList.remove('mdc-data-table__header-cell--sorted-descending');
                        elem.setAttribute('aria-sort', 'none');
                    }
                });
            }
        }
    }

    function initNavigation() {
        if (pagination.length > 0) {
            let buttonsNav = pagination[0].querySelectorAll('.mdc-data-table__pagination-navigation button');

            if (buttonsNav.length > 0) {
                buttonsNav.forEach((elem) => {
                    let tagA = elem.querySelector('a');
                    tagA.addEventListener('click', (event) => {
                        event.preventDefault();
                    });
                    elem.addEventListener('click', () => {
                        reload(tagA.href, true);
                    })
                });
            }
        }
    }

    function setSummury(begin, end, totalCount) {
        let containers = summury.querySelectorAll('b');
        if (containers.length > 0) {
            containers[0].innerHTML = `${begin}-${end}`;
            containers[1].innerHTML = totalCount;
        }
    }

    function setNavigation(navContent) {
        if (navContent === false) 
        {
            nav.innerHTML = '';
        } else {
            nav.innerHTML = navContent;
            initNavigation();
        }
    }

    function setBody(data) {
        tableBody.classList.add('mdc-data-table__content-reload');
        setTimeout(() => {
            if (pagination.length > 0) {
                if (summury) {
                    let s = data.summury;
                    setSummury(s.begin, s.end, s.totalCount);
                }
                if (nav) {
                    setNavigation(data.nav);
                }
                if (rowsPage && rowsPage.value != data.pageSize.toString()) {
                    rowsReload = false;
                    rowsPage.value = data.pageSize.toString();
                }
            }
            tableBody.innerHTML = data.items;
            tableBody.classList.remove('mdc-data-table__content-reload');
        }, 150);
    }

    function reload(link, fromNavigation = false) {
        //Запомнить текущий Url с параметрами
        currentUrl = link;
        table.showProgress();
        ajax({
            'url': link
        })
            .done((data) => {
                if (data.status === 'success') {
                    if (typeof _onSuccess !== 'undefined') {
                        _onSuccess(data);
                    }
                    if (fromNavigation) {
                        window.history.pushState({ link: link, id: th.id }, '', link);
                    } else {
                        redrawSortColumn(link);
                    }
                    setBody(data.data);
                } else if (data.status === 'error') {
                    let snackbar = app.controls.item('app-snackbar');
                    snackbar.showMessage(data.message);
                    table.hideProgress();
                }
            })
            .complete((data) => {
                if (typeof _onComplete !== 'undefined') {
                    _onComplete(data);
                }
                table.hideProgress();
            })
            .get();
    }

    function getNewUrl(link, param, value) {
        let url = new URL(link);
        url.searchParams.set(param, value);
        return url.href;
    }

    /**
     * Перезагрузить таблицу
     * @param {string} link 
     */
    DataTableProcessing.prototype.reload = function (link) {
        reload(link, false);
    }

    /**     
     * @param {callback} fn - вызывается в случае успешного выполнения
     */
    DataTableProcessing.prototype.onSuccess = function (fn) {
        _onSuccess = fn;
    };

    /**     
     * @param {callback} fn - вызывается в любом случае
     */
    DataTableProcessing.prototype.onComplete = function (fn) {
        _onComplete = fn;
    };

    Object.defineProperty(DataTableProcessing.prototype, "id", {
        get: function () {
            return idDataTable + '-processing';
        },
        enumerable: true,
        configurable: true
    });
}

window.onpopstate = function (e) {
    // if (e.state) {
    //     app.controls.item(e.state.id).reload(e.state.link);
    // } else {
    //     document.location.reload();
    // }
};

export { DataTableProcessing };