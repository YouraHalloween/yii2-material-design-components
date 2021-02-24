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

    let _eventSuccess;

    let _table = app.controls.item(id);    

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

    // Object.defineProperty(DataTableProcessing.prototype, "id", {
    //     get: function () {
    //         return _$form.attr('id');
    //     },
    //     set: function (value) {
    //         if (this.id != value) {
    //             _$form.attr('id', value);
    //         }
    //     },
    //     enumerable: true,
    //     configurable: true
    // });
}

// export { DataTableProcessing };