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
                <th scope="col">Adress</th>
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
                    <td><?= $user->address ?></td>
                    <td class="text-end">
                        <button class="btn btn-primary btn-sm btnEdit" data-id="<?= $user->id ?>">edit</button>
                    </td>
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

<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Modal</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h2>Edit your data</h2>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>

<?php $this->push('scripts'); ?>
<script src="<?= asset('/js/moni.js') ?>"></script>

<script>
    (function() {
        'use strict';

        moni('.btnEdit').on('click', function() {
            console.log(moni('.modal-title'));
            
            
            const id = moni(this).data('id');
            
            moni('.modal-title').html(`Editing data for id ${id}`);

            moni('#editModal').show();
        });
    })();
</script>

<?php $this->end(); ?>