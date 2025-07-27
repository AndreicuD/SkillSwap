<?php
$imagePath = Yii::getAlias('@webroot/img/certificate.jpg');
$imageUrl = str_replace($_SERVER['DOCUMENT_ROOT'], '', $imagePath);
?>

<html>
    <head>
        <style>
            @page {
                margin: 0;
                background: url('<?= $imageUrl ?>') no-repeat center center;
                background-image-resize: 6; /* scale to full page */
            }

            html, body {
                margin: 0;
                padding: 0;
                height: 100%;
                background: transparent;
            }

            .certificate-content {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                text-align: center;
                padding-top: 8em;
                font-family: sans-serif;
            }
        </style>
    </head>
    
    <body>
        <div style="max-width: 800px; margin: 0 auto; padding: 9em 2em 0; text-align: center; background-color: transparent; ">

            <h1 style="font-size: 36px; margin-bottom: 0.5em;">Certificate of Completion</h1>

            <p style="font-size: 18px; margin: 2em 0 1em;">This certificate is proudly granted to</p>

            <h2 style="font-size: 28px; margin: 0.5em 0; font-weight: bold;"><?= $user ?></h2>

            <p style="font-size: 18px; margin: 2em 0 1em;">for the successful completion of the course</p>

            <h3 style="font-size: 24px; font-style: italic; margin-bottom: 2em;"><?= $title ?></h3>

            <p style="font-size: 16px; margin-bottom: 4em;">Awarded on <?= date('F j, Y', strtotime($date)) ?></p>

            <div style="text-align: center; margin-top: 8em;">
                <div style="border-top: 1px solid #000; width: 200px; margin: 0 auto;"></div>
                <p style="margin-top: 0.5em;">Skill Swap</p>
            </div>
        </div>
    </body>
</html>

