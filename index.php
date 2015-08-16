<?php
/**
 * Created by Valery Tsarov.
 * Date: 14.08.15
 */
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="Table for selection of goods">
    <meta name="author" content="Valery Tsarov">

    <title>Simple table of goods</title>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
    <script src="cdn/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="cdn/css/bootstrap.min.css">

    <!--    bootstrap-editable-->
    <script src="cdn/bootstrap-editable/bootstrap-editable.min.js"></script>
    <link href="cdn/bootstrap-editable/bootstrap-editable.css" rel="stylesheet">

    <style>
        .starter-template {
            padding: 40px 15px;
            text-align: center;
            width: 600px;
        }
        #GoodsTable {
            width: 100%;
        }
        /*Auto-numeration*/
        #GoodsTable tbody tr:not(.fble_htr) {
            counter-increment: rowNumber;
        }
        #GoodsTable tbody tr:not(.fble_htr) td:first-child::before {
            content: counter(rowNumber);
            min-width: 1em;
            margin-right: 0.5em;
        }
    </style>
</head>

<body>
<div class="container">

    <div class="starter-template">
        <h1>Goods <button type="button" class="btn btn-primary btn-large" onclick="oPage.confirmCreateRow();">+</button>
            <div id='AjaxLoader' style="float: right; display: inline;"><img src="cdn/img/loading.gif" /></div></h1>
        <p class="lead">
        <div class="bs-example">
            <table class="table-striped" id="GoodsTable">
                <thead>
                <tr>
                    <th>Row</th>
                    <th>Name</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
        </p>
    </div>

</div><!-- /.container -->

<!-- Modal -->
<div class="modal fade" id="confirmDelete" tabindex="-1" role="dialog" aria-labelledby="DeleteRow">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="DeleteRow">Remove row</h4>
            </div>
            <div class="modal-body" id="ConfirmDelete-modal-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button id="ButtonConfirmDelete" type="button" class="btn btn-danger"  data-pk="" onclick="oPage.deleteRow(this);">Delete</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="confirmCreate" tabindex="-1" role="dialog" aria-labelledby="AddRow">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="AddRow">Create row</h4>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label class="control-label" for="recipient-name">Name:</label>
                        <input type="text" id="commodity-name" class="form-control">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" onclick="oPage.createRow();">Create</button>
            </div>
        </div>
    </div>
</div>

<script src="cdn/js/index.js"></script>

</body>
</html>
