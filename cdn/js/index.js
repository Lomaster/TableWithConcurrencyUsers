/**
 * Created by Valery Tsarov on 15.08.15.
 */

var AjaxUrl = 'ajax/post.ajax.php',
    TableColumnsCount = 3,
    oPage = {};

function addNewUser() {
    var FistName  = $("#FistName").val();
    var LastName = $("#LastName").val();
    var Email = $("#UserMail").val();
    var Phone = $("#Phone").val();
    if ( FistName == "" ) {
        alert("Имя не может быть пустым");
        return;
    }
    if ( LastName == "" ) {
        alert("Фамилия не может быть пустым");
        return;
    }
    if ( Email == "" ) {
        alert("Почта не может быть пустым");
        return;
    }
    $.ajax({
        url: '/auser/ajax',
        data: 'Action=AddUser&Firstname='+FistName+'&Lastname='+LastName+'&Email='+Email+'&Phone='+Phone,
        dataType: 'json',
        type: 'post',
        cache: false,
        success: function (response) {
            $('#alert-modal-content').html(response.Message);
            $('#alert-modal').modal('show');
            if ( response.ok ) {
                getUserList();
                $('#signup_form').trigger( 'reset' );
            }
        },
        error: function () {
            $('#alert-modal-content').html("Ошибка соединения с сервером!");
            $('#alert-modal').modal('show');
        }
    });
}

//Изменения фильтров
function changeFilterRoles() {
    FilterRole = $('#filter_roles').val();
    getUserList();
}
function changeFilterStatuses() {
    FilterStatus = $('#filter_status').val();
    getUserList();
}
function changeSort() {
    if ( SortPage>0 ) {
        var Sort = "asc";
        SortPage = -1;
    } else {
        var Sort = "desc"
        SortPage = 1;
    }
    $("#sort_conteiner").html(" № <i class='fa fa-sort-alpha-"+Sort+"'></i>");
    getUserList();
}

//Изменения в пагинации
function changePerPage() {
    PerPage = $('#rows_per_page').val();
    getUserList();
}

function changePage(Direction) {
    CurrentPage = Direction;
    getUserList();
}

function getUserList() {
    $.ajax({
        url: AjaxUrl,
        data: 'Action=GetUserList&CurrentPage='+CurrentPage+'&PerPage='+PerPage+'&FilterRole='+FilterRole+'&FilterStatus='+FilterStatus+'&SortPage='+SortPage,
        dataType: 'json',
        type: 'post',
        success: function (response) {
            if ( !response.ok ) {
                $('#alert-modal-content').html(response.Message);
                $('#alert-modal').modal('show');
            } else {
                CurrentPage = response.CurrentPage;
                PerPage = response.PerPage;
                RowCount = response.RowCount;
                renderTable(response.ListUsers);
            }
        },
        error: function () {
            $('#alert-modal-content').html("Ошибка соединения с сервером!");
            $('#alert-modal').modal('show');
        }
    });
}

