<?php
session_start();

include_once 'crest/crest.php';

// Define the pipeline ID
define("SUPRAJA_PIPELINE_ID", 23); //  

function fetchDeals() {
    $deals = CRest::call('crm.deal.list', [
        'filter' => ['CATEGORY_ID' => SUPRAJA_PIPELINE_ID,23], // Use the defined pipeline ID
        'select' => [
             '*', 'UF_*'
        ]
    ]);

    // Extract only the deals' results, if available
    $dealResults = $deals['result'] ?? [];

    // echo '<pre>';
    // print_r($dealResults); // Display only the deals data
    // echo '</pre>';
    
    return $dealResults;
}


function calculateTotals($deals) {
    $dealsTotal = count($deals);
    $totalAmount = 0;
    $referralAmountTotal = 0;

    foreach ($deals as $deal) {
        // Ensure the property price is present and numeric
        $propertyPrice = isset($deal['UF_CRM_1729499970282']) && is_numeric($deal['UF_CRM_1729499970282']) ? floatval($deal['UF_CRM_1729499970282']) : 0;
        
        // Debugging output for property price
        error_log("Deal ID: {$deal['ID']}, Property Price: $propertyPrice");
        
        $totalAmount += $propertyPrice;

        // Ensure the referral fee status is present
        if (isset($deal['UF_CRM_1729500039460'])) {
            $referralFeeStatus = $deal['UF_CRM_1729500039460'];
            // Debugging output for referral fee status
            error_log("Deal ID: {$deal['ID']}, Referral Fee Status: $referralFeeStatus");

            if ($referralFeeStatus == 717) { // Approved
                $referralAmountTotal += $propertyPrice * 0.1; // 10%
            } elseif ($referralFeeStatus == 719) { // Rejected
                $referralAmountTotal += $propertyPrice * 0.05; // 5%
            }
        }
    }

    // Log the final totals
    error_log("Total Deals: $dealsTotal, Total Amount: $totalAmount, Referral Amount Total: $referralAmountTotal");

    return [
        'dealsTotal' => $dealsTotal,
        'totalAmount' => $totalAmount,
        'referralAmountTotal' => $referralAmountTotal
    ];
}



   



     


 
$deals = fetchDeals();
$totals = calculateTotals($deals);


$statusFilter = $_GET['status'] ?? 'all';
if ($statusFilter !== 'all') {
    $deals = array_filter($deals, function($deal) use ($statusFilter) {
        return ($statusFilter === 'approved' && $deal['UF_CRM_1729500039460'] == 717) || 
               ($statusFilter === 'rejected' && $deal['UF_CRM_1729500039460'] == 719) || 
               ($statusFilter === 'not_selected' && $deal['UF_CRM_1729500039460'] == null);
    });
}

// After checking if the POST request is valid
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Fetch deal ID and referral fee ID from POST data
    $dealId = $_POST['dealId'] ?? null;
    $referralFeeId = $_POST['referralFeeId'] ?? null;

    // Check if deal ID and referral fee ID are set and valid
    if ($dealId && $referralFeeId && in_array($referralFeeId, [717, 719])) {
        // Call the Bitrix24 API to update the deal
        $result = CRest::call('crm.deal.update', [
            'id' => $dealId,  // Ensure only this deal is updated
            'fields' => ['UF_CRM_1729500039460' => $referralFeeId]  // Update the referral fee
        ]);

        // Check if the update was successful
        if (isset($result['result']) && $result['result']) {
            $_SESSION['message'] = "Referral fee updated successfully.";
        } else {
            // Log the error message
            $errorMessage = $result['error'] ?? 'Unknown error';
            error_log("Failed to update referral fee for Deal ID $dealId. Error: " . htmlspecialchars($errorMessage));
        }

        // Redirect to avoid resubmission on refresh and preserve the current status filter
        header("Location: " . $_SERVER['PHP_SELF'] . "?status=" . urlencode($statusFilter));
        exit;
    } else {
        // Log a warning if the IDs are not valid
        error_log("Invalid deal ID or referral fee ID. Deal ID: " . htmlspecialchars($dealId) . ", Referral Fee ID: " . htmlspecialchars($referralFeeId));
    }
}

// Set the status filter to keep filtering the deals
$statusFilter = $_GET['status'] ?? 'all';





