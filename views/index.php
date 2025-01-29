<?php $this->layout('layouts/main', ['title' => $pageTitle]) ?>

<section class="py-5">
    <div class="mb-3 d-flex align-items-center justify-content-between">
        <h3>Users/Staff List</h3>
        <form>
            <input type="text" placeholder="Search.." class="form-control form-control-sm">
        </form>
    </div>

    <table class="table table-sm">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">First Name</th>
                <th scope="col">Last Name</th>
                <th scope="col">Email</th>
                <th scope="col">Phone</th>
                <th scope="col" class="text-end">Address</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $index => $user): ?>
                <tr>
                    <th scope="row"><?= $index + 1 ?></th>
                    <td><?= $user->first_name; ?></td>
                    <td><?= $user->last_name ?></td>
                    <td><?= $user->email ?></td>
                    <td><?= $user->phone ?></td>
                    <td class="text-end"><?= $user->address ?></td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>

    <!-- Pagination -->
    <!--
    <nav aria-label="Page navigation example" class="d-flex justify-content-end">
        <ul class="pagination pagination-sm">
            <li class="page-item <?= $currentPage == 1 ? 'disabled' : '' ?>">
                <a class="page-link" href="?page=<?= $currentPage - 1 ?>" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>

            <?php
            //     $range = 2; // Number of pages to show before and after the current page
            // $start = max(1, $currentPage - $range);
            // $end = min($totalPages, $currentPage + $range);

            // // Show the first page link if not already in the range
            // if ($start > 1) {
            //     echo '<li class="page-item"><a class="page-link" href="?page=1">1</a></li>';
            //     if ($start > 2) {
            //         echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
            //     }
            // }

            // // Generate page links within the range
            // for ($page = $start; $page <= $end; $page++) {
            //     echo '<li class="page-item ' . ($page == $currentPage ? 'active' : '') . '">
            //         <a class="page-link" href="?page=' . $page . '">' . $page . '</a>
            //       </li>';
            // }

            // // Show the last page link if not already in the range
            // if ($end < $totalPages) {
            //     if ($end < $totalPages - 1) {
            //         echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
            //     }
            //     echo '<li class="page-item"><a class="page-link" href="?page=' . $totalPages . '">' . $totalPages . '</a></li>';
            // }
            ?>

            <li class="page-item <?= $currentPage == $totalPages ? 'disabled' : '' ?>">
                <a class="page-link" href="?page=<?= $currentPage + 1 ?>" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        </ul>
    </nav>
        -->
</section>