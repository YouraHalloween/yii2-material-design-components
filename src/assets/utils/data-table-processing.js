// import { ajax } from './ajax.js'; 

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
    let _eventSuccess;
    let _eventComplete;

    let table = app.controls.item(id);
    let pagination = table.root.querySelectorAll('.mdc-data-table__pagination');
    if (pagination.length > 0) {
        var summury = pagination[0].querySelector('.mdc-data-table__pagination-total');
        var nav = pagination[0].querySelector('.mdc-data-table__pagination-navigation');
    }
    let tableBody = table.root.querySelector('tbody');

    let rows = app.controls.item(id+'-rows');

    if (rows) {
        console.log(rows.value);
        rows.root.addEventListener('MDCSelect:change', (value) => {
            console.log(rows.value);
        });
    }

    if (pagination.length > 0) {
        refreshNavigation();
    }

    function refreshNavigation() {
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
        nav.innerHTML = navContent;
        refreshNavigation();
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
            }
            tableBody.innerHTML = data.items;
            tableBody.classList.remove('mdc-data-table__content-reload');
        }, 150);
    }

    function reload(link, fromNavigation = false) {
        table.showProgress();
        app.utils.ajax({
                'url': link
            })
            .done((data) => {                
                if (data.status === 'success') {
                    if (typeof _eventSuccess !== 'undefined') {
                        _eventSuccess(data);
                    }                    
                    if (fromNavigation) {
                        window.history.pushState({link: link, id: th.id}, '', link);
                    }
                    setBody(data.data);
                } else if (data.status === 'error') {                    
                    let snackbar = app.controls.item('app-snackbar');
                    snackbar.showMessage(data.message);
                    table.hideProgress();
                }
            })
            .complete((data) => {
                if (typeof _eventComplete !== 'undefined') {
                    _eventComplete(data);
                }
                table.hideProgress();
            })
            .get();
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
    DataTableProcessing.prototype.eventSuccess = function (fn) {
        _eventSuccess = fn;
    };

    /**     
     * @param {callback} fn - вызывается в любом случае
     */
    DataTableProcessing.prototype.eventComplete = function (fn) {
        _eventComplete = fn;
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
    if (e.state) {
        app.controls.item(e.state.id).reload(e.state.link);
    } else {
        document.location.reload();
    }
};

// export { DataTableProcessing };