// Retrieve the message and clear it from the session
$message = $_SESSION['message'] ?? '';
unset($_SESSION['message']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRM Application - Home</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #f8f9fa;
            color: #333;
            display: flex;
        }

        .container {
            flex: 1;
            max-width: 1200px;
            margin: 20px auto;
            padding-left: 220px; /* Leave space for the sidebar */
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100px;
            height: 100%;
            background-color: #20c997;
            color: #fff;
            padding: 20px;
        }

        .sidebar h3 {
            color: #fff;
        }

        .sidebar a {
            display: block;
            color: #fff;
            padding: 10px;
            text-decoration: none;
            margin-bottom: 10px;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        .sidebar a:hover {
            background-color: #495057;
        }

        h2 {
            color: #343a40;
            text-align: center;
            margin-bottom: 20px;
        }

        .totals {
            display: flex;
            justify-content: space-between;
            padding: 20px;
            background-color: #e9ecef;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .totals div {
            font-size: 16px; /* Adjusted font size for better visibility */
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        th,
        td {
            border: 1px solid #dee2e6;
            padding: 12px 15px;
            text-align: left;
        }

        th {
            background-color: #20c997;
            color: white;
            text-transform: uppercase;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        input[type="text"],
        input[type="number"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            background-color: #f8f9fa;
        }

        button {
            padding: 10px 15px;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        .btn-blue {
            background-color: #007bff;
        }

        .btn-blue:hover {
            background-color: #0056b3;
        }

        .btn-red {
            background-color: #dc3545;
        }

        .btn-red:hover {
            background-color: #b02a37;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
        }

        @media screen and (max-width: 768px) {
            .totals {
                flex-direction: column;
                text-align: center;
            }
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <h3></h3>
        <a href="?status=not_selected">All Deals</a>
        <a href="?status=approved">Approved Deals</a>
        <a href="?status=rejected">Rejected Deals</a>
        
    </div>

    <div class="container">
        <!-- Display totals -->
        <div class="totals">
        <div>Total Deals: <span><?php echo $totals['dealsTotal']; ?></span></div>
    <div>Total Amount: <span><?php echo number_format($totals['totalAmount'], 2); ?></span></div>
    <div>Total Referral Amount: <span><?php echo number_format($totals['referralAmountTotal'], 2); ?></span></div>
</div>

        <h2>Current Deals</h2>
        <table>
            <thead>
                <tr>
                    <th>Deal Name</th>
                    <th>Responsible Person</th>
                    <th>Project Name</th>
                    <th>Unit Number</th>
                    <th>Property Price</th>
                    <th>Gross Commission</th>
                    <th>Referral Fee</th>
                    <th>Referral</th>
                    <th>Action</th>
                    <th>Referral Comments</th>
                </tr>
            </thead>
            <tbody>
            <?php
    foreach ($deals as $deal):
        // Initialize referral fee status
        $referralFeeStatus = 'Not Selected';

        // Check if referral fee exists and set the status
        if (isset($deal['UF_CRM_1729500039460'])) {
            if ($deal['UF_CRM_1729500039460'] == 717) {
                $referralFeeStatus = 'Approved';
            } elseif ($deal['UF_CRM_1729500039460'] == 719) {
                $referralFeeStatus = 'Rejected';
            }
        }
    ?>
                <tr>
                    <td><input type="text" name="DealName" value="<?php echo htmlspecialchars($deal['UF_CRM_1729499879083'] ?? ''); ?>" readonly></td>
                    <td><input type="text" name="Responsibleperson" value="<?php echo htmlspecialchars($deal['UF_CRM_1729499903050'] ?? ''); ?>" readonly></td>
                    <td><input type="text" name="ProjectName" value="<?php echo htmlspecialchars($deal['UF_CRM_1729499931812'] ?? ''); ?>" readonly></td>
                    <td><input type="text" name="UnitNumber" value="<?php echo htmlspecialchars($deal['UF_CRM_1729499952106'] ?? ''); ?>" readonly></td>
                    <td><input type="number" name="PropertyPrice" value="<?php echo htmlspecialchars($deal['UF_CRM_1729499970282'] ?? ''); ?>" readonly></td>
                    <td><input type="number" name="GrossCommission" value="<?php echo htmlspecialchars($deal['UF_CRM_1729499988571'] ?? ''); ?>" readonly></td>
                    <td><input type="text" name="ReferralFee" value="<?php echo htmlspecialchars($deal['UF_CRM_1729500007347'] ?? ''); ?>" readonly></td>
                    
                    <td>
                        <input type="text" name="Referral" 
                               value="<?php 
                                   echo htmlspecialchars($referralFeeStatus);
                               ?>" 
                               readonly>
                    </td>

                    <td>
                    <div class="action-buttons">
                    <form method="POST" action="">
                                <input type="hidden" name="dealId" value="<?php echo $deal['ID']; ?>">
                                <input type="hidden" name="referralFeeId" value="717">
                                <button type="submit" class="btn-blue">Approve</button>
                            </form>
                            <form method="POST" action="">
                                <input type="hidden" name="dealId" value="<?php echo $deal['ID']; ?>">
                                <input type="hidden" name="referralFeeId" value="719">
                                <button type="submit" class="btn-red">Reject</button>
                            </form>
                        </div>
                        </div>
                    </td>
                    <td><input type="text" name="Referral Comments" value="<?php echo htmlspecialchars($deal['UF_CRM_1729500122690'] ?? ''); ?>" readonly></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <?php if ($message): ?>
            <div class="alert"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
    </div>
</body>
</html>