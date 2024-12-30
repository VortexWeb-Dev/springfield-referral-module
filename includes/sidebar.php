<div class="sidebar bg-white border-r min-h-screen flex flex-col justify-between w-[12%]">
    <div class="p-6">
        <h1 class="text-2xl font-bold mb-8 text-center hover:text-blue-300">
            <a href="">Springfield</a>
        </h1>
        <ul class="flex flex-col gap-2">
            <li class="text-sm">
                <a href="?status=all" class="block p-2 rounded transition duration-200 hover:bg-blue-400 <?php echo isset($_GET['status']) && $_GET['status'] === 'all' || !isset($_GET['status']) ? 'bg-blue-400' : ''; ?>">
                    <i class="fa-solid fa-handshake mr-2"></i> All Deals
                </a>
            </li>
            <li class="text-sm">
                <a href="?status=approved" class="block p-2 rounded transition duration-200 hover:bg-blue-400 <?php echo isset($_GET['status']) && $_GET['status'] === 'approved' ? 'bg-blue-400' : ''; ?>">
                    <i class="fa-solid fa-check-circle mr-2"></i> Approved Deals
                </a>
            </li>
            <li class="text-sm">
                <a href="?status=rejected" class="block p-2 rounded transition duration-200 hover:bg-blue-400 <?php echo isset($_GET['status']) && $_GET['status'] === 'rejected' ? 'bg-blue-400' : ''; ?>">
                    <i class="fa-solid fa-times-circle mr-2"></i> Rejected Deals
                </a>
            </li>
        </ul>
    </div>
    <div class="p-6 text-sm text-gray-400 text-center">
        © <?= date('Y') ?> Springfield.
    </div>
</div>