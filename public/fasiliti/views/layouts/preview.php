<?php
use yii\helpers\Html;
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Preview</title>
    <?php $this->head() ?>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #fff;
            font-family: Arial, sans-serif;
            font-size: 14px;
            color: #000;
        }

        /* A4 Page Layout Simulation for Preview */
        .page {
            width: 210mm; /* A4 width */
            height: 297mm; /* A4 height */
            margin: 0 auto;
            padding: 20mm; /* Add padding for page content */
            box-sizing: border-box;
            background-color: #fff;
            overflow: hidden;
            border: 1px solid #ddd; /* Optional border for A4 effect */
        }

        /* Container for content */
        .content-container {
            width: 100%;
            padding: 20px;
            box-sizing: border-box;
        }

        /* Header with Print Button */
        .header {
            position: fixed;
            top: 10px;
            right: 10px;
            z-index: 10;
        }

        .header button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 14px;
            border-radius: 5px;
        }

        .header button:hover {
            background-color: #0056b3;
        }

        /* Print styles */
        @media print {
            /* Simulate A4 paper size for print preview */
            .page {
                width: 210mm;
                height: 297mm;
                margin: 0 auto;
                padding: 10mm;
                background-color: #fff;
            }

            /* Remove the print button during printing */
            .header {
                display: none;
            }

            /* Remove padding for print */
            .content-container {
                padding: 0;
            }

            /* Ensure content layout is maintained */
            .content-container {
                width: 100%;
                margin: 0;
            }

            /* Optional: Print in landscape if needed */
            /* @page { size: A4 landscape; } */
        }
    </style>
</head>
<body>
    <?php $this->beginBody() ?>

    <!-- Print Button Section -->
    <div class="header">
        <button onclick="window.print()">Print</button>
    </div>

    <!-- A4 Layout Simulation for Preview -->
    <div class="page">
        <!-- Content Section -->
        <div class="content-container">
            <?= $content ?>
        </div>
    </div>

    <?php $this->endBody() ?>

</body>
</html>
