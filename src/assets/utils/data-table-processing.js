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

    let idDataTable = id;
    let _eventSuccess;

    let _table = app.controls.item(id);
    console.log(_table);
    let _pagination = _table.root.querySelectorAll('.mdc-data-table__pagination');

    if (_pagination.length > 0) {
        _refreshNavigation();
    }
    
    function _refreshNavigation() {
        if (_pagination.length > 0) {
            let _buttonsLink = _pagination[0].querySelectorAll('.mdc-data-table__pagination-navigation button a');

            if (_buttonsLink.length > 0) {
                _buttonsLink.forEach((elem) => {
                    elem.addEventListener('click', (event) => {
                        event.preventDefault();
                        console.log(event.target.href);
                        _table.showProgress();
                    });
                });
            }
        }
    }   

    DataTableProcessing.prototype.refreshNavigation = function() {
        _refreshNavigation();
    }

    // ax.done((data) => {
    //     if (data.status === 'success') {
    //         _eventSuccess(data);
    //     } else if (data.status === 'model-error') {
    //         let focusField = false;
    //         let snackbar = app.controls.item('app-snackbar');
    //         for (key in data.message) {
    //             data.message[key].forEach((item) => {
    //                 snackbar.add(item);
    //             })
    //             if (key.trim() !== '') {
    //                 let control = app.controls.item(key);

    //                 if (!focusField) {
    //                     control.focus();
    //                     focusField = true;
    //                 }

    //                 control.valid = false;
    //                 control.helperMessage.error = data.message[key][0];
    //             }
    //         }
    //         snackbar.showMessage();
    //     }
    // })
    //     .complete((data) => {
    //         let unblock = _blockedControls.unblock || !(data.status === 'success');

    //         if (_blockedControls.control == 'all') {
    //             app.controls.groupEnabled(_$form.attr('id'), unblock);
    //         } else {
    //             _submit.disabled = !unblock;
    //         }
    //     })
    //     .post();


    // /**     
    //  * @param {callback} fn - вызывается в случае успешного выполнения
    //  */
    // DataTableProcessing.prototype.eventSuccess = function (fn) {
    //     _eventSuccess = fn;
    // };

    Object.defineProperty(DataTableProcessing.prototype, "id", {
        get: function () {
            return idDataTable + '-processing';
        },
        enumerable: true,
        configurable: true
    });
}

// export { DataTableProcessing };