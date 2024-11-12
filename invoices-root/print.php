<?php
require_once dirname(__DIR__) . '/config/constant.php';

$name = $_GET['name'];
$id   = $_GET['id'];

switch ($name) {
    case 'sales':
        $url = "sales-invoice.php?id=$id";
        break;
    case 'salesReturn':
        $url = "sales-return-invoice.php?id=$id";
        break;    
    case 'lab_invoice':
        $url = "lab-invoice.php?id=$id";
        break;
    case 'report':
        $url = "../test-report-show.php?id=$id";
        break;
    case 'prescription':
        $url = "../prescription.php?prescription=$id";
        break;    
    default:
        # code...
        break;
}

?>
<!DOCTYPE html>
<html>

<head>
    <title>Print</title>
    <style>
        #frame-area {
            width: 100%;
        }

        .buttons {
            display: none;
        }


        /* CSS */
        .button-32 {
            text-decoration: none;
            background-color: #5E5DF0;
            border-radius: 8px;
            color: #fff;
            cursor: pointer;
            font-weight: bold;
            padding: 10px 15px;
            text-align: center;
            transition: 200ms;
            width: 100px;
            box-sizing: border-box;
            border: 0;
            font-size: 16px;
            user-select: none;
            -webkit-user-select: none;
            touch-action: manipulation;
        }

        .button-32:not(:disabled):hover,
        .button-32:not(:disabled):focus {
            outline: 0;
            background: #4948d9;
            box-shadow: 0 0 0 2px rgba(0, 0, 0, .2), 0 3px 8px 0 rgba(0, 0, 0, .15);
        }

        .button-32:disabled {
            filter: saturate(0.2) opacity(0.5);
            -webkit-filter: saturate(0.2) opacity(0.5);
            cursor: not-allowed;
        }

        .sale-btn {
            text-decoration: none;
            background-color: #058779fc;
            border-radius: 8px;
            color: #fff;
            cursor: pointer;
            font-weight: bold;
            padding: 10px 15px;
            text-align: center;
            transition: 200ms;
            width: 100px;
            box-sizing: border-box;
            border: 0;
            font-size: 16px;
            user-select: none;
            -webkit-user-select: none;
            touch-action: manipulation;
        }

        .sale-btn:not(:disabled):hover,
        .sale-btn:not(:disabled):focus {
            outline: 0;
            background: #055d53fc;
            box-shadow: 0 0 0 2px rgba(0, 0, 0, .2), 0 3px 8px 0 rgba(0, 0, 0, .15);
        }

        .sale-btn:disabled {
            filter: saturate(0.2) opacity(0.5);
            -webkit-filter: saturate(0.2) opacity(0.5);
            cursor: not-allowed;
        }

        .print-btn {
            background-color: #04AA6D;
            border-radius: 8px;
            color: #fff;
            cursor: pointer;
            font-weight: bold;
            padding: 10px 15px;
            text-align: center;
            transition: 200ms;
            width: 100px;
            box-sizing: border-box;
            border: 0;
            font-size: 16px;
            user-select: none;
            -webkit-user-select: none;
            touch-action: manipulation;
        }

        .print-btn:not(:disabled):hover,
        .print-btn:not(:disabled):focus {
            outline: 0;
            background: #0c704c;
            box-shadow: 0 0 0 2px rgba(0, 0, 0, .2), 0 3px 8px 0 rgba(0, 0, 0, .15);
        }

        .print-btn:disabled {
            filter: saturate(0.2) opacity(0.5);
            -webkit-filter: saturate(0.2) opacity(0.5);
            cursor: not-allowed;
        }

        @media (min-width: 992px) {

            #frame-area {
                width: 50%;
                margin: auto;
            }

            .buttons {
                display: flex;
                justify-content: space-evenly;
                padding: 10px;
            }
        }
    </style>
</head>

<body>
    <div id="frame-area">
        <iframe id="pdfFrame" src="<?= $url ?>" width="100%" height="650" style="border: none;padding:0px;"></iframe>
        <div class="buttons">
            <!-- HTML !-->
            <a href="<?= URL ?>sales.php" class="button-32">Back</a>
            <a href="<?= URL ?>new-sales.php" class="sale-btn">New Sale</a>
            <button type="button" class="print-btn" onclick="document.getElementById('pdfFrame').contentWindow.print()">Print</button>
        </div>
    </div>
    <script>
        window.onload = function() {
            var pdfFrame = document.getElementById('pdfFrame');
            pdfFrame.focus();
            pdfFrame.contentWindow.print();
        };
    </script>
</body>

</html>