<?php include('includes/header.php'); ?>

<?php require('data/fetch_users.php'); ?>

<?php

$currentUser = getCurrentUser();

if (!isAdmin($currentUser['ID'])) {
    echo '
    <div class="absolute inset-0 flex justify-center items-center text-center">
        <p class="text-3xl font-bold text-gray-800 dark:text-white">
            You are not authorized to view this page.
        </p>
    </div>';
    exit;
}


?>

<?php include('includes/sidebar.php'); ?>

<div class="flex-1 bg-gray-100 dark:bg-gray-900 relative">
    <?php include('includes/navbar.php'); ?>

    <!-- Loading Spinner -->
    <div id="loadingSpinner" class="absolute inset-0 flex items-center justify-center">
        <div class="animate-spin rounded-full h-16 w-16 border-t-4 border-blue-500"></div>
    </div>

    <div class="max-w-[95%] mx-auto px-8 py-8 pt-0" id="formContainer" style="display: none;">
        <h1 class="text-3xl font-bold text-center text-gray-800 dark:text-white">
            <span class="month"></span> <span class="year"></span> Referral Module
        </h1>

        <div class="flex justify-between items-center my-4 shadow bg-white dark:bg-gray-800 dark:text-white rounded p-4">
            <div>Total Deals: <span id="totalDeals"></span></div>
            <div>Total Amount: <span id="totalAmount"></span></div>
            <div>Total Referral Amount: <span id="referralAmountTotal"></span></div>
        </div>

        <div class="flex flex-col">
            <div class="-m-1.5 overflow-x-auto">
                <div class="p-1.5 min-w-full inline-block align-middle">
                    <div class="overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                            <thead>
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-blue-800 uppercase dark:text-neutral-500">Deal Name</th>
                                    <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-blue-800 uppercase dark:text-neutral-500">Responsible Person</th>
                                    <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-blue-800 uppercase dark:text-neutral-500">Project Name</th>
                                    <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-blue-800 uppercase dark:text-neutral-500">Unit Number</th>
                                    <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-blue-800 uppercase dark:text-neutral-500">Property Price</th>
                                    <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-blue-800 uppercase dark:text-neutral-500">Gross Commission</th>
                                    <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-blue-800 uppercase dark:text-neutral-500">Referral Fee</th>
                                    <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-blue-800 uppercase dark:text-neutral-500">Referral Comments</th>
                                    <th scope="col" class="px-6 py-3 text-end text-xs font-medium text-blue-800 uppercase dark:text-neutral-500">Referral Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                                <tr>
                                    <td colspan="9">Fetching data...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const loadingSpinner = document.getElementById("loadingSpinner");
        const formContainer = document.getElementById("formContainer");

        // Show spinner initially
        loadingSpinner.style.display = 'flex';
        formContainer.style.display = 'none';

        // Get status from URL parameter
        const urlParams = new URLSearchParams(window.location.search);
        const status = urlParams.get('status');

        fetch('./data/fetch_data.php' + (status ? '?status=' + status : ''))
            .then(response => response.json())
            .then(data => {
                console.log(data);

                const deals = data.deals;
                const totalDeals = data.totalDeals;
                const totalAmount = data.totalAmount;
                const totalReferralAmount = data.totalReferralAmount;

                document.getElementById('totalDeals').textContent = totalDeals;
                document.getElementById('totalAmount').textContent = totalAmount;
                document.getElementById('referralAmountTotal').textContent = totalReferralAmount;

                const tableBody = document.querySelector('tbody');
                tableBody.innerHTML = '';

                if (deals && deals.length === 0) {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td colspan="9" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200 text-center">No deals found</td>
                    `;
                    tableBody.appendChild(row);
                }

                deals.forEach(deal => {
                    const row = document.createElement('tr');
                    const updateUrl = `./data/update_deal.php?dealId=${deal.ID}&newStatus=${deal.UF_CRM_1728042953037 == 1297 ? 1295 : 1297}` + (status ? '&status=' + status : '');
                    row.innerHTML = `
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">${deal.TITLE}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-500 dark:text-blue-200 underline"><a target="_blank" href="https://crm.springfieldproperties.ae/company/personal/user/${deal.ASSIGNED_BY_ID['ID']}/">${deal.ASSIGNED_BY_ID['FULL_NAME']}</a></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">${deal.UF_CRM_1727625779110 || 'N/A'}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">${deal.UF_CRM_1727625804043 || 'N/A'}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">${deal.OPPORTUNITY}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">${deal.UF_CRM_1727871887978}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">${deal.UF_CRM_1727626055823 || 'N/A'}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">${deal.UF_CRM_1729349757819 || 'N/A'}</td>
                       <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                            ${deal.UF_CRM_1728042953037 == 1297 ? `
                                <!-- Approve button for rejected deals -->
                                <a href="${updateUrl}" class="p-2 inline-flex text-white items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent focus:outline-none disabled:opacity-50 disabled:pointer-events-none dark:bg-blue-500 dark:hover:bg-blue-400 dark:focus:bg-blue-400 bg-blue-600 hover:bg-blue-800 focus:bg-blue-800">Approve</a>
                            ` : deal.UF_CRM_1728042953037 == 1295 ? `
                                <!-- Reject button for approved deals -->
                                <a href="${updateUrl}" class="p-2 inline-flex text-white items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent focus:outline-none disabled:opacity-50 disabled:pointer-events-none dark:bg-red-500 dark:hover:bg-red-400 dark:focus:bg-red-400 bg-red-600 hover:bg-red-800 focus:bg-red-800">Reject</a>
                            ` : `
                                <!-- Both Approve and Reject buttons for undefined status -->
                                <a href="${updateUrl}" class="p-2 inline-flex text-white items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent focus:outline-none disabled:opacity-50 disabled:pointer-events-none dark:bg-blue-500 dark:hover:bg-blue-400 dark:focus:bg-blue-400 bg-blue-600 hover:bg-blue-800 focus:bg-blue-800">Approve</a>
                                <a href="${updateUrl}" class="p-2 inline-flex text-white items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent focus:outline-none disabled:opacity-50 disabled:pointer-events-none dark:bg-red-500 dark:hover:bg-red-400 dark:focus:bg-red-400 bg-red-600 hover:bg-red-800 focus:bg-red-800">Reject</a>
                            `}
                        </td>


                    `;
                    tableBody.appendChild(row);
                });




                // Hide the spinner and show the form once data is loaded
                loadingSpinner.style.display = 'none';
                formContainer.style.display = 'block';
            })
            .catch(error => {
                console.error("Error fetching data:", error);

                // Hide spinner if there's an error
                loadingSpinner.style.display = 'none';
                formContainer.style.display = 'block';
            });
    });
</script>


<?php include('includes/footer.php'); ?>