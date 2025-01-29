<?php $this->layout('layouts/main', ['title' => $pageTitle]) ?>

<section class="py-5">
    <h2>Hey, <?= @$user->first_name ?></h2>

    <a href="<?= url('/logout') ?>" class="btn btn-danger">Logout</a>


</section>