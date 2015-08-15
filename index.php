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
<!--    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>-->
    <script src="cdn/js/bootstrap.min.js"></script>
<!--    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">-->
    <link rel="stylesheet" href="cdn/css/bootstrap.min.css">
<!--    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">-->

    <!--    bootstrap-editable-->
    <script src="cdn/bootstrap-editable/bootstrap-editable.min.js"></script>
    <link href="cdn/bootstrap-editable/bootstrap-editable.css" rel="stylesheet">
<!--    <script type='text/javascript' src='cdn/bootstrap-editable-custom.js'></script>-->

    <style>
        body {
        }
        .starter-template {
            padding: 40px 15px;
            text-align: center;
            width: 600px;
        }
        .LoaderGifCont {
            /*display: none;*/
            display: inline;
            width: 16px;
        }
        #LoaderGif {
            display: none;
        }
        #GoodsTable {
            width: 100%;
        }
    </style>
</head>

<body>

<div class="container">

    <div class="starter-template">
        <h1>Goods <button type="button" class="btn btn-primary btn-large" onclick="oPage.addRow();">+</button></h1>
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
                <tr class="active">
                    <td>1</td>
                    <td><a href="#" class="goodsRow">Credit Card</a></td>
                    <td><a class="btn btn-danger btn-xs btn-small" data-toggle="modal" data-target="#confirmDelete">X</a></td>
                </tr>
                <tr class="success">
                    <td>2</td>
                    <td><a data-original-title="Enter tripname" data-name="tripname" data-pk="1" data-type="text" id="tripname" href="#" class="editable">superuser 1</a></td>
                    <td>01/07/2014</td>
                </tr>
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
<div class="modal fade" id="confirmAdd" tabindex="-1" role="dialog" aria-labelledby="AddRow">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="AddRow">Remove row</h4>
            </div>
            <div class="modal-body">
                Are you sure?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger" onclick="oPage.addRow();">Delete</button>
            </div>
        </div>
    </div>
</div>

<script src="cdn/js/index.js"></script>

</body>
</html>
