<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>🪙Treasury Check Verification System</title>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="js/tcvs.js"></script>
<link rel="stylesheet" href="css/style.css" />
</head>
<body>
<?php
//Ideally you should store your API Key in the httpd conf file on your server (if using Apache) using SetEnv API_KEY "XXXXXXXXXXXXXXXXXXXXX"
$apiKey = getenv('API_KEY'); 

//If you do not want to store it there save it to the API/API_KEY.txt file as plain text
if($apiKey==NULL){$apiKey = file_get_contents("API/API_KEY.txt");}

?>
<div id="page">
    <div id="page-bgtop">
        <!-- end #sidebar -->

        <div id="content" class="treasury">

            <h2>Treasury Check Verification System (TCVS) Validation</h2>
            <p>Issue information for U.S. Treasury checks can be verified provided that the financial institution has a valid routing transit number, check number and check amount. Treasury checks that are older than 13-months old will not be available in this application. These checks should not be cashed by your institution since they are no longer valid after one year. Please inform the payee to contact the issuing agency for additional information. This website is available for use 7 days a week from 6:00am to 12:00am ET. <b><span style="color:red">*</span> Field Required</b></p><a href="#" id="toggleLink"><b>🪙 How to Read a Treasury Check</a> </b><br/><br/>
            <img class="tcvs-check" src="images/tcvs-check.png" alt="My Image" id="myImage">


            <?php if(!$_POST) { ?>
                <div class="tforms">
                    <form method="post" action="">

                        Check Symbol<span style="color:red">*</span><br/>
                        <input type="text" id="symbolNumber" name="symbolNumber" placeholder="1234" maxlength="4" minlength="4" required><br/><br/>

                        Check Serial Number<span style="color:red">*</span><br/>
                        <input type="text" id="serialNumber" name="serialNumber" placeholder="12345678" maxlength="8" minlength="8" required><br/><br/>

                        Issue Amount<span style="color:red">*</span><br/>
                        <input type="text" id="amount" name="amount" placeholder="100.01" required><br/><br/>

                        Routing Number<span style="color:red">*</span><br/>
                        <input type="text" id="bankRtn" name="bankRtn" placeholder="123456789" maxlength="9" minlength="9" required><br/><br/>

                        Check Tracking ID (optional)<br/>
                        <input type="text" id="checkTrackingId" maxlength="100" name="checkTrackingId"><br/><br/>

                        <input type="submit" class="t-button" value="Validate Check"><br/><br/>
                    </form>
                </div>

            <?php } ?>

            <?php

            if($_POST){

                $symbolNumber = $_POST['symbolNumber'];
                $serialNumber = $_POST['serialNumber'];
                $amount = $_POST['amount'];
                $bankRtn = $_POST['bankRtn'];
                $checkTrackingId = $_POST['checkTrackingId'];

                $url = 'https://tcvs.fiscal.treasury.gov/api/v2/validate?' . http_build_query([
                    'symbol_number' => $symbolNumber,
                    'serial_number' => $serialNumber,
                    'amount' => $amount,
                    'bank_rtn' => $bankRtn,
                    'check_tracking_id' => $checkTrackingId
                ]);

                $options = [
                    'http' => [
                        'header' => "Ocp-Apim-Subscription-Key: $apiKey\r\n",
                        'method' => 'GET'
                    ]
                ];

                $context = stream_context_create($options);
                $response = file_get_contents($url, false, $context);

                if ($response === FALSE) {
                    die('<hr/><br/><b>⚠️ COULD NOT VALIDATE THIS CHECK</b><br/><br/><center><a class="t-button" href="/tcvs">Validate Another Check</a></center>');
                }

                $data = json_decode($response, true);

                echo '<table>';
                echo '<tr><th>Check Tracking ID</th><th>Check Symbol</th><th>Check Serial Number</th><th>Issue Amount</th><th>Symbol Serial Matches</th><th>Amount Matches</th><th>Status</th><th>Payee</th></tr>';

                foreach ($data['checks'] as $check) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($check['check_tracking_id']) . '</td>';
                    echo '<td>' . htmlspecialchars($check['symbol_number']) . '</td>';
                    echo '<td>' . htmlspecialchars($check['serial_number']) . '</td>';
                    echo '<td>$' . htmlspecialchars($check['amount']) . '</td>';
                    echo '<td>' . htmlspecialchars($check['symbol_serial_matches'] ? 'Yes' : 'No') . '</td>';
                    echo '<td>' . htmlspecialchars($check['amount_matches'] ? 'Yes' : 'No') . '</td>';
                    echo '<td>';
                    if (htmlspecialchars($check['status'])!="I"){echo "<b>⛔</b>";}else{ echo "<b>🟢</b>";};
                    echo htmlspecialchars($check['status']) . '</td>';
                    echo '<td>' . htmlspecialchars($check['payee']) . '</td>';
                    echo '</tr>';
                }

                echo '</table>';
                echo '<br/><br/><center>
                <p style="text-align:center;"><b style="font-size:16px;">🛑Check Status Information</b></p>
                <p style="text-align:center;font-size:13px;"><b>Only checks with a status of Issue Outstanding (I) with a verified payee name should be accepted and the payee must be on the account the funds are being cashed or deposited to.</b> Status is only returned if the symbol/serial number and amount checks pass. Valid values are Issue Outstanding (I), Paid/Reconciled (R), Available Check Cancellation (A), Unavailable Check Cancellation (U), Limited Payability Cancellation (L). Payee will only appear for Checks with a status of I. Checks with a status of U, R, A, or L will not return Payee.</p><br/><br/>
                <a class="t-button" href="/tcvs">Validate Another Check</a></center>';

            }

            ?>

        </div>

    </div>
    <!-- end #content -->
<div class="footer"><a href="https://github.com/athreadgill/tcvs">Treasury Check Verification API System</a> by <a href="https://www.linkedin.com/in/andrew-threadgill-15a179340/">Andrew Threadgill</a> is marked <a href="https://creativecommons.org/publicdomain/zero/1.0/">CC0 1.0</a><img src="https://mirrors.creativecommons.org/presskit/icons/cc.svg" style="max-width: 1em;max-height:1em;margin-left: .2em;"><img src="https://mirrors.creativecommons.org/presskit/icons/zero.svg" style="max-width: 1em;max-height:1em;margin-left: .2em;">
</div>

    <div style="clear: both;">&nbsp;</div>
</div>

</body>
</html>
