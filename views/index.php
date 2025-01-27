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

    <nav aria-label="Page navigation example" class="d-flex justify-content-end">
        <ul class="pagination pagination-sm">
            <li class="page-item">
                <a class="page-link" href="#" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
            <li class="page-item"><a class="page-link" href="#">1</a></li>
            <li class="page-item"><a class="page-link" href="#">2</a></li>
            <li class="page-item"><a class="page-link" href="#">3</a></li>
            <li class="page-item">
                <a class="page-link" href="#" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        </ul>
    </nav>
</section>