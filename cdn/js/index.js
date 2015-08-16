/**
 * Created by Valery Tsarov on 15.08.15.
 */

var AjaxUrl = 'ajax/post.ajax.php',
    TableColumnsCount = 3,
    TableRefreshPause = 3000,
    TAId = null, //link to setTimeout
    LastModified = 1, //Do we need render table?
    oPage = {};

oPage.confirmCreateRow = function(oContainer) {
    $('#confirmCreate').modal('show');
}

oPage.createRow = function() {
    var Name  = $("#commodity-name").val();
    if ( Name == "" ) {
        alert("Field can't be empty!");
        return;
    }
    $.ajax({
        url: AjaxUrl,
        data: 'Action=Create&Goods=Trip&Name='+Name,
        dataType: 'json',
        type: 'post',
        cache: false,
        success: function (response) {
            var Html = "", DataName, DataVal, DataPK, Id, TIndex;
            $('#confirmCreate').modal('hide');
            for (Id in response) {

            }
            Html += '<tr id="trId_'+Id+'">\n';
            Html += '<td></td>';
            for (TIndex in response[Id]) {
                DataName = ' data-name="'+TIndex+'" ';
                DataVal = response[Id][TIndex];
                DataPK = ' data-pk="'+Id+'" ';
                Html += '<td><a data-original-title="Введите новое имя" '+DataPK+DataName+' data-params="{Action: \'Update\', Goods: \'Trip\'}" data-type="text" href="#" class="goodsRow column'+TIndex+'" data-value="'+DataVal+'">'+DataVal+'</a></td>';
            }
            Html += '<td><a class="btn btn-danger btn-xs btn-small" data-name="'+response[Id].Name+'" data-pk="'+Id+'" onclick="oPage.confirmDeleteRow(this);">X</a></td>';
            //Html += '<td><a class="btn btn-danger btn-xs btn-small" data-name="'+Data[Id].Name+'" data-pk="'+Id+'" data-toggle="modal" data-target="#confirmDelete">X</a></td>';
            Html += "</tr>\n";
            $('#GoodsTable tbody').append(Html);
            oPage.addEditable();
        //},
        //error: function () {
        }
    });
}

oPage.renderTable = function(Data) {
    clearTimeout(TAId);
    if ( !(Data instanceof Object) ) {
        TAId = setTimeout(oPage.loadContent, TableRefreshPause);
        return;
    }
    $('.columnName').popover('hide');
    var Html = '',
        RowCnt = 0,
        DataName = '',
        DataVal = '',
        DataText = '',
        DataPK = '',
        UDObj = null;

    for (Id in Data) {
        Html += '<tr id="trId_'+Id+'">\n';
        DataPK = ' data-pk="'+Id+'" ';
        Html += '<td></td>';
        for (TIndex in Data[Id]) {
            DataName = ' data-name="'+TIndex+'" ';
            DataVal = Data[Id][TIndex];
            Html += '<td><a data-original-title="Введите новое имя" '+DataPK+DataName+' data-params="{Action: \'Update\', Goods: \'Trip\'}" data-type="text" href="#" class="goodsRow column'+TIndex+'" data-value="'+DataVal+'">'+DataVal+'</a></td>';
        }
        Html += '<td><a class="btn btn-danger btn-xs btn-small" data-name="'+Data[Id].Name+'" data-pk="'+Id+'" onclick="oPage.confirmDeleteRow(this);">X</a></td>';
        //Html += '<td><a class="btn btn-danger btn-xs btn-small" data-name="'+Data[Id].Name+'" data-pk="'+Id+'" data-toggle="modal" data-target="#confirmDelete">X</a></td>';
        Html += "</tr>\n";
    }
    $('#GoodsTable tbody').html(Html);
    oPage.addEditable();
    TAId = setTimeout(oPage.loadContent, TableRefreshPause);
}

oPage.addEditable = function() {
    $('.columnName').editable({
        type:  'text',
        name:  'tripname',
        url:   AjaxUrl,
        ajaxOptions: { dataType: 'json' },
        success: function(response) {
            if (response) {
                $('.columnName').popover('hide');
                oPage.renderTable(response);
            }
        },
        title: 'Enter trip name'
    });
}

oPage.loadContent = function() {
    $.ajax({
        url: AjaxUrl,
        data: 'Action=Read&Goods=Trip&LastModified='+LastModified,
        dataType: 'json',
        type: 'post',
        cache: false,
        success: function (response) {
            if ( undefined!==response.LastModified ) {
                LastModified = response.LastModified;
                delete response.LastModified;
            }
            oPage.renderTable(response);
        //},
        //error: function () {
        }
    });
}

oPage.confirmDeleteRow = function(oContainer) {
    $('#ButtonConfirmDelete').attr('data-pk', $(oContainer).data('pk'));
    $('#ConfirmDelete-modal-body').text('Do you want delete `'+$(oContainer).data('name')+'`?');
    $('#confirmDelete').modal('show');
}

oPage.deleteRow = function(oContainer) {
    $.ajax({
        url: AjaxUrl,
        data: 'Action=Delete&Goods=Trip&pk='+$(oContainer).attr('data-pk'),
        dataType: 'json',
        type: 'post',
        cache: false,
        success: function (response) {
            $('#confirmDelete').modal('hide');
            $('#trId_'+$(oContainer).attr('data-pk')).remove();
            oPage.renderTable(response);
        //},
        //error: function () {
        }
    });

}

$( document ).ready(function() {
    oPage.loadContent();
    $("#AjaxLoader").ajaxError(function(event, jqxhr, settings, thrownError){
        $(this).hide();
        console.log('ajaxError', event, jqxhr, settings, thrownError);
        alert(jqxhr.responseText);
    });    $("#AjaxLoader").ajaxStop(function(){
        $(this).hide();
    });
    $("#AjaxLoader").ajaxStart(function(){
        $(this).show();
    });
});
