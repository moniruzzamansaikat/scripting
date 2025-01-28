<?php $this->layout('layouts/main', ['title' => $pageTitle]) ?>


<h1>Total Users: <?= $totalUsers ?></h1>

<form method="POST" action="<?= url('/generate') ?>">
    <input type="text" name="prompt" class="form-control" />
    <button class="btn btn-primary">Submit</button>
</form>