oPage.renderTable = function(Data) {
    var Html = '',
        RowCnt = 0,
        DataName = '',
        DataVal = '',
        DataText = '',
        DataPK = '',
        UDObj = null;
    if ( !Data.length ) {
        Html = 'No data';
    }
    for (Id in Data) {
        console.log(Id, Data[Id]);
        //Не выводим тех кто старше нас по статусу
        RowCnt++;

        Html += "<tr>\n";
        DataPK = ' data-pk="'+Id+'" ';
        //DataParams = ' data-params="{\'Action\': \'EditUser\'}" ';
        DataParams = '';
        Html += '<td>'+RowCnt+'</td>';
        for (TIndex in Data[Id]) {
            DataName = ' data-name="'+TIndex+'" ';
            DataVal = Data[Id][TIndex];
            Html += '<td><a data-original-title="Введите новое имя" '+DataPK+DataName+' data-type="text" href="#" class="goodsRow column'+TIndex+'" data-value="'+DataVal+'">'+DataVal+'</a></td>';
        }
        Html += '<td><a class="btn btn-danger btn-xs btn-small" data-name="'+Data[Id].Name+'" data-pk="'+Id+'" onclick="oPage.confirmDeleteRow(this);">X</a></td>';
        //Html += '<td><a class="btn btn-danger btn-xs btn-small" data-name="'+Data[Id].Name+'" data-pk="'+Id+'" data-toggle="modal" data-target="#confirmDelete">X</a></td>';

        Html += "</tr>\n";
    }
    if ( !Html ) {
        Html += "<tr><td colspan="+TableColumnsCount+" align=center>Нет записей</td></tr>\n";
    }
    $('#GoodsTable tbody').html(Html);

    $('.columnName').editable({
        type:  'text',
        name:  'tripname',
        url:   AjaxUrl,
        ajaxOptions: { dataType: 'json' },
        success: function(response) {
            $('.columnName').popover('hide');
            oPage.renderTable(response);
//            var CurrObj = $(this);
//            var current_value = CurrObj.data('value');
//            //console.log(response, current_value, AdminRolesObj[current_value]);
////	 		    alert(response.Message);
//            $('#alert-modal-content').html(response.Message);
//            $('#alert-modal').modal('show');
//            if ( !response.ok ) {
//                CurrObj.data('value', current_value);
//// 			    	console.log(CurrObj);
//                switch ( CurrObj.data('name') ) {
//                    case "Block":
//                        CurrObj.text(BlockTypesObj[current_value]);
//                        break;
//                    case "Role":
//                        CurrObj.text(AdminRolesObj[current_value]);
//                        break;
//// 			    		case "Statuses":
//// 			    			var InputObj = $('.input-sm');
//// 				    		console.log(InputObj.val());
//// 			    			CurrObj.text(InputObj.join(","));
//// 				    		break;
//                }
//
//            } else {
//                switch ( CurrObj.data('name') ) {
//                    case "Password":
//                        response.ok = false;
//                        $('.editable-cancel').trigger('click');
//                        break;
//                    case "Statuses":
//                        var SelectedData = $('.input-sm').val();
//                        var DataText = "";
//                        for (index in SelectedData) {
//                            if ( SelectedData[index]=='None' ) {
//                                DataText = "Empty  ";
//                                SelectedData = ['None'];
//                                break;
//                            } else if ( SelectedData[index]=='Full' ) {
//                                DataText = "";
//                                SelectedData = ['Full'];
//                                for (sindex in StatusesObj) {
//                                    if ( sindex=='None' || sindex=='Full' ) {
//                                        continue;
//                                    }
//                                    DataText += StatusesObj[sindex]+", ";
//                                }
//                                break;
//                            }
//                            DataText += StatusesObj[SelectedData[index]]+", ";
//                        }
//                        DataText = DataText.substr(0, DataText.length-2);
//                        CurrObj.text(DataText);
//                        CurrObj.data({'value': SelectedData});
//                        response.ok = false;
//                        $('.editable-cancel').trigger('click');
//                        break;
//                }
//
//            }
//            return response.ok;

        },
        title: 'Enter trip name'
    });
}

oPage.loadContent = function() {
    var Data = {
            1111: {Name: 'ololo'},
            222222: {Name: 'trololo'},
            33333: {Name: 'dfj df'}
        };
    console.log('Load content');
    oPage.renderTable(Data);
}

oPage.confirmDeleteRow = function(oContainer) {
    $('#ButtonConfirmDelete').attr('data-pk', $(oContainer).data('pk'));
    $('#ConfirmDelete-modal-body').text('Do you want delete `'+$(oContainer).data('name')+'`?');
    $('#confirmDelete').modal('show');
    //console.log('confirmDeleteRow', $(oContainer).data('pk'), $(oContainer).data('name'));
}

oPage.deleteRow = function(oContainer) {
    console.log('deleteRow', $(oContainer).attr('data-pk'));
}

oPage.confirmAddRow = function() {
    console.log('addRow');
}

oPage.addRow = function() {
    console.log('addRow');
}

$( document ).ready(function() {
    oPage.loadContent();
});
