<?= $header ?>

<div class="container">
    <h1>
        <?= $title ?>
    </h1>

    <ul class="list-group mt-md-3">
        <?php foreach($todoList as $todo): ?>
        <li class="list-group-item<?php if($todo['finished']): ?> list-group-item-dark<?php endif;?>">
            <div class="d-flex w-100 justify-content-between">
                <h5 class="mb-1">
                    <?= $todo['title'] ?>
                </h5>
            </div>
        </li>
        <?php endforeach; ?>
    </ul>

    <form action="index.php" method="post">
        <input type="hidden" name="mode" value="create">
        <div class="form-group  mt-md-3">
            <label for="todo">
                TODO
            </label>
            <input type="text" class="form-control" id="todo" name="todo" placeholder="Input Todo text" max="140" required>
        </div>
        <button type="submit" class="btn btn-primary">
            Submit
        </button>
    </form>

</div>

<?= $script ?>

<?= $footer ?>
